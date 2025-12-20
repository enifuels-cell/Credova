@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Add New Property</h1>
                    <p class="text-gray-600 mt-2">Fill in the details below to list your property</p>
                </div>
                <a href="{{ route('properties.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Properties
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                
                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }}"
                               placeholder="e.g., Cozy Downtown Apartment">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }}"
                                  placeholder="Describe your property, its features, and what makes it special...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }}"
                               placeholder="e.g., Downtown Seattle, WA">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Property Type -->
                    <div>
                        <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                        <select id="property_type" 
                                name="property_type"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('property_type') ? 'border-red-500' : 'border-gray-300' }}">
                            <option value="">Select property type</option>
                            @foreach($propertyTypes as $key => $value)
                                <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('property_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Property Details</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Bedrooms -->
                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                        <input type="number" 
                               id="bedrooms" 
                               name="bedrooms" 
                               min="1" 
                               max="20"
                               value="{{ old('bedrooms', 1) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('bedrooms') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('bedrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bathrooms -->
                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                        <input type="number" 
                               id="bathrooms" 
                               name="bathrooms" 
                               min="1" 
                               max="20"
                               value="{{ old('bathrooms', 1) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('bathrooms') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('bathrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Guests -->
                    <div>
                        <label for="max_guests" class="block text-sm font-medium text-gray-700 mb-2">Max Guests</label>
                        <input type="number" 
                               id="max_guests" 
                               name="max_guests" 
                               min="1" 
                               max="50"
                               value="{{ old('max_guests', 2) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('max_guests') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('max_guests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price per Night -->
                    <div>
                        <label for="price_per_night" class="block text-sm font-medium text-gray-700 mb-2">Price per Night ($)</label>
                        <input type="number" 
                               id="price_per_night" 
                               name="price_per_night" 
                               min="0" 
                               step="0.01"
                               value="{{ old('price_per_night') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('price_per_night') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('price_per_night')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Amenities</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($amenities as $key => $value)
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" 
                                   name="amenities[]" 
                                   value="{{ $key }}"
                                   {{ in_array($key, old('amenities', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">{{ $value }}</span>
                        </label>
                    @endforeach
                </div>
                @error('amenities')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Check-in/Check-out Times -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Check-in & Check-out</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Check-in Time -->
                    <div>
                        <label for="check_in_time" class="block text-sm font-medium text-gray-700 mb-2">Check-in Time</label>
                        <input type="time" 
                               id="check_in_time" 
                               name="check_in_time" 
                               value="{{ old('check_in_time', '15:00') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('check_in_time') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('check_in_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check-out Time -->
                    <div>
                        <label for="check_out_time" class="block text-sm font-medium text-gray-700 mb-2">Check-out Time</label>
                        <input type="time" 
                               id="check_out_time" 
                               name="check_out_time" 
                               value="{{ old('check_out_time', '11:00') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('check_out_time') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('check_out_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Policies -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Policies</h2>
                
                <div class="space-y-4">
                    <!-- Cancellation Policy -->
                    <div>
                        <label for="cancellation_policy" class="block text-sm font-medium text-gray-700 mb-2">Cancellation Policy</label>
                        <select id="cancellation_policy" 
                                name="cancellation_policy"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('cancellation_policy') ? 'border-red-500' : 'border-gray-300' }}">
                            <option value="">Select cancellation policy</option>
                            @foreach($cancellationPolicies as $key => $value)
                                <option value="{{ $key }}" {{ old('cancellation_policy') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('cancellation_policy')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- House Rules -->
                    <div>
                        <label for="house_rules" class="block text-sm font-medium text-gray-700 mb-2">House Rules (Optional)</label>
                        <textarea id="house_rules" 
                                  name="house_rules" 
                                  rows="3"
                                  class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('house_rules') ? 'border-red-500' : 'border-gray-300' }}"
                                  placeholder="e.g., No smoking, No pets, Quiet hours after 10 PM...">{{ old('house_rules') }}</textarea>
                        @error('house_rules')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instant Book -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="instant_book" 
                               name="instant_book" 
                               value="1"
                               {{ old('instant_book') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="instant_book" class="ml-2 text-sm text-gray-700">
                            Enable instant booking (guests can book without approval)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Images Upload -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Property Images</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Images (Required - Max 10 images, 5MB each)
                        </label>
                        <input type="file" 
                               id="images" 
                               name="images[]" 
                               multiple 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ ($errors->has('images') || $errors->has('images.*')) ? 'border-red-500' : 'border-gray-300' }}">
                        <p class="mt-1 text-xs text-gray-500">
                            Supported formats: JPEG, PNG, JPG, WEBP. First image will be the main image.
                        </p>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview area -->
                    <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" style="display:none;">
                        <!-- Images will be previewed here -->
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Property
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        previewContainer.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                ${index + 1}${index === 0 ? ' (Main)' : ''}
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    });
});
</script>
@endsection
