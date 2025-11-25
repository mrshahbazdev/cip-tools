<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Registration - CIP-Tools.de</title>
    <!-- Tailwind CSS compile hone ke baad yahan link hoga -->
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-lg p-8 space-y-6 bg-white shadow-xl rounded-xl border border-gray-200">
        <header class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">🚀 CIP-Tools.de Registration</h1>
            <p class="mt-2 text-sm text-gray-500">30-day trial shuru karein aur apna naya subdomain hasil karein.</p>
        </header>

        <!-- Errors Display -->
        @if ($errors->any())
            <div class="bg-red-100 p-4 rounded-lg border border-red-300">
                <p class="font-semibold text-red-700 mb-1">Registration mein masla hua:</p>
                <ul class="list-disc list-inside text-sm text-red-600">
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
                <label for="name" class="block text-sm font-medium text-gray-700">👤 Aapka Naam</label>
                <input type="text" name="name" required value="{{ old('name') }}" placeholder="Poora Naam"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">📧 Email Address (Super Admin)</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="apka@email.com"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Project Name -->
            <div>
                <label for="project_name" class="block text-sm font-medium text-gray-700">🏢 Project/Company Ka Naam (Subdomain)</label>
                <input type="text" name="project_name" required value="{{ old('project_name') }}" placeholder="Example: InnovationHub"
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Isse aapka subdomain banega: `example.cip-tools.de`</p>
            </div>

            <!-- Bonus Toggle -->
            <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg border border-indigo-200">
                <label for="pays_bonus" class="text-sm font-medium text-indigo-700">💰 Implemented Innovation Par Bonus Dena Chahte Hain?</label>
                <input type="checkbox" name="pays_bonus" value="1" id="pays_bonus"
                       class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
            </div>

            <!-- Liability Disclaimer -->
            <p class="text-xs text-gray-500 p-3 bg-gray-50 rounded-lg border border-gray-200">
                ⚠️ **Zaroori Note:** Aap Super Admin ke taur par is Project ke **bonus** ki adaigi aur **privacy** ki liability lenge.
            </p>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150">
                30 Din Ka Trial Shuru Karein
            </button>
        </form>
    </div>

</body>
</html>
