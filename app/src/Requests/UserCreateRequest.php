<?php
namespace App\Requests;

class UserCreateRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|unique:users,email',
            'role_id'  => 'required|integer',
            'password' => 'required|min:8',
        ];
    }
}
