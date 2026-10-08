<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TeleCRM — Telecaller Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --bg: #0a0d14;
  --surface: #111520;
  --surface2: #161c2e;
  --surface3: #1e2640;
  --border: #252d45;
  --border2: #2e3a5a;
  --accent: #4f8ef7;
  --accent2: #7b61ff;
  --accent3: #00d4aa;
  --danger: #ff5470;
  --warn: #ffb547;
  --success: #22d07a;
  --text: #e8eaf0;
  --text2: #8892b0;
  --text3: #4a5580;
  --hot: #ff5470;
  --warm: #ffb547;
  --cold: #4f8ef7;
  --font: 'Sora', sans-serif;
  --mono: 'JetBrains Mono', monospace;
}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;display:flex;overflow:hidden;}

/* SIDEBAR */
.sidebar{width:230px;min-width:230px;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;height:100vh;overflow:hidden;}
.logo{padding:24px 20px 20px;border-bottom:1px solid var(--border);}
.logo-mark{display:flex;align-items:center;gap:10px;}
.logo-icon{width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;}
.logo-text{font-size:16px;font-weight:700;letter-spacing:-.3px;}
.logo-text span{color:var(--accent);}
.nav{flex:1;overflow-y:auto;padding:12px 0;}
.nav-item{display:flex;align-items:center;gap:12px;padding:11px 20px;cursor:pointer;font-size:13.5px;font-weight:500;color:var(--text2);transition:.2s;position:relative;border-left:3px solid transparent;}
.nav-item:hover{color:var(--text);background:var(--surface2);}
.nav-item.active{color:var(--accent);background:rgba(79,142,247,.08);border-left-color:var(--accent);}
.nav-item .icon{font-size:16px;width:20px;text-align:center;}
.nav-badge{margin-left:auto;background:var(--danger);color:#fff;border-radius:10px;padding:1px 7px;font-size:11px;font-weight:700;}
.sidebar-footer{padding:16px 20px;border-top:1px solid var(--border);}
.user-card{display:flex;align-items:center;gap:10px;}
.avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent2),var(--accent3));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;}
.user-info{flex:1;}
.user-name{font-size:13px;font-weight:600;}
.user-role{font-size:11px;color:var(--text3);}

