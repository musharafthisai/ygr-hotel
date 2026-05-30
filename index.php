<?php
$config = [
  'brand'=>'YGR Royal Dum Biryani','tagline'=>'Taste the Royal Flavor of Authentic Biryani',
  'phone'=>'+91 XXXXX XXXXX','whatsapp'=>'91XXXXXXXXXX','email'=>'info@ygrbiryani.com',
  'address'=>'Chennai, Tamil Nadu','hours'=>'11:00 AM – 11:00 PM','login_url'=>'login.php',
];
$menu = [
  ['name'=>'Chicken Biryani','price'=>'₹180','tag'=>'Best Seller','desc'=>'Juicy chicken dum-cooked with aromatic basmati and signature spices.','tags'=>['Halal','Dum Cooked','Aromatic','Premium'],'img'=>'assets/img/chicken-biryani.jpg','dish'=>'chicken'],
  ['name'=>'Mutton Biryani','price'=>'₹260','tag'=>'Royal Pick','desc'=>'Tender mutton slow-cooked with layered masala perfection.','tags'=>['Slow Cooked','Rich Masala','Tender','Signature'],'img'=>'assets/img/mutton-biryani.jpg','dish'=>'mutton'],
  ['name'=>'Hyderabadi Biryani','price'=>'₹220','tag'=>'Heritage','desc'=>'Classic royal Hyderabadi spice with saffron-kissed basmati.','tags'=>['Saffron','Heritage','Spicy','Royal'],'img'=>'assets/img/hyderabadi-biryani.jpg','dish'=>'hyderabadi'],
  ['name'=>'Bucket Biryani','price'=>'₹899','tag'=>'Party Pack','desc'=>'Generous family feast for gatherings.','tags'=>['Family','Party','Value','8 Servings'],'img'=>'assets/img/bucket-biryani.jpg','dish'=>'bucket'],
  ['name'=>'Chicken 65','price'=>'₹160','tag'=>'Starter','desc'=>'Crispy fried chicken bites served sizzling hot.','tags'=>['Crispy','Spicy','Fried','Hot'],'img'=>'assets/img/chicken65.jpg','dish'=>'starter1'],
  ['name'=>'Grill & Tandoori','price'=>'₹280','tag'=>'Smoky','desc'=>'Smoky charcoal-grilled kebabs.','tags'=>['Charcoal','Smoky','Kebab'],'img'=>'assets/img/grill.jpg','dish'=>'starter2'],
];
$reviews = [
  ['text'=>'Best biryani in Chennai!','name'=>'Priya S.','loc'=>'Adyar','stars'=>5],
  ['text'=>'Mutton biryani melts in your mouth!','name'=>'Karthik M.','loc'=>'T.Nagar','stars'=>5],
  ['text'=>'Everyone loved the bucket biryani!','name'=>'Suresh K.','loc'=>'Tambaram','stars'=>5],
  ['text'=>'Amazing flavor every time.','name'=>'Anitha R.','loc'=>'Velachery','stars'=>5],
  ['text'=>'Hyderabadi biryani is pure nostalgia.','name'=>'Deepa V.','loc'=>'Anna Nagar','stars'=>5],
  ['text'=>'Catering was spot-on and delicious.','name'=>'Raj T.','loc'=>'Chromepet','stars'=>5],
];
?><!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="theme-color" content="#f5efe2">
<meta name="description" content="<?=$config['brand']?> — <?=$config['tagline']?>. Serving authentic dum biryani in Chennai since 2014.">
<meta property="og:title" content="<?=$config['brand']?>">
<meta property="og:description" content="<?=$config['tagline']?>">
<meta property="og:type" content="website">
<meta property="og:image" content="assets/img/logo.png">
<meta name="twitter:card" content="summary">
<link rel="icon" href="assets/img/logo.png">
<title><?=$config['brand']?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://cdnjs.cloudflare.com">
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
<style>
:root{
  --bg:#f5efe2;--bg-2:#ede6d6;--ink:#141416;--ink-soft:#2b2b30;
  --mute:#8b847a;--line:#dad2bf;--ghost:#c5bfae;
  --orange-1:#d9351f;--orange-2:#e85a2b;--orange-3:#f58b4e;--orange-4:#fdc68a;
  --gold:#C9922B;--gold-l:#E8C060;
  --cream:#FAF0E0;--white:#fff;
  --font-h:'DM Sans',system-ui,sans-serif;
  --font-b:'DM Sans',system-ui,sans-serif;
  --ease-out:cubic-bezier(0.6,0,0.2,1);
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html,body{background:var(--bg);color:var(--ink);font-family:var(--font-b);overflow-x:hidden;-webkit-font-smoothing:antialiased}
body{background:radial-gradient(120% 70% at 50% 0%,#f8f3e7 0%,#f2ead6 60%,#e9dec3 100%);min-height:100vh}
img{max-width:100%;display:block}a{color:inherit;text-decoration:none}

/* GRAIN */
.grain{position:fixed;inset:0;pointer-events:none;z-index:100;opacity:0.06;mix-blend-mode:multiply;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='220' height='220'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0.1  0 0 0 0 0.1  0 0 0 0 0.1  0 0 0 0.6 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");background-repeat:repeat;background-size:220px 220px}

/* NAV */
.nav{position:fixed;top:0;left:0;right:0;z-index:50;padding:28px 48px;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:10px;font-weight:700;letter-spacing:-0.02em;font-size:16px}
.logo-dot{width:20px;height:20px;border-radius:50%;background:linear-gradient(180deg,var(--orange-1),var(--orange-4));box-shadow:0 4px 8px -2px rgba(220,60,30,0.4),inset 0 1px 0 rgba(255,255,255,0.4),inset 0 -2px 3px rgba(120,20,0,0.3)}
.nav-links{list-style:none;display:flex;gap:30px}
.nav-links a{color:var(--ink-soft);text-decoration:none;font-size:14px;font-weight:500;position:relative;transition:color 0.3s}
.nav-links a:hover{color:var(--ink)}
.nav-links a::after{content:"";position:absolute;left:0;bottom:-3px;width:0;height:1.5px;background:currentColor;border-radius:2px;transition:width 0.4s var(--ease-out)}
.nav-links a:hover::after{width:100%}
.nav-cta{background:var(--ink);color:#fafaf7;border:none;padding:12px 22px;border-radius:999px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px;box-shadow:0 12px 20px -8px rgba(0,0,0,0.4),0 4px 8px -2px rgba(0,0,0,0.18),inset 0 1px 0 rgba(255,255,255,0.12),inset 0 -2px 4px rgba(0,0,0,0.4);transition:transform 0.4s var(--ease-out)}
.nav-cta:hover{transform:translateY(-2px)}
.nav.scrolled{background:rgba(245,239,226,0.92);backdrop-filter:blur(24px);height:64px!important;border-bottom:1px solid var(--line)}
.mobile-menu-btn{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:8px}
.mobile-menu-btn span{width:24px;height:2px;background:var(--ink);border-radius:2px;transition:all 0.3s}
.mobile-menu-btn.active span:nth-child(1){transform:rotate(45deg) translate(5px,5px)}
.mobile-menu-btn.active span:nth-child(2){opacity:0}
.mobile-menu-btn.active span:nth-child(3){transform:rotate(-45deg) translate(5px,-5px)}
.mobile-drawer{position:fixed;top:0;right:0;width:280px;height:100vh;z-index:49;transform:translateX(100%);background:rgba(245,239,226,0.96);backdrop-filter:blur(40px);padding:100px 32px 40px;display:flex;flex-direction:column;gap:8px}
.drawer-overlay{position:fixed;inset:0;z-index:48;background:rgba(0,0,0,0.3);opacity:0;pointer-events:none;transition:opacity 0.3s}
.drawer-overlay.open{opacity:1;pointer-events:all}

/* HERO */
.hero{position:relative;min-height:100vh;padding:130px 32px 60px;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:5}
.small-team{font-family:var(--font-h);font-weight:700;font-size:clamp(40px,6.4vw,92px);letter-spacing:-0.035em;line-height:1;color:var(--ink);text-align:center;margin-bottom:0;z-index:4;position:relative}
.small-team .word{display:inline-block;overflow:hidden;vertical-align:top}
.small-team .word>span{display:inline-block;transform:translateY(105%)}
.big-results-wrap{position:relative;width:100%;margin-top:-20px;display:flex;justify-content:center;z-index:1}
.big-results{font-family:var(--font-h);font-style:italic;font-weight:800;font-size:clamp(110px,21vw,300px);letter-spacing:-0.045em;line-height:0.85;color:var(--ghost);text-align:center;white-space:nowrap;user-select:none;z-index:1;text-shadow:0 4px 12px rgba(120,100,50,0.04)}
.big-results .letter{display:inline-block;transform-origin:bottom}
.cards-row{position:absolute;left:0;right:0;top:50%;height:320px;z-index:3;pointer-events:none}
.cards-row>*{pointer-events:auto}
.card{position:absolute;border-radius:18px;overflow:hidden;background:linear-gradient(180deg,var(--orange-1) 0%,var(--orange-2) 35%,var(--orange-3) 70%,var(--orange-4) 100%);box-shadow:0 30px 50px -16px rgba(180,50,20,0.5),0 14px 26px -8px rgba(140,40,10,0.32),0 5px 10px -2px rgba(80,20,0,0.2),inset 0 2px 0 rgba(255,220,180,0.45),inset 0 -4px 8px rgba(120,20,0,0.4);cursor:pointer;will-change:transform;transition:box-shadow 0.5s var(--ease-out)}
.card::before{content:"";position:absolute;inset:0;background:linear-gradient(155deg,rgba(255,255,255,0.25) 0%,transparent 30%,transparent 70%,rgba(100,20,0,0.25) 100%);pointer-events:none;border-radius:inherit;z-index:2}
.card img{width:100%;height:100%;object-fit:cover;object-position:center top;display:block}
.card:hover{box-shadow:0 44px 70px -20px rgba(180,50,20,0.6),0 22px 38px -10px rgba(140,40,10,0.38),0 8px 14px -3px rgba(80,20,0,0.22),inset 0 2px 0 rgba(255,220,180,0.5),inset 0 -4px 8px rgba(120,20,0,0.4)}

/* Card positions */
.card-1{width:130px;height:180px;left:4%;top:30px;z-index:1}
.card-2{width:160px;height:220px;left:12%;top:50px;z-index:2}
.card-3{width:200px;height:270px;left:22%;top:20px;z-index:4}
.card-4{width:150px;height:200px;left:36%;top:70px;z-index:3}
.card-5{width:230px;height:310px;left:44%;top:0;z-index:5}
.card-6{width:160px;height:215px;left:59%;top:55px;z-index:3}
.card-7{width:175px;height:240px;left:70%;top:30px;z-index:4}
.card-8{width:130px;height:175px;left:84%;top:50px;z-index:2}

/* Subline */
.subline{margin-top:180px;text-align:center;z-index:4;position:relative}
.subline .arrow-pill{display:inline-flex;align-items:center;gap:10px;background:var(--ink);color:#fafaf7;border:none;padding:12px 20px 12px 24px;border-radius:999px;font-family:inherit;font-size:14px;font-weight:500;cursor:pointer;box-shadow:0 14px 24px -8px rgba(0,0,0,0.4),0 5px 10px -2px rgba(0,0,0,0.2),inset 0 1px 0 rgba(255,255,255,0.12),inset 0 -2px 4px rgba(0,0,0,0.4);transition:transform 0.4s var(--ease-out)}
.subline .arrow-pill:hover{transform:translateY(-2px)}
.subline .arrow-pill .ar{width:26px;height:26px;border-radius:50%;background:linear-gradient(180deg,var(--orange-1),var(--orange-3));display:flex;align-items:center;justify-content:center;color:#fff;transition:transform 0.4s}
.subline .arrow-pill:hover .ar{transform:rotate(45deg)}
.subline-text{margin-top:22px;font-size:13px;color:var(--mute);letter-spacing:0.06em}

/* GLASS (for sections below) */
.glass{background:rgba(255,255,255,0.6);backdrop-filter:blur(12px) saturate(180%);-webkit-backdrop-filter:blur(12px) saturate(180%);border:1px solid rgba(201,146,43,0.25);position:relative;overflow:hidden}
.glass::before{content:"";position:absolute;inset:0;border-radius:inherit;padding:1.5px;background:linear-gradient(135deg,rgba(201,146,43,0.50) 0%,rgba(201,146,43,0.15) 30%,rgba(255,255,255,0.1) 50%,rgba(201,146,43,0.15) 70%,rgba(201,146,43,0.50) 100%);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none}
.glass-strong{background:rgba(255,255,255,0.75);backdrop-filter:blur(40px) saturate(200%);-webkit-backdrop-filter:blur(40px) saturate(200%);border:1px solid rgba(201,146,43,0.35);box-shadow:0 8px 32px rgba(0,0,0,0.08);position:relative;overflow:hidden}

.scroll-indicator{position:absolute;bottom:12px;left:50%;transform:translateX(-50%);z-index:5;display:flex;flex-direction:column;align-items:center;gap:4px;opacity:0.5;transition:opacity 0.3s}
.scroll-indicator span{font:300 8px var(--font-b);color:var(--mute);letter-spacing:0.15em;text-transform:uppercase}
@keyframes scroll-drop{0%,100%{transform:translateY(-100%);opacity:0}20%{opacity:1}80%{opacity:1}100%{transform:translateY(100%);opacity:0}}
.scroll-line{width:1px;height:20px;background:linear-gradient(to bottom,var(--orange-1),transparent);animation:scroll-drop 2s ease-in-out infinite}

/* TEAM */
.team-section{padding:140px 40px 80px;position:relative;z-index:5}
.team-head{max-width:1280px;margin:0 auto 60px;display:flex;justify-content:space-between;align-items:flex-end;gap:40px;flex-wrap:wrap}
.eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:11px;letter-spacing:0.28em;text-transform:uppercase;color:var(--mute);font-weight:500;margin-bottom:16px}
.eyebrow::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--orange-1);box-shadow:0 0 0 3px rgba(217,53,31,0.2)}
.team-head h2{font-family:var(--font-h);font-weight:700;font-size:clamp(34px,4.4vw,64px);line-height:1;letter-spacing:-0.03em;max-width:600px}
.team-head h2 em{font-style:italic;font-weight:800;background:linear-gradient(135deg,var(--orange-1),var(--orange-3));-webkit-background-clip:text;background-clip:text;color:transparent}
.team-head p{font-size:15px;line-height:1.55;color:var(--ink-soft);max-width:320px}
.team-grid{max-width:1280px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:24px}
.t-card{aspect-ratio:3/4;border-radius:22px;overflow:hidden;background:linear-gradient(180deg,var(--orange-1) 0%,var(--orange-2) 35%,var(--orange-3) 70%,var(--orange-4) 100%);position:relative;box-shadow:0 24px 40px -16px rgba(180,50,20,0.4),0 10px 22px -6px rgba(140,40,10,0.22),inset 0 2px 0 rgba(255,220,180,0.4),inset 0 -3px 6px rgba(120,20,0,0.3);cursor:pointer;transition:transform 0.5s var(--ease-out),box-shadow 0.5s}
.t-card::before{content:"";position:absolute;inset:0;background:linear-gradient(155deg,rgba(255,255,255,0.22) 0%,transparent 30%,transparent 70%,rgba(100,20,0,0.22) 100%);pointer-events:none;z-index:2}
.t-card:hover{transform:translateY(-10px) rotate(-1deg)}
.t-card img{width:100%;height:100%;object-fit:cover;object-position:center top}
.t-meta{position:absolute;left:14px;right:14px;bottom:14px;background:rgba(20,10,6,0.7);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);color:#fafaf7;padding:12px 16px;border-radius:14px;z-index:3;transform:translateY(8px);opacity:0;transition:opacity 0.4s,transform 0.4s var(--ease-out);border:1px solid rgba(255,255,255,0.12)}
.t-card:hover .t-meta{transform:translateY(0);opacity:1}
.t-meta .nm{font-weight:700;font-size:14px}
.t-meta .rl{font-size:11px;color:rgba(255,255,255,0.65);margin-top:2px;letter-spacing:0.03em}

/* STATS */
.stats{padding:80px 40px 140px;position:relative;z-index:5}
.stats-inner{max-width:1280px;margin:0 auto;background:var(--ink);border-radius:36px;padding:80px 60px;color:#f4edde;display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:40px;align-items:end;box-shadow:0 40px 80px -30px rgba(20,8,0,0.45),0 16px 32px -12px rgba(20,8,0,0.25),inset 0 1px 0 rgba(255,255,255,0.08),inset 0 -3px 8px rgba(0,0,0,0.5);position:relative;overflow:hidden}
.stats-inner::before{content:"";position:absolute;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(232,90,43,0.35),transparent 65%);top:-200px;right:-100px;filter:blur(40px)}
.stats-inner::after{content:"";position:absolute;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(253,198,138,0.2),transparent 65%);bottom:-150px;left:20%;filter:blur(30px)}
.stats h3{font-family:var(--font-h);font-weight:700;font-size:clamp(28px,3vw,42px);line-height:1.05;letter-spacing:-0.03em;position:relative;z-index:1}
.stats h3 em{font-style:italic;font-weight:800;background:linear-gradient(135deg,var(--orange-3),var(--orange-4));-webkit-background-clip:text;background-clip:text;color:transparent}
.stat-block{position:relative;z-index:1}
.stat-block .num{font-family:var(--font-h);font-weight:700;font-size:clamp(44px,4.5vw,68px);line-height:1;letter-spacing:-0.04em;display:flex;align-items:baseline;gap:4px}
.stat-block .num small{font-size:0.42em;color:var(--mute);font-weight:500}
.stat-block .lbl{font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:var(--mute);margin-top:14px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.08)}

/* ABOUT */
#about{min-height:100vh;background:var(--bg-2);padding:clamp(80px,12vh,140px) clamp(20px,5vw,80px);position:relative;overflow:hidden}
.about-bg-text{position:absolute;right:-60px;top:50%;transform:translateY(-50%);font:italic 700 28vw var(--font-h);color:rgba(201,146,43,0.04);pointer-events:none;user-select:none}
.orbit-ring{position:absolute;border-radius:50%;border:1px dashed rgba(201,146,43,0.25)}
.orbit-ring-1{width:320px;height:320px;animation:orbit-cw 22s linear infinite}
.orbit-ring-2{width:460px;height:460px;animation:orbit-ccw 35s linear infinite}
@keyframes orbit-cw{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes orbit-ccw{from{transform:rotate(0deg)}to{transform:rotate(-360deg)}}

#menu{min-height:100vh;background:var(--bg);padding:clamp(80px,12vh,120px) 0 clamp(60px,8vh,100px);position:relative;overflow:hidden}
#menu-particles{position:absolute;inset:0;pointer-events:none;z-index:0}
.menu-slide{padding:12px 0}
.menu-card{min-height:460px;display:flex;flex-direction:column;position:relative;overflow:hidden;cursor:pointer}
.card-glow{position:absolute;inset:-50%;border-radius:50%;background:radial-gradient(ellipse,rgba(201,146,43,0.15),transparent 70%);pointer-events:none;opacity:0;transition:opacity 0.5s;z-index:0}
.swiper-pagination-bullet{background:var(--gold)!important}
.swiper-button-next,.swiper-button-prev{color:var(--gold)!important}

#process{min-height:80vh;background:var(--bg-2);padding:clamp(80px,12vh,120px) clamp(20px,5vw,80px)}
.process-step{border-radius:20px;padding:28px 24px;margin:0 8px;position:relative;z-index:1;text-align:center;transition:transform 0.3s var(--ease-out),box-shadow 0.3s}
.process-step:hover{transform:translateY(-8px);box-shadow:0 20px 60px rgba(201,146,43,0.12)}

#why-us{min-height:60vh;background:var(--bg);padding:clamp(80px,12vh,120px) clamp(20px,5vw,80px)}
.why-card{min-height:320px;display:flex;flex-direction:column;transition:transform 0.3s var(--ease-out)}

#reviews{padding:clamp(80px,12vh,120px) 0;background:var(--bg);color:var(--ink);overflow:hidden}
.review-card{border-radius:20px;padding:28px;width:320px;flex-shrink:0}
@keyframes ticker-scroll{from{transform:translateX(0)}to{transform:translateX(-50%)}}
.reviews-track{display:flex;gap:24px;width:max-content;animation:ticker-scroll 50s linear infinite}
.reviews-track:hover{animation-play-state:paused}

#catering{padding:clamp(80px,12vh,120px) clamp(20px,5vw,80px);background:var(--bg-2);text-align:center}
#contact{padding:clamp(80px,12vh,120px) clamp(20px,5vw,80px);background:var(--bg)}
footer{border-top:1px solid rgba(201,146,43,0.12);padding:clamp(48px,6vw,72px) clamp(20px,5vw,80px);background:var(--bg)}

@media(max-width:1024px){.about-left + div{display:none}.team-grid{grid-template-columns:repeat(3,1fr)}.stats-inner{grid-template-columns:1fr 1fr;padding:50px 30px}}
@media(max-width:768px){
  .nav{padding:14px 18px}.nav-links{display:none!important}.mobile-menu-btn{display:flex!important}
  .hero{padding:90px 12px 36px}.small-team{font-size:10vw}
  .big-results{font-size:24vw}.cards-row{height:200px;top:53%}
  .card-1{width:70px;height:100px;left:2%;top:18px}
  .card-2{width:80px;height:115px;left:12%;top:30px}
  .card-3{width:100px;height:135px;left:22%;top:12px}
  .card-4{width:80px;height:110px;left:36%;top:45px}
  .card-5{width:115px;height:160px;left:46%;top:0}
  .card-6{width:85px;height:118px;left:62%;top:35px}
  .card-7{width:90px;height:125px;left:73%;top:18px}
  .card-8{width:70px;height:100px;left:85%;top:30px}
  .subline{margin-top:150px}
  .team-section{padding:100px 20px 60px}
  .team-grid{grid-template-columns:repeat(2,1fr)!important;gap:16px}
  .stats{padding:50px 20px 80px}
  .stats-inner{grid-template-columns:1fr!important;padding:40px 24px;gap:24px}
  .process-grid{grid-template-columns:1fr!important;gap:12px!important}
  .why-grid{grid-template-columns:1fr!important}
  .about-inner{grid-template-columns:1fr!important}.contact-inner{grid-template-columns:1fr!important}
  footer>div:first-child{grid-template-columns:1fr!important;text-align:center!important}
  .menu-card{min-height:auto!important}
  .menu-card .card-img{height:160px!important}
  #reviews{padding:60px 0}
  .review-card{width:280px;padding:22px}
}
@media(max-width:560px){
  .nav{padding:10px 12px}
  .nav .logo img{height:26px}
  .nav-cta{padding:8px 14px;font-size:11px;gap:4px}
  .hero{padding:76px 10px 30px;overflow:hidden}.small-team{font-size:9vw}.big-results{font-size:20vw}
  .cards-row{height:130px;top:54%}
  .card-1{width:44px;height:62px;left:1%;top:10px}
  .card-2{width:50px;height:72px;left:10%;top:20px}
  .card-3{width:62px;height:84px;left:22%;top:6px}
  .card-4{width:50px;height:68px;left:35%;top:26px}
  .card-5{width:72px;height:100px;left:46%;top:0}
  .card-6{width:52px;height:74px;left:60%;top:22px}
  .card-7{width:56px;height:78px;left:72%;top:10px}
  .card-8{width:44px;height:62px;left:84%;top:18px}
  .subline{margin-top:100px}
  .subline .arrow-pill{padding:8px 14px 8px 18px;font-size:11px}
  .subline-text{font-size:10px;margin-top:14px}
  .scroll-indicator{display:none}
  .team-section{padding:60px 14px 30px}
  .team-head{gap:20px;margin-bottom:24px;flex-direction:column;align-items:flex-start}
  .team-head h2{font-size:clamp(24px,7vw,32px)}
  .team-head p{font-size:13px;max-width:100%}
  .stats{padding:24px 14px 50px}
  .stats-inner{padding:28px 18px;border-radius:20px;gap:20px}
  .stats h3{font-size:clamp(20px,6vw,26px)}
  .stat-block .num{font-size:clamp(28px,10vw,38px)}
  .stat-block .lbl{font-size:10px;margin-top:8px;padding-top:8px}
  .team-grid{grid-template-columns:repeat(2,1fr)!important;gap:8px}
  .t-card{aspect-ratio:3/4}
  .why-card{min-height:auto!important;padding:18px}
  .process-step{padding:18px 14px;margin:0}
  .process-step h3{font-size:1rem}
  .menu-card{padding:14px!important}
  .menu-card .card-img{height:120px!important}
  .menu-card h3{font-size:clamp(1.1rem,4vw,1.3rem)!important}
  #reviews{padding:40px 0}
  .review-card{width:200px;padding:16px}
  .contact-inner{gap:20px}
  #catering .glass-strong{padding:30px 20px}
  footer{padding:32px 16px!important}
}
@media(max-width:400px){
  .nav{padding:8px 10px}
  .nav .logo img{height:22px}
  .nav .logo{font-size:13px}
  .nav-cta{padding:6px 10px;font-size:10px}
  .hero{padding:66px 6px 20px;overflow:hidden}
  .small-team{font-size:8vw}.big-results{font-size:16vw}
  .cards-row{height:90px;top:55%}
  .card-1{width:30px;height:42px;left:1%;top:6px}
  .card-2{width:34px;height:50px;left:10%;top:14px}
  .card-3{width:42px;height:60px;left:22%;top:4px}
  .card-4{width:34px;height:46px;left:35%;top:18px}
  .card-5{width:48px;height:68px;left:46%;top:0}
  .card-6{width:36px;height:50px;left:60%;top:14px}
  .card-7{width:38px;height:54px;left:72%;top:6px}
  .card-8{width:30px;height:42px;left:84%;top:12px}
  .subline{margin-top:70px}
  .subline .arrow-pill{padding:6px 12px 6px 14px;font-size:10px}
  .subline-text{font-size:9px;margin-top:10px}
  .team-grid{grid-template-columns:repeat(2,1fr)!important;gap:6px}
  .t-card{aspect-ratio:2/3;border-radius:14px}
  .t-meta{padding:8px 10px;left:8px;right:8px;bottom:8px;border-radius:10px}
  .t-meta .nm{font-size:11px}
  .t-meta .rl{font-size:9px}
  .team-head h2{font-size:clamp(20px,7vw,24px)}
  .team-section{padding:40px 10px 20px}
  .stats-inner{padding:20px 14px;border-radius:16px;gap:14px}
  .stats h3{font-size:clamp(18px,6vw,22px)}
  .stat-block .num{font-size:clamp(24px,8vw,30px)}
  .stat-block .lbl{font-size:9px}
  .why-card{padding:14px}
  .why-card h3{font-size:clamp(1rem,4vw,1.2rem)}
  .process-step{padding:14px 12px}
  .process-step h3{font-size:0.95rem}
  .process-step p{font-size:11px}
  .menu-card{padding:12px!important;border-radius:18px!important}
  .menu-card .card-img{height:90px!important}
  .menu-card h3{font-size:clamp(1rem,4vw,1.1rem)!important}
  .menu-card p{font-size:11px!important}
  .review-card{width:160px;padding:12px}
  .review-card p{font-size:11px}
  .about-bg-text{display:none}
  #contact .glass-strong{padding:22px 16px}
  .contact-inner{gap:16px}
  footer{padding:24px 12px!important}
  footer a{font-size:10px!important}
}
.dark-theme{--bg:#161618;--bg-2:#1c1c20;--ink:#f0efe7;--ink-soft:#b0b0b8;--mute:#6a6a72;--mute2:#3a3a42;--white:#2a2a2e;--cream:#1e1e22}
.dark-theme .glass,.dark-theme.glass{background:rgba(255,255,255,0.06)}
.dark-theme .glass-strong,.dark-theme.glass-strong{background:rgba(255,255,255,0.08);box-shadow:0 8px 32px rgba(0,0,0,0.25)}
.dark-theme .mobile-drawer,.dark-theme.mobile-drawer{background:rgba(22,22,24,0.96)}
.dark-theme .drawer-overlay{background:rgba(0,0,0,0.6)}

/* LOADING OVERLAY */
.loading-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;background:var(--bg);pointer-events:none;overflow:hidden}
.loader-ambient{position:absolute;width:90vmin;height:90vmin;border-radius:50%;background:radial-gradient(circle,rgba(201,146,43,0.1) 0%,transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%) scale(0.85);opacity:0}
.loader-content{position:relative;display:flex;align-items:center;justify-content:center;width:200px;height:200px}
.loader-ring-wrap{position:absolute;inset:0}
.loader-ring-svg{width:100%;height:100%;transform:rotate(-90deg)}
.loader-ring-bg{fill:none;stroke:rgba(201,146,43,0.1);stroke-width:2.5}
.loader-ring-fill{fill:none;stroke:var(--gold);stroke-width:3;stroke-linecap:round;stroke-dasharray:452.389;stroke-dashoffset:452.389;filter:drop-shadow(0 0 8px rgba(201,146,43,0.35))}
.loader-logo-wrap{width:120px;height:120px;display:flex;align-items:center;justify-content:center;opacity:0;transform:scale(0.4)}
.loader-logo{width:100%;height:auto;max-width:110px}
.loader-particles{position:absolute;inset:-50px;pointer-events:none}
.loader-dot{position:absolute;top:50%;left:50%;border-radius:50%;opacity:0;transform:scale(0)}
.loader-dot:nth-child(1){width:5px;height:5px;background:#E8C060;margin:-2.5px 0 0 -2.5px}
.loader-dot:nth-child(2){width:4px;height:4px;background:#C9922B;margin:-2px 0 0 -2px}
.loader-dot:nth-child(3){width:6px;height:6px;background:#D4A017;margin:-3px 0 0 -3px}
.loader-dot:nth-child(4){width:3px;height:3px;background:#E8C060;margin:-1.5px 0 0 -1.5px}
.loader-dot:nth-child(5){width:5px;height:5px;background:#C9922B;margin:-2.5px 0 0 -2.5px}
.loader-dot:nth-child(6){width:4px;height:4px;background:#D4A017;margin:-2px 0 0 -2px}
.loader-text{font:500 10px var(--font-b);color:var(--mute);letter-spacing:0.4em;text-transform:uppercase;margin-top:28px;height:16px;overflow:hidden}
.loader-text .char{display:inline-block;opacity:0;transform:translateY(14px)}
.loader-bar-track{width:180px;height:2px;background:rgba(201,146,43,0.1);border-radius:2px;margin-top:16px;overflow:hidden;position:relative}
.loader-bar-fill{height:100%;width:0%;background:linear-gradient(90deg,var(--gold),var(--gold-l),var(--gold));border-radius:2px;position:relative}
.loader-bar-fill::after{content:'';position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.35),transparent);animation:loader-shimmer 1.2s ease-in-out infinite}
@keyframes loader-shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
.loader-pct{font:600 11px var(--font-b);color:var(--gold);margin-top:8px;letter-spacing:0.12em;font-variant-numeric:tabular-nums}

/* PAGE TRANSITION */
.page-transition{position:fixed;inset:0;z-index:9998;background:var(--bg);transform:translateY(100%);pointer-events:none}
</style>
</head><body>

<!-- LOADING OVERLAY -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loader-ambient" id="loaderAmbient"></div>
  <div class="loader-content">
    <div class="loader-ring-wrap">
      <svg class="loader-ring-svg" viewBox="0 0 160 160">
        <circle class="loader-ring-bg" cx="80" cy="80" r="72"/>
        <circle class="loader-ring-fill" id="loaderRingFill" cx="80" cy="80" r="72"/>
      </svg>
    </div>
    <div class="loader-particles" id="loaderParticles">
      <div class="loader-dot"></div><div class="loader-dot"></div>
      <div class="loader-dot"></div><div class="loader-dot"></div>
      <div class="loader-dot"></div><div class="loader-dot"></div>
    </div>
    <div class="loader-logo-wrap" id="loaderLogoWrap">
      <img src="assets/img/logo.png" alt="YGR Royal Dum Biryani" class="loader-logo">
    </div>
  </div>
  <div class="loader-text" id="loaderText">YGR ROYAL DUM BIRYANI</div>
  <div class="loader-bar-track">
    <div class="loader-bar-fill" id="loaderBarFill"></div>
  </div>
  <div class="loader-pct" id="loaderPct">0%</div>
</div>

<!-- PAGE TRANSITION OVERLAY -->
<div class="page-transition" id="pageTransition"></div>

<!-- NAV -->
<nav class="nav" id="nav">
  <a href="#" class="logo" style="display:flex;align-items:center;text-decoration:none;color:var(--ink)">
    <img src="assets/img/logo.png" alt="YGR" style="height:32px;width:auto;filter:brightness(0)">

  </a>
  <ul class="nav-links">
    <li><a href="#">Home</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#menu">Menu</a></li>
    <li><a href="#process">Process</a></li>
    <li><a href="#reviews">Reviews</a></li>
    <li><a href="#catering">Catering</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>
  <div style="display:flex;gap:10px;align-items:center">
    <a href="https://wa.me/<?=$config['whatsapp']?>?text=I+want+to+order" target="_blank" class="nav-cta">Order Now ↗</a>
    <a href="<?=htmlspecialchars($config['login_url'])?>" style="font-size:13px;color:var(--ink-soft);text-decoration:none;font-weight:500;padding:8px 12px">Login</a>
    <button class="mobile-menu-btn" id="hamburger"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="drawer-overlay" id="drawerOverlay"></div>
<div class="mobile-drawer" id="mobileDrawer">
  <a href="#" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Home</a>
  <a href="#about" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">About</a>
  <a href="#menu" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Menu</a>
  <a href="#process" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Process</a>
  <a href="#reviews" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Reviews</a>
  <a href="#catering" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Catering</a>
  <a href="#contact" style="font:400 14px var(--font-b);color:var(--ink-soft);padding:10px 0">Contact</a>
  <div style="flex:1"></div>
  <a href="https://wa.me/<?=$config['whatsapp']?>?text=I+want+to+order" target="_blank" style="width:100%;text-align:center;border-radius:999px;padding:12px;background:var(--ink);color:#fafaf7;font:500 14px var(--font-b)">Order Now ↗</a>
  <a href="<?=htmlspecialchars($config['login_url'])?>" style="width:100%;text-align:center;border-radius:999px;padding:12px;font:400 14px var(--font-b);color:var(--mute)">Login</a>
</div>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="grain"></div>
  <h1 class="small-team" id="heroHeadline">
    <span class="word"><span>Authentic</span></span>
    <span class="word"><span>Dum</span></span>
    <span class="word"><span>Biryani</span></span>
  </h1>
  <div class="big-results-wrap">
    <div class="big-results">ROYAL</div>
  </div>

  <div class="cards-row" id="cardsRow">
    <?php
    $cardImgs = ['assets/img/hero.png','assets/img/chicken.png','assets/img/mutton.png','assets/img/65.png','assets/img/slide1.jpg','assets/img/slide2.jpg','assets/img/slide3.jpg','assets/img/slide4.jpg'];
    $cardRots = [-6,-4,-2,-1,2,4,6,8];
    $cardDepths = [10,8,12,6,14,7,11,5];
    foreach($cardImgs as $i=>$img):
    ?>
    <div class="card card-<?=$i+1?>" data-rot="<?=$cardRots[$i]?>" data-depth="<?=$cardDepths[$i]?>">
      <img src="<?=$img?>" alt="" loading="<?=$i===0?'eager':'lazy'?>">
    </div>
    <?php endforeach;?>
  </div>

  <div class="subline" id="subline">
    <a href="#menu" class="arrow-pill">
      Explore Menu
      <span class="ar">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </span>
    </a>
    <div class="subline-text">Royal Dum Biryani — Since 2014</div>
  </div>
  <div class="scroll-indicator"><span>Scroll</span><div style="width:1px;height:24px;overflow:hidden"><div class="scroll-line"></div></div></div>
</section>

<!-- TEAM -->
<section class="team-section" id="team">
  <div class="team-head">
    <div>
      <div class="eyebrow">Our Team</div>
      <h2>Masters of the <em>Dum</em> Craft</h2>
    </div>
    <p>Decades of combined experience perfecting the art of slow-cooked royal biryani.</p>
  </div>
  <div class="team-grid">
    <?php foreach([
      ['img'=>'assets/img/hero.png','name'=>'Chef Abdul','role'=>'Head Chef · 18 yrs'],
      ['img'=>'assets/img/chicken.png','name'=>'Rahul K.','role'=>'Spice Master · 12 yrs'],
      ['img'=>'assets/img/mutton.png','name'=>'Priya M.','role'=>'Marination Lead · 9 yrs'],
      ['img'=>'assets/img/65.png','name'=>'Vikram S.','role'=>'Dum Specialist · 14 yrs'],
    ] as $t):?>
    <div class="t-card">
      <img src="<?=$t['img']?>" alt="<?=$t['name']?>" loading="lazy">
      <div class="t-meta">
        <div class="nm"><?=$t['name']?></div>
        <div class="rl"><?=$t['role']?></div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</section>

<!-- STATS -->
<section class="stats" id="stats">
  <div class="stats-inner">
    <h3>We <em>don't just cook</em> — we craft tradition</h3>
    <div class="stat-block">
      <div class="num"><span data-count="12000">0</span><small>+</small></div>
      <div class="lbl">Happy Customers</div>
    </div>
    <div class="stat-block">
      <div class="num"><span data-count="50">0</span><small>K+</small></div>
      <div class="lbl">Biryanis Served</div>
    </div>
    <div class="stat-block">
      <div class="num"><span data-count="4.9">0</span><small>★</small></div>
      <div class="lbl">Avg. Rating</div>
    </div>
  </div>
</section>

<div class="dark-theme">
<!-- ABOUT, MENU, PROCESS, WHY US, REVIEWS, CATERING, CONTACT, FOOTER sections follow... -->
<section id="about">
  <div class="about-bg-text">YGR</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(40px,6vw,100px);max-width:1200px;margin:0 auto;align-items:center;position:relative;z-index:1" class="about-inner">
    <div class="about-left">
      <p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:20px">// Our Story</p>
      <h2 style="font:italic 700 clamp(3rem,6vw,5rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em;margin-bottom:28px">Royal<br>Tradition</h2>
      <p style="font:300 clamp(14px,1.5vw,16px) var(--font-b);color:var(--mute);line-height:1.85;max-width:480px;margin-bottom:40px">At YGR Royal Dum Biryani, we believe biryani is more than just food — it is an emotion. Inspired by India's royal kitchens and centuries of dum-style culinary tradition, every plate we serve is crafted with love, patience, and perfection.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:420px">
        <?php foreach([['10+','Years Cooking'],['50K+','Plates Served'],['4.9★','Customer Rating'],['100%','Halal Certified']] as $st):?>
        <div class="glass" style="border-radius:18px;padding:22px 24px"><div style="font:italic 700 clamp(1.8rem,3.5vw,2.4rem) var(--font-h);color:var(--gold);line-height:1;margin-bottom:6px"><?=$st[0]?></div><div style="font:300 12px var(--font-b);color:var(--mute);letter-spacing:0.05em"><?=$st[1]?></div></div>
        <?php endforeach;?>
      </div>
    </div>
    <div style="position:relative;display:flex;align-items:center;justify-content:center;min-height:480px">
      <div class="glass-strong" style="width:180px;height:180px;border-radius:50%;display:flex;align-items:center;justify-content:center;z-index:2"><img src="assets/img/logo.png" alt="YGR" style="height:90px;width:auto"></div>
      <div class="orbit-ring orbit-ring-1"><div class="orbit-ring-1-group" id="orbitItems"></div></div>
      <div class="orbit-ring orbit-ring-2"></div>
    </div>
  </div>
</section>

<section id="menu">
  <canvas id="menu-particles"></canvas>
  <div style="text-align:center;padding:0 clamp(20px,5vw,60px);margin-bottom:40px">
    <p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:16px">// Special Menu</p>
    <h2 style="font:italic 700 clamp(2.8rem,7vw,5.5rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em">Our Signature<br>Biryanis</h2>
  </div>
  <div class="swiper menu-swiper" style="padding:12px clamp(20px,5vw,60px) 40px;position:relative;z-index:1">
    <div class="swiper-wrapper">
      <?php foreach($menu as $item):?>
      <div class="swiper-slide menu-slide">
        <div class="menu-card glass-strong" data-tilt data-tilt-max="8" data-tilt-speed="600" data-tilt-glare="true" data-tilt-max-glare="0.15" style="border-radius:28px;padding:32px">
          <div class="card-glow"></div>
          <div style="position:absolute;top:20px;right:20px;z-index:2"><span style="background:var(--gold);color:#000;font:600 10px var(--font-b);padding:4px 12px;border-radius:999px;text-transform:uppercase;letter-spacing:0.1em"><?=htmlspecialchars($item['tag'])?></span></div>
          <div style="position:relative;z-index:1;flex:1;display:flex;align-items:center;justify-content:center;margin-bottom:20px;overflow:hidden;border-radius:16px;background:rgba(201,146,43,0.05)">
            <img src="<?=htmlspecialchars($item['img'])?>" alt="<?=htmlspecialchars($item['name'])?>" style="width:100%;height:200px;object-fit:cover;border-radius:16px;transition:transform 0.6s var(--ease-out)" class="card-img" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div style="display:none;width:100%;height:200px;align-items:center;justify-content:center;font:italic 500 1.2rem var(--font-h);color:rgba(201,146,43,0.4)"><?=htmlspecialchars($item['name'])?></div>
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:12px;z-index:1;position:relative"><?php foreach($item['tags'] as $tag):?><span class="glass" style="border-radius:999px;padding:3px 10px;font:300 9px var(--font-b);color:rgba(201,146,43,0.8);letter-spacing:0.08em"><?=htmlspecialchars($tag)?></span><?php endforeach;?></div>
          <div style="position:relative;z-index:1"><h3 style="font:italic 700 clamp(1.4rem,2.5vw,1.8rem) var(--font-h);color:var(--ink);margin-bottom:6px;line-height:1.1"><?=htmlspecialchars($item['name'])?></h3><p style="font:300 12px var(--font-b);color:var(--mute);line-height:1.6;margin-bottom:16px"><?=htmlspecialchars($item['desc'])?></p><div style="display:flex;align-items:center;justify-content:space-between"><span style="font:600 20px var(--font-b);color:var(--gold)"><?=htmlspecialchars($item['price'])?></span><a href="https://wa.me/<?=$config['whatsapp']?>?text=<?=urlencode('I want to order '.$item['name'])?>" target="_blank" class="glass" style="border-radius:999px;padding:8px 16px;font:500 11px var(--font-b);color:var(--gold)">Order →</a></div></div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <div class="swiper-button-prev" style="border-radius:50%;width:44px;height:44px"></div>
    <div class="swiper-button-next" style="border-radius:50%;width:44px;height:44px"></div>
    <div class="swiper-pagination menu-pagination" style="position:relative;margin-top:20px"></div>
  </div>
</section>

<section id="process">
  <div style="max-width:1100px;margin:0 auto">
    <div style="text-align:center;margin-bottom:60px"><p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:16px">// How We Cook</p><h2 style="font:italic 700 clamp(2.8rem,6vw,4.5rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em">Crafted with<br>Care</h2></div>
    <div class="process-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:60px;position:relative">
      <div style="position:absolute;top:28px;left:12.5%;right:12.5%;height:1px;background:repeating-linear-gradient(to right,rgba(201,146,43,0.35) 0,rgba(201,146,43,0.35) 8px,transparent 8px,transparent 16px);z-index:0"></div>
      <?php foreach([['01','Fresh Selection','Premium basmati, fresh halal meat, whole spices sourced fresh daily.'],['02','Authentic Marination','Meat marinated 4–6 hours in traditional spice blends for deep flavor.'],['03','Dum Slow Cooking','Sealed handi slow-cooked 45–60 min. The steam creates the magic.'],['04','Fresh Serving','Plated hot and fresh. Delivered within 30 minutes.']] as $step):?>
      <div class="process-step glass" style="border-radius:20px;padding:24px 20px;margin:0 6px;text-align:center">
        <div style="width:48px;height:48px;border-radius:50%;background:rgba(201,146,43,0.12);border:1px solid rgba(201,146,43,0.35);display:flex;align-items:center;justify-content:center;margin:0 auto 16px"><span style="font:italic 700 1rem var(--font-h);color:var(--gold)"><?=$step[0]?></span></div>
        <h3 style="font:italic 700 1.2rem var(--font-h);color:var(--ink);margin-bottom:10px"><?=$step[1]?></h3>
        <p style="font:300 12px var(--font-b);color:var(--mute);line-height:1.7;max-width:200px;margin:0 auto"><?=$step[2]?></p>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</section>

<section id="why-us">
  <div style="text-align:center;margin-bottom:60px"><p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:16px">// Why Choose Us</p><h2 style="font:italic 700 clamp(2.8rem,6vw,4.5rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em">Served<br>Differently</h2></div>
  <div class="why-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1100px;margin:0 auto">
    <?php foreach([['Authentic Dum Cooking','Traditional slow-cooking in a sealed handi creates layered aromas.',['Slow Cook','Sealed Pot','Aromatic']],['Premium Ingredients','Fresh halal meat daily. Aged basmati. Whole spices hand-ground.',['Fresh Halal','Basmati','No Preservatives']],['Fast Delivery','Hot biryani in under 30 minutes. Insulated packaging.',['30 Min','Hot Box','Tracked']]] as $f):?>
    <div class="why-card glass" data-tilt data-tilt-max="5" data-tilt-speed="800" style="border-radius:24px;padding:28px">
      <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px"><?php foreach($f[2] as $tag):?><span class="glass" style="border-radius:999px;padding:4px 10px;font:300 10px var(--font-b);color:rgba(201,146,43,0.75)"><?=$tag?></span><?php endforeach;?></div>
      <h3 style="font:italic 700 clamp(1.3rem,2vw,1.5rem) var(--font-h);color:var(--ink);margin-bottom:10px"><?=$f[0]?></h3>
      <p style="font:300 13px var(--font-b);color:var(--mute);line-height:1.7"><?=$f[1]?></p>
    </div>
    <?php endforeach;?>
  </div>
</section>

<section id="reviews">
  <div style="text-align:center;margin-bottom:40px;padding:0 clamp(20px,5vw,60px)"><h2 style="font:italic 700 clamp(2.5rem,5vw,4rem) var(--font-h);color:var(--ink);letter-spacing:-0.02em">What Families Say</h2></div>
  <div style="overflow:hidden;position:relative">
    <div style="position:absolute;left:0;top:0;bottom:0;width:100px;z-index:2;background:linear-gradient(to right,var(--bg),transparent);pointer-events:none"></div>
    <div style="position:absolute;right:0;top:0;bottom:0;width:100px;z-index:2;background:linear-gradient(to left,var(--bg),transparent);pointer-events:none"></div>
    <div class="reviews-track" id="reviewsTrack">
      <?php foreach(array_merge($reviews,$reviews) as $r):?>
      <div class="review-card glass">
        <div style="display:flex;gap:2px;margin-bottom:12px"><?php for($s=0;$s<$r['stars'];$s++):?><svg width="12" height="12" viewBox="0 0 24 24" fill="var(--gold)"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg><?php endfor;?></div>
        <p style="font:300 13px var(--font-b);color:var(--ink);line-height:1.7;margin-bottom:16px;font-style:italic">"<?=htmlspecialchars($r['text'])?>"</p>
        <div style="display:flex;align-items:center;gap:8px"><div style="width:30px;height:30px;border-radius:50%;background:rgba(201,146,43,0.15);display:flex;align-items:center;justify-content:center;font:600 10px var(--font-b);color:var(--gold)"><?=strtoupper(substr($r['name'],0,2))?></div><div><div style="font:500 12px var(--font-b);color:var(--ink)"><?=htmlspecialchars($r['name'])?></div><div style="font:300 10px var(--font-b);color:var(--mute)"><?=htmlspecialchars($r['loc'])?></div></div></div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</section>

<section id="catering">
  <div class="glass-strong" style="max-width:700px;margin:0 auto;border-radius:32px;padding:clamp(40px,5vw,60px) clamp(32px,4vw,50px)">
    <p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:14px">// Events</p>
    <h2 style="font:italic 700 clamp(2.2rem,5vw,4rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em;margin-bottom:28px">Biryani for<br>Every Occasion</h2>
    <div style="display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:28px"><?php foreach(['Weddings','Birthdays','Corporate','Family','Festivals'] as $occ):?><span class="glass" style="border-radius:999px;padding:8px 18px;font:400 12px var(--font-b);color:var(--ink-soft)"><?=$occ?></span><?php endforeach;?></div>
    <a href="https://wa.me/<?=$config['whatsapp']?>?text=I+need+catering" target="_blank" class="glass-strong" style="display:inline-flex;align-items:center;gap:8px;border-radius:999px;padding:14px 36px;font:500 14px var(--font-b);color:var(--gold)">Book Now <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></a>
  </div>
</section>

<section id="contact">
  <div class="contact-inner" style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(40px,6vw,80px);max-width:1000px;margin:0 auto;align-items:center">
    <div><p style="font:300 12px var(--font-b);color:var(--gold);letter-spacing:0.25em;text-transform:uppercase;margin-bottom:20px">// Find Us</p><h2 style="font:italic 700 clamp(3rem,6vw,5rem) var(--font-h);color:var(--ink);line-height:0.9;letter-spacing:-0.02em">Get In<br>Touch</h2><p style="font:300 14px var(--font-b);color:var(--mute);margin-top:24px;line-height:1.8;max-width:360px">Open every day. We would love to hear from you. Order, cater, or just say hello.</p></div>
    <div class="glass-strong" style="border-radius:28px;padding:36px 32px">
      <?php foreach([['map-pin',$config['address']],['phone',$config['phone']],['mail',$config['email']],['clock',$config['hours']]] as $c):?>
      <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:20px"><div class="glass" style="width:36px;height:36px;flex-shrink:0;border-radius:10px;display:flex;align-items:center;justify-content:center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><?php if($c[0]==='map-pin'):?><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/><?php elseif($c[0]==='phone'):?><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 11 19.79 19.79 0 01.22 2.38 2 2 0 012.18 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.56-.56a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/><?php elseif($c[0]==='mail'):?><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/><?php else:?><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/><?php endif;?></svg></div><span style="font:400 13px var(--font-b);color:var(--ink-soft);line-height:1.5"><?=htmlspecialchars($c[1])?></span></div>
      <?php endforeach;?>
      <a href="https://wa.me/<?=$config['whatsapp']?>?text=Hello+YGR" target="_blank" class="glass-strong" style="display:flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:12px 24px;margin-top:8px;font:500 14px var(--font-b);color:var(--gold)">WhatsApp ↗</a>
    </div>
  </div>
</section>

<footer>
  <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:32px;align-items:start;margin-bottom:32px">
    <div><img src="assets/img/logo.png" alt="YGR Royal Dum Biryani" style="height:55px;width:auto"><p style="font:300 12px var(--font-b);color:var(--mute);margin-top:10px;max-width:240px">Serving authentic dum biryani since 2014.</p></div>
    <div style="display:flex;flex-direction:column;gap:8px;align-items:center">
      <?php foreach(['Home'=>'#hero','About'=>'#about','Menu'=>'#menu','Catering'=>'#catering','Contact'=>'#contact'] as $label=>$href):?><a href="<?=$href?>" style="font:300 12px var(--font-b);color:var(--mute)"><?=$label?></a><?php endforeach;?>
      <div style="width:80%;height:1px;background:rgba(201,146,43,0.12);margin:4px 0"></div>
      <a href="<?=htmlspecialchars($config['login_url'])?>" style="font:400 12px var(--font-b);color:var(--orange-1)">Staff Login</a>
    </div>
    <div style="text-align:right"><p style="font:300 10px var(--font-b);color:var(--mute);letter-spacing:0.15em;text-transform:uppercase;margin-bottom:10px">Follow</p><p style="font:300 11px var(--font-b);color:var(--mute)">Open Daily<br><span style="color:var(--ink-soft)"><?=$config['hours']?></span></p></div>
  </div>
  <div style="border-top:1px solid rgba(201,146,43,0.08);padding-top:20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px"><span style="font:300 11px var(--font-b);color:var(--mute)">© <?=date('Y')?> YGR Royal Dum Biryani</span><span style="font:300 11px var(--font-b);color:var(--mute)">Chennai, Tamil Nadu</span></div>
</footer>
</div>

<script>
const MENU_DATA=<?=json_encode($menu)?>;
const CONFIG=<?=json_encode($config)?>;

// ─── LOADING ANIMATION ──────────────────────────
(function initLoading() {
  const overlay = document.getElementById('loadingOverlay');
  if (!overlay) return;
  const ringFill = document.getElementById('loaderRingFill');
  const textEl = document.getElementById('loaderText');
  const circumference = 2 * Math.PI * 72;
  if (ringFill) ringFill.style.strokeDasharray = circumference;
  const text = textEl.textContent;
  textEl.innerHTML = text.split('').map(c => `<span class="char">${c === ' ' ? ' ' : c}</span>`).join('');
  const pctProxy = { value: 0 };
  const dots = gsap.utils.toArray('.loader-dot');
  dots.forEach((dot, i) => {
    const angle = (i / dots.length) * 360;
    const rad = angle * Math.PI / 180;
    const r = 70 + Math.random() * 20;
    gsap.set(dot, { x: Math.cos(rad) * r, y: Math.sin(rad) * r });
  });
  const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
  tl.fromTo('#loaderAmbient', { autoAlpha: 0, scale: 0.85 }, { autoAlpha: 1, scale: 1, duration: 0.8 }, 0)
    .to('#loaderLogoWrap', { autoAlpha: 1, scale: 1, duration: 0.9, ease: 'back.out(1.7)' }, 0.25)
    .to(ringFill, { strokeDashoffset: 0, duration: 1.4, ease: 'power2.inOut' }, 0.45)
    .to('.loader-dot', { autoAlpha: 0.85, scale: 1, duration: 0.35, stagger: 0.05, ease: 'back.out(2.5)' }, 0.75)
    .to('.loader-dot', { y: -20 + Math.random() * 40, x: (Math.random() - 0.5) * 30, duration: 2.2, ease: 'sine.inOut' }, 0.95)
    .to('.loader-text .char', { autoAlpha: 1, y: 0, duration: 0.3, stagger: 0.025, ease: 'back.out(1.3)' }, 0.8)
    .to('#loaderBarFill', { width: '100%', duration: 2.6, ease: 'power2.inOut' }, 0.2)
    .to(pctProxy, { value: 100, duration: 2.6, ease: 'power2.inOut', onUpdate: () => { document.getElementById('loaderPct').textContent = Math.round(pctProxy.value) + '%'; } }, 0.2)
    .to(overlay, { autoAlpha: 0, duration: 0.65, ease: 'power2.inOut', onComplete: () => { overlay.style.display = 'none'; } }, '+=0.5');
})();

// ─── LOGIN PAGE TRANSITION ──────────────────────
document.querySelectorAll('a[href*="login.php"], a[href*="auth/login"]').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    const href = link.getAttribute('href');
    const pt = document.getElementById('pageTransition');
    if (pt) {
      gsap.fromTo(pt, { y: '100%' }, { y: '0%', duration: 0.45, ease: 'power3.inOut', onComplete: () => { window.location.href = href; } });
    } else {
      window.location.href = href;
    }
  });
});

// ─── INITIAL STATES ────────────────────────────
gsap.set("#nav", { opacity: 0, y: -20 });
gsap.set(".small-team .word > span", { y: "105%" });
gsap.set(".big-results .letter", { y: 80, opacity: 0 });
gsap.set("#subline", { opacity: 0, y: 20 });
gsap.set(".t-card", { opacity: 0 });
gsap.set(".stats-inner", { opacity: 0 });
document.querySelectorAll(".card").forEach((card) => {
  const rot = parseFloat(card.dataset.rot) || 0;
  card.dataset.restRot = rot;
  gsap.set(card, { y: -800, rotation: rot + 25, opacity: 0, scale: 0.7 });
});

// ─── INTRO TIMELINE ────────────────────────────
const intro = gsap.timeline({ defaults: { ease: "power3.out" } });
intro
  .to("#nav", { opacity: 1, y: 0, duration: 0.8 }, 0.1)
  .to(".small-team .word > span", { y: "0%", duration: 0.9, stagger: 0.08, ease: "power3.out" }, 0.3)
  .to(".big-results .letter", { y: 0, opacity: 1, duration: 0.9, stagger: 0.05, ease: "back.out(1.6)" }, 0.55)
  .to(".card", {
    y: 0, opacity: 1, scale: 1,
    rotation: (i, el) => parseFloat(el.dataset.restRot) || 0,
    duration: 1.1, stagger: { each: 0.08, from: "center" }, ease: "back.out(1.4)"
  }, 0.8)
  .to("#subline", { opacity: 1, y: 0, duration: 0.8 }, 1.6);

// ─── CONTINUOUS FLOAT ON CARDS ────────────────
const floatScale = window.innerWidth < 640 ? 0.35 : 1;
document.querySelectorAll(".card").forEach((card, i) => {
  const rot = parseFloat(card.dataset.restRot) || 0;
  gsap.to(card, {
    y: `+=${(8 + (i % 3) * 5) * floatScale}`,
    rotation: rot + (i % 2 === 0 ? 1.5 : -1.5) * floatScale,
    duration: 3 + (i % 4) * 0.5,
    delay: 1.8 + i * 0.1,
    ease: "sine.inOut",
    repeat: -1, yoyo: true
  });
});

// ─── MOUSE PARALLAX ON CARDS ──────────────────
const hero = document.querySelector(".hero");
let mx = 0, my = 0, tx = 0, ty = 0;
hero.addEventListener("mousemove", (e) => {
  const r = hero.getBoundingClientRect();
  mx = ((e.clientX - r.left) / r.width - 0.5) * 2;
  my = ((e.clientY - r.top) / r.height - 0.5) * 2;
});
hero.addEventListener("mouseleave", () => { mx = 0; my = 0; });
function parallax() {
  tx += (mx - tx) * 0.05;
  ty += (my - ty) * 0.05;
  document.querySelectorAll(".card").forEach((card) => {
    const d = parseFloat(card.dataset.depth) || 8;
    card.style.translate = `${tx * d * 0.15}px ${ty * d * 0.12}px`;
  });
  requestAnimationFrame(parallax);
}
parallax();

// ─── CARD HOVER 3D LIFT ───────────────────────
document.querySelectorAll(".card").forEach((card) => {
  const restRot = parseFloat(card.dataset.restRot) || 0;
  card.addEventListener("mousemove", (e) => {
    const r = card.getBoundingClientRect();
    const px = (e.clientX - r.left) / r.width - 0.5;
    const py = (e.clientY - r.top) / r.height - 0.5;
    gsap.to(card, {
      rotateX: -py * 16, rotateY: px * 16, scale: 1.12, zIndex: 20,
      duration: 0.4, ease: "power2.out", transformPerspective: 700, overwrite: "auto"
    });
  });
  card.addEventListener("mouseleave", () => {
    gsap.to(card, {
      rotateX: 0, rotateY: 0, scale: 1, zIndex: card.style.zIndex || "",
      duration: 0.8, ease: "elastic.out(1, 0.6)", overwrite: "auto"
    });
  });
});

// ─── GSAP PLUGINS ───────────────────────────────
gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

// ─── SCROLL: CARDS FAN OUT ────────────────────
ScrollTrigger.create({
  trigger: ".hero",
  start: "top top",
  end: "bottom top",
  scrub: 0.8,
  onUpdate: (self) => {
    const p = self.progress;
    const ms = window.innerWidth < 640 ? 0.3 : 1;
    gsap.set(".big-results", { scale: 1 + 0.15 * p, opacity: 1 - 0.4 * p });
    gsap.set(".small-team", { y: -60 * p, opacity: 1 - p * 1.5 });
    const moves = [
      { x: -260 * ms, y: -40 * ms, rot: -25 * ms }, { x: -200 * ms, y: 20 * ms, rot: -18 * ms },
      { x: -120 * ms, y: 80 * ms, rot: -10 * ms }, { x: -40 * ms, y: 120 * ms, rot: -4 * ms },
      { x: 40 * ms, y: 120 * ms, rot: 6 * ms }, { x: 120 * ms, y: 80 * ms, rot: 12 * ms },
      { x: 200 * ms, y: 20 * ms, rot: 22 * ms }, { x: 260 * ms, y: -40 * ms, rot: 28 * ms }
    ];
    document.querySelectorAll(".card").forEach((card, i) => {
      const m = moves[i];
      const rest = parseFloat(card.dataset.restRot) || 0;
      gsap.set(card, { x: m.x * p, y: m.y * p, rotation: rest + m.rot * p });
    });
    gsap.set("#subline", { opacity: 1 - p * 2 });
  }
});

// ─── TEAM GRID REVEAL ─────────────────────────
gsap.from(".eyebrow, .team-head h2, .team-head p", {
  opacity: 0, y: 30, duration: 0.9, stagger: 0.1, ease: "power3.out",
  scrollTrigger: { trigger: ".team-head", start: "top 80%" }
});
gsap.fromTo(".t-card",
  { y: 80, scale: 0.9, rotation: (i) => (i % 2 === 0 ? 4 : -4) },
  { opacity: 1, y: 0, scale: 1, rotation: 0, duration: 1, stagger: 0.08, ease: "back.out(1.3)",
    scrollTrigger: { trigger: ".team-grid", start: "top 80%" } }
);

// ─── STATS REVEAL + COUNTERS ──────────────────
gsap.fromTo(".stats-inner",
  { y: 60, scale: 0.97 },
  { opacity: 1, y: 0, scale: 1, duration: 1.2, ease: "power3.out",
    scrollTrigger: { trigger: ".stats", start: "top 80%" } }
);
ScrollTrigger.create({
  trigger: ".stats",
  start: "top 75%",
  onEnter: () => {
    document.querySelectorAll(".stat-block .num").forEach((el) => {
      const target = parseFloat(el.dataset.count);
      const span = el.querySelector("span");
      gsap.to({ v: 0 }, {
        v: target, duration: 2, ease: "power2.out",
        onUpdate: function () { span.textContent = Math.floor(this.targets()[0].v).toLocaleString(); }
      });
    });
  },
  once: true
});

// ─── SMOOTH SCROLL ─────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault();
    const t = document.querySelector(a.getAttribute('href'));
    if (t) gsap.to(window, { scrollTo: { y: t, offsetY: 80 }, duration: 0.8, ease: 'power3.out' });
  });
});

// ─── NAV SCROLL STATE ──────────────────────────
const nav = document.getElementById('nav');
const darkSections = document.querySelector('.dark-theme');
window.addEventListener('scroll', () => {
  const sy = window.scrollY;
  nav?.classList.toggle('scrolled', sy > 80);
});

// ─── HAMBURGER / DRAWER ────────────────────────
const hamburger = document.getElementById('hamburger'), drawer = document.getElementById('mobileDrawer'), drawerOv = document.getElementById('drawerOverlay');
function openDr() { hamburger?.classList.add('active'); gsap.to(drawer, { x: '0%', duration: 0.4, ease: 'power3.out' }); drawerOv?.classList.add('open'); }
function closeDr() { hamburger?.classList.remove('active'); gsap.to(drawer, { x: '100%', duration: 0.3, ease: 'power3.in' }); drawerOv?.classList.remove('open'); }
if (hamburger) hamburger.addEventListener('click', () => hamburger.classList.contains('active') ? closeDr() : openDr());
if (drawerOv) drawerOv.addEventListener('click', closeDr);
drawer?.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDr));

