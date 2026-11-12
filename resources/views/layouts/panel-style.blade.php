    <style>
        :root {
            --teal-900: #0b3d3a;
            --teal-800: #0f4c46;
            --teal-700: #0f766e;
            --teal-600: #0d9488;
            --teal-50: #f0fdfa;
            --amber-500: #f59e0b;
            --amber-100: #fef3c7;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --danger: #dc2626;
            --success: #16a34a;
            --radius: 13px;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, .06);
            --shadow: 0 12px 30px -14px rgba(15, 23, 42, .3);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--slate-50);
            color: var(--slate-900);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }

        /* Sidebar */
        .sidebar {
            width: 252px;
            flex-shrink: 0;
            background: linear-gradient(180deg, var(--teal-900), #08211f);
            color: #b7d6d2;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 11px;
            padding: 20px 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            text-decoration: none;
        }
        .brand-mark {
            width: 38px; height: 38px; border-radius: 11px;
            background: linear-gradient(135deg, var(--teal-600), #2dd4bf);
            display: grid; place-items: center;
            box-shadow: 0 8px 18px -8px rgba(45, 212, 191, .8);
            flex-shrink: 0;
        }
        .brand-mark svg { width: 20px; height: 20px; }
        .sidebar-brand strong { display: block; color: #fff; font-size: 14px; letter-spacing: -.01em; line-height: 1.2; }
        .sidebar-brand small { color: #7fb3ad; font-size: 10px; text-transform: uppercase; letter-spacing: .08em; }

        .sidebar-section { padding: 18px 12px 6px; }
        .sidebar-label {
            font-size: 10px; text-transform: uppercase; letter-spacing: .12em;
            color: #6b9a94; font-weight: 700; padding: 0 10px 8px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 9px;
            font-size: 13px; font-weight: 600; text-decoration: none;
            color: #b7d6d2; margin-bottom: 2px;
            transition: background .16s, color .16s;
        }
        .nav-item:hover { background: rgba(255, 255, 255, .08); color: #fff; }
        .nav-item.active { background: rgba(45, 212, 191, .16); color: #fff; box-shadow: inset 3px 0 0 #2dd4bf; }
        .nav-item.disabled { opacity: .45; cursor: not-allowed; }
        .nav-item .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; opacity: .6; flex-shrink: 0; }
        .nav-item em {
            margin-left: auto; font-style: normal; font-size: 9px; font-weight: 800;
            background: rgba(255, 255, 255, .12); padding: 2px 6px; border-radius: 999px;
            letter-spacing: .06em; text-transform: uppercase;
        }

        .sidebar-foot { margin-top: auto; padding: 16px 20px; border-top: 1px solid rgba(255, 255, 255, .09); font-size: 11.5px; color: #6b9a94; }

        /* Main */
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid var(--slate-200);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            position: sticky; top: 0; z-index: 20;
        }
        .topbar h1 { font-size: 17px; letter-spacing: -.01em; }
        .topbar .crumb { font-size: 11.5px; color: var(--slate-500); margin-top: 2px; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .user-chip {
            display: flex; align-items: center; gap: 10px;
            background: var(--slate-50); border: 1px solid var(--slate-200);
            border-radius: 10px; padding: 5px 5px 5px 11px;
        }
        .user-chip .meta { display: flex; flex-direction: column; line-height: 1.2; }
        .user-chip .meta strong { font-size: 12px; }
        .user-chip .meta small { font-size: 10px; color: var(--slate-500); text-transform: uppercase; letter-spacing: .06em; }

        .content { padding: 28px; flex: 1; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            border: 1px solid transparent; border-radius: 9px;
            padding: 9px 15px; font-family: inherit; font-size: 12.5px; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: background .16s, transform .12s, border-color .16s;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: var(--teal-700); color: #fff; box-shadow: 0 8px 18px -10px rgba(15, 118, 110, .8); }
        .btn-primary:hover { background: var(--teal-800); }
        .btn-outline { background: #fff; border-color: var(--slate-300); color: var(--slate-700); }
        .btn-outline:hover { background: var(--slate-100); }
        .btn-danger { background: transparent; color: var(--danger); border-color: #fecaca; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-sm { padding: 6px 10px; font-size: 11.5px; border-radius: 8px; }
        .btn-block { width: 100%; }

        /* Flash */
        .flash {
            padding: 11px 15px; border-radius: 10px; font-size: 12.5px; font-weight: 600;
            margin-bottom: 18px; border: 1px solid transparent;
        }
        .flash-success { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .flash-error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .flash-info { background: #e0f2fe; color: #075985; border-color: #bae6fd; }

        /* Cards */
        .card { background: #fff; border: 1px solid var(--slate-200); border-radius: var(--radius); box-shadow: var(--shadow-sm); }
        .card-head {
            padding: 18px 20px; border-bottom: 1px solid var(--slate-200);
            display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
        }
        .card-head h2 { font-size: 15px; letter-spacing: -.01em; }
        .card-head p { font-size: 12px; color: var(--slate-500); margin-top: 3px; }
        .card-body { padding: 20px; }

        /* Table */
        .table-wrap { overflow-x: auto; }
        table.data { width: 100%; border-collapse: collapse; font-size: 12.8px; }
        table.data thead th {
            text-align: left; font-size: 10.5px; text-transform: uppercase; letter-spacing: .08em;
            color: var(--slate-500); font-weight: 800;
            padding: 11px 14px; background: var(--slate-50); border-bottom: 1px solid var(--slate-200);
            white-space: nowrap;
        }
        table.data tbody td { padding: 12px 14px; border-bottom: 1px solid var(--slate-100); vertical-align: middle; }
        table.data tbody tr:hover { background: #fbfdfd; }
        table.data tbody tr:last-child td { border-bottom: 0; }
        .thumb { width: 60px; height: 42px; border-radius: 8px; object-fit: cover; border: 1px solid var(--slate-200); display: block; }
        .thumb-empty {
            width: 60px; height: 42px; border-radius: 8px; border: 1px dashed var(--slate-300);
            display: grid; place-items: center; font-size: 9px; color: var(--slate-500); background: var(--slate-50);
        }
        .cell-title { font-weight: 700; color: var(--slate-900); }
        .cell-sub { font-size: 11px; color: var(--slate-500); margin-top: 2px; }
        .money { font-weight: 700; color: var(--teal-800); white-space: nowrap; }
        .actions { display: flex; gap: 6px; flex-wrap: wrap; }

        /* Manajemen user */
        .toolbar-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 14px; flex-wrap: wrap; margin-bottom: 18px;
        }
        .level-tag {
            display: inline-flex; align-items: center;
            font-size: 10.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
            padding: 3px 9px; border-radius: 999px; border: 1px solid transparent; white-space: nowrap;
        }
        .level-admin { background: #ede9fe; color: #5b21b6; border-color: #ddd6fe; }
        .level-bendahara { background: #e0f2fe; color: #075985; border-color: #bae6fd; }
        .level-direktur { background: #fef3c7; color: #92400e; border-color: #fde68a; }
        .level-konsumen { background: var(--teal-50); color: var(--teal-700); border-color: #99f6e4; }
        .status-aktif { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .status-nonaktif { background: var(--slate-100); color: var(--slate-500); border-color: var(--slate-200); }

        .badge {
            display: inline-flex; align-items: center;
            font-size: 10.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
            padding: 3px 8px; border-radius: 999px;
            background: var(--teal-50); color: var(--teal-700); border: 1px solid #99f6e4;
        }

        /* Empty state */
        .empty {
            padding: 46px 20px; text-align: center; color: var(--slate-500); font-size: 13px;
        }

        /* Pager */
        .pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; flex-wrap: wrap; }
        .pager-info { font-size: 12px; color: var(--slate-500); }
        .pager-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 700; text-decoration: none;
            border: 1px solid var(--slate-300); border-radius: 8px; padding: 7px 12px; color: var(--slate-700);
        }
        .pager-link:hover { background: var(--slate-100); }
        .pager-link.disabled { opacity: .45; pointer-events: none; }

        /* Forms */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }
        .field { margin-bottom: 16px; }
        .field.full { grid-column: 1 / -1; }
        .field label { display: block; font-size: 12px; font-weight: 700; color: var(--slate-700); margin-bottom: 6px; }
        .field input, .field textarea, .field select {
            width: 100%; border: 1px solid var(--slate-300); border-radius: 9px;
            padding: 10px 12px; font-family: inherit; font-size: 13px; color: var(--slate-900);
            background: #fff; transition: border-color .16s, box-shadow .16s;
        }
        .field textarea { resize: vertical; min-height: 92px; }
        .field input:focus, .field textarea:focus, .field select:focus {
            outline: none; border-color: var(--teal-600); box-shadow: 0 0 0 3px rgba(13, 148, 136, .14);
        }
        .field .error { margin-top: 5px; font-size: 11.5px; color: var(--danger); font-weight: 600; }
        .field .hint { margin-top: 5px; font-size: 11px; color: var(--slate-500); }
        .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--slate-200); }

        .current-photo { margin-top: 8px; display: flex; align-items: center; gap: 12px; }
        .current-photo img { width: 110px; height: 74px; object-fit: cover; border-radius: 9px; border: 1px solid var(--slate-200); }
        .current-photo span { font-size: 11.5px; color: var(--slate-500); }

        .checkbox-field {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 14px; border: 1px solid var(--slate-200);
            border-radius: 10px; background: var(--slate-50); cursor: pointer;
        }
        .field .checkbox-field input[type="checkbox"] {
            width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0; accent-color: var(--teal-700);
        }
        .checkbox-field .text strong { display: block; font-size: 12.5px; color: var(--slate-900); }
        .checkbox-field .text span { font-size: 11.5px; color: var(--slate-500); }

        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: static; }
            .sidebar-section { display: flex; flex-wrap: wrap; gap: 4px; padding: 12px; }
            .sidebar-label, .sidebar-foot { display: none; }
            .nav-item { margin: 0; }
            .content { padding: 18px; }
            .topbar { padding: 12px 18px; }
        }
        /* Status badge */
        .status {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 10px; font-weight: 800; letter-spacing: .05em; text-transform: uppercase;
            padding: 3px 9px; border-radius: 999px; border: 1px solid transparent; white-space: nowrap;
        }
        .status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .status-belum_bayar { background: var(--slate-100); color: var(--slate-500); border-color: var(--slate-200); }
        .status-menunggu { background: var(--amber-100); color: #92400e; border-color: #fde68a; }
        .status-valid { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .status-ditolak { background: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* Filter tabs */
        .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
        .filter-tab {
            font-size: 12px; font-weight: 700; text-decoration: none;
            padding: 7px 12px; border-radius: 8px; border: 1px solid var(--slate-200);
            background: #fff; color: var(--slate-700); transition: background .16s, border-color .16s;
        }
        .filter-tab:hover { background: var(--slate-100); }
        .filter-tab.active { background: var(--teal-700); border-color: var(--teal-700); color: #fff; }
        .filter-tab .count {
            display: inline-block; margin-left: 5px; font-size: 10px; font-weight: 800;
            background: rgba(15, 23, 42, .08); border-radius: 999px; padding: 1px 6px;
        }
        .filter-tab.active .count { background: rgba(255, 255, 255, .22); }

        /* Search */
        .search-form { display: flex; gap: 8px; align-items: center; }
        .search-form input {
            border: 1px solid var(--slate-300); border-radius: 9px; padding: 8px 12px;
            font-family: inherit; font-size: 12.5px; min-width: 220px; background: #fff; color: var(--slate-900);
        }
        .search-form input:focus { outline: none; border-color: var(--teal-600); box-shadow: 0 0 0 3px rgba(13,148,136,.14); }

        /* Laporan */
        .report-filter { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
        .report-filter .field { margin-bottom: 0; flex: 1 1 160px; min-width: 160px; }
        .report-filter .filter-actions { display: flex; gap: 8px; flex: 0 0 auto; }
        .report-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }
        .stat-card {
            background: #fff; border: 1px solid var(--slate-200); border-radius: 12px;
            padding: 15px 17px; box-shadow: var(--shadow-sm);
        }
        .stat-card .label {
            font-size: 10.5px; font-weight: 800; letter-spacing: .06em;
            text-transform: uppercase; color: var(--slate-500);
        }
        .stat-card .value { margin-top: 5px; font-size: 21px; font-weight: 800; letter-spacing: -.02em; color: var(--teal-800); }
        .stat-card .sub { margin-top: 3px; font-size: 11px; color: var(--slate-500); }
        .report-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Detail layout */
        .detail-columns { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
        .detail-list { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 22px; }
        .detail-list .full { grid-column: 1 / -1; }
        .detail-list .label { display: block; font-size: 10.5px; text-transform: uppercase; letter-spacing: .06em; font-weight: 800; color: var(--slate-500); margin-bottom: 2px; }
        .detail-list .value { font-size: 13px; font-weight: 700; color: var(--slate-900); }
        .detail-list .value.money { font-size: 18px; color: var(--teal-800); }
        .detail-list p.value { font-weight: 500; color: var(--slate-700); }

        .proof-box { border: 1px solid var(--slate-200); border-radius: 11px; padding: 12px; background: var(--slate-50); }
        .proof-box img { width: 100%; max-height: 320px; object-fit: contain; border-radius: 8px; background: #fff; }
        .proof-box .proof-file { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: var(--teal-700); text-decoration: none; }
        .proof-box .proof-file:hover { text-decoration: underline; }

        .decision-box { border: 1px solid var(--slate-200); border-radius: 12px; padding: 16px; }
        .decision-box h3 { font-size: 13.5px; }
        .decision-box p { font-size: 11.5px; color: var(--slate-500); margin-top: 3px; }
        .decision-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; }
        .decision-actions .btn { width: 100%; }

        @media (max-width: 980px) {
            .detail-columns { grid-template-columns: 1fr; }
            .report-stats { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .detail-list { grid-template-columns: 1fr; }
            .detail-list .full { grid-column: auto; }
            .form-grid { grid-template-columns: 1fr; }
            .user-chip .meta { display: none; }
            .search-form { width: 100%; }
            .search-form input { min-width: 0; flex: 1; }
            .report-stats { grid-template-columns: 1fr; }
            .report-filter .filter-actions { width: 100%; }
            .report-filter .filter-actions .btn { flex: 1; }
        }
        @stack('styles')
    </style>
