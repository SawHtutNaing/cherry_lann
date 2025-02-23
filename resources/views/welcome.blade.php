<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cherry Lann - Digital Marketing Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#003B73',
                        secondary: '#00A9FF',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#00A9FF 0.5px, #ffffff 0.5px);
            background-size: 10px 10px;
        }

        .social-icon:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="fixed z-50 w-full shadow-sm bg-white/90 backdrop-blur-md">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <img {{-- src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo-vJDiSFnWCQw29K9zk1Pgr5QxglvIZn.jpeg" --}} src="{{ asset('images/logo.jpeg') }}" alt="Cherry Lann Logo"
                        class="h-12">
                </div>
                <div class="hidden md:block">
                    <div class="flex items-baseline ml-10 space-x-4">
                        <a href="#home"
                            class="px-3 py-2 text-sm font-medium rounded-md text-primary hover:text-secondary">Home</a>
                        <a href="#about"
                            class="px-3 py-2 text-sm font-medium rounded-md text-primary hover:text-secondary">About</a>
                        <a href="#services"
                            class="px-3 py-2 text-sm font-medium rounded-md text-primary hover:text-secondary">Services</a>
                        <a href="#contact"
                            class="px-3 py-2 text-sm font-medium rounded-md text-primary hover:text-secondary">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 hero-pattern">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="items-center lg:grid lg:grid-cols-2 lg:gap-8">
                <div>
                    <h1 class="text-4xl font-bold text-primary sm:text-5xl md:text-6xl">
                        Boost Your Digital Presence
                    </h1>
                    <p class="mt-6 text-lg text-gray-600">
                        Specialized in Facebook marketing, post boosting, and creating engaging content that drives
                        results. Let us help you reach your target audience effectively.
                    </p>
                    <div class="mt-8">
                        <a href="#contact"
                            class="inline-flex items-center px-6 py-3 text-base font-medium text-white transition-colors duration-300 border border-transparent rounded-md bg-primary hover:bg-secondary">
                            Get Started
                        </a>
                    </div>
                </div>
                <div class="mt-12 lg:mt-0">
                    <img src="https://images.unsplash.com/photo-1552581234-26160f608093?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Digital Marketing Team" class="rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-primary sm:text-4xl">
                    Why Choose Cherry Lann?
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    We're dedicated to helping businesses grow their online presence through effective digital marketing
                    strategies.
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                    <!-- Experience -->
                    <div class="p-8 transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                        <div class="flex items-center justify-center w-12 h-12 mb-6 rounded-lg bg-secondary">
                            <i class="text-2xl text-white fas fa-star"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary">Proven Experience</h3>
                        <p class="mt-4 text-gray-600">
                            Years of experience in digital marketing and a track record of successful campaigns.
                        </p>
                    </div>

                    <!-- Results -->
                    <div class="p-8 transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                        <div class="flex items-center justify-center w-12 h-12 mb-6 rounded-lg bg-secondary">
                            <i class="text-2xl text-white fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary">Results-Driven</h3>
                        <p class="mt-4 text-gray-600">
                            Focus on delivering measurable results and ROI for your marketing investment.
                        </p>
                    </div>

                    <!-- Support -->
                    <div class="p-8 transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                        <div class="flex items-center justify-center w-12 h-12 mb-6 rounded-lg bg-secondary">
                            <i class="text-2xl text-white fas fa-headset"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-primary">Dedicated Support</h3>
                        <p class="mt-4 text-gray-600">
                            Continuous support and guidance throughout your marketing journey.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-primary sm:text-4xl">Our Services</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Comprehensive digital marketing solutions for your business
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-16 md:grid-cols-2 lg:grid-cols-3">
                <!-- Facebook Marketing -->
                <div class="overflow-hidden transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Facebook Marketing" class="object-cover w-full h-full">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-primary">Facebook Marketing</h3>
                        <ul class="mt-4 space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Page Management
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Post Boosting
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Audience Targeting
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Content Creation -->
                <div class="overflow-hidden transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1542435503-956c469947f6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Content Creation" class="object-cover w-full h-full">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-primary">Content Creation</h3>
                        <ul class="mt-4 space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Engaging Posts
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Copywriting
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Content Strategy
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Graphic Design -->
                <div class="overflow-hidden transition-shadow bg-white shadow-sm rounded-xl hover:shadow-lg">
                    <div class="h-48 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Graphic Design" class="object-cover w-full h-full">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-primary">Graphic Design</h3>
                        <ul class="mt-4 space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Social Media Graphics
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Banner Design
                            </li>
                            <li class="flex items-center">
                                <i class="mr-2 fas fa-check text-secondary"></i>
                                Brand Assets
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-primary sm:text-4xl">Connect With Us</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Ready to boost your digital presence? Reach out to us today!
                </p>
            </div>

            <div class="mt-12">
                <div class="flex justify-center space-x-8">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/cherrylannmarketing" class="social-icon group">
                        <div
                            class="flex items-center justify-center w-16 h-16 text-white transition-colors duration-300 rounded-full bg-primary group-hover:bg-secondary">
                            <i class="text-2xl fab fa-facebook-f"></i>
                        </div>
                        <p class="mt-2 text-sm text-center text-gray-600">Follow Us</p>
                    </a>

                    <!-- Viber -->
                    <a href="https://invite.viber.com/?g2=AQBZa7DQZu5SCVKFOyCTTmys8UyJBdT6lq%2BIpY2ysZjrz6pGf%2FeztDXH01YjtT90"
                        class="social-icon group">
                        <div
                            class="flex items-center justify-center w-16 h-16 text-white transition-colors duration-300 rounded-full bg-primary group-hover:bg-secondary">
                            <i class="text-2xl fab fa-viber"></i>
                        </div>
                        <p class="mt-2 text-sm text-center text-gray-600">Message Us</p>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary">
        <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <img {{-- src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/logo-vJDiSFnWCQw29K9zk1Pgr5QxglvIZn.jpeg" --}} src="{{ asset('images/logo.jpeg') }}" alt="Cherry Lann Logo"
                    class="h-12 mx-auto mb-4">
                <p class="text-white">
                    © 2025 Cherry Lann Digital Marketing. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
