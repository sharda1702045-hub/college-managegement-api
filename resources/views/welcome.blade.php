<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>College Management System Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0b0f19 0%, #111827 50%, #070a13 100%);
            --accent-primary: #3b82f6;
            --accent-primary-rgb: 59, 130, 246;
            --accent-secondary: #8b5cf6;
            --accent-secondary-rgb: 139, 92, 246;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --card-bg: rgba(30, 41, 59, 0.45);
            --card-border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Ambient Glowing Orbs */
        .glowing-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(140px);
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
        }

        .orb-left {
            background: rgba(var(--accent-primary-rgb), 0.8);
            top: -100px;
            left: -100px;
            animation: float-orb 15s infinite alternate ease-in-out;
        }

        .orb-right {
            background: rgba(var(--accent-secondary-rgb), 0.8);
            bottom: -100px;
            right: -100px;
            animation: float-orb 18s infinite alternate-reverse ease-in-out;
        }

        @keyframes float-orb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 40px) scale(1.15); }
        }

        /* Container & Glassmorphism Header */
        .page-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
            padding: 3rem 1.5rem;
        }

        .hero-section {
            text-align: center;
            margin-bottom: 4rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .system-badge {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);
        }

        .system-badge .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #3b82f6;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px #3b82f6;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 30%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            color: var(--text-muted);
            font-size: 1.2rem;
            max-w-2xl: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Interactive Grid Cards */
        .gateway-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .gateway-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(800px circle at var(--mouse-x, 0) var(--mouse-y, 0), rgba(255, 255, 255, 0.06), transparent 40%);
            z-index: 1;
            pointer-events: none;
        }

        .gateway-card:hover {
            transform: translateY(-8px);
            border-color: rgba(var(--card-hover-glow-rgb, 59, 130, 246), 0.4);
            box-shadow: 0 12px 40px rgba(var(--card-hover-glow-rgb, 59, 130, 246), 0.15);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            background: rgba(var(--card-hover-glow-rgb, 59, 130, 246), 0.1);
            color: rgb(var(--card-hover-glow-rgb, 59, 130, 246));
            border: 1px solid rgba(var(--card-hover-glow-rgb, 59, 130, 246), 0.2);
            transition: all 0.3s ease;
        }

        .gateway-card:hover .card-icon {
            background: rgb(var(--card-hover-glow-rgb, 59, 130, 246));
            color: #ffffff;
            box-shadow: 0 0 15px rgba(var(--card-hover-glow-rgb, 59, 130, 246), 0.4);
        }

        .card-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: #ffffff;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            flex-grow: 1;
        }

        .btn-action {
            width: 100%;
            padding: 0.85rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-decoration: none;
        }

        .btn-action-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .btn-action-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }

        .btn-action-secondary {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
        }

        .btn-action-secondary:hover {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
            color: #ffffff;
        }

        /* Tech Badges Section */
        .tech-section {
            margin-top: 5rem;
            animation: fadeInUp 1.2s ease-out;
        }

        .tech-title {
            text-align: center;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.15em;
            margin-bottom: 1.5rem;
        }

        .badge-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .tech-badge {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 50px;
            padding: 0.5rem 1.2rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .tech-badge:hover {
            background: rgba(30, 41, 59, 0.9);
            border-color: rgba(255, 255, 255, 0.15);
            color: #f1f5f9;
        }

        .tech-badge i {
            font-size: 1rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card-1 {
            animation: fadeInUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .animate-card-2 {
            animation: fadeInUp 1.1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .gateway-card {
                padding: 1.75rem;
            }
        }
    </style>
</head>
<body>

    <!-- Glow Orbs -->
    <div class="glowing-orb orb-left"></div>
    <div class="glowing-orb orb-right"></div>

    <div class="page-wrapper container">
        
        <!-- Hero Header -->
        <header class="hero-section">
            <div class="system-badge">
                <span class="pulse-dot"></span>
                <span>System Status: Operational</span>
            </div>
            <h1 class="hero-title">College Management Portal</h1>
            <p class="hero-subtitle">
                A unified gateway for administrative controls, course management, student records, and developer resources.
            </p>
        </header>

        <!-- Gateway Options -->
        <div class="row g-4 justify-content-center max-w-5xl mx-auto">
            <!-- Admin Login Card -->
            <div class="col-md-6 animate-card-1">
                <div class="gateway-card" style="--card-hover-glow-rgb: 59, 130, 246;">
                    <div>
                        <div class="card-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3 class="card-title">Admin Management</h3>
                        <p class="card-desc">
                            Authorized personnel portal to manage student profiles, academic courses, registrations, roles, permissions, and administrative staff.
                        </p>
                    </div>
                    <a href="{{ route('admin.login') }}" class="btn-action btn-action-primary">
                        <span>Admin Login</span>
                        <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>

            <!-- Swagger API Docs Card -->
            <div class="col-md-6 animate-card-2">
                <div class="gateway-card" style="--card-hover-glow-rgb: 139, 92, 246;">
                    <div>
                        <div class="card-icon">
                            <i class="bi bi-terminal"></i>
                        </div>
                        <h3 class="card-title">Developer Hub</h3>
                        <p class="card-desc">
                            Interactive Swagger UI API sandbox documentation. Browse and test REST API endpoints, schemas, authentication, and responses.
                        </p>
                    </div>
                    <a href="{{ url('/api/documentation') }}" class="btn-action btn-action-secondary">
                        <span>Swagger Docs</span>
                        <i class="bi bi-code-slash"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tech Stack & Specifications Footer -->
        <section class="tech-section">
            <h4 class="tech-title">Powered By</h4>
            <div class="badge-list">
                <div class="tech-badge">
                    <i class="bi bi-bootstrap-fill text-purple-400"></i>
                    <span>Bootstrap 5</span>
                </div>
                <div class="tech-badge">
                    <i class="bi bi-gear-fill text-red-500"></i>
                    <span>Laravel 11</span>
                </div>
                <div class="tech-badge">
                    <i class="bi bi-database-fill text-info"></i>
                    <span>SQLite/MySQL</span>
                </div>
                <div class="tech-badge">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>Spatie Roles & Perms</span>
                </div>
                <div class="tech-badge">
                    <i class="bi bi-journal-code text-warning"></i>
                    <span>Swagger L5</span>
                </div>
            </div>
        </section>

    </div>

    <!-- Script for Dynamic Hover Light Effect -->
    <script>
        document.querySelectorAll('.gateway-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });
    </script>
</body>
</html>
