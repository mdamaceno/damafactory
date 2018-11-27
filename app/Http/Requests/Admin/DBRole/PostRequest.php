<?php

namespace App\Http\Requests\Admin\DBRole;

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
                'name' => 'filled|max:50|' . Rule::unique('db_roles')->ignore($this->name, 'name'),
                'http_permission' => 'nullable',
                'active' => 'filled|boolean',
            ];
        }

        if ($this->getMethod() === 'POST') {
            return [
                'name' => 'required|max:50|unique:db_roles,name',
                'http_permission' => 'nullable',
                'active' => 'boolean',
            ];
        }

        return [];
    }
}