/* MAIN */
.main{flex:1;height:100vh;overflow-y:auto;display:flex;flex-direction:column;}
.topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;position:sticky;top:0;z-index:100;}
.topbar-title{font-size:17px;font-weight:700;letter-spacing:-.3px;}
.topbar-right{display:flex;align-items:center;gap:12px;}
.btn{padding:8px 18px;border-radius:8px;border:none;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;transition:.2s;}
.btn-primary{background:var(--accent);color:#fff;}
.btn-primary:hover{background:#3a7de8;}
.btn-outline{background:transparent;color:var(--text2);border:1px solid var(--border2);}
.btn-outline:hover{color:var(--text);border-color:var(--text2);}
.btn-danger{background:var(--danger);color:#fff;}
.btn-success{background:var(--success);color:#000;font-weight:700;}
.btn-warn{background:var(--warn);color:#000;font-weight:700;}
.btn-sm{padding:5px 12px;font-size:12px;}
.search-bar{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 14px;color:var(--text);font-family:var(--font);font-size:13px;width:220px;outline:none;}
.search-bar::placeholder{color:var(--text3);}
.content{padding:24px 28px;flex:1;}

/* OVERVIEW */
.stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:24px;}
.stat-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;position:relative;overflow:hidden;}
.stat-card::before{content:'';position:absolute;top:0;right:0;width:80px;height:80px;border-radius:50%;opacity:.07;transform:translate(20px,-20px);}
.stat-card.blue::before{background:var(--accent);}
.stat-card.purple::before{background:var(--accent2);}
.stat-card.green::before{background:var(--accent3);}
.stat-card.red::before{background:var(--danger);}
.stat-card.teal::before{background:#00bcd4;}
.stat-label{font-size:12px;color:var(--text2);font-weight:500;margin-bottom:8px;text-transform:uppercase;letter-spacing:.5px;}
.stat-value{font-size:28px;font-weight:700;letter-spacing:-1px;line-height:1;}
.stat-sub{font-size:12px;color:var(--text3);margin-top:6px;}
.stat-sub .up{color:var(--success);}
.stat-sub .down{color:var(--danger);}
.stat-icon{position:absolute;top:20px;right:20px;font-size:24px;opacity:.4;}

.mid-grid{display:grid;grid-template-columns:1fr 1fr 1.2fr;gap:16px;margin-bottom:24px;}
.card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px;}
.card-title{font-size:13px;font-weight:700;color:var(--text);margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;}
.card-title .sub{font-size:11px;color:var(--text3);font-weight:400;}

/* DONUT */
.donut-wrap{display:flex;align-items:center;gap:20px;}
.donut-svg{flex-shrink:0;}
.donut-legend{display:flex;flex-direction:column;gap:10px;}
.legend-item{display:flex;align-items:center;gap:8px;font-size:12px;}
.legend-dot{width:10px;height:10px;border-radius:50%;}
.legend-count{font-weight:700;font-size:14px;}
.legend-label{color:var(--text2);}

/* TODAY LIST */
.today-list{display:flex;flex-direction:column;gap:8px;}
.today-item{background:var(--surface2);border-radius:8px;padding:10px 14px;display:flex;align-items:center;gap:12px;cursor:pointer;transition:.2s;}
.today-item:hover{background:var(--surface3);}
.today-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.today-name{font-size:13px;font-weight:600;flex:1;}
.today-time{font-size:11px;color:var(--text3);font-family:var(--mono);}
.tag{padding:2px 8px;border-radius:4px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;display:inline-block;}
.tag.hot{background:rgba(255,84,112,.15);color:var(--hot);}
.tag.warm{background:rgba(255,181,71,.15);color:var(--warn);}
.tag.cold{background:rgba(79,142,247,.15);color:var(--cold);}
.tag.contacted{background:rgba(34,208,122,.15);color:var(--success);}
.tag.invalid{background:rgba(79,142,247,.1);color:var(--text3);}
.tag.booked{background:rgba(123,97,255,.15);color:var(--accent2);}
.tag.attended{background:rgba(0,212,170,.15);color:var(--accent3);}
.tag.missed{background:rgba(255,84,112,.15);color:var(--danger);}
.tag.converted{background:rgba(34,208,122,.2);color:var(--success);}
.tag.lost{background:rgba(255,84,112,.1);color:var(--text3);}
.tag.registered{background:rgba(0,212,170,.25);color:#00ffcc;border:1px solid rgba(0,212,170,.3);}
.tag.newlead{background:rgba(79,142,247,.15);color:var(--accent);border:1px solid rgba(79,142,247,.3);}

/* LEADS TABLE */
.table-wrap{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.table-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:12px;}
.filters{display:flex;gap:8px;align-items:center;}
.filter-btn{padding:6px 14px;border-radius:6px;border:1px solid var(--border);background:transparent;color:var(--text2);font-size:12px;font-weight:500;cursor:pointer;font-family:var(--font);transition:.2s;}
.filter-btn.active,.filter-btn:hover{background:var(--surface3);color:var(--text);border-color:var(--border2);}
table{width:100%;border-collapse:collapse;}
thead tr{background:var(--surface2);}
th{padding:11px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.6px;border-bottom:1px solid var(--border);}
td{padding:12px 16px;font-size:13px;border-bottom:1px solid var(--border);vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr:hover{background:var(--surface2);cursor:pointer;}
.lead-name{font-weight:600;color:var(--accent);cursor:pointer;}
.phone{font-family:var(--mono);font-size:12px;color:var(--text2);}
.source-badge{padding:3px 9px;border-radius:4px;font-size:11px;font-weight:600;background:var(--surface3);color:var(--text2);border:1px solid var(--border);}

/* STUDENT DETAIL */
.back-btn{display:flex;align-items:center;gap:8px;color:var(--text2);font-size:13px;font-weight:500;cursor:pointer;margin-bottom:20px;background:none;border:none;font-family:var(--font);}
.back-btn:hover{color:var(--text);}
.profile-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:24px;margin-bottom:16px;}
.profile-top{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;}
.profile-info{display:flex;align-items:center;gap:16px;}
.profile-avatar{width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;}
.profile-name{font-size:18px;font-weight:700;}
.profile-actions{display:flex;gap:8px;flex-wrap:wrap;}
.profile-fields{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;}
.field-group{background:var(--surface2);border-radius:8px;padding:12px;}
.field-label{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;font-weight:700;}
.field-value{font-size:13px;font-weight:600;}

/* CALL HISTORY — new layout */
.call-history{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.call-history-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}

/* Timeline groups */
.timeline-group{border-bottom:1px solid var(--border);}
.timeline-group:last-child{border-bottom:none;}
.timeline-group-header{padding:12px 20px;background:var(--surface2);display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:.2s;}
.timeline-group-header:hover{background:var(--surface3);}
.prev-arrow{font-size:11px;color:var(--text3);transition:.2s;}

/* Original 8-col grid (kept for backward compat) */
.ch-cols{padding:10px 20px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:130px 110px 130px 90px 110px 150px 200px 100px;gap:10px;background:var(--surface2);}
.ch-col{font-size:10px;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.5px;}
.call-row{padding:14px 20px;border-bottom:1px solid var(--border);display:grid;grid-template-columns:130px 110px 130px 90px 110px 150px 200px 100px;gap:10px;align-items:center;font-size:12px;}

/* Previous = read-only 7-col grid (no close column, notes becomes a view-only box) */
.ch-cols-readonly{grid-template-columns:130px 110px 130px 90px 110px 130px 1fr;}
.call-row-readonly{grid-template-columns:130px 110px 130px 90px 110px 130px 1fr;opacity:.85;}
.call-row-readonly:hover{background:var(--surface2);}

/* Current = editable 7-col grid (no close column — wider Notes+Assign area) */
.ch-cols-current{grid-template-columns:130px 110px 130px 90px 110px 150px 1fr;}
.call-row-current{grid-template-columns:130px 110px 130px 90px 110px 150px 1fr;background:rgba(79,142,247,.04);border-left:3px solid var(--accent);}

.call-row:last-child{border-bottom:none;}
.call-row:hover{background:var(--surface2);}
.call-time{color:var(--text2);font-family:var(--mono);font-size:11px;}
.direction-badge{padding:3px 8px;border-radius:4px;font-size:10px;font-weight:700;text-transform:uppercase;display:inline-flex;align-items:center;gap:4px;}
.direction-badge.incoming{background:rgba(34,208,122,.12);color:var(--success);}
.direction-badge.outgoing{background:rgba(79,142,247,.12);color:var(--accent);}
.direction-badge.missed{background:rgba(255,84,112,.12);color:var(--danger);}
.recording-btn{background:var(--surface3);border:1px solid var(--border);border-radius:6px;padding:5px 10px;color:var(--accent3);font-size:11px;cursor:pointer;font-family:var(--font);display:flex;align-items:center;gap:4px;white-space:nowrap;}
.notes-input{background:var(--surface3);border:1px solid var(--border);border-radius:6px;padding:6px 10px;color:var(--text);font-size:12px;font-family:var(--font);width:100%;resize:none;outline:none;}
.notes-input:focus{border-color:var(--accent);}
.tag-select{background:var(--surface3);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:11px;font-family:var(--font);cursor:pointer;outline:none;width:100%;}
.assign-area{display:flex;flex-direction:column;gap:4px;}
.assign-select{background:var(--surface3);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:11px;font-family:var(--font);cursor:pointer;outline:none;width:100%;}
.assign-ok{background:var(--accent);border:none;border-radius:6px;padding:5px 10px;color:#fff;font-size:11px;font-weight:700;cursor:pointer;font-family:var(--font);width:100%;}
.close-btn{background:rgba(255,84,112,.1);border:1px solid rgba(255,84,112,.25);border-radius:6px;padding:5px 10px;color:var(--danger);font-size:11px;font-weight:700;cursor:pointer;font-family:var(--font);width:100%;}

/* Lead status flow badge */
.lead-flow-status{display:flex;align-items:center;gap:6px;padding:6px 12px;background:rgba(79,142,247,.08);border:1px solid rgba(79,142,247,.2);border-radius:8px;font-size:12px;color:var(--text2);margin-bottom:14px;}
.lead-flow-status strong{color:var(--text);}

/* Assigned warning banner */
.assigned-warning{background:rgba(255,181,71,.1);border:1px solid rgba(255,181,71,.3);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--warn);display:flex;align-items:center;gap:10px;margin-bottom:12px;}

/* FOLLOW-UPS */
.followup-search-bar{display:flex;align-items:center;gap:10px;margin-bottom:18px;}
.followup-row{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:14px;margin-bottom:8px;cursor:pointer;transition:.2s;}
.followup-row:hover{background:var(--surface2);}

/* SCHEDULE DEMO */
.demo-staff-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
.staff-demo-card{background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
.staff-demo-header{background:var(--surface2);padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px;}
.staff-avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;}
.staff-name{font-size:13px;font-weight:700;}
.demo-days{display:flex;gap:0;border-bottom:1px solid var(--border);}
.demo-day-tab{flex:1;padding:8px;text-align:center;font-size:11px;font-weight:600;color:var(--text3);cursor:pointer;border-right:1px solid var(--border);transition:.2s;}
.demo-day-tab:last-child{border-right:none;}
.demo-day-tab.active,.demo-day-tab:hover{background:var(--surface3);color:var(--accent);}
.slots-grid{padding:12px;}
.slot{border:1px solid var(--border);border-radius:7px;padding:8px 10px;margin-bottom:6px;cursor:pointer;transition:.2s;position:relative;}
.slot:hover{border-color:var(--accent);}
/* Available = bright/white */
.slot.available{background:rgba(255,255,255,.04);border-color:#3a4260;}
.slot.available:hover{background:rgba(255,255,255,.08);border-color:var(--accent);}
.slot.available .slot-name{color:var(--text);font-weight:600;}
.slot.available .slot-time{color:var(--text2);}
/* Booked = gray / disabled */
.slot.booked{background:var(--surface2);border-color:var(--border);cursor:not-allowed;opacity:.55;pointer-events:none;}
.slot.booked .slot-name{color:var(--text3);}
.slot.booked .slot-time{color:var(--text3);}
.slot-time{font-size:10px;font-family:var(--mono);color:var(--text3);}
.slot-name{font-size:12px;font-weight:600;margin-top:2px;}
.slot-num{display:inline-block;background:var(--surface3);color:var(--text3);border-radius:4px;padding:1px 5px;font-size:9px;font-family:var(--mono);margin-right:4px;}

/* DEMO TRACKING */
.demo-tabs{display:flex;gap:4px;background:var(--surface2);border-radius:10px;padding:4px;margin-bottom:20px;width:fit-content;}
.demo-tab{padding:8px 18px;border-radius:7px;font-size:13px;font-weight:600;cursor:pointer;color:var(--text2);transition:.2s;}
.demo-tab.active{background:var(--surface3);color:var(--text);}
.demo-staff-badge{padding:3px 9px;background:var(--surface2);border-radius:5px;font-size:11px;font-weight:600;border:1px solid var(--border);}
.demo-status-select{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:7px 28px 7px 12px;color:var(--text);font-family:var(--font);font-size:12px;font-weight:600;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238892b0' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center;min-width:140px;}
.demo-status-select option{background:var(--surface2);}

/* REGISTERED STUDENTS */
.reg-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;}
.reg-stat{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;text-align:center;}
.reg-stat-num{font-size:32px;font-weight:700;color:var(--accent3);}
.reg-stat-label{font-size:11px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:4px;}
.reg-row{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:16px;margin-bottom:8px;transition:.2s;}
.reg-row:hover{background:var(--surface2);}
.reg-step{display:flex;gap:8px;align-items:center;font-size:12px;color:var(--text3);}
.reg-step .arrow{color:var(--border2);}
.reg-step strong{color:var(--text2);}
.reg-step .done{color:var(--success);}

/* MODAL */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:1000;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
.modal{background:var(--surface);border:1px solid var(--border2);border-radius:16px;padding:28px;width:460px;max-width:95vw;max-height:90vh;overflow-y:auto;}
.modal-title{font-size:16px;font-weight:700;margin-bottom:20px;}
.form-group{margin-bottom:14px;}
.form-label{font-size:12px;color:var(--text2);font-weight:600;margin-bottom:6px;display:block;}
.form-input{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:10px 14px;color:var(--text);font-family:var(--font);font-size:13px;width:100%;outline:none;transition:.2s;}
.form-input:focus{border-color:var(--accent);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px;}

/* LEADS FILTER BAR */
.leads-filter-bar{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
.leads-search{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 14px;color:var(--text);font-family:var(--font);font-size:13px;min-width:200px;outline:none;transition:.2s;}
.leads-search::placeholder{color:var(--text3);}
.leads-search:focus{border-color:var(--accent);}
.leads-dropdown{background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:9px 32px 9px 14px;color:var(--text2);font-family:var(--font);font-size:13px;font-weight:500;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238892b0' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;transition:.2s;}
.leads-dropdown:hover{border-color:var(--border2);color:var(--text);}
.leads-dropdown option{background:var(--surface2);}

::-webkit-scrollbar{width:5px;height:5px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:10px;}

.page-section{display:none;}
.page-section.active{display:block;}

.trend-bar{display:flex;align-items:flex-end;gap:3px;height:32px;}
.trend-b{width:8px;background:var(--border2);border-radius:2px;transition:.3s;}
.trend-b.hi{background:var(--accent);}

.cal-filter{display:flex;align-items:center;gap:8px;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:6px 12px;font-size:12px;color:var(--text2);cursor:pointer;}
.cal-filter span{color:var(--text);font-weight:600;}

.pulse{display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--success);position:relative;}
.pulse::after{content:'';position:absolute;inset:-3px;border-radius:50%;border:1.5px solid var(--success);animation:pulse 2s infinite;}
@keyframes pulse{0%{opacity:1;transform:scale(1);}100%{opacity:0;transform:scale(1.8);}}

.status-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.status-dot.online{background:var(--success);}

.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.section-title{font-size:20px;font-weight:700;letter-spacing:-.4px;}
.section-sub{font-size:13px;color:var(--text2);margin-top:2px;}
</style>
</head>
<body>

<!-- SIDEBAR -->
  @include('partials.sidebar', ['role' => 'telecaller'])

  <div class="main">
  <div class="topbar">
    <div class="topbar-title" id="topbar-title">Overview</div>
    <div class="topbar-right">
      <div class="cal-filter">📅 <span id="today-date"></span></div>
      <input class="search-bar" placeholder="🔍  Search leads, names..." />
      <button class="btn btn-primary" onclick="openAddLeadModal()">+ Add Lead</button>
    </div>
  </div>

  <div class="content">

    <!-- ===== OVERVIEW ===== -->
    
        @yield('content')
    <script>
// ========== DATA ==========
const demoStaffList = ['Kavitha','Ramesh','Deepa','Suresh'];
const admissionOfficers = ['Ravi AO','Sunita AO','Prakash AO'];
const allAssignees = ['Self',...demoStaffList,...admissionOfficers];
const staffColors = ['#4f8ef7','#7b61ff','#00d4aa','#ff5470'];

const TAGS = ['hot','warm','cold','contacted','Demo booked','Demo Attended','Demo Missed','Demo converted','Demo lost'];

const inboundData = [
  {id:'IB001',name:'Priya Nair',phone:'+91 98400 12345',email:'priya@gmail.com',date:'22 Apr 2025',source:'MyOperator',tag:'hot',location:'Chennai',secondary:'+91 98400 55555',assigned:'Self',assignedAt:null,closed:false,
    calls:[
      {uid:'MO-8821',time:'09:14 AM',date:'22 Apr',type:'incoming',direction:'incoming',duration:'4m 32s',notes:'Interested in data science course, wants demo this week',tag:'hot'},
      {uid:'MO-7720',time:'03:30 PM',date:'21 Apr',type:'outgoing',direction:'outgoing',duration:'2m 10s',notes:'Called back, confirmed interest',tag:'warm'},
    ]},
  {id:'IB002',name:'Arjun Mehta',phone:'+91 90000 54321',email:'arjun@yahoo.com',date:'22 Apr 2025',source:'MyOperator',tag:'warm',location:'Mumbai',secondary:'',assigned:'Kavitha',assignedAt:'22 Apr 10:45 AM',closed:false,
    calls:[
      {uid:'MO-8800',time:'10:32 AM',date:'22 Apr',type:'incoming',direction:'incoming',duration:'6m 01s',notes:'Asking about fees and duration',tag:'warm'},
    ]},
  {id:'IB003',name:'Sneha Pillai',phone:'+91 91234 56789',email:'sneha@outlook.com',date:'21 Apr 2025',source:'MyOperator',tag:'cold',location:'Bangalore',secondary:'+91 91234 99999',assigned:null,assignedAt:null,closed:false,
    calls:[
      {uid:'MO-8755',time:'11:05 AM',date:'21 Apr',type:'missed',direction:'missed',duration:'—',notes:'',tag:'cold'},
    ]},
  {id:'IB004',name:'Kiran Desai',phone:'+91 99887 11223',email:'kiran@gmail.com',date:'20 Apr 2025',source:'MyOperator',tag:'Demo booked',location:'Pune',secondary:'',assigned:'Ramesh',assignedAt:'20 Apr 2:30 PM',closed:false,
    calls:[
      {uid:'MO-8700',time:'02:15 PM',date:'20 Apr',type:'outgoing',direction:'outgoing',duration:'8m 20s',notes:'Very interested, demo booked for tomorrow',tag:'Demo booked'},
    ]},
  {id:'IB005',name:'Meena Krishnan',phone:'+91 94444 77777',email:'meena@gmail.com',date:'19 Apr 2025',source:'MyOperator',tag:'contacted',location:'Coimbatore',secondary:'',assigned:null,assignedAt:null,closed:false,
    calls:[
      {uid:'MO-8650',time:'04:00 PM',date:'19 Apr',type:'incoming',direction:'incoming',duration:'3m 10s',notes:'First contact, requested callback',tag:'contacted'},
    ]},
];

const outboundData = [
  {id:'OB001',name:'Rahul Singh',phone:'+91 77777 88888',email:'rahul@gmail.com',date:'22 Apr 2025',source:'Instagram',tag:'Demo Attended',location:'Delhi',secondary:'',assigned:'Deepa',assignedAt:'22 Apr 9:15 AM',closed:false,
    calls:[
      {uid:'OB-1001',time:'09:00 AM',date:'22 Apr',type:'outgoing',direction:'outgoing',duration:'5m 12s',notes:'Very excited about the course, wants details',tag:'hot'},
      {uid:'OB-1001B',time:'11:00 AM',date:'22 Apr',type:'outgoing',direction:'outgoing',duration:'3m 00s',notes:'Demo attended confirmed',tag:'Demo Attended'},
    ]},
  {id:'OB002',name:'Divya Rajan',phone:'+91 88888 22222',email:'divya@gmail.com',date:'22 Apr 2025',source:'WhatsApp',tag:'warm',location:'Hyderabad',secondary:'',assigned:null,assignedAt:null,closed:false,
    calls:[
      {uid:'OB-1002',time:'11:30 AM',date:'22 Apr',type:'outgoing',direction:'outgoing',duration:'3m 40s',notes:'Comparing with other institutes',tag:'warm'},
    ]},
  {id:'OB003',name:'Vijay Kumar',phone:'+91 66666 44444',email:'vijay@gmail.com',date:'21 Apr 2025',source:'Facebook',tag:'cold',location:'Kochi',secondary:'',assigned:null,assignedAt:null,closed:false,
    calls:[
      {uid:'OB-1003',time:'02:00 PM',date:'21 Apr',type:'outgoing',direction:'outgoing',duration:'2m 00s',notes:'Said will think',tag:'cold'},
    ]},
  {id:'OB004',name:'Sanjay Patel',phone:'+91 44444 33333',email:'sanjay@gmail.com',date:'20 Apr 2025',source:'Google',tag:'Demo converted',location:'Ahmedabad',secondary:'',assigned:'Ravi AO',assignedAt:'22 Apr 1:00 PM',closed:false,
    calls:[
      {uid:'OB-1004',time:'10:00 AM',date:'20 Apr',type:'outgoing',direction:'outgoing',duration:'7m 30s',notes:'Demo converted, moved to admission officer',tag:'Demo converted'},
    ]},
];

const demoTrackData = [
  {name:'Karthik Rajan',phone:'+91 99887 66554',staff:'Kavitha',date:'24 Apr',slot:'Slot 1 – 11:30',status:'booked'},
  {name:'Rekha Nair',phone:'+91 92345 67890',staff:'Ramesh',date:'24 Apr',slot:'Slot 3 – 12:30',status:'booked'},
  {name:'Nalini Suresh',phone:'+91 55443 22110',staff:'Kavitha',date:'22 Apr',slot:'Slot 6 – 3:00',status:'attended'},
  {name:'Sunita Rao',phone:'+91 88776 55443',staff:'Deepa',date:'21 Apr',slot:'Slot 2 – 12:00',status:'missed'},
  {name:'Kiran Desai',phone:'+91 99887 11223',staff:'Kavitha',date:'22 Apr',slot:'Slot 1 – 11:30',status:'booked'},
  {name:'Rahul Singh',phone:'+91 77777 88888',staff:'Ramesh',date:'22 Apr',slot:'Slot 3 – 12:30',status:'attended'},
  {name:'Sanjay Patel',phone:'+91 44444 33333',staff:'Deepa',date:'20 Apr',slot:'Slot 5 – 10:00',status:'converted'},
  {name:'Divya Rajan',phone:'+91 88888 22222',staff:'Suresh',date:'19 Apr',slot:'Slot 2 – 12:00',status:'lost'},
];

const registeredData = [
  {name:'Sanjay Patel',phone:'+91 44444 33333',admissionOfficer:'Ravi AO',registeredDate:'21 Apr 2025',status:'registered',course:'Data Science'},
  {name:'Nalini Suresh',phone:'+91 55443 22110',admissionOfficer:'Sunita AO',registeredDate:'22 Apr 2025',status:'registered',course:'Full Stack Dev'},
  {name:'Preethi Kumar',phone:'+91 77321 44555',admissionOfficer:'Prakash AO',registeredDate:'20 Apr 2025',status:'registered',course:'Data Science'},
  {name:'Aarav Shah',phone:'+91 99001 22334',admissionOfficer:'Ravi AO',registeredDate:'18 Apr 2025',status:'registered',course:'Machine Learning'},
  {name:'Karthik Rajan',phone:'+91 99887 66554',admissionOfficer:'Sunita AO',registeredDate:'—',status:'converted',course:'UI/UX Design'},
  {name:'Rekha Nair',phone:'+91 92345 67890',admissionOfficer:'Ravi AO',registeredDate:'—',status:'converted',course:'Data Science'},
  {name:'Vijayalakshmi S',phone:'+91 88211 55677',admissionOfficer:'Prakash AO',registeredDate:'—',status:'converted',course:'Full Stack Dev'},
];

// ========== NAVIGATION ==========
function showSection(sec, el) {
  document.querySelectorAll('.page-section').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
  document.getElementById('sec-'+sec).classList.add('active');
  if(el) el.classList.add('active');
  const titles = {overview:'Overview',inbound:'Inbound Leads',outbound:'Outbound Leads',followups:'Follow-ups',schedule:'Schedule Demo',tracking:'Demo Tracking',registered:'Registered Students',batches:'Batch System'};
  document.getElementById('topbar-title').textContent = titles[sec]||sec;
  if(sec==='inbound') renderLeadsTable('inbound');
  if(sec==='outbound') renderLeadsTable('outbound');
  if(sec==='followups') renderFollowups();
  if(sec==='schedule') renderSchedule();
  if(sec==='tracking') renderTracking('all');
  if(sec==='registered') renderRegistered();
  if(sec==='batches') renderBatches();
}

// ========== LEADS TABLE (slim: name, phone, date, tag) ==========
function renderLeadsTable(type, dataArr) {
  const data = dataArr || (type==='inbound' ? inboundData : outboundData);
  const tbody = document.getElementById(type+'-tbody');
  tbody.innerHTML = data.map((l,i) => {
    // For Inbound Leads: all leads shown as "New Lead" (since they come from our dashboard)
    const displayTag = type==='inbound' ? 'New Lead' : l.tag;
    const displayTagClass = type==='inbound' ? 'newlead' : tagClass(l.tag);
    return `
    <tr onclick="openStudentDetail('${type}','${l.name}')">
      <td><span style="color:var(--text3);font-family:var(--mono);font-size:11px">${l.id}</span></td>
      <td><span class="lead-name">${l.name}</span></td>
      <td><span class="phone">${l.phone}</span></td>
      <td><span class="phone">${l.date}</span></td>
      <td><span class="tag ${displayTagClass}">${displayTag}</span></td>
    </tr>
  `;}).join('');
}

function tagClass(tag) {
  const map = {
    'hot':'hot','warm':'warm','cold':'cold','contacted':'contacted',
    'Demo booked':'booked','Demo Attended':'attended','Demo Missed':'missed',
    'Demo converted':'converted','Demo lost':'lost','registered':'registered','booked':'booked','attended':'attended','missed':'missed','converted':'converted','lost':'lost'
  };
  return map[tag] || 'cold';
}

function filterLeads(type) {
  const search = document.getElementById(type+'-search').value.toLowerCase();
  const tag = document.getElementById(type+'-tag-filter').value;
  const allData = type==='inbound' ? inboundData : outboundData;
  const filtered = allData.filter(l => {
    const matchSearch = !search || l.name.toLowerCase().includes(search) || l.phone.includes(search);
    const matchTag = !tag || l.tag.toLowerCase().includes(tag.toLowerCase());
    return matchSearch && matchTag;
  });
  renderLeadsTable(type, filtered);
}

// ========== STUDENT DETAIL ==========
let currentDetailType = '';
let pendingAssignCallIdx = null;
let pendingCloseLeadName = null;
let pendingCloseType = null;

function openStudentDetail(type, name) {
  currentDetailType = type;
  const data = type==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===name);
  if(!lead) return;
  const listEl = document.getElementById(type+'-list');
  const detailEl = document.getElementById(type+'-detail');
  listEl.style.display = 'none';
  detailEl.style.display = 'block';
  detailEl.innerHTML = buildDetailHTML(lead, type);
}

function buildDetailHTML(lead, type) {
  const tc = tagClass(lead.tag);
  const isAssigned = lead.assigned && lead.assigned !== 'Self';
  // Only show calls that are after assignment, or all if not assigned
  const visibleCalls = lead.calls; // show all call history; newest first for display
  return `
    <button class="back-btn" onclick="closeDetail('${type}')">← Back to ${type==='inbound'?'Inbound':'Outbound'} Leads</button>
    ${isAssigned ? `<div class="assigned-warning">⚠️ This lead is assigned to <strong>${lead.assigned}</strong> since ${lead.assignedAt}. Add notes before closing.</div>` : ''}
    <div class="profile-card">
      <div class="profile-top">
        <div class="profile-info">
          <div class="profile-avatar">${lead.name[0]}</div>
          <div>
            <div class="profile-name">${lead.name}</div>
            <div style="margin-top:4px;display:flex;gap:6px;align-items:center">
              <span class="tag ${tc}">${lead.tag}</span>
              <span style="font-size:12px;color:var(--text3)">${lead.id}</span>
              ${lead.assigned ? `<span style="font-size:12px;color:var(--text2)">Assigned → <strong style="color:var(--accent)">${lead.assigned}</strong></span>` : ''}
            </div>
          </div>
        </div>
        <div class="profile-actions">
          <button class="btn btn-outline btn-sm" onclick="openEditModal('${lead.name}','${type}')">✏️ Edit</button>
          <button class="btn btn-primary btn-sm" onclick="goScheduleDemo('${lead.name}')">📅 Schedule Demo</button>
        </div>
      </div>
      <div class="profile-fields">
        <div class="field-group"><div class="field-label">Phone</div><div class="field-value">${lead.phone}</div></div>
        <div class="field-group"><div class="field-label">Email</div><div class="field-value" style="font-size:12px">${lead.email}</div></div>
        <div class="field-group"><div class="field-label">Source</div><div class="field-value">${lead.source}</div></div>
        <div class="field-group"><div class="field-label">Location</div><div class="field-value">${lead.location}</div></div>
        <div class="field-group"><div class="field-label">Secondary</div><div class="field-value">${lead.secondary||'—'}</div></div>
        <div class="field-group"><div class="field-label">Lead Date</div><div class="field-value">${lead.date}</div></div>
        <div class="field-group"><div class="field-label">Assigned To</div><div class="field-value">${lead.assigned||'Unassigned'}</div></div>
        <div class="field-group"><div class="field-label">Lead Type</div><div class="field-value">${type==='inbound'?'Inbound':'Outbound'}</div></div>
      </div>
    </div>
    <div class="call-history">
      <div class="call-history-header">
        <div style="font-size:14px;font-weight:700;">🕑 Timeline <span style="font-size:12px;font-weight:400;color:var(--text3);margin-left:8px">(Previous calls + current active call)</span></div>
        <button class="btn btn-primary btn-sm" onclick="openAddCallModal('${lead.name}','${type}')" style="display:flex;align-items:center;gap:6px;padding:6px 14px;">
          <span style="font-size:16px;line-height:1;">+</span> Add Entry
        </button>
      </div>

      <!-- PREVIOUS SECTION (collapsible, non-editable) -->
      <div class="timeline-group">
        <div class="timeline-group-header" onclick="togglePrev(this)">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:13px;font-weight:700;color:var(--text2);text-transform:uppercase;letter-spacing:.5px">Previous</span>
            <span style="font-size:11px;color:var(--text3)">(${Math.max(visibleCalls.length-1,0)} calls — already spoken)</span>
          </div>
          <span class="prev-arrow">▼</span>
        </div>
        <div class="timeline-prev-body" style="display:none">
          ${visibleCalls.length > 1 ? `
            <div class="ch-cols ch-cols-readonly">
              <div class="ch-col">Call Time</div>
              <div class="ch-col">Direction</div>
              <div class="ch-col">Recording</div>
              <div class="ch-col">Duration</div>
              <div class="ch-col">Owned By</div>
              <div class="ch-col">Tag</div>
              <div class="ch-col">Notes</div>
            </div>
            ${visibleCalls.slice(1).map((c,ci) => buildCallRowReadonly(c, ci+1, lead)).join('')}
          ` : `<div style="padding:20px;text-align:center;color:var(--text3);font-size:12px">No previous calls yet.</div>`}
        </div>
      </div>

      <!-- CURRENT SECTION (editable, only latest call + Assign) -->
      <div class="timeline-group">
        <div class="timeline-group-header" style="cursor:default">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:13px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.5px">● Current</span>
            <span style="font-size:11px;color:var(--text3)">(Active call being handled)</span>
          </div>
        </div>
        <div class="timeline-current-body">
          ${visibleCalls.length > 0 ? `
            <div class="ch-cols ch-cols-current">
              <div class="ch-col">Call Time</div>
              <div class="ch-col">Direction</div>
              <div class="ch-col">Recording</div>
              <div class="ch-col">Duration</div>
              <div class="ch-col">Owned By</div>
              <div class="ch-col">Tag</div>
              <div class="ch-col">Notes + Assign</div>
            </div>
            ${buildCallRowCurrent(visibleCalls[0], 0, lead, type)}
          ` : `<div style="padding:24px;text-align:center;color:var(--text3)">No current call to handle.</div>`}
        </div>
      </div>
    </div>
  `;
}

function togglePrev(headerEl) {
  const body = headerEl.nextElementSibling;
  const arrow = headerEl.querySelector('.prev-arrow');
  if(body.style.display === 'none') {
    body.style.display = 'block';
    arrow.textContent = '▲';
  } else {
    body.style.display = 'none';
    arrow.textContent = '▼';
  }
}

// Read-only row for Previous section (no editable fields, no close button)
function buildCallRowReadonly(c, ci, lead) {
  const dirClass = c.direction==='incoming'?'incoming':c.direction==='outgoing'?'outgoing':'missed';
  const dirLabel = c.direction==='incoming'?'📲 Incoming':c.direction==='outgoing'?'📞 Outgoing':'📵 Missed';
  const tc = tagClass(c.tag||lead.tag);
  return `
    <div class="call-row call-row-readonly">
      <div>
        <div class="call-time">${c.time}</div>
        <div style="font-size:10px;color:var(--text3);margin-top:2px">${c.date}</div>
      </div>
      <div><span class="direction-badge ${dirClass}">${dirLabel}</span></div>
      <div>
        ${c.direction!=='missed'
          ? `<button class="recording-btn">▶ Play <span style="font-size:9px;color:var(--text3)">${c.duration}</span></button>`
          : `<span style="color:var(--text3);font-size:11px">No recording</span>`}
      </div>
      <div style="font-size:12px;color:var(--text2);font-family:var(--mono)">${c.duration}</div>
      <div style="font-size:12px;color:var(--text2);font-weight:600">${lead.assigned||'Self'}</div>
      <div><span class="tag ${tc}" style="display:inline-block">${c.tag||lead.tag}</span></div>
      <div style="font-size:12px;color:var(--text2);padding:6px 10px;background:var(--surface3);border-radius:6px;border:1px solid var(--border)">${c.notes||'—'}</div>
    </div>
  `;
}

// Current editable row — only Assign button (no Close)
function buildCallRowCurrent(c, ci, lead, type) {
  const dirClass = c.direction==='incoming'?'incoming':c.direction==='outgoing'?'outgoing':'missed';
  const dirLabel = c.direction==='incoming'?'📲 Incoming':c.direction==='outgoing'?'📞 Outgoing':'📵 Missed';
  return `
    <div class="call-row call-row-current">
      <div>
        <div class="call-time">${c.time}</div>
        <div style="font-size:10px;color:var(--text3);margin-top:2px">${c.date}</div>
      </div>
      <div><span class="direction-badge ${dirClass}">${dirLabel}</span></div>
      <div>
        ${c.direction!=='missed'
          ? `<button class="recording-btn">▶ Play <span style="font-size:9px;color:var(--text3)">${c.duration}</span></button>`
          : `<span style="color:var(--text3);font-size:11px">No recording</span>`}
      </div>
      <div style="font-size:12px;color:var(--text2);font-family:var(--mono)">${c.duration}</div>
      <div style="font-size:12px;color:var(--text2);font-weight:600">${lead.assigned||'Self'}</div>
      <div>
        <select class="tag-select" onchange="updateCallTag(this,'${type}','${lead.name}',${ci})">
          ${TAGS.map(t=>`<option value="${t}" ${(c.tag||lead.tag)===t?'selected':''}>${t}</option>`).join('')}
        </select>
      </div>
      <div class="assign-area">
        <textarea class="notes-input" rows="2" id="note-${lead.name.replace(/\s/g,'_')}-${ci}" placeholder="Add notes...">${c.notes||''}</textarea>
        <button class="assign-ok" onclick="openAssignModal('${lead.name}','${type}')">👤 Assign</button>
      </div>
    </div>
  `;
}

function updateCallTag(sel, type, name, callIdx) {
  const data = type==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===name);
  if(lead && lead.calls[callIdx]) {
    lead.calls[callIdx].tag = sel.value;
    lead.tag = sel.value;
    // Re-render just the tag badge in profile
  }
}

function closeDetail(type) {
  document.getElementById(type+'-list').style.display = 'block';
  document.getElementById(type+'-detail').innerHTML = '';
  document.getElementById(type+'-detail').style.display = 'none';
}

function goScheduleDemo(name) {
  document.getElementById('schedule-for-name').textContent = name;
  document.getElementById('schedule-lead-banner').style.display = 'flex';
  showSection('schedule', document.querySelectorAll('.nav-item')[4]);
}

// ========== EDIT MODAL ==========
function openEditModal(name, type) {
  const data = type==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===name);
  if(!lead) return;
  document.getElementById('edit-name').value = lead.name;
  document.getElementById('edit-phone').value = lead.phone;
  document.getElementById('edit-email').value = lead.email;
  document.getElementById('edit-sec').value = lead.secondary||'';
  document.getElementById('edit-loc').value = lead.location;
  document.getElementById('edit-source').value = lead.source;
  document.getElementById('edit-modal').style.display = 'flex';
}
function saveEdit() {
  document.getElementById('edit-modal').style.display = 'none';
  showToast('Profile updated successfully!','success');
}
function openAddLeadModal() { document.getElementById('add-lead-modal').style.display='flex'; }

// ========== ASSIGN MODAL ==========
let pendingAssignName = null;
let pendingAssignType = null;
function openAssignModal(name, type) {
  pendingAssignName = name;
  pendingAssignType = type;
  document.getElementById('assign-notes').value = '';
  document.getElementById('assign-tag').value = '';
  // Default date = today
  const today = new Date();
  const yyyy = today.getFullYear();
  const mm = String(today.getMonth()+1).padStart(2,'0');
  const dd = String(today.getDate()).padStart(2,'0');
  document.getElementById('assign-date').value = `${yyyy}-${mm}-${dd}`;
  document.getElementById('assign-time').value = '';
  document.getElementById('assign-modal').style.display = 'flex';
}
function confirmAssign() {
  const notes = document.getElementById('assign-notes').value.trim();
  const fixDate = document.getElementById('assign-date').value;
  if(!notes) { showToast('Notes are required before assigning!','warn'); return; }
  if(!fixDate) { showToast('Please fix a date before pushing the lead!','warn'); return; }
  const assignTo = document.getElementById('assign-to').value;
  const fixTime = document.getElementById('assign-time').value;
  const newTag = document.getElementById('assign-tag').value;
  const data = pendingAssignType==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===pendingAssignName);
  if(lead) {
    lead.assigned = assignTo === 'self' ? 'Self' : assignTo;
    lead.assignedAt = new Date().toLocaleString('en-IN',{day:'numeric',month:'short',hour:'2-digit',minute:'2-digit'});
    lead.fixedDate = fixDate + (fixTime? ' '+fixTime : '');
    if(newTag) lead.tag = newTag;
    // Add a follow-up entry
    const allFollowups = pendingAssignType==='inbound' ? followupDataFlat.inbound : followupDataFlat.outbound;
    if(!allFollowups.find(f=>f.name===lead.name)) {
      allFollowups.push({name:lead.name,phone:lead.phone,tag:lead.tag,notes:notes,type:pendingAssignType,lastCall:'Just now',uid:lead.calls[lead.calls.length-1]?.uid||'—'});
    }
  }
  document.getElementById('assign-modal').style.display = 'none';
  showToast(`Lead pushed to ${assignTo==='self'?'Self':assignTo} for ${fixDate}!`,'success');
  openStudentDetail(pendingAssignType, pendingAssignName);
}

// ========== CLOSE LEAD MODAL ==========
function openCloseModal(name, type) {
  pendingCloseLeadName = name;
  pendingCloseType = type;
  const data = type==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===name);
  const warn = document.getElementById('close-modal-warning');
  if(lead && lead.assigned && lead.assigned !== 'Self') {
    warn.style.display = 'block';
  } else {
    warn.style.display = 'none';
  }
  document.getElementById('close-notes').value = '';
  document.getElementById('close-modal').style.display = 'flex';
}
function confirmClose() {
  const notes = document.getElementById('close-notes').value.trim();
  if(!notes) { showToast('Closing notes are required!','warn'); return; }
  const data = pendingCloseType==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===pendingCloseLeadName);
  const warn = document.getElementById('close-modal-warning');
  if(lead && lead.assigned && lead.assigned !== 'Self' && !notes) {
    warn.style.display = 'block';
    showToast('You must add notes before closing an assigned lead!','warn');
    return;
  }
  if(lead) {
    lead.closed = true;
    lead.tag = document.getElementById('close-status').value;
  }
  document.getElementById('close-modal').style.display = 'none';
  showToast('Lead closed successfully!','success');
  closeDetail(pendingCloseType);
}

