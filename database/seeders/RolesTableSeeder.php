<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // New user registration (Lead - not purchased yet)
        \App\Models\Role::updateOrCreate(['id' => 1], ['name' => 'user', 'caption' => 'User role (Lead)', 'is_admin' => 0, 'created_at' => time()]);
        
        // Enrolled student (converted from User after purchase)
        \App\Models\Role::updateOrCreate(['id' => 2], ['name' => 'student', 'caption' => 'Student role', 'is_admin' => 0, 'created_at' => time()]);
        
        // Instructor (grades assignments, creates content)
        \App\Models\Role::updateOrCreate(['id' => 3], ['name' => 'teacher', 'caption' => 'Teacher role', 'is_admin' => 0, 'created_at' => time()]);
        
        // Admin/Sales (manages content, students, CRM)
        \App\Models\Role::updateOrCreate(['id' => 4], ['name' => 'admin', 'caption' => 'Admin role', 'is_admin' => 1, 'created_at' => time()]);
        
        // Manager (overview dashboard, KPI tracking)
        \App\Models\Role::updateOrCreate(['id' => 5], ['name' => 'manager', 'caption' => 'Manager role', 'is_admin' => 1, 'created_at' => time()]);
        
        // CEO (top-level management, set KPIs)
        \App\Models\Role::updateOrCreate(['id' => 6], ['name' => 'ceo', 'caption' => 'CEO role', 'is_admin' => 1, 'created_at' => time()]);
    }
}
