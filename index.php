<?php
?>
<?php
// ================================================
// index.php - Page principale Milano Studio
// ================================================
require_once __DIR__ . '/php/config.php';
$success  = isset($_GET['success']);
$paid     = isset($_GET['paid']);
$error    = $_GET['error'] ?? '';
$ret_res_id = (int)($_GET['res_id'] ?? 0);
$updated    = isset($_GET['updated']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Milano Studio — Photography</title>
    <link rel="icon" type="image/jpeg" href="faa.png">
    <link rel="shortcut icon" type="image/jpeg" href="faa.png">
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script>emailjs.init('88HKnv1yOYXH7EY6e');</script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:wght@300;400;500&family=Montserrat:wght@300;400;500;600&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --red: #E31E24;
            --red-dark: #b81519;
            --red-light: rgba(227,30,36,0.1);
            --gold: #E31E24;
            --gold-light: #ff6b6e;
            --gold-dim: rgba(227,30,36,0.08);
            --dark: #0f0f0f;
            --dark-2: #1a1a1a;
            --dark-3: #222;
            --white: #ffffff;
            --off-white: #f8f8f8;
            --gray: #777;
            --gray-light: #444;
            --text: #1a1a1a;
            --text-light: #555;
            --border: rgba(0,0,0,0.1);
            --border-red: rgba(227,30,36,0.25);
        }

        html { scroll-behavior: smooth; background: var(--white); }
        body { background: var(--white); color: var(--text); font-family: 'Cormorant Garamond', serif; font-size: 18px; line-height: 1.7; overflow-x: hidden; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: var(--red); border-radius: 2px; }

        .page-wrapper { max-width: 1200px; margin: 0 auto; }

        /* NOTIF */
        .notif { position: fixed; top: 90px; right: 24px; z-index: 9999; padding: 16px 24px; border-radius: 6px; font-family: 'Montserrat', sans-serif; font-size: 0.78rem; letter-spacing: 0.1em; max-width: 360px; animation: slideIn 0.4s ease both; backdrop-filter: blur(12px); }
        .notif-success { background: rgba(76,175,125,0.12); border: 1px solid rgba(76,175,125,0.4); color: #4caf7d; }
        .notif-error   { background: rgba(224,92,92,0.12);  border: 1px solid rgba(224,92,92,0.4);  color: #e05c5c; }
        @keyframes slideIn { from { opacity:0; transform: translateX(24px); } to { opacity:1; transform: translateX(0); } }

        /* HEADER */
        header { position: fixed; top: 0; left: 0; width: 100%; z-index: 999; display: flex; align-items: center; justify-content: space-between; padding: 14px 60px; background: rgba(255,255,255,0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.08); gap: 20px; transition: background 0.4s, box-shadow 0.4s; }
        header.scrolled { background: #fff; box-shadow: 0 2px 20px rgba(0,0,0,0.1); }

        .header-logo { display: flex; align-items: center; text-decoration: none; flex-shrink: 0; }
        .header-logo img { height: 52px; width: auto; object-fit: contain; transition: transform 0.3s; }
        .header-logo img:hover { transform: scale(1.04); }

        nav { display: flex; gap: 36px; flex: 1; justify-content: center; }
        nav a { font-family: 'Dancing Script', cursive; font-size: 1.15rem; font-weight: 700; letter-spacing: 0.04em; color: var(--text); text-decoration: none; position: relative; padding-bottom: 4px; transition: color 0.3s; }
        nav a::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 0; height: 2px; background: var(--red); transition: width 0.35s ease; }
        nav a:hover { color: var(--red); }
        nav a:hover::after { width: 100%; }

        .header-right { display: flex; align-items: center; gap: 12px; }
        .lang { display: flex; gap: 4px; }
        .lang button { background: transparent; border: 1px solid rgba(0,0,0,0.15); color: var(--gray); font-family: 'Montserrat', sans-serif; font-size: 0.62rem; letter-spacing: 0.15em; padding: 5px 9px; cursor: pointer; transition: all 0.3s; text-transform: uppercase; border-radius: 2px; }
        .lang button:hover { border-color: var(--red); color: var(--red); background: var(--red-light); }
       

        /* HERO */
        .hero { min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 120px 40px 100px; position: relative; overflow: hidden; background: #fff; }
        .hero::after { content: ''; position: absolute; inset: 0; background:
            radial-gradient(ellipse 80% 60% at 50% 50%, rgba(227,30,36,0.04) 0%, transparent 70%),
            linear-gradient(135deg, rgba(227,30,36,0.03) 0%, transparent 50%),
            linear-gradient(225deg, rgba(227,30,36,0.03) 0%, transparent 50%);
            z-index: 0; pointer-events: none; }
        section, footer, .page-wrapper { position: relative; z-index: 1; }
        #services, #contact { background: var(--white) !important; }
        .hero > * { position: relative; z-index: 2; }

        .hero-eyebrow { font-family: 'Montserrat', sans-serif; font-size: 0.62rem; letter-spacing: 0.4em; text-transform: uppercase; color: var(--red); margin-bottom: 18px; animation: fadeUp 0.8s ease both; }
        .hero-logo { width: 140px; height: 140px; object-fit: contain; margin-bottom: 16px; animation: fadeUp 0.9s 0.05s ease both; filter: drop-shadow(0 4px 16px rgba(227,30,36,0.12)); }
        .hero h1 { font-family: 'Dancing Script', cursive; font-size: clamp(3.8rem, 11vw, 9rem); font-weight: 700; line-height: 1.05; color: var(--text); animation: fadeUp 1s 0.1s ease both; letter-spacing: 0.01em; position: relative; }
        .hero h1::before { content: 'Milano'; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-family: 'Dancing Script', cursive; font-size: 1.6em; color: rgba(0,0,0,0.12); white-space: nowrap; pointer-events: none; z-index: -1; }
        .hero h1 em { font-style: normal; color: var(--text); }
        .hero p { margin-top: 26px; font-size: 1.05rem; font-weight: 300; color: var(--text-light); letter-spacing: 0.06em; animation: fadeUp 1s 0.25s ease both; }
        .hero-divider { width: 1px; height: 48px; background: linear-gradient(to bottom, rgba(0,0,0,0.25), transparent); margin: 30px auto 0; animation: growLine 1.2s 0.5s ease both; }
        .hero-scroll { position: absolute; bottom: 36px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 8px; animation: fadeUp 1s 1s ease both; cursor: pointer; text-decoration: none; }
        .hero-scroll span { font-family: 'Montserrat', sans-serif; font-size: 0.52rem; letter-spacing: 0.32em; text-transform: uppercase; color: var(--gray); }
        .hero-scroll-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--red); animation: bounce 2s infinite; }

        @keyframes bounce { 0%, 100% { transform: translateY(0); opacity: 1; } 50% { transform: translateY(8px); opacity: 0.35; } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(28px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes growLine { from { height: 0; opacity: 0; } to { height: 48px; opacity: 1; } }

        /* SECTIONS */
        section { padding: 110px 60px; border-top: 1px solid rgba(0,0,0,0.08); }
        section h2 { font-family: 'Dancing Script', cursive; font-size: clamp(2.8rem, 6vw, 5rem); font-weight: 700; color: var(--text); margin-bottom: 72px; position: relative; display: block; text-align: center; letter-spacing: 0.02em; }
        section h2::after { content: ''; position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background: linear-gradient(90deg, transparent, var(--red), transparent); }

        /* SERVICES LIST */
        #services { text-align: center; background: var(--white); }
        #servicesList { list-style: none; display: flex; flex-direction: column; gap: 0; max-width: 1100px; margin: 0 auto; }
        #servicesList > li { padding: 48px 0; border-bottom: 1px solid rgba(0,0,0,0.07); display: flex; flex-direction: column; gap: 32px; }
        .svc-header { display: flex; width: 100%; align-items: center; padding: 0 4px; }
        .svc-name { font-family: 'Dancing Script', cursive; font-size: 2.2rem; font-weight: 700; color: var(--text); }
        .svc-price { margin-left: auto; font-family: 'Cormorant Garamond', serif; font-size: 1rem; font-style: italic; letter-spacing: 0.1em; color: var(--red); border: 1px solid rgba(227,30,36,0.3); padding: 7px 18px; border-radius: 30px; background: rgba(227,30,36,0.05); }

        /* CARDS — gold/olive luxury theme matching reference */
        .wedding-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; width: 100%; }

        .wedding-card {
            background: #fff;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 18px;
            padding: 0;
            display: flex; flex-direction: column;
            overflow: hidden;
            position: relative;
            transition: transform 0.4s ease, box-shadow 0.4s ease, border-color 0.4s;
            box-shadow: 0 6px 28px rgba(0,0,0,0.08);
        }
        .wedding-card:hover {
            transform: translateY(-8px);
            border-color: rgba(180,148,76,0.45);
            box-shadow: 0 28px 60px rgba(180,148,76,0.14), 0 8px 24px rgba(0,0,0,0.08);
        }

        /* No top bar — replaced by centered header block */
        .wedding-card-top { display: none; }

        .wedding-card-body { padding: 32px 24px 20px; display: flex; flex-direction: column; flex: 1; align-items: center; }

        /* Icon circle */
        .wedding-card-icon {
            width: 72px; height: 72px;
            border: 2px solid #b4944c;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
            margin-bottom: 16px;
            background: #fffdf6;
            box-shadow: 0 2px 12px rgba(180,148,76,0.12);
        }

        .wedding-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem; font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
            line-height: 1.1;
            text-align: center;
        }
        .wedding-card-desc {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.55rem; letter-spacing: 0.3em; text-transform: uppercase;
            color: #b4944c;
            margin-bottom: 22px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            text-align: center;
            width: 100%;
        }

        /* Feature rows */
        .pkg-line {
            display: flex; align-items: center; justify-content: space-between;
            padding: 9px 0; border-bottom: 1px solid rgba(0,0,0,0.05); gap: 10px; width: 100%;
        }
        .pkg-line:last-of-type { border-bottom: none; }
        .pkg-line-desc { font-family: 'Cormorant Garamond', serif; font-size: 0.95rem; color: #444; line-height: 1.4; }
        .pkg-line-price { font-family: 'Montserrat', sans-serif; font-size: 0.68rem; font-weight: 700; color: #fff; white-space: nowrap; flex-shrink: 0; background: #b4944c; padding: 4px 10px; border-radius: 30px; letter-spacing: 0.06em; }

        /* CTA Button — gold pill */
        .btn-reserver {
            background: linear-gradient(135deg, #c9a84c, #b4944c, #d4b068);
            border: none;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.62rem; letter-spacing: 0.22em; text-transform: uppercase; font-weight: 600;
            padding: 16px 20px; cursor: pointer; width: 100%;
            transition: all 0.35s; margin-top: 0;
            position: relative; overflow: hidden;
            border-radius: 0 0 18px 18px;
        }
        .btn-reserver::before { content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0.12); opacity: 0; transition: opacity 0.35s; }
        .btn-reserver:hover::before { opacity: 1; }
        .btn-reserver:hover { box-shadow: 0 6px 24px rgba(180,148,76,0.4); letter-spacing: 0.3em; }
        .btn-reserver span { position: relative; z-index: 1; }

        /* CONTACT */
        #contact { text-align: center; background: var(--off-white); }
        .contact-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; max-width: 900px; margin: 0 auto; }
        .contact-card { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 10px; padding: 28px 20px; display: flex; flex-direction: column; align-items: center; gap: 8px; transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s; text-decoration: none; position: relative; overflow: hidden; }
        .contact-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--red); opacity: 0; transition: opacity 0.3s; }
        .contact-card:hover { border-color: rgba(227,30,36,0.3); transform: translateY(-4px); box-shadow: 0 14px 40px rgba(227,30,36,0.1); }
        .contact-card:hover::before { opacity: 1; }
        .contact-card-icon { font-size: 1.7rem; margin-bottom: 4px; display: flex; align-items: center; justify-content: center; }
        .contact-card-icon svg { width: 36px; height: 36px; }
        .contact-card-label { font-family: 'Montserrat', sans-serif; font-size: 0.55rem; letter-spacing: 0.3em; text-transform: uppercase; color: var(--red); }
        .contact-card-value { font-family: 'Cormorant Garamond', serif; font-size: 1rem; color: var(--text); text-decoration: none; text-align: center; word-break: break-all; transition: color 0.3s; line-height: 1.4; }
        .contact-card:hover .contact-card-value { color: var(--red); }

        /* MODAL */
        .select-wrapper { position: relative; margin-bottom: 20px; }
        .select-wrapper select { width: 100%; appearance: none; background: #f8f8f8; border: 1px solid rgba(0,0,0,0.15); color: var(--text); font-family: 'Cormorant Garamond', serif; font-size: 1rem; padding: 12px 16px; cursor: pointer; outline: none; transition: border-color 0.3s; border-radius: 4px; }
        .select-wrapper select:focus { border-color: var(--red); }
        .select-wrapper::after { content: '↓'; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--red); pointer-events: none; font-size: 0.8rem; }
        .select-wrapper select option { background: #fff; color: var(--text); }
        .pkg-radio-label { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1px solid rgba(0,0,0,0.1); cursor: pointer; transition: border-color 0.3s; font-family: 'Montserrat', sans-serif; font-size: 0.75rem; border-radius: 2px; color: var(--text); }
        .pkg-radio-label:hover { border-color: var(--red); }
        .pkg-radio-label input[type="radio"] { accent-color: var(--red); }
        .form-group { margin-bottom: 26px; }
        fieldset { border: 1px solid rgba(0,0,0,0.1); border-radius: 4px; padding: 24px 28px; margin-bottom: 24px; background: #fafafa; }
        legend { font-family: 'Montserrat', sans-serif; font-size: 0.62rem; letter-spacing: 0.25em; text-transform: uppercase; color: var(--red); padding: 0 10px; }
        fieldset label { display: block; font-family: 'Montserrat', sans-serif; font-size: 0.68rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gray); margin-bottom: 8px; }
        .checkbox-row { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; font-family: 'Montserrat', sans-serif; font-size: 0.72rem; letter-spacing: 0.1em; color: var(--text-light); }
        .checkbox-row input[type="checkbox"] { accent-color: var(--red); width: 16px; height: 16px; cursor: pointer; }
        .btn-submit { display: inline-block; background: var(--red); border: 2px solid var(--red); color: #fff; font-family: 'Montserrat', sans-serif; font-size: 0.68rem; letter-spacing: 0.25em; text-transform: uppercase; padding: 14px 36px; cursor: pointer; transition: all 0.35s; border-radius: 2px; }
        .btn-submit:hover { background: var(--red-dark); border-color: var(--red-dark); }

        /* FOOTER */
        footer { padding: 36px 60px; border-top: 1px solid rgba(0,0,0,0.08); display: flex; align-items: center; justify-content: space-between; gap: 20px; background: #fff; }
        footer p { font-family: 'Montserrat', sans-serif; font-size: 0.6rem; letter-spacing: 0.2em; color: #aaa; text-transform: uppercase; }
        .footer-logo img { height: 38px; width: auto; object-fit: contain; opacity: 0.7; transition: opacity 0.3s; }
        .footer-logo img:hover { opacity: 1; }

        /* GALLERY RIBBON — ruban adhésif infini */
        #gallery { text-align: center; padding: 110px 0 60px; border-top: 1px solid rgba(0,0,0,0.08); overflow: hidden; background: var(--off-white); }
        #gallery h2 { margin-bottom: 48px; }
        .ribbon-wrap { width: 100%; overflow: hidden; position: relative; cursor: pointer; }
        .ribbon-wrap::before, .ribbon-wrap::after { content:''; position:absolute; top:0; bottom:0; width:80px; z-index:2; pointer-events:none; }
        .ribbon-wrap::before { left:0; background: linear-gradient(to right, var(--off-white), transparent); }
        .ribbon-wrap::after  { right:0; background: linear-gradient(to left,  var(--off-white), transparent); }
        .ribbon-track { display: flex; gap: 12px; animation: ribbonScroll 40s linear infinite; width: max-content; }
        [dir="rtl"] .ribbon-track { flex-direction: row; }
        .ribbon-track:hover { animation-play-state: paused; }
        @keyframes ribbonScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .ribbon-item { flex: 0 0 320px; height: 420px; overflow: hidden; border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); cursor: pointer; position: relative; transition: border-color 0.35s, transform 0.35s, box-shadow 0.35s; background: #eee; }
        .ribbon-item:hover { border-color: rgba(227,30,36,0.5); transform: scale(1.03); box-shadow: 0 16px 48px rgba(227,30,36,0.15); z-index: 3; }
        .ribbon-item img { width: 100%; height: 100%; object-fit: cover; display: block; filter: brightness(0.92); transition: filter 0.4s; }
        .ribbon-item:hover img { filter: brightness(1); }
        @media (max-width: 768px) { .ribbon-item { flex: 0 0 220px; height: 290px; } }
        #lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.96); z-index: 10000; align-items: center; justify-content: center; flex-direction: column; padding: 20px; }
        #lightbox.open { display: flex; }
        #lightbox img { max-width: 90vw; max-height: 85vh; object-fit: contain; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; }
        .lb-close { position: absolute; top: 20px; right: 28px; background: none; border: none; color: #888; font-size: 2rem; cursor: pointer; line-height: 1; transition: color 0.2s; }
        .lb-close:hover { color: var(--red); }
        .lb-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(227,30,36,0.15); border: 1px solid rgba(227,30,36,0.3); color: var(--red); font-size: 1.5rem; width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 2px; transition: background 0.3s; }
        .lb-nav:hover { background: rgba(227,30,36,0.35); }
        .lb-prev { left: 18px; } .lb-next { right: 18px; }
        .lb-counter { margin-top: 14px; font-family: 'Montserrat', sans-serif; font-size: 0.58rem; letter-spacing: 0.28em; color: #888; text-transform: uppercase; }

        /* CONFIGURATEUR PRIX */
        .cfg-wrap { display: flex; flex-direction: column; gap: 10px; flex: 1; margin-bottom: 16px; }
        .cfg-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 8px 0; border-bottom: 1px solid rgba(0,0,0,0.06); }
        .cfg-row:last-of-type { border-bottom: none; }
        .cfg-fixed { opacity: 0.7; }
        .cfg-label { font-family: 'Cormorant Garamond', serif; font-size: 1rem; color: var(--text); flex: 1; }
        .cfg-check-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .cfg-checkbox { accent-color: #b4944c; width: 16px; height: 16px; cursor: pointer; flex-shrink: 0; }
        .cfg-sub { font-family: 'Montserrat', sans-serif; font-size: 0.6rem; color: #fff; letter-spacing: 0.08em; white-space: nowrap; background: #b4944c; padding: 4px 10px; border-radius: 30px; font-weight: 700; }
        .cfg-extra { color: rgba(180,148,76,0.6); }
        .cfg-badge { font-family: 'Montserrat', sans-serif; font-size: 0.55rem; letter-spacing: 0.12em; color: #3d9970; border: 1px solid rgba(61,153,112,0.35); padding: 4px 10px; border-radius: 20px; background: rgba(61,153,112,0.07); }
        .cfg-toggle { display: flex; gap: 4px; }
        .cfg-btn { background: #f5f5f5; border: 1px solid rgba(0,0,0,0.15); color: var(--text-light); font-family: 'Montserrat', sans-serif; font-size: 0.65rem; width: 32px; height: 28px; cursor: pointer; border-radius: 4px; transition: all 0.25s; }
        .cfg-btn.active { background: #b4944c; border-color: #b4944c; color: #fff; font-weight: 700; }
        .cfg-note { font-family: 'Montserrat', sans-serif; font-size: 0.55rem; color: rgba(180,148,76,0.6); letter-spacing: 0.08em; font-style: italic; }
        .cfg-total-label { font-family: 'Montserrat', sans-serif; font-size: 0.6rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--gray); }
        .cfg-total-price { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: #b4944c; transition: all 0.3s; }
        .cfg-total-wrap { display: flex; align-items: center; justify-content: space-between; padding: 12px 0 6px; margin-top: 6px; border-top: 1px solid rgba(180,148,76,0.2); width: 100%; }

        /* SCROLL REVEAL */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* RESPONSIVE */
        @media (max-width: 900px) { .wedding-cards { grid-template-columns: 1fr 1fr; } .contact-cards { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 768px) { header { padding: 10px 20px; flex-wrap: wrap; } .header-logo img { height: 42px; } nav { gap: 18px; order: 3; width: 100%; justify-content: center; } section { padding: 70px 24px; } footer { padding: 24px; flex-direction: column; gap: 10px; text-align: center; } }
        @media (max-width: 560px) { .wedding-cards { grid-template-columns: 1fr; } .contact-cards { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php if ($success): ?>
<div class="notif notif-success" id="notif">
    <?= $paid ? '✓ Paiement confirmé ! Votre réservation est enregistrée.' : '✓ Réservation envoyée ! Nous vous contacterons bientôt.' ?>
</div>
<?php elseif ($error): ?>
<div class="notif notif-error" id="notif">✕ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- HEADER -->
<header id="site-header">
    <a href="#home" class="header-logo">
        <img src="faa.png" alt="Milano Studio Photography">
    </a>
    <nav>
        <a href="#home"     id="nav-home">Home</a>
        <a href="#services" id="nav-services">Services</a>
        <a href="#gallery"  id="nav-gallery">Gallery</a>
        <a href="#contact"  id="nav-contact">Contact</a>
    </nav>
    <div class="header-right">
        <div class="lang">
            <button onclick="setLang('en')">EN</button>
            <button onclick="setLang('fr')">FR</button>
            <button onclick="setLang('ar')">AR</button>
        </div>
        
        <!-- CART ICON -->
        <button class="cart-btn" onclick="toggleCart()" id="cart-btn" title="Mes réservations">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <span class="cart-count" id="cart-count">0</span>
        </button>
    </div>
</header>

<!-- HERO -->
<section id="home" class="hero">
    <div class="hero-eyebrow">Milano Studio Photography</div>
    <img src="faa.png" alt="Milano Studio Logo" class="hero-logo">
    <h1 id="title">Milano<em> Studio</em></h1>
    <p id="subtitle">Capture your best moments with HamzaFaleh</p>
    <div class="hero-divider"></div>
    <a href="#gallery" class="hero-scroll">
        <span>Découvrir</span>
        <div class="hero-scroll-dot"></div>
    </a>
</section>

<!-- GALLERY -->
<section id="gallery">
    <h2 id="galleryTitle" class="reveal">Galerie Photos</h2>
    <div class="ribbon-wrap">
        <div class="ribbon-track" id="ribbonTrack">
        <?php
        $photos = ['18.jpg','1.jpg','3.jpg','4.jpg','11.jpg','5.jpg','12.jpg','6.jpg','13.jpg','7.jpg','14.jpg','2.jpg','8.jpg','15.jpg','16.jpg','9.jpg','17.jpg','10.jpg'];
        $doubled = array_merge($photos, $photos);
        foreach ($doubled as $i => $photo):
        $realIdx = $i % count($photos);
        $loading = $i < 6 ? 'eager' : 'lazy';
        ?>
        <div class="ribbon-item" onclick="openLightbox(<?= $realIdx ?>)">
            <img src="<?= $photo ?>?v=1" alt="Milano Studio" loading="<?= $loading ?>" onerror="this.parentElement.style.display='none'">
        </div>
        <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="page-wrapper">

<!-- MODAL -->
<div id="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9000; align-items:center; justify-content:center;" onclick="if(event.target===this)closeModal()">
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.1); padding:48px; max-width:580px; width:90%; position:relative; max-height:90vh; overflow-y:auto; border-radius:12px; box-shadow: 0 24px 80px rgba(0,0,0,0.2);">
        <button onclick="closeModal()" style="position:absolute; top:16px; right:20px; background:none; border:none; color:#aaa; font-size:1.4rem; cursor:pointer; line-height:1;">✕</button>
        <h3 id="modal-title" style="font-family:'Dancing Script',cursive; font-size:2rem; color:#1a1a1a; margin-bottom:6px;">Réservation</h3>
        <p id="modal-package-label" style="font-family:'Montserrat',sans-serif; font-size:0.62rem; letter-spacing:0.22em; text-transform:uppercase; color:#E31E24; margin-bottom:20px;"></p>

        <!-- Sélecteur options dans le modal -->
        <div id="modal-cam-group" style="margin-bottom:28px; padding:18px; background:#f8f8f8; border:1px solid rgba(227,30,36,0.15); border-radius:8px; display:flex; flex-direction:column; gap:14px;">

            <!-- Photo illimité — obligatoire -->
            <div style="display:flex; align-items:center; justify-content:space-between; padding:6px 0;">
                <span style="font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">📀 Photo illimité clé USB + montage vidéo</span>
                <span style="font-family:'Montserrat',sans-serif; font-size:0.58rem; color:#4caf7d; border:1px solid rgba(76,175,80,0.3); padding:3px 8px; border-radius:20px;">Inclus ✓</span>
            </div>

            <!-- Caméras -->
            <div>
                <p id="modal-lbl-cameras" style="font-family:'Montserrat',sans-serif; font-size:0.58rem; letter-spacing:0.2em; text-transform:uppercase; color:#E31E24; margin-bottom:10px;">📹 Caméras vidéo</p>
                <div style="display:flex; gap:16px; flex-wrap:wrap;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">
                        <input type="radio" name="nb_cameras" value="1" id="modal-cam-1" checked style="accent-color:#E31E24;" onchange="updateModalTotal()">
                        <span id="modal-lbl-cam1">1 caméra</span> — <span style="color:#E31E24; font-weight:bold;">450 DT</span>
                    </label>
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">
                        <input type="radio" name="nb_cameras" value="2" id="modal-cam-2" style="accent-color:#E31E24;" onchange="updateModalTotal()">
                        <span id="modal-lbl-cam2">2 caméras</span> — <span style="color:#E31E24; font-weight:bold;">700 DT</span> <span style="font-size:0.75rem; color:#aaa;">(+250 DT)</span>
                    </label>
                </div>
            </div>

            <div style="border-top:1px solid rgba(201,169,110,0.1); padding-top:14px; display:flex; flex-direction:column; gap:10px;">

                <!-- Drone -->
                <label style="display:flex; align-items:center; justify-content:space-between; cursor:pointer;">
                    <span style="display:flex; align-items:center; gap:8px; font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">
                        <input type="checkbox" id="modal-drone" style="accent-color:#E31E24; width:16px; height:16px;" onchange="updateModalTotal()">
                        🚁 <span id="modal-lbl-drone">Drone</span>
                    </span>
                    <span style="font-family:'Montserrat',sans-serif; font-size:0.6rem; color:#E31E24; letter-spacing:0.1em;"><b>+400 DT</b></span>
                </label>

                <!-- Girafe -->
                <label style="display:flex; align-items:center; justify-content:space-between; cursor:pointer;">
                    <span style="display:flex; align-items:center; gap:8px; font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">
                        <input type="checkbox" id="modal-girafe" style="accent-color:#E31E24; width:16px; height:16px;" onchange="updateModalTotal()">
                        🦒 <span id="modal-lbl-girafe">Girafe photography</span>
                    </span>
                    <span style="font-family:'Montserrat',sans-serif; font-size:0.6rem; color:#E31E24; letter-spacing:0.1em;"><b>+450 DT</b></span>
                </label>

                <!-- Photo book -->
                <label id="modal-photobook-row" style="display:flex; align-items:center; justify-content:space-between; cursor:pointer;">
                    <span style="display:flex; align-items:center; gap:8px; font-family:'Cormorant Garamond',serif; font-size:1rem; color:#1a1a1a;">
                        <input type="checkbox" id="modal-photobook" style="accent-color:#E31E24; width:16px; height:16px;" onchange="updateModalTotal()">
                        📒 <span id="modal-lbl-photobook">Photo book 50 photos</span>
                    </span>
                    <span id="modal-photobook-price" style="font-family:'Montserrat',sans-serif; font-size:0.6rem; color:#E31E24; letter-spacing:0.1em;"><b>+150 DT</b></span>
                </label>

                <!-- Transport — Gouvernorat / Délégation -->
                <div style="padding-top:10px; border-top:1px solid rgba(0,0,0,0.07);">
                    <span style="font-family:'Cormorant Garamond',serif; font-size:1rem; color:#999; display:block; margin-bottom:10px;">🚗 <span id="modal-lbl-transport">Localisation</span></span>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div class="select-wrapper" style="margin-bottom:0;">
                            <select id="modal-transport-gov" onchange="updateDelegations()" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.95rem; padding:10px 36px 10px 12px; cursor:pointer; outline:none; transition:border-color 0.3s; border-radius:4px;">
                                <option value="" id="opt-gov-placeholder">-- Gouvernorat --</option>
                            </select>
                        </div>
                        <div class="select-wrapper" style="margin-bottom:0;">
                            <select id="modal-transport-del" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.95rem; padding:10px 36px 10px 12px; cursor:pointer; outline:none; transition:border-color 0.3s; border-radius:4px;">
                                <option value="" id="opt-del-placeholder">-- Délégation --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Localisation standalone — visible for ALL types (shooting, event, wedding) -->
        <div id="modal-localisation-group" style="margin-bottom:20px; padding:18px; background:#f8f8f8; border:1px solid rgba(0,0,0,0.1); border-radius:8px; display:none;">
            <span style="font-family:'Cormorant Garamond',serif; font-size:1rem; color:#999; display:block; margin-bottom:10px;">🚗 <span>Localisation</span></span>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <div class="select-wrapper" style="margin-bottom:0;">
                    <select id="modal-loc-gov" onchange="updateLocDelegations()" style="width:100%; appearance:none; background:#fff; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.95rem; padding:10px 36px 10px 12px; cursor:pointer; outline:none; border-radius:4px;">
                        <option value="">-- Gouvernorat --</option>
                    </select>
                </div>
                <div class="select-wrapper" style="margin-bottom:0;">
                    <select id="modal-loc-del" style="width:100%; appearance:none; background:#fff; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.95rem; padding:10px 36px 10px 12px; cursor:pointer; outline:none; border-radius:4px;">
                        <option value="">-- Délégation --</option>
                    </select>
                </div>
            </div>
        </div>

        <form action="traitement_devis.php" method="POST" onsubmit="syncLocalisationToForm(); cartSaveFromModal(); envoyerEmailJS()">
            <input type="hidden" name="choix_package" id="modal-package-input">
            <input type="hidden" id="modal-type-original">
            <input type="hidden" name="res_id" id="modal-res-id" value="">
            <!-- Hidden inputs for localisation — synced from selects above on submit -->
            <input type="hidden" name="gouvernorat" id="modal-hidden-gov">
            <input type="hidden" name="delegation" id="modal-hidden-del">
            <div class="form-group" id="modal-type-group" style="display:none;">
                <label id="lbl-type">Type de cérémonie</label>
                <div class="select-wrapper">
                    <select name="wedding_type" id="modal-wedding-type">
                        <option value="wtiya">👰 Wtiya</option>
                        <option value="henna">🤵 Henna</option>
                        <option value="mariage">💍 Mariage</option>
                    </select>
                </div>
            </div>
            <!-- Package choices removed — managed via cart -->
            <fieldset>
                <legend id="modal-coordonnees">Coordonnées du client</legend>
                <div class="form-group" id="field-homme">
                    <label id="lbl-homme" for="m_nom_homme">Le nom de l'homme</label>
                    <input type="text" id="m_nom_homme" name="nom_homme" placeholder="Votre nom" style="width:100%; background:transparent; border:none; border-bottom:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:1.05rem; padding:10px 4px; outline:none; transition:border-color 0.3s;" onfocus="this.style.borderBottomColor='#E31E24'" onblur="this.style.borderBottomColor='rgba(0,0,0,0.15)'">
                </div>
                <div class="form-group" id="field-femme">
                    <label id="lbl-femme" for="m_nom_femme">Le nom de la femme</label>
                    <input type="text" id="m_nom_femme" name="nom_femme" placeholder="Votre nom" style="width:100%; background:transparent; border:none; border-bottom:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:1.05rem; padding:10px 4px; outline:none; transition:border-color 0.3s;" onfocus="this.style.borderBottomColor='#E31E24'" onblur="this.style.borderBottomColor='rgba(0,0,0,0.15)'">
                </div>
            </fieldset>
            <div class="form-group">
                <label id="lbl-tel" for="m_telephone">Numéro de téléphone</label>
                <input type="tel" id="m_telephone" name="telephone" placeholder="12345678" pattern="\d{8}" minlength="8" maxlength="8" required style="width:100%; background:transparent; border:none; border-bottom:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:1.05rem; padding:10px 4px; outline:none; transition:border-color 0.3s;" onfocus="this.style.borderBottomColor='#E31E24'" onblur="this.style.borderBottomColor='rgba(0,0,0,0.15)'">
            </div>
            <div class="form-group">
                <label id="lbl-cin" for="m_cin">Numéro CIN</label>
                <input type="text" id="m_cin" name="cin" placeholder="12345678" pattern="\d{8}" minlength="8" maxlength="8" required style="width:100%; background:transparent; border:none; border-bottom:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:1.05rem; padding:10px 4px; outline:none; transition:border-color 0.3s;" onfocus="this.style.borderBottomColor='#E31E24'" onblur="this.style.borderBottomColor='rgba(0,0,0,0.15)'">
            </div>
            <div class="form-group">
                <label id="lbl-date" for="m_date_soiree">Date de la soirée</label>
                <input type="date" id="m_date_soiree" name="date_soiree" min="<?= date('Y-m-d') ?>" required style="width:100%; background:transparent; border:none; border-bottom:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:1.05rem; padding:10px 4px; outline:none; transition:border-color 0.3s; color-scheme:light;" onfocus="this.style.borderBottomColor='#E31E24'" onblur="this.style.borderBottomColor='rgba(0,0,0,0.15)'">
                <small id="dispo-msg" style="font-family:'Montserrat',sans-serif; font-size:0.62rem; letter-spacing:0.1em; margin-top:6px; display:block;"></small>
            </div>
            <div class="checkbox-row">
                <input type="checkbox" id="m_conditions" name="conditions" required>
                <label id="lbl-conditions" for="m_conditions">J'accepte les conditions générales</label>
            </div>
            <!-- Total récapitulatif modal -->
            <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 18px; background:rgba(227,30,36,0.05); border:1px solid rgba(227,30,36,0.2); border-radius:8px; margin-bottom:20px;">
                <span style="font-family:'Montserrat',sans-serif; font-size:0.6rem; letter-spacing:0.2em; text-transform:uppercase; color:#888;" id="lbl-total-estime-modal">Total estimé</span>
                <span id="modal-total-display" style="font-family:'Dancing Script',cursive; font-size:1.8rem; color:#E31E24; font-weight:700;">450 DT</span>
            </div>
            <input type="hidden" name="total_price" id="modal-total-price" value="450">
            <button type="submit" class="btn-submit" id="btn-envoyer">Envoyer ma réservation</button>
        </form>
    </div>
</div>

<!-- SERVICES -->
<section id="services">
    <h2 id="servicesTitle" class="reveal">Services &amp; Prix</h2>
    <ul id="servicesList">

        <li class="reveal">
            <div class="svc-header">
                <span class="svc-name" id="svc-shooting">Shooting</span>
                <span class="svc-price" id="svc-shooting-price">250 DT</span>
            </div>
            <div class="wedding-cards">

                <!-- Shooting Individuel -->
                <div class="wedding-card">
                    <div class="wedding-card-top"></div>
                    <div class="wedding-card-body">
                        <div class="wedding-card-icon">📸</div>
                        <div class="wedding-card-title" id="card-title-shoot-ind">Shooting Individuel</div>
                        <div class="wedding-card-desc" id="desc-shoot-ind">Séance photo personnelle</div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc">📸 <span id="lbl-photo-si">Appareil photo</span></span>
                            <span class="cfg-badge">✓ Inclus</span>
                        </div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc" id="lbl-prix-shoot-ind">Prix fixe</span>
                            <span class="pkg-line-price">250 DT</span>
                        </div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc" id="lbl-sans-transport-si" style="color:#999; font-size:0.82rem;">Prix sans transport</span>
                        </div>
                        <!-- Localisation -->
                        <div style="width:100%; margin-top:6px; display:flex; flex-direction:column; gap:6px;">
                            <span style="font-family:'Cormorant Garamond',serif; font-size:0.85rem; color:#999;">🚗 <span id="lbl-transport-si">Localisation</span></span>
                            <div class="select-wrapper" style="margin-bottom:0;">
                                <select class="cfg-gov-select" onchange="updateCardDelegations(this)" data-type="si" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                    <option value="">-- Gouvernorat --</option>
                                </select>
                            </div>
                            <div class="select-wrapper" style="margin-bottom:0;">
                                <select class="cfg-del-select" id="cfg-del-si" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                    <option value="">-- Délégation --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn-reserver" style="width:100%;" onclick="openModalFromCard('Shooting Individuel','shoot-ind','si')" id="btn-reserver-shoot-ind"><span id="btn-text-shoot-ind">Réserver</span></button>
                </div>

                <!-- Shooting Mariage -->
                <div class="wedding-card">
                    <div class="wedding-card-top"></div>
                    <div class="wedding-card-body">
                        <div class="wedding-card-icon">💑</div>
                        <div class="wedding-card-title" id="card-title-shoot-mar">Shooting Mariage</div>
                        <div class="wedding-card-desc" id="desc-shoot-mar">Séance photo de couple</div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc">📸 <span id="lbl-photo-sm">Appareil photo</span></span>
                            <span class="cfg-badge">✓ Inclus</span>
                        </div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc" id="lbl-prix-shoot-mar">Prix fixe</span>
                            <span class="pkg-line-price">250 DT</span>
                        </div>
                        <div class="pkg-line" style="margin-bottom:8px;">
                            <span class="pkg-line-desc" id="lbl-sans-transport-sm" style="color:#999; font-size:0.82rem;">Prix sans transport</span>
                        </div>
                        <!-- Localisation -->
                        <div style="width:100%; margin-top:6px; display:flex; flex-direction:column; gap:6px;">
                            <span style="font-family:'Cormorant Garamond',serif; font-size:0.85rem; color:#999;">🚗 <span id="lbl-transport-sm">Localisation</span></span>
                            <div class="select-wrapper" style="margin-bottom:0;">
                                <select class="cfg-gov-select" onchange="updateCardDelegations(this)" data-type="sm" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                    <option value="">-- Gouvernorat --</option>
                                </select>
                            </div>
                            <div class="select-wrapper" style="margin-bottom:0;">
                                <select class="cfg-del-select" id="cfg-del-sm" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                    <option value="">-- Délégation --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn-reserver" style="width:100%;" onclick="openModalFromCard('Shooting Mariage','shoot-mar','sm')" id="btn-reserver-shoot-mar"><span id="btn-text-shoot-mar">Réserver</span></button>
                </div>

            </div>
        </li>

        <li class="reveal">
            <div class="svc-header">
                <span class="svc-name" id="svc-wedding">Wedding Photography</span>
                <span class="svc-price" id="svc-wedding-price">à partir de 450 DT</span>
            </div>
            <div class="wedding-cards">
                <?php foreach ([
                    ['wtiya',   '👰', 'Wtiya',   'desc-wtiya',   'Soirée de la femme'],
                    ['henna',   '🤵', 'Henna',   'desc-henna',   "Soirée de l'homme"],
                    ['mariage', '💍', 'Mariage', 'desc-mariage', 'Cérémonie de mariage'],
                ] as [$type, $icon, $title, $descId, $desc]): ?>
                <div class="wedding-card">
                    <div class="wedding-card-top"></div>
                    <div class="wedding-card-body">
                        <div class="wedding-card-icon"><?= $icon ?></div>
                        <div class="wedding-card-title" id="card-title-<?= $type ?>"><?= $title ?></div>
                        <div class="wedding-card-desc" id="<?= $descId ?>"><?= $desc ?></div>

                        <!-- CONFIGURATEUR -->
                        <div class="cfg-wrap" id="cfg-<?= $type ?>">

                            <div class="cfg-row cfg-fixed">
                                <label class="cfg-label">📸 Appareil photo</label>
                                <span class="cfg-badge">Inclus ✓</span>
                            </div>

                            <div class="cfg-row cfg-fixed">
                                <label class="cfg-label" style="font-size:0.85rem;">📀 Photo illimité clé USB + montage vidéo</label>
                                <span class="cfg-badge">Inclus ✓</span>
                            </div>

                            <div class="cfg-row">
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('<?= $type ?>','drone',this.checked?1:0)">
                                    🚁 <span class="lbl-drone">Drone</span>
                                </label>
                                <span class="cfg-sub">+400 DT</span>
                            </div>

                            <div class="cfg-row">
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('<?= $type ?>','girafe',this.checked?1:0)">
                                    🦒 <span class="lbl-girafe">Girafe photography</span>
                                </label>
                                <span class="cfg-sub">+450 DT</span>
                            </div>

                            <div class="cfg-row" id="cfg-photobook-row-<?= $type ?>" <?= $type === 'henna' ? 'style="display:none;"' : '' ?>>
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('<?= $type ?>','photobook',this.checked?1:0)">
                                    📒 <span class="lbl-photobook-<?= $type ?>"><?= $type === 'mariage' ? 'Photo book 100 photos' : 'Photo book 50 photos' ?></span>
                                </label>
                                <span class="cfg-sub"><?= $type === 'mariage' ? '+250 DT' : '+150 DT' ?></span>
                            </div>

                            <div class="cfg-row" style="flex-direction:column; align-items:flex-start; gap:6px; padding-top:6px;">
                                <label class="cfg-label" style="color:#999;">🚗 <span class="lbl-transport">Localisation</span></label>
                                <div style="width:100%; display:flex; flex-direction:column; gap:6px;">
                                    <div class="select-wrapper" style="margin-bottom:0;">
                                        <select class="cfg-gov-select" onchange="updateCardDelegations(this)" data-type="<?= $type ?>" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                            <option value="">-- Gouvernorat --</option>
                                        </select>
                                    </div>
                                    <div class="select-wrapper" style="margin-bottom:0;">
                                        <select class="cfg-del-select" id="cfg-del-<?= $type ?>" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                            <option value="">-- Délégation --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="cfg-total-wrap">
                                <span class="cfg-total-label lbl-total-estime">Total estimé</span>
                                <span class="cfg-total-price" id="cfg-total-<?= $type ?>">450 DT</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn-reserver" style="width:100%;" onclick="openModalCfg('<?= $title ?>','<?= $type ?>')" id="btn-reserver-<?= $type ?>"><span>Réserver</span></button>
                </div>
                <?php endforeach; ?>
            </div>
        </li>

        <li class="reveal" style="padding-bottom:12px;">
            <div class="svc-header">
                <span class="svc-name" id="svc-event">Event Photography</span>
                <span class="svc-price">450 DT</span>
            </div>
            <div class="wedding-cards" style="grid-template-columns: repeat(auto-fit, minmax(280px, 380px)); justify-content: center;">
                <div class="wedding-card">
                    <div class="wedding-card-top"></div>
                    <div class="wedding-card-body">
                        <div class="wedding-card-icon">🎉</div>
                        <div class="wedding-card-title" id="card-title-event">Event Photography</div>
                        <div class="wedding-card-desc" id="desc-event">Cérémonie & événements</div>

                        <div class="cfg-wrap" id="cfg-event">

                            <div class="cfg-row cfg-fixed">
                                <label class="cfg-label">📸 Appareil photo</label>
                                <span class="cfg-badge">Inclus ✓</span>
                            </div>

                            <div class="cfg-row cfg-fixed">
                                <label class="cfg-label" style="font-size:0.85rem;">📀 Photo illimité clé USB + montage vidéo</label>
                                <span class="cfg-badge">Inclus ✓</span>
                            </div>

                            <div class="cfg-row">
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('event','drone',this.checked?1:0)">
                                    🚁 <span class="lbl-drone">Drone</span>
                                </label>
                                <span class="cfg-sub">+400 DT</span>
                            </div>

                            <div class="cfg-row">
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('event','girafe',this.checked?1:0)">
                                    🦒 <span class="lbl-girafe">Girafe photography</span>
                                </label>
                                <span class="cfg-sub">+450 DT</span>
                            </div>

                            <div class="cfg-row" id="cfg-photobook-row-event">
                                <label class="cfg-label cfg-check-label">
                                    <input type="checkbox" class="cfg-checkbox" onchange="setCfg('event','photobook',this.checked?1:0)">
                                    📒 <span class="lbl-photobook-event">Photo book 50 photos</span>
                                </label>
                                <span class="cfg-sub">+150 DT</span>
                            </div>

                            <div class="cfg-row" style="flex-direction:column; align-items:flex-start; gap:6px; padding-top:6px;">
                                <label class="cfg-label" style="color:#999;">🚗 <span class="lbl-transport">Localisation</span></label>
                                <div style="width:100%; display:flex; flex-direction:column; gap:6px;">
                                    <div class="select-wrapper" style="margin-bottom:0;">
                                        <select class="cfg-gov-select" onchange="updateCardDelegations(this)" data-type="event" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                            <option value="">-- Gouvernorat --</option>
                                        </select>
                                    </div>
                                    <div class="select-wrapper" style="margin-bottom:0;">
                                        <select class="cfg-del-select" id="cfg-del-event" style="width:100%; appearance:none; background:#f8f8f8; border:1px solid rgba(0,0,0,0.15); color:#1a1a1a; font-family:'Cormorant Garamond',serif; font-size:0.85rem; padding:8px 28px 8px 10px; cursor:pointer; outline:none; border-radius:4px;">
                                            <option value="">-- Délégation --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="cfg-total-wrap">
                                <span class="cfg-total-label lbl-total-estime">Total estimé</span>
                                <span class="cfg-total-price" id="cfg-total-event">450 DT</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn-reserver" style="width:100%;" onclick="openModalCfg('Event Photography','event')" id="btn-reserver-event"><span>Réserver</span></button>
                </div>
            </div>
        </li>
    </ul>
</section>

<!-- CONTACT -->
<section id="contact">
    <h2 id="contactTitle" class="reveal">Contact</h2>
    <div class="contact-cards">
        <a class="contact-card reveal" href="tel:+21655587602">
            <div class="contact-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#E31E24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.59 1.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.82a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16.92z"/></svg>
            </div>
            <span class="contact-card-label" id="lbl-phone1">Téléphone</span>
            <span class="contact-card-value">+216 55 587 602</span>
        </a>
        <a class="contact-card reveal" href="tel:+21694313130">
            <div class="contact-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#E31E24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            </div>
            <span class="contact-card-label" id="lbl-phone2">Téléphone</span>
            <span class="contact-card-value">+216 94 313 130</span>
        </a>
        <a class="contact-card reveal" href="mailto:milano.studio.photo@gmail.com">
            <div class="contact-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#E31E24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <span class="contact-card-label" id="lbl-email">Email</span>
            <span class="contact-card-value">milano.studio.photo@gmail.com</span>
        </a>
        <a class="contact-card reveal" href="https://www.facebook.com/masra7cinemamezouna" target="_blank" rel="noopener">
            <div class="contact-card-icon">
                <!-- Facebook official logo -->
                <svg viewBox="0 0 24 24" fill="#1877F2" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
            </div>
            <span class="contact-card-label">Facebook</span>
            <span class="contact-card-value">Milano Studio</span>
        </a>
        <a class="contact-card reveal" href="https://www.instagram.com/milano_studio_photography/" target="_blank" rel="noopener">
            <div class="contact-card-icon">
                <!-- Instagram official gradient logo -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <radialGradient id="igGrad" cx="30%" cy="107%" r="150%">
                            <stop offset="0%" stop-color="#ffd600"/>
                            <stop offset="25%" stop-color="#ff6f00"/>
                            <stop offset="50%" stop-color="#e91e8c"/>
                            <stop offset="75%" stop-color="#c2185b"/>
                            <stop offset="100%" stop-color="#7b1fa2"/>
                        </radialGradient>
                    </defs>
                    <rect width="24" height="24" rx="6" fill="url(#igGrad)"/>
                    <rect x="2.5" y="2.5" width="19" height="19" rx="5" fill="none" stroke="white" stroke-width="1.5"/>
                    <circle cx="12" cy="12" r="4.5" fill="none" stroke="white" stroke-width="1.5"/>
                    <circle cx="18" cy="6" r="1.2" fill="white"/>
                </svg>
            </div>
            <span class="contact-card-label">Instagram</span>
            <span class="contact-card-value">@milano_studio_photography</span>
        </a>
        <a class="contact-card reveal" href="https://www.tiktok.com/@milano_studio?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener">
            <div class="contact-card-icon">
                <!-- TikTok official logo -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z" fill="white"/>
                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z" fill="none" stroke="#69C9D0" stroke-width="0.5"/>
                </svg>
            </div>
            <span class="contact-card-label">TikTok</span>
            <span class="contact-card-value">@milano_studio</span>
        </a>
    </div>
</section>

<!-- LIGHTBOX -->
<div id="lightbox" onclick="if(event.target===this)closeLightbox()">
    <button class="lb-close" onclick="closeLightbox()">✕</button>
    <button class="lb-nav lb-prev" onclick="lbNavigate(-1)">&#8249;</button>
    <img id="lb-img" src="" alt="">
    <button class="lb-nav lb-next" onclick="lbNavigate(1)">&#8250;</button>
    <div class="lb-counter" id="lb-counter"></div>
</div>

<!-- FOOTER -->
<footer>
    <p id="footer-rights">&copy; <?= date('Y') ?> Milano Studio — Tous droits réservés</p>
    <div class="footer-logo">
        <img src="faa.png" alt="Milano Studio">
    </div>
</footer>

</div><!-- end page-wrapper -->

<script>
// AUTO-HIDE NOTIF
const notif = document.getElementById('notif');
if (notif) { setTimeout(() => { notif.style.transition = 'opacity 0.5s'; notif.style.opacity = '0'; setTimeout(() => notif.remove(), 500); }, 5000); }

// HEADER SCROLL
window.addEventListener('scroll', () => { document.getElementById('site-header').classList.toggle('scrolled', window.scrollY > 60); });

// SCROLL REVEAL
const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => { if (e.isIntersecting) { setTimeout(() => e.target.classList.add('visible'), i * 80); obs.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

// LANG SWITCHER
function setLang(lang) {
    const texts = {
        fr: {
            'title':"Milano Studio",'subtitle':"Capturez vos meilleurs moments avec HamzaFaleh",
            'servicesTitle':"Services & Prix",'contactTitle':"Contact",
            'nav-home':"Accueil",'nav-services':"Services",'nav-gallery':"Galerie",'nav-contact':"Contact",
            'svc-shooting':"Shooting",'svc-wedding':"Wedding Photography",'svc-event':"Event Photography",
            'desc-wtiya':"Soirée de la femme",'desc-henna':"Soirée de l'homme",'desc-mariage':"Cérémonie de mariage",
            'modal-title':"Réservation",'modal-coordonnees':"Coordonnées du client",
            'lbl-homme':"Le nom de l'homme",'lbl-femme':"Le nom de la femme",
            'lbl-tel':"Numéro de téléphone",'lbl-cin':"Numéro CIN",
            'lbl-date':"Date de la soirée",'lbl-conditions':"J'accepte les conditions générales",
            'btn-envoyer':"Envoyer ma réservation",'lbl-type':"Type de cérémonie",'lbl-package':"Package",
            'lbl-phone1':"Téléphone",'lbl-phone2':"Téléphone",'lbl-email':"Email",
            'modal-lbl-cameras':"📹 Caméras vidéo",
            'modal-lbl-cam1':"1 caméra",'modal-lbl-cam2':"2 caméras",
            'modal-lbl-drone':"Drone",'modal-lbl-girafe':"Girafe photography",
            'modal-lbl-transport':"Transport",'modal-lbl-sans-transport':"Sans transport",
            'footer-rights':"© "+new Date().getFullYear()+" Milano Studio — Tous droits réservés",
            'galleryTitle':"Galerie Photos",
            'card-title-shoot-ind':"Shooting Individuel",'card-title-shoot-mar':"Shooting Mariage",
            'card-title-wtiya':"Wtiya",'card-title-henna':"Henna",'card-title-mariage':"Mariage",
            'desc-shoot-ind':"Séance photo personnelle",'desc-shoot-mar':"Séance photo de couple",
            'pkg1-label':"Choix 1 — 1 caméra + appareil photo",'pkg2-label':"Choix 2 — 2 caméras + appareil photo",'pkg3-label':"Choix 3 — 2 caméras + appareil + drone",
            'lbl-prix-shoot-ind':"Prix fixe",'lbl-prix-shoot-mar':"Prix fixe",
            'btn-reserver-shoot-ind':"Réserver",'btn-reserver-shoot-mar':"Réserver",
            'btn-text-shoot-ind':"Réserver",'btn-text-shoot-mar':"Réserver",
            'lbl-photo-si':"Appareil photo",'lbl-photo-sm':"Appareil photo",
            'lbl-cam-si':"Caméras",'lbl-cam-sm':"Caméras",
            'lbl-drone-si':"Drone",'lbl-drone-sm':"Drone",
            'lbl-girafe-si':"Girafe photography",'lbl-girafe-sm':"Girafe photography",
            'lbl-transport-si':"Transport",'lbl-transport-sm':"Transport",
            'lbl-sans-transport-si':"Sans transport",'lbl-sans-transport-sm':"Sans transport",
            'lbl-total-si':"Total estimé",'lbl-total-sm':"Total estimé",
            'svc-shooting-price':"à partir de 450 DT",
            'btn-reserver-wtiya':"Réserver",'btn-reserver-henna':"Réserver",'btn-reserver-mariage':"Réserver",'btn-reserver-event':"Réserver",
            'pkg1-wtiya':"1 caméra + appareil photo",'pkg2-wtiya':"2 caméras + appareil photo",'pkg3-wtiya':"2 caméras + appareil + drone",
            'pkg1-henna':"1 caméra + appareil photo",'pkg2-henna':"2 caméras + appareil photo",'pkg3-henna':"2 caméras + appareil + drone",
            'pkg1-mariage':"1 caméra + appareil photo",'pkg2-mariage':"2 caméras + appareil photo",'pkg3-mariage':"2 caméras + appareil + drone",
        },
        en: {
            'title':"Milano Studio",'subtitle':"Capture your best moments with HamzaFaleh",
            'servicesTitle':"Services & Prices",'contactTitle':"Contact",
            'nav-home':"Home",'nav-services':"Services",'nav-gallery':"Gallery",'nav-contact':"Contact",
            'svc-shooting':"Individual Shooting",'svc-wedding':"Wedding Photography",'svc-event':"Event Photography",
            'desc-wtiya':"Woman night",'desc-henna':"Man night",'desc-mariage':"Wedding ceremony",
            'modal-title':"Reservation",'modal-coordonnees':"Client details",
            'lbl-homme':"Groom's name",'lbl-femme':"Bride's name",
            'lbl-tel':"Phone number",'lbl-cin':"ID Number (CIN)",
            'lbl-date':"Event date",'lbl-conditions':"I accept the terms and conditions",
            'btn-envoyer':"Send reservation",'lbl-type':"Ceremony type",'lbl-package':"Package",
            'lbl-phone1':"Phone",'lbl-phone2':"Phone",'lbl-email':"Email",
            'modal-lbl-cameras':"📹 Video cameras",
            'modal-lbl-cam1':"1 camera",'modal-lbl-cam2':"2 cameras",
            'modal-lbl-drone':"Drone",'modal-lbl-girafe':"Girafe photography",
            'modal-lbl-transport':"Transport",'modal-lbl-sans-transport':"Price without transport",
            'footer-rights':"© "+new Date().getFullYear()+" Milano Studio — All rights reserved",
            'galleryTitle':"Photo Gallery",
            'card-title-shoot-ind':"Individual Shooting",'card-title-shoot-mar':"Wedding Shooting",
            'card-title-wtiya':"Wtiya",'card-title-henna':"Henna",'card-title-mariage':"Wedding",
            'desc-shoot-ind':"Personal photo session",'desc-shoot-mar':"Couple photo session",
            'pkg1-label':"Choice 1 — 1 camera + photo",'pkg2-label':"Choice 2 — 2 cameras + photo",'pkg3-label':"Choice 3 — 2 cameras + drone",
            'lbl-prix-shoot-ind':"Fixed price",'lbl-prix-shoot-mar':"Fixed price",
            'btn-reserver-shoot-ind':"Book",'btn-reserver-shoot-mar':"Book",
            'btn-text-shoot-ind':"Book",'btn-text-shoot-mar':"Book",
            'lbl-photo-si':"Camera",'lbl-photo-sm':"Camera",
            'lbl-cam-si':"Cameras",'lbl-cam-sm':"Cameras",
            'lbl-drone-si':"Drone",'lbl-drone-sm':"Drone",
            'lbl-girafe-si':"Girafe photography",'lbl-girafe-sm':"Girafe photography",
            'lbl-transport-si':"Transport",'lbl-transport-sm':"Transport",
            'lbl-sans-transport-si':"Without transport",'lbl-sans-transport-sm':"Without transport",
            'lbl-total-si':"Estimated total",'lbl-total-sm':"Estimated total",
            'svc-shooting-price':"from 450 DT",
            'btn-reserver-wtiya':"Book",'btn-reserver-henna':"Book",'btn-reserver-mariage':"Book",'btn-reserver-event':"Book",
            'pkg1-wtiya':"1 camera + photo",'pkg2-wtiya':"2 cameras + photo",'pkg3-wtiya':"2 cameras + drone",
            'pkg1-henna':"1 camera + photo",'pkg2-henna':"2 cameras + photo",'pkg3-henna':"2 cameras + drone",
            'pkg1-mariage':"1 camera + photo",'pkg2-mariage':"2 cameras + photo",'pkg3-mariage':"2 cameras + drone",
        },
        ar: {
            'title':"ميلانو ستوديو",'subtitle':"نوثق أجمل لحظاتكم مع حمزة الفالح",
            'servicesTitle':"الخدمات والأسعار",'contactTitle':"تواصل معنا",
            'nav-home':"الرئيسية",'nav-services':"الخدمات",'nav-gallery':"المعرض",'nav-contact':"اتصل بنا",
            'svc-shooting':"جلسة تصوير فردية",'svc-wedding':"تصوير الأعراس",'svc-event':"تصوير الفعاليات",
            'desc-wtiya':"سهرة المرأة",'desc-henna':"سهرة الرجل",'desc-mariage':"حفل الزفاف",
            'modal-title':"الحجز",'modal-coordonnees':"بيانات العميل",
            'lbl-homme':"اسم العريس",'lbl-femme':"اسم العروسة",
            'lbl-tel':"رقم الهاتف",'lbl-cin':"رقم بطاقة الهوية",
            'lbl-date':"تاريخ الحفلة",'lbl-conditions':"أوافق على الشروط والأحكام",
            'btn-envoyer':"إرسال الحجز",'lbl-type':"نوع المناسبة",'lbl-package':"الباقة",
            'lbl-phone1':"هاتف",'lbl-phone2':"هاتف",'lbl-email':"البريد الإلكتروني",
            'modal-lbl-cameras':"📹 كاميرات الفيديو",
            'modal-lbl-cam1':"كاميرا واحدة",'modal-lbl-cam2':"كاميرتان",
            'modal-lbl-drone':"درون",'modal-lbl-girafe':"تصوير جيراف",
            'modal-lbl-transport':"النقل",'modal-lbl-sans-transport':"السعر بدون نقل",
            'footer-rights':"© "+new Date().getFullYear()+" Milano Studio — جميع الحقوق محفوظة",
            'galleryTitle':"معرض الصور",
            'card-title-shoot-ind':"تصوير فردي",'card-title-shoot-mar':"تصوير زفاف",
            'card-title-wtiya':"وطية",'card-title-henna':"حنة",'card-title-mariage':"عرس",
            'desc-shoot-ind':"جلسة تصوير شخصية",'desc-shoot-mar':"جلسة تصوير للزوجين",
            'pkg1-label':"الباقة 1 — كاميرا + آلة تصوير",'pkg2-label':"الباقة 2 — كاميرتان + آلة تصوير",'pkg3-label':"الباقة 3 — كاميرتان + آلة + درون",
            'lbl-prix-shoot-ind':"السعر",'lbl-prix-shoot-mar':"السعر",
            'btn-reserver-shoot-ind':"احجز",'btn-reserver-shoot-mar':"احجز",
            'btn-text-shoot-ind':"احجز",'btn-text-shoot-mar':"احجز",
            'lbl-photo-si':"آلة تصوير",'lbl-photo-sm':"آلة تصوير",
            'lbl-cam-si':"كاميرات",'lbl-cam-sm':"كاميرات",
            'lbl-drone-si':"درون",'lbl-drone-sm':"درون",
            'lbl-girafe-si':"تصوير جيراف",'lbl-girafe-sm':"تصوير جيراف",
            'lbl-transport-si':"النقل",'lbl-transport-sm':"النقل",
            'lbl-sans-transport-si':"بدون نقل",'lbl-sans-transport-sm':"بدون نقل",
            'lbl-total-si':"المجموع التقديري",'lbl-total-sm':"المجموع التقديري",
            'svc-shooting-price':"ابتداءً من 450 دينار",
            'btn-reserver-wtiya':"احجز",'btn-reserver-henna':"احجز",'btn-reserver-mariage':"احجز",'btn-reserver-event':"احجز",
            'pkg1-wtiya':"1 كاميرا + آلة تصوير",'pkg2-wtiya':"2 كاميرات + آلة تصوير",'pkg3-wtiya':"2 كاميرات + آلة + درون",
            'pkg1-henna':"1 كاميرا + آلة تصوير",'pkg2-henna':"2 كاميرات + آلة تصوير",'pkg3-henna':"2 كاميرات + آلة + درون",
            'pkg1-mariage':"1 كاميرا + آلة تصوير",'pkg2-mariage':"2 كاميرات + آلة تصوير",'pkg3-mariage':"2 كاميرات + آلة + درون",
        }
    };
    const t = texts[lang]; if (!t) return;
    Object.entries(t).forEach(([id, text]) => {
        const el = document.getElementById(id); if (!el) return;
        if (id === 'title') { el.innerHTML = text; }
        else { el.innerText = text; }
    });
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.lang = lang;
    // Fix ribbon scroll direction for RTL
    const track = document.getElementById('ribbonTrack');
    if (track) track.style.animationDirection = lang === 'ar' ? 'reverse' : 'normal';
    // Translate card labels by class
    const classTexts = {
        fr: { 'lbl-drone':'Drone', 'lbl-girafe':'Girafe photography', 'lbl-transport':'Transport', 'lbl-sans-transport':'Prix sans transport', 'lbl-total-estime':'Total estimé' },
        en: { 'lbl-drone':'Drone', 'lbl-girafe':'Girafe photography', 'lbl-transport':'Transport', 'lbl-sans-transport':'Price without transport', 'lbl-total-estime':'Estimated total' },
        ar: { 'lbl-drone':'درون', 'lbl-girafe':'تصوير جيراف', 'lbl-transport':'النقل', 'lbl-sans-transport':'السعر بدون نقل', 'lbl-total-estime':'المجموع التقديري' },
    };
    const ct = classTexts[lang];
    if (ct) Object.entries(ct).forEach(([cls, txt]) => { document.querySelectorAll('.' + cls).forEach(el => el.innerText = txt); });
    const ph = { fr:{h:'Votre nom',t:'12345678',c:'12345678'}, en:{h:'Your name',t:'12345678',c:'12345678'}, ar:{h:'اسمك',t:'12345678',c:'12345678'} };
    const p = ph[lang];
    if (p) { ['m_nom_homme','m_nom_femme'].forEach(id => document.getElementById(id).placeholder = p.h); document.getElementById('m_telephone').placeholder = p.t; document.getElementById('m_cin').placeholder = p.c; }
}

function updateNomFields(type) {
    const fH = document.getElementById('field-homme'), fF = document.getElementById('field-femme');
    const iH = document.getElementById('m_nom_homme'), iF = document.getElementById('m_nom_femme');
    const lH = document.getElementById('lbl-homme'), lF = document.getElementById('lbl-femme');
    const lang = document.documentElement.lang || 'fr';
    const L = { fr:{h:"Le nom de l'homme",f:"Le nom de la femme",p:"Nom de la personne",e:"Nom de l'événement"}, en:{h:"Groom's name",f:"Bride's name",p:"Person's name",e:"Event name"}, ar:{h:"اسم العريس",f:"اسم العروسة",p:"اسم الشخص",e:"اسم الحفل"} };
    const l = L[lang] || L.fr;
    const s = (el, show, req) => { el.style.display = show ? 'block' : 'none'; el.querySelector('input').required = req; };
    if (type==='wtiya')     { s(fH,false,false); s(fF,true,true);  iH.value=''; if(lF) lF.innerText=l.f; }
    else if(type==='henna') { s(fH,true,true);   s(fF,false,false); iF.value=''; if(lH) lH.innerText=l.h; }
    else if(type==='shoot-ind') { s(fH,true,true); s(fF,false,false); iF.value=''; if(lH) lH.innerText=l.p; }
    else if(type==='event') { s(fH,true,true);   s(fF,false,false); iF.value=''; if(lH) lH.innerText=l.e; }
    else { s(fH,true,true); s(fF,true,true); if(lH) lH.innerText=l.h; if(lF) lF.innerText=l.f; }
}

function openModal(label, value) {
    document.getElementById('modal-package-label').innerText = label;
    document.getElementById('modal-package-input').value = value;
    const isW = ['wtiya','henna','mariage'].includes(value);
    const isShooting = ['shoot-ind','shoot-mar','event'].includes(value);

    // Safe show/hide (elements may have been removed)
    const safeDisplay = (id, show) => { const el = document.getElementById(id); if (el) el.style.display = show ? 'block' : 'none'; };
    safeDisplay('modal-type-group', isW);
    safeDisplay('modal-prix-group', false); // always hidden — removed from UI

    // Show/hide camera+drone group only for wedding
    const camGroup = document.getElementById('modal-cam-group');
    if (camGroup) camGroup.style.display = isW ? 'block' : 'none';
    // Hide photobook for non-wedding or henna
    const pbRow2 = document.getElementById('modal-photobook-row');
    if (pbRow2) pbRow2.style.display = (isW && value !== 'henna') ? 'flex' : 'none';
    const pbChk2 = document.getElementById('modal-photobook');
    if (pbChk2) pbChk2.checked = false;

    if (isW) {
        const wType = document.getElementById('modal-wedding-type');
        if (wType) wType.value = value;
        // Reset camera/drone selections to default
        const cam1    = document.getElementById('modal-cam-1');
        const droneEl = document.getElementById('modal-drone');
        const girEl   = document.getElementById('modal-girafe');
        if (cam1)    cam1.checked    = true;
        if (droneEl) droneEl.checked = false;
        if (girEl)   girEl.checked   = false;
        const disp = document.getElementById('modal-total-display');
        const inp  = document.getElementById('modal-total-price');
        if (disp) disp.textContent = '400 DT';
        if (inp)  inp.value = '400';
    }

    if (isShooting) {
        const disp = document.getElementById('modal-total-display');
        const inp  = document.getElementById('modal-total-price');
        if (disp) disp.textContent = '250 DT';
        if (inp)  inp.value = '250';
        // Set clear package description for shooting
        const pkgInput = document.getElementById('modal-package-input');
        if (pkgInput) pkgInput.value = label + ' — appareil photo — 200 DT';
    }

    const origInput = document.getElementById('modal-type-original');
    if (origInput) origInput.value = value;

    // Show standalone localisation block for shooting/event (not inside cam-group)
    const locGroup = document.getElementById('modal-localisation-group');
    if (locGroup) {
        locGroup.style.display = isShooting ? 'block' : 'none';
        if (isShooting) {
            // Populate gouvernorat if not already done
            const govSel = document.getElementById('modal-loc-gov');
            if (govSel && govSel.options.length <= 1 && window.tunisiaData) {
                Object.keys(tunisiaData).sort().forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g; opt.textContent = g;
                    govSel.appendChild(opt);
                });
            }
            // Reset
            if (govSel) govSel.value = '';
            const delSel = document.getElementById('modal-loc-del');
            if (delSel) { delSel.innerHTML = '<option value="">-- Délégation --</option>'; }
        }
    }
    // Clear all form fields for fresh reservation
    ['m_nom_homme','m_nom_femme','m_telephone','m_cin','m_date_soiree'].forEach(id => {
        const el = document.getElementById(id); if (el) el.value = '';
    });
    const resIdEl = document.getElementById('modal-res-id');
    if (resIdEl) resIdEl.value = '';
    const dispoMsg = document.getElementById('dispo-msg');
    if (dispoMsg) dispoMsg.textContent = '';
    const editOverlay = document.getElementById('modal-overlay');
    if (editOverlay) delete editOverlay.dataset.editIdx;
    updateNomFields(value);
    document.getElementById('modal-overlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
}

function closeModal() {
    const overlay = document.getElementById('modal-overlay');
    overlay.style.display = 'none';
    document.body.style.overflow = '';
    document.body.style.pointerEvents = '';
    document.documentElement.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('modal-wedding-type').addEventListener('change', function() { updateNomFields(this.value); });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && document.getElementById('modal-overlay').style.display === 'flex') closeModal();
});

// CONFIGURATEUR PRIX — WEDDING
const cfgState = {
    wtiya:   { drone: 0, girafe: 0, photobook: 0 },
    henna:   { drone: 0, girafe: 0, photobook: 0 },
    mariage: { drone: 0, girafe: 0, photobook: 0 },
    event:   { drone: 0, girafe: 0, photobook: 0 },
};

// CONFIGURATEUR PRIX — SHOOTING
const shootState = {
    si: { cam: 1, drone: false, girafe: false },
    sm: { cam: 1, drone: false, girafe: false },
};

function calcShootTotal(id) {
    const s = shootState[id];
    let total = 400;
    if (s.cam === 2) total += 150;
    if (s.drone) total += 350;
    if (s.girafe) total += 500;
    return total;
}

function setShootCfg(id, field, val, btn) {
    shootState[id][field] = val;
    // Update active state on cam buttons
    if (field === 'cam') {
        document.getElementById(id + '-cam-1').classList.toggle('active', val === 1);
        document.getElementById(id + '-cam-2').classList.toggle('active', val === 2);
    }
    updateShootTotal(id);
}

function updateShootTotal(id) {
    shootState[id].drone  = document.getElementById(id + '-drone')?.checked  || false;
    shootState[id].girafe = document.getElementById(id + '-girafe')?.checked || false;
    const total = calcShootTotal(id);
    const el = document.getElementById('cfg-total-' + id);
    if (el) {
        el.style.transform = 'scale(1.15)';
        el.textContent = total + ' DT';
        setTimeout(() => el.style.transform = 'scale(1)', 280);
    }
}

function openShootModal(label, type, id) {
    _modalType = type;
    const s = shootState[id];
    // Sync modal camera radios to card state
    const cam1 = document.getElementById('modal-cam-1');
    const cam2 = document.getElementById('modal-cam-2');
    if (cam1) cam1.checked = (s.cam === 1);
    if (cam2) cam2.checked = (s.cam === 2);
    // Sync drone/girafe
    const droneEl  = document.getElementById('modal-drone');
    const girafeEl = document.getElementById('modal-girafe');
    if (droneEl)  droneEl.checked  = s.drone;
    if (girafeEl) girafeEl.checked = s.girafe;
    // Show camera group in modal
    const camGroup = document.getElementById('modal-cam-group');
    if (camGroup) camGroup.style.display = 'block';
    updateModalTotal();
    // Shooting: hide wedding type selector, show package label
    const _tg = document.getElementById('modal-type-group'); if(_tg) _tg.style.display = 'none';
        document.getElementById('modal-package-label').innerText = label;
    document.getElementById('modal-package-input').value = type;
    document.getElementById('modal-wedding-type').value = '';
    updateNomFields(type);
    document.getElementById('modal-overlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
}

function calcTotal(type, cam) {
    const s = cfgState[type];
    let total = 450;
    if (cam === 2) total += 250;
    if (s.drone) total += 400;
    if (s.girafe) total += 450;
    if (s.photobook) total += (type === 'mariage' ? 250 : 150);
    return total;
}

function setCfg(type, field, val) {
    cfgState[type][field] = val;
    const totalEl = document.getElementById('cfg-total-' + type);
    const newTotal = calcTotal(type, 1);
    totalEl.style.transform = 'scale(1.15)';
    totalEl.style.color = '#E31E24';
    totalEl.textContent = newTotal + ' DT';
    setTimeout(() => { totalEl.style.transform = 'scale(1)'; totalEl.style.color = ''; }, 300);
}

function updateModalTotal() {
    const cam = document.getElementById('modal-cam-2')?.checked ? 2 : 1;
    const drone = document.getElementById('modal-drone')?.checked ? 1 : 0;
    const girafe = document.getElementById('modal-girafe')?.checked ? 1 : 0;
    const pbType = document.getElementById('modal-type-original')?.value || '';
    const photobook = document.getElementById('modal-photobook')?.checked ? 1 : 0;
    let total = 450;
    if (cam === 2) total += 250;
    if (drone) total += 400;
    if (girafe) total += 450;
    if (photobook) total += (pbType === 'mariage' ? 250 : 150);
    const display = document.getElementById('modal-total-display');
    const input = document.getElementById('modal-total-price');
    if (display) { display.textContent = total + ' DT'; }
    if (input) { input.value = total; }
    // Update package label summary
    const parts = [cam === 1 ? '1 caméra' : '2 caméras', 'photo illimité clé USB + montage vidéo'];
    if (drone) parts.push('drone');
    if (girafe) parts.push('girafe');
    if (photobook) parts.push(pbType === 'mariage' ? 'photo book 100 photos' : 'photo book 50 photos');
    const label = document.getElementById('modal-package-label');
    if (label) label.innerText = parts.join(' + ') + ' — ' + total + ' DT (sans transport)';
    const pkgInput = document.getElementById('modal-package-input');
    if (pkgInput) pkgInput.value = parts.join(' + ') + ' — ' + total + ' DT';
}

function openModalCfg(label, type) {
    _modalType = type;
    const origInput = document.getElementById('modal-type-original');
    if (origInput) origInput.value = type;
    const s = cfgState[type];
    // Reset camera to 1 and uncheck options
    const cam1 = document.getElementById('modal-cam-1');
    const cam2 = document.getElementById('modal-cam-2');
    const droneEl = document.getElementById('modal-drone');
    const girafeEl = document.getElementById('modal-girafe');
    if (cam1) cam1.checked = true;
    if (cam2) cam2.checked = false;
    if (droneEl) droneEl.checked = false;
    if (girafeEl) girafeEl.checked = false;
    // Show/hide camera group — for wedding types AND event
    const camGroup = document.getElementById('modal-cam-group');
    if (camGroup) camGroup.style.display = ['wtiya','henna','mariage','event'].includes(type) ? 'block' : 'none';
    // Show/hide photobook row + update label and price
    const pbRow = document.getElementById('modal-photobook-row');
    const pbLbl = document.getElementById('modal-lbl-photobook');
    const pbPrice = document.getElementById('modal-photobook-price');
    const pbChk = document.getElementById('modal-photobook');
    if (pbRow) pbRow.style.display = type === 'henna' ? 'none' : 'flex';
    if (pbChk) pbChk.checked = false;
    if (type === 'mariage') {
        if (pbLbl) pbLbl.textContent = 'Photo book 100 photos';
        if (pbPrice) pbPrice.innerHTML = '<b>+250 DT</b>';
    } else {
        if (pbLbl) pbLbl.textContent = 'Photo book 50 photos';
        if (pbPrice) pbPrice.innerHTML = '<b>+150 DT</b>';
    }
    updateModalTotal();
    // For event: hide the "Type de cérémonie" dropdown (not needed)
    const _tg2 = document.getElementById('modal-type-group');
    if (_tg2) _tg2.style.display = type === 'event' ? 'none' : 'block';
    if (type !== 'event') {
        document.getElementById('modal-wedding-type').value = type;
    }
    updateNomFields(type);
    document.getElementById('modal-overlay').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
}

const allPhotos = ['18.jpg','1.jpg','3.jpg','4.jpg','11.jpg','5.jpg','12.jpg','6.jpg','13.jpg','7.jpg','14.jpg','2.jpg','8.jpg','15.jpg','16.jpg','9.jpg','17.jpg','10.jpg'];
let lbIndex = 0;
function openLightbox(idx) {
    lbIndex = idx;
    document.getElementById('lb-img').src = allPhotos[lbIndex];
    document.getElementById('lb-counter').textContent = (lbIndex + 1) + ' / ' + allPhotos.length;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
}
function closeLightbox() { document.getElementById('lightbox').classList.remove('open'); document.body.style.overflow = ''; document.documentElement.style.overflow = ''; }
function lbNavigate(dir) {
    lbIndex = (lbIndex + dir + allPhotos.length) % allPhotos.length;
    document.getElementById('lb-img').src = allPhotos[lbIndex];
    document.getElementById('lb-counter').textContent = (lbIndex + 1) + ' / ' + allPhotos.length;
}
document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') lbNavigate(-1);
    if (e.key === 'ArrowRight') lbNavigate(1);
});


document.getElementById('m_date_soiree').addEventListener('change', async function() {
    const date = this.value, msg = document.getElementById('dispo-msg');
    if (!date) { msg.textContent = ''; return; }
    try {
        const data = await (await fetch('php/check_dispo.php?date=' + date)).json();
        if (data.count >= 4) { msg.style.color='#e05c5c'; msg.textContent='✕ Date complète (4/4) — choisissez une autre date.'; this.setCustomValidity('Date non disponible'); }
        else { msg.style.color='#4caf7d'; msg.textContent='✓ Date disponible ('+data.count+'/4)'; this.setCustomValidity(''); }
    } catch(e) { msg.textContent = ''; }
});
</script>

<!-- CART SIDEBAR -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<aside class="cart-sidebar" id="cart-sidebar">
    <div class="cart-header">
        <div>
            <h3 class="cart-title" id="cart-title-label">🛒 Mes Réservations</h3>
            <p class="cart-sub" id="cart-sub-label">Votre panier de soirées</p>
        </div>
        <button class="cart-close" onclick="toggleCart()">✕</button>
    </div>

    <div class="cart-body" id="cart-body">
        <div class="cart-empty" id="cart-empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <p id="cart-empty-text">Aucune réservation pour l'instant</p>
        </div>
        <div id="cart-items"></div>
    </div>

    <div class="cart-footer" id="cart-footer" style="display:none;">
        <div class="cart-total-row">
            <span id="cart-total-lbl">Total estimé</span>
            <span class="cart-total-amount" id="cart-grand-total">0 DT</span>
        </div>
        <p class="cart-note" id="cart-note-text">* Sans transport · Hors options supplémentaires</p>
        <button class="cart-clear-btn" id="cart-clear-btn" onclick="cartClear()">
            <span id="cart-clear-lbl">🗑 Vider le panier</span>
        </button>
    </div>
</aside>

<style>
/* CART BUTTON */
.cart-btn { position:relative; background:none; border:1px solid rgba(0,0,0,0.15); color:var(--text); width:40px; height:40px; border-radius:6px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.25s; }
.cart-btn:hover { border-color:var(--red); color:var(--red); background:var(--red-light); }
.cart-count { position:absolute; top:-7px; right:-7px; background:var(--red); color:#fff; font-family:'Montserrat',sans-serif; font-size:0.58rem; font-weight:700; width:18px; height:18px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:transform 0.3s; }
.cart-count.pop { transform:scale(1.5); }
.cart-count[data-count="0"] { display:none; }

/* OVERLAY */
.cart-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.35); z-index:1099; opacity:0; pointer-events:none; transition:opacity 0.35s; }
.cart-overlay.open { opacity:1; pointer-events:all; }

/* SIDEBAR */
.cart-sidebar { position:fixed; top:0; right:0; width:380px; max-width:95vw; height:100vh; background:#fff; z-index:1100; display:flex; flex-direction:column; box-shadow:-4px 0 40px rgba(0,0,0,0.12); transform:translateX(100%); transition:transform 0.38s cubic-bezier(0.4,0,0.2,1); }
.cart-sidebar.open { transform:translateX(0); }

.cart-header { display:flex; align-items:flex-start; justify-content:space-between; padding:28px 24px 20px; border-bottom:1px solid rgba(0,0,0,0.08); }
.cart-title { font-family:'Dancing Script',cursive; font-size:1.6rem; color:var(--text); }
.cart-sub { font-family:'Montserrat',sans-serif; font-size:0.58rem; letter-spacing:0.15em; color:var(--gray); text-transform:uppercase; margin-top:2px; }
.cart-close { background:none; border:none; color:#aaa; font-size:1.3rem; cursor:pointer; padding:4px; transition:color 0.2s; line-height:1; }
.cart-close:hover { color:var(--red); }

.cart-body { flex:1; overflow-y:auto; padding:20px 24px; }
.cart-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px; height:200px; color:#bbb; }
.cart-empty p { font-family:'Montserrat',sans-serif; font-size:0.7rem; letter-spacing:0.1em; }

/* CART ITEM */
.cart-item { background:var(--off-white); border:1px solid rgba(0,0,0,0.07); border-radius:10px; padding:16px; margin-bottom:12px; position:relative; border-left:3px solid var(--red); }
.cart-item-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.cart-item-name { font-family:'Dancing Script',cursive; font-size:1.25rem; color:var(--text); }
.cart-item-delete { background:none; border:none; color:#ccc; cursor:pointer; font-size:1rem; transition:color 0.2s; padding:2px 6px; }
.cart-item-delete:hover { color:var(--red); }
.cart-item-row { display:flex; align-items:center; justify-content:space-between; padding:5px 0; border-bottom:1px solid rgba(0,0,0,0.05); font-family:'Cormorant Garamond',serif; font-size:0.95rem; color:var(--text-light); }
.cart-item-row:last-of-type { border-bottom:none; }
.cart-item-row span:last-child { color:var(--red); font-weight:600; font-family:'Montserrat',sans-serif; font-size:0.72rem; }
.cart-item-total { display:flex; align-items:center; justify-content:space-between; margin-top:10px; padding-top:10px; border-top:1px solid rgba(227,30,36,0.15); }
.cart-item-total-lbl { font-family:'Montserrat',sans-serif; font-size:0.58rem; letter-spacing:0.15em; text-transform:uppercase; color:var(--gray); }
.cart-item-total-price { font-family:'Dancing Script',cursive; font-size:1.5rem; color:var(--red); font-weight:700; }
.cart-item-date { font-family:'Montserrat',sans-serif; font-size:0.6rem; color:var(--gray); letter-spacing:0.08em; margin-top:6px; }

/* FOOTER */
.cart-footer { padding:20px 24px; border-top:2px solid rgba(227,30,36,0.12); background:#fafafa; }
.cart-total-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:6px; font-family:'Montserrat',sans-serif; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--gray); }
.cart-total-amount { font-family:'Dancing Script',cursive; font-size:2rem; color:var(--red); font-weight:700; }
.cart-note { font-family:'Montserrat',sans-serif; font-size:0.55rem; color:#bbb; letter-spacing:0.08em; margin-bottom:14px; }
.cart-confirm-btn { width:100%; background:var(--red); color:#fff; border:none; padding:14px; font-family:'Montserrat',sans-serif; font-size:0.65rem; letter-spacing:0.2em; text-transform:uppercase; cursor:pointer; border-radius:6px; transition:background 0.25s; margin-bottom:8px; }
.cart-confirm-btn:hover { background:var(--red-dark); }
.cart-clear-btn { width:100%; background:none; color:#aaa; border:1px solid rgba(0,0,0,0.1); padding:10px; font-family:'Montserrat',sans-serif; font-size:0.6rem; letter-spacing:0.15em; text-transform:uppercase; cursor:pointer; border-radius:6px; transition:all 0.2s; }
.cart-clear-btn:hover { border-color:var(--red); color:var(--red); }
.cart-edit-btn { width:100%; margin-top:10px; background:none; border:1px solid rgba(227,30,36,0.3); color:var(--red); padding:8px; font-family:'Montserrat',sans-serif; font-size:0.6rem; letter-spacing:0.12em; text-transform:uppercase; cursor:pointer; border-radius:6px; transition:all 0.2s; }
.cart-edit-btn:hover { background:var(--red-light); }
</style>

<script>
// ===================== CART SYSTEM =====================
let cart = JSON.parse(localStorage.getItem('milano_cart') || '[]');

function saveCart() { localStorage.setItem('milano_cart', JSON.stringify(cart)); }

function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
    renderCart();
}

function addToCart(item) {
    cart.push(item);
    saveCart();
    updateCartCount();
    // Pop animation
    const count = document.getElementById('cart-count');
    count.classList.add('pop');
    setTimeout(() => count.classList.remove('pop'), 400);
}

function removeFromCart(idx) {
    cart.splice(idx, 1);
    saveCart();
    updateCartCount();
    renderCart();
}

function cartClear() {
    cart = [];
    saveCart();
    updateCartCount();
    renderCart();
}

function updateCartCount() {
    const el = document.getElementById('cart-count');
    el.textContent = cart.length;
    el.dataset.count = cart.length;
    document.getElementById('cart-btn').style.color = cart.length > 0 ? 'var(--red)' : '';
    document.getElementById('cart-btn').style.borderColor = cart.length > 0 ? 'var(--red)' : '';
}

function renderCart() {
    const body = document.getElementById('cart-items');
    const empty = document.getElementById('cart-empty');
    const footer = document.getElementById('cart-footer');
    body.innerHTML = '';
    if (cart.length === 0) {
        empty.style.display = 'flex';
        footer.style.display = 'none';
        return;
    }
    empty.style.display = 'none';
    footer.style.display = 'block';
    let grand = 0;
    cart.forEach((item, idx) => {
        grand += item.total;
        const div = document.createElement('div');
        div.className = 'cart-item';
        let rows = '';
        if (item.cameras) rows += `<div class="cart-item-row"><span>📹 ${item.cameras}</span><span>${item.cam_price} DT</span></div>`;
        if (item.drone)   rows += `<div class="cart-item-row"><span>🚁 Drone</span><span>+400 DT</span></div>`;
        if (item.girafe)  rows += `<div class="cart-item-row"><span>🦒 Girafe photography</span><span>+450 DT</span></div>`;
        if (item.photobook) rows += `<div class="cart-item-row"><span>📒 Photo book</span><span>+${item.type==='mariage'?250:150} DT</span></div>`;
        rows += `<div class="cart-item-row"><span>📸 Appareil photo</span><span>Inclus</span></div>`;
        div.innerHTML = `
            <div class="cart-item-header">
                <span class="cart-item-name">${item.name}</span>
                <button class="cart-item-delete" onclick="removeFromCart(${idx})">✕</button>
            </div>
            ${rows}
            <div class="cart-item-total">
                <span class="cart-item-total-lbl">Total</span>
                <span class="cart-item-total-price">${item.total} DT</span>
            </div>
            ${item.date ? `<div class="cart-item-date">📅 ${item.date}</div>` : ''}
        `;
        body.appendChild(div);
    });
    document.getElementById('cart-grand-total').textContent = grand + ' DT';
}

function cartConfirmAll() {
    if (cart.length === 0) return;
    // Open edit modal for first pending item
    const item = cart[0];
    openCartEdit(item, 0);
    toggleCart();
}

function cartEditItem(idx) {
    // Close cart sidebar first, then open edit modal
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
    setTimeout(() => openCartEdit(cart[idx], idx), 50);
}

function openCartEdit(item, idx) {
    // openModal resets all fields — call it first, then override with cart item data
    openModal(item.name, item.type);

    // Override form fields with saved cart data (always set, even empty string)
    const setVal = (id, v) => { const el = document.getElementById(id); if (el) el.value = v || ''; };
    setVal('m_nom_homme', item.nom_homme);
    setVal('m_nom_femme', item.nom_femme);
    setVal('m_telephone', item.telephone);
    setVal('m_cin',       item.cin || '');
    setVal('m_date_soiree', item.date);

    // Restore camera/drone/girafe options for wedding types
    const isWedding = ['wtiya','henna','mariage'].includes(item.type);
    if (isWedding) {
        const cam1   = document.getElementById('modal-cam-1');
        const cam2   = document.getElementById('modal-cam-2');
        const droneEl  = document.getElementById('modal-drone');
        const girafeEl = document.getElementById('modal-girafe');
        const is2cam = item.cameras === '2 caméras';
        if (cam1) cam1.checked = !is2cam;
        if (cam2) cam2.checked = is2cam;
        if (droneEl)  droneEl.checked  = !!item.drone;
        if (girafeEl) girafeEl.checked = !!item.girafe;
        const pbChkEdit = document.getElementById('modal-photobook');
        if (pbChkEdit) pbChkEdit.checked = !!item.photobook;
        updateModalTotal(); // recalculate with restored values
    }

    // Set res_id so traitement_devis.php does UPDATE instead of INSERT
    const resIdInput = document.getElementById('modal-res-id');
    if (resIdInput) resIdInput.value = item.res_id || '';

    // Mark this modal as editing cart item at index idx
    document.getElementById('modal-overlay').dataset.editIdx = idx;
}

function renderCart() {
    const body = document.getElementById('cart-items');
    const empty = document.getElementById('cart-empty');
    const footer = document.getElementById('cart-footer');
    body.innerHTML = '';
    if (cart.length === 0) {
        empty.style.display = 'flex';
        footer.style.display = 'none';
        return;
    }
    empty.style.display = 'none';
    footer.style.display = 'block';
    let grand = 0;
    cart.forEach((item, idx) => {
        grand += item.total;
        const div = document.createElement('div');
        div.className = 'cart-item';
        let rows = '';
        if (item.cameras) rows += `<div class="cart-item-row"><span>📹 ${item.cameras}</span><span>${item.cam_price} DT</span></div>`;
        if (item.drone)   rows += `<div class="cart-item-row"><span>🚁 Drone</span><span>+400 DT</span></div>`;
        if (item.girafe)  rows += `<div class="cart-item-row"><span>🦒 Girafe photography</span><span>+450 DT</span></div>`;
        if (item.photobook) rows += `<div class="cart-item-row"><span>📒 Photo book</span><span>+${item.type==='mariage'?250:150} DT</span></div>`;
        rows += `<div class="cart-item-row"><span>📸 Appareil photo</span><span style="color:#4caf7d;">Inclus</span></div>`;
        rows += `<div class="cart-item-row"><span>📀 Photo illimité clé USB</span><span style="color:#4caf7d;">Inclus</span></div>`;
        div.innerHTML = `
            <div class="cart-item-header">
                <span class="cart-item-name">${item.name}</span>
                <button class="cart-item-delete" onclick="removeFromCart(${idx})" title="Supprimer">✕</button>
            </div>
            ${rows}
            <div class="cart-item-total">
                <span class="cart-item-total-lbl">Total</span>
                <span class="cart-item-total-price">${item.total} DT</span>
            </div>
            ${item.date ? `<div class="cart-item-date">📅 ${item.date}</div>` : ''}
            <button class="cart-edit-btn" onclick="cartEditItem(${idx});">✏️ Modifier</button>
        `;
        body.appendChild(div);
    });
    document.getElementById('cart-grand-total').textContent = grand + ' DT';
}

function cartSaveFromModal() {
    const pkg    = document.getElementById('modal-type-original')?.value || document.getElementById('modal-package-input')?.value || '';
    const title  = document.getElementById('modal-package-label')?.innerText || pkg;
    const date   = document.getElementById('m_date_soiree')?.value || '';
    const cam    = document.getElementById('modal-cam-2')?.checked ? 2 : 1;
    const drone  = document.getElementById('modal-drone')?.checked || false;
    const girafe = document.getElementById('modal-girafe')?.checked || false;
    const isWedding = ['wtiya','henna','mariage'].includes(pkg);
    const isShooting = ['shoot-ind','shoot-mar'].includes(pkg);

    const photobook = document.getElementById('modal-photobook')?.checked || false;
    const pbType = document.getElementById('modal-type-original')?.value || '';
    let total = isWedding ? 450 : 250;
    let cam_price = null;
    let cameras = null;

    if (isWedding) {
        cam_price = cam === 1 ? 450 : 700;
        cameras = cam === 1 ? '1 caméra' : '2 caméras';
        total = cam_price;
        if (drone)     total += 400;
        if (girafe)    total += 450;
        if (photobook) total += (pbType === 'mariage' ? 250 : 150);
    }

    // Get reservation ID if editing
    const resId = document.getElementById('modal-res-id')?.value || null;

    // Get edit index if modifying existing
    const editIdx = document.getElementById('modal-overlay').dataset.editIdx;
    const newItem = {
        res_id: resId,
        name: pkg === 'shoot-ind' ? 'Shooting Individuel' : pkg === 'shoot-mar' ? 'Shooting Mariage' : title.split('—')[0].trim(),
        type: pkg,
        date: date,
        nom_homme: document.getElementById('m_nom_homme')?.value || '',
        nom_femme: document.getElementById('m_nom_femme')?.value || '',
        telephone: document.getElementById('m_telephone')?.value || '',
        cameras: cameras,
        cam_price: cam_price,
        drone: isWedding ? drone : false,
        girafe: isWedding ? girafe : false,
        photobook: isWedding ? photobook : false,
        total: total,
    };
    if (editIdx !== undefined && editIdx !== '' && cart[editIdx] !== undefined) {
        // Keep original name from cart item (don't re-derive from garbled label)
        newItem.name = cart[editIdx].name;
        cart[editIdx] = newItem;
        delete document.getElementById('modal-overlay').dataset.editIdx;
        saveCart();
        updateCartCount();
        // Pop animation to signal update
        const countEl = document.getElementById('cart-count');
        if (countEl) { countEl.classList.add('pop'); setTimeout(() => countEl.classList.remove('pop'), 400); }
    } else {
        addToCart(newItem);
    }
}

// Init on load
updateCartCount();

// After form submission, update cart item with real DB res_id
(function() {
    const params = new URLSearchParams(window.location.search);
    const retId  = parseInt(params.get('res_id') || '0');
    const updated = params.get('updated') === '1';
    if (!retId) return;

    if (updated) {
        // Modification — res_id already in cart, just refresh display
        renderCart && renderCart();
    } else {
        // New INSERT — find cart item with no res_id and assign the new DB id
        let changed = false;
        cart.forEach(item => {
            if (!item.res_id) { item.res_id = retId; changed = true; }
        });
        if (changed) { saveCart(); updateCartCount(); }
    }
    // Clean URL
    const url = new URL(window.location);
    url.searchParams.delete('res_id');
    url.searchParams.delete('updated');
    history.replaceState({}, '', url);
})();

// ── TUNISIA DATA — Gouvernorats & Délégations ───────────────────────────────
const TUNISIA_DATA = {
  "Ariana":["Ariana Ville","Ettadhamen","Kalâat el-Andalous","La Soukra","Mnihla","Raoued","Sidi Thabet"],
  "Béja":["Amdoun","Béja Nord","Béja Sud","Goubellat","Medjez el-Bab","Nefza","Téboursouk","Testour","Thibar"],
  "Ben Arous":["Ben Arous","Bou Mhel el-Bassatine","El Mourouj","Ezzahra","Fouchana","Hammam Chott","Hammam Lif","Khalidia","Medina Jedida","Mégrine","Mohamedia","Mornag","Nouvelle Medina","Radès"],
  "Bizerte":["Bizerte Nord","Bizerte Sud","El Alia","Ghar El Melh","Ghezala","Joumine","Mateur","Menzel Bourguiba","Menzel Jemil","Ras Jebel","Sejnane","Tinja","Utique"],
  "Gabès":["El Hamma","Gabès Medina","Gabès Ouest","Gabès Sud","Ghannouch","Mareth","Matmata","Menzel El Habib","Métouia","Nouvelle Matmata"],
  "Gafsa":["Belkhir","El Guettar","El Ksar","Gafsa Nord","Gafsa Sud","Mdhilla","Métlaoui","Moularès","Redeyef","Sened","Sidi Aïch"],
  "Jendouba":["Aïn Draham","Balta-Bou Aouane","Bou Salem","El Jendouba","El Jendouba Nord","Fernana","Ghardimaou","Oued Meliz","Tabarka"],
  "Kairouan":["Bou Hajla","Chebika","Cherarda","El Ouslatia","Haffouz","Hajeb El Ayoun","Kairouan Nord","Kairouan Sud","Nasrallah","Oued Haffouz","Sbikha"],
  "Kasserine":["Aïn Jedey","El Ayoun","Ezzouhour","Foussana","Fériana","Hassi El Frid","Hidra","Jedelienne","Kasserine Nord","Kasserine Sud","Majel Bel Abbès","Sbeïtla","Sbitla","Thélepte"],
  "Kébili":["Douz Nord","Douz Sud","El Faouar","Kébili Nord","Kébili Sud","Souk Lahad"],
  "Kef":["Dahmani","El Ksour","Jerissa","Kef Est","Kef Ouest","Nebeur","Sakiet Sidi Youssef","Sers","Tajerouine","Touiref"],
  "Mahdia":["Bou Merdes","Chebba","Chorbane","El Bradaa","Essouassi","Hebira","Ksour Essaf","Mahdia","Melloulèche","Ouled Chamekh","Sidi Alouane"],
  "Manouba":["Borj El Amri","Den Den","Douar Hicher","El Battan","Jedaïda","Manouba","Mornaguia","Oued Ellil","Tebourba"],
  "Médenine":["Ben Gardane","Beni Khedache","Djerba Ajim","Djerba Houmt Souk","Djerba Midoun","Médenine Nord","Médenine Sud","Sidi Makhlouf","Zarzis"],
  "Monastir":["Bekalta","Bembla","Beni Hassen","Jammel","Ksar Hellal","Ksibet El Mediouni","Moknine","Monastir","Ouerdanine","Sahline","Sayada-Lamta-Bou Hajar","Téboulba","Zeramdine"],
  "Nabeul":["Beni Khalled","Beni Khiar","Bou Argoub","Dar Chaabane","El Haouaria","El Mida","Grombalia","Hammamet","Kelibia","Korba","Kélibia","Menzel Bouzelfa","Menzel Temime","Nabeul","Soliman","Takelsa"],
  "Sfax":["Agareb","Bir Ali Ben Khalifa","El Amra","El Hencha","Ghraïba","Jebiniana","Kerkennah","Mahres","Menzel Chaker","Sakiet Eddaïer","Sakiet Ezzit","Sfax Est","Sfax Médina","Sfax Ouest","Sfax Sud","Skhira","Thyna","Bir Ali Ben Khalifa"],
  "Sidi Bouzid":["Bir El Hafey","El Meknassi","Jilma","Mazzouna","Menzel Bouzaiane","Mezouna","Ouled Haffouz","Regueb","Sidi Ali Ben Aoun","Sidi Bouzid Est","Sidi Bouzid Ouest","Souk Jedid"],
  "Siliana":["Bargou","Bou Arada","El Aroussa","El Krib","Gaâfour","Kesra","Makthar","Rohia","Sidi Morched","Siliana Nord","Siliana Sud"],
  "Sousse":["Akouda","Bouficha","Enfidha","Hergla","Hammam Sousse","Kalaa Kebira","Kalaa Sghira","Kondar","M'saken","Sidi Bou Ali","Sidi El Heni","Sousse Jaouhara","Sousse Médina","Sousse Riadh","Sousse Sidi Abdelhamid","Zaouiet Sousse"],
  "Tataouine":["Bir Lahmar","Dhehiba","Ghomrassen","Remada","Smar","Tataouine Nord","Tataouine Sud"],
  "Tozeur":["Degache","Hazoua","Nefta","Tamerza","Tozeur"],
  "Tunis":["Bab Bhar","Bab Souika","Carthage","El Kabaria","El Kram","El Menzah","El Omrane","El Omrane Supérieur","El Ouardia","Ettahrir","Ezzouhour","La Goulette","La Marsa","La Médina","Le Bardo","Séjoumi","Sidi El Béchir","Sidi Hassine"],
  "Zaghouan":["Bir Mcherga","El Fahs","Nadhour","Saouaf","Zaghouan","Zriba"]
};

function populateGovSelects() {
    const govs = Object.keys(TUNISIA_DATA).sort();
    // Modal select
    const modalGov = document.getElementById('modal-transport-gov');
    if (modalGov) {
        const ph = modalGov.options[0];
        modalGov.innerHTML = '';
        modalGov.appendChild(ph);
        govs.forEach(g => { const o = document.createElement('option'); o.value = g; o.textContent = g; modalGov.appendChild(o); });
    }
    // Card selects
    document.querySelectorAll('.cfg-gov-select').forEach(sel => {
        const first = sel.options[0];
        sel.innerHTML = '';
        sel.appendChild(first);
        govs.forEach(g => { const o = document.createElement('option'); o.value = g; o.textContent = g; sel.appendChild(o); });
    });
}

function updateDelegations() {
    const gov = document.getElementById('modal-transport-gov')?.value;
    const delSel = document.getElementById('modal-transport-del');
    if (!delSel) return;
    delSel.innerHTML = '<option value="">-- Délégation --</option>';
    if (gov && TUNISIA_DATA[gov]) {
        TUNISIA_DATA[gov].forEach(d => { const o = document.createElement('option'); o.value = d; o.textContent = d; delSel.appendChild(o); });
    }
}

function updateCardDelegations(govSel) {
    const type = govSel.dataset.type;
    const delSel = document.getElementById('cfg-del-' + type);
    if (!delSel) return;
    delSel.innerHTML = '<option value="">-- Délégation --</option>';
    const gov = govSel.value;
    if (gov && TUNISIA_DATA[gov]) {
        TUNISIA_DATA[gov].forEach(d => { const o = document.createElement('option'); o.value = d; o.textContent = d; delSel.appendChild(o); });
    }
}

document.addEventListener('DOMContentLoaded', populateGovSelects);

// ── EmailJS : envoi email à l'admin à chaque nouvelle réservation ──────────
function syncLocalisationToForm() {
    // For wedding types, localisation is in the cam-group selects
    // For shooting/event, it's in the standalone modal-loc-* selects
    const type = document.getElementById('modal-type-original')?.value || '';
    const isWedding = ['wtiya','henna','mariage'].includes(type);
    let gov = '', del = '';
    if (isWedding) {
        gov = document.getElementById('modal-transport-gov')?.value || '';
        del = document.getElementById('modal-transport-del')?.value || '';
    } else {
        gov = document.getElementById('modal-loc-gov')?.value || '';
        del = document.getElementById('modal-loc-del')?.value || '';
    }
    const hGov = document.getElementById('modal-hidden-gov');
    const hDel = document.getElementById('modal-hidden-del');
    if (hGov) hGov.value = gov;
    if (hDel) hDel.value = del;
}

function updateLocDelegations() {
    const govSel = document.getElementById('modal-loc-gov');
    const delSel = document.getElementById('modal-loc-del');
    if (!govSel || !delSel) return;
    const gov = govSel.value;
    delSel.innerHTML = '<option value="">-- Délégation --</option>';
    if (gov && window.tunisiaData && tunisiaData[gov]) {
        tunisiaData[gov].forEach(d => {
            const opt = document.createElement('option');
            opt.value = d; opt.textContent = d;
            delSel.appendChild(opt);
        });
    }
}

// Opens modal for shooting cards and pre-fills localisation from the card selects
function openModalFromCard(label, value, cardId) {
    openModal(label, value);
    // Sync card's gouvernorat/délégation to the standalone modal-loc selects
    const cardGov = document.querySelector(`.cfg-gov-select[data-type="${cardId}"]`);
    const cardDel = document.getElementById('cfg-del-' + cardId);
    const modalGov = document.getElementById('modal-loc-gov');
    const modalDel = document.getElementById('modal-loc-del');
    if (cardGov && modalGov && cardGov.value) {
        modalGov.value = cardGov.value;
        updateLocDelegations();
        setTimeout(() => {
            if (cardDel && modalDel) modalDel.value = cardDel.value;
        }, 50);
    }
}

function envoyerEmailJS() {
    const pkg      = document.getElementById('modal-type-original')?.value || '';
    const isWedding = ['wtiya','henna','mariage'].includes(pkg);

    const typeLabels = {
        'wtiya':'Wtiya','henna':'Henna','mariage':'Mariage',
        'shoot-ind':'Shooting Individuel','shoot-mar':'Shooting Mariage','event':'Event Photography'
    };

    const nom_h  = document.getElementById('m_nom_homme')?.value || '';
    const nom_f  = document.getElementById('m_nom_femme')?.value || '';
    const nom    = (nom_h + ' ' + nom_f).trim() || 'Non renseigné';
    const tel    = document.getElementById('m_telephone')?.value || '';
    const cin    = document.getElementById('m_cin')?.value || '';
    const date   = document.getElementById('m_date_soiree')?.value || '';
    const total  = document.getElementById('modal-total-price')?.value || '0';
    const pkgTxt = document.getElementById('modal-package-label')?.innerText || '';
    const type   = typeLabels[pkg] || pkg;

    // Gouvernorat / délégation
    const gov = document.getElementById('modal-transport-gov')?.value || '';
    const del = document.getElementById('modal-transport-del')?.value || '';
    const loc = gov ? (del ? gov + ' / ' + del : gov) : 'Non renseigné';

    emailjs.send('service_devcjn9', 'template_bxij247', {
        type:      type,
        nom:       nom,
        telephone: tel,
        cin:       cin,
        date:      date,
        package:   pkgTxt,
        prix:      total + ' DT',
        lieu:      loc,
    }).catch(function(err) {
        console.warn('EmailJS error:', err);
    });
}
</script>

</body>
</html>