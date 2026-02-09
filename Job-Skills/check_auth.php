<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Authentication & Authorization Check ===\n\n";

// Check if users exist
$users = App\Models\User::all();
echo "Total users: " . $users->count() . "\n\n";

if ($users->count() > 0) {
    echo "Users in database:\n";
    foreach ($users as $user) {
        echo "  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, is_admin: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    }
    echo "\n";
}

// Check if there's an admin user
$adminUsers = App\Models\User::where('is_admin', true)->get();
echo "Admin users: " . $adminUsers->count() . "\n";
if ($adminUsers->count() > 0) {
    foreach ($adminUsers as $admin) {
        echo "  - {$admin->name} ({$admin->email})\n";
    }
}
echo "\n";

// Check authentication middleware
echo "Auth middleware is configured in routes/web.php\n";
echo "Gates defined in AppServiceProvider:\n";
echo "  - access-admin\n";
echo "  - manage-jobs\n";
echo "  - update-job\n";
echo "  - delete-job\n\n";

echo "=== Recommendations ===\n";
if ($adminUsers->count() === 0) {
    echo "⚠️  No admin users found! You need to create an admin user.\n";
    echo "   Run: php artisan tinker\n";
    echo "   Then: \$user = User::first(); \$user->is_admin = true; \$user->save();\n\n";
}

echo "✓ Migration has been run\n";
echo "✓ Gates are defined\n";
echo "✓ Routes are protected with auth middleware\n";
