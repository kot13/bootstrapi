<?php
namespace App\Requests;

class GetTokenRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
        ];
    }
}
