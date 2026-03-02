<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Lift Request') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Group: {{$group->name}}</h1>

                <a href="{{route('groups.show', $group)}}" class="btn">Back</a>
            </div>
                <div class="card bg-base-200 shadow-lg border border-base-300 ">
                        <div class="card-body space-y-6">
                            <div class="flex justify-between">
                                <div class="font-semibold text-2xl">
                                    {{$request->origin}} - {{$request->destination}}
                                </div>
                                <x-status-badge :status="$request->status" />
                            </div>
                            <div>
                                <span class="text-lg font-semibold" >Earliest Departure:</span> {{date('D j M Y, H:i', strtotime($request->earliest_departure))}}
                            </div>
                            <div>
                                <span class="text-lg font-semibold" >Latest Departure:</span> {{date('D j M Y, H:i', strtotime($request->latest_departure))}}
                            </div>
                            <div>
                                <span class="text-lg font-semibold" >Requested by:</span> {{$request->requester->name}}
                            </div>
                            <div class="flex flex-col justify-between gap-5">
                                @if (! $request->trip)
                                    @if (auth()->id() !== $request->requester_id)
                                        <form method="POST" action="{{route('requests.accept', [$group, $request])}}">
                                            @csrf
                                            <button class="btn btn-primary">Accept Request</button>
                                        </form>
                                    @else
                                        <p class="text-lg font-semibold">Waiting for a driver to accept your request</p>
                                        <div class="card-actions justify-end">
                                                <a href="{{route('requests.edit', [$group, $request])}}" class="btn btn-accent btn-outline">Edit Request</a>
                                                <form method="POST" action="{{route('requests.destroy', [$group, $request])}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-error">Delete Request</button>
                                                </form>
                                        </div>
                                    @endif
                                @else
                                    @if ($request->trip->status === 'accepted')
                                        <p class="text-lg font-semibold">This request has been accepted</p>
                                    @else
                                        <p class="text-lg font-semibold">This request has been completed</p>
                                    @endif
                                    <p><span class="text-lg font-semibold" >Driver:</span> {{$request->trip->driver->name}}</p>
                                    <div class="card-actions justify-end gap-5">

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
                                @endif
                            </div>
                        </div>
                </div>
        </div>
    </div>
</x-app-layout>
