<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_type_id' => 'required|exists:property_types,id',
            'barangay_id' => 'required|exists:barangays,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:5000',
            'address' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:1000|max:999999999',
            'deposit' => 'nullable|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:0|max:20',
            'bathrooms' => 'required|integer|min:0|max:20',
            'floor_area' => 'nullable|numeric|min:0',
            'lot_area' => 'nullable|numeric|min:0',
            'floor_level' => 'nullable|integer|min:0',
            'max_occupants' => 'nullable|integer|min:1|max:50',
            'is_furnished' => 'boolean',
            'pets_allowed' => 'boolean',
            'parking_available' => 'boolean',
            'parking_slots' => 'nullable|integer|min:0|max:10',
            'minimum_lease_months' => 'required|integer|min:1|max:60',
            'available_from' => 'nullable|date',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'property_type_id.required' => 'Please select a property type.',
            'barangay_id.required' => 'Please select a barangay location.',
            'title.required' => 'Please enter a property title.',
            'description.required' => 'Please provide a property description.',
            'description.min' => 'Description must be at least 50 characters.',
            'address.required' => 'Please enter the complete address.',
            'price.required' => 'Please enter the monthly rent price.',
            'price.min' => 'Minimum price is ₱1,000.',
            'bedrooms.required' => 'Please specify the number of bedrooms.',
            'bathrooms.required' => 'Please specify the number of bathrooms.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_furnished' => $this->boolean('is_furnished'),
            'pets_allowed' => $this->boolean('pets_allowed'),
            'parking_available' => $this->boolean('parking_available'),
        ]);
    }
}
