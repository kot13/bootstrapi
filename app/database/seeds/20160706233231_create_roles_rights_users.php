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
        Role::create([
            'name' => 'user',
            'description' => 'Пользователь',
        ]);

        $user = new User();

        $user->email      = 'admin@example.com';
        $user->full_name  = 'Администратор';
        $user->password   = password_hash('qwerty', PASSWORD_DEFAULT, ['cost' => 13]);
        $user->role_id    = User::ROLE_ADMIN;
        $user->status     = User::STATUS_ACTIVE;

        $user->save();
    }
}