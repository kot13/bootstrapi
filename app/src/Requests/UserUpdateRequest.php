<?php
namespace App\Requests;

class UserUpdateRequest implements IRequest
{
    /**
     * @return array<string,string>
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
