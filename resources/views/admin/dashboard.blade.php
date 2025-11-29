{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #6366f1;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1f2937;
        --light: #f8fafc;
        --gray: #6b7280;
        --card-bg: rgba(255, 255, 255, 0.95);
        --glass: rgba(255, 255, 255, 0.15);
        --border: rgba(255, 255, 255, 0.2);
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Inter', 'Segoe UI', sans-serif;
        min-height: 100vh;
    }

    .container-custom { padding: 2rem 1rem; max-width: 1400px; }

    /* Stats Cards */
    .stat-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        padding: 1.75rem;
        transition: all .3s ease;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content:''; position:absolute; top:0; left:0; width:100%; height:4px;
        background:var(--primary);
    }
    .stat-card.success::before { background:var(--success); }
    .stat-card.warning::before { background:var(--warning); }
    .stat-card:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(79,70,229,.15); }

    .stat-icon {
        width:56px; height:56px; border-radius:1rem;
        display:flex; align-items:center; justify-content:center;
        font-size:1.75rem; color:#fff;
    }
    .stat-value { font-size:2.25rem; font-weight:800; color:var(--dark); line-height:1; }
    .stat-label { color:var(--gray); font-weight:500; font-size:.95rem; margin-top:.25rem; }

    /* Chart Cards */
    .chart-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        padding: 1.75rem;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
        height: 100%;
    }
    .chart-title {
        font-weight:700; color:var(--dark); font-size:1.2rem;
        margin-bottom:1.5rem; display:flex; align-items:center; gap:.5rem;
    }

    /* Aspect-ratio container */
    .chart-container {
        position:relative; height:0; padding-bottom:75%;   /* 4:3 */
        margin-top:1rem;
    }
    .chart-container canvas {
        position:absolute; top:0; left:0; width:100%; height:100%;
    }

    @media (max-width:768px){
        .stat-value{font-size:1.75rem;}
        .chart-title{font-size:1.1rem;}
    }
    @media (max-width:576px){
        .stat-card{padding:1.25rem;}
    }

    .hover-lift{transition:transform .3s ease,box-shadow .3s ease;}
    .hover-lift:hover{transform:translateY(-4px);box-shadow:0 12px 25px rgba(79,70,229,.15)!important;}

    /* Hide scrollbar on small-screen horizontal scroll */
    .overflow-x-auto::-webkit-scrollbar{display:none;}
    .overflow-x-auto{-ms-overflow-style:none;scrollbar-width:none;}
</style>

