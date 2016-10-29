<?php
namespace App\Schema;

final class UserSchema extends BaseSchema
{
    protected $resourceType = 'user';

    public function getId($user)
    {
        return $user->id;
    }

    public function getAttributes($user)
    {
        return [
            'full_name'  => $user->full_name,
            'email'      => $user->email,
            'role_id'    => (int) $user->role_id,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'created_by' => $user->created_by,
            'updated_by' => $user->updated_by,
            'status'     => $user->status,
        ];
    }

    public function getRelationships($user, $isPrimary, array $includeList)
    {
        return [
            'role' => [
                self::DATA => $user->role,
            ],
        ];
    }
}