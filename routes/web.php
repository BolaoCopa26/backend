<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup-db', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $output = "DB connected!\n";
        
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output .= \Illuminate\Support\Facades\Artisan::output();
        
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $output .= "\nSeeders: " . \Illuminate\Support\Facades\Artisan::output();
        
        return "<pre>" . $output . "</pre>";
    } catch (\Throwable $e) {
        return "<pre>DB Error: " . $e->getMessage() . "\n\nPlease check your Render Environment Variables (DB_HOST, DB_PORT=4000, DB_USERNAME, etc).</pre>";
    }
});
