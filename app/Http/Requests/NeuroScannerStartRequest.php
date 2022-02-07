<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NeuroScannerStartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hostname' => 'required|string',
            'threshold' => 'required|numeric|between:50,100',
        ];
    }
}
