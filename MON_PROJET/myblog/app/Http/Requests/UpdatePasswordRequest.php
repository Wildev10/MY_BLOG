<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Validation pour le changement de mot de passe
 *
 * Règles:
 * - current_password: obligatoire, doit correspondre au mot de passe actuel
 * - password: obligatoire, min 8 caractères, confirmé
 */
class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        $fail('Le mot de passe actuel est incorrect.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
