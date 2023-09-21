<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationInviteRequest extends FormRequest
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
        $authUser = auth()->user();
        return [
            'email' => [
                'email',
                'required',
                Rule::unique('organization_invites', 'email')->where(function($query) use ($authUser) {
                    return $query->where('org_id', $authUser->org_id);
                })
            ]

            // unique:organization_invites,email,org_id
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email has already been invited.',
        ];
    }
}
