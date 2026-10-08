<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AdmitPro CRM</title>
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
</head>
<body>
<div class="shell">

<!-- ═══ SIDEBAR ═══ -->
  @include('partials.sidebar', ['role' => 'admission'])

  <div class="main">
  <div class="topbar">
    <div class="tb-title" id="page-title">Dashboard <span class="tb-crumb">/ Overview</span></div>
    <div class="tb-srch">
      <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" style="color:var(--text3);flex-shrink:0;"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"/></svg>
      <input type="text" placeholder="Search leads, students…">
    </div>
    <div class="notif"><svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="color:var(--text2);"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a3 3 0 01-2.83-2h5.66A3 3 0 0110 18z"/></svg><div class="notif-dot"></div></div>
    <button class="btn btn-primary btn-sm">+ Add Lead</button>
  </div>

  <div class="content">

    <!-- DASHBOARD -->
    
        @yield('content')
    <div class="fullwin" id="lead-fw">
  <!-- topbar -->
  <div class="fw-topbar">
    <button class="btn btn-ghost btn-sm" onclick="closeLead()">
      <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
      Back to Leads
    </button>
    <div id="fw-lead-name" style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text0);flex:1;margin-left:10px;"></div>
    <div id="fw-lead-tag-bar"></div>
    <div style="display:flex;gap:7px;margin-left:12px;">
      <button class="btn btn-sm btn-green">📞 Call</button>
      <button class="btn btn-sm">💬 WhatsApp</button>
      <button class="btn btn-sm btn-amber" onclick="openFUModal()">📅 Follow-up</button>
    </div>
  </div>

  <div class="stu-fw-body" id="fw-lead-body">
    <!-- rendered by JS -->
  </div>
</div>

<!-- ═══ BATCH FULL WINDOW ═══ -->
<div class="fullwin" id="batch-fw">
  <div class="fw-topbar">
    <button class="btn btn-ghost btn-sm" onclick="closeBatchFW()">← Back to Batches</button>
    <div id="fw-batch-name" style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text0);flex:1;margin-left:10px;"></div>
    <span id="fw-batch-tag" class="bctag"></span>
    <button class="btn btn-primary btn-sm" style="margin-left:12px;" onclick="openAllocModal(curBatchId)">+ Allocate Student</button>
  </div>
  <div style="overflow-y:auto;padding:24px 28px;" id="fw-batch-body"></div>
</div>

<!-- ALLOCATE MODAL -->
<div class="modal-wrap" id="alloc-modal" onclick="if(event.target===this)closeAllocModal()">
  <div class="modal">
    <div class="modal-head"><div class="modal-title">Allocate Student to Batch</div><button class="btn btn-ghost btn-sm" onclick="closeAllocModal()">✕</button></div>
    <div class="modal-body">
      <div id="alloc-info" style="background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:11px 13px;margin-bottom:12px;font-size:12px;color:var(--text2);"></div>
      <input class="alloc-srch" id="alloc-search" placeholder="Search registered students by name or phone…" oninput="searchStudents(this.value)">
      <div class="alloc-list" id="alloc-results"></div>
      <div class="divider"></div>
      <div style="display:flex;justify-content:flex-end;gap:7px;">
        <button class="btn btn-sm" onclick="closeAllocModal()">Cancel</button>
        <button class="btn btn-sm btn-primary" onclick="confirmAlloc()">Confirm Allocation</button>
      </div>
    </div>
  </div>
</div>

<!-- FOLLOWUP MODAL -->
<div class="fu-modal-wrap" id="fu-modal" onclick="if(event.target===this)closeFUModal()">
  <div class="modal">
    <div class="modal-head"><div class="modal-title">Set Follow-up</div><button class="btn btn-ghost btn-sm" onclick="closeFUModal()">✕</button></div>
    <div class="modal-body">
      <div id="fu-lead-info" style="background:var(--bg3);border:1px solid var(--border);border-radius:9px;padding:11px 13px;margin-bottom:12px;font-size:12px;color:var(--text2);"></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
        <div class="fg"><label>Follow-up Date</label><input type="date" id="fu-date" style="background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:8px 11px;font-size:12px;color:var(--text0);outline:none;font-family:'DM Sans',sans-serif;"></div>
        <div class="fg"><label>Time</label><input type="time" id="fu-time" style="background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:8px 11px;font-size:12px;color:var(--text0);outline:none;font-family:'DM Sans',sans-serif;" value="10:00"></div>
      </div>
      <div class="fg" style="margin-bottom:12px;"><label>Note / Reason</label><textarea id="fu-note" style="background:var(--bg3);border:1px solid var(--border2);border-radius:8px;padding:8px 11px;font-size:12px;color:var(--text0);resize:none;min-height:65px;font-family:'DM Sans',sans-serif;outline:none;" placeholder="e.g. Said will join within 1 week, call back Monday…"></textarea></div>
      <div class="divider"></div>
      <div style="display:flex;justify-content:flex-end;gap:7px;">
        <button class="btn btn-sm" onclick="closeFUModal()">Cancel</button>
        <button class="btn btn-sm btn-primary" onclick="confirmFollowup()">Save Follow-up</button>
      </div>
    </div>
  </div>
</div>

<script>
// ═══════════ DATA ═══════════
let LEADS = [
  {id:1, name:'Arjun Mehta',    phone:'98xx-xxx-211', date:'24 Apr', source:'Facebook',  tag:'converted',    init:'AM', course:'Fullstack',          bg:'#4f8fff18', clr:'#60a5fa'},
  {id:2, name:'Sneha Pillai',   phone:'91xx-xxx-302', date:'23 Apr', source:'Instagram', tag:'confirmed',    init:'SP', course:'UI/UX',               bg:'#9b7ef818', clr:'#c084fc'},
  {id:3, name:'Rahul Das',      phone:'87xx-xxx-419', date:'22 Apr', source:'Google',    tag:'converted',    init:'RD', course:'Data Analytics',      bg:'#1fd6a018', clr:'#1fd6a0'},
  {id:4, name:'Kavya Suresh',   phone:'76xx-xxx-155', date:'21 Apr', source:'Referral',  tag:'registered',   init:'KS', course:'Digital Marketing',   bg:'#f5b42918', clr:'#f5b429'},
  {id:5, name:'Imran Khan',     phone:'99xx-xxx-087', date:'20 Apr', source:'Walk-in',   tag:'confirmed',    init:'IK', course:'Fullstack',            bg:'#4f8fff18', clr:'#60a5fa'},
  {id:6, name:'Divya Sharma',   phone:'88xx-xxx-963', date:'19 Apr', source:'YouTube',   tag:'payment-done', init:'DS', course:'UI/UX',               bg:'#9b7ef818', clr:'#c084fc'},
  {id:7, name:'Mithun Roy',     phone:'77xx-xxx-541', date:'18 Apr', source:'Facebook',  tag:'followup',     init:'MR', course:'Digital Marketing',   bg:'#f9731618', clr:'#f97316'},
  {id:8, name:'Nisha Patel',    phone:'80xx-xxx-220', date:'17 Apr', source:'Google',    tag:'registered',   init:'NP', course:'Data Analytics',      bg:'#1fd6a018', clr:'#1fd6a0'},
  {id:9, name:'Arun Krishnan',  phone:'95xx-xxx-441', date:'16 Apr', source:'Referral',  tag:'payment',      init:'AK', course:'Fullstack',            bg:'#4f8fff18', clr:'#60a5fa'},
];

let FOLLOWUPS = [
  {id:1, leadId:7, name:'Mithun Roy',   phone:'77xx-xxx-541', date:'28 Apr 2026', time:'10:00', note:'Said will join within 1 week', status:'pending'},
  {id:2, leadId:1, name:'Arjun Mehta',  phone:'98xx-xxx-211', date:'25 Apr 2026', time:'11:30', note:'Wants more info on batch schedule', status:'overdue'},
  {id:3, leadId:3, name:'Rahul Das',    phone:'87xx-xxx-419', date:'27 Apr 2026', time:'15:00', note:'Considering Data Analytics batch', status:'pending'},
];

