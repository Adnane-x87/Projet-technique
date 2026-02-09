<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

echo "--- Security Check ---\n";

// 1. Check Guest Access
Auth::logout();
$guestCanAccess = Gate::allows('access-admin');
echo "Guest: " . ($guestCanAccess ? "FAIL" : "PASS") . "\n";

// 2. Check Non-Admin User
$user = User::where('is_admin', false)->first();
if (!$user) {
    echo "Creating non-admin...\n";
    $user = User::factory()->create(['is_admin' => false]);
}
Auth::login($user);
$nonAdminCanAccess = Gate::allows('access-admin');
echo "Non-Admin: " . ($nonAdminCanAccess ? "FAIL" : "PASS") . "\n";

// 3. Check Admin User
$admin = User::where('is_admin', true)->first();
if (!$admin) {
    echo "Creating admin...\n";
    $admin = User::factory()->create(['is_admin' => true]);
}
Auth::login($admin);
$adminCanAccess = Gate::allows('access-admin');
echo "Admin: " . ($adminCanAccess ? "PASS" : "FAIL") . "\n";
