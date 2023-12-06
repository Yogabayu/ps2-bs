<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "user_uuid"         => "required",
            "transc_id"         => "required",
            "place_transc_id"   => "required",
            "date"              => "required",
            "start"             => "required",
            "end"               => "required",
            "no_rek"           => "required",
            "evidence_file"     => "required",
        ];
    }
}
