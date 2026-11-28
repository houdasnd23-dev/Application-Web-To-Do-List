<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevUserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            'admin' => 'dev-admin@taskly.local',
            'chef' => 'dev-chef@taskly.local',
            'membre' => 'dev-membre@taskly.local',
        ];

        User::unguarded(function () use ($accounts) {
            foreach ($accounts as $roleName => $email) {
                $role = Role::where('name', $roleName)->firstOrFail();

                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Test '.$roleName,
                        'password' => 'password',
                        'role_id' => $role->id,
                    ]
                );
            }
        });
    }
}