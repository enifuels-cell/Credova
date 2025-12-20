<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $savedSearch->name }} - Property Alert</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .property-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            background: #fff;
        }
        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .property-content {
            padding: 20px;
        }
        .property-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1f2937;
        }
        .property-location {
            color: #6b7280;
            margin-bottom: 12px;
        }
        .property-price {
            font-size: 20px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 12px;
        }
        .property-details {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .detail-item {
            background: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
        }
        .cta-button {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 10px;
        }
        .search-summary {
            background: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .unsubscribe {
            margin-top: 20px;
            font-size: 12px;
        }
        .alert-badge {
            background: #dc2626;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏠 Homygo</div>
        <h1>
            @if($newCount > 0)
                <span class="alert-badge">{{ $newCount }} NEW</span> 
                Properties Found!
            @else
                Your Saved Search Results
            @endif
        </h1>
        <p>Results for: <strong>{{ $savedSearch->name }}</strong></p>
    </div>

    <div class="search-summary">
        <h3>Search Criteria:</h3>
        <p>{{ $savedSearch->search_summary }}</p>
        @if($savedSearch->alert_frequency)
            <p><small>You're receiving {{ $savedSearch->alert_frequency }} alerts for this search.</small></p>
        @endif
    </div>

    <h2>
        @if($properties->count() > 0)
            {{ $properties->count() }} {{ Str::plural('Property', $properties->count()) }} Found
        @else
            No Properties Found
        @endif
    </h2>

    @if($properties->count() > 0)
        @foreach($properties->take(5) as $property)
            <div class="property-card">
                @if($property->primaryImage && $property->primaryImage->image_path)
                    <img src="{{ asset('storage/' . $property->primaryImage->image_path) }}" 
                         alt="{{ $property->title }}" 
                         class="property-image">
                @endif
                
                <div class="property-content">
                    <h3 class="property-title">{{ $property->title }}</h3>
                    <p class="property-location">📍 {{ $property->location }}</p>
                    <p class="property-price">${{ number_format($property->price_per_night, 2) }}/night</p>
                    
                    <div class="property-details">
                        @if($property->bedrooms)
                            <span class="detail-item">🛏️ {{ $property->bedrooms }} bed{{ $property->bedrooms > 1 ? 's' : '' }}</span>
                        @endif
                        @if($property->bathrooms)
                            <span class="detail-item">🚿 {{ $property->bathrooms }} bath{{ $property->bathrooms > 1 ? 's' : '' }}</span>
                        @endif
                        @if($property->max_guests)
                            <span class="detail-item">👥 {{ $property->max_guests }} guest{{ $property->max_guests > 1 ? 's' : '' }}</span>
                        @endif
                        @if($property->property_type)
                            <span class="detail-item">🏠 {{ ucfirst($property->property_type) }}</span>
                        @endif
                    </div>
                    
                    @if($property->description)
                        <p>{{ Str::limit($property->description, 120) }}</p>
                    @endif
                    
                    <a href="{{ route('properties.show', $property) }}" class="cta-button">
                        View Property Details
                    </a>
                </div>
            </div>
        @endforeach

        @if($properties->count() > 5)
            <div style="text-align: center; margin: 30px 0;">
                <p>And {{ $properties->count() - 5 }} more properties...</p>
                <a href="{{ route('saved-searches.show', $savedSearch) }}" class="cta-button">
                    View All {{ $properties->count() }} Results
                </a>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 40px 20px; background: #f9fafb; border-radius: 8px;">
            <h3>No properties match your search criteria yet</h3>
            <p>Don't worry! We'll keep looking and notify you when new properties become available.</p>
            <a href="{{ route('saved-searches.edit', $savedSearch) }}" class="cta-button">
                Modify Search Criteria
            </a>
        </div>
    @endif

    <div class="footer">
        <p>
            <strong>Manage Your Saved Searches:</strong><br>
            <a href="{{ route('saved-searches.index') }}">View All Saved Searches</a> | 
            <a href="{{ route('saved-searches.edit', $savedSearch) }}">Edit This Search</a>
        </p>
        
        <div class="unsubscribe">
            <p>
                You're receiving this email because you have alerts enabled for the saved search "{{ $savedSearch->name }}".
                <br>
                <a href="{{ route('saved-searches.edit', $savedSearch) }}">Update your alert preferences</a> or 
                <a href="{{ route('saved-searches.index') }}">manage all your saved searches</a>.
            </p>
        </div>
    </div>
</body>
</html>
