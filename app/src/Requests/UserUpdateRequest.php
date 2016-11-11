<?php
namespace App\Requests;

class UserUpdateRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'email',
            'role_id'  => 'integer',
            'password' => 'min:8',
        ];
    }
}