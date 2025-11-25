<?php

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail; // Assume hum Invoice ke liye alag mail banayenge

class PaymentController extends Controller
{
    // Stripe Plan ID (Aapke Stripe Dashboard se)
    protected $membershipPlanId = 'price_XXXXXXXXXXXXXX';

    /**
     * Payment form dikhana
     */
    public function showPaymentForm(Request $request)
    {
        // Current tenant (Project) aur Super Admin (Billable User)
        $project = tenant();
        $admin = $project->superAdmin;

        // Agar user already active hai, to redirect kar dein
        if ($project->is_active) {
            return redirect()->route('tenant.dashboard')->with('success', 'Aapka account pehle hi active hai.');
        }

        // Stripe setup intent create karein
        $intent = $admin->createSetupIntent();

        return view('tenant.payment_form', [
            'project' => $project,
            'admin' => $admin,
            'intent' => $intent,
            'stripeKey' => config('services.stripe.key') // .env se STRIPE_KEY load hoga
        ]);
    }

    /**
     * Credit Card/PayPal Subscription Handling (Instant Activation)
     */
    public function subscribe(Request $request)
    {
        $project = tenant();
        $admin = $project->superAdmin;

        // Invoicing details validation (Required for 12-month membership)
        $request->validate([
            'billing_details' => 'required|string', // Name, Address, VAT/GST details
            'payment_method' => 'required|string',
        ]);

        try {
            // Subscription create karna
            $admin->newSubscription('default', $this->membershipPlanId)
                  ->create($request->payment_method, [
                      'email' => $admin->email
                  ]);

            // Payment successful, Project ko activate karein
            $project->is_active = true;
            $project->save();

            // Super Admin ko activation email bhejen
            // TODO: Activation email logic

            return redirect()->route('tenant.dashboard')->with('success', 'Payment successful! Aapka project fori taur par activate ho gaya hai.');

        } catch (\Exception $e) {
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
            'billing_details' => 'required|string',
        ]);

        // 1. Invoice PDF generate karein aur database mein record rakhen
        // 2. Invoice Super Admin ko bhejen (Yahan InvoiceMail use hoga)

        // Status: Abhi activate nahi hoga
        $project->is_active = false;
        $project->save();

        // Mail::to($admin->email)->send(new InvoiceMail($project, $request->billing_details));

        return redirect()->route('payment.form')->with('info', 'Invoice aapke email par bhej di gayi hai. Payment receive hone par hum manually aapka account activate kar denge.');
    }
}