// ========== FOLLOW-UPS (flat list with search, no date bubbles) ==========
const followupDataFlat = {
  inbound: [
    {name:'Priya Nair',phone:'+91 98400 12345',tag:'warm',lastCall:'Yesterday 4:30 PM',uid:'MO-8821',notes:'Interested, needs follow-up',type:'inbound'},
    {name:'Arjun Mehta',phone:'+91 90000 54321',tag:'hot',lastCall:'Today 10:32 AM',uid:'MO-8800',notes:'Comparing options',type:'inbound'},
    {name:'Sneha Pillai',phone:'+91 91234 56789',tag:'cold',lastCall:'Yesterday 11:05 AM',uid:'MO-8755',notes:'Needs more time',type:'inbound'},
    {name:'Meena Krishnan',phone:'+91 94444 77777',tag:'contacted',lastCall:'19 Apr 4:00 PM',uid:'MO-8650',notes:'Requested callback',type:'inbound'},
  ],
  outbound: [
    {name:'Rahul Singh',phone:'+91 77777 88888',tag:'hot',lastCall:'Today 9:00 AM',uid:'OB-1001',notes:'Very interested',type:'outbound'},
    {name:'Divya Rajan',phone:'+91 88888 22222',tag:'warm',lastCall:'Today 11:30 AM',uid:'OB-1002',notes:'Comparing institutes',type:'outbound'},
    {name:'Vijay Kumar',phone:'+91 66666 44444',tag:'cold',lastCall:'21 Apr 2:00 PM',uid:'OB-1003',notes:'Said will think',type:'outbound'},
    {name:'Kiran Desai',phone:'+91 99887 11223',tag:'booked',lastCall:'20 Apr 2:15 PM',uid:'MO-8700',notes:'Demo booked, confirm',type:'inbound'},
  ]
};

