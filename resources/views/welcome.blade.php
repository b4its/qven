<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Q.ven - Sistem Pengawasan Makanan Bergizi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --blue-dark: #0a3d8f;
      --blue-mid: #1565d8;
      --blue-bright: #2979ff;
      --blue-light: #5ba3f5;
      --bg-primary: #ffffff;
      --bg-secondary: #f5faff;
      --text-dark: #0d1b3e;
      --text-mid: #1a3a6e;
      --text-muted: #5a7daa;
      --footer-bg: #0d1b3e;
      --tech-card-bg: #ffffff;
      --tech-card-border: #eaeaea;
      --tech-name-color: #1a3a6e;
      --tech-desc-color: #5a7daa;
      --nav-bg: rgba(0,0,0,0.6);
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg-primary);
      color: var(--text-dark);
      overflow-x: hidden;
      line-height: 1.6;
    }

    a { text-decoration: none; color: inherit; }

    img { max-width: 100%; display: block; }

    /* ── NAVBAR ── */
    .navbar {
      position: fixed; top: 0; left: 0; right: 0; z-index: 100;
      display: flex; align-items: center; justify-content: space-between;
      height: 64px; padding: 0 5%;
      background: rgba(0,0,0,0.6);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
    }
    .navbar .logo img {
      height: 32px; width: auto;
      filter: brightness(0) invert(1);
    }
    .navbar .nav-links {
      display: flex; gap: 32px; list-style: none;
    }
    .navbar .nav-links a {
      font-weight: 500; font-size: 0.85rem;
      color: rgba(255,255,255,0.8);
      transition: color 0.2s; white-space: nowrap;
    }
    .navbar .nav-links a:hover { color: #fff; }
    .navbar .nav-actions {
      display: flex; gap: 16px; align-items: center;
    }
    .navbar .nav-actions a {
      font-size: 0.85rem; font-weight: 600; color: #fff;
    }
    .navbar .btn-signup {
      padding: 6px 20px; border-radius: 999px;
      border: 2px solid rgba(255,255,255,0.6); color: #fff;
      font-weight: 600; font-size: 0.85rem; cursor: pointer; background: transparent;
      transition: all 0.25s;
    }
    .navbar .btn-signup:hover { background: #fff; color: #2563eb; }
    .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; }
    .hamburger span {
      display: block; width: 24px; height: 2px; border-radius: 2px;
      background: #fff; transition: all 0.3s;
    }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    .mobile-menu {
      display: none; position: fixed; top: 64px; left: 0; right: 0;
      z-index: 99; flex-direction: column; gap: 4px;
      padding: 20px 5% 28px;
      background: rgba(37, 99, 235, 0.98);
    }
    .mobile-menu a {
      font-weight: 500; font-size: 0.95rem;
      color: rgba(255,255,255,0.8); padding: 10px 0;
      transition: color 0.2s;
    }
    .mobile-menu a:hover { color: #fff; }
    .mobile-menu .mobile-actions {
      display: flex; flex-direction: column; gap: 12px; margin-top: 24px;
    }
    .mobile-menu .mobile-actions a {
      display: flex; justify-content: center; align-items: center;
      width: 100%; padding: 12px 20px; border-radius: 999px;
      font-weight: 600; font-size: 1rem;
    }
    .mobile-menu .mobile-actions .btn-login {
      background: rgba(255,255,255,0.1); color: #fff;
    }
    .mobile-menu .mobile-actions .btn-login:hover { background: rgba(255,255,255,0.2); }
    .mobile-menu .mobile-actions .btn-signup-mobile {
      border: 2px solid rgba(255,255,255,0.6); color: #fff;
    }

    /* ── HERO ── */
    .hero {
      position: relative; min-height: 100dvh;
      display: flex; align-items: center; overflow: hidden;
      background: url({{ asset('landing/bg-dashboard.png') }}) center/cover no-repeat;
    }
    .hero-inner {
      position: relative; z-index: 10; width: 100%;
      max-width: 1200px; margin: 0 auto; padding: 80px 5%;
      display: flex; align-items: center; gap: 32px;
    }
    .hero-left { flex: 1; max-width: 480px; }
    .hero-left h1 {
      color: #fff; line-height: 1.25; margin-bottom: 20px;
      font-size: clamp(1.6rem, 3.5vw, 2.6rem); font-weight: 500;
    }
    .hero-left h1 strong { font-weight: 800; }
    .hero-left p { font-size: clamp(0.75rem, 2.5vw, 0.95rem); margin-bottom: 32px; line-height: 1.6; color: rgba(255,255,255,0.85); }
    .hero-buttons { display: flex; gap: 16px; }
    .hero-buttons button {
      padding: 12px 28px; border-radius: 999px;
      font-weight: 700; font-size: 0.875rem; cursor: pointer;
      transition: all 0.3s; border: none;
    }
    .btn-primary { background: #fff; color: #2563eb; box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
    .btn-primary:hover { transform: scale(1.05); }
    .btn-secondary {
      background: transparent; color: #fff;
      border: 2px solid rgba(255,255,255,0.6) !important;
    }
    .btn-secondary:hover { transform: scale(1.05); }
    .hero-right { flex: 1; display: flex; justify-content: center; align-items: center; }
    .hero-right img {
      width: 100%; max-width: 520px;
      filter: drop-shadow(0 25px 50px rgba(0,0,0,0.35));
      animation: gentleFloat 6s ease-in-out infinite;
    }
    .hero-chevron {
      position: absolute; bottom: 28px; left: 50%; transform: translateX(-50%);
      z-index: 10; width: 48px; height: 48px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; background: #fff; border: none;
      box-shadow: 0 6px 20px rgba(0,0,0,0.18);
      transition: transform 0.3s;
    }
    .hero-chevron:hover { transform: translateX(-50%) scale(1.1); }
    .hero-border {
      position: absolute; bottom: 0; left: 0; right: 0;
      height: 1px; background: rgba(255,255,255,0.15); pointer-events: none;
    }

    @keyframes gentleFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-14px); }
    }

    /* ── FITUR SECTION ── */
    .fitur-section {
      padding: 80px 0; position: relative; overflow: hidden;
      background: #fff;
    }
    .fitur-header { text-align: center; margin-bottom: 48px; position: relative; z-index: 10; }
    .fitur-header p { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #2563eb; margin-bottom: 8px; }
    .fitur-header h2 { font-weight: 800; font-size: clamp(1.4rem, 2.5vw, 2rem); color: #1e293b; }
    .fitur-header h2 span { color: #2563eb; }

    .marquee-container { 
      position: relative; 
      z-index: 10; 
      height: 550px; /* Tinggi disesuaikan agar tidak terpotong seperti masalah sebelumnya */
      padding: 20px 0; 
      overflow: hidden; 
    }
    .marquee-track-wrapper { 
      display: flex; 
      width: max-content; 
    }
    .marquee-track {
      display: flex; 
      gap: 24px; 
      padding-right: 24px;
      /* Animasi berjalan ke kiri selama 30 detik (bisa diubah kecepatannya) */
      animation: scrollMarquee 30s linear infinite; 
    }
    .fitur-card {
      flex: none; 
      width: clamp(240px, 32vw, 340px); 
      border-radius: 16px; 
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: transform 0.5s cubic-bezier(0.4,0,0.2,1), opacity 0.5s cubic-bezier(0.4,0,0.2,1);
    }
    .fitur-card img { width: 100%; height: auto; display: block; user-select: none; pointer-events: none; }

    /* Kunci animasi: Geser persis 50% dari total panjang track */
    @keyframes scrollMarquee {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    /* ── CARA KERJA OVERVIEW ── */
    .ck-overview {
      position: relative; overflow: hidden;
      padding: 96px 5%; background: #ffffff;
    }
    .ck-blur-bg { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
    .ck-blur-bg .blob {
      position: absolute; border-radius: 50%;
      filter: blur(60px); pointer-events: none;
    }
    .ck-overview-inner { position: relative; z-index: 10; max-width: 1200px; margin: 0 auto; }
    .ck-header { text-align: center; margin-bottom: 80px; }
    .ck-header p { font-size: 0.85rem; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase; color: #1d4ed8; margin-bottom: 16px; }
    .ck-header h2 { font-weight: 800; font-size: 2.5rem; letter-spacing: -0.02em; color: #0f172a; }
    .ck-header h2 span { color: #1d4ed8; }

    .ck-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }

    /* Orbit */
    .orbit-wrap { display: flex; justify-content: center; align-items: center; }
    .orbit-container { position: relative; width: min(80vmin, 500px); height: min(80vmin, 500px); }
    .orbit-ring-outer {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 94%; height: 94%; border-radius: 50%;
      border: 1.5px solid rgba(29, 78, 216, 0.15);
    }
    .orbit-ring-inner {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 62%; height: 62%; border-radius: 50%;
      border: 1.5px solid rgba(29, 78, 216, 0.10);
    }
    .orbit-center {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 26%; height: 26%; border-radius: 50%;
      min-width: 80px; min-height: 80px; max-width: 160px; max-height: 160px;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      box-shadow: 0 0 0 16px rgba(59, 130, 246, 0.10), 0 0 0 32px rgba(59, 130, 246, 0.05);
      display: flex; align-items: center; justify-content: center; z-index: 10;
    }
    .orbit-node {
      position: absolute; top: 50%; left: 50%;
      width: clamp(36px, 8vmin, 46px); height: clamp(36px, 8vmin, 46px);
      margin-top: calc(-1 * (clamp(36px, 8vmin, 46px) / 2)); margin-left: calc(-1 * (clamp(36px, 8vmin, 46px) / 2));
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      border-radius: 50%; z-index: 5;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      display: flex; align-items: center; justify-content: center;
      animation: pulse-node 3s ease-in-out infinite;
    }
    @keyframes pulse-node {
      0%, 100% { box-shadow: 0 0 0 0 rgba(29, 78, 216, 0.4), 0 4px 12px rgba(0,0,0,0.15); }
      50% { box-shadow: 0 0 0 12px rgba(29, 78, 216, 0), 0 4px 12px rgba(0,0,0,0.15); }
    }

    /* Steps */
    .steps-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .step-item {
      display: flex; align-items: center; gap: 16px;
      padding: 16px 20px; border-radius: 12px;
      background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      border: 1px solid #e2e8f0;
      transition: all 0.3s;
    }
    .step-item:hover { transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .step-num {
      width: 40px; height: 40px; min-width: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem; font-weight: 700; color: #fff;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    .step-text { font-size: 1rem; font-weight: 600; color: #334155; line-height: 1.4; }

    .btn-peta {
      display: flex; align-items: center; gap: 16px;
      padding: 16px 20px; border-radius: 12px;
      background: #1d4ed8; color: #fff; font-weight: 600; font-size: 1rem;
      border: none; cursor: pointer; font-family: inherit;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
      transition: all 0.3s;
    }
    .btn-peta:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
    .btn-peta-icon {
      width: 40px; height: 40px; min-width: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,0.2);
    }
    .btn-peta-icon svg { transition: transform 0.3s; }
    .btn-peta-icon.open svg { transform: rotate(180deg); }
    .btn-peta { width: 100%; justify-content: center; }
    .steps-grid .btn-peta { grid-column: auto; justify-self: stretch; }

    /* ── CARA KERJA PETA (Flowchart) ── */
    .ck-peta {
      position: relative; overflow: hidden;
      padding: 96px 5%;
      background: linear-gradient(180deg, #3B82F6 0%, #1d4ed8 40%, #1e40af 100%);
      transition: background 0.35s;
      display: none;
    }
    .ck-peta.open { display: block; }
    .ck-peta-waves { position: absolute; inset: 0; overflow: hidden; pointer-events: none; }
    .ck-peta-waves svg { position: absolute; width: 200%; }
    .ck-peta-glow1 {
      position: absolute; width: 400px; height: 400px;
      right: -150px; top: -100px; border-radius: 50%; pointer-events: none;
      background: radial-gradient(circle, rgba(96,165,250,0.25) 0%, transparent 70%);
    }
    .ck-peta-glow2 {
      position: absolute; width: 350px; height: 350px;
      left: -100px; bottom: -80px; border-radius: 50%; pointer-events: none;
      background: radial-gradient(circle, rgba(30,64,175,0.4) 0%, transparent 70%);
    }
    @keyframes waveMove1 { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    @keyframes waveMove2 { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
    @keyframes waveMove3 { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    .ck-peta-inner { position: relative; z-index: 10; max-width: 1200px; margin: 0 auto; }
    .ck-peta-header {
      text-align: center;
      margin: 0 0 24px;
      max-width: none;
      background: none;
      padding: 0;
      border-radius: 0;
      display: block;
      backdrop-filter: none;
      box-shadow: none;
    }
    .ck-peta-header p { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #ffffff; margin-bottom: 6px; }
    .ck-peta-header h2 { font-weight: 800; font-size: clamp(1.4rem, 2.6vw, 2rem); color: #ffffff; margin: 0; }
    .ck-peta-header h2 span { color: #ffffff; font-weight: 800; }
    .ck-peta-header .sub { font-size: 0.88rem; margin-top: 8px; color: #ffffff; }

    .flowchart { position: relative; height: clamp(350px, 45vw, 500px); }
    .flowchart svg.lines { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }
    .flowchart svg.lines line {
      stroke: rgba(255,255,255,0.5); stroke-width: 0.8; stroke-dasharray: 4,3;
      opacity: 0; transition: opacity 1s ease;
    }
    .flowchart svg.lines line.visible { opacity: 0.7; }

    .flow-node-wrap {
      position: absolute; z-index: 10;
      transform: translate(-50%, -50%) scale(0);
      transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .flow-node-wrap.visible { transform: translate(-50%, -50%) scale(1); }

    .flow-node-group { position: relative; }
    .flow-node-circle {
      width: clamp(68px, 9vw, 88px); height: clamp(68px, 9vw, 88px); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      border: 3px solid rgba(255,255,255,0.6);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
      transition: transform 0.3s;
    }
    .flow-node-circle:hover { transform: scale(1.05); }
    .flow-node-circle .inner {
      width: clamp(44px, 6vw, 56px); height: clamp(44px, 6vw, 56px); border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: white;
    }
    .flow-node-circle .inner svg {
      width: 26px; height: 26px;
    }
    .flow-tooltip {
      position: absolute; width: 200px; padding: 12px; border-radius: 12px;
      background: #fff; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
      left: 50%; z-index: 50; pointer-events: none;
      opacity: 0; visibility: hidden; transition: all 0.3s;
    }
    .flow-tooltip.tooltip-top { bottom: calc(100% + 10px); transform: translateX(-50%) translateY(8px); }
    .flow-tooltip.tooltip-bottom { top: calc(100% + 10px); transform: translateX(-50%) translateY(-8px); }
    .flow-node-group:hover .flow-tooltip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0) !important; }
    .flow-tooltip .tt-title { font-weight: 700; font-size: 0.875rem; color: #1d4ed8; }
    .flow-tooltip .tt-desc { font-size: 11px; color: #64748b; margin-top: 4px; }

    .flow-label {
      position: absolute; white-space: nowrap; font-size: 11px; font-weight: 700;
      color: #1e293b; background: rgba(255,255,255,0.9);
      padding: 4px 12px; border-radius: 20px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      left: 50%; transform: translateX(-50%); bottom: -38px;
    }

    /* ── TEKNOLOGI SECTION ── */
    .tech-section {
      padding: 80px 0; position: relative; overflow: hidden;
      background: var(--bg-primary);
    }
    .tech-header { text-align: center; margin-bottom: 48px; position: relative; z-index: 10; }
    .tech-header p { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--blue-bright); margin-bottom: 8px; }
    .tech-header h2 { font-weight: 800; font-size: clamp(1.4rem, 2.5vw, 2rem); color: var(--text-dark); margin-bottom: 8px; }
    .tech-header h2 span { color: var(--blue-bright); }

    .tech-marquee-container { position: relative; z-index: 10; height: 120px; padding: 16px 0; }
    .tech-marquee-wrapper { position: absolute; inset: 0; display: flex; gap: 20px; }
    .tech-marquee-track { display: flex; gap: 20px; position: absolute; }
    .tech-track-a { left: 0; animation: marqueeTechA 20s linear infinite; }
    .tech-track-b { left: 1500px; animation: marqueeTechB 20s linear infinite; }
    .tech-card {
      flex: none; width: 280px; display: flex; align-items: center; gap: 16px;
      padding: 16px 24px; border-radius: 16px;
      background: var(--tech-card-bg); border: 1px solid var(--tech-card-border);
      box-shadow: 0 2px 12px rgba(0,0,0,0.06);
      transition: transform 0.5s cubic-bezier(0.4,0,0.2,1), opacity 0.5s cubic-bezier(0.4,0,0.2,1);
      will-change: transform, opacity;
    }
    .tech-card-img { width: 48px; height: 48px; flex-shrink: 0; border-radius: 12px; overflow: hidden; display: flex; align-items: center; justify-content: center; padding: 4px; background: #f5f5f5; }
    .tech-card-img img { width: 100%; height: 100%; object-fit: contain; }
    .tech-card-info h4 { font-size: 0.875rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--tech-name-color); }
    .tech-card-info p { font-size: 0.75rem; color: var(--tech-desc-color); line-height: 1.4; }

    @keyframes marqueeTechA { 0% { transform: translateX(0); } 100% { transform: translateX(-1500px); } }
    @keyframes marqueeTechB { 0% { transform: translateX(0); } 100% { transform: translateX(-1500px); } }

    /* ── FOOTER ── */
    .footer {
      text-align: center; padding: 28px 5%; font-size: 0.82rem;
      background: var(--footer-bg); color: rgba(255,255,255,0.6);
    }
    .footer strong { color: #fff; }

    /* ── REVEAL ANIMATIONS ── */
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: none; }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      /* Mobile breakpoint: show hamburger and mobile menu only on small screens */
      .navbar .nav-links { display: none; }
      .navbar .nav-actions { display: none; }
      .hamburger { display: flex; }
      .mobile-menu.open { display: flex; }
      .ck-grid { grid-template-columns: 1fr; gap: 48px; }
      .orbit-container { transform: scale(0.85); }
      .hero-inner { flex-direction: column; text-align: center; gap: 32px; padding: 60px 5% !important; }
      .hero-left { max-width: 100%; }
      .hero-buttons { justify-content: center; }
      .hero-right { max-width: 420px; }

      /* 1. Batasi lebar wrapper selebar layar dan nyalakan scroll horizontal */
      .marquee-track-wrapper {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x mandatory;
        padding-bottom: 15px;
      }
      
      /* 2. Matikan animasi otomatis agar tidak melawan jari saat di-scroll */
      .marquee-track {
        animation: none !important;
        width: max-content;
      }
      
      /* 3. Efek magnet agar saat digeser, card posisinya pas di tengah */
      .fitur-card {
        scroll-snap-align: center;
      }

      /* 4. (Opsional) Sembunyikan garis scrollbar agar UI tetap bersih */
      .marquee-track-wrapper::-webkit-scrollbar {
        display: none;
      }
    }
    @media (max-width: 640px) {
      .steps-grid { grid-template-columns: 1fr; }
      .orbit-container { transform: scale(0.65); }
      /* UBAH INI: Naikkan tingginya dari 380px menjadi sekitar 500px */
      .marquee-container { height: 500px; }
      .fitur-card { width: 280px; }
      .hero-left h1 { margin-bottom: 16px; line-height: 1.3; }
      .hero-left p { margin-bottom: 24px; }
    }
    /* Extra-small devices (phones) optimizations */
    @media (max-width: 480px) {
      .navbar { height: 56px; padding: 0 4%; }
      .navbar .logo img { height: 28px; }
      .mobile-menu { top: 56px; bottom: 0; overflow-y: auto; }

      .hero { min-height: 100vh; }
      .hero-inner { padding: 50px 5%; flex-direction: column; gap: 24px; }
      .hero-left h1 { font-size: clamp(1.1rem, 5vw, 1.6rem); margin-bottom: 12px; line-height: 1.35; }
      .hero-left p { font-size: clamp(0.7rem, 2.2vw, 0.85rem); margin-bottom: 20px; }
      .hero-buttons { flex-direction: column; gap: 12px; }
      .hero-buttons button { width: 100%; padding: 12px; }
      .hero-right img { max-width: 320px; margin: 0 auto; }

      /* Make marquee static / scrollable on small screens to reduce CPU */
      /* UBAH INI: Biarkan tingginya auto agar page di bawah otomatis terdorong turun */
      .marquee-container { height: auto; }
      .marquee-track-wrapper { position: relative; display: flex; gap: 16px; overflow-x: auto; padding-bottom: 6px; }
      .marquee-track, .marquee-track-a, .marquee-track-b { position: static !important; left: auto !important; animation: none !important; display: flex; gap: 16px; }
      /* hide duplicated track on small screens */
      .marquee-track.marquee-track-b { display: none !important; }
      .fitur-card { width: 260px; }

      /* UBAH INI JUGA: Agar logo stack teknologi tidak kepotong */
      .tech-marquee-container { height: auto; padding-bottom: 20px; }
      .tech-marquee-wrapper { position: relative; display: flex; gap: 12px; overflow-x: auto; }
      .tech-marquee-track, .tech-track-a, .tech-track-b { position: static !important; left: auto !important; animation: none !important; display: flex; gap: 12px; }
      .tech-marquee-track.tech-track-b { display: none !important; }
      .tech-card { width: 220px; }

      .ck-peta .flowchart { height: 300px; }

      .btn-primary, .btn-secondary { width: 100%; }
    }
    @keyframes spBlob {
      0%, 100% { transform: translate(-50%,-50%) scale(1); opacity: 0.5; }
      50% { transform: translate(-50%,-50%) scale(1.2); opacity: 1; }
    }
    
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">
    <!-- TODO (PHP): Ganti atribut src ini dengan path dinamis dari server, misal: -->
    <img src="{{ asset('landing/logo-qven.png') }}" alt="Q.ven">
  </div>
  <ul class="nav-links">
    <li><a href="#hero">Dashboard</a></li>
    <li><a href="#fitur">Fitur</a></li>
    <li><a href="#cara-kerja">Cara Kerja</a></li>
    <li><a href="#teknologi">Teknologi</a></li>
  </ul>
  <div class="nav-actions">
    <a href="{{ route('auth.login.index') }}">Log In</a>
    <a href="{{ route('auth.register.index') }}" class="btn-signup">Sign Up</a>
  </div>
  <div class="hamburger" id="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>
<!-- Mobile menu (hanya muncul di mobile). -->
<div class="mobile-menu" id="mobileMenu">
  <!-- TODO (PHP): Render daftar menu secara dinamis jika diperlukan -->
  <a href="#hero" onclick="closeMenu()">Dashboard</a>
  <a href="#fitur" onclick="closeMenu()">Fitur</a>
  <a href="#cara-kerja" onclick="closeMenu()">Cara Kerja</a>
  <a href="#teknologi" onclick="closeMenu()">Teknologi</a>
  <div class="mobile-actions">
    <!-- TODO (PHP): Ganti href button login/signup ke endpoint PHP autentikasi -->
    <a href="{{ route('auth.login.index') }}" class="btn-login">Log In</a>
    <a href="{{ route('auth.register.index') }}" class="btn-signup-mobile">Sign Up</a>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-inner">
    <div class="hero-left">
      <h1>Memastikan <strong>kualitas gizinya</strong> serta <strong>Kelayakan Vendor</strong> dengan <strong>Machine Learning</strong> dan Transparansi <strong>Blockchain</strong></h1>
      <p>Quality Vendor and Nutrition<br>Makan Bergizi Gratis</p>
      <div class="hero-buttons">
        <a href="{{ route('auth.login.index') }}">
          <button class="btn-primaru">Coba Sekarang</button>
        </a>
        <a href="{{ route('auth.register.index') }}">
          <button class="btn-secondary">Sign Up</button>
        </a>
      </div>
    </div>
    <div class="hero-right">
      <!-- TODO (PHP): Ganti src gambar hero dengan path dinamis dari server jika perlu -->
      <img src="{{ asset('landing/food-tray.png')}}" alt="Nampan Makanan Bergizi">
    </div>
  </div>
  <button class="hero-chevron" onclick="document.getElementById('fitur').scrollIntoView({behavior:'smooth'})">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
  </button>
  <div class="hero-border"></div>
</section>

<!-- FITUR -->
<section class="fitur-section" id="fitur">
  <div class="fitur-header">
    <p>FITUR UNGGULAN</p>
    <h2>Fitur Unggulan <span>Q.ven</span></h2>
  </div>
  <div class="marquee-container">
    <div class="marquee-track-wrapper">
    </div>
  </div>
</section>

<!-- CARA KERJA OVERVIEW -->
<section class="ck-overview" id="cara-kerja">
  <div class="ck-blur-bg">
    <div class="blob" style="width:500px;height:500px;top:-10%;left:-10%;background:radial-gradient(circle,rgba(59,130,246,0.12) 0%,transparent 70%);"></div>
    <div class="blob" style="width:400px;height:400px;top:40%;right:-8%;background:radial-gradient(circle,rgba(37,99,235,0.10) 0%,transparent 70%);"></div>
    <div class="blob" style="width:350px;height:350px;bottom:-5%;left:30%;background:radial-gradient(circle,rgba(96,165,250,0.10) 0%,transparent 70%);"></div>
    <div class="blob" style="width:300px;height:300px;top:20%;left:50%;background:radial-gradient(circle,rgba(29,78,216,0.08) 0%,transparent 70%);"></div>
    <div class="blob" style="width:450px;height:450px;bottom:10%;right:20%;background:radial-gradient(circle,rgba(59,130,246,0.07) 0%,transparent 70%);"></div>
  </div>

  <div class="ck-overview-inner">
    <div class="ck-header">
      <p>CARA KERJA</p>
      <h2>Cara Kerja <span>Q.ven</span></h2>
    </div>

    <div class="ck-grid">
      <!-- Orbit -->
      <div class="orbit-wrap">
        <div class="orbit-container" id="orbitContainer">
          <div class="orbit-ring-outer"></div>
          <div class="orbit-ring-inner"></div>
          <div class="orbit-center" id="orbitCenter">
            <svg width="56" height="56" viewBox="0 0 64 64" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <line x1="16" y1="4" x2="16" y2="20"/>
              <line x1="24" y1="4" x2="24" y2="20"/>
              <line x1="32" y1="4" x2="32" y2="20"/>
              <path d="M12 20 Q24 26 36 20" fill="none"/>
              <line x1="24" y1="24" x2="24" y2="56" stroke-width="3.5"/>
              <path d="M20 56 Q24 60 28 56" fill="none"/>
              <ellipse cx="50" cy="18" rx="7" ry="10"/>
              <line x1="50" y1="28" x2="50" y2="52" stroke-width="3.5"/>
              <path d="M46 52 Q50 57 54 52" fill="none"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Steps -->
      <div class="steps-wrap">
        <div class="steps-grid">
          <div class="step-item">
            <div class="step-num">1</div>
            <span class="step-text">Akses Sistem</span>
          </div>
          <div class="step-item">
            <div class="step-num">2</div>
            <span class="step-text">Registrasi dan Login</span>
          </div>
          <div class="step-item">
            <div class="step-num">3</div>
            <span class="step-text">Identifikasi Role Pengguna</span>
          </div>
          <div class="step-item">
            <div class="step-num">4</div>
            <span class="step-text">Pengelolaan Vendor</span>
          </div>
          <div class="step-item">
            <div class="step-num">5</div>
            <span class="step-text">Pengelolaan Data oleh Admin Vendor</span>
          </div>
          <div class="step-item">
            <div class="step-num">6</div>
            <span class="step-text">Analisis dan Penyimpanan Data</span>
          </div>
          <div class="step-item">
            <div class="step-num">7</div>
            <span class="step-text">Penerimaan dan Feedback</span>
          </div>
          <button class="btn-peta" id="btnPeta" onclick="togglePeta()">
            <div class="btn-peta-icon" id="btnPetaIcon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
            </div>
            <span id="btnPetaText">Lihat Peta</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CARA KERJA PETA (FLOWCHART) - VERSI 25 -->
<section class="ck-peta" id="cara-kerja-peta">
  <div class="ck-peta-waves">
    <svg style="top:10%;height:200px;animation:waveMove1 20s linear infinite" viewBox="0 0 1440 120" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,0.3)" d="M0,60 C240,120 480,0 720,60 C960,120 1200,0 1440,60 L1440,120 L0,120 Z"/>
    </svg>
    <svg style="top:30%;height:180px;animation:waveMove2 15s linear infinite" viewBox="0 0 1440 120" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,0.25)" d="M0,40 C360,100 720,20 1080,80 C1260,100 1380,60 1440,50 L1440,120 L0,120 Z"/>
    </svg>
    <svg style="bottom:5%;height:220px;animation:waveMove3 18s linear infinite" viewBox="0 0 1440 120" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,0.2)" d="M0,80 C240,20 480,100 720,60 C960,20 1200,100 1440,60 L1440,120 L0,120 Z"/>
    </svg>
  </div>
  <div class="ck-peta-glow1"></div>
  <div class="ck-peta-glow2"></div>

  <div class="ck-peta-inner">
    <div class="ck-peta-header">
      <p>Alur Sistem</p>
      <h2>Cara Kerja <span>Q.ven</span></h2>
      <p class="sub">Hover atau tap pada setiap node untuk melihat detail proses</p>
    </div>

    <div class="flowchart" id="flowchart">
      <svg class="lines" viewBox="0 0 100 100" preserveAspectRatio="none" id="flowLines"></svg>
      <div id="flowNodes"></div>
    </div>
  </div>
</section>

<!-- TEKNOLOGI -->
<section class="tech-section" id="teknologi">
  <div class="tech-header">
    <p>Stack</p>
    <h2>Teknologi Yang Digunakan <span>Q.ven</span></h2>
  </div>
  <div class="tech-marquee-container">
    <div class="tech-marquee-wrapper">
      <div class="tech-marquee-track tech-track-a" id="techTrackA"></div>
      <div class="tech-marquee-track tech-track-b" id="techTrackB"></div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <p>&copy; 2026 <strong>Q.ven</strong> — Sistem Pengawasan Makanan Bergizi Gratis. All rights reserved.</p>
</footer>

<script>
/* ===================== NAVBAR ===================== */
function toggleMenu() {
  document.getElementById('hamburger').classList.toggle('open');
  document.getElementById('mobileMenu').classList.toggle('open');
}
function closeMenu() {
  document.getElementById('hamburger').classList.remove('open');
  document.getElementById('mobileMenu').classList.remove('open');
}

/* ===================== THEME TOGGLE ===================== */
function applyTheme(theme) {
  if (theme === 'dark') document.documentElement.classList.add('dark');
  else document.documentElement.classList.remove('dark');
  const btn = document.getElementById('themeToggle');
  if (btn) btn.textContent = theme === 'dark' ? '☀️' : '🌙';
  try { localStorage.setItem('theme', theme); } catch(e){}
}

function toggleTheme() {
  const isDark = document.documentElement.classList.contains('dark');
  applyTheme(isDark ? 'light' : 'dark');
}

document.addEventListener('DOMContentLoaded', () => {
  const saved = (function(){ try { return localStorage.getItem('theme'); } catch(e){ return null; } })();
  if (saved) applyTheme(saved);
  else {
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(prefersDark ? 'dark' : 'light');
  }
  const tbtn = document.getElementById('themeToggle'); if (tbtn) tbtn.addEventListener('click', toggleTheme);
});

/* ===================== ORBIT ROTATION ===================== */
(function() {
  const orbitContainer = document.getElementById('orbitContainer');
  const orbitCenter = document.getElementById('orbitCenter');
  if (!orbitContainer) return;

  const baseAngles = [0, 51, 103, 154, 206, 257, 309];
  let baseRadii = [];
  let rotation = 0, isHovering = false;

  // Compute radii based on orbitContainer size so orbit adapts to viewport
  function computeRadii() {
    const rect = orbitContainer.getBoundingClientRect();
    const size = Math.min(rect.width, rect.height) || 400;
    const outer = Math.round(size * 0.47);
    const inner = Math.round(size * 0.31);
    baseRadii = [outer, inner, outer, inner, outer, inner, outer];
    // update existing nodes if any
    const nodes = orbitContainer.querySelectorAll('.orbit-node');
    nodes.forEach((node, i) => {
      const angle = baseAngles[i];
      const r = baseRadii[i] || outer;
      node.style.transform = 'rotate(' + angle + 'deg) translateX(' + r + 'px) rotate(' + (-angle) + 'deg)';
    });
  }

  orbitContainer.addEventListener('mouseenter', () => { isHovering = true; });
  orbitContainer.addEventListener('mouseleave', () => { isHovering = false; });

  // Create nodes (compute radii first)
  computeRadii();
  baseAngles.forEach((angle, i) => {
    const node = document.createElement('div');
    node.className = 'orbit-node';
    const delays = ['0s','0.3s','0.6s','0.9s','1.2s','1.5s','1.8s'];
    const r = baseRadii[i] || Math.round((orbitContainer.offsetWidth || 400) * 0.4);
    node.style.transform = 'rotate(' + angle + 'deg) translateX(' + r + 'px) rotate(' + (-angle) + 'deg)';
    node.style.animationDelay = delays[i];
    node.innerHTML = '<svg width="20" height="20" viewBox="0 0 64 64" fill="none" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"><line x1="20" y1="8" x2="20" y2="24"/><line x1="28" y1="8" x2="28" y2="24"/><line x1="36" y1="8" x2="36" y2="24"/><path d="M16 24 Q28 30 40 24" fill="none"/><line x1="28" y1="28" x2="28" y2="56"/><ellipse cx="50" cy="22" rx="6" ry="9"/><line x1="50" y1="31" x2="50" y2="56"/></svg>';
    orbitContainer.appendChild(node);
  });

  // Recompute radii and update node positions on window resize (debounced)
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      computeRadii();
    }, 120);
  });

  function rotateOrbit() {
    if (!isHovering) {
      rotation += 0.08;
      orbitContainer.style.transform = 'rotate(' + rotation + 'deg)';
      const nodes = orbitContainer.querySelectorAll('.orbit-node');
      nodes.forEach((node, i) => {
        node.style.transform = 'rotate(' + baseAngles[i] + 'deg) translateX(' + baseRadii[i] + 'px) rotate(' + (-baseAngles[i] - rotation) + 'deg)';
      });
      if (orbitCenter) orbitCenter.style.transform = 'translate(-50%, -50%) rotate(' + (-rotation) + 'deg)';
    }
    requestAnimationFrame(rotateOrbit);
  }
  rotateOrbit();
})();

/* ===================== FITUR MARQUEE + FOCAL ZOOM ===================== */
(function() {
  const SET = ['{{ asset('landing/public/fitur-pengawasan.png') }}', '{{ asset('landing/public/fitur-analisis.png') }}', '{{ asset('landing/public/fitur-integritas.png') }}'];

  function makeCard(src, idx) {
    return '<div class="fitur-card fitur-card-item" data-set-idx="' + idx + '">' +
      '<img src="' + src + '" alt="Fitur" loading="lazy" draggable="false">' +
      '</div>';
  }

  const trackWrapper = document.querySelector('.marquee-track-wrapper');
  
  // Membuat duplikasi gambar agar cukup panjang untuk animasi berjalan
  let cardsHtml = '';
  // Kita loop 4 kali (SET x 4) agar panjang elemen cukup untuk animasi 50%
  for (let i = 0; i < 4; i++) {
    SET.forEach((src, j) => { cardsHtml += makeCard(src, j); });
  }
  
  // Masukkan semua card ke dalam 1 track panjang
  trackWrapper.innerHTML = '<div class="marquee-track">' + cardsHtml + '</div>';

  // Logika efek Zoom (Focal Zoom)
  let raf;
  function tick() {
    const all = document.querySelectorAll('.fitur-card-item');
    if (!all.length) { raf = requestAnimationFrame(tick); return; }
    
    // Cari titik tengah horizontal dari layar
    const cx = window.innerWidth / 2;
    
    // Cari elemen mana yang paling dekat dengan titik tengah
    let ci = 0, cd = Infinity;
    all.forEach((c, i) => {
      const rect = c.getBoundingClientRect();
      const centerOfCard = rect.left + (rect.width / 2);
      const d = Math.abs(centerOfCard - cx);
      
      if (d < cd) { 
        cd = d; 
        ci = i; 
      }
    });
    
    // Berikan efek membesar dan full warna ke elemen yang di tengah
    all.forEach((c, i) => {
      const isCenter = (i === ci);
      c.style.transform = 'scale(' + (isCenter ? 1.06 : 0.88) + ')';
      c.style.opacity = isCenter ? '1' : '0.5';
    });
    
    raf = requestAnimationFrame(tick);
  }
  
  if (window.innerWidth > 768) raf = requestAnimationFrame(tick);
})();

/* ===================== TEKNOLOGI MARQUEE + FOCAL ZOOM ===================== */
(function() {
  // TODO (PHP): Render data teknologi dari server (nama, deskripsi, src).
  // Bisa di-echo langsung sebagai JSON atau dipanggil lewat endpoint API.
  const SET = [
    { name: 'TensorFlow', desc: 'Framework ML untuk model prediksi', src: 'https://img.icons8.com/color/1200/tensorflow.jpg' },
    { name: 'PyTorch', desc: 'Deep learning untuk analisis data', src: 'https://img.icons8.com/fluency/1200/pytorch.png' },
    { name: 'Laravel', desc: 'Backend framework PHP', src: 'https://img.icons8.com/fluency/1200/laravel.png' },
    { name: 'Hyperledger Fabric', desc: 'Blockchain untuk transparansi', src: 'https://products.containerize.com/id/blockchain-platforms/hyperledger-fabric/menu_image.png' },
    { name: 'Pandas', desc: 'Data manipulation & analysis', src: 'https://img.icons8.com/color/1200/pandas.jpg' },
  ];
  const CARD_W = 280, GAP = 20, SET_W = (CARD_W + GAP) * SET.length;

  function makeTechCard(tech, idx) {
    return '<div class="tech-card tech-card-item" data-set-idx="' + idx + '">' +
      '<div class="tech-card-img"><img src="' + tech.src + '" alt="' + tech.name + '" loading="lazy" onerror="this.style.display=\'none\'" draggable="false"></div>' +
      '<div class="tech-card-info"><h4>' + tech.name + '</h4><p>' + tech.desc + '</p></div>' +
      '</div>';
  }

  const trackA = document.getElementById('techTrackA');
  const trackB = document.getElementById('techTrackB');
  let cardsHtml = '';
  SET.forEach((tech, i) => { cardsHtml += makeTechCard(tech, i); });
  trackA.innerHTML = cardsHtml;
  trackB.innerHTML = cardsHtml;

  let raf;
  function tick() {
    const all = document.querySelectorAll('.tech-card-item');
    if (!all.length) { raf = requestAnimationFrame(tick); return; }
    const cx = window.innerWidth / 2;
    let ci = 0, cd = Infinity;
    all.forEach((c, i) => {
      const d = Math.abs((c.getBoundingClientRect().left + c.offsetWidth / 2) - cx);
      if (d < cd) { cd = d; ci = i; }
    });
    const mi = ci % SET.length;
    all.forEach((c, i) => {
      const isCenter = (i % SET.length) === mi;
      c.style.transform = 'scale(' + (isCenter ? 1.06 : 0.88) + ')';
      c.style.opacity = isCenter ? '1' : '0.5';
    });
    raf = requestAnimationFrame(tick);
  }
  // Only run the focal-zoom animation on larger screens to save CPU on phones
  if (window.innerWidth > 768) raf = requestAnimationFrame(tick);
})();

/* ===================== CARA KERJA PETA TOGGLE ===================== */
let petaVisible = false;
function togglePeta() {
  petaVisible = !petaVisible;
  const peta = document.getElementById('cara-kerja-peta');
  const icon = document.getElementById('btnPetaIcon');
  const text = document.getElementById('btnPetaText');

  if (petaVisible) {
    peta.classList.add('open');
    icon.classList.add('open');
    text.textContent = 'Sembunyikan Peta';
    setTimeout(() => { initFlowchart(); }, 100);
    setTimeout(() => { document.getElementById('cara-kerja-peta').scrollIntoView({behavior: 'smooth'}); }, 150);
  } else {
    peta.classList.remove('open');
    icon.classList.remove('open');
    text.textContent = 'Lihat Peta';
  }
}

/* ===================== FLOWCHART (VERSI 25) ===================== */
let flowchartInited = false;
function initFlowchart() {
  if (flowchartInited) {
    // Just re-trigger animations
    showFlowchartAnim();
    return;
  }
  flowchartInited = true;

  const icons = {
    'log-in': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>',
    'user-plus': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>',
    'user-cog': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><circle cx="19" cy="11" r="2"/><path d="M19 8v1"/><path d="M19 13v1"/><path d="M21.6 9.5l-.8.8"/><path d="M17.2 9.5l.8.8"/></svg>',
    'store': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M9 21v-6h6v6"/><path d="M10 9h4"/><path d="M10 13h4"/></svg>',
    'database': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg>',
    'truck': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'bar-chart-3': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>',
    'message-square': '<svg viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
  };

  const flowSteps = [
    { id: 1, title: 'Akses Sistem', desc: 'Pengguna mengakses sistem Q.ven melalui aplikasi web.', icon: 'log-in', x: 6, y: 50 },
    { id: 2, title: 'Registrasi', desc: 'Pengguna melakukan registrasi akun atau login ke sistem.', icon: 'user-plus', x: 18, y: 20 },
    { id: 3, title: 'Role Check', desc: 'Sistem mengidentifikasi role pengguna (Admin, Vendor, atau Penerima).', icon: 'user-cog', x: 32, y: 50 },
    { id: 4, title: 'Kelola Vendor', desc: 'Admin melakukan pengelolaan vendor makanan yang bergabung.', icon: 'store', x: 44, y: 20 },
    { id: 5, title: 'Data Admin', desc: 'Admin vendor mengelola data distribusi dan stok makanan.', icon: 'database', x: 56, y: 50 },
    { id: 6, title: 'Distribusi', desc: 'Vendor mendistribusikan makanan sesuai jadwal dan lokasi.', icon: 'truck', x: 68, y: 20 },
    { id: 7, title: 'Blockchain', desc: 'Sistem menganalisis data dan menyimpannya di blockchain.', icon: 'bar-chart-3', x: 80, y: 50 },
    { id: 8, title: 'Feedback', desc: 'Penerima manfaat memberikan feedback terhadap kualitas makanan.', icon: 'message-square', x: 94, y: 35 },
  ];

  const linesSvg = document.getElementById('flowLines');
  const nodesContainer = document.getElementById('flowNodes');

  // Draw lines
  let linesHtml = '';
  for (let i = 0; i < flowSteps.length - 1; i++) {
    const from = flowSteps[i];
    const to = flowSteps[i + 1];
    linesHtml += '<line x1="' + from.x + '%" y1="' + from.y + '%" x2="' + to.x + '%" y2="' + to.y + '%" data-idx="' + i + '"/>';
  }
  linesSvg.innerHTML = linesHtml;

  // Draw nodes
  let nodesHtml = '';
  flowSteps.forEach((step, i) => {
    const isBottom = i % 2 === 0;
    nodesHtml +=
      '<div class="flow-node-wrap" style="left:' + step.x + '%;top:' + step.y + '%;" data-idx="' + i + '">' +
        '<div class="flow-node-group">' +
          '<div class="flow-node-circle">' +
            '<div class="inner">' + icons[step.icon] + '</div>' +
          '</div>' +
          '<div class="flow-tooltip tooltip-' + (isBottom ? 'bottom' : 'top') + '">' +
            '<div class="tt-title">' + step.title + '</div>' +
            '<div class="tt-desc">' + step.desc + '</div>' +
          '</div>' +
        '</div>' +
        '<div class="flow-label">' + step.title + '</div>' +
      '</div>';
  });
  nodesContainer.innerHTML = nodesHtml;

  showFlowchartAnim();
}

function showFlowchartAnim() {
  // Trigger line animations
  setTimeout(() => {
    document.querySelectorAll('.flowchart svg.lines line').forEach((line, i) => {
      setTimeout(() => { line.classList.add('visible'); }, 500 + i * 250);
    });
  }, 100);

  // Trigger node animations
  document.querySelectorAll('.flow-node-wrap').forEach((node, i) => {
    setTimeout(() => { node.classList.add('visible'); }, i * 200);
  });
}

/* ===================== SCROLL REVEAL ===================== */
(function() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('visible');
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal, .fitur-header, .tech-header, .ck-header, .ck-peta-header, .steps-wrap').forEach(el => {
    el.classList.add('reveal');
    observer.observe(el);
  });
})();
// Close mobile menu automatically when switching to desktop width
window.addEventListener('resize', function() {
  if (window.innerWidth > 768) closeMenu();
});
</script>

</body>
</html>