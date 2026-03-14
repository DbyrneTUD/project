<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Lift Request') }}
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
                    <div><span class="font-semibold">Driver: </span> {{$trip->driver->name}} </div>
                    <div><span class="font-semibold">Passenger: </span> {{$trip->requester->name}} </div>
                    <div><span class="font-semibold">Route: </span>  {{$request->origin}} - {{$request->destination}} </div>
                    <div><span class="font-semibold" >Earliest Departure:</span> {{date('D j M Y, H:i', strtotime($request->earliest_departure))}}</div>
                    <div><span class="font-semibold" >Latest Departure:</span> {{date('D j M Y, H:i', strtotime($request->latest_departure))}}</div>


                    <div class="flex gap-3 justify-end">
                        @if (($request->trip->driver_id === auth()->id() || $request->requester_id === auth()->id()) && $request->trip->status !== 'completed')
                            <form method="POST" action="{{route('requests.cancel', [$group, $request])}}">
                                @csrf
                                <button class="btn btn-error">Cancel Trip</button>
                            </form>
                        @endif

                        @if ($request->trip->driver_id === auth()->id() && $request->trip->status !== 'completed')
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

</x-app-layout>
