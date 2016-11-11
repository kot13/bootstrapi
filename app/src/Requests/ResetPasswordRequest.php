<?php
namespace App\Requests;

class ResetPasswordRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'token'    => 'required',
            'password' => 'required',
        ];
    }
}