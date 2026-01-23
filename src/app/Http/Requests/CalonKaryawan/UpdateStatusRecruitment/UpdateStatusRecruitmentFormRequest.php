<?php

namespace App\Http\Requests\CalonKaryawan\UpdateStatusRecruitment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRecruitmentFormRequest extends FormRequest
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
            'status' => 'required|in:SHORTLIST_ADMIN,TES_TULIS,INTERVIEW,TALENT_POOL,OFFERING,REJECTED',
        ];
    }
}
