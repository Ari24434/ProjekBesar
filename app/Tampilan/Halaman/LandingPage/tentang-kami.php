<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tentang Kami — Oman's Club Academy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet"/>
  <style>
/* ══════════ DESIGN TOKENS ══════════ */
:root {
  --navy:        #08153A;
  --blue-dark:   #0D2149;
  --blue-mid:    #1A3D7C;
  --blue-main:   #1E54B7;
  --blue-bright: #2D72D9;
  --blue-light:  #5B8EF5;
  --blue-pale:   #DAE5FD;
  --blue-ghost:  #EBF2FE;
  --frost:       #F0F5FF;
  --white:       #FFFFFF;
  --off-white:   #F7FAFF;
  --ink:         #0F1729;
  --slate:       #475569;
  --ash:         #94A3B8;
  --smoke:       #E2E8F5;
  --gold:        #C8A84B;
  --gold-light:  #F0D070;
  --emerald:     #10B981;
  --crimson:     #EF4444;
  --r-lg: 16px; --r-md: 10px; --r-sm: 6px;
}
*{box-sizing:border-box;margin:0;padding:0;}
html{scroll-behavior:smooth;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden;}

/* ══════════ SCROLL REVEAL ══════════ */
.reveal{opacity:0;transform:translateY(32px);transition:opacity .72s cubic-bezier(.22,.61,.36,1),transform .72s cubic-bezier(.22,.61,.36,1);}
.reveal.visible{opacity:1;transform:translateY(0);}
.reveal-left{opacity:0;transform:translateX(-36px);transition:opacity .72s cubic-bezier(.22,.61,.36,1),transform .72s cubic-bezier(.22,.61,.36,1);}
.reveal-left.visible{opacity:1;transform:translateX(0);}
.reveal-right{opacity:0;transform:translateX(36px);transition:opacity .72s cubic-bezier(.22,.61,.36,1),transform .72s cubic-bezier(.22,.61,.36,1);}
.reveal-right.visible{opacity:1;transform:translateX(0);}
.reveal-delay-1{transition-delay:.1s;}
.reveal-delay-2{transition-delay:.2s;}
.reveal-delay-3{transition-delay:.3s;}
.reveal-delay-4{transition-delay:.4s;}
.reveal-delay-5{transition-delay:.5s;}

/* ══════════ NAVBAR ══════════ */
.navbar-oca{
  background:rgba(8,21,58,.96);
  backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(255,255,255,.07);
  padding:.9rem 0;
  position:sticky;top:0;z-index:1000;
  transition:padding .3s,box-shadow .3s;
}
.navbar-oca.scrolled{padding:.55rem 0;box-shadow:0 4px 30px rgba(0,0,0,.3);}
.brand-text{font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--white);letter-spacing:-.3px;}
.brand-text span{color:var(--gold);}
.brand-icon{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,var(--blue-main),var(--blue-bright));display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(45,114,217,.4);}
.nav-link-c{color:rgba(255,255,255,.72)!important;font-size:.875rem;font-weight:500;padding:.45rem .9rem!important;border-radius:8px;transition:all .2s;}
.nav-link-c:hover,.nav-link-c.active-link{color:var(--white)!important;background:rgba(255,255,255,.07);}
.nav-link-c.active-link{color:var(--gold)!important;}
.btn-nav-masuk{background:transparent;border:1.5px solid rgba(255,255,255,.28);color:var(--white);font-size:.845rem;font-weight:600;padding:.42rem 1.2rem;border-radius:50px;transition:all .25s;}
.btn-nav-masuk:hover{background:var(--blue-bright);border-color:var(--blue-bright);color:var(--white);}
.btn-nav-daftar{background:var(--gold);border:none;color:var(--navy);font-size:.845rem;font-weight:700;padding:.42rem 1.2rem;border-radius:50px;transition:all .25s;}
.btn-nav-daftar:hover{background:var(--gold-light);color:var(--navy);}

/* ══════════ HERO — TENTANG KAMI ══════════ */
.hero-about{
  background:linear-gradient(145deg,var(--navy) 0%,#0A1E5E 52%,#112471 100%);
  min-height:82vh;display:flex;align-items:center;
  position:relative;overflow:hidden;padding:7rem 0 5rem;
}
.ha-grid{
  position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);
  background-size:52px 52px;pointer-events:none;
}
/* Animated floating orbs */
.ha-orb{position:absolute;border-radius:50%;pointer-events:none;animation:orbFloat 8s ease-in-out infinite alternate;}
.ha-orb.o1{width:600px;height:600px;top:-200px;right:-160px;background:radial-gradient(circle,rgba(45,114,217,.18) 0%,transparent 65%);animation-duration:9s;}
.ha-orb.o2{width:400px;height:400px;bottom:-150px;left:-80px;background:radial-gradient(circle,rgba(200,168,75,.13) 0%,transparent 65%);animation-duration:11s;animation-delay:-4s;}
.ha-orb.o3{width:260px;height:260px;top:40%;left:40%;background:radial-gradient(circle,rgba(91,142,245,.09) 0%,transparent 65%);animation-duration:7s;animation-delay:-2s;}

