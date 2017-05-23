<?php
namespace App\Requests;

class RequestPasswordResetRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return ['email' => 'required|email'];
    }
}
