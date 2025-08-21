<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        return $auth->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category' => ['required', Rule::in([
                'lavage-simple',
                'lavage-repassage',
                'repassage-seul',
                'nettoyage-sec',
                'costume',
                'robe-ceremonie'
            ])],
            'payment_method' => ['required', Rule::in([
                'cash',
                'wave',
                'orange-money',
                'free-money',
                'bank',
                'check'
            ])],
            'inventory' => 'nullable|array',
            'inventory.*.id' => 'required_with:inventory|integer',
            'inventory.*.quantity' => 'required_with:inventory|integer|min:1',
            'inventory.*.clothingType' => ['required_with:inventory', 'string', Rule::in([
                'pantalon',
                'sous-vetement',
                'chemise',
                't-shirt',
                'veste',
                'chaussure',
                'serviette',
                'drap',
                'couette',
                'autre'
            ])],
            'inventory.*.color' => ['required_with:inventory', 'string', Rule::in([
                'noir',
                'blanc',
                'jaune',
                'rouge',
                'bleu',
                'vert',
                'orange'
            ])],
            'notes' => 'nullable|string|max:1000',
            'status' => ['nullable', Rule::in(['en-attente', 'en-cours', 'termine', 'recupere'])],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Le nom du client est obligatoire.',
            'customer_name.max' => 'Le nom du client ne peut pas dépasser 255 caractères.',

            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être positif.',

            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité doit être au moins 1.',

            'category.required' => 'Le service est obligatoire.',
            'category.in' => 'Le service sélectionné n\'est pas valide.',

            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_method.in' => 'Le mode de paiement sélectionné n\'est pas valide.',

            'inventory.array' => 'L\'inventaire doit être un tableau.',
            'inventory.*.quantity.min' => 'Chaque article doit avoir une quantité d\'au moins 1.',
            'inventory.*.clothingType.in' => 'Type de vêtement non valide.',
            'inventory.*.color.in' => 'Couleur non valide.',

            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
