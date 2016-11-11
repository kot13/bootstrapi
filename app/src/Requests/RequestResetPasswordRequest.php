<?php
namespace App\Requests;

class RequestResetPasswordRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return ['email' => 'required|email'];
    }
}