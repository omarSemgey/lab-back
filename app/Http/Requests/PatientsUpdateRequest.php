<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

class PatientsUpdateRequest extends FormRequest
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
        $id = $this->route('Patient');
        return [
            'name' => 'required|string|max:255|min:3',
            'email' => [
                'nullable',
                'email',
                Rule::unique('patients')->ignore($id),
            ],            
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|integer',
        ];
    }
}
