<?php

namespace App\Http\Requests\Transaksi\SuratPeringatan;

use Illuminate\Foundation\Http\FormRequest;

class SuratPeringatanApproveFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('hr');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nomor' => 'nullable|string',
            'tanggal_surat' => 'nullable|date',
            'status_approval' => 'required|in:APPROVED,REJECTED',
            'hr_note' => 'nullable|string',
        ];
    }
}
