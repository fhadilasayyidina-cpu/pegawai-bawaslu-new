<?php

use App\Enums\Role;

$user = auth()->user();

if (!$user) {
    return redirect()->route('login');
}

// Redirect based on role
if ($user->role === Role::ADMIN) {
    return redirect('/admin/dashboard');
} elseif ($user->role === Role::OPERATOR) {
    return redirect('/operator/dashboard');
} elseif ($user->role === Role::PEGAWAI) {
    return redirect('/pegawai/dashboard');
}

abort(403);
?>
