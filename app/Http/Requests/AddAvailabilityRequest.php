<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddAvailabilityRequest extends FormRequest
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
            'student_id' => 'required|exists:students,slug',
            'days' => 'required|array|min:1',
            'days.*' => 'integer|between:0,6',
        ];
    }
}
