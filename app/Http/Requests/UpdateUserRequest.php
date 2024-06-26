<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'password' => 'nullable|string|',
            'new_password' => 'nullable|confirmed|string|max:50|min:6',
            'username' => 'required|string|exists:user,username',
            'money' => 'required|numeric|max:500000',
            'action' => 'required|in:' . CONG_TIEN . ',' . TRU_TIEN,
        ];
    }
}
