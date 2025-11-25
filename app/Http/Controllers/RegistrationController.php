<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy; // Tenancy package ke liye zaroori

class RegistrationController extends Controller
{
    /**
     * Super Admin registration form dikhana.
     */
    public function create()
    {
        // View file: resources/views/auth/register_super_admin.blade.php
        return view('auth.register_super_admin');
    }

    /**
     * User aur Dynamic Tenant (Subdomain) banana.
     */
    public function store(Request $request)
    {
        // 1. Validation (Must be unique in central database)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'project_name' => 'required|string|unique:projects,name',
        ]);

        DB::beginTransaction();
        try {
            // 2. Central User Banana (Central Database mein)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt(Str::random(10)),
            ]);

            // 3. Project Model Banana
            $project_slug = Str::slug($request->project_name);

            $project = Project::create([
                'super_admin_id' => $user->id,
                'name' => $request->project_name,
                'subdomain' => $project_slug,
                'pays_bonus' => $request->has('pays_bonus'),
                'trial_ends_at' => now()->addDays(30),
                'is_active' => false,
            ]);

            // 4. Dynamic Tenant (Subdomain) Create Karna
            // Ye domains table mein entry karega
            $project->domains()->create([
                'domain' => $project_slug . '.cip-tools.de',
            ]);

            DB::commit();

            // 5. Success: Redirect to new subdomain's admin login
            return redirect()->away('https://' . $project_slug . '.cip-tools.de/admin')
                             ->with('success', 'Trial account created. Login with your credentials.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Error handling zaroori hai
            return back()->withInput()->withErrors(['error' => 'Registration mein masla hua: ' . $e->getMessage()]);
        }
    }
}
