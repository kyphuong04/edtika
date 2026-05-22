<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$teacherRoleId = \App\Models\Role::getTeacherRoleId();
$permissions = \App\Models\Permission::where("role_id", $teacherRoleId)
    ->with("section")
    ->get()
    ->map(function($p) { 
        return $p->section->name ?? "N/A"; 
    })
    ->filter(function($name) {
        return strpos($name, "noticeboard") !== false || strpos($name, "blog") !== false;
    });
foreach($permissions as $perm) {
    echo $perm . "\n";
}
