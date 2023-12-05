<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSucursalRequest extends FormRequest
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
            'empresa_id' => 'gt:0',
            'nombre' => 'required',
            'direccion' => 'required',
            'localidad' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'empresa_id.gt' => 'Seleccione una empresa !',
            'nombre.required' => 'Debe ingresar un nombre !',
            'direccion.required' => 'Debe ingresar una dirección !',
            'localidad.required' => 'Debe ingresar una localidad !',
        ];
    }
}
