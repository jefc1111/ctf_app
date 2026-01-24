<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;


class PermissionsAndRolesSeeder extends Seeder
{
    public static array $ROLES = [
        'sa' => 'Super Admin', // Is progrmatically granted all permissions
        'a' => 'Admin',
        'es' => 'Event Staff',
        'sc' => 'Senior Coach',
        'c' => 'Coach',
        /* 'tc' => 'Team Captain', */ // I think this is going to be handles separately in Jetsream's Teams implementation
        'p' => 'Participant'
    ];

    private static array $PERMISSIONS_ROLE_MAP = [
        'add_case' => ['a'],
        'change_case' => ['a'],
        'delete_case' => ['a'],
        'add_category' => ['a'],
        'change_category' => ['a'],
        'delete_category' => ['a'],
        'add_submission' => [ /* 'a', */ 'p'],
        'add_coach_feedback' => [ /* 'a', */ 'sc', 'c'],
        'change_submission_status' => ['a', 'sc', 'c'],
        'change_submission_category' => ['a', 'sc', 'c'],
        'delete_submission' => ['a'],
        'add_team' => ['a', 'p' /* only 1 team */],
        'change_team_coach' => ['a'],
        'kick_team_member' => ['a' /*, 'tc' */],
        'change_team_name' => ['a' /*, 'tc' */],
        'disqualify_team' => ['a'],
        'add_user' => ['a'],
        'change_role' => ['a'],
        /* 'change_username' => array_keys(PermissionsAndRolesSeeder::roles), */ // Standard authenticated user functionality 
        'change_user_verification_status' => ['a']
    ];

    /**
     * Run the database seeds.  
     */
    public function run(): void
    {
        foreach (PermissionsAndRolesSeeder::$ROLES as $shortcode => $roleName) {
            $$shortcode = Role::create(['name' => $roleName]);
        }

        foreach (PermissionsAndRolesSeeder::$PERMISSIONS_ROLE_MAP as $permission_codename => $roleShortcodes) {
            $permission = Permission::create(['name' => Str::headline($permission_codename)]);

            foreach ($roleShortcodes as $roleShortcode) {
                $$roleShortcode->givePermissionTo($permission);
            }
        }
    }

    public static function nonSuperAdminRoles(): array
    {
        return array_diff(self::$ROLES, ['Super Admin']);
    }
}
