<?php
namespace App\Requests;

class RequestResetPasswordRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return ['email' => 'required|email'];
    }
}
