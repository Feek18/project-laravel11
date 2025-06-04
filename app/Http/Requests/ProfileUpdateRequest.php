<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            // 'name' => ['required', 'string', 'max:255'],
            // 'email' => [
            //     'required',
            //     'string',
            //     'lowercase',
            //     'email',
            //     'max:255',
            //     Rule::unique(User::class)->ignore($this->user()->id),
            // ],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'gender' => ['required', 'in:pria,wanita'],
        ];
    }
}
