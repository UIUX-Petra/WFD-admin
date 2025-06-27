@extends('layouts.admin-layout')

@section('title', 'Reports Dashboard')

@push('styles')
    <style>
        [x-cloak] {
            display: none !important;
        }

        .filter-button {
            transition: all 0.2s ease-in-out;
        }

        .filter-button.active {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div x-data="reportDashboard()" x-init="init()" class="relative">
        {{-- Header & Filter --}}
        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
            <h1 class="text-3xl font-semibold text-gray-800">Reports & Moderation Dashboard</h1>
            <div class="flex items-center bg-gray-200 rounded-lg p-1 space-x-1">
                <button @click="changePeriod('week')" :class="{ 'active': selectedPeriod === 'week' }"
                    class="filter-button px-4 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-300">This Week</button>
                <button @click="changePeriod('month')" :class="{ 'active': selectedPeriod === 'month' }"
                    class="filter-button px-4 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-300">This Month</button>
                <button @click="changePeriod('year')" :class="{ 'active': selectedPeriod === 'year' }"
                    class="filter-button px-4 py-1.5 text-sm rounded-md text-gray-700 hover:bg-gray-300">This Year</button>
            </div>
        </div>

        {{-- Loader spinner --}}
        <div x-show="isLoading"
             x-transition.opacity
             class="absolute inset-0 top-50 left-50 bg-white bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-500">
            <div class="flex flex-col items-center space-y-2">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"></path>
                </svg>
                <p class="text-sm text-gray-600 font-medium">Loading new data...</p>
            </div>
        </div>

        {{-- Statistic cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-sm font-medium text-gray-500">Total Reports Received</p>
                <p class="text-3xl font-bold text-gray-800" x-text="data.stats.totalReceived"></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-sm font-medium text-gray-500">Reports Handled</p>
                <p class="text-3xl font-bold text-green-600" x-text="data.stats.reportsHandled"></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-sm font-medium text-gray-500">Pending Reports</p>
                <p class="text-3xl font-bold text-red-600" x-text="data.stats.pendingReports"></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-sm font-medium text-gray-500">Completion Rate</p>
                <p class="text-3xl font-bold text-blue-600" x-text="`${data.stats.completionRate}%`"></p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Report Trends</h3>
                <div><canvas id="reportTrendChart"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Report by Content Type</h3>
                <div><canvas id="typeBreakdownChart"></canvas></div>
            </div>
        </div>

        {{-- Table --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Breakdown by Report Reason</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Reports</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="data.table.reasonBreakdown.length > 0">
                        <template x-for="item in data.table.reasonBreakdown" :key="item.reason">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    x-text="item.reason"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700" x-text="item.count"></td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="!isLoading && data.table.reasonBreakdown.length === 0">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available for this period.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function reportDashboard() {
            return {
                isLoading: true,
                selectedPeriod: 'month',
                data: {
                    stats: {
                        totalReceived: 0,
                        reportsHandled: 0,
                        pendingReports: 0,
                        completionRate: 0
                    },
                    charts: {
                        typeBreakdown: {
                            labels: [],
                            data: []
                        },
                        trend: {
                            labels: [],
                            received: [],
                            handled: []
                        }
                    },
                    table: {
                        reasonBreakdown: []
                    }
                },
                trendChart: null,
                typeChart: null,

                init() {
                    this.fetchDashboardData();
                },

                async fetchDashboardData() {
                    this.isLoading = true;

                    try {
                        // Delay 400ms for smoother loading transition
                        await new Promise(resolve => setTimeout(resolve, 400));

                        const url = `/admin/dashboard/report-data?period=${this.selectedPeriod}`;
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) {
                            const errorBody = await response.text();
                            throw new Error(`Failed to fetch dashboard data. Status: ${response.status}. Body: ${errorBody}`);
                        }

                        this.data = await response.json();

                        console.log('Data received:', JSON.parse(JSON.stringify(this.data)));

                        this.updateTrendChart();
                        this.updateTypeChart();

                    } catch (error) {
                        console.error('Dashboard Error:', error);
                        alert('Could not load dashboard data. See console for details.');
                    } finally {
                        this.isLoading = false;
                    }
                },

                changePeriod(newPeriod) {
                    this.selectedPeriod = newPeriod;
                    this.fetchDashboardData();
                },

                updateTrendChart() {
                    if (this.trendChart) this.trendChart.destroy();
                    const ctx = document.getElementById('reportTrendChart').getContext('2d');
                    this.trendChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.data.charts.trend.labels,
                            datasets: [
                                {
                                    label: 'Reports Received',
                                    data: this.data.charts.trend.received,
                                    borderColor: '#3b82f6',
                                    tension: 0.1,
                                    fill: false
                                },
                                {
                                    label: 'Reports Handled',
                                    data: this.data.charts.trend.handled,
                                    borderColor: '#16a34a',
                                    tension: 0.1,
                                    fill: false
                                }
                            ]
                        }
                    });
                },

                updateTypeChart() {
                    if (this.typeChart) this.typeChart.destroy();
                    const ctx = document.getElementById('typeBreakdownChart').getContext('2d');
                    this.typeChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: this.data.charts.typeBreakdown.labels,
                            datasets: [{
                                label: 'Report by Type',
                                data: this.data.charts.typeBreakdown.data,
                                backgroundColor: [
                                    '#f97316', '#a855f7', '#0d9488',
                                    '#facc15', '#ec4899', '#6366f1'
                                ],
                                hoverOffset: 4
                            }]
                        }
                    });
                }
            }
        }
    </script>
@endpush
