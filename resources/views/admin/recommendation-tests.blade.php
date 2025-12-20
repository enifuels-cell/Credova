@extends('layouts.admin')

@section('title', 'AI Recommendation Testing Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">AI Recommendation Testing</h1>
        <button id="runTestsBtn" class="btn btn-primary">
            <i class="fas fa-flask mr-2"></i>Run Algorithm Tests
        </button>
    </div>

    <!-- Test Results Dashboard -->
    <div class="row">
        <!-- Test Status Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Test Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="testStatus">Ready</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Points Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Points</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="dataPoints">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Algorithm Performance -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Accuracy Score</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="accuracyScore">--</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" id="accuracyProgress" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-brain fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coverage -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Coverage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="coverageScore">--</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Results Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Algorithm Test Results</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Test Actions:</div>
                            <a class="dropdown-item" href="#" onclick="exportTestResults()">Export Results</a>
                            <a class="dropdown-item" href="#" onclick="clearTestResults()">Clear Results</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="testResults" class="text-center">
                        <i class="fas fa-flask fa-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-600">Click "Run Algorithm Tests" to begin testing the AI recommendation algorithms with real data.</p>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loadingState" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-3 text-gray-600">Running comprehensive algorithm tests...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Test Results -->
    <div id="detailedResults" style="display: none;">
        <div class="row">
            <!-- User Preferences Test -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">User Preference-Based Recommendations</h6>
                    </div>
                    <div class="card-body">
                        <div id="userPreferencesResults"></div>
                    </div>
                </div>
            </div>

            <!-- Collaborative Filtering Test -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Collaborative Filtering</h6>
                    </div>
                    <div class="card-body">
                        <div id="collaborativeResults"></div>
                    </div>
                </div>
            </div>

            <!-- Content-Based Filtering Test -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Content-Based Filtering</h6>
                    </div>
                    <div class="card-body">
                        <div id="contentBasedResults"></div>
                    </div>
                </div>
            </div>

            <!-- Trending Properties Test -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trending Properties Algorithm</h6>
                    </div>
                    <div class="card-body">
                        <div id="trendingResults"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let testResultsData = null;

// Run algorithm tests
document.getElementById('runTestsBtn').addEventListener('click', function() {
    runAlgorithmTests();
});

async function runAlgorithmTests() {
    const btn = document.getElementById('runTestsBtn');
    const originalText = btn.innerHTML;
    
    // Show loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status"></span>Running Tests...';
    document.getElementById('testResults').style.display = 'none';
    document.getElementById('loadingState').style.display = 'block';
    document.getElementById('testStatus').textContent = 'Running...';
    
    try {
        const response = await fetch('/admin/test-recommendations', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        testResultsData = data;
        
        // Update status cards
        updateStatusCards(data);
        
        // Display test results
        displayTestResults(data);
        
        // Show detailed results
        displayDetailedResults(data.test_results);
        
        document.getElementById('testStatus').textContent = 'Completed';
        
    } catch (error) {
        console.error('Error running tests:', error);
        document.getElementById('testResults').innerHTML = 
            '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error running algorithm tests. Please check the console for details.</div>';
        document.getElementById('testStatus').textContent = 'Error';
    } finally {
        // Reset button
        btn.disabled = false;
        btn.innerHTML = originalText;
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('testResults').style.display = 'block';
    }
}

function updateStatusCards(data) {
    if (data.test_results && data.test_results.performance_metrics) {
        const metrics = data.test_results.performance_metrics;
        
        // Update data points
        if (metrics.total_data_points) {
            const total = Object.values(metrics.total_data_points).reduce((a, b) => a + b, 0);
            document.getElementById('dataPoints').textContent = total.toLocaleString();
        }
        
        // Update accuracy score
        if (metrics.algorithm_performance && metrics.algorithm_performance.recommendation_accuracy) {
            const accuracy = parseFloat(metrics.algorithm_performance.recommendation_accuracy);
            document.getElementById('accuracyScore').textContent = accuracy.toFixed(1) + '%';
            document.getElementById('accuracyProgress').style.width = accuracy + '%';
            document.getElementById('accuracyProgress').setAttribute('aria-valuenow', accuracy);
        }
        
        // Update coverage score
        if (metrics.algorithm_performance && metrics.algorithm_performance.recommendation_coverage) {
            document.getElementById('coverageScore').textContent = metrics.algorithm_performance.recommendation_coverage;
        }
    }
}

function displayTestResults(data) {
    const container = document.getElementById('testResults');
    
    let html = '<div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i><strong>Algorithm Testing Completed Successfully!</strong></div>';
    
    if (data.summary) {
        html += '<div class="row">';
        html += '<div class="col-md-6">';
        html += '<h5>Test Summary</h5>';
        html += '<ul class="list-group list-group-flush">';
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">Tests Completed <span class="badge badge-primary badge-pill">${data.summary.tests_completed}</span></li>`;
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">Overall Status <span class="badge badge-success badge-pill">${data.summary.overall_status}</span></li>`;
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">Algorithm Performance <span class="badge badge-info badge-pill">${data.summary.algorithm_performance}</span></li>`;
        html += `<li class="list-group-item d-flex justify-content-between align-items-center">Production Readiness <span class="badge badge-warning badge-pill">${data.summary.recommendations?.production_readiness || 'unknown'}</span></li>`;
        html += '</ul>';
        html += '</div>';
        
        if (data.summary.key_metrics) {
            html += '<div class="col-md-6">';
            html += '<h5>Key Metrics</h5>';
            html += '<ul class="list-group list-group-flush">';
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">Coverage <span class="badge badge-secondary badge-pill">${data.summary.key_metrics.coverage}</span></li>`;
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">Accuracy <span class="badge badge-secondary badge-pill">${data.summary.key_metrics.accuracy}</span></li>`;
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">Engagement <span class="badge badge-secondary badge-pill">${data.summary.key_metrics.engagement}</span></li>`;
            html += '</ul>';
            html += '</div>';
        }
        html += '</div>';
    }
    
    container.innerHTML = html;
}

function displayDetailedResults(testResults) {
    // User Preferences Results
    if (testResults.user_preferences) {
        displayUserPreferencesResults(testResults.user_preferences);
    }
    
    // Collaborative Filtering Results
    if (testResults.collaborative_filtering) {
        displayCollaborativeResults(testResults.collaborative_filtering);
    }
    
    // Content-Based Results
    if (testResults.content_based) {
        displayContentBasedResults(testResults.content_based);
    }
    
    // Trending Results
    if (testResults.trending) {
        displayTrendingResults(testResults.trending);
    }
    
    document.getElementById('detailedResults').style.display = 'block';
}

function displayUserPreferencesResults(results) {
    let html = '<div class="table-responsive"><table class="table table-sm">';
    html += '<thead><tr><th>User</th><th>Recommendations</th><th>Quality Score</th><th>Price Alignment</th></tr></thead><tbody>';
    
    results.forEach(result => {
        html += `<tr>`;
        html += `<td>${result.user_name}</td>`;
        html += `<td>${result.recommendations_count}</td>`;
        html += `<td><span class="badge badge-${result.quality_score > 70 ? 'success' : 'warning'}">${result.quality_score.toFixed(1)}</span></td>`;
        html += `<td>${result.price_alignment.toFixed(1)}%</td>`;
        html += `</tr>`;
    });
    
    html += '</tbody></table></div>';
    document.getElementById('userPreferencesResults').innerHTML = html;
}

function displayCollaborativeResults(results) {
    let html = '<div class="row">';
    html += `<div class="col-6"><strong>Test User:</strong> #${results.test_user_id}</div>`;
    html += `<div class="col-6"><strong>Similar Users:</strong> ${results.similar_users_found}</div>`;
    html += `<div class="col-6"><strong>Recommendations:</strong> ${results.collaborative_recommendations}</div>`;
    html += `<div class="col-6"><strong>Accuracy:</strong> ${results.potential_accuracy}%</div>`;
    html += '</div>';
    document.getElementById('collaborativeResults').innerHTML = html;
}

function displayContentBasedResults(results) {
    let html = '<div class="row">';
    html += `<div class="col-6"><strong>Base Property:</strong> #${results.base_property_id}</div>`;
    html += `<div class="col-6"><strong>Type:</strong> ${results.base_property_type}</div>`;
    html += `<div class="col-6"><strong>Similar Properties:</strong> ${results.similar_properties_found}</div>`;
    html += `<div class="col-6"><strong>Similarity Score:</strong> ${results.average_similarity_score}%</div>`;
    html += '</div>';
    document.getElementById('contentBasedResults').innerHTML = html;
}

function displayTrendingResults(results) {
    let html = '<div class="mb-3">';
    html += `<strong>Trending Properties Found:</strong> ${results.trending_properties_count}<br>`;
    html += `<strong>Algorithm Effectiveness:</strong> ${results.trending_algorithm_effectiveness}%`;
    html += '</div>';
    
    if (results.top_trending && results.top_trending.length > 0) {
        html += '<div class="table-responsive"><table class="table table-sm">';
        html += '<thead><tr><th>Property</th><th>Location</th><th>Bookings</th><th>Rating</th></tr></thead><tbody>';
        
        results.top_trending.forEach(property => {
            html += `<tr>`;
            html += `<td>${property.title.substring(0, 30)}...</td>`;
            html += `<td>${property.location}</td>`;
            html += `<td>${property.booking_count}</td>`;
            html += `<td>${property.average_rating}</td>`;
            html += `</tr>`;
        });
        
        html += '</tbody></table></div>';
    }
    
    document.getElementById('trendingResults').innerHTML = html;
}

function exportTestResults() {
    if (testResultsData) {
        const dataStr = JSON.stringify(testResultsData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'ai-recommendation-test-results.json';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    }
}

function clearTestResults() {
    document.getElementById('testResults').innerHTML = 
        '<div class="text-center"><i class="fas fa-flask fa-3x text-gray-300 mb-3"></i><p class="text-gray-600">Click "Run Algorithm Tests" to begin testing the AI recommendation algorithms with real data.</p></div>';
    document.getElementById('detailedResults').style.display = 'none';
    document.getElementById('testStatus').textContent = 'Ready';
    document.getElementById('dataPoints').textContent = 'Loading...';
    document.getElementById('accuracyScore').textContent = '--';
    document.getElementById('coverageScore').textContent = '--';
    document.getElementById('accuracyProgress').style.width = '0%';
    testResultsData = null;
}
</script>
@endsection
