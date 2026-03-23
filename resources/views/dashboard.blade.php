<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Dashboard') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <h1 class="text-2xl font-bold">My Groups:</h1>
            @if($myGroups->isEmpty())
                <div class="card bg-base-200 border border-base-300 ">
                    <div class="card-body">
                        <p>You have not joined any groups. </p>
                    </div>
                </div>
            @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($myGroups as $group)
                    <div class="card bg-base-200 shadow-lg border border-base-300 overflow-hidden">
                        <div class="flex">
                            @if($group->photo_path)
                                <div>
                                    <img src="{{asset('storage/' . $group->photo_path)}}" alt="{{$group->name}}" class="object-cover h-full w-60">
                                </div>
                            @endif
                            <div class="card-body space-y-4">
                                <div class="flex items-start justify-between">
                                    <h2 class="card-title text-xl">
                                        {{$group->name}}
                                    </h2>
                                    @if($group->created_by === auth()->id())
                                        <span class="badge badge-outline badge-primary font-semibold h-8">
                                            Admin
                                        </span>
                                    @endif
                                </div>
                                <p class="text-md">
                                    {{$group->description ?? 'No description provided for this group'}}
                                </p>
                                <div class="card-actions justify-end">
                                        <a href="{{route('groups.show', $group)}}" class="btn btn-accent btn-md">
                                            View Group
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{$myGroups->links()}}
            @endif
            <div class="mx-auto mt-3 h-1 w-auto rounded bg-accent "></div>


            <h1 class="text-2xl font-bold">My Lift Requests:</h1>
            @if($myRequests->isEmpty())
                <div class="card bg-base-200 border border-base-300 ">
                    <div class="card-body">
                        <p>You have not requested any lifts yet</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($myRequests as $request)
                        <div class="card bg-base-200 shadow-lg border border-base-300 ">
                            <div class="card-body space-y-4">
                                <div class="flex items-start justify-between">
                                    <h2 class="card-title mr-2">
                                        {{$request->origin}} - {{$request->destination}}
                                    </h2>

                                    <x-status-badge :status="$request->status" />
                                </div>
                                <div class="flex flex-col gap-2">
                                    <span class="font-semibold" >Earliest Departure:</span> {{date('D j M Y, H:i', strtotime($request->earliest_departure))}}
                                    <span class="font-semibold" >Latest Departure:</span> {{date('D j M Y, H:i', strtotime($request->latest_departure))}}
                                </div>
                                <div class="card-actions justify-end">
                                    <a href="{{route('requests.show', [$request->group_id, $request->id])}}" class="btn btn-accent btn-md">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif


            <div class="mx-auto mt-3 h-1 w-auto rounded bg-accent "></div>
            <h1 class="text-2xl font-bold">Upcoming Trips Im Driving:</h1>
            @if($myDrivingTrips->isEmpty())
                <div class="card bg-base-200 border border-base-300 mb-10">
                    <div class="card-body ">
                        <p>You are not the driver for any trips yet. </p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    @foreach($myDrivingTrips as $trip)
                        <div class="card bg-base-200 shadow-lg border border-base-300 ">
                            <div class="card-body space-y-4">
                                <div class="flex items-start justify-between">
                                    <h2 class="card-title text-xl">
                                        {{$trip->liftRequest->origin}} - {{$trip->liftRequest->destination}}
                                    </h2>
                                    <x-status-badge :status="$trip->status" />
                                </div>
                                <div class="flex flex-col gap-2">
                                    <span class="font-semibold text-xl pb-5" >Requester: <a href="{{route('profile.show',$trip->requester)}}" class="link link-hover"> {{$trip->requester->name}} </a></span>
                                    <span class="font-semibold" >Earliest Departure:</span> {{date('D j M Y, H:i', strtotime($trip->liftRequest->earliest_departure))}}
                                    <span class="font-semibold" >Latest Departure:</span> {{date('D j M Y, H:i', strtotime($trip->liftRequest->latest_departure))}}
                                </div>
                                <div class="card-actions justify-end">
                                    <a href="{{route('trips.show', $trip)}}" class="btn btn-accent btn-md">
                                        View Trip
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
</x-app-layout>
