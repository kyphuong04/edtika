$file = "routes/admin.php"
$content = Get-Content $file -Raw

# Define the new routes  
$newRoutes = @"

        Route::group(['prefix' => 'themes'], function () {
            Route::get('/', 'ThemesController@index');
            Route::get('/create', 'ThemesController@create');
            Route::post('/store', 'ThemesController@store');
            Route::get('/{id}/edit', 'ThemesController@edit');
            Route::post('/{id}/update', 'ThemesController@update');
            Route::get('/{id}/delete', 'ThemesController@delete');
            Route::get('/{id}/enable', 'ThemesController@enable');
            Route::post('/getHomeLandingComponents', 'ThemesController@getHomeLandingComponents');
            Route::get('/colors', 'ThemeColorsController@index');
            Route::get('/fonts', 'ThemeFontsController@index');
            Route::get('/headers', 'ThemeHeadersController@index');
            Route::get('/footers', 'ThemeFootersController@index');
        });

        Route::group(['prefix' => 'landing-builder', 'namespace' => 'LandingBuilder'], function () {
            Route::get('/start', 'LandingBuilderController@welcome');
            Route::get('/all-pages', 'LandingBuilderController@allLandingPages');
            Route::get('/create', 'LandingBuilderController@create');
            Route::post('/store', 'LandingBuilderController@store');
            Route::get('/{id}/edit', 'LandingBuilderController@edit');
            Route::post('/{id}/update', 'LandingBuilderController@update');
            Route::get('/{id}/delete', 'LandingBuilderController@delete');
        });
"@

# Find and replace
$search = "        /* End Admin Middleware */"
$replacement = $newRoutes + "`r`n" + $search

$content = $content -replace [regex]::Escape($search), $replacement

# Save the file
Set-Content -Path $file -Value $content -Encoding UTF8

Write-Host "Done! Routes added successfully."

# Clear cache
php artisan route:clear | Out-Null
php artisan cache:clear | Out-Null

Write-Host "Cache cleared!"
