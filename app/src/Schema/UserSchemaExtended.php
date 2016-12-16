<?php
namespace App\Schema;

use \Carbon\Carbon;

final class UserSchemaExtended extends BaseSchema
{
    protected $resourceType = 'user';

    public function getId($user)
    {
        return $user->id;
    }

    public function getAttributes($user)
    {
        return [
            'full_name'   => $user->full_name,
            'email'       => $user->email,
            'role_id'     => (int) $user->role_id,
            'created_at'  => Carbon::parse($user->created_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'updated_at'  => Carbon::parse($user->updated_at)->setTimezone('UTC')->format(Carbon::ISO8601),
            'created_by'  => $user->created_by,
            'updated_by'  => $user->updated_by,
            'status'      => $user->status,
            'extendField' => 'Only admin',
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