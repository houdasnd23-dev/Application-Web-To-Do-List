<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('taskly.admin.email');
        $password = config('taskly.admin.password');

        if (! $email || ! $password) {
            $this->command->error('ADMIN_EMAIL et ADMIN_PASSWORD doivent etre definis dans .env');
            return;
        }

        $role = Role::where('name', 'admin')->firstOrFail();

        // role_id n'est pas assignable en masse : on deverrouille uniquement ici
        User::unguarded(function () use ($email, $password, $role) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => config('taskly.admin.name'),
                    'password' => $password,
                    'role_id' => $role->id,
                ]
            );
        });
    }
}