@keyframes orbFloat{from{transform:translate(0,0) scale(1);}to{transform:translate(20px,-30px) scale(1.06);}}
@keyframes fadeInUp{from{opacity:0;transform:translateY(22px);}to{opacity:1;transform:translateY(0);}}
@keyframes fadeInDown{from{opacity:0;transform:translateY(-16px);}to{opacity:1;transform:translateY(0);}}
@keyframes floatY{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}
@keyframes counterUp{from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);}}
@keyframes drawLine{from{stroke-dashoffset:400;}to{stroke-dashoffset:0;}}
@keyframes spinSlow{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
@keyframes pulseGlow{0%,100%{box-shadow:0 0 0 0 rgba(45,114,217,.3);}50%{box-shadow:0 0 0 14px rgba(45,114,217,.0);}}

.ha-badge{
  display:inline-flex;align-items:center;gap:.5rem;
  background:rgba(200,168,75,.14);border:1px solid rgba(200,168,75,.36);
  color:var(--gold-light);font-size:.72rem;font-weight:600;
  padding:.36rem .95rem;border-radius:50px;letter-spacing:.6px;
  text-transform:uppercase;margin-bottom:1.4rem;
  animation:fadeInDown .7s ease both;
}
.ha-title{
  font-family:'Playfair Display',serif;
  font-size:clamp(2.2rem,4.5vw,3.8rem);font-weight:900;
  color:var(--white);line-height:1.1;margin-bottom:1.3rem;
  animation:fadeInUp .7s .1s ease both;
}
.ha-title .acc{color:var(--gold);}
.ha-title .line-break{display:block;}
.ha-desc{font-size:1rem;color:rgba(255,255,255,.6);line-height:1.85;max-width:480px;margin-bottom:2.2rem;animation:fadeInUp .7s .2s ease both;}
.ha-cta{display:flex;gap:.9rem;flex-wrap:wrap;animation:fadeInUp .7s .3s ease both;}
.btn-ha-primary{background:var(--blue-bright);color:#fff;border:none;padding:.78rem 1.8rem;border-radius:50px;font-weight:700;font-size:.9rem;font-family:'Plus Jakarta Sans',sans-serif;transition:all .25s;box-shadow:0 8px 22px rgba(45,114,217,.36);cursor:pointer;}
.btn-ha-primary:hover{background:var(--blue-light);transform:translateY(-2px);color:#fff;}
.btn-ha-ghost{background:transparent;border:1.5px solid rgba(255,255,255,.28);color:#fff;padding:.78rem 1.8rem;border-radius:50px;font-weight:600;font-size:.9rem;font-family:'Plus Jakarta Sans',sans-serif;transition:all .25s;cursor:pointer;}
.btn-ha-ghost:hover{background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.6);}

/* Right visual — decorative ring + stat cards */
.ha-visual{position:relative;animation:fadeInUp .9s .25s ease both;}
.ha-ring-wrap{
  width:340px;height:340px;position:relative;margin:0 auto;
}
.ha-ring{
  width:100%;height:100%;border-radius:50%;
  border:1.5px solid rgba(255,255,255,.1);
  position:absolute;inset:0;
}
.ha-ring.r2{width:82%;height:82%;top:9%;left:9%;border-color:rgba(45,114,217,.2);animation:spinSlow 40s linear infinite;}
.ha-ring.r3{width:64%;height:64%;top:18%;left:18%;border-color:rgba(200,168,75,.18);animation:spinSlow 28s linear infinite reverse;}
.ha-center-circle{
  position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
  width:160px;height:160px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue-main),var(--blue-bright));
  display:flex;align-items:center;justify-content:center;flex-direction:column;
  box-shadow:0 0 0 16px rgba(45,114,217,.1),0 12px 40px rgba(45,114,217,.38);
  animation:pulseGlow 3s ease infinite;
}
.ha-center-num{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;color:#fff;line-height:1;}
.ha-center-lbl{font-size:.68rem;color:rgba(255,255,255,.6);font-weight:500;margin-top:.2rem;letter-spacing:.3px;}

/* Floating stat pills around ring */
.ha-stat-pill{
  position:absolute;
  background:rgba(255,255,255,.08);backdrop-filter:blur(12px);
  border:1px solid rgba(255,255,255,.13);border-radius:12px;
  padding:.55rem 1rem;display:flex;align-items:center;gap:.6rem;
  font-size:.8rem;white-space:nowrap;
}
.ha-stat-pill .sp-num{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:#fff;line-height:1;}
.ha-stat-pill .sp-lbl{font-size:.68rem;color:rgba(255,255,255,.5);margin-top:.1rem;}
.sp-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}
.ha-stat-pill.p1{top:-20px;left:-30px;animation:floatY 4s ease-in-out infinite;}
.ha-stat-pill.p2{bottom:-14px;right:-24px;animation:floatY 4.5s 1s ease-in-out infinite;}
.ha-stat-pill.p3{top:50%;right:-48px;transform:translateY(-50%);animation:floatY 5s .5s ease-in-out infinite;}

/* ══════════ MARQUEE STRIP ══════════ */
.marquee-strip{background:var(--navy);border-top:1px solid rgba(255,255,255,.07);border-bottom:1px solid rgba(255,255,255,.07);overflow:hidden;padding:.75rem 0;}
.marquee-track{display:flex;gap:0;width:max-content;animation:marqueeScroll 22s linear infinite;}
@keyframes marqueeScroll{from{transform:translateX(0);}to{transform:translateX(-50%);}  }
.marquee-item{display:flex;align-items:center;gap:.7rem;padding:0 2.2rem;white-space:nowrap;font-size:.8rem;font-weight:600;color:rgba(255,255,255,.38);}
.marquee-item i{color:var(--gold);font-size:.85rem;}

/* ══════════ SECTION COMMON ══════════ */
.sec-chip{display:inline-block;background:var(--blue-ghost);color:var(--blue-main);font-size:.71rem;font-weight:700;padding:.32rem .85rem;border-radius:50px;letter-spacing:.5px;text-transform:uppercase;margin-bottom:.85rem;}
.sec-chip.gold{background:rgba(200,168,75,.1);color:var(--gold);}
.sec-chip.light{background:rgba(255,255,255,.1);color:var(--blue-pale);}
.sec-title{font-family:'Playfair Display',serif;font-size:clamp(1.7rem,3.2vw,2.5rem);font-weight:700;color:var(--ink);line-height:1.2;}
.sec-title .acc{color:var(--blue-main);}
.sec-title.light{color:var(--white);}
.sec-title.light .acc{color:var(--gold);}
.sec-sub{font-size:.95rem;color:var(--ash);line-height:1.8;max-width:560px;}
.sec-sub.light{color:rgba(255,255,255,.52);}

/* ══════════ CERITA SECTION ══════════ */
.cerita-sec{padding:6rem 0;background:var(--off-white);}

/* Timeline */
.timeline{position:relative;padding-left:0;}
.timeline::before{content:'';position:absolute;left:22px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,var(--blue-main),var(--blue-pale),transparent);}
.tl-item{display:flex;gap:1.4rem;margin-bottom:2.4rem;position:relative;}
.tl-item:last-child{margin-bottom:0;}
.tl-dot{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--blue-main),var(--blue-bright));display:flex;align-items:center;justify-content:center;font-size:1rem;color:#fff;flex-shrink:0;box-shadow:0 4px 14px rgba(30,84,183,.28);z-index:1;position:relative;animation:pulseGlow 3s ease infinite;}
.tl-year{font-size:.7rem;font-weight:700;color:var(--blue-main);letter-spacing:.5px;margin-bottom:.28rem;text-transform:uppercase;}
.tl-title{font-size:1rem;font-weight:700;color:var(--ink);margin-bottom:.38rem;}
.tl-desc{font-size:.875rem;color:var(--ash);line-height:1.7;}

/* Story visual side */
.story-visual{position:relative;}
.story-img-frame{
  background:linear-gradient(135deg,var(--navy),var(--blue-mid));
  border-radius:24px;padding:2.2rem;
  position:relative;overflow:hidden;
  box-shadow:0 24px 60px rgba(8,21,58,.18);
}
.story-img-frame::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,.04) 1px,transparent 1px);background-size:18px 18px;}
.story-img-frame::after{content:'';position:absolute;bottom:0;left:0;right:0;height:180px;background:linear-gradient(to top,rgba(45,114,217,.12),transparent);}
.story-quote{position:relative;z-index:1;}
.sq-mark{font-family:'Playfair Display',serif;font-size:5rem;color:rgba(200,168,75,.22);line-height:.8;margin-bottom:.8rem;}
.sq-text{font-family:'Playfair Display',serif;font-size:1.15rem;color:rgba(255,255,255,.82);line-height:1.7;font-style:italic;margin-bottom:1.4rem;}
.sq-author{display:flex;align-items:center;gap:.75rem;}
.sq-av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--gold),#A07828);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.2rem;color:#fff;box-shadow:0 4px 12px rgba(200,168,75,.32);}
.sq-name{font-size:.9rem;font-weight:700;color:#fff;}
.sq-role{font-size:.76rem;color:rgba(255,255,255,.4);}

/* Stat chips on story */
.story-stat-chip{
  position:absolute;background:rgba(255,255,255,.92);border:1px solid var(--smoke);
  border-radius:12px;padding:.7rem 1.1rem;
  box-shadow:0 8px 24px rgba(8,21,58,.12);
  display:flex;align-items:center;gap:.7rem;
  z-index:2;
}
.ssc-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;}
.ssc-num{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--ink);line-height:1;}
.ssc-lbl{font-size:.69rem;color:var(--ash);}
.story-stat-chip.sc1{top:-18px;right:-18px;animation:floatY 4.2s ease-in-out infinite;}
.story-stat-chip.sc2{bottom:-18px;left:-18px;animation:floatY 5s 1s ease-in-out infinite;}