<div class="container container-custom mx-auto">

    {{-- Header --}}
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-black mb-2">Admin Dashboard</h1>
        <p class="text-black opacity-75">
            Welcome back, <strong>{{ Auth::user()->name }}</strong> — Here's your overview
        </p>
    </div>

    {{-- Top Stats – Horizontal scroll on mobile --}}
    <div class="d-flex flex-nowrap overflow-x-auto gap-4 mb-5 pb-2">
        {{-- Total Employees --}}
        <div style="min-width:260px;">
            <div class="stat-card d-flex align-items-center h-100">
                <div class="stat-icon me-4" style="background:var(--primary);"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalEmployees ?? 0 }}</div>
                    <div class="stat-label">Total Employees</div>
                </div>
            </div>
        </div>

        {{-- User Accounts --}}
        <div style="min-width:260px;">
            <div class="stat-card d-flex align-items-center success h-100">
                <div class="stat-icon me-4" style="background:var(--success);"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">User Accounts</div>
                </div>
            </div>
        </div>

        {{-- Training Programs --}}
        <div style="min-width:260px;">
            <div class="stat-card d-flex align-items-center warning h-100">
                <div class="stat-icon me-4" style="background:var(--warning);"><i class="bi bi-book-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalTrainings ?? 0 }}</div>
                    <div class="stat-label">Training Programs</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row g-4">

        {{-- Left: Department & Designation Doughnuts --}}
        <div class="col-lg-6">
            <div class="chart-card">
                <h3 class="chart-title"><i class="bi bi-pie-chart-fill text-primary"></i> Employee Distribution</h3>
                <div class="row g-4">
                    <div class="col-md-6 col-12">
                        <div class="chart-container"><canvas id="departmentChart"></canvas></div>
                        <p class="text-center text-muted small mt-2">By Department</p>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="chart-container"><canvas id="designationChart"></canvas></div>
                        <p class="text-center text-muted small mt-2">By Designation</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: New Hires Bar Chart --}}
        <div class="col-lg-6">
            <div class="chart-card h-100 d-flex flex-column">
                <h3 class="chart-title"><i class="bi bi-bar-chart-line-fill text-success"></i> New Hires Trend ({{ date('Y') }})</h3>
                <div class="chart-container flex-grow-1"><canvas id="hiresChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <a href="{{ route('employees.index') }}" class="text-decoration-none">
                <div class="stat-card text-center p-4 hover-lift">
                    <i class="bi bi-person-plus-fill text-primary" style="font-size:2rem;"></i>
                    <p class="mt-3 mb-0 text-dark fw-semibold">Manage Employees</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('departments.index') }}" class="text-decoration-none">
                <div class="stat-card text-center p-4 hover-lift">
                    <i class="bi bi-building text-success" style="font-size:2rem;"></i>
                    <p class="mt-3 mb-0 text-dark fw-semibold">Manage Departments</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('designations.index') }}" class="text-decoration-none">
                <div class="stat-card text-center p-4 hover-lift">
                    <i class="bi bi-person-badge text-warning" style="font-size:2rem;"></i>
                    <p class="mt-3 mb-0 text-dark fw-semibold">Manage Designations</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // ---------- DATA FROM CONTROLLER ----------
    const departmentLabels  = @json($departmentLabels  ?? []);
    const departmentCounts  = @json($departmentCounts  ?? []);

    const designationLabels = @json($designationLabels ?? []);
    const designationCounts = @json($designationCounts ?? []);

    const monthlyLabels = @json($monthlyLabels ?? []);
    const monthlyHires  = @json($monthlyHires  ?? []);

    // Debug (remove in production)
    console.log({departmentLabels, departmentCounts, designationLabels, designationCounts, monthlyLabels, monthlyHires});

    // ---------- COLORS ----------
    const pieColors = [
        '#4f46e5','#10b981','#f59e0b','#ef4444','#3b82f6',
        '#a855f7','#06b6d4','#eab308','#ec4899','#8b5cf6'
    ];

    // ---------- DOUGHNUT OPTIONS ----------
    const doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 20, font: { size: 12 } } },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label || ''}: ${ctx.parsed ?? 0}`
                }
            }
        }
    };

    // ---------- HELPERS ----------
    const ensureData = (labels, counts) => {
        const cleanLabels = labels.filter(l => l && l.trim() !== '');
        const cleanCounts = counts.slice(0, cleanLabels.length);
        if (!cleanLabels.length) {
            return { labels: ['No Data'], counts: [1], hideLegend: true };
        }
        return { labels: cleanLabels, counts: cleanCounts, hideLegend: false };
    };

    // ---------- DEPARTMENT DOUGHNUT ----------
    const deptCtx = document.getElementById('departmentChart');
    if (deptCtx) {
        const {labels, counts, hideLegend} = ensureData(departmentLabels, departmentCounts);
        new Chart(deptCtx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: counts,
                    backgroundColor: pieColors.slice(0, labels.length),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                ...doughnutOptions,
                plugins: {
                    ...doughnutOptions.plugins,
                    legend: hideLegend ? { display: false } : doughnutOptions.plugins.legend
                }
            }
        });
    }

    // ---------- DESIGNATION DOUGHNUT ----------
    const desigCtx = document.getElementById('designationChart');
    if (desigCtx) {
        const {labels, counts, hideLegend} = ensureData(designationLabels, designationCounts);
        new Chart(desigCtx, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: counts,
                    backgroundColor: pieColors.slice(3).concat(pieColors.slice(0, 3)),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                ...doughnutOptions,
                plugins: {
                    ...doughnutOptions.plugins,
                    legend: hideLegend ? { display: false } : doughnutOptions.plugins.legend
                }
            }
        });
    }

    // ---------- NEW HIRES BAR ----------
    const hiresCtx = document.getElementById('hiresChart');
    if (hiresCtx) {
        new Chart(hiresCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'New Hires',
                    data: monthlyHires,
                    backgroundColor: 'rgba(79,70,229,0.8)',
                    borderColor: '#4f46e5',
                    borderWidth: 2,
                    borderRadius: 6,
                    barThickness: 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,.05)' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
</script>
@endsection