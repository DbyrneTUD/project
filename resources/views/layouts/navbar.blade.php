<div class="max-lg:collapse bg-base-200 shadow-sm w-full">
    <input id="navbar-toggle" class="peer hidden" type="checkbox" />
    <label for="navbar-toggle" class="fixed inset-0 hidden max-lg:peer-checked:block"></label>
    <div class="collapse-title navbar">
        <!-- Navbar Logo and Hamburger toggle on mobile-->
        <div class="navbar-start pl-3 gap-3">
            <label for="navbar-toggle" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
            </label>
            <a href="/" class="flex items-center gap-3">
                <img src="{{asset('images/car1.png')}}" alt="logo" class="h-12 w-12" />
                <span class="text-xl font-semibold">Need a Lift</span>
            </a>
        </div>

        <!-- Main Navbar links center-->
        <div class="navbar-center hidden lg:flex">
            @auth
                <ul class="menu menu-horizontal gap-5 px-1 text-base-content text-lg font-semibold">
                    <li><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                    <li><a href="{{route('groups.index')}}">Groups</a></li>
                    <li><a href="{{route('trips.index')}}">My Trips</a></li>
                </ul>
            @endauth
        </div>

        <!-- Navbar profile, notifications, login/logout/register buttons-->
        <div class="navbar-end hidden lg:flex gap-3 pr-3 ">
            @auth
                <span class="font-semibold px-3 text-lg">Hi, {{auth()->user()->name}}</span>
                <a href="{{route('notifications.index')}}" class="btn btn-ghost">
                    <div class="indicator">
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="indicator-item status status-primary"></span>
                        @endif
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                    </div>
                </a>
                <a href="{{route('profile.show', auth()->user())}}" class="btn btn-outline">Profile</a>
                <form method="POST" action="{{route('logout')}}">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-error font-bold">Logout</button>
                </form>
            @endauth
            @guest
            <a href="{{route('register')}}" class="btn btn-primary font-bold">Register</a>
            <a href="{{route('login')}}" class="btn btn-success font-bold">Login</a>
            @endguest
        </div>
    </div>

    <!-- Hamburger menu for smaller screens -->
    <div class="collapse-content lg:hidden z-1">
        <ul class="menu">
            @auth
                <li class="menu-title">Hi, {{auth()->user()->name}}</li>
                <li><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                <li><a href="{{route('groups.index')}}">Groups</a></li>
                <li><a href="{{route('trips.index')}}">My Trips</a></li>
                <li><a href="{{route('notifications.index')}}">Notifications</a></li>
                <li><a href="{{route('profile.show', auth()->user())}}">Profile</a></li>
                <li>
                    <form method="POST" action="{{route('logout')}}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            @endauth
            @guest
                <li><a href="{{route('register')}}">Register</a></li>
                <li><a href="{{route('login')}}">Login</a></li>
            @endguest
        </ul>
    </div>

</div>
