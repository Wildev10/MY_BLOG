<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validation pour la mise à jour du profil utilisateur
 *
 * Règles:
 * - name: obligatoire, 2-100 caractères
 * - username: unique (sauf pour l'utilisateur actuel), alphanumérique
 * - email: unique (sauf pour l'utilisateur actuel), format email valide
 * - bio: optionnel, max 500 caractères
 * - location: optionnel, max 100 caractères
 * - avatar: optionnel, image (jpeg, png, gif, webp), max 2MB
 */
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // L'autorisation est gérée par le middleware auth
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_]+$/', // Alphanumérique + underscore
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'bio' => [
                'nullable',
                'string',
                'max:500',
            ],
            'location' => [
                'nullable',
                'string',
                'max:100',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB max
            ],
            'remove_avatar' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'username.required' => 'Le pseudo est obligatoire.',
            'username.min' => 'Le pseudo doit contenir au moins 3 caractères.',
            'username.max' => 'Le pseudo ne peut pas dépasser 30 caractères.',
            'username.regex' => 'Le pseudo ne peut contenir que des lettres, chiffres et underscores.',
            'username.unique' => 'Ce pseudo est déjà pris.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'bio.max' => 'La bio ne peut pas dépasser 500 caractères.',
            'location.max' => 'La localisation ne peut pas dépasser 100 caractères.',
            'avatar.image' => 'Le fichier doit être une image.',
            'avatar.mimes' => 'L\'image doit être au format JPEG, PNG, GIF ou WebP.',
            'avatar.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }
}
