<?php

namespace App\Http\Requests\Tenancy\Domain;

use Illuminate\Foundation\Http\FormRequest;

class DomainStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('super-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => 'required|string|unique:domains,domain|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'nama_company' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:tenants,email',
        ];
    }
}
