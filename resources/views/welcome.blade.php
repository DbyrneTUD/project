<x-app-layout>
    <!-- Hero section -->
    <div class="hero bg-base-100 min-h-screen">
        <div class="hero-content text-center gap-20">
            <div class="max-w-md space-y-6">
                <img src="{{asset('images/car1.png')}}" alt="logo" class="h-50 w-50 mx-auto" />
                <h1 class="text-6xl font-bold">Need a Lift ?</h1>
                <div class="mx-auto mt-3 h-1 w-auto rounded bg-primary"></div>


                <div class="flex flex-col gap-3 ">
                    @guest
                    <p class="py-6 text-2xl">Register now to join a local community group and organise a lift!</p>
                    <div class="flex gap-3 justify-center">
                    <a href="{{route('register')}}" class="btn btn-primary btn-xl">Register</a>
                    <a href="{{route('login')}}" class="btn btn-success btn-xl">Login</a>
                    </div>
                    @endguest
                    @auth
                    <a href="{{route('dashboard')}}" class="btn btn-primary btn-xl">Continue to Dashboard</a>

                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
