@extends('layouts.admin-layout')

@section('title', 'Main Dashboard')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        [x-cloak] { display: none !important; }
        .filter-button {
            transition: all 0.2s ease-in-out;
        }
        .filter-button.active {
            background-color: #4f46e5; 
            color: white;
            font-weight: 600;
        }
        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@section('content')
<div x-data="statisticsDashboard()" x-init="init()" class="relative">
    
    {{-- Header n filter --}}
    <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
        <div class="flex justify-between items-center mb-1">
            <h1 class="text-4xl font-black font-gotham text-transparent bg-clip-text bg-gradient-to-r from-[#5BE6B0] to-[#20BDA9]">
            Main Dashboard
            </h1>
        </div>
        <div class="flex items-center bg-gray-100 rounded-lg p-1 space-x-1 border border-[#b0e0e4]">
                <button 
                    @click="changePeriod('week')" 
                    :class="[
                        'filter-button px-4 py-1.5 text-sm rounded-md transition-colors duration-200',
                        selectedPeriod === 'week' ? 'bg-[#b0e0e4] text-[#2e304f] font-semibold' : 'text-gray-700 hover:bg-[#b0e0e4]'
                    ]"
                >This Week</button>

                <button 
                    @click="changePeriod('month')" 
                    :class="[
                        'filter-button px-4 py-1.5 text-sm rounded-md transition-colors duration-200',
                        selectedPeriod === 'month' ? 'bg-[#b0e0e4] text-[#2e304f] font-semibold' : 'text-gray-700 hover:bg-[#b0e0e4]'
                    ]"
                >This Month</button>

                <button 
                    @click="changePeriod('year')" 
                    :class="[
                        'filter-button px-4 py-1.5 text-sm rounded-md transition-colors duration-200',
                        selectedPeriod === 'year' ? 'bg-[#b0e0e4] text-[#2e304f] font-semibold' : 'text-gray-700 hover:bg-[#b0e0e4]'
                    ]"
                >This Year</button>
        </div>

    </div>

    <div x-show="isLoading" x-transition class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50 rounded-lg">
        <i class="ri-loader-4-line text-4xl text-indigo-600 animate-spin"></i>
    </div>

    {{-- Statistic card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total users -->
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="ri-group-2-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="data.stats.totalUsers"></p>
                </div>
            </div>
        </div>
        <!-- New users -->
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="ri-user-add-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500" x-text="`New Users (${data.stats.periodLabel})`"></p>
                    <p class="text-2xl font-bold text-gray-800" x-text="data.stats.newUsers"></p>
                </div>
            </div>
        </div>
        <!-- Blocked users -->
         <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="ri-user-unfollow-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Blocked Users</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="data.stats.blockedUsers"></p>
                </div>
            </div>
        </div>
        <!-- New questions -->
        <div class="stat-card bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="ri-questionnaire-fill text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500" x-text="`New Questions (${data.stats.periodLabel})`"></p>
                    <p class="text-2xl font-bold text-gray-800" x-text="data.stats.newQuestions"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="mt-8 bg-white p-6 rounded-xl shadow-md border border-gray-100">
        <h3 class="text-xl font-semibold text-gray-700 mb-1">User & Question Growth</h3>
        <p class="text-sm text-gray-500 mb-4" x-text="`Trends for ${data.stats.periodLabel}`"></p>
        <div><canvas id="growthTrendChart"></canvas></div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    function statisticsDashboard() {
        return {
            isLoading: true,
            selectedPeriod: 'month',
            data: {
                stats: { totalUsers: 0, newUsers: 0, blockedUsers: 0, newQuestions: 0, periodLabel: 'This Month' },
                charts: {
                    growthTrend: { labels: [], users: [], questions: [] }
                }
            },
            growthChart: null,

            init() {
                this.fetchDashboardData();
                this.$watch('data', () => this.updateGrowthChart());
            },

            async fetchDashboardData() {
                this.isLoading = true;
                try {
                    const url = `/admin/dashboard/statistics-data?period=${this.selectedPeriod}`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (!response.ok) throw new Error(`API request failed with status ${response.status}`);
                    
                    this.data = await response.json();
                } catch (error) {
                    console.error('Dashboard Error:', error);
                    alert('Could not load dashboard statistics.');
                } finally {
                    this.isLoading = false;
                }
            },

            changePeriod(newPeriod) {
                if (this.selectedPeriod !== newPeriod) {
                    this.selectedPeriod = newPeriod;
                    this.fetchDashboardData();
                }
            },

            updateGrowthChart() {
                if (this.growthChart) {
                    this.growthChart.destroy();
                }
                const ctx = document.getElementById('growthTrendChart').getContext('2d');
                this.growthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.data.charts.growthTrend.labels,
                        datasets: [{
                            label: 'New Users',
                            data: this.data.charts.growthTrend.users,
                            borderColor: '#3b82f6', // blue-500
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#3b82f6'
                        }, {
                            label: 'New Questions',
                            data: this.data.charts.growthTrend.questions,
                            borderColor: '#f59e0b', // amber-500
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#f59e0b'
                        }]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    }
    </script>
@endpush
