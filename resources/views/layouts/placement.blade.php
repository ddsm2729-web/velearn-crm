<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Placement Pro — CRM Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
  :root {
    --bg-base: #0a0e1a;
    --bg-elev-1: #111726;
    --bg-elev-2: #161d2f;
    --bg-elev-3: #1c2438;
    --border: #232c44;
    --border-light: #2d3754;
    --text-primary: #e8edf7;
    --text-secondary: #8b96b3;
    --text-muted: #5d6986;
    --accent: #4f7cff;
    --accent-hover: #6388ff;
    --accent-soft: rgba(79, 124, 255, 0.12);
    --accent-soft-2: rgba(79, 124, 255, 0.2);
    --success: #22c55e;
    --success-soft: rgba(34, 197, 94, 0.15);
    --warning: #f59e0b;
    --warning-soft: rgba(245, 158, 11, 0.15);
    --danger: #ef4444;
    --danger-soft: rgba(239, 68, 68, 0.15);
    --hot: #ec4899;
    --hot-soft: rgba(236, 72, 153, 0.15);
    --purple: #a855f7;
    --purple-soft: rgba(168, 85, 247, 0.15);
    --cyan: #06b6d4;
    --cyan-soft: rgba(6, 182, 212, 0.15);
    --teal: #14b8a6;
    --teal-soft: rgba(20, 184, 166, 0.15);
    --orange: #f97316;
    --orange-soft: rgba(249, 115, 22, 0.15);
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.3);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.4);
    --shadow-lg: 0 8px 30px rgba(0,0,0,0.5);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --font-display: 'Instrument Serif', serif;
    --font-body: 'Manrope', system-ui, sans-serif;
    --font-mono: 'JetBrains Mono', monospace;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { background: var(--bg-base); color: var(--text-primary); font-family: var(--font-body); font-size: 14px; line-height: 1.5; min-height: 100vh; -webkit-font-smoothing: antialiased; }
  body {
    background:
      radial-gradient(ellipse 1200px 600px at 80% -10%, rgba(79, 124, 255, 0.08), transparent 60%),
      radial-gradient(ellipse 800px 500px at 0% 100%, rgba(168, 85, 247, 0.04), transparent 60%),
      var(--bg-base);
    background-attachment: fixed;
  }

  .app { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
  .sidebar {
    background: rgba(17, 23, 38, 0.6);
    backdrop-filter: blur(20px);
    border-right: 1px solid var(--border);
    padding: 24px 16px;
    position: sticky; top: 0; height: 100vh; overflow-y: auto;
  }
  .brand { display: flex; align-items: center; gap: 10px; padding: 8px 12px 18px; border-bottom: 1px solid var(--border); margin-bottom: 12px; }
  .brand-mark { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; color: white; box-shadow: 0 4px 12px rgba(79, 124, 255, 0.4); }
  .brand-name { font-weight: 700; font-size: 16px; letter-spacing: -0.3px; }
  .brand-name em { font-family: var(--font-display); font-style: italic; font-weight: 400; color: var(--accent); }

  /* role switch in sidebar */
  .role-switch {
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 4px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2px;
    margin-bottom: 16px;
  }
  .role-btn {
    background: transparent;
    border: none;
    color: var(--text-secondary);
    padding: 9px 8px;
    border-radius: 7px;
    font-size: 11.5px;
    font-weight: 700;
    font-family: var(--font-body);
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    transition: all 0.15s;
  }
  .role-btn:hover:not(.active) { color: var(--text-primary); }
  .role-btn.active {
    background: linear-gradient(135deg, var(--accent), #3b6ae8);
    color: white;
    box-shadow: 0 2px 8px rgba(79, 124, 255, 0.3);
  }

  .nav-section-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px; color: var(--text-muted); padding: 0 12px; margin: 14px 0 6px; font-weight: 600; }
  .nav-item { display: flex; align-items: center; gap: 12px; padding: 9px 12px; border-radius: var(--radius-sm); color: var(--text-secondary); cursor: pointer; font-weight: 500; font-size: 13.5px; transition: all 0.15s ease; border: 1px solid transparent; margin-bottom: 2px; }
  .nav-item:hover { background: var(--bg-elev-2); color: var(--text-primary); }
  .nav-item.active { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-soft-2); font-weight: 600; }
  .nav-item svg { width: 18px; height: 18px; stroke-width: 1.8; flex-shrink: 0; }
  .nav-badge { margin-left: auto; background: var(--bg-elev-3); color: var(--text-secondary); font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 999px; font-family: var(--font-mono); }
  .nav-item.active .nav-badge { background: rgba(79, 124, 255, 0.2); color: var(--accent); }
  .nav-item.role-tag::after {
    content: attr(data-role-tag);
    margin-left: auto;
    font-size: 9px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }
  .nav-item[data-role-tag="P"]::after { background: rgba(79, 124, 255, 0.15); color: var(--accent); }
  .nav-item[data-role-tag="S"]::after { background: rgba(20, 184, 166, 0.15); color: var(--teal); }
  .nav-item.has-badge.role-tag::after { display: none; }

  /* hide nav items based on role */
  body[data-role="placement"] .nav-item[data-show="student"] { display: none; }
  body[data-role="student"]   .nav-item[data-show="placement"] { display: none; }

  .main { padding: 24px 32px 48px; min-width: 0; overflow-x: hidden; }
  .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
  .page-title-wrap h1 { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; margin-bottom: 2px; display: flex; align-items: center; gap: 12px; }
  .page-title-wrap h1 em { font-family: var(--font-display); font-style: italic; font-weight: 400; color: var(--accent); }
  .role-chip {
    font-family: var(--font-body);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 4px 10px;
    border-radius: 999px;
    border: 1px solid;
  }
  body[data-role="placement"] .role-chip { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-soft-2); }
  body[data-role="student"] .role-chip { background: var(--teal-soft); color: var(--teal); border-color: rgba(20, 184, 166, 0.3); }
  .page-title-wrap .crumb { font-size: 12px; color: var(--text-muted); }
  .topbar-right { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

  .date-pill { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, var(--bg-elev-1), var(--bg-elev-2)); border: 1px solid var(--border); color: var(--text-primary); padding: 8px 14px; border-radius: var(--radius-md); font-size: 12.5px; font-weight: 600; }
  .date-pill svg { width: 14px; height: 14px; color: var(--accent); }
  .date-pill .dw { color: var(--text-muted); font-weight: 500; }

  .search { background: var(--bg-elev-1); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 9px 14px 9px 38px; color: var(--text-primary); font-family: var(--font-body); font-size: 13px; width: 200px; outline: none; transition: all 0.15s; }
  .search:focus { border-color: var(--accent); background: var(--bg-elev-2); }
  .search-wrap { position: relative; }
  .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--text-muted); }
  .user-pill { display: flex; align-items: center; gap: 10px; background: var(--bg-elev-1); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 6px 12px 6px 6px; }
  .user-pill .av { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; color: white; }
  .user-pill span { font-size: 12.5px; font-weight: 600; }

  .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-elev-1); color: var(--text-primary); font-family: var(--font-body); font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; text-decoration: none; }
  .btn:hover { background: var(--bg-elev-2); border-color: var(--border-light); }
  .btn-primary { background: linear-gradient(135deg, #4f7cff, #3b6ae8); border-color: rgba(79, 124, 255, 0.4); color: white; box-shadow: 0 2px 8px rgba(79, 124, 255, 0.3); }
  .btn-primary:hover { background: linear-gradient(135deg, #6388ff, #4f7cff); border-color: var(--accent); }
  .btn-ghost { background: transparent; border: 1px solid var(--border); }
  .btn-ghost:hover { background: var(--bg-elev-1); }
  .btn-sm { padding: 6px 10px; font-size: 12px; }
  .btn svg { width: 14px; height: 14px; stroke-width: 2.2; }

  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 24px; }
  .stat-card { background: linear-gradient(135deg, var(--bg-elev-1), var(--bg-elev-2)); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px; position: relative; overflow: hidden; transition: all 0.2s; }
  .stat-card::before { content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: radial-gradient(circle at top right, var(--accent-soft), transparent 70%); opacity: 0.5; }
  .stat-card:hover { border-color: var(--border-light); transform: translateY(-1px); }
  .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
  .stat-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; background: var(--accent-soft); color: var(--accent); }
  .stat-icon svg { width: 14px; height: 14px; }
  .stat-icon.green { background: var(--success-soft); color: var(--success); }
  .stat-icon.amber { background: var(--warning-soft); color: var(--warning); }
  .stat-icon.red { background: var(--danger-soft); color: var(--danger); }
  .stat-icon.purple { background: var(--purple-soft); color: var(--purple); }
  .stat-icon.pink { background: var(--hot-soft); color: var(--hot); }
  .stat-icon.cyan { background: var(--cyan-soft); color: var(--cyan); }
  .stat-icon.teal { background: var(--teal-soft); color: var(--teal); }
  .stat-icon.orange { background: var(--orange-soft); color: var(--orange); }
  .stat-value { font-family: var(--font-mono); font-size: 28px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.5px; line-height: 1.1; }
  .stat-sub { font-size: 11.5px; color: var(--text-muted); margin-top: 4px; }

  .panel { background: var(--bg-elev-1); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 20px; }
  .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--border); gap: 12px; flex-wrap: wrap; }
  .panel-title { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 14.5px; letter-spacing: -0.2px; }
  .panel-title .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 8px var(--accent); animation: pulse 2s infinite; }
  .panel-title small { color: var(--text-muted); font-weight: 500; font-size: 12px; margin-left: 4px; }
  @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

  .split-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
  @media (max-width: 1100px) { .split-grid { grid-template-columns: 1fr; } }

  .strip-list { padding: 8px; display: flex; flex-direction: column; gap: 6px; max-height: 600px; overflow-y: auto; }
  .strip { display: flex; align-items: center; background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 12px 14px; gap: 14px; transition: all 0.15s; cursor: pointer; position: relative; }
  .strip:hover { background: var(--bg-elev-3); border-color: var(--border-light); transform: translateX(2px); }
  .strip-cell { display: flex; flex-direction: column; gap: 2px; min-width: 0; flex-shrink: 0; }
  .strip-cell.grow { flex: 1; min-width: 0; }
  .strip-cell .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); font-weight: 600; }
  .strip-cell .val { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .strip-cell .val.mono { font-family: var(--font-mono); font-weight: 500; }
  .strip-cell .val.muted { color: var(--text-secondary); font-weight: 500; }

  /* progress-coded border for eligible students */
  .strip[data-progress="green"] { border-left: 3px solid var(--success); }
  .strip[data-progress="orange"] { border-left: 3px solid var(--orange); }
  .strip[data-progress="red"] { border-left: 3px solid var(--danger); }

  /* compact strip used in narrow panels */
  .strip-compact { display: flex; flex-direction: column; background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 12px 14px; gap: 10px; cursor: pointer; transition: all 0.15s; position: relative; overflow: hidden; }
  .strip-compact:hover { background: var(--bg-elev-3); border-color: var(--border-light); transform: translateX(2px); }
  .strip-compact .head { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
  .strip-compact .head .name { font-size: 14px; font-weight: 700; letter-spacing: -0.2px; }
  .strip-compact .meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px 14px; }
  .strip-compact .meta .item { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
  .strip-compact .meta .item .lbl { font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); font-weight: 600; }
  .strip-compact .meta .item .val { font-size: 12px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .strip-compact .meta .item .val.mono { font-family: var(--font-mono); font-weight: 500; }
  .strip-compact .meta .item .val.muted { color: var(--text-secondary); font-weight: 500; }

  .tag { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; border-radius: 999px; border: 1px solid transparent; white-space: nowrap; }
  .tag-hot { background: var(--hot-soft); color: var(--hot); border-color: rgba(236, 72, 153, 0.3); }
  .tag-scheduled { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-soft-2); }
  .tag-rescheduled { background: var(--warning-soft); color: var(--warning); border-color: rgba(245, 158, 11, 0.3); }
  .tag-selected { background: var(--success-soft); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
  .tag-rejected { background: var(--danger-soft); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
  .tag-pending { background: var(--bg-elev-3); color: var(--text-secondary); border-color: var(--border-light); }
  .tag-onprocess { background: var(--cyan-soft); color: var(--cyan); border-color: rgba(6, 182, 212, 0.3); }
  .tag-hold { background: var(--purple-soft); color: var(--purple); border-color: rgba(168, 85, 247, 0.3); }
  .tag-attended { background: var(--success-soft); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
  .tag-notattended { background: var(--orange-soft); color: var(--orange); border-color: rgba(249, 115, 22, 0.3); }
  .tag-noshow { background: var(--danger-soft); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
  .tag-profilesent { background: var(--teal-soft); color: var(--teal); border-color: rgba(20, 184, 166, 0.3); }
  .tag-resultawaited { background: var(--purple-soft); color: var(--purple); border-color: rgba(168, 85, 247, 0.3); }
  .tag-datepending { background: var(--warning-soft); color: var(--warning); border-color: rgba(245, 158, 11, 0.3); }
  .tag-active { background: var(--success-soft); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
  .tag-filled { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-soft-2); }
  .tag-closed { background: var(--danger-soft); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
  .tag-new { background: var(--purple-soft); color: var(--purple); border-color: rgba(168, 85, 247, 0.3); }
  .tag-contacted { background: var(--cyan-soft); color: var(--cyan); border-color: rgba(6, 182, 212, 0.3); }
  .tag-followup { background: var(--warning-soft); color: var(--warning); border-color: rgba(245, 158, 11, 0.3); }
  .tag-immediate { background: var(--success-soft); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
  .tag-later { background: var(--orange-soft); color: var(--orange); border-color: rgba(249, 115, 22, 0.3); }
  .tag-nothiring { background: var(--danger-soft); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }

  /* expandable linked opening on company detail */
  .open-expand-wrap { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 8px; }
  .open-expand-header { display: flex; align-items: center; padding: 12px 14px; gap: 14px; cursor: pointer; transition: background 0.15s; }
  .open-expand-header:hover { background: var(--bg-elev-3); }
  .open-expand-chev { width: 18px; height: 18px; color: var(--text-muted); transition: transform 0.2s; flex-shrink: 0; }
  .open-expand-wrap.expanded .open-expand-chev { transform: rotate(90deg); }
  .open-expand-body { display: none; border-top: 1px solid var(--border); padding: 14px; background: rgba(0,0,0,0.15); }
  .open-expand-wrap.expanded .open-expand-body { display: block; }
  .open-status-tabs { display: flex; gap: 4px; background: var(--bg-elev-1); padding: 4px; border-radius: 8px; border: 1px solid var(--border); width: fit-content; margin-bottom: 12px; flex-wrap: wrap; }
  .open-status-tab { padding: 5px 11px; border-radius: 6px; font-size: 11.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; background: transparent; border: none; font-family: var(--font-body); display: inline-flex; align-items: center; gap: 6px; }
  .open-status-tab.active { background: var(--bg-elev-3); color: var(--text-primary); }
  .open-status-tab .cnt { font-family: var(--font-mono); font-size: 10px; background: var(--bg-elev-3); padding: 1px 6px; border-radius: 999px; }
  .open-status-tab.active .cnt { background: var(--accent-soft); color: var(--accent); }

  /* assign button in call history */
  .assign-btn { background: var(--bg-elev-3); border: 1px solid var(--border); color: var(--text-secondary); padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-family: var(--font-body); white-space: nowrap; }
  .assign-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
  .assign-btn svg { width: 11px; height: 11px; }
  .tag .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

  .tabs { display: flex; gap: 4px; background: var(--bg-elev-2); padding: 4px; border-radius: 10px; border: 1px solid var(--border); width: fit-content; margin-bottom: 16px; flex-wrap: wrap; }
  .tab { padding: 7px 14px; border-radius: 7px; font-size: 12.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; transition: all 0.15s; background: transparent; border: none; font-family: var(--font-body); }
  .tab.active { background: var(--bg-elev-3); color: var(--text-primary); box-shadow: var(--shadow-sm); }
  .tab:hover:not(.active) { color: var(--text-primary); }

  .filter-bar { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 16px; }
  .filter-select { background: var(--bg-elev-1); border: 1px solid var(--border); color: var(--text-primary); padding: 8px 30px 8px 12px; border-radius: var(--radius-sm); font-family: var(--font-body); font-size: 12.5px; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b96b3' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>"); background-repeat: no-repeat; background-position: right 10px center; }
  .filter-select:focus { outline: none; border-color: var(--accent); }

  .modal-overlay { position: fixed; inset: 0; background: rgba(5, 8, 16, 0.7); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 200; padding: 24px; animation: fadeIn 0.2s ease; }
  .modal-overlay.show { display: flex; }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  .modal { background: var(--bg-elev-1); border: 1px solid var(--border-light); border-radius: var(--radius-xl); width: 100%; max-width: 720px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column; box-shadow: var(--shadow-lg); animation: slideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
  @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
  .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
  .modal-title { font-size: 17px; font-weight: 700; letter-spacing: -0.3px; }
  .modal-close { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: 8px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-secondary); }
  .modal-close:hover { color: var(--text-primary); background: var(--bg-elev-3); }
  .modal-body { padding: 22px 24px; overflow-y: auto; }
  .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; background: rgba(0,0,0,0.15); }
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .form-grid .full { grid-column: 1 / -1; }
  .form-group { display: flex; flex-direction: column; gap: 6px; }
  .form-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); font-weight: 600; }
  .form-input, .form-select, .form-textarea { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 10px 12px; color: var(--text-primary); font-family: var(--font-body); font-size: 13px; outline: none; transition: all 0.15s; }
  .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--accent); background: var(--bg-elev-3); }
  .form-input[readonly] { opacity: 0.7; cursor: not-allowed; background: var(--bg-elev-3); }
  .form-textarea { resize: vertical; min-height: 80px; font-family: var(--font-body); }
  .form-select { appearance: none; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238b96b3' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 32px; }

  .detail-header { background: linear-gradient(135deg, var(--bg-elev-1), var(--bg-elev-2)); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 24px; margin-bottom: 20px; }
  .detail-top { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 20px; }
  .detail-avatar { width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800; color: white; flex-shrink: 0; box-shadow: 0 4px 16px rgba(79, 124, 255, 0.3); }
  .detail-name { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 6px; }
  .detail-meta { display: flex; align-items: center; gap: 10px; color: var(--text-secondary); font-size: 12.5px; flex-wrap: wrap; }
  .detail-meta .sep { color: var(--text-muted); }
  .detail-actions { margin-left: auto; display: flex; gap: 8px; flex-wrap: wrap; }
  .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; }
  .info-cell { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; }
  .info-cell .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 0.7px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
  .info-cell .val { font-size: 13.5px; font-weight: 600; word-break: break-word; white-space: normal; }
  .info-cell .val.mono { font-family: var(--font-mono); }
  .info-cell .val a { color: var(--accent); text-decoration: none; }
  .info-cell .val a:hover { text-decoration: underline; }

  /* progress bar */
  .progress-bar { height: 6px; background: var(--bg-elev-3); border-radius: 999px; overflow: hidden; margin-top: 6px; }
  .progress-fill { height: 100%; border-radius: 999px; transition: width 0.4s ease; }
  .progress-fill.green { background: linear-gradient(90deg, var(--success), #16a34a); }
  .progress-fill.orange { background: linear-gradient(90deg, var(--orange), #ea580c); }
  .progress-fill.red { background: linear-gradient(90deg, var(--danger), #dc2626); }

  /* timeline */
  .timeline-row { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 6px; position: relative; }
  .timeline-row::before { content: ''; position: absolute; left: -1px; top: 0; bottom: 0; width: 3px; background: var(--accent); border-radius: 3px 0 0 3px; }
  .timeline-row.green::before { background: var(--success); }
  .timeline-row.red::before { background: var(--danger); }
  .timeline-row.amber::before { background: var(--warning); }
  .timeline-time { font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary); min-width: 75px; }

  .pill-incoming { background: var(--success-soft); color: var(--success); padding: 5px 10px; border-radius: 6px; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; gap: 5px; }
  .pill-outgoing { background: var(--accent-soft); color: var(--accent); padding: 5px 10px; border-radius: 6px; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; gap: 5px; }
  .play-btn { background: var(--bg-elev-3); border: 1px solid var(--border); color: var(--text-primary); padding: 5px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
  .play-btn:hover { border-color: var(--accent); color: var(--accent); }

  .empty { text-align: center; padding: 50px 20px; color: var(--text-muted); font-size: 13px; }
  .empty svg { width: 40px; height: 40px; opacity: 0.4; margin-bottom: 12px; }

  .section-heading { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; gap: 12px; flex-wrap: wrap; }
  .section-heading h2 { font-size: 17px; font-weight: 700; letter-spacing: -0.3px; display: flex; align-items: center; gap: 8px; }
  .section-heading h2 .count { font-family: var(--font-mono); font-size: 12px; background: var(--bg-elev-2); color: var(--text-secondary); padding: 2px 8px; border-radius: 999px; font-weight: 500; }

  .toast { position: fixed; bottom: 24px; right: 24px; background: var(--bg-elev-3); border: 1px solid var(--success); color: var(--text-primary); padding: 12px 18px; border-radius: var(--radius-md); font-weight: 600; font-size: 13px; display: flex; align-items: center; gap: 10px; box-shadow: var(--shadow-lg); z-index: 300; transform: translateY(80px); opacity: 0; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
  .toast.show { transform: translateY(0); opacity: 1; }
  .toast svg { width: 18px; height: 18px; color: var(--success); }

  ::-webkit-scrollbar { width: 8px; height: 8px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 999px; }
  ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

  .hidden { display: none !important; }
  .skills-chips { display: flex; gap: 5px; flex-wrap: wrap; }
  .skill-chip { background: var(--bg-elev-3); border: 1px solid var(--border); color: var(--text-secondary); padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; font-family: var(--font-mono); }
  .name-link { cursor: pointer; transition: color 0.15s; }
  .name-link:hover { color: var(--accent); text-decoration: underline; }

  /* mini summary boxes for opening detail / monitoring */
  .mini-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 10px; }
  .mini-stat { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; text-align: center; }
  .mini-stat .mlbl { font-size: 10px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
  .mini-stat .mval { font-family: var(--font-mono); font-size: 22px; font-weight: 700; letter-spacing: -0.4px; }
  .mini-stat.green .mval { color: var(--success); }
  .mini-stat.red .mval { color: var(--danger); }
  .mini-stat.purple .mval { color: var(--purple); }
  .mini-stat.cyan .mval { color: var(--cyan); }
  .mini-stat.orange .mval { color: var(--orange); }
  .mini-stat.amber .mval { color: var(--warning); }
  .mini-stat.accent .mval { color: var(--accent); }

  /* shortlist module */
  .shortlist-pick {
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 14px;
    margin-bottom: 14px;
  }
  .checkbox-strip {
    display: flex; align-items: center; gap: 12px;
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 10px 14px;
    margin-bottom: 6px;
    cursor: pointer;
    transition: all 0.15s;
  }
  .checkbox-strip:hover { background: var(--bg-elev-3); border-color: var(--border-light); }
  .checkbox-strip.checked { border-color: var(--accent); background: var(--accent-soft); }
  .checkbox-strip input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--accent); }
  .link-toggle-group {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 6px;
    flex: 1;
    max-width: 360px;
  }
  .link-toggle {
    background: var(--bg-elev-3);
    border: 1px solid var(--border);
    color: var(--text-secondary);
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    transition: all 0.15s;
  }
  .link-toggle:hover { color: var(--text-primary); border-color: var(--border-light); }
  .link-toggle.active { background: var(--accent); color: white; border-color: var(--accent); }

  /* shortlist redesign: step bar */
  .step-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
  .step-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px 8px 8px; background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: 999px; font-size: 12.5px; font-weight: 600; color: var(--text-secondary); transition: all 0.2s; }
  .step-pill.active { background: var(--accent-soft); border-color: var(--accent); color: var(--text-primary); }
  .step-pill.done { background: var(--success-soft); border-color: rgba(34,197,94,0.4); color: var(--success); }
  .step-pill .step-num { width: 24px; height: 24px; background: var(--bg-elev-3); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-family: var(--font-mono); font-size: 12px; }
  .step-pill.active .step-num { background: var(--accent); color: white; }
  .step-pill.done .step-num { background: var(--success); color: white; }
  .step-arrow { color: var(--text-muted); font-weight: 600; font-size: 14px; }
  .selection-summary { font-family: var(--font-mono); font-size: 12px; background: var(--accent-soft); color: var(--accent); padding: 5px 12px; border-radius: 999px; border: 1px solid var(--accent-soft-2); font-weight: 600; }

  /* improved checkbox strip — clearer card style */
  .student-card { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 14px 16px; margin-bottom: 8px; transition: all 0.15s; }
  .student-card:hover { background: var(--bg-elev-3); border-color: var(--border-light); }
  .student-card.checked { background: linear-gradient(135deg, rgba(79,124,255,0.08), rgba(168,85,247,0.04)); border-color: var(--accent); box-shadow: 0 0 0 1px var(--accent-soft); }
  .student-card-head { display: flex; align-items: center; gap: 14px; }
  .student-card-check { width: 22px; height: 22px; border-radius: 6px; border: 2px solid var(--border-light); background: var(--bg-elev-3); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.15s; }
  .student-card.checked .student-card-check { background: var(--accent); border-color: var(--accent); }
  .student-card-check svg { width: 14px; height: 14px; color: white; opacity: 0; }
  .student-card.checked .student-card-check svg { opacity: 1; }
  .student-card-avatar { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #4f7cff, #a855f7); color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; }
  .student-card-info { flex: 1; min-width: 0; }
  .student-card-name { font-weight: 700; font-size: 14px; margin-bottom: 3px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .student-card-meta { font-size: 11.5px; color: var(--text-secondary); display: flex; gap: 12px; flex-wrap: wrap; }
  .student-card-meta span { display: inline-flex; align-items: center; gap: 4px; }
  .student-card-links { display: none; padding-top: 12px; margin-top: 12px; border-top: 1px dashed var(--border); }
  .student-card.checked .student-card-links { display: block; }
  .student-card-links-label { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); font-weight: 600; margin-bottom: 6px; }
  .pill-match { background: var(--success-soft); color: var(--success); padding: 1px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.3px; }
  .pill-sent { background: var(--teal-soft); color: var(--teal); padding: 1px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.3px; }

  /* step 3 review card */
  .review-card { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 14px 18px; }
  .review-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed var(--border); font-size: 13px; }
  .review-row:last-child { border-bottom: none; }
  .review-row .lab { color: var(--text-muted); font-weight: 500; }
  .review-row .val { color: var(--text-primary); font-weight: 600; }

  /* === searchable opening card grid (Shortlist Step 1, LMS Step 2) === */
  .opening-card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
  .opening-card {
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 14px;
    cursor: pointer;
    transition: all 0.15s;
    display: flex;
    flex-direction: column;
    gap: 10px;
    position: relative;
    overflow: hidden;
  }
  .opening-card:hover { background: var(--bg-elev-3); border-color: var(--border-light); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
  .opening-card.selected { border-color: var(--accent); background: linear-gradient(135deg, rgba(79,124,255,0.10), rgba(168,85,247,0.05)); box-shadow: 0 0 0 1px var(--accent-soft); }
  .opening-card.disabled { opacity: 0.5; cursor: not-allowed; }
  .opening-card-tick { position: absolute; top: 10px; right: 10px; width: 22px; height: 22px; border-radius: 6px; background: var(--accent); display: none; align-items: center; justify-content: center; }
  .opening-card.selected .opening-card-tick { display: inline-flex; }
  .opening-card-tick svg { width: 14px; height: 14px; color: white; }
  .oc-head { display: flex; align-items: center; gap: 10px; }
  .oc-logo { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #4f7cff, #a855f7); color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; }
  .oc-title { font-weight: 700; font-size: 14px; line-height: 1.2; }
  .oc-sub { font-size: 11.5px; color: var(--text-secondary); margin-top: 2px; }
  .oc-meta-row { display: flex; gap: 10px; flex-wrap: wrap; font-size: 11.5px; }
  .oc-meta-row .oc-meta { display: inline-flex; align-items: center; gap: 4px; color: var(--text-secondary); }
  .oc-meta strong { color: var(--text-primary); font-family: var(--font-mono); font-weight: 600; }
  .oc-chips { display: flex; gap: 4px; flex-wrap: wrap; }
  .oc-chip { background: var(--bg-elev-3); border: 1px solid var(--border); color: var(--text-secondary); padding: 2px 7px; border-radius: 5px; font-size: 10.5px; font-weight: 600; font-family: var(--font-mono); }
  .oc-chip.match { background: var(--success-soft); color: var(--success); border-color: rgba(34,197,94,0.3); }
  .oc-foot { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 2px; padding-top: 10px; border-top: 1px dashed var(--border); }
  .oc-badge-pushed { background: var(--teal-soft); color: var(--teal); padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.4px; }

  /* === LMS course grid === */
  .lms-course-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; }
  .course-card {
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 16px;
    cursor: pointer;
    transition: all 0.15s;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .course-card:hover { background: var(--bg-elev-3); border-color: var(--border-light); transform: translateY(-1px); }
  .course-card.selected { border-color: var(--accent); background: linear-gradient(135deg, rgba(79,124,255,0.08), rgba(20,184,166,0.04)); box-shadow: 0 0 0 1px var(--accent-soft); }
  .course-card-head { display: flex; align-items: center; gap: 12px; }
  .course-card-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--accent-soft); color: var(--accent); display: inline-flex; align-items: center; justify-content: center; }
  .course-card-icon svg { width: 22px; height: 22px; }
  .course-card.selected .course-card-icon { background: var(--accent); color: white; }
  .course-card-name { font-weight: 700; font-size: 14.5px; }
  .course-card-batch { font-size: 11.5px; color: var(--text-secondary); margin-top: 2px; }
  .course-card-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding-top: 10px; border-top: 1px dashed var(--border); }
  .ccs { text-align: center; }
  .ccs .ccs-num { font-family: var(--font-mono); font-size: 18px; font-weight: 700; color: var(--text-primary); }
  .ccs .ccs-lbl { font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600; }
  .course-card .skill-chips { display: flex; gap: 4px; flex-wrap: wrap; }

  /* === Opening detail · student workflow === */
  .pipeline-banner {
    display: grid;
    grid-template-columns: 1fr 16px 1fr 16px 1fr 16px 1fr;
    align-items: center;
    gap: 0;
    background: linear-gradient(135deg, var(--bg-elev-1), var(--bg-elev-2));
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 16px 20px;
    margin-bottom: 20px;
  }
  @media (max-width: 800px) { .pipeline-banner { grid-template-columns: 1fr; gap: 14px; } .pipe-divider { display: none; } }
  .pipe-step { display: flex; align-items: center; gap: 12px; }
  .pipe-icon { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .pipe-icon svg { width: 18px; height: 18px; }
  .pipe-icon.teal   { background: var(--teal-soft);    color: var(--teal); }
  .pipe-icon.orange { background: var(--orange-soft);  color: var(--orange); }
  .pipe-icon.accent { background: var(--accent-soft);  color: var(--accent); }
  .pipe-icon.green  { background: var(--success-soft); color: var(--success); }
  .pipe-num { font-family: var(--font-mono); font-size: 22px; font-weight: 700; letter-spacing: -0.4px; }
  .pipe-lbl { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.6px; color: var(--text-muted); font-weight: 600; }
  .pipe-divider { height: 2px; background: linear-gradient(90deg, var(--border), var(--border-light), var(--border)); border-radius: 2px; }

  /* pushed-to chip with remove button */
  .pushed-chip {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--teal-soft); color: var(--teal);
    border: 1px solid rgba(20,184,166,0.3);
    padding: 6px 4px 6px 12px; border-radius: 8px;
    font-size: 12px; font-weight: 600;
  }
  .pushed-chip-x {
    background: rgba(20,184,166,0.2);
    border: none;
    color: var(--teal);
    width: 20px; height: 20px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 14px;
    font-weight: 700;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s;
  }
  .pushed-chip-x:hover { background: var(--danger); color: white; }

  /* course mini cards (inside opening detail Push panel) */
  .course-mini-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; }
  .course-mini {
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px 14px;
    cursor: pointer;
    transition: all 0.15s;
    display: flex; flex-direction: column; gap: 6px;
    position: relative;
    overflow: hidden;
  }
  .course-mini:hover:not(.disabled) { background: var(--bg-elev-3); border-color: var(--accent); transform: translateY(-1px); }
  .course-mini.match { border-color: rgba(34,197,94,0.3); }
  .course-mini.match::before { content:''; position:absolute; top:0; left:0; bottom:0; width:3px; background: var(--success); }
  .course-mini.disabled { opacity: 0.55; cursor: not-allowed; background: var(--bg-elev-1); }
  .cm-head { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .cm-title { font-weight: 700; font-size: 13.5px; flex: 1; min-width: 0; }
  .cm-match { background: var(--success-soft); color: var(--success); padding: 1px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.3px; }
  .cm-pushed { background: var(--teal-soft); color: var(--teal); padding: 1px 7px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.3px; }
  .cm-batch { font-size: 11.5px; color: var(--text-secondary); font-family: var(--font-mono); }
  .cm-skills { display: flex; gap: 4px; flex-wrap: wrap; }
  .cm-cta { font-size: 11px; color: var(--accent); font-weight: 700; margin-top: 4px; }

  /* applicant card */
  .applicant-card { background: var(--bg-elev-2); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 14px 16px; margin-bottom: 8px; transition: all 0.15s; }
  .applicant-card:hover { background: var(--bg-elev-3); border-color: var(--border-light); }
  .applicant-card.checked { background: linear-gradient(135deg, rgba(79,124,255,0.08), rgba(168,85,247,0.04)); border-color: var(--accent); box-shadow: 0 0 0 1px var(--accent-soft); }
  .ac-head { display: flex; align-items: center; gap: 14px; }
  .ac-check { width: 22px; height: 22px; border-radius: 6px; border: 2px solid var(--border-light); background: var(--bg-elev-3); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all 0.15s; }
  .applicant-card.checked .ac-check { background: var(--accent); border-color: var(--accent); }
  .ac-check svg { width: 14px; height: 14px; color: white; opacity: 0; }
  .applicant-card.checked .ac-check svg { opacity: 1; }
  .ac-name { font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 3px; }
  .ac-applied { font-size: 10.5px; background: var(--orange-soft); color: var(--orange); padding: 1px 8px; border-radius: 4px; font-weight: 700; letter-spacing: 0.3px; }
  .ac-meta { display: flex; gap: 12px; font-size: 11.5px; color: var(--text-secondary); flex-wrap: wrap; align-items: center; }
  .ac-links { display: none; padding-top: 12px; margin-top: 12px; border-top: 1px dashed var(--border); }
  .applicant-card.checked .ac-links { display: block; }

  /* LMS feed card (student view simulator) */
  .lms-feed-card {
    display: flex; align-items: center; gap: 14px;
    background: var(--bg-elev-2);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 12px 14px;
    margin-bottom: 6px;
    transition: all 0.15s;
  }
  .lms-feed-card:hover { background: var(--bg-elev-3); border-color: var(--border-light); }
  .pill-applied { background: var(--success-soft); color: var(--success); padding: 1px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; letter-spacing: 0.3px; }

  /* === Push mode banner (Eligible Students view) === */
  .push-banner {
    display: flex; align-items: center; gap: 14px;
    background: linear-gradient(135deg, var(--teal-soft), rgba(79,124,255,0.06));
    border: 1px solid rgba(20,184,166,0.3);
    border-radius: var(--radius-md);
    padding: 14px 18px;
    margin-bottom: 16px;
  }
  .push-banner.hidden { display: none; }
  .push-banner-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--teal); color: white; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .push-banner-icon svg { width: 22px; height: 22px; }
  .push-banner-info { flex: 1; min-width: 0; }
  .push-banner-title { font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .push-banner-title em { font-style: normal; color: var(--teal); }
  .push-banner-sub { font-size: 12px; color: var(--text-secondary); margin-top: 2px; }
  .push-banner-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

  /* push mode → strips show as cards with tick */
  .strip[data-push="on"] { padding-left: 50px; position: relative; }
  .strip[data-push="on"]::before {
    content: ''; position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    width: 22px; height: 22px; border-radius: 6px;
    border: 2px solid var(--border-light); background: var(--bg-elev-3);
    background-image: none;
  }
  .strip[data-push="on"][data-picked="1"]::before {
    background: var(--accent); border-color: var(--accent);
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3'><polyline points='20 6 9 17 4 12'/></svg>");
    background-repeat: no-repeat;
    background-position: center;
  }
  .strip[data-push="on"][data-pushed="1"] { opacity: 0.55; pointer-events: none; }
  .strip[data-push="on"][data-pushed="1"]::before {
    background: var(--teal); border-color: var(--teal);
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3'><polyline points='20 6 9 17 4 12'/></svg>");
    background-repeat: no-repeat;
    background-position: center;
  }

  /* === Applied Opening detail · pipeline tabs === */
  .pipeline-tabs { display: flex; gap: 4px; background: var(--bg-elev-2); padding: 4px; border-radius: 10px; border: 1px solid var(--border); width: fit-content; margin-bottom: 14px; flex-wrap: wrap; }
  .pipeline-tab { padding: 7px 14px; border-radius: 7px; font-size: 12.5px; font-weight: 600; color: var(--text-secondary); cursor: pointer; transition: all 0.15s; background: transparent; border: none; font-family: var(--font-body); display: inline-flex; align-items: center; gap: 6px; }
  .pipeline-tab.active { background: var(--bg-elev-3); color: var(--text-primary); box-shadow: var(--shadow-sm); }
  .pipeline-tab:hover:not(.active) { color: var(--text-primary); }
  .pipeline-tab .ptcount { font-family: var(--font-mono); font-size: 10.5px; background: var(--bg-elev-3); color: var(--text-secondary); padding: 1px 7px; border-radius: 999px; font-weight: 600; }
  .pipeline-tab.active .ptcount { background: var(--accent-soft); color: var(--accent); }

  @media (max-width: 900px) {
    .app { grid-template-columns: 1fr; }
    .sidebar { position: relative; height: auto; }
    .main { padding: 16px; }
    .search { width: 140px; }
    .link-toggle-group { grid-template-columns: repeat(2, 1fr); }
  }
</style>
</head>
<body data-role="placement">
<div class="app">

    @include('partials.sidebar', ['role' => 'placement'])

  <main class="main">

    <div class="topbar">
      <div class="page-title-wrap">
        <h1><span id="pageTitle">Dashboard</span><span class="role-chip" id="roleChip">Placement Coord</span></h1>
        <div class="crumb" id="pageCrumb">Overview of placement activity</div>
      </div>
      <div class="topbar-right">
        <div class="date-pill">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <span class="dw" id="todayDow"></span>
          <span id="todayDate"></span>
        </div>
        <div class="search-wrap">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input class="search" placeholder="Search…" />
        </div>
        <div class="user-pill">
          <div class="av">AD</div>
          <span>Admin</span>
        </div>
      </div>
    </div>

    <!-- ===== DASHBOARD ===== -->
    
        @yield('content')
    <div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="modalTitle">Add Entry</div>
      <div class="modal-close" onclick="closeModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </div>
    </div>
    <div class="modal-body" id="modalBody"></div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn btn-primary" onclick="submitModal()" id="modalSubmitBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg> Okay
      </button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Saved</span>
</div>

<script>
/* ============================================================
   CONSTANTS / HELPERS
   ============================================================ */
const todayISO = new Date().toISOString().slice(0,10);
const yesterdayISO = new Date(Date.now() - 86400000).toISOString().slice(0,10);
const tomorrowISO = new Date(Date.now() + 86400000).toISOString().slice(0,10);
const in3daysISO = new Date(Date.now() + 3*86400000).toISOString().slice(0,10);
const in14daysISO = new Date(Date.now() + 14*86400000).toISOString().slice(0,10);
const todayDisplay = new Date().toLocaleDateString('en-GB', {day:'numeric', month:'short'});
const todayLong = new Date().toLocaleDateString('en-GB', {day:'numeric', month:'long', year:'numeric'});
const todayDow = new Date().toLocaleDateString('en-US', {weekday:'short'});

const $ = s => document.querySelector(s);
const $$ = s => document.querySelectorAll(s);

function el(tag, attrs={}, ...children){
  const e = document.createElement(tag);
  for (const [k,v] of Object.entries(attrs)){
    if (k === 'class') e.className = v;
    else if (k === 'html') e.innerHTML = v;
    else if (k.startsWith('on') && typeof v === 'function') e.addEventListener(k.slice(2).toLowerCase(), v);
    else e.setAttribute(k, v);
  }
  for (const c of children){ if (c == null) continue; e.appendChild(typeof c === 'string' ? document.createTextNode(c) : c); }
  return e;
}
function initials(name){ return (name||'').split(' ').filter(Boolean).slice(0,2).map(s=>s[0]).join('').toUpperCase(); }
function toast(msg){
  $('#toastMsg').textContent = msg;
  $('#toast').classList.add('show');
  clearTimeout(window.__t);
  window.__t = setTimeout(()=>$('#toast').classList.remove('show'), 2500);
}
function escAttr(s){ return String(s||'').replace(/'/g,"\\'").replace(/"/g,'&quot;'); }
function copyText(t){
  try {
    navigator.clipboard.writeText(t);
    toast('Copied to clipboard');
  } catch(e){
    // fallback
    const ta = document.createElement('textarea');
    ta.value = t; document.body.appendChild(ta); ta.select();
    try { document.execCommand('copy'); toast('Copied to clipboard'); } catch(e2){}
    document.body.removeChild(ta);
  }
}
function progressBucket(p){
  if (p >= 80) return 'green';
  if (p >= 60) return 'orange';
  return 'red';
}
function chip(text){ return `<span class="skill-chip">${text}</span>`; }
function fmtDate(iso){
  if (!iso) return '—';
  try { return new Date(iso).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'}); }
  catch { return iso; }
}
function shortDate(iso){
  if (!iso) return '—';
  try { return new Date(iso).toLocaleDateString('en-GB', {day:'2-digit', month:'short'}); }
  catch { return iso; }
}

/* ============================================================
   TAGS
   ============================================================ */
const TAG_DEF = {
  // interview status
  'scheduled':       { cls:'tag-scheduled',    label:'Scheduled' },
  'attended':        { cls:'tag-attended',     label:'Attended' },
  'not_attended':    { cls:'tag-notattended',  label:'Not Attended' },
  'no_show':         { cls:'tag-noshow',       label:'No Show' },
  'hold':            { cls:'tag-hold',         label:'Hold' },
  'selected':        { cls:'tag-selected',     label:'Selected' },
  'rejected':        { cls:'tag-rejected',     label:'Rejected' },
  'rescheduled':     { cls:'tag-rescheduled',  label:'Re-Scheduled' },
  'on_process':      { cls:'tag-onprocess',    label:'On Process' },
  // placement / lead
  'profile_sent':    { cls:'tag-profilesent',  label:'Profile Sent' },
  'date_pending':    { cls:'tag-datepending',  label:'Date Pending' },
  'result_awaited':  { cls:'tag-resultawaited',label:'Result Awaited' },
  'not_applied':     { cls:'tag-pending',      label:'Not Applied' },
  // opening status
  'active':          { cls:'tag-active',       label:'Active' },
  'filled':          { cls:'tag-filled',       label:'Filled' },
  'closed':          { cls:'tag-closed',       label:'Closed' },
  // company lead
  'new':             { cls:'tag-new',          label:'New' },
  'contacted':       { cls:'tag-contacted',    label:'Contacted' },
  'followup':        { cls:'tag-followup',     label:'Follow-up' },
  'immediate_hiring':{ cls:'tag-immediate',    label:'Immediate Hiring' },
  'later_hiring':    { cls:'tag-later',        label:'Later Hiring' },
  'not_hiring':      { cls:'tag-nothiring',    label:'Not Hiring' },
  'hot':             { cls:'tag-hot',          label:'HOT' },
};
function tagPill(t){
  const def = TAG_DEF[t] || { cls:'tag-pending', label: t || '—' };
  return `<span class="tag ${def.cls}"><span class="dot"></span>${def.label}</span>`;
}

/* ============================================================
   DATA STORE  (rich enough to demo both workflows)
   ============================================================ */
const store = {
  // ===== COMPANY DATABASE =====
  companies: [
    { id:'C001', name:'Zoho Corp', city:'Chennai', industry:'IT / SaaS', website:'https://zoho.com', companyEmail:'careers@zoho.com',
      hr:'Karthik Iyer', hrPhone:'+91 98765 43210', hrEmail:'karthik@zoho.com', linkedin:'linkedin.com/in/karthik-iyer',
      source:'LinkedIn', dateAdded: todayISO, currentOpeningsFlag:'Yes', leadStatus:'active',
      notes:'Hiring aggressively for Q2. Prefers freshers with strong SQL.',
      callHistory:[
        { time:'09:14 AM', date: todayDisplay, dir:'in', duration:'4m 32s', outcome:'Opening Available', note:'Discussed hiring needs for Q2 — Data Analyst & SDE', owner:'Self' },
        { time:'02:30 PM', date: '20 May', dir:'out', duration:'8m 12s', outcome:'Callback', note:'Sent JD and walked through process', owner:'Self' },
      ]},
    { id:'C002', name:'Freshworks', city:'Chennai', industry:'IT / SaaS', website:'https://freshworks.com', companyEmail:'hr@freshworks.com',
      hr:'Aishwarya R', hrPhone:'+91 99876 54321', hrEmail:'aishwarya@freshworks.com', linkedin:'linkedin.com/in/aishwarya-r',
      source:'Reference', dateAdded: todayISO, currentOpeningsFlag:'Yes', leadStatus:'active',
      notes:'Two-stage interview process. First round virtual, second on-site.',
      callHistory:[{ time:'11:00 AM', date: todayDisplay, dir:'out', duration:'6m 04s', outcome:'Opening Available', note:'Confirmed interview slots for 3 candidates', owner:'Self' }]},
    { id:'C003', name:'TCS', city:'Bangalore', industry:'IT Services', website:'https://tcs.com', companyEmail:'careers@tcs.com',
      hr:'Rajeev Menon', hrPhone:'+91 90000 11111', hrEmail:'rajeev@tcs.com', linkedin:'linkedin.com/in/rajeev-menon',
      source:'Portal', dateAdded: yesterdayISO, currentOpeningsFlag:'Yes', leadStatus:'contacted',
      notes:'Bulk hiring drive. ~50 openings. Aptitude test mandatory.',
      callHistory:[{ time:'03:45 PM', date: '21 May', dir:'in', duration:'2m 18s', outcome:'Opening Available', note:'Initial outreach', owner:'Self' }]},
    { id:'C004', name:'Infosys', city:'Pune', industry:'IT Services', website:'https://infosys.com', companyEmail:'careers@infosys.com',
      hr:'Priya Sundaram', hrPhone:'+91 91234 56789', hrEmail:'priya@infosys.com', linkedin:'linkedin.com/in/priya-s',
      source:'LinkedIn', dateAdded: yesterdayISO, currentOpeningsFlag:'Yes', leadStatus:'active',
      notes:'Looking for candidates with cloud certs preferred.',
      callHistory:[]},
    { id:'C005', name:'Wipro', city:'Hyderabad', industry:'IT Services', website:'https://wipro.com', companyEmail:'careers@wipro.com',
      hr:'Manoj Verma', hrPhone:'+91 88800 11122', hrEmail:'manoj@wipro.com', linkedin:'linkedin.com/in/manoj-v',
      source:'Cold Call', dateAdded: yesterdayISO, currentOpeningsFlag:'No', leadStatus:'followup',
      notes:'Said openings will come in ~2 weeks. Follow up scheduled.',
      callHistory:[{ time:'10:00 AM', date:'20 May', dir:'out', duration:'3m 45s', outcome:'Callback', note:'No openings now. Asked to call back in 2 weeks.', owner:'Self' }]},
    { id:'C006', name:'CleverTap', city:'Mumbai', industry:'MarTech / SaaS', website:'https://clevertap.com', companyEmail:'hr@clevertap.com',
      hr:'Sandeep Iyer', hrPhone:'+91 90909 80808', hrEmail:'sandeep@clevertap.com', linkedin:'linkedin.com/in/sandeep-iyer',
      source:'LinkedIn', dateAdded: todayISO, currentOpeningsFlag:'Yes', leadStatus:'active',
      notes:'Frontend roles open. Need React + TS experience.',
      callHistory:[]},
    { id:'C007', name:'Mu Sigma', city:'Bangalore', industry:'Analytics', website:'https://mu-sigma.com', companyEmail:'careers@mu-sigma.com',
      hr:'Anjali Nair', hrPhone:'+91 95555 12121', hrEmail:'anjali@mu-sigma.com', linkedin:'',
      source:'Portal', dateAdded:'2025-05-10', currentOpeningsFlag:'No', leadStatus:'new',
      notes:'Newly added. Not yet called.', callHistory:[]},
  ],

  // ===== CURRENT OPENINGS =====
  openings: [
    { id:'O001', openingCode:'OPN-001', role:'Data Analyst', company:'Zoho Corp', companyEmail:'careers@zoho.com',
      hr:'Karthik Iyer', hrPhone:'+91 98765 43210', hrEmail:'karthik@zoho.com', location:'Chennai',
      skills:['Python','SQL','PowerBI'], qualification:'B.Tech / BCA / Any Graduate', experience:'Fresher',
      ctc:'5.5 LPA', openingsCount:5, applicationDeadline:'2025-06-15', interviewMode:'Online',
      interviewProcess:'Aptitude → Tech → HR', bond:'None', openingStatus:'active',
      dateAdded: todayISO, addedBy:'Admin', notes:'Strong SQL preferred.',
      jd:{ mode:'file', fileName:'jd-zoho-data-analyst.pdf', text:'' },
      lmsPushedTo:['Full Stack Dev__FSD-2024-B','Data Science__DS-2024-A'],
      applicants:[
        { studentId:'V001', appliedOn: yesterdayISO, status:'applied' },
        { studentId:'V002', appliedOn: yesterdayISO, status:'applied' },
        { studentId:'V006', appliedOn: todayISO, status:'applied' },
        { studentId:'V004', appliedOn: todayISO, status:'applied' },
      ],
      studentsSent:[
        { studentId:'V001', sentOn: yesterdayISO, linkType:'combined' },
        { studentId:'V002', sentOn: yesterdayISO, linkType:'combined' },
      ],
      callHistory:[
        { time:'09:14 AM', date: todayDisplay, dir:'in', duration:'4m 32s', outcome:'Opening Available', note:'JD received, role finalized', owner:'Self' },
      ]},
    { id:'O002', openingCode:'OPN-002', role:'SDE I', company:'Freshworks', companyEmail:'hr@freshworks.com',
      hr:'Aishwarya R', hrPhone:'+91 99876 54321', hrEmail:'aishwarya@freshworks.com', location:'Chennai',
      skills:['React','Node.js','MongoDB'], qualification:'B.Tech CS/IT', experience:'0-1 yr',
      ctc:'8 LPA', openingsCount:3, applicationDeadline:'2025-06-10', interviewMode:'Offline',
      interviewProcess:'Tech Round 1 → Tech Round 2 → HR', bond:'None', openingStatus:'active',
      dateAdded: todayISO, addedBy:'Admin', notes:'Strong React + Node mandatory.',
      jd:{ mode:'text', fileName:'', text:'Role: Software Development Engineer I (SDE I)\nLocation: Hyderabad\nTeam: Customer Experience Platform\n\nWhat you\'ll do:\n• Build and ship production-grade backend services in Java + Spring Boot\n• Write clean, testable code with unit + integration coverage\n• Collaborate on REST API design and SQL schema decisions\n• Participate in on-call rotation after first 6 months\n\nWho you are:\n• 0–1 year experience (freshers welcome)\n• Strong fundamentals in OOP, DSA, and at least one of Java / Kotlin / Go\n• Comfortable with Git, CI/CD basics\n• Bonus: exposure to AWS, Docker, Kafka\n\nWhat we offer:\n• 6 LPA base · health insurance · 25 leaves\n• Mentorship pairing for first 90 days\n• Onsite at Hyderabad office, hybrid after 6 months' },
      lmsPushedTo:['Full Stack Dev__FSD-2024-B'],
      applicants:[
        { studentId:'V001', appliedOn: yesterdayISO, status:'applied' },
        { studentId:'V004', appliedOn: yesterdayISO, status:'applied' },
        { studentId:'V007', appliedOn: todayISO, status:'applied' },
      ],
      studentsSent:[
        { studentId:'V001', sentOn: yesterdayISO, linkType:'combined' },
        { studentId:'V004', sentOn: yesterdayISO, linkType:'portfolio' },
      ],
      callHistory:[
        { time:'11:00 AM', date: todayDisplay, dir:'out', duration:'6m 04s', outcome:'Opening Available', note:'Confirmed slots for 3 candidates', owner:'Self' },
      ]},
    { id:'O003', openingCode:'OPN-003', role:'Frontend Developer', company:'CleverTap', companyEmail:'hr@clevertap.com',
      hr:'Sandeep Iyer', hrPhone:'+91 90909 80808', hrEmail:'sandeep@clevertap.com', location:'Mumbai',
      skills:['React','TypeScript','Tailwind'], qualification:'Any Graduate', experience:'0-1 yr',
      ctc:'7 LPA', openingsCount:2, applicationDeadline:'2025-06-20', interviewMode:'Online',
      interviewProcess:'Tech → HR', bond:'None', openingStatus:'active',
      dateAdded: todayISO, addedBy:'Admin', notes:'TypeScript essential.',
      jd:{ mode:'file', fileName:'jd-clevertap-fe.pdf', text:'' },
      lmsPushedTo:['Full Stack Dev__FSD-2024-B'],
      applicants:[
        { studentId:'V004', appliedOn: todayISO, status:'applied' },
        { studentId:'V007', appliedOn: todayISO, status:'applied' },
      ],
      studentsSent:[],
      callHistory:[]},
    { id:'O004', openingCode:'OPN-004', role:'Associate Engineer', company:'TCS', companyEmail:'careers@tcs.com',
      hr:'Rajeev Menon', hrPhone:'+91 90000 11111', hrEmail:'rajeev@tcs.com', location:'Bangalore',
      skills:['Java','Spring','MySQL'], qualification:'B.Tech / BCA / B.Sc IT', experience:'Fresher',
      ctc:'4.2 LPA', openingsCount:50, applicationDeadline:'2025-05-30', interviewMode:'Offline',
      interviewProcess:'Aptitude → Tech → HR', bond:'2 yrs', openingStatus:'active',
      dateAdded: yesterdayISO, addedBy:'Admin', notes:'Bulk hiring drive.',
      jd:{ mode:'text', fileName:'', text:'Position: Associate Engineer (TCS Ninja Hire)\nLocation: Bangalore (Whitefield campus)\n\nResponsibilities:\n• Work on enterprise data pipelines (SQL + Python)\n• Support existing batch ETL jobs and contribute to migration to Snowflake\n• Document tickets and follow Agile ceremonies\n\nMust have:\n• B.Tech / BE / MCA · CGPA 6.5+\n• Strong SQL — joins, window functions, CTEs\n• Python basics (pandas, requests)\n• Communication: English fluency required\n\nNice to have:\n• Cloud familiarity (AWS / Azure)\n• Tableau / PowerBI exposure\n\nCompensation: 3.36 LPA + variable\nBond: 1 year service agreement' },
      lmsPushedTo:['Data Science__DS-2024-A'],
      applicants:[
        { studentId:'V005', appliedOn: '2025-05-19', status:'applied' },
        { studentId:'V002', appliedOn: '2025-05-20', status:'applied' },
      ],
      studentsSent:[{ studentId:'V005', sentOn:'2025-05-20', linkType:'combined' }],
      callHistory:[]},
    { id:'O005', openingCode:'OPN-005', role:'System Engineer', company:'Infosys', companyEmail:'careers@infosys.com',
      hr:'Priya Sundaram', hrPhone:'+91 91234 56789', hrEmail:'priya@infosys.com', location:'Pune',
      skills:['Java','AWS','Docker'], qualification:'B.Tech / B.E.', experience:'Fresher',
      ctc:'4.8 LPA', openingsCount:20, applicationDeadline:'2025-05-25', interviewMode:'Walk-in',
      interviewProcess:'Aptitude → Tech → HR', bond:'1 yr', openingStatus:'filled',
      dateAdded: yesterdayISO, addedBy:'Admin', notes:'Cloud cert preferred.',
      jd:{ mode:'file', fileName:'jd-infosys-se.pdf', text:'' },
      lmsPushedTo:[],
      applicants:[{ studentId:'V004', appliedOn:'2025-05-18', status:'applied' }],
      studentsSent:[{ studentId:'V004', sentOn:'2025-05-19', linkType:'combined' }],
      callHistory:[]},
    { id:'O006', openingCode:'OPN-006', role:'QA Engineer', company:'Wipro', companyEmail:'careers@wipro.com',
      hr:'Manoj Verma', hrPhone:'+91 88800 11122', hrEmail:'manoj@wipro.com', location:'Hyderabad',
      skills:['Selenium','TestNG','Jira'], qualification:'Any Graduate', experience:'0-1 yr',
      ctc:'4.0 LPA', openingsCount:5, applicationDeadline:'2025-05-15', interviewMode:'Online',
      interviewProcess:'Tech → HR', bond:'None', openingStatus:'hold',
      dateAdded:'2025-05-08', addedBy:'Admin', notes:'On hold per client request.',
      jd:{ mode:'text', fileName:'', text:'Role: QA Test Engineer (Wipro)\nLocation: Pune\n\nKey responsibilities:\n• Design, write and execute manual + automated test cases\n• Bug reporting in JIRA with clear repro steps\n• Regression cycle ownership\n• Pair with developers for shift-left testing\n\nRequirements:\n• 0–1 year experience · B.Tech / B.Sc Computer Science\n• Solid grasp of SDLC and STLC\n• Tools: Selenium WebDriver, TestNG, Postman\n• Bonus: REST Assured, CI/CD pipelines\n\nWhat we offer:\n• 4.5 LPA · meal vouchers · health insurance\n• Training in API automation in first month\n• Bond: 6 months' },
      lmsPushedTo:[],
      applicants:[],
      studentsSent:[],
      callHistory:[]},
  ],

  // ===== ELIGIBLE STUDENTS (verified pool) =====
  eligible: [
    { id:'V001', name:'Arjun Krishnan', phone:'+91 98765 11111', email:'arjun.k@gmail.com',
      address:'12, Anna Nagar, Chennai', dob:'2002-03-14',
      course:'Full Stack Dev', batchCode:'FSD-2024-B', batchType:'Weekday', startDate:'2024-01-10', endDate:'2024-07-10',
      duration:'6 months', courseProgress:92, overallPercent:85,
      assignmentsSubmitted:'28 / 30', attendance:'94%',
      assessmentScores:[{module:'HTML/CSS', score:88},{module:'JavaScript', score:82},{module:'React', score:90},{module:'Node.js', score:84}],
      midterm:80, finalExam:88,
      projectTitle:'Bookstore E-commerce', projectTech:'MERN', projectDescription:'Full-stack bookstore with cart, payments, admin dashboard.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/arjun-k.pdf',
      portfolio:'https://portfolio.io/arjun', projectLink:'https://github.com/arjunk/bookstore',
      combinedLink:'https://portfolio.io/arjun/combined',
      placementStatus:'profile_sent', courseStatus:'Completed',
      callHistory:[
        { time:'02:45 PM', date:'Today', dir:'out', duration:'3m 50s', owner:'Self',
          note:'[Inform about Interview] Arjun briefed about tomorrow 10 AM Zoho interview. Sent meeting link via email. Will reach office by 9:45.' },
        { time:'10:15 AM', date:'Yesterday', dir:'in',  duration:'2m 12s', owner:'Self',
          note:'[Ask Interview Experience] Followed up after Freshworks Round 1. Said the DSA round was fine; HR round on Monday.' },
        { time:'05:00 PM', date:'21 May',   dir:'out', duration:'1m 40s', owner:'Anita S. (Student Coord)',
          note:'[Inform about Interview] Confirmed Freshworks SDE I interview slot for 21 May at 4 PM.' },
      ] },
    { id:'V002', name:'Divya Lakshmi', phone:'+91 98765 22222', email:'divya.l@gmail.com',
      address:'Kovaipudur, Coimbatore', dob:'2001-08-22',
      course:'Data Science', batchCode:'DS-2024-A', batchType:'Weekend', startDate:'2023-09-15', endDate:'2024-05-15',
      duration:'8 months', courseProgress:95, overallPercent:91,
      assignmentsSubmitted:'24 / 25', attendance:'97%',
      assessmentScores:[{module:'Python', score:92},{module:'SQL', score:94},{module:'ML', score:88},{module:'Stats', score:90}],
      midterm:91, finalExam:93,
      projectTitle:'Churn Prediction Model', projectTech:'Python, scikit-learn', projectDescription:'Telecom churn prediction with 89% AUC.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/divya-l.pdf',
      portfolio:'https://portfolio.io/divya', projectLink:'https://github.com/divyal/churn-pred',
      combinedLink:'https://portfolio.io/divya/combined',
      placementStatus:'profile_sent', courseStatus:'Completed 1 month ago',
      callHistory:[
        { time:'11:30 AM', date:'Today', dir:'out', duration:'4m 18s', owner:'Self',
          note:'[Ask Interview Experience] Asked about Zoho Data Analyst round. Said the SQL test was tough but cleared 4 of 5; coding round tomorrow.' },
        { time:'09:00 AM', date:'Yesterday', dir:'out', duration:'1m 55s', owner:'Kumar V. (Student Coord)',
          note:'[Inform about Interview] Reminded Divya about Zoho interview today at 11 AM. Confirmed attendance.' },
      ] },
    { id:'V003', name:'Mohammed Faisal', phone:'+91 98765 33333', email:'faisal.m@gmail.com',
      address:'Adyar, Chennai', dob:'2000-11-05',
      course:'Cloud DevOps', batchCode:'CD-2024-C', batchType:'Weekday', startDate:'2024-02-01', endDate:'2024-07-01',
      duration:'5 months', courseProgress:78, overallPercent:74,
      assignmentsSubmitted:'18 / 22', attendance:'82%',
      assessmentScores:[{module:'Linux', score:76},{module:'AWS', score:72},{module:'Docker', score:78},{module:'Kubernetes', score:70}],
      midterm:72, finalExam:76,
      projectTitle:'CI/CD Pipeline', projectTech:'Jenkins, AWS, Docker', projectDescription:'End-to-end CI/CD with blue-green deploy.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/faisal-m.pdf',
      portfolio:'https://portfolio.io/faisal', projectLink:'https://github.com/faisalm/cicd-demo',
      combinedLink:'https://portfolio.io/faisal/combined',
      placementStatus:'not_applied', courseStatus:'Completed 2 months ago',
      callHistory:[
        { time:'03:20 PM', date:'Yesterday', dir:'out', duration:'5m 32s', owner:'Self',
          note:'[Other] Career counseling call. Faisal is hesitant about cloud roles in Chennai — wants Bangalore. Suggested to broaden location preference; agreed to apply to 3 Pune openings this week.' },
        { time:'10:00 AM', date:'21 May',   dir:'in',  duration:'2m 05s', owner:'Self',
          note:'[Inform about Interview] Faisal called asking when CleverTap interview will be confirmed. Told him HR scheduled for next week; will share link by Friday.' },
      ] },
    { id:'V004', name:'Sneha Raghavan', phone:'+91 98765 44444', email:'sneha.r@gmail.com',
      address:'Velachery, Chennai', dob:'2001-06-18',
      course:'Full Stack Dev', batchCode:'FSD-2024-B', batchType:'Weekday', startDate:'2024-01-10', endDate:'2024-07-10',
      duration:'6 months', courseProgress:88, overallPercent:82,
      assignmentsSubmitted:'27 / 30', attendance:'91%',
      assessmentScores:[{module:'HTML/CSS', score:85},{module:'JavaScript', score:80},{module:'React', score:84},{module:'Node.js', score:82}],
      midterm:78, finalExam:84,
      projectTitle:'Task Manager App', projectTech:'MERN', projectDescription:'Kanban-style task manager with auth.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/sneha-r.pdf',
      portfolio:'https://portfolio.io/sneha', projectLink:'https://github.com/snehar/task-mgr',
      combinedLink:'https://portfolio.io/sneha/combined',
      placementStatus:'selected', courseStatus:'Completed',
      callHistory:[
        { time:'11:00 AM', date:'Today', dir:'out', duration:'6m 12s', owner:'Self', note:'[Ask Interview Experience] Asked about Infosys interview. Tech round on Java + Spring; HR friendly. Felt confident.' },
        { time:'04:30 PM', date:'Yesterday', dir:'out', duration:'2m 15s', owner:'Anita S. (Student Coord)', note:'[Inform about Interview] Reminded about today 10 AM interview at Infosys. Confirmed she will attend.' },
      ] },
    { id:'V005', name:'Vikram Singh', phone:'+91 98765 55555', email:'vikram.s@gmail.com',
      address:'HSR Layout, Bangalore', dob:'2000-09-30',
      course:'Data Science', batchCode:'DS-2024-A', batchType:'Weekend', startDate:'2023-09-15', endDate:'2024-05-15',
      duration:'8 months', courseProgress:65, overallPercent:62,
      assignmentsSubmitted:'19 / 25', attendance:'74%',
      assessmentScores:[{module:'Python', score:64},{module:'SQL', score:58},{module:'ML', score:62},{module:'Stats', score:66}],
      midterm:58, finalExam:64,
      projectTitle:'Sales Forecasting', projectTech:'Python, Prophet', projectDescription:'Time-series sales forecast.', projectStatus:'In Progress',
      resume:'https://files.placementpro.io/resumes/vikram-s.pdf',
      portfolio:'https://portfolio.io/vikram', projectLink:'https://github.com/vikrams/sales-fcst',
      combinedLink:'https://portfolio.io/vikram/combined',
      placementStatus:'rejected', courseStatus:'Completed 3 months ago',
      callHistory:[
        { time:'02:15 PM', date:'Today', dir:'out', duration:'4m 38s', owner:'Anita S. (Student Coord)',
          note:'[Ask Interview Experience] Discussed TCS rejection. Vikram said SQL window functions tripped him up. Suggested 2 weeks practice + portfolio rework before re-applying.' },
        { time:'08:30 AM', date:'21 May',   dir:'out', duration:'1m 22s', owner:'Self',
          note:'[Inform about Interview] Reminded about TCS Data Engineer interview today at 11 AM. Confirmed Vikram will join from home.' },
        { time:'04:00 PM', date:'18 May',   dir:'in',  duration:'3m 10s', owner:'Self',
          note:'[Other] Vikram asked if he can re-take the SQL assessment. Approved retake on Saturday.' },
      ] },
    { id:'V006', name:'Priya Nair', phone:'+91 98400 12345', email:'priya@gmail.com',
      address:'T. Nagar, Chennai', dob:'2002-04-25',
      course:'Data Science', batchCode:'DS-2025-A', batchType:'Weekday', startDate:'2024-11-01', endDate:'2025-05-01',
      duration:'6 months', courseProgress:85, overallPercent:79,
      assignmentsSubmitted:'22 / 25', attendance:'89%',
      assessmentScores:[{module:'Python', score:82},{module:'SQL', score:76},{module:'ML', score:80}],
      midterm:75, finalExam:82,
      projectTitle:'Customer Segmentation', projectTech:'Python, scikit-learn', projectDescription:'K-means based RFM segmentation.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/priya-n.pdf',
      portfolio:'https://portfolio.io/priya', projectLink:'https://github.com/priyan/segment',
      combinedLink:'https://portfolio.io/priya/combined',
      placementStatus:'profile_sent', courseStatus:'Completed' },
    { id:'V007', name:'Karthik Reddy', phone:'+91 98765 66666', email:'karthik.r@gmail.com',
      address:'Indiranagar, Bangalore', dob:'2001-12-12',
      course:'Full Stack Dev', batchCode:'FSD-2024-B', batchType:'Weekday', startDate:'2024-01-10', endDate:'2024-07-10',
      duration:'6 months', courseProgress:73, overallPercent:70,
      assignmentsSubmitted:'22 / 30', attendance:'78%',
      assessmentScores:[{module:'HTML/CSS', score:72},{module:'JavaScript', score:68},{module:'React', score:74}],
      midterm:65, finalExam:72,
      projectTitle:'Recipe Sharing App', projectTech:'MERN', projectDescription:'Recipe app with social features.', projectStatus:'Completed',
      resume:'https://files.placementpro.io/resumes/karthik-r.pdf',
      portfolio:'https://portfolio.io/karthik', projectLink:'https://github.com/karthikr/recipes',
      combinedLink:'https://portfolio.io/karthik/combined',
      placementStatus:'profile_sent', courseStatus:'Completed' },
  ],

  // ===== INTERVIEWS / STUDENT INTERVIEW RECORDS =====
  interviews: [
    { id:'S001', studentId:'V001', name:'Arjun Krishnan', phone:'+91 98765 11111', email:'arjun.k@gmail.com',
      course:'Full Stack Dev', duration:'6 months', batchType:'Weekday', batchCode:'FSD-2024-B',
      count:2, company:'Zoho Corp', openingId:'O001', location:'Chennai', role:'Data Analyst',
      tag:'scheduled', date: todayISO, time:'10:00 AM',
      interviewMode:'Online', meetingLink:'https://meet.zoho.com/abc-defg-hij',
      hrContactDay:'+91 98765 43210', notes:'Bring updated resume in PDF.',
      timeline:[
        { company:'TCS', role:'Associate Engineer', date:'18 May 2025', result:'rejected', reason:'Lacked Java OOP depth' },
        { company:'Zoho Corp', role:'Data Analyst', date: todayDisplay, result:'scheduled', reason:'Awaiting interview' },
      ],
      callHistory:[{ time:'10:15 AM', date: todayDisplay, dir:'out', duration:'3m 12s', note:'Briefed candidate on JD and prep tips', owner:'Self' }]
    },
    { id:'S002', studentId:'V002', name:'Divya Lakshmi', phone:'+91 98765 22222', email:'divya.l@gmail.com',
      course:'Data Science', duration:'8 months', batchType:'Weekend', batchCode:'DS-2024-A',
      count:3, company:'Freshworks', openingId:'O002', location:'Chennai', role:'SDE I',
      tag:'scheduled', date: todayISO, time:'02:00 PM',
      interviewMode:'Offline', meetingLink:'', hrContactDay:'+91 99876 54321', notes:'Carry govt ID.',
      timeline:[
        { company:'Mu Sigma', role:'DS Trainee', date:'12 May 2025', result:'rejected', reason:'Communication weak' },
        { company:'TCS', role:'Data Engg', date:'17 May 2025', result:'rescheduled', reason:'HR unavailable' },
        { company:'Freshworks', role:'SDE I', date: todayDisplay, result:'scheduled', reason:'Slot confirmed 2pm' },
      ],
      callHistory:[]
    },
    { id:'S003', studentId:'V003', name:'Mohammed Faisal', phone:'+91 98765 33333', email:'faisal.m@gmail.com',
      course:'Cloud DevOps', duration:'5 months', batchType:'Weekday', batchCode:'CD-2024-C',
      count:1, company:'CleverTap', openingId:'O003', location:'Mumbai', role:'Frontend Developer',
      tag:'rescheduled', date: tomorrowISO, time:'11:30 AM',
      interviewMode:'Online', meetingLink:'https://meet.google.com/xyz', hrContactDay:'+91 90909 80808', notes:'',
      timeline:[{ company:'CleverTap', role:'Frontend Developer', date: todayDisplay, result:'rescheduled', reason:'Asked to move to next day' }],
      callHistory:[]
    },
    { id:'S004', studentId:'V004', name:'Sneha Raghavan', phone:'+91 98765 44444', email:'sneha.r@gmail.com',
      course:'Full Stack Dev', duration:'6 months', batchType:'Weekday', batchCode:'FSD-2024-B',
      count:4, company:'Infosys', openingId:'O005', location:'Pune', role:'System Engineer',
      tag:'selected', date: yesterdayISO, time:'03:00 PM',
      interviewMode:'Walk-in', meetingLink:'', hrContactDay:'+91 91234 56789', notes:'Offer letter under processing.',
      timeline:[
        { company:'Wipro', role:'QA', date:'05 May 2025', result:'rejected', reason:'Did not clear aptitude' },
        { company:'TCS', role:'AE', date:'10 May 2025', result:'rejected', reason:'Tech round weak' },
        { company:'Cognizant', role:'PA', date:'15 May 2025', result:'rejected', reason:'Salary mismatch' },
        { company:'Infosys', role:'System Engineer', date:'21 May 2025', result:'selected', reason:'Strong fit, good communication' },
      ],
      callHistory:[]
    },
    { id:'S005', studentId:'V005', name:'Vikram Singh', phone:'+91 98765 55555', email:'vikram.s@gmail.com',
      course:'Data Science', duration:'8 months', batchType:'Weekend', batchCode:'DS-2024-A',
      count:2, company:'TCS', openingId:'O004', location:'Bangalore', role:'Data Engineer',
      tag:'rejected', date: yesterdayISO, time:'09:00 AM',
      interviewMode:'Offline', meetingLink:'', hrContactDay:'+91 90000 11111', notes:'',
      timeline:[
        { company:'Mu Sigma', role:'Analyst', date:'08 May 2025', result:'rejected', reason:'JD mismatch' },
        { company:'TCS', role:'Data Engineer', date:'21 May 2025', result:'rejected', reason:'Lacked production SQL experience' },
      ],
      callHistory:[]
    },
    { id:'S006', studentId:'V006', name:'Priya Nair', phone:'+91 98400 12345', email:'priya@gmail.com',
      course:'Data Science', duration:'6 months', batchType:'Weekday', batchCode:'DS-2025-A',
      count:1, company:'Zoho Corp', openingId:'O001', location:'Chennai', role:'Data Analyst',
      tag:'scheduled', date: todayISO, time:'04:00 PM',
      interviewMode:'Online', meetingLink:'https://meet.zoho.com/pri-prq-xyz', hrContactDay:'+91 98765 43210', notes:'Wants demo first.',
      timeline:[{ company:'Zoho Corp', role:'Data Analyst', date: todayDisplay, result:'scheduled', reason:'Wants demo this week' }],
      callHistory:[{ time:'09:14 AM', date: todayDisplay, dir:'in', duration:'4m 32s', note:'Interested in data science course, wants demo this week', owner:'Self' }]
    },
    { id:'S007', studentId:'V007', name:'Karthik Reddy', phone:'+91 98765 66666', email:'karthik.r@gmail.com',
      course:'Full Stack Dev', duration:'6 months', batchType:'Weekday', batchCode:'FSD-2024-B',
      count:2, company:'CleverTap', openingId:'O003', location:'Mumbai', role:'Frontend Developer',
      tag:'hold', date: todayISO, time:'12:00 PM',
      interviewMode:'Online', meetingLink:'https://meet.google.com/abc', hrContactDay:'+91 90909 80808', notes:'Cleared R1, awaiting R2 result.',
      timeline:[{ company:'CleverTap', role:'Frontend Developer', date: todayDisplay, result:'hold', reason:'Cleared round 1, awaiting round 2 result' }],
      callHistory:[]
    },
    { id:'S008', studentId:'V003', name:'Mohammed Faisal', phone:'+91 98765 33333', email:'faisal.m@gmail.com',
      course:'Cloud DevOps', duration:'5 months', batchType:'Weekday', batchCode:'CD-2024-C',
      count:1, company:'Wipro', openingId:'O006', location:'Hyderabad', role:'QA Engineer',
      tag:'not_attended', date: yesterdayISO, time:'10:00 AM',
      interviewMode:'Online', meetingLink:'', hrContactDay:'+91 88800 11122', notes:'Did not show up. Called student — sick.',
      timeline:[{ company:'Wipro', role:'QA Engineer', date:'21 May 2025', result:'not_attended', reason:'Was unwell — provided medical certificate; rescheduling requested.' }],
      callHistory:[]
    },
  ],

  // ===== FOLLOW-UPS =====
  followupsStudent: [
    { id:'FS001', studentId:'V001', name:'Arjun Krishnan', phone:'+91 98765 11111', email:'arjun.k@gmail.com',
      course:'Full Stack Dev', batchCode:'FSD-2024-B', company:'Zoho Corp',
      interviewDate: todayISO, purpose:'inform', purposeLabel:'Inform about Interview',
      callDate: yesterdayISO, callTime:'04:30 PM', dir:'out', duration:'2m 45s',
      status:'—', assignedTo:'Self', followUpDate: tomorrowISO,
      reason:'Called to remind about tomorrow 10 AM interview at Zoho. Confirmed he\'ll be on time. Shared meeting link via email.' },
    { id:'FS002', studentId:'V004', name:'Sneha Raghavan', phone:'+91 98765 44444', email:'sneha.r@gmail.com',
      course:'Full Stack Dev', batchCode:'FSD-2024-B', company:'Infosys',
      interviewDate:'2025-05-21', purpose:'experience', purposeLabel:'Ask Interview Experience',
      callDate: todayISO, callTime:'11:00 AM', dir:'out', duration:'6m 12s',
      status:'selected', assignedTo:'Self', followUpDate: in3daysISO,
      reason:'Asked about Infosys interview experience. She said tech round was on Java + Spring; HR was friendly. Felt confident. Updated tag to Selected after HR confirmation.' },
    { id:'FS003', studentId:'V005', name:'Vikram Singh', phone:'+91 98765 55555', email:'vikram.s@gmail.com',
      course:'Data Science', batchCode:'DS-2024-A', company:'TCS',
      interviewDate:'2025-05-21', purpose:'experience', purposeLabel:'Ask Interview Experience',
      callDate: todayISO, callTime:'02:15 PM', dir:'out', duration:'4m 38s',
      status:'rejected', assignedTo:'Anita S. (Student Coord)', followUpDate: in3daysISO,
      reason:'Discussed TCS rejection. Vikram said SQL window functions tripped him up. Suggested portfolio rework + 2 weeks practice.' },
  ],
  followupsCompany: [
    { id:'FC001', company:'Infosys', hr:'Priya Sundaram', hrPhone:'+91 91234 56789', hrEmail:'priya@infosys.com',
      location:'Pune', role:'System Engineer', student:'Sneha Raghavan',
      purpose:'status', purposeLabel:'Ask Interview Status',
      callDate: todayISO, callTime:'09:45 AM', dir:'out', duration:'3m 20s',
      status:'Selected', assignedTo:'Self', followUpDate: in3daysISO,
      reason:'Called HR for Sneha\'s interview result. HR confirmed selection; offer letter under processing. Will share by Monday.' },
    { id:'FC002', company:'CleverTap', hr:'Sandeep Iyer', hrPhone:'+91 90909 80808', hrEmail:'sandeep@clevertap.com',
      location:'Mumbai', role:'Frontend Developer', student:'Mohammed Faisal',
      purpose:'date', purposeLabel:'Ask Interview Date',
      callDate: yesterdayISO, callTime:'10:30 AM', dir:'out', duration:'5m 10s',
      status:'Date Confirmed', assignedTo:'Self', followUpDate: tomorrowISO,
      reason:'Called HR to finalize date for Faisal\'s interview. Confirmed tomorrow 11:30 AM, online. Meeting link sent.' },
    { id:'FC003', company:'TCS', hr:'Rajeev Menon', hrPhone:'+91 90000 11111', hrEmail:'rajeev@tcs.com',
      location:'Bangalore', role:'Data Engineer', student:'Vikram Singh',
      purpose:'status', purposeLabel:'Ask Interview Status',
      callDate: todayISO, callTime:'01:00 PM', dir:'out', duration:'4m 02s',
      status:'Rejected', assignedTo:'Kumar V. (Student Coord)', followUpDate: in3daysISO,
      reason:'Called HR for Vikram\'s TCS result. Rejected on tech round (SQL depth). HR suggested re-apply after 6 months.' },
  ],
  // pending leads — assigned-to-self with future follow-up (from "Case 2" workflow in both docs)
  leadsPending: [
    { id:'L001', type:'company-future', entity:'Wipro', hr:'Manoj Verma', hrPhone:'+91 88800 11122',
      reason:'HR said openings expected in ~2 weeks. Set follow-up.', leadStatus:'followup',
      assignedTo:'Self', followUpDate: in14daysISO, addedOn:'20 May 2025' },
    { id:'L002', type:'company-result', entity:'CleverTap', hr:'Sandeep Iyer', hrPhone:'+91 90909 80808',
      reason:'Interview done for Karthik Reddy. R2 result pending from HR.', leadStatus:'result_awaited',
      assignedTo:'Self', followUpDate: in3daysISO, addedOn: todayDisplay },
    { id:'L003', type:'student-result', entity:'Mohammed Faisal', hr:'Sandeep Iyer (Wipro)', hrPhone:'+91 88800 11122',
      reason:'Did not attend Wipro interview. Reason: medical. Re-schedule requested.', leadStatus:'rescheduled',
      assignedTo:'Self', followUpDate: tomorrowISO, addedOn: todayDisplay },
  ],

  // mailchimp send log (simulated)
  mailchimpLog: [],

  // LMS push log (Student Coord pushes openings to course LMS feeds)
  lmsPushLog: [
    { id:'LMS001', date: yesterdayISO, course:'Full Stack Dev', batchCode:'FSD-2024-B',
      openingIds:['O001','O002','O003'], studentCount:3, pushedBy:'Self' },
    { id:'LMS002', date: yesterdayISO, course:'Data Science', batchCode:'DS-2024-A',
      openingIds:['O001','O004'], studentCount:2, pushedBy:'Anita S. (Student Coord)' },
  ],

  // placement-coord company hiring follow-ups (immediate/later/not hiring)
  hiringFollowups: [
    { id:'H001', company:'Zoho Corp', hr:'Karthik Iyer', hrPhone:'+91 98765 43210', location:'Chennai',
      hiringStatus:'immediate_hiring', assignedTo:'Self', followUpDate: tomorrowISO,
      reason:'Confirmed Q2 hiring for Data Analyst & SDE. Follow up tomorrow for opening details.', addedOn: todayDisplay },
    { id:'H002', company:'Wipro', hr:'Manoj Verma', hrPhone:'+91 88800 11122', location:'Hyderabad',
      hiringStatus:'later_hiring', assignedTo:'Priya M. (Placement Coord)', followUpDate: in14daysISO,
      reason:'No openings now. HR said openings expected in ~2 weeks. Call back then.', addedOn:'20 May' },
    { id:'H003', company:'Mu Sigma', hr:'Anjali Nair', hrPhone:'+91 95555 12121', location:'Bangalore',
      hiringStatus:'later_hiring', assignedTo:'Rajesh K. (Placement Coord)', followUpDate: in14daysISO,
      reason:'Newly added. Reached out — they said hiring is paused this quarter, try after July.', addedOn:'19 May' },
    { id:'H004', company:'TCS', hr:'Rajeev Menon', hrPhone:'+91 90000 11111', location:'Bangalore',
      hiringStatus:'immediate_hiring', assignedTo:'Suresh R. (Placement Head)', followUpDate: in3daysISO,
      reason:'Bulk hiring drive ongoing. ~50 openings. Confirmed JD; need to send batch profiles.', addedOn:'21 May' },
    { id:'H005', company:'Infosys', hr:'Priya Sundaram', hrPhone:'+91 91234 56789', location:'Pune',
      hiringStatus:'not_hiring', assignedTo:'Self', followUpDate: in14daysISO,
      reason:'Closed current opening (Sneha placed). No new openings this month. Check back next month.', addedOn: todayDisplay },
  ],
};

/* ============================================================
   NAVIGATION + ROLE
   ============================================================ */
const pageInfo = {
  dashboard:   { title:'Dashboard', crumb:'Overview of placement activity' },
  companies:   { title:'Company Database', crumb:'Master list of all employer partners' },
  openings:    { title:'Current Openings', crumb:'Active vacancies being worked on' },
  eligible:    { title:'Eligible Students', crumb:'Students cleared for placement outreach' },
  applied:     { title:'Applied Openings', crumb:'Openings where students have applied — forward profiles to HR' },
  interviews:  { title:'Interviews', crumb:'All scheduled and tracked interviews' },
  status:      { title:'Interview Status', crumb:'Per-student & per-company interview tracking' },
  followups:   { title:'Follow-ups / Lead Management', crumb:'Post-interview status & pending follow-ups' },
};

let currentRole = 'placement';
function setRole(role){
  currentRole = role;
  document.body.setAttribute('data-role', role);
  $$('.role-btn').forEach(b => b.classList.toggle('active', b.dataset.roleSet === role));
  $('#roleChip').textContent = role === 'placement' ? 'Placement Coord' : 'Student Coord';
  // if current view is hidden in this role, fall back to dashboard
  const active = $('.nav-item.active');
  if (active && active.dataset.show && active.dataset.show !== role){
    showView('dashboard');
  }
  renderDashboard();
  // follow-ups view changes shape per role — re-render
  if (typeof renderFollowups === 'function') renderFollowups();
  if (typeof refreshNavCounts === 'function') refreshNavCounts();
}
$$('.role-btn').forEach(b => b.addEventListener('click', ()=> setRole(b.dataset.roleSet)));

function showView(v){
  $$('.view').forEach(s => s.classList.add('hidden'));
  $('#view-'+v).classList.remove('hidden');
  $$('.nav-item[data-view]').forEach(n => n.classList.toggle('active', n.dataset.view===v));
  $('#pageTitle').textContent = pageInfo[v].title;
  $('#pageCrumb').textContent = pageInfo[v].crumb;
  window.scrollTo({top:0, behavior:'smooth'});
}
$$('.nav-item[data-view]').forEach(n => n.addEventListener('click', ()=> showView(n.dataset.view)));

let lastView = 'dashboard';
const origShow = showView;
showView = function(v){ if (v !== 'detail') lastView = v; origShow(v); };
function back(){ showView(lastView); }

/* ─── Auto-navigate via query param: ?tab=eligible etc. ─── */
(function(){
  const params = new URLSearchParams(window.location.search);
  const tab = params.get('tab');
  if (tab && pageInfo[tab]) {
    showView(tab);
    // If redirected from student coordinator side, switch to student role view
    if (params.get('opening')) {
      // Highlight coming from an opening push — could be used for push mode
      console.info('[Placement] Redirected from opening:', params.get('opening'));
    }
  }
})();

/* ============================================================
   STUDENT NAME LINK (clickable everywhere)
   ============================================================ */
function nameLink(name){
  return `<span class="name-link" onclick="event.stopPropagation(); openStudentByName('${escAttr(name)}')">${name}</span>`;
}
function openStudentByName(name){
  // prefer the unified Eligible profile (with full project, links, call history, interview timeline)
  const v = store.eligible.find(x => x.name.toLowerCase() === name.toLowerCase());
  if (v) return openEligibleDetail(v.id);
  // fall back to interview detail if the student isn't in eligible list
  const s = store.interviews.find(x => x.name.toLowerCase() === name.toLowerCase());
  if (s) return openInterviewDetail(s.id);
  toast(`No detail for "${name}"`);
}

/* ============================================================
   TAB HANDLING
   ============================================================ */
// follow-up tabs
$$('.tab[data-subtab]').forEach(t => {
  t.addEventListener('click', ()=>{
    t.parentElement.querySelectorAll('.tab').forEach(s=>s.classList.remove('active'));
    t.classList.add('active');
    const k = t.dataset.subtab;
    $('#fuStudentWrap').classList.toggle('hidden', k !== 'fu-student');
    $('#fuCompanyWrap').classList.toggle('hidden', k !== 'fu-company');
    if (k === 'fu-student') $('#folEntryBtn').setAttribute('onclick',"openModal('followup-student')");
    if (k === 'fu-company') $('#folEntryBtn').setAttribute('onclick',"openModal('followup-company')");
  });
});

// openings tabs
let openingTab = 'today';
$$('.tab[data-opentab]').forEach(t => {
  t.addEventListener('click', ()=>{
    t.parentElement.querySelectorAll('.tab').forEach(s=>s.classList.remove('active'));
    t.classList.add('active');
    openingTab = t.dataset.opentab;
    renderOpenings();
  });
});

// interviews — tabs removed; status is a filter now. Keep var for safety but unused.
let intvTab = 'all';

/* ============================================================
   ASSIGNEES (role-wise) — used by all assignment modals
   ============================================================ */
const ASSIGNEES = [
  'Self',
  'Priya M. (Placement Coord)',
  'Rajesh K. (Placement Coord)',
  'Anita S. (Student Coord)',
  'Kumar V. (Student Coord)',
  'Suresh R. (Placement Head)',
  'Admin Team',
];

/* ============================================================
   MODAL SYSTEM
   ============================================================ */
let modalCtx = null;
let modalPrefill = null;

const modalSchemas = {
  company: {
    title: 'New Company Entry',
    fields: [
      { name:'name',         label:'Company Name', type:'text', full:true, required:true },
      { name:'city',         label:'City / Location', type:'text', required:true },
      { name:'industry',     label:'Industry / Sector', type:'text', required:true, placeholder:'IT, Finance, Healthcare…' },
      { name:'website',      label:'Company Website', type:'url' },
      { name:'companyEmail', label:'Company Mail ID', type:'email', required:true },
      { name:'hr',           label:'HR Name', type:'text', required:true },
      { name:'hrPhone',      label:'HR Contact Number', type:'tel', required:true },
      { name:'hrEmail',      label:'HR Email ID', type:'email', required:true },
      { name:'linkedin',     label:'LinkedIn / Social URL', type:'text' },
      { name:'source',       label:'Source', type:'select', options:['LinkedIn','Reference','Portal','Cold Call','Walk-in','Other'], required:true },
      { name:'currentOpeningsFlag', label:'Current Openings?', type:'select', options:['Yes','No'], required:true },
      { name:'leadStatus',   label:'Lead Status', type:'select', options:['new','contacted','followup','active','closed'], required:true, value:'new' },
      { name:'notes',        label:'Notes', type:'textarea', full:true, placeholder:'Any context, hiring process, preferences…' },
    ],
    onSubmit(d){
      const id = 'C' + String(store.companies.length+1).padStart(3,'0');
      store.companies.unshift({ id, ...d, dateAdded: todayISO, callHistory:[] });
      renderCompanies(); refreshNavCounts(); toast('Company added');
      // Case: openings flag Yes → guide to add opening
      if (d.currentOpeningsFlag === 'Yes'){
        setTimeout(()=>{
          if (confirm(`"${d.name}" has openings. Add the opening now?`)){
            openModal('opening', { company: d.name, companyEmail: d.companyEmail, hr: d.hr, hrPhone: d.hrPhone, hrEmail: d.hrEmail, location: d.city, __lock:['company','companyEmail','hr','hrPhone','hrEmail'] });
          }
        }, 400);
      } else {
        // Case 2 of Module 1: no current opening → auto-create lead pending follow-up
        const lid = 'L' + String(store.leadsPending.length+1).padStart(3,'0');
        store.leadsPending.unshift({ id:lid, type:'company-future', entity:d.name, hr:d.hr, hrPhone:d.hrPhone, reason:'No current opening. Follow-up to check later.', leadStatus:'followup', assignedTo:'Self', followUpDate: in14daysISO, addedOn: todayDisplay });
        renderFollowups(); refreshNavCounts();
        toast('Lead set to follow-up (2 weeks)');
      }
    }
  },
  opening: {
    title: 'New Opening Entry',
    fields: [
      { name:'company',      label:'Company Name', type:'text', full:true, required:true },
      { name:'role',         label:'Job Role / Designation', type:'text', required:true },
      { name:'companyEmail', label:'Company Mail ID', type:'email', required:true },
      { name:'hr',           label:'HR Name', type:'text', required:true },
      { name:'hrPhone',      label:'HR Contact Number', type:'tel', required:true },
      { name:'hrEmail',      label:'HR Email ID', type:'email', required:true },
      { name:'location',     label:'Job Location', type:'text', required:true },
      { name:'skills',       label:'Skills Required (comma sep.)', type:'text', full:true },
      { name:'qualification',label:'Minimum Qualification', type:'text', required:true, placeholder:'B.Tech / BCA / Any Graduate' },
      { name:'experience',   label:'Experience Required', type:'select', options:['Fresher','0-1 yr','1-2 yrs','2-3 yrs','3-5 yrs','5+ yrs'], required:true },
      { name:'ctc',          label:'CTC / Salary', type:'text', required:true, placeholder:'e.g. 4.5 LPA' },
      { name:'openingsCount',label:'Number of Openings', type:'number', required:true, value:1 },
      { name:'applicationDeadline', label:'Application Deadline', type:'date' },
      { name:'interviewMode',label:'Interview Mode', type:'select', options:['Online','Offline','Walk-in','Hybrid'], required:true },
      { name:'interviewProcess', label:'Interview Process', type:'text', full:true, placeholder:'Aptitude → Tech → HR' },
      { name:'bond',         label:'Bond / Service Agmt', type:'text', placeholder:'e.g. 1 yr / None' },
      { name:'openingStatus',label:'Opening Status', type:'select', options:['active','hold','filled','closed'], required:true, value:'active' },
      { name:'notes',        label:'Notes / Remarks', type:'textarea', full:true },
      { name:'jd',           label:'Job Description', type:'jd-input', full:true },
    ],
    onSubmit(d){
      const id = 'O' + String(store.openings.length+1).padStart(3,'0');
      // figure out JD shape from dual-mode input
      const jdMode = d.jd_mode || 'file';
      let jdData;
      if (jdMode === 'text'){
        jdData = { mode:'text', text: d.jd_text || '', fileName: '' };
      } else {
        jdData = { mode:'file', text:'', fileName: (d.jd_file || 'jd-attached.pdf') };
      }
      store.openings.unshift({
        id, openingCode: 'OPN-' + String(store.openings.length+1).padStart(3,'0'),
        ...d, skills:(d.skills||'').split(',').map(s=>s.trim()).filter(Boolean),
        openingsCount: Number(d.openingsCount)||1,
        dateAdded: todayISO, addedBy:'Admin',
        jd: jdData,
        lmsPushedTo:[], applicants:[], studentsSent:[], callHistory:[]
      });
      renderOpenings(); renderDashboard(); refreshNavCounts();
      toast('Opening added — visible to Student Coord.');
    }
  },
  eligible: {
    title: 'New Eligible Student',
    fields: [
      { name:'name',          label:'Student Name', type:'text', full:true, required:true },
      { name:'phone',         label:'Phone', type:'tel', required:true },
      { name:'email',         label:'Email', type:'email', required:true },
      { name:'address',       label:'Address', type:'text', full:true },
      { name:'dob',           label:'Date of Birth', type:'date' },
      { name:'course',        label:'Course', type:'text', required:true },
      { name:'batchCode',     label:'Batch Code', type:'text', required:true },
      { name:'batchType',     label:'Batch Type', type:'select', options:['Weekday','Weekend','Bootcamp','Online'], required:true },
      { name:'duration',      label:'Course Duration', type:'text', required:true, placeholder:'e.g. 6 months' },
      { name:'courseProgress',label:'Course Progress (%)', type:'number', required:true, value:80 },
      { name:'overallPercent',label:'Overall %', type:'number', required:true, value:75 },
      { name:'attendance',    label:'Attendance %', type:'text', placeholder:'e.g. 92%' },
      { name:'courseStatus',  label:'Course Status', type:'select', options:['Completed','Completed 1 month ago','Completed 2 months ago','Completed 3 months ago','Completed 6 months ago','Completed > 6 months ago'], required:true },
      { name:'projectTitle',  label:'Project Title', type:'text' },
      { name:'projectTech',   label:'Project Tech Stack', type:'text' },
      { name:'projectDescription', label:'Project Description', type:'textarea', full:true },
      { name:'resume',        label:'Resume URL', type:'url' },
      { name:'portfolio',     label:'Portfolio URL', type:'url' },
      { name:'projectLink',   label:'Project / GitHub URL', type:'url' },
      { name:'combinedLink',  label:'Combined Link (Resume + Portfolio + Project)', type:'url', full:true, required:true },
    ],
    onSubmit(d){
      const id = 'V' + String(store.eligible.length+1).padStart(3,'0');
      store.eligible.unshift({
        id, ...d,
        courseProgress: Number(d.courseProgress)||0,
        overallPercent: Number(d.overallPercent)||0,
        placementStatus:'not_applied',
        assessmentScores:[], assignmentsSubmitted:'—'
      });
      renderEligible(); refreshNavCounts(); toast('Eligible student added');
    }
  },
  student: {
    title: 'New Interview Entry',
    fields: [
      { name:'name',         label:'Student Name', type:'text', full:true, required:true },
      { name:'phone',        label:'Phone Number', type:'tel', required:true },
      { name:'email',        label:'Email', type:'email', required:true },
      { name:'course',       label:'Course', type:'text', required:true },
      { name:'duration',     label:'Course Duration', type:'text', required:true },
      { name:'batchType',    label:'Batch Type', type:'select', options:['Weekday','Weekend','Bootcamp','Online'], required:true },
      { name:'batchCode',    label:'Batch Code', type:'text', required:true },
      { name:'count',        label:'Interview Count', type:'number', required:true, value:1 },
      { name:'company',      label:'Company Name', type:'text', required:true },
      { name:'location',     label:'Location', type:'text', required:true },
      { name:'role',         label:'Job Role', type:'text', required:true },
      { name:'date',         label:'Interview Date', type:'date', required:true, value: todayISO },
      { name:'time',         label:'Interview Time', type:'time', required:true, value:'10:00' },
      { name:'interviewMode',label:'Interview Mode', type:'select', options:['Online','Offline','Walk-in','Hybrid'], required:true },
      { name:'meetingLink',  label:'Meeting Link (if online)', type:'url', full:true },
      { name:'hrContactDay', label:'HR Contact for Day', type:'tel' },
      { name:'tag',          label:'Status Tag', type:'select', options:['scheduled','attended','not_attended','no_show','hold','selected','rejected','rescheduled'], required:true, value:'scheduled' },
      { name:'notes',        label:'Notes', type:'textarea', full:true },
    ],
    onSubmit(d){
      const id = 'S' + String(store.interviews.length+1).padStart(3,'0');
      const displayTime = displayTimeOf(d.time);
      store.interviews.unshift({
        id, ...d, count: Number(d.count)||1, time: displayTime,
        timeline:[{ company:d.company, role:d.role, date: shortDate(d.date), result:d.tag, reason:'Initial entry' }],
        callHistory:[]
      });
      renderInterviewStatus(); renderDashboard(); refreshNavCounts(); toast('Interview entry added');
    }
  },
  'schedule-interview': {
    title: 'Schedule Interview',
    fields: [
      { name:'name',      label:'Student Name', type:'text', full:true, required:true },
      { name:'phone',     label:'Phone Number', type:'tel', required:true },
      { name:'email',     label:'Email', type:'email', required:true },
      { name:'course',    label:'Course', type:'text', required:true },
      { name:'duration',  label:'Course Duration', type:'text', required:true },
      { name:'batchCode', label:'Batch Code', type:'text', required:true },
      { name:'batchType', label:'Batch Type', type:'select', options:['Weekday','Weekend','Bootcamp','Online'], required:true },
      { name:'company',   label:'Company Name', type:'text', required:true },
      { name:'role',      label:'Job Role', type:'text' },
      { name:'location',  label:'Location', type:'text' },
      { name:'date',      label:'Scheduled Date', type:'date', value: todayISO, required:true },
      { name:'time',      label:'Scheduled Time', type:'time', required:true, value:'10:00' },
      { name:'interviewMode', label:'Interview Mode', type:'select', options:['Online','Offline','Walk-in','Hybrid'], required:true },
      { name:'meetingLink', label:'Meeting Link (if online)', type:'url', full:true },
    ],
    submitLabel: 'Done',
    onSubmit(d){
      const displayTime = displayTimeOf(d.time);
      const id = 'S' + String(store.interviews.length+1).padStart(3,'0');
      store.interviews.unshift({
        id, name:d.name, phone:d.phone, email:d.email, course:d.course, duration:d.duration,
        batchType:d.batchType, batchCode:d.batchCode, count:1,
        company:d.company, location:d.location || '—', role:d.role || '—',
        tag:'scheduled', date:d.date, time:displayTime,
        interviewMode:d.interviewMode, meetingLink:d.meetingLink, hrContactDay:'',
        timeline:[{ company:d.company, role:d.role || '—', date: shortDate(d.date), result:'scheduled', reason:`Scheduled at ${displayTime}` }],
        callHistory:[]
      });
      renderInterviewStatus(); renderDashboard(); refreshNavCounts();
      toast('Interview scheduled successfully');
    }
  },
  'followup-student': {
    title: 'New Student Follow-up Call',
    fields: [
      { name:'name',     label:'Student Name', type:'text', full:true, required:true },
      { name:'phone',    label:'Phone', type:'tel', required:true },
      { name:'email',    label:'Email', type:'email' },
      { name:'course',   label:'Course', type:'text' },
      { name:'batchCode',label:'Batch Code', type:'text' },
      { name:'company',  label:'Company Name', type:'text', required:true },
      { name:'interviewDate', label:'Interview Date', type:'date', value: todayISO },
      { name:'purpose',  label:'Purpose of Call', type:'select', options:['inform','experience','other'], required:true, value:'inform' },
      { name:'callDate', label:'Call Date', type:'date', value: todayISO, required:true },
      { name:'callTime', label:'Call Time', type:'time', value:'10:00', required:true },
      { name:'dir',      label:'Direction', type:'select', options:['Outgoing','Incoming'], required:true, value:'Outgoing' },
      { name:'duration', label:'Call Duration', type:'text', placeholder:'e.g. 3m 12s', required:true },
      { name:'status',   label:'Update Interview Status', type:'select', options:['—','scheduled','attended','selected','rejected','hold','not_attended','rescheduled'], value:'—' },
      { name:'assignedTo', label:'Assigned To', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'followUpDate', label:'Next Follow-up Date', type:'date', value: in3daysISO },
      { name:'reason',   label:'Call Notes / Feedback', type:'textarea', full:true, required:true, placeholder:'What did the student say? Any feedback about the interview experience?' },
    ],
    onSubmit(d){
      const id = 'FS' + String(store.followupsStudent.length+1).padStart(3,'0');
      const purposeLabel = { inform:'Inform about Interview', experience:'Ask Interview Experience', other:'Other' }[d.purpose] || d.purpose;
      store.followupsStudent.unshift({ id, ...d, purposeLabel });
      const callEntry = {
        time: displayTimeOf(d.callTime),
        date: shortDate(d.callDate),
        dir: d.dir === 'Incoming' ? 'in' : 'out',
        duration: d.duration,
        owner: d.assignedTo,
        note: `[${purposeLabel}] ${d.reason}`
      };
      // log to eligible student call history (unified profile)
      const v = store.eligible.find(x => x.name.toLowerCase() === d.name.toLowerCase());
      if (v){
        v.callHistory = v.callHistory || [];
        v.callHistory.unshift(callEntry);
      }
      // also log to interview call history if there's an active interview
      const s = store.interviews.find(x => x.name.toLowerCase() === d.name.toLowerCase());
      if (s){
        s.callHistory = s.callHistory || [];
        s.callHistory.unshift(callEntry);
        if (d.status && d.status !== '—'){
          s.tag = d.status;
          s.timeline = s.timeline || [];
          s.timeline.unshift({ company:d.company, role:s.role||'-', date: shortDate(d.interviewDate||d.callDate), result:d.status, reason:d.reason });
        }
      }
      renderFollowups(); renderInterviewStatus(); refreshNavCounts();
      toast('Student follow-up logged · added to call history');
    }
  },
  'followup-company': {
    title: 'New Company Follow-up Call',
    fields: [
      { name:'company', label:'Company Name', type:'text', full:true, required:true },
      { name:'hr',      label:'HR Name', type:'text', required:true },
      { name:'hrPhone', label:'HR Number', type:'tel', required:true },
      { name:'hrEmail', label:'HR Email', type:'email' },
      { name:'location',label:'Location', type:'text' },
      { name:'role',    label:'Job Role', type:'text', required:true },
      { name:'student', label:'Student Name (for context)', type:'text' },
      { name:'purpose', label:'Purpose of Call', type:'select', options:['date','status','other'], required:true, value:'date' },
      { name:'callDate',label:'Call Date', type:'date', value: todayISO, required:true },
      { name:'callTime',label:'Call Time', type:'time', value:'10:00', required:true },
      { name:'dir',     label:'Direction', type:'select', options:['Outgoing','Incoming'], required:true, value:'Outgoing' },
      { name:'duration',label:'Call Duration', type:'text', placeholder:'e.g. 3m 12s', required:true },
      { name:'status',  label:'HR Response', type:'select', options:['—','Date Confirmed','Date Pending','Result Awaited','Selected','Rejected','Hold'], value:'—' },
      { name:'assignedTo', label:'Assigned To', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'followUpDate', label:'Next Follow-up Date', type:'date', value: in3daysISO },
      { name:'reason',  label:'Call Notes / Feedback', type:'textarea', full:true, required:true, placeholder:'What did HR say? Any decision or next step?' },
    ],
    onSubmit(d){
      const id = 'FC' + String(store.followupsCompany.length+1).padStart(3,'0');
      const purposeLabel = { date:'Ask Interview Date', status:'Ask Interview Status', other:'Other' }[d.purpose] || d.purpose;
      store.followupsCompany.unshift({ id, ...d, purposeLabel });
      // log to company call history
      const c = store.companies.find(x => x.name.toLowerCase() === d.company.toLowerCase());
      if (c){
        c.callHistory = c.callHistory || [];
        c.callHistory.unshift({
          time: displayTimeOf(d.callTime),
          date: shortDate(d.callDate),
          dir: d.dir === 'Incoming' ? 'in' : 'out',
          duration: d.duration,
          outcome: d.status !== '—' ? d.status : purposeLabel,
          owner: d.assignedTo,
          note: `[${purposeLabel}] ${d.reason}`
        });
      }
      renderFollowups(); renderCompanies(); refreshNavCounts();
      toast('Company follow-up logged · added to call history');
    }
  },
  'lead-pending': {
    title: 'New Pending Lead',
    fields: [
      { name:'type',     label:'Lead Type', type:'select', options:['company-future','company-result','student-result'], required:true },
      { name:'entity',   label:'Company / Student Name', type:'text', full:true, required:true },
      { name:'hr',       label:'HR / Contact', type:'text' },
      { name:'hrPhone',  label:'HR Phone', type:'tel' },
      { name:'leadStatus', label:'Lead Status', type:'select', options:['followup','date_pending','result_awaited','hold','rescheduled'], required:true },
      { name:'assignedTo', label:'Assigned To', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'followUpDate', label:'Follow-up Date', type:'date', value: in3daysISO, required:true },
      { name:'reason',   label:'Reason / Notes', type:'textarea', full:true, required:true },
    ],
    onSubmit(d){
      const id = 'L' + String(store.leadsPending.length+1).padStart(3,'0');
      store.leadsPending.unshift({ id, ...d, addedOn: todayDisplay });
      renderFollowups(); refreshNavCounts();
      toast('Lead added to pending list');
    }
  },
  'add-call': {
    title: 'Add Call History Entry',
    fields: [
      { name:'companyName', label:'Company / Context', type:'text', full:true, required:true },
      { name:'date',        label:'Call Date', type:'date', value: todayISO, required:true },
      { name:'time',        label:'Call Time', type:'time', value:'10:00', required:true },
      { name:'dir',         label:'Direction', type:'select', options:['Outgoing','Incoming'], required:true },
      { name:'duration',    label:'Duration', type:'text', placeholder:'e.g. 4m 32s', required:true },
      { name:'outcome',     label:'Call Outcome', type:'select', options:['Opening Available','Not Available','Callback','No Answer','Informed about Interview','Interview Experience','Other'], required:true },
      { name:'owner',       label:'Owner', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'note',        label:'Notes', type:'textarea', full:true, required:true, placeholder:'What was discussed? Next steps?' },
    ],
    submitLabel: 'Add to History',
    onSubmit(d){
      const target   = modalPrefill?.target || 'company';
      const targetId = modalPrefill?.targetId;
      const entry = {
        time: displayTimeOf(d.time), date: shortDate(d.date),
        dir: d.dir === 'Incoming' ? 'in' : 'out',
        duration: d.duration, outcome: d.outcome, note: d.note, owner: d.owner
      };
      if (target === 'opening' && targetId){
        const o = store.openings.find(x => x.id === targetId);
        if (!o){ toast('Opening not found'); return; }
        o.callHistory = o.callHistory || [];
        o.callHistory.unshift(entry);
        renderOpenings();
        toast('Call entry added to opening history');
        openOpeningDetail(targetId);
        return;
      }
      if (target === 'interview' && targetId){
        const s = store.interviews.find(x => x.id === targetId);
        if (!s){ toast('Interview record not found'); return; }
        s.callHistory = s.callHistory || [];
        s.callHistory.unshift(entry);
        toast('Call entry added to student history');
        openInterviewDetail(targetId);
        return;
      }
      if (target === 'student' && targetId){
        const v = store.eligible.find(x => x.id === targetId);
        if (!v){ toast('Student not found'); return; }
        v.callHistory = v.callHistory || [];
        v.callHistory.unshift(entry);
        toast('Call entry added to student call history');
        openEligibleDetail(targetId);
        return;
      }
      const c = store.companies.find(x => x.name === d.companyName);
      if (!c){ toast('Company not found'); return; }
      c.callHistory = c.callHistory || [];
      c.callHistory.unshift(entry);
      renderCompanies();
      toast('Call entry added to history');
      openCompanyDetail(c.name);
    }
  },
  'send-to-hr': {
    title: 'Send Applicant Profile to HR via Mailchimp',
    fields: [
      { name:'studentName', label:'Student',    type:'text', full:true, required:true },
      { name:'company',     label:'Company',    type:'text', required:true },
      { name:'role',        label:'Role',       type:'text', required:true },
      { name:'hrEmail',     label:'To (HR Email)', type:'email', full:true, required:true },
      { name:'linkType',    label:'Which Link to Send?', type:'select', options:['combined','resume','portfolio','project'], required:true, value:'combined' },
      { name:'subject',     label:'Email Subject', type:'text', full:true, required:true },
      { name:'message',     label:'Email Body',    type:'textarea', full:true, required:true,
        value:'Dear HR,\n\nPlease find attached the candidate profile for your kind review.\n\nBest regards,\nPlacement Cell' },
    ],
    submitLabel: 'Send via Mailchimp',
    onSubmit(d){
      if (!pendingSendCtx){ toast('Send context lost'); return; }
      const { oid, vid } = pendingSendCtx;
      const o = store.openings.find(x => x.id === oid);
      const v = store.eligible.find(x => x.id === vid);
      if (!o || !v){ toast('Opening or student not found'); return; }
      o.studentsSent = o.studentsSent || [];
      if (!o.studentsSent.find(s => s.studentId === vid)){
        o.studentsSent.push({ studentId: vid, sentOn: todayISO, linkType: d.linkType });
      }
      if (v.placementStatus === 'not_applied' || v.placementStatus === 'rejected'){
        v.placementStatus = 'profile_sent';
      }
      store.mailchimpLog = store.mailchimpLog || [];
      store.mailchimpLog.unshift({
        id: 'M' + (store.mailchimpLog.length+1).toString().padStart(3,'0'),
        date: todayISO, studentId: vid, openingId: oid, linkType: d.linkType,
        hr: d.hrEmail, subject: d.subject
      });
      toast(`✓ Sent ${v.name}'s ${d.linkType} link to ${d.hrEmail}`);
      pendingSendCtx = null;
      openAppliedDetail(oid);
    }
  },
  'assign-followup': {
    title: 'Assign Company for Follow-up',
    fields: [
      { name:'companyName',  label:'Company', type:'text', full:true, required:true },
      { name:'hr',           label:'HR Name', type:'text', required:true },
      { name:'hrPhone',      label:'HR Phone', type:'tel', required:true },
      { name:'location',     label:'Location', type:'text', required:true },
      { name:'hiringStatus', label:'Hiring Status', type:'select', options:['immediate_hiring','later_hiring','not_hiring'], required:true, value:'later_hiring' },
      { name:'assignedTo',   label:'Assigned To', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'followUpDate', label:'Follow-up Date', type:'date', value: in3daysISO, required:true },
      { name:'reason',       label:'Reason / Notes', type:'textarea', full:true, required:true, placeholder:'Why is this lead being assigned? What\'s the follow-up about?' },
    ],
    submitLabel: 'Assign',
    onSubmit(d){
      const id = 'H' + String((store.hiringFollowups||[]).length+1).padStart(3,'0');
      store.hiringFollowups = store.hiringFollowups || [];
      store.hiringFollowups.unshift({
        id, company: d.companyName, hr: d.hr, hrPhone: d.hrPhone, location: d.location,
        hiringStatus: d.hiringStatus, assignedTo: d.assignedTo, followUpDate: d.followUpDate,
        reason: d.reason, addedOn: todayDisplay
      });
      renderFollowups(); refreshNavCounts();
      toast(`Assigned to ${d.assignedTo} · follow-up date set`);
    }
  },
  'hiring-followup': {
    title: 'New Company Hiring Follow-up',
    fields: [
      { name:'companyName',  label:'Company', type:'text', full:true, required:true },
      { name:'hr',           label:'HR Name', type:'text', required:true },
      { name:'hrPhone',      label:'HR Phone', type:'tel', required:true },
      { name:'location',     label:'Location', type:'text', required:true },
      { name:'hiringStatus', label:'Hiring Status', type:'select', options:['immediate_hiring','later_hiring','not_hiring'], required:true },
      { name:'assignedTo',   label:'Assigned To', type:'select', options:ASSIGNEES, value:'Self', required:true },
      { name:'followUpDate', label:'Follow-up Date', type:'date', value: in3daysISO, required:true },
      { name:'reason',       label:'Reason / Notes', type:'textarea', full:true, required:true },
    ],
    onSubmit(d){
      const id = 'H' + String((store.hiringFollowups||[]).length+1).padStart(3,'0');
      store.hiringFollowups = store.hiringFollowups || [];
      store.hiringFollowups.unshift({
        id, company: d.companyName, hr: d.hr, hrPhone: d.hrPhone, location: d.location,
        hiringStatus: d.hiringStatus, assignedTo: d.assignedTo, followUpDate: d.followUpDate,
        reason: d.reason, addedOn: todayDisplay
      });
      renderFollowups(); refreshNavCounts();
      toast('Hiring follow-up added');
    }
  },
  'interview-outcome': {
    title: 'Update Interview Outcome',
    fields: [
      { name:'studentName', label:'Student', type:'text', full:true, required:true },
      { name:'companyName', label:'Company', type:'text', required:true },
      { name:'attendance',  label:'Attendance', type:'select', options:['Attended','Not Attended','No Show'], required:true },
      { name:'reason',      label:'Reason (if not attended)', type:'textarea', full:true },
      { name:'result',      label:'Result', type:'select', options:['—','selected','rejected','hold'], value:'—' },
      { name:'feedback',    label:'HR Feedback', type:'textarea', full:true },
    ],
    submitLabel:'Save',
    onSubmit(d){
      const s = store.interviews.find(x => x.id === modalPrefill?.id);
      if (!s) { toast('Interview not found'); return; }
      // map attendance to tag
      const attMap = { 'Attended':'attended', 'Not Attended':'not_attended', 'No Show':'no_show' };
      let newTag = attMap[d.attendance];
      // result overrides if present
      if (d.result && d.result !== '—') newTag = d.result;
      s.tag = newTag;
      s.timeline = s.timeline || [];
      s.timeline.unshift({ company:s.company, role:s.role, date: todayDisplay, result:newTag, reason:(d.reason || d.feedback || 'Outcome updated') });
      // if result is hold → auto-create pending lead
      if (d.result === 'hold'){
        const lid = 'L' + String(store.leadsPending.length+1).padStart(3,'0');
        store.leadsPending.unshift({
          id:lid, type:'company-result', entity:s.company, hr:s.hrContactDay||'—', hrPhone:s.hrContactDay||'—',
          reason:`Result on hold for ${s.name}. ${d.feedback||''}`,
          leadStatus:'result_awaited', assignedTo:'Self', followUpDate: in3daysISO, addedOn: todayDisplay
        });
      }
      // if not attended → create reschedule pending lead
      if (d.attendance === 'Not Attended' && d.reason){
        const lid = 'L' + String(store.leadsPending.length+1).padStart(3,'0');
        store.leadsPending.unshift({
          id:lid, type:'student-result', entity:s.name, hr:`${s.hr||'—'} (${s.company})`, hrPhone:s.hrContactDay||'—',
          reason:`Did not attend ${s.company}. Reason: ${d.reason}. Re-schedule requested.`,
          leadStatus:'rescheduled', assignedTo:'Self', followUpDate: tomorrowISO, addedOn: todayDisplay
        });
      }
      renderInterviewStatus(); renderFollowups(); renderDashboard(); refreshNavCounts();
      toast('Outcome saved');
      // re-open detail
      openInterviewDetail(s.id);
    }
  },
};

function displayTimeOf(t){
  try {
    const [h,m] = t.split(':').map(Number);
    const period = h >= 12 ? 'PM' : 'AM';
    const hh = ((h % 12) || 12).toString().padStart(2,'0');
    return `${hh}:${String(m).padStart(2,'0')} ${period}`;
  } catch(e){ return t; }
}

function openModal(key, prefill){
  modalCtx = key; modalPrefill = prefill || null;
  const s = modalSchemas[key];
  $('#modalTitle').textContent = s.title;
  const submitLbl = s.submitLabel || 'Okay';
  $('#modalSubmitBtn').innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg> ${submitLbl}`;
  const body = $('#modalBody'); body.innerHTML = '';
  const grid = el('div', { class:'form-grid' });
  s.fields.forEach(f => {
    const group = el('div', { class:'form-group ' + (f.full ? 'full' : '') });
    group.appendChild(el('label', { class:'form-label' }, f.label + (f.required ? ' *' : '')));
    let input;
    const prefilled = (modalPrefill && modalPrefill[f.name] != null) ? modalPrefill[f.name] : null;
    if (f.type === 'select'){
      input = el('select', { class:'form-select', name:f.name });
      f.options.forEach(o => {
        const opt = el('option', { value:o }, String(o).charAt(0).toUpperCase()+String(o).slice(1).replace(/_/g,' '));
        if (prefilled === o || (prefilled == null && f.value === o)) opt.setAttribute('selected','selected');
        input.appendChild(opt);
      });
    } else if (f.type === 'textarea'){
      input = el('textarea', { class:'form-textarea', name:f.name, ...(f.placeholder?{placeholder:f.placeholder}:{}) });
      if (prefilled) input.value = prefilled;
    } else if (f.type === 'file'){
      const wrap = el('label', { style:'border:1.5px dashed var(--border-light);border-radius:var(--radius-sm);padding:14px;background:var(--bg-elev-2);text-align:center;cursor:pointer;display:block;' });
      wrap.innerHTML = `
        <div style="color:var(--accent);margin-bottom:4px;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="file-text" style="font-size:12.5px;font-weight:600;">Click to attach JD file</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">PDF, DOC, DOCX — Max 5MB</div>`;
      const fi = el('input', { type:'file', name:f.name, style:'display:none;' });
      fi.addEventListener('change', () => { if (fi.files[0]) wrap.querySelector('.file-text').textContent = fi.files[0].name; });
      wrap.appendChild(fi);
      group.appendChild(wrap); grid.appendChild(group); return;
    } else if (f.type === 'jd-input'){
      // Dual-mode JD: upload PDF OR type text
      const wrap = el('div', { class:'jd-input-wrap', style:'display:flex;flex-direction:column;gap:10px;' });
      // mode tabs
      const tabs = el('div', { style:'display:flex;gap:4px;background:var(--bg-elev-2);padding:4px;border-radius:8px;border:1px solid var(--border);width:fit-content;' });
      const fileTab = el('button', { type:'button', class:'jd-tab active', 'data-mode':'file', style:'padding:7px 14px;border-radius:6px;font-size:12.5px;font-weight:600;border:none;background:var(--bg-elev-3);color:var(--text-primary);cursor:pointer;display:inline-flex;align-items:center;gap:6px;' });
      fileTab.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Upload PDF';
      const textTab = el('button', { type:'button', class:'jd-tab', 'data-mode':'text', style:'padding:7px 14px;border-radius:6px;font-size:12.5px;font-weight:600;border:none;background:transparent;color:var(--text-secondary);cursor:pointer;display:inline-flex;align-items:center;gap:6px;' });
      textTab.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg> Type Text';
      tabs.appendChild(fileTab); tabs.appendChild(textTab);
      // file panel
      const filePanel = el('label', { class:'jd-file-panel', style:'border:1.5px dashed var(--border-light);border-radius:var(--radius-sm);padding:14px;background:var(--bg-elev-2);text-align:center;cursor:pointer;display:block;' });
      filePanel.innerHTML = `
        <div style="color:var(--accent);margin-bottom:4px;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="jd-file-name" style="font-size:12.5px;font-weight:600;">Click to attach JD PDF</div>
        <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">PDF, DOC, DOCX — Max 5MB</div>`;
      const fi = el('input', { type:'file', name:f.name + '_file', accept:'.pdf,.doc,.docx', style:'display:none;' });
      fi.addEventListener('change', () => { if (fi.files[0]) filePanel.querySelector('.jd-file-name').textContent = fi.files[0].name; });
      filePanel.appendChild(fi);
      // text panel
      const textPanel = el('div', { class:'jd-text-panel', style:'display:none;' });
      const ta = el('textarea', { name:f.name + '_text', class:'form-textarea', style:'min-height:160px;font-family:var(--font-body);', placeholder:'Paste or type the full Job Description here…\n\nResponsibilities:\n• …\n\nRequirements:\n• …\n\nNice to have:\n• …' });
      textPanel.appendChild(ta);
      // hidden field tracking mode (file|text)
      const modeField = el('input', { type:'hidden', name:f.name + '_mode', value:'file' });
      // tab click handlers
      fileTab.addEventListener('click', () => {
        modeField.value = 'file';
        fileTab.classList.add('active'); fileTab.style.background='var(--bg-elev-3)'; fileTab.style.color='var(--text-primary)';
        textTab.classList.remove('active'); textTab.style.background='transparent'; textTab.style.color='var(--text-secondary)';
        filePanel.style.display='block'; textPanel.style.display='none';
      });
      textTab.addEventListener('click', () => {
        modeField.value = 'text';
        textTab.classList.add('active'); textTab.style.background='var(--bg-elev-3)'; textTab.style.color='var(--text-primary)';
        fileTab.classList.remove('active'); fileTab.style.background='transparent'; fileTab.style.color='var(--text-secondary)';
        filePanel.style.display='none'; textPanel.style.display='block';
      });
      // prefill from modalPrefill if present
      if (prefilled){
        if (typeof prefilled === 'object' && prefilled.mode){
          if (prefilled.mode === 'text'){ textTab.click(); ta.value = prefilled.text || ''; }
          else { fi.files = prefilled.file ? prefilled.file : fi.files; filePanel.querySelector('.jd-file-name').textContent = prefilled.fileName || 'Click to attach JD PDF'; }
        }
      }
      wrap.appendChild(tabs); wrap.appendChild(filePanel); wrap.appendChild(textPanel); wrap.appendChild(modeField);
      group.appendChild(wrap); grid.appendChild(group); return;
    } else {
      const v = prefilled != null ? prefilled : (f.value != null ? f.value : '');
      input = el('input', { class:'form-input', type:f.type, name:f.name, ...(f.placeholder?{placeholder:f.placeholder}:{}), value: v });
      if (modalPrefill && modalPrefill.__lock && modalPrefill.__lock.includes(f.name)){
        input.setAttribute('readonly','readonly');
      }
    }
    group.appendChild(input); grid.appendChild(group);
  });
  body.appendChild(grid);
  $('#modalOverlay').classList.add('show');
}
function closeModal(){ $('#modalOverlay').classList.remove('show'); modalCtx = null; modalPrefill = null; }
function submitModal(){
  if (!modalCtx) return;
  const s = modalSchemas[modalCtx];
  const data = {};
  let missing = null;
  s.fields.forEach(f => {
    // jd-input: collect 3 sub-fields (mode, text, file name)
    if (f.type === 'jd-input'){
      const modeEl = $(`#modalBody [name="${f.name}_mode"]`);
      const textEl = $(`#modalBody [name="${f.name}_text"]`);
      const fileEl = $(`#modalBody [name="${f.name}_file"]`);
      data[f.name + '_mode'] = modeEl ? modeEl.value : 'file';
      data[f.name + '_text'] = textEl ? textEl.value : '';
      data[f.name + '_file'] = (fileEl && fileEl.files[0]) ? fileEl.files[0].name : '';
      return;
    }
    const elx = $(`#modalBody [name="${f.name}"]`);
    if (!elx) return;
    let v = elx.type === 'file' ? (elx.files[0]?.name || '') : elx.value;
    if (f.required && !v) missing = f.label;
    data[f.name] = v;
  });
  if (missing){ toast('Please fill: ' + missing); return; }
  s.onSubmit(data); closeModal();
}
$('#modalOverlay').addEventListener('click', e => { if (e.target.id === 'modalOverlay') closeModal(); });

function emptyHtml(msg){
  return `<div class="empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><div>${msg}</div></div>`;
}

/* ============================================================
   ICONS used by dashboard stat cards
   ============================================================ */
const ICON = {
  building:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21V7l9-4 9 4v14"/><path d="M9 21V12h6v9"/></svg>`,
  briefcase:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>`,
  checkin:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>`,
  graph:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
  calendar:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>`,
  star:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
  refresh:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>`,
  cross:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
  bell:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>`,
  send:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>`,
  users:`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
};

/* ============================================================
   DASHBOARD RENDER
   ============================================================ */
function renderDashboard(){
  const todaysOpenings = store.openings.filter(o => o.dateAdded === todayISO);
  const todaysInterviews = store.interviews.filter(s => s.date === todayISO);
  const scheduledToday = store.interviews.filter(s => s.date === todayISO && s.tag === 'scheduled').length;
  const scheduledTotal = store.interviews.filter(s => s.tag === 'scheduled').length;
  const attendedToday = store.interviews.filter(s => s.date === todayISO && ['attended','selected','rejected'].includes(s.tag)).length;
  const attendedOverall = store.interviews.filter(s => ['attended','selected','rejected'].includes(s.tag)).length;
  const rejectedToday = store.interviews.filter(s => s.date === todayISO && s.tag === 'rejected').length;
  const rejectedTotal = store.interviews.filter(s => s.tag === 'rejected').length;
  const selectedTotal = store.interviews.filter(s => s.tag === 'selected').length;
  const rescheduledTotal = store.interviews.filter(s => s.tag === 'rescheduled').length;
  const holdTotal = store.interviews.filter(s => s.tag === 'hold').length;
  const profilesSentToday = store.mailchimpLog.filter(l => l.date === todayISO).length + 2; // demo seed
  const profileSentTotal = store.eligible.filter(v => v.placementStatus === 'profile_sent').length;
  const activeOpenings = store.openings.filter(o => o.openingStatus === 'active').length;
  const filledOpenings = store.openings.filter(o => o.openingStatus === 'filled').length;
  const eligibleCount = store.eligible.length;
  const pendingLeads = store.leadsPending.length;

  let stats;
  if (currentRole === 'placement'){
    stats = [
      { label:'Total Companies', value:store.companies.length, sub:'in database', icon:'building', cls:'' },
      { label:'Active Openings', value:activeOpenings, sub:'live & accepting profiles', icon:'briefcase', cls:'green' },
      { label:'Filled Openings', value:filledOpenings, sub:'closed by placement', icon:'star', cls:'amber' },
      { label:"Today's Vacancies", value:todaysOpenings.length, sub:'added today', icon:'briefcase', cls:'purple' },
      { label:"Today's Interviews", value:todaysInterviews.length, sub:'scheduled today', icon:'calendar', cls:'cyan' },
      { label:'Selected Total', value:selectedTotal, sub:'offers in hand', icon:'checkin', cls:'green' },
      { label:'On Hold', value:holdTotal, sub:'awaiting HR result', icon:'refresh', cls:'purple' },
      { label:'Pending Leads', value:pendingLeads, sub:'assigned to self', icon:'bell', cls:'red' },
    ];
  } else {
    stats = [
      { label:'Eligible Students',  value:eligibleCount,        sub:'cleared for outreach',     icon:'users',    cls:'teal' },
      { label:'Profile Sent',       value:profileSentTotal,     sub:'profiles sent to HRs',     icon:'send',     cls:'' },
      { label:'Interview Scheduled',value:scheduledTotal,       sub:'date confirmed',           icon:'calendar', cls:'cyan' },
      { label:'Interview Attended', value:attendedOverall,      sub:'completed interviews',     icon:'checkin',  cls:'green' },
      { label:'Selected',           value:selectedTotal,        sub:'placed students',          icon:'star',     cls:'amber' },
      { label:'Rejected',           value:rejectedTotal,        sub:'not selected',             icon:'cross',    cls:'red' },
      { label:'On Hold',            value:holdTotal,            sub:'result awaited',           icon:'bell',     cls:'purple' },
      { label:'Rescheduled',        value:rescheduledTotal,     sub:'pending reslot',           icon:'refresh',  cls:'orange' },
    ];
  }

  $('#dashStats').innerHTML = stats.map(s => `
    <div class="stat-card">
      <div class="stat-label"><div class="stat-icon ${s.cls}">${ICON[s.icon]}</div>${s.label}</div>
      <div class="stat-value">${s.value}</div>
      <div class="stat-sub">${s.sub}</div>
    </div>
  `).join('');

  // vacancies
  $('#dashVacancies').innerHTML = todaysOpenings.length ? todaysOpenings.map(o => `
    <div class="strip" onclick="openOpeningDetail('${o.id}')">
      <div class="strip-cell" style="min-width: 120px;"><div class="lbl">Company</div><div class="val">${o.company}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Role</div><div class="val muted">${o.role}</div></div>
      <div class="strip-cell" style="min-width: 70px;"><div class="lbl">CTC</div><div class="val mono">${o.ctc}</div></div>
      <div class="strip-cell" style="min-width: 70px;"><div class="lbl">Slots</div><div class="val mono">${o.openingsCount}</div></div>
      <div class="strip-cell grow"><div class="lbl">Skills</div><div class="skills-chips">${(o.skills||[]).map(chip).join('')}</div></div>
      <div class="strip-cell">${tagPill(o.openingStatus)}</div>
    </div>
  `).join('') : emptyHtml('No vacancies today');

  // interviews
  $('#dashInterviews').innerHTML = todaysInterviews.length ? todaysInterviews.map(s => `
    <div class="strip-compact" onclick="openInterviewDetail('${s.id}')">
      <div class="head">
        <div class="name">${nameLink(s.name)}</div>
        ${tagPill(s.tag)}
      </div>
      <div class="meta">
        <div class="item"><div class="lbl">Phone</div><div class="val mono">${s.phone}</div></div>
        <div class="item"><div class="lbl">Company</div><div class="val muted">${s.company}</div></div>
        <div class="item"><div class="lbl">Course</div><div class="val muted">${s.course}</div></div>
        <div class="item"><div class="lbl">Batch Type</div><div class="val muted">${s.batchType}</div></div>
        <div class="item"><div class="lbl">Batch Code</div><div class="val mono">${s.batchCode}</div></div>
        <div class="item"><div class="lbl">Time</div><div class="val mono">${s.time || '—'}</div></div>
      </div>
    </div>
  `).join('') : emptyHtml('No interviews today');

  // pending followups (top 5)
  $('#dashFollowups').innerHTML = store.leadsPending.length ? store.leadsPending.slice(0,5).map(l => `
    <div class="strip" onclick="showView('followups')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${l.id}</div></div>
      <div class="strip-cell" style="min-width:160px;"><div class="lbl">Entity</div><div class="val">${l.type === 'student-result' ? nameLink(l.entity) : l.entity}</div></div>
      <div class="strip-cell" style="min-width:140px;"><div class="lbl">HR / Contact</div><div class="val muted">${l.hr}</div></div>
      <div class="strip-cell" style="min-width:130px;"><div class="lbl">Phone</div><div class="val mono">${l.hrPhone}</div></div>
      <div class="strip-cell" style="min-width:120px;"><div class="lbl">Follow-up</div><div class="val mono">${fmtDate(l.followUpDate)}</div></div>
      <div class="strip-cell">${tagPill(l.leadStatus)}</div>
      <div class="strip-cell grow"><div class="lbl">Reason</div><div class="val muted">${l.reason}</div></div>
    </div>
  `).join('') : emptyHtml('No pending leads — all caught up!');

  // overall students
  $('#dashOverall').innerHTML = store.interviews.length ? store.interviews.map(s => `
    <div class="strip" onclick="openInterviewDetail('${s.id}')">
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Name</div><div class="val">${nameLink(s.name)}</div></div>
      <div class="strip-cell" style="min-width: 120px;"><div class="lbl">Phone</div><div class="val mono">${s.phone}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Course</div><div class="val muted">${s.course}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Batch</div><div class="val muted">${s.batchType}</div></div>
      <div class="strip-cell" style="min-width: 95px;"><div class="lbl">Code</div><div class="val mono">${s.batchCode}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Company</div><div class="val muted">${s.company}</div></div>
      <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Location</div><div class="val muted">${s.location}</div></div>
      <div class="strip-cell" style="min-width: 70px;"><div class="lbl">Count</div><div class="val mono">${s.count}</div></div>
      <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Date</div><div class="val mono">${shortDate(s.date)}</div></div>
      <div class="strip-cell grow" style="align-items: flex-end;">${tagPill(s.tag)}</div>
    </div>
  `).join('') : emptyHtml('No students yet');
}

/* ============================================================
   COMPANIES RENDER
   ============================================================ */
function renderCompanies(){
  $('#compCount').textContent = store.companies.length;
  // populate filter selects
  const cities = [...new Set(store.companies.map(c=>c.city))];
  const inds   = [...new Set(store.companies.map(c=>c.industry))];
  const curCity = $('#compCityFilter').value;
  const curInd  = $('#compIndustryFilter').value;
  $('#compCityFilter').innerHTML = '<option value="">City: All</option>' + cities.map(c=>`<option ${c===curCity?'selected':''}>${c}</option>`).join('');
  $('#compIndustryFilter').innerHTML = '<option value="">Industry: All</option>' + inds.map(i=>`<option ${i===curInd?'selected':''}>${i}</option>`).join('');

  const ls = $('#compLeadFilter').value.toLowerCase();
  const of = $('#compOpenFlagFilter').value;
  const cf = $('#compCityFilter').value;
  const if_ = $('#compIndustryFilter').value;

  const filtered = store.companies.filter(c => {
    if (ls && c.leadStatus !== ls) return false;
    if (of && c.currentOpeningsFlag !== of) return false;
    if (cf && c.city !== cf) return false;
    if (if_ && c.industry !== if_) return false;
    return true;
  });

  $('#companiesList').innerHTML = filtered.map(c => `
    <div class="strip" onclick="openCompanyDetail('${escAttr(c.name)}')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${c.id}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${c.name}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">City</div><div class="val muted">${c.city}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Industry</div><div class="val muted">${c.industry}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">HR Name</div><div class="val">${c.hr}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">HR Phone</div><div class="val mono">${c.hrPhone}</div></div>
      <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Source</div><div class="val muted">${c.source}</div></div>
      <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Openings?</div><div class="val mono" style="color: ${c.currentOpeningsFlag === 'Yes' ? 'var(--success)' : 'var(--text-muted)'}">${c.currentOpeningsFlag}</div></div>
      <div class="strip-cell" style="min-width: 95px;"><div class="lbl">Added</div><div class="val mono">${shortDate(c.dateAdded)}</div></div>
      <div class="strip-cell">${tagPill(c.leadStatus)}</div>
    </div>
  `).join('') || emptyHtml('No companies match these filters.');
}
['compLeadFilter','compOpenFlagFilter','compCityFilter','compIndustryFilter'].forEach(id => {
  document.addEventListener('change', e => { if (e.target.id === id) renderCompanies(); });
});

/* ============================================================
   OPENINGS RENDER
   ============================================================ */
function renderOpenings(){
  $('#openCount').textContent = store.openings.length;
  let list = store.openings;
  if (openingTab === 'today')   list = list.filter(o => o.dateAdded === todayISO);
  if (openingTab === 'old')     list = list.filter(o => o.dateAdded !== todayISO);
  if (openingTab === 'active')  list = list.filter(o => o.openingStatus === 'active');
  if (openingTab === 'hold')    list = list.filter(o => o.openingStatus === 'hold');
  if (openingTab === 'filled')  list = list.filter(o => o.openingStatus === 'filled');
  if (openingTab === 'closed')  list = list.filter(o => o.openingStatus === 'closed');

  const isStudentRole = currentRole === 'student';

  $('#openingsList').innerHTML = list.length ? list.map(o => {
    const pushedTo  = (o.lmsPushedTo || []).length;
    const applied   = (o.applicants || []).length;
    const sentToHR  = (o.studentsSent || []).length;
    const pendingApplicants = applied - sentToHR;
    return `
      <div class="strip" onclick="openOpeningDetail('${o.id}')">
        <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${o.id}</div></div>
        <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${o.company}</div></div>
        <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Role</div><div class="val">${o.role}</div></div>
        <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Location</div><div class="val muted">${o.location}</div></div>
        <div class="strip-cell" style="min-width: 75px;"><div class="lbl">CTC</div><div class="val mono">${o.ctc}</div></div>
        <div class="strip-cell" style="min-width: 60px;"><div class="lbl">Slots</div><div class="val mono">${o.openingsCount}</div></div>
        ${isStudentRole ? `
          <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Pushed</div><div class="val mono" style="color: var(--teal);">${pushedTo} course${pushedTo===1?'':'s'}</div></div>
          <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Applied</div><div class="val mono" style="color: ${pendingApplicants > 0 ? 'var(--orange)' : 'var(--text-secondary)'};">${applied}${pendingApplicants > 0 ? ` <span style="font-size:10px;">(${pendingApplicants} new)</span>` : ''}</div></div>
          <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Sent HR</div><div class="val mono" style="color: var(--accent);">${sentToHR}</div></div>
        ` : `
          <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Exp.</div><div class="val muted">${o.experience}</div></div>
          <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Mode</div><div class="val muted">${o.interviewMode}</div></div>
        `}
        <div class="strip-cell" style="min-width: 95px;"><div class="lbl">Date</div><div class="val mono">${shortDate(o.dateAdded)}</div></div>
        <div class="strip-cell">${tagPill(o.openingStatus)}</div>
      </div>
    `;
  }).join('') : emptyHtml(`No openings in "${openingTab}".`);
}

/* ============================================================
   ELIGIBLE STUDENTS RENDER
   ============================================================ */
function renderEligible(){
  $('#eligCount').textContent = store.eligible.length;
  const courses = [...new Set(store.eligible.map(v=>v.course))];
  const curC = $('#eligCourseFilter').value;
  $('#eligCourseFilter').innerHTML = '<option value="">Course: All</option>' + courses.map(c => `<option ${c===curC?'selected':''}>${c}</option>`).join('');

  // populate batch filter (filtered by selected course, if any)
  const batchEl = $('#eligBatchFilter');
  if (batchEl){
    const curB = batchEl.value;
    const batches = [...new Set(store.eligible.filter(v => !curC || v.course === curC).map(v => v.batchCode))];
    batchEl.innerHTML = '<option value="">Batch: All</option>' + batches.map(b => `<option ${b===curB?'selected':''}>${b}</option>`).join('');
  }

  const cf = $('#eligCourseFilter').value;
  const bf = $('#eligBatchFilter')?.value || '';
  const pf = $('#eligProgressFilter').value;
  const sf = $('#eligStatusFilter').value.toLowerCase().replace(/ /g,'_');

  const filtered = store.eligible.filter(v => {
    if (cf && v.course !== cf) return false;
    if (bf && v.batchCode !== bf) return false;
    if (pf && progressBucket(v.courseProgress) !== pf) return false;
    if (sf && v.placementStatus !== sf) return false;
    return true;
  });

  // figure out which students are already in this opening's pushed batches (push-mode only)
  let alreadyPushed = new Set();
  if (pushModeOpeningId){
    const o = store.openings.find(x => x.id === pushModeOpeningId);
    if (o) (o.lmsPushedTo || []).forEach(key => {
      const [course, batch] = key.split('__');
      store.eligible.filter(v => v.course === course && v.batchCode === batch).forEach(v => alreadyPushed.add(v.id));
    });
  }

  $('#eligibleList').innerHTML = filtered.length ? filtered.map(v => {
    const bucket = progressBucket(v.courseProgress);
    const inPush = !!pushModeOpeningId;
    const picked = !!pushModePicked[v.id];
    const pushed = alreadyPushed.has(v.id);
    const clickAttr = inPush
      ? `onclick="togglePushPick('${v.id}')"`
      : `onclick="openEligibleDetail('${v.id}')"`;
    const pushAttrs = inPush ? `data-push="on" data-picked="${picked?1:0}" data-pushed="${pushed?1:0}"` : '';
    return `
    <div class="strip" data-progress="${bucket}" ${pushAttrs} ${clickAttr}>
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${v.id}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Student</div><div class="val">${inPush ? v.name : nameLink(v.name)}</div></div>
      <div class="strip-cell" style="min-width: 120px;"><div class="lbl">Phone</div><div class="val mono">${v.phone}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Course</div><div class="val muted">${v.course}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Batch Code</div><div class="val mono">${v.batchCode}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Type</div><div class="val muted">${v.batchType}</div></div>
      <div class="strip-cell" style="min-width: 130px;">
        <div class="lbl">Progress</div>
        <div class="val mono">${v.courseProgress}%</div>
        <div class="progress-bar"><div class="progress-fill ${bucket}" style="width:${v.courseProgress}%;"></div></div>
      </div>
      <div class="strip-cell" style="min-width: 70px;"><div class="lbl">Overall</div><div class="val mono">${v.overallPercent}%</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Status</div>${tagPill(v.placementStatus)}</div>
      <div class="strip-cell grow"><div class="lbl">Course Status</div><div class="val muted">${v.courseStatus || '—'}</div></div>
    </div>
  `;}).join('') : emptyHtml('No students match these filters.');
}
['eligCourseFilter','eligBatchFilter','eligProgressFilter','eligStatusFilter'].forEach(id => {
  document.addEventListener('change', e => { if (e.target.id === id) renderEligible(); });
});

/* ============================================================
   INTERVIEWS RENDER
   ============================================================ */

/* ============================================================
   MONITORING RENDER (per-opening summary)
   ============================================================ */
function renderInterviewStatus(){
  populateStatusFilters();
  renderInterviewStatusStudents();
  renderInterviewStatusCompanies();
}
function populateStatusFilters(){
  const comp = [...new Set(store.interviews.map(s=>s.company))];
  const cur = $('#statusStuCompany').value;
  $('#statusStuCompany').innerHTML = '<option value="">Company: All</option>' + comp.map(c=>`<option ${c===cur?'selected':''}>${c}</option>`).join('');
}
function renderInterviewStatusStudents(){
  const q   = ($('#statusStuSearch')?.value || '').trim().toLowerCase();
  const tag = $('#statusStuTag')?.value || '';
  const df  = $('#statusStuDate')?.value || '';
  const mo  = $('#statusStuMonth')?.value || '';
  const co  = $('#statusStuCompany')?.value || '';

  let list = applyStatusDateMonthFilter(store.interviews.slice(), df, mo, s => s.date);
  if (q)   list = list.filter(s => (s.name + ' ' + s.company + ' ' + s.role).toLowerCase().includes(q));
  if (tag) list = list.filter(s => s.tag === tag);
  if (co)  list = list.filter(s => s.company === co);

  $('#statusStudentsList').innerHTML = list.length ? list.map(s => `
    <div class="strip" onclick="openStudentByName('${escAttr(s.name)}')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${s.id}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Student</div><div class="val">${nameLink(s.name)}</div></div>
      <div class="strip-cell" style="min-width: 120px;"><div class="lbl">Phone</div><div class="val mono">${s.phone}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Course</div><div class="val muted">${s.course}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Company</div><div class="val muted">${s.company}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Role</div><div class="val muted">${s.role}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Date</div><div class="val mono">${shortDate(s.date)}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Time</div><div class="val mono">${s.time || '—'}</div></div>
      <div class="strip-cell grow" style="align-items: flex-end;">${tagPill(s.tag)}</div>
    </div>
  `).join('') : emptyHtml('No students match these filters.');
}
function renderInterviewStatusCompanies(){
  const q  = ($('#statusCoSearch')?.value || '').trim().toLowerCase();
  const df = $('#statusCoDate')?.value || '';
  const mo = $('#statusCoMonth')?.value || '';

  const rows = store.openings.map(o => {
    // narrow this opening's interviews by date/month filter (so counts match the filter)
    const intvs = applyStatusDateMonthFilter(
      store.interviews.filter(s => s.openingId === o.id),
      df, mo, s => s.date
    );
    const scheduled = intvs.filter(s => s.tag === 'scheduled').length;
    const attended  = intvs.filter(s => ['attended','selected','rejected'].includes(s.tag)).length;
    const notAtt    = intvs.filter(s => ['not_attended','no_show'].includes(s.tag)).length;
    const selected  = intvs.filter(s => s.tag === 'selected').length;
    const rejected  = intvs.filter(s => s.tag === 'rejected').length;
    const hold      = intvs.filter(s => s.tag === 'hold').length;
    return { o, scheduled, attended, notAtt, selected, rejected, hold, total: intvs.length };
  }).filter(r => r.total > 0)
    .filter(r => !q || (r.o.company + ' ' + r.o.role + ' ' + r.o.location).toLowerCase().includes(q));

  $('#statusCompaniesList').innerHTML = rows.length ? rows.map(r => `
    <div class="strip" onclick="openOpeningDetail('${r.o.id}')">
      <div class="strip-cell" style="min-width: 60px;"><div class="lbl">ID</div><div class="val mono">${r.o.id}</div></div>
      <div class="strip-cell" style="min-width: 150px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${r.o.company}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Opening Role</div><div class="val">${r.o.role}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Location</div><div class="val muted">${r.o.location}</div></div>
      <div class="strip-cell" style="min-width: 85px;"><div class="lbl">Scheduled</div><div class="val mono" style="color:var(--accent);">${r.scheduled}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Attended</div><div class="val mono" style="color:var(--success);">${r.attended}</div></div>
      <div class="strip-cell" style="min-width: 95px;"><div class="lbl">Not Attended</div><div class="val mono" style="color:var(--orange);">${r.notAtt}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Selected</div><div class="val mono" style="color:var(--success);">${r.selected}</div></div>
      <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Rejected</div><div class="val mono" style="color:var(--danger);">${r.rejected}</div></div>
      <div class="strip-cell grow" style="min-width: 60px;"><div class="lbl">Hold</div><div class="val mono" style="color:var(--purple);">${r.hold}</div></div>
      <div class="strip-cell">${tagPill(r.o.openingStatus)}</div>
    </div>
  `).join('') : emptyHtml('No interviews match these filters.');
}

// shared date+month range filter
function applyStatusDateMonthFilter(arr, df, mo, getDate){
  const now = new Date();
  const startOfWeek = new Date(now); startOfWeek.setDate(now.getDate() - now.getDay()); startOfWeek.setHours(0,0,0,0);
  const endOfWeek = new Date(startOfWeek); endOfWeek.setDate(endOfWeek.getDate() + 6); endOfWeek.setHours(23,59,59,999);
  const startOfLastWeek = new Date(startOfWeek); startOfLastWeek.setDate(startOfLastWeek.getDate() - 7);
  const endOfLastWeek = new Date(startOfWeek); endOfLastWeek.setDate(endOfLastWeek.getDate() - 1); endOfLastWeek.setHours(23,59,59,999);
  const yesterday = new Date(now); yesterday.setDate(yesterday.getDate() - 1);
  const yISO = yesterday.toISOString().slice(0,10);

  return arr.filter(item => {
    const ds = getDate(item);
    if (!ds) return !df && !mo;
    const d = new Date(ds);
    if (df === 'Today'      && ds !== todayISO) return false;
    if (df === 'Yesterday'  && ds !== yISO)     return false;
    if (df === 'This Week'  && (d < startOfWeek || d > endOfWeek)) return false;
    if (df === 'Last Week'  && (d < startOfLastWeek || d > endOfLastWeek)) return false;
    if (mo !== '' && d.getMonth() !== Number(mo)) return false;
    return true;
  });
}

// helper used by clickable student rows everywhere → unified profile
// (defined earlier; this block is intentionally a no-op note)
// status sub-tab + filter listeners
document.addEventListener('click', e => {
  const t = e.target.closest('.tab[data-statustab]');
  if (!t) return;
  t.parentElement.querySelectorAll('.tab').forEach(s => s.classList.remove('active'));
  t.classList.add('active');
  const tab = t.dataset.statustab;
  $('#statusStudentsWrap').classList.toggle('hidden', tab !== 'students');
  $('#statusCompaniesWrap').classList.toggle('hidden', tab !== 'companies');
});
['statusStuSearch','statusStuTag','statusStuDate','statusStuMonth','statusStuCompany'].forEach(id => {
  document.addEventListener('input', e => { if (e.target.id === id) renderInterviewStatusStudents(); });
  document.addEventListener('change', e => { if (e.target.id === id) renderInterviewStatusStudents(); });
});
['statusCoSearch','statusCoDate','statusCoMonth'].forEach(id => {
  document.addEventListener('input', e => { if (e.target.id === id) renderInterviewStatusCompanies(); });
  document.addEventListener('change', e => { if (e.target.id === id) renderInterviewStatusCompanies(); });
});

/* ============================================================
   COMPANY DETAIL — expandable linked opening (per-status students)
   ============================================================ */
function renderLinkedOpeningExpand(o){
  const sentList = (o.studentsSent || []).map(s => store.eligible.find(v => v.id === s.studentId)).filter(Boolean);
  const intvs    = store.interviews.filter(s => s.openingId === o.id);
  const scheduled = intvs.filter(s => s.tag === 'scheduled');
  const selected  = intvs.filter(s => s.tag === 'selected');
  const rejected  = intvs.filter(s => s.tag === 'rejected');
  const hold      = intvs.filter(s => s.tag === 'hold');
  const groups = {
    profile_sent: sentList.map(v => ({ id:v.id, name:v.name, sub:v.course, link: () => openEligibleDetail(v.id) })),
    scheduled: scheduled.map(s => ({ id:s.id, name:s.name, sub:`${s.time} · ${shortDate(s.date)}`, link: () => openInterviewDetail(s.id) })),
    selected:  selected.map(s => ({ id:s.id, name:s.name, sub:'Selected', link: () => openInterviewDetail(s.id) })),
    rejected:  rejected.map(s => ({ id:s.id, name:s.name, sub:'Rejected', link: () => openInterviewDetail(s.id) })),
    hold:      hold.map(s => ({ id:s.id, name:s.name, sub:'Awaiting result', link: () => openInterviewDetail(s.id) })),
  };
  const wrapId = `oexp-${o.id}`;
  return `
    <div class="open-expand-wrap" id="${wrapId}">
      <div class="open-expand-header" onclick="toggleLinkedOpening('${o.id}')">
        <svg class="open-expand-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <div style="min-width: 80px;"><div class="lbl" style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.7px;font-weight:600;">ID</div><div style="font-family:var(--font-mono);font-weight:600;">${o.id}</div></div>
        <div style="min-width: 140px;"><div class="lbl" style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.7px;font-weight:600;">Role</div><div style="font-weight:600;">${o.role}</div></div>
        <div style="min-width: 80px;"><div class="lbl" style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.7px;font-weight:600;">CTC</div><div style="font-family:var(--font-mono);font-weight:600;">${o.ctc}</div></div>
        <div style="min-width: 70px;"><div class="lbl" style="font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.7px;font-weight:600;">Slots</div><div style="font-family:var(--font-mono);font-weight:600;">${o.openingsCount}</div></div>
        <div style="flex:1;"></div>
        ${tagPill(o.openingStatus)}
        <button class="btn btn-sm btn-ghost" onclick="event.stopPropagation(); openOpeningDetail('${o.id}')">View
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </button>
      </div>
      <div class="open-expand-body">
        <div class="open-status-tabs">
          ${[
            ['profile_sent','Profile Sent', groups.profile_sent.length],
            ['scheduled',   'Scheduled',    groups.scheduled.length],
            ['selected',    'Selected',     groups.selected.length],
            ['rejected',    'Rejected',     groups.rejected.length],
            ['hold',        'Hold',         groups.hold.length],
          ].map(([k,lbl,cnt],i) => `
            <button class="open-status-tab ${i===0?'active':''}" data-otab="${o.id}-${k}" onclick="event.stopPropagation(); pickOpeningStatusTab('${o.id}','${k}')">
              ${lbl} <span class="cnt">${cnt}</span>
            </button>
          `).join('')}
        </div>
        ${['profile_sent','scheduled','selected','rejected','hold'].map((k,i) => `
          <div id="oslist-${o.id}-${k}" class="${i===0?'':'hidden'}">
            ${groups[k].length ? groups[k].map(item => `
              <div class="strip" onclick="event.stopPropagation(); ${k === 'profile_sent' ? `openEligibleDetail('${item.id}')` : `openInterviewDetail('${item.id}')`}">
                <div class="strip-cell" style="min-width: 60px;"><div class="lbl">ID</div><div class="val mono">${item.id}</div></div>
                <div class="strip-cell grow"><div class="lbl">Student</div><div class="val">${nameLink(item.name)}</div></div>
                <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Detail</div><div class="val muted">${item.sub}</div></div>
                ${tagPill(k)}
              </div>
            `).join('') : `<div class="empty" style="padding:30px 20px;">No students with status "${k.replace('_',' ')}".</div>`}
          </div>
        `).join('')}
      </div>
    </div>
  `;
}
function toggleLinkedOpening(id){
  const w = document.getElementById(`oexp-${id}`);
  if (w) w.classList.toggle('expanded');
}
function pickOpeningStatusTab(oid, k){
  // mark active tab
  const tabs = document.querySelectorAll(`#oexp-${oid} .open-status-tab`);
  tabs.forEach(t => t.classList.toggle('active', t.dataset.otab === `${oid}-${k}`));
  // toggle lists
  ['profile_sent','scheduled','selected','rejected','hold'].forEach(s => {
    const el = document.getElementById(`oslist-${oid}-${s}`);
    if (el) el.classList.toggle('hidden', s !== k);
  });
}

function renderMonitoring(){ /* legacy alias kept for compatibility */ renderInterviewStatus(); }


/* ============================================================
   FOLLOW-UPS RENDER
   ============================================================ */
function renderFollowups(){
  // role-aware: placement → hiring follow-ups; student → 2-tab (student/company)
  if (currentRole === 'placement'){
    $('#folPlacementWrap').classList.remove('hidden');
    $('#folStudentWrap').classList.add('hidden');
    $('#folHeading').innerHTML = `Company Hiring Follow-ups <span class="count" id="folCount">${(store.hiringFollowups||[]).length}</span>`;
    $('#folEntryBtn').setAttribute('onclick',"openModal('hiring-followup')");
    renderHiringFollowups();
  } else {
    $('#folPlacementWrap').classList.add('hidden');
    $('#folStudentWrap').classList.remove('hidden');
    $('#folEntryBtn').setAttribute('onclick',"openModal('followup-student')");
    const total = store.followupsStudent.length + store.followupsCompany.length;
    $('#folHeading').innerHTML = `Follow-ups <span class="count" id="folCount">${total}</span>`;
    renderFuStudent();
    renderFuCompany();
  }
}

function renderFuStudent(){
  const q  = ($('#fuStuSearch')?.value || '').trim().toLowerCase();
  const p  = $('#fuStuPurpose')?.value || '';
  const df = $('#fuStuDate')?.value || '';
  const mo = $('#fuStuMonth')?.value || '';
  let list = store.followupsStudent.filter(f => {
    if (q && !(`${f.name} ${f.company} ${f.course||''}`.toLowerCase().includes(q))) return false;
    if (p && f.purpose !== p) return false;
    return true;
  });
  list = applyStatusDateMonthFilter(list, df, mo, f => f.callDate);
  $('#fuStudentList').innerHTML = list.length ? list.map(f => `
    <div class="strip" onclick="openInterviewByNameOrEligible('${escAttr(f.name)}')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${f.id}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Student</div><div class="val">${nameLink(f.name)}</div></div>
      <div class="strip-cell" style="min-width: 120px;"><div class="lbl">Phone</div><div class="val mono">${f.phone}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Course</div><div class="val muted">${f.course||'—'}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Company</div><div class="val muted">${f.company}</div></div>
      <div class="strip-cell" style="min-width: 170px;"><div class="lbl">Purpose</div><div class="val muted" style="font-size:12px;">${f.purposeLabel || '—'}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Call Date</div><div class="val mono">${fmtDate(f.callDate)}</div></div>
      <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Duration</div><div class="val mono">${f.duration||'—'}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Assigned</div><div class="val muted">${f.assignedTo||'—'}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Next Follow-up</div><div class="val mono">${fmtDate(f.followUpDate)}</div></div>
      ${f.status && f.status !== '—' ? `<div class="strip-cell">${tagPill(f.status)}</div>` : ''}
      <div class="strip-cell grow"><div class="lbl">Notes</div><div class="val muted">${f.reason}</div></div>
    </div>
  `).join('') : emptyHtml('No student follow-ups match these filters.');
}

function renderFuCompany(){
  const q  = ($('#fuCoSearch')?.value || '').trim().toLowerCase();
  const p  = $('#fuCoPurpose')?.value || '';
  const df = $('#fuCoDate')?.value || '';
  const mo = $('#fuCoMonth')?.value || '';
  let list = store.followupsCompany.filter(f => {
    if (q && !(`${f.company} ${f.hr||''} ${f.role||''}`.toLowerCase().includes(q))) return false;
    if (p && f.purpose !== p) return false;
    return true;
  });
  list = applyStatusDateMonthFilter(list, df, mo, f => f.callDate);
  $('#fuCompanyList').innerHTML = list.length ? list.map(f => `
    <div class="strip" onclick="openCompanyDetail('${escAttr(f.company)}')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${f.id}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${f.company}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">HR</div><div class="val">${f.hr}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">HR No.</div><div class="val mono">${f.hrPhone}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Role</div><div class="val muted">${f.role||'—'}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Student</div><div class="val">${f.student ? nameLink(f.student) : '—'}</div></div>
      <div class="strip-cell" style="min-width: 170px;"><div class="lbl">Purpose</div><div class="val muted" style="font-size:12px;">${f.purposeLabel || '—'}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Call Date</div><div class="val mono">${fmtDate(f.callDate)}</div></div>
      <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Duration</div><div class="val mono">${f.duration||'—'}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Assigned</div><div class="val muted">${f.assignedTo||'—'}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Next Follow-up</div><div class="val mono">${fmtDate(f.followUpDate)}</div></div>
      ${f.status && f.status !== '—' ? `<div class="strip-cell"><div class="lbl">HR Response</div><div class="val">${f.status}</div></div>` : ''}
      <div class="strip-cell grow"><div class="lbl">Notes</div><div class="val muted">${f.reason}</div></div>
    </div>
  `).join('') : emptyHtml('No company follow-ups match these filters.');
}

// helper used by student follow-up strips
function openInterviewByNameOrEligible(name){
  event && event.stopPropagation && event.stopPropagation();
  openStudentByName(name);
}

// student follow-up filter/search listeners
['fuStuSearch','fuStuPurpose','fuStuDate','fuStuMonth','fuCoSearch','fuCoPurpose','fuCoDate','fuCoMonth'].forEach(id => {
  document.addEventListener('input', e => { if (e.target.id === id) (id.startsWith('fuStu') ? renderFuStudent() : renderFuCompany()); });
  document.addEventListener('change', e => { if (e.target.id === id) (id.startsWith('fuStu') ? renderFuStudent() : renderFuCompany()); });
});

/* ============================================================
   PLACEMENT-ROLE HIRING FOLLOW-UPS (filter+search+date)
   ============================================================ */
function renderHiringFollowups(){
  const list = store.hiringFollowups || [];
  const q   = ($('#folSearch')?.value || '').trim().toLowerCase();
  const tag = $('#folTagFilter')?.value || '';
  const df  = $('#folDateFilter')?.value || '';
  const startOfWeek = new Date(); startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay());
  const endOfWeek = new Date(startOfWeek); endOfWeek.setDate(endOfWeek.getDate() + 6);
  const filtered = list.filter(f => {
    if (q){
      const hay = (f.company + ' ' + (f.hr||'') + ' ' + (f.location||'')).toLowerCase();
      if (!hay.includes(q)) return false;
    }
    if (tag && f.hiringStatus !== tag) return false;
    if (df){
      const d = new Date(f.followUpDate);
      const today = new Date(); today.setHours(0,0,0,0);
      if (df === 'Today' && f.followUpDate !== todayISO) return false;
      if (df === 'This Week' && (d < startOfWeek || d > endOfWeek)) return false;
      if (df === 'Overdue' && d >= today) return false;
      if (df === 'Upcoming' && d < today) return false;
    }
    return true;
  });
  $('#folPlacementList').innerHTML = filtered.length ? filtered.map(f => `
    <div class="strip" onclick="openCompanyDetail('${escAttr(f.company)}')">
      <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${f.id}</div></div>
      <div class="strip-cell" style="min-width: 150px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${f.company}</div></div>
      <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Location</div><div class="val muted">${f.location}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">HR Name</div><div class="val">${f.hr}</div></div>
      <div class="strip-cell" style="min-width: 140px;"><div class="lbl">HR Contact No.</div><div class="val mono">${f.hrPhone}</div></div>
      <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Hiring Status</div>${tagPill(f.hiringStatus)}</div>
      <div class="strip-cell" style="min-width: 160px;"><div class="lbl">Assigned To</div><div class="val muted">${f.assignedTo || 'Self'}</div></div>
      <div class="strip-cell" style="min-width: 110px;"><div class="lbl">Follow-up</div><div class="val mono">${fmtDate(f.followUpDate)}</div></div>
      <div class="strip-cell grow"><div class="lbl">Reason / Notes</div><div class="val muted">${f.reason}</div></div>
    </div>
  `).join('') : emptyHtml('No hiring follow-ups match these filters.');
}
['folSearch','folTagFilter','folDateFilter'].forEach(id => {
  document.addEventListener('input', e => { if (e.target.id === id) renderHiringFollowups(); });
  document.addEventListener('change', e => { if (e.target.id === id) renderHiringFollowups(); });
});

/* ============================================================
   DETAIL PAGES
   ============================================================ */
function renderCallHistory(history, opts){
  opts = opts || {};
  const showAssign = !!opts.showAssign;
  const company = opts.companyName || '';
  // when assign column needed → 7-column grid; otherwise 6-column
  const cols = showAssign
    ? 'grid-template-columns: 110px 110px 110px 80px 70px 1fr 100px;'
    : 'grid-template-columns: 110px 110px 110px 80px 70px 1fr;';
  const minw = showAssign ? '880px' : '780px';
  const targetType = opts.targetType || 'company';
  const targetId   = opts.targetId   || '';
  const entryBtn = opts.addEntry
    ? `<button class="btn btn-sm btn-primary" onclick="openModal('add-call', { companyName: '${escAttr(company)}', target: '${targetType}', targetId: '${escAttr(targetId)}', __lock:['companyName'] })">
         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Entry
       </button>`
    : '';
  // assign prefill data for each row built per company (placement workflow)
  // OR per student (student workflow — purpose-driven follow-up modal)
  const c = company ? store.companies.find(x => x.name === company) : null;
  const hrName = c ? c.hr : '';
  const hrPhone = c ? c.hrPhone : '';
  const loc = c ? c.city : '';
  // for student target, we'll need the eligible record
  const studentRec = targetType === 'student' ? store.eligible.find(x => x.id === targetId) : null;
  return `
    <div class="panel">
      <div class="panel-header"><div class="panel-title">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        Call History <small>(every call logged)</small>
      </div>${entryBtn}</div>
      <div style="padding: 0; overflow-x: auto;">
        <div style="display:grid;${cols}padding:10px 20px;border-bottom:1px solid var(--border);font-size:10px;text-transform:uppercase;letter-spacing:0.7px;color:var(--text-muted);font-weight:600;min-width:${minw};">
          <div>Call Time</div><div>Direction</div><div>Recording</div><div>Duration</div><div>Owner</div><div>Notes</div>${showAssign ? '<div>Action</div>' : ''}
        </div>
        ${(history||[]).map((h, idx) => {
          const reasonPrefill = `Follow-up on call: ${h.note}`;
          // build the right assign onClick based on target type
          let assignOnClick;
          if (targetType === 'student' && studentRec){
            assignOnClick = `openModal('followup-student', { name:'${escAttr(studentRec.name)}', phone:'${escAttr(studentRec.phone)}', email:'${escAttr(studentRec.email||'')}', course:'${escAttr(studentRec.course)}', batchCode:'${escAttr(studentRec.batchCode)}', reason:'${escAttr(reasonPrefill)}', __lock:['name','phone'] })`;
          } else {
            assignOnClick = `openModal('assign-followup', { companyName:'${escAttr(company)}', hr:'${escAttr(hrName)}', hrPhone:'${escAttr(hrPhone)}', location:'${escAttr(loc)}', reason:'${escAttr(reasonPrefill)}', __lock:['companyName'] })`;
          }
          return `
          <div style="display:grid;${cols}padding:14px 20px;border-bottom:1px solid var(--border);gap:10px;align-items:center;font-size:12.5px;min-width:${minw};">
            <div><div style="font-family:var(--font-mono);font-weight:600;">${h.time}</div><div style="font-size:11px;color:var(--text-muted);">${h.date}</div></div>
            <div>${h.dir === 'in' ? '<span class="pill-incoming">↓ INCOMING</span>' : '<span class="pill-outgoing">↑ OUTGOING</span>'}</div>
            <div><button class="play-btn">▶ Play <span style="color:var(--text-muted);font-family:var(--font-mono);font-size:10px;">${h.duration}</span></button></div>
            <div style="font-family:var(--font-mono);">${h.duration}</div>
            <div>${h.owner}</div>
            <div style="color:var(--text-secondary);">${h.note}</div>
            ${showAssign ? `<div>
              <button class="assign-btn" onclick="${assignOnClick}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                Assign
              </button>
            </div>` : ''}
          </div>
        `;}).join('') || `<div class="empty" style="padding:30px 20px;">No calls logged yet.</div>`}
      </div>
    </div>
  `;
}

function renderDetail({ type, avatar, name, badge, metaParts, info, extra, actions }){
  const v = $('#view-detail');
  const actionsHtml = (actions || []).map(a => `<button class="btn ${a.cls||''} btn-sm" ${a.onClick ? `onclick="${a.onClick}"` : ''}>${a.icon || ''} ${a.label}</button>`).join('');
  v.innerHTML = `
    <a href="#" onclick="event.preventDefault(); back()" style="display:inline-flex;align-items:center;gap:6px;color:var(--text-secondary);text-decoration:none;font-size:13px;margin-bottom:14px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Back
    </a>
    <div class="detail-header">
      <div class="detail-top">
        <div class="detail-avatar">${avatar}</div>
        <div style="flex:1;">
          <div class="detail-name">${name}</div>
          <div class="detail-meta">
            ${badge}
            ${metaParts.filter(Boolean).map(p => `<span class="sep">·</span> <span>${p}</span>`).join('')}
          </div>
        </div>
        <div class="detail-actions">${actionsHtml}</div>
      </div>
      <div class="info-grid">
        ${info.map(([k,val,cls='']) => `<div class="info-cell"><div class="lbl">${k}</div><div class="val ${cls||''}">${val}</div></div>`).join('')}
      </div>
    </div>
    ${extra}
  `;
  $$('.view').forEach(s => s.classList.add('hidden'));
  v.classList.remove('hidden');
  window.scrollTo({top:0, behavior:'smooth'});
}

function openCompanyDetail(name){
  const c = store.companies.find(x => x.name === name);
  if (!c) return;
  const linkedOpenings = store.openings.filter(o => o.company === c.name);
  renderDetail({
    type:'company',
    avatar: initials(c.name),
    name: c.name,
    badge: `${tagPill(c.leadStatus)} <span class="tag tag-pending">${c.id}</span>`,
    metaParts: [c.industry, c.city, c.source],
    info: [
      ['HR Name', c.hr], ['HR Phone', c.hrPhone, 'mono'], ['HR Email', c.hrEmail],
      ['Company Mail', c.companyEmail], ['Website', c.website ? `<a href="${c.website}" target="_blank">${c.website}</a>` : '—'],
      ['LinkedIn', c.linkedin || '—'], ['Source', c.source], ['City', c.city], ['Industry', c.industry],
      ['Date Added', fmtDate(c.dateAdded), 'mono'], ['Current Openings?', c.currentOpeningsFlag],
    ],
    extra: `
      ${linkedOpenings.length ? `
        <div class="panel" style="margin-bottom: 20px;">
          <div class="panel-header"><div class="panel-title">Linked Openings <small>(${linkedOpenings.length}) · click to expand status</small></div></div>
          <div style="padding:10px;">
            ${linkedOpenings.map(o => renderLinkedOpeningExpand(o)).join('')}
          </div>
        </div>
      ` : ''}
      ${renderCallHistory(c.callHistory, { showAssign: true, addEntry: true, companyName: c.name })}
    `,
    actions: [
      { label:'Edit', cls:'btn-ghost', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
      { label:'Add Opening', cls:'btn-primary', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        onClick: `openModal('opening', { company: '${escAttr(c.name)}', companyEmail:'${escAttr(c.companyEmail)}', hr:'${escAttr(c.hr)}', hrPhone:'${escAttr(c.hrPhone)}', hrEmail:'${escAttr(c.hrEmail)}', location:'${escAttr(c.city)}', __lock:['company','companyEmail','hr','hrPhone','hrEmail'] })` },
    ]
  });
}


/* ============================================================
   OPENING DETAIL — slim (info only · workflow lives in Applied Openings)
   ============================================================ */
function openOpeningDetail(id){
  const o = store.openings.find(x => x.id === id);
  if (!o) return;
  o.lmsPushedTo = o.lmsPushedTo || [];
  o.applicants  = o.applicants  || [];
  o.studentsSent = o.studentsSent || [];

  const intvs = store.interviews.filter(s => s.openingId === o.id);
  // for placement view, keep the interview status summary visible
  const summary = {
    scheduled: intvs.filter(s => s.tag === 'scheduled').length,
    attended:  intvs.filter(s => ['attended','selected','rejected'].includes(s.tag)).length,
    notAtt:    intvs.filter(s => ['not_attended','no_show'].includes(s.tag)).length,
    selected:  intvs.filter(s => s.tag === 'selected').length,
    rejected:  intvs.filter(s => s.tag === 'rejected').length,
    hold:      intvs.filter(s => s.tag === 'hold').length,
  };

  // student-view: small "Pushed to" indicator (for context); workflow itself is in Applied Openings
  const pushedChips = (currentRole === 'student' && o.lmsPushedTo.length) ? `
    <div class="panel" style="margin-bottom: 20px;">
      <div class="panel-header">
        <div class="panel-title">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/></svg>
          Pushed to <small>(visible on these batches' LMS feeds)</small>
        </div>
      </div>
      <div style="padding: 14px 18px; display:flex; gap:8px; flex-wrap:wrap;">
        ${o.lmsPushedTo.map(key => {
          const [course, batch] = key.split('__');
          const n = store.eligible.filter(v => v.course === course && v.batchCode === batch).length;
          return `<span class="pushed-chip">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            ${course} <span style="opacity:0.7;font-family:var(--font-mono);font-size:11px;">${batch} · ${n}</span>
          </span>`;
        }).join('')}
      </div>
    </div>` : '';

  renderDetail({
    type:'opening',
    avatar: initials(o.company),
    name: `${o.company} · ${o.role}`,
    badge: `${tagPill(o.openingStatus)} <span class="tag tag-pending">${o.id}</span>`,
    metaParts: [o.location, o.ctc, o.experience, `${o.openingsCount} openings`],
    info: [
      ['Company Mail', o.companyEmail], ['HR Name', o.hr], ['HR Phone', o.hrPhone, 'mono'], ['HR Email', o.hrEmail],
      ['Job Location', o.location], ['Qualification', o.qualification], ['Experience', o.experience], ['CTC / Salary', o.ctc, 'mono'],
      ['Openings Count', o.openingsCount, 'mono'], ['Application Deadline', fmtDate(o.applicationDeadline), 'mono'],
      ['Interview Mode', o.interviewMode], ['Interview Process', o.interviewProcess], ['Bond', o.bond || 'None'],
      ['Date Added', fmtDate(o.dateAdded), 'mono'], ['Added By', o.addedBy], ['Skills', (o.skills||[]).join(', ') || '—'],
    ],
    extra: `
      ${pushedChips}

      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header">
          <div class="panel-title">Job Description</div>
          ${(() => {
            // determine mode for header button
            const jd = o.jd;
            if (jd && typeof jd === 'object' && jd.mode === 'text'){
              return `<button class="btn btn-sm btn-ghost" onclick="copyText(\`${(jd.text||'').replace(/[\`\\\\]/g,'\\\\$&').replace(/\$/g,'\\\\$')}\`)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                Copy
              </button>`;
            }
            return `<button class="btn btn-sm btn-ghost">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Download
            </button>`;
          })()}
        </div>
        <div style="padding: 20px;">
          ${(() => {
            const jd = o.jd;
            // old shape: jd was just a string filename
            if (!jd){
              return `<div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--bg-elev-2);border:1px solid var(--border);border-radius:var(--radius-md);">
                <div style="width:44px;height:44px;background:var(--text-muted);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                <div><div style="font-weight:700;">No JD attached</div><div style="font-size:12px;color:var(--text-muted);">Edit this opening to add a JD as PDF or text.</div></div>
              </div>`;
            }
            // dual-mode object
            if (typeof jd === 'object'){
              if (jd.mode === 'text' && jd.text){
                return `<div style="background:var(--bg-elev-2);border:1px solid var(--border);border-radius:var(--radius-md);padding:18px 22px;font-family:var(--font-body);font-size:13.5px;line-height:1.65;color:var(--text-secondary);white-space:pre-wrap;max-height:480px;overflow-y:auto;">${escAttr(jd.text)}</div>`;
              }
              const fname = jd.fileName || 'jd-attached.pdf';
              return `<div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--bg-elev-2);border:1px solid var(--border);border-radius:var(--radius-md);">
                <div style="width:44px;height:44px;background:var(--danger-soft);color:var(--danger);border-radius:10px;display:flex;align-items:center;justify-content:center;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div><div style="font-weight:700;">${fname}</div><div style="font-size:12px;color:var(--text-muted);">Attached JD · click Download to fetch</div></div>
              </div>`;
            }
            // legacy string filename
            return `<div style="display:flex;align-items:center;gap:14px;padding:14px;background:var(--bg-elev-2);border:1px solid var(--border);border-radius:var(--radius-md);">
              <div style="width:44px;height:44px;background:var(--danger-soft);color:var(--danger);border-radius:10px;display:flex;align-items:center;justify-content:center;"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
              <div><div style="font-weight:700;">${jd}</div><div style="font-size:12px;color:var(--text-muted);">Attached JD · click Download to fetch</div></div>
            </div>`;
          })()}
        </div>
      </div>

      ${currentRole === 'placement' ? `
        <div class="panel" style="margin-bottom: 20px;">
          <div class="panel-header"><div class="panel-title">Interview Status Summary</div></div>
          <div style="padding: 16px;"><div class="mini-summary">
            <div class="mini-stat accent"><div class="mlbl">Scheduled</div><div class="mval">${summary.scheduled}</div></div>
            <div class="mini-stat green"><div class="mlbl">Attended</div><div class="mval">${summary.attended}</div></div>
            <div class="mini-stat orange"><div class="mlbl">Not Attended</div><div class="mval">${summary.notAtt}</div></div>
            <div class="mini-stat green"><div class="mlbl">Selected</div><div class="mval">${summary.selected}</div></div>
            <div class="mini-stat red"><div class="mlbl">Rejected</div><div class="mval">${summary.rejected}</div></div>
            <div class="mini-stat purple"><div class="mlbl">Hold</div><div class="mval">${summary.hold}</div></div>
          </div></div>
        </div>

        <div class="panel" style="margin-bottom: 20px;">
          <div class="panel-header"><div class="panel-title">Interviews Scheduled <small>(${intvs.length})</small></div></div>
          <div class="strip-list" style="max-height:none;">
            ${intvs.length ? intvs.map(s => `
              <div class="strip" onclick="openInterviewDetail('${s.id}')">
                <div class="strip-cell" style="min-width: 60px;"><div class="lbl">ID</div><div class="val mono">${s.id}</div></div>
                <div class="strip-cell" style="min-width: 140px;"><div class="lbl">Student</div><div class="val">${nameLink(s.name)}</div></div>
                <div class="strip-cell" style="min-width: 100px;"><div class="lbl">Date</div><div class="val mono">${shortDate(s.date)}</div></div>
                <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Time</div><div class="val mono">${s.time}</div></div>
                <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Mode</div><div class="val muted">${s.interviewMode}</div></div>
                <div class="strip-cell grow">${tagPill(s.tag)}</div>
              </div>
            `).join('') : emptyHtml('No interviews scheduled yet.')}
          </div>
        </div>
      ` : ''}

      ${renderCallHistory(o.callHistory, { showAssign: true, addEntry: true, companyName: o.company, targetType: 'opening', targetId: o.id })}
    `,
    actions: [
      { label:'Edit', cls:'btn-ghost', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
      currentRole === 'student'
        ? { label:'Push Openings', cls:'btn-primary',
            icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/></svg>',
            onClick: `goPushOpenings('${o.id}')` }
        : { label:'Schedule Interview', cls:'btn-primary',
            icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/></svg>',
            onClick: `openModal('schedule-interview', { company:'${escAttr(o.company)}', role:'${escAttr(o.role)}', location:'${escAttr(o.location)}', __lock:['company','role','location'] })` },
    ]
  });
}

/* ============================================================
   PUSH MODE  (Eligible Students view, triggered from opening)
   ============================================================ */
let pushModeOpeningId = null;          // when set, eligible view is in push mode
let pushModePicked = {};               // { studentId: true } selections in this session

function goPushOpenings(oid){
  const o = store.openings.find(x => x.id === oid);
  if (!o) return;
  pushModeOpeningId = oid;
  pushModePicked = {};
  // pre-set course filter — pick the dominant matching course based on skills
  const matchingCourse = findBestMatchingCourse(o);
  showView('eligible');
  // wait a tick for DOM to be ready
  setTimeout(() => {
    const banner = $('#pushModeBanner');
    if (banner){
      banner.classList.remove('hidden');
      $('#pushBannerTitle').innerHTML = `Pushing: <em>${o.company} — ${o.role}</em>`;
      $('#pushBannerSub').textContent = `Use the filters below (course / batch / progress) to narrow down students who match this role, then push to selected.`;
    }
    // set course filter
    if (matchingCourse) $('#eligCourseFilter').value = matchingCourse;
    renderEligible();
    updatePushSelSummary();
  }, 50);
}

function exitPushMode(){
  pushModeOpeningId = null;
  pushModePicked = {};
  const banner = $('#pushModeBanner');
  if (banner) banner.classList.add('hidden');
  renderEligible();
}

function findBestMatchingCourse(o){
  // pick the course whose students' projectTech overlaps most with opening skills
  const oSkills = (o.skills||[]).map(s => s.toLowerCase());
  const courseScores = {};
  store.eligible.forEach(v => {
    if (!courseScores[v.course]) courseScores[v.course] = { score:0, count:0 };
    const tech = (v.projectTech||'').toLowerCase();
    oSkills.forEach(s => { if (tech.includes(s)) courseScores[v.course].score++; });
    courseScores[v.course].count++;
    if (v.course.toLowerCase().includes(oSkills[0] || '')) courseScores[v.course].score += 2;
  });
  let best = null, bestScore = 0;
  for (const [c, s] of Object.entries(courseScores)){
    if (s.score > bestScore){ bestScore = s.score; best = c; }
  }
  return best;
}

function togglePushPick(vid){
  pushModePicked[vid] = !pushModePicked[vid];
  if (!pushModePicked[vid]) delete pushModePicked[vid];
  renderEligible();
  updatePushSelSummary();
}

function updatePushSelSummary(){
  const n = Object.keys(pushModePicked).length;
  const el = $('#pushSelSummary');
  if (el) el.textContent = `${n} selected`;
}

function pushSelectAll(){
  // select all currently-visible (filtered) students not already pushed
  const o = store.openings.find(x => x.id === pushModeOpeningId);
  if (!o) return;
  const alreadyPushed = (o.lmsPushedTo || []).reduce((set, key) => {
    const [course, batch] = key.split('__');
    store.eligible.filter(v => v.course === course && v.batchCode === batch).forEach(v => set.add(v.id));
    return set;
  }, new Set());
  // get visible list via the same filter logic
  const visible = currentlyVisibleEligible();
  visible.forEach(v => { if (!alreadyPushed.has(v.id)) pushModePicked[v.id] = true; });
  renderEligible();
  updatePushSelSummary();
}

function currentlyVisibleEligible(){
  const cf = $('#eligCourseFilter')?.value || '';
  const bf = $('#eligBatchFilter')?.value || '';
  const pf = $('#eligProgressFilter')?.value || '';
  const sf = ($('#eligStatusFilter')?.value || '').toLowerCase().replace(/ /g,'_');
  return store.eligible.filter(v => {
    if (cf && v.course !== cf) return false;
    if (bf && v.batchCode !== bf) return false;
    if (pf && progressBucket(v.courseProgress) !== pf) return false;
    if (sf && v.placementStatus !== sf) return false;
    return true;
  });
}

function pushDoIt(){
  const o = store.openings.find(x => x.id === pushModeOpeningId);
  if (!o){ toast('Push mode lost — open opening again'); return; }
  const picks = Object.keys(pushModePicked);
  if (!picks.length){ toast('Select at least one student first'); return; }
  // for each picked student → add their course__batch to opening.lmsPushedTo
  const courseBatchKeys = new Set();
  picks.forEach(vid => {
    const v = store.eligible.find(x => x.id === vid);
    if (!v) return;
    courseBatchKeys.add(`${v.course}__${v.batchCode}`);
  });
  o.lmsPushedTo = o.lmsPushedTo || [];
  courseBatchKeys.forEach(k => { if (!o.lmsPushedTo.includes(k)) o.lmsPushedTo.push(k); });
  // log
  store.lmsPushLog = store.lmsPushLog || [];
  store.lmsPushLog.unshift({
    id: 'LMS' + String(store.lmsPushLog.length+1).padStart(3,'0'),
    date: todayISO, course: [...courseBatchKeys].map(k => k.split('__')[0]).join(', '),
    batchCode: [...courseBatchKeys].map(k => k.split('__')[1]).join(', '),
    openingIds:[o.id], studentCount: picks.length, pushedBy: 'Self'
  });
  toast(`✓ Pushed ${o.company} — ${o.role} to ${picks.length} student${picks.length>1?'s':''}`);
  exitPushMode();
  showView('openings');
}

/* ============================================================
   APPLIED OPENINGS VIEW (Student Coord)
   ============================================================ */
function renderApplied(){
  const list = store.openings.filter(o => (o.applicants || []).length > 0);
  $('#appliedCount').textContent = list.length;

  const q = ($('#appliedSearch')?.value || '').trim().toLowerCase();
  const filter = $('#appliedFilter')?.value || '';

  let filtered = list;
  if (q) filtered = filtered.filter(o => (o.company + ' ' + o.role).toLowerCase().includes(q));
  if (filter === 'pending') filtered = filtered.filter(o => {
    const sent = new Set((o.studentsSent||[]).map(s => s.studentId));
    return (o.applicants||[]).some(a => !sent.has(a.studentId));
  });
  if (filter === 'sent') filtered = filtered.filter(o => {
    const sent = new Set((o.studentsSent||[]).map(s => s.studentId));
    return (o.applicants||[]).length > 0 && (o.applicants||[]).every(a => sent.has(a.studentId));
  });

  $('#appliedList').innerHTML = filtered.length ? filtered.map(o => {
    const total = (o.applicants||[]).length;
    const sent = (o.studentsSent||[]).length;
    const pending = total - sent;
    const intvs = store.interviews.filter(s => s.openingId === o.id);
    const scheduled = intvs.filter(s => s.tag === 'scheduled').length;
    const selected = intvs.filter(s => s.tag === 'selected').length;
    return `
      <div class="strip" onclick="openAppliedDetail('${o.id}')">
        <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${o.id}</div></div>
        <div class="strip-cell" style="min-width: 150px;"><div class="lbl">Company</div><div class="val" style="color: var(--accent);">${o.company}</div></div>
        <div class="strip-cell" style="min-width: 130px;"><div class="lbl">Role</div><div class="val">${o.role}</div></div>
        <div class="strip-cell" style="min-width: 90px;"><div class="lbl">Location</div><div class="val muted">${o.location}</div></div>
        <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Applied</div><div class="val mono" style="color:var(--orange);">${total}${pending > 0 ? ` <span style="font-size:10px;">(${pending} new)</span>` : ''}</div></div>
        <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Sent HR</div><div class="val mono" style="color:var(--accent);">${sent}</div></div>
        <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Scheduled</div><div class="val mono" style="color:var(--cyan);">${scheduled}</div></div>
        <div class="strip-cell" style="min-width: 80px;"><div class="lbl">Selected</div><div class="val mono" style="color:var(--success);">${selected}</div></div>
        <div class="strip-cell grow">${tagPill(o.openingStatus)}</div>
      </div>`;
  }).join('') : emptyHtml('No openings have applicants yet. Push openings from Current Openings → students apply via their LMS → they show up here.');
}
document.addEventListener('input',  e => { if (e.target.id === 'appliedSearch') renderApplied(); });
document.addEventListener('change', e => { if (e.target.id === 'appliedFilter') renderApplied(); });

/* ============================================================
   APPLIED OPENING DETAIL — the workspace (applicants + pipeline tabs)
   ============================================================ */
let appliedPipelineTab = 'applied'; // applied | profile_sent | scheduled | attended | not_attended | selected | rejected | hold

function openAppliedDetail(id){
  const o = store.openings.find(x => x.id === id);
  if (!o) return;
  o.applicants = o.applicants || [];
  o.studentsSent = o.studentsSent || [];

  const sentMap = new Map(o.studentsSent.map(s => [s.studentId, s]));
  const applicants = o.applicants.map(a => ({ ...a, student: store.eligible.find(v => v.id === a.studentId) })).filter(a => a.student);
  const pendingApplicants = applicants.filter(a => !sentMap.has(a.studentId));

  // interview records for this opening, grouped by tag
  const intvs = store.interviews.filter(s => s.openingId === o.id);
  const byTag = {
    scheduled:    intvs.filter(s => s.tag === 'scheduled'),
    attended:     intvs.filter(s => s.tag === 'attended'),
    not_attended: intvs.filter(s => s.tag === 'not_attended' || s.tag === 'no_show'),
    selected:     intvs.filter(s => s.tag === 'selected'),
    rejected:     intvs.filter(s => s.tag === 'rejected'),
    hold:         intvs.filter(s => s.tag === 'hold'),
  };
  // profile_sent = sent to HR but no interview record yet
  const intvStudentIds = new Set(intvs.map(s => s.studentId));
  const profileSent = o.studentsSent
    .filter(s => !intvStudentIds.has(s.studentId))
    .map(s => ({ sentRec: s, student: store.eligible.find(v => v.id === s.studentId) }))
    .filter(x => x.student);

  const tabCounts = {
    applied:      pendingApplicants.length,
    profile_sent: profileSent.length,
    scheduled:    byTag.scheduled.length,
    attended:     byTag.attended.length,
    not_attended: byTag.not_attended.length,
    selected:     byTag.selected.length,
    rejected:     byTag.rejected.length,
    hold:         byTag.hold.length,
  };

  renderDetail({
    type:'applied',
    avatar: initials(o.company),
    name: `${o.company} · ${o.role}`,
    badge: `${tagPill(o.openingStatus)} <span class="tag tag-pending">${o.id}</span>`,
    metaParts: [o.location, o.ctc, `HR: ${o.hr}`, o.hrEmail],
    info: [
      ['Total Applied', applicants.length, 'mono'],
      ['Sent to HR', o.studentsSent.length, 'mono'],
      ['Pending to Forward', pendingApplicants.length, 'mono'],
      ['Interviews Scheduled', byTag.scheduled.length, 'mono'],
      ['Selected', byTag.selected.length, 'mono'],
      ['Rejected', byTag.rejected.length, 'mono'],
    ],
    extra: `
      <!-- APPLIED STUDENTS -->
      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header">
          <div class="panel-title">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Applied Students <small>(open each profile → check progress → send link to HR)</small>
          </div>
          <div style="font-family:var(--font-mono);font-size:12px;color:var(--text-muted);">${applicants.length} applied</div>
        </div>
        <div class="strip-list" style="max-height:none;">
          ${applicants.length ? applicants.map(a => {
            const v = a.student;
            const isSent = sentMap.has(v.id);
            const sentRec = sentMap.get(v.id);
            const bucket = progressBucket(v.courseProgress);
            return `
              <div class="strip" data-progress="${bucket}">
                <div class="strip-cell" style="min-width:60px;"><div class="lbl">ID</div><div class="val mono">${v.id}</div></div>
                <div class="strip-cell" style="min-width:140px;"><div class="lbl">Student</div><div class="val">${nameLink(v.name)}</div></div>
                <div class="strip-cell" style="min-width:130px;"><div class="lbl">Course</div><div class="val muted">${v.course}</div></div>
                <div class="strip-cell" style="min-width:100px;"><div class="lbl">Batch</div><div class="val mono">${v.batchCode}</div></div>
                <div class="strip-cell" style="min-width:90px;"><div class="lbl">Progress</div><div class="val mono">${v.courseProgress}%</div></div>
                <div class="strip-cell" style="min-width:100px;"><div class="lbl">Applied On</div><div class="val mono">${shortDate(a.appliedOn)}</div></div>
                <div class="strip-cell grow">${isSent ? `<span class="tag tag-profilesent"><span class="dot"></span>SENT · ${sentRec.linkType}</span>` : '<span class="tag tag-pending"><span class="dot"></span>PENDING</span>'}</div>
                <div class="strip-cell" style="display:flex;gap:6px;">
                  <button class="btn btn-sm btn-ghost" onclick="event.stopPropagation(); openEligibleDetail('${v.id}')">View Profile</button>
                  ${isSent
                    ? `<button class="btn btn-sm btn-ghost" disabled style="opacity:0.55;">✓ Sent</button>`
                    : `<button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openSendToHrModal('${o.id}','${v.id}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Send to HR
                      </button>`}
                </div>
              </div>`;
          }).join('') : emptyHtml('No applicants yet. Push this opening from Current Openings → students will see it on their LMS and can Apply.')}
        </div>
      </div>

      <!-- PIPELINE TABS -->
      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header">
          <div class="panel-title">Pipeline Status</div>
        </div>
        <div style="padding: 16px 18px;">
          <div class="pipeline-tabs">
            <button class="pipeline-tab active" data-applied-tab="applied">Applied <span class="ptcount">${tabCounts.applied}</span></button>
            <button class="pipeline-tab" data-applied-tab="profile_sent">Profile Sent <span class="ptcount">${tabCounts.profile_sent}</span></button>
            <button class="pipeline-tab" data-applied-tab="scheduled">Interview Scheduled <span class="ptcount">${tabCounts.scheduled}</span></button>
            <button class="pipeline-tab" data-applied-tab="attended">Attended <span class="ptcount">${tabCounts.attended}</span></button>
            <button class="pipeline-tab" data-applied-tab="not_attended">Not Attended <span class="ptcount">${tabCounts.not_attended}</span></button>
            <button class="pipeline-tab" data-applied-tab="selected">Selected <span class="ptcount">${tabCounts.selected}</span></button>
            <button class="pipeline-tab" data-applied-tab="rejected">Rejected <span class="ptcount">${tabCounts.rejected}</span></button>
            <button class="pipeline-tab" data-applied-tab="hold">Hold <span class="ptcount">${tabCounts.hold}</span></button>
          </div>
          <div id="appliedPipelineList"></div>
        </div>
      </div>

      ${renderCallHistory(o.callHistory, { showAssign: true, addEntry: true, companyName: o.company, targetType: 'opening', targetId: o.id })}
    `,
    actions: [
      { label:'View Opening', cls:'btn-ghost',
        icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
        onClick: `openOpeningDetail('${o.id}')` },
    ]
  });

  // wire tabs
  $$('.pipeline-tab[data-applied-tab]').forEach(t => {
    t.addEventListener('click', () => {
      $$('.pipeline-tab[data-applied-tab]').forEach(x => x.classList.remove('active'));
      t.classList.add('active');
      appliedPipelineTab = t.dataset.appliedTab;
      renderAppliedPipelineList(o, pendingApplicants, profileSent, byTag);
    });
  });
  appliedPipelineTab = 'applied';
  renderAppliedPipelineList(o, pendingApplicants, profileSent, byTag);
}

function renderAppliedPipelineList(o, pendingApplicants, profileSent, byTag){
  const wrap = $('#appliedPipelineList');
  if (!wrap) return;
  let html = '';
  if (appliedPipelineTab === 'applied'){
    if (!pendingApplicants.length) html = emptyHtml('No applicants pending to forward. All applicants have been sent to HR.');
    else html = pendingApplicants.map(a => {
      const v = a.student;
      return `
        <div class="strip" onclick="openEligibleDetail('${v.id}')">
          <div class="strip-cell" style="min-width:140px;"><div class="lbl">Student</div><div class="val">${nameLink(v.name)}</div></div>
          <div class="strip-cell" style="min-width:130px;"><div class="lbl">Course</div><div class="val muted">${v.course}</div></div>
          <div class="strip-cell" style="min-width:90px;"><div class="lbl">Progress</div><div class="val mono">${v.courseProgress}%</div></div>
          <div class="strip-cell" style="min-width:100px;"><div class="lbl">Applied</div><div class="val mono">${shortDate(a.appliedOn)}</div></div>
          <div class="strip-cell grow"><span class="tag tag-pending"><span class="dot"></span>PENDING</span></div>
          <div class="strip-cell"><button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openSendToHrModal('${o.id}','${v.id}')">Send to HR</button></div>
        </div>`;
    }).join('');
  } else if (appliedPipelineTab === 'profile_sent'){
    if (!profileSent.length) html = emptyHtml('No profiles in this stage. Profiles forwarded to HR but not yet scheduled appear here.');
    else html = profileSent.map(x => {
      const v = x.student; const s = x.sentRec;
      return `
        <div class="strip" onclick="openEligibleDetail('${v.id}')">
          <div class="strip-cell" style="min-width:140px;"><div class="lbl">Student</div><div class="val">${nameLink(v.name)}</div></div>
          <div class="strip-cell" style="min-width:130px;"><div class="lbl">Course</div><div class="val muted">${v.course}</div></div>
          <div class="strip-cell" style="min-width:120px;"><div class="lbl">Link Sent</div><div class="val">${s.linkType}</div></div>
          <div class="strip-cell" style="min-width:100px;"><div class="lbl">Sent On</div><div class="val mono">${shortDate(s.sentOn)}</div></div>
          <div class="strip-cell grow">${tagPill('profile_sent')}</div>
          <div class="strip-cell"><button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); openModal('schedule-interview', { name:'${escAttr(v.name)}', phone:'${escAttr(v.phone)}', email:'${escAttr(v.email)}', course:'${escAttr(v.course)}', duration:'${escAttr(v.duration)}', batchCode:'${escAttr(v.batchCode)}', batchType:'${escAttr(v.batchType)}', company:'${escAttr(o.company)}', role:'${escAttr(o.role)}', location:'${escAttr(o.location)}', __lock:['name','phone','email','course','duration','batchCode','company','role','location'] })">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/></svg> Schedule Interview
          </button></div>
        </div>`;
    }).join('');
  } else {
    // tag-based tabs read from interviews
    const list = byTag[appliedPipelineTab] || [];
    if (!list.length) html = emptyHtml(`No students in "${appliedPipelineTab.replace('_',' ')}" stage yet.`);
    else html = list.map(s => `
      <div class="strip" onclick="openInterviewDetail('${s.id}')">
        <div class="strip-cell" style="min-width:140px;"><div class="lbl">Student</div><div class="val">${nameLink(s.name)}</div></div>
        <div class="strip-cell" style="min-width:130px;"><div class="lbl">Course</div><div class="val muted">${s.course}</div></div>
        <div class="strip-cell" style="min-width:100px;"><div class="lbl">Date</div><div class="val mono">${shortDate(s.date)}</div></div>
        <div class="strip-cell" style="min-width:80px;"><div class="lbl">Time</div><div class="val mono">${s.time||'—'}</div></div>
        <div class="strip-cell" style="min-width:90px;"><div class="lbl">Mode</div><div class="val muted">${s.interviewMode||'—'}</div></div>
        <div class="strip-cell grow">${tagPill(s.tag)}</div>
      </div>`).join('');
  }
  wrap.innerHTML = html;
}

/* ============================================================
   SEND TO HR MODAL (Mailchimp integration · per-applicant)
   ============================================================ */
let pendingSendCtx = null; // { oid, vid }
function openSendToHrModal(oid, vid){
  pendingSendCtx = { oid, vid };
  const o = store.openings.find(x => x.id === oid);
  const v = store.eligible.find(x => x.id === vid);
  openModal('send-to-hr', {
    studentName: v?.name || '',
    company: o?.company || '',
    role: o?.role || '',
    hrEmail: o?.hrEmail || '',
    subject: `Applicant Profile — ${o?.role || ''} — ${v?.name || ''}`,
    linkType: 'combined',
    __lock: ['studentName','company','role','hrEmail']
  });
}

/* simulate Apply on LMS (kept for backwards compatibility — used from eligible student profile) */
function simulateApply(oid, vid){
  const o = store.openings.find(x => x.id === oid);
  const v = store.eligible.find(x => x.id === vid);
  if (!o || !v) return;
  o.applicants = o.applicants || [];
  if (!o.applicants.find(a => a.studentId === vid)){
    o.applicants.unshift({ studentId: vid, appliedOn: todayISO, status:'applied' });
  }
  toast(`${v.name} applied to ${o.company} · ${o.role}`);
  openEligibleDetail(vid);
}


function openEligibleDetail(id){
  const v = store.eligible.find(x => x.id === id);
  if (!v) return;
  const bucket = progressBucket(v.courseProgress);
  const intvHistory = store.interviews.filter(s => s.studentId === v.id || s.name === v.name);
  renderDetail({
    type:'eligible',
    avatar: initials(v.name),
    name: v.name,
    badge: `${tagPill(v.placementStatus)} <span class="tag tag-pending">${v.id}</span>`,
    metaParts: [v.course, `Batch ${v.batchCode}`, v.courseStatus || ''],
    info: [
      ['Phone', v.phone, 'mono'], ['Email', v.email], ['Address', v.address || '—'], ['Date of Birth', v.dob || '—', 'mono'],
      ['Course', v.course], ['Batch Code', v.batchCode, 'mono'], ['Batch Type', v.batchType],
      ['Start Date', fmtDate(v.startDate), 'mono'], ['End Date', fmtDate(v.endDate), 'mono'], ['Duration', v.duration],
      ['Assignments', v.assignmentsSubmitted || '—'], ['Attendance', v.attendance || '—'],
      ['Mid-term', `${v.midterm || '—'}${v.midterm?'%':''}`, 'mono'], ['Final Exam', `${v.finalExam || '—'}${v.finalExam?'%':''}`, 'mono'],
    ],
    extra: `
      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header"><div class="panel-title">Course Progress</div></div>
        <div style="padding: 18px 20px;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <div style="font-size:13px;color:var(--text-secondary);">Overall progress</div>
            <div style="font-family:var(--font-mono);font-weight:700;font-size:18px;color:var(--${bucket==='green'?'success':bucket==='orange'?'orange':'danger'});">${v.courseProgress}%</div>
          </div>
          <div class="progress-bar" style="height:10px;"><div class="progress-fill ${bucket}" style="width:${v.courseProgress}%;"></div></div>
          ${v.assessmentScores && v.assessmentScores.length ? `
            <div style="margin-top:18px;">
              <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.6px;color:var(--text-muted);font-weight:600;margin-bottom:8px;">Module-wise Assessment Scores</div>
              ${v.assessmentScores.map(m => `
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);">
                  <div style="font-weight:600;">${m.module}</div>
                  <div style="font-family:var(--font-mono);color:var(--${progressBucket(m.score)==='green'?'success':progressBucket(m.score)==='orange'?'orange':'danger'});">${m.score}%</div>
                </div>
              `).join('')}
            </div>
          ` : ''}
        </div>
      </div>

      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header"><div class="panel-title">Project Details</div></div>
        <div style="padding: 16px 20px;">
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:10px;">
            <div class="info-cell"><div class="lbl">Title</div><div class="val">${v.projectTitle || '—'}</div></div>
            <div class="info-cell"><div class="lbl">Tech Stack</div><div class="val">${v.projectTech || '—'}</div></div>
            <div class="info-cell"><div class="lbl">Status</div><div class="val">${v.projectStatus || '—'}</div></div>
          </div>
          ${v.projectDescription ? `<div style="font-size:13px;color:var(--text-secondary);line-height:1.6;">${v.projectDescription}</div>` : ''}
        </div>
      </div>

      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header"><div class="panel-title">Profile Links</div></div>
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px;">
          <div class="info-cell"><div class="lbl">Resume</div><div class="val"><a href="${v.resume||'#'}" target="_blank">${v.resume ? 'Open resume' : '—'}</a></div></div>
          <div class="info-cell"><div class="lbl">Portfolio</div><div class="val"><a href="${v.portfolio||'#'}" target="_blank">${v.portfolio || '—'}</a></div></div>
          <div class="info-cell"><div class="lbl">Project / GitHub</div><div class="val"><a href="${v.projectLink||'#'}" target="_blank">${v.projectLink || '—'}</a></div></div>
          <div class="info-cell" style="border-color: var(--accent);"><div class="lbl">Combined Link (for HR)</div><div class="val"><a href="${v.combinedLink||'#'}" target="_blank">${v.combinedLink || '—'}</a></div></div>
        </div>
      </div>

      ${(() => {
        // LMS feed simulator — openings pushed to this student's course/batch
        const myKey = `${v.course}__${v.batchCode}`;
        const feed = store.openings.filter(o => (o.lmsPushedTo||[]).includes(myKey));
        if (!feed.length) return '';
        return `
          <div class="panel" style="margin-bottom: 20px;">
            <div class="panel-header"><div class="panel-title">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/></svg>
              Student's LMS Feed <small>(openings pushed to ${v.batchCode} · students see this on their dashboard)</small>
            </div></div>
            <div style="padding: 12px 16px;">
              ${feed.map(o => {
                const hasApplied = (o.applicants || []).some(a => a.studentId === v.id);
                return `
                  <div class="lms-feed-card">
                    <div style="flex:1;min-width:0;">
                      <div style="font-weight:700;font-size:13.5px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        ${o.company}
                        <span style="color:var(--text-muted);font-weight:500;">·</span>
                        <span style="color:var(--text-secondary);font-weight:500;">${o.role}</span>
                        ${hasApplied ? '<span class="pill-applied">✓ APPLIED</span>' : ''}
                      </div>
                      <div style="font-size:11.5px;color:var(--text-secondary);margin-top:4px;display:flex;gap:12px;flex-wrap:wrap;">
                        <span>📍 ${o.location}</span>
                        <span>💰 ${o.ctc}</span>
                        <span>👥 ${o.openingsCount} slots</span>
                        <span>📅 Deadline ${fmtDate(o.applicationDeadline)}</span>
                      </div>
                    </div>
                    ${hasApplied
                      ? `<button class="btn btn-sm btn-ghost" disabled style="opacity:0.6;">Already Applied</button>`
                      : `<button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); simulateApply('${o.id}','${v.id}')">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg> Apply
                        </button>`}
                    <button class="btn btn-sm btn-ghost" onclick="event.stopPropagation(); openOpeningDetail('${o.id}')">View Opening</button>
                  </div>
                `;
              }).join('')}
            </div>
          </div>
        `;
      })()}

      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header"><div class="panel-title">Interview Timeline <small>(${intvHistory.length})</small></div></div>
        <div style="padding: 14px;">
          ${intvHistory.length ? intvHistory.map(s => `
            <div class="timeline-row ${s.tag === 'selected' ? 'green' : s.tag === 'rejected' ? 'red' : 'amber'}" onclick="openInterviewDetail('${s.id}')" style="cursor:pointer;">
              <div class="timeline-time">${shortDate(s.date)}</div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:13.5px;">${s.company} <span style="color:var(--text-muted);font-weight:500;">— ${s.role}</span></div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:3px;">${s.interviewMode} · ${s.time}</div>
              </div>
              ${tagPill(s.tag)}
            </div>
          `).join('') : emptyHtml('No interviews yet.')}
        </div>
      </div>

      ${renderCallHistory(v.callHistory || [], { showAssign: true, addEntry: true, companyName: v.name, targetType: 'student', targetId: v.id })}
    `,
    actions: [
      { label:'Edit', cls:'btn-ghost', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>' },
      { label:'Schedule Interview', cls:'btn-primary', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/></svg>',
        onClick: `openModal('schedule-interview', { name:'${escAttr(v.name)}', phone:'${escAttr(v.phone)}', email:'${escAttr(v.email)}', course:'${escAttr(v.course)}', duration:'${escAttr(v.duration)}', batchCode:'${escAttr(v.batchCode)}', batchType:'${escAttr(v.batchType)}', __lock:['name','phone','email','course','duration','batchCode'] })` },
    ]
  });
}

function openInterviewDetail(id){
  const s = store.interviews.find(x => x.id === id);
  if (!s) return;
  renderDetail({
    type:'interview',
    avatar: initials(s.name),
    name: s.name,
    badge: `${tagPill(s.tag)} <span class="tag tag-pending">${s.id}</span>`,
    metaParts: [s.company, s.role, s.location, `Assigned → Self`],
    info: [
      ['Phone', s.phone, 'mono'], ['Email', s.email], ['Course', s.course], ['Duration', s.duration],
      ['Batch Type', s.batchType], ['Batch Code', s.batchCode, 'mono'],
      ['Company', s.company], ['Role', s.role], ['Location', s.location],
      ['Interview Date', fmtDate(s.date), 'mono'], ['Interview Time', s.time || '—', 'mono'],
      ['Interview Mode', s.interviewMode || '—'], ['Meeting Link', s.meetingLink ? `<a href="${s.meetingLink}" target="_blank">Open link</a>` : '—'],
      ['HR Contact (Day-of)', s.hrContactDay || '—', 'mono'], ['Attended Count', s.count, 'mono'],
    ],
    extra: `
      ${s.notes ? `
        <div class="panel" style="margin-bottom: 20px;">
          <div class="panel-header"><div class="panel-title">Notes</div></div>
          <div style="padding: 18px 20px; color: var(--text-secondary); font-size: 13px; line-height: 1.6;">${s.notes}</div>
        </div>
      ` : ''}
      <div class="panel" style="margin-bottom: 20px;">
        <div class="panel-header"><div class="panel-title">Interview Timeline <small>(every company attended)</small></div></div>
        <div style="padding: 14px;">
          ${(s.timeline||[]).map(t => `
            <div class="timeline-row ${t.result === 'selected' ? 'green' : t.result === 'rejected' ? 'red' : ['not_attended','no_show','rescheduled'].includes(t.result) ? 'amber' : ''}">
              <div class="timeline-time">${t.date}</div>
              <div style="flex:1;">
                <div style="font-weight:700;font-size:13.5px;">${t.company} <span style="color:var(--text-muted);font-weight:500;">— ${t.role}</span></div>
                <div style="font-size:12px;color:var(--text-secondary);margin-top:3px;">${t.reason}</div>
              </div>
              ${tagPill(t.result)}
            </div>
          `).join('') || emptyHtml('No interview history yet.')}
        </div>
      </div>
      ${renderCallHistory(s.callHistory, { addEntry: true, companyName: s.company, targetType: 'interview', targetId: s.id })}
    `,
    actions: [
      { label:'Update Outcome', cls:'btn-primary', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polyline points="20 6 9 17 4 12"/></svg>',
        onClick: `openModal('interview-outcome', { id:'${s.id}', studentName:'${escAttr(s.name)}', companyName:'${escAttr(s.company)}', __lock:['studentName','companyName'] })` },
      { label:'Edit', cls:'btn-ghost', icon:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/></svg>' },
    ]
  });
}

/* ============================================================
   COUNTS + INIT
   ============================================================ */
function refreshNavCounts(){
  $('#navCompCount').textContent = store.companies.length;
  $('#navOpenCount').textContent = store.openings.length;
  $('#navEligCount').textContent = store.eligible.length;
  $('#navStuCount').textContent  = store.interviews.length;
  const appliedCount = store.openings.filter(o => (o.applicants||[]).length > 0).length;
  const navApp = $('#navAppliedCount'); if (navApp) navApp.textContent = appliedCount;
  const folTotal = currentRole === 'placement'
    ? (store.hiringFollowups||[]).length
    : store.followupsStudent.length + store.followupsCompany.length + store.leadsPending.length;
  $('#navFolCount').textContent  = folTotal;
}

function initAll(){
  $('#todayDate').textContent = todayLong;
  $('#todayDow').textContent = todayDow + ',';
  renderDashboard();
  renderCompanies();
  renderOpenings();
  renderEligible();
  renderInterviewStatus();
  renderInterviewStatus();
  renderApplied();
  renderFollowups();
  refreshNavCounts();
}
initAll();

// re-render derived views when the user lands on them
(function patchNav(){
  const _show = window.showView;
  window.showView = function(v){
    _show(v);
    if (v === 'status')    renderInterviewStatus();
    if (v === 'applied')   renderApplied();
    if (v === 'followups') renderFollowups();
    // exit push mode automatically if user navigates away from Eligible
    if (v !== 'eligible' && pushModeOpeningId){
      pushModeOpeningId = null;
      pushModePicked = {};
      const b = $('#pushModeBanner'); if (b) b.classList.add('hidden');
    }
  };
})();
</script>
</body>
</html>
