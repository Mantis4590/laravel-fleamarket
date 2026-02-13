<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 本人チェックはController側
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:400'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max' => '本文は400文字以内で入力してください',
        ];
    }
}
