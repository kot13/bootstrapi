<?php
namespace App\Schema;

class RoleSchema extends BaseSchema{

    protected $resourceType = 'role';

    public function getId($role)
    {
        return $role->id;
    }

    public function getAttributes($role)
    {
        return [
            'name' => $role->name,
            'description' => $role->description,
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at,
            'created_by' => $role->created_by,
            'updated_by' => $role->updated_by,
        ];
    }

    public function getRelationships($role, $isPrimary, array $includeList)
    {
        return [
            'rights' => [
                self::DATA => $role->rights,
            ],
        ];
    }
}