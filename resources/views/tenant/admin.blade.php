<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ $project->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen antialiased">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header & Project Info -->
        <header class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                
                <div class="space-y-1">
                    <!-- Project Logo (Placeholder: First letter of project name) -->
                    <div class="text-3xl font-extrabold text-indigo-700">
                        {{ strtoupper(substr($project->name, 0, 1)) }}
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }} Dashboard</h1>
                    <p class="text-sm text-gray-500 italic">
                        "{{ $project->slogan ?? 'Thought together and made together.' }}"
                    </p>
                </div>

                <!-- Status Badge -->
                <div class="mt-4 md:mt-0 text-right">
                    @if ($project->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            ✅ ACTIVE (12-Month Membership)
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            ⏱️ TRIAL / INACTIVE
                        </span>
                    @endif
                </div>
            </div>
        </header>

        <!-- Status & Action Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <!-- 1. Activation Status Card -->
            <div class="p-6 bg-white rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Activation Status</h2>
                @if ($project->is_active)
                    <p class="text-green-600 font-bold text-2xl">Activated</p>
                    <p class="text-gray-500 mt-2">Aapka tool mukammal taur par active hai.</p>
                @else
                    <p class="text-red-600 font-bold text-2xl">Payment Pending</p>
                    <p class="text-gray-500 mt-2">
                        Aapka trial abhi jari hai ya khatam ho chuka hai. 
                        <a href="{{ route('tenant.payment.form', ['tenant' => $project->subdomain]) }}" 
                        class="text-indigo-600 hover:underline font-medium">Payment Page</a>                    </p>
                    <p class="text-xs text-red-400 mt-1">Trial ends: {{ $project->trial_ends_at?->format('d M, Y') ?? 'N/A' }}</p>
                @endif
            </div>

            <!-- 2. Customization Link Card -->
            <div class="p-6 bg-white rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Project Settings</h2>
                <p class="text-gray-500 mb-4">Logo, Slogan, aur Bonus/Incentive ki tafseelat yahan update karein.</p>
                <a href="#" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-150">
                    Edit Customization
                </a>
            </div>

            <!-- 3. User Management Card -->
            <div class="p-6 bg-white rounded-xl shadow-md border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">User & Roles</h2>
                <p class="text-gray-500 mb-4">Naye users ko invite karein aur unke roles define karein (Project Admin, User).</p>
                <a href="#" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-150">
                    Manage Team
                </a>
            </div>
        </div>

        <!-- Main Tool Content (Placeholder) -->
        <section class="p-6 bg-white rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Core Innovation Tool</h2>
            <p class="text-gray-600">
                Yahan aapke users Idea Submissions, Voting, aur Review Workflow jaisi core CIP-Tools features dekhenge.
            </p>
            
            @if ($project->pays_bonus)
                <p class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-sm text-yellow-800">
                    💡 **Incentive:** Aapne Implemented Innovation par Proposer ko Bonus dene ka faisla kiya hai.
                </p>
            @endif
            
            <a href="#" class="mt-4 inline-block text-indigo-600 hover:underline">View Idea Submission Form</a>
        </section>

    </div>

</body>
</html>