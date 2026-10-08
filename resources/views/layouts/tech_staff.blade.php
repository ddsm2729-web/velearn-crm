<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Staff Portal — Aceledu</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#f4f5f8;--surface:#fff;--card2:#f8f9fc;
  --border:#e4e7f0;--border2:#d0d6e8;
  --accent:#2563eb;--accent2:#7c3aed;--accent3:#059669;
  --text:#111827;--text2:#4b5563;--text3:#9ca3af;
  --p:#22346b;--a:#24b8ec;--al:#e6f7fd;--pl:#eef0f7;
  --mu:#6b7280;--bd:rgba(34,52,107,0.13);
  --su:#16a34a;--sub:#dcfce7;--wa:#d97706;--wab:#fef3c7;
  --da:#dc2626;--dab:#fee2e2;--r:12px;--rs:8px;
  --shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
  --shadow2:0 4px 12px rgba(0,0,0,0.1),0 16px 40px rgba(0,0,0,0.08);
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px;}
.shell{display:flex;height:100vh;overflow:hidden;}
.sidebar{width:240px;min-width:240px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;box-shadow:2px 0 8px rgba(0,0,0,0.04);}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.content{flex:1;overflow-y:auto;padding:24px 28px;}
.logo-wrap{padding:20px 20px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.logo-mark{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:17px;color:#fff;flex-shrink:0;}
.logo-name{font-family:'Playfair Display',serif;font-weight:700;font-size:16px;color:var(--text);}
.logo-role{font-size:10px;color:var(--text3);font-weight:500;margin-top:1px;}
.nav-sec{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.12em;padding:14px 20px 5px;}
.nitem{display:flex;align-items:center;gap:11px;padding:10px 20px;cursor:pointer;transition:all .18s;color:var(--text2);font-size:13.5px;font-weight:500;position:relative;user-select:none;}
.nitem:hover{background:#f0f4ff;color:var(--accent);}
.nitem.on{background:#eff4ff;color:var(--accent);font-weight:600;}
.nitem.on::before{content:'';position:absolute;left:0;top:7px;bottom:7px;width:3px;border-radius:0 3px 3px 0;background:var(--accent);}
.nitem .nic{font-size:15px;flex-shrink:0;width:20px;text-align:center;}
.nitem .nlabel{flex:1;}
.nbadge{padding:1px 7px;border-radius:20px;font-size:10px;font-weight:700;color:#fff;}
.nb-r{background:var(--da);}
.nb-g{background:var(--su);}
.nb-a{background:var(--wa);}
.nb-b{background:var(--accent);}
.sidebar-foot{margin-top:auto;border-top:1px solid var(--border);padding:14px;}
.user-pill{display:flex;align-items:center;gap:9px;background:var(--card2);border:1px solid var(--border);border-radius:10px;padding:9px 11px;cursor:pointer;}
.user-av{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:12px;color:#fff;flex-shrink:0;}
.user-name{font-size:12.5px;font-weight:600;color:var(--text);}
.user-sub{font-size:10.5px;color:var(--text3);}
.online{width:7px;height:7px;border-radius:50%;background:var(--su);box-shadow:0 0 6px rgba(22,163,74,.5);flex-shrink:0;}
.topbar{display:flex;align-items:center;gap:12px;padding:13px 28px;background:var(--surface);border-bottom:1px solid var(--border);box-shadow:var(--shadow);flex-shrink:0;}
.tb-title{font-family:'Playfair Display',serif;font-size:19px;font-weight:700;color:var(--text);flex:1;}
.tb-title em{color:var(--su);font-style:normal;}
.search-box{display:flex;align-items:center;gap:8px;background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:8px 13px;width:230px;}
.search-box input{border:none;background:transparent;outline:none;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);width:100%;}
.search-box input::placeholder{color:var(--text3);}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;border:none;border-radius:9px;cursor:pointer;font-family:'Outfit',sans-serif;font-weight:600;transition:all .18s;}
.btn:hover{transform:translateY(-1px);}
.btn-pri{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;padding:9px 18px;font-size:13px;}
.btn-pri:hover{opacity:.88;}
.btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--text2);padding:8px 16px;font-size:13px;}
.btn-ghost:hover{border-color:var(--accent);color:var(--accent);}
.btn-danger{background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;padding:9px 18px;font-size:13px;}
.btn-success{background:linear-gradient(135deg,#059669,#047857);color:#fff;padding:9px 18px;font-size:13px;}
.btn-sm{padding:7px 14px;font-size:12px;}
.btn-xs{padding:4px 11px;font-size:11px;border-radius:7px;}
.view{display:none;}
.view.active{display:block;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:14px;box-shadow:var(--shadow);}
.card-pad{padding:20px;}
.card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.card-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;}
.cbadge{background:#eff4ff;color:var(--accent);border:1px solid #bfdbfe;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:600;}
/* STATS */
.stats4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
.stats5{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:16px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:var(--shadow);cursor:pointer;transition:all .2s;overflow:hidden;position:relative;}
.stat-card:hover{transform:translateY(-2px);box-shadow:var(--shadow2);}
.stat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.sc-fs::after{background:linear-gradient(90deg,#4f46e5,transparent);}
.sc-ux::after{background:linear-gradient(90deg,#be185d,transparent);}
.sc-dm::after{background:linear-gradient(90deg,#b45309,transparent);}
.sc-da::after{background:linear-gradient(90deg,#059669,transparent);}
.sc-bl::after{background:linear-gradient(90deg,var(--accent),transparent);}
.sc-pu::after{background:linear-gradient(90deg,var(--accent2),transparent);}
.stat-ico{font-size:20px;margin-bottom:6px;}
.stat-lbl{font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;}
.stat-num{font-family:'Playfair Display',serif;font-size:28px;font-weight:700;color:var(--text);line-height:1;}
.stat-sub{font-size:11px;color:var(--text3);margin-top:4px;}
/* COURSE TABS */
.ctabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:16px;}
.ctab{padding:7px 16px;border-radius:9px;background:var(--surface);border:1px solid var(--border);color:var(--text2);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .18s;user-select:none;}
.ctab:hover{border-color:var(--border2);}
.ctab.on{background:#eff4ff;border-color:#bfdbfe;color:var(--accent);}
.ctab-fs.on{background:#eef2ff;border-color:#a5b4fc;color:#4f46e5;}
.ctab-ux.on{background:#fce7f3;border-color:#f9a8d4;color:#be185d;}
.ctab-dm.on{background:#fef3c7;border-color:#fcd34d;color:#b45309;}
.ctab-da.on{background:#d1fae5;border-color:#6ee7b7;color:#059669;}
/* BATCH GRID */
.batch-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
.batch-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;cursor:pointer;transition:all .22s;position:relative;overflow:hidden;box-shadow:var(--shadow);}
.batch-card:hover{transform:translateY(-3px);box-shadow:var(--shadow2);border-color:var(--border2);}
.batch-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
.bc-fs::before{background:linear-gradient(90deg,#4f46e5,#818cf8);}
.bc-ux::before{background:linear-gradient(90deg,#be185d,#f472b6);}
.bc-dm::before{background:linear-gradient(90deg,#b45309,#fcd34d);}
.bc-da::before{background:linear-gradient(90deg,#059669,#34d399);}
.batch-code{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;}
.batch-name{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;margin-bottom:4px;}
.batch-type{display:inline-block;padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600;margin-bottom:12px;}
.bt-fs{background:#eef2ff;color:#4f46e5;}
.bt-ux{background:#fce7f3;color:#be185d;}
.bt-dm{background:#fef3c7;color:#b45309;}
.bt-da{background:#d1fae5;color:#059669;}
.nc-fs{color:#4f46e5;}
.nc-ux{color:#be185d;}
.nc-dm{color:#b45309;}
.nc-da{color:#059669;}
.prog-wrap{height:5px;background:var(--border);border-radius:99px;overflow:hidden;margin:10px 0 5px;}
.prog-fill{height:100%;border-radius:99px;}
.pf-fs{background:linear-gradient(90deg,#4f46e5,#818cf8);}
.pf-ux{background:linear-gradient(90deg,#be185d,#f472b6);}
.pf-dm{background:linear-gradient(90deg,#b45309,#fcd34d);}
.pf-da{background:linear-gradient(90deg,#059669,#34d399);}
.batch-footer{display:flex;justify-content:space-between;font-size:10px;color:var(--text3);margin-bottom:12px;}
.batch-stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;padding-top:12px;border-top:1px solid var(--border);}
.bsr-item{text-align:center;}
.bsr-val{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--text);}
.bsr-lbl{font-size:10px;color:var(--text3);}
/* TABLE */
.data-table{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow);}
.dt-head{display:grid;padding:10px 18px;background:var(--card2);border-bottom:1px solid var(--border);font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;user-select:none;}
.dt-row{display:grid;padding:12px 18px;border-bottom:1px solid var(--border);align-items:center;cursor:pointer;transition:background .15s;}
.dt-row:hover{background:#f8faff;}
.dt-row:last-child{border-bottom:none;}
.cols-stu{grid-template-columns:34px 1.5fr 130px 120px 100px 90px 90px;}
.sav{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:11px;color:#fff;}
.sname{font-weight:600;font-size:13px;color:var(--text);}
.semail{font-size:11px;color:var(--text3);}
.pill{display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600;}
.p-active{background:var(--sub);color:#166534;}
.p-inactive{background:var(--dab);color:#991b1b;}
.p-pending{background:var(--wab);color:#92400e;}
.p-fs{background:#eef2ff;color:#4f46e5;}
.p-ux{background:#fce7f3;color:#be185d;}
.p-dm{background:#fef3c7;color:#b45309;}
.p-da{background:#d1fae5;color:#059669;}
.att-inline{display:flex;align-items:center;gap:6px;}
.att-bar{width:48px;height:5px;background:var(--border);border-radius:99px;overflow:hidden;}
.att-fill{height:100%;border-radius:99px;}
/* BATCH GROUP HEADER in students view */
.batch-group-hdr{padding:8px 18px;background:var(--card2);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;font-size:12px;font-weight:700;color:var(--text2);}
.batch-group-pill{padding:2px 9px;border-radius:20px;font-size:10px;font-weight:700;}
/* STUDENT DETAIL */
.detail-grid{display:grid;grid-template-columns:290px 1fr;gap:16px;}
.back-link{display:inline-flex;align-items:center;gap:6px;color:var(--text2);font-size:13px;font-weight:500;cursor:pointer;margin-bottom:16px;padding:6px 12px;border-radius:8px;transition:all .15s;}
.back-link:hover{background:var(--card2);color:var(--accent);}
.hero-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow);margin-bottom:12px;}
.hero-banner{height:72px;background:linear-gradient(135deg,#1d4ed8,#7c3aed 50%,#059669);}
.hero-body{padding:0 18px 18px;}
.hero-av{width:62px;height:62px;border-radius:50%;border:4px solid #fff;box-shadow:0 4px 14px rgba(37,99,235,.2);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:22px;color:#fff;margin-top:-31px;}
.hero-name{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;margin-top:8px;}
.hero-id{font-size:11px;color:var(--text3);margin-top:2px;}
.info-list{margin-top:14px;}
.info-row{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:12.5px;}
.info-row:last-child{border-bottom:none;}
.info-lbl{color:var(--text2);}
.info-val{color:var(--text);font-weight:600;text-align:right;}
.lms-tabs{display:flex;gap:3px;background:var(--pl);border-radius:var(--rs);padding:3px;margin-bottom:14px;}
.lms-tab{flex:1;padding:7px 4px;border-radius:6px;border:none;background:transparent;cursor:pointer;font-family:'Outfit',sans-serif;font-size:11.5px;font-weight:500;color:var(--mu);transition:all .15s;text-align:center;}
.lms-tab.on{background:var(--p);color:#fff;font-weight:600;}
.lms-panel{display:none;}
.lms-panel.on{display:block;}
.lms-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px;}
.lms-sc{background:var(--surface);border:.5px solid var(--bd);border-radius:var(--r);padding:12px;text-align:center;}
.lms-sc-l{font-size:10px;color:var(--mu);margin-bottom:3px;}
.lms-sc-v{font-family:'Playfair Display',serif;font-size:22px;font-weight:700;}
.mod-item{display:flex;align-items:center;gap:9px;padding:7px 0;border-bottom:.5px solid var(--bd);font-size:12px;}
.mod-item:last-child{border-bottom:none;}
.mod-dot{width:18px;height:18px;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;flex-shrink:0;}
.md-done{background:var(--a);color:#fff;}
.md-todo{background:#e9ecf5;color:var(--mu);}
.cal-wrap{background:var(--surface);border:.5px solid var(--bd);border-radius:var(--r);padding:14px;}
.cal-hdr{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;margin-bottom:3px;}
.cal-hdr span{font-size:9px;color:var(--mu);text-align:center;font-weight:600;}
.cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}
.cal-day{aspect-ratio:1;border-radius:3px;display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:600;cursor:pointer;color:#fff;transition:opacity .15s;}
.cal-day:hover{opacity:.8;}
.cd-p{background:#16a34a;}
.cd-a{background:#dc2626;}
.cd-n{background:#e9ecf5;color:#9ca3af;}
.cal-legend{display:flex;gap:12px;margin-top:10px;padding-top:9px;border-top:.5px solid var(--bd);align-items:center;flex-wrap:wrap;}
.leg-item{display:flex;align-items:center;gap:5px;font-size:10.5px;font-weight:600;}
.leg-dot{width:10px;height:10px;border-radius:2px;}
.asgn-card{background:var(--surface);border:.5px solid var(--bd);border-radius:var(--r);padding:14px;margin-bottom:10px;border-left:3px solid transparent;}
.asgn-card.ac-graded{border-left-color:#16a34a;}
.asgn-card.ac-pending{border-left-color:var(--wa);}
.asgn-card.ac-overdue{border-left-color:var(--da);}
.asgn-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px;}
.asgn-title{font-size:13px;font-weight:700;color:var(--p);}
.asgn-meta{font-size:11px;color:var(--mu);margin-top:2px;}
.asgn-score{font-family:'Playfair Display',serif;font-size:26px;font-weight:800;line-height:1;text-align:right;}
.asgn-score span{font-size:12px;}
.asgn-bar{display:flex;align-items:center;gap:8px;}
.asgn-track{flex:1;height:6px;background:var(--border);border-radius:99px;overflow:hidden;}
.asgn-fill{height:100%;border-radius:99px;}
.feedback-box{background:var(--al);border-radius:var(--rs);padding:9px 12px;margin-top:10px;font-size:11.5px;color:#0c447c;line-height:1.7;}
.mini-card{border-radius:var(--rs);padding:14px;margin-bottom:10px;}
.mc-ok{background:#f0fdf4;border:1px solid #bbf7d0;}
.mc-fix{background:#fff5f5;border:1px solid #fecaca;}
.mc-wait{background:#fefce8;border:1px solid #fde68a;}
.main-proj{background:var(--surface);border:.5px solid var(--bd);border-radius:var(--r);padding:16px;}
.rev-row{margin-bottom:14px;}
.rev-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:5px;}
.rev-lbl{font-size:12px;font-weight:600;color:var(--p);}
.rev-pct{font-size:13px;font-weight:800;}
.rev-track{height:14px;background:#f3f4f6;border-radius:7px;overflow:hidden;}
.rev-fill{height:100%;border-radius:7px;display:flex;align-items:center;justify-content:flex-end;padding-right:6px;}
.rev-fill span{font-size:9px;color:#fff;font-weight:700;}
.rev-note{font-size:10px;color:var(--mu);margin-top:4px;}
/* SLOTS */
.slot-layout{display:grid;grid-template-columns:1fr 300px;gap:16px;}
.slot-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:var(--shadow);}
.slot-head{padding:14px 18px;border-bottom:1px solid var(--border);background:var(--card2);}
.slot-title{font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--text);}
.slot-sub{font-size:12px;color:var(--text3);margin-top:2px;}
.date-tabs{display:flex;border-bottom:1px solid var(--border);}
.dtab{flex:1;padding:9px 4px;text-align:center;font-size:12px;font-weight:600;color:var(--text2);cursor:pointer;border-bottom:2px solid transparent;transition:all .15s;user-select:none;}
.dtab:hover{color:var(--accent);}
.dtab.on{color:var(--accent);border-bottom-color:var(--accent);background:#f8faff;}
.slots-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px;}
.slot-box{border:1.5px solid var(--border);border-radius:var(--r);padding:12px 10px;cursor:pointer;background:var(--surface);transition:all .18s;}
.slot-box:hover{border-color:var(--a);background:var(--al);transform:translateY(-1px);}
.slot-box.sb-booked{border-color:#bfdbfe;background:#eff4ff;}
.slot-num{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:3px;}
.slot-time{font-size:13px;font-weight:700;color:var(--p);}
.slot-avail{font-size:11px;color:var(--su);font-weight:500;margin-top:3px;}
.slot-bname{font-size:11.5px;color:var(--accent);font-weight:600;margin-top:3px;}
.slot-bcourse{font-size:10px;color:var(--text3);}
.aside-panel{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px;box-shadow:var(--shadow);}
.aside-title{font-family:'Playfair Display',serif;font-size:14px;font-weight:700;color:var(--text);margin-bottom:12px;}
.asgnd-item{background:var(--card2);border:1px solid var(--border);border-radius:9px;padding:11px;margin-bottom:7px;cursor:pointer;transition:all .15s;}
.asgnd-item:hover{border-color:var(--border2);background:#f0f4ff;}
.asgnd-name{font-size:13px;font-weight:600;color:var(--text);}
.asgnd-meta{font-size:11px;color:var(--text3);margin:2px 0 6px;}
.time-tag{display:inline-flex;padding:2px 8px;background:var(--pl);color:var(--p);border-radius:6px;font-size:10px;font-weight:600;margin-right:4px;}
/* LINKS */
.links-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;}
.link-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;box-shadow:var(--shadow);}
.link-ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:12px;}
.link-title{font-family:'Playfair Display',serif;font-size:15px;font-weight:700;margin-bottom:4px;}
.link-desc{font-size:12px;color:var(--text3);margin-bottom:14px;}
.link-inp{width:100%;border:1px solid var(--border);border-radius:8px;padding:8px 11px;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);background:var(--card2);outline:none;margin-bottom:8px;}
.link-inp:focus{border-color:var(--accent);}
.link-history{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:var(--shadow);}
.lh-item{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);font-size:12.5px;}
.lh-item:last-child{border-bottom:none;}
.lh-tag{padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;flex-shrink:0;}
.lh-title{font-size:12px;font-weight:600;color:var(--text2);flex-shrink:0;min-width:80px;}
.lh-url{flex:1;color:var(--text2);font-family:monospace;font-size:11.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.lh-time{font-size:11px;color:var(--text3);flex-shrink:0;}
.copy-btn{flex-shrink:0;padding:4px 12px;border:1px solid var(--border2);border-radius:7px;background:transparent;color:var(--text2);font-size:11px;font-weight:600;cursor:pointer;transition:all .15s;}
.copy-btn:hover{background:var(--accent);color:#fff;border-color:var(--accent);}
/* MODALS */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .2s;}
.overlay.show{opacity:1;pointer-events:all;}
.modal{background:var(--surface);border-radius:16px;padding:24px;width:460px;max-height:88vh;overflow-y:auto;box-shadow:var(--shadow2);transform:scale(.96);transition:transform .2s;}
.overlay.show .modal{transform:scale(1);}
.modal-lg{width:560px;}
.modal-xl{width:640px;}
.mhead{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;}
.mtitle{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--text);}
.msub{font-size:12px;color:var(--text3);margin-top:3px;}
.mclose{background:var(--card2);border:1px solid var(--border);border-radius:8px;width:30px;height:30px;cursor:pointer;font-size:14px;color:var(--text2);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.fg{margin-bottom:14px;}
.flabel{display:block;font-size:11.5px;font-weight:700;color:var(--text2);margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;}
.finp{width:100%;border:1px solid var(--border);border-radius:8px;padding:9px 11px;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);background:var(--surface);outline:none;transition:border .15s;}
.finp:focus{border-color:var(--accent);}
.fsel{width:100%;border:1px solid var(--border);border-radius:8px;padding:9px 11px;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);background:var(--surface);outline:none;cursor:pointer;}
.fsel:focus{border-color:var(--accent);}
.fta{width:100%;border:1px solid var(--border);border-radius:8px;padding:9px 11px;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);background:var(--surface);outline:none;min-height:80px;resize:vertical;}
.fta:focus{border-color:var(--accent);}
.mfooter{display:flex;gap:10px;margin-top:18px;}
.rubric-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:.5px solid var(--border);}
.rubric-row:last-child{border-bottom:none;}
.rubric-inp{width:70px;border:1px solid var(--border);border-radius:7px;padding:5px 8px;font-family:'Outfit',sans-serif;font-size:13px;text-align:center;outline:none;}
.rubric-inp:focus{border-color:var(--accent);}
.tag-row{display:flex;flex-wrap:wrap;gap:6px;}
.tag-opt{padding:5px 12px;border-radius:20px;background:var(--card2);border:1px solid var(--border);font-size:11.5px;cursor:pointer;transition:all .15s;user-select:none;}
.tag-opt:hover{border-color:var(--accent);color:var(--accent);}
.tag-opt.sel{background:#eff4ff;border-color:var(--accent);color:var(--accent);font-weight:600;}
.gchip{display:inline-flex;padding:3px 10px;border-radius:20px;font-size:11.5px;font-weight:700;}
.g-a{background:#d1fae5;color:#065f46;}
.g-b{background:#dbeafe;color:#1e40af;}
.g-c{background:#fef3c7;color:#92400e;}
.g-f{background:#fee2e2;color:#991b1b;}
.g-n{background:var(--card2);color:var(--text3);}
/* TOAST */
.toast{position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(12px);background:var(--text);color:#fff;border-radius:10px;padding:10px 20px;font-size:13px;font-weight:500;display:flex;align-items:center;gap:8px;opacity:0;transition:all .25s;z-index:9999;pointer-events:none;}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
/* EMPTY */
.empty-state{text-align:center;padding:36px 20px;color:var(--text3);}
.empty-state .ei{font-size:38px;margin-bottom:10px;}
.empty-state .et{font-size:14px;font-weight:600;color:var(--text2);}
.empty-state .es{font-size:12px;margin-top:4px;}

/* ══ DASHBOARD SPECIFIC ══ */
.dash-filter-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.dash-filter-bar h2{font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--text);}
.filter-tabs{display:flex;gap:4px;background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:3px;}
.filter-tab{padding:6px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;color:var(--text2);transition:all .15s;user-select:none;}
.filter-tab.on{background:var(--accent);color:#fff;}
.dash-section-title{font-size:13px;font-weight:700;color:var(--text2);margin-bottom:10px;display:flex;align-items:center;gap:6px;}
.dash-two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;}
.session-strip{display:flex;align-items:center;gap:12px;padding:11px 14px;background:var(--card2);border:1px solid var(--border);border-radius:10px;margin-bottom:8px;}
.session-strip:last-child{margin-bottom:0;}
.ss-av{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;}
.ss-name{font-size:13px;font-weight:600;color:var(--text);}
.ss-meta{font-size:11px;color:var(--text3);margin-top:1px;}
.ss-status{margin-left:auto;flex-shrink:0;padding:3px 10px;border-radius:20px;font-size:10.5px;font-weight:700;}
.ss-demo-booked{background:#dbeafe;color:#1e40af;}
.ss-doubt-booked{background:#f5f3ff;color:#5b21b6;}
.ss-demo-done{background:var(--sub);color:#166534;}
.ss-doubt-done{background:var(--sub);color:#166534;}
.activity-strip{display:flex;align-items:center;gap:12px;padding:11px 14px;background:var(--card2);border:1px solid var(--border);border-radius:10px;margin-bottom:8px;}
.act-av{width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:11px;color:#fff;flex-shrink:0;}
.act-info{flex:1;min-width:0;}
.act-name{font-size:12.5px;font-weight:600;color:var(--text);}
.act-detail{font-size:11px;color:var(--text3);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.act-badge{flex-shrink:0;font-size:10px;padding:2px 8px;border-radius:20px;font-weight:600;}

/* ══ SUB-TABS (demo/doubt/assignment) ══ */
.subtab-bar{display:flex;gap:0;background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:18px;box-shadow:var(--shadow);}
.subtab{flex:1;padding:11px 8px;text-align:center;font-size:12.5px;font-weight:600;cursor:pointer;color:var(--text2);border-right:1px solid var(--border);transition:all .15s;user-select:none;}
.subtab:last-child{border-right:none;}
.subtab:hover{background:var(--card2);color:var(--accent);}
.subtab.on{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#fff;}
.subpanel{display:none;}
.subpanel.on{display:block;}

/* ASSIGNED SESSION STRIP (demo/doubt) */
.booked-strip{display:flex;align-items:center;gap:12px;padding:13px 16px;background:var(--surface);border:1px solid var(--border);border-radius:10px;margin-bottom:8px;box-shadow:var(--shadow);}
.bs-av{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:14px;color:#fff;flex-shrink:0;}
.bs-info{flex:1;min-width:0;}
.bs-name{font-size:13.5px;font-weight:700;color:var(--text);}
.bs-meta{font-size:11.5px;color:var(--text3);margin-top:2px;}
.bs-status{padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;flex-shrink:0;}
.bs-phone{font-size:12px;color:var(--text2);font-weight:500;flex-shrink:0;}

/* ASSIGNMENT SYSTEM */
.assign-header{display:flex;align-items:center;justify-content:flex-end;margin-bottom:16px;}
.upload-strip{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px 16px;margin-bottom:10px;box-shadow:var(--shadow);}
.us-title{font-size:13.5px;font-weight:700;color:var(--text);}
.us-meta{font-size:11.5px;color:var(--text3);margin-top:3px;display:flex;gap:12px;flex-wrap:wrap;}
.us-meta span{display:flex;align-items:center;gap:3px;}
.us-deadline{display:inline-flex;padding:2px 9px;border-radius:20px;font-size:10.5px;font-weight:600;background:var(--wab);color:#92400e;margin-left:auto;}

/* EVALUATION STRIP */
.eval-strip-wrap{border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:10px;box-shadow:var(--shadow);}
.eval-strip{display:grid;grid-template-columns:1.4fr 100px 100px 110px 80px 70px auto auto;gap:10px;padding:12px 16px;background:var(--surface);align-items:center;cursor:pointer;transition:background .15s;}
.eval-strip:hover{background:var(--card2);}
.eval-strip-expand{background:#f8faff;border-top:1px solid var(--border);padding:14px 16px;display:none;}
.eval-strip-expand.show{display:block;}
.eval-expand-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.eeg-item{text-align:center;padding:10px;background:var(--surface);border-radius:8px;border:1px solid var(--border);}
.eeg-val{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;}
.eeg-lbl{font-size:10px;color:var(--text3);margin-top:2px;}
.status-uploaded{background:#dbeafe;color:#1e40af;}
.status-reuploaded{background:#fef3c7;color:#92400e;}
.status-evaluated{background:var(--sub);color:#166534;}

/* GRADE CHIPS in eval */
.grade-chip{padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
</style>
</head>
<body>
<div class="shell">

<!-- ═══ SIDEBAR ═══ -->
  @include('partials.sidebar', ['role' => 'tech_staff'])

  <div class="main">
  <div class="topbar">
    <div class="tb-title" id="tb-title">Staff <em>Dashboard</em></div>
    <div class="search-box">
      <span>🔍</span>
      <input id="search-inp" type="text" placeholder="Search students, batches…" oninput="doSearch(this.value)">
    </div>
    <button class="btn btn-pri btn-sm" id="tb-btn" onclick="topBtnClick()" style="display:none">+ New Batch</button>
  </div>

  <div class="content">

    <!-- ══ VIEW: DASHBOARD ══ -->
    
        @yield('content')
    <script>
'use strict';

/* ══════════════════════════════════════════════
   DATA
══════════════════════════════════════════════ */

var COURSES = {
  fs:   { name:'Full Stack',        icon:'⚡', color:'#4f46e5', cls:'fs' },
  uiux: { name:'UI/UX Design',      icon:'🎨', color:'#be185d', cls:'ux' },
  dm:   { name:'Digital Marketing', icon:'📣', color:'#b45309', cls:'dm' },
  dac:  { name:'Data Science',      icon:'📊', color:'#059669', cls:'da' }
};

var BATCHES = [
  { code:'FS-2024-A',   course:'fs',   type:'Weekday',    students:8,  progress:65, start:'Jan 2024', end:'Jun 2024' },
  { code:'FS-2024-B',   course:'fs',   type:'Weekend',    students:6,  progress:40, start:'Mar 2024', end:'Aug 2024' },
  { code:'FS-2024-C',   course:'fs',   type:'Fast Track', students:4,  progress:80, start:'Apr 2024', end:'Jun 2024' },
  { code:'UIUX-2024-A', course:'uiux', type:'Weekday',    students:7,  progress:55, start:'Feb 2024', end:'Jul 2024' },
  { code:'UIUX-2024-B', course:'uiux', type:'Weekend',    students:5,  progress:30, start:'Apr 2024', end:'Sep 2024' },
  { code:'DM-2024-A',   course:'dm',   type:'Weekday',    students:6,  progress:70, start:'Jan 2024', end:'May 2024' },
  { code:'DM-2024-B',   course:'dm',   type:'Weekend',    students:4,  progress:25, start:'Apr 2024', end:'Aug 2024' },
  { code:'DA-2024-A',   course:'dac',  type:'Weekday',    students:8,  progress:50, start:'Feb 2024', end:'Jul 2024' }
];

var STUDENTS = [
  { id:'STU001', name:'Priya Sharma',    email:'priya@email.com',   phone:'98765 43210', course:'fs',   batch:'FS-2024-A',   joined:'12 Jan 2024', att:85, status:'active',   fee:'Paid'    },
  { id:'STU002', name:'Rahul Verma',     email:'rahul@email.com',   phone:'98765 43211', course:'fs',   batch:'FS-2024-A',   joined:'14 Jan 2024', att:60, status:'active',   fee:'Pending' },
  { id:'STU003', name:'Anjali Singh',    email:'anjali@email.com',  phone:'98765 43212', course:'fs',   batch:'FS-2024-B',   joined:'05 Mar 2024', att:90, status:'active',   fee:'Paid'    },
  { id:'STU004', name:'Karthik Raj',     email:'karthik@email.com', phone:'98765 43213', course:'uiux', batch:'UIUX-2024-A', joined:'10 Feb 2024', att:75, status:'active',   fee:'Paid'    },
  { id:'STU005', name:'Deepa Nair',      email:'deepa@email.com',   phone:'98765 43214', course:'uiux', batch:'UIUX-2024-A', joined:'12 Feb 2024', att:40, status:'inactive', fee:'Paid'    },
  { id:'STU006', name:'Mohammed Rizwan', email:'rizwan@email.com',  phone:'98765 43215', course:'dm',   batch:'DM-2024-A',   joined:'08 Jan 2024', att:92, status:'active',   fee:'Paid'    },
  { id:'STU007', name:'Sneha Patel',     email:'sneha@email.com',   phone:'98765 43216', course:'dm',   batch:'DM-2024-A',   joined:'09 Jan 2024', att:55, status:'pending',  fee:'Pending' },
  { id:'STU008', name:'Vikram Rao',      email:'vikram@email.com',  phone:'98765 43217', course:'dac',  batch:'DA-2024-A',   joined:'15 Feb 2024', att:88, status:'active',   fee:'Paid'    },
  { id:'STU009', name:'Lakshmi Devi',    email:'lakshmi@email.com', phone:'98765 43218', course:'dac',  batch:'DA-2024-A',   joined:'16 Feb 2024', att:72, status:'active',   fee:'Paid'    },
  { id:'STU010', name:'Arun Kumar',      email:'arun@email.com',    phone:'98765 43219', course:'fs',   batch:'FS-2024-C',   joined:'02 Apr 2024', att:95, status:'active',   fee:'Paid'    }
];

var BATCH_COURSE_INFO = {
  fs:   { ncls:'nc-fs', btcls:'bt-fs', bccls:'bc-fs', pfcls:'pf-fs', name:'Full Stack',        icon:'⚡' },
  uiux: { ncls:'nc-ux', btcls:'bt-ux', bccls:'bc-ux', pfcls:'pf-ux', name:'UI/UX Design',      icon:'🎨' },
  dm:   { ncls:'nc-dm', btcls:'bt-dm', bccls:'bc-dm', pfcls:'pf-dm', name:'Digital Marketing', icon:'📣' },
  dac:  { ncls:'nc-da', btcls:'bt-da', bccls:'bc-da', pfcls:'pf-da', name:'Data Science',      icon:'📊' }
};

/* Demo/Doubt preloaded data */
var SLOT_TIMES = ['11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM'];
var DATE_LABELS = ['07 May 2026','08 May 2026','09 May 2026','10 May 2026'];
var SLOT_DATES  = ['07 May 2026','08 May 2026','09 May 2026','10 May 2026'];

var slotStore = { demo:{}, doubt:{} };
var DEMO_PRELOAD = [
  { name:'Aisha Banu',  phone:'99887 76655', course:'Full Stack',     di:0, ti:0 },
  { name:'Naveen Raj',  phone:'99887 76644', course:'UI/UX Design',   di:0, ti:2 },
  { name:'Sanjana M',   phone:'99887 76633', course:'Data Science',   di:0, ti:7 }
];
var DOUBT_PRELOAD = [
  { name:'Rahul Verma',  phone:'98765 43211', course:'Full Stack',      issue:'React Hooks',       di:0, ti:0 },
  { name:'Deepa Nair',   phone:'98765 43214', course:'UI/UX Design',    issue:'Figma Prototype',   di:0, ti:3 },
  { name:'Sneha Patel',  phone:'98765 43216', course:'Digital Marketing',issue:'Google Ads',        di:0, ti:5 },
  { name:'Karthik Raj',  phone:'98765 43213', course:'UI/UX Design',    issue:'Color Theory',      di:1, ti:6 },
  { name:'Priya Sharma', phone:'98765 43210', course:'Full Stack',      issue:'Node.js Auth',      di:1, ti:1 }
];

var COMPLETED_DEMOS = [
  { name:'Suresh Kumar',   phone:'99887 11122', demoDate:'06 May 2024', slotTime:'11:00 AM', course:'Full Stack',        status:'Demo Completed' },
  { name:'Meena Rajan',    phone:'99887 33344', demoDate:'06 May 2024', slotTime:'12:00 PM', course:'UI/UX Design',      status:'Demo Completed' },
  { name:'Arjun Pillai',   phone:'99887 55566', demoDate:'05 May 2024', slotTime:'3:00 PM',  course:'Digital Marketing', status:'Demo Completed' },
  { name:'Kavitha Menon',  phone:'99887 77788', demoDate:'05 May 2024', slotTime:'4:00 PM',  course:'Data Science',      status:'Demo Completed' }
];

var COMPLETED_DOUBTS = [
  { name:'Priya Sharma',    phone:'98765 43210', doubtDate:'06 May 2024', slotTime:'11:00 AM', course:'Full Stack',        topic:'Redux State',    status:'Doubt Completed' },
  { name:'Mohammed Rizwan', phone:'98765 43215', doubtDate:'06 May 2024', slotTime:'12:30 PM', course:'Digital Marketing', topic:'Google Ads',     status:'Doubt Completed' },
  { name:'Vikram Rao',      phone:'98765 43217', doubtDate:'05 May 2024', slotTime:'3:30 PM',  course:'Data Science',      topic:'Power BI Charts', status:'Doubt Completed' },
  { name:'Anjali Singh',    phone:'98765 43212', doubtDate:'05 May 2024', slotTime:'4:00 PM',  course:'Full Stack',        topic:'CSS Grid',       status:'Doubt Completed' },
  { name:'Deepa Nair',      phone:'98765 43214', doubtDate:'04 May 2024', slotTime:'11:30 AM', course:'UI/UX Design',      topic:'Figma Prototype', status:'Doubt Completed' },
  { name:'Karthik Raj',     phone:'98765 43213', doubtDate:'04 May 2024', slotTime:'3:00 PM',  course:'UI/UX Design',      topic:'Color Theory',   status:'Doubt Completed' }
];


/* Attendance data by month */
var ATT_MONTHS = {
  'April 2026': { present:[1,2,3,4,7,8,9,10,11,14,15,16,17,21,22,23,24,28], absent:[5,6,12,18] },
  'March 2026':  { present:[4,5,6,7,11,12,13,14,18,19,20,21,25,26,27,28], absent:[1,8,15,22] },
  'May 2026':    { present:[1,2,5,6,7], absent:[3,4] }
};

/* Today's activities per course */
var ACTIVITIES = {
  fs: [
    { name:'Priya Sharma',    batchCode:'FS-2024-A', batchType:'Weekday',    course:'Full Stack',  activity:'Submitted Assignment: React To-Do App' },
    { name:'Rahul Verma',     batchCode:'FS-2024-A', batchType:'Weekday',    course:'Full Stack',  activity:'Attended Live Class: Module 9 — Redux' },
    { name:'Anjali Singh',    batchCode:'FS-2024-B', batchType:'Weekend',    course:'Full Stack',  activity:'Submitted Mini Project: Portfolio Website' },
    { name:'Arun Kumar',      batchCode:'FS-2024-C', batchType:'Fast Track', course:'Full Stack',  activity:'Attended Live Class: Module 15 — Deployment' }
  ],
  uiux: [
    { name:'Karthik Raj',     batchCode:'UIUX-2024-A', batchType:'Weekday', course:'UI/UX Design', activity:'Submitted Assignment: Wireframe Design' },
    { name:'Deepa Nair',      batchCode:'UIUX-2024-A', batchType:'Weekday', course:'UI/UX Design', activity:'Attended Live Class: Module 6 — Prototype' }
  ],
  dm: [
    { name:'Mohammed Rizwan', batchCode:'DM-2024-A', batchType:'Weekday',   course:'Digital Marketing', activity:'Submitted Assignment: SEO Analysis Report' },
    { name:'Sneha Patel',     batchCode:'DM-2024-A', batchType:'Weekday',   course:'Digital Marketing', activity:'Attended Live Class: Module 7 — Google Ads' }
  ],
  dac: [
    { name:'Vikram Rao',      batchCode:'DA-2024-A', batchType:'Weekday',   course:'Data Science', activity:'Submitted Main Project: Sales Dashboard' },
    { name:'Lakshmi Devi',    batchCode:'DA-2024-A', batchType:'Weekday',   course:'Data Science', activity:'Attended Live Class: Module 8 — Power BI' }
  ]
};

/* ── ACADEMIC DATA ── */
var acadState = {
  assignment:  { course:'fs', subTab:'asgn-list',  listId:'asgn-list',  evalId:'asgn-eval'  },
  miniproject: { course:'fs', subTab:'mini-list',  listId:'mini-list',  evalId:'mini-eval'  },
  mainproject: { course:'fs', subTab:'mainp-list', listId:'mainp-list', evalId:'mainp-eval' }
};

var ACAD_UPLOADS = {
  assignment:  { fs:[], uiux:[], dm:[], dac:[] },
  miniproject: { fs:[], uiux:[], dm:[], dac:[] },
  mainproject: { fs:[], uiux:[], dm:[], dac:[] }
};

var ACAD_SUBMISSIONS = {
  assignment:  { fs:[], uiux:[], dm:[], dac:[] },
  miniproject: { fs:[], uiux:[], dm:[], dac:[] },
  mainproject: { fs:[], uiux:[], dm:[], dac:[] }
};

/* Pre-seed some data */
ACAD_UPLOADS.assignment.fs = [
  { id:'AU001', title:'React Hooks Challenge', desc:'Build custom hooks for form handling and data fetching.', batchType:'Weekday', batchCode:'FS-2024-A', module:'Module 9', uploadedDate:'01 May 2024', count:1, deadline:'10 May 2024' },
  { id:'AU002', title:'Node.js REST API',       desc:'Create a full CRUD REST API with Express and MongoDB.', batchType:'Weekday', batchCode:'FS-2024-A', module:'Module 13', uploadedDate:'03 May 2024', count:2, deadline:'15 May 2024' }
];
ACAD_UPLOADS.assignment.uiux = [
  { id:'AU003', title:'Figma Wireframe Design', desc:'Design a complete wireframe for an e-commerce app.', batchType:'Weekday', batchCode:'UIUX-2024-A', module:'Module 5', uploadedDate:'02 May 2024', count:1, deadline:'12 May 2024' }
];
ACAD_SUBMISSIONS.assignment.fs = [
  { id:'AS001', uploadId:'AU001', stuName:'Priya Sharma',   batchCode:'FS-2024-A', batchType:'Weekday', uploadStatus:'uploaded',   module:'Module 9',  count:1, submittedDate:'05 May 2024', evaluated:false, score:null, grade:null, feedback:'', expanded:false, reupload:false },
  { id:'AS002', uploadId:'AU001', stuName:'Rahul Verma',    batchCode:'FS-2024-A', batchType:'Weekday', uploadStatus:'reuploaded', module:'Module 9',  count:2, submittedDate:'06 May 2024', evaluated:true,  score:78,   grade:'B+', feedback:'Good work overall. Improve error handling.', expanded:false, reupload:true },
  { id:'AS003', uploadId:'AU002', stuName:'Arun Kumar',     batchCode:'FS-2024-C', batchType:'Fast Track', uploadStatus:'uploaded', module:'Module 13', count:1, submittedDate:'06 May 2024', evaluated:false, score:null, grade:null, feedback:'', expanded:false, reupload:false }
];
ACAD_UPLOADS.miniproject.fs = [
  { id:'MU001', title:'Portfolio Website', desc:'Build a personal portfolio with React.', batchType:'Weekday', batchCode:'FS-2024-A', module:'React Module', uploadedDate:'28 Apr 2024', count:1, deadline:'08 May 2024', projectType:'Mini Project 1' }
];
ACAD_SUBMISSIONS.miniproject.fs = [
  { id:'MS001', uploadId:'MU001', stuName:'Anjali Singh', batchCode:'FS-2024-B', batchType:'Weekend', uploadStatus:'uploaded', module:'React Module', count:1, submittedDate:'05 May 2026', evaluated:false, score:null, grade:null, feedback:'', expanded:false, reupload:false, projectType:'Mini Project 1', reuploadUsed:false },
  { id:'MS002', stuName:'Priya Sharma', batchCode:'FS-2024-A', batchType:'Weekday', uploadStatus:'reuploaded', module:'React Module', count:2, submittedDate:'06 May 2026', evaluated:true, score:82, grade:'A', feedback:'Great improvement!', expanded:false, reupload:true, projectType:'Mini Project 1', reuploadUsed:true }
];
ACAD_UPLOADS.mainproject.fs = [
  { id:'MP001', title:'E-Commerce Platform (MERN)', desc:'Full-stack e-commerce app with auth, cart, and payments.', batchType:'Weekday', batchCode:'FS-2024-A', module:'Final Module', uploadedDate:'01 May 2024', count:1, deadline:'20 May 2024', projectType:'Review 1' }
];
ACAD_SUBMISSIONS.mainproject.fs = [
  { id:'MPS001', stuName:'Priya Sharma', batchCode:'FS-2024-A', batchType:'Weekday', module:'Final Module', submittedDate:'06 May 2026', evaluated:false, score:null, grade:null, feedback:'', expanded:false,
    reviews:[
      { label:'Review 1', uploadStatus:'uploaded',  reuploaded:false, reuploaded_once:false, score:null, evaluated:false },
      { label:'Review 2', uploadStatus:'pending',   reuploaded:false, reuploaded_once:false, score:null, evaluated:false },
      { label:'Review 3', uploadStatus:'pending',   reuploaded:false, reuploaded_once:false, score:null, evaluated:false }
    ]
  },
  { id:'MPS002', stuName:'Rahul Verma', batchCode:'FS-2024-A', batchType:'Weekday', module:'Final Module', submittedDate:'04 May 2026', evaluated:false, score:null, grade:null, feedback:'', expanded:false,
    reviews:[
      { label:'Review 1', uploadStatus:'reuploaded', reuploaded:true, reuploaded_once:true, score:85, evaluated:true },
      { label:'Review 2', uploadStatus:'uploaded',   reuploaded:false, reuploaded_once:false, score:null, evaluated:false },
      { label:'Review 3', uploadStatus:'pending',    reuploaded:false, reuploaded_once:false, score:null, evaluated:false }
    ]
  }
];

/* current upload/eval context */
var currentUploadType = 'assignment';
var currentEvalSubId  = null;
var currentEvalType   = 'assignment';

/* links */
var generatedLinks = [];

/* slots */
var activeSlotDate = { demo:0, doubt:0 };
var slotModal = {};

/* LMS (student detail) */
var LMS = {
  STU001: {
    pct:70, done:14, total:20,
    mods:['HTML & CSS Fundamentals','CSS Flexbox & Grid','JavaScript Basics','ES6+ Features','DOM Manipulation','Events & Forms','Fetch API & Promises','React Introduction','React Hooks','React Router','Redux Basics','Node.js Introduction','Express.js','MongoDB','Module 15 (upcoming)','','','','',''],
    att:{ present:[1,2,3,4,7,8,9,10,11,14,15,16,17,21,22,23,24,28], absent:[5,6,12,18] },
    assigns:[
      { title:'HTML/CSS Basics Portfolio', mod:'Module 1', date:'15 Feb', score:85, max:100, feedback:'Excellent layout structure. Consider improving mobile responsiveness.', status:'graded' },
      { title:'JavaScript ES6 Challenges', mod:'Module 4', date:'22 Feb', score:78, max:100, feedback:'Good understanding of arrow functions. Promises need more practice.', status:'graded' },
      { title:'React To-Do App',           mod:'Module 8', date:'Pending', score:null, max:100, feedback:null, status:'pending' }
    ],
    mini:[
      { title:'Portfolio Website',      mod:'React',    date:'10 Mar', score:90, max:100, st:'ok',   feedback:'Outstanding design and clean code. Great CSS animations.' },
      { title:'Weather Dashboard (API)',mod:'Fetch API',date:'Resubmission Pending', score:62, max:100, st:'fix', feedback:'API works but error handling missing. Fix UI responsiveness.' }
    ],
    main:{ title:'E-Commerce Platform (MERN)', reviews:[
      { lbl:'Review 1', date:'Apr 5',  score:85, note:'DB schema excellent · Auth flow solid', done:true  },
      { lbl:'Review 2', date:'Apr 20', score:72, note:'Cart logic needs fixes · Payment gateway incomplete', done:true  },
      { lbl:'Review 3', date:'Pending',score:null, note:'Final deployment review', done:false }
    ]}
  }
};

/* ══════════════════════════════════════════════
   HELPERS
══════════════════════════════════════════════ */

function $(id){ return document.getElementById(id); }

function showToast(msg, icon){
  $('t-msg').textContent = msg;
  $('t-ico').textContent = icon || '✅';
  var t = $('toast');
  t.classList.add('show');
  setTimeout(function(){ t.classList.remove('show'); }, 3000);
}

function openOv(id){ $(id).classList.add('show'); }
function closeOv(id){ $(id).classList.remove('show'); }
function toggleTag(el){ el.classList.toggle('sel'); }

function attColor(pct){
  return pct>=75?'#16a34a':pct>=50?'#d97706':'#dc2626';
}

function getLMS(sid){
  return LMS[sid] || {
    pct:55, done:8, total:15,
    mods:['Introduction','Fundamentals','Core Concepts','Advanced Topics','Project Work','Review','Capstone','Module 8','','','','','','',''],
    att:{ present:[1,2,3,4,7,8,10,11,14,15,17,21,22,28], absent:[5,6,9,12,16,18] },
    assigns:[
      { title:'Assignment 1 — Foundations', mod:'Module 1', date:'1 Mar', score:75, max:100, feedback:'Good understanding of core concepts.', status:'graded' },
      { title:'Assignment 2 — Deep Dive',   mod:'Module 3', date:'Pending', score:null, max:100, feedback:null, status:'pending' }
    ],
    mini:[
      { title:'Mini Project 1', mod:'Core Module', date:'15 Mar', score:80, max:100, st:'ok', feedback:'Well structured and clean implementation.' },
      { title:'Mini Project 2', mod:'Advanced',   date:'Pending', score:null, max:100, st:'wait', feedback:null }
    ],
    main:{ title:'Capstone Project', reviews:[{ lbl:'Review 1', date:'Upcoming', score:null, note:'Not yet started', done:false }] }
  };
}

function infoRow(lbl, val){
  return '<div class="info-row"><span class="info-lbl">'+lbl+'</span><span class="info-val">'+val+'</span></div>';
}
function lmsStat(lbl, val, color){
  return '<div class="lms-sc"><div class="lms-sc-l">'+lbl+'</div><div class="lms-sc-v" style="color:'+color+'">'+val+'</div></div>';
}

/* ══════════════════════════════════════════════
   NAVIGATION
══════════════════════════════════════════════ */

var NAV_META = {
  dashboard:   { title:'Staff <em>Dashboard</em>',       btn:'',             navId:'nav-dashboard'   },
  batches:     { title:'Batch <em>System</em>',           btn:'+ New Batch',  navId:'nav-batches'     },
  students:    { title:'Student <em>Management</em>',     btn:'+ Add Student',navId:'nav-students'    },
  detail:      { title:'Student <em>Profile</em>',        btn:'',             navId:'nav-students'    },
  demo:        { title:'Demo <em>Sessions</em>',          btn:'',             navId:'nav-demo'        },
  doubt:       { title:'Doubt <em>Sessions</em>',         btn:'',             navId:'nav-doubt'       },
  links:       { title:'<em>Meetings</em>',                btn:'',             navId:'nav-links'       },
  assignment:  { title:'Assignment <em>Centre</em>',      btn:'',             navId:'nav-assignment'  },
  miniproject: { title:'Mini <em>Project</em>',           btn:'',             navId:'nav-miniproject' },
  mainproject: { title:'Main <em>Project</em>',           btn:'',             navId:'nav-mainproject' }
};

function navTo(view){
  var views = document.querySelectorAll('.view');
  for(var i=0;i<views.length;i++) views[i].classList.remove('active');
  var nitems = document.querySelectorAll('.nitem');
  for(var i=0;i<nitems.length;i++) nitems[i].classList.remove('on');
  var el = $('v-'+view);
  if(el) el.classList.add('active');
  var meta = NAV_META[view] || {};
  var navEl = $(meta.navId);
  if(navEl) navEl.classList.add('on');
  $('tb-title').innerHTML = meta.title || view;
  $('tb-btn').textContent = meta.btn || '';
  $('tb-btn').style.display = meta.btn ? '' : 'none';
  $('search-inp').value = '';
}

function topBtnClick(){ showToast('Feature coming soon!','ℹ️'); }

/* ══════════════════════════════════════════════
   DASHBOARD
══════════════════════════════════════════════ */

function setDashFilter(el, filter){
  var tabs = el.parentNode.children;
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  el.classList.add('on');
  showToast('Showing '+filter+' data','📅');
}

function renderDashboard(){
  renderDashSessions();
  switchDashActivity(null, 'fs');
}

function renderDashSessions(){
  /* Today's Demos */
  var demoHtml = '';
  for(var i=0;i<DEMO_PRELOAD.length;i++){
    var d = DEMO_PRELOAD[i];
    var col = d.course==='Full Stack'?'#4f46e5':d.course==='UI/UX Design'?'#be185d':d.course==='Digital Marketing'?'#b45309':'#059669';
    var stu = findStudentByName(d.name);
    var nameClick = stu ? 'onclick="viewStudent(\''+stu.id+'\')" style="cursor:pointer;color:var(--accent);font-weight:600;font-size:13px;text-decoration:underline"' : 'style="font-weight:600;font-size:13px"';
    demoHtml += '<div class="session-strip">';
    demoHtml += '<div class="ss-av" style="background:'+col+'">'+d.name[0]+'</div>';
    demoHtml += '<div style="flex:1"><div '+nameClick+'>'+d.name+'</div><div class="ss-meta">📱 '+d.phone+' · '+d.course+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    demoHtml += '<div class="ss-status ss-demo-booked">Demo Booked</div>';
    demoHtml += '<button onclick="openConnectPopup(\''+d.name+'\',\''+d.course+' Demo — '+SLOT_TIMES[d.ti]+'\')" style="margin-left:8px;padding:5px 12px;border-radius:7px;border:1px solid var(--accent);background:#eff4ff;color:var(--accent);font-size:11px;font-weight:700;cursor:pointer">🔗 Connect</button>';
    demoHtml += '</div>';
  }
  $('dash-demo-list').innerHTML = demoHtml;

  /* Today's Doubts */
  var doubHtml = '';
  for(var i=0;i<DOUBT_PRELOAD.length;i++){
    var d = DOUBT_PRELOAD[i];
    if(d.di !== 0) continue;
    var col = d.course==='Full Stack'?'#4f46e5':d.course==='UI/UX Design'?'#be185d':d.course==='Digital Marketing'?'#b45309':'#059669';
    var stu = findStudentByName(d.name);
    var nameClick = stu ? 'onclick="viewStudent(\''+stu.id+'\')" style="cursor:pointer;color:var(--accent);font-weight:600;font-size:13px;text-decoration:underline"' : 'style="font-weight:600;font-size:13px"';
    doubHtml += '<div class="session-strip">';
    doubHtml += '<div class="ss-av" style="background:'+col+'">'+d.name[0]+'</div>';
    doubHtml += '<div style="flex:1"><div '+nameClick+'>'+d.name+'</div><div class="ss-meta">📱 '+d.phone+' · '+d.course+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    doubHtml += '<div class="ss-status ss-doubt-booked">Doubt Booked</div>';
    doubHtml += '<button onclick="openConnectPopup(\''+d.name+'\',\''+d.course+' Doubt — '+SLOT_TIMES[d.ti]+'\')" style="margin-left:8px;padding:5px 12px;border-radius:7px;border:1px solid var(--accent2);background:#f5f3ff;color:var(--accent2);font-size:11px;font-weight:700;cursor:pointer">🔗 Connect</button>';
    doubHtml += '</div>';
  }
  $('dash-doubt-list').innerHTML = doubHtml;
}

function switchDashActivity(el, course){
  if(el){
    var tabs = $('dash-act-tabs').children;
    for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
    el.classList.add('on');
  }
  var acts = ACTIVITIES[course] || [];
  var html = '';
  if(!acts.length){
    html = '<div class="empty-state"><div class="ei">📭</div><div class="et">No activity today</div></div>';
  } else {
    for(var i=0;i<acts.length;i++){
      var a = acts[i];
      var co = COURSES[course];
      html += '<div class="activity-strip">';
      html += '<div class="act-av" style="background:'+co.color+'">'+a.name[0]+'</div>';
      html += '<div class="act-info">';
      html += '<div class="act-name">'+a.name+'</div>';
      html += '<div class="act-detail">'+a.activity+'</div>';
      html += '</div>';
      html += '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">';
      html += '<span class="act-badge" style="background:#eef2ff;color:#4f46e5">'+a.batchCode+'</span>';
      html += '<span class="act-badge" style="background:var(--card2);color:var(--text3)">'+a.batchType+'</span>';
      html += '</div>';
      html += '</div>';
    }
  }
  $('dash-activity-list').innerHTML = html;
}

/* ══════════════════════════════════════════════
   BATCHES
══════════════════════════════════════════════ */

var batchFilter = 'all';

function filterBatches(filter){
  batchFilter = filter;
  var tabs = $('batch-tabs').children;
  var tabMap = { all:0, fs:1, uiux:2, dm:3, dac:4 };
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  var idx = tabMap[filter];
  if(idx !== undefined) tabs[idx].classList.add('on');
  renderBatches();
}

function renderBatches(){
  var list = batchFilter==='all' ? BATCHES : BATCHES.filter(function(b){ return b.course===batchFilter; });
  $('batch-count-badge').textContent = list.length + ' Batches';
  var html = '';
  for(var i=0;i<list.length;i++){
    var b = list[i];
    var ci = BATCH_COURSE_INFO[b.course];
    var cname = COURSES[b.course].name;
    var autoTitle = cname + ' — ' + b.code + ' (' + b.type + ')';
    html += '<div class="batch-card '+ci.bccls+'" onclick="viewBatchStudents(\''+b.code+'\')">';
    html += '<div class="batch-code">'+b.code+'</div>';
    html += '<div class="batch-name '+ci.ncls+'">'+ci.icon+' '+ci.name+'</div>';
    html += '<div class="batch-type '+ci.btcls+'">'+b.type+'</div>';
    html += '<div class="prog-wrap"><div class="prog-fill '+ci.pfcls+'" style="width:'+b.progress+'%"></div></div>';
    html += '<div class="batch-footer"><span>'+b.start+'</span><span>'+b.progress+'% done</span><span>'+b.end+'</span></div>';
    html += '<div class="batch-stats-row">';
    html += '<div class="bsr-item"><div class="bsr-val">'+b.students+'</div><div class="bsr-lbl">Students</div></div>';
    html += '<div class="bsr-item"><div class="bsr-val">'+b.progress+'%</div><div class="bsr-lbl">Progress</div></div>';
    html += '<div class="bsr-item"><div class="bsr-val">'+(b.type==='Weekday'?'5d':b.type==='Weekend'?'2d':'6d')+'</div><div class="bsr-lbl">Days/Wk</div></div>';
    html += '</div>';
    html += '<div style="padding-top:10px;border-top:1px solid var(--border);margin-top:8px">';
    html += '<button onclick="event.stopPropagation();openBatchConnectPopup(\''+b.code+'\',\''+autoTitle+'\')" style="width:100%;padding:8px;border-radius:8px;border:1px solid var(--accent);background:#eff4ff;color:var(--accent);font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px">📹 Connect</button>';
    html += '</div>';
    html += '</div>';
  }
  $('batch-grid').innerHTML = html;
}

/* Clicking a batch → show ONLY that batch's students */
function viewBatchStudents(code){
  var b = BATCHES.find(function(x){ return x.code===code; });
  if(!b) return;
  navTo('students');
  /* Set batch sub-filter to specific batch */
  currentStuCourse = b.course;
  currentStuBatch  = code;
  /* Update course tabs */
  var tabs = $('stu-tabs').children;
  var tabMap = { all:0, fs:1, uiux:2, dm:3, dac:4 };
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  var ti = tabMap[b.course];
  if(ti !== undefined) tabs[ti].classList.add('on');
  renderStudents(b.course, code);
  showBatchSubFilter(b.course, code);
}

/* ══════════════════════════════════════════════
   STUDENTS
══════════════════════════════════════════════ */

var currentStuCourse = 'all';
var currentStuBatch  = 'all';

function filterStudents(course, batch){
  currentStuCourse = course;
  currentStuBatch  = batch || 'all';
  var tabs = $('stu-tabs').children;
  var tabMap = { all:0, fs:1, uiux:2, dm:3, dac:4 };
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  var idx = tabMap[course];
  if(idx !== undefined) tabs[idx].classList.add('on');
  renderStudents(course, 'all');
  if(course === 'all'){
    $('batch-subfilter').style.display = 'none';
  } else {
    showBatchSubFilter(course, 'all');
  }
}

function showBatchSubFilter(course, activeBatch){
  var courseBatches = BATCHES.filter(function(b){ return b.course===course; });
  var ci = BATCH_COURSE_INFO[course];
  var html = '<div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center">';
  html += '<span style="font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.08em">Filter batch:</span>';
  html += '<span class="ctab '+(activeBatch==='all'?'on':'')+'" onclick="filterStudents(\''+course+'\',\'all\')">All '+ci.name+'</span>';
  for(var i=0;i<courseBatches.length;i++){
    var b = courseBatches[i];
    html += '<span class="ctab ctab-'+ci.name.toLowerCase().replace(/\s/g,'').substr(0,2)+' '+(activeBatch===b.code?'on':'')+'" onclick="filterStudents(\''+course+'\',\''+b.code+'\')">'+b.code+' <span style="font-size:10px;opacity:.7">'+b.type+'</span></span>';
  }
  html += '</div>';
  $('batch-subfilter').innerHTML = html;
  $('batch-subfilter').style.display = 'block';
}

function renderStudents(course, batchCode){
  var bCode = batchCode || currentStuBatch || 'all';
  var list;
  if(course === 'all'){
    list = STUDENTS;
  } else if(bCode !== 'all'){
    list = STUDENTS.filter(function(s){ return s.course===course && s.batch===bCode; });
  } else {
    list = STUDENTS.filter(function(s){ return s.course===course; });
  }

  if(!list.length){
    $('stu-list').innerHTML = '<div class="empty-state"><div class="ei">👤</div><div class="et">No students found</div><div class="es">Try a different filter</div></div>';
    return;
  }

  /* Group by batch when showing a course (not specific batch) */
  var html = '';
  if(course !== 'all' && bCode === 'all'){
    /* Group by batch */
    var batches = [];
    for(var i=0;i<list.length;i++){
      if(batches.indexOf(list[i].batch) === -1) batches.push(list[i].batch);
    }
    for(var bi=0;bi<batches.length;bi++){
      var bc = batches[bi];
      var bInfo = BATCHES.find(function(x){ return x.code===bc; });
      var ci = BATCH_COURSE_INFO[course];
      html += '<div class="batch-group-hdr">';
      html += '<span>'+ci.icon+' '+bc+'</span>';
      html += '<span class="batch-group-pill" style="background:var(--pl);color:var(--p)">'+(bInfo?bInfo.type:'')+'</span>';
      var bCount = list.filter(function(s){ return s.batch===bc; }).length;
      html += '<span style="margin-left:auto;font-size:10px;color:var(--text3)">'+bCount+' students</span>';
      html += '</div>';
      var bStudents = list.filter(function(s){ return s.batch===bc; });
      for(var si=0;si<bStudents.length;si++){
        html += buildStudentRow(bStudents[si]);
      }
    }
  } else {
    for(var i=0;i<list.length;i++){
      html += buildStudentRow(list[i]);
    }
  }
  $('stu-list').innerHTML = html;
}

function buildStudentRow(s){
  var ci = BATCH_COURSE_INFO[s.course];
  var ac = attColor(s.att);
  var pillCls = 'p-'+(s.status==='active'?'active':s.status==='inactive'?'inactive':'pending');
  var html = '<div class="dt-row cols-stu" onclick="viewStudent(\''+s.id+'\')">'; 
  html += '<div><div class="sav" style="background:'+COURSES[s.course].color+'">'+s.name[0]+'</div></div>';
  html += '<div><div class="sname">'+s.name+'</div><div class="semail">'+s.email+'</div></div>';
  html += '<div style="font-size:11.5px;color:var(--text3);font-family:monospace">'+s.batch+'</div>';
  html += '<div><span class="pill p-'+ci.bccls.replace('bc-','')+'">'+ci.icon+' '+ci.name+'</span></div>';
  html += '<div style="font-size:12px;color:var(--text2)">'+s.joined+'</div>';
  html += '<div class="att-inline"><div class="att-bar"><div class="att-fill" style="width:'+s.att+'%;background:'+ac+'"></div></div><span style="font-size:11.5px;color:'+ac+';font-weight:600">'+s.att+'%</span></div>';
  html += '<div><span class="pill '+pillCls+'">'+s.status+'</span></div>';
  html += '</div>';
  return html;
}

/* ══════════════════════════════════════════════
   STUDENT DETAIL
══════════════════════════════════════════════ */

var currentStuId = null;
var detailPrevView = 'students';

function viewStudent(id){
  currentStuId = id;
  navTo('detail');
  var s = STUDENTS.find(function(x){ return x.id===id; });
  if(!s) return;
  var lms = getLMS(id);
  var ci  = BATCH_COURSE_INFO[s.course];
  var co  = COURSES[s.course];
  var ac  = attColor(s.att);

  /* ── MINI DASHBOARD ── */
  var md = '';
  /* Stats row */
  md += '<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;margin-bottom:14px">';
  md += miniStat('📊','Course Progress', lms.pct+'%', co.color);
  md += miniStat('📅','Attendance', s.att+'%', ac);
  md += miniStat('📘','Modules Done', lms.done+'/'+lms.total, '#24b8ec');
  var graded = lms.assigns.filter(function(a){ return a.score!==null; }).length;
  md += miniStat('📋','Assignments', graded+'/'+lms.assigns.length, '#7c3aed');
  var mDone  = lms.mini.filter(function(m){ return m.st==='ok'; }).length;
  md += miniStat('🔧','Mini Projects', mDone+'/'+lms.mini.length, '#059669');
  md += '</div>';

  /* Upcoming sessions */
  var demoMatch = [];
  for(var di=0;di<DATE_LABELS.length;di++){
    for(var si=0;si<SLOT_TIMES.length;si++){
      if(slotStore.demo[di] && slotStore.demo[di][si] && slotStore.demo[di][si].name===s.name){
        demoMatch.push({ date:DATE_LABELS[di], time:SLOT_TIMES[si] });
      }
    }
  }
  var doubtMatch = [];
  for(var di=0;di<DATE_LABELS.length;di++){
    for(var si=0;si<SLOT_TIMES.length;si++){
      if(slotStore.doubt[di] && slotStore.doubt[di][si] && slotStore.doubt[di][si].name===s.name){
        doubtMatch.push({ date:DATE_LABELS[di], time:SLOT_TIMES[si] });
      }
    }
  }

  if(demoMatch.length || doubtMatch.length){
    md += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">';
    md += '<div class="card card-pad" style="padding:14px">';
    md += '<div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">🎯 Demo Session</div>';
    if(demoMatch.length){ md += '<div class="session-strip" style="margin-bottom:0"><div class="ss-name">'+demoMatch[0].date+' — '+demoMatch[0].time+'</div><div class="ss-status ss-demo-booked">Demo Booked</div></div>'; }
    else { md += '<div style="font-size:12px;color:var(--text3)">No demo scheduled</div>'; }
    md += '</div>';
    md += '<div class="card card-pad" style="padding:14px">';
    md += '<div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">💬 Doubt Session</div>';
    if(doubtMatch.length){ md += '<div class="session-strip" style="margin-bottom:0"><div class="ss-name">'+doubtMatch[0].date+' — '+doubtMatch[0].time+'</div><div class="ss-status ss-doubt-booked">Doubt Booked</div></div>'; }
    else { md += '<div style="font-size:12px;color:var(--text3)">No doubt scheduled</div>'; }
    md += '</div>';
    md += '</div>';
  }

  /* Recent Activity */
  var acts = ACTIVITIES[s.course] || [];
  var stuActs = acts.filter(function(a){ return a.name===s.name; });
  if(stuActs.length){
    md += '<div class="card card-pad" style="padding:14px;margin-bottom:0">';
    md += '<div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">⚡ Today\'s Activity</div>';
    for(var i=0;i<stuActs.length;i++){
      md += '<div class="activity-strip" style="margin-bottom:'+(i<stuActs.length-1?'8px':'0')+'">';
      md += '<div class="act-av" style="background:'+co.color+'">'+s.name[0]+'</div>';
      md += '<div class="act-info"><div class="act-detail">'+stuActs[i].activity+'</div></div>';
      md += '</div>';
    }
    md += '</div>';
  }
  $('detail-mini-dash').innerHTML = md;

  /* ── LEFT PANEL ── */
  var left = '';
  left += '<div class="hero-card">';
  left += '<div class="hero-banner"></div>';
  left += '<div class="hero-body">';
  left += '<div class="hero-av" style="background:'+co.color+'">'+s.name[0]+'</div>';
  left += '<div class="hero-name">'+s.name+'</div>';
  left += '<div class="hero-id">'+s.id+' · <span style="color:'+co.color+'">'+ci.icon+' '+ci.name+'</span></div>';
  left += '<div style="margin-top:10px"><span class="pill p-'+(s.status==='active'?'active':s.status==='inactive'?'inactive':'pending')+'">'+s.status+'</span></div>';
  left += '<div class="info-list" style="margin-top:12px">';
  left += infoRow('📧 Email', s.email);
  left += infoRow('📱 Phone', s.phone);
  left += infoRow('🗂 Batch', s.batch);
  left += infoRow('📅 Joined', s.joined);
  left += infoRow('💳 Fee', s.fee);
  left += infoRow('📊 Attendance', '<span style="color:'+ac+';font-weight:700">'+s.att+'%</span>');
  left += '</div></div></div>';
  left += '<button class="btn btn-danger" style="width:100%;margin-top:12px" onclick="openAssign(\''+id+'\',\''+s.name+'\')">⚠️ Assign Lead to Staff</button>';
  left += '<div style="font-size:11px;color:var(--text3);text-align:center;margin-top:6px">Assign to Telecaller or Support Executive</div>';

  /* ── RIGHT PANEL — LMS ── */
  var right = '<div class="card card-pad">';
  right += '<div class="card-head"><div class="card-title">📚 LMS History <span class="cbadge">'+s.name.split(' ')[0]+'\'s Progress</span></div></div>';
  right += '<div class="lms-tabs">';
  right += '<button class="lms-tab on" onclick="switchLms(this,\'lms-ov\')">Overview</button>';
  right += '<button class="lms-tab" onclick="switchLms(this,\'lms-att\')">Attendance</button>';
  right += '<button class="lms-tab" onclick="switchLms(this,\'lms-asgn\')">Assignments</button>';
  right += '<button class="lms-tab" onclick="switchLms(this,\'lms-mini\')">Mini Projects</button>';
  right += '<button class="lms-tab" onclick="switchLms(this,\'lms-main\')">Main Project</button>';
  right += '</div>';

  /* OVERVIEW */
  right += '<div class="lms-panel on" id="lms-ov">';
  right += '<div class="lms-stats">';
  right += lmsStat('Course', lms.pct+'%', co.color);
  right += lmsStat('Attendance', s.att+'%', ac);
  right += lmsStat('Modules', lms.done+'/'+lms.total, '#24b8ec');
  right += '</div>';
  right += '<div style="height:6px;background:var(--border);border-radius:99px;overflow:hidden;margin-bottom:14px">';
  right += '<div style="width:'+lms.pct+'%;height:100%;border-radius:99px;background:'+co.color+'"></div></div>';
  right += '<div style="font-size:12px;font-weight:700;color:var(--p);margin-bottom:8px">📘 Module Progress</div>';
  right += '<div style="max-height:260px;overflow-y:auto">';
  for(var m=0;m<lms.mods.length;m++){
    if(!lms.mods[m]) continue;
    var done = m < lms.done;
    right += '<div class="mod-item"><div class="mod-dot '+(done?'md-done':'md-todo')+'">'+(done?'✓':'')+'</div>';
    right += '<span style="color:'+(done?'var(--text)':'var(--text3)')+';font-weight:'+(done?600:400)+'">Module '+(m+1)+' — '+lms.mods[m]+'</span>';
    if(done) right += '<span style="margin-left:auto;font-size:10px;color:var(--su);font-weight:600">Done</span>';
    right += '</div>';
  }
  right += '</div></div>';

  /* ATTENDANCE */
  var pDays=lms.att.present,aDays=lms.att.absent;
  var pct=Math.round(pDays.length/(pDays.length+aDays.length)*100)||0;
  right += '<div class="lms-panel" id="lms-att">';
  right += '<div class="lms-stats" style="margin-bottom:14px">';
  right += lmsStat('Present',pDays.length,'#16a34a');
  right += lmsStat('Absent',aDays.length,'#dc2626');
  right += lmsStat('Rate',pct+'%',attColor(pct));
  right += '</div>';
  right += '<div class="cal-wrap"><div style="font-size:12px;font-weight:700;color:var(--p);margin-bottom:10px">April 2024 — Attendance Calendar</div>';
  right += '<div class="cal-hdr">';
  var dN=['Mo','Tu','We','Th','Fr','Sa','Su'];
  for(var d=0;d<7;d++) right+='<span>'+dN[d]+'</span>';
  right += '</div><div class="cal-grid">';
  for(var day=1;day<=30;day++){
    var cls=pDays.indexOf(day)>-1?'cd-p':aDays.indexOf(day)>-1?'cd-a':'cd-n';
    var tip=pDays.indexOf(day)>-1?'Present ✅':aDays.indexOf(day)>-1?'Absent ❌':'No Class';
    right += '<div class="cal-day '+cls+'" title="'+tip+'" onclick="showToast(\'Day '+day+' — '+tip+'\')">'+day+'</div>';
  }
  right += '</div><div class="cal-legend">';
  right += '<div class="leg-item" style="color:#16a34a"><div class="leg-dot" style="background:#16a34a"></div>Present: '+pDays.length+'</div>';
  right += '<div class="leg-item" style="color:#dc2626"><div class="leg-dot" style="background:#dc2626"></div>Absent: '+aDays.length+'</div>';
  right += '<span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--p)">Min 75% ';
  right += pct>=75?'<span style="color:#16a34a">✓ Met</span>':'<span style="color:#dc2626">✗ Below</span>';
  right += '</span></div></div></div>';

  /* ASSIGNMENTS */
  right += '<div class="lms-panel" id="lms-asgn"><div style="font-size:13px;font-weight:700;color:var(--p);margin-bottom:12px">📋 Assignment Submissions</div>';
  for(var ai=0;ai<lms.assigns.length;ai++){
    var a=lms.assigns[ai];
    var apct=a.score?Math.round(a.score/a.max*100):0;
    var acol=apct>=75?'#16a34a':apct>=50?'#d97706':'#dc2626';
    right += '<div class="asgn-card ac-'+a.status+'">';
    right += '<div class="asgn-head"><div><div class="asgn-title">'+a.title+'</div><div class="asgn-meta">'+a.mod+' · Submitted: '+a.date+'</div></div>';
    if(a.score!==null){ right += '<div class="asgn-score" style="color:'+acol+'">'+a.score+'<span>/'+a.max+'</span></div>'; }
    else{ right += '<div style="font-size:12px;font-weight:700;color:'+(a.status==='overdue'?'#dc2626':'#d97706')+'">'+((a.status==='overdue'?'⚠ Overdue':'⏳ Pending'))+'</div>'; }
    right += '</div>';
    if(a.score!==null){ right += '<div class="asgn-bar"><div class="asgn-track"><div class="asgn-fill" style="width:'+apct+'%;background:'+acol+'"></div></div><span style="font-size:11px;font-weight:600;color:'+acol+'">'+apct+'%</span></div>'; }
    if(a.feedback) right += '<div class="feedback-box">💬 '+a.feedback+'</div>';
    right += '</div>';
  }
  right += '</div>';

  /* MINI PROJECTS */
  right += '<div class="lms-panel" id="lms-mini"><div style="font-size:13px;font-weight:700;color:var(--p);margin-bottom:12px">◈ Mini Projects</div>';
  for(var mi=0;mi<lms.mini.length;mi++){
    var mp=lms.mini[mi];
    var mpct=mp.score?Math.round(mp.score/mp.max*100):0;
    var mcls=mp.st==='ok'?'mc-ok':mp.st==='fix'?'mc-fix':'mc-wait';
    var mcol=mp.st==='ok'?'#16a34a':mp.st==='fix'?'#dc2626':'#d97706';
    var tbg=mp.st==='ok'?'#bbf7d0':mp.st==='fix'?'#fecaca':'#fde68a';
    var slabel=mp.st==='ok'?'✓ Completed':mp.st==='fix'?'⚠ Changes Needed':'⏳ Pending Review';
    right += '<div class="mini-card '+mcls+'"><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">';
    right += '<div><div style="font-size:13px;font-weight:700;color:'+mcol+'">'+mp.title+'</div><div style="font-size:11px;color:'+mcol+';opacity:.7">'+mp.mod+' · '+mp.date+'</div></div>';
    if(mp.score!==null){ right += '<div style="text-align:center"><div style="font-family:\'Playfair Display\',serif;font-size:24px;font-weight:800;color:'+mcol+'">'+mpct+'<span style="font-size:11px">%</span></div></div>'; }
    else { right += '<div style="font-size:12px;font-weight:700;color:'+mcol+'">⏳ Pending</div>'; }
    right += '</div>';
    if(mp.score!==null){ right += '<div style="display:flex;align-items:center;gap:6px;margin-top:6px"><div style="flex:1;height:6px;border-radius:4px;overflow:hidden;background:'+tbg+'"><div style="width:'+mpct+'%;height:100%;background:'+mcol+'"></div></div><span style="font-size:10px;font-weight:600;color:'+mcol+'">'+slabel+'</span></div>'; }
    if(mp.feedback) right += '<div style="font-size:11px;color:'+mcol+';margin-top:8px;opacity:.8">💬 '+mp.feedback+'</div>';
    right += '</div>';
  }
  right += '</div>';

  /* MAIN PROJECT */
  right += '<div class="lms-panel" id="lms-main">';
  right += '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px"><div style="font-size:13px;font-weight:700;color:var(--p)">🏗 '+lms.main.title+'</div>';
  var doneRevs=lms.main.reviews.filter(function(r){return r.done;}).length;
  right += '<span class="cbadge">Review '+doneRevs+' / '+lms.main.reviews.length+'</span></div>';
  right += '<div class="main-proj">';
  for(var ri=0;ri<lms.main.reviews.length;ri++){
    var rev=lms.main.reviews[ri];
    var rcol=rev.score>=75?'#16a34a':rev.score>=50?'#d97706':rev.score?'#dc2626':'#9ca3af';
    var rbg=rev.score>=75?'linear-gradient(90deg,#16a34a,#4ade80)':rev.score>=50?'linear-gradient(90deg,#d97706,#fbbf24)':rev.score?'linear-gradient(90deg,#dc2626,#f87171)':'';
    right += '<div class="rev-row"><div class="rev-hd"><div class="rev-lbl">'+rev.lbl+'<span style="font-size:10px;color:'+(rev.done?'#166534':'var(--mu)')+';font-weight:500"> · '+rev.date+' · '+(rev.done?'✓ Completed':'⏳ Pending')+'</span></div>';
    if(rev.score!==null) right += '<span class="rev-pct" style="color:'+rcol+'">'+rev.score+'%</span>';
    else right += '<span style="font-size:11px;color:var(--mu)">—</span>';
    right += '</div><div class="rev-track">';
    if(rev.score!==null) right += '<div class="rev-fill" style="width:'+rev.score+'%;background:'+rbg+'"><span>'+rev.score+'%</span></div>';
    else right += '<div style="width:0;height:100%"></div>';
    right += '</div><div class="rev-note">'+rev.note+'</div></div>';
  }
  right += '</div></div>';
  right += '</div>'; /* /card */

  $('detail-body').innerHTML = '<div>'+left+'</div><div>'+right+'</div>';
}

function miniStat(ico, lbl, val, color){
  return '<div class="stat-card" style="padding:14px;cursor:default"><div class="stat-ico">'+ico+'</div><div class="stat-lbl">'+lbl+'</div><div class="stat-num" style="font-size:22px;color:'+color+'">'+val+'</div></div>';
}

function switchLms(btn, panelId){
  var tabs = document.querySelectorAll('.lms-tab');
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  btn.classList.add('on');
  var panels = document.querySelectorAll('.lms-panel');
  for(var i=0;i<panels.length;i++) panels[i].classList.remove('on');
  var p = $(panelId);
  if(p) p.classList.add('on');
}

/* ══════════════════════════════════════════════
   ASSIGN MODAL
══════════════════════════════════════════════ */

function openAssign(id, name){
  $('assign-sub').textContent = name+' ('+id+')';
  $('assign-to').value = '';
  $('assign-reason').value = '';
  $('assign-notes').value = '';
  $('assign-date').value = new Date().toISOString().split('T')[0];
  var tags = document.querySelectorAll('#assign-tags .tag-opt');
  for(var i=0;i<tags.length;i++) tags[i].classList.remove('sel');
  openOv('ov-assign');
}

function submitAssign(){
  var to = $('assign-to').value;
  var reason = $('assign-reason').value;
  var date = $('assign-date').value;
  if(!to){ showToast('Please select assignee','⚠️'); return; }
  if(!reason){ showToast('Please select a reason','⚠️'); return; }
  if(!date){ showToast('Please set a follow-up date','⚠️'); return; }
  closeOv('ov-assign');
  showToast('Lead assigned to '+(to==='telecaller'?'Telecaller':'Support Executive')+' successfully!', '✅');
}

/* ══════════════════════════════════════════════
   SLOT SYSTEM
══════════════════════════════════════════════ */

function initSlots(){
  var types = ['demo','doubt'];
  for(var ti=0;ti<types.length;ti++){
    var type = types[ti];
    slotStore[type] = {};
    for(var di=0;di<DATE_LABELS.length;di++){
      slotStore[type][di] = {};
      for(var si=0;si<SLOT_TIMES.length;si++) slotStore[type][di][si] = null;
    }
  }
  for(var i=0;i<DEMO_PRELOAD.length;i++){
    var p=DEMO_PRELOAD[i];
    slotStore.demo[p.di][p.ti]={ name:p.name, phone:p.phone, course:p.course, issue:'' };
  }
  for(var i=0;i<DOUBT_PRELOAD.length;i++){
    var p=DOUBT_PRELOAD[i];
    slotStore.doubt[p.di][p.ti]={ name:p.name, phone:p.phone, course:p.course, issue:p.issue };
  }
}

function renderSlotPanel(type){
  var di = activeSlotDate[type];
  var dayData = slotStore[type][di];
  var bookedCount = 0;
  for(var si=0;si<SLOT_TIMES.length;si++) if(dayData[si]) bookedCount++;

  var dateTabs='';
  for(var i=0;i<DATE_LABELS.length;i++)
    dateTabs += '<div class="dtab'+(di===i?' on':'')+'" onclick="switchSlotDate(\''+type+'\','+i+')">'+DATE_LABELS[i]+'</div>';

  var boxes='';
  for(var si=0;si<SLOT_TIMES.length;si++){
    var sl=dayData[si];
    if(sl){
      boxes += '<div class="slot-box sb-booked" onclick="slotClick(\''+type+'\','+di+','+si+')">';
      boxes += '<div class="slot-num">Slot '+(si+1)+'</div>';
      boxes += '<div class="slot-time">'+SLOT_TIMES[si]+'</div>';
      boxes += '<div class="slot-bname">'+sl.name+'</div>';
      boxes += '<div class="slot-bcourse">'+sl.course+(sl.issue?' · '+sl.issue:'')+'</div>';
      boxes += '</div>';
    } else {
      boxes += '<div class="slot-box" onclick="slotClick(\''+type+'\','+di+','+si+')">';
      boxes += '<div class="slot-num">Slot '+(si+1)+'</div>';
      boxes += '<div class="slot-time">'+SLOT_TIMES[si]+'</div>';
      boxes += '<div class="slot-avail">+ Available</div>';
      boxes += '</div>';
    }
  }

  var html = '<div class="slot-card"><div class="slot-head"><div style="display:flex;align-items:center;justify-content:space-between">';
  html += '<div><div class="slot-title">My '+(type==='demo'?'Demo':'Doubt')+' Slots</div>';
  html += '<div class="slot-sub">'+bookedCount+'/10 slots booked</div></div>';
  html += '<span class="cbadge">'+(10-bookedCount)+' Available</span></div></div>';
  html += '<div class="date-tabs">'+dateTabs+'</div>';
  html += '<div class="slots-grid">'+boxes+'</div></div>';

  $(type+'-slot-panel').innerHTML = html;

  if($('badge-demo')) $('badge-demo').textContent = Object.values(slotStore.demo[0]).filter(function(x){return x!==null;}).length;
  if($('badge-doubt')) $('badge-doubt').textContent = Object.values(slotStore.doubt[0]).filter(function(x){return x!==null;}).length;

  renderSlotAside(type);
}

function switchSlotDate(type, di){
  activeSlotDate[type] = di;
  renderSlotPanel(type);
}

function slotClick(type, di, si){
  slotModal = { type:type, di:di, si:si };
  var sl = slotStore[type][di][si];
  if(sl){
    $('slot-m-title').textContent = 'Booked Slot — '+SLOT_TIMES[si];
    $('slot-m-sub').textContent = DATE_LABELS[di];
    var html = '<div style="background:var(--al);border-radius:var(--rs);padding:14px;margin-bottom:12px">';
    html += '<div style="font-size:14px;font-weight:700;color:var(--p);margin-bottom:10px">'+sl.name+'</div>';
    html += infoRow('📱 Phone', sl.phone);
    html += infoRow('📚 Course', sl.course);
    if(sl.issue) html += infoRow('❓ Topic', sl.issue);
    html += infoRow('⏰ Time', SLOT_TIMES[si]+' · '+DATE_LABELS[di]);
    html += '</div>';
    html += '<button class="btn btn-danger" style="width:100%" onclick="cancelSlot()">Cancel This Slot</button>';
    $('slot-m-body').innerHTML = html;
    $('slot-m-footer').innerHTML = '<button class="btn btn-ghost" style="flex:1" onclick="closeOv(\'ov-slot\')">Close</button>';
    openOv('ov-slot');
  } else {
    $('slot-m-title').textContent = 'Book '+(type==='demo'?'Demo':'Doubt')+' Slot';
    $('slot-m-sub').textContent = DATE_LABELS[di]+' · '+SLOT_TIMES[si];
    var opts = '<option value="">— Select student —</option>';
    for(var i=0;i<STUDENTS.length;i++) opts += '<option value="'+STUDENTS[i].id+'">'+STUDENTS[i].name+' · '+COURSES[STUDENTS[i].course].name+'</option>';
    var html = '<div class="fg"><label class="flabel">Time Slot</label><input class="finp" value="'+DATE_LABELS[di]+' · '+SLOT_TIMES[si]+'" readonly></div>';
    html += '<div class="fg"><label class="flabel">Student / Lead *</label><select class="fsel" id="slot-student">'+opts+'</select></div>';
    if(type==='doubt') html += '<div class="fg"><label class="flabel">Doubt Topic</label><input class="finp" id="slot-topic" placeholder="e.g. React Hooks, CSS Grid…"></div>';
    html += '<div class="fg"><label class="flabel">Notes</label><textarea class="fta" id="slot-notes" style="min-height:60px" placeholder="Additional notes…"></textarea></div>';
    $('slot-m-body').innerHTML = html;
    $('slot-m-footer').innerHTML = '<button class="btn btn-ghost" style="flex:1" onclick="closeOv(\'ov-slot\')">Cancel</button><button class="btn btn-pri" style="flex:1" onclick="confirmSlot()">Confirm Booking</button>';
    openOv('ov-slot');
  }
}

function cancelSlot(){
  slotStore[slotModal.type][slotModal.di][slotModal.si] = null;
  closeOv('ov-slot');
  renderSlotPanel(slotModal.type);
  showToast('Slot cancelled successfully');
}

function confirmSlot(){
  var sid = $('slot-student').value;
  if(!sid){ showToast('Please select a student','⚠️'); return; }
  var s = STUDENTS.find(function(x){ return x.id===sid; });
  var topic = $('slot-topic') ? $('slot-topic').value : '';
  slotStore[slotModal.type][slotModal.di][slotModal.si] = { name:s.name, phone:s.phone, course:COURSES[s.course].name, issue:topic };
  closeOv('ov-slot');
  renderSlotPanel(slotModal.type);
  showToast('Slot booked for '+s.name+'!');
}

function renderSlotAside(type){
  var asideId = type+'-aside';
  var html = '';
  var count = 0;
  for(var di=0;di<DATE_LABELS.length;di++){
    for(var si=0;si<SLOT_TIMES.length;si++){
      var sl = slotStore[type][di][si];
      if(sl){
        count++;
        html += '<div class="asgnd-item" onclick="slotClick(\''+type+'\','+di+','+si+')">';
        html += '<div class="asgnd-name">'+sl.name+'</div>';
        html += '<div class="asgnd-meta">'+sl.course+(sl.issue?' · '+sl.issue:'')+'</div>';
        html += '<div><span class="time-tag">'+DATE_LABELS[di]+'</span><span class="time-tag">'+SLOT_TIMES[si]+'</span></div>';
        html += '</div>';
      }
    }
  }
  if(!count) html = '<div class="empty-state" style="padding:20px"><div class="ei">📭</div><div class="et">No sessions assigned yet</div></div>';
  $(asideId).innerHTML = html;
}

/* ── Sub-tab switching (demo/doubt) ── */
function switchSubTab(section, btn, panelId){
  /* update sub-tabs */
  var parent = btn.parentNode;
  var tabs = parent.children;
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  btn.classList.add('on');
  /* find panels in same view */
  var view = $('v-'+section);
  var panels = view.querySelectorAll('.subpanel');
  for(var i=0;i<panels.length;i++) panels[i].classList.remove('on');
  var p = $(panelId);
  if(p) p.classList.add('on');
}

/* Render assigned and completed session lists */
function renderSessionLists(){
  /* Assigned Demos */
  var dHtml = '';
  var courseColors = { 'Full Stack':'#4f46e5','UI/UX Design':'#be185d','Digital Marketing':'#b45309','Data Science':'#059669','Data Analytics':'#059669' };
  for(var i=0;i<DEMO_PRELOAD.length;i++){
    var d = DEMO_PRELOAD[i];
    var col = courseColors[d.course] || '#4f46e5';
    dHtml += '<div class="booked-strip">';
    dHtml += '<div class="bs-av" style="background:'+col+'">'+d.name[0]+'</div>';
    dHtml += '<div class="bs-info"><div class="bs-name">'+d.name+'</div><div class="bs-meta">'+d.course+' · '+DATE_LABELS[d.di]+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    dHtml += '<div class="bs-phone">📱 '+d.phone+'</div>';
    dHtml += '<div class="bs-status" style="background:#dbeafe;color:#1e40af">Demo Booked</div>';
    dHtml += '</div>';
  }
  if(!dHtml) dHtml = '<div class="empty-state"><div class="ei">📭</div><div class="et">No assigned demos</div></div>';
  $('assigned-demo-list').innerHTML = dHtml;

  /* Completed Demos */
  var cHtml = '';
  for(var i=0;i<COMPLETED_DEMOS.length;i++){
    var d = COMPLETED_DEMOS[i];
    var col = courseColors[d.course] || '#4f46e5';
    cHtml += '<div class="booked-strip">';
    cHtml += '<div class="bs-av" style="background:'+col+'">'+d.name[0]+'</div>';
    cHtml += '<div class="bs-info"><div class="bs-name">'+d.name+'</div><div class="bs-meta">'+d.course+' · '+d.demoDate+' · '+d.slotTime+'</div></div>';
    cHtml += '<div class="bs-phone">📱 '+d.phone+'</div>';
    cHtml += '<div class="bs-status" style="background:var(--sub);color:#166534">Demo Completed</div>';
    cHtml += '</div>';
  }
  if(!cHtml) cHtml = '<div class="empty-state"><div class="ei">✅</div><div class="et">No completed demos</div></div>';
  $('completed-demo-list').innerHTML = cHtml;

  /* Assigned Doubts */
  var doHtml = '';
  for(var i=0;i<DOUBT_PRELOAD.length;i++){
    var d = DOUBT_PRELOAD[i];
    var col = courseColors[d.course] || '#4f46e5';
    doHtml += '<div class="booked-strip">';
    doHtml += '<div class="bs-av" style="background:'+col+'">'+d.name[0]+'</div>';
    doHtml += '<div class="bs-info"><div class="bs-name">'+d.name+'</div><div class="bs-meta">'+d.course+' · '+(d.issue||'General')+' · '+DATE_LABELS[d.di]+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    doHtml += '<div class="bs-phone">📱 '+d.phone+'</div>';
    doHtml += '<div class="bs-status" style="background:#f5f3ff;color:#5b21b6">Doubt Booked</div>';
    doHtml += '</div>';
  }
  if(!doHtml) doHtml = '<div class="empty-state"><div class="ei">📭</div><div class="et">No assigned doubts</div></div>';
  $('assigned-doubt-list').innerHTML = doHtml;

  /* Completed Doubts */
  var cdHtml = '';
  for(var i=0;i<COMPLETED_DOUBTS.length;i++){
    var d = COMPLETED_DOUBTS[i];
    var col = courseColors[d.course] || '#4f46e5';
    cdHtml += '<div class="booked-strip">';
    cdHtml += '<div class="bs-av" style="background:'+col+'">'+d.name[0]+'</div>';
    cdHtml += '<div class="bs-info"><div class="bs-name">'+d.name+'</div><div class="bs-meta">'+d.course+' · '+(d.topic||'General')+' · '+d.doubtDate+' · '+d.slotTime+'</div></div>';
    cdHtml += '<div class="bs-phone">📱 '+d.phone+'</div>';
    cdHtml += '<div class="bs-status" style="background:var(--sub);color:#166534">Doubt Completed</div>';
    cdHtml += '</div>';
  }
  if(!cdHtml) cdHtml = '<div class="empty-state"><div class="ei">✅</div><div class="et">No completed doubts</div></div>';
  $('completed-doubt-list').innerHTML = cdHtml;
}

/* ══════════════════════════════════════════════
   CLASS LINKS
══════════════════════════════════════════════ */

function populateLinkDropdowns(){
  var bOpts = '<option value="">— Select batch —</option>';
  for(var i=0;i<BATCHES.length;i++) bOpts += '<option value="'+BATCHES[i].code+'">'+BATCHES[i].code+' · '+COURSES[BATCHES[i].course].name+'</option>';
  $('dl-batch').innerHTML = bOpts;
  $('ll-batch').innerHTML = bOpts;
  var sOpts = '<option value="">— Select student —</option>';
  for(var i=0;i<STUDENTS.length;i++) sOpts += '<option value="'+STUDENTS[i].id+'">'+STUDENTS[i].name+' · '+COURSES[STUDENTS[i].course].name+'</option>';
  $('dl-student').innerHTML = sOpts;
}

function genLink(type, titleId, urlId){
  var titleEl = $(titleId);
  var urlEl   = $(urlId);
  var title = titleEl && titleEl.value.trim() ? titleEl.value.trim() : type;
  var url   = urlEl && urlEl.value.trim() ? urlEl.value.trim() : 'https://meet.google.com/'+Math.random().toString(36).substr(2,10);
  if(titleEl) titleEl.value='';
  if(urlEl) urlEl.value='';
  var now = new Date();
  generatedLinks.unshift({ type:type, title:title, url:url, time:now.toLocaleTimeString([],{hour:'2-digit',minute:'2-digit'}), date:now.toLocaleDateString() });
  renderLinks();
  showToast(title+' link generated!','🔗');
}

function renderLinks(){
  var el = $('links-list');
  if(!generatedLinks.length){
    el.innerHTML = '<div class="empty-state"><div class="ei">🔗</div><div class="et">No links generated yet</div><div class="es">Use the panels above to generate links</div></div>';
    return;
  }
  var tagBg  = { DEMO:'#eff4ff', DOUBT:'#f5f3ff', LIVE:'#d1fae5' };
  var tagCol = { DEMO:'var(--accent)', DOUBT:'var(--accent2)', LIVE:'var(--accent3)' };
  var html='';
  for(var i=0;i<generatedLinks.length;i++){
    var l = generatedLinks[i];
    html += '<div class="lh-item">';
    html += '<span class="lh-tag" style="background:'+(tagBg[l.type]||'#f3f4f6')+';color:'+(tagCol[l.type]||'var(--text2)')+'">'+l.type+'</span>';
    html += '<span class="lh-title">'+l.title+'</span>';
    html += '<span class="lh-url">'+l.url+'</span>';
    html += '<span class="lh-time">'+l.time+'</span>';
    html += '<button class="copy-btn" onclick="copyLink(this,\''+l.url+'\')">Copy</button>';
    html += '</div>';
  }
  el.innerHTML = html;
}

function copyLink(btn, url){
  if(navigator.clipboard) navigator.clipboard.writeText(url).catch(function(){});
  btn.textContent = 'Copied!';
  setTimeout(function(){ btn.textContent = 'Copy'; }, 2000);
  showToast('Link copied to clipboard!','📋');
}

function clearLinks(){
  generatedLinks = [];
  renderLinks();
  showToast('Links history cleared');
}

/* ══════════════════════════════════════════════
   ACADEMIC SYSTEM (Assignment / Mini / Main)
══════════════════════════════════════════════ */

function switchAcadCourse(section, el, course){
  acadState[section].course = course;
  var tabsId = section==='assignment'?'asn-course-tabs':section==='miniproject'?'mini-course-tabs':'main-course-tabs';
  var tabs = $(tabsId).children;
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  el.classList.add('on');
  renderAcadContent(section);
}

function switchAcadSubTab(section, el, panelId){
  var subTabIds = { assignment:['asgn-list','asgn-eval'], miniproject:['mini-list','mini-eval'], mainproject:['mainp-list','mainp-eval'] };
  var ids = subTabIds[section];
  for(var i=0;i<ids.length;i++){ var p=$(ids[i]); if(p) p.classList.remove('on'); }
  var tabs = el.parentNode.children;
  for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
  el.classList.add('on');
  acadState[section].subTab = panelId;
  var tp = $(panelId);
  if(tp) tp.classList.add('on');
  renderAcadContent(section);
}

function renderAcadContent(section){
  var state = acadState[section];
  var course = state.course;
  /* Render upload list */
  var uploads = ACAD_UPLOADS[section][course] || [];
  var listId = section==='assignment'?'assignment-upload-list':section==='miniproject'?'miniproject-upload-list':'mainproject-upload-list';
  var el = $(listId);
  if(el){
    if(!uploads.length){
      el.innerHTML = '<div class="empty-state"><div class="ei">📤</div><div class="et">No uploads yet</div><div class="es">Click Upload to add new content</div></div>';
    } else {
      var html='';
      for(var i=0;i<uploads.length;i++){
        var u = uploads[i];
        html += '<div class="upload-strip">';
        html += '<div style="display:flex;align-items:flex-start;justify-content:space-between">';
        html += '<div><div class="us-title">'+u.title+(u.projectType?' <span style="font-size:10px;background:#eef2ff;color:#4f46e5;padding:1px 7px;border-radius:20px;font-weight:600;vertical-align:middle">'+u.projectType+'</span>':'')+'</div>';
        html += '<div class="us-meta">';
        html += '<span>📦 '+u.batchCode+'</span><span>📋 '+u.batchType+'</span><span>📘 '+u.module+'</span>';
        html += '<span>📅 Uploaded: '+u.uploadedDate+'</span>';
        if(u.count) html += '<span>📝 Count: '+u.count+'</span>';
        html += '</div>';
        html += '<div style="font-size:11.5px;color:var(--text3);margin-top:5px">'+u.desc+'</div>';
        html += '</div>';
        html += '<div class="us-deadline">⏰ Due: '+u.deadline+'</div>';
        html += '</div></div>';
      }
      el.innerHTML = html;
    }
  }

  /* Render evaluation list */
  var subs = ACAD_SUBMISSIONS[section][course] || [];
  var evalListId = section==='assignment'?'assignment-eval-list':section==='miniproject'?'miniproject-eval-list':'mainproject-eval-list';
  var el2 = $(evalListId);
  if(el2){
    if(!subs.length){
      el2.innerHTML = '<div class="empty-state"><div class="ei">🎯</div><div class="et">No submissions yet</div><div class="es">Students haven\'t submitted yet</div></div>';
    } else {
      var html='';
      for(var i=0;i<subs.length;i++){
        var sub = subs[i];
        html += '<div class="eval-strip-wrap" id="esw-'+sub.id+'">';
        html += '<div class="eval-strip" onclick="toggleEvalExpand(\''+sub.id+'\')">';
        /* name+batch */
        html += '<div><div class="sname">'+sub.stuName+'</div><div class="semail">'+sub.batchCode+'</div></div>';
        html += '<div style="font-size:11.5px;color:var(--text3)">'+sub.batchType+'</div>';
        /* upload status */
        var usCls = sub.uploadStatus==='reuploaded'?'status-reuploaded':'status-uploaded';
        var usLbl = sub.uploadStatus==='reuploaded'?'🔄 Reuploaded':'📤 Uploaded';
        html += '<div><span class="pill '+usCls+'">'+usLbl+'</span></div>';
        html += '<div style="font-size:11.5px;color:var(--text3)">'+sub.module+'</div>';
        html += '<div style="font-size:12px;color:var(--text2)">'+sub.submittedDate+'</div>';
        /* download */
        html += '<div><span title="Download" style="cursor:pointer;font-size:16px;color:var(--accent)">⬇️</span></div>';
        /* evaluate button */
        if(sub.evaluated){
          html += '<div><span class="pill status-evaluated">✓ Evaluated</span></div>';
          html += '<div><button class="btn btn-ghost btn-xs" onclick="event.stopPropagation();openEvalModal(\''+sub.id+'\',\''+section+'\')">✏️ Edit</button></div>';
        } else {
          html += '<div></div>';
          html += '<div><button class="btn btn-pri btn-xs" onclick="event.stopPropagation();openEvalModal(\''+sub.id+'\',\''+section+'\')">🎯 Evaluate</button></div>';
        }
        html += '</div>'; /* /eval-strip */
        /* expandable eval detail */
        html += '<div class="eval-strip-expand'+(sub.evaluated&&sub.expanded?' show':'')+'" id="ese-'+sub.id+'">';
        if(sub.evaluated){
          html += '<div class="eval-expand-grid">';
          html += '<div class="eeg-item"><div class="eeg-val" style="color:var(--su)">'+sub.score+'</div><div class="eeg-lbl">Score</div></div>';
          html += '<div class="eeg-item"><div class="eeg-val"><span class="gchip g-'+(sub.grade?sub.grade.replace('+','p').toLowerCase():'n')+'">'+sub.grade+'</span></div><div class="eeg-lbl">Grade</div></div>';
          html += '<div class="eeg-item" style="grid-column:span 2"><div style="font-size:12px;color:var(--text2);text-align:left;padding:6px 0">💬 '+sub.feedback+'</div><div class="eeg-lbl">Feedback</div></div>';
          html += '</div>';
        } else {
          html += '<div style="font-size:12px;color:var(--text3)">Not yet evaluated</div>';
        }
        html += '</div>';
        html += '</div>'; /* /eval-strip-wrap */
      }
      el2.innerHTML = html;
    }
  }
}

function toggleEvalExpand(subId){
  var expandEl = $('ese-'+subId);
  if(!expandEl) return;
  expandEl.classList.toggle('show');
}

function openUploadPopup(section){
  currentUploadType = section;
  var titles = { assignment:'Upload Assignment', miniproject:'Upload Mini Project', mainproject:'Upload Main Project' };
  $('upload-m-title').textContent = titles[section];
  $('upload-m-sub').textContent = 'Course: '+COURSES[acadState[section].course].name;

  var hasProjectType = section !== 'assignment';
  var typeOpts = section==='miniproject' ?
    '<option>Mini Project 1</option><option>Mini Project 2</option><option>Mini Project 3</option>' :
    '<option>Review 1</option><option>Review 2</option><option>Review 3</option>';

  var batchOpts = '<option value="">— Select batch type —</option><option>Weekday</option><option>Weekend</option><option>Fast Track</option>';
  var codeOpts  = '<option value="">— Select batch code —</option>';
  var course = acadState[section].course;
  var cBatches = BATCHES.filter(function(b){ return b.course===course; });
  for(var i=0;i<cBatches.length;i++) codeOpts += '<option>'+cBatches[i].code+'</option>';

  var modOpts = '<option value="">— Select module —</option>';
  for(var i=1;i<=20;i++) modOpts += '<option>Module '+i+'</option>';

  var html='';
  html += '<div class="fg"><label class="flabel">Title *</label><input class="finp" id="up-title" placeholder="e.g. React Hooks Challenge"></div>';
  html += '<div class="fg"><label class="flabel">Description</label><textarea class="fta" id="up-desc" placeholder="Assignment instructions…"></textarea></div>';
  if(hasProjectType){
    html += '<div class="fg"><label class="flabel">Project Type *</label><select class="fsel" id="up-ptype">'+typeOpts+'</select></div>';
  }
  html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">';
  html += '<div class="fg"><label class="flabel">Batch Type *</label><select class="fsel" id="up-btype">'+batchOpts+'</select></div>';
  html += '<div class="fg"><label class="flabel">Batch Code *</label><select class="fsel" id="up-bcode">'+codeOpts+'</select></div>';
  html += '</div>';
  html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">';
  html += '<div class="fg"><label class="flabel">Module *</label><select class="fsel" id="up-module">'+modOpts+'</select></div>';
  html += '<div class="fg"><label class="flabel">Assignment Count</label><input class="finp" id="up-count" type="number" min="1" value="1" placeholder="1"></div>';
  html += '</div>';
  html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">';
  html += '<div class="fg"><label class="flabel">Uploaded Date</label><input class="finp" id="up-date" type="date" value="'+new Date().toISOString().split('T')[0]+'"></div>';
  html += '<div class="fg"><label class="flabel">Deadline Date *</label><input class="finp" id="up-deadline" type="date"></div>';
  html += '</div>';
  $('upload-m-body').innerHTML = html;
  openOv('ov-upload');
}

function submitUpload(){
  var title = $('up-title').value.trim();
  var desc  = $('up-desc').value.trim();
  var btype = $('up-btype').value;
  var bcode = $('up-bcode').value;
  var mod   = $('up-module').value;
  var cnt   = $('up-count').value || 1;
  var dt    = $('up-date').value;
  var dl    = $('up-deadline').value;
  var ptype = $('up-ptype') ? $('up-ptype').value : null;

  if(!title){ showToast('Please enter a title','⚠️'); return; }
  if(!btype){ showToast('Please select batch type','⚠️'); return; }
  if(!bcode){ showToast('Please select batch code','⚠️'); return; }
  if(!mod){   showToast('Please select a module','⚠️'); return; }
  if(!dl){    showToast('Please set a deadline','⚠️'); return; }

  var section = currentUploadType;
  var course  = acadState[section].course;
  var id = 'U'+Date.now();
  var newUp = { id:id, title:title, desc:desc, batchType:btype, batchCode:bcode, module:mod, uploadedDate:new Date().toLocaleDateString(), count:parseInt(cnt), deadline:dl };
  if(ptype) newUp.projectType = ptype;
  ACAD_UPLOADS[section][course].push(newUp);

  closeOv('ov-upload');
  renderAcadContent(section);
  showToast(title+' uploaded successfully!','📤');
}

/* Evaluate modal */
function openEvalModal(subId, section){
  currentEvalSubId = subId;
  currentEvalType  = section;

  var course = acadState[section].course;
  var subs = ACAD_SUBMISSIONS[section][course];
  var sub = null;
  for(var i=0;i<subs.length;i++) if(subs[i].id===subId){ sub=subs[i]; break; }
  if(!sub) return;

  $('eval-m-title').textContent = (sub.evaluated?'Edit Evaluation':'Evaluate Submission');
  $('eval-m-sub').textContent   = sub.stuName+' · '+sub.batchCode;

  var rubrics = [
    { name:'Functionality', max:40 },
    { name:'Code Quality',  max:30 },
    { name:'UI / UX',       max:20 },
    { name:'Documentation', max:10 }
  ];
  if(section==='miniproject') rubrics=[{ name:'Design & Creativity',max:30 },{ name:'Implementation',max:40 },{ name:'Presentation',max:30 }];
  if(section==='mainproject') rubrics=[{ name:'Planning',max:20 },{ name:'Implementation',max:40 },{ name:'Testing',max:20 },{ name:'Deployment',max:10 },{ name:'Presentation',max:10 }];

  var html='';
  html += '<div style="background:var(--al);border-radius:var(--rs);padding:12px;margin-bottom:14px">';
  html += '<div style="font-size:13px;font-weight:700;color:var(--p)">'+sub.stuName+'</div>';
  html += '<div style="font-size:11px;color:var(--mu);margin-top:2px">'+sub.batchCode+' · '+sub.module+' · Submitted: '+sub.submittedDate+'</div>';
  html += '</div>';
  html += '<div class="fg"><label class="flabel">Score Breakdown</label>';
  for(var i=0;i<rubrics.length;i++){
    var prefill = (sub.evaluated && sub.score) ? Math.round(sub.score * rubrics[i].max / 100) : '';
    html += '<div class="rubric-row">';
    html += '<span style="flex:1;color:var(--text)">'+rubrics[i].name+'</span>';
    html += '<span style="font-size:11px;color:var(--text3)">/ '+rubrics[i].max+' pts</span>';
    html += '<input class="rubric-inp" id="rb-'+i+'" type="number" min="0" max="'+rubrics[i].max+'" placeholder="0" value="'+prefill+'" oninput="calcEvalTotal('+JSON.stringify(rubrics.map(function(r){return r.max;}))+',\'ets\')">';
    html += '</div>';
  }
  html += '</div>';
  html += '<div class="fg"><div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-top:.5px solid var(--bd)">';
  html += '<span style="font-size:13px;font-weight:700;color:var(--p)">Total Score</span>';
  html += '<span id="ets" style="font-family:\'Playfair Display\',serif;font-size:22px;font-weight:800;color:var(--su)">'+(sub.score||0)+' / 100</span>';
  html += '</div></div>';
  html += '<div class="fg"><label class="flabel">Grade</label><select class="fsel" id="eval-grade">';
  html += '<option value="A+">A+ (90–100) — Exceptional</option>';
  html += '<option value="A">A (80–89) — Excellent</option>';
  html += '<option value="B+" selected>B+ (70–79) — Good</option>';
  html += '<option value="B">B (60–69) — Average</option>';
  html += '<option value="C">C (50–59) — Below Average</option>';
  html += '<option value="F">F (Below 50) — Fail / Resubmit</option>';
  html += '</select></div>';
  html += '<div class="fg"><label class="flabel">Feedback to Student</label>';
  html += '<textarea class="fta" id="eval-feedback" placeholder="Write detailed, constructive feedback…">'+(sub.feedback||'')+'</textarea></div>';

  $('eval-m-body').innerHTML = html;
  openOv('ov-eval');
}

function calcEvalTotal(maxArr, dispId){
  var total=0;
  for(var i=0;i<maxArr.length;i++){
    var inp=$('rb-'+i);
    if(inp) total += Math.min(parseInt(inp.value)||0, maxArr[i]);
  }
  var d=$(dispId);
  if(d) d.textContent = Math.min(total,100)+' / 100';
}

function submitEval(){
  if(!currentEvalSubId) return;
  var section = currentEvalType;
  var course  = acadState[section].course;
  var subs = ACAD_SUBMISSIONS[section][course];
  var sub = null;
  for(var i=0;i<subs.length;i++) if(subs[i].id===currentEvalSubId){ sub=subs[i]; break; }
  if(!sub){ closeOv('ov-eval'); return; }

  var total=0;
  var j=0;
  while($('rb-'+j)){
    total += parseInt($('rb-'+j).value)||0;
    j++;
  }
  if(total===0){ showToast('Please enter scores','⚠️'); return; }
  sub.score    = Math.min(total,100);
  sub.grade    = $('eval-grade').value;
  sub.feedback = $('eval-feedback').value;
  sub.evaluated= true;
  sub.expanded  = true;

  closeOv('ov-eval');
  renderAcadContent(section);
  showToast('Evaluation saved! Score: '+sub.score+'/100','✅');
}

/* ══════════════════════════════════════════════
   GLOBAL SEARCH
══════════════════════════════════════════════ */

function doSearch(q){
  q = q.trim().toLowerCase();
  if(!q) return;
  var matches = STUDENTS.filter(function(s){
    return s.name.toLowerCase().indexOf(q)>-1 || s.email.toLowerCase().indexOf(q)>-1 || s.batch.toLowerCase().indexOf(q)>-1 || s.id.toLowerCase().indexOf(q)>-1;
  });
  if(matches.length){
    navTo('students');
    currentStuCourse='all';
    currentStuBatch='all';
    $('batch-subfilter').style.display='none';
    var html='';
    for(var i=0;i<matches.length;i++) html += buildStudentRow(matches[i]);
    $('stu-list').innerHTML = html;
    var tabs=$('stu-tabs').children;
    for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on');
    tabs[0].classList.add('on');
  }
}

/* ══════════════════════════════════════════════
   CLOSE OVERLAYS ON OUTSIDE CLICK
══════════════════════════════════════════════ */
document.addEventListener('click', function(e){
  if(e.target.classList.contains('overlay')) e.target.classList.remove('show');
});

/* ══════════════════════════════════════════════
   INIT
══════════════════════════════════════════════ */
function init(){
  navTo('dashboard');
  renderDashboard();
  renderBatches();
  renderStudents('all','all');
  initSlots();
  renderSlotPanel('demo');
  renderSlotPanel('doubt');
  renderSessionLists();
  renderLinks();
  populateLinkDropdowns();
  renderAcadContent('assignment');
  renderAcadContent('miniproject');
  renderAcadContent('mainproject');
}

init();

/* ════════════════════════════════════════════
   NEW V3 HELPERS
════════════════════════════════════════════ */

/* Find student by name for click-through */
function findStudentByName(name){
  for(var i=0;i<STUDENTS.length;i++) if(STUDENTS[i].name===name) return STUDENTS[i];
  return null;
}

/* Course color helper */
function courseColor(name){
  return name==='Full Stack'?'#4f46e5':name==='UI/UX Design'?'#be185d':name==='Digital Marketing'?'#b45309':'#059669';
}

/* ════════════════════════════════════════════
   CLICKABLE NAMES: patch renderDashSessions
════════════════════════════════════════════ */
var _origRenderDashSessions = renderDashSessions;
renderDashSessions = function(){
  var demoHtml='';
  for(var i=0;i<DEMO_PRELOAD.length;i++){
    var d=DEMO_PRELOAD[i]; var col=courseColor(d.course);
    var stu=findStudentByName(d.name);
    var nameEl=stu?'<span onclick="viewStudent(\''+stu.id+'\')" style="font-size:13px;font-weight:600;color:var(--accent);cursor:pointer;text-decoration:underline">'+d.name+'</span>':'<div class="ss-name">'+d.name+'</div>';
    demoHtml+='<div class="session-strip"><div class="ss-av" style="background:'+col+'">'+d.name[0]+'</div>';
    demoHtml+='<div>'+nameEl+'<div class="ss-meta">📱 '+d.phone+' · '+d.course+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    demoHtml+='<div class="ss-status ss-demo-booked">Demo Booked</div></div>';
  }
  $('dash-demo-list').innerHTML=demoHtml;

  var doubHtml='';
  for(var i=0;i<DOUBT_PRELOAD.length;i++){
    var d=DOUBT_PRELOAD[i]; if(d.di!==0) continue; var col=courseColor(d.course);
    var stu=findStudentByName(d.name);
    var nameEl=stu?'<span onclick="viewStudent(\''+stu.id+'\')" style="font-size:13px;font-weight:600;color:var(--accent);cursor:pointer;text-decoration:underline">'+d.name+'</span>':'<div class="ss-name">'+d.name+'</div>';
    doubHtml+='<div class="session-strip"><div class="ss-av" style="background:'+col+'">'+d.name[0]+'</div>';
    doubHtml+='<div>'+nameEl+'<div class="ss-meta">📱 '+d.phone+' · '+d.course+' · '+SLOT_TIMES[d.ti]+'</div></div>';
    doubHtml+='<div class="ss-status ss-doubt-booked">Doubt Booked</div></div>';
  }
  $('dash-doubt-list').innerHTML=doubHtml;
};

/* Patch activity strips */
var _origSwitchDashActivity = switchDashActivity;
switchDashActivity = function(el, course){
  if(el){ var tabs=$('dash-act-tabs').children; for(var i=0;i<tabs.length;i++) tabs[i].classList.remove('on'); el.classList.add('on'); }
  var acts=ACTIVITIES[course]||[]; var html='';
  if(!acts.length){ html='<div class="empty-state"><div class="ei">📭</div><div class="et">No activity today</div></div>'; }
  else {
    for(var i=0;i<acts.length;i++){
      var a=acts[i]; var co=COURSES[course];
      var stu=findStudentByName(a.name);
      var nameEl=stu?'<div onclick="viewStudent(\''+stu.id+'\')" style="font-size:12.5px;font-weight:600;color:var(--accent);cursor:pointer">'+a.name+'</div>':'<div style="font-size:12.5px;font-weight:600;color:var(--text)">'+a.name+'</div>';
      html+='<div class="activity-strip"><div class="act-av" style="background:'+co.color+'">'+a.name[0]+'</div>';
      html+='<div class="act-info">'+nameEl+'<div class="act-detail">'+a.activity+'</div></div>';
      html+='<div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0">';
      html+='<span class="act-badge" style="background:#eef2ff;color:#4f46e5">'+a.batchCode+'</span>';
      html+='<span class="act-badge" style="background:var(--card2);color:var(--text3)">'+a.batchType+'</span>';
      html+='</div></div>';
    }
  }
  $('dash-activity-list').innerHTML=html;
};

/* ════════════════════════════════════════════
   SESSION LISTS: dates + clickable names + filter
════════════════════════════════════════════ */
var _sessionRawData = {};

var _origRenderSessionLists = renderSessionLists;
renderSessionLists = function(){
  var cc={'Full Stack':'#4f46e5','UI/UX Design':'#be185d','Digital Marketing':'#b45309','Data Science':'#059669','Data Analytics':'#059669'};

  var ad=[];
  for(var i=0;i<DEMO_PRELOAD.length;i++){
    var d=DEMO_PRELOAD[i];
    ad.push({name:d.name,phone:d.phone,course:d.course,date:DATE_LABELS[d.di],time:SLOT_TIMES[d.ti],status:'Demo Booked'});
  }
  _sessionRawData['demo-assign']=ad;

  var cd=[];
  for(var i=0;i<COMPLETED_DEMOS.length;i++){
    var d=COMPLETED_DEMOS[i];
    cd.push({name:d.name,phone:d.phone,course:d.course,date:d.demoDate,time:d.slotTime,status:'Demo Completed'});
  }
  _sessionRawData['demo-comp']=cd;

  var ado=[];
  for(var i=0;i<DOUBT_PRELOAD.length;i++){
    var d=DOUBT_PRELOAD[i];
    ado.push({name:d.name,phone:d.phone,course:d.course,topic:d.issue,date:DATE_LABELS[d.di],time:SLOT_TIMES[d.ti],status:'Doubt Booked'});
  }
  _sessionRawData['doubt-assign']=ado;

  var cdo=[];
  for(var i=0;i<COMPLETED_DOUBTS.length;i++){
    var d=COMPLETED_DOUBTS[i];
    cdo.push({name:d.name,phone:d.phone,course:d.course,topic:d.topic||'',date:d.doubtDate,time:d.slotTime,status:'Doubt Completed'});
  }
  _sessionRawData['doubt-comp']=cdo;

  renderSList('demo-assign','assigned-demo-list',_sessionRawData['demo-assign'],cc);
  renderSList('demo-comp','completed-demo-list',_sessionRawData['demo-comp'],cc);
  renderSList('doubt-assign','assigned-doubt-list',_sessionRawData['doubt-assign'],cc);
  renderSList('doubt-comp','completed-doubt-list',_sessionRawData['doubt-comp'],cc);
};

function renderSList(key,listId,data,cc){
  if(!data||!data.length){var el=$(listId);if(el)el.innerHTML='<div class="empty-state"><div class="ei">📭</div><div class="et">No sessions found</div></div>';return;}
  var isAssigned=(key==='demo-assign'||key==='doubt-assign');
  var html='';
  for(var i=0;i<data.length;i++){
    var d=data[i]; var col=cc[d.course]||'#4f46e5';
    var sbg=d.status==='Demo Booked'?'background:#dbeafe;color:#1e40af':d.status==='Doubt Booked'?'background:#f5f3ff;color:#5b21b6':'background:var(--sub);color:#166534';
    var stu=findStudentByName(d.name);
    var nameStyle=stu?'color:var(--accent);cursor:pointer;text-decoration:underline':'color:var(--text)';
    var nameClick=stu?' onclick="viewStudent(\''+stu.id+'\')"':'';
    var connectTitle=d.name+' — '+d.course+(d.topic?' ('+d.topic+')':'')+' — '+d.date+' '+d.time;
    html+='<div class="booked-strip">';
    html+='<div class="bs-av" style="background:'+col+'">'+d.name[0]+'</div>';
    html+='<div class="bs-info"><div class="bs-name" style="'+nameStyle+'"'+nameClick+'>'+d.name+'</div>';
    html+='<div class="bs-meta">'+d.course+(d.topic?' · '+d.topic:'')+' · 📅 '+d.date+' · ⏰ '+d.time+'</div></div>';
    html+='<div class="bs-phone">📱 '+d.phone+'</div>';
    html+='<div class="bs-status" style="padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;flex-shrink:0;'+sbg+'">'+d.status+'</div>';
    if(isAssigned){
      html+='<button onclick="openConnectPopup(\''+d.name+'\',\''+connectTitle.replace(/'/g,"&#39;")+'\')" style="margin-left:8px;padding:5px 12px;border-radius:7px;border:1px solid var(--accent);background:#eff4ff;color:var(--accent);font-size:11px;font-weight:700;cursor:pointer;flex-shrink:0">🔗 Connect</button>';
    }
    html+='</div>';
  }
  var el=$(listId); if(el) el.innerHTML=html;
}

function filterSessionList(key){
  var sEl=$(key+'-search'), dEl=$(key+'-date');
  var q=sEl?sEl.value.trim().toLowerCase():'';
  var dv=dEl?dEl.value:'';
  var raw=_sessionRawData[key]||[];
  var cc={'Full Stack':'#4f46e5','UI/UX Design':'#be185d','Digital Marketing':'#b45309','Data Science':'#059669','Data Analytics':'#059669'};
  var listIds={'demo-assign':'assigned-demo-list','demo-comp':'completed-demo-list','doubt-assign':'assigned-doubt-list','doubt-comp':'completed-doubt-list'};
  var filtered=raw.filter(function(d){
    var mt=!q||(d.name.toLowerCase().indexOf(q)>-1||d.course.toLowerCase().indexOf(q)>-1||(d.topic&&d.topic.toLowerCase().indexOf(q)>-1));
    var md=!dv||dateMatch(d.date,dv);
    return mt&&md;
  });
  renderSList(key,listIds[key],filtered,cc);
}

function dateMatch(dispDate,isoVal){
  try{var d=new Date(dispDate);return d.toISOString().split('T')[0]===isoVal;}catch(e){return false;}
}

function quickFilter(key,type){
  if(type==='today'){
    var today=new Date().toISOString().split('T')[0];
    var de=$(key+'-date'); if(de) de.value=today;
    filterSessionList(key);
  }
}
function clearSessionFilter(key){
  var se=$(key+'-search'), de=$(key+'-date');
  if(se) se.value=''; if(de) de.value='';
  var cc={'Full Stack':'#4f46e5','UI/UX Design':'#be185d','Digital Marketing':'#b45309','Data Science':'#059669','Data Analytics':'#059669'};
  var listIds={'demo-assign':'assigned-demo-list','demo-comp':'completed-demo-list','doubt-assign':'assigned-doubt-list','doubt-comp':'completed-doubt-list'};
  renderSList(key,listIds[key],_sessionRawData[key]||[],cc);
}

/* Patch renderSlotAside for clickable names */
var _origRenderSlotAside = renderSlotAside;
renderSlotAside = function(type){
  var asideId=type+'-aside'; var html=''; var count=0;
  for(var di=0;di<DATE_LABELS.length;di++){
    for(var si=0;si<SLOT_TIMES.length;si++){
      var sl=slotStore[type][di][si];
      if(sl){
        count++;
        var stu=findStudentByName(sl.name);
        var nc=stu?' onclick="viewStudent(\''+stu.id+'\')" style="cursor:pointer;color:var(--accent)"':'';
        html+='<div class="asgnd-item"><div class="asgnd-name"'+nc+'>'+sl.name+'</div>';
        html+='<div class="asgnd-meta">'+sl.course+(sl.issue?' · '+sl.issue:'')+'</div>';
        html+='<div><span class="time-tag">'+DATE_LABELS[di]+'</span><span class="time-tag">'+SLOT_TIMES[si]+'</span></div>';
        html+='</div>';
      }
    }
  }
  if(!count) html='<div class="empty-state" style="padding:20px"><div class="ei">📭</div><div class="et">No sessions assigned yet</div></div>';
  $(asideId).innerHTML=html;
};

/* ════════════════════════════════════════════
   STUDENT DETAIL V3: stats in LMS, sessions below
════════════════════════════════════════════ */
var _origViewStudent = viewStudent;
viewStudent = function(id){
  currentStuId=id;
  navTo('detail');
  var s=STUDENTS.find(function(x){return x.id===id;}); if(!s) return;
  var lms=getLMS(id); var ci=BATCH_COURSE_INFO[s.course]; var co=COURSES[s.course]; var ac=attColor(s.att);

  /* LEFT PANEL */
  var left='<div class="hero-card"><div class="hero-banner"></div><div class="hero-body">';
  left+='<div class="hero-av" style="background:'+co.color+'">'+s.name[0]+'</div>';
  left+='<div class="hero-name">'+s.name+'</div>';
  left+='<div class="hero-id">'+s.id+' · <span style="color:'+co.color+'">'+ci.icon+' '+ci.name+'</span></div>';
  left+='<div style="margin-top:10px"><span class="pill p-'+(s.status==='active'?'active':s.status==='inactive'?'inactive':'pending')+'">'+s.status+'</span></div>';
  left+='<div class="info-list" style="margin-top:12px">';
  left+=infoRow('📧 Email',s.email); left+=infoRow('📱 Phone',s.phone);
  left+=infoRow('🗂 Batch',s.batch); left+=infoRow('📅 Joined',s.joined);
  left+=infoRow('💳 Fee',s.fee);
  left+=infoRow('📊 Attendance','<span style="color:'+ac+';font-weight:700">'+s.att+'%</span>');
  left+='</div></div></div>';
  left+='<button class="btn btn-danger" style="width:100%;margin-top:12px" onclick="openAssign(\''+id+'\',\''+s.name+'\')">⚠️ Assign Lead to Staff</button>';
  left+='<div style="font-size:11px;color:var(--text3);text-align:center;margin-top:6px">Assign to Telecaller or Support Executive</div>';

  /* RIGHT — LMS */
  var graded=lms.assigns.filter(function(a){return a.score!==null;}).length;
  var mDone=lms.mini.filter(function(m){return m.st==='ok';}).length;

  var right='<div class="card card-pad">';
  right+='<div class="card-head"><div class="card-title">📚 LMS History <span class="cbadge">'+s.name.split(' ')[0]+'\'s Progress</span></div></div>';
  right+='<div class="lms-tabs">';
  right+='<button class="lms-tab on" onclick="switchLms(this,\'lms-ov\')">Overview</button>';
  right+='<button class="lms-tab" onclick="switchLms(this,\'lms-att\')">Attendance</button>';
  right+='<button class="lms-tab" onclick="switchLms(this,\'lms-asgn\')">Assignments</button>';
  right+='<button class="lms-tab" onclick="switchLms(this,\'lms-mini\')">Mini Projects</button>';
  right+='<button class="lms-tab" onclick="switchLms(this,\'lms-main\')">Main Project</button>';
  right+='</div>';

  /* OVERVIEW — stat cards moved HERE */
  right+='<div class="lms-panel on" id="lms-ov">';
  right+='<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-bottom:14px">';
  right+=v3MiniStat('📊','Progress',lms.pct+'%',co.color);
  right+=v3MiniStat('📅','Attendance',s.att+'%',ac);
  right+=v3MiniStat('📘','Modules',lms.done+'/'+lms.total,'#24b8ec');
  right+=v3MiniStat('📋','Assignments',graded+'/'+lms.assigns.length,'#7c3aed');
  right+=v3MiniStat('🔧','Mini Proj',mDone+'/'+lms.mini.length,'#059669');
  right+='</div>';
  right+='<div style="height:5px;background:var(--border);border-radius:99px;overflow:hidden;margin-bottom:14px"><div style="width:'+lms.pct+'%;height:100%;border-radius:99px;background:'+co.color+'"></div></div>';
  right+='<div style="font-size:12px;font-weight:700;color:var(--p);margin-bottom:8px">📘 Module Progress</div>';
  right+='<div style="max-height:220px;overflow-y:auto">';
  for(var m=0;m<lms.mods.length;m++){
    if(!lms.mods[m]) continue; var done=m<lms.done;
    right+='<div class="mod-item"><div class="mod-dot '+(done?'md-done':'md-todo')+'">'+(done?'✓':'')+'</div>';
    right+='<span style="color:'+(done?'var(--text)':'var(--text3)')+';font-weight:'+(done?600:400)+'">Module '+(m+1)+' — '+lms.mods[m]+'</span>';
    if(done) right+='<span style="margin-left:auto;font-size:10px;color:var(--su);font-weight:600">Done</span>';
    right+='</div>';
  }
  right+='</div></div>';

  /* ATTENDANCE — compact with month filter */
  var months=Object.keys(ATT_MONTHS);
  var mOpts=''; for(var mi=0;mi<months.length;mi++) mOpts+='<option'+(months[mi]==='April 2026'?' selected':'')+'>'+months[mi]+'</option>';
  right+='<div class="lms-panel" id="lms-att">';
  right+='<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">';
  right+='<div style="font-size:12px;font-weight:700;color:var(--p)">📅 Attendance Calendar</div>';
  right+='<select id="att-month-sel" onchange="switchAttMonth(this.value,\''+id+'\')" style="border:1px solid var(--border);border-radius:7px;padding:5px 10px;font-family:Outfit,sans-serif;font-size:12px;background:var(--card2);outline:none;cursor:pointer">'+mOpts+'</select>';
  right+='</div><div id="att-cal-wrap">'+buildCompactAttCal(lms,'April 2026')+'</div></div>';

  /* ASSIGNMENTS */
  right+='<div class="lms-panel" id="lms-asgn"><div style="font-size:13px;font-weight:700;color:var(--p);margin-bottom:12px">📋 Assignment Submissions</div>';
  for(var ai=0;ai<lms.assigns.length;ai++){
    var a=lms.assigns[ai]; var apct=a.score?Math.round(a.score/a.max*100):0; var acol2=apct>=75?'#16a34a':apct>=50?'#d97706':'#dc2626';
    right+='<div class="asgn-card ac-'+a.status+'">';
    right+='<div class="asgn-head"><div><div class="asgn-title">'+a.title+'</div><div class="asgn-meta">'+a.mod+' · Submitted: '+a.date+'</div></div>';
    if(a.score!==null) right+='<div class="asgn-score" style="color:'+acol2+'">'+a.score+'<span>/'+a.max+'</span></div>';
    else right+='<div style="font-size:12px;font-weight:700;color:'+(a.status==='overdue'?'#dc2626':'#d97706')+'">'+((a.status==='overdue'?'⚠ Overdue':'⏳ Pending'))+'</div>';
    right+='</div>';
    if(a.score!==null) right+='<div class="asgn-bar"><div class="asgn-track"><div class="asgn-fill" style="width:'+apct+'%;background:'+acol2+'"></div></div><span style="font-size:11px;font-weight:600;color:'+acol2+'">'+apct+'%</span></div>';
    if(a.feedback) right+='<div class="feedback-box">💬 '+a.feedback+'</div>';
    right+='</div>';
  }
  right+='</div>';

  /* MINI PROJECTS */
  right+='<div class="lms-panel" id="lms-mini"><div style="font-size:13px;font-weight:700;color:var(--p);margin-bottom:12px">◈ Mini Projects</div>';
  for(var mi=0;mi<lms.mini.length;mi++){
    var mp=lms.mini[mi]; var mpct=mp.score?Math.round(mp.score/mp.max*100):0;
    var mcls=mp.st==='ok'?'mc-ok':mp.st==='fix'?'mc-fix':'mc-wait';
    var mcol=mp.st==='ok'?'#16a34a':mp.st==='fix'?'#dc2626':'#d97706';
    var tbg=mp.st==='ok'?'#bbf7d0':mp.st==='fix'?'#fecaca':'#fde68a';
    var slabel=mp.st==='ok'?'✓ Completed':mp.st==='fix'?'⚠ Changes Needed':'⏳ Pending Review';
    right+='<div class="mini-card '+mcls+'"><div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">';
    right+='<div><div style="font-size:13px;font-weight:700;color:'+mcol+'">'+mp.title+'</div><div style="font-size:11px;color:'+mcol+';opacity:.7">'+mp.mod+' · '+mp.date+'</div></div>';
    if(mp.score!==null) right+='<div style="text-align:center"><div style="font-family:\'Playfair Display\',serif;font-size:24px;font-weight:800;color:'+mcol+'">'+mpct+'<span style="font-size:11px">%</span></div></div>';
    else right+='<div style="font-size:12px;font-weight:700;color:'+mcol+'">⏳ Pending</div>';
    right+='</div>';
    if(mp.score!==null) right+='<div style="display:flex;align-items:center;gap:6px;margin-top:6px"><div style="flex:1;height:6px;border-radius:4px;overflow:hidden;background:'+tbg+'"><div style="width:'+mpct+'%;height:100%;background:'+mcol+'"></div></div><span style="font-size:10px;font-weight:600;color:'+mcol+'">'+slabel+'</span></div>';
    if(mp.feedback) right+='<div style="font-size:11px;color:'+mcol+';margin-top:8px;opacity:.8">💬 '+mp.feedback+'</div>';
    right+='</div>';
  }
  right+='</div>';

  /* MAIN PROJECT */
  right+='<div class="lms-panel" id="lms-main">';
  right+='<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px"><div style="font-size:13px;font-weight:700;color:var(--p)">🏗 '+lms.main.title+'</div>';
  var doneRevs=lms.main.reviews.filter(function(r){return r.done;}).length;
  right+='<span class="cbadge">Review '+doneRevs+' / '+lms.main.reviews.length+'</span></div><div class="main-proj">';
  for(var ri=0;ri<lms.main.reviews.length;ri++){
    var rev=lms.main.reviews[ri];
    var rcol=rev.score>=75?'#16a34a':rev.score>=50?'#d97706':rev.score?'#dc2626':'#9ca3af';
    var rbg=rev.score>=75?'linear-gradient(90deg,#16a34a,#4ade80)':rev.score>=50?'linear-gradient(90deg,#d97706,#fbbf24)':rev.score?'linear-gradient(90deg,#dc2626,#f87171)':'';
    right+='<div class="rev-row"><div class="rev-hd"><div class="rev-lbl">'+rev.lbl+'<span style="font-size:10px;color:'+(rev.done?'#166534':'var(--mu)')+';font-weight:500"> · '+rev.date+' · '+(rev.done?'✓ Completed':'⏳ Pending')+'</span></div>';
    if(rev.score!==null) right+='<span class="rev-pct" style="color:'+rcol+'">'+rev.score+'%</span>';
    else right+='<span style="font-size:11px;color:var(--mu)">—</span>';
    right+='</div><div class="rev-track">';
    if(rev.score!==null) right+='<div class="rev-fill" style="width:'+rev.score+'%;background:'+rbg+'"><span>'+rev.score+'%</span></div>';
    else right+='<div style="width:0;height:100%"></div>';
    right+='</div><div class="rev-note">'+rev.note+'</div></div>';
  }
  right+='</div></div></div>';

  $('detail-body').innerHTML='<div>'+left+'</div><div>'+right+'</div>';
  window._currentStudentId=id;

  /* SESSIONS + ACTIVITY — below the grid */
  var demoMatch=[], doubtMatch=[];
  for(var di=0;di<DATE_LABELS.length;di++){
    for(var si=0;si<SLOT_TIMES.length;si++){
      if(slotStore.demo[di]&&slotStore.demo[di][si]&&slotStore.demo[di][si].name===s.name) demoMatch.push({date:DATE_LABELS[di],time:SLOT_TIMES[si]});
      if(slotStore.doubt[di]&&slotStore.doubt[di][si]&&slotStore.doubt[di][si].name===s.name) doubtMatch.push({date:DATE_LABELS[di],time:SLOT_TIMES[si]});
    }
  }
  for(var i=0;i<DOUBT_PRELOAD.length;i++) if(DOUBT_PRELOAD[i].name===s.name) doubtMatch.push({date:DATE_LABELS[DOUBT_PRELOAD[i].di],time:SLOT_TIMES[DOUBT_PRELOAD[i].ti]});
  for(var i=0;i<DEMO_PRELOAD.length;i++) if(DEMO_PRELOAD[i].name===s.name) demoMatch.push({date:DATE_LABELS[DEMO_PRELOAD[i].di],time:SLOT_TIMES[DEMO_PRELOAD[i].ti]});

  var md='<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:0">';
  md+='<div class="card card-pad" style="padding:14px"><div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">🎯 Demo Session</div>';
  if(demoMatch.length) md+='<div class="session-strip" style="margin-bottom:0"><div class="ss-name">'+demoMatch[0].date+' — '+demoMatch[0].time+'</div><div class="ss-status ss-demo-booked">Demo Booked</div></div>';
  else md+='<div style="font-size:12px;color:var(--text3)">No demo scheduled</div>';
  md+='</div>';
  md+='<div class="card card-pad" style="padding:14px"><div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">💬 Doubt Session</div>';
  if(doubtMatch.length) md+='<div class="session-strip" style="margin-bottom:0"><div class="ss-name">'+doubtMatch[0].date+' — '+doubtMatch[0].time+'</div><div class="ss-status ss-doubt-booked">Doubt Booked</div></div>';
  else md+='<div style="font-size:12px;color:var(--text3)">No doubt scheduled</div>';
  md+='</div></div>';

  var acts=ACTIVITIES[s.course]||[];
  var stuActs=acts.filter(function(a){return a.name===s.name;});
  if(stuActs.length){
    md+='<div class="card card-pad" style="padding:14px;margin-top:14px"><div style="font-size:12px;font-weight:700;color:var(--text2);margin-bottom:8px">⚡ Today\'s Activity</div>';
    for(var i=0;i<stuActs.length;i++){
      md+='<div class="activity-strip" style="margin-bottom:'+(i<stuActs.length-1?'8px':'0')+'">';
      md+='<div class="act-av" style="background:'+co.color+'">'+s.name[0]+'</div>';
      md+='<div class="act-info"><div class="act-detail">'+stuActs[i].activity+'</div></div></div>';
    }
    md+='</div>';
  }
  $('detail-mini-dash').innerHTML=md;
};

function v3MiniStat(ico,lbl,val,color){
  return '<div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:10px;text-align:center;position:relative;overflow:hidden"><div style="position:absolute;top:0;left:0;right:0;height:2px;background:'+color+'"></div><div style="font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;margin-bottom:3px">'+lbl+'</div><div style="font-family:\'Playfair Display\',serif;font-size:18px;font-weight:700;color:'+color+'">'+val+'</div></div>';
}

/* ─── Compact attendance calendar ─── */
function buildCompactAttCal(lms,month){
  var attData=(typeof ATT_MONTHS!=='undefined'&&ATT_MONTHS[month])?ATT_MONTHS[month]:lms.att;
  var pDays=attData.present, aDays=attData.absent;
  var pct=Math.round(pDays.length/(pDays.length+aDays.length)*100)||0;
  var html='<div style="display:flex;gap:10px;margin-bottom:10px">';
  html+='<div style="background:var(--sub);border-radius:8px;padding:8px 10px;text-align:center;flex:1"><div style="font-size:9px;color:#166534;font-weight:700;text-transform:uppercase">Present</div><div style="font-family:\'Playfair Display\',serif;font-size:18px;font-weight:800;color:#166534">'+pDays.length+'</div></div>';
  html+='<div style="background:var(--dab);border-radius:8px;padding:8px 10px;text-align:center;flex:1"><div style="font-size:9px;color:#991b1b;font-weight:700;text-transform:uppercase">Absent</div><div style="font-family:\'Playfair Display\',serif;font-size:18px;font-weight:800;color:#dc2626">'+aDays.length+'</div></div>';
  html+='<div style="background:#eff4ff;border-radius:8px;padding:8px 10px;text-align:center;flex:1"><div style="font-size:9px;color:var(--accent);font-weight:700;text-transform:uppercase">Rate</div><div style="font-family:\'Playfair Display\',serif;font-size:18px;font-weight:800;color:'+attColor(pct)+'">'+pct+'%</div></div>';
  html+='</div>';
  var dN=['Mo','Tu','We','Th','Fr','Sa','Su'];
  html+='<div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:2px">';
  for(var d=0;d<7;d++) html+='<div style="font-size:9px;color:var(--mu);text-align:center;font-weight:600;padding:2px 0">'+dN[d]+'</div>';
  html+='</div><div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px">';
  var days=month.indexOf('April')>-1?30:month.indexOf('February')>-1?28:31;
  for(var day=1;day<=days;day++){
    var cls=pDays.indexOf(day)>-1?'background:#16a34a':aDays.indexOf(day)>-1?'background:#dc2626':'background:#e9ecf5';
    var tc=pDays.indexOf(day)>-1||aDays.indexOf(day)>-1?'#fff':'#9ca3af';
    var tip=pDays.indexOf(day)>-1?'Present ✅':aDays.indexOf(day)>-1?'Absent ❌':'No Class';
    html+='<div title="'+tip+'" onclick="showToast(\'Day '+day+' — '+tip+'\')" style="aspect-ratio:1;border-radius:2px;'+cls+';display:flex;align-items:center;justify-content:center;font-size:7px;font-weight:600;color:'+tc+';cursor:pointer">'+day+'</div>';
  }
  html+='</div>';
  html+='<div style="display:flex;gap:10px;margin-top:8px;padding-top:8px;border-top:.5px solid var(--bd);align-items:center;flex-wrap:wrap">';
  html+='<div style="display:flex;align-items:center;gap:4px;font-size:10px;font-weight:600;color:#16a34a"><div style="width:9px;height:9px;background:#16a34a;border-radius:2px"></div>Present: '+pDays.length+'</div>';
  html+='<div style="display:flex;align-items:center;gap:4px;font-size:10px;font-weight:600;color:#dc2626"><div style="width:9px;height:9px;background:#dc2626;border-radius:2px"></div>Absent: '+aDays.length+'</div>';
  html+='<span style="margin-left:auto;font-size:11px;font-weight:700;color:var(--p)">Min 75% ';
  html+=pct>=75?'<span style="color:#16a34a">✓ Met</span>':'<span style="color:#dc2626">✗ Below</span>';
  html+='</span></div>';
  return html;
}

function switchAttMonth(month,stuId){
  var wrap=$('att-cal-wrap');
  if(!wrap) return;
  var lms=getLMS(stuId||window._currentStudentId||'');
  wrap.innerHTML=buildCompactAttCal(lms,month);
}

/* ════════════════════════════════════════════
   MINI PROJECT REUPLOAD  +  MAIN PROJECT PER-REVIEW REUPLOAD
════════════════════════════════════════════ */
var _origRenderAcadContent = renderAcadContent;
renderAcadContent = function(section){
  _origRenderAcadContent(section);
  if(section==='miniproject') patchMiniEvalReupload(section);
  if(section==='mainproject') renderMainProjEval(section);
};

function patchMiniEvalReupload(section){
  var course=acadState[section].course;
  var subs=ACAD_SUBMISSIONS[section][course]||[];
  var evalListId='miniproject-eval-list';
  var el=$(evalListId); if(!el||!subs.length) return;
  var html='';
  for(var i=0;i<subs.length;i++){
    var sub=subs[i];
    var usCls=sub.uploadStatus==='reuploaded'?'status-reuploaded':'status-uploaded';
    var usLbl=sub.uploadStatus==='reuploaded'?'🔄 Reuploaded':'📤 Uploaded';
    var stu=findStudentByName(sub.stuName);
    var nc=stu?' onclick="event.stopPropagation();viewStudent(\''+stu.id+'\')" style="color:var(--accent);cursor:pointer"':'';
    html+='<div class="eval-strip-wrap" id="esw-'+sub.id+'">';
    html+='<div class="eval-strip" onclick="toggleEvalExpand(\''+sub.id+'\')">';
    html+='<div><div class="sname"'+nc+'>'+sub.stuName+'</div><div class="semail">'+sub.batchCode+'</div></div>';
    html+='<div style="font-size:11.5px;color:var(--text3)">'+sub.batchType+'</div>';
    html+='<div><span class="pill '+usCls+'">'+usLbl+'</span></div>';
    html+='<div style="font-size:11.5px;color:var(--text3)">'+sub.module+'</div>';
    html+='<div style="font-size:12px;color:var(--text2)">'+sub.submittedDate+'</div>';
    html+='<div><span title="Download" style="cursor:pointer;font-size:16px;color:var(--accent)">⬇️</span></div>';
    if(sub.evaluated){
      html+='<div><span class="pill status-evaluated">✓ Evaluated</span></div>';
      html+='<div><button class="btn btn-ghost btn-xs" onclick="event.stopPropagation();openEvalModal(\''+sub.id+'\',\'miniproject\')">✏️ Edit</button></div>';
    } else {
      html+='<div></div>';
      html+='<div><button class="btn btn-pri btn-xs" onclick="event.stopPropagation();openEvalModal(\''+sub.id+'\',\'miniproject\')">🎯 Evaluate</button></div>';
    }
    html+='</div>';
    html+='<div class="eval-strip-expand'+(sub.evaluated&&sub.expanded?' show':'')+'" id="ese-'+sub.id+'">';
    if(sub.evaluated){
      html+='<div class="eval-expand-grid">';
      html+='<div class="eeg-item"><div class="eeg-val" style="color:var(--su)">'+sub.score+'</div><div class="eeg-lbl">Score</div></div>';
      html+='<div class="eeg-item"><div class="eeg-val"><span class="gchip g-'+(sub.grade?sub.grade.replace('+','p').toLowerCase():'n')+'">'+sub.grade+'</span></div><div class="eeg-lbl">Grade</div></div>';
      html+='<div class="eeg-item" style="grid-column:span 2"><div style="font-size:12px;color:var(--text2);text-align:left;padding:6px 0">💬 '+sub.feedback+'</div><div class="eeg-lbl">Feedback</div></div>';
      html+='</div>';
      if(!sub.reuploadUsed){
        html+='<div style="margin-top:10px;padding-top:10px;border-top:1px solid var(--border);display:flex;align-items:center;gap:10px">';
        html+='<span style="font-size:11.5px;color:var(--text2);flex:1">Student has one reupload opportunity remaining.</span>';
        html+='<button class="btn btn-ghost btn-xs" style="border-color:var(--wa);color:var(--wa)" onclick="miniReupload(\''+sub.id+'\')">🔄 Allow Reupload</button></div>';
      } else {
        html+='<div style="margin-top:8px;font-size:11px;color:var(--wa);font-weight:600">🔄 Reupload opportunity already used</div>';
      }
    } else {
      html+='<div style="font-size:12px;color:var(--text3)">Not yet evaluated</div>';
      if(sub.uploadStatus==='uploaded'&&!sub.reuploadUsed){
        html+='<div style="margin-top:8px"><button class="btn btn-ghost btn-xs" onclick="miniReupload(\''+sub.id+'\')">🔄 Request Reupload</button></div>';
      }
    }
    html+='</div></div>';
  }
  el.innerHTML=html;
}

function miniReupload(subId){
  var course=acadState['miniproject'].course;
  var subs=ACAD_SUBMISSIONS['miniproject'][course];
  for(var i=0;i<subs.length;i++) if(subs[i].id===subId){subs[i].reuploadUsed=true;subs[i].uploadStatus='reuploaded';break;}
  patchMiniEvalReupload('miniproject');
  showToast('Reupload opportunity granted','🔄');
}

function renderMainProjEval(section){
  var course=acadState[section].course;
  var subs=ACAD_SUBMISSIONS[section][course]||[];
  var el=$('mainproject-eval-list'); if(!el) return;
  if(!subs.length){ el.innerHTML='<div class="empty-state"><div class="ei">🎯</div><div class="et">No submissions yet</div></div>'; return; }
  var html='';
  for(var i=0;i<subs.length;i++){
    var sub=subs[i]; if(!sub.reviews) continue;
    var stu=findStudentByName(sub.stuName);
    var nc=stu?' onclick="viewStudent(\''+stu.id+'\')" style="color:var(--accent);cursor:pointer"':'';
    html+='<div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:16px;box-shadow:var(--shadow)">';
    html+='<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:var(--surface);border-bottom:1px solid var(--border)">';
    html+='<div style="width:36px;height:36px;border-radius:8px;background:#4f46e5;display:flex;align-items:center;justify-content:center;font-family:\'Playfair Display\',serif;font-weight:700;font-size:14px;color:#fff;flex-shrink:0">'+sub.stuName[0]+'</div>';
    html+='<div style="flex:1"><div class="sname"'+nc+'>'+sub.stuName+'</div><div class="semail">'+sub.batchCode+' · '+sub.batchType+'</div></div>';
    html+='<span style="font-size:11.5px;color:var(--text3)">'+sub.module+'</span>';
    html+='<span style="font-size:11.5px;color:var(--text3)">'+sub.submittedDate+'</span>';
    html+='</div>';
    html+='<div style="padding:10px 16px 14px;background:#f8faff">';
    for(var ri=0;ri<sub.reviews.length;ri++){
      var rev=sub.reviews[ri];
      var rusCls=rev.uploadStatus==='reuploaded'?'status-reuploaded':rev.uploadStatus==='pending'?'':' status-uploaded';
      var rusLbl=rev.uploadStatus==='reuploaded'?'🔄 Reuploaded':rev.uploadStatus==='pending'?'⏳ Pending':'📤 Uploaded';
      var rusBg=rev.uploadStatus==='reuploaded'?'background:var(--wab);color:#92400e':rev.uploadStatus==='pending'?'background:#f3f4f6;color:#6b7280':'background:#dbeafe;color:#1e40af';
      html+='<div style="display:flex;align-items:center;gap:8px;padding:9px 10px;background:var(--card2);border:1px solid var(--border);border-radius:8px;margin-bottom:6px">';
      html+='<div style="font-size:12px;font-weight:700;color:var(--p);flex:0 0 76px">'+rev.label+'</div>';
      html+='<div style="flex:1;min-width:0"><div class="sname" style="font-size:12px"'+nc+'>'+sub.stuName+'</div><div class="semail">'+sub.batchCode+'</div></div>';
      html+='<span class="pill" style="'+rusBg+'">'+rusLbl+'</span>';
      if(rev.evaluated&&rev.score!==null) html+='<span style="font-family:\'Playfair Display\',serif;font-size:16px;font-weight:800;color:var(--su);flex-shrink:0">'+rev.score+'<span style="font-size:10px">%</span></span>';
      else if(rev.uploadStatus!=='pending') html+='<span style="font-size:11px;color:var(--text3);flex-shrink:0">Not evaluated</span>';
      html+='<div style="display:flex;gap:5px;flex-shrink:0">';
      if(rev.uploadStatus!=='pending'){
        if(!rev.evaluated) html+='<button class="btn btn-pri btn-xs" onclick="openMainRevEval(\''+sub.id+'\','+ri+',\'mainproject\')">🎯 Evaluate</button>';
        else html+='<button class="btn btn-ghost btn-xs" onclick="openMainRevEval(\''+sub.id+'\','+ri+',\'mainproject\')">✏️ Edit</button>';
        if(!rev.reuploaded_once&&rev.uploadStatus==='uploaded') html+='<button class="btn btn-ghost btn-xs" style="border-color:var(--wa);color:var(--wa)" onclick="markRevReupload(\''+sub.id+'\','+ri+')">🔄 Reupload</button>';
        else if(rev.reuploaded_once) html+='<span style="font-size:10px;color:var(--wa);font-weight:600">Used</span>';
      }
      html+='</div></div>';
    }
    html+='</div></div>';
  }
  el.innerHTML=html;
}

var _mainRevCtx={};
function openMainRevEval(subId,ri,section){
  _mainRevCtx={subId:subId,ri:ri,section:section};
  var course=acadState[section].course;
  var subs=ACAD_SUBMISSIONS[section][course];
  var sub=null; for(var i=0;i<subs.length;i++) if(subs[i].id===subId){sub=subs[i];break;} if(!sub) return;
  var rev=sub.reviews[ri];
  currentEvalSubId='__main_rev__'; currentEvalType=section;
  $('eval-m-title').textContent=(rev.evaluated?'Edit ':'Evaluate ')+rev.label;
  $('eval-m-sub').textContent=sub.stuName+' · '+sub.batchCode;
  var rubrics=[{name:'Planning',max:20},{name:'Implementation',max:40},{name:'Testing',max:20},{name:'Deployment',max:10},{name:'Presentation',max:10}];
  var html='<div style="background:var(--al);border-radius:var(--rs);padding:12px;margin-bottom:14px">';
  html+='<div style="font-size:13px;font-weight:700;color:var(--p)">'+sub.stuName+' — '+rev.label+'</div>';
  html+='<div style="font-size:11px;color:var(--mu);margin-top:2px">'+sub.batchCode+' · '+sub.module+'</div></div>';
  html+='<div class="fg"><label class="flabel">Score Breakdown</label>';
  for(var i=0;i<rubrics.length;i++){
    var pf=(rev.evaluated&&rev.score)?Math.round(rev.score*rubrics[i].max/100):'';
    html+='<div class="rubric-row"><span style="flex:1;color:var(--text)">'+rubrics[i].name+'</span>';
    html+='<span style="font-size:11px;color:var(--text3)">/ '+rubrics[i].max+' pts</span>';
    html+='<input class="rubric-inp" id="rb-'+i+'" type="number" min="0" max="'+rubrics[i].max+'" placeholder="0" value="'+pf+'" oninput="calcEvalTotal('+JSON.stringify(rubrics.map(function(r){return r.max;}))+',\'ets\')"></div>';
  }
  html+='</div><div class="fg"><div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-top:.5px solid var(--bd)">';
  html+='<span style="font-size:13px;font-weight:700;color:var(--p)">Total Score</span>';
  html+='<span id="ets" style="font-family:\'Playfair Display\',serif;font-size:22px;font-weight:800;color:var(--su)">'+(rev.score||0)+' / 100</span></div></div>';
  html+='<div class="fg"><label class="flabel">Feedback</label><textarea class="fta" id="eval-feedback" placeholder="Write feedback…"></textarea></div>';
  $('eval-m-body').innerHTML=html; openOv('ov-eval');
}

function markRevReupload(subId,ri){
  var section='mainproject'; var course=acadState[section].course;
  var subs=ACAD_SUBMISSIONS[section][course];
  for(var i=0;i<subs.length;i++) if(subs[i].id===subId&&subs[i].reviews){
    subs[i].reviews[ri].reuploaded_once=true; subs[i].reviews[ri].uploadStatus='reuploaded'; break;
  }
  renderMainProjEval(section);
  showToast('Reupload allowed for Review '+(ri+1),'🔄');
}

/* Patch submitEval to handle main review */
var _origSubmitEval = submitEval;
submitEval = function(){
  if(currentEvalSubId==='__main_rev__'){
    var ctx=_mainRevCtx; var course=acadState[ctx.section].course;
    var subs=ACAD_SUBMISSIONS[ctx.section][course];
    var sub=null; for(var i=0;i<subs.length;i++) if(subs[i].id===ctx.subId){sub=subs[i];break;} if(!sub){closeOv('ov-eval');return;}
    var total=0; var j=0; while($('rb-'+j)){total+=parseInt($('rb-'+j).value)||0;j++;}
    if(total===0){showToast('Please enter scores','⚠️');return;}
    sub.reviews[ctx.ri].score=Math.min(total,100); sub.reviews[ctx.ri].evaluated=true;
    closeOv('ov-eval'); renderMainProjEval(ctx.section);
    showToast('Review '+sub.reviews[ctx.ri].label+' evaluated: '+Math.min(total,100)+'/100','✅');
    return;
  }
  _origSubmitEval();
};

/* Re-run init patches after page load */
(function(){
  renderDashSessions();
  switchDashActivity(null,'fs');
  renderSessionLists();
  populateMeetingStudents();
})();

/* ════════════════════════════════════════════
   CONNECT POPUP (Student session — demo/doubt/dashboard)
════════════════════════════════════════════ */
function openConnectPopup(name, title){
  $('connect-popup-name').textContent = name;
  $('connect-popup-av').textContent = name[0];
  $('connect-popup-title').value = title || '';
  $('connect-popup-link-wrap').style.display = 'none';
  $('connect-popup-link-val').value = '';
  openOv('ov-connect');
}

function doConnect(){
  var title = $('connect-popup-title').value.trim();
  if(!title){ showToast('Please enter a session title','⚠️'); return; }
  var link = 'https://meet.google.com/' + Math.random().toString(36).substr(2,10);
  $('connect-popup-link-val').value = link;
  $('connect-popup-link-wrap').style.display = '';
  showToast('Meeting link generated — share with student!','📹');
}

function copyConnectLink(){
  var v = $('connect-popup-link-val').value;
  if(navigator.clipboard && v) navigator.clipboard.writeText(v).catch(function(){});
  showToast('Link copied!','📋');
}

/* ════════════════════════════════════════════
   BATCH CONNECT POPUP
════════════════════════════════════════════ */
function openBatchConnectPopup(batchCode, autoTitle){
  $('batch-connect-code').textContent = batchCode;
  $('batch-connect-sub').textContent = 'Start a group session for batch ' + batchCode;
  $('batch-connect-title').value = autoTitle || '';
  $('batch-connect-link-wrap').style.display = 'none';
  $('batch-connect-link-val').value = '';
  openOv('ov-batch-connect');
}

function doBatchConnect(){
  var title = $('batch-connect-title').value.trim();
  if(!title){ showToast('Please enter a session title','⚠️'); return; }
  var link = 'https://meet.google.com/' + Math.random().toString(36).substr(2,10);
  $('batch-connect-link-val').value = link;
  $('batch-connect-link-wrap').style.display = '';
  showToast('Batch meeting link generated!','📹');
}

function copyBatchConnectLink(){
  var v = $('batch-connect-link-val').value;
  if(navigator.clipboard && v) navigator.clipboard.writeText(v).catch(function(){});
  showToast('Link copied!','📋');
}

/* ════════════════════════════════════════════
   MEETINGS — EVALUATION CLASS LINK
════════════════════════════════════════════ */
function populateMeetingStudents(){
  var sel = $('eval-link-student');
  if(!sel) return;
  var opts = '<option value="">— Select student —</option>';
  for(var i=0;i<STUDENTS.length;i++){
    var s = STUDENTS[i];
    opts += '<option value="'+s.id+'">'+s.name+' · '+COURSES[s.course].name+' · '+s.batch+'</option>';
  }
  sel.innerHTML = opts;
}

var _evalLinkGenerated = false;

function genEvalLink(){
  var title = $('eval-link-title').value.trim();
  var stuId = $('eval-link-student').value;
  if(!title){ showToast('Please enter a session title','⚠️'); return; }
  if(!stuId){ showToast('Please select a student','⚠️'); return; }
  var link = 'https://meet.google.com/' + Math.random().toString(36).substr(2,10);
  $('eval-link-url-display').value = link;
  $('eval-link-url-wrap').style.display = '';
  $('eval-connect-btn').disabled = false;
  $('eval-connect-btn').style.opacity = '1';
  _evalLinkGenerated = true;
  showToast('Evaluation meeting link generated!','📹');
}

function copyEvalLink(){
  var v = $('eval-link-url-display').value;
  if(navigator.clipboard && v) navigator.clipboard.writeText(v).catch(function(){});
  showToast('Link copied!','📋');
}

function connectEvalLink(){
  if(!_evalLinkGenerated){ showToast('Please generate a link first','⚠️'); return; }
  var link = $('eval-link-url-display').value;
  if(link){ window.open(link,'_blank'); }
  showToast('Joining evaluation session…','📹');
}

</script>
</body>
</html>
