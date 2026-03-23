<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Trip') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Trip from group: {{$group->name}}</h1>

                <a href="{{url()->previous()}}" class="btn">Back</a>
            </div>
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body space-y-2">
                    <div><span class="font-semibold">Status: </span> {{ucfirst($trip->status)}} </div>
                    <div><span class="font-semibold">Driver: </span><a href="{{route('profile.show', $trip->driver)}}" class="link link-hover">{{$trip->driver->name}} </a></div>
                    <div><span class="font-semibold">Passenger: </span><a href="{{route('profile.show', $trip->requester)}}" class="link link-hover">{{$trip->requester->name}} </a></div>
                    <div><span class="font-semibold">Route: </span>  {{$request->origin}} - {{$request->destination}} </div>
                    <div><span class="font-semibold" >Earliest Departure:</span> {{date('D j M Y, H:i', strtotime($request->earliest_departure))}}</div>
                    <div><span class="font-semibold" >Latest Departure:</span> {{date('D j M Y, H:i', strtotime($request->latest_departure))}}</div>
                    <div class="flex justify-between mt-5">
                        <div class="flex gap-2">
                            <a href="https://www.google.com/maps/dir/?api=1&origin={{urlencode($request->origin)}}&destination={{urlencode($request->destination)}}&travelmode=driving" class="btn bg-white text-black border-[#e5e5e5]" target="_blank">
                                <svg aria-label="Google logo" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g><path d="m0 0H512V512H0" fill="#fff"></path><path fill="#34a853" d="M153 292c30 82 118 95 171 60h62v48A192 192 0 0190 341"></path><path fill="#4285f4" d="m386 400a140 175 0 0053-179H260v74h102q-7 37-38 57"></path><path fill="#fbbc02" d="m90 341a208 200 0 010-171l63 49q-12 37 0 73"></path><path fill="#ea4335" d="m153 219c22-69 116-109 179-50l55-54c-78-75-230-72-297 55"></path></g></svg>
                                Open Trip in Google Maps
                            </a>
                            <a href="{{ route('messages.show', $trip) }}" class="btn btn-outline btn-primary">Open Chat</a>
                        </div>
                        <div class="flex gap-3">
                            @if (($request->trip->driver_id === auth()->id() || $request->requester_id === auth()->id()) && $request->trip->status !== 'completed')
                                <form method="POST" action="{{route('requests.cancel', [$group, $request])}}">
                                    @csrf
                                    <button class="btn btn-error">Cancel Trip</button>
                                </form>
                            @endif

                            @if ($request->trip->requester_id === auth()->id() && $request->trip->status !== 'completed')
                                <form method="POST" action="{{route('requests.complete', [$group, $request])}}">
                                    @csrf
                                    <button class="btn btn-accent">Complete Trip</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
