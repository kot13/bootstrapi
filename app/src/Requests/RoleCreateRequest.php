<?php
namespace App\Requests;

class RoleCreateRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|max:255',
            'description' => 'required|max:255',
        ];
    }
}