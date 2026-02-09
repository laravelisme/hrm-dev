<?php

namespace App\Http\Requests\Transaksi\LemburKaryawan;

use Illuminate\Foundation\Http\FormRequest;

class LemburHrApproveFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('hr');
    }

    public function rules(): array
    {
        return [
            'status_approval' => 'required|in:APPROVED,REJECTED',
            'durasi_verifikasi_menit' => 'required|numeric|min:1',
        ];
    }
}
