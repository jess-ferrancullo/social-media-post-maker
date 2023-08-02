<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

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
            'message' => ['required'],
            'upload' => ['required', Rule::in(['none','image','video', 'link'])],
            'link' => ['required_if:upload,link', 'nullable', 'active_url'],
            'media_images' => ['required_if:upload,image', 'nullable', 'max:10'],
            'media_images.*' => ['required_if:upload,image', 'nullable', 'image'],
            'media_video' => ['required_if:upload,video', 'nullable', File::types(['mp4', 'mov', 'gif'])],
        ];
    }
    
    public function messages(): array
    {
        return [
            'media_images.max' => 'Only maximum of 10 images can be uploaded',
        ];
    }
}