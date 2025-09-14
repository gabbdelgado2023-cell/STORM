<x-guest-layout>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
        <!-- Title -->
        <h1 class="text-2xl font-bold text-center text-indigo-600 dark:text-indigo-400 mb-6">
            STUDENT REGISTRATION
        </h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Full Name')" class="font-semibold" />
                <x-text-input 
                    id="name" 
                    class="block mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email Address')" class="font-semibold" />
                <x-text-input 
                    id="email" 
                    class="block mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" class="font-semibold" />
                <x-text-input 
                    id="password" 
                    class="block mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-semibold" />
                <x-text-input 
                    id="password_confirmation" 
                    class="block mt-1 w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition" href="{{ route('login') }}">
                    {{ __('Already registered? Login here') }}
                </a>

                <x-primary-button class="px-6 py-2 rounded-lg">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