// ─── SECTION REVEALS ───────────────────────────
gsap.utils.toArray('.process-step').forEach((el, i) => {
  gsap.from(el, { autoAlpha: 0, y: 40, scale: 0.95, duration: 0.8, ease: 'power3.out', delay: i * 0.18, scrollTrigger: { trigger: el, start: 'top 88%', once: true } });
});
gsap.utils.toArray('.why-card').forEach((el, i) => {
  gsap.from(el, { autoAlpha: 0, y: 40, scale: 0.96, duration: 0.8, ease: 'power3.out', delay: (i % 3) * 0.12, scrollTrigger: { trigger: el, start: 'top 88%', once: true } });
});

// ─── MENU SWIPER ───────────────────────────────
new Swiper('.menu-swiper', {
  slidesPerView: 1.1, spaceBetween: 16, centeredSlides: true, loop: true, speed: 700,
  grabCursor: true, effect: 'coverflow',
  coverflowEffect: { rotate: 6, stretch: 0, depth: 120, modifier: 1.5, slideShadows: false },
  autoplay: { delay: 4000, disableOnInteraction: false },
  pagination: { el: '.menu-pagination', clickable: true, renderBullet: (i, cls) => `<span class="${cls}" style="background:var(--orange-2)"></span>` },
  navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
  breakpoints: { 480: { slidesPerView: 1.3, spaceBetween: 12, coverflowEffect: { rotate: 3, stretch: 0, depth: 60, modifier: 1, slideShadows: false } }, 640: { slidesPerView: 2, spaceBetween: 20 }, 1024: { slidesPerView: 3, spaceBetween: 28 } }
});

