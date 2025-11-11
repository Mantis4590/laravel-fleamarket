<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            // プロフィール画像:jpeg or pngのみ
            'image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:10240'],

            // ユーザー名: 必須・20文字以内
            'name' => ['required', 'string', 'max:20'],

            // 郵便番号:必須・ハイフンありの8文字(例:123-4567)
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],

            // 住所:必須
            'address' => ['required', 'string', 'max:255'],

            // 建物名:任意
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }
    public function messages() {
        return [
            // 画像
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',

            // ユーザー名
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',

            // 郵便番号
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンありの8文字で入力してください',

            // 住所
            'address.required' => '住所を入力してください',
        ];
    }
}