let BATCHES = [
  {id:1, code:'FS-APR-21', course:'Fullstack',         mentor:'Arun Kumar', type:'Weekday', hours:120, start:'21 Apr 2026', lastDate:'28 Apr 2026', status:'started',  allocatable:true,  students:['Kavya Suresh']},
  {id:2, code:'UI-APR-22', course:'UI/UX',             mentor:'Meena Iyer', type:'Weekend', hours:80,  start:'22 Apr 2026', lastDate:'29 Apr 2026', status:'started',  allocatable:true,  students:[]},
  {id:3, code:'FS-MAY-01', course:'Fullstack',         mentor:'Arun Kumar', type:'Weekday', hours:120, start:'1 May 2026',  lastDate:'8 May 2026',  status:'upcoming', allocatable:true,  students:[]},
  {id:4, code:'UI-MAY-03', course:'UI/UX',             mentor:'Meena Iyer', type:'Weekend', hours:80,  start:'3 May 2026',  lastDate:'10 May 2026', status:'upcoming', allocatable:true,  students:[]},
  {id:5, code:'DM-MAY-05', course:'Digital Marketing', mentor:'Preethi V',  type:'Weekday', hours:60,  start:'5 May 2026',  lastDate:'12 May 2026', status:'upcoming', allocatable:true,  students:['Kavya Suresh']},
  {id:6, code:'DA-MAY-02', course:'Data Analytics',    mentor:'Suresh R',   type:'Weekend', hours:90,  start:'2 May 2026',  lastDate:'9 May 2026',  status:'upcoming', allocatable:true,  students:[]},
];

let ALLOC = {};
let curLeadId = null, curBatchId = null, curFilter = 'all', batchFilter = 'all', selStu = null;
let activeStudentTab = 'calls'; // tracks which sub-tab is active in student detail
let activePaySubTab = 'link';

// ═══════════ HELPERS ═══════════
function tClass(t){return{converted:'tag-converted',confirmed:'tag-confirmed',payment:'tag-payment','payment-done':'tag-payment-done',registered:'tag-registered',followup:'tag-followup'}[t]||'tag-converted';}
function tLabel(t){return{converted:'Converted',confirmed:'Confirmed',payment:'Payment Sent','payment-done':'Payment Done',registered:'Registered',followup:'Follow-up'}[t]||t;}
function cTagC(c){return{'Fullstack':'bctag-fs','UI/UX':'bctag-ui','Digital Marketing':'bctag-dm','Data Analytics':'bctag-da'}[c]||'bctag-fs';}
function cGlow(c){return{'Fullstack':'#60a5fa','UI/UX':'#c084fc','Digital Marketing':'#f5b429','Data Analytics':'#1fd6a0'}[c]||'#60a5fa';}
function inits(n){return n.split(' ').map(w=>w[0]).join('').slice(0,2);}

// ═══════════ PAGE NAV ═══════════
function showPage(p, el){
  document.querySelectorAll('.page').forEach(x=>x.classList.remove('active'));
  document.querySelectorAll('.si').forEach(x=>x.classList.remove('active'));
  const pg = document.getElementById('page-'+p); if(pg) pg.classList.add('active');
  const si = el || document.getElementById('si-'+p); if(si) si.classList.add('active');
  const T = {dashboard:'Dashboard',leads:'Leads',followups:'Follow-ups',payments:'Payments',batches:'Batch Allocation'};
  const C = {dashboard:'/ Overview',leads:'/ All Leads',followups:'/ Pending Callbacks',payments:'/ Payment Tracker',batches:'/ All Batches'};
  document.getElementById('page-title').innerHTML = `${T[p]||p} <span class="tb-crumb">${C[p]||''}</span>`;
  if(p==='leads') renderLeads();
  if(p==='followups') renderFollowups();
  if(p==='payments') renderPayments();
  if(p==='batches') renderBatches(batchFilter);
}

// ═══════════ DASHBOARD ═══════════
function renderDashboard(){
  const recent = LEADS.slice(0,5);
  document.getElementById('dash-recent').innerHTML = recent.map(l=>`
    <div class="mtr" onclick="openLead(${l.id})">
      <div class="mtd"><div style="display:flex;align-items:center;gap:8px;"><div class="lav" style="width:28px;height:28px;border-radius:7px;background:${l.bg};color:${l.clr};font-size:10px;">${l.init}</div><div><div style="font-size:12px;font-weight:500;color:var(--text0);">${l.name}</div><div style="font-size:10px;color:var(--text3);">${l.course}</div></div></div></div>
      <div class="mtd" style="font-size:11px;color:var(--text2);">${l.phone}</div>
      <div class="mtd" style="font-size:11px;color:var(--text2);">${l.date}</div>
      <div class="mtd"><span class="tag ${tClass(l.tag)}" style="font-size:10px;">${tLabel(l.tag)}</span></div>
    </div>`).join('');

  const counts = {converted:0,confirmed:0,payment:0,'payment-done':0,registered:0};
  LEADS.forEach(l=>{ if(counts[l.tag]!==undefined) counts[l.tag]++; });
  const total = LEADS.length||1;
  const pipe = [
    {label:'Converted',     count:counts.converted,         color:'var(--amber)'},
    {label:'Confirmed',     count:counts.confirmed,         color:'var(--green)'},
    {label:'Payment Sent',  count:counts.payment,           color:'var(--purple)'},
    {label:'Payment Done',  count:counts['payment-done'],   color:'#22d3ee'},
    {label:'Registered',    count:counts.registered,        color:'var(--accent)'},
  ];
  document.getElementById('dash-pipeline').innerHTML = pipe.map(p=>`
    <div class="pipeline-row">
      <div style="width:8px;height:8px;border-radius:50%;background:${p.color};flex-shrink:0;"></div>
      <div class="pip-label">${p.label}</div>
      <div class="pip-bar-wrap"><div class="pip-bar" style="width:${Math.round(p.count/total*100)}%;background:${p.color};"></div></div>
      <div class="pip-count" style="color:${p.color};">${p.count}</div>
    </div>`).join('');
}

