<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background: radial-gradient(circle at 50% 50%, #0f172a, #020617);
            color: white;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
            transition: opacity 1s ease, transform 1s ease;
        }

        .loader {
            width: 80px;
            aspect-ratio: 1;
            border-radius: 50%;
            border: 8px solid #ffffff20;
            border-top-color: #38bdf8;
            animation: spin 1s linear infinite;
            position: relative;
        }

        .glow {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 0 25px 5px #38bdf8;
            opacity: 0.6;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.2); }
        }

        .text {
            margin-top: 30px;
            font-size: 1.25rem;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .dots::after {
            content: "";
            display: inline-block;
            width: 1ch;
            text-align: left;
            animation: dots 1.2s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ""; }
            40% { content: "."; }
            60% { content: ".."; }
            80%, 100% { content: "..."; }
        }

        /* Efek fade-out */
        .fade-out {
            opacity: 0;
            transform: scale(1.05);
        }
    </style>
</head>
<body id="page">
    <div class="relative flex flex-col items-center">
        <div class="loader">
            <div class="glow"></div>
        </div>
        <div class="text mt-6 text-sky-300 font-medium">
            Memuat halaman<span class="dots"></span>
        </div>
    </div>

    <script>
        // Tunggu 4 detik, lalu mulai animasi fade-out
        setTimeout(() => {
            const page = document.getElementById('page');
            page.classList.add('fade-out');
        }, 4000);

        // Setelah fade-out selesai (1 detik), redirect ke halaman berikut
        setTimeout(() => {
            window.location.href = "prm/login"; // ganti sesuai kebutuhan
        }, 5000);
    </script>
</body>
</html>
