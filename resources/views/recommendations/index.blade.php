@extends('layouts.app')

@section('title', 'AI Recommendations')

@section('styles')
<link href="{{ asset('css/ai-recommendations.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="page-header mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-robot text-purple-600 mr-3"></i>
            Personalized Recommendations
        </h1>
        <p class="text-gray-600">
            Discover properties tailored just for you with our AI-powered recommendation engine
        </p>
    </div>

    <!-- User Profile Insights -->
    <div class="mb-8">
        <div id="user-profile-insights">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Recommendation Controls -->
    <div class="section-actions mb-6">
        <button class="refresh-recommendations">
            <i class="fas fa-sync-alt"></i> Refresh Recommendations
        </button>
        <button class="load-more-recommendations">
            <i class="fas fa-plus"></i> Load More
        </button>
    </div>

    <!-- Personalized Recommendations Section -->
    <div class="recommendations-section">
        <h2>
            <i class="fas fa-magic"></i>
            Recommended for You
        </h2>
        <p class="section-subtitle">
            Based on your booking history, preferences, and behavior patterns
        </p>
        
        <div class="recommendations-grid" id="personalized-recommendations">
            <!-- Loading state -->
            <div class="loading-recommendations">
                <i class="fas fa-spinner fa-spin"></i>
                Loading personalized recommendations...
            </div>
        </div>
    </div>

    <!-- Trending Properties Section -->
    <div class="recommendations-section">
        <h2>
            <i class="fas fa-fire"></i>
            Trending Now
        </h2>
        <p class="section-subtitle">
            Popular properties that other guests are booking
        </p>
        
        <div class="recommendations-grid" id="trending-properties">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Based on Saved Searches Section -->
    @if(auth()->user()->savedSearches()->count() > 0)
    <div class="recommendations-section">
        <h2>
            <i class="fas fa-search"></i>
            From Your Saved Searches
        </h2>
        <p class="section-subtitle">
            New properties matching your saved search criteria
        </p>
        
        <div class="recommendations-grid" id="saved-search-recommendations">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
    @endif

    <!-- Recommendation Insights Modal -->
    <div id="insights-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Why We Recommended This</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div id="recommendation-insights-content">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Preferences Modal -->
    <div id="preferences-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Recommendation Preferences</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="preferences-form">
                    <div class="preference-group">
                        <label>Price Sensitivity</label>
                        <div class="slider-container">
                            <input type="range" name="price_sensitivity" min="0" max="1" step="0.1" value="0.5">
                            <div class="slider-labels">
                                <span>Budget Conscious</span>
                                <span>Price Flexible</span>
                            </div>
                        </div>
                    </div>

                    <div class="preference-group">
                        <label>Location Importance</label>
                        <div class="slider-container">
                            <input type="range" name="location_importance" min="0" max="1" step="0.1" value="0.7">
                            <div class="slider-labels">
                                <span>Flexible</span>
                                <span>Very Important</span>
                            </div>
                        </div>
                    </div>

                    <div class="preference-group">
                        <label>Amenity Importance</label>
                        <div class="slider-container">
                            <input type="range" name="amenity_importance" min="0" max="1" step="0.1" value="0.6">
                            <div class="slider-labels">
                                <span>Basic is Fine</span>
                                <span>Must Have All</span>
                            </div>
                        </div>
                    </div>

                    <div class="preference-group">
                        <label>Review Importance</label>
                        <div class="slider-container">
                            <input type="range" name="review_importance" min="0" max="1" step="0.1" value="0.8">
                            <div class="slider-labels">
                                <span>Don't Mind</span>
                                <span>Only Highly Rated</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                        <button type="button" class="btn btn-secondary modal-close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Smart Search Bar with AI Suggestions -->
<div class="search-bar-container" style="position: relative;">
    <input 
        type="text" 
        id="property-search" 
        placeholder="Search for your perfect stay..."
        class="form-control"
        autocomplete="off"
    >
    <div id="search-suggestions" class="search-suggestions-container" style="display: none;">
        <!-- AI-powered suggestions will be populated here -->
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/ai-recommendations.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AI recommendations
    window.aiRecommendations = new AIRecommendations();
    
    // Load initial data
    window.aiRecommendations.loadPersonalizedRecommendations();
    window.aiRecommendations.loadTrendingProperties();
    
    @if(auth()->user()->savedSearches()->count() > 0)
    // Load recommendations based on saved searches
    fetch('/ai-recommendations/saved-searches')
        .then(response => response.json())
        .then(data => {
            window.aiRecommendations.renderRecommendations(
                data.recommendations, 
                'saved-search-recommendations'
            );
        })
        .catch(error => console.error('Error loading saved search recommendations:', error));
    @endif
    
    // Modal handlers
    document.addEventListener('click', function(e) {
        if (e.target.matches('.show-insights-btn')) {
            const propertyId = e.target.dataset.propertyId;
            showRecommendationInsights(propertyId);
        }
        
        if (e.target.matches('.show-preferences-btn')) {
            document.getElementById('preferences-modal').style.display = 'block';
        }
        
        if (e.target.matches('.modal-close')) {
            e.target.closest('.modal').style.display = 'none';
        }
    });
    
    // Preferences form handler
    document.getElementById('preferences-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const preferences = {};
        
        for (let [key, value] of formData.entries()) {
            preferences[key] = parseFloat(value);
        }
        
        window.aiRecommendations.updatePreferences(preferences)
            .then(() => {
                document.getElementById('preferences-modal').style.display = 'none';
                window.aiRecommendations.showToast('Preferences updated successfully!', 'success');
                window.aiRecommendations.refreshRecommendations();
            })
            .catch(error => {
                console.error('Error updating preferences:', error);
                window.aiRecommendations.showToast('Failed to update preferences', 'error');
            });
    });
});

