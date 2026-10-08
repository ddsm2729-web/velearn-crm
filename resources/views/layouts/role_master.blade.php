<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'AdmitPro CRM')</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
:root{
  --bg0:#080a10;--bg1:#0e1018;--bg2:#141720;--bg3:#1b1f2c;--bg4:#222738;--bg5:#2a2f44;
  --border:#ffffff0d;--border2:#ffffff16;--border3:#ffffff24;
  --text0:#eef0ff;--text1:#c0c6e0;--text2:#7c84a0;--text3:#4a5068;
  --accent:#4f8fff;--accent2:#3b72e0;--glow:#4f8fff22;
  --green:#1fd6a0;--gbg:#1fd6a016;
  --amber:#f5b429;--abg:#f5b42916;
  --red:#f06464;--rbg:#f0646416;
  --purple:#9b7ef8;--pbg:#9b7ef816;
  --orange:#f97316;--obg:#f9731616;
  --sw:228px;
}
html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--bg0);color:var(--text0);overflow:hidden;}
::-webkit-scrollbar{width:3px;}::-webkit-scrollbar-thumb{background:var(--bg5);border-radius:4px;}
.shell{display:flex;height:100vh;}

/* ── SIDEBAR ── */
.sb{width:var(--sw);flex-shrink:0;background:var(--bg1);border-right:1px solid var(--border);display:flex;flex-direction:column;position:relative;overflow:hidden;}
.sb::before{content:'';position:absolute;top:-100px;left:-80px;width:260px;height:260px;background:radial-gradient(circle,#4f8fff10 0%,transparent 65%);pointer-events:none;}
.sb-logo{padding:20px 18px 16px;border-bottom:1px solid var(--border);}
.sb-logo-inner{display:flex;align-items:center;gap:10px;}
.sb-mark{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#4f8fff,#7c5cf8);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:800;font-size:14px;color:#fff;box-shadow:0 0 18px #4f8fff40;flex-shrink:0;}
.sb-brand{font-family:'Syne',sans-serif;font-weight:800;font-size:15px;color:var(--text0);}
.sb-sub{font-size:9px;color:var(--text3);letter-spacing:.12em;text-transform:uppercase;margin-top:1px;}
.sb-nav{flex:1;padding:10px 10px 4px;overflow-y:auto;}
.si{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:10px;cursor:pointer;font-size:13px;color:var(--text2);transition:all .17s;margin-bottom:1px;border:1px solid transparent;}
.si:hover{background:var(--bg3);color:var(--text1);}
.si.active{background:var(--glow);color:var(--accent);border-color:#4f8fff1a;}
.si-ic{width:15px;height:15px;flex-shrink:0;opacity:.65;}
.si.active .si-ic{opacity:1;}
.si-badge{margin-left:auto;font-size:9px;font-weight:700;padding:2px 7px;border-radius:20px;line-height:1.4;}
.si-badge.blue{background:var(--accent);color:#fff;box-shadow:0 0 8px #4f8fff55;}
.si-badge.amber{background:var(--amber);color:#0a0b0f;}
.si-badge.green{background:var(--green);color:#0a0b0f;}
.si-badge.orange{background:var(--orange);color:#fff;}
.sb-foot{padding:14px;border-top:1px solid var(--border);background:var(--bg2);}
.sb-user{display:flex;align-items:center;gap:10px;}
.sb-av{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#4f8fff,#9b7ef8);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;}
.sb-uname{font-size:13px;font-weight:500;color:var(--text0);}
.sb-urole{display:flex;align-items:center;gap:5px;margin-top:2px;}
.sb-dot{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 6px var(--green);}
.sb-urole span{font-size:10px;color:var(--text3);}

/* ── MAIN ── */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.topbar{height:56px;background:var(--bg1);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;padding:0 22px;flex-shrink:0;}
.tb-title{font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text0);flex:1;}
.tb-crumb{font-size:11px;color:var(--text3);font-weight:400;font-family:'DM Sans',sans-serif;margin-left:6px;}
.tb-srch{display:flex;align-items:center;gap:8px;background:var(--bg2);border:1px solid var(--border2);border-radius:9px;padding:6px 12px;flex:0 0 230px;}
.tb-srch input{background:none;border:none;outline:none;color:var(--text1);font-size:12px;font-family:inherit;width:100%;}
.notif{width:34px;height:34px;border-radius:9px;background:var(--bg3);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;}
.notif-dot{position:absolute;top:7px;right:7px;width:7px;height:7px;border-radius:50%;background:var(--red);border:1.5px solid var(--bg1);}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:9px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid var(--border2);background:var(--bg3);color:var(--text1);transition:all .17s;font-family:'DM Sans',sans-serif;white-space:nowrap;}
.btn:hover{background:var(--bg4);color:var(--text0);border-color:var(--border3);}
.btn-primary{background:var(--accent);color:#fff;border-color:var(--accent);box-shadow:0 0 14px #4f8fff30;}
.btn-primary:hover{background:var(--accent2);}
.btn-green{background:var(--gbg);color:var(--green);border-color:#1fd6a020;}
.btn-green:hover{background:#1fd6a024;}
.btn-amber{background:var(--abg);color:var(--amber);border-color:#f5b42920;}
.btn-red{background:var(--rbg);color:var(--red);border-color:#f0646420;}
.btn-ghost{background:transparent;border-color:transparent;color:var(--text2);}
.btn-ghost:hover{background:var(--bg3);}
.btn-sm{padding:5px 11px;font-size:11px;border-radius:7px;}
.btn-xs{padding:4px 9px;font-size:11px;border-radius:6px;}

/* CONTENT */
.content{flex:1;overflow-y:auto;padding:20px 22px;}
.page{display:none;}
.page.active{display:block;animation:fu .2s ease;}
@keyframes fu{from{opacity:0;transform:translateY(5px);}to{opacity:1;transform:translateY(0);}}

/* FULL WINDOW */
.fullwin{position:fixed;inset:0;background:var(--bg0);z-index:400;display:none;flex-direction:column;overflow:hidden;}
.fullwin.open{display:flex;}
.fw-topbar{height:56px;background:var(--bg1);border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;padding:0 22px;flex-shrink:0;}
.fw-body{flex:1;overflow-y:auto;}

/* TAGS */
.tag{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;}
.tag-converted{background:var(--abg);color:var(--amber);border:1px solid #f5b42920;}
.tag-confirmed{background:var(--gbg);color:var(--green);border:1px solid #1fd6a020;}
.tag-payment{background:var(--pbg);color:var(--purple);border:1px solid #9b7ef820;}
.tag-payment-done{background:var(--gbg);color:var(--green);border:1px solid #1fd6a020;}
.tag-registered{background:var(--glow);color:var(--accent);border:1px solid #4f8fff20;}
.tag-followup{background:var(--obg);color:var(--orange);border:1px solid #f9731620;}
.tag-source{background:var(--bg4);color:var(--text2);border:1px solid var(--border2);}

/* STAT CARDS */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px;}
.sc{background:var(--bg2);border:1px solid var(--border);border-radius:13px;padding:18px 20px;position:relative;overflow:hidden;transition:border-color .2s;}
.sc:hover{border-color:var(--border2);}
.sc::after{content:'';position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;opacity:.08;}
.sc.blue::after{background:var(--accent);}
.sc.green::after{background:var(--green);}
.sc.amber::after{background:var(--amber);}
.sc.orange::after{background:var(--orange);}
.sc-icon{width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;}
.sc-icon.blue{background:var(--glow);}
.sc-icon.green{background:var(--gbg);}
.sc-icon.amber{background:var(--abg);}
.sc-icon.orange{background:var(--obg);}
.sc-num{font-family:'Syne',sans-serif;font-size:26px;font-weight:700;color:var(--text0);line-height:1;}
.sc-label{font-size:12px;color:var(--text2);margin-top:5px;}
.sc-delta{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;margin-top:8px;padding:2px 7px;border-radius:20px;}
.du{background:var(--gbg);color:var(--green);}
.dw{background:var(--abg);color:var(--amber);}

/* SECTION CARD */
.secc{background:var(--bg2);border:1px solid var(--border);border-radius:13px;padding:16px 18px;}
.sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.sec-title{font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--text0);}

/* MINI TABLE for dashboard */
.mtr{display:grid;grid-template-columns:2fr 1.2fr .9fr 1fr;border-bottom:1px solid var(--border);cursor:pointer;transition:background .14s;}
.mtr:last-child{border-bottom:none;}
.mtr:hover{background:var(--bg3);}
.mth{padding:7px 10px;font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.mtd{padding:10px 10px;font-size:12px;color:var(--text1);display:flex;align-items:center;}

/* ── LEADS TABLE — no action col ── */
.ltable{background:var(--bg1);border:1px solid var(--border);border-radius:13px;overflow:hidden;}
.lt-head-leads{display:grid;grid-template-columns:2.4fr 1.3fr 1fr 1fr 1fr;background:var(--bg2);border-bottom:1px solid var(--border);}
.lrow-leads{display:grid;grid-template-columns:2.4fr 1.3fr 1fr 1fr 1fr;border-bottom:1px solid var(--border);cursor:pointer;transition:background .14s;}
.lrow-leads:last-child{border-bottom:none;}
.lrow-leads:hover{background:var(--bg2);}

/* ── FOLLOWUP TABLE — no action col ── */
.lt-head-fu{display:grid;grid-template-columns:2fr 1.3fr 1.2fr 1.4fr 1fr;background:var(--bg2);border-bottom:1px solid var(--border);}
.lrow-fu{display:grid;grid-template-columns:2fr 1.3fr 1.2fr 1.4fr 1fr;border-bottom:1px solid var(--border);cursor:pointer;transition:background .14s;}
.lrow-fu:last-child{border-bottom:none;}
.lrow-fu:hover{background:var(--bg2);}

.th{padding:9px 13px;font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.td{padding:12px 13px;font-size:12px;color:var(--text1);display:flex;align-items:center;}
.lc{display:flex;align-items:center;gap:9px;}
.lav{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;}
.ln{font-size:13px;font-weight:500;color:var(--text0);}
.ls{font-size:11px;color:var(--text3);}

/* FILTER TABS */
.ftabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;}
.ftab{padding:5px 14px;border-radius:20px;font-size:11px;font-weight:500;cursor:pointer;border:1px solid var(--border2);color:var(--text2);background:transparent;transition:all .17s;font-family:'DM Sans',sans-serif;}
.ftab:hover{background:var(--bg3);color:var(--text1);}
.ftab.active{background:var(--glow);color:var(--accent);border-color:#4f8fff22;}

.ptop{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.ph2{font-family:'Syne',sans-serif;font-size:17px;font-weight:700;color:var(--text0);}
.psub{font-size:12px;color:var(--text3);margin-left:7px;font-weight:400;}

/* INFO GRID */
.igrid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.igrid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;}
.ii{background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:10px 12px;}
.il{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:3px;}
.iv{font-size:13px;color:var(--text0);font-weight:500;}

/* ── STUDENT DETAIL — FULL WINDOW ── */
.stu-fw-body{display:flex;flex-direction:column;height:100%;}

/* Profile header strip */
.stu-profile-strip{
  background:linear-gradient(135deg,var(--bg2),var(--bg3));
  border-bottom:1px solid var(--border2);
  padding:24px 32px;
  display:flex;align-items:center;gap:20px;
  flex-shrink:0;
}
.stu-big-av{width:62px;height:62px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;flex-shrink:0;}
.stu-name{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;color:var(--text0);}
.stu-course{font-size:13px;color:var(--text2);margin-top:3px;}
.stu-meta-row{display:flex;align-items:center;gap:10px;margin-top:8px;flex-wrap:wrap;}
.stu-meta-pill{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text2);background:var(--bg4);padding:4px 10px;border-radius:20px;border:1px solid var(--border2);}

/* Profile info grid */
.stu-profile-grid{padding:20px 32px;border-bottom:1px solid var(--border);background:var(--bg1);flex-shrink:0;}
.stu-profile-grid-inner{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;}

/* ── TIMELINE CALL HISTORY ── */
.tl-wrap{background:var(--bg1);border:1px solid var(--border2);border-radius:13px;overflow:hidden;}
.tl-header{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid var(--border2);}
.tl-title{display:flex;align-items:center;gap:8px;font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--text0);}
.tl-title-sub{font-size:12px;color:var(--text3);font-family:'DM Sans',sans-serif;font-weight:400;}

.tl-section{padding:0;}
.tl-section-label{display:grid;grid-template-columns:160px 130px 150px 90px 110px 90px 1fr;gap:0;padding:8px 18px;background:var(--bg2);border-bottom:1px solid var(--border);}
.tl-section-label span{font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;}
.tl-section-head{display:flex;align-items:center;gap:8px;padding:10px 18px;border-bottom:1px solid var(--border);background:var(--bg3);}
.tl-section-title{font-size:11px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.1em;}
.tl-section-count{font-size:10px;color:var(--text3);}
.tl-collapse-btn{margin-left:auto;font-size:11px;color:var(--text3);cursor:pointer;background:none;border:none;font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:4px;}

.tl-row{display:grid;grid-template-columns:160px 130px 150px 90px 110px 90px 1fr;gap:0;align-items:center;padding:12px 18px;border-bottom:1px solid var(--border);transition:background .14s;}
.tl-row:last-child{border-bottom:none;}
.tl-row:hover{background:var(--bg2);}
.tl-row.current-row{border-left:3px solid var(--accent);background:#4f8fff08;}

.tl-cell{font-size:12px;color:var(--text1);padding-right:10px;}
.tl-time{font-size:12px;color:var(--text0);font-weight:500;line-height:1.4;}
.tl-time-date{font-size:10px;color:var(--text3);}

.dir-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;}
.dir-out{background:#4f8fff18;color:#60a5fa;border:1px solid #4f8fff22;}
.dir-in{background:#1fd6a018;color:var(--green);border:1px solid #1fd6a022;}

.play-btn{display:inline-flex;align-items:center;gap:5px;background:var(--bg4);border:1px solid var(--border2);border-radius:7px;padding:5px 11px;font-size:11px;color:var(--text1);cursor:pointer;transition:all .15s;white-space:nowrap;}
.play-btn:hover{border-color:var(--accent);color:var(--accent);}
.play-dot{width:0;height:0;border-style:solid;border-width:4px 0 4px 7px;border-color:transparent transparent transparent currentColor;}

.call-tag-select{background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:6px 10px;font-size:12px;color:var(--text0);font-family:'DM Sans',sans-serif;outline:none;cursor:pointer;min-width:120px;}
.call-tag-select:focus{border-color:var(--accent);}

.tl-note-input{background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:7px 11px;font-size:12px;color:var(--text0);font-family:'DM Sans',sans-serif;outline:none;width:100%;transition:border-color .15s;}
.tl-note-input:focus{border-color:var(--accent);}

.assign-btn{display:flex;align-items:center;justify-content:center;gap:6px;width:100%;margin-top:6px;padding:8px;background:var(--accent);color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s;}
.assign-btn:hover{background:var(--accent2);}

/* CURRENT section pulsing dot */
.current-dot{width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 0 0 #1fd6a066;animation:cpulse 1.8s infinite;flex-shrink:0;}
@keyframes cpulse{0%{box-shadow:0 0 0 0 #1fd6a066;}70%{box-shadow:0 0 0 7px transparent;}100%{box-shadow:0 0 0 0 transparent;}}

/* ── REGISTRATION MANUAL FORM ── */
.reg-summary-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500;background:var(--bg4);border:1px solid var(--border2);color:var(--text1);}
.reg-summary-pill.green{background:var(--gbg);border-color:#1fd6a022;color:var(--green);font-weight:700;}

.reg-manual-form{background:var(--bg2);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:16px;}
.reg-manual-head{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--bg3);}
.reg-manual-title{font-size:13px;font-weight:600;color:var(--text0);}
.reg-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:16px;}
.reg-form-grid.three{grid-template-columns:1fr 1fr 1fr;}
.rfield{display:flex;flex-direction:column;gap:5px;}
.rfield label{font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;}
.rfield input,.rfield select{background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text0);font-family:'DM Sans',sans-serif;outline:none;width:100%;transition:border-color .15s;}
.rfield input:focus,.rfield select:focus{border-color:var(--accent);}
.rfield select option{background:var(--bg3);}
.reg-form-foot{display:flex;justify-content:flex-end;gap:8px;padding:0 16px 14px;}

/* Fee toggle */
.fee-toggle{display:flex;gap:0;background:var(--bg3);border:1px solid var(--border2);border-radius:10px;overflow:hidden;margin-bottom:14px;}
.fee-tog-btn{flex:1;padding:10px;font-size:13px;font-weight:500;text-align:center;cursor:pointer;color:var(--text2);border:none;background:transparent;transition:all .18s;font-family:'DM Sans',sans-serif;}
.fee-tog-btn:hover{background:var(--bg4);color:var(--text1);}
.fee-tog-btn.active{background:var(--glow);color:var(--accent);}
.fee-pane{display:none;}
.fee-pane.active{display:block;animation:fu .18s ease;}
.split-entry{padding:14px 16px;border-bottom:1px solid var(--border);}
.split-entry:last-of-type{border-bottom:none;}
.split-entry-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;}
.split-label{font-size:10px;font-weight:700;color:var(--accent);background:var(--glow);padding:3px 10px;border-radius:20px;border:1px solid #4f8fff22;}
.split-add-btn{width:100%;padding:10px;border-radius:8px;background:transparent;border:1px dashed var(--border3);color:var(--text3);font-size:12px;cursor:pointer;transition:all .15s;font-family:'DM Sans',sans-serif;display:flex;align-items:center;justify-content:center;gap:7px;}
.split-add-btn:hover{background:var(--bg3);color:var(--text1);border-color:var(--accent);}
.saved-block{background:var(--bg2);border:1px solid var(--border);border-radius:11px;overflow:hidden;}
.saved-block-head{display:flex;align-items:center;justify-content:space-between;padding:11px 14px;border-bottom:1px solid var(--border);background:var(--bg3);}
.saved-block-title{font-size:12px;font-weight:600;color:var(--text0);}
.saved-row{display:grid;grid-template-columns:28px 1fr 1fr 90px;align-items:center;padding:10px 14px;border-bottom:1px solid var(--border);}
.saved-row:last-child{border-bottom:none;}
.saved-idx{font-size:11px;font-weight:700;color:var(--text3);}
.saved-amt{font-size:14px;font-weight:600;color:var(--text0);}
.saved-date{font-size:12px;color:var(--text2);}

/* Tabs below profile */
.stu-tabs-bar{display:flex;gap:0;background:var(--bg1);border-bottom:1px solid var(--border);padding:0 32px;flex-shrink:0;}
.stu-tab{padding:13px 22px;font-size:13px;font-weight:500;color:var(--text2);cursor:pointer;border-bottom:2px solid transparent;transition:all .17s;display:flex;align-items:center;gap:7px;}
.stu-tab:hover{color:var(--text1);}
.stu-tab.active{color:var(--accent);border-bottom-color:var(--accent);}
.stu-tab-icon{width:14px;height:14px;flex-shrink:0;}

/* Tab content area */
.stu-tab-content{flex:1;overflow-y:auto;padding:24px 32px;}
.tab-pane{display:none;}
.tab-pane.active{display:block;animation:fu .18s ease;}

/* CALL HISTORY */
.call-entry{display:flex;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);}
.call-entry:last-child{border-bottom:none;}
.c-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;}
.c-who{font-size:13px;font-weight:500;color:var(--text0);}
.c-sub{font-size:11px;color:var(--text3);margin-top:3px;}
.rec-pill{display:inline-flex;align-items:center;gap:5px;background:var(--bg4);border:1px solid var(--border2);border-radius:20px;padding:3px 10px;font-size:11px;color:var(--text2);margin-top:5px;cursor:pointer;transition:all .15s;}
.rec-pill:hover{border-color:var(--accent);color:var(--accent);}
.rdot{width:5px;height:5px;border-radius:50%;background:var(--red);}
.call-section-label{font-size:10px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;padding:10px 0 4px;display:flex;align-items:center;gap:8px;}
.call-section-label::after{content:'';flex:1;height:1px;background:var(--border);}

/* dropdown toggle for telecaller history */
.drop-toggle{display:flex;align-items:center;justify-content:space-between;background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:10px 14px;cursor:pointer;margin-bottom:0;transition:background .15s;}
.drop-toggle:hover{background:var(--bg4);}
.drop-toggle-title{font-size:13px;color:var(--text1);font-weight:500;}
.drop-arrow{font-size:10px;color:var(--text3);transition:transform .2s;}
.drop-body{background:var(--bg3);border:1px solid var(--border);border-top:none;border-radius:0 0 9px 9px;padding:0 14px;overflow:hidden;max-height:0;transition:max-height .3s ease,padding .3s ease;}
.drop-body.open{max-height:600px;padding:4px 14px 12px;}

/* NOTES */
.note-add{width:100%;background:var(--bg3);border:1px solid var(--border2);border-radius:9px;padding:11px 13px;font-size:13px;color:var(--text0);resize:none;min-height:80px;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .15s;}
.note-add:focus{border-color:var(--accent);}
.notecard{background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:11px 13px;margin-bottom:7px;}
.note-dt{font-size:10px;color:var(--text3);margin-bottom:3px;}
.note-txt{font-size:13px;color:var(--text1);}

/* PAYMENT TAB */
.pay-hdr-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.pay-sub-tabs{display:flex;gap:6px;}
.pay-stab{padding:6px 16px;border-radius:20px;font-size:12px;font-weight:500;cursor:pointer;border:1px solid var(--border2);color:var(--text2);background:transparent;transition:all .17s;font-family:'DM Sans',sans-serif;}
.pay-stab.active{background:var(--glow);color:var(--accent);border-color:#4f8fff22;}
.pay-stab-pane{display:none;}
.pay-stab-pane.active{display:block;animation:fu .18s ease;}

.pay-card{background:var(--bg2);border:1px solid var(--border);border-radius:13px;overflow:hidden;margin-bottom:14px;}
.pay-hdr{display:flex;align-items:center;gap:12px;padding:13px 16px;border-bottom:1px solid var(--border);background:var(--bg3);}
.rzp{background:#072654;color:#fff;font-size:9px;font-weight:700;padding:3px 8px;border-radius:6px;letter-spacing:.06em;border:1px solid #1a4a8a;}
.pay-form{display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:14px 16px;}
.fg{display:flex;flex-direction:column;gap:4px;}
.fg.full{grid-column:1/-1;}
.fg label{font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;}
.fg input,.fg select{background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:8px 11px;font-size:12px;color:var(--text0);font-family:'DM Sans',sans-serif;outline:none;transition:border-color .15s;}
.fg input:focus,.fg select:focus{border-color:var(--accent);}
.fg select option{background:var(--bg3);}
.pay-acts{display:flex;gap:7px;padding:0 16px 14px;flex-wrap:wrap;}

/* Payment history rows */
.pay-hist-wrap{background:var(--bg2);border:1px solid var(--border);border-radius:11px;overflow:hidden;}
.pay-hist-row{display:flex;align-items:center;gap:12px;padding:13px 16px;border-bottom:1px solid var(--border);}
.pay-hist-row:last-child{border-bottom:none;}
.pdot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.pdot.paid{background:var(--green);box-shadow:0 0 6px var(--green);}
.pdot.pending{background:var(--amber);box-shadow:0 0 5px var(--amber);}
.pdot.failed{background:var(--red);}
.pay-hist-info{flex:1;}
.pay-hist-title{font-size:13px;font-weight:500;color:var(--text0);}
.pay-hist-sub{font-size:11px;color:var(--text3);margin-top:2px;}
.invoice-card{background:var(--bg3);border:1px solid var(--border2);border-radius:11px;padding:16px;margin-bottom:10px;}
.invoice-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--border);}
.invoice-no{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--text0);}
.invoice-date{font-size:11px;color:var(--text3);margin-top:2px;}
.invoice-rows{display:flex;flex-direction:column;gap:6px;}
.invoice-row{display:flex;justify-content:space-between;font-size:12px;color:var(--text1);}
.invoice-row.total{border-top:1px solid var(--border);padding-top:8px;margin-top:4px;font-weight:600;color:var(--text0);}
.invoice-footer{margin-top:12px;display:flex;gap:7px;}

/* REGISTRATION TAB */
.reg-success-banner{background:linear-gradient(135deg,#1fd6a012,#4f8fff10);border:1px solid #1fd6a020;border-radius:13px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;}
.reg-check{width:48px;height:48px;border-radius:50%;background:var(--gbg);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:22px;}
.reg-form-section{background:var(--bg2);border:1px solid var(--border);border-radius:11px;overflow:hidden;margin-bottom:14px;}
.reg-form-section-head{display:flex;align-items:center;gap:8px;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--bg3);}
.reg-form-section-head span{font-size:12px;font-weight:600;color:var(--text0);}
.reg-form-section-body{padding:14px 16px;}
.locked-state{background:var(--bg3);border:1px solid var(--border);border-radius:11px;padding:32px;text-align:center;}

/* BATCH CARDS */
.bgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(295px,1fr));gap:12px;}
.bcard{background:var(--bg2);border:1px solid var(--border);border-radius:13px;overflow:hidden;transition:border-color .18s,transform .18s;position:relative;}
.bcard:hover{border-color:var(--border2);transform:translateY(-1px);}
.bcard-glow{position:absolute;top:0;left:0;right:0;height:1px;}
.bcard-head{padding:13px 15px 10px;}
.bctag{display:inline-flex;padding:3px 9px;border-radius:20px;font-size:9px;font-weight:700;letter-spacing:.05em;margin-bottom:7px;}
.bctag-fs{background:#3b72e018;color:#60a5fa;border:1px solid #60a5fa1a;}
.bctag-ui{background:#9b7ef818;color:#c084fc;border:1px solid #c084fc1a;}
.bctag-dm{background:#f5b42918;color:var(--amber);border:1px solid #f5b42920;}
.bctag-da{background:#1fd6a018;color:var(--green);border:1px solid #1fd6a020;}
.bcard-name{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--text0);}
.bcard-mentor{font-size:11px;color:var(--text3);margin-top:2px;}
.bcard-body{display:grid;grid-template-columns:1fr 1fr;gap:7px;padding:10px 15px;border-top:1px solid var(--border);}
.bsl{font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:1px;}
.bsv{font-size:12px;color:var(--text0);font-weight:500;}
.bcard-foot{display:flex;align-items:center;gap:7px;padding:9px 15px;border-top:1px solid var(--border);background:var(--bg3);}
.stu-pill{display:flex;align-items:center;gap:4px;font-size:11px;color:var(--text2);margin-right:auto;}
.live-badge{background:var(--rbg);color:var(--red);font-size:9px;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #f0646420;animation:blr 1.8s infinite;}
@keyframes blr{0%,100%{opacity:1;}50%{opacity:.4;}}
.up-badge{background:var(--glow);color:var(--accent);font-size:9px;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #4f8fff20;}
.bsec-title{display:flex;align-items:center;gap:10px;font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--text0);margin:18px 0 10px;}
.bsec-line{flex:1;height:1px;background:var(--border);}

/* BATCH FULL WIN */
.bfw-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:18px;}
.bfw-item{background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:10px 12px;}
.stu-table{width:100%;border-collapse:collapse;}
.stu-table th{font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;padding:7px 11px;text-align:left;border-bottom:1px solid var(--border);}
.stu-table td{padding:10px 11px;font-size:12px;border-bottom:1px solid var(--border);color:var(--text1);}
.stu-table tr:last-child td{border-bottom:none;}
.stu-table tr:hover td{background:var(--bg3);}
.empty-state{text-align:center;padding:32px;color:var(--text3);font-size:12px;}
.empty-icon{font-size:32px;display:block;margin-bottom:10px;}

/* ALLOCATE MODAL */
.modal-wrap{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:600;display:none;align-items:flex-start;justify-content:center;padding:48px 20px;}
.modal-wrap.open{display:flex;}
.modal{background:var(--bg1);border:1px solid var(--border2);border-radius:14px;width:500px;max-width:96vw;overflow:hidden;animation:mi .2s ease;}
@keyframes mi{from{opacity:0;transform:scale(.96)translateY(8px);}to{opacity:1;transform:scale(1)translateY(0);}}
.modal-head{display:flex;align-items:center;gap:10px;padding:16px 20px;border-bottom:1px solid var(--border);background:var(--bg2);}
.modal-title{font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:var(--text0);flex:1;}
.modal-body{padding:20px;}
.alloc-srch{width:100%;background:var(--bg2);border:1px solid var(--border2);border-radius:9px;padding:9px 13px;font-size:12px;color:var(--text0);font-family:'DM Sans',sans-serif;outline:none;transition:border-color .15s;margin-bottom:12px;}
.alloc-srch:focus{border-color:var(--accent);}
.alloc-list{background:var(--bg2);border:1px solid var(--border);border-radius:11px;overflow:hidden;max-height:260px;overflow-y:auto;}
.alloc-row{display:flex;align-items:center;gap:11px;padding:10px 13px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .14s;}
.alloc-row:hover{background:var(--bg3);}
.alloc-row.sel{background:var(--glow);border-left:2px solid var(--accent);}
.alloc-row:last-child{border-bottom:none;}
.divider{height:1px;background:var(--border);margin:14px 0;}

/* FOLLOWUP MODAL */
.fu-modal-wrap{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:600;display:none;align-items:flex-start;justify-content:center;padding:48px 20px;}
.fu-modal-wrap.open{display:flex;}

/* PAYMENTS PAGE */
.lt-head-pay{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr 130px;background:var(--bg2);border-bottom:1px solid var(--border);}
.lrow-pay{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr 130px;border-bottom:1px solid var(--border);transition:background .14s;}
.lrow-pay:last-child{border-bottom:none;}

/* REG FORMS PAGE */
.lt-head-reg{display:grid;grid-template-columns:2fr 1.2fr 1fr 1fr 1fr 130px;background:var(--bg2);border-bottom:1px solid var(--border);}
.lrow-reg{display:grid;grid-template-columns:2fr 1.2fr 1fr 1fr 1fr 130px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .14s;}
.lrow-reg:last-child{border-bottom:none;}
.lrow-reg:hover{background:var(--bg2);}

/* PIPELINE */
.pipeline-row{display:flex;align-items:center;gap:10px;background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:10px 13px;margin-bottom:7px;}
.pip-label{flex:1;font-size:13px;color:var(--text1);}
.pip-count{font-family:'Syne',sans-serif;font-size:15px;font-weight:700;}
.pip-bar-wrap{width:70px;height:3px;background:var(--bg5);border-radius:4px;}
.pip-bar{height:3px;border-radius:4px;}

.link-box{background:var(--bg3);border:1px solid var(--border2);border-radius:9px;padding:10px 14px;display:flex;align-items:center;gap:10px;margin-bottom:10px;}
.link-url{font-size:12px;color:var(--accent);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.reg-link-card{background:linear-gradient(135deg,#4f8fff10,#9b7ef810);border:1px solid #4f8fff20;border-radius:13px;padding:16px 18px;margin-bottom:14px;}
</style>
    @stack('styles')
</head>
<body>
<div class="shell">
    <!-- ═══ SIDEBAR ═══ -->
    <div class="sb">
        <div class="sb-logo">
            <div class="sb-logo-inner">
                <div class="sb-mark">AP</div>
                <div><div class="sb-brand">AdmitPro</div><div class="sb-sub">CRM Platform</div></div>
            </div>
        </div>
        <div class="sb-nav">
            @yield('sidebar')
        </div>
        <div class="sb-foot">
            <div class="sb-user">
                <div class="sb-av">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</div>
                <div>
                    <div class="sb-uname">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="sb-urole">
                        <div class="sb-dot"></div>
                        <span>{{ Auth::user()->roles->first()->name ?? 'Role' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══ MAIN ═══ -->
    <div class="main">
        <div class="topbar">
            <div class="tb-title" id="page-title">@yield('page_title', 'Dashboard') <span class="tb-crumb">@yield('page_breadcrumb', '/ Overview')</span></div>
            <div class="tb-srch">
                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" style="color:var(--text3);flex-shrink:0;"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/></svg>
                <input type="text" placeholder="Search...">
            </div>
            <div class="notif"><svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="color:var(--text2);"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a3 3 0 01-2.83-2h5.66A3 3 0 0110 18z"/></svg><div class="notif-dot"></div></div>
            @yield('topbar_actions')
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
