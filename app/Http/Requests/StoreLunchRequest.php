<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLunchRequest extends FormRequest
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
        // 'exists:users,id'
          return [
            'receivers' => ['array'],
            'receivers.*' => ['sometimes', 'distinct', 'exists:users,id'],
            'quantity' => ['numeric', 'required'],
            'note' => ['string']
        ];
    }
}
