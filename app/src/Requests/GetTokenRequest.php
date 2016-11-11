<?php
namespace App\Requests;

class GetTokenRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }
}