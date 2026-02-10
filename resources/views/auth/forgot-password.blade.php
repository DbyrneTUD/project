<x-app-layout>
    <div class="min-h-screen pt-24 bg-base-100 p-4">
        <div class="mx-auto max-w-lg">
            <div class="card bg-base-200 shadow-lg border border-base-300">
                <div class="card-body">
                    <h1 class="card-title text-3xl">Forget your password</h1>
                        <div class="mb-4 text-sm text-base-content/70">
                            {{ ('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="p-1 h-8 block mt-1 w-full bg-base-100" type="email" name="email" :value="old('email')" required autofocus />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="btn btn-primary btn-md">Email Password Reset Link</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
