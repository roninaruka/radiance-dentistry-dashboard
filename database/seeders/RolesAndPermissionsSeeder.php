<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (if any specific permissions are needed, but we'll use roles mostly)
        // Permission::create(['name' => 'edit articles']);

        // create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $contentCreatorRole = Role::firstOrCreate(['name' => 'content_creator']);
        $receptionistRole = Role::firstOrCreate(['name' => 'receptionist']);

        // Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@radiance.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@radiance.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);

        // Create Content Creator User
        $contentCreator = User::firstOrCreate(
            ['email' => 'creator@radiance.com'],
            [
                'name' => 'Content Creator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $contentCreator->assignRole($contentCreatorRole);

        // Create Receptionist User
        $receptionist = User::firstOrCreate(
            ['email' => 'receptionist@radiance.com'],
            [
                'name' => 'Receptionist',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $receptionist->assignRole($receptionistRole);
    }
}
