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

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center justify-center flex-1">
                <div class="flex items-center space-x-8">
                    <a href="/" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="#"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">About</a>
                    <a href="#"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                    <a href="/search"
                        class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Search</a>
                </div>
            </div>

            <!-- Login buttons -->
            <?php if (isset($_SESSION['user_id'])) : ?>
            <div class="w-32 hidden md:flex items-center justify-end space-x-4 flex-none">
                <a href="/dashboard"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600">Profile</a>
            </div>
            <div class="w-32 hidden md:flex items-center justify-end space-x-4 flex-none">
                <a href="/user/logout"
                    class="bg-red-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-600">Logout</a>
            </div>
            <?php else : ?>
            <div class="w-32 hidden md:flex items-center justify-end space-x-4 flex-none">
                <a href="/user/register"
                    class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                <a href="/user/login"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600">Login</a>
            </div>
            <?php endif; ?>





            <!-- Mobile menu -->




            <!-- Mobile menu hamburger button -->
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

        <!-- Mobile menu opened -->
        <div id="mobile-menu" class="md:hidden transition-all duration-300 opacity-0 max-h-0 overflow-hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="/"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="/about"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="/projects"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Projects</a>
                <a href="/contact"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="/dashboard"
                    class="bg-blue-500 text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-blue-600">Profile</a>
                <a href="/user/logout"
                    class="bg-red-500 text-white block px-3 py-2 rounded-md text-base font-medium hover:bg-red-600">Logout</a>
                <?php else : ?>
                <a href="/user/register"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Register</a>
                <a href="/user/login"
                    class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>



<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenu.classList.contains('opacity-0')) {
        mobileMenu.classList.remove('opacity-0', 'max-h-0');
        mobileMenu.classList.add('opacity-100', 'max-h-screen');
    } else {
        mobileMenu.classList.remove('opacity-100', 'max-h-screen');
        mobileMenu.classList.add('opacity-0', 'max-h-0');
    }
}
</script>
