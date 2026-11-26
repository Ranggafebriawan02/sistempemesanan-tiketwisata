@extends('layouts.bendahara')

@section('title', 'Manajemen Pemesanan')
@section('breadcrumb', 'Bendahara / Manajemen Pemesanan')

@push('styles')
<style>
    /* Styling Filter Tab Status & Quick Period */
    .filter-tabs {
        display: flex;
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
        background: #f7f7f7;
        border: 1px solid #ebebeb;
        transition: all 0.2s;
        cursor: pointer;
    }
    .filter-tab:hover {
        background: #ebebeb;
        color: #222222;
    }
    .filter-tab.active {
        background: #222222;
        color: #ffffff;
        border-color: #222222;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .filter-tab .count {
        background: rgba(0, 0, 0, 0.08);
        padding: 2px 7px;
        border-radius: var(--radius-full);
        font-size: 11px;
        font-weight: 700;
    }
    .filter-tab.active .count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Period Filter Pills */
    .period-tabs {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .period-tab {
        padding: 6px 14px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #dddddd;
        background: #ffffff;
        color: #555555;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .period-tab:hover {
        border-color: #222222;
        color: #222222;
        background: #f7f7f7;
    }
    .period-tab.active {
        background: #ff385c;
        border-color: #ff385c;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(255, 56, 92, 0.25);
    }

    /* Filter Row Layout */
    .filter-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .filter-row-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .filter-row-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px dashed #ebebeb;
    }

    /* Ajax Search Input */
    .search-input-wrap {
        position: relative;
        min-width: 280px;
        max-width: 100%;
    }
    .search-input-wrap input {
        width: 100%;
        padding: 9px 38px 9px 14px;
        border: 1.5px solid #dddddd;
        border-radius: var(--radius-full);
        font-family: inherit;
        font-size: 13px;
        color: #222222;
        background: #ffffff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input-wrap input:focus {
        border-color: #222222;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
    }
    .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #888888;
        pointer-events: none;
    }
    .search-spinner {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
        width: 16px;
        height: 16px;
        border: 2px solid #dddddd;
        border-top-color: #ff385c;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin {
        to { transform: translateY(-50%) rotate(360deg); }
    }

    /* Custom period dropdowns / inputs */
    .custom-period-box {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .custom-period-box select,
    .custom-period-box input {
        padding: 6px 12px;
        border: 1.5px solid #dddddd;
        border-radius: 10px;
        font-size: 12.5px;
        font-family: inherit;
        color: #222222;
        background: #ffffff;
        outline: none;
    }
    .custom-period-box select:focus,
    .custom-period-box input:focus {
        border-color: #222222;
    }

    /* Table Loading Overlay */
    .table-container {
        position: relative;
        min-height: 200px;
    }
    .table-loading {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #222222;
        z-index: 10;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
    .table-loading.active {
        opacity: 1;
        pointer-events: auto;
    }
    .spinner-bar {
        width: 20px;
        height: 20px;
        border: 2.5px solid #ebebeb;
        border-top-color: #ff385c;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
</style>
@endpush

@section('content')
    <!-- Filter Card: Status + Periode + Pencarian Realtime Ajax -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
            <div class="filter-container">
                <!-- Baris Atas: Tabs Status & Kolom Pencarian Ajax -->
                <div class="filter-row-top">
                    @php
                        $tabs = [
                            'semua' => 'Semua',
                            'menunggu' => 'Menunggu',
                            'valid' => 'Valid',
                            'ditolak' => 'Ditolak',
                            'belum_bayar' => 'Belum Bayar',
                        ];
                    @endphp
                    <div class="filter-tabs" id="statusFilterTabs">
                        @foreach ($tabs as $key => $label)
                            <button type="button"
                                    data-status="{{ $key }}"
                                    class="filter-tab status-btn {{ $status === $key ? 'active' : '' }}">
                                {{ $label }}
                                <span class="count" id="count-{{ $key }}">{{ $counts[$key] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Ajax Search Box -->
                    <div class="search-input-wrap">
                        <input type="text"
                               id="ajaxSearchInput"
                               value="{{ $q }}"
                               placeholder="Ketik kode, nama, paket (otomatis)..."
                               autocomplete="off">
                        <span class="search-icon" id="searchIcon">🔍</span>
                        <div class="search-spinner" id="searchSpinner"></div>
                    </div>
                </div>

                <!-- Baris Bawah: Filter Periode (Hari Ini, Seminggu Kemarin, Bulan, Tahun, Rentang Kustom) -->
                <div class="filter-row-bottom">
                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                        <span style="font-size:12px; font-weight:800; color:#717171; text-transform:uppercase; letter-spacing:.05em;">
                            📅 Periode:
                        </span>
                        <div class="period-tabs" id="periodFilterTabs">
                            <button type="button" data-period="semua" class="period-tab {{ $periode === 'semua' ? 'active' : '' }}">Semua Waktu</button>
                            <button type="button" data-period="hari_ini" class="period-tab {{ $periode === 'hari_ini' ? 'active' : '' }}">Hari Ini</button>
                            <button type="button" data-period="seminggu" class="period-tab {{ $periode === 'seminggu' ? 'active' : '' }}">Seminggu Kemarin</button>
                            <button type="button" data-period="bulan" class="period-tab {{ $periode === 'bulan' ? 'active' : '' }}">Per Bulan</button>
                            <button type="button" data-period="tahun" class="period-tab {{ $periode === 'tahun' ? 'active' : '' }}">Per Tahun</button>
                            <button type="button" data-period="kustom" class="period-tab {{ $periode === 'kustom' ? 'active' : '' }}">Rentang Tanggal</button>
                        </div>
                    </div>

                    <!-- Input Sub-Periode (Tampil dinamis sesuai opsi) -->
                    <div class="custom-period-box" id="subPeriodOptions">
                        <!-- Pilihan Bulan & Tahun -->
                        <div id="boxMonthYear" style="display: {{ $periode === 'bulan' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
                            <select id="selectMonth">
                                @php
                                    $namaBulan = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                    $selectedMonth = $bulan ? (int)$bulan : (int)date('m');
                                @endphp
                                @foreach ($namaBulan as $num => $nm)
                                    <option value="{{ $num }}" @selected($selectedMonth === $num)>{{ $nm }}</option>
                                @endforeach
                            </select>
                            <select id="selectMonthYear">
                                @php $selectedYear = $tahun ? (int)$tahun : (int)date('Y'); @endphp
                                @foreach ($daftarTahun as $th)
                                    <option value="{{ $th }}" @selected($selectedYear === (int)$th)>{{ $th }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilihan Tahun Saja -->
                        <div id="boxYearOnly" style="display: {{ $periode === 'tahun' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
                            <select id="selectYear">
                                @foreach ($daftarTahun as $th)
                                    <option value="{{ $th }}" @selected($selectedYear === (int)$th)>Tahun {{ $th }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilihan Rentang Kustom (Dari & Sampai) -->
                        <div id="boxCustomRange" style="display: {{ $periode === 'kustom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
                            <input type="date" id="inputDateDari" value="{{ $dari }}" title="Dari Tanggal">
                            <span style="font-size:12px; color:#717171;">s/d</span>
                            <input type="date" id="inputDateSampai" value="{{ $sampai }}" title="Sampai Tanggal">
                            <button type="button" id="btnApplyRange" class="btn btn-primary btn-sm" style="padding: 5px 12px;">Terapkan</button>
                        </div>

                        <button type="button" id="btnResetAll" class="btn btn-outline btn-sm" style="padding: 5px 12px; display: {{ ($status !== 'semua' || $periode !== 'semua' || $q !== '') ? 'inline-flex' : 'none' }};">
                            ✕ Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card">
        <div class="card-head">
            <div>
                <h2>Daftar Pemesanan Wisata</h2>
                <p id="filterSummaryText">
                    Memuat data pemesanan...
                </p>
            </div>
            <span class="badge" id="totalBadge">{{ $pemesanan->total() }} DATA</span>
        </div>

        <div class="table-container">
            <div class="table-loading" id="tableLoading">
                <div class="spinner-bar"></div>
                <span>Memuat data...</span>
            </div>

            <div id="tableContentArea">
                @include('bendahara.pemesanan.partials.table', ['pemesanan' => $pemesanan])
            </div>
        </div>

        <div class="card-body" id="paginationArea" style="padding-top:0;">
            {{ $pemesanan->links('pagination.simple') }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStatus = "{{ $status }}";
    let currentPeriod = "{{ $periode }}";
    let currentDari = "{{ $dari }}";
    let currentSampai = "{{ $sampai }}";
    let currentBulan = "{{ $bulan }}";
    let currentTahun = "{{ $tahun }}";
    let currentQ = "{{ $q }}";
    let currentPage = 1;
    let searchTimeout = null;

    const searchInput = document.getElementById('ajaxSearchInput');
    const searchIcon = document.getElementById('searchIcon');
    const searchSpinner = document.getElementById('searchSpinner');
    const tableLoading = document.getElementById('tableLoading');
    const tableArea = document.getElementById('tableContentArea');
    const paginationArea = document.getElementById('paginationArea');
    const totalBadge = document.getElementById('totalBadge');
    const filterSummaryText = document.getElementById('filterSummaryText');
    const btnResetAll = document.getElementById('btnResetAll');

    // Sub-periode boxes
    const boxMonthYear = document.getElementById('boxMonthYear');
    const boxYearOnly = document.getElementById('boxYearOnly');
    const boxCustomRange = document.getElementById('boxCustomRange');
    const selectMonth = document.getElementById('selectMonth');
    const selectMonthYear = document.getElementById('selectMonthYear');
    const selectYear = document.getElementById('selectYear');
    const inputDateDari = document.getElementById('inputDateDari');
    const inputDateSampai = document.getElementById('inputDateSampai');
    const btnApplyRange = document.getElementById('btnApplyRange');

    function updateSummaryText() {
        let text = 'Menampilkan ';
        if (currentStatus === 'semua') {
            text += 'seluruh status';
        } else {
            text += 'status <strong>' + currentStatus.toUpperCase().replace('_', ' ') + '</strong>';
        }

        if (currentPeriod === 'hari_ini') {
            text += ' &middot; Hari ini';
        } else if (currentPeriod === 'seminggu') {
            text += ' &middot; 7 hari terakhir';
        } else if (currentPeriod === 'bulan') {
            const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const m = selectMonth.value;
            const y = selectMonthYear.value;
            text += ' &middot; Bulan ' + (monthNames[m] || m) + ' ' + y;
        } else if (currentPeriod === 'tahun') {
            text += ' &middot; Tahun ' + selectYear.value;
        } else if (currentPeriod === 'kustom' && (currentDari || currentSampai)) {
            text += ' &middot; Rentang ' + (currentDari || '...') + ' s/d ' + (currentSampai || '...');
        }

        if (currentQ) {
            text += ' &middot; Kata kunci: "' + currentQ + '"';
        }

        filterSummaryText.innerHTML = text;

        if (currentStatus !== 'semua' || currentPeriod !== 'semua' || currentQ !== '') {
            btnResetAll.style.display = 'inline-flex';
        } else {
            btnResetAll.style.display = 'none';
        }
    }

    updateSummaryText();

    async function fetchData(page = 1) {
        currentPage = page;
        tableLoading.classList.add('active');
        searchIcon.style.display = 'none';
        searchSpinner.style.display = 'block';

        const params = new URLSearchParams();
        params.set('status', currentStatus);
        params.set('periode', currentPeriod);
        if (currentDari) params.set('dari', currentDari);
        if (currentSampai) params.set('sampai', currentSampai);
        if (currentBulan) params.set('bulan', currentBulan);
        if (currentTahun) params.set('tahun', currentTahun);
        if (currentQ) params.set('q', currentQ);
        if (page > 1) params.set('page', page);

        // Update URL tanpa reload
        const newUrl = window.location.pathname + '?' + params.toString();
        window.history.replaceState({}, '', newUrl);

        try {
            const response = await fetch(newUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network error');

            const data = await response.json();

            tableArea.innerHTML = data.html_table;
            paginationArea.innerHTML = data.html_pagination;
            totalBadge.textContent = data.total + ' DATA';

            if (data.counts) {
                for (const [key, val] of Object.entries(data.counts)) {
                    const el = document.getElementById('count-' + key);
                    if (el) el.textContent = val;
                }
            }

            updateSummaryText();
            attachPaginationHandlers();
        } catch (err) {
            console.error('Fetch error:', err);
        } finally {
            tableLoading.classList.remove('active');
            searchSpinner.style.display = 'none';
            searchIcon.style.display = 'block';
        }
    }

    function attachPaginationHandlers() {
        const links = paginationArea.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page') || 1;
                fetchData(page);
            });
        });
    }

    attachPaginationHandlers();

    // Event: Ubah Status Tab
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.status-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.getAttribute('data-status');
            fetchData(1);
        });
    });

    // Event: Ubah Periode Tab
    document.querySelectorAll('.period-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.period-tab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentPeriod = this.getAttribute('data-period');

            boxMonthYear.style.display = 'none';
            boxYearOnly.style.display = 'none';
            boxCustomRange.style.display = 'none';

            if (currentPeriod === 'bulan') {
                boxMonthYear.style.display = 'flex';
                currentBulan = selectMonth.value;
                currentTahun = selectMonthYear.value;
                currentDari = '';
                currentSampai = '';
            } else if (currentPeriod === 'tahun') {
                boxYearOnly.style.display = 'flex';
                currentTahun = selectYear.value;
                currentBulan = '';
                currentDari = '';
                currentSampai = '';
            } else if (currentPeriod === 'kustom') {
                boxCustomRange.style.display = 'flex';
                currentBulan = '';
                currentTahun = '';
                return; // Tunggu klik terapkan
            } else {
                currentBulan = '';
                currentTahun = '';
                currentDari = '';
                currentSampai = '';
            }

            fetchData(1);
        });
    });

    // Event: Ganti Bulan / Tahun Dropdown
    selectMonth.addEventListener('change', function () {
        currentBulan = this.value;
        fetchData(1);
    });
    selectMonthYear.addEventListener('change', function () {
        currentTahun = this.value;
        fetchData(1);
    });
    selectYear.addEventListener('change', function () {
        currentTahun = this.value;
        fetchData(1);
    });

    // Event: Terapkan Rentang Tanggal
    btnApplyRange.addEventListener('click', function () {
        currentDari = inputDateDari.value;
        currentSampai = inputDateSampai.value;
        fetchData(1);
    });

    // Event: Pencarian Real-time Ajax dengan Debounce
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const val = this.value.trim();
        searchTimeout = setTimeout(() => {
            currentQ = val;
            fetchData(1);
        }, 350);
    });

    // Event: Reset Semua Filter
    btnResetAll.addEventListener('click', function () {
        currentStatus = 'semua';
        currentPeriod = 'semua';
        currentDari = '';
        currentSampai = '';
        currentBulan = '';
        currentTahun = '';
        currentQ = '';
        searchInput.value = '';

        document.querySelectorAll('.status-btn').forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-status') === 'semua');
        });
        document.querySelectorAll('.period-tab').forEach(b => {
            b.classList.toggle('active', b.getAttribute('data-period') === 'semua');
        });

        boxMonthYear.style.display = 'none';
        boxYearOnly.style.display = 'none';
        boxCustomRange.style.display = 'none';

        fetchData(1);
    });
});
</script>
@endpush
