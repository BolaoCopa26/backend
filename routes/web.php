<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup-db', function () {
    $output = "";

    // 1. Test connection
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $output .= "✅ DB connected!\n\n";
    } catch (\Throwable $e) {
        return "<pre>❌ DB Error: " . $e->getMessage() . "\n\nPlease check your Render Environment Variables.</pre>";
    }

    // 2. Run migrations (may fail if tables exist - that's OK)
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output .= "✅ Migrations:\n" . \Illuminate\Support\Facades\Artisan::output() . "\n";
    } catch (\Throwable $e) {
        $output .= "⚠️ Migration warning (tables may already exist): " . $e->getMessage() . "\n\n";
    }

    // 3. Run seeders (always runs, even if migration had issues)
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $output .= "✅ Seeders:\n" . \Illuminate\Support\Facades\Artisan::output() . "\n";
    } catch (\Throwable $e) {
        $output .= "❌ Seeder error: " . $e->getMessage() . "\n";
    }

    return "<pre>" . $output . "</pre>";
});
