<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'name' => 'ARS Super Admin',
                'email' => 'ars@gmail.com',
                'role' => AdminUser::ROLE_SUPER,
                'password' => '1234567',
                'is_active' => true,
            ],
            [
                'name' => 'ARS Blog SEO Admin',
                'email' => 'ars@gmail.com',
                'role' => AdminUser::ROLE_BLOG,
                'password' => '1234567',
                'is_active' => true,
            ],
            [
                'name' => 'ARS Advanced Admin',
                'email' => 'arsdeveloper@gmail.com',
                'role' => AdminUser::ROLE_ADVANCED,
                'password' => '1234567',
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $admin) {
            $record = AdminUser::query()->firstOrNew([
                'email' => $admin['email'],
                'role' => $admin['role'],
            ]);

            $record->name = $admin['name'];
            $record->is_active = (bool) $admin['is_active'];
            $record->password = Hash::make($admin['password']);
            $record->save();
        }
    }
}
