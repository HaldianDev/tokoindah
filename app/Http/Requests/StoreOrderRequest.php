<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,credit',
            'cart' => 'required|json',
            'credit_tenor_months' => 'required_if:payment_method,credit|integer|in:3,6,12',
            'ktp_file' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'payment_proof_file' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new \Illuminate\Validation\ValidationException($validator))->errors();
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['success' => false, 'message' => 'Data tidak valid.', 'errors' => $errors], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