function getAllFollowups() {
  return [...followupDataFlat.inbound, ...followupDataFlat.outbound];
}

function filterFollowups() {
  const search = document.getElementById('fu-search').value.toLowerCase();
  const tag = document.getElementById('fu-tag-filter').value.toLowerCase();
  const type = document.getElementById('fu-type-filter').value.toLowerCase();
  const all = getAllFollowups();
  const filtered = all.filter(l => {
    const ms = !search || l.name.toLowerCase().includes(search) || l.phone.includes(search) || l.notes.toLowerCase().includes(search);
    const mt = !tag || l.tag.toLowerCase() === tag;
    const mtype = !type || l.type === type;
    return ms && mt && mtype;
  });
  renderFollowupList(filtered);
}

function renderFollowups() {
  renderFollowupList(getAllFollowups());
}

function renderFollowupList(list) {
  const el = document.getElementById('followup-list');
  if(!list.length) {
    el.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text3)">No follow-ups found 🎉</div>';
    return;
  }
  el.innerHTML = list.map(l => `
    <div class="followup-row" onclick="openFollowupDetail('${l.name}','${l.type}')">
      <div class="today-dot" style="background:var(--${l.tag==='hot'?'hot':l.tag==='warm'?'warn':l.tag==='cold'?'cold':'accent'})"></div>
      <div style="flex:1">
        <div style="font-size:14px;font-weight:700;color:var(--accent)">${l.name}</div>
        <div style="font-size:12px;color:var(--text3);margin-top:2px">${l.notes}</div>
      </div>
      <div style="text-align:right">
        <div class="phone">${l.phone}</div>
        <div style="font-size:11px;color:var(--text3);margin-top:2px">${l.lastCall}</div>
      </div>
      <span class="tag ${tagClass(l.tag)}">${l.tag}</span>
      <span style="font-size:10px;background:var(--surface2);border:1px solid var(--border);border-radius:4px;padding:2px 8px;color:var(--text3)">${l.type==='inbound'?'📥 Inbound':'📤 Outbound'}</span>
    </div>
  `).join('');
}

