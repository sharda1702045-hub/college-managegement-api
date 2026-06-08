<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create permissions
        $permissions = [
            'view-students',
            'manage-students',
            'view-courses',
            'manage-courses',
            'view-enrollments',
            'manage-enrollments',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 2. Create roles and assign permissions
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions($permissions);

        $staffRole = Role::findOrCreate('staff', 'web');
        $staffRole->syncPermissions([
            'view-students',
            'view-courses',
            'view-enrollments',
        ]);

        // 3. Create admin user
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($adminRole);

        // 4. Create staff user
        $staff = User::updateOrCreate([
            'email' => 'staff@example.com',
        ], [
            'name' => 'Staff User',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole($staffRole);
    }
}
