<?php

namespace App\Http\Requests\Admin\DBRole;

use App\Http\Requests\Admin\BaseRequest;

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
                'name' => 'filled|max:50',
                'http_permission' => 'nullable',
                'active' => 'filled|boolean',
            ];
        }

        if ($this->getMethod() === 'POST') {
            return [
                'name' => 'required|max:50',
                'http_permission' => 'nullable',
                'active' => 'required|boolean',
            ];
        }

        return [];
    }
}
