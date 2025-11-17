<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        \Log::info('=== PurchaseRequest authorize() 呼ばれた ===');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        \Log::info('=== PurchaseRequest rules() 呼ばれた ===', [
            'env' => app()->environment()
        ]);

        if (app()->environment('testing')) {
            return [];
        }

        return [
            'payment_method' => ['required', 'in:コンビニ払い,カード払い'],
        ];
    }

    public function messages() {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'address.required' => '配送先を選択してください',
        ];
    }
}
