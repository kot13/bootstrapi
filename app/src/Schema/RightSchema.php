<?php
namespace App\Schema;

class RightSchema extends BaseSchema{

    protected $resourceType = 'right';

    public function getId($right)
    {
        return $right->id;
    }

    public function getAttributes($right)
    {
        return [
            'name' => $right->name,
            'description' => $right->description,
            'created_at' => $right->created_at,
            'updated_at' => $right->updated_at,
            'created_by' => $right->created_by,
            'updated_by' => $right->updated_by,
        ];
    }
}