<?php

namespace Database\Seeders;

use App\Models\User;
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
        // 1. Create API permissions
        $apiPermissions = [
            'view-students',
            'manage-students',
            'view-courses',
            'manage-courses',
            'view-enrollments',
            'manage-enrollments',
        ];

        // 2. Create Admin UI permissions
        $uiPermissions = [
            'students.*',
            'courses.*',
            'enrollments.*',
            'admins.*',
        ];

        $allPermissions = array_merge($apiPermissions, $uiPermissions);

        foreach ($allPermissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // 3. Create roles and assign permissions
        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        $superAdminRole->syncPermissions($allPermissions);

        $adminRole = Role::findOrCreate('Admin', 'web');
        $adminRole->syncPermissions([
            'view-students',
            'manage-students',
            'view-courses',
            'manage-courses',
            'view-enrollments',
            'manage-enrollments',
            'students.*',
            'courses.*',
            'enrollments.*',
            'admins.*',
        ]);

        // Keep lowercase 'admin' role for API compatibility if needed
        $legacyAdminRole = Role::findOrCreate('admin', 'web');
        $legacyAdminRole->syncPermissions([
            'view-students',
            'manage-students',
            'view-courses',
            'manage-courses',
            'view-enrollments',
            'manage-enrollments',
        ]);

        $staffRole = Role::findOrCreate('Staff', 'web');
        $staffRole->syncPermissions([
            'view-students',
            'view-courses',
            'view-enrollments',
        ]);

        // Keep lowercase 'staff' role for API registration default
        $legacyStaffRole = Role::findOrCreate('staff', 'web');
        $legacyStaffRole->syncPermissions([
            'view-students',
            'view-courses',
            'view-enrollments',
        ]);

        // 4. Create default users and assign roles
        
        // Super Admin
        $superAdmin = User::updateOrCreate([
            'email' => 'superadmin@example.com',
        ], [
            'name' => 'Super Admin User',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        // Admin
        $admin = User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
        ]);
        // Assign both uppercase and lowercase for maximum safety
        $admin->assignRole($adminRole);
        $admin->assignRole($legacyAdminRole);

        // Staff
        $staff = User::updateOrCreate([
            'email' => 'staff@example.com',
        ], [
            'name' => 'Staff User',
            'password' => Hash::make('password'),
        ]);
        // Assign both uppercase and lowercase for maximum safety
        $staff->assignRole($staffRole);
        $staff->assignRole($legacyStaffRole);
    }
}

