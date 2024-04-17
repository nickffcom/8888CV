<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CongTruTienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'username'=>'required|string|exists:user,username',
            'money'=>'required|numeric|max:500000',
            'action'=>'required|in:'.CONG_TIEN.','.TRU_TIEN,
        ];
    }
}