function openFollowupDetail(name, type) {
  showSection(type, document.querySelectorAll('.nav-item')[type==='inbound'?1:2]);
  openStudentDetail(type, name);
}

// ========== SCHEDULE ==========
const slotTimes = ['11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM'];
const slotBookings = {};
demoStaffList.forEach(s=>{ slotBookings[s]=[{},{},{},{}]; });
const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
const staffDayLabels = () => {
  const r = [];
  for(let i=0;i<4;i++){
    const d = new Date(Date.now()+i*86400000);
    r.push({label:i===0?'Today':i===1?'Tomorrow':days[d.getDay()]+' '+d.getDate(), idx:i});
  }
  return r;
};
const activeDemoDay = {};
demoStaffList.forEach(s=>activeDemoDay[s]=0);

function renderSchedule() {
  const grid = document.getElementById('demo-staff-grid');
  grid.innerHTML = demoStaffList.map((staff,si) => {
    const dayLabels = staffDayLabels();
    const activeDay = activeDemoDay[staff];
    const slots = slotBookings[staff][activeDay];
    const bookedCount = Object.values(slots).filter(v=>v).length;
    return `
      <div class="staff-demo-card">
        <div class="staff-demo-header">
          <div class="staff-avatar" style="background:${staffColors[si]}">${staff[0]}</div>
          <div>
            <div class="staff-name">${staff}</div>
            <div style="font-size:11px;color:var(--text3)">${bookedCount}/10 slots booked</div>
          </div>
        </div>
        <div class="demo-days">
          ${dayLabels.map((dl,di)=>`
            <div class="demo-day-tab ${di===activeDay?'active':''}" onclick="selectDemoDay('${staff}',${di})">
              ${dl.label}
            </div>
          `).join('')}
        </div>
        <div class="slots-grid">
          ${slotTimes.map((t,ti)=>{
            const booking = slots[ti];
            const cls = booking ? 'booked' : 'available';
            return `
              <div class="slot ${cls}" onclick="${booking?`alert('Booked: '+JSON.stringify(${JSON.stringify(booking)}).name)`:`openSlotModal('${staff}',${activeDay},${ti},'${t}')`}">
                <div style="display:flex;align-items:center;">
                  <span class="slot-num">S${ti+1}</span>
                  <span class="slot-time">${t}</span>
                </div>
                <div class="slot-name">${booking ? '✅ '+booking.name : '+ Available'}</div>
              </div>
            `;
          }).join('')}
        </div>
      </div>
    `;
  }).join('');
}
function selectDemoDay(staff, day) { activeDemoDay[staff] = day; renderSchedule(); }

