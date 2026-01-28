<?php

/**
 * Custom Admin Routes File
 * 
 * This file allows adding custom admin routes without modifying the main admin.php
 * Routes defined here will be loaded by the main admin routes file.
 */

use Illuminate\Support\Facades\Route;

// Get the admin panel prefix from the main application
$prefix = getAdminPanelUrlPrefix();

/**
 * APPEARANCE ROUTES
 * Routes for Themes and Landing Builder
 */

// Themes Routes
Route::group(['prefix' => 'themes'], function () {
    Route::get('/', 'ThemesController@index');
    Route::get('/create', 'ThemesController@create');
    Route::post('/store', 'ThemesController@store');
    Route::get('/{id}/edit', 'ThemesController@edit');
    Route::post('/{id}/update', 'ThemesController@update');
    Route::get('/{id}/delete', 'ThemesController@delete');
    Route::get('/{id}/enable', 'ThemesController@enable');
    Route::post('/getHomeLandingComponents', 'ThemesController@getHomeLandingComponents');
    
    // Theme Colors
    Route::get('/colors', 'ThemeColorsController@index');
    Route::get('/colors/create', 'ThemeColorsController@create');
    Route::post('/colors/store', 'ThemeColorsController@store');
    Route::get('/colors/{id}/edit', 'ThemeColorsController@edit');
    Route::post('/colors/{id}/update', 'ThemeColorsController@update');
    Route::get('/colors/{id}/delete', 'ThemeColorsController@delete');
    
    // Theme Fonts
    Route::get('/fonts', 'ThemeFontsController@index');
    Route::get('/fonts/create', 'ThemeFontsController@create');
    Route::post('/fonts/store', 'ThemeFontsController@store');
    Route::get('/fonts/{id}/edit', 'ThemeFontsController@edit');
    Route::post('/fonts/{id}/update', 'ThemeFontsController@update');
    Route::get('/fonts/{id}/delete', 'ThemeFontsController@delete');
    
    // Theme Headers
    Route::get('/headers', 'ThemeHeadersController@index');
    Route::get('/headers/create', 'ThemeHeadersController@create');
    Route::post('/headers/store', 'ThemeHeadersController@store');
    Route::get('/headers/{id}/edit', 'ThemeHeadersController@edit');
    Route::post('/headers/{id}/update', 'ThemeHeadersController@update');
    Route::get('/headers/{id}/delete', 'ThemeHeadersController@delete');
    
    // Theme Footers
    Route::get('/footers', 'ThemeFootersController@index');
    Route::get('/footers/create', 'ThemeFootersController@create');
    Route::post('/footers/store', 'ThemeFootersController@store');
    Route::get('/footers/{id}/edit', 'ThemeFootersController@edit');
    Route::post('/footers/{id}/update', 'ThemeFootersController@update');
    Route::get('/footers/{id}/delete', 'ThemeFootersController@delete');
});

// Landing Builder Routes
Route::group(['prefix' => 'landing-builder', 'namespace' => 'LandingBuilder'], function () {
    // Welcome/Start page
    Route::get('/start', 'LandingBuilderController@welcome');
    
    // All pages list
    Route::get('/all-pages', 'LandingBuilderController@allLandingPages');
    
    // Create
    Route::get('/create', 'LandingBuilderController@create');
    Route::post('/store', 'LandingBuilderController@store');
    
    // Edit
    Route::get('/{id}/edit', 'LandingBuilderController@edit');
    Route::post('/{id}/update', 'LandingBuilderController@update');
    
    // Delete & Duplicate
    Route::get('/{id}/delete', 'LandingBuilderController@delete');
    Route::get('/{id}/duplicate', 'LandingBuilderController@duplicate');
    
    // Component management
    Route::post('/{id}/sort-components', 'LandingBuilderController@sortComponents');
    Route::get('/component-preview/{name}', 'LandingBuilderController@componentPreview');
    
    // Landing Builder Components Routes
    Route::group(['prefix' => 'components'], function () {
        Route::get('/', 'LandingBuilderComponentController@index');
        Route::get('/create', 'LandingBuilderComponentController@create');
        Route::post('/store', 'LandingBuilderComponentController@store');
        Route::get('/{id}/edit', 'LandingBuilderComponentController@edit');
        Route::post('/{id}/update', 'LandingBuilderComponentController@update');
        Route::get('/{id}/delete', 'LandingBuilderComponentController@delete');
        Route::post('/order-items', 'LandingBuilderComponentController@orderItems');
    });
});