<?php
namespace App\Requests;

class RoleUpdateRequest implements IRequest
{
    /**
     * @return array<string,string>
     */
    public function rules()
    {
        return [
            'name'        => 'max:255',
            'description' => 'max:255',
        ];
    }
}
