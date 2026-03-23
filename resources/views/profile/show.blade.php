<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ("Profile") }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold">{{ ("$user->name") }}</h1>
                @if(auth()->id() === $user->id)
                    <a href="{{route('profile.edit')}}" class="btn btn-outline">Edit Profile</a>
                @endif
            </div>
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body flex flex-row justify-start gap-10">

                    <div>
                        @if($user->photo_path)
                            <div>
                                <img src="{{asset('storage/' . $user->photo_path)}}" alt="{{$user->name}}" class="object-cover h-50 w-50 rounded ">
                            </div>
                        @else
                            <p> No profile photo uploaded yet</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-5 mb-5">
                        <div ><span class="text-lg font-semibold">Name:</span> {{$user->name}}</div>
                        <div ><span class="text-lg font-semibold">Joined:</span> {{$user->created_at->format('j M Y')}}</div>
                        <div>
                            @if($user->bio)
                                <p><span class="text-lg font-semibold">Bio:</span> {{$user->bio}}</p>
                            @else
                                <p><span class="text-lg font-semibold">Bio:</span> No bio added yet</p>
                            @endif
                        </div>

                        <div><span class="text-lg font-semibold">Lifts given:</span> {{$liftsGiven}}</div>
                        <div><span class="text-lg font-semibold">Lifts taken:</span> {{$liftsTaken}}</div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</x-app-layout>
