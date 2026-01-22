<?php

namespace App\Http\Requests\MasterData\HariLibur;

use Illuminate\Foundation\Http\FormRequest;

class HariLiburUpdateFormRequest extends FormRequest
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
            'hari_libur' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_cuti_bersama' => 'required|boolean',
            'is_umum' => 'required|boolean',
            'is_repeat' => 'required|boolean',
        ];
    }
}
