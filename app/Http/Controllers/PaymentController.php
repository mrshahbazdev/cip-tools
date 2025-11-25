<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail; // Assume hum Invoice ke liye yeh mail use kar rahe hain

class PaymentController extends Controller
{
    // Stripe Plan ID (Aapke Stripe Dashboard se)
    protected $membershipPlanId = 'price_XXXXXXXXXXXXXX'; // IMPORTANT: REPLACE THIS WITH YOUR ACTUAL STRIPE PRICE ID

    /**
     * Payment form dikhana
     */
    public function showPaymentForm(Request $request)
    {
        $project = tenant();

        // Agar tenant identify nahi hua to 404
        if (!$project) {
            abort(404, 'Project not initialized');
        }

        $admin = $project->superAdmin;

        // Agar user already active hai, to redirect kar dein
        if ($project->is_active) {
            return redirect()->route('tenant.admin')->with('success', 'Aapka account pehle hi active hai.');
        }

        // Stripe setup intent create karein (Cashier requires Billable trait on User)
        // Ye fori card details collect karne ke liye zaroori hai
        $intent = $admin->createSetupIntent();

        // Note: is mein $admin variable ka hona zaroori hai (jaisa ke view mein use hua hai)
        return view('tenant.payment_form', [
            'project' => $project,
            'admin' => $admin,
            'intent' => $intent,
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    /**
     * Credit Card/PayPal Subscription Handling (Instant Activation)
     */
    public function subscribe(Request $request)
    {
        $project = tenant();
        $admin = $project->superAdmin;

        // Invoicing details validation (REQUIRED: 12-month membership ke liye)
        $request->validate([
            'billing_details' => 'required|string|min:20',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Subscription create karna
            $admin->newSubscription('default', $this->membershipPlanId)
                  ->create($request->payment_method, [
                      'email' => $admin->email
                  ]);

            // Payment successful, Project ko activate karein (Requirement: Instant Activation)
            $project->is_active = true;
            $project->save();

            DB::commit();

            // TODO: Activation email/Notification logic shamil karein

            return redirect()->route('tenant.admin')->with('success', 'Payment successful! Aapka project fori taur par activate ho gaya hai.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Invoice Request Handling (Manual Activation)
     */
    public function generateInvoice(Request $request)
    {
        $project = tenant();
        $admin = $project->superAdmin;

        $request->validate([
            'billing_details' => 'required|string|min:20',
        ]);

        // Project ko activate nahi karna (Requirement: Manual Activation after payment)
        $project->is_active = false;
        $project->save();

        // 1. Invoice Email Bhejen (Confirmation aur Next Steps)
        Mail::to($admin->email)->send(new InvoiceMail($project, $request->billing_details));

        // 2. Database mein Invoice Request ka record rakhen (Future scope)

        return redirect()->route('tenant.admin')->with('info', 'Invoice aapke email par bhej di gayi hai. Payment receive hone par hum manually aapka account activate kar denge.');
    }
}