VanillaTilt.init(document.querySelectorAll('[data-tilt]'), { max: 6, speed: 600, glare: true, 'max-glare': 0.12, gyroscope: false });

document.querySelectorAll('.menu-card').forEach(card => {
  card.addEventListener('mouseenter', () => {
    gsap.to(card.querySelector('.card-img'), { scale: 1.06, duration: 0.5 });
    gsap.to(card.querySelector('.card-glow'), { opacity: 1, duration: 0.4 });
  });
  card.addEventListener('mouseleave', () => {
    gsap.to(card.querySelector('.card-img'), { scale: 1, duration: 0.4 });
    gsap.to(card.querySelector('.card-glow'), { opacity: 0, duration: 0.4 });
  });
});

document.getElementById('reviewsTrack')?.addEventListener('mouseenter', function () { this.style.animationPlayState = 'paused'; });
document.getElementById('reviewsTrack')?.addEventListener('mouseleave', function () { this.style.animationPlayState = 'running'; });

// Orbit spices
const orbitItems = document.getElementById('orbitItems');
if (orbitItems) {
  ['Cardamom', 'Saffron', 'Cloves', 'Star Anise', 'Bay Leaf', 'Cinnamon'].forEach((name, i) => {
    const angle = (i / 6) * 360, rad = angle * Math.PI / 180, x = Math.cos(rad) * 160, y = Math.sin(rad) * 160;
    const el = document.createElement('div');
    el.className = 'glass';
    el.style.cssText = `position:absolute;border-radius:999px;padding:4px 12px;font:300 10px var(--font-b);color:var(--orange-3);letter-spacing:0.1em;text-transform:uppercase;left:calc(50% + ${x}px);top:calc(50% + ${y}px);transform:translate(-50%,-50%);pointer-events:none`;
    el.textContent = name;
    orbitItems.appendChild(el);
  });
}
</script>
</body></html>
