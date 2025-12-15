<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'lease_duration_months' => 'required|integer|min:1|max:60',
            'notes' => 'nullable|string|max:1000',
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
            'start_date.required' => 'Please select a start date.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'lease_duration_months.required' => 'Please specify lease duration.',
            'lease_duration_months.min' => 'Lease duration must be at least 1 month.',
            'lease_duration_months.max' => 'Lease duration cannot exceed 60 months.',
        ];
    }
}
