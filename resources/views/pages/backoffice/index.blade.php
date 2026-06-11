@extends('layouts.backoffice')

@section('title', 'Backoffice')

@push('styles')
    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #ffffff 0%, #fff4ee 100%);
            border: 1px solid rgba(250, 91, 25, 0.12);
            border-radius: 22px;
            box-shadow: 0 16px 40px rgba(31, 41, 55, 0.08);
        }

        .metric-card {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 14px 30px rgba(31, 41, 55, 0.08);
        }

        .metric-card .card-body {
            min-height: 132px;
        }

        .metric-label {
            font-size: 0.9rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.82);
        }

        .metric-value {
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1;
            font-weight: 700;
        }

        .chart-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 14px 30px rgba(31, 41, 55, 0.08);
            overflow: hidden;
        }

        .chart-wrap {
            position: relative;
            min-height: 320px;
        }

        .chart-wrap canvas {
            max-height: 320px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-hero p-4 p-lg-5 mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h4 class="fw-semibold mb-1">Dashboard Backoffice</h4>
                <div class="text-muted">Ringkasan user dan distribusi jawaban feedback SmartPeak.</div>
            </div>
            <span class="badge rounded-pill bg-primary px-3 py-2">Admin</span>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="metric-card bg-primary text-white h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="metric-label">Total User Saat Ini</div>
                        <div class="metric-value">{{ number_format($totalUsers) }}</div>
                        <div class="small text-white-50">Semua user aktif yang tidak termasuk admin.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-card bg-dark text-white h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="metric-label">User Baru Bulan Ini</div>
                        <div class="metric-value">{{ number_format($newUsersThisMonth) }}</div>
                        <div class="small text-white-50">Berdasarkan akun yang dibuat pada bulan berjalan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach ($dashboardCharts as $chart)
            <div class="col-12 col-xl-6">
                <div class="card chart-card h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-1">{{ $chart['title'] }}</h5>
                        <div class="text-muted small">Distribusi jawaban feedback dari tabel answer.</div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="chart-wrap">
                            <canvas id="chart-{{ $chart['key'] }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const charts = @json($dashboardCharts);

            charts.forEach(function (chart) {
                const canvas = document.getElementById(`chart-${chart.key}`);

                if (!canvas) {
                    return;
                }

                new Chart(canvas, {
                    type: 'pie',
                    data: {
                        labels: chart.data.labels,
                        datasets: [{
                            data: chart.data.values,
                            backgroundColor: chart.data.colors,
                            borderColor: '#ffffff',
                            borderWidth: 2,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    padding: 16,
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const value = context.raw || 0;
                                        return `${context.label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush