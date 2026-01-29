<?php

namespace App\Http\Requests\Recruitment\TestTulis;

use Illuminate\Foundation\Http\FormRequest;

class GenerateDeadlineFormRequest extends FormRequest
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
            'deadline_psikologi' => 'required|date',
            'deadline_teknikal' => 'required|date',
        ];
    }
}
