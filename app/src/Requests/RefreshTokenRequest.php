<?php
namespace App\Requests;

class RefreshTokenRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }
}
