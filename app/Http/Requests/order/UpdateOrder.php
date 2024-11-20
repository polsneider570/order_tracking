<?php

namespace App\Http\Requests\order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrder extends FormRequest
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
            'product_name' => 'sometimes|string|max:255',
            'amount'       => 'sometimes|numeric|min:0',
            'status'       => 'sometimes|in:' . implode(',', config('order.statuses')),
        ];
    }
}
