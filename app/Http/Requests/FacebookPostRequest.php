<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FacebookPostRequest extends FormRequest
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
            'link' => ['nullable', 'active_url'],
            'message' => ['required'],
            // 'upload' => ['nullable', 'file', 'mimes:mp4,mov'],
            'upload' => ['required', Rule::in(['none','image','video', 'link'])],
        ];
    }
}
