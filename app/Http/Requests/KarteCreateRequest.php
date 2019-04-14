<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KarteCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => 'required|integer|unique:karte,number',
            'meal' => 'required',
            'category' => [
              'required',
              Rule::in([
                'Super-Spar-Menüs',
                'Croque',
                'Klassiker',
                'Grillgerichte',
                'Snacks',
                'Beilagen',
                'Salate',
                'Fisch'
              ])
            ],
            'price' => 'required|numeric',
            'photo' => 'nullable|image'
        ];
    }
}
