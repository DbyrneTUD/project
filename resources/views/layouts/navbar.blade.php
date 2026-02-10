<div class="navbar bg-base-200 shadow-sm">
    <div class="navbar-start pl-3 gap-3">
        <a href="/" class="flex items-center gap-3">
            <img src="{{asset('images/car1.png')}}" alt="logo" class="h-12 w-12" />
            <span class="text-xl font-semibold"> Need a Lift
                </span>
        </a>
    </div>
    <div class="navbar-center hidden lg:flex">
        @auth
            <ul class="menu menu-horizontal gap-5 px-1 text-base-content text-lg font-semibold">
                <li><a>Dashboard</a></li>
                <li><a>Groups</a></li>
                <li><a>Trips</a></li>
                <li><a>Profile</a></li>
            </ul>

        @endauth
    </div>

    <div class="navbar-end flex gap-3 pr-3">
        @auth
            <form method="POST" action="{{route('logout')}}">
                @csrf
                <button type="submit" class="btn btn-primary font-bold">Logout</button>
            </form>
        @endauth
        @guest
        <a href="{{route('register')}}" class="btn btn-primary font-bold">Register</a>
        <a href="{{route('login')}}" class="btn btn-success font-bold">Login</a>
        @endguest
    </div>

</div>
