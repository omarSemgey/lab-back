<?php

namespace App\Http\Requests;

use App\Models\Employees;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeesUpdateRequest extends FormRequest
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
        $id = $this->route('Employee');
        return [
            'name' => 'nullable|string|max:25|min:3',
            'password' => 'nullable|string|min:6',
            'email' => [
                'nullable',
                'email',
                Rule::unique('employees')->ignore($id),
            ],
            'role' => 'nullable|integer',
            'branches_id' => 'nullable|integer'
        ];
    }
}