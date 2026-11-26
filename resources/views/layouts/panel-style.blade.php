    <style>
        :root {
            /* Scandinavian Minimalist (Airbnb Style) Admin/User Panel */
            --bg-body: #f7f7f7;
            --bg-surface: #ffffff;
            --primary: #ff385c;
            --primary-hover: #e00b41;
            --primary-light: #fff0f3;
            --neutral-900: #222222;
            --neutral-800: #333333;
            --neutral-700: #555555;
            --neutral-500: #717171;
            --neutral-300: #b0b0b0;
            --neutral-200: #dddddd;
            --neutral-100: #ebebeb;
            --neutral-50: #f7f7f7;
            --danger: #c13515;
            --success: #008a05;
            --radius-sm: 8px;
            --radius: 14px;
            --radius-lg: 20px;
            --radius-full: 9999px;
            --shadow-subtle: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-body);
            color: var(--neutral-900);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }

        /* Modern Scandinavian Sidebar */
        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #ffffff;
            border-right: 1px solid #ebebeb;
            color: #222222;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 22px 20px 20px;
            border-bottom: 1px solid #f0f0f0;
            text-decoration: none;
        }
        .brand-mark {
            width: 38px; height: 38px; border-radius: 12px;
            background: #ff385c;
            display: grid; place-items: center;
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.28);
            flex-shrink: 0;
        }
        .brand-mark svg { width: 20px; height: 20px; stroke: #fff; }
        .sidebar-brand strong { display: block; color: #222222; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; }
        .sidebar-brand small { color: #ff385c; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; }

        .sidebar-section { padding: 20px 14px 6px; }
        .sidebar-label {
            font-size: 10px; text-transform: uppercase; letter-spacing: .12em;
            color: #888888; font-weight: 800; padding: 0 12px 10px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 12px;
            font-size: 13.5px; font-weight: 600; text-decoration: none;
            color: #555555; margin-bottom: 3px;
            transition: all .16s ease;
        }
        .nav-item:hover { background: #f7f7f7; color: #222222; }
        .nav-item.active {
            background: #222222;
            color: #ffffff;
            font-weight: 700;
        }
        .nav-item.disabled { opacity: .45; cursor: not-allowed; }
        .nav-item .dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: currentColor; opacity: .7; flex-shrink: 0;
        }
        .nav-item.active .dot { background: #ff385c; opacity: 1; }
        .nav-item em {
            margin-left: auto; font-style: normal; font-size: 9.5px; font-weight: 800;
            background: #f0f0f0; color: #555555; padding: 2px 7px; border-radius: var(--radius-full);
            letter-spacing: .06em; text-transform: uppercase;
        }
        .nav-item.active em {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .sidebar-foot {
            margin-top: auto; padding: 18px 20px;
            border-top: 1px solid #f0f0f0; font-size: 11.5px; color: #888888;
        }

        /* Main Workspace */
        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #ebebeb;
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            position: sticky; top: 0; z-index: 20;
        }
        .topbar h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #222222;
            letter-spacing: -.02em;
        }
        .topbar .crumb { font-size: 12px; color: #717171; margin-top: 2px; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .user-chip {
            display: flex; align-items: center; gap: 12px;
            background: #ffffff; border: 1px solid #dddddd;
            border-radius: var(--radius-full); padding: 5px 6px 5px 14px;
            box-shadow: var(--shadow-subtle);
        }
        .user-chip .meta { display: flex; flex-direction: column; line-height: 1.2; text-align: left; }
        .user-chip .meta strong { font-size: 12.5px; color: #222222; font-weight: 700; }
        .user-chip .meta small { font-size: 10px; color: #717171; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }

        .content { padding: 32px; flex: 1; }

        /* Unified Airbnb Action Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            border: 1px solid transparent; border-radius: var(--radius-full);
            padding: 9px 18px; font-family: inherit; font-size: 13px; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: all .16s ease;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: #ff385c;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
        }
        .btn-primary:hover {
            background: #e00b41;
            box-shadow: 0 6px 18px rgba(255, 56, 92, 0.4);
        }
        .btn-outline {
            background: #ffffff;
            border-color: #dddddd;
            color: #222222;
        }
        .btn-outline:hover {
            border-color: #222222;
            background: #f7f7f7;
        }
        .btn-danger {
            background: transparent;
            color: var(--danger);
            border-color: #ffd8d0;
        }
        .btn-danger:hover { background: #fee2e2; border-color: #fca5a5; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }
        .btn-block { width: 100%; }

        /* Flash Alerts */
        .flash {
            padding: 13px 18px; border-radius: 12px; font-size: 13px; font-weight: 600;
            margin-bottom: 22px; border: 1px solid transparent;
        }
        .flash-success { background: #e6f7ef; color: #008a05; border-color: #a3e6be; }
        .flash-error { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .flash-info { background: #f0f4f8; color: #1e293b; border-color: #cbd5e1; }

        /* Clean White Cards */
        .card {
            background: #ffffff;
            border: 1px solid #ebebeb;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-subtle);
        }
        .card-head {
            padding: 20px 24px; border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;
        }
        .card-head h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 800;
            color: #222222;
            letter-spacing: -.01em;
        }
        .card-head p { font-size: 12.5px; color: #717171; margin-top: 3px; }
        .card-body { padding: 24px; }

        /* Table Styling */
        .table-wrap { overflow-x: auto; }
        table.data { width: 100%; border-collapse: collapse; font-size: 13px; }
        table.data thead th {
            text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
            color: #717171; font-weight: 800;
            padding: 12px 16px; background: #fafafa; border-bottom: 1px solid #ebebeb;
            white-space: nowrap;
        }
        table.data tbody td { padding: 14px 16px; border-bottom: 1px solid #f2f2f2; vertical-align: middle; }
        table.data tbody tr:hover { background: #fafafa; }
        table.data tbody tr:last-child td { border-bottom: 0; }
        .thumb { width: 64px; height: 46px; border-radius: 10px; object-fit: cover; border: 1px solid #e0e0e0; display: block; }
        .thumb-empty {
            width: 64px; height: 46px; border-radius: 10px; border: 1px dashed #cccccc;
            display: grid; place-items: center; font-size: 9.5px; color: #888888; background: #f7f7f7;
        }
        .cell-title { font-weight: 700; color: #222222; }
        .cell-sub { font-size: 11.5px; color: #717171; margin-top: 2px; }
        .money { font-weight: 800; color: #222222; white-space: nowrap; font-family: 'Outfit', sans-serif; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* Neutral Badges & Role Tags (No cyan/light blue) */
        .toolbar-row {
            display: flex; align-items: center; justify-content: space-between;
            gap: 14px; flex-wrap: wrap; margin-bottom: 20px;
        }
        .level-tag {
            display: inline-flex; align-items: center;
            font-size: 10.5px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
            padding: 4px 10px; border-radius: var(--radius-full); border: 1px solid transparent; white-space: nowrap;
        }
        .level-admin { background: #222222; color: #ffffff; }
        .level-bendahara { background: #fff8f6; color: #c13515; border-color: #ffd8d0; }
        .level-direktur { background: #f7f7f7; color: #222222; border-color: #dddddd; }
        .level-konsumen { background: #f7f7f7; color: #555555; border-color: #ebebeb; }
        .status-aktif { background: #e6f7ef; color: #008a05; border-color: #bbf7d0; }
        .status-nonaktif { background: #f7f7f7; color: #888888; border-color: #e5e5e5; }

        .badge {
            display: inline-flex; align-items: center;
            font-size: 11px; font-weight: 700; letter-spacing: .03em;
            padding: 4px 10px; border-radius: var(--radius-full);
            background: #f7f7f7; color: #444444; border: 1px solid #e0e0e0;
        }

        /* Empty state */
        .empty {
            padding: 50px 20px; text-align: center; color: #717171; font-size: 13.5px;
        }

        /* Pagination */
        .pager { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 20px; flex-wrap: wrap; }
        .pager-info { font-size: 12.5px; color: #717171; }
        .pager-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12.5px; font-weight: 700; text-decoration: none;
            border: 1px solid #dddddd; border-radius: var(--radius-full); padding: 7px 16px; color: #222222;
        }
        .pager-link:hover { background: #f7f7f7; border-color: #222222; }
        .pager-link.disabled { opacity: .4; pointer-events: none; }

        /* Forms Styling */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
        .field { margin-bottom: 18px; }
        .field.full { grid-column: 1 / -1; }
        .field label { display: block; font-size: 12.5px; font-weight: 700; color: #222222; margin-bottom: 6px; }
        .field input, .field textarea, .field select {
            width: 100%; border: 1.5px solid #dddddd; border-radius: 12px;
            padding: 11px 14px; font-family: inherit; font-size: 13.5px; color: #222222;
            background: #fff; transition: border-color .16s, box-shadow .16s;
        }
        .field textarea { resize: vertical; min-height: 96px; }
        .field input:focus, .field textarea:focus, .field select:focus {
            outline: none; border-color: #222222; box-shadow: 0 0 0 3px rgba(0, 0, 0, .08);
        }
        .field .error { margin-top: 5px; font-size: 11.5px; color: var(--danger); font-weight: 600; }
        .field .hint { margin-top: 5px; font-size: 11px; color: #888888; }
        .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 12px; padding-top: 18px; border-top: 1px solid #f0f0f0; }

        .current-photo { margin-top: 8px; display: flex; align-items: center; gap: 14px; }
        .current-photo img { width: 110px; height: 74px; object-fit: cover; border-radius: 10px; border: 1px solid #e0e0e0; }
        .current-photo span { font-size: 12px; color: #717171; }

        .checkbox-field {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 12px 16px; border: 1.5px solid #ebebeb;
            border-radius: 12px; background: #ffffff; cursor: pointer;
        }
        .field .checkbox-field input[type="checkbox"] {
            width: 16px; height: 16px; margin-top: 2px; flex-shrink: 0; accent-color: #ff385c;
        }
        .checkbox-field .text strong { display: block; font-size: 13px; color: #222222; }
        .checkbox-field .text span { font-size: 12px; color: #717171; }

        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: static; border-right: none; border-bottom: 1px solid #ebebeb; }
            .sidebar-section { display: flex; flex-wrap: wrap; gap: 6px; padding: 12px; }
            .sidebar-label, .sidebar-foot { display: none; }
            .nav-item { margin: 0; }
            .content { padding: 20px; }
            .topbar { padding: 14px 20px; }
        }

        /* Report Filter Grid (4 Columns + Actions) */
        .report-filter {
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 16px;
            align-items: flex-end;
        }
        .report-filter .field {
            margin-bottom: 0;
        }
        .report-filter .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            padding-top: 0;
            border-top: none;
            margin-top: 0;
        }

        /* Report Stats Row (Airbnb Scandinavian Cards) */
        .report-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }
        .stat-card {
            background: #ffffff;
            border: 1px solid #ebebeb;
            border-radius: var(--radius-lg);
            padding: 20px 22px;
            box-shadow: var(--shadow-subtle);
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        .stat-card .label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #717171;
            margin-bottom: 6px;
        }
        .stat-card .value {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #222222;
            letter-spacing: -.02em;
            line-height: 1.2;
        }
        .stat-card .sub {
            font-size: 12px;
            color: #888888;
            margin-top: 4px;
        }

        @media (max-width: 1024px) {
            .report-filter { grid-template-columns: repeat(2, 1fr); }
            .report-stats { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            .report-filter { grid-template-columns: 1fr; }
            .report-stats { grid-template-columns: 1fr; }
        }

        /* Sidebar Collapsible / Toggle Functionality */
        .sidebar {
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), padding 0.25s;
            overflow-x: hidden;
            white-space: nowrap;
        }

        /* Tombol Toggle Hamburger di Topbar */
        .btn-toggle-sidebar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #ebebeb;
            background: #ffffff;
            color: #222222;
            cursor: pointer;
            transition: all 0.16s ease;
            flex-shrink: 0;
            margin-right: 8px;
        }
        .btn-toggle-sidebar:hover {
            background: #f7f7f7;
            border-color: #222222;
            color: #ff385c;
        }
        .btn-toggle-sidebar svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.2;
        }

        /* Saat sidebar diciutkan / collapsed */
        body.sidebar-collapsed .sidebar {
            width: 78px;
        }
        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 22px 10px 20px;
        }
        body.sidebar-collapsed .sidebar-brand span:not(.brand-mark) {
            display: none;
        }
        body.sidebar-collapsed .sidebar-label {
            display: none;
        }
        body.sidebar-collapsed .sidebar-section {
            padding: 16px 10px;
        }
        body.sidebar-collapsed .nav-item {
            justify-content: center;
            padding: 12px;
            font-size: 0;
        }
        body.sidebar-collapsed .nav-item .dot {
            width: 10px;
            height: 10px;
            margin: 0;
        }
        body.sidebar-collapsed .nav-item em {
            display: none;
        }
        body.sidebar-collapsed .sidebar-foot {
            display: none;
        }

        /* Mobile overlay */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 30;
        }
        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 260px !important;
                height: 100vh;
                z-index: 40;
                transform: translateX(-100%);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
            }
            body.sidebar-open .sidebar-backdrop {
                display: block;
            }
        }

        /* General Status Badges */
        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: capitalize;
            padding: 5px 12px;
            border-radius: var(--radius-full);
            border: 1px solid transparent;
            white-space: nowrap;
        }
        .status-belum_bayar { background: #f5f5f5; color: #555555; border-color: #dddddd; }
        .status-menunggu { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .status-valid { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .status-ditolak { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

        /* Detail 2 Columns Layout for Show Pages */
        .detail-columns {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            align-items: start;
        }

        /* Detail List Grid */
        .detail-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 24px;
        }
        .detail-list .full {
            grid-column: 1 / -1;
        }
        .detail-list .label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #717171;
            margin-bottom: 4px;
        }
        .detail-list .value {
            font-size: 14px;
            font-weight: 700;
            color: #222222;
        }
        .detail-list p.value {
            font-weight: 500;
            color: #333333;
            line-height: 1.5;
            margin-top: 4px;
        }

        /* Decision Box for Validation */
        .decision-box {
            background: #fafafa;
            border: 1px solid #ebebeb;
            border-radius: 14px;
            padding: 18px;
        }
        .decision-box h3 {
            font-size: 14px;
            font-weight: 800;
            color: #222222;
            margin-bottom: 4px;
        }
        .decision-box p {
            font-size: 12.5px;
            color: #717171;
        }
        .decision-actions {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        /* Proof File / Image Box */
        .proof-box {
            border: 1px solid #ebebeb;
            border-radius: 12px;
            padding: 12px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .proof-box img {
            max-width: 100%;
            max-height: 320px;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #ebebeb;
        }
        .proof-file {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 700;
            color: #ff385c;
            text-decoration: none;
        }
        .proof-file:hover {
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            .detail-columns {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 600px) {
            .detail-list {
                grid-template-columns: 1fr;
            }
        }

        /* Universal Filter Tabs (Pill Buttons) */
        .filter-tabs {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            color: #555555;
            background: #ffffff;
            border: 1px solid #ebebeb;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: var(--shadow-subtle);
        }
        .filter-tab:hover {
            background: #f7f7f7;
            color: #222222;
            border-color: #dddddd;
            transform: translateY(-1px);
        }
        .filter-tab.active {
            background: #222222 !important;
            color: #ffffff !important;
            border-color: #222222 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        .filter-tab .count {
            background: rgba(0, 0, 0, 0.08);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 700;
        }
        .filter-tab.active .count {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
        }

        /* Search Form (Pill Search Input + Button) */
        .search-form {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }
        .search-form input[type="search"],
        .search-form input[type="text"] {
            border: 1.5px solid #dddddd;
            border-radius: var(--radius-full);
            padding: 8px 16px;
            font-family: inherit;
            font-size: 13px;
            color: #222222;
            background: #ffffff;
            min-width: 260px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-form input:focus {
            border-color: #222222;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
        }
        .search-form button {
            border-radius: var(--radius-full);
            padding: 8px 16px;
            font-size: 12.5px;
            font-weight: 700;
        }

        /* Toolbar Row Container */
        .toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 4px 0;
        }

        /* Table Action Buttons Formatting */
        table.data td .actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        table.data td .actions .btn {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: var(--radius-full);
        }

        /* Status & Level Badges Refinement */
        .level-tag, .status {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }
    </style>
