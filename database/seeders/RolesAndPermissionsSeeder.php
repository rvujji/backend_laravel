<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();
        /*
        |--------------------------------------------------------------------------
        | PERMISSIONS
        |--------------------------------------------------------------------------
        */
        $permissions = [
            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            'dashboard:read',
            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */
            'profile:read',
            'profile:update',
            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            'user:read',
            'user:create',
            'user:update',
            'user:delete',
            'user:manage',
            'user:audit',
            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */
            'role:read',
            'role:create',
            'role:update',
            'role:delete',
            /*
            |--------------------------------------------------------------------------
            | Workshops
            |--------------------------------------------------------------------------
            */
            'workshop:read',
            'workshop:create',
            'workshop:update',
            'workshop:delete',
            'workshop:publish',
            'workshop:update:any',
            'workshop:update:own',
            'workshop:delete:any',
            'workshop:delete:own',
            /*
            |--------------------------------------------------------------------------
            | Enrollment
            |--------------------------------------------------------------------------
            */
            'enrollment:read',
            'enrollment:create',
            'enrollment:update',
            'enrollment:cancel',
            /*
            |--------------------------------------------------------------------------
            | Reviews
            |--------------------------------------------------------------------------
            */
            'review:read',
            'review:create',
            'review:update',
            'review:delete',
        ];
        /*
        |--------------------------------------------------------------------------
        | CREATE PERMISSIONS
        |--------------------------------------------------------------------------
        */
        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $trainerRole = Role::firstOrCreate([
            'name' => 'trainer',
            'guard_name' => 'web'
        ]);
        $studentRole = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'web'
        ]);
        $parentRole = Role::firstOrCreate([
            'name' => 'parent',
            'guard_name' => 'web'
        ]);
        $supervisorRole = Role::firstOrCreate([
            'name' => 'supervisor',
            'guard_name' => 'web'
        ]);
        $othersRole = Role::firstOrCreate([
            'name' => 'others',
            'guard_name' => 'web'
        ]);
        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */
        $adminRole->givePermissionTo(Permission::all());
        /*
        |--------------------------------------------------------------------------
        | TRAINER
        |--------------------------------------------------------------------------
        */
        $trainerRole->givePermissionTo([

            'dashboard:read',

            'profile:read',
            'profile:update',

            'workshop:read',
            'workshop:create',
            'workshop:update',
            'workshop:publish',
            'workshop:update:own',
            'workshop:delete:own',

            'enrollment:read',
            'enrollment:update',

            'review:read',
            'review:update',
        ]);
        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */
        $studentRole->givePermissionTo([

            'dashboard:read',

            'profile:read',
            'profile:update',

            'workshop:read',

            'enrollment:create',
            'enrollment:read',
            'enrollment:cancel',

            'review:create',
            'review:read',
        ]);
        /*
        |--------------------------------------------------------------------------
        | PARENT
        |--------------------------------------------------------------------------
        */
        $parentRole->givePermissionTo([

            'dashboard:read',

            'profile:read',
            'profile:update',

            'workshop:read',

            'enrollment:read',

            'review:read',
        ]);
        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */
        $supervisorRole->givePermissionTo([

            'dashboard:read',

            'profile:read',
            'profile:update',

            'user:read',
            'workshop:read',
            'workshop:update',

            'enrollment:read',

            'review:read',
        ]);
        /*
        |--------------------------------------------------------------------------
        | OTHERS
        |--------------------------------------------------------------------------
        */
        $othersRole->givePermissionTo([

            'dashboard:read',

            'profile:read',
            'profile:update',
        ]);
    }
}