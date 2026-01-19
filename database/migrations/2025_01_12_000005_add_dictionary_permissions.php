<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDictionaryPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get the Education section ID
        $educationSection = DB::table('sections')->where('name', 'education')->first();
        
        if ($educationSection) {
            // Insert admin dictionary permission
            DB::table('permissions')->insert([
                [
                    'section_id' => $educationSection->id,
                    'name' => 'admin_dictionary',
                    'caption' => 'Dictionary & Flashcard',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // Get the permission ID we just created
            $adminDictionaryPermission = DB::table('permissions')
                ->where('name', 'admin_dictionary')
                ->first();

            // Assign permission to admin, manager, teacher, and organization roles
            if ($adminDictionaryPermission) {
                $roles = DB::table('roles')
                    ->whereIn('name', ['admin', 'manager', 'teacher', 'organization'])
                    ->get();

                foreach ($roles as $role) {
                    DB::table('role_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $adminDictionaryPermission->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Panel permissions - these are available for all users
        // No need to add to permissions table as panel routes use middleware
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete the permission and related role permissions
        $permission = DB::table('permissions')->where('name', 'admin_dictionary')->first();
        
        if ($permission) {
            DB::table('role_permissions')->where('permission_id', $permission->id)->delete();
            DB::table('permissions')->where('id', $permission->id)->delete();
        }
    }
}
