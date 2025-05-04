<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cherry Lann - Marketing Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Carousel Styles */
        .carousel {
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .carousel::-webkit-scrollbar {
            display: none;
        }

        /* Social Icons Hover Animation */
        .social-icon {
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: translateY(-5px);
        }

        /* Gradient Animation */
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed z-50 w-full border-b border-gray-200 bg-white/80 backdrop-blur-md">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <span
                        class="text-2xl font-bold text-transparent bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text">
                        Cherry Lann
                    </span>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-baseline ml-10 space-x-4">
                        <a href="#home"
                            class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-pink-500">Home</a>
                        <a href="#about"
                            class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-pink-500">About</a>
                        <a href="#services"
                            class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-pink-500">Services</a>
                        <a href="#contact"
                            class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-pink-500">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 animate-gradient bg-gradient-to-br from-pink-50 via-white to-rose-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Transform Your Brand With</span>
                    <span class="block text-transparent bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text">
                        Cherry Lann
                    </span>
                </h1>
                <p class="max-w-md mx-auto mt-3 text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    We craft digital experiences that captivate, convert, and create lasting impressions. Your success
                    story starts here.
                </p>
                <div class="max-w-md mx-auto mt-5 sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="#contact"
                            class="flex items-center justify-center w-full px-8 py-3 text-base font-medium text-white border border-transparent rounded-md bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 md:py-4 md:text-lg md:px-10">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Why Choose Cherry Lann?
                </h2>
                <p class="max-w-2xl mt-4 text-xl text-gray-500 lg:mx-auto">
                    We combine creativity, strategy, and technology to deliver exceptional results.
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                    <!-- Card 1 -->
                    <div
                        class="relative p-8 transition-shadow bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg">
                        <div class="absolute -top-4">
                            <span
                                class="inline-flex items-center justify-center p-3 shadow-lg bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl">
                                <i class="text-2xl text-white fas fa-lightbulb"></i>
                            </span>
                        </div>
                        <h3 class="mt-8 text-xl font-medium text-gray-900">Creative Excellence</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Our team of creative experts crafts unique and impactful solutions that make your brand
                            stand out.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="relative p-8 transition-shadow bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg">
                        <div class="absolute -top-4">
                            <span
                                class="inline-flex items-center justify-center p-3 shadow-lg bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl">
                                <i class="text-2xl text-white fas fa-chart-line"></i>
                            </span>
                        </div>
                        <h3 class="mt-8 text-xl font-medium text-gray-900">Data-Driven Strategy</h3>
                        <p class="mt-2 text-base text-gray-500">
                            We leverage analytics and insights to create strategies that deliver measurable results.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="relative p-8 transition-shadow bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-lg">
                        <div class="absolute -top-4">
                            <span
                                class="inline-flex items-center justify-center p-3 shadow-lg bg-gradient-to-r from-pink-500 to-rose-500 rounded-xl">
                                <i class="text-2xl text-white fas fa-users"></i>
                            </span>
                        </div>
                        <h3 class="mt-8 text-xl font-medium text-gray-900">Expert Team</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Our experienced professionals are passionate about helping your business grow and succeed.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section with Carousel -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Our Services
                </h2>
                <p class="mt-4 text-xl text-gray-500">
                    Comprehensive solutions for your digital success
                </p>
            </div>

            <div class="relative mt-12">
                <!-- Carousel Container -->
                <div class="flex gap-6 py-4 overflow-x-auto carousel snap-x snap-mandatory">
                    <!-- Service Card 1 -->
                    <div class="flex-shrink-0 w-full snap-start sm:w-96">
                        <div class="overflow-hidden bg-white shadow-lg rounded-2xl">
                            <div
                                class="flex items-center justify-center h-48 bg-gradient-to-r from-pink-500 to-rose-500">
                                <i class="text-5xl text-white fas fa-bullhorn"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Digital Marketing</h3>
                                <p class="mt-2 text-gray-500">
                                    Strategic campaigns that drive engagement and conversions across all digital
                                    channels.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Card 2 -->
                    <div class="flex-shrink-0 w-full snap-start sm:w-96">
                        <div class="overflow-hidden bg-white shadow-lg rounded-2xl">
                            <div
                                class="flex items-center justify-center h-48 bg-gradient-to-r from-pink-500 to-rose-500">
                                <i class="text-5xl text-white fas fa-palette"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Brand Design</h3>
                                <p class="mt-2 text-gray-500">
                                    Creative brand identity development that tells your unique story.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Card 3 -->
                    <div class="flex-shrink-0 w-full snap-start sm:w-96">
                        <div class="overflow-hidden bg-white shadow-lg rounded-2xl">
                            <div
                                class="flex items-center justify-center h-48 bg-gradient-to-r from-pink-500 to-rose-500">
                                <i class="text-5xl text-white fas fa-code"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">Web Development</h3>
                                <p class="mt-2 text-gray-500">
                                    Custom websites that combine stunning design with powerful functionality.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Service Card 4 -->
                    <div class="flex-shrink-0 w-full snap-start sm:w-96">
                        <div class="overflow-hidden bg-white shadow-lg rounded-2xl">
                            <div
                                class="flex items-center justify-center h-48 bg-gradient-to-r from-pink-500 to-rose-500">
                                <i class="text-5xl text-white fas fa-search"></i>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900">SEO Optimization</h3>
                                <p class="mt-2 text-gray-500">
                                    Data-driven SEO strategies that improve your visibility and rankings.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scroll Indicators -->
                <div class="flex justify-center gap-2 mt-8">
                    <div class="w-2 h-2 bg-pink-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Let's Connect
                </h2>
                <p class="mt-4 text-xl text-gray-500">
                    Follow us on social media or reach out directly
                </p>
            </div>

            <div class="mt-12">
                <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                    <!-- Facebook -->

                    class="flex flex-col items-center p-6 transition-all bg-white shadow-sm social-icon rounded-2xl hover:shadow-lg">
                    <div class="flex items-center justify-center w-16 h-16 text-white bg-blue-500 rounded-full">
                        <i class="text-2xl fab fa-facebook-f"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Facebook</h3>
                    <p class="mt-1 text-sm text-gray-500">Follow our page</p>
                    </a>

                    <!-- Instagram -->
                    {{-- <a href="#"
                        class="flex flex-col items-center p-6 transition-all bg-white shadow-sm social-icon rounded-2xl hover:shadow-lg">
                        <div
                            class="flex items-center justify-center w-16 h-16 text-white rounded-full bg-gradient-to-br from-pink-500 via-red-500 to-yellow-500">
                            <i class="text-2xl fab fa-instagram"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Instagram</h3>
                        <p class="mt-1 text-sm text-gray-500">See our work</p>
                    </a> --}}

                    <!-- Telegram -->
                    {{-- <a href="#"
                        class="flex flex-col items-center p-6 transition-all bg-white shadow-sm social-icon rounded-2xl hover:shadow-lg">
                        <div class="flex items-center justify-center w-16 h-16 text-white rounded-full"
                            style="background-color: rgba(104, 56, 168, 1);">

                            <i class="text-2xl fa-brands fa-viber"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Viber</h3>
                        <p class="mt-1 text-sm text-gray-500">Message us</p>
                    </a> --}}
                    <a href="https://invite.viber.com/?g2=AQBZa7DQZu5SCVKFOyCTTmys8UyJBdT6lq%2BIpY2ysZjrz6pGf%2FeztDXH01YjtT90"
                        class="flex flex-col items-center p-6 transition-all bg-white shadow-sm social-icon rounded-2xl hover:shadow-lg"
                        target="_blank">
                        <div class="flex items-center justify-center w-16 h-16 text-white rounded-full"
                            style="background-color: rgba(104, 56, 168, 1);"
                            >
                            <i class="text-2xl fa-brands fa-viber"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Viber</h3>
                        <p class="mt-1 text-sm text-gray-500">Join our group</p>
                    </a>

                    <!-- LinkedIn -->
                    {{-- <a href="#"
                        class="flex flex-col items-center p-6 transition-all bg-white shadow-sm social-icon rounded-2xl hover:shadow-lg">
                        <div class="flex items-center justify-center w-16 h-16 text-white bg-blue-700 rounded-full">
                            <i class="text-2xl fab fa-linkedin-in"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">LinkedIn</h3>
                        <p class="mt-1 text-sm text-gray-500">Connect with us</p>
                    </a> --}}
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900">
        <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <span
                    class="text-2xl font-bold text-transparent bg-gradient-to-r from-pink-500 to-rose-500 bg-clip-text">
                    Cherry Lann
                </span>
                <p class="mt-4 text-gray-400">
                    Transform your digital presence with us
                </p>
                <div class="mt-8">
                    <p class="text-sm text-gray-400">
                        © 2024 Cherry Lann. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
