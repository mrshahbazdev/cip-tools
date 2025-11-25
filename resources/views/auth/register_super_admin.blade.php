<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Registration - CIP-Tools.de</title>

    <!-- Tailwind CSS via CDN (Vite fix) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Custom styles for better appearance */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center font-sans p-4">

    <div class="w-full max-w-lg p-8 space-y-6 bg-white card-shadow rounded-xl border border-gray-200">
        <header class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">🚀 CIP-Tools.de Registration</h1>
            <p class="mt-2 text-sm text-gray-500">30-day trial shuru karein aur apna naya subdomain hasil karein.</p>
        </header>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Errors Display -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-semibold">Registration mein masla hua:</span>
                </div>
                <ul class="list-disc list-inside text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
            @csrf

            <!-- User Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    👤 Aapka Naam
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                       placeholder="Poora Naam"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    📧 Email Address (Super Admin)
                </label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                       placeholder="apka@email.com"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
            </div>

            <!-- Project Name -->
            <div>
                <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">
                    🏢 Project/Company Ka Naam
                </label>
                <input type="text" name="project_name" id="project_name" required value="{{ old('project_name') }}"
                       placeholder="Example: InnovationHub"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                <p class="mt-2 text-xs text-gray-500 bg-blue-50 p-2 rounded">
                    💡 <strong>Note:</strong> Isse aapka subdomain banega: <code class="bg-blue-100 px-1 rounded">yourproject.cip-tools.de</code>
                </p>
            </div>

            <!-- Bonus Toggle -->
            <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                <div>
                    <label for="pays_bonus" class="text-sm font-medium text-indigo-700 block">
                        💰 Bonus System Enable Karein?
                    </label>
                    <p class="text-xs text-indigo-600 mt-1">
                        Innovation implement hone par bonus payments activate karein
                    </p>
                </div>
                <input type="checkbox" name="pays_bonus" value="1" id="pays_bonus"
                       {{ old('pays_bonus') ? 'checked' : '' }}
                       class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            </div>

            <!-- Liability Disclaimer -->
            <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-amber-800">⚠️ Zaroori Notice</p>
                        <p class="text-xs text-amber-700 mt-1">
                            Aap Super Admin ke taur par is Project ke <strong>bonus payments</strong> aur <strong>data privacy</strong> ki puri liability lenge.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 btn-hover transition duration-150">
                🚀 30 Din Ka FREE Trial Shuru Karein
            </button>

            <!-- Additional Info -->
            <div class="text-center">
                <p class="text-xs text-gray-500">
                    ✅ No credit card required<br>
                    ✅ Full access to all features<br>
                    ✅ Cancel anytime
                </p>
            </div>
        </form>
    </div>

    <!-- Optional: Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>

</body>
</html>
