<script src="https://cdn.tailwindcss.com"></script>
<nav class="bg-gray-800">
    <div class="max-w-full mx-auto px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo/Brand (Left) -->
            <div class="w-32 flex-none">
                <a href="/" class="flex items-center">
                    <img src="/assets/brand/logo.svg" alt="Logo" class="h-12 w-32 text-white">
                </a>
            </div>

            <!-- Navigation Links (Center) -->
            <div class="hidden md:flex items-center justify-center flex-1">
                <div class="flex items-center space-x-8">
                    <a href="/" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="/about"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">About</a>
                    <a href="/projects"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                    <a href="/contact"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                </div>
            </div>

            <!-- Auth Buttons (Right) -->
            <div class="w-32 hidden md:flex items-center justify-end space-x-4 flex-none">
                <a href="/register"
                    class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                <a href="/user/login"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600">Login</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" onclick="toggleMobileMenu()"
                    class="text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu icon -->
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="/about"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="/projects"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>
                <a href="/contact"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                <a href="/register"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Register</a>
                <a href="/user/login"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Login</a>
            </div>
        </div>
    </div>
</nav>
<script>
    function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
}
</script>