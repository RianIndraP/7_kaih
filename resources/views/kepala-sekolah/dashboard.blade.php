@extends('layouts.kepala-sekolah')

@section('title', 'Dashboard Kepala Sekolah')

@section('page-title', 'Dashboard')

@section('content')
<style>
.dashboard-content {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 120px);
    padding: 1.5rem;
    border-radius: 20px;
    margin: -1.5rem;
}

/* Header Styles */
.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
}

.header-content {
    position: relative;
    z-index: 1;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-avatar i {
    font-size: 1.5rem;
    color: #667eea;
}

.user-details h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.user-details p {
    font-size: 0.95rem;
    opacity: 0.9;
    margin: 0;
}

.date-display {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start), var(--color-end));
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-card.primary { --color-start: #667eea; --color-end: #764ba2; }
.stat-card.success { --color-start: #48bb78; --color-end: #38a169; }
.stat-card.info { --color-start: #4299e1; --color-end: #3182ce; }
.stat-card.warning { --color-start: #ed8936; --color-end: #dd6b20; }

.stat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    position: relative;
}

.stat-card.primary .stat-icon { 
    background: linear-gradient(135deg, #667eea, #764ba2); 
}
.stat-card.success .stat-icon { 
    background: linear-gradient(135deg, #48bb78, #38a169); 
}
.stat-card.info .stat-icon { 
    background: linear-gradient(135deg, #4299e1, #3182ce); 
}
.stat-card.warning .stat-icon { 
    background: linear-gradient(135deg, #ed8936, #dd6b20); 
}

.stat-trend {
    padding: 0.2rem 0.6rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.2rem;
}

.trend-up {
    background: rgba(72, 187, 120, 0.1);
    color: #38a169;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.4rem;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 500;
    margin-bottom: 0.2rem;
}

.stat-description {
    font-size: 0.8rem;
    color: #a0aec0;
}

/* Charts Section */
.charts-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.chart-header {
    margin-bottom: 1.25rem;
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.4rem;
}

.chart-subtitle {
    font-size: 0.8rem;
    color: #718096;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.chart-canvas {
    height: 280px;
    position: relative;
}

/* Bottom Section */
.bottom-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

.activity-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}

.activity-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
}

.activity-subtitle {
    font-size: 0.8rem;
    color: #718096;
    margin-top: 0.25rem;
}

.live-badge {
    background: linear-gradient(135deg, #f56565, #e53e3e);
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.live-dot {
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    animation: blink 1.5s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

/* Activity Tabs */
.activity-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.activity-tab {
    padding: 0.75rem 1.5rem;
    border: none;
    background: none;
    color: #718096;
    font-weight: 500;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    position: relative;
}

.activity-tab:hover {
    color: #4a5568;
}

.activity-tab.active {
    color: #667eea;
    border-bottom-color: #667eea;
}

/* Activity Items */
.activity-list {
    max-height: 350px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 0.75rem;
    background: #f8fafc;
    transition: all 0.2s ease;
}

.activity-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
    color: white;
}

.activity-icon.success { 
    background: linear-gradient(135deg, #f6d55c, #ed8936); 
    box-shadow: 0 4px 12px rgba(237, 137, 54, 0.3);
}

.activity-icon.info { background: linear-gradient(135deg, #4299e1, #3182ce); }
.activity-icon.warning { background: linear-gradient(135deg, #ed8936, #dd6b20); }
.activity-icon.danger { background: linear-gradient(135deg, #f56565, #e53e3e); }

.activity-content {
    flex: 1;
}

.activity-name {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.875rem;
    color: #718096;
}

.activity-status {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.status-completed {
    background: rgba(246, 213, 92, 0.15);
    color: #d69e2e;
}

.status-pending {
    background: rgba(237, 137, 54, 0.1);
    color: #dd6b20;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #a0aec0;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-text {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.empty-subtext {
    font-size: 0.875rem;
    color: #cbd5e0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .charts-container {
        grid-template-columns: 1fr;
    }
    
    .bottom-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    /* Sidebar Mobile */
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    /* Header Mobile */
    .main-header {
        left: 0;
    }
    
    .header-content {
        padding: 1rem;
    }
    
    .mobile-menu-toggle {
        display: block;
    }
    
    .header-left h1 {
        font-size: 1.25rem;
    }
    
    .header-right {
        gap: 0.5rem;
    }
    
    .header-btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .header-btn span {
        display: none;
    }
    
    /* Main Content Mobile */
    .main-content {
        margin-left: 0;
        padding: 1rem;
        margin-top: 70px;
    }
    
    .dashboard-content {
        padding: 1rem;
        margin: -1rem;
    }
    
    .dashboard-header {
        padding: 1.5rem;
    }
    
    .header-top {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .user-info {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
    }
    
    .user-details h1 {
        font-size: 1.25rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .chart-card,
    .activity-card {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .dashboard-content {
        padding: 0.75rem;
        margin: -0.75rem;
    }
    
    .dashboard-header {
        padding: 1rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
    }
    
    .user-details h1 {
        font-size: 1.1rem;
    }
    
    .user-details p {
        font-size: 0.85rem;
    }
    
    .date-display {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .stat-icon {
        font-size: 1.5rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .chart-card,
    .activity-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}

/* Custom Scrollbar */
.activity-list::-webkit-scrollbar {
    width: 6px;
}

.activity-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.activity-list::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Button Styles */
.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.btn-outline {
    background: transparent;
    border: 1px solid #e2e8f0;
    color: #4a5568;
}

.btn-outline:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
}

.dropdown-toggle::after {
    margin-left: 0.5rem;
}

/* Dropdown Menu */
.dropdown-menu {
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background: #f7fafc;
    color: #2d3748;
}
</style>

<div class="dashboard-content">
    {{-- Update Message Notification --}}
    @php
        $updateMessage = \App\Models\WebsiteManagement::getUpdateMessage('kepala_sekolah');
    @endphp
    @include('kepala-sekolah._update_message', ['updateMessage' => $updateMessage])

            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="header-content">
                    <div class="header-top">
                        <div class="user-info">
                            <div class="user-avatar">
                                @if(Auth::user()->foto)
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto Kepala Sekolah">
                                @else
                                    <i class="fas fa-user-tie"></i>
                                @endif
                            </div>
                            <div class="user-details">
                                <h1>Dashboard Kepala Sekolah</h1>
                                <p>Selamat datang, {{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="date-display">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Statistics Cards -->
    <section class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon">
                    👨‍🏫
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_guru']) }}</div>
            <div class="stat-label">Total Guru</div>
            <div class="stat-description">Staf pengajar terdaftar</div>
        </div>

        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    👨‍🎓
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_siswa']) }}</div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-description">Peserta didik terdaftar</div>
        </div>

        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon">
                    🏫
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_kelas']) }}</div>
            <div class="stat-label">Total Kelas</div>
            <div class="stat-description">Ruang kelas tersedia</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-icon">
                    ✅
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-clock"></i>
                    <span>Today</span>
                </div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_kebiasaan_hari_ini']) }}</div>
            <div class="stat-label">Kebiasaan Hari Ini</div>
            <div class="stat-description">Aktivitas tercatat</div>
        </div>
    </section>

    <!-- Charts Section -->
    <section class="charts-container">
        <!-- Weekly Kebiasaan Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Grafik Kebiasaan Mingguan</div>
                    <div class="chart-subtitle">Monitoring aktivitas 7 kebiasaan siswa - Minggu Ini</div>
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="kebiasaanChart"></canvas>
            </div>
        </div>

        <!-- Guru by Status -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Guru Berdasarkan Status</div>
                    <div class="chart-subtitle">Distribusi staf pengajar</div>
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="guruStatusChart"></canvas>
            </div>
        </div>
    </section>

    <!-- Bottom Section -->
    <section class="bottom-section">
        <!-- Siswa by Kelas -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <div class="chart-title">Jumlah Siswa per Kelas</div>
                    <div class="chart-subtitle">Distribusi peserta didik per ruang kelas</div>
                </div>
            </div>
            <div class="chart-canvas">
                <canvas id="siswaKelasChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="activity-card">
            <div class="activity-header">
                <div>
                    <div class="activity-title">Siswa Berprestasi Hari Ini</div>
                    <div class="activity-subtitle">Siswa yang menyelesaikan semua kebiasaan</div>
                </div>
                <div class="live-badge">
                    <div class="live-dot"></div>
                    <span>LIVE</span>
                </div>
            </div>

            <!-- Activity Content -->
            <div class="activity-list">
                @if(isset($completedAllHabitsToday) && $completedAllHabitsToday->count() > 0)
                    @foreach($completedAllHabitsToday as $student)
                        <div class="activity-item">
                            <div class="activity-icon success">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-name">{{ $student->name }}</div>
                                <div class="activity-time">{{ $student->kelas->nama_kelas ?? 'Tidak ada kelas' }} • Selesai semua kebiasaan</div>
                            </div>
                            <div class="activity-status status-completed">
                                <i class="fas fa-star"></i> Lengkap
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="empty-text">Belum ada siswa berprestasi</div>
                        <div class="empty-subtext">Belum ada siswa yang menyelesaikan semua kebiasaan hari ini</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart configuration
let kebiasaanChart, guruStatusChart, siswaKelasChart;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadChartData();
});

function initializeCharts() {
    // Chart default options
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#667eea',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: false
            }
        }
    };

    // Kebiasaan Chart
    const kebiasaanCtx = document.getElementById('kebiasaanChart').getContext('2d');
    kebiasaanChart = new Chart(kebiasaanCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Kebiasaan',
                data: [],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            ...defaultOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#718096',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#718096',
                        font: {
                            size: 12
                        },
                        stepSize: 1
                    }
                }
            },
            plugins: {
                ...defaultOptions.plugins,
                tooltip: {
                    ...defaultOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return 'Total: ' + context.parsed.y + ' kebiasaan';
                        }
                    }
                }
            }
        }
    });

    // Guru Status Chart
    const guruStatusCtx = document.getElementById('guruStatusChart').getContext('2d');
    guruStatusChart = new Chart(guruStatusCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#667eea',
                    '#48bb78',
                    '#4299e1',
                    '#ed8936',
                    '#f56565'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            ...defaultOptions,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        },
                        color: '#4a5568'
                    }
                },
                tooltip: {
                    ...defaultOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Siswa Kelas Chart
    const siswaKelasCtx = document.getElementById('siswaKelasChart').getContext('2d');
    siswaKelasChart = new Chart(siswaKelasCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [],
                backgroundColor: 'rgba(72, 187, 120, 0.8)',
                borderColor: '#48bb78',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            ...defaultOptions,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#718096',
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        color: '#718096',
                        font: {
                            size: 12
                        },
                        stepSize: 1
                    }
                }
            },
            plugins: {
                ...defaultOptions.plugins,
                tooltip: {
                    ...defaultOptions.plugins.tooltip,
                    callbacks: {
                        label: function(context) {
                            return 'Total: ' + context.parsed.y + ' siswa';
                        }
                    }
                }
            }
        }
    });
}

function loadChartData() {
    // Load weekly kebiasaan data
    fetch('/kepala-sekolah/chart-data?type=kebiasaan&period=week')
        .then(response => response.json())
        .then(data => {
            kebiasaanChart.data.labels = data.map(item => new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short' }));
            kebiasaanChart.data.datasets[0].data = data.map(item => item.count);
            kebiasaanChart.update();
        });

    // Load guru by status data
    fetch('/kepala-sekolah/guru-by-status')
        .then(response => response.json())
        .then(data => {
            guruStatusChart.data.labels = data.map(item => item.status_pegawai);
            guruStatusChart.data.datasets[0].data = data.map(item => item.count);
            guruStatusChart.update();
        });

    // Load siswa by kelas data
    fetch('/kepala-sekolah/siswa-by-kelas')
        .then(response => response.json())
        .then(data => {
            siswaKelasChart.data.labels = data.map(item => item.nama_kelas);
            siswaKelasChart.data.datasets[0].data = data.map(item => item.siswa_count);
            siswaKelasChart.update();
        });
}

function updateChart(type, period) {
    fetch(`/kepala-sekolah/chart-data?type=${type}&period=${period}`)
        .then(response => response.json())
        .then(data => {
            kebiasaanChart.data.labels = data.map(item => new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short', month: 'short', day: 'numeric' }));
            kebiasaanChart.data.datasets[0].data = data.map(item => item.count);
            kebiasaanChart.update();
        });
}
</script>
@endsection
