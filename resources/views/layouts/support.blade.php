<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Support CRM</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    /* ── DARK THEME ── */
    --bg:#0a0d18;
    --bg-2:#0f1320;
    --surface:#131724;
    --surface-2:#181d2c;
    --card2:#1a1f2e;
    --hover:#1e2336;
    --border:rgba(255,255,255,0.07);
    --border2:rgba(255,255,255,0.13);
    --text:#ffffff;
    --text2:rgba(255,255,255,0.68);
    --text3:rgba(255,255,255,0.42);
    --accent:#4d8eff;
    --accent-soft:rgba(77,142,255,0.15);
    --accent2:#a78bfa;
    --accent3:#34d399;
    --gold:#fbbf24;
    --red:#f87171;
    --shadow:0 1px 3px rgba(0,0,0,0.3),0 4px 16px rgba(0,0,0,0.2);
    --shadow2:0 4px 12px rgba(0,0,0,0.5),0 12px 36px rgba(0,0,0,0.4);
    /* Tag colors — call tags */
    --hot-bg:rgba(248,113,113,0.15);--hot-fg:#fc6c8a;
    --warm-bg:rgba(251,191,36,0.13);--warm-fg:#f59e0b;
    --cold-bg:rgba(96,165,250,0.15);--cold-fg:#60a5fa;
    /* Direction pills */
    --out-bg:rgba(167,139,250,0.18);--out-fg:#c4b5fd;
    --in-bg:rgba(52,211,153,0.18);--in-fg:#6ee7b7;
  }
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'Outfit',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;font-size:14px;-webkit-font-smoothing:antialiased;}
  .shell{display:flex;min-height:100vh;}

  /* ── SIDEBAR ── */
  .sidebar{width:240px;background:var(--bg-2);border-right:1px solid var(--border);display:flex;flex-direction:column;padding:24px 0;position:sticky;top:0;height:100vh;flex-shrink:0;overflow-y:auto;}
  .logo-wrap{display:flex;align-items:center;gap:11px;padding:0 22px 22px;border-bottom:1px solid var(--border);margin-bottom:18px;}
  .logo-mark{width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:11px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:17px;color:#fff;flex-shrink:0;}
  .logo-text{font-family:'Playfair Display',serif;font-weight:700;font-size:17px;color:var(--text);line-height:1.1;}
  .logo-text small{display:block;font-family:'Outfit',sans-serif;font-size:10px;font-weight:500;color:var(--text3);margin-top:3px;letter-spacing:0.06em;}
  .nav-item{display:flex;align-items:center;gap:13px;padding:11px 22px;cursor:pointer;transition:all 0.18s;color:var(--text2);font-size:13.5px;font-weight:500;position:relative;}
  .nav-item:hover{background:var(--hover);color:var(--text);}
  .nav-item.active{background:var(--accent-soft);color:var(--accent);font-weight:600;}
  .nav-item.active::before{content:'';position:absolute;left:0;top:6px;bottom:6px;width:3px;border-radius:0 3px 3px 0;background:var(--accent);}
  .nav-item .ni-ic{font-size:16px;width:20px;text-align:center;flex-shrink:0;}
  .nav-item .label{flex:1;}
  .nav-badge{background:var(--accent);color:#fff;border-radius:20px;padding:2px 8px;font-size:10px;font-weight:700;}
  .nav-badge.red{background:var(--red);}

  .sidebar-bottom{margin-top:auto;border-top:1px solid var(--border);padding-top:14px;}
  .exec-chip{margin:8px 14px 0;background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:11px;display:flex;align-items:center;gap:10px;}
  .exec-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;}
  .exec-name{font-size:12.5px;font-weight:600;color:var(--text);line-height:1.2;}
  .exec-role{font-size:10px;color:var(--text3);margin-top:2px;}

  /* ── MAIN ── */
  .main{flex:1;padding:26px 36px 60px;overflow-x:hidden;min-width:0;}
  .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;}
  .page-title-wrap{display:flex;flex-direction:column;}
  .page-crumb{font-size:11px;color:var(--text3);font-weight:500;letter-spacing:0.04em;}
  .page-title{font-family:'Playfair Display',serif;font-size:23px;font-weight:700;color:var(--text);margin-top:2px;}
  .page-title span{color:var(--text3);font-weight:400;font-size:14px;margin-left:8px;font-family:'Outfit',sans-serif;}
  .topbar-right{display:flex;gap:10px;align-items:center;}
  .search-bar{background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:9px 14px;display:flex;align-items:center;gap:9px;width:280px;font-size:13px;}
  .search-bar input{border:none;outline:none;background:transparent;flex:1;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);}
  .search-bar input::placeholder{color:var(--text3);}
  .search-bar .ic{color:var(--text3);}
  .notif-btn{width:42px;height:42px;border-radius:11px;background:var(--surface);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:all 0.2s;font-size:16px;color:var(--text2);}
  .notif-btn:hover{border-color:var(--accent);color:var(--accent);}
  .notif-dot{position:absolute;top:9px;right:9px;width:8px;height:8px;border-radius:50%;background:var(--red);border:2px solid var(--bg);}

  /* ── SECTIONS ── */
  .section-view{display:none;animation:fadein 0.25s ease;}
  .section-view.active{display:block;}
  @keyframes fadein{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}

  /* ── STATS GRID ── */
  .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px;}
  .stat-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 16px;position:relative;overflow:hidden;transition:transform 0.18s,border-color 0.18s;cursor:pointer;}
  .stat-card:hover{transform:translateY(-2px);border-color:var(--border2);}
  .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
  .stat-card.blue::before{background:var(--accent);}
  .stat-card.purple::before{background:var(--accent2);}
  .stat-card.gold::before{background:var(--gold);}
  .stat-card.green::before{background:var(--accent3);}
  .stat-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;margin-bottom:9px;}
  .stat-card.blue .stat-icon{background:rgba(77,142,255,0.15);color:var(--accent);}
  .stat-card.purple .stat-icon{background:rgba(167,139,250,0.15);color:var(--accent2);}
  .stat-card.gold .stat-icon{background:rgba(251,191,36,0.15);color:var(--gold);}
  .stat-card.green .stat-icon{background:rgba(52,211,153,0.15);color:var(--accent3);}
  .stat-num{font-family:'Playfair Display',serif;font-size:23px;font-weight:700;color:var(--text);line-height:1;}
  .stat-label{font-size:11.5px;color:var(--text2);margin-top:5px;font-weight:500;}
  .stat-trend{font-size:10px;color:var(--accent3);font-weight:600;margin-top:6px;}
  .stat-trend.down{color:var(--red);}
  .stat-breakdown{margin-top:9px;padding-top:8px;border-top:1px solid var(--border);display:flex;gap:10px;flex-wrap:wrap;}
  .stat-breakdown .item{font-size:10px;color:var(--text3);font-weight:500;display:flex;align-items:center;gap:4px;}
  .stat-breakdown .item .num{font-family:'Outfit',sans-serif;font-size:10.5px;font-weight:700;color:var(--text);}
  .stat-breakdown .item .dot{width:5px;height:5px;border-radius:50%;}
  .stat-breakdown .item .dot.tech{background:var(--accent);}
  .stat-breakdown .item .dot.nontech{background:var(--accent2);}

  /* ── TODAY RAISERS ── */
  .raiser-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .raiser-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;}
  .raiser-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
  .raiser-title{font-size:13.5px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px;}
  .raiser-pill{font-size:10.5px;font-weight:600;padding:4px 10px;border-radius:20px;}
  .raiser-pill.tech{background:rgba(77,142,255,0.15);color:var(--accent);}
  .raiser-pill.nontech{background:rgba(167,139,250,0.15);color:var(--accent2);}
  .raiser-list{display:flex;flex-direction:column;gap:8px;max-height:280px;overflow-y:auto;}
  .raiser-row{display:flex;align-items:center;gap:11px;padding:11px;border-radius:10px;background:var(--card2);border:1px solid transparent;transition:all 0.15s;cursor:pointer;}
  .raiser-row:hover{background:var(--hover);border-color:var(--border2);}
  .mini-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;}
  .raiser-info{flex:1;min-width:0;}
  .raiser-name{font-size:13px;font-weight:600;color:var(--text);}
  .raiser-q{font-size:11.5px;color:var(--text3);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
  .raiser-time{font-size:11px;color:var(--text3);font-weight:500;flex-shrink:0;font-variant-numeric:tabular-nums;}

  /* ── TAB ROW ── */
  .tab-row{display:inline-flex;gap:5px;background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:5px;}
  .tab-pill{padding:8px 16px;border-radius:7px;font-size:12.5px;font-weight:600;color:var(--text2);cursor:pointer;transition:all 0.18s;display:flex;align-items:center;gap:7px;border:none;background:transparent;font-family:'Outfit',sans-serif;}
  .tab-pill:hover{color:var(--text);}
  .tab-pill.active{background:var(--accent);color:#fff;}
  .tab-pill .cnt{background:rgba(0,0,0,0.2);border-radius:20px;padding:1px 7px;font-size:10px;font-weight:700;}
  .tab-pill.active .cnt{background:rgba(255,255,255,0.22);}

  /* ── SUB TAB ROW (status filter under main tabs) ── */
  .sub-tab-row{display:flex;gap:6px;flex-wrap:wrap;}
  .sub-tab-pill{padding:7px 13px;border-radius:8px;font-size:11.5px;font-weight:600;color:var(--text3);background:var(--surface);border:1px solid var(--border);cursor:pointer;transition:all 0.15s;display:flex;align-items:center;gap:7px;font-family:'Outfit',sans-serif;}
  .sub-tab-pill:hover{color:var(--text);border-color:var(--border2);}
  .sub-tab-pill.active{background:var(--accent-soft);color:var(--accent);border-color:rgba(77,142,255,0.35);}
  .sub-tab-pill .cnt{background:rgba(255,255,255,0.06);border-radius:11px;padding:1px 6px;font-size:10px;font-weight:700;color:var(--text3);}
  .sub-tab-pill.active .cnt{background:var(--accent);color:#fff;}

  /* Status pill on rows */
  .status-pill{display:inline-flex;padding:3px 10px;border-radius:6px;font-size:10.5px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;}
  .status-pill.new{background:rgba(96,165,250,0.15);color:#60a5fa;}
  .status-pill.opened{background:rgba(251,191,36,0.15);color:var(--gold);}
  .status-pill.pending{background:rgba(244,114,182,0.15);color:#f472b6;}
  .status-pill.inreview{background:rgba(167,139,250,0.15);color:var(--accent2);}
  .status-pill.resolved{background:rgba(52,211,153,0.15);color:var(--accent3);}

  /* Prev section toggle button */
  .prev-toggle-btn{background:transparent;border:1px solid var(--border);color:var(--text3);font-size:11px;cursor:pointer;padding:6px 10px;border-radius:7px;transition:all 0.15s;font-family:'Outfit',sans-serif;display:inline-flex;align-items:center;gap:6px;}
  .prev-toggle-btn:hover{background:var(--hover);color:var(--text);border-color:var(--border2);}

  /* ── RIBBON ── */
  .ribbon{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .ribbon-head{display:grid;background:var(--bg-2);padding:13px 18px;font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.06em;border-bottom:1px solid var(--border);}
  .ribbon-row{display:grid;padding:14px 18px;align-items:center;border-bottom:1px solid var(--border);transition:background 0.15s;cursor:pointer;}
  .ribbon-row:last-child{border-bottom:none;}
  .ribbon-row:hover{background:var(--hover);}
  .cell-name{display:flex;align-items:center;gap:11px;min-width:0;}
  .cell-name-text{font-size:13.5px;font-weight:600;color:var(--text);}
  .cell-name-sub{font-size:11px;color:var(--text3);margin-top:2px;}
  .cell-mono{font-family:'Outfit',sans-serif;font-size:13px;color:var(--text2);font-variant-numeric:tabular-nums;}
  .desc-cell{font-size:12.5px;color:var(--text2);line-height:1.45;padding-right:8px;}
  .desc-clickable{cursor:pointer;display:inline-flex;align-items:center;gap:6px;border-radius:6px;padding:2px 0;transition:color 0.15s;}
  .desc-clickable:hover{color:var(--accent);}
  .desc-clickable .read-more{font-size:10.5px;color:var(--accent);font-weight:600;flex-shrink:0;background:var(--accent-soft);padding:2px 7px;border-radius:5px;}

  /* ── BUTTONS ── */
  .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:9px;font-family:'Outfit',sans-serif;font-size:12.5px;font-weight:600;cursor:pointer;border:none;transition:all 0.18s;white-space:nowrap;}
  .btn-primary{background:var(--accent);color:#fff;}
  .btn-primary:hover{background:#3b7bff;transform:translateY(-1px);}
  .btn-ghost{background:var(--surface);color:var(--text2);border:1px solid var(--border2);}
  .btn-ghost:hover{border-color:var(--accent);color:var(--accent);background:var(--surface-2);}
  .btn-success{background:var(--accent3);color:#0a0d18;}
  .btn-success:hover{background:#10b981;}
  .btn-danger{background:rgba(248,113,113,0.15);color:var(--red);}
  .btn-danger:hover{background:var(--red);color:#fff;}
  .btn-mini{padding:6px 11px;font-size:11.5px;border-radius:7px;}
  .btn-edit{background:rgba(251,146,60,0.15);color:#fb923c;border:1px solid rgba(251,146,60,0.25);}
  .btn-edit:hover{background:rgba(251,146,60,0.22);}

  .attach-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 9px;background:var(--accent-soft);color:var(--accent);border-radius:7px;font-size:11px;font-weight:500;cursor:pointer;text-decoration:none;}
  .attach-chip:hover{background:rgba(77,142,255,0.22);}

  /* ── PROFILE / STUDENT DETAIL ── */
  .back-btn{display:inline-flex;align-items:center;gap:7px;color:var(--text3);font-size:13px;font-weight:500;cursor:pointer;margin-bottom:16px;padding:6px 0;transition:color 0.15s;}
  .back-btn:hover{color:var(--accent);}

  .profile-card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:28px;margin-bottom:22px;}
  .profile-head{display:flex;align-items:flex-start;gap:18px;margin-bottom:24px;flex-wrap:wrap;}
  .avatar-lg{width:74px;height:74px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:800;font-size:28px;color:#fff;flex-shrink:0;}
  .profile-main-info{flex:1;min-width:200px;}
  .profile-name{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--text);margin-bottom:8px;}
  .profile-tagline{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .lead-pill{padding:3px 10px;border-radius:6px;font-size:10.5px;font-weight:700;letter-spacing:0.04em;}
  .lead-pill.hot{background:var(--hot-bg);color:var(--hot-fg);}
  .lead-pill.warm{background:var(--warm-bg);color:var(--warm-fg);}
  .lead-pill.cold{background:var(--cold-bg);color:var(--cold-fg);}
  .lead-id{font-size:13px;color:var(--text2);font-weight:600;letter-spacing:0.02em;}
  .lead-assigned{font-size:13px;color:var(--text3);}
  .lead-assigned .self{color:var(--accent);font-weight:600;}
  .profile-actions{display:flex;gap:9px;flex-shrink:0;}

  .info-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
  .info-card{background:var(--card2);border:1px solid var(--border);border-radius:12px;padding:16px 18px;}
  .info-label{font-size:10.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.07em;}
  .info-value{font-size:14px;font-weight:600;color:var(--text);margin-top:6px;}

  /* ── DETAIL TABS ── */
  .detail-tab-wrap{background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
  .detail-tabs{display:flex;border-bottom:1px solid var(--border);background:var(--bg-2);}
  .detail-tab{flex:1;padding:14px 20px;background:transparent;border:none;font-family:'Outfit',sans-serif;font-size:13.5px;font-weight:600;color:var(--text3);cursor:pointer;transition:all 0.18s;border-bottom:3px solid transparent;display:flex;align-items:center;justify-content:center;gap:8px;}
  .detail-tab:hover{color:var(--text);}
  .detail-tab.active{color:var(--accent);border-bottom-color:var(--accent);background:var(--surface);}
  .detail-tab-body{padding:24px;display:none;}
  .detail-tab-body.active{display:block;}

  /* ── TIMELINE / CALL HISTORY ── */
  .timeline-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px;}
  .timeline-title{font-size:17px;font-weight:700;color:var(--text);display:flex;align-items:center;gap:10px;}
  .timeline-title .clock-emoji{font-size:18px;}
  .timeline-sub{font-size:12.5px;color:var(--text3);margin-left:8px;font-weight:500;}

  .call-section{margin-top:18px;}
  .call-section-head{padding:12px 4px;display:flex;align-items:center;justify-content:space-between;}
  .call-section-label{font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.1em;display:flex;align-items:center;gap:8px;}
  .call-section-label.current{color:var(--accent);}
  .call-section-label .live-dot{width:8px;height:8px;border-radius:50%;background:var(--accent);animation:pulse 1.6s infinite;}
  @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.4;transform:scale(1.3)}}
  .call-section-sub{font-size:12px;color:var(--text3);font-weight:400;text-transform:none;letter-spacing:0;margin-left:4px;}

  .call-table{background:var(--bg-2);border:1px solid var(--border);border-radius:11px;overflow:hidden;margin-top:8px;}
  .call-table-head{display:grid;grid-template-columns:0.9fr 1fr 1.1fr 0.7fr 0.7fr 0.9fr 1.6fr;padding:13px 18px;font-size:10.5px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.07em;border-bottom:1px solid var(--border);}
  .call-table-row{display:grid;grid-template-columns:0.9fr 1fr 1.1fr 0.7fr 0.7fr 0.9fr 1.6fr;padding:16px 18px;align-items:center;}
  .call-time-cell{font-family:'Outfit',sans-serif;font-size:13.5px;color:var(--text);font-weight:600;}
  .call-time-cell .date{font-size:11px;color:var(--text3);font-weight:500;display:block;margin-top:2px;}

  .dir-pill{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:7px;font-size:11px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;}
  .dir-pill.outgoing{background:var(--out-bg);color:var(--out-fg);}
  .dir-pill.incoming{background:var(--in-bg);color:var(--in-fg);}

  .play-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;background:rgba(52,211,153,0.12);border:1px solid rgba(52,211,153,0.22);border-radius:8px;font-size:12px;font-weight:600;color:var(--accent3);cursor:pointer;transition:all 0.15s;}
  .play-pill:hover{background:rgba(52,211,153,0.18);}
  .play-pill .dur{color:var(--text3);font-weight:500;font-size:11px;}

  .tag-pill{display:inline-flex;padding:4px 11px;border-radius:6px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;}
  .tag-pill.warm{background:var(--warm-bg);color:var(--warm-fg);}
  .tag-pill.hot{background:var(--hot-bg);color:var(--hot-fg);}
  .tag-pill.cold{background:var(--cold-bg);color:var(--cold-fg);}

  .tag-select{background:var(--surface);border:1px solid var(--border2);border-radius:7px;padding:6px 12px;font-family:'Outfit',sans-serif;font-size:12px;color:var(--text);cursor:pointer;outline:none;font-weight:600;width:fit-content;}
  .tag-select:focus{border-color:var(--accent);}

  .notes-cell{background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:10px 13px;font-size:12.5px;color:var(--text2);line-height:1.5;}
  .notes-cell.editable{display:flex;flex-direction:column;gap:8px;padding:0;border:none;background:transparent;}
  .notes-cell.editable .notes-input{background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:10px 13px;font-family:'Outfit',sans-serif;font-size:12.5px;color:var(--text);resize:none;min-height:54px;outline:none;}
  .notes-cell.editable .notes-input:focus{border-color:var(--accent);}

  /* ── LMS ACCORDION ── */
  .lms-accordion{display:flex;flex-direction:column;gap:10px;}
  .lms-acc{background:var(--bg-2);border:1px solid var(--border);border-radius:11px;overflow:hidden;transition:border-color 0.18s;}
  .lms-acc:hover{border-color:var(--border2);}
  .lms-acc.open{border-color:var(--accent);}
  .lms-acc-head{padding:16px 18px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;gap:12px;}
  .lms-acc-title{display:flex;align-items:center;gap:11px;flex:1;min-width:0;}
  .lms-acc-icon{width:34px;height:34px;border-radius:9px;background:var(--accent-soft);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
  .lms-acc-title-text{font-size:13.5px;font-weight:600;color:var(--text);}
  .lms-acc-title-sub{font-size:11.5px;color:var(--text3);margin-top:2px;}
  .lms-acc-meta{display:flex;align-items:center;gap:14px;}
  .lms-acc-stat{font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:var(--text);line-height:1;}
  .lms-acc-stat.green{color:var(--accent3);}
  .lms-acc-stat.gold{color:var(--gold);}
  .lms-acc-chev{font-size:11px;color:var(--text3);transition:transform 0.2s;}
  .lms-acc.open .lms-acc-chev{transform:rotate(180deg);}
  .lms-acc-body{display:none;padding:0 18px 18px;border-top:1px solid var(--border);}
  .lms-acc.open .lms-acc-body{display:block;}

  .progress-bar-wrap{background:rgba(255,255,255,0.05);border-radius:6px;height:8px;overflow:hidden;margin-top:14px;}
  .progress-bar{height:100%;border-radius:6px;background:linear-gradient(90deg,var(--accent),var(--accent2));transition:width 0.4s;}
  .progress-bar.green{background:linear-gradient(90deg,var(--accent3),#10b981);}
  .progress-bar.gold{background:linear-gradient(90deg,var(--gold),#f59e0b);}

  .module-row{display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid var(--border);font-size:12.5px;}
  .module-row:last-child{border-bottom:none;}
  .module-name{color:var(--text);font-weight:500;}
  .module-status{font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;}
  .module-status.done{background:rgba(52,211,153,0.15);color:var(--accent3);}
  .module-status.todo{background:rgba(255,255,255,0.05);color:var(--text3);}
  .module-status.late{background:rgba(248,113,113,0.15);color:var(--red);}
  .module-status.missed{background:rgba(248,113,113,0.15);color:var(--red);}
  .module-status.upcoming{background:var(--accent-soft);color:var(--accent);}

  /* ── DOUBT ALLOCATION ── */
  .alloc-wrap{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px;}
  .alloc-table{width:100%;border-collapse:separate;border-spacing:8px;}
  .alloc-table th{text-align:left;font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.07em;padding:6px 10px;}
  .alloc-table .slot-label{font-size:12px;font-weight:600;color:var(--text);background:var(--card2);padding:11px 12px;border-radius:9px;font-variant-numeric:tabular-nums;width:120px;text-align:center;border:1px solid var(--border);}
  .alloc-cell{padding:10px;background:var(--bg-2);border:1.5px dashed var(--border2);border-radius:10px;cursor:pointer;transition:all 0.18s;text-align:center;min-height:64px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;}
  .alloc-cell:hover{border-color:var(--accent);background:var(--accent-soft);}
  .alloc-cell.taken{background:linear-gradient(135deg,rgba(77,142,255,0.15),rgba(167,139,250,0.15));border:1.5px solid var(--accent);cursor:not-allowed;}
  .alloc-cell.taken .alloc-name{font-size:11.5px;font-weight:700;color:var(--accent);}
  .alloc-cell.taken .alloc-sub{font-size:10px;color:var(--text3);}
  .alloc-cell.empty .plus{font-size:18px;color:var(--text3);font-weight:300;}
  .alloc-cell.empty .label{font-size:10px;color:var(--text3);font-weight:500;text-transform:uppercase;letter-spacing:0.05em;}

  .staff-head{display:flex;align-items:center;gap:9px;}
  .staff-avatar{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;font-size:12px;color:#fff;flex-shrink:0;}
  .staff-name{font-size:12.5px;font-weight:600;color:var(--text);}

  /* ── MODAL ── */
  .overlay{display:none;position:fixed;inset:0;z-index:100;background:rgba(0,0,0,0.75);backdrop-filter:blur(8px);align-items:center;justify-content:center;padding:20px;}
  .overlay.open{display:flex;animation:fadein 0.22s ease;}
  .modal{background:var(--surface);border:1px solid var(--border2);border-radius:16px;width:540px;max-width:100%;max-height:90vh;overflow-y:auto;padding:28px;animation:slideup 0.28s cubic-bezier(.22,.68,0,1.2);box-shadow:var(--shadow2);}
  @keyframes slideup{from{transform:translateY(24px);opacity:0}to{transform:none;opacity:1}}
  .modal-head{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:18px;}
  .modal-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--text);}
  .modal-sub{font-size:12.5px;color:var(--text3);margin-top:3px;}
  .close-x{width:32px;height:32px;border-radius:8px;background:var(--card2);border:1px solid var(--border);cursor:pointer;font-size:14px;color:var(--text2);display:flex;align-items:center;justify-content:center;transition:all 0.15s;}
  .close-x:hover{background:var(--hot-bg);color:var(--red);border-color:var(--red);}
  .form-row{margin-bottom:14px;}
  .form-label{font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:7px;display:block;}
  .form-input,.form-select,.form-textarea{width:100%;background:var(--bg-2);border:1px solid var(--border2);border-radius:10px;padding:10px 13px;font-family:'Outfit',sans-serif;font-size:13px;color:var(--text);outline:none;transition:border-color 0.15s;}
  .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--accent);}
  .form-input::placeholder{color:var(--text3);}
  .form-textarea{resize:vertical;min-height:74px;}
  .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .modal-footer{display:flex;gap:9px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);}

  input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(1) opacity(0.5);cursor:pointer;}
  select.form-select option{background:var(--surface);color:var(--text);}

  /* ── TOAST ── */
  .toast{position:fixed;bottom:24px;right:24px;background:var(--text);color:#0a0d18;padding:13px 18px;border-radius:11px;box-shadow:0 10px 30px rgba(0,0,0,0.5);font-size:13px;font-weight:600;z-index:200;display:none;align-items:center;gap:9px;max-width:380px;}
  .toast.show{display:flex;animation:slideup 0.3s ease;}
  .toast.success{background:var(--accent3);color:#063421;}
  .toast.warn{background:var(--gold);color:#3a2810;}
  .toast.error{background:var(--red);color:#3a0c0c;}
  .toast.alert{background:var(--accent);color:#fff;}

  /* ── CERTIFICATE ── */
  .cert-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:14px;margin-bottom:10px;transition:border-color 0.18s;}
  .cert-card:hover{border-color:var(--border2);}
  .cert-icon{width:48px;height:48px;border-radius:11px;background:linear-gradient(135deg,rgba(251,191,36,0.2),rgba(251,146,60,0.2));border:1px solid rgba(251,191,36,0.25);display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--gold);flex-shrink:0;}
  .cert-info{flex:1;min-width:0;}
  .cert-name{font-size:13.5px;font-weight:600;color:var(--text);}
  .cert-course{font-size:12px;color:var(--text2);margin-top:2px;}
  .cert-meta{display:flex;gap:11px;margin-top:5px;}
  .cert-meta span{font-size:11px;color:var(--text3);display:flex;align-items:center;gap:4px;}
  .cert-status{font-size:10.5px;font-weight:700;padding:4px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:0.05em;}
  .cert-status.ready{background:rgba(52,211,153,0.15);color:var(--accent3);}
  .cert-status.pending{background:rgba(251,191,36,0.15);color:var(--gold);}
  .cert-status.issued{background:var(--accent-soft);color:var(--accent);}

  /* ── FOLLOW UP ── */
  .followup-banner{background:linear-gradient(135deg,rgba(251,191,36,0.1),rgba(251,146,60,0.1));border:1px solid rgba(251,191,36,0.25);border-radius:12px;padding:14px 18px;display:flex;align-items:center;gap:11px;margin-bottom:18px;}
  .followup-banner .ic{font-size:22px;}
  .followup-banner-text{flex:1;font-size:13px;color:var(--gold);font-weight:500;}
  .followup-banner-text strong{font-weight:700;color:var(--text);}

  /* ══════════════ BATCH ALLOCATION ══════════════ */
  .batch-section-head{display:flex;align-items:center;gap:11px;margin-bottom:14px;}
  .batch-section-pill{font-size:10px;font-weight:700;padding:4px 9px;border-radius:6px;letter-spacing:0.06em;display:flex;align-items:center;gap:5px;}
  .batch-section-pill.live{background:var(--hot-bg);color:var(--hot-fg);}
  .batch-section-pill.live::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--hot-fg);animation:pulse 1.6s infinite;}
  .batch-section-pill.upcoming{background:var(--accent-soft);color:var(--accent);}
  .batch-section-title{font-size:17px;font-weight:700;color:var(--text);}
  .batch-section-sub{font-size:12.5px;color:var(--text3);font-weight:400;margin-left:6px;}

  .batch-grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:26px;}
  .batch-grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}

  .batch-card{background:var(--surface);border:1px solid var(--border);border-radius:11px;padding:0;overflow:hidden;transition:transform 0.18s,border-color 0.18s;display:flex;flex-direction:column;}
  .batch-card:hover{transform:translateY(-2px);border-color:var(--border2);}
  .batch-card-stripe{height:3px;width:100%;}
  .batch-card-body{padding:11px 13px;flex:1;display:flex;flex-direction:column;}
  .batch-card-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:9px;gap:6px;}
  .course-pill{font-size:9px;font-weight:700;padding:2.5px 7px;border-radius:5px;letter-spacing:0.06em;text-transform:uppercase;white-space:nowrap;}
  .live-mini-pill{font-size:8.5px;font-weight:700;padding:2px 6px;border-radius:4px;background:var(--hot-bg);color:var(--hot-fg);display:inline-flex;align-items:center;gap:4px;letter-spacing:0.05em;}
  .live-mini-pill::before{content:'';width:4px;height:4px;border-radius:50%;background:var(--hot-fg);animation:pulse 1.6s infinite;}
  .upc-mini-pill{font-size:8.5px;font-weight:700;padding:2px 6px;border-radius:4px;background:var(--accent-soft);color:var(--accent);letter-spacing:0.06em;}
  .batch-name-big{font-family:'Outfit',sans-serif;font-size:14.5px;font-weight:700;color:var(--text);}
  .batch-mentor{font-size:10.5px;color:var(--text3);margin-top:2px;}
  .batch-mentor strong{color:var(--text2);font-weight:500;}

  .batch-meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:10px 0;padding:9px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);}
  .batch-meta-item .lbl{font-size:9px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:0.06em;}
  .batch-meta-item .val{font-size:11.5px;font-weight:600;color:var(--text);margin-top:2px;}

  .batch-card-foot{display:flex;align-items:center;justify-content:space-between;gap:6px;margin-top:auto;}
  .batch-students-count{font-size:10px;color:var(--text3);display:flex;align-items:center;gap:4px;}
  .batch-card-actions{display:flex;gap:5px;}
  .batch-card-actions .btn{padding:5px 9px;font-size:10px;border-radius:6px;}

  /* Course color stripes — Fullstack / UI/UX / Digital Marketing / Data Science */
  .stripe-fullstack{background:var(--accent2);}
  .stripe-uiux{background:#f472b6;}
  .stripe-dm{background:var(--gold);}
  .stripe-ds{background:var(--accent3);}

  /* Course pill colors */
  .pill-fullstack{background:rgba(167,139,250,0.15);color:var(--accent2);}
  .pill-uiux{background:rgba(244,114,182,0.15);color:#f472b6;}
  .pill-dm{background:rgba(251,191,36,0.15);color:var(--gold);}
  .pill-ds{background:rgba(52,211,153,0.15);color:var(--accent3);}

  /* Course filter row */
  .course-filter{display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;}
  .course-fil-btn{padding:8px 14px;border-radius:20px;font-size:12px;font-weight:600;color:var(--text2);background:var(--surface);border:1px solid var(--border);cursor:pointer;transition:all 0.18s;font-family:'Outfit',sans-serif;}
  .course-fil-btn:hover{color:var(--text);border-color:var(--border2);}
  .course-fil-btn.active{background:var(--accent);color:#fff;border-color:var(--accent);}

  /* RESPONSIVE */
  @media(max-width:1100px){
    .stats-grid{grid-template-columns:repeat(2,1fr);}
    .raiser-grid{grid-template-columns:1fr;}
    .info-grid{grid-template-columns:repeat(2,1fr);}
    .batch-grid-4{grid-template-columns:repeat(2,1fr);}
    .batch-grid-2{grid-template-columns:1fr;}
    .call-table-head,.call-table-row{grid-template-columns:0.8fr 1fr 1.1fr 0.7fr 0.7fr 0.9fr 1.4fr;font-size:11px;}
  }
  @media(max-width:760px){
    .sidebar{position:fixed;left:-260px;transition:left 0.25s;z-index:50;}
    .sidebar.open{left:0;}
    .main{padding:18px 16px 50px;}
    .search-bar{display:none;}
    .stats-grid{grid-template-columns:1fr;}
    .info-grid{grid-template-columns:1fr;}
    .batch-grid-4,.batch-grid-2{grid-template-columns:1fr;}
    .call-table{overflow-x:auto;}
    .call-table-head,.call-table-row{min-width:980px;}
  }

  /* Scrollbar */
  ::-webkit-scrollbar{width:8px;height:8px;}
  ::-webkit-scrollbar-track{background:transparent;}
  ::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.1);border-radius:4px;}
  ::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,0.2);}
</style>
</head>
<body>

<div class="shell">

  <!-- ════════════ SIDEBAR ════════════ -->
  <aside class="sidebar" id="sidebar">
    <div class="logo-wrap">
      <div class="logo-mark">S</div>
      <div class="logo-text">
        Support CRM
        <small>Executive workspace</small>
      </div>
    </div>

    <div class="nav-item active" onclick="showSection('dashboard',this)">
      <span class="ni-ic">◈</span><span class="label">Dashboard</span>
    </div>
    <div class="nav-item" onclick="showSection('leads',this)">
      <span class="ni-ic">◉</span><span class="label">Leads</span>
      <span class="nav-badge" id="nav-leads-cnt">11</span>
    </div>
    <div class="nav-item" onclick="showSection('doubt-allocation',this)">
      <span class="ni-ic">⏱</span><span class="label">Doubt Allocation</span>
    </div>
    <div class="nav-item" onclick="showSection('certificates',this)">
      <span class="ni-ic">⌬</span><span class="label">Certificates</span>
    </div>
    <div class="nav-item" onclick="showSection('followup',this)">
      <span class="ni-ic">↻</span><span class="label">Follow Up</span>
      <span class="nav-badge" id="nav-followup-cnt">0</span>
    </div>
    <div class="nav-item" onclick="showSection('batch',this)">
      <span class="ni-ic">⚏</span><span class="label">Batch Allocation</span>
    </div>

    <div class="sidebar-bottom">
      <div class="exec-chip">
        <div class="exec-avatar">PS</div>
        <div>
          <div class="exec-name">Priya Sundar</div>
          <div class="exec-role">Support Executive</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- ════════════ MAIN ════════════ -->
  <main class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="page-title-wrap">
        <div class="page-crumb" id="page-crumb">SUPPORT CRM</div>
        <div class="page-title" id="page-title">Dashboard <span id="page-sub">— Today's overview</span></div>
      </div>
      <div class="topbar-right">
        <div class="search-bar">
          <span class="ic">⌕</span>
          <input type="text" placeholder="Search leads, students…" />
        </div>
        <div class="notif-btn" onclick="toast('alert','3 new leads need attention')">
          🔔<span class="notif-dot"></span>
        </div>
        <button class="btn btn-primary" onclick="openLeadModal()">＋ Add Lead</button>
      </div>
    </div>

    <!-- ════════════ DASHBOARD ════════════ -->
    
        @yield('content')
    <script>
/* ═══════════════════════ DATA ═══════════════════════ */
const STAFF = [
  {id:'s1',name:'Suresh M',color:'#4d8eff,#a78bfa',initials:'SM'},
  {id:'s2',name:'Anitha R',color:'#34d399,#22c55e',initials:'AR'},
  {id:'s3',name:'Karthik V',color:'#f87171,#fb923c',initials:'KV'},
  {id:'s4',name:'Divya P',color:'#a78bfa,#f472b6',initials:'DP'}
];

const TIME_SLOTS = ['11:30 AM','12:00 PM','12:30 PM','1:00 PM','2:00 PM','2:45 PM','3:30 PM','4:15 PM','5:00 PM','5:45 PM'];

let ALLOCATIONS = {
  's1_2':{student:'Arjun Karthikeyan',notes:'Thermodynamics doubt',date:'2026-04-27'},
  's2_4':{student:'Vignesh Kumar',notes:'SolidWorks assembly',date:'2026-04-27'},
  's3_0':{student:'Karthik Raja',notes:'AutoCAD scaling',date:'2026-04-27'},
  's4_6':{student:'Sneha Rao',notes:'Gear ratio walkthrough',date:'2026-04-27'},
  's1_8':{student:'Bharath M',notes:'Creo template fix',date:'2026-04-27'}
};

const TECH_QUERIES = [
  {id:1,leadId:'IB001',name:'Arjun Karthikeyan',phone:'+91 98400 12345',secondary:'+91 98400 55555',email:'arjun.k@gmail.com',subject:'Thermodynamics — entropy in irreversible process',slot:'2:00 PM',date:'27 Apr',leadDate:'22 Apr 2026',source:'MyOperator',location:'Madurai',leadType:'Inbound',avatarColor:'#4d8eff,#a78bfa',status:'hot',workflowStatus:'new',course:'Mechanical Engineering'},
  {id:2,leadId:'IB002',name:'Vignesh Kumar',phone:'+91 98123 45670',secondary:'+91 95432 11223',email:'vignesh.k@gmail.com',subject:'SolidWorks — assembly mate constraint failing',slot:'3:00 PM',date:'27 Apr',leadDate:'20 Apr 2026',source:'Google Ads',location:'Madurai',leadType:'Inbound',avatarColor:'#22d3ee,#4d8eff',status:'warm',workflowStatus:'opened',course:'SolidWorks'},
  {id:3,leadId:'IB003',name:'Bharath M',phone:'+91 88765 12345',secondary:'+91 90876 33445',email:'bharath.m@gmail.com',subject:'Creo — drawing template not loading',slot:'4:00 PM',date:'27 Apr',leadDate:'18 Apr 2026',source:'Walk-in',location:'Madurai',leadType:'Inbound',avatarColor:'#a78bfa,#f472b6',status:'hot',workflowStatus:'opened',course:'Creo'},
  {id:4,leadId:'IB004',name:'Karthik Raja',phone:'+91 99876 54321',secondary:'+91 91234 56789',email:'karthik.r@gmail.com',subject:'AutoCAD — block reference scaling issue',slot:'11:30 AM',date:'27 Apr',leadDate:'15 Apr 2026',source:'Referral',location:'Madurai',leadType:'Inbound',avatarColor:'#f87171,#fb923c',status:'warm',workflowStatus:'pending',course:'AutoCAD'},
  {id:5,leadId:'IB005',name:'Sneha Rao',phone:'+91 99123 87654',secondary:'+91 98321 09876',email:'sneha.r@gmail.com',subject:'Mech — gear ratio calculation walkthrough',slot:'5:00 PM',date:'27 Apr',leadDate:'24 Apr 2026',source:'Instagram',location:'Madurai',leadType:'Inbound',avatarColor:'#34d399,#22d3ee',status:'cold',workflowStatus:'inreview',course:'Mechanical Engineering'},
  {id:6,leadId:'IB006',name:'Meera Suresh',phone:'+91 97654 32108',secondary:'+91 96543 21098',email:'meera.s@gmail.com',subject:'Civil — RCC slab design code reference',slot:'12:30 PM',date:'27 Apr',leadDate:'21 Apr 2026',source:'MyOperator',location:'Madurai',leadType:'Inbound',avatarColor:'#a78bfa,#f472b6',status:'warm',workflowStatus:'resolved',course:'Civil Engineering'}
];

const NONTECH_QUERIES = [
  {id:101,leadId:'IB007',name:'Priya Devi',phone:'+91 90876 54321',secondary:'—',email:'priya.d@gmail.com',category:'Placement',description:'Wants details on internship + placement support for Video Editing course',attachment:'CV_Priya.pdf',date:'27 Apr',leadDate:'25 Apr 2026',source:'Google Ads',location:'Madurai',leadType:'Inbound',avatarColor:'#fb923c,#f87171',status:'hot',workflowStatus:'new',course:'Video Editing'},
  {id:102,leadId:'IB008',name:'Lakshmi Priya',phone:'+91 95432 09876',secondary:'+91 93456 78901',email:'lakshmi.p@gmail.com',category:'Fee / Billing',description:'EMI plan — needs revised installment schedule for Civil course',attachment:null,date:'27 Apr',leadDate:'23 Apr 2026',source:'MyOperator',location:'Madurai',leadType:'Inbound',avatarColor:'#f472b6,#fb923c',status:'warm',workflowStatus:'opened',course:'Civil Engineering'},
  {id:103,leadId:'IB009',name:'Anitha Murugan',phone:'+91 99887 65432',secondary:'—',email:'anitha.m@gmail.com',category:'Certificate',description:'Certificate not received after course completion. End date: Mar 30',attachment:'screenshot.png',date:'26 Apr',leadDate:'10 Apr 2026',source:'Walk-in',location:'Madurai',leadType:'Inbound',avatarColor:'#34d399,#22c55e',status:'cold',workflowStatus:'pending',course:'Graphic Design'},
  {id:104,leadId:'IB010',name:'Divya Lakshmi',phone:'+91 98432 19876',secondary:'+91 97123 45678',email:'divya.l@gmail.com',category:'Schedule',description:'Wants to switch to weekend batch for Graphic Design — work conflict',attachment:null,date:'26 Apr',leadDate:'19 Apr 2026',source:'Instagram',location:'Madurai',leadType:'Inbound',avatarColor:'#34d399,#a78bfa',status:'hot',workflowStatus:'inreview',course:'Graphic Design'},
  {id:105,leadId:'IB011',name:'Suresh Babu',phone:'+91 98234 67890',secondary:'—',email:'suresh.b@gmail.com',category:'General',description:'Asked for syllabus PDF + faculty list for Mechanical course',attachment:'mech_syllabus.pdf',date:'25 Apr',leadDate:'05 Apr 2026',source:'Google Ads',location:'Madurai',leadType:'Inbound',avatarColor:'#4d8eff,#a78bfa',status:'cold',workflowStatus:'resolved',course:'Mechanical Engineering'}
];

const STATUS_LIST = [
  {key:'new',label:'New'},
  {key:'opened',label:'Opened'},
  {key:'pending',label:'Pending'},
  {key:'inreview',label:'In Review'},
  {key:'resolved',label:'Resolved'}
];
const ASSIGNEES = ['Self','Suresh M','Anitha R','Karthik V','Divya P'];

/* Per-student previous calls */
const STUDENT_CALLS = {};
function defaultPrevCalls(name){
  return [
    {time:'03:30 PM',date:'21 Apr',direction:'outgoing',recDur:'2m 10s',duration:'2m 10s',owner:'Self',tag:'warm',notes:'Called back, confirmed interest'},
    {time:'11:14 AM',date:'19 Apr',direction:'outgoing',recDur:'1m 48s',duration:'1m 48s',owner:'Karthik',tag:'cold',notes:'Initial intro call — followed up later'}
  ];
}

const CERTIFICATES = [
  {id:1,name:'Anitha Murugan',course:'Graphic Design — Advanced',completed:'Mar 30, 2026',grade:'A',status:'ready',avatarColor:'#34d399,#22c55e'},
  {id:2,name:'Suresh Babu',course:'Mechanical Engineering Fundamentals',completed:'Apr 02, 2026',grade:'A+',status:'ready',avatarColor:'#4d8eff,#a78bfa'},
  {id:3,name:'Ravi Shankar',course:'AutoCAD 2D & 3D',completed:'Apr 10, 2026',grade:'A',status:'issued',avatarColor:'#22d3ee,#4d8eff'},
  {id:4,name:'Lakshmi Priya',course:'Civil Engineering Drafting',completed:'Apr 15, 2026',grade:'B+',status:'ready',avatarColor:'#f472b6,#fb923c'},
  {id:5,name:'Karthik Raja',course:'AutoCAD Professional',completed:'Apr 18, 2026',grade:'A',status:'pending',avatarColor:'#f87171,#fb923c'},
  {id:6,name:'Vignesh Kumar',course:'SolidWorks Modeling',completed:'Apr 20, 2026',grade:'A',status:'ready',avatarColor:'#22d3ee,#4d8eff'},
  {id:7,name:'Bharath M',course:'Creo Parametric',completed:'Apr 22, 2026',grade:'B+',status:'pending',avatarColor:'#a78bfa,#f472b6'},
  {id:8,name:'Priya Devi',course:'Video Editing — Premiere Pro',completed:'Apr 24, 2026',grade:'A+',status:'ready',avatarColor:'#fb923c,#f87171'}
];

/* ═══════════ BATCHES ═══════════ */
const STARTED_BATCHES = [
  {id:'FS-APR-21',course:'Fullstack',courseKey:'Fullstack',pillKey:'fullstack',stripeKey:'fullstack',mentor:'Arun Kumar',type:'Weekday',hours:'120h',starts:'21 Apr 2026',lastJoin:'28 Apr 2026',students:1},
  {id:'UI-APR-22',course:'UI/UX',courseKey:'UI/UX',pillKey:'uiux',stripeKey:'uiux',mentor:'Meena Iyer',type:'Weekend',hours:'80h',starts:'22 Apr 2026',lastJoin:'29 Apr 2026',students:0}
];

const UPCOMING_BATCHES = [
  {id:'FS-MAY-01',course:'Fullstack',courseKey:'Fullstack',pillKey:'fullstack',stripeKey:'fullstack',mentor:'Arun Kumar',type:'Weekday',hours:'120h',starts:'1 May 2026',lastJoin:'8 May 2026',students:0},
  {id:'UI-MAY-03',course:'UI/UX',courseKey:'UI/UX',pillKey:'uiux',stripeKey:'uiux',mentor:'Meena Iyer',type:'Weekend',hours:'80h',starts:'3 May 2026',lastJoin:'10 May 2026',students:0},
  {id:'DM-MAY-05',course:'Digital Marketing',courseKey:'Digital Marketing',pillKey:'dm',stripeKey:'dm',mentor:'Preethi V',type:'Weekday',hours:'60h',starts:'5 May 2026',lastJoin:'12 May 2026',students:1},
  {id:'DA-MAY-02',course:'Data Science',courseKey:'Data Science',pillKey:'ds',stripeKey:'ds',mentor:'Suresh R',type:'Weekend',hours:'90h',starts:'2 May 2026',lastJoin:'9 May 2026',students:0},
  {id:'FS-MAY-15',course:'Fullstack',courseKey:'Fullstack',pillKey:'fullstack',stripeKey:'fullstack',mentor:'Arun Kumar',type:'Weekend',hours:'120h',starts:'15 May 2026',lastJoin:'22 May 2026',students:2},
  {id:'UI-MAY-18',course:'UI/UX',courseKey:'UI/UX',pillKey:'uiux',stripeKey:'uiux',mentor:'Meena Iyer',type:'Weekday',hours:'80h',starts:'18 May 2026',lastJoin:'25 May 2026',students:0},
  {id:'DM-MAY-20',course:'Digital Marketing',courseKey:'Digital Marketing',pillKey:'dm',stripeKey:'dm',mentor:'Preethi V',type:'Weekend',hours:'60h',starts:'20 May 2026',lastJoin:'27 May 2026',students:0},
  {id:'DA-MAY-25',course:'Data Science',courseKey:'Data Science',pillKey:'ds',stripeKey:'ds',mentor:'Suresh R',type:'Weekday',hours:'90h',starts:'25 May 2026',lastJoin:'1 Jun 2026',students:0}
];

let FOLLOWUPS = [];
let CURRENT_LEAD_TAB = 'tech';
let CURRENT_LEAD_SUB_TAB = 'new';
let CURRENT_ALLOC_CELL = null;
let CURRENT_STUDENT = null;
let CURRENT_COURSE_FILTER = 'all';
let PREV_OPEN = true;
let CURRENT_BATCH_ID = null;

/* ═══════════ INIT ═══════════ */
window.addEventListener('DOMContentLoaded',()=>{
  document.getElementById('alloc-date').valueAsDate = new Date();
  // Default assign date = tomorrow
  const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate()+1);
  document.getElementById('assign-date').valueAsDate = tomorrow;
  document.getElementById('assign-to').innerHTML = ASSIGNEES.map(a=>`<option>${a}</option>`).join('');
  document.getElementById('batch-staff-name').innerHTML = STAFF.map(s=>`<option>${s.name}</option>`).join('');
  renderRaisers();
  renderSubTabs();
  renderTechRows();
  renderNonTechRows();
  renderAllocTable();
  renderCertList();
  renderStartedBatches();
  renderUpcomingBatches();
  renderFollowUps();
  populateAllocSelects();
  updateNavCounts();
  updateLeadTabCounts();
});

/* ═══════════ NAV ═══════════ */
function showSection(id, navEl){
  document.querySelectorAll('.section-view').forEach(s=>s.classList.remove('active'));
  const sec = document.getElementById('sec-'+id);
  if(sec) sec.classList.add('active');
  if(navEl){
    document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
    navEl.classList.add('active');
  }
  const titles={
    'dashboard':['SUPPORT CRM','Dashboard',"— Today's overview"],
    'leads':['SUPPORT CRM / LEADS','Leads','— Doubt & query raised students'],
    'student-detail':['SUPPORT CRM / LEADS / PROFILE','Student profile','— Full lead context'],
    'doubt-allocation':['SUPPORT CRM','Doubt allocation','— Staff × time slot grid'],
    'certificates':['SUPPORT CRM','Certificates',''],
    'followup':['SUPPORT CRM','Follow up','— My assigned leads'],
    'batch':['BATCH ALLOCATION / ALL BATCHES','Batch Allocation','']
  };
  const t = titles[id]||['','',''];
  document.getElementById('page-crumb').textContent = t[0];
  document.getElementById('page-title').innerHTML = t[1] + (t[2]? ' <span>'+t[2]+'</span>':'');
  window.scrollTo({top:0,behavior:'smooth'});
}

/* ═══════════ HELPERS ═══════════ */
function avatarHTML(name,color,size='mini-avatar'){
  const initials = name.split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase();
  return `<div class="${size}" style="background:linear-gradient(135deg,${color});">${initials}</div>`;
}

/* ═══════════ DASHBOARD RAISERS ═══════════ */
function renderRaisers(){
  document.getElementById('raiser-tech').innerHTML = TECH_QUERIES.map(q=>`
    <div class="raiser-row" onclick="openStudent(${q.id},'tech')">
      ${avatarHTML(q.name,q.avatarColor)}
      <div class="raiser-info">
        <div class="raiser-name">${q.name}</div>
        <div class="raiser-q">${q.subject}</div>
      </div>
      <div class="raiser-time">${q.slot}</div>
    </div>
  `).join('');
  document.getElementById('raiser-nontech').innerHTML = NONTECH_QUERIES.map(q=>`
    <div class="raiser-row" onclick="openStudent(${q.id},'nontech')">
      ${avatarHTML(q.name,q.avatarColor)}
      <div class="raiser-info">
        <div class="raiser-name">${q.name}</div>
        <div class="raiser-q">${q.category} — ${q.description}</div>
      </div>
      <div class="raiser-time">·</div>
    </div>
  `).join('');
}

/* ═══════════ LEADS TABS ═══════════ */
function switchLeadTab(tab){
  CURRENT_LEAD_TAB = tab;
  document.getElementById('lead-tab-tech').classList.toggle('active',tab==='tech');
  document.getElementById('lead-tab-nontech').classList.toggle('active',tab==='nontech');
  document.getElementById('leads-tech-view').style.display = tab==='tech' ? 'block':'none';
  document.getElementById('leads-nontech-view').style.display = tab==='nontech' ? 'block':'none';
  renderSubTabs();
  renderTechRows();
  renderNonTechRows();
}
function switchLeadSubTab(status){
  CURRENT_LEAD_SUB_TAB = status;
  renderSubTabs();
  renderTechRows();
  renderNonTechRows();
}
function renderSubTabs(){
  const list = CURRENT_LEAD_TAB==='tech' ? TECH_QUERIES : NONTECH_QUERIES;
  document.getElementById('lead-sub-tabs').innerHTML = STATUS_LIST.map(s=>{
    const cnt = list.filter(q=>q.workflowStatus===s.key).length;
    const active = CURRENT_LEAD_SUB_TAB===s.key ? 'active' : '';
    return `<button class="sub-tab-pill ${active}" onclick="switchLeadSubTab('${s.key}')">${s.label}<span class="cnt">${cnt}</span></button>`;
  }).join('');
}
function updateLeadTabCounts(){
  document.getElementById('lead-tab-tech-cnt').textContent = TECH_QUERIES.length;
  document.getElementById('lead-tab-nontech-cnt').textContent = NONTECH_QUERIES.length;
}

function renderTechRows(){
  const filtered = TECH_QUERIES.filter(q=>q.workflowStatus===CURRENT_LEAD_SUB_TAB);
  const wrap = document.getElementById('tech-rows');
  if(filtered.length===0){
    wrap.innerHTML = `<div style="padding:36px 18px;text-align:center;color:var(--text3);font-size:13px;">No technical queries with status <strong style="color:var(--text2);">${CURRENT_LEAD_SUB_TAB}</strong></div>`;
    return;
  }
  wrap.innerHTML = filtered.map(q=>{
    const short = q.subject.length>52 ? q.subject.slice(0,52)+'…' : q.subject;
    const isLong = q.subject.length>52;
    return `
    <div class="ribbon-row" style="grid-template-columns:1.7fr 1.2fr 2.2fr 0.9fr 0.9fr 1.2fr;" onclick="openStudent(${q.id},'tech')">
      <div class="cell-name">
        ${avatarHTML(q.name,q.avatarColor)}
        <div>
          <div class="cell-name-text">${q.name}</div>
          <div class="cell-name-sub">${q.leadId}</div>
        </div>
      </div>
      <div class="cell-mono">${q.phone}</div>
      <div class="desc-cell">
        <span class="desc-clickable" onclick="event.stopPropagation();openDescModal(${q.id},'tech')">
          ${short}
          ${isLong ? '<span class="read-more">Read</span>' : '<span class="read-more">View</span>'}
        </span>
      </div>
      <div class="cell-mono" style="font-weight:600;color:var(--accent);">${q.slot}</div>
      <div class="cell-mono" style="color:var(--text3);">${q.date}</div>
      <div onclick="event.stopPropagation();">
        <button class="btn btn-mini btn-primary" onclick="goToDoubtAllocation()">⏱ Schedule Doubt</button>
      </div>
    </div>
  `;}).join('');
}

function renderNonTechRows(){
  const filtered = NONTECH_QUERIES.filter(q=>q.workflowStatus===CURRENT_LEAD_SUB_TAB);
  const wrap = document.getElementById('nontech-rows');
  if(filtered.length===0){
    wrap.innerHTML = `<div style="padding:36px 18px;text-align:center;color:var(--text3);font-size:13px;">No non-technical queries with status <strong style="color:var(--text2);">${CURRENT_LEAD_SUB_TAB}</strong></div>`;
    return;
  }
  wrap.innerHTML = filtered.map(q=>{
    const short = q.description.length>52 ? q.description.slice(0,52)+'…' : q.description;
    const isLong = q.description.length>52;
    return `
    <div class="ribbon-row" style="grid-template-columns:2fr 1.3fr 1.1fr 2.2fr 1fr;" onclick="openStudent(${q.id},'nontech')">
      <div class="cell-name">
        ${avatarHTML(q.name,q.avatarColor)}
        <div>
          <div class="cell-name-text">${q.name}</div>
          <div class="cell-name-sub">${q.leadId}</div>
        </div>
      </div>
      <div class="cell-mono">${q.phone}</div>
      <div><span class="raiser-pill nontech" style="font-size:10.5px;">${q.category}</span></div>
      <div class="desc-cell">
        <span class="desc-clickable" onclick="event.stopPropagation();openDescModal(${q.id},'nontech')">
          ${short}
          ${isLong ? '<span class="read-more">Read</span>' : '<span class="read-more">View</span>'}
        </span>
      </div>
      <div onclick="event.stopPropagation();">${q.attachment ? `<a class="attach-chip" href="javascript:toast('alert','Open: ${q.attachment}')">📎 ${q.attachment}</a>` : '<span style="color:var(--text3);font-size:11.5px;">—</span>'}</div>
    </div>
  `;}).join('');
}

function goToDoubtAllocation(){
  showSection('doubt-allocation', document.querySelectorAll('.nav-item')[2]);
}

/* ═══════════ DESCRIPTION MODAL ═══════════ */
let DESC_MODAL_TARGET = null; // {id, source}
function openDescModal(id, source){
  const list = source==='tech' ? TECH_QUERIES : NONTECH_QUERIES;
  const q = list.find(x=>x.id===id);
  if(!q) return;
  DESC_MODAL_TARGET = {id, source};
  document.getElementById('desc-modal-title').textContent = q.name;
  document.getElementById('desc-modal-sub').textContent = `${q.leadId} · ${q.phone}`;

  // Meta pills row
  const meta = [];
  meta.push(`<span class="status-pill ${q.workflowStatus}">${STATUS_LIST.find(s=>s.key===q.workflowStatus).label}</span>`);
  if(source==='tech'){
    meta.push(`<span class="raiser-pill tech" style="font-size:10.5px;">⚙ Technical</span>`);
    meta.push(`<span class="raiser-pill" style="font-size:10.5px;background:var(--accent-soft);color:var(--accent);">⏱ ${q.slot}</span>`);
    meta.push(`<span class="raiser-pill" style="font-size:10.5px;background:var(--card2);color:var(--text2);">📅 ${q.date}</span>`);
    document.getElementById('desc-modal-field-label').textContent = 'Subject / Description';
  } else {
    meta.push(`<span class="raiser-pill nontech" style="font-size:10.5px;">${q.category}</span>`);
    meta.push(`<span class="raiser-pill" style="font-size:10.5px;background:var(--card2);color:var(--text2);">📅 ${q.date}</span>`);
    document.getElementById('desc-modal-field-label').textContent = 'Description';
  }
  document.getElementById('desc-modal-meta').innerHTML = meta.join('');

  // Body
  document.getElementById('desc-modal-body').textContent = source==='tech' ? q.subject : q.description;

  // Attachment
  const attachEl = document.getElementById('desc-modal-attachment');
  if(source==='nontech' && q.attachment){
    attachEl.style.display = 'block';
    attachEl.innerHTML = `<label class="form-label" style="margin-bottom:7px;">Attachment</label>
      <a class="attach-chip" href="javascript:toast('alert','Open: ${q.attachment}')">📎 ${q.attachment}</a>`;
  } else {
    attachEl.style.display = 'none';
  }

  document.getElementById('desc-modal').classList.add('open');
}
function closeDescModal(){ document.getElementById('desc-modal').classList.remove('open'); }
function openStudentFromDesc(){
  if(!DESC_MODAL_TARGET) return;
  closeDescModal();
  openStudent(DESC_MODAL_TARGET.id, DESC_MODAL_TARGET.source);
}

/* ═══════════ STUDENT DETAIL ═══════════ */
function openStudent(id,source){
  const list = source==='tech'? TECH_QUERIES : NONTECH_QUERIES;
  const s = list.find(x=>x.id===id);
  if(!s) return;
  CURRENT_STUDENT = s;

  // Profile
  document.getElementById('sd-name').textContent = s.name;
  document.getElementById('sd-leadid').textContent = s.leadId;
  document.getElementById('sd-leadpill').textContent = s.status.toUpperCase();
  document.getElementById('sd-leadpill').className = 'lead-pill ' + s.status;
  document.getElementById('sd-phone').textContent = s.phone;
  document.getElementById('sd-email').textContent = s.email;
  document.getElementById('sd-source').textContent = s.source;
  document.getElementById('sd-location').textContent = s.location;
  document.getElementById('sd-secondary').textContent = s.secondary || '—';
  document.getElementById('sd-leaddate').textContent = s.leadDate;
  document.getElementById('sd-assigned').textContent = 'Self';
  document.getElementById('sd-leadtype').textContent = s.leadType;

  const av = document.getElementById('sd-avatar');
  av.style.background = `linear-gradient(135deg,${s.avatarColor})`;
  av.textContent = s.name[0].toUpperCase();

  // Calls
  if(!STUDENT_CALLS[s.id]) STUDENT_CALLS[s.id] = defaultPrevCalls(s.name);
  renderPrevCalls(s.id);

  // Current call notes prefill
  document.getElementById('current-notes').value = source==='tech' ? `${s.subject} — wants live walkthrough.` : `${s.category}: ${s.description}`;
  document.getElementById('current-tag-sel').value = (s.workflowStatus==='new'?'opened':s.workflowStatus);

  // Reset tabs
  document.querySelectorAll('.detail-tab').forEach((b,i)=>b.classList.toggle('active',i===0));
  document.querySelectorAll('.detail-tab-body').forEach((b,i)=>b.classList.toggle('active',i===0));
  document.querySelectorAll('.lms-acc').forEach(a=>a.classList.remove('open'));
  // Reset prev section to expanded
  PREV_OPEN = true;
  document.getElementById('prev-table').style.display = '';
  document.getElementById('prev-toggle').textContent = '▲ Hide';

  showSection('student-detail',null);
}

function togglePrev(){
  PREV_OPEN = !PREV_OPEN;
  document.getElementById('prev-table').style.display = PREV_OPEN ? '' : 'none';
  document.getElementById('prev-toggle').textContent = PREV_OPEN ? '▲ Hide' : '▼ Show';
}

function updateCurrentTag(val){
  if(!CURRENT_STUDENT) return;
  CURRENT_STUDENT.workflowStatus = val;
  renderSubTabs();
  renderTechRows();
  renderNonTechRows();
  toast('success',`Status → ${STATUS_LIST.find(s=>s.key===val).label}`);
}

function renderPrevCalls(sid){
  const calls = STUDENT_CALLS[sid] || [];
  document.getElementById('prev-count').textContent = calls.length;
  document.getElementById('prev-call-rows').innerHTML = calls.map(c=>`
    <div class="call-table-row" style="border-top:1px solid var(--border);">
      <div class="call-time-cell">${c.time}<span class="date">${c.date}</span></div>
      <div><span class="dir-pill ${c.direction}">${c.direction==='outgoing'?'📞 Outgoing':'📲 Incoming'}</span></div>
      <div><span class="play-pill" onclick="toast('alert','Playing recording')">▶ Play <span class="dur">${c.recDur}</span></span></div>
      <div class="cell-mono" style="color:var(--text);font-weight:600;">${c.duration}</div>
      <div class="cell-mono" style="color:var(--text);font-weight:500;">${c.owner}</div>
      <div><span class="tag-pill ${c.tag}">${c.tag}</span></div>
      <div><div class="notes-cell">${c.notes}</div></div>
    </div>
  `).join('');
}

function switchDetailTab(tab,btn){
  document.querySelectorAll('.detail-tab').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.detail-tab-body').forEach(b=>b.classList.remove('active'));
  document.getElementById('dtab-'+tab).classList.add('active');
}

function toggleAcc(headEl){
  headEl.parentElement.classList.toggle('open');
}

function addCallEntry(){
  if(!CURRENT_STUDENT){ toast('warn','Open a student first'); return; }
  const time = new Date().toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',hour12:true});
  const date = new Date().toLocaleDateString('en-IN',{day:'numeric',month:'short'});
  const newCall = {time,date,direction:'outgoing',recDur:'0m 12s',duration:'0m 12s',owner:'Self',tag:'warm',notes:'New manual entry — edit notes inline.'};
  STUDENT_CALLS[CURRENT_STUDENT.id].unshift(newCall);
  renderPrevCalls(CURRENT_STUDENT.id);
  toast('success','Call entry added to timeline');
}

function assignCurrentCall(){
  // legacy stub — kept for safety; real flow goes through openAssignModal
  openAssignModal();
}

function openAssignModal(){
  if(!CURRENT_STUDENT){ toast('warn','Open a student first'); return; }
  document.getElementById('assign-modal-sub').textContent = `For ${CURRENT_STUDENT.name} (${CURRENT_STUDENT.leadId})`;
  document.getElementById('assign-notes').value = document.getElementById('current-notes').value || '';
  document.getElementById('assign-modal').classList.add('open');
}
function closeAssignModal(){ document.getElementById('assign-modal').classList.remove('open'); }
function confirmAssign(){
  const to = document.getElementById('assign-to').value;
  const dateRaw = document.getElementById('assign-date').value;
  const notes = document.getElementById('assign-notes').value.trim();
  if(!to){ toast('warn','Pick an assignee'); return; }
  if(!dateRaw){ toast('warn','Pick a date'); return; }
  const date = new Date(dateRaw).toLocaleDateString('en-IN',{day:'numeric',month:'short'});
  const tag = document.getElementById('current-tag-sel').value;
  const exists = FOLLOWUPS.find(f=>f.id===CURRENT_STUDENT.id);
  if(!exists){
    FOLLOWUPS.push({...CURRENT_STUDENT,assigned:date,due:date,tag,notes,assignedTo:to});
  } else {
    exists.assigned = date; exists.assignedTo = to; exists.notes = notes;
  }
  renderFollowUps();
  updateNavCounts();
  document.getElementById('followup-banner').style.display='flex';
  document.getElementById('followup-banner-text').innerHTML = `<strong>${CURRENT_STUDENT.name} assigned to ${to}.</strong> Due ${date}.`;
  closeAssignModal();
  toast('alert',`🔔 Assigned to ${to} for ${date}`);
}

/* ═══════════ DOUBT ALLOCATION ═══════════ */
function renderAllocTable(){
  const tbl = document.getElementById('alloc-table');
  let html = '<thead><tr><th></th>';
  STAFF.forEach(s=>{
    html += `<th><div class="staff-head">
      <div class="staff-avatar" style="background:linear-gradient(135deg,${s.color});">${s.initials}</div>
      <div class="staff-name">${s.name}</div>
    </div></th>`;
  });
  html += '</tr></thead><tbody>';
  TIME_SLOTS.forEach((slot,si)=>{
    html += `<tr><td class="slot-label">${slot}</td>`;
    STAFF.forEach(s=>{
      const key = `${s.id}_${si}`;
      const a = ALLOCATIONS[key];
      if(a){
        html += `<td><div class="alloc-cell taken" title="Locked">
          <div class="alloc-name">${a.student}</div>
          <div class="alloc-sub">${a.notes.slice(0,28)}${a.notes.length>28?'…':''}</div>
        </div></td>`;
      } else {
        html += `<td><div class="alloc-cell empty" onclick="openAllocModal('${s.id}',${si})">
          <div class="plus">＋</div>
          <div class="label">Allocate</div>
        </div></td>`;
      }
    });
    html += '</tr>';
  });
  html += '</tbody>';
  tbl.innerHTML = html;
  document.getElementById('alloc-summary').textContent = `${Object.keys(ALLOCATIONS).length} of ${TIME_SLOTS.length*STAFF.length} slots used`;
}
function populateAllocSelects(){
  document.getElementById('alloc-slot').innerHTML = TIME_SLOTS.map((s,i)=>`<option value="${i}">${s}</option>`).join('');
  document.getElementById('alloc-staff').innerHTML = STAFF.map(s=>`<option value="${s.id}">${s.name}</option>`).join('');
}
function openAllocModal(staffId,slotIdx){
  CURRENT_ALLOC_CELL = (staffId!==null && slotIdx!==null) ? {staffId,slotIdx} : null;
  document.getElementById('alloc-student').value = '';
  document.getElementById('alloc-notes').value = '';
  if(CURRENT_ALLOC_CELL){
    document.getElementById('alloc-slot').value = slotIdx;
    document.getElementById('alloc-staff').value = staffId;
    const staff = STAFF.find(s=>s.id===staffId);
    document.getElementById('alloc-modal-sub').textContent = `${staff.name} · ${TIME_SLOTS[slotIdx]}`;
  } else {
    document.getElementById('alloc-modal-sub').textContent = 'Choose student, slot & notes';
  }
  document.getElementById('alloc-modal').classList.add('open');
}
function closeAllocModal(){ document.getElementById('alloc-modal').classList.remove('open'); }
function confirmAlloc(){
  const student = document.getElementById('alloc-student').value.trim();
  const slot = +document.getElementById('alloc-slot').value;
  const staffId = document.getElementById('alloc-staff').value;
  const date = document.getElementById('alloc-date').value;
  const notes = document.getElementById('alloc-notes').value.trim();
  if(!student){ toast('warn','Enter student name'); return; }
  const key = `${staffId}_${slot}`;
  if(ALLOCATIONS[key]){ toast('error','That slot is already allocated'); return; }
  ALLOCATIONS[key] = {student,notes:notes||'No notes',date};
  closeAllocModal();
  renderAllocTable();
  toast('success',`Allocated ${student} → ${STAFF.find(s=>s.id===staffId).name} @ ${TIME_SLOTS[slot]}`);
}

/* ═══════════ CERTIFICATES ═══════════ */
function renderCertList(){
  document.getElementById('cert-list').innerHTML = CERTIFICATES.map(c=>`
    <div class="cert-card">
      <div class="cert-icon">⌬</div>
      <div class="cert-info">
        <div class="cert-name">${c.name}</div>
        <div class="cert-course">${c.course}</div>
        <div class="cert-meta">
          <span>◷ ${c.completed}</span>
          <span>◉ Grade ${c.grade}</span>
        </div>
      </div>
      <span class="cert-status ${c.status}">${c.status==='ready'?'Ready':c.status==='issued'?'Issued':'Pending'}</span>
      <button class="btn ${c.status==='pending'?'btn-ghost':'btn-primary'}" onclick="downloadCert(${c.id})" ${c.status==='pending'?'disabled style="opacity:0.5;cursor:not-allowed;"':''}>↓ Download</button>
    </div>
  `).join('');
}
function downloadCert(id){
  const c = CERTIFICATES.find(x=>x.id===id);
  if(!c) return;
  if(c.status==='pending'){ toast('warn','Certificate not ready yet'); return; }
  toast('success',`Downloading certificate for ${c.name}`);
  const blob = new Blob([`Certificate of Completion\n\nThis is to certify that ${c.name}\nhas successfully completed the\n${c.course}\n\nGrade: ${c.grade}\nCompleted: ${c.completed}\n\n— Velora Design Institute, Madurai`],{type:'text/plain'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = `${c.name.replace(/ /g,'_')}_certificate.txt`;
  a.click();
  if(c.status==='ready'){ c.status='issued'; renderCertList(); }
}

/* ═══════════ FOLLOW UP ═══════════ */
function renderFollowUps(){
  const wrap = document.getElementById('followup-rows');
  if(FOLLOWUPS.length===0){
    wrap.innerHTML = `<div style="padding:38px 18px;text-align:center;color:var(--text3);font-size:13px;">No follow-ups assigned yet · open a lead → click <strong style="color:var(--text2);">Assign</strong> in current call to add one</div>`;
    return;
  }
  wrap.innerHTML = FOLLOWUPS.map(f=>`
    <div class="ribbon-row" style="grid-template-columns:2fr 1.4fr 1.5fr 1fr 1fr 1fr;">
      <div class="cell-name">
        ${avatarHTML(f.name,f.avatarColor)}
        <div>
          <div class="cell-name-text">${f.name}</div>
          <div class="cell-name-sub">${f.leadId||''}</div>
        </div>
      </div>
      <div class="cell-mono">${f.phone}</div>
      <div>
        <div style="font-size:12.5px;color:var(--text);font-weight:500;">${(f.subject||f.description||'').slice(0,50)}${(f.subject||f.description||'').length>50?'…':''}</div>
        <div style="font-size:11px;color:var(--text3);margin-top:2px;">${f.course||''}</div>
      </div>
      <div class="cell-mono" style="color:var(--text3);">${f.assigned}</div>
      <div><span class="raiser-pill" style="background:rgba(251,191,36,0.15);color:var(--gold);">${f.due}</span></div>
      <div style="display:flex;gap:5px;">
        <button class="btn btn-mini btn-success" onclick="markFollowDone(${f.id})">✓</button>
        <button class="btn btn-mini btn-danger" onclick="removeFollow(${f.id})">✕</button>
      </div>
    </div>
  `).join('');
}
function markFollowDone(id){
  FOLLOWUPS = FOLLOWUPS.filter(f=>f.id!==id);
  toast('success','Marked done');
  renderFollowUps(); updateNavCounts();
}
function removeFollow(id){
  FOLLOWUPS = FOLLOWUPS.filter(f=>f.id!==id);
  toast('warn','Removed from list');
  renderFollowUps(); updateNavCounts();
}

/* ═══════════ BATCH ALLOCATION ═══════════ */
function batchCardHTML(b,isLive){
  const studentsLabel = b.students===1 ? '1 student' : `${b.students} students`;
  const livePill = isLive ? '<span class="live-mini-pill">LIVE</span>' : '<span class="upc-mini-pill">UPCOMING</span>';
  return `<div class="batch-card">
    <div class="batch-card-stripe stripe-${b.stripeKey}"></div>
    <div class="batch-card-body">
      <div class="batch-card-top">
        <span class="course-pill pill-${b.pillKey}">${b.course}</span>
        ${livePill}
      </div>
      <div>
        <div class="batch-name-big">${b.id}</div>
        <div class="batch-mentor">Mentor: <strong>${b.mentor}</strong></div>
      </div>
      <div class="batch-meta-grid">
        <div class="batch-meta-item"><div class="lbl">Type</div><div class="val">${b.type}</div></div>
        <div class="batch-meta-item"><div class="lbl">Hours</div><div class="val">${b.hours}</div></div>
        <div class="batch-meta-item"><div class="lbl">Starts</div><div class="val">${b.starts}</div></div>
        <div class="batch-meta-item"><div class="lbl">Last join</div><div class="val">${b.lastJoin}</div></div>
      </div>
      <div class="batch-card-foot">
        <div class="batch-students-count">👥 ${studentsLabel}</div>
        <div class="batch-card-actions">
          <button class="btn btn-ghost btn-mini" onclick="batchOverview('${b.id}')">Overview</button>
          <button class="btn btn-primary btn-mini" onclick="batchAllocate('${b.id}')">Allocate</button>
        </div>
      </div>
    </div>
  </div>`;
}
function renderStartedBatches(){
  document.getElementById('started-batches').innerHTML = STARTED_BATCHES.map(b=>batchCardHTML(b,true)).join('');
}
function renderUpcomingBatches(){
  const filtered = CURRENT_COURSE_FILTER==='all' ? UPCOMING_BATCHES : UPCOMING_BATCHES.filter(b=>b.courseKey===CURRENT_COURSE_FILTER);
  const wrap = document.getElementById('upcoming-batches');
  if(filtered.length===0){
    wrap.innerHTML = `<div style="grid-column:1/-1;padding:40px;text-align:center;color:var(--text3);font-size:13px;background:var(--surface);border:1px solid var(--border);border-radius:14px;">No upcoming batches in <strong style="color:var(--text2);">${CURRENT_COURSE_FILTER}</strong></div>`;
    return;
  }
  wrap.innerHTML = filtered.map(b=>batchCardHTML(b,false)).join('');
}
function filterCourses(btn,course){
  CURRENT_COURSE_FILTER = course;
  document.querySelectorAll('.course-fil-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  renderUpcomingBatches();
}
function batchOverview(id){
  toast('alert',`Overview: ${id} — opens batch detail page`);
}
function batchAllocate(id){
  CURRENT_BATCH_ID = id;
  document.getElementById('batch-modal-sub').textContent = `Adding to batch ${id}`;
  document.getElementById('batch-batch-name').value = id;
  document.getElementById('batch-student-name').value = '';
  document.getElementById('batch-modal').classList.add('open');
}
function closeBatchModal(){ document.getElementById('batch-modal').classList.remove('open'); }
function confirmBatchAlloc(){
  const student = document.getElementById('batch-student-name').value.trim();
  const staff = document.getElementById('batch-staff-name').value;
  if(!student){ toast('warn','Enter student name'); return; }
  const all = [...STARTED_BATCHES,...UPCOMING_BATCHES];
  const b = all.find(x=>x.id===CURRENT_BATCH_ID);
  if(!b){ toast('error','Batch not found'); return; }
  b.students += 1;
  if(STARTED_BATCHES.find(x=>x.id===CURRENT_BATCH_ID)) renderStartedBatches();
  else renderUpcomingBatches();
  closeBatchModal();
  toast('success',`✓ ${student} → ${b.id} (handled by ${staff})`);
}

/* ═══════════ ADD LEAD ═══════════ */
function openLeadModal(){
  document.getElementById('lead-name').value='';
  document.getElementById('lead-phone').value='';
  document.getElementById('lead-email').value='';
  document.getElementById('lead-query').value='';
  document.getElementById('lead-modal').classList.add('open');
}
function closeLeadModal(){ document.getElementById('lead-modal').classList.remove('open'); }
function confirmAddLead(){
  const name = document.getElementById('lead-name').value.trim();
  const phone = document.getElementById('lead-phone').value.trim();
  const email = document.getElementById('lead-email').value.trim();
  const course = document.getElementById('lead-course').value;
  const query = document.getElementById('lead-query').value.trim();
  if(!name||!phone){ toast('warn','Name and phone are required'); return; }
  const newId = NONTECH_QUERIES.length+200;
  const leadId = 'IB' + String(NONTECH_QUERIES.length+TECH_QUERIES.length+1).padStart(3,'0');
  NONTECH_QUERIES.unshift({
    id:newId,leadId,name,phone,secondary:'—',email:email||'—',
    category:'General',description:query||'New lead — initial contact pending',
    attachment:null,date:new Date().toLocaleDateString('en-IN',{day:'numeric',month:'short'}),
    leadDate:new Date().toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'}),
    source:'Manual',location:'Madurai',leadType:'Manual',
    avatarColor:'#4d8eff,#a78bfa',status:'warm',workflowStatus:'new',course
  });
  closeLeadModal();
  renderSubTabs();
  renderNonTechRows();
  updateLeadTabCounts();
  updateNavCounts();
  toast('success',`✓ Lead ${leadId} added — ${name}`);
}

/* ═══════════ UTILITY ═══════════ */
function updateNavCounts(){
  document.getElementById('nav-leads-cnt').textContent = TECH_QUERIES.length + NONTECH_QUERIES.length;
  document.getElementById('nav-followup-cnt').textContent = FOLLOWUPS.length;
}
function toast(type,msg){
  const t = document.getElementById('toast');
  document.getElementById('toast-msg').textContent = msg;
  t.className = 'toast show '+type;
  setTimeout(()=>t.classList.remove('show'),2800);
}

document.getElementById('alloc-modal').addEventListener('click',e=>{ if(e.target.id==='alloc-modal') closeAllocModal(); });
document.getElementById('lead-modal').addEventListener('click',e=>{ if(e.target.id==='lead-modal') closeLeadModal(); });
document.getElementById('assign-modal').addEventListener('click',e=>{ if(e.target.id==='assign-modal') closeAssignModal(); });
document.getElementById('batch-modal').addEventListener('click',e=>{ if(e.target.id==='batch-modal') closeBatchModal(); });
document.getElementById('desc-modal').addEventListener('click',e=>{ if(e.target.id==='desc-modal') closeDescModal(); });
</script>
</body>
</html>
