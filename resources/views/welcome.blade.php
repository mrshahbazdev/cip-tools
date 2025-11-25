<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIP-Tools.de - Innovation Management Platform</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-text {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">CIP-Tools.de</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-blue-600 font-medium transition duration-150">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-blue-600 font-medium transition duration-150">How It Works</a>
                    <a href="#pricing" class="text-gray-600 hover:text-blue-600 font-medium transition duration-150">Pricing</a>
                </div>

                <!-- CTA Button -->
                <div class="flex items-center">
                    <a href="{{ route('register.create') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200 shadow-sm">
                        Start Free Trial
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Transform Ideas Into
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-purple-200">Innovation</span>
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Empower your organization to collect, manage, and reward innovative ideas with our multi-tenant platform.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('register.create') }}"
                       class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition duration-200 shadow-lg transform hover:scale-105">
                        🚀 Start 30-Day Free Trial
                    </a>
                    <a href="#features"
                       class="border border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold transition duration-200">
                        Learn More
                    </a>
                </div>
                <p class="text-blue-200 mt-6 text-sm">
                    No credit card required • Setup in 2 minutes
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-3xl font-bold text-blue-600">500+</div>
                    <div class="text-gray-600 mt-2">Projects Launched</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-600">10,000+</div>
                    <div class="text-gray-600 mt-2">Ideas Managed</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-purple-600">99.9%</div>
                    <div class="text-gray-600 mt-2">Uptime Reliability</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything You Need to Drive Innovation</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Powerful features designed to streamline your innovation management process</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-blue-300 transition duration-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Dedicated Subdomains</h3>
                    <p class="text-gray-600">Each project gets its own secure subdomain with complete data isolation and custom branding.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-green-300 transition duration-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Bonus & Reward System</h3>
                    <p class="text-gray-600">Automatically track and reward implemented innovations with flexible bonus structures.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-purple-300 transition duration-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Enterprise Security</h3>
                    <p class="text-gray-600">Bank-level security with data encryption, role-based access, and compliance features.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-orange-300 transition duration-200">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Automated Workflows</h3>
                    <p class="text-gray-600">Streamline idea submission, review, and implementation with customizable approval processes.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-red-300 transition duration-200">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600">Track innovation metrics, ROI, and team performance with detailed analytics and reports.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 p-8 rounded-xl border border-gray-200 hover:border-indigo-300 transition duration-200">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Team Collaboration</h3>
                    <p class="text-gray-600">Enable cross-functional teams to collaborate on ideas with comments, voting, and feedback systems.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Simple Setup, Powerful Results</h2>
                <p class="text-xl text-gray-600">Get your innovation platform running in minutes</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Register & Create</h3>
                    <p class="text-gray-600">Sign up and create your project with a custom subdomain in under 2 minutes.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-green-600">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Customize & Configure</h3>
                    <p class="text-gray-600">Set up your workflows, bonus systems, and team permissions.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-purple-600">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Launch & Innovate</h3>
                    <p class="text-gray-600">Invite your team and start collecting and rewarding innovative ideas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600">Start with a free trial, then choose the plan that works for you</p>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl p-8 text-white text-center">
                <h3 class="text-2xl font-bold mb-4">Annual Membership</h3>
                <div class="text-5xl font-bold mb-2">€99.99</div>
                <div class="text-blue-100 mb-6">per year per project</div>

                <div class="grid md:grid-cols-2 gap-6 mb-8 text-left">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Dedicated subdomain</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Unlimited team members</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Bonus system included</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Advanced analytics</span>
                    </div>
                </div>

                <a href="{{ route('register.create') }}"
                   class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-4 rounded-lg text-lg font-semibold transition duration-200 shadow-lg inline-block">
                    Start 30-Day Free Trial
                </a>
                <p class="text-blue-200 mt-4 text-sm">No commitment during trial period</p>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-16 bg-gray-900 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Transform Your Innovation Process?</h2>
            <p class="text-xl text-gray-300 mb-8">Join forward-thinking companies already using CIP-Tools.de</p>
            <a href="{{ route('register.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-200 shadow-lg inline-block">
                Start Your Free Trial Today
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span class="ml-2 text-xl font-bold">CIP-Tools.de</span>
                </div>
                <p class="text-gray-400 mb-4">Innovation Management Platform</p>
                <div class="flex justify-center space-x-6 mb-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-150">Contact</a>
                </div>
                <p class="text-gray-500 text-sm">© 2024 CIP-Tools.de. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
