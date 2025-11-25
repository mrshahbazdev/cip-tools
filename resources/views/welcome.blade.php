<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIP-Tools.de - Innovation & Idea Management</title>
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for clean typography and background */
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .feature-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="antialiased text-gray-800">

    <!-- Header / Navigation -->
    <header class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="/" class="text-3xl font-extrabold text-indigo-700 hover:text-indigo-800 transition">CIP-Tools.de</a>
            <nav class="flex space-x-4">
                <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium transition duration-150 hidden sm:block">Features</a>
                <a href="#pricing" class="text-gray-600 hover:text-indigo-600 font-medium transition duration-150 hidden sm:block">Pricing</a>
                <a href="{{ route('register.create') }}" class="px-3 sm:px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition duration-150 font-semibold text-sm sm:text-base">
                    Free 30-Day Trial
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="text-center py-16 sm:py-24 bg-gray-50 border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                Innovation <span class="text-indigo-600">Thought Together</span> and Made Together.
            </h2>
            <p class="mt-4 text-lg sm:text-xl text-gray-600">
                Collect new and valuable ideas from every individual in your organization.
            </p>
            <a href="{{ route('register.create') }}" class="mt-8 inline-block px-8 py-4 bg-indigo-600 text-white text-lg font-bold rounded-xl shadow-2xl shadow-indigo-400/50 hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                Start Your 30-Day Trial Now
            </a>
            <p class="mt-4 text-sm text-gray-500">No credit card required. Instant subdomain activation.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl sm:text-4xl font-bold text-center mb-10 sm:mb-12 text-gray-800">Key Features of CIP-Tools</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Feature 1: Subdomain & Tenancy -->
                <div class="feature-card p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="text-indigo-500 text-4xl mb-4">🌐</div>
                    <h4 class="text-xl font-semibold mb-2">Dedicated Subdomain</h4>
                    <p class="text-gray-600">
                        Automatic, unique subdomain for each project (e.g., `company.cip-tools.de`) for secure and isolated operation.
                    </p>
                </div>

                <!-- Feature 2: Monetization & Activation -->
                <div class="feature-card p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="text-green-500 text-4xl mb-4">💰</div>
                    <h4 class="text-xl font-semibold mb-2">Flexible Activation</h4>
                    <p class="text-gray-600">
                        Instant activation via Credit Card/PayPal, or manual activation through Invoice for 12-month membership.
                    </p>
                </div>

                <!-- Feature 3: Automated Trial -->
                <div class="feature-card p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="text-amber-500 text-4xl mb-4">⏱️</div>
                    <h4 class="text-xl font-semibold mb-2">Automated Trial Alerts</h4>
                    <p class="text-gray-600">
                        Automated alerts sent to Super Admin on days 20, 25, and 30 during the 30-day trial.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing / Next Steps Section -->
    <section id="pricing" class="py-16 sm:py-20 bg-gray-100">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h3 class="text-3xl sm:text-4xl font-bold mb-4 text-gray-800">Ready to Activate?</h3>
            <p class="text-lg text-gray-600 mb-8">
                After the trial, only a 12-month membership is required.
            </p>
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-indigo-200">
                <h4 class="text-3xl font-extrabold text-indigo-700">Annual Membership</h4>
                <p class="text-5xl font-extrabold mt-2 mb-4 text-gray-900">[EUR 99.99<span class="text-xl font-normal text-gray-500"> / Year</span>]</p>
                <p class="text-sm text-gray-500 mb-6">Choose your pricing plan and invoicing details from your dashboard.</p>
                <a href="{{ route('register.create') }}" class="inline-block px-10 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg hover:bg-indigo-700 transition duration-150">
                    Go to Registration
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            &copy; 2025 CIP-Tools.de | All rights reserved. | <a href="#" class="hover:underline">Privacy Policy</a>
        </div>
    </footer>

</body>
</html>
