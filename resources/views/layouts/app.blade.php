<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FaceAttend AI - Premium Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg: #050a14;
            --card-bg: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --glass-blur: blur(20px);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.1) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem 2rem;
            background: var(--card-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border);
            border-radius: 1rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            filter: brightness(1.1);
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-logout:hover {
            background: #ef4444;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        th {
            text-align: left;
            padding: 1rem;
            color: var(--text-dim);
            font-size: 0.85rem;
            text-transform: uppercase;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.01);
        }

        input, textarea, select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.8rem 1rem;
            color: white;
            width: 100%;
            box-sizing: border-box;
        }

        h2 { font-size: 1.8rem; margin: 0; }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
            <div id="real-time-clock" style="background: var(--card-bg); backdrop-filter: var(--glass-blur); border: 1px solid var(--border); padding: 0.5rem 1.2rem; border-radius: 2rem; font-family: monospace; font-size: 1rem; color: var(--primary); font-weight: 700; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                00:00:00
            </div>
        </div>
        @auth
        @endauth

        @yield('content')
    </div>
    @yield('scripts')
    <script>
        function updateClock() {
            const now = new Date();
            
            // Time
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            
            // Date
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('id-ID', options);
            
            const clockElement = document.getElementById('real-time-clock');
            if (clockElement) {
                clockElement.innerHTML = `<span style="color: var(--text-dim); font-size: 0.8rem; font-weight: 400; margin-right: 1rem;">${dateString}</span> ${timeString}`;
            }

            const welcomeClock = document.getElementById('welcome-clock');
            if (welcomeClock) {
                welcomeClock.textContent = timeString;
            }

            const welcomeDate = document.getElementById('welcome-date');
            if (welcomeDate) {
                welcomeDate.textContent = dateString;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
