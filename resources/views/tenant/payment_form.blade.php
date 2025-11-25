<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Activation - {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Stripe JS for payment elements -->
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        /* Custom styling for Stripe elements to match Tailwind inputs */
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased font-sans">

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        <header class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Activate Membership: {{ $project->name }}</h1>
            <p class="text-gray-600 mt-2">Trial Period: <span class="font-semibold text-red-600">
                @if (!$project->is_active && $project->trial_ends_at && $project->trial_ends_at->isPast())
                    EXPIRED!
                @else
                    {{ $project->trial_ends_at ? $project->trial_ends_at->diffInDays(\Carbon\Carbon::now()) : 'N/A' }} days remaining
                @endif
            </span></p>
        </header>

        <!-- Status Messages -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
                {{ session('info') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-semibold mb-6 border-b pb-2 text-gray-700">12-Month Membership Plan</h2>

            <!-- Plan Details -->
            <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
                <p class="text-lg font-bold text-indigo-800">Annual Price: [EUR 99.99/Year - Example]</p>
                <p class="text-sm text-indigo-700">12-month membership is required for full tool activation.</p>
            </div>

            <!-- Tab Navigation for Payment Methods -->
            <div class="flex border-b mb-6">
                <button onclick="showTab('card')" id="tab-card" class="py-2 px-4 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600 transition duration-150">Credit Card / PayPal</button>
                <button onclick="showTab('invoice')" id="tab-invoice" class="py-2 px-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150">Invoice Request</button>
            </div>

            <!-- Payment Content -->

            <!-- 1. Credit Card / PayPal Tab -->
            <div id="content-card" class="payment-content">
                <p class="text-gray-600 mb-4">Instant activation ke liye Credit Card ya PayPal use karein.</p>

                <form action="{{ route('payment.subscribe') }}" method="POST" id="payment-form" class="space-y-6">
                    @csrf

                    <!-- Billing Details -->
                    <div class="mb-4">
                        <label for="billing_details" class="block text-sm font-medium text-gray-700 mb-1">Billing Details (Full Name, Address, VAT/GST ID):</label>
                        <textarea name="billing_details" id="billing_details" rows="3" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"></textarea>
                    </div>

                    <!-- Stripe Card Element -->
                    <div class="mb-4">
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Credit Card Information:</label>
                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert" class="text-sm text-red-600 mt-2"></div>
                    </div>

                    <input type="hidden" name="payment_method" id="payment-method-input">

                    <button type="submit" id="card-button" data-secret="{{ $intent->client_secret }}" class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Pay and Activate Now (Instant)
                    </button>
                </form>
            </div>

            <!-- 2. Invoice Request Tab -->
            <div id="content-invoice" class="payment-content hidden">
                <p class="text-sm text-gray-600 mb-4">Invoice request karne par, aapka account payment receive hone ke **baad manually** activate kiya jayega.</p>
                <form action="{{ route('payment.invoice') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="mb-4">
                        <label for="invoice_details" class="block text-sm font-medium text-gray-700 mb-1">Invoicing Details (For Invoice Issuance):</label>
                        <textarea name="billing_details" id="invoice_details" rows="5" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        Request Invoice (Manual Activation)
                    </button>
                </form>
            </div>

        </div>

    </div>

    <script>
        // Tab Switching Logic
        function showTab(tabId) {
            document.querySelectorAll('.payment-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(`content-${tabId}`).classList.remove('hidden');

            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('border-indigo-600', 'text-indigo-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById(`tab-${tabId}`).classList.add('border-indigo-600', 'text-indigo-600');
        }

        // --- Stripe Integration Logic ---

        // Note: Stripe key must be available globally via Laravel config/services
        const stripe = Stripe('{{ config('services.stripe.key') }}');

        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const cardButton = document.getElementById('card-button');
        // Client secret is used to confirm the setup intent
        const clientSecret = cardButton.dataset.secret;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;

            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            // Using the Super Admin details for billing, as required
                            name: '{{ $admin->name }}',
                            email: '{{ $admin->email }}',
                            // Detailed billing info is taken from the billing_details textarea
                        }
                    }
                }
            );

            if (error) {
                // Show errors to the user
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                cardButton.disabled = false;
            } else {
                // Payment Method ID ko hidden input mein daalna
                document.getElementById('payment-method-input').value = setupIntent.payment_method;
                form.submit(); // Subscription request ko server par bhejen
            }
        });

        // Initialize tab on load
        showTab('card');

    </script>
</body>
</html>