// ═══════════ LEADS ═══════════
function renderLeads(){
  let data = curFilter==='all' ? LEADS : LEADS.filter(l=>l.tag===curFilter);
  document.getElementById('leads-count').textContent = `${data.length} leads`;
  document.getElementById('leads-body').innerHTML = data.length
    ? data.map(l=>`
      <div class="lrow-leads" onclick="openLead(${l.id})">
        <div class="td"><div class="lc"><div class="lav" style="background:${l.bg};color:${l.clr};">${l.init}</div><div><div class="ln">${l.name}</div><div class="ls">${l.course}</div></div></div></div>
        <div class="td" style="color:var(--text2);">${l.phone}</div>
        <div class="td" style="color:var(--text2);">${l.date}</div>
        <div class="td"><span class="tag tag-source">${l.source}</span></div>
        <div class="td"><span class="tag ${tClass(l.tag)}">${tLabel(l.tag)}</span></div>
      </div>`).join('')
    : '<div class="empty-state"><span class="empty-icon">🔍</span>No leads for this filter</div>';
}
function filterLeads(f, el){
  curFilter = f;
  document.querySelectorAll('#leads-filters .ftab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  renderLeads();
}

// ═══════════ STUDENT DETAIL FULL WINDOW ═══════════
function openLead(id){
  const l = LEADS.find(x=>x.id===id);
  if(!l) return;
  curLeadId = id;
  activeStudentTab = 'calls';
  activePaySubTab = 'link';
  document.getElementById('fw-lead-name').textContent = l.name;
  document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag ${tClass(l.tag)}">${tLabel(l.tag)}</span>`;
  renderStudentFW(l);
  document.getElementById('lead-fw').classList.add('open');
}

function closeLead(){
  document.getElementById('lead-fw').classList.remove('open');
  renderLeads(); renderDashboard();
}

function renderStudentFW(l){
  const payEnabled = ['confirmed','payment','payment-done','registered'].includes(l.tag);
  const regEnabled = l.tag === 'confirmed' || ['payment','payment-done','registered'].includes(l.tag);

  document.getElementById('fw-lead-body').innerHTML = `
    <!-- 1. PROFILE HEADER -->
    <div class="stu-profile-strip">
      <div class="stu-big-av" style="background:${l.bg};color:${l.clr};">${l.init}</div>
      <div style="flex:1;">
        <div class="stu-name">${l.name}</div>
        <div class="stu-course">${l.course} · ${l.source}</div>
        <div class="stu-meta-row">
          <div class="stu-meta-pill">📞 ${l.phone}</div>
          <div class="stu-meta-pill">📅 ${l.date}</div>
          <div class="stu-meta-pill">🎓 ${l.course}</div>
          <span class="tag ${tClass(l.tag)}" style="font-size:12px;">${tLabel(l.tag)}</span>
        </div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:10px;color:var(--text3);margin-bottom:4px;">LEAD ID</div>
        <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--accent);">#L-00${l.id}</div>
      </div>
    </div>

    <!-- 2. PROFILE INFO GRID -->
    <div class="stu-profile-grid">
      <div class="stu-profile-grid-inner">
        <div class="ii"><div class="il">Full Name</div><div class="iv">${l.name}</div></div>
        <div class="ii"><div class="il">Phone</div><div class="iv">${l.phone}</div></div>
        <div class="ii"><div class="il">Email</div><div class="iv">—</div></div>
        <div class="ii"><div class="il">Date of Birth</div><div class="iv">—</div></div>
        <div class="ii"><div class="il">Course Interest</div><div class="iv">${l.course}</div></div>
        <div class="ii"><div class="il">Lead Source</div><div class="iv">${l.source}</div></div>
        <div class="ii"><div class="il">Date Added</div><div class="iv">${l.date}</div></div>
        <div class="ii"><div class="il">City</div><div class="iv">—</div></div>
      </div>
    </div>

    <!-- 3. TABS -->
    <div class="stu-tabs-bar">
      <div class="stu-tab ${activeStudentTab==='calls'?'active':''}" onclick="switchStudentTab('calls',this)">
        <svg class="stu-tab-icon" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
        Call History
      </div>
      <div class="stu-tab ${activeStudentTab==='registration'?'active':''}" onclick="switchStudentTab('registration',this)">
        <svg class="stu-tab-icon" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/></svg>
        Registration
        ${regEnabled ? '' : '<span style="font-size:9px;background:var(--rbg);color:var(--red);padding:2px 6px;border-radius:20px;margin-left:4px;">Locked</span>'}
      </div>
      <div class="stu-tab ${activeStudentTab==='payment'?'active':''}" onclick="switchStudentTab('payment',this)">
        <svg class="stu-tab-icon" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v2H4V6zm0 4h3v2H4v-2zm4 0h3v2H8v-2z"/></svg>
        Payment History
      </div>
    </div>

    <!-- 4. TAB CONTENT -->
    <div class="stu-tab-content">
      ${renderCallsTab(l)}
      ${renderRegistrationTab(l, regEnabled)}
      ${renderPaymentTab(l, payEnabled)}
    </div>
  `;
}

function switchStudentTab(tab, el){
  activeStudentTab = tab;
  document.querySelectorAll('.stu-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.tab-pane').forEach(p=>p.classList.remove('active'));
  document.getElementById('tab-'+tab).classList.add('active');
}

// ──────── CALLS TAB — TIMELINE ────────
function renderCallsTab(l){
  if(!window.CALL_DATA) window.CALL_DATA = {};
  if(!window.CALL_DATA[l.id]) window.CALL_DATA[l.id] = {
    prevCollapsed: false,
    currentNote: '',
    currentTag: '',
    prevCalls: [
      {time:'03:30 PM', date:'21 Apr', dir:'OUTGOING', duration:'2m 10s', owner:'Self', tag:'WARM', note:'Called back, confirmed interest'}
    ]
  };
  const cd = window.CALL_DATA[l.id];

  const prevRows = cd.prevCalls.map(c=>`
    <div class="tl-row">
      <div class="tl-cell"><div class="tl-time">${c.time}</div><div class="tl-time-date">${c.date}</div></div>
      <div class="tl-cell"><span class="dir-badge ${c.dir==='OUTGOING'?'dir-out':'dir-in'}">
        ${c.dir==='OUTGOING'
          ? `<svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>`
          : `<svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>`}
        ${c.dir}
      </span></div>
      <div class="tl-cell">
        <div class="play-btn"><div class="play-dot"></div> Play ${c.duration}</div>
      </div>
      <div class="tl-cell" style="color:var(--text0);font-weight:500;">${c.duration}</div>
      <div class="tl-cell" style="color:var(--text0);">${c.owner}</div>
      <div class="tl-cell">
        <span style="background:var(--abg);color:var(--amber);border:1px solid #f5b42922;padding:3px 9px;border-radius:6px;font-size:11px;font-weight:700;">${c.tag}</span>
      </div>
      <div class="tl-cell" style="font-size:12px;color:var(--text1);">${c.note}</div>
    </div>`).join('');

  return `<div class="tab-pane ${activeStudentTab==='calls'?'active':''}" id="tab-calls">
  <div style="max-width:960px;">
    <div class="tl-wrap">

      <!-- TIMELINE HEADER -->
      <div class="tl-header">
        <div class="tl-title">
          <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="color:var(--accent);"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
          Timeline
          <span class="tl-title-sub">(Previous calls + current active call)</span>
        </div>
        <button class="btn btn-sm btn-primary" onclick="addCallEntry(${l.id})">+ Add Entry</button>
      </div>

      <!-- COLUMN HEADERS -->
      <div class="tl-section-label">
        <span>CALL TIME</span>
        <span>DIRECTION</span>
        <span>RECORDING</span>
        <span>DURATION</span>
        <span>OWNED BY</span>
        <span>TAG</span>
        <span>NOTES</span>
      </div>

      <!-- PREVIOUS SECTION -->
      <div class="tl-section-head">
        <div style="width:8px;height:8px;border-radius:50%;background:var(--text3);flex-shrink:0;"></div>
        <div class="tl-section-title">PREVIOUS</div>
        <div class="tl-section-count">(${cd.prevCalls.length} call${cd.prevCalls.length!==1?'s':''} — already spoken)</div>
        <button class="tl-collapse-btn" onclick="togglePrevCalls(${l.id},this)">
          <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor" id="prev-arrow-${l.id}" style="transform:${cd.prevCollapsed?'rotate(180deg)':''}"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
          ${cd.prevCollapsed ? 'Show' : 'Hide'}
        </button>
      </div>
      <div id="prev-calls-${l.id}" style="display:${cd.prevCollapsed?'none':'block'};">
        ${prevRows}
      </div>

      <!-- CURRENT SECTION -->
      <div class="tl-section-head" style="background:linear-gradient(90deg,#4f8fff10,var(--bg3));border-top:1px solid var(--border2);">
        <div class="current-dot"></div>
        <div class="tl-section-title" style="color:var(--accent);">CURRENT</div>
        <div class="tl-section-count" style="color:var(--text2);">(Active call being handled)</div>
      </div>

      <!-- CURRENT CALL ROW — with column headers: NOTES + ASSIGN -->
      <div style="display:grid;grid-template-columns:160px 130px 150px 90px 110px 90px 1fr;background:var(--bg2);border-bottom:1px solid var(--border);padding:8px 18px;">
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">CALL TIME</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">DIRECTION</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">RECORDING</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">DURATION</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">OWNED BY</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">TAG</span>
        <span style="font-size:9px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.1em;">NOTES + ASSIGN</span>
      </div>
      <div class="tl-row current-row">
        <div class="tl-cell"><div class="tl-time">09:14 AM</div><div class="tl-time-date">22 Apr</div></div>
        <div class="tl-cell"><span class="dir-badge dir-in">
          <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
          INCOMING
        </span></div>
        <div class="tl-cell"><div class="play-btn"><div class="play-dot"></div> Play 4m 32s</div></div>
        <div class="tl-cell" style="color:var(--text0);font-weight:500;">4m 32s</div>
        <div class="tl-cell" style="color:var(--text0);">Self</div>
        <div class="tl-cell">
          <select class="call-tag-select" id="cur-tag-${l.id}" onchange="updateCurrentTag(${l.id},this.value)">
            <option value="">Select tag</option>
            <option value="hot" ${cd.currentTag==='hot'?'selected':''}>hot</option>
            <option value="warm" ${cd.currentTag==='warm'?'selected':''}>warm</option>
            <option value="cold" ${cd.currentTag==='cold'?'selected':''}>cold</option>
            <option value="confirmed" ${cd.currentTag==='confirmed'?'selected':''}>confirmed</option>
            <option value="not-interested" ${cd.currentTag==='not-interested'?'selected':''}>not interested</option>
            <option value="followup" ${cd.currentTag==='followup'?'selected':''}>follow-up</option>
          </select>
        </div>
        <div class="tl-cell" style="display:flex;flex-direction:column;gap:6px;">
          <input class="tl-note-input" id="cur-note-${l.id}" placeholder="Add notes from this call…" value="${cd.currentNote}">
          <button class="assign-btn" onclick="assignCallTag(${l.id})">
            <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 116 0 3 3 0 01-6 0zM2 15.5a7.5 7.5 0 1115 0"/></svg>
            Assign
          </button>
        </div>
      </div>

    </div>
  </div>
</div>`;
}

// ──────── PAYMENT TAB ────────
function renderPaymentTab(l, payEnabled){
  return `<div class="tab-pane ${activeStudentTab==='payment'?'active':''}" id="tab-payment">
    ${!payEnabled ? `
      <div class="locked-state">
        <div style="font-size:36px;margin-bottom:12px;">🔒</div>
        <div style="font-size:14px;font-weight:600;color:var(--text0);margin-bottom:6px;">Payment Section Locked</div>
        <div style="font-size:12px;color:var(--text3);">Mark the lead as <strong style="color:var(--green);">Confirmed</strong> to unlock payment features</div>
      </div>` : `
    <div style="max-width:760px;">
      <!-- Sub-tabs: Payment Link | Invoice -->
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div class="pay-sub-tabs">
          <button class="pay-stab ${activePaySubTab==='link'?'active':''}" onclick="switchPayTab('link',this)">Payment Link</button>
          <button class="pay-stab ${activePaySubTab==='invoice'?'active':''}" onclick="switchPayTab('invoice',this)">Paid Invoice</button>
        </div>
        <div class="rzp" style="font-size:10px;padding:4px 10px;">RAZORPAY</div>
      </div>

      <!-- PAYMENT LINK PANE -->
      <div class="pay-stab-pane ${activePaySubTab==='link'?'active':''}" id="psub-link">
        <div class="pay-card">
          <div class="pay-hdr"><div><div style="font-size:13px;font-weight:600;color:var(--text0);">Generate Payment Link</div><div style="font-size:11px;color:var(--text3);margin-top:1px;">Secure · Instant · Auto-tracked in CRM</div></div></div>
          <div class="pay-form">
            <div class="fg"><label>Student Name</label><input value="${l.name}" readonly style="opacity:.7;"></div>
            <div class="fg"><label>Phone</label><input value="${l.phone}" readonly style="opacity:.7;"></div>
            <div class="fg"><label>Course</label><select><option>${l.course}</option><option>Fullstack</option><option>UI/UX</option><option>Digital Marketing</option><option>Data Analytics</option></select></div>
            <div class="fg"><label>Amount (₹)</label><input type="number" value="35000"></div>
            <div class="fg"><label>Fee Type</label><select><option>Full Payment</option><option>Part — 50%</option><option>EMI — 3 months</option></select></div>
            <div class="fg"><label>Link Expiry</label><input type="date" value="2026-05-01"></div>
            <div class="fg full"><label>Description</label><input value="${l.course} Course Fee — Batch 2026"></div>
          </div>
          <div class="pay-acts">
            <button class="btn btn-sm btn-primary" onclick="generatePayLink(${l.id})">⚡ Generate Link</button>
            <button class="btn btn-sm btn-green">Send via WhatsApp</button>
            <button class="btn btn-sm">Send via Email</button>
            <button class="btn btn-sm">Copy Link</button>
          </div>
        </div>

        <!-- Payment link send history -->
        <div style="font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;margin-bottom:8px;">Link Send History</div>
        <div class="pay-hist-wrap">
          ${l.tag==='payment'||l.tag==='payment-done'||l.tag==='registered' ? `
          <div class="pay-hist-row">
            <div class="pdot ${l.tag==='payment'?'pending':'paid'}"></div>
            <div class="pay-hist-info">
              <div class="pay-hist-title">₹35,000 — Link Sent via WhatsApp</div>
              <div class="pay-hist-sub">Sent 24 Apr 2026 · Expires 1 May 2026 · ${l.tag==='payment'?'Awaiting payment':'Payment received ✓'}</div>
            </div>
            <span class="tag ${l.tag==='payment'?'tag-payment':'tag-payment-done'}" style="font-size:10px;">${l.tag==='payment'?'Pending':'Paid'}</span>
            ${l.tag==='payment'?`<button class="btn btn-xs btn-amber" style="margin-left:8px;">Resend</button>`:''}
          </div>` : `
          <div class="empty-state" style="padding:20px;"><span style="font-size:20px;display:block;margin-bottom:6px;">📋</span>No payment links sent yet</div>`}
        </div>
      </div>

      <!-- INVOICE PANE -->
      <div class="pay-stab-pane ${activePaySubTab==='invoice'?'active':''}" id="psub-invoice">
        ${l.tag==='payment-done'||l.tag==='registered' ? `
        <div class="invoice-card">
          <div class="invoice-header">
            <div>
              <div class="invoice-no">Invoice #INV-2026-00${l.id}</div>
              <div class="invoice-date">Issued: 24 Apr 2026 · Paid via UPI</div>
            </div>
            <div style="text-align:right;">
              <span class="tag tag-confirmed" style="font-size:11px;">Paid ✓</span>
              <div style="font-size:20px;font-weight:700;color:var(--green);font-family:'Syne',sans-serif;margin-top:6px;">₹35,000</div>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
            <div><div style="font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Student</div><div style="font-size:12px;color:var(--text0);font-weight:500;">${l.name}</div></div>
            <div><div style="font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Phone</div><div style="font-size:12px;color:var(--text0);font-weight:500;">${l.phone}</div></div>
            <div><div style="font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Course</div><div style="font-size:12px;color:var(--text0);font-weight:500;">${l.course}</div></div>
            <div><div style="font-size:9px;color:var(--text3);text-transform:uppercase;letter-spacing:.08em;margin-bottom:2px;">Batch</div><div style="font-size:12px;color:var(--text0);font-weight:500;">FS-APR-21</div></div>
          </div>
          <div class="invoice-rows">
            <div class="invoice-row"><span>Course Fee</span><span>₹35,000</span></div>
            <div class="invoice-row"><span>GST (0%)</span><span>₹0</span></div>
            <div class="invoice-row total"><span>Total Paid</span><span style="color:var(--green);">₹35,000</span></div>
          </div>
          <div class="invoice-footer">
            <button class="btn btn-sm btn-green">⬇ Download PDF</button>
            <button class="btn btn-sm">Send to Student</button>
          </div>
        </div>` : `
        <div class="locked-state">
          <div style="font-size:36px;margin-bottom:12px;">🧾</div>
          <div style="font-size:14px;font-weight:600;color:var(--text0);margin-bottom:6px;">No Invoice Yet</div>
          <div style="font-size:12px;color:var(--text3);">Invoice will appear here once payment is confirmed as <strong style="color:var(--green);">Payment Done</strong></div>
        </div>`}
      </div>
    </div>`}
  </div>`;
}

function switchPayTab(tab, el){
  activePaySubTab = tab;
  document.querySelectorAll('.pay-stab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.pay-stab-pane').forEach(p=>p.classList.remove('active'));
  document.getElementById('psub-'+tab).classList.add('active');
}

// ──────── REGISTRATION TAB ────────
function renderRegistrationTab(l, regEnabled){
  if(!window.REG_DATA) window.REG_DATA = {};
  if(!window.REG_DATA[l.id]) window.REG_DATA[l.id] = {
    saved: false,
    course: l.course, batchCode: '', batchType: '', fees: '', paymentMode: '',
    feeType: 'single', feeSaved: false,
    single: {amount:'', date:''},
    splits: [{amount:'',date:''},{amount:'',date:''}]
  };
  const rd = window.REG_DATA[l.id];

  return `<div class="tab-pane ${activeStudentTab==='registration'?'active':''}" id="tab-registration">
  <div style="max-width:720px;">
  ${!regEnabled ? `
    <div class="locked-state">
      <div style="font-size:36px;margin-bottom:12px;">🔒</div>
      <div style="font-size:14px;font-weight:600;color:var(--text0);margin-bottom:6px;">Registration Locked</div>
      <div style="font-size:12px;color:var(--text3);">Tag the lead as <strong style="color:var(--green);">Confirmed</strong> in the Call History to unlock registration</div>
    </div>` : `

    <!-- ADMISSION DETAILS FORM -->
    <div class="reg-manual-form" id="reg-form-wrap-${l.id}">
      <div class="reg-manual-head">
        <div class="reg-manual-title">Admission Details</div>
        ${rd.saved ? `<button class="btn btn-xs" onclick="regEditDetails(${l.id})">✏ Edit</button>` : ''}
      </div>
      ${rd.saved ? `
      <!-- SAVED VIEW -->
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;padding:16px;">
        <div class="ii"><div class="il">Course</div><div class="iv">${rd.course}</div></div>
        <div class="ii"><div class="il">Batch Code</div><div class="iv">${rd.batchCode||'—'}</div></div>
        <div class="ii"><div class="il">Batch Type</div><div class="iv">${rd.batchType||'—'}</div></div>
        <div class="ii"><div class="il">Course Fees</div><div class="iv" style="color:var(--green);">${rd.fees ? '₹'+Number(rd.fees).toLocaleString('en-IN') : '—'}</div></div>
        <div class="ii"><div class="il">Payment Mode</div><div class="iv">${rd.paymentMode||'—'}</div></div>
      </div>` : `
      <!-- EDIT FORM -->
      <div class="reg-form-grid three">
        <div class="rfield">
          <label>Course</label>
          <select id="rg-course-${l.id}">
            <option value="${l.course}" selected>${l.course}</option>
            <option>Fullstack</option><option>UI/UX</option><option>Digital Marketing</option><option>Data Analytics</option>
          </select>
        </div>
        <div class="rfield">
          <label>Batch Code</label>
          <input id="rg-batch-${l.id}" placeholder="e.g. DM-MAY-05" value="${rd.batchCode}">
        </div>
        <div class="rfield">
          <label>Batch Type</label>
          <select id="rg-type-${l.id}">
            <option value="">Select type</option>
            <option ${rd.batchType==='Weekday'?'selected':''}>Weekday</option>
            <option ${rd.batchType==='Weekend'?'selected':''}>Weekend</option>
            <option ${rd.batchType==='Fast Track'?'selected':''}>Fast Track</option>
          </select>
        </div>
        <div class="rfield">
          <label>Course Fees (₹)</label>
          <input type="number" id="rg-fees-${l.id}" placeholder="e.g. 35000" value="${rd.fees}">
        </div>
        <div class="rfield">
          <label>Payment Mode</label>
          <select id="rg-mode-${l.id}">
            <option value="">Select mode</option>
            <option ${rd.paymentMode==='Full Payment'?'selected':''}>Full Payment</option>
            <option ${rd.paymentMode==='Split Payment'?'selected':''}>Split Payment</option>
            <option ${rd.paymentMode==='EMI'?'selected':''}>EMI</option>
          </select>
        </div>
      </div>
      <div class="reg-form-foot">
        <button class="btn btn-sm btn-primary" onclick="regSaveDetails(${l.id})">Save Details</button>
      </div>`}
    </div>

    ${rd.saved ? `
    <!-- FEE COLLECTION -->
    <div style="font-size:11px;font-weight:600;color:var(--text3);text-transform:uppercase;letter-spacing:.09em;margin-bottom:10px;">Fee Collection</div>
    <div class="fee-toggle" id="fee-toggle-${l.id}">
      <div class="fee-tog-btn ${rd.feeType==='single'?'active':''}" onclick="regSetFeeType(${l.id},'single',this)">💳 Single Payment</div>
      <div style="width:1px;background:var(--border2);"></div>
      <div class="fee-tog-btn ${rd.feeType==='split'?'active':''}" onclick="regSetFeeType(${l.id},'split',this)">📅 Split Payment</div>
    </div>

    <div class="fee-pane ${rd.feeType==='single'?'active':''}" id="reg-fee-single-${l.id}">
      ${_regRenderSingle(l.id)}
    </div>
    <div class="fee-pane ${rd.feeType==='split'?'active':''}" id="reg-fee-split-${l.id}">
      ${_regRenderSplits(l.id)}
    </div>

    ${['payment-done','registered'].includes(l.tag) ? `
    <!-- BATCH ALLOCATION CTA -->
    <div style="margin-top:18px;background:linear-gradient(135deg,#4f8fff14,#1fd6a010);border:1px solid #4f8fff28;border-radius:13px;padding:16px 20px;display:flex;align-items:center;gap:14px;">
      <div style="width:42px;height:42px;border-radius:11px;background:var(--glow);border:1px solid #4f8fff22;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">🧩</div>
      <div style="flex:1;">
        <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;color:var(--text0);margin-bottom:2px;">Payment Complete — Ready for Batch</div>
        <div style="font-size:11px;color:var(--text2);">Allocate this student to a batch to complete the enrollment process.</div>
      </div>
      <button class="btn btn-primary btn-sm" onclick="closeLead();showPage('batches',document.getElementById('si-batches'));">
        Allocate to Batch →
      </button>
    </div>` : ''}
    ` : `
    <div style="background:var(--bg3);border:1px dashed var(--border3);border-radius:11px;padding:20px;text-align:center;color:var(--text3);font-size:12px;">
      Save admission details above to unlock fee collection
    </div>`}
  `}
  </div></div>`;
}

// ══════════════ TIMELINE HELPERS ══════════════
function togglePrevCalls(id, btn){
  const cd = window.CALL_DATA[id];
  cd.prevCollapsed = !cd.prevCollapsed;
  const el = document.getElementById('prev-calls-'+id);
  const arrow = document.getElementById('prev-arrow-'+id);
  if(el){ el.style.display = cd.prevCollapsed ? 'none' : 'block'; }
  if(arrow){ arrow.style.transform = cd.prevCollapsed ? 'rotate(180deg)' : ''; }
  btn.childNodes[btn.childNodes.length-1].textContent = cd.prevCollapsed ? ' Show' : ' Hide';
}

function updateCurrentTag(id, val){
  if(!window.CALL_DATA) window.CALL_DATA = {};
  if(!window.CALL_DATA[id]) window.CALL_DATA[id] = {prevCollapsed:false,currentNote:'',currentTag:'',prevCalls:[]};
  window.CALL_DATA[id].currentTag = val;
}

function addCallEntry(id){
  const note = document.getElementById('cur-note-'+id)?.value?.trim();
  const tagSel = document.getElementById('cur-tag-'+id)?.value;
  if(!note && !tagSel){ alert('Please add a note or select a tag first.'); return; }
  if(!window.CALL_DATA) window.CALL_DATA = {};
  if(!window.CALL_DATA[id]) window.CALL_DATA[id] = {prevCollapsed:false,currentNote:'',currentTag:'',prevCalls:[]};
  const cd = window.CALL_DATA[id];
  cd.prevCalls.push({time:'09:14 AM', date:'22 Apr', dir:'INCOMING', duration:'4m 32s', owner:'Self', tag: tagSel ? tagSel.toUpperCase() : 'NOTE', note: note || '(no note)'});
  cd.currentNote = '';
  cd.currentTag = '';
  // If tag is confirmed, update lead tag
  const l = LEADS.find(x=>x.id===id);
  if(l && tagSel === 'confirmed'){
    l.tag = 'confirmed';
    document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag tag-confirmed">Confirmed</span>`;
  }
  if(l && tagSel === 'followup'){
    openFUModal(); return;
  }
  if(l) renderStudentFW(l);
}

function assignCallTag(id){
  addCallEntry(id);
}

function saveNote(id){
  addCallEntry(id);
}

function generatePayLink(id){
  const l = LEADS.find(x=>x.id===id);
  if(!l) return;
  l.tag = 'payment';
  document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag tag-payment">Payment Sent</span>`;
  activeStudentTab = 'payment';
  renderStudentFW(l);
  renderPayments();
}

function markRegistered(id){
  const l = LEADS.find(x=>x.id===id);
  if(!l) return;
  l.tag = 'registered';
  document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag tag-registered">Registered</span>`;
  activeStudentTab = 'registration';
  renderStudentFW(l);
}

function toggleDrop(el){
  const body = el.nextElementSibling;
  const arrow = el.querySelector('.drop-arrow');
  const open = body.classList.contains('open');
  body.classList.toggle('open');
  arrow.style.transform = open ? 'rotate(0)' : 'rotate(180deg)';
}

// ══════════════ REGISTRATION FORM HELPERS ══════════════
function _initReg(id){
  if(!window.REG_DATA) window.REG_DATA = {};
  const l = LEADS.find(x=>x.id===id);
  if(!window.REG_DATA[id]) window.REG_DATA[id] = {
    saved:false, course:l?l.course:'', batchCode:'', batchType:'', fees:'', paymentMode:'',
    feeType:'single', feeSaved:false, single:{amount:'',date:''}, splits:[{amount:'',date:''},{amount:'',date:''}]
  };
  return window.REG_DATA[id];
}

function regSaveDetails(id){
  const rd = _initReg(id);
  const course  = document.getElementById('rg-course-'+id)?.value;
  const batch   = document.getElementById('rg-batch-'+id)?.value?.trim();
  const type    = document.getElementById('rg-type-'+id)?.value;
  const fees    = document.getElementById('rg-fees-'+id)?.value;
  const mode    = document.getElementById('rg-mode-'+id)?.value;
  if(!batch || !type || !fees || !mode){ alert('Please fill in all admission fields.'); return; }
  Object.assign(rd, {saved:true, course, batchCode:batch, batchType:type, fees, paymentMode:mode});
  const l = LEADS.find(x=>x.id===id);
  if(l){ activeStudentTab = 'registration'; renderStudentFW(l); }
}

function regEditDetails(id){
  const rd = _initReg(id); rd.saved = false;
  const l = LEADS.find(x=>x.id===id);
  if(l){ activeStudentTab = 'registration'; renderStudentFW(l); }
}

function regSetFeeType(id, type, el){
  const rd = _initReg(id); rd.feeType = type; rd.feeSaved = false;
  el.closest('#fee-toggle-'+id).querySelectorAll('.fee-tog-btn').forEach(b=>b.classList.remove('active'));
  el.classList.add('active');
  const sp = document.getElementById('reg-fee-single-'+id);
  const pp = document.getElementById('reg-fee-split-'+id);
  if(sp){ sp.classList.toggle('active', type==='single'); sp.innerHTML = _regRenderSingle(id); }
  if(pp){ pp.classList.toggle('active', type==='split');  pp.innerHTML = _regRenderSplits(id); }
}

function _regRenderSingle(id){
  const rd = _initReg(id);
  if(rd.feeSaved && rd.feeType==='single') return `
    <div class="saved-block">
      <div class="saved-block-head"><div class="saved-block-title">Single Payment — Saved</div><button class="btn btn-xs" onclick="regEditFee(${id})">✏ Edit</button></div>
      <div class="saved-row">
        <div class="saved-idx">#1</div>
        <div class="saved-amt">${rd.single.amount?'₹'+Number(rd.single.amount).toLocaleString('en-IN'):'—'}</div>
        <div class="saved-date">${rd.single.date||'—'}</div>
        <span class="tag tag-confirmed" style="font-size:10px;justify-self:end;">Saved ✓</span>
      </div>
    </div>`;
  return `<div class="reg-manual-form" style="margin-bottom:0;">
    <div class="reg-form-grid">
      <div class="rfield"><label>Amount (₹)</label><input type="number" id="rf-s-amt-${id}" placeholder="e.g. 35000" value="${rd.single.amount}"></div>
      <div class="rfield"><label>Payment Date</label><input type="date" id="rf-s-date-${id}" value="${rd.single.date}"></div>
    </div>
    <div class="reg-form-foot"><button class="btn btn-sm btn-primary" onclick="regSaveSingle(${id})">Save</button></div>
  </div>`;
}

function _regRenderSplits(id){
  const rd = _initReg(id);
  if(rd.feeSaved && rd.feeType==='split') return `
    <div class="saved-block">
      <div class="saved-block-head"><div class="saved-block-title">Split Payments (${rd.splits.length}) — Saved</div><button class="btn btn-xs" onclick="regEditFee(${id})">✏ Edit</button></div>
      ${rd.splits.map((s,i)=>`<div class="saved-row"><div class="saved-idx">#${i+1}</div><div class="saved-amt">${s.amount?'₹'+Number(s.amount).toLocaleString('en-IN'):'—'}</div><div class="saved-date">${s.date||'—'}</div><span class="tag ${i===0?'tag-confirmed':'tag-converted'}" style="font-size:10px;justify-self:end;">${i===0?'Paid ✓':'Pending'}</span></div>`).join('')}
    </div>`;
  return `<div class="reg-manual-form" style="margin-bottom:0;">
    <div id="reg-splits-${id}">
      ${rd.splits.map((s,i)=>`
      <div class="split-entry" id="rse-${id}-${i}">
        <div class="split-entry-head"><span class="split-label">Payment ${i+1}</span>${rd.splits.length>2?`<button class="btn btn-xs btn-red" onclick="regRemSplit(${id},${i})">Remove</button>`:''}</div>
        <div class="reg-form-grid">
          <div class="rfield"><label>Amount (₹)</label><input type="number" id="rf-sp-amt-${id}-${i}" placeholder="e.g. 17500" value="${s.amount}"></div>
          <div class="rfield"><label>Date</label><input type="date" id="rf-sp-date-${id}-${i}" value="${s.date}"></div>
        </div>
      </div>`).join('')}
    </div>
    <div style="padding:10px 16px;border-top:1px solid var(--border);">
      <button class="split-add-btn" onclick="regAddSplit(${id})">
        <svg width="13" height="13" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Add Another Split
      </button>
    </div>
    <div class="reg-form-foot"><button class="btn btn-sm btn-primary" onclick="regSaveSplits(${id})">Save All</button></div>
  </div>`;
}

function regSaveSingle(id){
  const rd = _initReg(id);
  const amt  = document.getElementById('rf-s-amt-'+id)?.value;
  const date = document.getElementById('rf-s-date-'+id)?.value;
  if(!amt||!date){ alert('Enter amount and date.'); return; }
  rd.single = {amount:amt,date}; rd.feeSaved = true; rd.feeType = 'single';
  document.getElementById('reg-fee-single-'+id).innerHTML = _regRenderSingle(id);
}

function regSaveSplits(id){
  const rd = _initReg(id);
  const updated = rd.splits.map((_,i)=>({amount:document.getElementById(`rf-sp-amt-${id}-${i}`)?.value||'',date:document.getElementById(`rf-sp-date-${id}-${i}`)?.value||''}));
  if(updated.some(s=>!s.amount||!s.date)){ alert('Fill in all split entries.'); return; }
  rd.splits = updated; rd.feeSaved = true; rd.feeType = 'split';
  document.getElementById('reg-fee-split-'+id).innerHTML = _regRenderSplits(id);
}

function regEditFee(id){
  const rd = _initReg(id); rd.feeSaved = false;
  if(rd.feeType==='single') document.getElementById('reg-fee-single-'+id).innerHTML = _regRenderSingle(id);
  else document.getElementById('reg-fee-split-'+id).innerHTML = _regRenderSplits(id);
}

function regAddSplit(id){
  const rd = _initReg(id); rd.splits.push({amount:'',date:''});
  document.getElementById('reg-fee-split-'+id).innerHTML = _regRenderSplits(id);
}

function regRemSplit(id,idx){
  const rd = _initReg(id);
  if(rd.splits.length<=2){ alert('Minimum 2 split payments.'); return; }
  rd.splits.splice(idx,1);
  document.getElementById('reg-fee-split-'+id).innerHTML = _regRenderSplits(id);
}

// updateTag kept for follow-up path only
function updateTag(tag){
  const l = LEADS.find(x=>x.id===curLeadId);
  if(!l) return;
  if(tag==='followup'){ openFUModal(); return; }
  l.tag = tag;
  document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag ${tClass(tag)}">${tLabel(tag)}</span>`;
  renderStudentFW(l);
  renderDashboard();
}

// ═══════════ FOLLOW-UPS ═══════════
function renderFollowups(){
  document.getElementById('fu-body').innerHTML = FOLLOWUPS.length
    ? FOLLOWUPS.map(f=>`
      <div class="lrow-fu" onclick="openLead(${f.leadId})">
        <div class="td"><div class="lc"><div class="lav" style="width:30px;height:30px;border-radius:8px;background:var(--obg);color:var(--orange);font-size:10px;">${inits(f.name)}</div><div><div class="ln">${f.name}</div><div class="ls">Lead #${f.leadId}</div></div></div></div>
        <div class="td" style="color:var(--text2);">${f.phone}</div>
        <div class="td" style="color:${f.status==='overdue'?'var(--red)':'var(--text1)'};">${f.date} · ${f.time}</div>
        <div class="td" style="font-size:11px;color:var(--text2);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.note}</div>
        <div class="td"><span class="tag" style="${f.status==='overdue'?'background:var(--rbg);color:var(--red);border:1px solid #f0646420;':'background:var(--obg);color:var(--orange);border:1px solid #f9731620;'}">${f.status==='overdue'?'Overdue':'Pending'}</span></div>
      </div>`).join('')
    : '<div class="empty-state"><span class="empty-icon">📅</span>No follow-ups scheduled</div>';
}

// ═══════════ FOLLOWUP MODAL ═══════════
function openFUModal(){
  const l = LEADS.find(x=>x.id===curLeadId);
  document.getElementById('fu-lead-info').innerHTML = l
    ? `<strong style="color:var(--text0);">${l.name}</strong> · ${l.course} · ${l.phone}`
    : '';
  document.getElementById('fu-date').value = new Date().toISOString().split('T')[0];
  document.getElementById('fu-time').value = '10:00';
  document.getElementById('fu-note').value = '';
  document.getElementById('fu-modal').classList.add('open');
}
function closeFUModal(){ document.getElementById('fu-modal').classList.remove('open'); }
function confirmFollowup(){
  const l = LEADS.find(x=>x.id===curLeadId);
  const date = document.getElementById('fu-date').value;
  const time = document.getElementById('fu-time').value;
  const note = document.getElementById('fu-note').value || 'Follow-up scheduled';
  if(!l||!date) return;
  l.tag = 'followup';
  FOLLOWUPS.push({id:Date.now(), leadId:curLeadId, name:l.name, phone:l.phone, date, time, note, status:'pending'});
  document.getElementById('fu-badge').textContent = FOLLOWUPS.length;
  closeFUModal();
  document.getElementById('fw-lead-tag-bar').innerHTML = `<span class="tag tag-followup">Follow-up</span>`;
  activeStudentTab = 'calls';
  renderStudentFW(l);
  alert(`Follow-up set for ${l.name} on ${date} at ${time}`);
}

// ═══════════ PAYMENTS PAGE ═══════════
function renderPayments(){
  const payLeads = LEADS.filter(l=>['payment','payment-done','registered'].includes(l.tag));
  document.getElementById('pay-body').innerHTML = payLeads.length
    ? payLeads.map(l=>`
      <div class="lrow-pay" style="cursor:pointer;" onclick="openLead(${l.id})">
        <div class="td"><div class="lc"><div class="lav" style="background:${l.bg};color:${l.clr};">${l.init}</div><div><div class="ln">${l.name}</div><div class="ls">${l.course}</div></div></div></div>
        <div class="td">₹35,000</div>
        <div class="td" style="color:var(--text2);">${l.date}</div>
        <div class="td" style="color:var(--text2);">UPI</div>
        <div class="td"><span class="tag ${l.tag==='payment'?'tag-payment':'tag-payment-done'}">${l.tag==='payment'?'Pending':'Paid'}</span></div>
        <div class="td" onclick="event.stopPropagation()">
          ${l.tag==='payment'
            ?`<div style="display:flex;gap:5px;"><button class="btn btn-xs btn-amber">Resend</button><button class="btn btn-xs" onclick="markPayDone(${l.id})">Mark Paid</button></div>`
            :`<div style="display:flex;gap:5px;">
                <button class="btn btn-xs btn-green">Receipt</button>
                <button class="btn btn-xs" style="background:var(--glow);color:var(--accent);border-color:#4f8fff22;" onclick="closeLead();showPage('batches',document.getElementById('si-batches'));">
                  <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zm8 0a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zm-8 8a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zm8 0a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/></svg>
                  Allocate Batch
                </button>
              </div>`}
        </div>
      </div>`).join('')
    : '<div class="empty-state"><span class="empty-icon">💳</span>No payment records yet</div>';
}
function markPayDone(id){
  const l = LEADS.find(x=>x.id===id);
  if(l){ l.tag='payment-done'; renderPayments(); }
}

// ═══════════ REGISTRATIONS ═══════════
function renderRegistrations(){
  const list = LEADS.filter(l=>['payment-done','registered'].includes(l.tag));
  document.getElementById('reg-badge').textContent = list.length;
  document.getElementById('reg-body').innerHTML = list.length
    ? list.map(l=>`
      <div class="lrow-reg" onclick="openLead(${l.id})">
        <div class="td"><div class="lc"><div class="lav" style="background:${l.bg};color:${l.clr};">${l.init}</div><div><div class="ln">${l.name}</div><div class="ls">${l.phone}</div></div></div></div>
        <div class="td">${l.course}</div>
        <div class="td" style="color:var(--text2);">${l.date}</div>
        <div class="td"><span style="font-size:10px;background:var(--bg4);color:var(--text2);padding:2px 8px;border-radius:5px;">—</span></div>
        <div class="td"><span class="tag ${l.tag==='registered'?'tag-registered':'tag-payment-done'}">${l.tag==='registered'?'Registered':'Form Pending'}</span></div>
        <div class="td"><button class="btn btn-xs btn-primary" onclick="event.stopPropagation();openLead(${l.id});setTimeout(()=>{switchToTab('registration')},100)">View Form</button></div>
      </div>`).join('')
    : '<div class="empty-state"><span class="empty-icon">📋</span>No students ready for registration yet</div>';
}
function switchToTab(tab){ activeStudentTab = tab; }

// ═══════════ BATCHES ═══════════
function renderBatches(filter='all'){
  batchFilter = filter;
  const started = BATCHES.filter(b=>b.status==='started');
  const upcoming = BATCHES.filter(b=>b.status==='upcoming'&&(filter==='all'||b.course===filter));
  document.getElementById('started-batches').innerHTML = started.map(bCard).join('');
  document.getElementById('upcoming-batches').innerHTML = upcoming.map(bCard).join('');
}
function bCard(b){
  const stuCount = (ALLOC[b.id]||[]).length + b.students.length;
  return `<div class="bcard">
    <div class="bcard-glow" style="background:${cGlow(b.course)};"></div>
    <div class="bcard-head">
      <span class="bctag ${cTagC(b.course)}">${b.course.toUpperCase()}</span>
      ${b.status==='started'?`<span class="live-badge" style="float:right;">● LIVE</span>`:`<span class="up-badge" style="float:right;">UPCOMING</span>`}
      <div class="bcard-name">${b.code}</div>
      <div class="bcard-mentor">Mentor: ${b.mentor}</div>
    </div>
    <div class="bcard-body">
      <div><div class="bsl">Type</div><div class="bsv">${b.type}</div></div>
      <div><div class="bsl">Hours</div><div class="bsv">${b.hours}h</div></div>
      <div><div class="bsl">Starts</div><div class="bsv">${b.start}</div></div>
      <div><div class="bsl">Last Join</div><div class="bsv">${b.lastDate}</div></div>
    </div>
    <div class="bcard-foot">
      <div class="stu-pill">
        <svg width="11" height="11" viewBox="0 0 20 20" fill="currentColor" style="color:var(--text3);"><path d="M9 6a3 3 0 116 0 3 3 0 01-6 0z"/><path d="M2 16a7 7 0 0114 0H2z"/></svg>
        ${stuCount} student${stuCount!==1?'s':''}
      </div>
      <button class="btn btn-xs" onclick="openBatchFW(${b.id})">Overview</button>
      ${b.allocatable?`<button class="btn btn-xs btn-primary" onclick="openAllocModal(${b.id})">Allocate</button>`:`<span style="font-size:10px;color:var(--red);background:var(--rbg);padding:3px 10px;border-radius:20px;">Closed</span>`}
    </div>
  </div>`;
}
function filterBatches(f, el){
  document.querySelectorAll('#course-filters .ftab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  renderBatches(f);
}

// BATCH FULL WINDOW
function openBatchFW(id){
  curBatchId = id;
  const b = BATCHES.find(x=>x.id===id);
  const all = [...b.students, ...(ALLOC[id]||[])];
  document.getElementById('fw-batch-name').textContent = `${b.code} — Batch Overview`;
  const btag = document.getElementById('fw-batch-tag');
  btag.className = 'bctag '+cTagC(b.course);
  btag.textContent = b.course;
  document.getElementById('fw-batch-body').innerHTML = `
    <div class="bfw-grid">
      <div class="bfw-item"><div class="il">Course</div><div class="iv">${b.course}</div></div>
      <div class="bfw-item"><div class="il">Batch Code</div><div class="iv">${b.code}</div></div>
      <div class="bfw-item"><div class="il">Mentor</div><div class="iv">${b.mentor}</div></div>
      <div class="bfw-item"><div class="il">Type</div><div class="iv">${b.type}</div></div>
      <div class="bfw-item"><div class="il">Total Hours</div><div class="iv">${b.hours}h</div></div>
      <div class="bfw-item"><div class="il">Status</div><div class="iv" style="color:${b.status==='started'?'var(--green)':'var(--amber)'};">${b.status==='started'?'Started':'Upcoming'}</div></div>
      <div class="bfw-item"><div class="il">Start Date</div><div class="iv">${b.start}</div></div>
      <div class="bfw-item"><div class="il">Last Join Date</div><div class="iv">${b.lastDate}</div></div>
      <div class="bfw-item"><div class="il">Students Enrolled</div><div class="iv" style="color:var(--accent);">${all.length}</div></div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
      <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text0);">Batch Students (${all.length})</div>
      ${b.allocatable?`<button class="btn btn-sm btn-primary" onclick="openAllocModal(${b.id})">+ Allocate Student</button>`:'<span style="font-size:12px;color:var(--red);">Allocation Closed</span>'}
    </div>
    ${all.length===0
      ?`<div class="empty-state" style="background:var(--bg2);border-radius:12px;"><span class="empty-icon">👤</span>No students yet${b.allocatable?`<br><br><button class="btn btn-sm btn-primary" onclick="openAllocModal(${b.id})">Allocate First Student</button>`:''}</div>`
      :`<div style="background:var(--bg2);border:1px solid var(--border);border-radius:12px;overflow:hidden;"><table class="stu-table"><thead><tr><th>#</th><th>Student Name</th><th>Phone</th><th>Course</th><th>Batch Code</th><th>Status</th></tr></thead><tbody>${all.map((s,i)=>`<tr><td style="color:var(--text3);">${i+1}</td><td style="font-weight:500;color:var(--text0);">${s}</td><td style="color:var(--text3);">—</td><td>${b.course}</td><td><span style="font-size:10px;background:var(--bg4);padding:2px 8px;border-radius:5px;">${b.code}</span></td><td><span class="tag tag-registered" style="font-size:10px;">Registered</span></td></tr>`).join('')}</tbody></table></div>`}`;
  document.getElementById('batch-fw').classList.add('open');
}
function closeBatchFW(){ document.getElementById('batch-fw').classList.remove('open'); }

// ALLOCATE MODAL
function openAllocModal(id){
  curBatchId = id; selStu = null;
  const b = BATCHES.find(x=>x.id===id);
  document.getElementById('alloc-info').innerHTML = `<strong style="color:var(--text0);">${b.code}</strong> · ${b.course} · ${b.type} · ${b.hours}h · Starts ${b.start}`;
  document.getElementById('alloc-search').value = '';
  searchStudents('');
  document.getElementById('alloc-modal').classList.add('open');
}
function closeAllocModal(){ document.getElementById('alloc-modal').classList.remove('open'); }
function searchStudents(q){
  const pool = LEADS.filter(l=>l.tag==='registered');
  const res = pool.filter(l=>l.name.toLowerCase().includes(q.toLowerCase())||l.phone.includes(q));
  const el = document.getElementById('alloc-results');
  el.innerHTML = res.length
    ? res.map(l=>`
      <div class="alloc-row ${selStu&&selStu.id===l.id?'sel':''}" onclick="selStu=LEADS.find(x=>x.id==${l.id});searchStudents(document.getElementById('alloc-search').value)">
        <div class="lav" style="width:32px;height:32px;border-radius:8px;background:${l.bg};color:${l.clr};font-size:11px;">${l.init}</div>
        <div style="flex:1;"><div style="font-size:13px;font-weight:500;color:var(--text0);">${l.name}</div><div style="font-size:10px;color:var(--text3);">${l.phone} · ${l.course}</div></div>
        <span class="tag tag-registered" style="font-size:10px;">Registered</span>
        ${selStu&&selStu.id===l.id?'<span style="color:var(--accent);font-size:11px;font-weight:700;margin-left:6px;">✓</span>':''}
      </div>`).join('')
    : '<div class="empty-state" style="padding:20px;"><span style="font-size:20px;display:block;margin-bottom:6px;">🔍</span>No registered students found</div>';
}
function confirmAlloc(){
  if(!selStu){ alert('Select a student first.'); return; }
  if(!ALLOC[curBatchId]) ALLOC[curBatchId]=[];
  if(!ALLOC[curBatchId].includes(selStu.name)) ALLOC[curBatchId].push(selStu.name);
  closeAllocModal();
  renderBatches(batchFilter);
  if(document.getElementById('batch-fw').classList.contains('open')) openBatchFW(curBatchId);
  alert(`✅ ${selStu.name} allocated to ${BATCHES.find(b=>b.id===curBatchId).code}`);
}

// ═══════════ INIT ═══════════
renderDashboard();
renderLeads();
</script>
</body>
</html>
