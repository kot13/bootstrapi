<?php
namespace App\Requests;

class RefreshTokenRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }
}