let pendingSlot = null;
function openSlotModal(staff, day, slotIdx, time) {
  pendingSlot = {staff,day,slotIdx};
  const leadName = document.getElementById('schedule-for-name').textContent;
  document.getElementById('slot-modal-content').innerHTML = `
    <div style="background:var(--surface2);border-radius:10px;padding:16px;margin-bottom:16px;">
      <div style="font-size:12px;color:var(--text3);margin-bottom:8px">BOOKING DETAILS</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div><div class="field-label">Staff</div><div class="field-value">${staff}</div></div>
        <div><div class="field-label">Time Slot</div><div class="field-value" style="color:var(--accent)">${time}</div></div>
        <div><div class="field-label">Day</div><div class="field-value">${staffDayLabels()[day].label}</div></div>
        <div><div class="field-label">Slot #</div><div class="field-value">Slot ${slotIdx+1}</div></div>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Student Name</label>
      <input class="form-input" id="slot-student" value="${leadName!=='—'?leadName:''}" placeholder="Enter student name"/>
    </div>
  `;
  document.getElementById('confirm-slot-btn').onclick = confirmSlotBooking;
  document.getElementById('slot-modal').style.display = 'flex';
}
function confirmSlotBooking() {
  const name = document.getElementById('slot-student').value.trim();
  if(!name){showToast('Please enter student name','warn');return;}
  const {staff,day,slotIdx} = pendingSlot;
  slotBookings[staff][day][slotIdx] = {name};
  document.getElementById('slot-modal').style.display = 'none';
  renderSchedule();
  showToast(`Demo booked for ${name}!`,'success');
}

