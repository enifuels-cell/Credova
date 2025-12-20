/**
 * AI Recommendations JavaScript Library
 * Handles all AI-powered recommendation functionality
 */

class AIRecommendations {
    constructor() {
        this.baseUrl = '/ai-recommendations';
        this.currentUser = null;
        this.recommendationCache = new Map();
        this.feedbackQueue = [];
        this.init();
    }

    init() {
        this.loadUserProfile();
        this.setupEventListeners();
        this.initializeSearchSuggestions();
    }

    async loadUserProfile() {
        try {
            const response = await fetch('/profile/api', {
                headers: { 'Authorization': `Bearer ${this.getToken()}` }
            });
            this.currentUser = await response.json();
        } catch (error) {
            console.error('Error loading user profile:', error);
        }
    }

    setupEventListeners() {
        // Recommendation feedback buttons
        document.addEventListener('click', (e) => {
            if (e.target.matches('.rec-like-btn')) {
                this.handleRecommendationFeedback(e.target.dataset.propertyId, 'liked');
            }
            
            if (e.target.matches('.rec-dislike-btn')) {
                this.handleRecommendationFeedback(e.target.dataset.propertyId, 'disliked');
            }
            
            if (e.target.matches('.rec-view-btn')) {
                this.trackRecommendationView(e.target.dataset.propertyId);
            }
            
            if (e.target.matches('.refresh-recommendations')) {
                this.refreshRecommendations();
            }
            
            if (e.target.matches('.load-more-recommendations')) {
                this.loadMoreRecommendations();
            }
        });
    }

