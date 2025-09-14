<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>STORM - Student Organizations Records and Management System</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
<!-- Combined Navbar & Hero Section for STORM -->
<div class="bg-gray-900">
  <!-- Navbar -->
  <header class="absolute inset-x-0 top-0 z-50">
    <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">
      <div class="flex lg:flex-1">
        <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
            <span class="sr-only">STORM</span>
            <h1 class="text-2xl font-bold text-indigo-400">STORM</h1>
        </a>
      </div>

      <!-- Mobile menu button -->
      <div class="flex lg:hidden">
        <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-200">
          <span class="sr-only">Open main menu</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-6 w-6">
            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </div>

      <!-- Desktop menu -->
      <div class="hidden lg:flex lg:gap-x-12">
        <a href="#" class="text-sm/6 font-semibold text-white hover:text-indigo-400">Organizations</a>
        <a href="#" class="text-sm/6 font-semibold text-white hover:text-indigo-400">Events</a>
        <a href="#" class="text-sm/6 font-semibold text-white hover:text-indigo-400">Membership</a>
      </div>

      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-white">Login</a>
        <a href="{{ route('register') }}" class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">Register</a>
      </div>
    </nav>

    <!-- Mobile menu dialog -->
    <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
      <div tabindex="0" class="fixed inset-0 focus:outline-none">
        <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-100/10">
          <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
              <span class="sr-only">STORM</span>
              <h1 class="text-2xl font-bold text-indigo-400">STORM</h1>
            </a>
            <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-200">
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="h-6 w-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-white/10">
              <div class="space-y-2 py-6">
                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Organizations</a>
                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Events</a>
                <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Membership</a>
              </div>
              <div class="py-6">
                <a href="{{ route('login') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white hover:bg-white/5">Login</a>
                <a href="{{ route('register') }}" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white hover:bg-white/5">Register</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </dialog>
  </header>

    <!-- Hero Section -->
    <div class="relative isolate px-6 pt-24 lg:px-8">
        <div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80">
        <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-gradient-to-tr from-indigo-500 to-purple-600 opacity-30 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
        </div>

        <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56 text-center">
        <h1 class="text-5xl font-semibold tracking-tight text-white sm:text-7xl">Welcome to STORM</h1>
        <p class="mt-6 text-lg text-gray-300 sm:text-xl/8">Student Organizations Records and Management System. Manage organizations, track events, and monitor student memberships all in one place.</p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="{{ route('register') }}" class="rounded-md bg-indigo-500 px-6 py-3 text-lg font-semibold text-white shadow hover:bg-indigo-400">Get Started</a>
            <a href="{{ route('login') }}" class="text-lg font-semibold text-white">Login →</a>
        </div>
        </div>

        <div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]">
        <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-gradient-to-tr from-indigo-500 to-purple-600 opacity-30 sm:left-[calc(50%+36rem)] sm:w-288.75"></div>
        </div>
    </div>
    </div>



    <!-- Features Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Explore STORM</h2>

            <div class="grid gap-8 md:grid-cols-3">
                <!-- Most Active Organizations -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-4">Most Active Organizations</h3>
                    <p class="text-gray-300 mb-4">View the organizations with the highest member engagement and events this semester.</p>
                    <a href="#" class="text-indigo-400 font-semibold hover:underline">See all organizations →</a>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-4">Upcoming Events</h3>
                    <p class="text-gray-300 mb-4">Stay updated with upcoming events, workshops, and activities organized by the clubs.</p>
                    <a href="#" class="text-indigo-400 font-semibold hover:underline">View calendar →</a>
                </div>

                <!-- Top Members -->
                <div class="bg-gray-800 p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold mb-4">Top Members</h3>
                    <p class="text-gray-300 mb-4">Check out students who are actively participating and contributing the most in clubs.</p>
                    <a href="#" class="text-indigo-400 font-semibold hover:underline">See top members →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Optional Stats Section -->
    <section class="bg-gray-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div>
                    <p class="text-4xl font-bold text-indigo-400">25</p>
                    <p class="mt-2 text-gray-300">Organizations</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-indigo-400">120</p>
                    <p class="mt-2 text-gray-300">Upcoming Events</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-indigo-400">450</p>
                    <p class="mt-2 text-gray-300">Active Members</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 py-6 mt-10">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} STORM. All rights reserved.
        </div>
    </footer>
</body>
</html>
