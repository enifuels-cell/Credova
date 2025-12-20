// AI Recommendations Component
class AIRecommendations {
    constructor() {
        this.baseURL = window.location.protocol + '//' + window.location.host;
        this.init();
    }

    // Helper method to create absolute HTTPS URLs
    getApiUrl(endpoint) {
        return `${this.baseURL}${endpoint}`;
    }

    // Helper method to get authorization headers
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json'
        };
        
        const token = this.getToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        return headers;
    }

    init() {
        this.bindEvents();
        this.loadPersonalizedRecommendations();
    }

    bindEvents() {
        // Recommendation feedback buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.rec-like-btn')) {
                this.provideFeedback(e.target.dataset.propertyId, 'liked');
            }
            if (e.target.matches('.rec-dislike-btn')) {
                this.provideFeedback(e.target.dataset.propertyId, 'disliked');
            }
            if (e.target.matches('.rec-view-btn')) {
                this.provideFeedback(e.target.dataset.propertyId, 'viewed');
            }
        });

        // Load more recommendations
        document.addEventListener('click', (e) => {
            if (e.target.matches('.load-more-recommendations')) {
                this.loadMoreRecommendations();
            }
        });

        // Refresh recommendations
        document.addEventListener('click', (e) => {
            if (e.target.matches('.refresh-recommendations')) {
                this.refreshRecommendations();
            }
        });

        // Search suggestions
        const searchInput = document.querySelector('#property-search');
        if (searchInput) {
            searchInput.addEventListener('input', debounce((e) => {
                this.getSearchSuggestions(e.target.value);
            }, 300));
        }
    }

    async loadPersonalizedRecommendations(limit = 10) {
        try {
            const response = await fetch(this.getApiUrl(`/api/ai-recommendations/personalized?limit=${limit}`), {
                headers: this.getHeaders()
            });

            const data = await response.json();
            this.renderRecommendations(data.recommendations, 'personalized-recommendations');
            this.updateUserProfile(data.user_profile);
        } catch (error) {
            console.error('Failed to load personalized recommendations:', error);
        }
    }

    async loadSimilarProperties(propertyId, limit = 8) {
        try {
            const response = await fetch(this.getApiUrl(`/api/ai-recommendations/similar/${propertyId}?limit=${limit}`));
            const data = await response.json();
            this.renderRecommendations(data.similar_properties, 'similar-properties');
        } catch (error) {
            console.error('Failed to load similar properties:', error);
        }
    }

    async loadTrendingProperties(limit = 10) {
        try {
            const response = await fetch(this.getApiUrl(`/api/ai-recommendations/trending?limit=${limit}`));
            const data = await response.json();
            this.renderRecommendations(data.trending_properties, 'trending-properties');
        } catch (error) {
            console.error('Failed to load trending properties:', error);
        }
    }

    async provideFeedback(propertyId, feedbackType, rating = null, notes = '') {
        try {
            const response = await fetch(this.getApiUrl('/api/ai-recommendations/feedback'), {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify({
                    property_id: propertyId,
                    feedback_type: feedbackType,
                    rating: rating,
                    notes: notes
                })
            });

            if (response.ok) {
                this.showFeedbackSuccess(feedbackType);
                this.updateRecommendationUI(propertyId, feedbackType);
            }
        } catch (error) {
            console.error('Failed to provide feedback:', error);
        }
    }

    async getSearchSuggestions(query) {
        if (query.length < 2) return;

        try {
            const response = await fetch(this.getApiUrl(`/api/ai-recommendations/search-suggestions?q=${encodeURIComponent(query)}`), {
                headers: this.getHeaders()
            });

            const data = await response.json();
            this.renderSearchSuggestions(data);
        } catch (error) {
            console.error('Failed to get search suggestions:', error);
        }
    }

    async getPricePrediction(propertyId) {
        try {
            const response = await fetch(this.getApiUrl(`/api/ai-recommendations/price-prediction/${propertyId}`), {
                headers: this.getHeaders()
            });

            const data = await response.json();
            this.renderPricePrediction(data);
        } catch (error) {
            console.error('Failed to get price prediction:', error);
        }
    }

    renderRecommendations(recommendations, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = recommendations.map(property => `
            <div class="recommendation-card" data-property-id="${property.id}">
                <div class="property-image">
                    <img src="${property.featured_image || '/images/placeholder.jpg'}" 
                         alt="${property.title}" loading="lazy">
                    <div class="property-badge">${property.recommendation_reason || 'Recommended'}</div>
                </div>
                
                <div class="property-info">
                    <h3 class="property-title">${property.title}</h3>
                    <p class="property-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${property.location}
                    </p>
                    
                    <div class="property-details">
                        <span class="property-type">${property.property_type}</span>
                        <span class="property-guests">
                            <i class="fas fa-users"></i> ${property.max_guests} guests
                        </span>
                    </div>
                    
                    <div class="property-rating">
                        ${this.renderStarRating(property.average_rating)}
                        <span>(${property.review_count} reviews)</span>
                    </div>
                    
                    <div class="property-price">
                        <span class="price-amount">$${property.price_per_night}</span>
                        <span class="price-unit">per night</span>
                        ${property.predicted_price ? `
                            <div class="price-prediction">
                                <small>Predicted: $${property.predicted_price}</small>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="recommendation-actions">
                        <button class="btn btn-primary rec-view-btn" 
                                data-property-id="${property.id}">
                            View Details
                        </button>
                        <div class="feedback-buttons">
                            <button class="btn-icon rec-like-btn" 
                                    data-property-id="${property.id}"
                                    title="Like this recommendation">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="btn-icon rec-dislike-btn" 
                                    data-property-id="${property.id}"
                                    title="Not interested">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                        </div>
                    </div>
                    
                    ${property.match_score ? `
                        <div class="match-score">
                            <div class="match-score-bar">
                                <div class="match-score-fill" 
                                     style="width: ${property.match_score}%"></div>
                            </div>
                            <span class="match-score-text">${property.match_score}% match</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        `).join('');

        // Add click handlers for view buttons
        container.querySelectorAll('.rec-view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const propertyId = btn.dataset.propertyId;
                this.provideFeedback(propertyId, 'viewed');
                window.location.href = `/properties/${propertyId}`;
            });
        });
    }

    renderSearchSuggestions(suggestions) {
        const container = document.getElementById('search-suggestions');
        if (!container) return;

        const html = `
            <div class="search-suggestions-panel">
                ${suggestions.locations.length > 0 ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-map-marker-alt"></i> Locations</h4>
                        ${suggestions.locations.map(location => `
                            <div class="suggestion-item" data-type="location" data-value="${location}">
                                ${location}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                ${suggestions.property_types.length > 0 ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-home"></i> Property Types</h4>
                        ${suggestions.property_types.map(type => `
                            <div class="suggestion-item" data-type="property_type" data-value="${type}">
                                ${type}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                ${suggestions.amenities.length > 0 ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-star"></i> Amenities</h4>
                        ${suggestions.amenities.map(amenity => `
                            <div class="suggestion-item" data-type="amenity" data-value="${amenity}">
                                ${amenity}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                <div class="suggestion-group">
                    <h4><i class="fas fa-fire"></i> Popular Searches</h4>
                    ${suggestions.popular_searches.map(search => `
                        <div class="suggestion-item popular-search" data-value="${search}">
                            ${search}
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        container.innerHTML = html;
        container.style.display = 'block';

        // Add click handlers for suggestions
        container.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', () => {
                const searchInput = document.querySelector('#property-search');
                searchInput.value = item.dataset.value;
                container.style.display = 'none';
                // Trigger search
                searchInput.dispatchEvent(new Event('change'));
            });
        });
    }

    renderPricePrediction(prediction) {
        const container = document.getElementById('price-prediction-container');
        if (!container) return;

        container.innerHTML = `
            <div class="price-prediction-panel">
                <h4><i class="fas fa-chart-line"></i> AI Price Prediction</h4>
                
                <div class="prediction-overview">
                    <div class="current-price">
                        <label>Current Price</label>
                        <span class="price">$${prediction.current_price}</span>
                    </div>
                    <div class="predicted-price">
                        <label>Optimal Price</label>
                        <span class="price ${prediction.prediction_trend}">
                            $${prediction.predicted_price}
                        </span>
                    </div>
                    <div class="price-change">
                        <label>Change</label>
                        <span class="change ${prediction.prediction_trend}">
                            ${prediction.price_change > 0 ? '+' : ''}${prediction.price_change}%
                        </span>
                    </div>
                </div>
                
                <div class="prediction-factors">
                    <h5>Key Factors</h5>
                    ${prediction.factors.map(factor => `
                        <div class="factor-item">
                            <span class="factor-name">${factor.name}</span>
                            <div class="factor-impact ${factor.impact}">
                                <div class="factor-bar" style="width: ${Math.abs(factor.score)}%"></div>
                            </div>
                            <span class="factor-score">${factor.score > 0 ? '+' : ''}${factor.score}%</span>
                        </div>
                    `).join('')}
                </div>
                
                <div class="prediction-confidence">
                    <label>Confidence Level</label>
                    <div class="confidence-bar">
                        <div class="confidence-fill" style="width: ${prediction.confidence}%"></div>
                    </div>
                    <span>${prediction.confidence}%</span>
                </div>
            </div>
        `;
    }

    updateUserProfile(profile) {
        const container = document.getElementById('user-profile-insights');
        if (!container) return;

        container.innerHTML = `
            <div class="profile-insights">
                <h4><i class="fas fa-user-chart"></i> Your Booking Profile</h4>
                
                <div class="insight-grid">
                    <div class="insight-item">
                        <label>Total Bookings</label>
                        <span class="value">${profile.total_bookings}</span>
                    </div>
                    
                    <div class="insight-item">
                        <label>Booking Frequency</label>
                        <span class="value">${profile.booking_frequency}</span>
                    </div>
                    
                    <div class="insight-item">
                        <label>Price Range</label>
                        <span class="value">$${profile.preferred_price_range.min} - $${profile.preferred_price_range.max}</span>
                    </div>
                </div>
                
                ${profile.favorite_locations.length > 0 ? `
                    <div class="favorite-locations">
                        <label>Favorite Locations</label>
                        <div class="location-tags">
                            ${profile.favorite_locations.map(location => `
                                <span class="location-tag">${location}</span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                ${profile.preferred_amenities.length > 0 ? `
                    <div class="preferred-amenities">
                        <label>Preferred Amenities</label>
                        <div class="amenity-tags">
                            ${profile.preferred_amenities.map(amenity => `
                                <span class="amenity-tag">${amenity}</span>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    }

    renderStarRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        return `
            <div class="star-rating">
                ${'<i class="fas fa-star"></i>'.repeat(fullStars)}
                ${hasHalfStar ? '<i class="fas fa-star-half-alt"></i>' : ''}
                ${'<i class="far fa-star"></i>'.repeat(emptyStars)}
                <span class="rating-number">${rating.toFixed(1)}</span>
            </div>
        `;
    }

    showFeedbackSuccess(feedbackType) {
        const messages = {
            liked: 'Thanks! We\'ll show you more properties like this.',
            disliked: 'Got it! We\'ll improve your recommendations.',
            viewed: 'Viewed property saved to your history.',
            not_interested: 'We\'ll remember your preferences.'
        };

        this.showToast(messages[feedbackType] || 'Feedback recorded!', 'success');
    }

    updateRecommendationUI(propertyId, feedbackType) {
        const card = document.querySelector(`[data-property-id="${propertyId}"]`);
        if (!card) return;

        if (feedbackType === 'liked') {
            card.classList.add('liked');
            card.querySelector('.rec-like-btn').classList.add('active');
        } else if (feedbackType === 'disliked') {
            card.classList.add('disliked');
            // Optionally fade out or remove the card
            setTimeout(() => {
                card.style.opacity = '0.5';
            }, 500);
        }
    }

    async loadMoreRecommendations() {
        const container = document.getElementById('personalized-recommendations');
        const currentCount = container.children.length;
        
        await this.loadPersonalizedRecommendations(currentCount + 10);
    }

    async refreshRecommendations() {
        const refreshBtn = document.querySelector('.refresh-recommendations');
        if (refreshBtn) {
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
            refreshBtn.disabled = true;
        }

        await this.loadPersonalizedRecommendations();

        if (refreshBtn) {
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            refreshBtn.disabled = false;
        }
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    getToken() {
        return localStorage.getItem('auth_token') || 
               document.querySelector('meta[name="api-token"]')?.content;
    }
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initialize AI Recommendations when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.aiRecommendations = new AIRecommendations();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AIRecommendations;
}
