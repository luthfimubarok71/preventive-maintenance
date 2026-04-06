<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --blue: #2563eb;
            --blue-light: #60a5fa;
            --bg: #eff6ff;
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --gradient: linear-gradient(135deg, #60a5fa, #2563eb);
        }

        body.dark {
            --bg: #0f172a;
            --card: #020617;
            --text: #e5e7eb;
            --muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, Poppins, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: 0.4s ease;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 56px 64px;
        }

        /* ===== MAIN ===== */
        main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            padding: 32px 0;
        }

        .hero {
            margin-top: 0;
            padding-top: 0;
        }

        .hero h1 {
            margin-top: 0;
            margin-bottom: 20px;
            max-width: 560px;
        }

        .preview {
            animation: slideRight 0.9s ease forwards;
        }

        h1 {
            font-size: 42px;
            line-height: 1.25;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .subtitle {
            font-size: 16px;
            line-height: 1.7;
            color: var(--muted);
            max-width: 520px;
            margin-bottom: 32px;
        }

        /* ===== BUTTON ===== */
        .start-btn {
            margin-top: 4px;
            background: var(--gradient);
            color: #ffffff;
            border: none;
            padding: 14px 38px;
            border-radius: 999px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 14px 36px rgba(37, 99, 235, 0.45);
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .start-btn:hover {
            transform: translateY(-4px) scale(1.04);
            box-shadow: 0 22px 48px rgba(37, 99, 235, 0.6);
        }

        .start-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        .preview {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .start-wrapper {
            margin-top: 20px;
        }

        /* ===== IMAGE BOX ===== */
        .image-box {
            height: 380px;
            border-radius: 18px;
            overflow: hidden;
            background: linear-gradient(135deg,
                    rgba(96, 165, 250, 0.25),
                    rgba(37, 99, 235, 0.85));
            box-shadow: 0 22px 54px rgba(37, 99, 235, 0.35);
            transition: 0.4s;
        }

        .image-box:hover {
            transform: scale(1.03);
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== TOGGLE ===== */
        .toggle {
            width: 44px;
            height: 24px;
            background: #cbd5f5;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            margin: 20px auto 0;
        }

        .toggle::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background: #ffffff;
            border-radius: 50%;
            transition: 0.3s;
        }

        body.dark .toggle {
            background: #334155;
        }

        body.dark .toggle::before {
            left: 23px;
        }

        /* ===== FOOTER ===== */
        footer {
            text-align: center;
            padding: 48px 0 16px;
            color: var(--muted);
            max-width: 820px;
            margin: auto;
            animation: fadeUp 1.1s ease forwards;
        }

        /* ===== HERO LOGO ===== */
        .hero-logo {
            width: 72px;
            height: auto;
            margin-bottom: 12px;
            display: block;
        }

        /* ===== ANIMATION ===== */
        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            header {
                padding: 16px 24px;
            }

            .menu {
                position: static;
                transform: none;
                margin: 16px 0;
                justify-content: center;
            }

            main {
                grid-template-columns: 1fr;
                gap: 48px;
                text-align: left;
            }

            .hero {
                margin-top: 0;
                text-align: left;
            }

            .right-area {
                margin-top: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- MAIN -->
        <main>

            <!-- HERO / LEFT -->
            <div class="hero">
                <img src="{{ asset('assets/image/pgncomlogo.png') }}" alt="Logo" class="hero-logo">

                <h1>
                    Kelola Aktivitas Operasional<br>
                    Lebih Mudah, Cepat, dan<br>
                    Terstruktur
                </h1>

                <p class="current-date">
                    Tanggal hari ini: {{ date('l, d F Y') }}
                </p>
            </div>

            <!-- PREVIEW / RIGHT -->
            <div class="preview">

                <div class="image-box">
                    <img src="{{ asset('assets/image/dashboard.png') }}" alt="Preview Dashboard">
                </div>

                <div class="start-wrapper">
                    <a href="/login" class="start-btn">
                        Start
                    </a>
                </div>

                <div class="toggle" onclick="toggleDark()"></div>

            </div>

        </main>

        <!-- FOOTER -->
        <footer>
            Sistem ini dirancang untuk meningkatkan efektivitas kerja tim dengan pencatatan rapi,
            proses terkontrol, dan monitoring transparan.
        </footer>

    </div>

    <script>
        function toggleDark() {
            document.body.classList.toggle('dark');
        }
    </script>

</body>

</html>