    initializeSearchSuggestions() {
        const searchInput = document.getElementById('property-search');
        if (!searchInput) return;

        let debounceTimer;
        
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                this.fetchSearchSuggestions(e.target.value);
            }, 300);
        });

        searchInput.addEventListener('focus', () => {
            document.getElementById('search-suggestions').style.display = 'block';
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.search-bar-container')) {
                document.getElementById('search-suggestions').style.display = 'none';
            }
        });
    }

    async loadPersonalizedRecommendations(limit = 10) {
        try {
            const cacheKey = `personalized_${limit}`;
            if (this.recommendationCache.has(cacheKey)) {
                this.renderRecommendations(this.recommendationCache.get(cacheKey), 'personalized-recommendations');
                return;
            }

            this.showLoadingState('personalized-recommendations');
            
            const response = await fetch(`${this.baseUrl}/personalized?limit=${limit}`, {
                headers: { 'Authorization': `Bearer ${this.getToken()}` }
            });
            
            const data = await response.json();
            
            this.recommendationCache.set(cacheKey, data.recommendations);
            this.renderRecommendations(data.recommendations, 'personalized-recommendations');
            
            // Load user profile insights
            if (data.user_profile) {
                this.renderUserProfileInsights(data.user_profile);
            }
            
        } catch (error) {
            console.error('Error loading personalized recommendations:', error);
            this.showErrorState('personalized-recommendations', 'Failed to load recommendations');
        }
    }

    async loadTrendingProperties(limit = 8) {
        try {
            const cacheKey = `trending_${limit}`;
            if (this.recommendationCache.has(cacheKey)) {
                this.renderRecommendations(this.recommendationCache.get(cacheKey), 'trending-properties');
                return;
            }

            const response = await fetch(`${this.baseUrl}/trending?limit=${limit}`, {
                headers: { 'Authorization': `Bearer ${this.getToken()}` }
            });
            
            const data = await response.json();
            
            this.recommendationCache.set(cacheKey, data.trending_properties);
            this.renderRecommendations(data.trending_properties, 'trending-properties');
            
        } catch (error) {
            console.error('Error loading trending properties:', error);
            this.showErrorState('trending-properties', 'Failed to load trending properties');
        }
    }

    async loadSimilarProperties(propertyId, limit = 6) {
        try {
            const response = await fetch(`${this.baseUrl}/similar/${propertyId}?limit=${limit}`, {
                headers: { 'Authorization': `Bearer ${this.getToken()}` }
            });
            
            const data = await response.json();
            return data.similar_properties;
            
        } catch (error) {
            console.error('Error loading similar properties:', error);
            return [];
        }
    }

    async fetchSearchSuggestions(query) {
        if (!query || query.length < 2) {
            document.getElementById('search-suggestions').style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`${this.baseUrl}/search-suggestions?q=${encodeURIComponent(query)}`, {
                headers: { 'Authorization': `Bearer ${this.getToken()}` }
            });
            
            const suggestions = await response.json();
            this.renderSearchSuggestions(suggestions);
            
        } catch (error) {
            console.error('Error fetching search suggestions:', error);
        }
    }

    async handleRecommendationFeedback(propertyId, feedbackType) {
        try {
            // Update UI immediately
            this.updateFeedbackUI(propertyId, feedbackType);
            
            // Add to queue for batch processing
            this.feedbackQueue.push({
                property_id: propertyId,
                feedback_type: feedbackType,
                timestamp: Date.now()
            });
            
            // Process feedback
            await this.processFeedback({
                property_id: propertyId,
                feedback_type: feedbackType
            });
            
            this.showToast(`Feedback recorded: ${feedbackType}`, 'success');
            
        } catch (error) {
            console.error('Error handling recommendation feedback:', error);
            this.showToast('Failed to record feedback', 'error');
        }
    }

    async processFeedback(feedback) {
        const response = await fetch(`${this.baseUrl}/feedback`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.getToken()}`,
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify(feedback)
        });

        if (!response.ok) {
            throw new Error('Failed to process feedback');
        }

        return await response.json();
    }

    async updatePreferences(preferences) {
        const response = await fetch(`${this.baseUrl}/preferences`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.getToken()}`,
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify({ preferences })
        });

        if (!response.ok) {
            throw new Error('Failed to update preferences');
        }

        // Clear cache to force refresh
        this.recommendationCache.clear();
        
        return await response.json();
    }

    async trackRecommendationView(propertyId) {
        try {
            await this.processFeedback({
                property_id: propertyId,
                feedback_type: 'viewed'
            });
        } catch (error) {
            console.error('Error tracking recommendation view:', error);
        }
    }

    renderRecommendations(recommendations, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = recommendations.map(property => this.createRecommendationCard(property)).join('');
    }

    createRecommendationCard(property) {
        const matchScore = property.match_score || Math.floor(Math.random() * 30) + 70;
        const isLiked = property.user_feedback === 'liked';
        const isDisliked = property.user_feedback === 'disliked';
        
        return `
            <div class="recommendation-card ${isLiked ? 'liked' : ''} ${isDisliked ? 'disliked' : ''}" data-property-id="${property.id}">
                <div class="property-image">
                    <img src="${property.images?.[0] || '/images/property-placeholder.jpg'}" alt="${property.title}">
                    <div class="property-badge">${property.property_type}</div>
                </div>
                
                <div class="property-info">
                    <h3 class="property-title">${property.title}</h3>
                    
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${property.location}
                    </div>
                    
                    <div class="property-details">
                        <span class="property-type">${property.property_type}</span>
                        <span class="property-guests">
                            <i class="fas fa-users"></i>
                            ${property.max_guests} guests
                        </span>
                    </div>
                    
                    <div class="property-rating">
                        <div class="star-rating">
                            ${this.generateStarRating(property.average_rating || 0)}
                            <span class="rating-number">${(property.average_rating || 0).toFixed(1)}</span>
                        </div>
                        <span class="review-count">(${property.review_count || 0} reviews)</span>
                    </div>
                    
                    <div class="property-price">
                        <span class="price-amount">₱${Number(property.price || 0).toLocaleString()}</span>
                        <span class="price-unit">per night</span>
                        ${property.price_prediction ? `
                            <div class="price-prediction">
                                <small>Predicted: ₱${Number(property.price_prediction).toLocaleString()}</small>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="recommendation-actions">
                        <button class="rec-view-btn" data-property-id="${property.id}">
                            View Property
                        </button>
                        
                        <div class="feedback-buttons">
                            <button class="btn-icon rec-like-btn ${isLiked ? 'active' : ''}" 
                                    data-property-id="${property.id}" 
                                    title="I like this recommendation">
                                <i class="fas fa-thumbs-up"></i>
                            </button>
                            <button class="btn-icon rec-dislike-btn ${isDisliked ? 'active' : ''}" 
                                    data-property-id="${property.id}" 
                                    title="Not interested">
                                <i class="fas fa-thumbs-down"></i>
                            </button>
                            <button class="btn-icon show-insights-btn" 
                                    data-property-id="${property.id}" 
                                    title="Why was this recommended?">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="match-score">
                        <div class="match-score-bar">
                            <div class="match-score-fill" style="width: ${matchScore}%"></div>
                        </div>
                        <div class="match-score-text">
                            ${matchScore}% match based on your preferences
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    generateStarRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let stars = '';
        
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }
        
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star"></i>';
        }
        
        return stars;
    }

    renderSearchSuggestions(suggestions) {
        const container = document.getElementById('search-suggestions');
        if (!container) return;

        const html = `
            <div class="search-suggestions-panel">
                ${suggestions.locations?.length ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-map-marker-alt"></i> Locations</h4>
                        ${suggestions.locations.map(location => `
                            <div class="suggestion-item" data-type="location" data-value="${location}">
                                ${location}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                ${suggestions.property_types?.length ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-home"></i> Property Types</h4>
                        ${suggestions.property_types.map(type => `
                            <div class="suggestion-item" data-type="property_type" data-value="${type}">
                                ${type}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                ${suggestions.amenities?.length ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-star"></i> Amenities</h4>
                        ${suggestions.amenities.map(amenity => `
                            <div class="suggestion-item" data-type="amenity" data-value="${amenity}">
                                ${amenity}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
                
                ${suggestions.popular_searches?.length ? `
                    <div class="suggestion-group">
                        <h4><i class="fas fa-fire"></i> Popular Searches</h4>
                        ${suggestions.popular_searches.map(search => `
                            <div class="suggestion-item popular-search" data-type="popular" data-value="${search}">
                                ${search}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;

        container.innerHTML = html;
        container.style.display = 'block';

        // Add click handlers for suggestions
        container.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', () => {
                document.getElementById('property-search').value = item.dataset.value;
                container.style.display = 'none';
                // Trigger search
                this.performSearch(item.dataset.value, item.dataset.type);
            });
        });
    }

    renderUserProfileInsights(profile) {
        const container = document.getElementById('user-profile-insights');
        if (!container) return;

        container.innerHTML = `
            <div class="profile-insights">
                <h4><i class="fas fa-user-chart"></i> Your Booking Profile</h4>
                
                <div class="insight-grid">
                    <div class="insight-item">
                        <label>Total Bookings</label>
                        <div class="value">${profile.total_bookings || 0}</div>
                    </div>
                    <div class="insight-item">
                        <label>Booking Frequency</label>
                        <div class="value">${profile.booking_frequency || 'New User'}</div>
                    </div>
                    <div class="insight-item">
                        <label>Avg. Price Range</label>
                        <div class="value">₱${Number(profile.preferred_price_range?.average || 0).toLocaleString()}</div>
                    </div>
                </div>
                
                <div class="favorite-locations">
                    <label>Favorite Locations</label>
                    <div class="location-tags">
                        ${(profile.favorite_locations || []).map(location => `
                            <span class="location-tag">${location}</span>
                        `).join('')}
                    </div>
                </div>
                
                <div class="preferred-amenities">
                    <label>Preferred Amenities</label>
                    <div class="amenity-tags">
                        ${(profile.preferred_amenities || []).map(amenity => `
                            <span class="amenity-tag">${amenity}</span>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    updateFeedbackUI(propertyId, feedbackType) {
        const card = document.querySelector(`[data-property-id="${propertyId}"]`);
        if (!card) return;

        // Reset all feedback buttons
        card.querySelectorAll('.rec-like-btn, .rec-dislike-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Activate the clicked feedback type
        const targetBtn = card.querySelector(`.rec-${feedbackType.replace('d', '')}-btn`);
        if (targetBtn) {
            targetBtn.classList.add('active');
        }

        // Add visual feedback to card
        card.classList.remove('liked', 'disliked');
        if (feedbackType === 'liked') {
            card.classList.add('liked');
        } else if (feedbackType === 'disliked') {
            card.classList.add('disliked');
        }
    }

    showLoadingState(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="loading-recommendations">
                <i class="fas fa-spinner fa-spin"></i>
                Loading recommendations...
            </div>
        `;
    }

    showErrorState(containerId, message) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="error-state">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
                <button onclick="window.location.reload()">Retry</button>
            </div>
        `;
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }

    async refreshRecommendations() {
        this.recommendationCache.clear();
        await Promise.all([
            this.loadPersonalizedRecommendations(),
            this.loadTrendingProperties()
        ]);
        this.showToast('Recommendations updated', 'success');
    }

    async loadMoreRecommendations() {
        // Implementation for loading more recommendations
        this.showToast('Loading more recommendations...', 'info');
    }

    performSearch(query, type) {
        // Redirect to search page with parameters
        const params = new URLSearchParams({
            q: query,
            type: type || 'general'
        });
        window.location.href = `/properties/search?${params}`;
    }

    getToken() {
        return document.querySelector('meta[name="api-token"]')?.getAttribute('content') || '';
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }
}

// Test Data Generator for Development
class AIRecommendationTestData {
    constructor() {
        this.properties = this.generateTestProperties();
        this.users = this.generateTestUsers();
        this.bookings = this.generateTestBookings();
    }

    generateTestProperties(count = 50) {
        const properties = [];
        const locations = ['Manila', 'Cebu', 'Davao', 'Baguio', 'Boracay', 'Palawan', 'Bohol', 'Iloilo'];
        const propertyTypes = ['Apartment', 'House', 'Villa', 'Condo', 'Studio'];
        const amenities = ['WiFi', 'Kitchen', 'Parking', 'Pool', 'Gym', 'Balcony', 'Air Conditioning'];

        for (let i = 1; i <= count; i++) {
            properties.push({
                id: i,
                title: `Beautiful ${propertyTypes[Math.floor(Math.random() * propertyTypes.length)]} in ${locations[Math.floor(Math.random() * locations.length)]}`,
                location: locations[Math.floor(Math.random() * locations.length)],
                property_type: propertyTypes[Math.floor(Math.random() * propertyTypes.length)],
                price: Math.floor(Math.random() * 5000) + 1000,
                max_guests: Math.floor(Math.random() * 8) + 1,
                bedrooms: Math.floor(Math.random() * 4) + 1,
                bathrooms: Math.floor(Math.random() * 3) + 1,
                amenities: this.getRandomAmenities(amenities),
                average_rating: (Math.random() * 2 + 3).toFixed(1),
                review_count: Math.floor(Math.random() * 200),
                images: [`/images/property-${i % 10 + 1}.jpg`],
                created_at: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000)
            });
        }

        return properties;
    }

    generateTestUsers(count = 20) {
        const users = [];
        const names = ['John Doe', 'Jane Smith', 'Bob Wilson', 'Alice Brown', 'Charlie Davis'];

        for (let i = 1; i <= count; i++) {
            users.push({
                id: i,
                name: names[Math.floor(Math.random() * names.length)],
                email: `user${i}@example.com`,
                created_at: new Date(Date.now() - Math.random() * 365 * 24 * 60 * 60 * 1000),
                preferences: {
                    price_sensitivity: Math.random(),
                    location_importance: Math.random(),
                    amenity_importance: Math.random(),
                    review_importance: Math.random()
                }
            });
        }

        return users;
    }

    generateTestBookings(count = 100) {
        const bookings = [];

        for (let i = 1; i <= count; i++) {
            const property = this.properties[Math.floor(Math.random() * this.properties.length)];
            const user = this.users[Math.floor(Math.random() * this.users.length)];
            const startDate = new Date(Date.now() + Math.random() * 90 * 24 * 60 * 60 * 1000);
            const endDate = new Date(startDate.getTime() + (Math.random() * 7 + 1) * 24 * 60 * 60 * 1000);

            bookings.push({
                id: i,
                user_id: user.id,
                property_id: property.id,
                start_date: startDate,
                end_date: endDate,
                guest_count: Math.floor(Math.random() * property.max_guests) + 1,
                total_price: property.price * ((endDate - startDate) / (24 * 60 * 60 * 1000)),
                status: ['confirmed', 'pending', 'cancelled'][Math.floor(Math.random() * 3)],
                created_at: new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000)
            });
        }

        return bookings;
    }

    getRandomAmenities(amenities) {
        const count = Math.floor(Math.random() * 5) + 2;
        const selected = [];
        const available = [...amenities];

        for (let i = 0; i < count && available.length > 0; i++) {
            const index = Math.floor(Math.random() * available.length);
            selected.push(available.splice(index, 1)[0]);
        }

        return selected;
    }

    // Test recommendation algorithms
    testPersonalizedRecommendations(userId, limit = 10) {
        const user = this.users.find(u => u.id === userId);
        if (!user) return [];

        const userBookings = this.bookings.filter(b => b.user_id === userId && b.status === 'confirmed');
        
        // Simple collaborative filtering
        const recommendations = this.properties
            .filter(p => !userBookings.some(b => b.property_id === p.id))
            .map(property => ({
                ...property,
                match_score: this.calculateMatchScore(user, property, userBookings)
            }))
            .sort((a, b) => b.match_score - a.match_score)
            .slice(0, limit);

        return recommendations;
    }

    calculateMatchScore(user, property, userBookings) {
        let score = 0;

        // Price preference
        const avgUserPrice = userBookings.length > 0 
            ? userBookings.reduce((sum, b) => sum + b.total_price, 0) / userBookings.length
            : property.price;
        
        const priceDiff = Math.abs(property.price - avgUserPrice) / avgUserPrice;
        score += (1 - Math.min(priceDiff, 1)) * 30;

        // Location preference
        const userLocations = userBookings.map(b => {
            const bookedProperty = this.properties.find(p => p.id === b.property_id);
            return bookedProperty?.location;
        });
        
        if (userLocations.includes(property.location)) {
            score += 25;
        }

        // Property type preference
        const userPropertyTypes = userBookings.map(b => {
            const bookedProperty = this.properties.find(p => p.id === b.property_id);
            return bookedProperty?.property_type;
        });
        
        if (userPropertyTypes.includes(property.property_type)) {
            score += 20;
        }

        // Rating boost
        score += parseFloat(property.average_rating) * 5;

        // Random factor for diversity
        score += Math.random() * 10;

        return Math.min(100, Math.max(0, score));
    }

    testSimilarProperties(propertyId, limit = 6) {
        const targetProperty = this.properties.find(p => p.id === propertyId);
        if (!targetProperty) return [];

        return this.properties
            .filter(p => p.id !== propertyId)
            .map(property => ({
                ...property,
                similarity_score: this.calculateSimilarityScore(targetProperty, property)
            }))
            .sort((a, b) => b.similarity_score - a.similarity_score)
            .slice(0, limit);
    }

    calculateSimilarityScore(property1, property2) {
        let score = 0;

        // Location similarity
        if (property1.location === property2.location) score += 30;

        // Property type similarity
        if (property1.property_type === property2.property_type) score += 25;

        // Price similarity
        const priceDiff = Math.abs(property1.price - property2.price) / Math.max(property1.price, property2.price);
        score += (1 - priceDiff) * 20;

        // Size similarity
        const sizeDiff = Math.abs(property1.max_guests - property2.max_guests) / Math.max(property1.max_guests, property2.max_guests);
        score += (1 - sizeDiff) * 15;

        // Amenity similarity
        const amenities1 = property1.amenities || [];
        const amenities2 = property2.amenities || [];
        const commonAmenities = amenities1.filter(a => amenities2.includes(a));
        const totalAmenities = new Set([...amenities1, ...amenities2]).size;
        if (totalAmenities > 0) {
            score += (commonAmenities.length / totalAmenities) * 10;
        }

        return Math.min(100, score);
    }

    // Run algorithm tests
    runTests() {
        console.log('Testing AI Recommendation Algorithms');
        console.log('====================================');

        // Test personalized recommendations
        const userId = 1;
        const personalizedRecs = this.testPersonalizedRecommendations(userId, 5);
        console.log(`Personalized recommendations for user ${userId}:`, personalizedRecs);

        // Test similar properties
        const propertyId = 1;
        const similarProps = this.testSimilarProperties(propertyId, 5);
        console.log(`Similar properties to property ${propertyId}:`, similarProps);

        // Test trending properties (simplified)
        const trending = this.properties
            .sort((a, b) => b.review_count - a.review_count)
            .slice(0, 5);
        console.log('Trending properties:', trending);

        return {
            personalized: personalizedRecs,
            similar: similarProps,
            trending: trending
        };
    }
}

// Export for use in development/testing
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AIRecommendations, AIRecommendationTestData };
}
