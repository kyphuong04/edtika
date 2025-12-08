<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== ROUTE ERROR DIAGNOSTIC ===\n\n";

// Get all routes
$routes = Route::getRoutes();
$errors = [];
$checked = 0;

echo "Total routes to check: " . count($routes) . "\n\n";
echo "Checking for missing controllers...\n\n";

foreach ($routes as $route) {
    $checked++;
    $action = $route->getAction();
    
    if (isset($action['controller'])) {
        list($controller, $method) = explode('@', $action['controller']);
        
        // Check if controller class exists
        if (!class_exists($controller)) {
            $errors[] = [
                'type' => 'Missing Controller',
                'controller' => $controller,
                'method' => $method,
                'uri' => $route->uri(),
                'methods' => implode('|', $route->methods())
            ];
        } else {
            // Check if method exists
            if (!method_exists($controller, $method)) {
                $errors[] = [
                    'type' => 'Missing Method',
                    'controller' => $controller,
                    'method' => $method,
                    'uri' => $route->uri(),
                    'methods' => implode('|', $route->methods())
                ];
            }
        }
    }
}

echo "\nChecked: {$checked} routes\n";
echo "Errors found: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "=== ERRORS FOUND ===\n\n";
    
    $grouped = [];
    foreach ($errors as $error) {
        $key = $error['controller'];
        if (!isset($grouped[$key])) {
            $grouped[$key] = [];
        }
        $grouped[$key][] = $error;
    }
    
    foreach ($grouped as $controller => $controllerErrors) {
        echo "Controller: {$controller}\n";
        echo "Errors: " . count($controllerErrors) . "\n";
        echo "Type: " . $controllerErrors[0]['type'] . "\n";
        echo "Routes affected:\n";
        
        foreach ($controllerErrors as $err) {
            echo "  - [{$err['methods']}] {$err['uri']}\n";
            echo "    Method: {$err['method']}\n";
        }
        echo "\n";
    }
} else {
    echo "✓ No route errors found!\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
