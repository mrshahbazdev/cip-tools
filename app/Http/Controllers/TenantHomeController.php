<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project; // Project Model abhi bhi tenant() helper se accessible hai

class TenantHomeController extends Controller
{
    /**
     * User profile aur dashboard view dikhana.
     */
    public function index()
    {
        // 1. Authentication check
        if (!Auth::check()) {
            // Agar authenticated nahi hai, to login page par redirect karein (Tenant Login)
            return redirect()->route('tenant.login'); 
        }

        // 2. Data fetching
        $user = Auth::user();
        $project = tenant(); // Current Project (Tenant)

        // 3. User specific dashboard view load karein
        // Hum Tenant Admin Dashboard view ko reuse karenge
        return view('tenant.admin', [
            'project' => $project,
            'user' => $user,
            'isAdmin' => $project->super_admin_id === $user->id, // Super Admin check
        ]);
    }
}