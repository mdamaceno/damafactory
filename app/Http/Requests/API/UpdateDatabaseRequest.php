<?php

namespace App\Http\Requests\API;

class UpdateDatabaseRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => 'filled|string|between:3,255|unique:dbs',
            'driver' => 'filled|string|in:firebird,mysql',
            'host' => 'filled|ipv4',
            'port' => 'filled|numeric',
            'database' => 'filled|string|max:255',
            'username' => 'filled|string|max:255',
            'password' => 'filled|string',
            'charset' => 'filled|string',
        ];
    }
}