/* ══════════ VISI MISI ══════════ */
.visi-sec{
  padding:6rem 0;
  background:linear-gradient(180deg,var(--white) 0%,var(--frost) 100%);
  position:relative;overflow:hidden;
}
.visi-sec::before{
  content:'';position:absolute;top:-80px;right:-80px;width:500px;height:500px;
  border-radius:50%;
  background:radial-gradient(circle,rgba(30,84,183,.05) 0%,transparent 65%);
  pointer-events:none;
}

.visi-card{
  background:linear-gradient(135deg,var(--navy) 0%,#0F2970 100%);
  border-radius:22px;padding:2.4rem;
  position:relative;overflow:hidden;height:100%;
}
.visi-card::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,.04) 1px,transparent 1px);background-size:18px 18px;pointer-events:none;}
.vc-glow{position:absolute;border-radius:50%;pointer-events:none;}
.vc-glow.g1{width:260px;height:260px;top:-100px;right:-80px;background:radial-gradient(circle,rgba(45,114,217,.2) 0%,transparent 65%);}
.vc-glow.g2{width:180px;height:180px;bottom:-70px;left:-50px;background:radial-gradient(circle,rgba(200,168,75,.12) 0%,transparent 65%);}
.vc-tag{display:inline-flex;align-items:center;gap:.4rem;font-size:.7rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;padding:.3rem .8rem;border-radius:50px;margin-bottom:1rem;position:relative;z-index:1;}
.vc-tag.visi-tag{background:rgba(200,168,75,.16);color:var(--gold-light);border:1px solid rgba(200,168,75,.3);}
.vc-tag.misi-tag{background:rgba(91,142,245,.15);color:var(--blue-pale);border:1px solid rgba(91,142,245,.25);}
.vc-title{font-family:'Playfair Display',serif;font-size:1.65rem;font-weight:700;color:#fff;line-height:1.3;margin-bottom:1rem;position:relative;z-index:1;}
.vc-desc{font-size:.875rem;color:rgba(255,255,255,.56);line-height:1.8;position:relative;z-index:1;}

.misi-item{display:flex;align-items:flex-start;gap:.85rem;margin-bottom:1rem;position:relative;z-index:1;}
.misi-item:last-child{margin-bottom:0;}
.misi-bullet{width:28px;height:28px;border-radius:7px;background:rgba(45,114,217,.2);border:1px solid rgba(45,114,217,.3);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:var(--blue-pale);flex-shrink:0;font-weight:700;margin-top:.1rem;}
.misi-text{font-size:.875rem;color:rgba(255,255,255,.62);line-height:1.7;}
.misi-text strong{color:rgba(255,255,255,.88);font-weight:600;}

/* Values grid */
.values-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-top:2rem;}
.val-card{
  background:var(--white);border:1px solid var(--smoke);border-radius:var(--r-lg);
  padding:1.4rem;transition:all .28s;position:relative;overflow:hidden;
}
.val-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2.5px;border-radius:0 0 var(--r-lg) var(--r-lg);opacity:0;transition:opacity .28s;}
.val-card:hover{box-shadow:0 8px 28px rgba(30,84,183,.09);border-color:var(--blue-pale);transform:translateY(-3px);}
.val-card:hover::after{opacity:1;}
.val-card.c-blue::after{background:linear-gradient(90deg,var(--blue-main),var(--blue-light));}
.val-card.c-gold::after{background:linear-gradient(90deg,#A07828,var(--gold));}
.val-card.c-green::after{background:linear-gradient(90deg,#0A8A5F,var(--emerald));}
.val-card.c-purple::after{background:linear-gradient(90deg,#6D28D9,#8B5CF6);}
.val-icon{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:.9rem;}
.val-icon.blue{background:var(--frost);color:var(--blue-main);}
.val-icon.gold{background:#FDF8ED;color:var(--gold);}
.val-icon.green{background:#ECFDF5;color:var(--emerald);}
.val-icon.purple{background:#F5F3FF;color:#6D28D9;}
.val-title{font-size:.92rem;font-weight:700;color:var(--ink);margin-bottom:.32rem;}
.val-desc{font-size:.81rem;color:var(--ash);line-height:1.65;margin:0;}

/* ══════════ TIM SECTION ══════════ */
.tim-sec{padding:6rem 0;background:var(--off-white);}
.team-card{
  background:var(--white);border:1px solid var(--smoke);border-radius:20px;
  padding:1.8rem;text-align:center;transition:all .3s;position:relative;overflow:hidden;
}
.team-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;opacity:0;transition:opacity .3s;}
.team-card:hover{box-shadow:0 12px 38px rgba(30,84,183,.1);border-color:var(--blue-pale);transform:translateY(-5px);}
.team-card:hover::before{opacity:1;}
.team-card.tc-blue::before{background:linear-gradient(90deg,var(--blue-main),var(--blue-bright));}
.team-card.tc-gold::before{background:linear-gradient(90deg,#A07828,var(--gold));}
.team-card.tc-green::before{background:linear-gradient(90deg,#0A8A5F,var(--emerald));}
.team-avatar-wrap{position:relative;display:inline-block;margin-bottom:1.2rem;}
.team-avatar{
  width:88px;height:88px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-family:'Playfair Display',serif;font-size:2.2rem;color:#fff;
  margin:0 auto;box-shadow:0 8px 22px rgba(30,84,183,.22);
}
.team-avatar.av-blue{background:linear-gradient(135deg,var(--blue-main),var(--blue-bright));}
.team-avatar.av-gold{background:linear-gradient(135deg,var(--gold),#8A6020);}
.team-avatar.av-green{background:linear-gradient(135deg,var(--emerald),#0A8A5F);}
.team-online{position:absolute;bottom:4px;right:4px;width:14px;height:14px;border-radius:50%;background:var(--emerald);border:2.5px solid #fff;}
.team-name{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--ink);margin-bottom:.25rem;}
.team-role{font-size:.8rem;color:var(--blue-main);font-weight:600;margin-bottom:.75rem;}
.team-desc{font-size:.81rem;color:var(--ash);line-height:1.65;margin-bottom:1.2rem;}
.team-socials{display:flex;justify-content:center;gap:.5rem;}
.ts-btn{width:30px;height:30px;border-radius:7px;border:1px solid var(--smoke);background:transparent;color:var(--ash);display:flex;align-items:center;justify-content:center;font-size:.85rem;cursor:pointer;transition:all .2s;}
.ts-btn:hover{border-color:var(--blue-pale);color:var(--blue-main);background:var(--frost);}

/* ══════════ STATS SECTION ══════════ */
.stats-sec{
  padding:6rem 0;
  background:linear-gradient(135deg,var(--navy) 0%,#0A1E5E 55%,#142B80 100%);
  position:relative;overflow:hidden;
}
.stats-sec::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);background-size:46px 46px;pointer-events:none;}
.ss-orb{position:absolute;border-radius:50%;pointer-events:none;}
.ss-orb.o1{width:500px;height:500px;top:-180px;right:-130px;background:radial-gradient(circle,rgba(45,114,217,.15) 0%,transparent 65%);}
.ss-orb.o2{width:360px;height:360px;bottom:-130px;left:-100px;background:radial-gradient(circle,rgba(200,168,75,.1) 0%,transparent 65%);}

.big-stat{text-align:center;position:relative;z-index:1;}
.big-num{
  font-family:'Playfair Display',serif;
  font-size:clamp(3rem,6vw,5rem);
  font-weight:700;color:var(--white);line-height:1;margin-bottom:.4rem;
  background:linear-gradient(135deg,#fff,rgba(255,255,255,.75));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.big-num.gold{background:linear-gradient(135deg,var(--gold),var(--gold-light));-webkit-background-clip:text;background-clip:text;}
.big-num.green{background:linear-gradient(135deg,var(--emerald),#6EE7B7);-webkit-background-clip:text;background-clip:text;}
.big-lbl{font-size:.85rem;color:rgba(255,255,255,.46);font-weight:500;}
.stat-divider{width:1px;height:60px;background:rgba(255,255,255,.1);align-self:center;}

/* ══════════ FASILITAS SECTION ══════════ */
.fasilitas-sec{padding:6rem 0;background:var(--white);}
.fas-card{
  display:flex;align-items:flex-start;gap:1.1rem;
  padding:1.5rem;border:1px solid var(--smoke);border-radius:var(--r-lg);
  background:var(--white);transition:all .28s;height:100%;
}
.fas-card:hover{box-shadow:0 8px 26px rgba(30,84,183,.08);border-color:var(--blue-pale);background:var(--frost);}
.fas-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
.fas-title{font-size:.93rem;font-weight:700;color:var(--ink);margin-bottom:.35rem;}
.fas-desc{font-size:.83rem;color:var(--ash);line-height:1.65;margin:0;}

/* ══════════ CTA SECTION ══════════ */
.cta-sec{
  padding:6rem 0;
  background:var(--off-white);
  position:relative;overflow:hidden;
}
.cta-inner{
  background:linear-gradient(135deg,var(--navy) 0%,#0F2970 55%,var(--blue-main) 100%);
  border-radius:28px;padding:4rem;
  position:relative;overflow:hidden;
  text-align:center;
}
.cta-inner::before{content:'';position:absolute;inset:0;background-image:radial-gradient(circle,rgba(255,255,255,.04) 1px,transparent 1px);background-size:20px 20px;}
.cta-glow{position:absolute;border-radius:50%;pointer-events:none;}
.cta-glow.g1{width:400px;height:400px;top:-150px;right:-100px;background:radial-gradient(circle,rgba(45,114,217,.22) 0%,transparent 65%);}
.cta-glow.g2{width:300px;height:300px;bottom:-120px;left:-80px;background:radial-gradient(circle,rgba(200,168,75,.14) 0%,transparent 65%);}
.cta-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(200,168,75,.14);border:1px solid rgba(200,168,75,.3);color:var(--gold-light);font-size:.72rem;font-weight:700;padding:.35rem .9rem;border-radius:50px;letter-spacing:.5px;text-transform:uppercase;margin-bottom:1.3rem;position:relative;z-index:1;}
.cta-title{font-family:'Playfair Display',serif;font-size:clamp(1.7rem,3.5vw,2.8rem);font-weight:900;color:#fff;line-height:1.18;margin-bottom:1rem;position:relative;z-index:1;}
.cta-title .acc{color:var(--gold);}
.cta-desc{font-size:.95rem;color:rgba(255,255,255,.55);line-height:1.8;max-width:480px;margin:0 auto 2rem;position:relative;z-index:1;}
.cta-btns{display:flex;justify-content:center;gap:.9rem;flex-wrap:wrap;position:relative;z-index:1;}
.btn-cta-primary{background:var(--gold);color:var(--navy);border:none;padding:.82rem 2.1rem;border-radius:50px;font-weight:700;font-size:.93rem;font-family:'Plus Jakarta Sans',sans-serif;transition:all .25s;box-shadow:0 6px 20px rgba(200,168,75,.3);cursor:pointer;}
.btn-cta-primary:hover{background:var(--gold-light);transform:translateY(-2px);box-shadow:0 10px 28px rgba(200,168,75,.4);}
.btn-cta-ghost{background:transparent;border:1.5px solid rgba(255,255,255,.28);color:#fff;padding:.82rem 2.1rem;border-radius:50px;font-weight:600;font-size:.93rem;font-family:'Plus Jakarta Sans',sans-serif;transition:all .25s;cursor:pointer;}
.btn-cta-ghost:hover{background:rgba(255,255,255,.09);border-color:rgba(255,255,255,.6);}

/* ══════════ FOOTER ══════════ */
.footer{background:var(--navy);padding:3.5rem 0 2rem;border-top:1px solid rgba(255,255,255,.07);}
.footer-brand{margin-bottom:1rem;}
.footer-desc{font-size:.85rem;color:rgba(255,255,255,.38);line-height:1.75;max-width:260px;}
.footer-heading{font-size:.8rem;font-weight:700;color:rgba(255,255,255,.52);letter-spacing:.6px;text-transform:uppercase;margin-bottom:.9rem;}
.footer-link{display:block;font-size:.85rem;color:rgba(255,255,255,.42);margin-bottom:.55rem;text-decoration:none;transition:color .18s;}
.footer-link:hover{color:rgba(255,255,255,.82);}
.footer-divider{height:1px;background:rgba(255,255,255,.07);margin:2rem 0 1.5rem;}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.75rem;font-size:.8rem;color:rgba(255,255,255,.3);}
.footer-bottom a{color:rgba(255,255,255,.38);text-decoration:none;}
.footer-bottom a:hover{color:rgba(255,255,255,.7);}

/* ══════════ BACK TO TOP ══════════ */
.btt{
  position:fixed;bottom:28px;right:28px;width:42px;height:42px;border-radius:50%;
  background:var(--blue-main);color:#fff;border:none;
  display:flex;align-items:center;justify-content:center;font-size:1.1rem;
  cursor:pointer;box-shadow:0 6px 18px rgba(30,84,183,.32);
  transition:all .3s;opacity:0;pointer-events:none;z-index:900;
}
.btt.show{opacity:1;pointer-events:auto;}
.btt:hover{background:var(--blue-bright);transform:translateY(-3px);}

/* ══════════ RESPONSIVE ══════════ */
@media(max-width:991px){
  .ha-visual{margin-top:3rem;}
  .ha-ring-wrap{width:280px;height:280px;}
  .story-stat-chip.sc1{top:-12px;right:-8px;}
  .story-stat-chip.sc2{bottom:-12px;left:-8px;}
}
@media(max-width:767px){
  .ha-title{font-size:2.1rem;}
  .values-grid{grid-template-columns:1fr;}
  .cta-inner{padding:2.5rem 1.5rem;}
  .ha-ring-wrap{width:230px;height:230px;}
  .ha-stat-pill.p3{display:none;}
  .ha-center-num{font-size:1.8rem;}
  .stat-divider{display:none;}
  .footer-bottom{flex-direction:column;text-align:center;}
}
@media(max-width:480px){
  .ha-stat-pill{font-size:.72rem;padding:.45rem .8rem;}
  .ha-stat-pill .sp-num{font-size:1.1rem;}
  .cta-btns{flex-direction:column;align-items:center;}
  .visi-card{padding:1.7rem;}
}
  </style>
</head>
<body>

<!-- ══════════ NAVBAR ══════════ -->
<nav class="navbar navbar-expand-lg navbar-oca" id="navbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="landing-page.html">
      <div class="brand-icon"><i class="bi bi-mortarboard-fill text-white" style="font-size:.95rem;"></i></div>
      <span class="brand-text">Oman's<span> Club</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="bi bi-list text-white fs-4"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link nav-link-c" href="landing-page.html">Beranda</a></li>
        <li class="nav-item"><a class="nav-link nav-link-c" href="#">Fitur</a></li>
        <li class="nav-item"><a class="nav-link nav-link-c" href="#">Tryout</a></li>
        <li class="nav-item"><a class="nav-link nav-link-c active-link" href="tentang-kami.html">Tentang Kami</a></li>
      </ul>
      <div class="d-flex gap-2 mt-3 mt-lg-0">
        <a href="login.html" class="btn-nav-masuk">Masuk</a>
        <a href="login.html" class="btn-nav-daftar">Daftar Gratis</a>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════ HERO ══════════ -->
<section class="hero-about">
  <div class="ha-grid"></div>
  <div class="ha-orb o1"></div>
  <div class="ha-orb o2"></div>
  <div class="ha-orb o3"></div>
  <div class="container position-relative" style="z-index:1;">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6">
        <div class="ha-badge"><i class="bi bi-mortarboard-fill"></i> Oman's Club Academy</div>
        <h1 class="ha-title">
          Mencetak <span class="acc">ASN Terbaik</span>
          <span class="line-break">Generasi Masa Depan</span>
        </h1>
        <p class="ha-desc">Platform tryout CPNS berbasis <em>Computer Assisted Test</em> yang dirancang untuk mempersiapkan para calon ASN menghadapi seleksi SKD BKN dengan simulasi yang autentik dan analisis yang mendalam.</p>
        <div class="ha-cta">
          <button class="btn-ha-primary" onclick="document.getElementById('cerita').scrollIntoView({behavior:'smooth'})">
            <i class="bi bi-compass-fill me-1"></i> Kenali Kami
          </button>
          <button class="btn-ha-ghost" onclick="window.location.href='login.html'">
            <i class="bi bi-play-circle me-1"></i> Coba Tryout
          </button>
        </div>
      </div>
      <div class="col-lg-6 d-flex justify-content-center">
        <div class="ha-visual">
          <div class="ha-ring-wrap">
            <div class="ha-ring"></div>
            <div class="ha-ring r2"></div>
            <div class="ha-ring r3"></div>
            <div class="ha-center-circle">
              <div class="ha-center-num">60+</div>
              <div class="ha-center-lbl">Peserta Aktif</div>
            </div>
          </div>
          <div class="ha-stat-pill p1">
            <div class="sp-icon" style="background:rgba(200,168,75,.18);color:var(--gold-light);"><i class="bi bi-trophy-fill"></i></div>
            <div><div class="sp-num">75%</div><div class="sp-lbl">Tingkat Kelulusan</div></div>
          </div>
          <div class="ha-stat-pill p2">
            <div class="sp-icon" style="background:rgba(16,185,129,.18);color:#6EE7B7;"><i class="bi bi-patch-check-fill"></i></div>
            <div><div class="sp-num">330</div><div class="sp-lbl">Bank Soal</div></div>
          </div>
          <div class="ha-stat-pill p3">
            <div class="sp-icon" style="background:rgba(91,142,245,.18);color:var(--blue-pale);"><i class="bi bi-journal-text"></i></div>
            <div><div class="sp-num">5</div><div class="sp-lbl">Sesi Tryout</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ MARQUEE ══════════ -->
<div class="marquee-strip">
  <div class="marquee-track">
    <!-- dup 2x untuk seamless loop -->
    <div class="marquee-item"><i class="bi bi-patch-check-fill"></i> SKD BKN Terakreditasi</div>
    <div class="marquee-item"><i class="bi bi-shield-check"></i> Soal Dikurasi Ahli</div>
    <div class="marquee-item"><i class="bi bi-graph-up-arrow"></i> Analisis Real-Time</div>
    <div class="marquee-item"><i class="bi bi-clock-history"></i> Timer CAT Autentik</div>
    <div class="marquee-item"><i class="bi bi-people-fill"></i> 60+ Peserta Aktif</div>
    <div class="marquee-item"><i class="bi bi-trophy-fill"></i> 75% Tingkat Lulus</div>
    <div class="marquee-item"><i class="bi bi-book-fill"></i> 330 Bank Soal</div>
    <div class="marquee-item"><i class="bi bi-bar-chart-fill"></i> Laporan Performa</div>
    <div class="marquee-item"><i class="bi bi-patch-check-fill"></i> SKD BKN Terakreditasi</div>
    <div class="marquee-item"><i class="bi bi-shield-check"></i> Soal Dikurasi Ahli</div>
    <div class="marquee-item"><i class="bi bi-graph-up-arrow"></i> Analisis Real-Time</div>
    <div class="marquee-item"><i class="bi bi-clock-history"></i> Timer CAT Autentik</div>
    <div class="marquee-item"><i class="bi bi-people-fill"></i> 60+ Peserta Aktif</div>
    <div class="marquee-item"><i class="bi bi-trophy-fill"></i> 75% Tingkat Lulus</div>
    <div class="marquee-item"><i class="bi bi-book-fill"></i> 330 Bank Soal</div>
    <div class="marquee-item"><i class="bi bi-bar-chart-fill"></i> Laporan Performa</div>
  </div>
</div>

<!-- ══════════ CERITA KAMI ══════════ -->
<section class="cerita-sec" id="cerita">
  <div class="container">
    <div class="row gy-5 align-items-start">
      <!-- Timeline -->
      <div class="col-lg-6 reveal-left">
        <span class="sec-chip">Perjalanan Kami</span>
        <h2 class="sec-title mb-3">Dari Mimpi Menjadi <span class="acc">Kenyataan</span></h2>
        <p class="sec-sub mb-5">Oman's Club Academy lahir dari kegelisahan dan betapa sulitnya menemukan media belajar CPNS yang autentik dan terjangkau. Kami hadir untuk mengubah itu.</p>
        <div class="timeline">
          <div class="tl-item reveal reveal-delay-1">
            <div class="tl-dot"><i class="bi bi-lightbulb-fill"></i></div>
            <div>
              <div class="tl-year">Januari 2021</div>
              <div class="tl-title">Ide Lahir</div>
              <div class="tl-desc">Berawal dari obrolan di antara alumni yang frustasi dengan mahalnya bimbel CPNS konvensional. Kami mulai merancang konsep platform CAT berbasis web.</div>
            </div>
          </div>
          <div class="tl-item reveal reveal-delay-2">
            <div class="tl-dot"><i class="bi bi-code-slash"></i></div>
            <div>
              <div class="tl-year">Januari 2026</div>
              <div class="tl-title">Pengembangan Sistem</div>
              <div class="tl-desc">Tim mulai membangun infrastruktur CAT dengan  engine soal acak, timer real-time, dan sistem skoring otomatis sesuai standar BKN.</div>
            </div>
          </div>
          <div class="tl-item reveal reveal-delay-3">
            <div class="tl-dot"><i class="bi bi-book-fill"></i></div>
            <div>
              <div class="tl-year">Maret 2026</div>
              <div class="tl-title">Bank Soal Perdana</div>
              <div class="tl-desc">Soal TWK, TIU, dan TKP dikurasi dari tim ahli yang mengacu pada kisi-kisi SKD BKN terbaru. Setiap soal dilengkapi pembahasan mendalam.</div>
            </div>
          </div>
          <div class="tl-item reveal reveal-delay-4">
            <div class="tl-dot"><i class="bi bi-rocket-takeoff-fill"></i></div>
            <div>
              <div class="tl-year">Juni 2026</div>
              <div class="tl-title">Peluncuran Perdana</div>
              <div class="tl-desc">Sesi Tryout 1 dibuka. 60 peserta angkatan pertama bergabung. Antusiasme melampaui ekspektasi 75% berhasil melampaui passing grade SKD.</div>
            </div>
          </div>
        </div>
      </div>
      <!-- Story card -->
      <div class="col-lg-6 reveal-right">
        <div class="story-visual" style="padding:22px 22px 22px 22px;">
          <div class="story-img-frame">
            <div class="story-quote">
              <div class="sq-mark">"</div>
              <div class="sq-text">Kami tidak hanya ingin menjadi tempat latihan soal. Kami ingin menjadi teman seperjalanan setiap calon ASN — dari belajar, berlatih, hingga akhirnya lulus dan mengabdi pada negeri.</div>
              <div class="sq-author">
                <div class="sq-av">O</div>
                <div>
                  <div class="sq-name">Oman Syarif, S.Kom.</div>
                  <div class="sq-role">Founder & CEO, Oman's Club Academy</div>
                </div>
              </div>
            </div>
          </div>
          <div class="story-stat-chip sc1">
            <div class="ssc-icon" style="background:var(--frost);color:var(--blue-main);"><i class="bi bi-trophy-fill"></i></div>
            <div><div class="ssc-num">75%</div><div class="ssc-lbl">Tingkat Kelulusan</div></div>
          </div>
          <div class="story-stat-chip sc2">
            <div class="ssc-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-people-fill"></i></div>
            <div><div class="ssc-num">60+</div><div class="ssc-lbl">Peserta Terdaftar</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ ANGKA KAMI ══════════ -->
<section class="stats-sec">
  <div class="ss-orb o1"></div>
  <div class="ss-orb o2"></div>
  <div class="container position-relative" style="z-index:1;">
    <div class="text-center mb-5 reveal">
      <span class="sec-chip light">Dalam Angka</span>
      <h2 class="sec-title light mt-2">Pencapaian yang <span class="acc">Berbicara</span></h2>
    </div>
    <div class="d-flex justify-content-center align-items-center gap-3 gap-md-5 flex-wrap">
      <div class="big-stat reveal reveal-delay-1">
        <div class="big-num" id="cnt-peserta">0</div>
        <div class="big-lbl">Peserta Terdaftar</div>
      </div>
      <div class="stat-divider"></div>
      <div class="big-stat reveal reveal-delay-2">
        <div class="big-num gold" id="cnt-soal">0</div>
        <div class="big-lbl">Bank Soal Aktif</div>
      </div>
      <div class="stat-divider"></div>
      <div class="big-stat reveal reveal-delay-3">
        <div class="big-num green" id="cnt-lulus">0%</div>
        <div class="big-lbl">Tingkat Kelulusan SKD</div>
      </div>
      <div class="stat-divider"></div>
      <div class="big-stat reveal reveal-delay-4">
        <div class="big-num" id="cnt-sesi">0</div>
        <div class="big-lbl">Sesi Tryout Berjalan</div>
      </div>
    </div>
    <!-- Progress bar SKD -->
    <div class="row justify-content-center mt-5 g-4">
      <div class="col-md-3 col-sm-6 reveal reveal-delay-1">
        <div style="text-align:center;margin-bottom:.6rem;">
          <span style="font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:rgba(255,255,255,.42);">Rata-rata TWK</span>
          <span style="float:right;font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--blue-pale);">72</span>
        </div>
        <div style="height:5px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden;">
          <div class="stat-bar-fill" data-w="48" style="height:100%;border-radius:3px;background:var(--blue-light);width:0;transition:width 1.2s ease;"></div>
        </div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.28);margin-top:.35rem;">Min. 65 · ✓ Melewati</div>
      </div>
      <div class="col-md-3 col-sm-6 reveal reveal-delay-2">
        <div style="text-align:center;margin-bottom:.6rem;">
          <span style="font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:rgba(255,255,255,.42);">Rata-rata TIU</span>
          <span style="float:right;font-family:'Playfair Display',serif;font-size:1.1rem;color:#6EE7B7;">85</span>
        </div>
        <div style="height:5px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden;">
          <div class="stat-bar-fill" data-w="49" style="height:100%;border-radius:3px;background:var(--emerald);width:0;transition:width 1.2s ease;"></div>
        </div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.28);margin-top:.35rem;">Min. 80 · ✓ Melewati</div>
      </div>
      <div class="col-md-3 col-sm-6 reveal reveal-delay-3">
        <div style="text-align:center;margin-bottom:.6rem;">
          <span style="font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:rgba(255,255,255,.42);">Rata-rata TKP</span>
          <span style="float:right;font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--gold-light);">166</span>
        </div>
        <div style="height:5px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden;">
          <div class="stat-bar-fill" data-w="74" style="height:100%;border-radius:3px;background:var(--gold);width:0;transition:width 1.2s ease;"></div>
        </div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.28);margin-top:.35rem;">Min. 166 · ⚠ Di Passing Grade</div>
      </div>
      <div class="col-md-3 col-sm-6 reveal reveal-delay-4">
        <div style="text-align:center;margin-bottom:.6rem;">
          <span style="font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:rgba(255,255,255,.42);">Rata-rata Total</span>
          <span style="float:right;font-family:'Playfair Display',serif;font-size:1.1rem;color:#fff;">334</span>
        </div>
        <div style="height:5px;background:rgba(255,255,255,.1);border-radius:3px;overflow:hidden;">
          <div class="stat-bar-fill" data-w="67" style="height:100%;border-radius:3px;background:linear-gradient(90deg,var(--blue-light),var(--emerald));width:0;transition:width 1.2s ease;"></div>
        </div>
        <div style="font-size:.72rem;color:rgba(255,255,255,.28);margin-top:.35rem;">Passing Grade 311 · ✓ Lulus</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ VISI MISI & NILAI ══════════ -->
<section class="visi-sec">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="sec-chip">Fondasi Kami</span>
      <h2 class="sec-title mt-2">Visi, Misi &amp; <span class="acc">Nilai</span></h2>
    </div>
    <div class="row g-4 mb-5">
      <div class="col-lg-6 reveal-left">
        <div class="visi-card">
          <div class="vc-glow g1"></div>
          <div class="vc-glow g2"></div>
          <div class="vc-tag visi-tag"><i class="bi bi-eye-fill"></i> Visi</div>
          <div class="vc-title">Menjadi Platform Tryout CPNS Terpercaya dan Terjangkau di Indonesia</div>
          <div class="vc-desc">Kami bermimpi tentang Indonesia di mana setiap calon ASN tanpa memandang latar belakang ekonomi memiliki akses yang setara terhadap persiapan seleksi berkualitas tinggi.</div>
        </div>
      </div>
      <div class="col-lg-6 reveal-right">
        <div class="visi-card">
          <div class="vc-glow g1"></div>
          <div class="vc-glow g2"></div>
          <div class="vc-tag misi-tag"><i class="bi bi-bullseye"></i> Misi</div>
          <div class="vc-title">Tiga Pilar yang Memandu Kami</div>
          <div class="misi-item">
            <div class="misi-bullet">01</div>
            <div class="misi-text"><strong>Simulasi Autentik:</strong> Menyediakan tryout CAT yang merefleksikan kondisi ujian SKD BKN sesungguhnya yang terdapat soal, timer, dan antarmuka yang identik.</div>
          </div>
          <div class="misi-item">
            <div class="misi-bullet">02</div>
            <div class="misi-text"><strong>Analisis Mendalam:</strong> Memberikan laporan performa yang actionable, bukan sekadar nilai, sehingga peserta tahu persis di mana harus fokus.</div>
          </div>
          <div class="misi-item">
            <div class="misi-bullet">03</div>
            <div class="misi-text"><strong>Aksesibilitas:</strong> Memastikan platform dapat diakses dari perangkat apapun, sehingga belajar tidak dibatasi oleh keterbatasan fisik.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Nilai Inti -->
    <div class="reveal">
      <div class="text-center mb-4">
        <span class="sec-chip gold">Nilai Inti</span>
        <h3 class="sec-title mt-1" style="font-size:1.6rem;">Prinsip yang Kami Pegang Teguh</h3>
      </div>
      <div class="values-grid">
        <div class="val-card c-blue reveal reveal-delay-1">
          <div class="val-icon blue"><i class="bi bi-patch-check-fill"></i></div>
          <div class="val-title">Integritas</div>
          <p class="val-desc">Setiap soal dikurasi dengan ketat, setiap nilai dihitung secara transparan. Tidak ada manipulasi hanya fakta tentang kemampuanmu saat ini.</p>
        </div>
        <div class="val-card c-gold reveal reveal-delay-2">
          <div class="val-icon gold"><i class="bi bi-lightbulb-fill"></i></div>
          <div class="val-title">Inovasi Berkelanjutan</div>
          <p class="val-desc">Kami terus memperbarui bank soal, fitur analisis, dan pengalaman pengguna berdasarkan feedback peserta dan perkembangan kisi-kisi BKN.</p>
        </div>
        <div class="val-card c-green reveal reveal-delay-3">
          <div class="val-icon green"><i class="bi bi-people-fill"></i></div>
          <div class="val-title">Komunitas Inklusif</div>
          <p class="val-desc">Setiap peserta adalah bagian dari keluarga Oman's Club, Kami percaya kolaborasi dan semangat saling support adalah kunci keberhasilan bersama.</p>
        </div>
        <div class="val-card c-purple reveal reveal-delay-4">
          <div class="val-icon purple"><i class="bi bi-graph-up-arrow"></i></div>
          <div class="val-title">Orientasi Hasil</div>
          <p class="val-desc">Metrik keberhasilan kami bukan jumlah pengguna, melainkan berapa banyak peserta yang berhasil lulus SKD dan mewujudkan mimpi menjadi ASN.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ TIM ══════════ -->
<section class="tim-sec">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="sec-chip">Di Balik Layar</span>
      <h2 class="sec-title mt-2">Tim yang <span class="acc">Berdedikasi</span></h2>
      <p class="sec-sub mx-auto mt-3">Orang-orang luar biasa yang bekerja tanpa henti untuk memastikan pengalaman belajarmu selalu terbaik.</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-3 col-md-6 reveal reveal-delay-1">
        <div class="team-card tc-gold">
          <div class="team-avatar-wrap">
            <div class="team-avatar av-gold">O</div>
            <div class="team-online"></div>
          </div>
          <div class="team-name">Oman Syarif, S.Kom.</div>
          <div class="team-role">Founder & CEO</div>
          <div class="team-desc">Inisiator visi Oman's Club Academy. Berpengalaman 5 tahun di bidang edukasi digital dan pengembangan platform CAT.</div>
          <div class="team-socials">
            <div class="ts-btn"><i class="bi bi-linkedin"></i></div>
            <div class="ts-btn"><i class="bi bi-instagram"></i></div>
            <div class="ts-btn"><i class="bi bi-envelope-fill"></i></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal reveal-delay-2">
        <div class="team-card tc-blue">
          <div class="team-avatar-wrap">
            <div class="team-avatar av-blue">A</div>
            <div class="team-online"></div>
          </div>
          <div class="team-name">Ari Pratama, S.T.</div>
          <div class="team-role">Lead Developer</div>
          <div class="team-desc">Arsitek sistem CAT Oman's Club. Memastikan platform berjalan mulus, cepat, dan andal untuk ratusan peserta serentak.</div>
          <div class="team-socials">
            <div class="ts-btn"><i class="bi bi-linkedin"></i></div>
            <div class="ts-btn"><i class="bi bi-github"></i></div>
            <div class="ts-btn"><i class="bi bi-envelope-fill"></i></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal reveal-delay-3">
        <div class="team-card tc-green">
          <div class="team-avatar-wrap">
            <div class="team-avatar av-green">D</div>
            <div class="team-online"></div>
          </div>
          <div class="team-name">Dewi Kartika, S.Pd.</div>
          <div class="team-role">Kurator Soal</div>
          <div class="team-desc">Mantan birokrat sekaligus pengajar berpengalaman. Menjamin setiap soal relevan dengan standar SKD BKN terbaru.</div>
          <div class="team-socials">
            <div class="ts-btn"><i class="bi bi-linkedin"></i></div>
            <div class="ts-btn"><i class="bi bi-instagram"></i></div>
            <div class="ts-btn"><i class="bi bi-envelope-fill"></i></div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 reveal reveal-delay-4">
        <div class="team-card tc-blue">
          <div class="team-avatar-wrap">
            <div class="team-avatar av-blue">R</div>
            <div class="team-online" style="background:var(--gold);"></div>
          </div>
          <div class="team-name">Reza Maulana, S.Ds.</div>
          <div class="team-role">UI/UX Designer</div>
          <div class="team-desc">Merancang setiap piksel antarmuka agar intuitif dan menyenangkan. Percaya bahwa belajar yang baik dimulai dari pengalaman yang nyaman.</div>
          <div class="team-socials">
            <div class="ts-btn"><i class="bi bi-linkedin"></i></div>
            <div class="ts-btn"><i class="bi bi-dribbble"></i></div>
            <div class="ts-btn"><i class="bi bi-envelope-fill"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FASILITAS ══════════ -->
<section class="fasilitas-sec">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-lg-5 reveal-left">
        <span class="sec-chip">Unggulan Kami</span>
        <h2 class="sec-title mt-2">Dilengkapi Fitur yang <span class="acc">Tepat Sasaran</span></h2>
        <p class="sec-sub mt-3">Setiap fitur dirancang dengan satu tujuan yaitu membuat persiapan CPNS menjadi seefektif mungkin.</p>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-md-6 col-lg-4 reveal reveal-delay-1">
        <div class="fas-card">
          <div class="fas-icon" style="background:var(--frost);color:var(--blue-main);"><i class="bi bi-clock-history"></i></div>
          <div><div class="fas-title">Timer CAT Autentik</div><p class="fas-desc">Countdown real-time persis seperti ujian SKD BKN. Soal otomatis dikumpulkan saat waktu habis.</p></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 reveal reveal-delay-2">
        <div class="fas-card">
          <div class="fas-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-shuffle"></i></div>
          <div><div class="fas-title">Acak Soal Otomatis</div><p class="fas-desc">Soal diacak unik untuk setiap peserta, menghindari kebocoran dan memastikan evaluasi yang adil.</p></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 reveal reveal-delay-3">
        <div class="fas-card">
          <div class="fas-icon" style="background:#FFFBEB;color:var(--gold);"><i class="bi bi-bar-chart-fill"></i></div>
          <div><div class="fas-title">Analisis Instan</div><p class="fas-desc">Nilai keluar detik setelah kumpul. Lengkap dengan breakdown TWK, TIU, TKP dan perbandingan passing grade.</p></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 reveal reveal-delay-1">
        <div class="fas-card">
          <div class="fas-icon" style="background:var(--frost);color:var(--blue-main);"><i class="bi bi-grid-3x3-gap-fill"></i></div>
          <div><div class="fas-title">Navigasi Soal Visual</div><p class="fas-desc">Panel navigasi soal bergambar: langsung lompat ke soal manapun, tandai ragu, dan pantau progres sekilas.</p></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 reveal reveal-delay-2">
        <div class="fas-card">
          <div class="fas-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-graph-up-arrow"></i></div>
          <div><div class="fas-title">Riwayat & Tren Nilai</div><p class="fas-desc">Lacak perkembangan nilaimu dari sesi ke sesi. Grafik tren membantu kamu melihat area yang perlu ditingkatkan.</p></div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 reveal reveal-delay-3">
        <div class="fas-card">
          <div class="fas-icon" style="background:#F5F3FF;color:#6D28D9;"><i class="bi bi-phone-fill"></i></div>
          <div><div class="fas-title">Responsif Semua Perangkat</div><p class="fas-desc">Belajar dari HP, tablet, atau laptop. Antarmuka menyesuaikan layar otomatis tanpa kehilangan fungsionalitas.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ CTA ══════════ -->
<section class="cta-sec">
  <div class="container">
    <div class="cta-inner reveal">
      <div class="cta-glow g1"></div>
      <div class="cta-glow g2"></div>
      <div class="cta-badge"><i class="bi bi-rocket-takeoff-fill"></i> Mulai Perjalananmu</div>
      <h2 class="cta-title">Siap Menjadi <span class="acc">ASN Terbaik</span><br>Generasimu?</h2>
      <p class="cta-desc">Bergabunglah bersama 60+ peserta yang sudah memulai persiapan mereka. Daftar gratis, mulai tryout hari ini.</p>
      <div class="cta-btns">
        <button class="btn-cta-primary" onclick="window.location.href='login.html'">
          <i class="bi bi-person-plus-fill me-1"></i> Daftar Sekarang — Gratis
        </button>
        <button class="btn-cta-ghost" onclick="window.location.href='login.html'">
          <i class="bi bi-box-arrow-in-right me-1"></i> Sudah punya akun? Masuk
        </button>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FOOTER ══════════ -->
<footer class="footer">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-4">
        <div class="footer-brand d-flex align-items-center gap-2 mb-3">
          <div class="brand-icon"><i class="bi bi-mortarboard-fill text-white" style="font-size:.9rem;"></i></div>
          <span class="brand-text">Oman's<span> Club</span></span>
        </div>
        <p class="footer-desc">Platform tryout CPNS berbasis CAT untuk mempersiapkan calon ASN menghadapi seleksi SKD BKN dengan percaya diri.</p>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading">Platform</div>
        <a href="#" class="footer-link">Fitur</a>
        <a href="#" class="footer-link">Tryout SKD</a>
        <a href="#" class="footer-link">Bank Soal</a>
        <a href="#" class="footer-link">Analisis Nilai</a>
      </div>
      <div class="col-lg-2 col-6">
        <div class="footer-heading">Perusahaan</div>
        <a href="tentang-kami.html" class="footer-link" style="color:rgba(255,255,255,.6);">Tentang Kami</a>
        <a href="#" class="footer-link">Tim</a>
        <a href="#" class="footer-link">Blog</a>
        <a href="#" class="footer-link">Karir</a>
      </div>
      <div class="col-lg-4">
        <div class="footer-heading">Kontak</div>
        <div style="display:flex;flex-direction:column;gap:.5rem;">
          <div style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;color:rgba(255,255,255,.38);">
            <i class="bi bi-envelope-fill" style="color:var(--blue-light);"></i>
            admin@omansclub.ac.id
          </div>
          <div style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;color:rgba(255,255,255,.38);">
            <i class="bi bi-whatsapp" style="color:var(--emerald);"></i>
            +62 812-0000-0001
          </div>
          <div style="display:flex;align-items:center;gap:.6rem;font-size:.85rem;color:rgba(255,255,255,.38);">
            <i class="bi bi-geo-alt-fill" style="color:var(--gold);"></i>
            Bandar Lampung, Indonesia
          </div>
        </div>
        <div style="display:flex;gap:.5rem;margin-top:1.1rem;">
          <div class="ts-btn" style="border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.38);"><i class="bi bi-instagram"></i></div>
          <div class="ts-btn" style="border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.38);"><i class="bi bi-linkedin"></i></div>
          <div class="ts-btn" style="border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.38);"><i class="bi bi-twitter-x"></i></div>
          <div class="ts-btn" style="border-color:rgba(255,255,255,.1);color:rgba(255,255,255,.38);"><i class="bi bi-youtube"></i></div>
        </div>
      </div>
    </div>
    <div class="footer-divider"></div>
    <div class="footer-bottom">
      <span>© 2026 Oman's Club Academy. Hak cipta dilindungi undang-undang.</span>
      <div style="display:flex;gap:1.4rem;">
        <a href="#">Kebijakan Privasi</a>
        <a href="#">Syarat & Ketentuan</a>
      </div>
    </div>
  </div>
</footer>

<!-- ══════════ BACK TO TOP ══════════ -->
<button class="btt" id="btt" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ══ NAVBAR scroll ══ */
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 60);
  document.getElementById('btt').classList.toggle('show', window.scrollY > 400);
});

/* ══ SCROLL REVEAL ══ */
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      // trigger bar fills
      e.target.querySelectorAll('.stat-bar-fill').forEach(b => {
        b.style.width = b.dataset.w + '%';
      });
    }
  });
}, { threshold: 0.15 });
revealEls.forEach(el => revealObserver.observe(el));

/* Trigger bar fills that are inside stat-sec (already in view on mobile) */
document.querySelectorAll('.stat-bar-fill').forEach(b => {
  const parent = b.closest('.reveal, .reveal-left, .reveal-right');
  if (!parent) b.style.width = b.dataset.w + '%';
});

/* ══ COUNTER ANIMATION ══ */
function animateCount(el, target, suffix, duration) {
  let start = 0, step = target / (duration / 16);
  const timer = setInterval(() => {
    start = Math.min(start + step, target);
    el.textContent = Math.round(start) + suffix;
    if (start >= target) clearInterval(timer);
  }, 16);
}

const statsSection = document.querySelector('.stats-sec');
let counted = false;
const cntObserver = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting && !counted) {
    counted = true;
    animateCount(document.getElementById('cnt-peserta'), 60, '+', 1400);
    animateCount(document.getElementById('cnt-soal'), 330, '', 1400);
    animateCount(document.getElementById('cnt-lulus'), 75, '%', 1400);
    animateCount(document.getElementById('cnt-sesi'), 5, '', 900);
    // trigger progress bars in this section
    document.querySelectorAll('.stat-bar-fill').forEach(b => {
      setTimeout(() => { b.style.width = b.dataset.w + '%'; }, 400);
    });
  }
}, { threshold: 0.3 });
cntObserver.observe(statsSection);
</script>
</body>
</html>
