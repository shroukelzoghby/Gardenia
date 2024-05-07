<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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


            'email' => 'required|email|exists:users,email',
            'password'=>'required|string|min:6|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'password_confirm' => 'required|same:password'
        ];
    }
}
