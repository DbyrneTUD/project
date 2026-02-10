<x-app-layout>
    <div class="min-h-screen pt-24 bg-base-100 p-4">
        <div class="mx-auto max-w-lg">
            <div class="card bg-base-200 shadow-lg border border-base-300">
                <div class="card-body">
                    <h1 class="card-title text-3xl">Register</h1>
                    <p class="text-sm">Create your account</p>
                    <form method="POST" action="{{ route('register') }}" class="mt-4">
                    @csrf
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="('Name')" />
                        <x-text-input id="name" class="p-1 block mt-1 w-full h-8 bg-base-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="('Email')" />
                        <x-text-input id="email" class="p-1 block h-8 mt-1 w-full bg-base-100" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="('Password')" />

                        <x-text-input id="password" class="p-1 block mt-1 h-8 w-full bg-base-100"
                                        type="password"
                                        name="password"
                                        required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="p-1 block mt-1 w-full h-8 bg-base-100"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 gap-5">
                        <a class="underline text-sm text-base-content rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                            {{ ('Already registered?') }}
                        </a>

                        <button type="submit" class="btn btn-primary btn-md">Register</button>

                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
