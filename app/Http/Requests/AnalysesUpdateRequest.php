<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalysesUpdateRequest extends FormRequest
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
            'title' => 'nullable|string|max:255|min:3',
            'content' => 'nullable|file|image|mimes:png,jpg,jpeg|max:1000|mimetypes:image/png,/image/jpg,image/jpeg',
            'employees_id' => 'nullable|integer',
            'patients_id' => 'nullable|integer',
            'branches_id' => 'nullable|integer'
        ];
    }
}
