<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FaceAttend AI') }} - Authentication</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo a {
            text-decoration: none;
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .logo span {
            color: var(--primary);
        }

        /* Tailwind Compatibility Overrides */
        .min-h-screen { min-height: auto; }
        .bg-gray-100 { background: transparent; }
        .shadow-md { box-shadow: none; }
        .bg-white { background: transparent; }
        
        input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid var(--border) !important;
            border-radius: 0.75rem !important;
            padding: 0.8rem 1rem !important;
            color: white !important;
            width: 100% !important;
            margin-top: 0.5rem;
        }

        label {
            color: var(--text-dim) !important;
            font-size: 0.9rem !important;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            width: 100%;
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
            filter: brightness(1.1);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="glass-card">
        <div class="logo">
            <a href="/">FaceAttend <span>AI</span></a>
        </div>
        {{ $slot }}
    </div>
</body>
</html>
