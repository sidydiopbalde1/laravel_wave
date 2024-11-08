<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;

class TransferRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id|different:sender_id',
            'montant' => 'required|numeric|min:1',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'sender_id.required' => 'Le champ ID de l\'expéditeur est requis.',
            'sender_id.exists' => 'L\'ID de l\'expéditeur n\'existe pas.',
            'receiver_id.required' => 'Le champ ID du destinataire est requis.',
            'receiver_id.exists' => 'L\'ID du destinataire n\'existe pas.',
            'receiver_id.different' => 'L\'expéditeur et le destinataire doivent être différents.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à zéro.',
        ];
    }

    /**
     * Gère l'échec de la validation.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'La validation a échoué.',
            'errors' => $validator->errors()
        ], 422));
    }
    
    /**
     * Configure le validateur après les règles de validation de base.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sender = User::find($this->sender_id);
            $montant = $this->montant;
            $frais = $montant * 0.01;

            // Vérifier si le solde de l'envoyeur couvre le montant + frais
            if (!$sender || $sender->solde < ($montant + $frais)) {
                $validator->errors()->add('montant', 'Le solde est insuffisant pour cette transaction.');
            }
        });
    }
}
