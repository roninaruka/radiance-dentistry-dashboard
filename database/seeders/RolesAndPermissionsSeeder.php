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
            ['email' => 'roninaruka@gmail.com'],
            [
                'name' => 'Sunil Kumar',
                'password' => Hash::make('password@123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

    }
}
