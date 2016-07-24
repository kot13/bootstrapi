<?php
use App\Model\Role;
use App\Model\User;

class CreateRolesRightsUsers {
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Администратор',
        ]);

        $user = new User();

        $user->email  = 'admin@example.com';
        $user->password  = password_hash('qwerty', PASSWORD_DEFAULT, ['cost' => 13]);
        $user->role_id  = 1;
        $user->status  = 1;

        $user->save();
    }
}