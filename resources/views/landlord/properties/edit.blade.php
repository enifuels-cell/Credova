<x-app-layout>
    @section('title', 'Edit Property')

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('landlord.properties.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Properties
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Property</h1>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('landlord.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Basic Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Property Title *</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="property_type_id" class="block text-sm font-medium text-gray-700 mb-1">Property Type *</label>
                                <select name="property_type_id" id="property_type_id" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select Type</option>
                                    @foreach($propertyTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('property_type_id', $property->property_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-1">Barangay *</label>
                                <select name="barangay_id" id="barangay_id" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select Barangay</option>
                                    @foreach($barangays as $barangay)
                                        <option value="{{ $barangay->id }}" {{ old('barangay_id', $property->barangay_id) == $barangay->id ? 'selected' : '' }}>
                                            {{ $barangay->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Complete Address *</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                <textarea name="description" id="description" rows="4" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Pricing</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₱) *</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" required min="0" step="0.01"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="price_period" class="block text-sm font-medium text-gray-700 mb-1">Price Period *</label>
                                <select name="price_period" id="price_period" required
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="month" {{ old('price_period', $property->price_period) == 'month' ? 'selected' : '' }}>Per Month</option>
                                    <option value="day" {{ old('price_period', $property->price_period) == 'day' ? 'selected' : '' }}>Per Day</option>
                                    <option value="week" {{ old('price_period', $property->price_period) == 'week' ? 'selected' : '' }}>Per Week</option>
                                    <option value="year" {{ old('price_period', $property->price_period) == 'year' ? 'selected' : '' }}>Per Year</option>
                                </select>
                            </div>

                            <div>
                                <label for="deposit" class="block text-sm font-medium text-gray-700 mb-1">Security Deposit (₱)</label>
                                <input type="number" name="deposit" id="deposit" value="{{ old('deposit', $property->deposit) }}" min="0" step="0.01"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Property Details</h2>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">Bedrooms</label>
                                <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">Bathrooms</label>
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="floor_area" class="block text-sm font-medium text-gray-700 mb-1">Floor Area (sqm)</label>
                                <input type="number" name="floor_area" id="floor_area" value="{{ old('floor_area', $property->floor_area) }}" min="0" step="0.01"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="max_occupants" class="block text-sm font-medium text-gray-700 mb-1">Max Occupants</label>
                                <input type="number" name="max_occupants" id="max_occupants" value="{{ old('max_occupants', $property->max_occupants) }}" min="1"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="furnishing" class="block text-sm font-medium text-gray-700 mb-1">Furnishing</label>
                                <select name="furnishing" id="furnishing"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="unfurnished" {{ old('furnishing', $property->furnishing) == 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                    <option value="semi-furnished" {{ old('furnishing', $property->furnishing) == 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                                    <option value="fully-furnished" {{ old('furnishing', $property->furnishing) == 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                                </select>
                            </div>

                            <div>
                                <label for="minimum_stay" class="block text-sm font-medium text-gray-700 mb-1">Minimum Stay</label>
                                <select name="minimum_stay" id="minimum_stay"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="1 month" {{ old('minimum_stay', $property->minimum_stay) == '1 month' ? 'selected' : '' }}>1 Month</option>
                                    <option value="3 months" {{ old('minimum_stay', $property->minimum_stay) == '3 months' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6 months" {{ old('minimum_stay', $property->minimum_stay) == '6 months' ? 'selected' : '' }}>6 Months</option>
                                    <option value="1 year" {{ old('minimum_stay', $property->minimum_stay) == '1 year' ? 'selected' : '' }}>1 Year</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label for="available_from" class="block text-sm font-medium text-gray-700 mb-1">Available From</label>
                                <input type="date" name="available_from" id="available_from"
                                    value="{{ old('available_from', $property->available_from ? $property->available_from->format('Y-m-d') : '') }}"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="pets_allowed" value="1" {{ old('pets_allowed', $property->pets_allowed) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">Pets Allowed</span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="smoking_allowed" value="1" {{ old('smoking_allowed', $property->smoking_allowed) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">Smoking Allowed</span>
                            </label>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Amenities</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($amenities as $amenity)
                                <label class="flex items-center">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                        {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Location (Optional)</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Existing Images -->
                    @if($property->images->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Current Images</h2>

                            <div class="grid grid-cols-4 gap-4">
                                @foreach($property->images as $image)
                                    <div class="relative group">
                                        <img src="{{ $image->image_url }}" class="w-full h-24 object-cover rounded-lg">
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                                            <label class="text-white text-sm cursor-pointer">
                                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="mr-1">
                                                Delete
                                            </label>
                                        </div>
                                        @if($image->is_primary)
                                            <span class="absolute top-1 left-1 bg-emerald-600 text-white text-xs px-2 py-1 rounded">Primary</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- New Images -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Add New Images</h2>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <input type="file" name="images[]" id="images" multiple accept="image/*"
                                class="hidden" onchange="previewImages(this)">
                            <label for="images" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">Click to upload more images</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                            </label>
                        </div>
                        <div id="imagePreview" class="grid grid-cols-4 gap-4 mt-4"></div>
                    </div>

                    <!-- Status -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 pb-2 border-b">Status</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Property Status</label>
                                <select name="status" id="status"
                                    class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="available" {{ old('status', $property->status) == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="maintenance" {{ old('status', $property->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <label class="flex items-center mt-6">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-700">Feature this property on homepage</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('landlord.properties.index') }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-6 rounded-lg transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg transition">
                            Update Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        `;
                        preview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
</x-app-layout>
