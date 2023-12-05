<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductoRequest extends FormRequest
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
            //'proveedor_id' => 'gt:0',
            'tipo_producto_id' => 'gt:0',
            'descripcion' => 'required',
            'imagen' => 'mimes:jpg,png,jpeg|max:5048',
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
            'tipo_producto_id.gt' => 'Debe ingresar tipo producto',
            'descripcion.required' => 'Debe ingresar descripción',
            'imagen.mimes' => 'Error en el tipo de imagen',
            'imagen.max' => 'Error en el tamaño de la imagen',
        ];
    }


}
