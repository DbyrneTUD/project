<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Trip History') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <h1 class="text-2xl font-bold">My Trips</h1>
            <div class="flex flex-col gap-6">
                @foreach($trips as $trip)

                    <div class="card bg-base-200 shadow-lg border border-base-300 ">
                        <div class="card-body">
                            <div class="flex justify-between items-center">
                                <x-status-badge :status="$trip->status" />
                                <span class="font-semibold"> {{$trip->liftRequest->origin}} - {{$trip->liftRequest->destination}}</span>
                                @if($trip->status === 'completed') Completed: {{date('D j M Y, H:i', strtotime($trip->completed_at))}} @endif
                                <a href="{{route('trips.show', $trip)}}" class="btn btn-accent btn-sm">View</a>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>


</x-app-layout>
