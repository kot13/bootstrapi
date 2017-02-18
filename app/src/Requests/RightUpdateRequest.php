<?php
namespace App\Requests;

class RightUpdateRequest implements IRequest
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
