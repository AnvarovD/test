<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class JobApplicationCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "F_I_O" => ['required', 'string'],
            "contact" => ['required', 'string'],
            "file" => [
                'required',
                File::types(['doc','pdf','jpg','png'])->max(3 * 1024)
            ],
        ];
    }
}
