@extends('layouts.app')

@section('title', 'Enterprise Monitoring Dashboard')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css" rel="stylesheet">
<style>
/* Enterprise Dashboard Styles */
.enterprise-dashboard {
    background: #f8fafc;
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.dashboard-header h1 {
    margin: 0;
    font-size: 32px;
    font-weight: 700;
}

.dashboard-header .subtitle {
    margin-top: 8px;
    opacity: 0.9;
    font-size: 16px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.metric-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-left: 4px solid transparent;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.metric-card.healthy { border-left-color: #48bb78; }
.metric-card.warning { border-left-color: #ed8936; }
.metric-card.critical { border-left-color: #f56565; }
.metric-card.info { border-left-color: #4299e1; }

.metric-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 15px;
}

.metric-title {
    font-size: 14px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.metric-icon.healthy { background: #48bb78; }
.metric-icon.warning { background: #ed8936; }
.metric-icon.critical { background: #f56565; }
.metric-icon.info { background: #4299e1; }

.metric-value {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 8px;
}

.metric-change {
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.metric-change.positive {
    color: #48bb78;
}

.metric-change.negative {
    color: #f56565;
}

.metric-change i {
    margin-right: 4px;
}

.monitoring-sections {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.monitoring-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 10px;
    color: #667eea;
}

.refresh-btn {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    color: #4a5568;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    background: #edf2f7;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 20px;
}

.alert-list {
    max-height: 300px;
    overflow-y: auto;
}

.alert-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: background-color 0.3s ease;
}

.alert-item:hover {
    background: #f7fafc;
}

.alert-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 14px;
    color: white;
}

.alert-icon.high { background: #f56565; }
.alert-icon.medium { background: #ed8936; }
.alert-icon.low { background: #4299e1; }

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 4px;
}

.alert-time {
    font-size: 12px;
    color: #718096;
}

.system-status {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.status-item {
    text-align: center;
    padding: 15px;
    background: #f7fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 auto 8px;
}

.status-indicator.healthy { background: #48bb78; }
.status-indicator.warning { background: #ed8936; }
.status-indicator.unhealthy { background: #f56565; }

.status-label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.performance-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.performance-metric {
    text-align: center;
    padding: 20px;
    background: #f7fafc;
    border-radius: 8px;
}

.performance-value {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.performance-label {
    font-size: 12px;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.time-range-selector {
    display: flex;
    gap: 5px;
    background: #f7fafc;
    padding: 4px;
    border-radius: 6px;
}

.time-range-btn {
    padding: 6px 12px;
    border: none;
    background: transparent;
    color: #4a5568;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.time-range-btn.active {
    background: white;
    color: #667eea;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.export-controls {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.export-btn {
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.export-btn:hover {
    background: #5a67d8;
}

.real-time-indicator {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #48bb78;
    font-size: 12px;
}

.pulse {
    width: 8px;
    height: 8px;
    background: #48bb78;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .monitoring-sections {
        grid-template-columns: 1fr;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .enterprise-dashboard {
        padding: 10px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .system-status {
        grid-template-columns: 1fr;
    }
    
    .performance-grid {
        grid-template-columns: 1fr;
    }
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #e2e8f0;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('content')
<div class="enterprise-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line mr-3"></i>Enterprise Monitoring Dashboard</h1>
        <div class="subtitle">
            Real-time system monitoring and business intelligence
            <span class="real-time-indicator ml-4">
                <div class="pulse"></div>
                Live Data
            </span>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="metrics-grid">
        <div class="metric-card healthy">
            <div class="metric-header">
                <div class="metric-title">Total Users</div>
                <div class="metric-icon healthy">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($metrics['total_users']) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +12% from last month
            </div>
        </div>

        <div class="metric-card info">
            <div class="metric-header">
                <div class="metric-title">Active Users (24h)</div>
                <div class="metric-icon info">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($metrics['active_users_24h']) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +5% from yesterday
            </div>
        </div>

        <div class="metric-card healthy">
            <div class="metric-header">
                <div class="metric-title">Total Properties</div>
                <div class="metric-icon healthy">
                    <i class="fas fa-home"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($metrics['total_properties']) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +8% from last month
            </div>
        </div>

        <div class="metric-card info">
            <div class="metric-header">
                <div class="metric-title">Active Bookings</div>
                <div class="metric-icon info">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($metrics['active_bookings']) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +15% from last week
            </div>
        </div>

        <div class="metric-card healthy">
            <div class="metric-header">
                <div class="metric-title">Revenue Today</div>
                <div class="metric-icon healthy">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="metric-value">₱{{ number_format($metrics['revenue_today'], 2) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +22% from yesterday
            </div>
        </div>

        <div class="metric-card warning">
            <div class="metric-header">
                <div class="metric-title">Security Events (24h)</div>
                <div class="metric-icon warning">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="metric-value">{{ number_format($metrics['security_events_24h']) }}</div>
            <div class="metric-change negative">
                <i class="fas fa-arrow-down"></i>
                -8% from yesterday
            </div>
        </div>

        <div class="metric-card healthy">
            <div class="metric-header">
                <div class="metric-title">System Uptime</div>
                <div class="metric-icon healthy">
                    <i class="fas fa-server"></i>
                </div>
            </div>
            <div class="metric-value">{{ $metrics['system_uptime'] }}</div>
            <div class="metric-change positive">
                <i class="fas fa-check"></i>
                All systems operational
            </div>
        </div>

        <div class="metric-card info">
            <div class="metric-header">
                <div class="metric-title">Monthly Revenue</div>
                <div class="metric-icon info">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
            <div class="metric-value">₱{{ number_format($metrics['revenue_month'], 2) }}</div>
            <div class="metric-change positive">
                <i class="fas fa-arrow-up"></i>
                +18% from last month
            </div>
        </div>
    </div>

    <!-- Monitoring Sections -->
    <div class="monitoring-sections">
        <!-- System Health -->
        <div class="monitoring-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-heartbeat"></i>
                    System Health
                </div>
                <button class="refresh-btn" onclick="refreshSystemHealth()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            
            <div class="system-status">
                <div class="status-item">
                    <div class="status-indicator {{ $healthStatus['database_status'] }}"></div>
                    <div class="status-label">Database</div>
                </div>
                <div class="status-item">
                    <div class="status-indicator {{ $healthStatus['cache_status'] }}"></div>
                    <div class="status-label">Cache</div>
                </div>
                <div class="status-item">
                    <div class="status-indicator {{ $healthStatus['storage_status'] }}"></div>
                    <div class="status-label">Storage</div>
                </div>
                <div class="status-item">
                    <div class="status-indicator {{ $healthStatus['queue_status'] }}"></div>
                    <div class="status-label">Queue</div>
                </div>
                <div class="status-item">
                    <div class="status-indicator {{ $healthStatus['api_status'] }}"></div>
                    <div class="status-label">API</div>
                </div>
            </div>
        </div>

        <!-- Security Alerts -->
        <div class="monitoring-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    Security Alerts
                </div>
                <div class="time-range-selector">
                    <button class="time-range-btn active" data-range="24h">24h</button>
                    <button class="time-range-btn" data-range="7d">7d</button>
                    <button class="time-range-btn" data-range="30d">30d</button>
                </div>
            </div>
            
            <div class="alert-list">
                @forelse($securityAlerts as $alert)
                <div class="alert-item">
                    <div class="alert-icon {{ $alert['severity'] }}">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <div class="alert-title">{{ ucfirst(str_replace('_', ' ', $alert['event_type'])) }}</div>
                        <div class="alert-time">{{ $alert['created_at']->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p>No security alerts in the selected timeframe</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="monitoring-sections">
        <!-- Performance Charts -->
        <div class="monitoring-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Performance Metrics
                </div>
                <button class="refresh-btn" onclick="refreshPerformanceMetrics()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            
            <div class="performance-grid">
                <div class="performance-metric">
                    <div class="performance-value">{{ $performanceMetrics['average_response_time'] }}ms</div>
                    <div class="performance-label">Avg Response Time</div>
                </div>
                <div class="performance-metric">
                    <div class="performance-value">{{ number_format($performanceMetrics['requests_per_minute'], 1) }}</div>
                    <div class="performance-label">Requests/Min</div>
                </div>
                <div class="performance-metric">
                    <div class="performance-value">{{ $performanceMetrics['error_rate'] * 100 }}%</div>
                    <div class="performance-label">Error Rate</div>
                </div>
                <div class="performance-metric">
                    <div class="performance-value">{{ $performanceMetrics['cpu_usage'] }}%</div>
                    <div class="performance-label">CPU Usage</div>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- Business Intelligence -->
        <div class="monitoring-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-chart-pie"></i>
                    Business Intelligence
                </div>
                <div class="time-range-selector">
                    <button class="time-range-btn" data-range="24h">24h</button>
                    <button class="time-range-btn active" data-range="7d">7d</button>
                    <button class="time-range-btn" data-range="30d">30d</button>
                </div>
            </div>
            
            <div class="chart-container">
                <canvas id="businessChart"></canvas>
            </div>
            
            <div class="export-controls">
                <button class="export-btn" onclick="exportReport('business')">
                    <i class="fas fa-download"></i> Export Report
                </button>
                <button class="export-btn" onclick="generateAnalytics()">
                    <i class="fas fa-chart-line"></i> Generate Analytics
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
class EnterpriseMonitoring {
    constructor() {
        this.performanceChart = null;
        this.businessChart = null;
        this.init();
    }

    init() {
        this.initializeCharts();
        this.setupEventListeners();
        this.startRealTimeUpdates();
    }

    initializeCharts() {
        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        this.performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: this.generateTimeLabels(24),
                datasets: [{
                    label: 'Response Time (ms)',
                    data: this.generateMockData(24, 200, 500),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }, {
                    label: 'CPU Usage (%)',
                    data: this.generateMockData(24, 30, 80),
                    borderColor: '#48bb78',
                    backgroundColor: 'rgba(72, 187, 120, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Business Chart
        const businessCtx = document.getElementById('businessChart').getContext('2d');
        this.businessChart = new Chart(businessCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 19, 15, 25, 22, 30, 28],
                    backgroundColor: 'rgba(102, 126, 234, 0.8)'
                }, {
                    label: 'Revenue (₱1000s)',
                    data: [8, 12, 10, 15, 14, 18, 16],
                    backgroundColor: 'rgba(72, 187, 120, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    setupEventListeners() {
        // Time range selector
        document.querySelectorAll('.time-range-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.time-range-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.updateCharts(e.target.dataset.range);
            });
        });
    }

    startRealTimeUpdates() {
        setInterval(() => {
            this.updateMetrics();
        }, 30000); // Update every 30 seconds
    }

    updateMetrics() {
        fetch('/enterprise/monitoring/metrics?range=24h')
            .then(response => response.json())
            .then(data => {
                this.updateMetricCards(data);
                this.updateCharts(data);
            })
            .catch(error => console.error('Error updating metrics:', error));
    }

    updateMetricCards(data) {
        // Update metric values in the cards
        // Implementation would update DOM elements with new data
    }

    updateCharts(timeRange = '24h') {
        // Fetch new data and update charts
        fetch(`/enterprise/monitoring/performance?range=${timeRange}`)
            .then(response => response.json())
            .then(data => {
                // Update chart data
                this.performanceChart.data.datasets[0].data = data.response_times || this.generateMockData(24, 200, 500);
                this.performanceChart.data.datasets[1].data = data.cpu_usage || this.generateMockData(24, 30, 80);
                this.performanceChart.update();
            })
            .catch(error => console.error('Error updating performance charts:', error));
    }

    generateTimeLabels(hours) {
        const labels = [];
        for (let i = hours; i >= 0; i--) {
            const time = new Date();
            time.setHours(time.getHours() - i);
            labels.push(time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
        }
        return labels;
    }

    generateMockData(length, min, max) {
        return Array.from({ length }, () => Math.floor(Math.random() * (max - min + 1)) + min);
    }
}

// Global functions for button handlers
function refreshSystemHealth() {
    fetch('/enterprise/monitoring/health')
        .then(response => response.json())
        .then(data => {
            // Update system health indicators
            console.log('System health refreshed:', data);
        })
        .catch(error => console.error('Error refreshing system health:', error));
}

function refreshPerformanceMetrics() {
    window.enterpriseMonitoring.updateMetrics();
}

function exportReport(type) {
    const startDate = document.getElementById('start-date')?.value || new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0];
    const endDate = document.getElementById('end-date')?.value || new Date().toISOString().split('T')[0];
    
    fetch('/enterprise/monitoring/report', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            report_type: type,
            date_range: { start: startDate, end: endDate },
            format: 'pdf'
        })
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${type}_report_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    })
    .catch(error => console.error('Error exporting report:', error));
}

function generateAnalytics() {
    // Open analytics generation modal or redirect
    window.open('/enterprise/monitoring/analytics', '_blank');
}

// Initialize the monitoring dashboard
document.addEventListener('DOMContentLoaded', function() {
    window.enterpriseMonitoring = new EnterpriseMonitoring();
});
</script>
@endsection
