<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attribute1' => 'required|string|max:300',
            'attribute2' => 'required|string|max:300',
            'attribute3' => 'required|string|max:300',
            'attribute4' => 'required|string|max:300',
            'attribute5' => 'required|string|max:300',
            'attribute6' => 'required|string|max:300',
        ];
    }
}
