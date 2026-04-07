<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMATK | Inventory System - Fakultas Ilmu Komputer</title>

    <link rel="shortcut icon" href="<?= base_url('assets/static/images/logo/favicon.svg') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #00b8d4;
            --primary-light: #00e5ff;
            --primary-dark: #00838f;
            --secondary-color: #71717a;
            --accent-color: #00d4e6;
            --dark-color: #18181b;
            --light-color: #f5f5f4;
        }

        * {
            font-family: Arial, sans-serif;
        }

        .font-accent {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
        }

        .font-accent-normal {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: normal;
        }

        .text-highlight {
            position: relative;
            display: inline-block;
        }

        .text-highlight::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: -4px;
            right: -4px;
            height: 35%;
            background: rgba(0, 229, 255, 0.25);
            border-radius: 4px;
            z-index: -1;
            transform: skewX(-3deg);
        }

        /* ── Navbar ── */
        .navbar-home {
            background: rgba(0, 0, 0, 0.18);
            padding: 20px 0;
            transition: background-color 0.3s ease, padding 0.3s ease, box-shadow 0.3s ease;
        }

        .navbar-home.scrolled {
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            transition: color 0.3s ease;
        }

        .navbar-home.scrolled .navbar-brand {
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 6px 10px;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        .navbar-toggler-icon {
            background-image: none;
            width: 1.2rem;
            height: 1.2rem;
            position: relative;
        }

        .navbar-toggler-icon::before,
        .navbar-toggler-icon::after,
        .navbar-toggler-icon span {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background: white;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .navbar-toggler-icon::before {
            top: 1px;
        }

        .navbar-toggler-icon span {
            top: 8px;
        }

        .navbar-toggler-icon::after {
            top: 15px;
        }

        .navbar-home.scrolled .navbar-toggler {
            border-color: rgba(0, 131, 143, 0.45);
        }

        .navbar-home.scrolled .navbar-toggler-icon::before,
        .navbar-home.scrolled .navbar-toggler-icon::after,
        .navbar-home.scrolled .navbar-toggler-icon span {
            background: var(--primary-dark);
        }

        .navbar-home.scrolled .nav-link {
            color: var(--dark-color) !important;
        }

        .nav-link:hover {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .navbar-home.scrolled .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            color: white !important;
            font-weight: 500;
            transition: box-shadow 0.3s ease, filter 0.3s ease;
        }

        .btn-login:hover {
            box-shadow: 0 5px 20px rgba(0, 184, 212, 0.4);
            filter: brightness(1.03);
        }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.42) 0%, rgba(0, 0, 0, 0.34) 40%, rgba(0, 0, 0, 0.26) 100%),
                url('<?= base_url("img/hero.jpg") ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.18) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -150px;
            animation: pulseHero 8s ease-in-out infinite;
        }

        @keyframes pulseHero {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.5;
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            line-height: 1.3;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 35px;
            line-height: 1.8;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-hero {
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-hero-primary {
            background: white;
            color: var(--primary-color);
            border: none;
            box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
        }

        .btn-hero-primary:hover {
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.4);
            color: var(--primary-color);
        }

        .btn-hero-outline {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .btn-hero-outline:hover {
            background: white;
            color: var(--primary-color);
        }

        /* ── Sections ── */
        .features-section,
        .how-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f5f5f4, #e0f7fa, #f4f4f5);
            position: relative;
            overflow: hidden;
        }

        .contact-section {
            padding: 100px 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            color: var(--secondary-color);
            font-size: 1.1rem;
            margin-bottom: 50px;
            line-height: 1.8;
        }

        /* Feature cards */
        .feature-card {
            background: white;
            border-radius: 24px;
            padding: 45px 35px;
            text-align: center;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            border: 1px solid rgba(82, 82, 91, 0.1);
            box-shadow: 0 10px 40px rgba(82, 82, 91, 0.1);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            box-shadow: 0 25px 50px rgba(82, 82, 91, 0.2);
            border-color: rgba(82, 82, 91, 0.2);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 90px;
            height: 90px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 30px;
            transition: box-shadow 0.3s ease;
        }

        .feature-icon.purple {
            background: linear-gradient(135deg, #18181b, #424242);
            color: white;
            box-shadow: 0 10px 30px rgba(82, 82, 91, 0.3);
        }

        .feature-icon.blue {
            background: linear-gradient(135deg, #00838f, #00e5ff);
            color: white;
            box-shadow: 0 10px 30px rgba(0, 229, 255, 0.3);
        }

        .feature-icon.green {
            background: linear-gradient(135deg, #37474f, #78909c);
            color: white;
            box-shadow: 0 10px 30px rgba(82, 82, 91, 0.3);
        }

        .feature-icon.orange {
            background: linear-gradient(135deg, #006064, #00bcd4);
            color: white;
            box-shadow: 0 10px 30px rgba(0, 188, 212, 0.3);
        }

        .feature-card h4 {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: var(--secondary-color);
            margin-bottom: 0;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* Stats */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #18181b, #1a1a2e, #16213e, #00838f, #00e5ff);
            position: relative;
            overflow: hidden;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }

        .stat-label {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            opacity: 0.85;
            font-size: 1rem;
        }

        /* Peminjaman */
        .peminjaman-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #fafaf9, #e0f7fa, #f4f4f5);
            position: relative;
            overflow: hidden;
        }

        .peminjaman-card {
            background: white;
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 15px 50px rgba(82, 82, 91, 0.12);
            border: 1px solid rgba(82, 82, 91, 0.1);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .peminjaman-card:hover {
            box-shadow: 0 20px 60px rgba(82, 82, 91, 0.2);
            border-color: rgba(82, 82, 91, 0.2);
        }

        .peminjaman-form .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .peminjaman-form .form-control,
        .peminjaman-form .form-select {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid #e9ecef;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .peminjaman-form .form-control:focus,
        .peminjaman-form .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 184, 212, 0.2);
        }

        .peminjaman-form .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: box-shadow 0.3s ease, filter 0.3s ease;
        }

        .peminjaman-form .btn-submit:hover {
            box-shadow: 0 10px 30px rgba(0, 184, 212, 0.4);
            filter: brightness(1.03);
        }

        .peminjaman-info {
            background: linear-gradient(135deg, #18181b, #1a1a2e, #16213e, #00b8d4);
            border-radius: 24px;
            padding: 45px;
            color: white;
            height: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 184, 212, 0.3);
        }

        .peminjaman-info h4 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .peminjaman-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .peminjaman-info-item i {
            font-size: 1.5rem;
            margin-right: 15px;
            opacity: 0.9;
        }

        .peminjaman-info-item h6 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .peminjaman-info-item p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .barang-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 500;
            display: inline-block;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .kategori-preview,
        .kategori-all {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .kategori-all-wrap {
            max-height: 180px;
            overflow-y: auto;
            padding-right: 4px;
            margin-top: 10px;
        }

        .kategori-all-wrap::-webkit-scrollbar {
            width: 6px;
        }

        .kategori-all-wrap::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.35);
            border-radius: 999px;
        }

        .btn-kategori-toggle {
            margin-top: 10px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: white;
            background: rgba(255, 255, 255, 0.08);
            font-size: 0.8rem;
            border-radius: 999px;
            padding: 4px 12px;
        }

        .btn-kategori-toggle:hover {
            color: white;
            background: rgba(255, 255, 255, 0.16);
        }

        /* Contact */
        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            border: 1px solid rgba(82, 82, 91, 0.1);
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            height: 100%;
        }

        .contact-card:hover {
            box-shadow: 0 12px 30px rgba(82, 82, 91, 0.16);
            border-color: rgba(82, 82, 91, 0.2);
        }

        .contact-card .contact-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 14px;
        }

        .contact-card h6 {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .contact-card p {
            color: var(--secondary-color);
            margin-bottom: 0;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .contact-card a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .contact-card a:hover {
            color: var(--primary-light);
        }

        .social-bar {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 32px;
        }

        .social-bar a {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: white;
            transition: box-shadow 0.3s ease, filter 0.3s ease;
        }

        .social-bar a.sb-ig {
            background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
        }

        .social-bar a.sb-fb {
            background: #1877F2;
        }

        .social-bar a.sb-web {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        }

        .social-bar a.sb-link {
            background: linear-gradient(135deg, #00b894, #00cec9);
        }

        .social-bar a:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            filter: brightness(1.05);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #18181b, #1e1e2e, #00838f);
            padding: 40px 0 20px;
            color: white;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .footer-nav-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
        }

        .footer-nav-link:hover {
            color: white;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 1rem;
        }

        .social-links a:hover {
            background: var(--primary-color);
            box-shadow: 0 8px 18px rgba(0, 184, 212, 0.35);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 24px;
            padding-top: 16px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }

        /* Modal Login */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .resi-modal-content {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8fdff, #ffffff);
            box-shadow: 0 20px 50px rgba(0, 131, 143, 0.2);
        }

        .resi-modal-header {
            border-bottom: 1px solid rgba(0, 184, 212, 0.15);
            background: linear-gradient(135deg, rgba(0, 184, 212, 0.08), rgba(0, 229, 255, 0.08));
        }

        .resi-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 184, 212, 0.12);
            color: var(--primary-dark);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .resi-code-box {
            border: 2px dashed rgba(0, 184, 212, 0.35);
            background: #f1fdff;
            border-radius: 14px;
            padding: 16px 14px;
            text-align: center;
            font-size: 1.45rem;
            letter-spacing: 2px;
            font-weight: 700;
            color: #006064;
            word-break: break-all;
        }

        .modal-header {
            border-bottom: none;
            padding: 30px 30px 0;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-title {
            font-weight: 700;
            color: var(--dark-color);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 184, 212, 0.15);
        }

        .btn-login-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: box-shadow 0.3s ease, filter 0.3s ease;
        }

        .btn-login-submit:hover {
            box-shadow: 0 5px 20px rgba(0, 184, 212, 0.4);
            filter: brightness(1.03);
        }

        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
            z-index: 1000;
        }

        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            box-shadow: 0 10px 30px rgba(0, 184, 212, 0.4);
            filter: brightness(1.03);
        }

        .input-group-text {
            background: var(--light-color);
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        @media (max-width:992px) {
            .navbar-home {
                background: rgba(24, 24, 27, 0.88);
                backdrop-filter: blur(8px);
            }

            .navbar-home .navbar-collapse {
                margin-top: 12px;
                background: rgba(255, 255, 255, 0.96);
                border-radius: 16px;
                padding: 14px 16px;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
            }

            .navbar-home .nav-link {
                color: var(--dark-color) !important;
                margin: 8px 0;
            }

            .navbar-home .nav-link:hover {
                color: var(--primary-color) !important;
            }

            .navbar-home .btn-login {
                width: 100%;
                margin-top: 8px;
            }

            .hero-title {
                font-size: 2.5rem;
            }

        }

        @media (max-width:768px) {
            .hero-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .peminjaman-card {
                padding: 30px 20px;
            }

            .kategori-all-wrap {
                max-height: 150px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-home fixed-top" id="navbarUtama">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
                <i class="bi bi-box-seam-fill me-2" style="font-size:1.8rem;"></i>
                <span>SIMA<span class="font-accent">TIK</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"><span></span></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#permintaan">Permintaan ATK</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur Unggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-login">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    <?php else: ?>
                        <a href="#" class="btn btn-login" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-8 col-xl-7" data-aos="fade-up" data-aos-duration="1000">
                    <div class="hero-content text-center">
                        <h1 class="hero-title">
                            Sistem <span class="font-accent text-highlight">Inventaris</span> ATK<br>
                            <span style="color:rgba(255,255,255,0.9);">Fakultas <span class="font-accent">Ilmu Komputer</span></span>
                        </h1>
                        <p class="hero-subtitle">
                            Kelola alat tulis kantor dengan <span class="font-accent">mudah, efisien,</span> dan terintegrasi.<br>
                            Pantau stok, lacak penggunaan, dan buat laporan secara <span class="font-accent">real-time.</span>
                        </p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="#permintaan" class="btn btn-hero btn-hero-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i>Klik untuk membuat permintaan
                            </a>
                            <a href="#fitur" class="btn btn-hero btn-hero-outline">
                                <i class="bi bi-stars me-2"></i>Fitur Unggulan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form Permintaan ATK -->
    <section class="peminjaman-section" id="permintaan">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Form <span class="font-accent">Permintaan</span> ATK</h2>
                <p class="section-subtitle">Ajukan permintaan ATK dan Barang Habis Pakai dengan <span class="font-accent">mudah</span></p>
            </div>

            <?php if (session('sukses')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i> <?= session('sukses') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <?php if (session('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif ?>

            <div class="row g-4">
                <!-- Form -->
                <div class="col-lg-7">
                    <div class="peminjaman-card">
                        <form class="peminjaman-form" action="<?= base_url('ask/store') ?>" method="post" id="formPermintaan">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_redirect" value="<?= current_url() . '#permintaan' ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="namaPemohon" class="form-label">
                                        <i class="bi bi-person me-1"></i>Nama Pemohon <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="namaPemohon" name="borrower_name"
                                        value="<?= old('borrower_name') ?>"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unitKerja" class="form-label">
                                        <i class="bi bi-building me-1"></i>Unit Kerja / Prodi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="unitKerja" name="borrower_unit" required>
                                        <option value="">Pilih Unit Kerja</option>
                                        <?php foreach ($unitKerja as $unit): ?>
                                            <option value="<?= esc($unit) ?>" <?= old('borrower_unit') == $unit ? 'selected' : '' ?>>
                                                <?= esc($unit) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="emailPemohon" class="form-label">
                                        <i class="bi bi-envelope me-1"></i>Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control" id="emailPemohon" name="email"
                                        value="<?= old('email') ?>"
                                        placeholder="nama@fasilkom.ac.id" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nimNipPemohon" class="form-label">
                                        <i class="bi bi-card-text me-1"></i>NIM / NIP
                                    </label>
                                    <input type="text" class="form-control" id="nimNipPemohon" name="borrower_identifier"
                                        value="<?= old('borrower_identifier') ?>"
                                        placeholder="Opsional">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="filterKategori" class="form-label">
                                    <i class="bi bi-tag me-1"></i>Filter Kategori Barang
                                </label>
                                <select class="form-select" id="filterKategori">
                                    <option value="">Tampilkan Semua</option>
                                    <?php foreach ($daftarKategori as $kat): ?>
                                        <option value="<?= $kat['id'] ?>"><?= esc($kat['name']) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="barangDiminta" class="form-label">
                                    <i class="bi bi-box-seam me-1"></i>Barang yang Diminta <span class="text-danger">*</span>
                                </label>
                                <?php
                                $oldProductId = (string) old('product_id');
                                $oldProductName = '';
                                foreach ($daftarProduk as $produk) {
                                    if ((string) $produk['id'] === $oldProductId) {
                                        $oldProductName = $produk['name'];
                                        break;
                                    }
                                }
                                ?>
                                <input type="text"
                                    class="form-control"
                                    id="barangDiminta"
                                    list="daftarBarangAutocomplete"
                                    value="<?= esc($oldProductName) ?>"
                                    placeholder="Ketik nama barang untuk cari otomatis..."
                                    autocomplete="off"
                                    required>
                                <input type="hidden" id="barangDimintaId" name="product_id" value="<?= esc($oldProductId) ?>">
                                <datalist id="daftarBarangAutocomplete">
                                    <?php foreach ($daftarProduk as $produk): ?>
                                        <option value="<?= esc($produk['name']) ?>"
                                            data-id="<?= $produk['id'] ?>"
                                            data-kategori="<?= $produk['category_id'] ?>"
                                            data-stok="<?= $produk['current_stock'] ?>"
                                            data-satuan="<?= esc($produk['unit']) ?>"
                                            data-tersedia="<?= $produk['current_stock'] > 0 ? '1' : '0' ?>"
                                            label="Stok: <?= $produk['current_stock'] ?> <?= esc($produk['unit']) ?><?= $produk['current_stock'] <= 0 ? ' (Stok Habis)' : '' ?>">
                                        </option>
                                    <?php endforeach ?>
                                </datalist>
                                <div id="infoStok" class="form-text text-muted mt-1"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jumlahDiminta" class="form-label">
                                        <i class="bi bi-123 me-1"></i>Jumlah <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                        <input type="number" class="form-control" id="jumlahDiminta" name="quantity"
                                            value="<?= old('quantity', 1) ?>" min="1" placeholder="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggalPermintaan" class="form-label">
                                        <i class="bi bi-calendar me-1"></i>Tanggal Permintaan <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggalPermintaan" name="request_date"
                                        value="<?= old('request_date', date('Y-m-d')) ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keteranganPermintaan" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>Keperluan / Keterangan
                                </label>
                                <textarea class="form-control" id="keteranganPermintaan" name="notes"
                                    rows="3" placeholder="Jelaskan keperluan permintaan barang..."><?= old('notes') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-submit w-100" id="btnAjukan">
                                <i class="bi bi-send me-2"></i>Ajukan Permintaan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Panel info -->
                <div class="col-lg-5">
                    <div class="peminjaman-info">
                        <h4><i class="bi bi-info-circle me-2"></i>Informasi Permintaan</h4>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h6>Barang yang Tersedia</h6>
                                <p>Permintaan terbatas untuk ATK dan Barang Habis Pakai yang tersedia di inventaris.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-clock-history"></i>
                            <div>
                                <h6>Proses Persetujuan</h6>
                                <p>Permintaan diproses dalam 1×24 jam kerja setelah pengajuan.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-truck"></i>
                            <div>
                                <h6>Distribusi Barang</h6>
                                <p>Setelah disetujui, barang didistribusikan ke unit kerja pemohon.</p>
                            </div>
                        </div>
                        <div class="peminjaman-info-item">
                            <i class="bi bi-question-circle"></i>
                            <div>
                                <h6>Butuh Bantuan?</h6>
                                <p>Hubungi Admin di <a href="https://wa.me/6287896314494" target="_blank" class="text-decoration-none fw-bold" style="color:var(--primary-color);">+62 878-9631-4494</a></p>
                                <p class="mt-1 small text-muted">Akses layanan student services di <a href="https://unsika.link/layananfasilkom" target="_blank">Layanan Fasilkom</a></p>
                            </div>
                        </div>
                        <hr style="opacity:0.2;">
                        <h6 class="mb-3"><i class="bi bi-tags me-2"></i>Kategori Tersedia:</h6>
                        <?php
                        $kategoriPreview = array_slice($daftarKategori, 0, 12);
                        $kategoriLainnya = array_slice($daftarKategori, 12);
                        ?>
                        <div class="kategori-preview">
                            <?php foreach ($kategoriPreview as $kat): ?>
                                <span class="barang-badge" title="<?= esc($kat['name']) ?>"><?= esc($kat['name']) ?></span>
                            <?php endforeach ?>
                        </div>

                        <?php if (!empty($kategoriLainnya)): ?>
                            <button class="btn btn-kategori-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#kategoriLainnyaCollapse" aria-expanded="false" aria-controls="kategoriLainnyaCollapse">
                                Lihat <?= count($kategoriLainnya) ?> kategori lainnya
                            </button>
                            <div class="collapse" id="kategoriLainnyaCollapse">
                                <div class="kategori-all-wrap">
                                    <div class="kategori-all">
                                        <?php foreach ($kategoriLainnya as $kat): ?>
                                            <span class="barang-badge" title="<?= esc($kat['name']) ?>"><?= esc($kat['name']) ?></span>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <span class="badge px-4 py-2 rounded-pill" style="font-size:1.05rem;font-weight:600;background:#e0f7fa;color:#006064;">Fitur Sistem</span>
                <h2 class="section-title mt-2">Fitur <span class="font-accent">Unggulan</span></h2>
                <p class="section-subtitle">Sistem inventaris <span class="font-accent">modern</span> untuk kemudahan pengelolaan</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-xl-3 col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon purple"><i class="bi bi-box-seam"></i></div>
                        <h4>Manajemen <span class="font-accent">Stok</span></h4>
                        <p>Kelola stok ATK dengan <span class="font-accent">mudah.</span> Catat barang masuk dan keluar secara real-time.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon blue"><i class="bi bi-graph-up-arrow"></i></div>
                        <h4>Laporan & <span class="font-accent">Analitik</span></h4>
                        <p>Dapatkan laporan lengkap dan <span class="font-accent">analisis penggunaan</span> ATK dalam berbagai format.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon green"><i class="bi bi-bell"></i></div>
                        <h4><span class="font-accent">Notifikasi</span> Stok</h4>
                        <p>Terima <span class="font-accent">peringatan otomatis</span> ketika stok mencapai batas minimum.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon orange"><i class="bi bi-clipboard-check"></i></div>
                        <h4 class="font-accent-normal">Permintaan ATK</h4>
                        <p>Ajukan permintaan dengan sistem <span class="font-accent">tracking</span> yang terorganisir.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-section">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number" data-count="<?= $stats['total_produk'] ?? 0 ?>">0</span><span class="stat-label">Jenis Barang</span></div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number" data-count="<?= $stats['total_kategori'] ?? 0 ?>">0</span><span class="stat-label">Kategori</span></div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-item"><span class="stat-number" data-count="<?= $stats['jam_operasi'] ?? 8 ?>">0</span><span class="stat-label">Jam Operasional</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section class="contact-section" id="kontak">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <h2 class="section-title">Hubungi <span class="font-accent">Kami</span></h2>
                <p class="section-subtitle">Ada pertanyaan? <span class="font-accent">Jangan ragu</span> untuk menghubungi kami</p>
            </div>
            <div class="row g-3 justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                        <h6>Alamat</h6>
                        <p>Fakultas Ilmu Komputer<br>Universitas Singaperbangsa Karawang</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-telephone"></i></div>
                        <h6>Telepon</h6>
                        <p>+62 878-9631-4494</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-envelope"></i></div>
                        <h6>Email</h6>
                        <p>fasilkom@unsika.ac.id</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-clock"></i></div>
                        <h6>Jam Operasional</h6>
                        <p>Sen–Jum: 08:00–16:00<br>Sabtu: 08:00–12:00</p>
                    </div>
                </div>
            </div>

            <!-- Social Bar -->
            <div class="social-bar" data-aos="fade-up" data-aos-delay="200">
                <a href="https://www.instagram.com/fasilkomunsika" target="_blank" class="sb-ig" title="@fasilkomunsika">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://www.facebook.com/fasilkom.unsika/" target="_blank" class="sb-fb" title="Facebook Fasilkom">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://cs.unsika.ac.id/" target="_blank" class="sb-web" title="cs.unsika.ac.id">
                    <i class="bi bi-globe"></i>
                </a>
                <a href="https://unsika.link/layananfasilkom" target="_blank" class="sb-link" title="Layanan Mahasiswa">
                    <i class="bi bi-link-45deg"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="footer-brand"><i class="bi bi-box-seam-fill me-2"></i>SIMA<span class="font-accent" style="color:rgba(255,255,255,0.9);">TIK</span></div>
                    <p class="footer-text mb-0">Sistem Inventaris ATK — <span class="font-accent">Fakultas Ilmu Komputer</span>, Universitas Singaperbangsa Karawang.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex flex-wrap gap-3 justify-content-lg-center">
                        <a href="#beranda" class="footer-nav-link">Beranda</a>
                        <a href="#fitur" class="footer-nav-link">Fitur</a>
                        <a href="#permintaan" class="footer-nav-link">Permintaan</a>
                        <a href="#kontak" class="footer-nav-link">Kontak</a>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="social-links justify-content-lg-end">
                        <a href="https://www.instagram.com/fasilkomunsika" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/fasilkom.unsika/" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://cs.unsika.ac.id/" target="_blank" title="Website"><i class="bi bi-globe"></i></a>
                        <a href="https://unsika.link/layananfasilkom" target="_blank" title="Layanan"><i class="bi bi-link-45deg"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> SIMATK — Fakultas Ilmu Komputer UNSIKA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modal Kode Resi -->
    <div class="modal fade" id="modalKodeResi" tabindex="-1" aria-labelledby="judulModalKodeResi" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content resi-modal-content">
                <div class="modal-header resi-modal-header">
                    <h5 class="modal-title" id="judulModalKodeResi">
                        <i class="bi bi-receipt-cutoff me-2" style="color:var(--primary-dark);"></i>Kode Resi Permintaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <span class="resi-badge"><i class="bi bi-info-circle"></i> Harap dicatat</span>
                    <p class="mb-3">Permintaan berhasil dikirim. Simpan kode resi berikut untuk pelacakan status.</p>
                    <div class="resi-code-box" id="resiCodeText">-</div>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-outline-info w-50" id="btnSalinResi">
                            <i class="bi bi-clipboard me-1"></i>Salin Kode
                        </button>
                        <button type="button" class="btn btn-primary w-50" data-bs-dismiss="modal">
                            <i class="bi bi-check2-circle me-1"></i>Baik
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="modalMasuk" tabindex="-1" aria-labelledby="judulModalMasuk" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judulModalMasuk">
                        <i class="bi bi-box-seam-fill me-2" style="color:var(--primary-color);"></i>Masuk ke <span class="font-accent">SIMATK</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <?php if (session('loginError')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i><?= session('loginError') ?>
                        </div>
                    <?php endif ?>

                    <!-- ✅ POST ke /auth/login bukan GET ke /dashboard -->
                    <form action="<?= base_url('auth/login') ?>" method="POST" id="formLogin">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="inputUsername" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="inputUsername" name="username"
                                    value="<?= old('username') ?>" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="inputPassword" name="password"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword"
                                    onclick="togglePassword()">
                                    <i class="bi bi-eye" id="ikonPassword"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="ingatSaya" name="remember">
                                <label class="form-check-label" for="ingatSaya">Ingat saya</label>
                            </div>
                            <a href="#" class="text-decoration-none" style="color:var(--primary-color);font-size:0.9rem;">Lupa password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary btn-login-submit">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>
                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-0 text-muted" style="font-size:0.9rem;">
                            Belum punya akun? <a href="#" class="text-decoration-none" style="color:var(--primary-color);">Hubungi Admin</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top -->
    <button class="scroll-top" id="tombolScrollAtas">
        <i class="bi bi-rocket-takeoff"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // ── Init AOS ────────────────────────────────────────────────
        AOS.init({
            once: true,
            offset: 100
        });

        // ── Navbar scroll ────────────────────────────────────────────
        <?php $kodeResiPopup = session()->getFlashdata('kode_resi') ?: service('request')->getGet('resi'); ?>
        const kodeResiBaru = <?= json_encode($kodeResiPopup) ?>;
        if (kodeResiBaru) {
            setTimeout(() => {
                const modalResiEl = document.getElementById('modalKodeResi');
                const resiCodeText = document.getElementById('resiCodeText');
                const btnSalinResi = document.getElementById('btnSalinResi');

                if (resiCodeText) {
                    resiCodeText.textContent = kodeResiBaru;
                }

                if (btnSalinResi) {
                    btnSalinResi.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(kodeResiBaru);
                            btnSalinResi.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin';
                        } catch (e) {
                            btnSalinResi.innerHTML = '<i class="bi bi-x-circle me-1"></i>Gagal Salin';
                        }
                    }, {
                        once: true
                    });
                }

                if (modalResiEl) {
                    const modalResi = new bootstrap.Modal(modalResiEl);
                    modalResi.show();
                }

                // Bersihkan parameter ?resi=... agar modal tidak muncul lagi saat refresh.
                const url = new URL(window.location.href);
                if (url.searchParams.has('resi')) {
                    url.searchParams.delete('resi');
                    history.replaceState({}, document.title, url.toString());
                }
            }, 250);
        }

        const navbar = document.getElementById('navbarUtama');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // ── Scroll to top ────────────────────────────────────────────
        const tombolScrollAtas = document.getElementById('tombolScrollAtas');
        window.addEventListener('scroll', () => {
            tombolScrollAtas.classList.toggle('show', window.scrollY > 300);
        });
        tombolScrollAtas.addEventListener('click', () => window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }));

        // ── Smooth scroll navigasi ───────────────────────────────────
        document.querySelectorAll('a[href^="#"]').forEach(tautan => {
            tautan.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    const offsetNavbar = navbar.offsetHeight;
                    window.scrollTo({
                        top: target.offsetTop - offsetNavbar,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // ── Counter animasi ──────────────────────────────────────────
        function animasiCounter(el) {
            const target = +el.getAttribute('data-count');
            const increment = target / 200;
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    el.textContent = target + '+';
                    clearInterval(timer);
                } else {
                    el.textContent = Math.ceil(current);
                }
            }, 8);
        }

        const observerCounter = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    document.querySelectorAll('.stat-number').forEach(animasiCounter);
                    observerCounter.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        const seksiStats = document.querySelector('.stats-section');
        if (seksiStats) observerCounter.observe(seksiStats);

        // ── Set tanggal minimal ke hari ini ──────────────────────────
        const inputTanggal = document.getElementById('tanggalPermintaan');
        if (inputTanggal && !inputTanggal.value) {
            inputTanggal.value = new Date().toISOString().split('T')[0];
        }
        if (inputTanggal) {
            inputTanggal.setAttribute('min', new Date().toISOString().split('T')[0]);
        }

        // ── Filter barang berdasarkan kategori ───────────────────────
        const selectKategori = document.getElementById('filterKategori');
        const inputBarang = document.getElementById('barangDiminta');
        const inputBarangId = document.getElementById('barangDimintaId');
        const datalistBarang = document.getElementById('daftarBarangAutocomplete');
        const inputJumlah = document.getElementById('jumlahDiminta');
        const infoStok = document.getElementById('infoStok');

        const semuaBarang = datalistBarang ?
            Array.from(datalistBarang.options).map((opt) => ({
                id: String(opt.dataset.id || ''),
                nama: String(opt.value || '').trim(),
                kategori: String(opt.dataset.kategori || ''),
                stok: Number(opt.dataset.stok || 0),
                satuan: String(opt.dataset.satuan || ''),
                tersedia: String(opt.dataset.tersedia || '0') === '1',
            })) : [];

        function renderDatalist(kategoriId = '') {
            if (!datalistBarang) return;
            const filtered = semuaBarang.filter((item) => !kategoriId || item.kategori === kategoriId);
            datalistBarang.innerHTML = filtered.map((item) => {
                const status = item.tersedia ? '' : ' (Stok Habis)';
                const safeNama = item.nama
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
                const safeSatuan = item.satuan
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');

                return `<option value="${safeNama}" label="Stok: ${item.stok} ${safeSatuan}${status}"></option>`;
            }).join('');
        }

        function setInfoStok(message = '', className = 'form-text text-muted mt-1') {
            if (!infoStok) return;
            infoStok.textContent = message;
            infoStok.className = className;
        }

        function sinkronBarangTerpilih() {
            if (!inputBarang || !inputBarangId) return;

            const keyword = inputBarang.value.trim();

            if (!keyword) {
                inputBarangId.value = '';
                setInfoStok('');
                if (inputJumlah) inputJumlah.removeAttribute('max');
                return;
            }

            const keywordLower = keyword.toLowerCase();
            const cocokParsial = semuaBarang.filter((item) => item.nama.toLowerCase().includes(keywordLower));
            const ditemukan = semuaBarang.find((item) => item.nama.toLowerCase() === keywordLower);

            // Autofill kategori: exact match diprioritaskan, fallback ke 1 hasil parsial.
            const kandidatKategori = ditemukan ?? (cocokParsial.length === 1 ? cocokParsial[0] : null);
            if (kandidatKategori && selectKategori && selectKategori.value !== kandidatKategori.kategori) {
                selectKategori.value = kandidatKategori.kategori;
                renderDatalist(kandidatKategori.kategori);
            }

            if (!ditemukan) {
                inputBarangId.value = '';
                setInfoStok('Pilih barang dari saran autocomplete agar data valid.', 'form-text text-danger mt-1');
                if (inputJumlah) inputJumlah.removeAttribute('max');
                return;
            }

            if (!ditemukan.tersedia || ditemukan.stok <= 0) {
                inputBarangId.value = '';
                setInfoStok('Barang ini stoknya habis. Pilih barang lain.', 'form-text text-danger mt-1');
                if (inputJumlah) inputJumlah.removeAttribute('max');
                return;
            }

            inputBarangId.value = ditemukan.id;
            setInfoStok(`Stok tersedia: ${ditemukan.stok} ${ditemukan.satuan}`, 'form-text text-success mt-1');
            if (inputJumlah) {
                inputJumlah.setAttribute('max', String(ditemukan.stok));
                if (Number(inputJumlah.value || 0) > ditemukan.stok) {
                    inputJumlah.value = String(ditemukan.stok);
                }
            }
        }

        if (selectKategori) {
            selectKategori.addEventListener('change', function() {
                renderDatalist(this.value);
                if (inputBarang) {
                    inputBarang.value = '';
                    inputBarang.setCustomValidity('');
                }
                if (inputBarangId) inputBarangId.value = '';
                setInfoStok('');
                if (inputJumlah) inputJumlah.removeAttribute('max');
            });
        }

        if (inputBarang) {
            inputBarang.addEventListener('input', sinkronBarangTerpilih);
            inputBarang.addEventListener('change', sinkronBarangTerpilih);
            inputBarang.addEventListener('blur', sinkronBarangTerpilih);
        }

        renderDatalist(selectKategori ? selectKategori.value : '');
        sinkronBarangTerpilih();

        // ── Submit: disable tombol agar tidak double-submit ──────────
        const formPermintaan = document.getElementById('formPermintaan');
        const btnAjukan = document.getElementById('btnAjukan');
        if (formPermintaan) {
            formPermintaan.addEventListener('submit', function(event) {
                sinkronBarangTerpilih();

                if (!inputBarangId || !inputBarangId.value) {
                    event.preventDefault();
                    if (inputBarang) {
                        inputBarang.setCustomValidity('Silakan pilih barang dari daftar autocomplete.');
                        inputBarang.reportValidity();
                    }
                    return;
                }

                if (inputBarang) {
                    inputBarang.setCustomValidity('');
                }

                btnAjukan.disabled = true;
                btnAjukan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
            });
        }

        // ── Toggle password visibility ───────────────────────────────
        function togglePassword() {
            const inputPw = document.getElementById('inputPassword');
            const ikon = document.getElementById('ikonPassword');
            if (inputPw.type === 'password') {
                inputPw.type = 'text';
                ikon.className = 'bi bi-eye-slash';
            } else {
                inputPw.type = 'password';
                ikon.className = 'bi bi-eye';
            }
        }
    </script>
</body>

</html>