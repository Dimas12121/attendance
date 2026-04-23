<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $students = Student::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('student_id', 'like', "%{$search}%");
        })
        ->when($status, function($query, $status) {
            if ($status == 'registered') return $query->whereNotNull('face_descriptor');
            if ($status == 'not_registered') return $query->whereNull('face_descriptor');
        })
        ->latest()->get();

        return view('students.index', compact('students', 'search', 'status'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'student_id' => 'required|unique:students',
            'phone_parent' => 'required',
            'birth_date' => 'required|date',
            'face_descriptor' => 'required'
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Siswa berhasil didaftarkan!');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'phone_parent' => 'required',
            'birth_date' => 'required|date',
        ]);

        $data = $request->all();
        if (!$request->face_descriptor) {
            unset($data['face_descriptor']);
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus dari sistem.');
    }
}
