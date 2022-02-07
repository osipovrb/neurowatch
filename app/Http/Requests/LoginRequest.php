<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'samaccountname' => 'required|string|in:luzgin,go_tumakov,go_osipov',
            'password' => 'required|string',
        ];
    }
}