async function showRecommendationInsights(propertyId) {
    try {
        const response = await fetch(`/ai-recommendations/insights?property_id=${propertyId}`, {
            headers: {
                'Authorization': `Bearer ${window.aiRecommendations.getToken()}`
            }
        });
        
        const insights = await response.json();
        
        const content = document.getElementById('recommendation-insights-content');
        content.innerHTML = `
            <div class="insights-grid">
                <div class="insight-card">
                    <h4><i class="fas fa-chart-line"></i> Booking Patterns</h4>
                    <p>Average stay: ${insights.user_booking_patterns.average_stay_duration} days</p>
                    <p>Booking frequency: ${insights.user_booking_patterns.booking_frequency}</p>
                    <p>Lead time: ${insights.user_booking_patterns.booking_lead_time} days</p>
                </div>
                
                <div class="insight-card">
                    <h4><i class="fas fa-dollar-sign"></i> Price Analysis</h4>
                    <p>Average booking: $${insights.price_analysis.average_booking_value}</p>
                    <p>Price trend: ${insights.price_analysis.price_trend}</p>
                </div>
                
                <div class="insight-card">
                    <h4><i class="fas fa-map-marker-alt"></i> Location Preferences</h4>
                    <div class="preference-tags">
                        ${insights.location_preferences.favorite_locations.map(loc => 
                            `<span class="preference-tag">${loc}</span>`
                        ).join('')}
                    </div>
                </div>
                
                ${insights.property_match_score ? `
                    <div class="insight-card">
                        <h4><i class="fas fa-star"></i> Match Score</h4>
                        <div class="match-breakdown">
                            <div class="match-item">
                                <span>Overall Score</span>
                                <div class="score-bar">
                                    <div class="score-fill" style="width: ${insights.property_match_score.overall_score}%"></div>
                                </div>
                                <span>${insights.property_match_score.overall_score}%</span>
                            </div>
                            <div class="match-item">
                                <span>Price Match</span>
                                <div class="score-bar">
                                    <div class="score-fill" style="width: ${insights.property_match_score.price_match}%"></div>
                                </div>
                                <span>${insights.property_match_score.price_match}%</span>
                            </div>
                            <div class="match-item">
                                <span>Location Match</span>
                                <div class="score-bar">
                                    <div class="score-fill" style="width: ${insights.property_match_score.location_match}%"></div>
                                </div>
                                <span>${insights.property_match_score.location_match}%</span>
                            </div>
                        </div>
                        <p class="explanation">${insights.property_match_score.explanation}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
        document.getElementById('insights-modal').style.display = 'block';
    } catch (error) {
        console.error('Error loading insights:', error);
        window.aiRecommendations.showToast('Failed to load insights', 'error');
    }
}
</script>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 12px;
    max-width: 600px;
    width: 90%;
    max-height: 80%;
    overflow-y: auto;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #718096;
}

.modal-body {
    padding: 20px;
}

.preference-group {
    margin-bottom: 25px;
}

.preference-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #2d3748;
}

.slider-container {
    position: relative;
}

.slider-container input[type="range"] {
    width: 100%;
    margin: 10px 0;
}

.slider-labels {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #718096;
}

.modal-actions {
    margin-top: 30px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.insights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.insight-card {
    background: #f7fafc;
    padding: 20px;
    border-radius: 8px;
}

.insight-card h4 {
    margin-bottom: 15px;
    color: #2d3748;
    display: flex;
    align-items: center;
}

.insight-card h4 i {
    margin-right: 8px;
    color: #667eea;
}

.preference-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.preference-tag {
    background: #e6fffa;
    color: #234e52;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
}

.match-breakdown {
    margin-bottom: 15px;
}

.match-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.score-bar {
    flex: 1;
    height: 8px;
    background: #edf2f7;
    border-radius: 4px;
    overflow: hidden;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #48bb78 0%, #38a169 100%);
    transition: width 0.3s ease;
}

.explanation {
    font-style: italic;
    color: #718096;
    margin-top: 10px;
}
</style>
@endsection
