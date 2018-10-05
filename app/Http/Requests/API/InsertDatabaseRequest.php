<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class InsertDatabaseRequest extends FormRequest
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
            'label' => 'required|string|between:3,255|unique:dbs',
            'driver' => 'required|string|in:firebird,mysql',
            'host' => 'required|ipv4',
            'port' => 'required|numeric',
            'database' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'charset' => 'required|string',
        ];
    }
}
