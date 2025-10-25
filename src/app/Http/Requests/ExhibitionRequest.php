<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ログインユーザーのみ出品可能
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'img_url' => ['required', 'image', 'mimes:jpeg,png', 'max:51200'],
            'category_ids' => ['required', 'array', 'min:1'],
            'condition' => ['required', 'string'],
            'price' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages() {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'img_url.required' => '商品画像を選択してください',
            'img_url.image' => '画像ファイルを選択してください',
            'img_url.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'category_ids.required' => 'カテゴリを選択してください',
            'category_ids.min' => '少なくとも一つのカテゴリを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上に設定してください',
        ];
    }
}
