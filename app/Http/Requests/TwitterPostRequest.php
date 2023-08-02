<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class TwitterPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard()->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['required_if:media,null'],
            'media' => ['nullable', 'max:4'],
            'media.*' => [
                File::types(['mp4', 'jpg', 'jpeg', 'gif', 'mov', 'png', 'ogg', 'webp'])
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'media.max' => 'Only maximum of 4 files can be uploaded'
        ];
    }
}
