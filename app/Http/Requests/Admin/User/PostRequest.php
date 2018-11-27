<?php

namespace App\Http\Requests\Admin\User;

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
                'email' => 'filled|email|' . Rule::unique('users')->ignore($this->email, 'email'),
                'role' => 'filled|in:db,master',
                'name' => 'filled|max:255',
                'password' => 'nullable|between:5,255',
            ];
        }

        if ($this->getMethod() === 'POST') {
            return [
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:db,master',
                'name' => 'required|max:255',
                'password' => 'required|between:5,255',
            ];
        }

        return [];
    }
}
