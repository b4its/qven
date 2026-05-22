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
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
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
    .hamburger { display: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 4px; z-index: 1001; }
    .hamburger span {
      display: block; width: 24px; height: 2px; border-radius: 2px;
      background: #fff; transition: all 0.3s;
    }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }
    
    .mobile-menu {
      display: none; position: fixed; top: 64px; left: 0; right: 0;
      z-index: 999; flex-direction: column; gap: 4px;
      padding: 20px 5% 28px;
      background: rgba(37, 99, 235, 0.98);
      backdrop-filter: blur(10px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .mobile-menu.open { display: flex; animation: slideDown 0.3s ease forwards; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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
      max-width: 1200px; margin: 0 auto; padding: 100px 5% 80px;
      display: flex; align-items: center; gap: 32px;
    }
    .hero-left { flex: 1; max-width: 480px; }
    .hero-left h1 {
      color: #fff; line-height: 1.25; margin-bottom: 20px;
      font-size: clamp(1.6rem, 3.5vw, 2.6rem); font-weight: 500;
    }
    .hero-left h1 strong { font-weight: 800; }
    .hero-left p { font-size: clamp(0.8rem, 2vw, 1rem); margin-bottom: 32px; line-height: 1.6; color: rgba(255,255,255,0.85); }
    .hero-buttons { display: flex; gap: 16px; flex-wrap: wrap; }
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
    .fitur-header { text-align: center; margin-bottom: 48px; position: relative; z-index: 10; padding: 0 5%; }
    .fitur-header p { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #2563eb; margin-bottom: 8px; }
    .fitur-header h2 { font-weight: 800; font-size: clamp(1.4rem, 2.5vw, 2rem); color: #1e293b; }
    .fitur-header h2 span { color: #2563eb; }
    .marquee-container { position: relative; z-index: 10; height: 476px; padding: 20px 0; }
    .marquee-track-wrapper { position: absolute; inset: 0; display: flex; gap: 24px; }
    .marquee-track {
      display: flex; gap: 24px; position: absolute;
      will-change: transform;
    }
    .marquee-track-a { left: 0; animation: marqueeA 16s linear infinite; }
    .marquee-track-b { left: 1092px; animation: marqueeB 16s linear infinite; }
    .fitur-card {
      flex: none; width: 340px; border-radius: 16px; overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: transform 0.5s cubic-bezier(0.4,0,0.2,1), opacity 0.5s cubic-bezier(0.4,0,0.2,1);
      will-change: transform, opacity;
    }
    .fitur-card img { width: 100%; height: auto; display: block; user-select: none; pointer-events: none; }
    @keyframes marqueeA { 0% { transform: translateX(0); } 100% { transform: translateX(-1092px); } }
    @keyframes marqueeB { 0% { transform: translateX(0); } 100% { transform: translateX(-1092px); } }

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
    .ck-header h2 { font-weight: 800; font-size: clamp(1.6rem, 3.5vw, 2.5rem); letter-spacing: -0.02em; color: #0f172a; }
    .ck-header h2 span { color: #1d4ed8; }
    .ck-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
    
    /* Orbit */
    .orbit-wrap { display: flex; justify-content: center; align-items: center; width: 100%; }
    .orbit-container { position: relative; width: 500px; height: 500px; transition: transform 0.3s ease; }
    .orbit-ring-outer {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 470px; height: 470px; border-radius: 50%;
      border: 1.5px solid rgba(29, 78, 216, 0.15);
    }
    .orbit-ring-inner {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 310px; height: 310px; border-radius: 50%;
      border: 1.5px solid rgba(29, 78, 216, 0.10);
    }
    .orbit-center {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
      width: 130px; height: 130px; border-radius: 50%;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      box-shadow: 0 0 0 16px rgba(59, 130, 246, 0.10), 0 0 0 32px rgba(59, 130, 246, 0.05);
      display: flex; align-items: center; justify-content: center; z-index: 10;
    }
    .orbit-node {
      position: absolute; top: 50%; left: 50%;
      width: 46px; height: 46px; margin-top: -23px; margin-left: -23px;
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
      width: 100%;
    }
    .btn-peta:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
    .btn-peta-icon {
      width: 40px; height: 40px; min-width: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: rgba(255,255,255,0.2);
    }
    .btn-peta-icon svg { transition: transform 0.3s; }
    .btn-peta-icon.open svg { transform: rotate(180deg); }

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
    .ck-peta-header { text-align: center; margin-bottom: 64px; }
    .ck-peta-header p { font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.7); margin-bottom: 8px; }
    .ck-peta-header h2 { font-weight: 800; font-size: clamp(1.6rem, 3vw, 2.4rem); color: #fff; }
    .ck-peta-header h2 span { color: #93c5fd; background:#07284d; padding:8px; border-radius:8px }
    .ck-peta-header .sub { font-size: 0.88rem; margin-top: 8px; color: rgba(255,255,255,0.75); }
    
    /* Horizontal Scroll Wrapper untuk Flowchart di Mobile */
    .flowchart-wrapper {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch; /* Smooth scroll di iOS */
      padding-bottom: 40px; /* Space untuk tooltip bawah */
    }
    .flowchart-wrapper::-webkit-scrollbar { height: 6px; }
    .flowchart-wrapper::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .flowchart-wrapper::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px; }

    .flowchart { position: relative; height: clamp(350px, 45vw, 500px); min-width: 800px; }
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
      width: 60px; height: 60px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer;
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      border: 3px solid rgba(255,255,255,0.6);
      box-shadow: 0 10px 20px rgba(0,0,0,0.3);
      transition: transform 0.3s;
    }
    .flow-node-circle:hover { transform: scale(1.1); }
    .flow-node-circle .inner {
      width: 40px; height: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      background: white;
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
    .tech-header { text-align: center; margin-bottom: 48px; position: relative; z-index: 10; padding: 0 5%; }
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

    /* ── RESPONSIVE MEDIA QUERIES ── */
    @media (max-width: 1024px) {
      .navbar .nav-links, .navbar .nav-actions { display: none; }
      .hamburger { display: flex; }
      .ck-grid { grid-template-columns: 1fr; gap: 48px; }
      .hero-inner { flex-direction: column; text-align: center; gap: 40px; padding-top: 120px; }
      .hero-left { max-width: 100%; }
      .hero-buttons { justify-content: center; }
      .hero-right { max-width: 420px; }
      .orbit-container { transform: scale(0.85); }
    }
    @media (max-width: 768px) {
      .orbit-container { transform: scale(0.65); }
      .fitur-card { width: 300px; }
      .marquee-container { height: 420px; }
      .ck-header h2 { font-size: 2rem; }
    }
    @media (max-width: 640px) {
      .steps-grid { grid-template-columns: 1fr; }
      .orbit-container { transform: scale(0.55); }
      .marquee-container { height: 380px; }
      .fitur-card { width: 280px; }
      .hero-left h1 { font-size: 1.8rem; }
    }
    @media (max-width: 480px) {
      .hero-buttons { flex-direction: column; width: 100%; }
      .hero-buttons button { width: 100%; }
      .orbit-container { transform: scale(0.45); }
      .ck-overview { padding: 64px 5%; }
      .tech-section { padding: 64px 0; }
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
    <img src="logo-qven.png" alt="Q.ven">
  </div>
  <ul class="nav-links">
    <li><a href="#hero">Dashboard</a></li>
    <li><a href="#fitur">Fitur</a></li>
    <li><a href="#cara-kerja">Cara Kerja</a></li>
    <li><a href="#teknologi">Teknologi</a></li>
  </ul>
  <div class="nav-actions">
    <a href="{{ route('auth.login.index') }}">Log In</a>
    <button class="btn-signup">Sign Up</button>
  </div>
  <div class="hamburger" id="hamburger" onclick="toggleMenu()">
    <span></span><span></span><span></span>
  </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
  <a href="#hero" onclick="closeMenu()">Dashboard</a>
  <a href="#fitur" onclick="closeMenu()">Fitur</a>
  <a href="#cara-kerja" onclick="closeMenu()">Cara Kerja</a>
  <a href="#teknologi" onclick="closeMenu()">Teknologi</a>
  <div class="mobile-actions">
    <a href="#" class="btn-login">Log In</a>
    <a href="#" class="btn-signup-mobile">Sign Up</a>
  </div>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-inner">
    <div class="hero-left">
      <h1>Memastikan kualitas gizi serta <strong>Kelayakan Vendor</strong> dengan <strong>Machine Learning</strong> dan Transparansi <strong>Blockchain</strong></h1>
      <p>Quality Vendor and Nutrition<br>Makan Bergizi Gratis</p>
      <div class="hero-buttons">
        <button class="btn-primary">Coba Sekarang</button>
        <button class="btn-secondary">Sign Up</button>
      </div>
    </div>
    <div class="hero-right">
      <img src="{{ asset('landing/food-tray.png') }}" alt="Nampan Makanan Bergizi">
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
      <div class="marquee-track marquee-track-a" id="fiturTrackA"></div>
      <div class="marquee-track marquee-track-b" id="fiturTrackB"></div>
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
          <div class="orbit-center" id="orbitCenter" >
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
            <span class="step-text" >Akses Sistem</span>
          </div>
          <div class="step-item">
            <div class="step-num">5</div>
            <span class="step-text">Pengelolaan Data oleh Admin Vendor</span>
          </div>
          <div class="step-item">
            <div class="step-num">2</div>
            <span class="step-text">Registrasi dan Login</span>
          </div>
          <div class="step-item">
            <div class="step-num">6</div>
            <span class="step-text">Analisis dan Penyimpanan Data</span>
          </div>
          <div class="step-item">
            <div class="step-num">3</div>
            <span class="step-text">Identifikasi Role Pengguna</span>
          </div>
          <div class="step-item">
            <div class="step-num">7</div>
            <span class="step-text">Penerimaan dan Feedback</span>
          </div>
          <div class="step-item">
            <div class="step-num">4</div>
            <span class="step-text">Pengelolaan Vendor</span>
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
    
    <!-- DIBUNGKUS WRAPPER AGAR BISA HORIZONTAL SCROLL DI MOBILE -->
    <div class="flowchart-wrapper">
        <div class="flowchart" id="flowchart">
          <svg class="lines" viewBox="0 0 100 100" preserveAspectRatio="none" id="flowLines"></svg>
          <div id="flowNodes"></div>
        </div>
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

/* ===================== ORBIT ROTATION ===================== */
(function() {
  const orbitContainer = document.getElementById('orbitContainer');
  const orbitCenter = document.getElementById('orbitCenter');
  if (!orbitContainer) return;
  const baseAngles = [0, 51, 103, 154, 206, 257, 309];
  const baseRadii = [155, 235, 155, 235, 155, 235, 155];
  let rotation = 0, isHovering = false;
  orbitContainer.addEventListener('mouseenter', () => { isHovering = true; });
  orbitContainer.addEventListener('mouseleave', () => { isHovering = false; });
  
  // Create nodes
  baseAngles.forEach((angle, i) => {
    const node = document.createElement('div');
    node.className = 'orbit-node';
    const delays = ['0s','0.3s','0.6s','0.9s','1.2s','1.5s','1.8s'];
    node.style.transform = 'rotate(' + angle + 'deg) translateX(' + baseRadii[i] + 'px) rotate(' + (-angle) + 'deg)';
    node.style.animationDelay = delays[i];
    node.innerHTML = '<svg width="20" height="20" viewBox="0 0 64 64" fill="none" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"><line x1="20" y1="8" x2="20" y2="24"/><line x1="28" y1="8" x2="28" y2="24"/><line x1="36" y1="8" x2="36" y2="24"/><path d="M16 24 Q28 30 40 24" fill="none"/><line x1="28" y1="28" x2="28" y2="56"/><ellipse cx="50" cy="22" rx="6" ry="9"/><line x1="50" y1="31" x2="50" y2="56"/></svg>';
    orbitContainer.appendChild(node);
  });
  function rotateOrbit() {
    if (!isHovering) {
      rotation += 0.08;
      // Rotasi ditangani JS, scaling diatur secara dinamis via CSS Transform Media Query
      orbitContainer.style.transform = `scale(var(--scale-factor, 1)) rotate(${rotation}deg)`;
      const nodes = orbitContainer.querySelectorAll('.orbit-node');
      nodes.forEach((node, i) => {
        node.style.transform = 'rotate(' + baseAngles[i] + 'deg) translateX(' + baseRadii[i] + 'px) rotate(' + (-baseAngles[i] - rotation) + 'deg)';
      });
      if (orbitCenter) orbitCenter.style.transform = 'translate(-50%, -50%) rotate(' + (-rotation) + 'deg)';
    }
    requestAnimationFrame(rotateOrbit);
  }
  
  // Update CSS custom property for responsive scaling without breaking the animation logic
  function setScaleFactor() {
      const width = window.innerWidth;
      let scale = 1;
      if (width <= 480) scale = 0.45;
      else if (width <= 640) scale = 0.55;
      else if (width <= 768) scale = 0.65;
      else if (width <= 1024) scale = 0.85;
      orbitContainer.style.setProperty('--scale-factor', scale);
  }
  window.addEventListener('resize', setScaleFactor);
  setScaleFactor();
  rotateOrbit();
})();

/* ===================== FITUR MARQUEE + FOCAL ZOOM ===================== */
(function() {
  const SET = ['{{ asset('landing/public/fitur-pengawasan.png') }}', '{{ asset('landing/public/fitur-analisis.png') }}', '{{ asset('landing/public/fitur-integritas.png') }}'];
  const CARD_W = 340, GAP = 24;
  function makeCard(src, idx, totalIdx) {
    return '<div class="fitur-card fitur-card-item" data-set-idx="' + idx + '">' +
      '<img src="' + src + '" alt="Fitur" loading="lazy" draggable="false">' +
      '</div>';
  }
  const trackA = document.getElementById('fiturTrackA');
  const trackB = document.getElementById('fiturTrackB');
  let cardsHtml = '';
  SET.forEach((src, i) => { cardsHtml += makeCard(src, i, i); });
  trackA.innerHTML = cardsHtml;
  trackB.innerHTML = cardsHtml;
  
  let raf;
  function tick() {
    const all = document.querySelectorAll('.fitur-card-item');
    if (!all.length) { raf = requestAnimationFrame(tick); return; }
    const cx = window.innerWidth / 2;
    let ci = 0, cd = Infinity;
    all.forEach((c, i) => {
      const r = c.getBoundingClientRect();
      const d = Math.abs((r.left + r.width / 2) - cx);
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
  raf = requestAnimationFrame(tick);
})();

/* ===================== TEKNOLOGI MARQUEE + FOCAL ZOOM ===================== */
(function() {
  const SET = [
    { name: 'TensorFlow', desc: 'Framework ML untuk model prediksi', src: 'https://img.icons8.com/color/1200/tensorflow.jpg' },
    { name: 'PyTorch', desc: 'Deep learning untuk analisis data', src: 'https://img.icons8.com/fluency/1200/pytorch.png' },
    { name: 'Laravel', desc: 'Backend framework PHP', src: 'https://img.icons8.com/fluency/1200/laravel.png' },
    { name: 'Hyperledger Fabric', desc: 'Blockchain untuk transparansi', src: 'https://products.containerize.com/id/blockchain-platforms/hyperledger-fabric/menu_image.png' },
    { name: 'Pandas', desc: 'Data manipulation & analysis', src: 'https://img.icons8.com/color/1200/pandas.jpg' },
  ];
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
  raf = requestAnimationFrame(tick);
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

/* ===================== FLOWCHART ===================== */
let flowchartInited = false;
function initFlowchart() {
  if (flowchartInited) {
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
  
  let linesHtml = '';
  for (let i = 0; i < flowSteps.length - 1; i++) {
    const from = flowSteps[i];
    const to = flowSteps[i + 1];
    linesHtml += '<line x1="' + from.x + '%" y1="' + from.y + '%" x2="' + to.x + '%" y2="' + to.y + '%" data-idx="' + i + '"/>';
  }
  linesSvg.innerHTML = linesHtml;
  
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
  setTimeout(() => {
    document.querySelectorAll('.flowchart svg.lines line').forEach((line, i) => {
      setTimeout(() => { line.classList.add('visible'); }, 500 + i * 250);
    });
  }, 100);
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
</script>
</body>
</html>