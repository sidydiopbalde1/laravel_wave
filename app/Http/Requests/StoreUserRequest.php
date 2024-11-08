<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CustumPassword;
use App\Rules\TelephoneRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette demande.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la demande.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Changement de 'mail' à 'email'
            'password' => ['required', 'string', new CustumPassword],
            'code_secret' => 'nullable|string|min:6',
            'telephone' => ['required', 'string', 'max:15', 'unique:users,telephone', new TelephoneRules()],
            'solde' => 'nullable|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'plafond' => 'nullable|numeric',
            'role_id' => 'nullable|exists:roles,id', // Changement pour vérifier l'existence dans la table roles
            'type_societe' => 'nullable|string|max:255',
        ];
    }

    /**
     * Obtenez les messages de validation personnalisés.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 255 caractères.',

            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',

            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.max' => 'L\'adresse e-mail ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',

            'password.required' => 'Le mot de passe est obligatoire.',

            // 'code_secret.required' => 'Le code secret est obligatoire.',
            // 'code_secret.string' => 'Le code secret doit être une chaîne de caractères.',
            // 'code_secret.min' => 'Le code secret doit contenir au moins 6 caractères.',

            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 15 caractères.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',

            'solde.numeric' => 'Le solde doit être un nombre.',

            'photo.image' => 'Le fichier téléchargé doit être une image.',
            'photo.mimes' => 'La photo doit être au format jpg, jpeg ou png.',
            'photo.max' => 'La photo ne peut pas dépasser 2 Mo.',


            'plafond.numeric' => 'Le plafond doit être un nombre.',

            'role_id.exists' => 'Le rôle sélectionné doit exister.', // Message pour le rôle
        ];
    }

    /**
     * Gère l'échec de la validation.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'La validation a échoué.',
            'errors' => $validator->errors()
        ], 422));
    }
}
