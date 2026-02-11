<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTransactionMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $item = $this->route('item'); // ルートモデルバインディングの Item
        if (!$item) {
            return false;
        }

        $userId = Auth::id();

        return $item->user_id === $userId || $item->buyer_id === $userId;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:400'],
            'image' => ['nullable', 'file', 'mimes:jpeg,jpg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max' => '本文は400文字以内で入力してください',
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }
}
