<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Technical Head Portal — Aceledu</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#0a0a0f;--surface:#14141d;--surface2:#1a1a26;--card2:#1f1f2c;
  --border:#262635;--border2:#353548;
  --accent:#4f8eff;--accent2:#a855f7;--accent3:#10b981;
  --text:#f0f0f5;--text2:#a8a8b8;--text3:#6a6a78;
  --p:#4f8eff;--a:#22d3ee;--al:rgba(34,211,238,.12);--pl:#1a1a26;
  --mu:#6a6a78;--bd:rgba(79,142,255,0.18);
  --su:#10b981;--sub:rgba(16,185,129,.15);--wa:#f59e0b;--wab:rgba(245,158,11,.15);
  --da:#ef4444;--dab:rgba(239,68,68,.15);
  --r:12px;--rs:8px;
  --shadow:0 1px 3px rgba(0,0,0,0.35),0 4px 16px rgba(0,0,0,0.25);
  --shadow2:0 4px 12px rgba(0,0,0,0.5),0 16px 40px rgba(0,0,0,0.35);
  --c-fs:#6366f1;--c-ux:#ec4899;--c-dm:#f59e0b;--c-da:#10b981;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);}
::-webkit-scrollbar{width:6px;height:6px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:#2e2e3f;border-radius:4px;}
::-webkit-scrollbar-thumb:hover{background:#3a3a4d;}

.shell{display:flex;height:100vh;overflow:hidden;}
.sidebar{width:240px;min-width:240px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.content{flex:1;overflow-y:auto;padding:24px 28px;}

.logo-wrap{padding:20px 20px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.logo-mark{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:17px;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(79,142,255,.35);}
.logo-name{font-family:'Playfair Display',serif;font-weight:700;font-size:16px;color:var(--text);}
.logo-role{font-size:10px;color:var(--text3);font-weight:500;margin-top:1px;}
.nav-sec{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;padding:14px 20px 5px;}
.nitem{display:flex;align-items:center;gap:11px;padding:10px 20px;cursor:pointer;transition:all .18s;color:var(--text2);font-size:13.5px;font-weight:500;position:relative;user-select:none;}
.nitem:hover{background:rgba(79,142,255,.07);color:var(--accent);}
.nitem.on{background:rgba(79,142,255,.12);color:var(--accent);font-weight:600;}
.nitem.on::before{content:'';position:absolute;left:0;top:7px;bottom:7px;width:3px;border-radius:0 3px 3px 0;background:var(--accent);box-shadow:0 0 8px var(--accent);}
.nitem .nic{font-size:15px;flex-shrink:0;width:20px;text-align:center;}
.nitem .nlabel{flex:1;}
.nbadge{padding:1px 7px;border-radius:20px;font-size:10px;font-weight:700;color:#fff;}
.nb-r{background:var(--da);}.nb-g{background:var(--su);}.nb-a{background:var(--wa);}.nb-b{background:var(--accent);}
.sidebar-foot{margin-top:auto;border-top:1px solid var(--border);padding:14px;}
.user-pill{display:flex;align-items:center;gap:9px;background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:9px 11px;cursor:pointer;}
.user-av{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--accent2),#c084fc);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;}
.user-name{font-size:12.5px;font-weight:600;color:var(--text);}
.user-sub{font-size:10.5px;color:var(--text3);}
.online{width:7px;height:7px;border-radius:50%;background:var(--su);box-shadow:0 0 8px rgba(16,185,129,.7);flex-shrink:0;}

.topbar{display:flex;align-items:center;gap:12px;padding:13px 28px;background:var(--surface);border-bottom:1px solid var(--border);flex-shrink:0;}
.tb-title{font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:var(--text);flex:1;}
.tb-title em{color:var(--accent2);font-style:normal;}
.search-box{display:flex;align-items:center;gap:8px;background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:8px 13px;width:230px;}
.search-box input{border:none;background:transparent;outline:none;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);width:100%;}
.search-box input::placeholder{color:var(--text3);}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;border:none;border-radius:9px;cursor:pointer;font-family:'Outfit',sans-serif;font-weight:600;transition:all .18s;}
.btn:hover{transform:translateY(-1px);}
.btn-pri{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;padding:9px 18px;font-size:13px;box-shadow:0 4px 12px rgba(79,142,255,.25);}
.btn-pri:hover{opacity:.92;box-shadow:0 6px 16px rgba(79,142,255,.4);}
.btn-ghost{background:var(--card2);border:1px solid var(--border2);color:var(--text2);padding:8px 16px;font-size:13px;}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent);background:rgba(79,142,255,.08);}
.btn-danger{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;padding:9px 18px;font-size:13px;}
.btn-success{background:linear-gradient(135deg,#10b981,#059669);color:#fff;padding:9px 18px;font-size:13px;}
.btn-warning{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:9px 18px;font-size:13px;}
.btn-sm{padding:7px 14px;font-size:12px;}
.btn-xs{padding:4px 11px;font-size:11px;border-radius:7px;}
.btn-connect-sm{background:transparent;border:1px solid rgba(168,85,247,.4);color:#c084fc;padding:5px 11px;font-size:11.5px;border-radius:7px;display:inline-flex;align-items:center;gap:5px;font-weight:600;cursor:pointer;font-family:'Outfit',sans-serif;transition:all .15s;}
.btn-connect-sm:hover{background:rgba(168,85,247,.12);transform:translateY(-1px);}

.view{display:none;}.view.active{display:block;}

.card{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow);}
.card-pad{padding:20px;}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:10px;flex-wrap:wrap;}
.card-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;}
.cbadge{background:rgba(79,142,255,.12);color:var(--accent);border:1px solid rgba(79,142,255,.25);border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;}

.stats-students{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:18px;}
.stat-mini{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;position:relative;overflow:hidden;transition:all .2s;cursor:pointer;}
.stat-mini:hover{transform:translateY(-2px);border-color:var(--border2);box-shadow:var(--shadow2);}
.stat-mini::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.sm-fs::after{background:linear-gradient(90deg,var(--c-fs),transparent);}
.sm-ux::after{background:linear-gradient(90deg,var(--c-ux),transparent);}
.sm-dm::after{background:linear-gradient(90deg,var(--c-dm),transparent);}
.sm-ds::after{background:linear-gradient(90deg,var(--c-da),transparent);}
.sm-bl::after{background:linear-gradient(90deg,var(--accent),transparent);}
.sm-pu::after{background:linear-gradient(90deg,var(--accent2),transparent);}
.sm-wa::after{background:linear-gradient(90deg,var(--wa),transparent);}
.sm-rd::after{background:linear-gradient(90deg,var(--da),transparent);}
.sm-ico{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:10px;}
.sm-ico-fs{background:rgba(99,102,241,.15);color:var(--c-fs);}
.sm-ico-ux{background:rgba(236,72,153,.15);color:var(--c-ux);}
.sm-ico-dm{background:rgba(245,158,11,.15);color:var(--c-dm);}
.sm-ico-ds{background:rgba(16,185,129,.15);color:var(--c-da);}
.sm-ico-bl{background:rgba(79,142,255,.15);color:var(--accent);}
.sm-ico-pu{background:rgba(168,85,247,.15);color:var(--accent2);}
.sm-lbl{font-size:10.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;}
.sm-num{font-family:'Playfair Display',serif;font-size:30px;font-weight:700;color:var(--text);line-height:1;}
.sm-sub{font-size:10.5px;color:var(--text3);margin-top:6px;}

.stats3{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:18px;}
.stats4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px;}

.ctabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;}
.ctab{padding:7px 16px;border-radius:9px;background:var(--surface);border:1px solid var(--border);color:var(--text2);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .18s;user-select:none;}
.ctab:hover{border-color:var(--border2);color:var(--text);}
.ctab.on{background:rgba(79,142,255,.12);border-color:rgba(79,142,255,.35);color:var(--accent);}
.ctab-fs.on{background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.4);color:#a5b4fc;}
.ctab-ux.on{background:rgba(236,72,153,.15);border-color:rgba(236,72,153,.4);color:#f9a8d4;}
.ctab-dm.on{background:rgba(245,158,11,.15);border-color:rgba(245,158,11,.4);color:#fbbf24;}
.ctab-da.on{background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.4);color:#6ee7b7;}

.mtabs{display:flex;gap:3px;background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:3px;margin-bottom:16px;max-width:560px;}
.mtab{flex:1;padding:9px 14px;border-radius:7px;border:none;background:transparent;cursor:pointer;font-family:'Outfit',sans-serif;font-size:12.5px;font-weight:600;color:var(--mu);transition:all .15s;text-align:center;}
.mtab:hover{color:var(--text);}
.mtab.on{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;box-shadow:0 2px 10px rgba(79,142,255,.35);}

.session-strip{display:flex;align-items:center;gap:14px;padding:12px 14px;background:var(--card2);border:1px solid var(--border);border-radius:11px;margin-bottom:8px;transition:all .15s;}
.session-strip:hover{border-color:var(--border2);background:var(--surface2);}
.ss-av{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:14px;color:#fff;flex-shrink:0;}
.ss-body{flex:1;min-width:0;}
.ss-name{font-size:13.5px;font-weight:700;color:var(--text);}
.ss-name.under{text-decoration:underline;text-underline-offset:3px;cursor:pointer;color:var(--accent);}
.ss-meta{font-size:11px;color:var(--text3);margin-top:3px;display:flex;gap:8px;flex-wrap:wrap;}
.ss-meta .dot{color:var(--border2);}
.ss-pill{padding:4px 11px;border-radius:20px;font-size:10.5px;font-weight:600;}
.ss-pill-booked{background:rgba(79,142,255,.15);color:#7ba8ff;border:1px solid rgba(79,142,255,.3);}
.ss-pill-doubt{background:rgba(168,85,247,.15);color:#c084fc;border:1px solid rgba(168,85,247,.3);}
.ss-pill-done{background:rgba(16,185,129,.15);color:#34d399;border:1px solid rgba(16,185,129,.3);}

.act-tabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;}
.act-tab{padding:6px 13px;border-radius:8px;background:var(--card2);border:1px solid var(--border);color:var(--text2);font-size:11.5px;font-weight:600;cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;gap:5px;}
.act-tab:hover{border-color:var(--border2);color:var(--text);}
.act-tab.on{background:linear-gradient(135deg,var(--accent),var(--accent2));border-color:transparent;color:#fff;}
.act-row{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--border);}
.act-row:last-child{border-bottom:none;}
.act-av{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;}
.act-info{flex:1;min-width:0;}
.act-who{font-size:13px;font-weight:700;color:var(--text);}
.act-what{font-size:11.5px;color:var(--text2);margin-top:2px;}
.act-tag{font-family:'Outfit',sans-serif;font-size:11px;font-weight:700;color:var(--accent);background:rgba(79,142,255,.1);padding:2px 8px;border-radius:6px;border:1px solid rgba(79,142,255,.2);}
.act-when{font-size:11px;color:var(--text3);flex-shrink:0;}

.strip{display:flex;align-items:stretch;background:var(--surface);border:1px solid var(--border);border-radius:11px;margin-bottom:9px;overflow:hidden;transition:all .15s;}
.strip:hover{border-color:var(--border2);transform:translateX(2px);}
.strip-acc{width:5px;flex-shrink:0;}
.sa-fs{background:linear-gradient(180deg,var(--c-fs),#a5b4fc);}
.sa-ux{background:linear-gradient(180deg,var(--c-ux),#f9a8d4);}
.sa-dm{background:linear-gradient(180deg,var(--c-dm),#fbbf24);}
.sa-da{background:linear-gradient(180deg,var(--c-da),#6ee7b7);}
.strip-num{width:52px;display:flex;flex-direction:column;align-items:center;justify-content:center;border-right:1px solid var(--border);background:var(--card2);flex-shrink:0;}
.sn-h{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;}
.sn-v{font-family:'Playfair Display',serif;font-size:18px;font-weight:800;color:var(--accent);line-height:1;}
.strip-body{flex:1;padding:11px 14px;min-width:0;}
.strip-row1{display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;}
.strip-title{font-size:13.5px;font-weight:700;color:var(--text);}
.strip-row2{display:flex;align-items:center;gap:7px;flex-wrap:wrap;font-size:11px;color:var(--text3);}
.strip-row2 .dot{color:var(--border2);}
.strip-actions{display:flex;align-items:center;gap:6px;padding:0 14px;flex-shrink:0;}

.pill{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.p-active{background:rgba(16,185,129,.15);color:#34d399;border:1px solid rgba(16,185,129,.3);}
.p-inactive{background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);}
.p-pending{background:rgba(245,158,11,.15);color:#fbbf24;border:1px solid rgba(245,158,11,.3);}
.p-upcoming{background:rgba(79,142,255,.15);color:#93c5fd;border:1px solid rgba(79,142,255,.3);}
.p-low{background:rgba(79,142,255,.15);color:#93c5fd;border:1px solid rgba(79,142,255,.3);}
.p-fs{background:rgba(99,102,241,.15);color:#a5b4fc;border:1px solid rgba(99,102,241,.3);}
.p-ux{background:rgba(236,72,153,.15);color:#f9a8d4;border:1px solid rgba(236,72,153,.3);}
.p-dm{background:rgba(245,158,11,.15);color:#fbbf24;border:1px solid rgba(245,158,11,.3);}
.p-da{background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);}

.chip{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--card2);color:var(--text2);border:1px solid var(--border);}

.data-table{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.dt-head{display:grid;padding:11px 18px;background:var(--card2);border-bottom:1px solid var(--border);font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;user-select:none;}
.dt-row{display:grid;padding:13px 18px;border-bottom:1px solid var(--border);align-items:center;cursor:pointer;transition:background .15s;}
.dt-row:hover{background:var(--card2);}
.dt-row:last-child{border-bottom:none;}
.cols-staff{grid-template-columns:38px 1.5fr 1fr 80px 1fr 90px 100px;}
.cols-stu{grid-template-columns:34px 1.4fr 1fr 1fr 90px;}
.sav{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:11px;color:#fff;}
.sname{font-weight:600;font-size:13px;color:var(--text);}
.sname.linky{cursor:pointer;}
.sname.linky:hover{color:var(--accent);text-decoration:underline;}
.semail{font-size:11px;color:var(--text3);}

.bs-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
.bs-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;cursor:pointer;transition:all .22s;position:relative;overflow:hidden;}
.bs-card:hover{transform:translateY(-3px);border-color:var(--border2);box-shadow:var(--shadow2);}
.bs-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.bs-fs::before{background:linear-gradient(90deg,var(--c-fs),#a5b4fc);}
.bs-ux::before{background:linear-gradient(90deg,var(--c-ux),#f9a8d4);}
.bs-dm::before{background:linear-gradient(90deg,var(--c-dm),#fbbf24);}
.bs-da::before{background:linear-gradient(90deg,var(--c-da),#6ee7b7);}
.bs-code{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;}
.bs-name{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);margin-bottom:8px;}
.bs-meta{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;}
.bs-row{display:flex;align-items:center;justify-content:space-between;font-size:12px;padding:6px 0;}
.bs-row .l{color:var(--text2);}
.bs-row .v{font-weight:600;color:var(--text);}
.bs-progress{margin:10px 0 8px;}
.bs-pwrap{height:6px;background:var(--border);border-radius:99px;overflow:hidden;margin-bottom:5px;}
.bs-pfill{height:100%;border-radius:99px;}
.bs-pf-fs{background:linear-gradient(90deg,var(--c-fs),#a5b4fc);}
.bs-pf-ux{background:linear-gradient(90deg,var(--c-ux),#f9a8d4);}
.bs-pf-dm{background:linear-gradient(90deg,var(--c-dm),#fbbf24);}
.bs-pf-da{background:linear-gradient(90deg,var(--c-da),#6ee7b7);}

.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--text2);font-size:13px;font-weight:500;cursor:pointer;margin-bottom:16px;padding:6px 12px;border-radius:8px;transition:all .15s;}
.back-link:hover{background:var(--card2);color:var(--accent);}

.bd-hero{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;margin-bottom:16px;position:relative;overflow:hidden;}
.bd-hero::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.bs-fs.bd-hero::before{background:linear-gradient(90deg,var(--c-fs),#a5b4fc);}
.bs-ux.bd-hero::before{background:linear-gradient(90deg,var(--c-ux),#f9a8d4);}
.bs-dm.bd-hero::before{background:linear-gradient(90deg,var(--c-dm),#fbbf24);}
.bs-da.bd-hero::before{background:linear-gradient(90deg,var(--c-da),#6ee7b7);}
.bd-head{display:flex;justify-content:space-between;align-items:flex-start;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.bd-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;padding-top:14px;border-top:1px solid var(--border);}
.bd-stat{text-align:center;}
.bd-stat-v{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--text);line-height:1;}
.bd-stat-l{font-size:10.5px;color:var(--text3);margin-top:4px;}

.prog{display:flex;align-items:center;gap:8px;}
.prog-tk{flex:1;height:6px;background:var(--border);border-radius:99px;overflow:hidden;}
.prog-fl{height:100%;border-radius:99px;}
.prog-v{font-size:11.5px;font-weight:700;color:var(--text);min-width:36px;text-align:right;}

.pg-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;margin-bottom:12px;transition:all .15s;cursor:pointer;position:relative;overflow:hidden;}
.pg-card:hover{border-color:var(--border2);transform:translateY(-1px);box-shadow:var(--shadow2);}
.pg-card::before{content:'';position:absolute;top:0;left:0;bottom:0;width:4px;}
.pg-card.bs-fs::before{background:linear-gradient(180deg,var(--c-fs),#a5b4fc);}
.pg-card.bs-ux::before{background:linear-gradient(180deg,var(--c-ux),#f9a8d4);}
.pg-card.bs-dm::before{background:linear-gradient(180deg,var(--c-dm),#fbbf24);}
.pg-card.bs-da::before{background:linear-gradient(180deg,var(--c-da),#6ee7b7);}
.pg-head{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-bottom:14px;}
.pg-info-l{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--text);}
.pg-info-m{font-size:12px;color:var(--text3);margin-top:3px;}
.pg-score-wrap{display:flex;align-items:center;gap:12px;}
.pg-score{text-align:right;}
.pg-score-v{font-family:'Playfair Display',serif;font-size:30px;font-weight:800;line-height:1;}
.pg-score-l{font-size:10.5px;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-top:3px;}
.pg-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.pg-cell{background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:12px;}
.pg-cell-l{font-size:10.5px;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;font-weight:700;margin-bottom:8px;}
.pg-cell-v{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--text);margin-bottom:6px;}
.pg-cell-bar{height:5px;background:var(--border);border-radius:99px;overflow:hidden;}
.pg-cell-bf{height:100%;border-radius:99px;}
.pg-foot{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid var(--border);flex-wrap:wrap;}

.overlay{position:fixed;inset:0;background:rgba(0,0,0,.7);display:none;align-items:center;justify-content:center;z-index:1000;backdrop-filter:blur(6px);}
.overlay.show{display:flex;}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:16px;padding:24px;width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 24px 60px rgba(0,0,0,.6);}
.modal-lg{width:620px;}
.mhead{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;}
.mtitle{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--text);}
.msub{font-size:12px;color:var(--text3);margin-top:2px;}
.mclose{width:27px;height:27px;border-radius:7px;border:none;background:var(--card2);cursor:pointer;font-size:14px;color:var(--text2);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s;}
.mclose:hover{background:var(--border);color:var(--text);}
.fg{margin-bottom:13px;}
.fg-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.flabel{font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:5px;}
.finp,.fsel,.fta{width:100%;background:var(--card2);border:1px solid var(--border2);border-radius:var(--rs);padding:9px 12px;color:var(--text);font-family:'Outfit',sans-serif;font-size:13px;outline:none;transition:border-color .18s;}
.finp::placeholder,.fta::placeholder{color:var(--text3);}
.finp:focus,.fsel:focus,.fta:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,142,255,.15);}
.fsel{appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236a6a78'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 8px center;padding-right:32px;}
.fsel option{background:var(--surface);color:var(--text);}
.finp[type="date"],.finp[type="time"]{color-scheme:dark;}
.fta{resize:vertical;min-height:75px;}
.fchk{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text2);cursor:pointer;user-select:none;padding:8px 12px;background:var(--card2);border:1px solid var(--border);border-radius:8px;transition:all .15s;}
.fchk:hover{border-color:var(--border2);}
.fchk.on{background:rgba(79,142,255,.12);border-color:var(--accent);color:var(--accent);font-weight:600;}
.fchk input{cursor:pointer;accent-color:var(--accent);}
.mfooter{display:flex;gap:10px;margin-top:16px;}

.fup{border:1.5px dashed var(--border2);border-radius:10px;padding:18px;text-align:center;cursor:pointer;background:var(--card2);transition:all .18s;}
.fup:hover{border-color:var(--accent);background:rgba(79,142,255,.05);}
.fup-ico{font-size:26px;margin-bottom:6px;color:var(--accent);}
.fup-t{font-size:13px;font-weight:600;color:var(--text);}
.fup-s{font-size:11px;color:var(--text3);margin-top:3px;}
.fup-pick{color:var(--accent);text-decoration:underline;cursor:pointer;}
.fup-file{display:none;}
.fup-active{border-color:var(--su);background:rgba(16,185,129,.07);}
.fup-active .fup-ico{color:var(--su);}

.dash-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;}

.slot-layout{display:grid;grid-template-columns:1fr 320px;gap:16px;}
.slot-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.slot-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--card2);display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap;}
.slot-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--text);}
.slot-sub{font-size:12px;color:var(--text3);margin-top:2px;}
.date-tabs{display:flex;border-bottom:1px solid var(--border);}
.dtab{flex:1;padding:9px 4px;text-align:center;font-size:12px;font-weight:600;color:var(--text2);cursor:pointer;border-bottom:2px solid transparent;transition:all .15s;user-select:none;}
.dtab:hover{color:var(--accent);}
.dtab.on{color:var(--accent);border-bottom-color:var(--accent);background:rgba(79,142,255,.07);}
.slots-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px;}
.slot-box{border:1.5px solid var(--border);border-radius:10px;padding:11px 10px;background:var(--card2);transition:all .18s;}
.slot-box.sb-booked{border-color:rgba(79,142,255,.4);background:rgba(79,142,255,.07);}
.slot-num{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.slot-time{font-size:13px;font-weight:700;color:var(--text);}
.slot-avail{font-size:11px;color:var(--su);font-weight:500;margin-top:3px;}
.slot-bname{font-size:11.5px;color:var(--accent);font-weight:600;margin-top:3px;}
.slot-bcourse{font-size:10px;color:var(--text3);}
.aside-panel{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;}
.aside-title{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);margin-bottom:12px;}
.aside-it{background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:10px;margin-bottom:7px;font-size:12px;}
.aside-it .nm{font-weight:600;color:var(--text);}
.aside-it .mt{font-size:10.5px;color:var(--text3);margin-top:2px;}

.filter-row{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:14px;}
.filter-search{display:flex;align-items:center;gap:6px;background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:7px 10px;flex:1;min-width:200px;max-width:280px;}
.filter-search input{border:none;background:transparent;outline:none;font-family:'Outfit',sans-serif;font-size:12.5px;color:var(--text);width:100%;}
.filter-search input::placeholder{color:var(--text3);}
.filter-date{background:var(--card2);border:1px solid var(--border);border-radius:8px;padding:7px 10px;color:var(--text);font-family:'Outfit',sans-serif;font-size:12.5px;outline:none;color-scheme:dark;}
.f-btn{padding:7px 14px;border-radius:8px;background:var(--card2);border:1px solid var(--border);color:var(--text2);font-size:12px;font-weight:600;cursor:pointer;font-family:'Outfit',sans-serif;}
.f-btn:hover{border-color:var(--border2);color:var(--text);}
.f-btn.on{background:linear-gradient(135deg,var(--accent),var(--accent2));border-color:transparent;color:#fff;}

.meet-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px;margin-bottom:10px;display:flex;align-items:center;gap:14px;transition:all .15s;}
.meet-card:hover{border-color:var(--border2);}
.meet-icon{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.mi-staff{background:rgba(79,142,255,.15);color:var(--accent);}
.mi-group{background:rgba(236,72,153,.15);color:var(--c-ux);}
.mi-mine{background:rgba(168,85,247,.15);color:var(--accent2);}
.meet-body{flex:1;min-width:0;}
.meet-title{font-size:14px;font-weight:700;color:var(--text);}
.meet-sub{font-size:11.5px;color:var(--text3);margin-top:3px;}
.meet-chips{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap;align-items:center;}
.meet-chip{font-size:10.5px;padding:2px 8px;border-radius:20px;background:var(--card2);color:var(--text2);font-weight:500;border:1px solid var(--border);}
.meet-link{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;background:rgba(168,85,247,.12);border:1px solid rgba(168,85,247,.3);color:#c084fc;font-size:11px;font-weight:600;cursor:pointer;font-family:'Outfit',monospace;}
.meet-link:hover{background:rgba(168,85,247,.2);}
.meet-actions{display:flex;gap:6px;flex-shrink:0;}

.syl-strip{display:flex;align-items:center;gap:14px;padding:13px 16px;background:var(--surface);border:1px solid var(--border);border-radius:11px;margin-bottom:8px;transition:all .15s;}
.syl-strip:hover{border-color:var(--border2);}
.syl-icon{width:42px;height:42px;border-radius:10px;background:rgba(168,85,247,.15);color:var(--accent2);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.syl-body{flex:1;min-width:0;}
.syl-title{font-size:13.5px;font-weight:700;color:var(--text);}
.syl-meta{font-size:11.5px;color:var(--text3);margin-top:3px;display:flex;gap:9px;flex-wrap:wrap;align-items:center;}

.toast{position:fixed;bottom:22px;right:22px;background:var(--surface);border:1px solid var(--border2);border-left:3px solid var(--su);border-radius:10px;padding:11px 18px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:500;color:var(--text);box-shadow:var(--shadow2);z-index:3000;transform:translateX(160%);transition:transform .32s cubic-bezier(.34,1.56,.64,1);}
.toast.show{transform:translateX(0);}

.empty-state{text-align:center;padding:36px 20px;color:var(--text3);}
.empty-state .ei{font-size:38px;margin-bottom:10px;}
.empty-state .et{font-size:14px;font-weight:600;color:var(--text2);}
.empty-state .es{font-size:12px;margin-top:4px;}

.sub-tl{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:10px;padding:11px 14px;display:flex;align-items:center;gap:10px;margin-top:10px;}
.sub-tl-ico{font-size:18px;}
.sub-tl-body{flex:1;font-size:12px;color:#fcd34d;}
.sub-tl-body strong{color:#fbbf24;}

/* Student LMS */
.lms-shell{display:grid;grid-template-columns:280px 1fr;gap:16px;margin-bottom:16px;}
.lms-side{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.lms-banner{height:60px;background:linear-gradient(135deg,var(--accent2),#6366f1,var(--c-da));}
.lms-av-wrap{padding:0 16px;margin-top:-30px;}
.lms-av{width:64px;height:64px;border-radius:14px;background:linear-gradient(135deg,var(--accent2),#c084fc);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:24px;color:#fff;border:3px solid var(--surface);}
.lms-name{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--text);padding:10px 16px 2px;}
.lms-id{font-size:11.5px;color:var(--text3);padding:0 16px;display:flex;gap:6px;align-items:center;}
.lms-status{display:inline-block;margin:8px 16px;padding:3px 10px;border-radius:99px;background:rgba(16,185,129,.15);color:#34d399;border:1px solid rgba(16,185,129,.3);font-size:10.5px;font-weight:700;}
.lms-info{padding:14px 16px;border-top:1px solid var(--border);}
.lms-irow{display:flex;align-items:center;justify-content:space-between;padding:6px 0;font-size:12px;}
.lms-irow .l{color:var(--text2);display:flex;align-items:center;gap:6px;}
.lms-irow .v{font-weight:600;color:var(--text);}
.lms-actions{padding:14px 16px;border-top:1px solid var(--border);}
.lms-main{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;}
.lms-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.lms-tabs{display:flex;gap:3px;background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:3px;margin-bottom:14px;}
.lms-tab{flex:1;padding:9px 12px;border-radius:7px;border:none;background:transparent;cursor:pointer;font-family:'Outfit',sans-serif;font-size:12.5px;font-weight:600;color:var(--mu);transition:all .15s;text-align:center;}
.lms-tab.on{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;}
.lms-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;}
.lms-stat{background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:12px;text-align:center;}
.lms-stat-l{font-size:11px;color:var(--text3);margin-bottom:5px;}
.lms-stat-v{font-family:'Playfair Display',serif;font-size:22px;font-weight:800;}
.lms-bar{height:8px;background:var(--border);border-radius:99px;overflow:hidden;margin-bottom:18px;}
.lms-bar-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:99px;}
.lms-modlist{}
.lms-mod{display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:1px solid var(--border);}
.lms-mod:last-child{border-bottom:none;}
.lms-mod-ico{width:24px;height:24px;border-radius:6px;background:rgba(34,211,238,.15);color:var(--a);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;}
.lms-mod-t{flex:1;font-size:13px;color:var(--text);font-weight:500;}
.lms-mod-s{font-size:11.5px;font-weight:700;}
.lms-grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.lms-box{background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:14px;}
.lms-box-t{font-size:13px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:6px;margin-bottom:10px;}

/* LMS Section header inside tab body */
.lms-sec-h{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;margin-bottom:12px;}

/* LMS Attendance Calendar */
.lms-cal-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px;}
.lms-cal-nav{display:flex;align-items:center;gap:10px;}
.lms-cal-mlabel{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);min-width:140px;text-align:center;}
.lms-cal-btn{width:28px;height:28px;border-radius:7px;border:1px solid var(--border);background:var(--card2);color:var(--text);cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;}
.lms-cal-btn:hover{border-color:var(--accent);}
.lms-cal-leg{display:flex;gap:12px;font-size:11px;flex-wrap:wrap;}
.lms-cal-lg{display:flex;align-items:center;gap:5px;color:var(--text2);}
.lms-cal-lgd{width:11px;height:11px;border-radius:3px;}
.lms-cal{background:var(--card2);border:1px solid var(--border);border-radius:12px;padding:14px;}
.lms-cal-dh{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:6px;}
.lms-cal-dh > div{text-align:center;font-size:10.5px;font-weight:700;color:var(--text3);padding:5px 0;text-transform:uppercase;letter-spacing:.05em;}
.lms-cal-days{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;}
.lms-cal-day{aspect-ratio:1;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;border:1px solid var(--border);background:var(--surface);color:var(--text2);position:relative;}
.lms-cal-day.p{background:rgba(16,185,129,.15);border-color:rgba(16,185,129,.35);color:#34d399;}
.lms-cal-day.a{background:rgba(239,68,68,.12);border-color:rgba(239,68,68,.35);color:#fca5a5;}
.lms-cal-day.l{background:rgba(245,158,11,.13);border-color:rgba(245,158,11,.35);color:#fbbf24;}
.lms-cal-day.h{background:transparent;border-color:transparent;color:transparent;}
.lms-cal-day.tdy{outline:2px solid var(--accent);outline-offset:-2px;}
.lms-cal-mk{font-size:9px;font-weight:700;margin-top:1px;letter-spacing:.03em;}
.lms-cal-sum{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:14px;}
.lms-cal-sumc{background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:11px;text-align:center;}
.lms-cal-sumc-v{font-family:'Playfair Display',serif;font-size:22px;font-weight:800;}
.lms-cal-sumc-l{font-size:10.5px;color:var(--text3);margin-top:2px;}

/* LMS Assignments list */
.lms-as-list{display:flex;flex-direction:column;gap:11px;}
.lms-as-row{background:var(--card2);border:1px solid var(--border);border-left:4px solid #34d399;border-radius:11px;padding:13px 16px;}
.lms-as-row.warn{border-left-color:#fbbf24;}
.lms-as-row.bad{border-left-color:#ef4444;}
.lms-as-row.pend{border-left-color:#f59e0b;}
.lms-as-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:9px;gap:14px;}
.lms-as-info{flex:1;min-width:0;}
.lms-as-t{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:#34d399;margin-bottom:2px;}
.lms-as-row.warn .lms-as-t{color:#fbbf24;}
.lms-as-row.bad .lms-as-t{color:#ef4444;}
.lms-as-row.pend .lms-as-t{color:var(--text);}
.lms-as-m{font-size:11.5px;color:var(--text3);}
.lms-as-sw{text-align:right;}
.lms-as-score{font-family:'Playfair Display',serif;font-size:30px;font-weight:800;color:#34d399;line-height:1;}
.lms-as-row.warn .lms-as-score{color:#fbbf24;}
.lms-as-row.bad .lms-as-score{color:#ef4444;}
.lms-as-score .o{font-size:13px;color:var(--text3);font-weight:600;}
.lms-as-pct{font-size:11px;font-weight:700;margin-top:3px;color:#34d399;}
.lms-as-row.warn .lms-as-pct{color:#fbbf24;}
.lms-as-row.bad .lms-as-pct{color:#ef4444;}
.lms-as-bar{height:6px;background:var(--border);border-radius:99px;overflow:hidden;margin-bottom:10px;}
.lms-as-bar-fl{height:100%;background:linear-gradient(90deg,#10b981,#34d399);border-radius:99px;}
.lms-as-bar-fl.warn{background:linear-gradient(90deg,#f59e0b,#fbbf24);}
.lms-as-bar-fl.bad{background:linear-gradient(90deg,#dc2626,#ef4444);}
.lms-as-fb{background:rgba(79,142,255,.08);border:1px solid rgba(79,142,255,.18);border-radius:8px;padding:7px 12px;font-size:12px;color:var(--text2);display:flex;align-items:flex-start;gap:8px;}
.lms-as-pendtag{font-size:12px;font-weight:700;color:#f59e0b;display:inline-flex;align-items:center;gap:5px;}

/* LMS Mini Projects */
.lms-mp-list{display:flex;flex-direction:column;gap:11px;}
.lms-mp-row{background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.22);border-radius:12px;padding:13px 16px;}
.lms-mp-row.bad{background:rgba(239,68,68,.06);border-color:rgba(239,68,68,.25);}
.lms-mp-row.pend{background:var(--card2);border-color:var(--border);}
.lms-mp-head{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:9px;}
.lms-mp-info{flex:1;min-width:0;}
.lms-mp-t{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:#34d399;margin-bottom:2px;}
.lms-mp-row.bad .lms-mp-t{color:#ef4444;}
.lms-mp-row.pend .lms-mp-t{color:var(--text);}
.lms-mp-m{font-size:11.5px;color:var(--text3);}
.lms-mp-sw{text-align:right;}
.lms-mp-pct{font-family:'Playfair Display',serif;font-size:28px;font-weight:800;color:#34d399;line-height:1;}
.lms-mp-row.bad .lms-mp-pct{color:#ef4444;}
.lms-mp-pct .u{font-size:13px;color:var(--text3);font-weight:600;}
.lms-mp-status{font-size:11px;font-weight:700;margin-top:3px;color:#34d399;}
.lms-mp-row.bad .lms-mp-status{color:#ef4444;}
.lms-mp-bar{height:6px;background:rgba(255,255,255,.06);border-radius:99px;overflow:hidden;margin-bottom:10px;}
.lms-mp-bar-fl{height:100%;background:linear-gradient(90deg,#10b981,#34d399);border-radius:99px;}
.lms-mp-row.bad .lms-mp-bar-fl{background:linear-gradient(90deg,#dc2626,#ef4444);}
.lms-mp-fb{background:rgba(79,142,255,.08);border:1px solid rgba(79,142,255,.18);border-radius:8px;padding:7px 12px;font-size:12px;color:var(--text2);display:flex;align-items:flex-start;gap:8px;}

/* LMS Main Project Reviews */
.lms-main-hero{background:var(--card2);border:1px solid var(--border);border-radius:12px;padding:16px 18px;}
.lms-main-th{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:10px;}
.lms-main-t{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;}
.lms-main-pill{padding:5px 13px;border-radius:99px;background:rgba(79,142,255,.15);color:var(--accent);border:1px solid rgba(79,142,255,.3);font-size:11.5px;font-weight:700;}
.lms-rev{margin-bottom:16px;}
.lms-rev:last-child{margin-bottom:0;}
.lms-rev-h{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;}
.lms-rev-l{font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:var(--text);}
.lms-rev-v{font-family:'Playfair Display',serif;font-size:16px;font-weight:800;color:#34d399;}
.lms-rev-v.warn{color:#fbbf24;}
.lms-rev-v.pend{color:var(--text3);}
.lms-rev-bar{height:18px;background:var(--border);border-radius:99px;overflow:hidden;position:relative;margin-bottom:6px;}
.lms-rev-bar-fl{height:100%;background:linear-gradient(90deg,#10b981,#34d399);border-radius:99px;display:flex;align-items:center;justify-content:flex-end;padding-right:9px;font-size:10.5px;font-weight:700;color:#fff;min-width:36px;}
.lms-rev-bar-fl.warn{background:linear-gradient(90deg,#f59e0b,#fbbf24);}
.lms-rev-note{font-size:11.5px;color:var(--text3);}

.sd-grid{display:grid;grid-template-columns:300px 1fr;gap:16px;}
.sd-side{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;text-align:center;}
.sd-av{width:80px;height:80px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:30px;color:#fff;margin:0 auto 12px;}
.sd-name{font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:var(--text);}
.sd-role{font-size:12px;color:var(--text3);margin-top:3px;}
.sd-stats{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:14px 0;padding:14px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);}
.sd-st{text-align:center;}
.sd-st-v{font-family:'Playfair Display',serif;font-size:22px;font-weight:800;}
.sd-st-l{font-size:10.5px;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-top:3px;}
.sd-irow{display:flex;align-items:center;justify-content:space-between;padding:7px 0;font-size:12px;}
.sd-irow .l{color:var(--text2);}
.sd-irow .v{font-weight:600;color:var(--text);}
.sd-main{background:transparent;}
.sd-sec{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;margin-bottom:14px;}
.sd-sec-h{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}
.sd-batch{display:flex;align-items:center;gap:12px;padding:10px;background:var(--card2);border:1px solid var(--border);border-radius:10px;margin-bottom:8px;cursor:pointer;transition:all .15s;}
.sd-batch:hover{border-color:var(--border2);}
.sd-batch-acc{width:4px;height:30px;border-radius:99px;flex-shrink:0;}
.sd-batch-info{flex:1;min-width:0;}
.sd-batch-c{font-size:12.5px;font-weight:700;color:var(--text);}
.sd-batch-m{font-size:10.5px;color:var(--text3);margin-top:1px;}
.sd-batch-prog{display:flex;align-items:center;gap:8px;min-width:120px;}

.sec-tabs{display:flex;background:var(--card2);border:1px solid var(--border);border-radius:11px;padding:4px;margin-bottom:18px;gap:4px;}
.sec-tab{flex:1;padding:11px 14px;border-radius:8px;border:none;background:transparent;cursor:pointer;font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:var(--text2);transition:all .15s;text-align:center;display:inline-flex;align-items:center;justify-content:center;gap:7px;}
.sec-tab:hover{color:var(--text);}
.sec-tab.on{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;box-shadow:0 4px 14px rgba(79,142,255,.3);}

/* ============ COURSES + BATCH CREATION ============ */
.dim{color:var(--text3);}

/* Track chips */
.track-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px;}
.track-chip{display:flex;align-items:center;gap:12px;padding:13px 16px;border-radius:11px;background:var(--card2);border:1px solid var(--border);cursor:pointer;transition:all .18s;user-select:none;}
.track-chip:hover{background:rgba(79,142,255,.06);border-color:var(--border2);}
.track-chip.track-active{background:linear-gradient(135deg,rgba(79,142,255,.12),rgba(168,85,247,.10));border-color:var(--accent);box-shadow:0 4px 16px rgba(79,142,255,.18);}
.track-ico{font-size:22px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;background:rgba(79,142,255,.12);border-radius:10px;flex-shrink:0;}
.track-chip.track-active .track-ico{background:rgba(79,142,255,.22);}
.track-meta{display:flex;flex-direction:column;gap:2px;flex:1;min-width:0;}
.track-name{font-weight:600;font-size:14px;color:var(--text);}
.track-sub{font-size:11px;color:var(--text3);}
.track-chip.track-active .track-name{color:var(--accent);}

/* Approval chip + notice */
.approval-chip{display:inline-flex;align-items:center;gap:6px;padding:5px 11px;border-radius:8px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.3);font-size:11px;color:var(--wa);font-weight:600;}
.approval-notice{padding:12px 14px;border-radius:10px;background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.22);font-size:12px;color:var(--text2);margin:16px 0 6px;line-height:1.55;}
.approval-mini{margin-top:10px;padding:7px 10px;border-radius:8px;background:rgba(245,158,11,.08);border:1px dashed rgba(245,158,11,.3);font-size:11px;color:var(--wa);text-align:center;}

/* Status pills */
.status-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;}
.status-pending{background:rgba(245,158,11,.12);color:var(--wa);border:1px solid rgba(245,158,11,.28);}
.status-done{background:rgba(16,185,129,.12);color:var(--su);border:1px solid rgba(16,185,129,.28);}
.status-live{background:rgba(34,211,238,.12);color:var(--a);border:1px solid rgba(34,211,238,.28);}
.status-upcoming{background:rgba(168,85,247,.14);color:#c084fc;border:1px solid rgba(168,85,247,.28);}

/* Autofill strip */
.autofill-strip{display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-radius:9px;background:rgba(34,211,238,.06);border:1px dashed rgba(34,211,238,.3);margin-bottom:14px;font-size:12px;color:var(--a);font-weight:600;}

/* Modal width */
.modal-wide{max-width:760px;}

/* Form section heading */
.form-section-head{font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin:18px 0 10px;padding-top:14px;border-top:1px solid var(--border);display:flex;align-items:center;gap:6px;}
.form-section-head:first-of-type{padding-top:8px;border-top:0;}
.form-subhint{font-size:10px;color:var(--text3);font-weight:500;text-transform:none;letter-spacing:.02em;margin-left:8px;}

/* File dropzone */
.fdrop{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;padding:20px;border-radius:11px;background:var(--card2);border:1px dashed var(--border2);cursor:pointer;text-align:center;transition:all .15s;}
.fdrop:hover{background:rgba(79,142,255,.05);border-color:var(--accent);}
.fdrop input[type="file"]{display:none;}
.fdrop-ic{font-size:24px;}
.fdrop-t{font-size:13px;color:var(--text);font-weight:600;}
.fdrop-s{font-size:11px;color:var(--text3);}
.fdrop-sm{padding:13px 16px;}
.fdrop-sm .fdrop-t{font-size:12px;}

/* Slot cards (in course modal) */
.slot-card{padding:12px;border-radius:10px;background:var(--card2);border:1px solid var(--border);margin-bottom:10px;}
.slot-head{display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.slot-num{display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;background:rgba(79,142,255,.18);color:var(--accent);font-weight:700;font-size:11px;}
.slot-label{font-size:12px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.05em;}

/* Syllabus course cards */
.syl-cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(360px,1fr));gap:12px;}
.syl-c-card{padding:14px 16px;border-radius:12px;background:var(--surface2);border:1px solid var(--border);transition:all .18s;}
.syl-c-card:hover{border-color:var(--border2);}
.syl-c-head{display:flex;align-items:center;gap:11px;margin-bottom:12px;}
.syl-c-ico{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
.syl-c-name{font-family:'Playfair Display',serif;font-weight:700;font-size:15px;color:var(--text);}
.syl-c-sub{font-size:11px;color:var(--text3);margin-top:2px;font-family:'Outfit',monospace;}
.syl-c-file{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:9px;background:rgba(79,142,255,.04);border:1px solid var(--border);margin-bottom:10px;}
.sfi{font-size:18px;}
.sfi-name{font-weight:600;font-size:12px;color:var(--text);}
.sfi-sub{font-size:10px;color:var(--text3);margin-top:2px;}
.syl-c-foot{display:flex;flex-wrap:wrap;gap:5px;padding-top:10px;border-top:1px solid var(--border);font-size:11px;color:var(--text2);align-items:center;}

/* Projects course group */
.proj-cg{margin-bottom:18px;}
.proj-cg-head{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px 10px 0 0;background:var(--card2);border:1px solid var(--border);border-bottom:0;}
.proj-cg-ico{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:7px;font-size:14px;}
.proj-cg-name{font-weight:700;font-size:13px;color:var(--text);}
.proj-cg-sub{font-size:10px;color:var(--text3);margin-top:1px;font-family:'Outfit',monospace;}

/* Scheduled rows */
.sched-row{display:flex;align-items:flex-start;gap:14px;padding:12px 14px;background:var(--surface2);border:1px solid var(--border);border-top:0;}
.sched-row:last-child{border-radius:0 0 10px 10px;}
.sched-num{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;font-weight:700;font-size:11px;flex-shrink:0;}
.sched-title{font-weight:600;font-size:13px;color:var(--text);}
.sched-sub{font-size:11px;color:var(--text3);margin-top:3px;}

/* Syllabus view modal */
.sv-stat-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:14px;}
.sv-stat{padding:14px 12px;border-radius:10px;background:rgba(79,142,255,.07);border:1px solid rgba(79,142,255,.18);text-align:center;}
.sv-stat-v{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--text);line-height:1;}
.sv-stat-l{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-top:6px;}
.sv-callout{padding:12px 14px;border-radius:10px;background:linear-gradient(135deg,rgba(79,142,255,.10),rgba(168,85,247,.10));border:1px solid rgba(79,142,255,.22);font-size:13px;color:var(--text);line-height:1.6;margin-bottom:14px;}
.syl-modules{display:flex;flex-direction:column;gap:8px;margin-bottom:14px;}
.sv-module{display:flex;align-items:center;gap:14px;padding:12px 14px;border-radius:10px;background:var(--card2);border:1px solid var(--border);}
.sv-mod-num{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;background:rgba(34,211,238,.16);color:var(--a);flex-shrink:0;}
.sv-mod-title{font-weight:600;font-size:13px;color:var(--text);margin-bottom:4px;}
.sv-mod-meta{font-size:11px;color:var(--text3);}

</style>
</head>
<body>
<div class="shell">

  @include('partials.sidebar', ['role' => 'tech_head'])

  <div class="main">
  <div class="topbar">
    <div class="tb-title" id="tb-title">Tech Head <em>Dashboard</em></div>
    <div class="search-box">
      <span>🔍</span>
      <input id="search-inp" type="text" placeholder="Search batches, students, staff…">
    </div>
    <button class="btn btn-pri btn-sm" id="tb-btn" onclick="topBtnClick()">⚡ Quick Action</button>
  </div>

  <div class="content">

    <!-- ============ DASHBOARD ============ -->
    
        @yield('content')
    