// ========== DEMO TRACKING ==========
let currentDemoTab = 'all';
function switchDemoTab(tab, el) {
  currentDemoTab = tab;
  document.querySelectorAll('.demo-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  renderTracking(tab);
}
function renderTracking(tab) {
  const list = document.getElementById('demo-track-list');
  const filtered = tab==='all' ? demoTrackData : demoTrackData.filter(d=>d.status===tab);
  if(!filtered.length){list.innerHTML='<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text3)">No demos in this category</td></tr>';return;}
  const statusLabel = {booked:'Demo Booked',attended:'Demo Attended',missed:'Demo Missed',converted:'Converted',lost:'Lost'};
  list.innerHTML = filtered.map((d,idx) => {
    return `
      <tr>
        <td>
          <div style="font-size:14px;font-weight:700">${d.name}</div>
          <div style="font-size:12px;color:var(--text3)">${d.phone}</div>
        </td>
        <td><span class="phone">${d.date}</span></td>
        <td><span style="color:var(--accent);font-weight:600;font-size:13px">${d.slot}</span></td>
        <td><span class="demo-staff-badge">${d.staff}</span></td>
        <td><span class="tag ${tagClass(d.status)}">${statusLabel[d.status]||d.status}</span></td>
      </tr>
    `;
  }).join('');
}
function updateDemoStatus(idx, newStatus) {
  demoTrackData[idx].status = newStatus;
  // If attended, mark the lead as Demo Attended
  const lead = inboundData.find(l=>l.name===demoTrackData[idx].name) || outboundData.find(l=>l.name===demoTrackData[idx].name);
  if(lead) {
    if(newStatus==='attended') lead.tag = 'Demo Attended';
    else if(newStatus==='converted') { lead.tag = 'Demo converted'; lead.assigned = 'Ravi AO'; }
    else if(newStatus==='missed') lead.tag = 'Demo Missed';
  }
  renderTracking(currentDemoTab);
  showToast('Status updated!','success');
}

// ========== REGISTERED STUDENTS ==========
function renderRegistered(dataArr) {
  const data = dataArr || registeredData;
  const regCount = data.filter(r=>r.status==='registered').length;
  const convCount = data.filter(r=>r.status==='converted').length;
  document.getElementById('reg-total').textContent = regCount;
  document.getElementById('reg-pending').textContent = convCount;
  document.getElementById('reg-completed').textContent = regCount;
  const el = document.getElementById('reg-list');
  if(!data.length){el.innerHTML='<div style="text-align:center;padding:40px;color:var(--text3)">No registered students yet.</div>';return;}
  el.innerHTML = data.map(r=>`
    <div class="reg-row">
      <div class="profile-avatar" style="width:40px;height:40px;font-size:16px;border-radius:10px">${r.name[0]}</div>
      <div style="flex:1;">
        <div style="font-size:14px;font-weight:700">${r.name}</div>
        <div style="font-size:12px;color:var(--text3);margin-top:2px">${r.phone} &nbsp;·&nbsp; ${r.course}</div>
      </div>
      <div class="reg-step">
        <span class="done">✅ Converted</span>
        <span class="arrow">→</span>
        <strong>${r.status==='registered'?'<span class="done">✅ Registered</span>':'<span style="color:var(--warn)">⏳ Pending</span>'}</strong>
      </div>
      <div style="text-align:right">
        <span class="tag ${tagClass(r.status)}">${r.status}</span>
        <div style="font-size:11px;color:var(--text3);margin-top:4px">AO: ${r.admissionOfficer}</div>
        ${r.registeredDate!=='—'?`<div style="font-size:11px;color:var(--text3)">${r.registeredDate}</div>`:''}
      </div>
    </div>
  `).join('');
}

function filterRegistered() {
  const search = document.getElementById('reg-search').value.toLowerCase();
  const ao = document.getElementById('reg-ao-filter').value;
  const status = document.getElementById('reg-status-filter').value;
  const filtered = registeredData.filter(r=>{
    const ms = !search || r.name.toLowerCase().includes(search) || r.phone.includes(search);
    const mao = !ao || r.admissionOfficer===ao;
    const mst = !status || r.status===status;
    return ms && mao && mst;
  });
  renderRegistered(filtered);
}

// ========== TOAST ==========
function showToast(msg, type='success') {
  let t = document.getElementById('toast');
  if(!t){t=document.createElement('div');t.id='toast';t.style.cssText='position:fixed;bottom:28px;right:28px;z-index:9999;padding:12px 22px;border-radius:10px;font-family:Sora,sans-serif;font-size:13px;font-weight:600;max-width:320px;transition:.3s;opacity:0;transform:translateY(10px);';document.body.appendChild(t);}
  t.textContent = msg;
  if(type==='success'){t.style.background='#22d07a';t.style.color='#000';}
  else if(type==='warn'){t.style.background='#ffb547';t.style.color='#000';}
  else{t.style.background='#ff5470';t.style.color='#fff';}
  t.style.opacity='1';t.style.transform='translateY(0)';
  clearTimeout(t._timer);
  t._timer = setTimeout(()=>{t.style.opacity='0';t.style.transform='translateY(10px)';},3000);
}

// ========== ADD CALL ENTRY ==========
let pendingAddCallName = null;
let pendingAddCallType = null;
function openAddCallModal(name, type) {
  pendingAddCallName = name;
  pendingAddCallType = type;
  const today = new Date();
  document.getElementById('new-call-date').value = today.toISOString().split('T')[0];
  document.getElementById('new-call-time').value = today.toTimeString().slice(0,5);
  document.getElementById('new-call-notes').value = '';
  document.getElementById('new-call-direction').value = 'outgoing';
  document.getElementById('new-call-tag').value = 'contacted';
  document.getElementById('new-call-duration').value = '';
  document.getElementById('add-call-modal').style.display = 'flex';
}
function confirmAddCall() {
  const notes = document.getElementById('new-call-notes').value.trim();
  if(!notes) { showToast('Notes are required!','warn'); return; }
  const data = pendingAddCallType==='inbound' ? inboundData : outboundData;
  const lead = data.find(l=>l.name===pendingAddCallName);
  if(!lead) return;
  const dateVal = document.getElementById('new-call-date').value;
  const timeVal = document.getElementById('new-call-time').value;
  const direction = document.getElementById('new-call-direction').value;
  const duration = document.getElementById('new-call-duration').value.trim() || '—';
  const tag = document.getElementById('new-call-tag').value;
  const d = new Date(dateVal);
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const dateLabel = d.getDate()+' '+months[d.getMonth()];
  const [h,m] = timeVal.split(':');
  const hr = parseInt(h);
  const ampm = hr>=12 ? 'PM' : 'AM';
  const hr12 = hr%12||12;
  const timeLabel = `${hr12}:${m} ${ampm}`;
  const uid = (pendingAddCallType==='inbound'?'MO-':'OB-') + Math.floor(Math.random()*9000+1000);
  lead.calls.unshift({uid, time:timeLabel, date:dateLabel, type:direction, direction, duration, notes, tag});
  lead.tag = tag;
  document.getElementById('add-call-modal').style.display = 'none';
  showToast('Call entry added successfully!','success');
  openStudentDetail(pendingAddCallType, pendingAddCallName);
}

// ========== BATCH SYSTEM ==========
const today = new Date();
function batchDate(offsetDays) {
  const d = new Date(today.getTime() + offsetDays*86400000);
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  return d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear();
}
function deadlineDate(offsetDays) {
  const d = new Date(today.getTime() + offsetDays*86400000);
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  return d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear();
}
const batchData = {
  fullstack: {
    label: 'Full Stack Development', emoji: '🖥️',
    started: [
      {code:'FS-B24-01', course:'Full Stack Web Dev', mentor:'Karthik Raja', startDate: batchDate(-18), deadline: deadlineDate(4), joined:22, required:30},
      {code:'FS-B24-02', course:'MERN Stack', mentor:'Anitha S', startDate: batchDate(-10), deadline: deadlineDate(-1), joined:28, required:28},
    ],
    upcoming: [
      {code:'FS-B24-03', course:'Full Stack Web Dev', mentor:'Karthik Raja', startDate: batchDate(12), deadline: deadlineDate(10), joined:8, required:30},
      {code:'FS-B24-04', course:'MERN Stack', mentor:'Anitha S', startDate: batchDate(20), deadline: deadlineDate(18), joined:3, required:28},
    ]
  },
  uiux: {
    label: 'UI/UX Design', emoji: '🎨',
    started: [
      {code:'UX-B24-01', course:'UI/UX Foundations', mentor:'Deepika M', startDate: batchDate(-14), deadline: deadlineDate(6), joined:18, required:25},
    ],
    upcoming: [
      {code:'UX-B24-02', course:'Advanced UX Research', mentor:'Deepika M', startDate: batchDate(15), deadline: deadlineDate(13), joined:5, required:25},
      {code:'UX-B24-03', course:'Figma Mastery', mentor:'Suresh K', startDate: batchDate(22), deadline: deadlineDate(20), joined:2, required:20},
    ]
  },
  dataanalytics: {
    label: 'Data Analytics', emoji: '📊',
    started: [
      {code:'DA-B24-01', course:'Data Analytics with Python', mentor:'Ramesh V', startDate: batchDate(-20), deadline: deadlineDate(3), joined:25, required:30},
      {code:'DA-B24-02', course:'Power BI & Tableau', mentor:'Kavitha N', startDate: batchDate(-8), deadline: deadlineDate(7), joined:14, required:25},
    ],
    upcoming: [
      {code:'DA-B24-03', course:'Data Analytics with Python', mentor:'Ramesh V', startDate: batchDate(18), deadline: deadlineDate(16), joined:6, required:30},
    ]
  },
  digitalmarketing: {
    label: 'Digital Marketing', emoji: '📣',
    started: [
      {code:'DM-B24-01', course:'Digital Marketing Pro', mentor:'Sunita L', startDate: batchDate(-12), deadline: deadlineDate(5), joined:20, required:25},
    ],
    upcoming: [
      {code:'DM-B24-02', course:'SEO & SEM Mastery', mentor:'Prakash R', startDate: batchDate(10), deadline: deadlineDate(8), joined:7, required:20},
      {code:'DM-B24-03', course:'Social Media Marketing', mentor:'Sunita L', startDate: batchDate(25), deadline: deadlineDate(23), joined:1, required:20},
    ]
  }
};

let currentBatchCat = 'fullstack';
function switchBatchCat(cat, el) {
  currentBatchCat = cat;
  document.querySelectorAll('#batch-cat-tabs .demo-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  renderBatches();
}

function isDeadlinePassed(deadlineStr) {
  const months = {Jan:0,Feb:1,Mar:2,Apr:3,May:4,Jun:5,Jul:6,Aug:7,Sep:8,Oct:9,Nov:10,Dec:11};
  const parts = deadlineStr.split(' ');
  if(parts.length < 3) return false;
  const d = new Date(parseInt(parts[2]), months[parts[1]], parseInt(parts[0]));
  return d < today;
}

function buildBatchRow(b) {
  const passed = isDeadlinePassed(b.deadline);
  const fillPct = Math.round((b.joined/b.required)*100);
  const fillColor = fillPct>=100 ? 'var(--success)' : fillPct>=70 ? 'var(--warn)' : 'var(--accent)';
  return `
    <tr>
      <td><span style="font-family:var(--mono);font-size:12px;color:var(--accent);font-weight:700">${b.code}</span></td>
      <td><span style="font-weight:600;font-size:13px">${b.course}</span></td>
      <td><span style="font-size:13px;color:var(--text2)">${b.mentor}</span></td>
      <td><span class="phone">${b.startDate}</span></td>
      <td>
        <span class="phone" style="color:${passed?'var(--danger)':'var(--warn)'};font-weight:600">${b.deadline}</span>
        ${passed?`<span style="font-size:10px;display:block;color:var(--danger)">Deadline Passed</span>`:''}
      </td>
      <td>
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:14px;font-weight:700;color:${fillColor}">${b.joined}</span>
          <div style="width:50px;height:5px;background:var(--border);border-radius:3px;overflow:hidden">
            <div style="height:100%;width:${Math.min(fillPct,100)}%;background:${fillColor};border-radius:3px;transition:.3s"></div>
          </div>
        </div>
      </td>
      <td><span style="font-size:14px;font-weight:700;color:var(--text2)">${b.required}</span></td>
      <td>
        ${b.joined>=b.required
          ? `<span class="tag converted">Full</span>`
          : passed
            ? `<span class="tag missed">Closed</span>`
            : `<span class="tag contacted" style="background:rgba(34,208,122,.15);color:var(--success)">Open</span>`}
      </td>
    </tr>
  `;
}

function renderBatches() {
  const cat = batchData[currentBatchCat];
  document.getElementById('batch-cat-emoji').textContent = cat.emoji;
  document.getElementById('batch-cat-name').textContent = cat.label;

  // Started batches — only show those whose deadline hasn't passed
  const visibleStarted = cat.started.filter(b => !isDeadlinePassed(b.deadline));
  const startedTbody = document.getElementById('started-batch-tbody');
  if(visibleStarted.length === 0) {
    startedTbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:28px;color:var(--text3)">No active started batches with open deadlines in this category.</td></tr>`;
  } else {
    startedTbody.innerHTML = visibleStarted.map(buildBatchRow).join('');
  }

  // Upcoming batches — always show all
  const upcomingTbody = document.getElementById('upcoming-batch-tbody');
  if(cat.upcoming.length === 0) {
    upcomingTbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:28px;color:var(--text3)">No upcoming batches scheduled yet.</td></tr>`;
  } else {
    upcomingTbody.innerHTML = cat.upcoming.map(buildBatchRow).join('');
  }
}

// ========== INIT ==========
document.getElementById('today-date').textContent = new Date().toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'});
renderLeadsTable('inbound');
renderLeadsTable('outbound');
renderRegistered();
</script>
</body>
</html>
