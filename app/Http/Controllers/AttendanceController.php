<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $students = Student::whereNotNull('face_descriptor')->get();
        return view('attendance.scan', compact('students'));
    }

    public function store(Request $request)
    {
        $student = Student::where('student_id', $request->student_id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.'], 404);
        }

        // Cek apakah hari ini siswa tersebut sudah absen
        $todayRecord = Attendance::where('student_id', $student->id)
            ->where('type', $request->type ?? 'in')
            ->whereDate('logged_at', Carbon::today())
            ->exists();

        if ($todayRecord) {
            return response()->json(['success' => false, 'message' => "{$student->name} sudah absen hari ini."], 422);
        }

        $photoPath = null;
        if ($request->photo) {
            $imageData = $request->photo;
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $photoName = 'attendance_' . time() . '_' . $student->student_id . '.jpg';
            \Storage::disk('public')->put('attendance_photos/' . $photoName, base64_decode($image));
            $photoPath = 'attendance_photos/' . $photoName;
        }

        $type = $request->type ?? 'in';
        $statusStr = "";
        $isLate = false;
        
        if ($type === 'in') {
            $batasMasuk = \App\Models\Setting::get('check_in_time', '07:00');
            $isLate = Carbon::now()->format('H:i') > $batasMasuk;
            $statusStr = $isLate ? "🔴 TERLAMBAT" : "🟢 TEPAT WAKTU";
        } else {
            $statusStr = "🔵 PULANG";
        }

        // Create log
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'type' => $type,
            'photo' => $photoPath,
            'logged_at' => Carbon::now()
        ]);

        // Kirim Notif WA ke Orang Tua secara otomatis (Pesan lebih detil)
        $wa = new WhatsAppService();
        $msgTitle = $type === 'in' ? "🔔 *PRESENSI MASUK*" : "🏠 *PRESENSI PULANG*";
        $extraInfo = $type === 'in' ? "Batas Masuk: " . $batasMasuk . "\n" : "Selamat istirahat di rumah.";
        
        $message = "{$msgTitle}\n\n"
                 . "Ananda: *{$student->name}*\n"
                 . "Status: *{$statusStr}*\n"
                 . "Waktu: " . $attendance->logged_at->format('H:i:s') . "\n"
                 . "{$extraInfo}\n"
                 . "Terima kasih.";
        
        $photoUrl = $attendance->photo ? asset('storage/' . $attendance->photo) : null;
        $wa->sendMessage($student->phone_parent, $message, $photoUrl);

        $successMsg = $type === 'in' 
            ? "Berhasil absen " . ($isLate ? 'Terlambat' : 'Tepat Waktu')
            : "Berhasil absen Pulang";

        return response()->json([
            'success' => true, 
            'message' => $successMsg,
            'student' => $student->name,
            'status' => $statusStr
        ]);
    }

    public function logs()
    {
        $logs = Attendance::with('student')->latest()->paginate(20);
        return view('attendance.logs', compact('logs'));
    }

    public function destroy(Attendance $attendance)
    {
        // Hapus file foto jika ada
        if ($attendance->photo) {
            \Storage::disk('public')->delete($attendance->photo);
        }
        $attendance->delete();
        return back()->with('success', 'Log berhasil dihapus.');
    }

    public function clearAll()
    {
        // Hapus semua foto dari storage
        $logs = Attendance::whereNotNull('photo')->get();
        foreach($logs as $log) {
            \Storage::disk('public')->delete($log->photo);
        }
        
        Attendance::truncate();
        return back()->with('success', 'Seluruh riwayat presensi telah dibersihkan.');
    }
}
