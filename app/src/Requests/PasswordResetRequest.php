<?php
namespace App\Requests;

class PasswordResetRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return [
            'token'    => 'required',
            'password' => 'required',
        ];
    }
}
