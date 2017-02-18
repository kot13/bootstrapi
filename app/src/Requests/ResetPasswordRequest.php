<?php
namespace App\Requests;

class ResetPasswordRequest implements IRequest
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
