<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => abort(403, 'Invalid user role.'),
        };
    }
}