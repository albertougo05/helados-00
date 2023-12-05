<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'email',
            'password' => 'required|min:6|confirmed',
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
            //'proveedor_id.gt' => 'Debe ingresar proveedor',
            'name.required' => 'Debe ingresar nombre completo',
            'email.email' => 'Ingrese dirección de mail válida',
            'password.required' => 'Debe ingresar una contraseña',
            'password.min:6' => 'La contraseña debe tener al menos 6 caractares',
            'password.confirmed' => 'Las contraseñas deben coincidir',
        ];
    }
}
