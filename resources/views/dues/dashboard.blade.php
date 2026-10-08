<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Velearn — Dues Collector</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700&family=Geist:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --bg-base: #07090d;
    --bg-deep: #0a0e14;
    --bg-card: #10151d;
    --bg-card-hover: #141b25;
    --bg-elevated: #161e29;
    --border-subtle: #1c2330;
    --border-strong: #232c3b;
    --border-accent: #2d3a4f;

    --text-primary: #e7ecf3;
    --text-secondary: #9aa4b5;
    --text-muted: #5e6878;
    --text-faint: #3d4654;

    --accent: #4f8cff;
    --accent-soft: rgba(79, 140, 255, 0.12);
    --accent-strong: #6ba0ff;

    --gold: #d4a857;
    --gold-soft: rgba(212, 168, 87, 0.14);

    --success: #34d399;
    --success-soft: rgba(52, 211, 153, 0.13);
    --warning: #f59e0b;
    --warning-soft: rgba(245, 158, 11, 0.13);
    --danger: #ef4444;
    --danger-soft: rgba(239, 68, 68, 0.13);
    --purple: #a78bfa;
    --purple-soft: rgba(167, 139, 250, 0.13);
    --pink: #f472b6;
    --pink-soft: rgba(244, 114, 182, 0.13);
    --teal: #2dd4bf;
    --teal-soft: rgba(45, 212, 191, 0.13);

    --shadow-sm: 0 1px 2px rgba(0,0,0,0.4);
    --shadow-md: 0 6px 20px rgba(0,0,0,0.35);
    --shadow-lg: 0 14px 40px rgba(0,0,0,0.5);

    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-xl: 18px;

    --font-display: 'Bricolage Grotesque', sans-serif;
    --font-body: 'Geist', sans-serif;
    --font-mono: 'JetBrains Mono', monospace;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }
  body {
    font-family: var(--font-body);
    background: var(--bg-base);
    color: var(--text-primary);
    font-size: 14px;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
    background-image:
      radial-gradient(ellipse 80% 50% at 50% -20%, rgba(79, 140, 255, 0.08), transparent),
      radial-gradient(ellipse 60% 50% at 100% 100%, rgba(212, 168, 87, 0.04), transparent);
    background-attachment: fixed;
  }

  ::-webkit-scrollbar { width: 8px; height: 8px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: var(--border-accent); }

  #app { display: grid; grid-template-columns: 248px 1fr; min-height: 100vh; }

  /* SIDEBAR */
  .sidebar {
    background: var(--bg-deep);
    border-right: 1px solid var(--border-subtle);
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
  }
  .brand {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 4px 8px 24px;
    border-bottom: 1px solid var(--border-subtle);
    margin-bottom: 16px;
  }
  .brand-mark {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--accent), var(--purple));
    border-radius: 8px;
    display: grid; place-items: center;
    font-family: var(--font-display);
    font-weight: 700;
    color: white;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(79, 140, 255, 0.35);
  }
  .brand-text { font-family: var(--font-display); font-size: 17px; font-weight: 600; letter-spacing: -0.02em; }
  .brand-sub { font-size: 10px; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; margin-top: 1px; }

  .nav-section-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: var(--text-faint); padding: 12px 12px 8px; font-weight: 500; }
  .nav-item {
    display: flex; align-items: center; gap: 12px; padding: 10px 12px;
    border-radius: var(--radius-md); color: var(--text-secondary); cursor: pointer;
    font-size: 14px; font-weight: 500; margin-bottom: 2px; transition: all 0.15s ease; position: relative;
  }
  .nav-item:hover { background: var(--bg-card); color: var(--text-primary); }
  .nav-item.active { background: var(--accent-soft); color: var(--accent-strong); }
  .nav-item.active::before {
    content: ''; position: absolute; left: -16px; top: 8px; bottom: 8px;
    width: 2px; background: var(--accent); border-radius: 0 2px 2px 0;
  }
  .nav-item .icon { width: 18px; height: 18px; flex-shrink: 0; }
  .nav-item .badge {
    margin-left: auto; font-size: 10px; padding: 2px 7px; border-radius: 10px;
    background: var(--danger-soft); color: var(--danger); font-weight: 600; font-family: var(--font-mono);
  }
  .nav-item .badge.success { background: var(--success-soft); color: var(--success); }

  .sidebar-footer { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border-subtle); }
  .user-chip {
    display: flex; align-items: center; gap: 10px; padding: 10px;
    border-radius: var(--radius-md); background: var(--bg-card); border: 1px solid var(--border-subtle);
  }
  .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #f472b6, #d4a857); display: grid; place-items: center; color: white; font-weight: 600; font-size: 12px; }
  .user-info { flex: 1; min-width: 0; }
  .user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
  .user-role { font-size: 11px; color: var(--text-muted); }

  /* MAIN */
  .main { padding: 28px 36px 60px; max-width: 100%; overflow-y: auto; height: 100vh; }
  .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
  .page-title-block h1 { font-family: var(--font-display); font-size: 26px; font-weight: 600; letter-spacing: -0.025em; }
  .page-title-block .crumb {
    font-size: 12px; color: var(--text-muted); letter-spacing: 0.06em; text-transform: uppercase;
    margin-bottom: 4px; display: flex; align-items: center; gap: 8px;
  }
  .page-title-block .crumb .dot { width: 4px; height: 4px; border-radius: 50%; background: var(--gold); box-shadow: 0 0 8px var(--gold); }
  .topbar-actions { display: flex; align-items: center; gap: 12px; }

  /* BUTTONS */
  .btn {
    display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px;
    border-radius: var(--radius-md); border: 1px solid var(--border-strong);
    background: var(--bg-card); color: var(--text-primary); font-family: inherit;
    font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.15s ease;
  }
  .btn:hover { background: var(--bg-card-hover); border-color: var(--border-accent); }
  .btn-primary { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 2px 12px rgba(79, 140, 255, 0.3); }
  .btn-primary:hover { background: var(--accent-strong); }
  .btn-success { background: var(--success-soft); border-color: rgba(52, 211, 153, 0.3); color: var(--success); }
  .btn-warning { background: var(--warning-soft); border-color: rgba(245, 158, 11, 0.3); color: var(--warning); }
  .btn-danger { background: var(--danger-soft); border-color: rgba(239, 68, 68, 0.3); color: var(--danger); }
  .btn-ghost { background: transparent; border-color: var(--border-subtle); }
  .btn-icon { width: 36px; height: 36px; padding: 0; justify-content: center; }
  .btn:disabled { opacity: 0.4; cursor: not-allowed; }
  .btn-sm { padding: 5px 10px; font-size: 12px; }

  /* STATS */
  .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
  .stat-card {
    background: var(--bg-card); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg); padding: 20px; position: relative; overflow: hidden; transition: all 0.2s ease;
  }
  .stat-card:hover { border-color: var(--border-accent); transform: translateY(-2px); box-shadow: var(--shadow-md); }
  .stat-card::before {
    content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px;
    background: radial-gradient(circle, var(--stat-color, var(--accent-soft)), transparent 70%);
    opacity: 0.5; pointer-events: none;
  }
  .stat-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--stat-bg, var(--accent-soft)); color: var(--stat-color-solid, var(--accent)); display: grid; place-items: center; margin-bottom: 16px; }
  .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); font-weight: 500; }
  .stat-value { font-family: var(--font-display); font-size: 28px; font-weight: 600; margin-top: 6px; letter-spacing: -0.02em; }
  .stat-trend { margin-top: 10px; font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; }
  .stat-trend .up { color: var(--success); font-weight: 600; }
  .stat-trend .down { color: var(--danger); font-weight: 600; }

  /* FILTER BAR */
  .filter-bar {
    display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
    padding: 10px 14px; background: var(--bg-card);
    border: 1px solid var(--border-subtle); border-radius: var(--radius-md);
    flex-wrap: wrap;
  }
  .filter-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.1em; font-weight: 500; margin-right: 4px; white-space: nowrap; }
  .filter-chip {
    padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 500;
    background: var(--bg-elevated); border: 1px solid var(--border-subtle);
    color: var(--text-secondary); cursor: pointer; transition: all 0.15s ease;
  }
  .filter-chip:hover { border-color: var(--border-accent); color: var(--text-primary); }
  .filter-chip.active { background: var(--accent-soft); color: var(--accent-strong); border-color: rgba(79, 140, 255, 0.35); }

  /* SEARCH */
  .search-bar {
    position: relative; flex: 1; min-width: 240px; max-width: 400px;
  }
  .search-bar input {
    width: 100%; padding: 9px 12px 9px 38px;
    background: var(--bg-deep); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-md); color: var(--text-primary);
    font-family: inherit; font-size: 13px; outline: none; transition: border 0.15s ease;
  }
  .search-bar input:focus { border-color: var(--border-accent); }
  .search-bar input::placeholder { color: var(--text-faint); }
  .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-faint); }

  /* PANEL */
  .panel { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); overflow: hidden; }
  .panel-header {
    padding: 18px 22px; border-bottom: 1px solid var(--border-subtle);
    display: flex; align-items: center; justify-content: space-between;
  }
  .panel-title { font-family: var(--font-display); font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
  .panel-title .pulse { width: 8px; height: 8px; background: var(--gold); border-radius: 50%; box-shadow: 0 0 0 4px var(--gold-soft); animation: pulse 2s infinite; }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
  .panel-sub { font-size: 12px; color: var(--text-muted); margin-left: 8px; font-weight: 400; }

  /* TABS */
  .due-tabs {
    display: flex; gap: 0; border-bottom: 1px solid var(--border-subtle); margin-bottom: 0; overflow-x: auto;
  }
  .due-tab {
    padding: 12px 20px; color: var(--text-muted); cursor: pointer; font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 8px; border-bottom: 2px solid transparent;
    transition: all 0.15s ease; margin-bottom: -1px; white-space: nowrap;
  }
  .due-tab:hover { color: var(--text-primary); }
  .due-tab.active { color: var(--accent-strong); border-bottom-color: var(--accent); }
  .due-tab .tab-count {
    font-size: 10px; padding: 2px 7px; border-radius: 10px;
    background: var(--bg-elevated); color: var(--text-muted); font-weight: 600; font-family: var(--font-mono);
  }
  .due-tab.active .tab-count { background: var(--accent-soft); color: var(--accent-strong); }
  .due-tab.tab-danger .tab-count { background: var(--danger-soft); color: var(--danger); }
  .due-tab.tab-warning .tab-count { background: var(--warning-soft); color: var(--warning); }

  /* TABLE */
  .due-table { width: 100%; border-collapse: collapse; }
  .due-table thead th {
    text-align: left; padding: 12px 18px; font-size: 10px; text-transform: uppercase;
    letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500;
    border-bottom: 1px solid var(--border-subtle); background: var(--bg-deep); white-space: nowrap;
  }
  .due-table tbody tr { border-bottom: 1px solid var(--border-subtle); cursor: pointer; transition: background 0.12s ease; }
  .due-table tbody tr:hover { background: var(--bg-card-hover); }
  .due-table tbody tr:last-child { border-bottom: none; }
  .due-table td { padding: 13px 18px; vertical-align: middle; font-size: 13px; }

  .lead-name-cell { display: flex; align-items: center; gap: 12px; }
  .lead-avatar { width: 36px; height: 36px; border-radius: 9px; display: grid; place-items: center; font-weight: 600; font-size: 12px; flex-shrink: 0; font-family: var(--font-display); }
  .lead-name { font-weight: 600; color: var(--text-primary); font-size: 14px; }
  .lead-course { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
  .phone-mono { font-family: var(--font-mono); color: var(--text-secondary); font-size: 12px; }
  .date-text { color: var(--text-secondary); font-size: 13px; }

  /* PILLS */
  .pill {
    display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px;
    border-radius: 999px; font-size: 11px; font-weight: 500;
    background: var(--bg-elevated); border: 1px solid var(--border-subtle); color: var(--text-secondary);
  }
  .pill.status { border: 1px solid; }
  .pill.status[data-status="Paid"] { background: var(--success-soft); color: var(--success); border-color: rgba(52, 211, 153, 0.25); }
  .pill.status[data-status="Pending"] { background: var(--warning-soft); color: var(--warning); border-color: rgba(245, 158, 11, 0.25); }
  .pill.status[data-status="Overdue"] { background: var(--danger-soft); color: #ff6b6b; border-color: rgba(239, 68, 68, 0.4); }
  .pill.status[data-status="Escalated"] { background: var(--purple-soft); color: var(--purple); border-color: rgba(167, 139, 250, 0.3); }
  .pill.status[data-status="Confirmed"] { background: var(--success-soft); color: var(--success); border-color: rgba(52, 211, 153, 0.25); }
  .pill.status[data-status="Full Payment"] { background: var(--accent-soft); color: var(--accent-strong); border-color: rgba(79, 140, 255, 0.25); }
  .pill.status[data-status="Split 1"] { background: var(--gold-soft); color: var(--gold); border-color: rgba(212, 168, 87, 0.3); }
  .pill.status[data-status="Split 2"] { background: var(--teal-soft); color: var(--teal); border-color: rgba(45, 212, 191, 0.3); }
  .pill.status[data-status="Split 3"] { background: var(--purple-soft); color: var(--purple); border-color: rgba(167, 139, 250, 0.3); }

  .tag-pill {
    display: inline-block; padding: 3px 9px; border-radius: 999px; font-size: 10px;
    font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; border: 1px solid;
  }
  .tag-pill.warm { background: var(--warning-soft); color: var(--warning); border-color: rgba(245, 158, 11, 0.25); }
  .tag-pill.confirmed { background: var(--success-soft); color: var(--success); border-color: rgba(52, 211, 153, 0.25); }
  .tag-pill.dues-pending { background: var(--danger-soft); color: var(--danger); border-color: rgba(239, 68, 68, 0.25); }
  .tag-pill.overdue { background: var(--danger-soft); color: #ff6b6b; border-color: rgba(239, 68, 68, 0.4); }
  .tag-pill.dues-collected { background: var(--success-soft); color: var(--success); border-color: rgba(52, 211, 153, 0.25); }
  .tag-pill.escalated { background: var(--purple-soft); color: var(--purple); border-color: rgba(167, 139, 250, 0.3); }

  /* avatar colors */
  .av-blue { background: rgba(79, 140, 255, 0.15); color: var(--accent-strong); }
  .av-purple { background: rgba(167, 139, 250, 0.15); color: var(--purple); }
  .av-green { background: rgba(52, 211, 153, 0.15); color: var(--success); }
  .av-gold { background: rgba(212, 168, 87, 0.15); color: var(--gold); }
  .av-pink { background: rgba(244, 114, 182, 0.15); color: var(--pink); }
  .av-teal { background: rgba(45, 212, 191, 0.15); color: var(--teal); }
  .av-orange { background: rgba(245, 158, 11, 0.15); color: var(--warning); }

  /* DASHBOARD BOX GRID */
  .dash-boxes { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
  .dash-box {
    background: var(--bg-card); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg); overflow: hidden;
  }
  .dash-box-header {
    padding: 16px 20px 12px; display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid var(--border-subtle);
  }
  .dash-box-title { font-family: var(--font-display); font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
  .dash-box-count { font-size: 11px; color: var(--text-muted); }
  .dash-box-body { padding: 0; max-height: 280px; overflow-y: auto; }
  .dash-due-row {
    display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; align-items: center;
    padding: 12px 20px; border-bottom: 1px solid var(--border-subtle); gap: 12px;
    cursor: pointer; transition: background 0.12s ease;
  }
  .dash-due-row:hover { background: var(--bg-card-hover); }
  .dash-due-row:last-child { border-bottom: none; }
  .dash-due-row-header {
    display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
    padding: 8px 20px; gap: 12px; background: var(--bg-deep);
    border-bottom: 1px solid var(--border-subtle);
  }
  .dash-col-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; }
  .dash-name-cell { display: flex; align-items: center; gap: 8px; }
  .dash-small-avatar { width: 28px; height: 28px; border-radius: 7px; display: grid; place-items: center; font-weight: 600; font-size: 10px; flex-shrink: 0; }
  .dash-name { font-weight: 600; font-size: 12px; }
  .dash-phone { font-size: 10px; color: var(--text-muted); font-family: var(--font-mono); }
  .dash-empty { padding: 30px; text-align: center; color: var(--text-faint); font-size: 13px; }

  /* BAR CHART */
  .chart-section {
    background: var(--bg-card); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 24px;
  }
  .chart-header {
    padding: 18px 22px; border-bottom: 1px solid var(--border-subtle);
    display: flex; align-items: center; justify-content: space-between;
  }
  .chart-title { font-family: var(--font-display); font-size: 15px; font-weight: 600; }
  .chart-body { padding: 24px 22px 16px; }
  .chart-canvas-wrap { position: relative; width: 100%; height: 200px; }
  .chart-bars { display: flex; align-items: flex-end; gap: 8px; height: 160px; padding-bottom: 0; }
  .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; position: relative; }
  .chart-bar-inner { width: 100%; border-radius: 5px 5px 0 0; transition: all 0.3s ease; cursor: pointer; position: relative; min-height: 4px; }
  .chart-bar-inner:hover { filter: brightness(1.3); }
  .chart-bar-label { font-size: 10px; color: var(--text-muted); font-weight: 500; padding-top: 8px; }
  .chart-tooltip {
    position: absolute; bottom: calc(100% + 6px); left: 50%; transform: translateX(-50%);
    background: var(--bg-elevated); border: 1px solid var(--border-accent); border-radius: var(--radius-sm);
    padding: 6px 10px; font-size: 11px; white-space: nowrap; color: var(--text-primary);
    font-weight: 600; pointer-events: none; opacity: 0; transition: opacity 0.15s ease;
    box-shadow: var(--shadow-md); z-index: 10;
  }
  .chart-bar-wrap:hover .chart-tooltip { opacity: 1; }

  /* PROFILE */
  .profile-header {
    background: var(--bg-card); border: 1px solid var(--border-subtle);
    border-radius: var(--radius-lg); padding: 24px; margin-bottom: 20px;
    position: relative; overflow: hidden;
  }
  .profile-header::after {
    content: ''; position: absolute; top: -50%; right: -10%;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(79, 140, 255, 0.06), transparent 60%);
    pointer-events: none;
  }
  .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 13px; cursor: pointer; margin-bottom: 16px; transition: color 0.15s ease; }
  .back-link:hover { color: var(--accent-strong); }
  .profile-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; position: relative; z-index: 1; }
  .profile-identity { display: flex; gap: 16px; align-items: flex-start; }
  .profile-avatar { width: 64px; height: 64px; border-radius: 14px; display: grid; place-items: center; font-family: var(--font-display); font-weight: 600; font-size: 22px; }
  .profile-name { font-family: var(--font-display); font-size: 24px; font-weight: 600; letter-spacing: -0.02em; }
  .profile-meta { color: var(--text-muted); font-size: 13px; margin-top: 2px; margin-bottom: 12px; }
  .profile-pills { display: flex; flex-wrap: wrap; gap: 8px; }
  .lead-id-box { text-align: right; }
  .lead-id-label { font-size: 10px; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; }
  .lead-id-value { font-family: var(--font-display); font-size: 22px; font-weight: 600; color: var(--accent-strong); letter-spacing: -0.01em; margin-top: 2px; }
  .profile-actions { display: flex; gap: 8px; }

  /* FIELD GRID */
  .field-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
  .field { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px 16px; transition: border 0.15s ease; }
  .field:focus-within { border-color: var(--border-accent); }
  .field-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; margin-bottom: 4px; display: block; }
  .field input, .field select, .field textarea { width: 100%; background: transparent; border: none; color: var(--text-primary); font-family: inherit; font-size: 14px; outline: none; padding: 0; }
  .field input::placeholder { color: var(--text-faint); }
  .field input.empty-dash { color: var(--text-faint); }
  .field.readonly { opacity: 0.85; }
  .field.readonly input { cursor: default; color: var(--text-secondary); }

  /* PROFILE TABS */
  .tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--border-subtle); margin-bottom: 22px; }
  .tab {
    padding: 12px 18px; color: var(--text-muted); cursor: pointer; font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 8px; border-bottom: 2px solid transparent;
    transition: all 0.15s ease; margin-bottom: -1px;
  }
  .tab:hover { color: var(--text-primary); }
  .tab.active { color: var(--accent-strong); border-bottom-color: var(--accent); }

  /* TIMELINE */
  .timeline-section { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); overflow: hidden; }
  .timeline-header { padding: 18px 22px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-subtle); }
  .timeline-title { font-family: var(--font-display); font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
  .timeline-table { width: 100%; border-collapse: collapse; }
  .timeline-table th { text-align: left; padding: 10px 18px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; background: var(--bg-deep); white-space: nowrap; }
  .timeline-table td { padding: 13px 18px; border-top: 1px solid var(--border-subtle); font-size: 13px; vertical-align: middle; }
  .timeline-section-row { background: linear-gradient(90deg, var(--bg-elevated), transparent); }
  .timeline-section-row td { padding: 10px 18px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted); font-weight: 600; }
  .label-prev { color: var(--text-muted); }
  .label-current { color: var(--gold); }
  .call-time-cell .time { font-weight: 600; color: var(--text-primary); font-size: 13px; }
  .call-time-cell .date { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
  .direction-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }
  .direction-pill.incoming { background: var(--success-soft); color: var(--success); border: 1px solid rgba(52, 211, 153, 0.25); }
  .direction-pill.outgoing { background: var(--accent-soft); color: var(--accent-strong); border: 1px solid rgba(79, 140, 255, 0.25); }
  .play-btn { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: var(--bg-elevated); border: 1px solid var(--border-subtle); border-radius: 999px; font-size: 11px; color: var(--text-secondary); cursor: pointer; transition: all 0.15s ease; font-family: var(--font-mono); }
  .play-btn:hover { color: var(--accent-strong); border-color: var(--border-accent); }
  .current-call-row { background: linear-gradient(90deg, rgba(212, 168, 87, 0.04), transparent); position: relative; }
  .current-call-row td:first-child { border-left: 3px solid var(--gold); }
  .select-tag, .note-input { width: 100%; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: var(--text-primary); border-radius: var(--radius-sm); padding: 7px 10px; font-family: inherit; font-size: 12px; outline: none; transition: border 0.15s ease; }
  .select-tag:focus, .note-input:focus { border-color: var(--border-accent); }
  .assign-btn { width: 100%; padding: 7px; background: var(--accent); color: white; border: none; border-radius: var(--radius-sm); font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer; margin-top: 6px; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.15s ease; }
  .assign-btn:hover { background: var(--accent-strong); }

  /* HISTORY DROPDOWN */
  .history-dropdown-wrap { position: relative; display: inline-block; }
  .history-dropdown-btn { padding: 4px 10px; background: var(--bg-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); font-size: 11px; color: var(--text-secondary); cursor: pointer; display: flex; align-items: center; gap: 5px; }
  .history-dropdown-btn:hover { border-color: var(--border-accent); color: var(--text-primary); }
  .history-dropdown-menu {
    position: absolute; right: 0; top: calc(100% + 4px); background: var(--bg-elevated);
    border: 1px solid var(--border-accent); border-radius: var(--radius-md); min-width: 140px;
    z-index: 100; box-shadow: var(--shadow-lg); display: none;
  }
  .history-dropdown-menu.open { display: block; }
  .history-dropdown-item { padding: 9px 14px; font-size: 12px; color: var(--text-secondary); cursor: pointer; transition: all 0.12s ease; }
  .history-dropdown-item:hover { background: var(--bg-card-hover); color: var(--text-primary); }

  /* REGISTRATION NEW STYLE */
  .reg-view-card {
    background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); margin-bottom: 20px; overflow: hidden;
  }
  .reg-view-header { padding: 16px 20px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; }
  .reg-view-title { font-family: var(--font-display); font-size: 15px; font-weight: 600; }
  .reg-view-sub { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .reg-fields-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding: 16px 20px; }
  .reg-fields-row.two { grid-template-columns: repeat(2, 1fr); }
  .reg-display-field { background: var(--bg-deep); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 12px 14px; }
  .reg-display-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; margin-bottom: 4px; }
  .reg-display-value { font-size: 14px; font-weight: 600; color: var(--text-primary); }
  .reg-display-value.fee { color: var(--gold); font-family: var(--font-display); font-size: 16px; }

  /* SPLIT PAYMENT CARDS */
  .split-cards { display: flex; flex-direction: column; gap: 14px; padding: 16px 20px; }
  .split-card { border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; }
  .split-card.completed { border-color: rgba(52, 211, 153, 0.35); background: rgba(52, 211, 153, 0.04); }
  .split-card-header { padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); }
  .split-card.completed .split-card-header { border-bottom-color: rgba(52, 211, 153, 0.2); }
  .split-card-title { font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
  .split-card-body { padding: 14px 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .split-input-group label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-faint); font-weight: 500; margin-bottom: 5px; }
  .split-input-group input {
    width: 100%; background: var(--bg-deep); border: 1px solid var(--border-subtle);
    color: var(--text-primary); border-radius: var(--radius-sm); padding: 9px 12px;
    font-family: inherit; font-size: 13px; outline: none; transition: border 0.15s ease;
  }
  .split-input-group input:focus { border-color: var(--border-accent); }
  .split-input-group input:disabled { opacity: 0.6; cursor: not-allowed; color: var(--success); }
  .split-save-btn { margin: 0 16px 14px; }

  /* PAYMENT HISTORY */
  .pay-tabs { display: flex; gap: 8px; margin-bottom: 18px; }
  .pay-tab { padding: 8px 16px; background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: 999px; color: var(--text-secondary); cursor: pointer; font-size: 12px; font-weight: 500; }
  .pay-tab.active { background: var(--accent-soft); color: var(--accent-strong); border-color: rgba(79, 140, 255, 0.3); }
  .reg-card { background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 22px; }
  .reg-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px dashed var(--border-subtle); }
  .reg-card-title { font-family: var(--font-display); font-size: 15px; font-weight: 600; }
  .reg-card-sub { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
  .reg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px; }
  .input-group { margin-bottom: 14px; }
  .input-group label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; margin-bottom: 6px; }
  .input-group input, .input-group select, .input-group textarea { width: 100%; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: var(--text-primary); border-radius: var(--radius-md); padding: 10px 12px; font-family: inherit; font-size: 13px; outline: none; transition: border 0.15s ease; }
  .input-group input:focus, .input-group select:focus, .input-group textarea:focus { border-color: var(--border-accent); }
  .razorpay-badge { margin-left: auto; padding: 5px 10px; background: var(--accent-soft); color: var(--accent-strong); border: 1px solid rgba(79, 140, 255, 0.3); border-radius: var(--radius-sm); font-size: 10px; font-weight: 700; letter-spacing: 0.12em; }
  .send-history { margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-subtle); }
  .send-history-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; margin-bottom: 12px; }
  .link-history-item { display: flex; align-items: center; gap: 14px; padding: 12px 14px; background: var(--bg-elevated); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); margin-bottom: 8px; }
  .channel-icon { width: 32px; height: 32px; border-radius: 8px; display: grid; place-items: center; font-size: 14px; flex-shrink: 0; }
  .send-history-empty { text-align: center; padding: 30px; color: var(--text-faint); font-size: 13px; }
  .send-history-icon { font-size: 28px; margin-bottom: 8px; }
  .invoice-card { border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 16px; margin-bottom: 12px; display: flex; align-items: center; gap: 14px; }
  .invoice-card.completed { border-color: rgba(52, 211, 153, 0.3); }
  .invoice-card.pending { border-color: var(--border-subtle); opacity: 0.5; }

  /* FOLLOWUPS TABLE */
  .followup-table-wrap { overflow-x: auto; }

  /* COMPLETED PAYMENTS */
  .completed-table-wrap { overflow-x: auto; }

  /* MODAL */
  .modal-overlay {
    position: fixed; inset: 0; background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(4px); z-index: 500;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity 0.2s ease;
  }
  .modal-overlay.open { opacity: 1; pointer-events: all; }
  .modal-box {
    background: var(--bg-elevated); border: 1px solid var(--border-accent);
    border-radius: var(--radius-xl); padding: 28px; min-width: 420px; max-width: 520px; width: 90%;
    box-shadow: var(--shadow-lg); transform: translateY(10px); transition: transform 0.2s ease;
  }
  .modal-overlay.open .modal-box { transform: translateY(0); }
  .modal-title { font-family: var(--font-display); font-size: 18px; font-weight: 600; margin-bottom: 4px; }
  .modal-sub { font-size: 12px; color: var(--text-muted); margin-bottom: 22px; }
  .modal-field { margin-bottom: 16px; }
  .modal-field label { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-faint); font-weight: 500; margin-bottom: 6px; }
  .modal-field input, .modal-field select, .modal-field textarea { width: 100%; background: var(--bg-deep); border: 1px solid var(--border-subtle); color: var(--text-primary); border-radius: var(--radius-md); padding: 10px 12px; font-family: inherit; font-size: 13px; outline: none; transition: border 0.15s ease; }
  .modal-field input:focus, .modal-field select:focus, .modal-field textarea:focus { border-color: var(--border-accent); }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px; }
  .modal-close-btn { position: absolute; top: 16px; right: 16px; background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 18px; padding: 4px 8px; }

  /* ALERT BANNER */
  .alert-banner {
    display: flex; align-items: center; gap: 16px; padding: 16px 20px;
    background: rgba(245, 158, 11, 0.07); border: 1px solid rgba(245, 158, 11, 0.25);
    border-radius: var(--radius-lg); margin-bottom: 24px;
  }
  .alert-icon { width: 40px; height: 40px; background: var(--warning-soft); border-radius: 10px; display: grid; place-items: center; flex-shrink: 0; color: var(--warning); }
  .alert-title { font-weight: 600; color: var(--warning); margin-bottom: 2px; }
  .alert-sub { font-size: 12px; color: var(--text-muted); }
  .alert-text { flex: 1; }

  /* TOAST */
  .toast {
    position: fixed; bottom: 28px; right: 28px;
    background: var(--bg-elevated); border: 1px solid var(--border-accent);
    border-radius: var(--radius-md); padding: 12px 18px;
    color: var(--text-primary); display: flex; align-items: center; gap: 12px;
    z-index: 1000; animation: slideIn 0.3s ease; font-size: 13px;
    box-shadow: var(--shadow-md);
  }
  .toast.success { border-color: rgba(52, 211, 153, 0.4); }
  .toast.error { border-color: rgba(239, 68, 68, 0.4); }
  .toast .toast-icon { font-size: 18px; }
  @keyframes slideIn { from { transform: translateX(20px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

  /* EXTEND DATE */
  .extend-date-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 10px; background: var(--gold-soft);
    border: 1px solid rgba(212, 168, 87, 0.3); border-radius: 999px;
    color: var(--gold); font-family: inherit; font-size: 11px; font-weight: 600;
    cursor: pointer; transition: all 0.15s ease;
  }
  .extend-date-btn:hover { background: rgba(212, 168, 87, 0.22); border-color: rgba(212, 168, 87, 0.5); }
  .extend-panel {
    margin: 0 16px 0; border-radius: var(--radius-md); overflow: hidden;
    border: 1px solid rgba(212, 168, 87, 0.3);
  }
  .extend-panel-inner {
    padding: 16px;
    background: linear-gradient(135deg, rgba(212, 168, 87, 0.06), rgba(212, 168, 87, 0.02));
  }

  .hidden { display: none !important; }
  .dash-filter-wrap { display: flex; align-items: center; gap: 8px; }
  .dash-filter-dropdown { position: relative; }
  .dash-filter-btn {
    display: flex; align-items: center; gap: 8px; padding: 8px 14px;
    background: var(--bg-card); border: 1px solid var(--border-strong);
    border-radius: var(--radius-md); color: var(--text-primary);
    font-family: inherit; font-size: 13px; font-weight: 500; cursor: pointer;
    transition: all 0.15s ease; min-width: 140px; justify-content: space-between;
  }
  .dash-filter-btn:hover { background: var(--bg-card-hover); border-color: var(--border-accent); }
  .dash-filter-menu {
    position: absolute; top: calc(100% + 6px); left: 0; min-width: 180px;
    background: var(--bg-elevated); border: 1px solid var(--border-accent);
    border-radius: var(--radius-md); box-shadow: var(--shadow-lg);
    z-index: 200; display: none; overflow: hidden; padding: 6px 0;
  }
  .dash-filter-menu.open { display: block; }
  .dash-filter-group-label {
    font-size: 9px; text-transform: uppercase; letter-spacing: 0.14em;
    color: var(--text-faint); padding: 8px 14px 4px; font-weight: 600;
  }
  .dash-filter-item {
    display: flex; align-items: center; gap: 8px; padding: 8px 14px;
    font-size: 13px; color: var(--text-secondary); cursor: pointer;
    transition: all 0.12s ease;
  }
  .dash-filter-item:hover { background: var(--bg-card-hover); color: var(--text-primary); }
  .dash-filter-item.selected { color: var(--accent-strong); background: var(--accent-soft); }

  .hidden { display: none !important; }
  .view { animation: fade 0.25s ease; }
  @keyframes fade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

  @media (max-width: 1100px) {
    #app { grid-template-columns: 200px 1fr; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .field-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-boxes { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<div id="app">
    @include('partials.sidebar', ['role' => 'dues'])

  <main class="main">
    <div id="content"></div>
  </main>
</div>

<!-- ASSIGN MODAL -->
<div class="modal-overlay" id="assignModal">
  <div class="modal-box" style="position:relative;">
    <button class="modal-close-btn" onclick="closeAssignModal()">✕</button>
    <div class="modal-title">Assign Follow-up</div>
    <div class="modal-sub">Assign this call to a staff member with details</div>

    <div class="modal-field">
      <label>Assign To</label>
      <select id="modal-assign-to">
        <option value="">Select staff...</option>
        <option>Karthik S</option>
        <option>Priya M</option>
        <option>Suresh R</option>
        <option>Nithya V</option>
        <option>Self</option>
      </select>
    </div>
    <div class="modal-field">
      <label>Fix Date</label>
      <input type="date" id="modal-fix-date" value="2026-04-30" />
    </div>
    <div class="modal-field">
      <label>Notes</label>
      <textarea rows="3" id="modal-notes" placeholder="Add call notes or instructions..."></textarea>
    </div>
    <div class="modal-field">
      <label>Tag Updated</label>
      <select id="modal-tag">
        <option value="">Select tag...</option>
        <option value="dues-pending">Dues Pending</option>
        <option value="overdue">Overdue</option>
        <option value="escalated">Escalated</option>
        <option value="dues-collected">Dues Collected</option>
        <option value="follow-up">Follow-up Scheduled</option>
      </select>
    </div>
    <div class="modal-actions">
      <button class="btn btn-ghost" onclick="closeAssignModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitAssign()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        Assign
      </button>
    </div>
  </div>
</div>

<script>
/* ============================================================
   STATE & DATA
============================================================ */
const today = new Date('2026-04-29');
const fmt = (d) => d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short' });
const inr = (n) => '₹' + Number(n).toLocaleString('en-IN');

const staffList = ['Karthik S', 'Priya M', 'Suresh R', 'Nithya V'];

const leads = [
  {
    id: 'L-001', initials: 'AM', name: 'Arjun Mehta', course: 'Fullstack',
    phone: '98xx-xxx-211', date: '24 Apr', source: 'Facebook', avatarClass: 'av-blue',
    amount: 35000, paid: 15000, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Pending',
    batchCode: 'FS26', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 15000, date: '2026-04-01', paid: true, paidOn: '2026-04-01' },
      { label: 'Split 2', amount: 10000, date: '2026-04-29', paid: false },
      { label: 'Split 3', amount: 10000, date: '2026-05-15', paid: false },
    ],
    followUpDate: '2026-04-30',
    escalated: false,
  },
  {
    id: 'L-002', initials: 'SP', name: 'Sneha Pillai', course: 'UI/UX',
    phone: '91xx-xxx-302', date: '23 Apr', source: 'Instagram', avatarClass: 'av-purple',
    amount: 28000, paid: 0, dueDate: '2026-05-02',
    paymentMode: 'full', status: 'Pending',
    batchCode: 'UX12', batchType: 'Weekend',
    splits: [
      { label: 'Full Payment', amount: 28000, date: '2026-05-02', paid: false },
    ],
    followUpDate: '2026-05-02',
    escalated: false,
  },
  {
    id: 'L-003', initials: 'RD', name: 'Rahul Das', course: 'Data Analytics',
    phone: '87xx-xxx-419', date: '22 Apr', source: 'Google', avatarClass: 'av-green',
    amount: 42000, paid: 20000, dueDate: '2026-04-25',
    paymentMode: 'split', status: 'Overdue',
    batchCode: 'DA09', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 20000, date: '2026-04-01', paid: true },
      { label: 'Split 2', amount: 22000, date: '2026-04-25', paid: false },
    ],
    followUpDate: '2026-04-28',
    escalated: false,
  },
  {
    id: 'L-004', initials: 'KS', name: 'Kavya Suresh', course: 'Digital Marketing',
    phone: '76xx-xxx-155', date: '21 Apr', source: 'Referral', avatarClass: 'av-gold',
    amount: 22000, paid: 22000, dueDate: null,
    paymentMode: 'full', status: 'Paid',
    batchCode: 'DM26', batchType: 'Weekday',
    splits: [
      { label: 'Full Payment', amount: 22000, date: '2026-04-10', paid: true, paidOn: '2026-04-09' },
    ],
    followUpDate: null,
    escalated: false,
  },
  {
    id: 'L-005', initials: 'IK', name: 'Imran Khan', course: 'Fullstack',
    phone: '99xx-xxx-087', date: '20 Apr', source: 'Walk-in', avatarClass: 'av-blue',
    amount: 35000, paid: 0, dueDate: '2026-05-05',
    paymentMode: 'full', status: 'Pending',
    batchCode: 'FS26', batchType: 'Weekend',
    splits: [
      { label: 'Full Payment', amount: 35000, date: '2026-05-05', paid: false },
    ],
    followUpDate: '2026-05-01',
    escalated: false,
  },
  {
    id: 'L-006', initials: 'DS', name: 'Divya Sharma', course: 'UI/UX',
    phone: '88xx-xxx-963', date: '19 Apr', source: 'YouTube', avatarClass: 'av-pink',
    amount: 28000, paid: 28000, dueDate: null,
    paymentMode: 'split', status: 'Paid',
    batchCode: 'UX12', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 14000, date: '2026-04-05', paid: true, paidOn: '2026-04-05' },
      { label: 'Split 2', amount: 14000, date: '2026-04-19', paid: true, paidOn: '2026-04-18' },
    ],
    followUpDate: null,
    escalated: false,
  },
  {
    id: 'L-007', initials: 'MR', name: 'Mithun Roy', course: 'Digital Marketing',
    phone: '77xx-xxx-541', date: '18 Apr', source: 'Facebook', avatarClass: 'av-orange',
    amount: 22000, paid: 8000, dueDate: '2026-04-30',
    paymentMode: 'split', status: 'Overdue',
    batchCode: 'DM26', batchType: 'Weekend',
    splits: [
      { label: 'Split 1', amount: 8000, date: '2026-04-05', paid: true, paidOn: '2026-04-06' },
      { label: 'Split 2', amount: 7000, date: '2026-04-20', paid: false },
      { label: 'Split 3', amount: 7000, date: '2026-05-10', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: true,
  },
  {
    id: 'L-008', initials: 'AN', name: 'Anitha Nair', course: 'MongoDB',
    phone: '95xx-xxx-728', date: '17 Apr', source: 'Google', avatarClass: 'av-teal',
    amount: 18000, paid: 6000, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Pending',
    batchCode: 'MG03', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 6000, date: '2026-04-10', paid: true, paidOn: '2026-04-11' },
      { label: 'Split 2', amount: 12000, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: false,
  },
  {
    id: 'L-009', initials: 'PK', name: 'Priya Kumar', course: 'UI/UX',
    phone: '93xx-xxx-441', date: '16 Apr', source: 'Instagram', avatarClass: 'av-purple',
    amount: 28000, paid: 14000, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Pending',
    batchCode: 'UX13', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 14000, date: '2026-04-01', paid: true, paidOn: '2026-04-02' },
      { label: 'Split 2', amount: 14000, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: false,
  },
  {
    id: 'L-010', initials: 'VS', name: 'Vikram Sinha', course: 'Data Analytics',
    phone: '82xx-xxx-670', date: '15 Apr', source: 'Google', avatarClass: 'av-green',
    amount: 42000, paid: 21000, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Overdue',
    batchCode: 'DA10', batchType: 'Weekend',
    splits: [
      { label: 'Split 1', amount: 21000, date: '2026-04-01', paid: true, paidOn: '2026-04-01' },
      { label: 'Split 2', amount: 21000, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: false,
  },
  {
    id: 'L-011', initials: 'NR', name: 'Nandini Rao', course: 'Fullstack',
    phone: '91xx-xxx-882', date: '14 Apr', source: 'Referral', avatarClass: 'av-pink',
    amount: 35000, paid: 17500, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Pending',
    batchCode: 'FS27', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 17500, date: '2026-04-01', paid: true, paidOn: '2026-04-03' },
      { label: 'Split 2', amount: 17500, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: false,
  },
  {
    id: 'L-012', initials: 'TM', name: 'Tarun Menon', course: 'Digital Marketing',
    phone: '78xx-xxx-319', date: '13 Apr', source: 'Facebook', avatarClass: 'av-orange',
    amount: 22000, paid: 0, dueDate: '2026-04-29',
    paymentMode: 'full', status: 'Overdue',
    batchCode: 'DM27', batchType: 'Weekend',
    splits: [
      { label: 'Full Payment', amount: 22000, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: true,
  },
  {
    id: 'L-013', initials: 'SR', name: 'Suresh Rajan', course: 'MongoDB',
    phone: '96xx-xxx-553', date: '12 Apr', source: 'Walk-in', avatarClass: 'av-teal',
    amount: 18000, paid: 9000, dueDate: '2026-04-29',
    paymentMode: 'split', status: 'Pending',
    batchCode: 'MG04', batchType: 'Weekday',
    splits: [
      { label: 'Split 1', amount: 9000, date: '2026-04-01', paid: true, paidOn: '2026-04-01' },
      { label: 'Split 2', amount: 9000, date: '2026-04-29', paid: false },
    ],
    followUpDate: '2026-04-29',
    escalated: false,
  },
];

let state = {
  view: 'dashboard',
  selectedLead: null,
  profileTab: 'call',
  payTab: 'link',
  dashFilter: 'This Week',
  chartFilter: 'weekly',
  dueTab: 'new',
  searchQuery: '',
  completedTab: 'full',
  extendOpen: {},            // tracks which split extend panels are open
};

/* ============================================================
   HELPERS
============================================================ */
const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

function go(view, opts = {}) {
  state.view = view;
  if (opts.lead) state.selectedLead = opts.lead;
  if (opts.tab) state.profileTab = opts.tab;
  $$('.nav-item').forEach(el => {
    const nav = el.dataset.nav;
    el.classList.toggle('active', nav === view || (view === 'profile' && nav === 'dues'));
  });
  render();
}

function toast(msg, type = 'success') {
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  const icon = type === 'success' ? '✓' : type === 'error' ? '⚠' : 'ℹ';
  t.innerHTML = `<span class="toast-icon">${icon}</span>${msg}`;
  document.body.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; }, 2400);
  setTimeout(() => t.remove(), 2700);
}

function setDashFilter(val) {
  state.dashFilter = val;
  const menu = document.getElementById('dashFilterMenu');
  if (menu) menu.classList.remove('open');
  render();
}

function toggleDashFilter() {
  const menu = document.getElementById('dashFilterMenu');
  if (menu) menu.classList.toggle('open');
}

function daysFromToday(dateStr) {
  if (!dateStr) return null;
  const d = new Date(dateStr);
  return Math.floor((d - today) / (1000 * 60 * 60 * 24));
}

function openAssignModal() {
  document.getElementById('assignModal').classList.add('open');
}
function closeAssignModal() {
  document.getElementById('assignModal').classList.remove('open');
}
function submitAssign() {
  const to = document.getElementById('modal-assign-to').value;
  const tag = document.getElementById('modal-tag').value;
  closeAssignModal();
  toast(`Call assigned${to ? ' to ' + to : ''} · Tag: ${tag || 'none'}`);
}

function toggleDropdown(id) {
  const el = document.getElementById(id);
  if (el) el.classList.toggle('open');
}

function getPaymentStatus(lead) {
  if (lead.paymentMode === 'full') return 'Full Payment';
  const lastPaid = lead.splits.filter(s => s.paid).pop();
  if (!lastPaid) return 'Split 1';
  return lastPaid.label;
}

function getStatusClass(status) {
  if (status === 'Paid') return 'Paid';
  if (status === 'Overdue') return 'Overdue';
  if (status === 'Escalated') return 'Escalated';
  return 'Pending';
}

/* ============================================================
   CHART DATA
============================================================ */
const weeklyData = [
  { day: 'Mon', amount: 32000, color: '#4f8cff' },
  { day: 'Tue', amount: 18000, color: '#4f8cff' },
  { day: 'Wed', amount: 47000, color: '#d4a857' },
  { day: 'Thu', amount: 28000, color: '#4f8cff' },
  { day: 'Fri', amount: 55000, color: '#34d399' },
  { day: 'Sat', amount: 22000, color: '#4f8cff' },
  { day: 'Sun', amount: 14000, color: '#4f8cff' },
];
const monthlyData = [
  { day: 'W1', amount: 120000, color: '#4f8cff' },
  { day: 'W2', amount: 95000, color: '#d4a857' },
  { day: 'W3', amount: 138000, color: '#34d399' },
  { day: 'W4', amount: 162000, color: '#a78bfa' },
];

/* ============================================================
   VIEW: DASHBOARD
============================================================ */
function viewDashboard() {
  const overdueStudents = leads.filter(l => l.status === 'Overdue').length;
  const collectedThisMonth = leads.reduce((a, l) => a + l.paid, 0);
  const totalDueAmount = leads.reduce((a, l) => a + (l.amount - l.paid), 0);
  const totalStudents = leads.filter(l => l.status !== 'Paid').length;

  const todayDues = leads.filter(l => l.dueDate && daysFromToday(l.dueDate) === 0);
  const todayReminders = leads.filter(l => l.followUpDate && daysFromToday(l.followUpDate) === 0);

  const chartData = state.chartFilter === 'weekly' ? weeklyData : monthlyData;
  const maxAmount = Math.max(...chartData.map(d => d.amount));

  return `
    <div class="view">
      <div class="topbar">
        <div class="page-title-block">
          <div class="crumb"><span class="dot"></span> Wednesday, 29 April 2026</div>
          <h1>Good afternoon, Nisha</h1>
        </div>
        <div class="topbar-actions">
          <button class="btn btn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Activity
          </button>
          <button class="btn btn-primary" onclick="go('dues')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            View All Students
          </button>
        </div>
      </div>

      <!-- Dashboard Filter Dropdown -->
      <div style="display:flex; align-items:center; gap:12px; margin-bottom:22px;">
        <div class="dash-filter-wrap">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--text-muted);"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          <span style="font-size:12px;color:var(--text-muted);font-weight:500;">Filter by:</span>
          <div class="dash-filter-dropdown" id="dashFilterDrop">
            <button class="dash-filter-btn" onclick="toggleDashFilter()">
              <span id="dashFilterLabel">${state.dashFilter}</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="dash-filter-menu" id="dashFilterMenu">
              ${[
                { group: 'Day', items: ['Today', 'Yesterday'] },
                { group: 'Week', items: ['This Week', 'Last Week'] },
                { group: 'Month', items: ['This Month', 'Last Month', 'Last 3 Months'] },
                { group: 'All', items: ['All Time'] },
              ].map(g => `
                <div class="dash-filter-group-label">${g.group}</div>
                ${g.items.map(item => `
                  <div class="dash-filter-item ${state.dashFilter === item ? 'selected' : ''}" onclick="setDashFilter('${item}')">
                    ${state.dashFilter === item ? '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>' : '<span style="width:11px;display:inline-block;"></span>'}
                    ${item}
                  </div>
                `).join('')}
              `).join('')}
            </div>
          </div>
        </div>
        <span style="font-size:11px; color:var(--text-faint);">Showing data for: <strong style="color:var(--accent-strong);">${state.dashFilter}</strong></span>
      </div>

      <!-- Stats -->
      <div class="stats-grid">
        <div class="stat-card" style="--stat-bg: var(--accent-soft); --stat-color-solid: var(--accent); --stat-color: var(--accent-soft);">
          <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
          </div>
          <div class="stat-label">Total Due Students</div>
          <div class="stat-value">${totalStudents}</div>
          <div class="stat-trend"><span class="down">↑ 2 new</span> this week</div>
        </div>

        <div class="stat-card" style="--stat-bg: var(--danger-soft); --stat-color-solid: var(--danger); --stat-color: var(--danger-soft);">
          <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          </div>
          <div class="stat-label">Overdue Students</div>
          <div class="stat-value">${overdueStudents}</div>
          <div class="stat-trend"><span class="down">Needs urgent attention</span></div>
        </div>

        <div class="stat-card" style="--stat-bg: var(--warning-soft); --stat-color-solid: var(--warning); --stat-color: var(--warning-soft);">
          <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div class="stat-label">Total Due Amount</div>
          <div class="stat-value" style="font-size:22px;">${inr(totalDueAmount)}</div>
          <div class="stat-trend"><span class="down">↑ ₹12,000</span> vs last week</div>
        </div>

        <div class="stat-card" style="--stat-bg: var(--success-soft); --stat-color-solid: var(--success); --stat-color: var(--success-soft);">
          <div class="stat-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          </div>
          <div class="stat-label">Total Collected</div>
          <div class="stat-value" style="font-size:22px;">${inr(collectedThisMonth)}</div>
          <div class="stat-trend"><span class="up">↑ Across all students</span></div>
        </div>
      </div>

      <!-- Today's Dues + Reminders -->
      <div class="dash-boxes">
        <!-- Today's Dues -->
        <div class="dash-box">
          <div class="dash-box-header">
            <div class="dash-box-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Today's Dues
            </div>
            <span class="dash-box-count">${todayDues.length} students</span>
          </div>
          <div class="dash-due-row-header">
            <span class="dash-col-label">Name</span>
            <span class="dash-col-label">Phone</span>
            <span class="dash-col-label">Payment Date</span>
            <span class="dash-col-label">Pay Status</span>
          </div>
          <div class="dash-box-body">
            ${todayDues.length === 0 ? `<div class="dash-empty">No dues today 🎉</div>` : todayDues.map(l => `
              <div class="dash-due-row" onclick="go('profile', { lead: '${l.id}', tab: 'call' })">
                <div class="dash-name-cell">
                  <div class="dash-small-avatar ${l.avatarClass}">${l.initials}</div>
                  <div>
                    <div class="dash-name">${l.name}</div>
                    <div class="dash-phone">${l.phone}</div>
                  </div>
                </div>
                <span class="phone-mono" style="font-size:11px;">${l.phone}</span>
                <span style="font-size:12px; color:var(--text-secondary);">${new Date(l.dueDate).toLocaleDateString('en-IN', {day:'2-digit',month:'short'})}</span>
                <span class="pill status" data-status="${getPaymentStatus(l)}" style="font-size:10px;">${getPaymentStatus(l)}</span>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Today's Dues Reminder -->
        <div class="dash-box">
          <div class="dash-box-header">
            <div class="dash-box-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              Today's Dues Reminder
            </div>
            <span class="dash-box-count">${todayReminders.length} pending</span>
          </div>
          <div class="dash-due-row-header">
            <span class="dash-col-label">Name</span>
            <span class="dash-col-label">Phone</span>
            <span class="dash-col-label">Pay Date</span>
            <span class="dash-col-label">Pay Status</span>
          </div>
          <div class="dash-box-body">
            ${todayReminders.length === 0 ? `<div class="dash-empty">No reminders for today</div>` : todayReminders.map(l => `
              <div class="dash-due-row" onclick="go('profile', { lead: '${l.id}', tab: 'call' })">
                <div class="dash-name-cell">
                  <div class="dash-small-avatar ${l.avatarClass}">${l.initials}</div>
                  <div>
                    <div class="dash-name">${l.name}</div>
                    <div class="dash-phone">${l.phone}</div>
                  </div>
                </div>
                <span class="phone-mono" style="font-size:11px;">${l.phone}</span>
                <span style="font-size:12px; color:${l.status === 'Overdue' ? 'var(--danger)' : 'var(--text-secondary)'};">${l.dueDate ? new Date(l.dueDate).toLocaleDateString('en-IN', {day:'2-digit',month:'short'}) : '—'}</span>
                <span class="pill status" data-status="${l.status === 'Overdue' ? 'Overdue' : getPaymentStatus(l)}" style="font-size:10px;">${l.status === 'Overdue' ? 'Overdue' : getPaymentStatus(l)}</span>
              </div>
            `).join('')}
          </div>
        </div>
      </div>

      <!-- Collection Bar Chart -->
      <div class="chart-section">
        <div class="chart-header">
          <div>
            <div class="chart-title">Dues Collection</div>
            <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">Daily collection amounts — hover bars to see details</div>
          </div>
          <div style="display:flex; gap:8px;">
            <span class="filter-chip ${state.chartFilter === 'weekly' ? 'active' : ''}" onclick="state.chartFilter='weekly'; render();">Week View</span>
            <span class="filter-chip ${state.chartFilter === 'monthly' ? 'active' : ''}" onclick="state.chartFilter='monthly'; render();">Month View</span>
          </div>
        </div>
        <div class="chart-body">
          <div class="chart-bars">
            ${chartData.map((d, i) => {
              const heightPct = Math.max(8, (d.amount / maxAmount) * 100);
              return `
                <div class="chart-bar-wrap">
                  <div style="flex:1; display:flex; align-items:flex-end; width:100%;">
                    <div class="chart-bar-inner" style="height:${heightPct}%; background: linear-gradient(180deg, ${d.color}ee, ${d.color}88);">
                      <div class="chart-tooltip">${inr(d.amount)}</div>
                    </div>
                  </div>
                  <div class="chart-bar-label">${d.day}</div>
                </div>
              `;
            }).join('')}
          </div>
          <div style="display:flex; justify-content:space-between; margin-top:14px; padding-top:12px; border-top:1px solid var(--border-subtle);">
            <div style="font-size:12px; color:var(--text-muted);">Total this ${state.chartFilter === 'weekly' ? 'week' : 'month'}: <strong style="color:var(--success);">${inr(chartData.reduce((a,d)=>a+d.amount,0))}</strong></div>
            <div style="font-size:12px; color:var(--text-muted);">Avg per ${state.chartFilter === 'weekly' ? 'day' : 'week'}: <strong style="color:var(--accent-strong);">${inr(Math.round(chartData.reduce((a,d)=>a+d.amount,0)/chartData.length))}</strong></div>
          </div>
        </div>
      </div>
    </div>
  `;
}

/* ============================================================
   VIEW: STUDENT DUE LIST
============================================================ */
function viewDues() {
  const newDues = leads.filter(l => l.status === 'Pending' && !l.escalated);
  const dueToday = leads.filter(l => l.dueDate && daysFromToday(l.dueDate) === 0);
  const overdue = leads.filter(l => l.status === 'Overdue' || l.escalated);

  const tabMap = { new: newDues, today: dueToday, overdue: overdue };
  const activeTab = ['new','today','overdue'].includes(state.dueTab) ? state.dueTab : 'new';
  const tabLeads = tabMap[activeTab] || newDues;

  let filtered = tabLeads;
  if (state.searchQuery) {
    const q = state.searchQuery.toLowerCase();
    filtered = filtered.filter(l => l.name.toLowerCase().includes(q) || l.phone.includes(q) || l.course.toLowerCase().includes(q));
  }

  return `
    <div class="view">
      <div class="topbar">
        <div class="page-title-block">
          <div class="crumb"><span class="dot"></span> All Students</div>
          <h1>Student Due List</h1>
        </div>
        <div class="topbar-actions">
          <button class="btn btn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export
          </button>
        </div>
      </div>

      <!-- Search + Filters -->
      <div class="filter-bar" style="margin-bottom:16px;">
        <div class="search-bar">
          <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Search by name, phone, course..." value="${state.searchQuery}"
            oninput="state.searchQuery = this.value; render();" id="due-search" />
        </div>
        <span class="filter-label" style="margin-left:8px;">Period:</span>
        ${['all', 'today', 'this-week', 'this-month'].map(p => {
          const label = {all:'All',today:'Today','this-week':'This Week','this-month':'This Month'}[p];
          return `<span class="filter-chip ${state.filterPeriod === p ? 'active' : ''}" onclick="state.filterPeriod='${p}'; render();">${label}</span>`;
        }).join('')}
      </div>

      <!-- Tabs: 3 only — New Dues, Due Today, Overdue -->
      <div class="panel">
        <div class="due-tabs">
          <div class="due-tab ${activeTab === 'new' ? 'active' : ''}" onclick="state.dueTab='new'; render();">
            New Dues <span class="tab-count">${newDues.length}</span>
          </div>
          <div class="due-tab ${activeTab === 'today' ? 'active' : ''}" onclick="state.dueTab='today'; render();" style="${activeTab === 'today' ? '' : 'color:var(--warning);'}">
            Due Today <span class="tab-count" style="background:var(--warning-soft);color:var(--warning);">${dueToday.length}</span>
          </div>
          <div class="due-tab ${activeTab === 'overdue' ? 'active' : ''}" onclick="state.dueTab='overdue'; render();" style="${activeTab === 'overdue' ? '' : 'color:var(--danger);'}">
            Overdue <span class="tab-count" style="background:var(--danger-soft);color:var(--danger);">${overdue.length}</span>
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table class="due-table">
            <thead>
              <tr>
                <th>Student Name</th>
                <th>Phone No.</th>
                <th>Course</th>
                <th>Payment Date</th>
                <th>Payment Status</th>
                <th>Status</th>
                <th>Due Amount</th>
              </tr>
            </thead>
            <tbody>
              ${filtered.length === 0 ? `<tr><td colspan="7" style="text-align:center;padding:30px;color:var(--text-faint);">No students in this category</td></tr>` :
              filtered.map(l => {
                const payStatus = getPaymentStatus(l);
                const days = l.dueDate ? daysFromToday(l.dueDate) : null;
                const dueDateDisplay = l.dueDate ? new Date(l.dueDate).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}) : '—';
                const status = l.status === 'Overdue' || l.escalated ? 'Overdue' : l.status;
                return `
                  <tr onclick="go('profile', { lead: '${l.id}', tab: 'call' })">
                    <td>
                      <div class="lead-name-cell">
                        <div class="lead-avatar ${l.avatarClass}">${l.initials}</div>
                        <div>
                          <div class="lead-name">${l.name}</div>
                          <div class="lead-course">${l.batchCode} · ${l.batchType}</div>
                        </div>
                      </div>
                    </td>
                    <td><span class="phone-mono">${l.phone}</span></td>
                    <td><span style="font-weight:500;">${l.course}</span></td>
                    <td>
                      <span class="date-text" style="${days !== null && days < 0 ? 'color:var(--danger);' : days === 0 ? 'color:var(--warning);' : ''}">${dueDateDisplay}</span>
                    </td>
                    <td><span class="pill status" data-status="${payStatus}">${payStatus}</span></td>
                    <td><span class="pill status" data-status="${status}">${status}</span></td>
                    <td><span style="font-family:var(--font-display); font-weight:600; color:${l.amount - l.paid > 0 ? 'var(--warning)' : 'var(--success)'};">${inr(l.amount - l.paid)}</span></td>
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

/* ============================================================
   VIEW: STUDENT PROFILE
============================================================ */
function viewProfile() {
  const lead = leads.find(l => l.id === state.selectedLead);
  if (!lead) return viewDues();

  const status = lead.escalated ? 'Escalated' : lead.status;

  return `
    <div class="view">
      <div class="profile-header">
        <div class="back-link" onclick="go('dues')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Back to Student Due List
        </div>
        <div class="profile-top">
          <div class="profile-identity">
            <div class="profile-avatar ${lead.avatarClass}">${lead.initials}</div>
            <div>
              <div class="profile-name">${lead.name}</div>
              <div class="profile-meta">${lead.course} · ${lead.source} · Batch ${lead.batchCode}</div>
              <div class="profile-pills">
                <span class="pill"><span style="color: var(--accent-strong);">📞</span> ${lead.phone}</span>
                <span class="pill"><span style="color: var(--gold);">📅</span> ${lead.date}</span>
                <span class="pill"><span style="color: var(--purple);">🎓</span> ${lead.course}</span>
                <span class="pill status" data-status="${status}">${status}</span>
                <span class="pill status" data-status="${getPaymentStatus(lead)}">${getPaymentStatus(lead)}</span>
              </div>
            </div>
          </div>
          <div style="display:flex; flex-direction:column; align-items:flex-end; gap:12px;">
            <div class="lead-id-box">
              <div class="lead-id-label">Student ID</div>
              <div class="lead-id-value">#${lead.id}</div>
            </div>
            <div class="profile-actions">
              <button class="btn btn-ghost" style="color: var(--pink); border-color: rgba(244,114,182,0.25);" onclick="toast('Calling ${lead.name}...')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                Call
              </button>
              <button class="btn btn-ghost" style="color: var(--success); border-color: rgba(52,211,153,0.25);" onclick="toast('WhatsApp opened for ${lead.name}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                WhatsApp
              </button>
              <button class="btn btn-warning" onclick="toast('Follow-up scheduled')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Follow-up
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Student Info Fields -->
      <div class="field-grid">
        <div class="field readonly"><label class="field-label">Full Name</label><input value="${lead.name}" disabled /></div>
        <div class="field readonly"><label class="field-label">Phone</label><input value="${lead.phone}" disabled /></div>
        <div class="field readonly"><label class="field-label">Email</label><input value="—" disabled /></div>
        <div class="field readonly"><label class="field-label">Course</label><input value="${lead.course}" disabled /></div>
        <div class="field readonly"><label class="field-label">Batch Code</label><input value="${lead.batchCode}" disabled /></div>
        <div class="field readonly"><label class="field-label">Batch Type</label><input value="${lead.batchType}" disabled /></div>
        <div class="field readonly"><label class="field-label">Total Fees</label><input value="${inr(lead.amount)}" disabled /></div>
        <div class="field readonly"><label class="field-label">Amount Paid</label><input value="${inr(lead.paid)}" disabled style="color:var(--success);" /></div>
      </div>

      <!-- Profile Tabs -->
      <div class="tabs">
        <div class="tab ${state.profileTab === 'call' ? 'active' : ''}" onclick="state.profileTab='call'; render();">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          Call History
        </div>
        <div class="tab ${state.profileTab === 'registration' ? 'active' : ''}" onclick="state.profileTab='registration'; render();">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Registration
        </div>
        <div class="tab ${state.profileTab === 'payment' ? 'active' : ''}" onclick="state.profileTab='payment'; render();">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
          Payment History
        </div>
      </div>

      ${state.profileTab === 'call' ? renderCallHistory(lead) : ''}
      ${state.profileTab === 'registration' ? renderRegistration(lead) : ''}
      ${state.profileTab === 'payment' ? renderPaymentHistory(lead) : ''}
    </div>
  `;
}

function renderCallHistory(lead) {
  return `
    <div class="timeline-section">
      <div class="timeline-header">
        <div class="timeline-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--gold);"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Call Timeline <span class="panel-sub" style="font-size:12px;color:var(--text-muted);margin-left:0;">Previous calls + active call</span>
        </div>
        <button class="btn btn-primary btn-sm">+ Add Entry</button>
      </div>

      <table class="timeline-table">
        <thead>
          <tr>
            <th>Call Time</th>
            <th>Direction</th>
            <th>Recording</th>
            <th>Duration</th>
            <th>Owned By</th>
            <th>Tag</th>
            <th style="width:32%;">Notes</th>
          </tr>
        </thead>
        <tbody>
          <!-- PREVIOUS section row — clickable to expand/collapse -->
          <tr class="timeline-section-row prev-toggle-row" onclick="togglePrevHistory()" style="cursor:pointer; user-select:none;">
            <td colspan="7">
              <div style="display:flex; align-items:center; gap:8px;">
                <div id="prev-arrow" style="transition:transform 0.2s ease; display:flex; align-items:center;">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--text-muted);"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
                <span class="label-prev">Previous History</span>
                <span style="text-transform:none; letter-spacing:normal; color:var(--text-faint); font-size:11px; font-weight:400;">(3 calls — click to expand)</span>
                <span id="prev-badge" style="margin-left:4px; font-size:10px; padding:2px 8px; border-radius:10px; background:var(--bg-card); border:1px solid var(--border-subtle); color:var(--text-muted); font-family:var(--font-mono);">3</span>
              </div>
            </td>
          </tr>
          <tr class="prev-hist-row" style="display:none;">
            <td><div class="call-time-cell"><div class="time">03:30 PM</div><div class="date">21 Apr</div></div></td>
            <td><span class="direction-pill outgoing">↗ Outgoing</span></td>
            <td><button class="play-btn">▶ 2m 10s</button></td>
            <td>2m 10s</td>
            <td>Self</td>
            <td><span class="tag-pill warm">WARM</span></td>
            <td style="color:var(--text-secondary);font-size:12px;">Called back, confirmed interest</td>
          </tr>
          <tr class="prev-hist-row" style="display:none;">
            <td><div class="call-time-cell"><div class="time">09:14 AM</div><div class="date">22 Apr</div></div></td>
            <td><span class="direction-pill incoming">↙ Incoming</span></td>
            <td><button class="play-btn">▶ 4m 32s</button></td>
            <td>4m 32s</td>
            <td>Self</td>
            <td><span class="tag-pill confirmed">CONFIRMED</span></td>
            <td style="color:var(--text-faint);font-size:12px;">(no note)</td>
          </tr>
          <tr class="prev-hist-row" style="display:none;">
            <td><div class="call-time-cell"><div class="time">02:48 PM</div><div class="date">26 Apr</div></div></td>
            <td><span class="direction-pill outgoing">↗ Outgoing</span></td>
            <td><button class="play-btn">▶ 3m 18s</button></td>
            <td>3m 18s</td>
            <td>Self</td>
            <td><span class="tag-pill dues-pending">DUES PENDING</span></td>
            <td style="color:var(--text-secondary);font-size:12px;">Promised to pay by Friday</td>
          </tr>

          <!-- CURRENT section -->
          <tr class="timeline-section-row">
            <td colspan="7"><span class="label-current">● CURRENT</span> &nbsp;<span style="text-transform:none;letter-spacing:normal;color:var(--text-faint);">(active call being handled)</span></td>
          </tr>
          <tr class="current-call-row">
            <td><div class="call-time-cell"><div class="time">10:22 AM</div><div class="date">29 Apr</div></div></td>
            <td><span class="direction-pill incoming">↙ Incoming</span></td>
            <td><button class="play-btn">▶ Live</button></td>
            <td>—</td>
            <td>Self</td>
            <td>
              <select class="select-tag" id="currentCallTag">
                <option value="">Select tag...</option>
                <option value="dues-pending">Dues Pending</option>
                <option value="overdue">Overdue</option>
                <option value="escalated">Escalated</option>
                <option value="dues-collected">Dues Collected</option>
              </select>
            </td>
            <td>
              <input class="note-input" placeholder="Add notes from this call..." style="margin-bottom:6px;" />
              <button class="assign-btn" onclick="openAssignModal()">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Assign
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  `;
}

function renderRegistration(lead) {
  const isSplit = lead.paymentMode === 'split';
  return `
    <!-- Admission Details (non-editable — filled by Admission Officer) -->
    <div class="reg-view-card">
      <div class="reg-view-header">
        <div>
          <div class="reg-view-title">Admission Details</div>
          <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Filled by Admission Officer · Read-only for Dues Staff</div>
        </div>
        <span class="pill status" data-status="Confirmed" style="font-size:11px;">✓ Verified</span>
      </div>
      <div class="reg-fields-row">
        <div class="reg-display-field">
          <div class="reg-display-label">Course</div>
          <div class="reg-display-value">${lead.course}</div>
        </div>
        <div class="reg-display-field">
          <div class="reg-display-label">Batch Code</div>
          <div class="reg-display-value">${lead.batchCode}</div>
        </div>
        <div class="reg-display-field">
          <div class="reg-display-label">Batch Type</div>
          <div class="reg-display-value">${lead.batchType}</div>
        </div>
      </div>
      <div class="reg-fields-row two" style="padding-top:0;">
        <div class="reg-display-field">
          <div class="reg-display-label">Course Fees</div>
          <div class="reg-display-value fee">${inr(lead.amount)}</div>
        </div>
        <div class="reg-display-field">
          <div class="reg-display-label">Payment Mode</div>
          <div class="reg-display-value">${isSplit ? 'Split Payment' : 'Full Payment'}</div>
        </div>
      </div>
    </div>

    <!-- Fee Collection (dues staff access) -->
    <div class="reg-view-card">
      <div class="reg-view-header">
        <div>
          <div class="reg-view-title">Fee Collection</div>
          <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
            ${isSplit ? 'Split 1 completed · Dues staff can edit remaining splits' : 'Manage full payment collection'}
          </div>
        </div>
        <div style="display:flex;gap:6px;align-items:center;">
          <span style="font-size:11px;color:var(--text-muted);">Collected:</span>
          <span style="font-family:var(--font-display);font-size:14px;font-weight:600;color:var(--success);">${inr(lead.paid)}</span>
          <span style="color:var(--text-faint);font-size:12px;">/</span>
          <span style="font-family:var(--font-display);font-size:14px;font-weight:600;color:var(--gold);">${inr(lead.amount)}</span>
        </div>
      </div>

      <div class="split-cards">
        ${lead.splits.map((split, idx) => {
          const extKey = lead.id + '-' + idx;
          const isExtOpen = !!state.extendOpen[extKey];
          return `
          <div class="split-card ${split.paid ? 'completed' : ''}">
            <div class="split-card-header">
              <div class="split-card-title">
                ${split.paid
                  ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`
                  : `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`}
                ${split.label}
                ${split.paid
                  ? `<span style="font-size:11px;color:var(--success);font-weight:400;">— Completed</span>`
                  : `<span style="font-size:11px;color:var(--warning);font-weight:400;">— Pending</span>`}
              </div>
              <div style="display:flex;align-items:center;gap:8px;">
                ${split.paid
                  ? `<span class="pill status" data-status="Paid" style="font-size:10px;">Paid</span>`
                  : `<span class="pill status" data-status="Pending" style="font-size:10px;">Due</span>
                     <button class="extend-date-btn" onclick="toggleExtend('${extKey}')">
                       <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transition:transform 0.2s;transform:${isExtOpen ? 'rotate(45deg)' : 'rotate(0)'};"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                       Extend Date
                     </button>`
                }
              </div>
            </div>
            <div class="split-card-body">
              <div class="split-input-group">
                <label>Amount (₹) <span style="font-size:9px;color:var(--text-faint);">set by Admission Officer</span></label>
                <input type="number" value="${split.amount}" ${split.paid ? 'disabled' : ''}
                  style="${split.paid ? 'color:var(--success);border-color:rgba(52,211,153,0.3);' : ''}" />
              </div>
              <div class="split-input-group">
                <label>Due Date <span style="font-size:9px;color:var(--text-faint);">set by Admission Officer</span></label>
                <input type="date" value="${split.date}" ${split.paid ? 'disabled' : ''}
                  style="${split.paid ? 'color:var(--success);border-color:rgba(52,211,153,0.3);' : ''}" />
              </div>
            </div>

            ${!split.paid ? `
              <!-- Extend Date Panel -->
              <div class="extend-panel" id="ext-${extKey}" style="display:${isExtOpen ? 'block' : 'none'};">
                <div class="extend-panel-inner">
                  <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span style="font-size:12px;font-weight:600;color:var(--gold);">Extend Payment Date for ${split.label}</span>
                  </div>
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                    <div class="split-input-group">
                      <label>Extended Date</label>
                      <input type="date" id="ext-date-${extKey}" value="${split.date}" style="border-color:rgba(212,168,87,0.4);" />
                    </div>
                    <div class="split-input-group">
                      <label>Extended Amount (₹)</label>
                      <input type="number" id="ext-amt-${extKey}" value="${split.amount}" style="border-color:rgba(212,168,87,0.4);" />
                    </div>
                  </div>
                  <div class="split-input-group" style="margin-bottom:12px;">
                    <label>Reason for Extension</label>
                    <input type="text" id="ext-reason-${extKey}" placeholder="e.g. Student requested 7 more days..." />
                  </div>
                  <div style="display:flex;gap:10px;">
                    <button class="btn btn-warning btn-sm" style="flex:1;justify-content:center;" onclick="saveExtension('${extKey}','${lead.id}',${idx})">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                      Save Extension
                    </button>
                    <button class="btn btn-primary btn-sm" style="flex:1;justify-content:center;" onclick="state.profileTab='payment'; state.payTab='link'; render(); toast('Redirected to Generate Payment Link')">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                      Generate Payment Link
                    </button>
                  </div>
                </div>
              </div>

              <div style="padding:0 16px 14px;">
                <button class="btn btn-success" style="width:100%;justify-content:center;" onclick="toast('${split.label} saved successfully')">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/></svg>
                  Save ${split.label}
                </button>
              </div>
            ` : ''}
          </div>
        `}).join('')}
      </div>
    </div>
  `;
}

function renderPaymentHistory(lead) {
  const paidSplits = lead.splits.filter(s => s.paid);
  const pendingSplits = lead.splits.filter(s => !s.paid);
  // Simulated previously generated links for pending splits
  const generatedLinks = lead.splits.filter(s => !s.paid).map((s, i) => ({
    id: 'LNK-' + lead.id + '-0' + (i+1),
    split: s.label,
    amount: s.amount,
    expiry: '2026-05-10',
    createdOn: '28 Apr 2026',
    sentVia: i % 2 === 0 ? 'WhatsApp' : 'SMS',
    status: 'Active',
  }));

  return `
    <div class="pay-tabs">
      <button class="pay-tab ${state.payTab === 'link' ? 'active' : ''}" onclick="state.payTab='link'; render();">Generate Payment Link</button>
      <button class="pay-tab ${state.payTab === 'paid' ? 'active' : ''}" onclick="state.payTab='paid'; render();">Paid Invoices</button>
      <span class="razorpay-badge">RAZORPAY</span>
    </div>

    <div class="reg-card">
      ${state.payTab === 'paid' ? `
        <div class="reg-card-header">
          <div>
            <div class="reg-card-title">Paid Invoices</div>
            <div class="reg-card-sub">${paidSplits.length} invoice${paidSplits.length !== 1 ? 's' : ''} generated · Invoices appear after payment completion</div>
          </div>
        </div>

        ${paidSplits.length === 0 ? `
          <div class="send-history-empty">
            <div class="send-history-icon">📄</div>
            No paid invoices yet. Invoices are generated after each payment is completed.
          </div>
        ` : paidSplits.map((split, i) => `
          <div class="invoice-card completed">
            <div style="width:40px;height:40px;background:var(--success-soft);border-radius:10px;display:grid;place-items:center;flex-shrink:0;color:var(--success);">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div style="flex:1;">
              <div style="font-weight:600;font-size:13px;">INV-${lead.id}-00${i+1} · ${split.label}</div>
              <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Paid ${new Date(split.date).toLocaleDateString('en-IN',{day:'2-digit',month:'short'})} · Razorpay</div>
            </div>
            <div style="text-align:right;">
              <div style="font-family:var(--font-display);font-weight:600;color:var(--success);">${inr(split.amount)}</div>
              <button class="btn btn-ghost btn-sm" style="margin-top:4px;" onclick="toast('Invoice downloaded')">Download PDF</button>
            </div>
          </div>
        `).join('')}

        ${pendingSplits.length > 0 ? `
          <div style="margin-top:16px;padding:14px;background:var(--warning-soft);border:1px dashed rgba(245,158,11,0.3);border-radius:var(--radius-md);">
            <div style="font-weight:600;color:var(--warning);margin-bottom:6px;">Pending Splits — Invoices will be added after payment</div>
            ${pendingSplits.map(s => `
              <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-top:1px solid rgba(245,158,11,0.15);font-size:12px;">
                <span style="color:var(--text-secondary);">${s.label}</span>
                <span style="font-weight:600;color:var(--warning);">${inr(s.amount)} · Due ${new Date(s.date).toLocaleDateString('en-IN',{day:'2-digit',month:'short'})}</span>
              </div>
            `).join('')}
          </div>
        ` : ''}
      ` : `
        <!-- GENERATE PAYMENT LINK -->
        <div class="reg-card-header">
          <div>
            <div class="reg-card-title">Generate Payment Link</div>
            <div class="reg-card-sub">Secure · Instant · Auto-tracked via Razorpay</div>
          </div>
        </div>
        <div class="reg-grid">
          <div class="input-group">
            <label>Student Name</label>
            <input value="${lead.name}" />
          </div>
          <div class="input-group">
            <label>Phone</label>
            <input value="${lead.phone}" />
          </div>
          <div class="input-group">
            <label>For Split</label>
            <select>
              ${lead.splits.filter(s => !s.paid).map(s => `<option>${s.label} — ${inr(s.amount)}</option>`).join('')}
              ${lead.splits.filter(s => !s.paid).length === 0 ? '<option>All splits paid</option>' : ''}
            </select>
          </div>
          <div class="input-group">
            <label>Amount (₹)</label>
            <input type="number" value="${lead.amount - lead.paid}" />
          </div>
          <div class="input-group">
            <label>Link Expiry</label>
            <input type="date" value="2026-05-10" />
          </div>
          <div class="input-group">
            <label>Send Via</label>
            <select>
              <option>WhatsApp</option>
              <option>SMS</option>
              <option>Email</option>
              <option>Copy Link</option>
            </select>
          </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:4px;margin-bottom:24px;">
          <button class="btn btn-primary" onclick="toast('⚡ Payment link generated — LNK-${lead.id}-NEW')">⚡ Generate Link</button>
          <button class="btn btn-success" onclick="toast('Sent via WhatsApp to ${lead.phone}')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            Send via WhatsApp
          </button>
          <button class="btn btn-ghost" onclick="toast('Link copied to clipboard')">📋 Copy Link</button>
        </div>

        <!-- GENERATED LINKS HISTORY -->
        ${generatedLinks.length > 0 ? `
          <div style="border-top:1px solid var(--border-subtle);padding-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);font-weight:600;margin-bottom:12px;">Previously Generated Links</div>
            <table style="width:100%;border-collapse:collapse;">
              <thead>
                <tr>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Link ID</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">For Split</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Amount</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Created</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Expiry</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Sent Via</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Status</th>
                  <th style="text-align:left;padding:8px 12px;font-size:9px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);background:var(--bg-deep);border-bottom:1px solid var(--border-subtle);">Action</th>
                </tr>
              </thead>
              <tbody>
                ${generatedLinks.map(lnk => `
                  <tr style="border-bottom:1px solid var(--border-subtle);">
                    <td style="padding:10px 12px;font-family:var(--font-mono);font-size:11px;color:var(--accent-strong);">${lnk.id}</td>
                    <td style="padding:10px 12px;"><span class="pill status" data-status="Split 1" style="font-size:10px;">${lnk.split}</span></td>
                    <td style="padding:10px 12px;font-weight:600;color:var(--gold);font-family:var(--font-display);">${inr(lnk.amount)}</td>
                    <td style="padding:10px 12px;font-size:12px;color:var(--text-secondary);">${lnk.createdOn}</td>
                    <td style="padding:10px 12px;font-size:12px;color:var(--text-secondary);">${lnk.expiry}</td>
                    <td style="padding:10px 12px;">
                      <span style="font-size:11px;font-weight:500;color:${lnk.sentVia === 'WhatsApp' ? 'var(--success)' : 'var(--accent-strong)'};">${lnk.sentVia === 'WhatsApp' ? '💬' : '📱'} ${lnk.sentVia}</span>
                    </td>
                    <td style="padding:10px 12px;">
                      <span class="pill" style="background:rgba(52,211,153,0.1);color:var(--success);border-color:rgba(52,211,153,0.25);font-size:10px;">● Active</span>
                    </td>
                    <td style="padding:10px 12px;">
                      <div style="display:flex;gap:6px;">
                        <button class="btn btn-ghost btn-sm" style="font-size:11px;padding:4px 8px;" onclick="toast('Link resent to ${lead.phone}')">Resend</button>
                        <button class="btn btn-ghost btn-sm" style="font-size:11px;padding:4px 8px;" onclick="toast('Link copied')">Copy</button>
                      </div>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        ` : `
          <div style="border-top:1px solid var(--border-subtle);padding-top:18px;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-faint);font-weight:600;margin-bottom:10px;">Previously Generated Links</div>
            <div style="text-align:center;padding:20px;color:var(--text-faint);font-size:13px;">No links generated yet. Generate your first link above.</div>
          </div>
        `}
      `}
    </div>
  `;
}

/* ============================================================
   VIEW: FOLLOW-UPS
============================================================ */
function viewFollowups() {
  const followupLeads = leads.filter(l => l.followUpDate);
  followupLeads.sort((a, b) => new Date(a.followUpDate) - new Date(b.followUpDate));

  return `
    <div class="view">
      <div class="topbar">
        <div class="page-title-block">
          <div class="crumb"><span class="dot"></span> Assigned to me</div>
          <h1>Follow-ups</h1>
        </div>
        <div class="topbar-actions">
          <button class="btn btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Send Reminders
          </button>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <div class="panel-title">
            <span class="pulse"></span>
            Follow-up Queue
            <span class="panel-sub">${followupLeads.length} students scheduled</span>
          </div>
        </div>
        <div style="overflow-x:auto;">
          <table class="due-table">
            <thead>
              <tr>
                <th>Student Name</th>
                <th>Phone No.</th>
                <th>Course</th>
                <th>Payment Date</th>
                <th>Payment Status</th>
                <th>Follow-up Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              ${followupLeads.map(l => {
                const days = daysFromToday(l.followUpDate);
                const fu = new Date(l.followUpDate).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'});
                const payDue = l.dueDate ? new Date(l.dueDate).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'}) : '—';
                const status = l.escalated ? 'Escalated' : l.status;
                let fuColor = 'var(--text-secondary)';
                if (days < 0) fuColor = 'var(--danger)';
                else if (days === 0) fuColor = 'var(--warning)';
                const payStatus = getPaymentStatus(l);
                return `
                  <tr onclick="go('profile', { lead: '${l.id}', tab: 'call' })">
                    <td>
                      <div class="lead-name-cell">
                        <div class="lead-avatar ${l.avatarClass}">${l.initials}</div>
                        <div>
                          <div class="lead-name">${l.name}</div>
                          <div class="lead-course">${l.course}</div>
                        </div>
                      </div>
                    </td>
                    <td><span class="phone-mono">${l.phone}</span></td>
                    <td>${l.course}</td>
                    <td><span class="date-text">${payDue}</span></td>
                    <td><span class="pill status" data-status="${payStatus}">${payStatus}</span></td>
                    <td><span style="font-size:13px;font-weight:500;color:${fuColor};">${fu}</span></td>
                    <td><span class="pill status" data-status="${status}">${status}</span></td>
                    <td onclick="event.stopPropagation();">
                      <div style="display:flex;gap:6px;">
                        <button class="btn btn-icon btn-sm" title="Call" style="color:var(--accent-strong);width:30px;height:30px;" onclick="toast('Calling ${l.name}...')">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </button>
                        <button class="btn btn-icon btn-sm" title="WhatsApp" style="color:var(--success);width:30px;height:30px;" onclick="toast('Reminder sent to ${l.name}')">
                          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </button>
                      </div>
                    </td>
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

/* ============================================================
   VIEW: COMPLETED PAYMENTS
============================================================ */
function viewCompleted() {
  // Full Paid: all splits paid
  const fullPaid = leads.filter(l => l.paid >= l.amount);
  // Split Paid: at least split 1 done, still has pending splits
  const splitPaid = leads.filter(l =>
    l.paymentMode === 'split' &&
    l.splits.length > 1 &&
    l.splits[0].paid === true &&
    l.splits.some(s => !s.paid)
  );

  const activeList = state.completedTab === 'full' ? fullPaid : splitPaid;

  return `
    <div class="view">
      <div class="topbar">
        <div class="page-title-block">
          <div class="crumb"><span class="dot"></span> Payment Summary</div>
          <h1>Completed Payments</h1>
        </div>
        <div class="topbar-actions">
          <button class="btn btn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export
          </button>
        </div>
      </div>

      <div class="panel">
        <!-- Two tabs only -->
        <div class="due-tabs">
          <div class="due-tab ${state.completedTab === 'full' ? 'active' : ''}" onclick="state.completedTab='full'; render();">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Full Paid
            <span class="tab-count" style="background:var(--success-soft);color:var(--success);">${fullPaid.length}</span>
          </div>
          <div class="due-tab ${state.completedTab === 'split' ? 'active' : ''}" onclick="state.completedTab='split'; render();">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            Split Paid
            <span class="tab-count" style="background:var(--gold-soft);color:var(--gold);">${splitPaid.length}</span>
          </div>
        </div>

        <div style="overflow-x:auto;">
          <table class="due-table">
            <thead>
              <tr>
                <th>Student Name</th>
                <th>Phone No.</th>
                <th>Course</th>
                <th>Payment Status</th>
                <th>Amount Paid</th>
                <th>Due Date</th>
                <th>Student Paid Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              ${activeList.length === 0 ? `
                <tr><td colspan="8" style="text-align:center;padding:36px;color:var(--text-faint);">
                  No students in this category yet
                </td></tr>
              ` : activeList.map(l => {
                // For Full Paid — use the last paid split for both dates
                // For Split Paid — use the first (Split 1) paid split for dates
                const relevantSplit = state.completedTab === 'full'
                  ? l.splits.filter(s => s.paid).pop()        // last paid (full)
                  : l.splits.find(s => s.paid);                // first paid (split 1)

                const dueDate = relevantSplit
                  ? new Date(relevantSplit.date).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'})
                  : '—';
                const paidOnDate = relevantSplit && relevantSplit.paidOn
                  ? new Date(relevantSplit.paidOn).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'})
                  : dueDate; // fallback to due date if paidOn not set

                const paidCount = l.splits.filter(s => s.paid).length;
                let payStatusLabel, bgColor, txtColor, borderColor;
                if (state.completedTab === 'full') {
                  payStatusLabel = l.paymentMode === 'full' ? 'Full Paid' : `Split ${paidCount} — Complete`;
                  bgColor = 'var(--success-soft)'; txtColor = 'var(--success)'; borderColor = 'rgba(52,211,153,0.3)';
                } else {
                  payStatusLabel = `Split ${paidCount} Paid`;
                  bgColor = 'var(--gold-soft)'; txtColor = 'var(--gold)'; borderColor = 'rgba(212,168,87,0.3)';
                }

                return `
                  <tr onclick="go('profile', { lead: '${l.id}', tab: 'payment' })">
                    <td>
                      <div class="lead-name-cell">
                        <div class="lead-avatar ${l.avatarClass}">${l.initials}</div>
                        <div>
                          <div class="lead-name">${l.name}</div>
                          <div class="lead-course">${l.course} · ${l.batchCode}</div>
                        </div>
                      </div>
                    </td>
                    <td><span class="phone-mono">${l.phone}</span></td>
                    <td><span style="font-weight:500;">${l.course}</span></td>
                    <td>
                      <span class="pill" style="background:${bgColor};color:${txtColor};border:1px solid ${borderColor};font-size:11px;gap:5px;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        ${payStatusLabel}
                      </span>
                    </td>
                    <td><span style="font-family:var(--font-display);font-weight:600;color:${txtColor};">${inr(l.paid)}</span></td>
                    <td>
                      <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:12px;color:var(--text-muted);font-size:9px;text-transform:uppercase;letter-spacing:0.08em;">Set Date</span>
                        <span class="date-text" style="font-weight:500;">${dueDate}</span>
                      </div>
                    </td>
                    <td>
                      <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:9px;text-transform:uppercase;letter-spacing:0.08em;color:${txtColor};">Paid On</span>
                        <span style="font-size:13px;font-weight:600;color:${txtColor};">${paidOnDate}</span>
                      </div>
                    </td>
                    <td>
                      ${state.completedTab === 'full'
                        ? `<span class="pill status" data-status="Paid">Paid</span>`
                        : `<span class="pill" style="background:var(--gold-soft);color:var(--gold);border:1px solid rgba(212,168,87,0.3);font-size:11px;">Split Paid</span>`}
                    </td>
                  </tr>
                `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  `;
}

function toggleExtend(key) {
  state.extendOpen[key] = !state.extendOpen[key];
  const panel = document.getElementById('ext-' + key);
  if (panel) panel.style.display = state.extendOpen[key] ? 'block' : 'none';
  // rotate the + icon button
  const btn = document.querySelector(`.extend-date-btn`);
  render(); // re-render to reflect icon rotation
}

function saveExtension(key, leadId, splitIdx) {
  const newDate = document.getElementById('ext-date-' + key)?.value || '';
  const newAmt  = document.getElementById('ext-amt-' + key)?.value || '';
  const reason  = document.getElementById('ext-reason-' + key)?.value || '';
  state.extendOpen[key] = false;
  toast(`Extension saved — ${newAmt ? '₹' + Number(newAmt).toLocaleString('en-IN') : ''} · ${newDate} · "${reason || 'No reason'}"`);
  render();
}

/* ============================================================
   TOGGLE PREVIOUS CALL HISTORY
============================================================ */
function togglePrevHistory() {
  const rows = document.querySelectorAll('.prev-hist-row');
  const arrow = document.getElementById('prev-arrow');
  const badge = document.getElementById('prev-badge');
  const hint = document.querySelector('.prev-toggle-row td span[style*="click"]');
  const isHidden = rows[0] && rows[0].style.display === 'none';
  rows.forEach(r => r.style.display = isHidden ? '' : 'none');
  if (arrow) arrow.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
  // update hint text in the section row
  const hintSpans = document.querySelectorAll('.prev-toggle-row td span');
  hintSpans.forEach(s => {
    if (s.textContent.includes('click to')) {
      s.textContent = isHidden ? '(3 calls — click to collapse)' : '(3 calls — click to expand)';
    }
  });
}

/* ============================================================
   RENDER
============================================================ */
function render() {
  const map = {
    dashboard: viewDashboard,
    dues: viewDues,
    profile: viewProfile,
    followups: viewFollowups,
    completed: viewCompleted,
  };
  document.getElementById('content').innerHTML = (map[state.view] || viewDashboard)();

  // Re-focus search if dues view
  if (state.view === 'dues') {
    const s = document.getElementById('due-search');
    if (s) { s.focus(); s.setSelectionRange(s.value.length, s.value.length); }
  }
}

// Sidebar nav click
document.addEventListener('click', (e) => {
  const navItem = e.target.closest('.nav-item[data-nav]');
  if (navItem) go(navItem.dataset.nav);

  // Close dropdowns when clicking outside
  if (!e.target.closest('.history-dropdown-wrap') && !e.target.closest('.dash-filter-dropdown')) {
    document.querySelectorAll('.history-dropdown-menu.open').forEach(el => el.classList.remove('open'));
    document.querySelectorAll('.dash-filter-menu.open').forEach(el => el.classList.remove('open'));
  } else if (!e.target.closest('.dash-filter-dropdown')) {
    document.querySelectorAll('.dash-filter-menu.open').forEach(el => el.classList.remove('open'));
  }

  // Close modal when clicking overlay
  if (e.target === document.getElementById('assignModal')) closeAssignModal();
});

render();
</script>
</body>
</html>
