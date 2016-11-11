<?php
namespace App\Requests;

class RightUpdateRequest implements IRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'max:255',
            'description' => 'max:255',
        ];
    }
}