<?php

namespace App\Http\Requests\Admin\Database;

use App\Http\Requests\Admin\BaseRequest;
use Illuminate\Validation\Rule;

class PostRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('modify') && $this->getMethod() === 'POST') {
            return [
                'label' => 'required|between:3,255|' . Rule::unique('dbs')->ignore($this->label, 'label'),
                'driver' => 'filled|string|in:firebird,mysql',
                'host' => 'filled',
                'port' => 'filled|numeric',
                'database' => 'filled|string|max:255',
                'username' => 'filled|string|max:255',
                'password' => 'filled|string',
                'charset' => 'filled|string',
            ];
        }

        if ($this->getMethod() === 'POST') {
            return [
                'label' => 'required|between:3,255|unique:dbs,label',
                'driver' => 'required|string|in:firebird,mysql',
                'host' => 'required',
                'port' => 'required|numeric',
                'database' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string',
                'charset' => 'required|string',
            ];
        }

        return [];
    }
}
