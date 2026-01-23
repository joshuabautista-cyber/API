<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorized
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Foreign Keys
            'semester_id' => 'required|integer|exists:tbl_semester,semester_id',
            // Grade Data
            'units'       => 'required|string|max:10', // Assuming units is small string
            'grades'      => 'nullable|string|max:5',  // e.g. "1.0", "Inc"
            'remarks'     => 'nullable|string|max:255',
            'pending_status' => 'nullable|string|max:50',
        ];
    }
}