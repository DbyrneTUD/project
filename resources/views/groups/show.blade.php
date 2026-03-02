<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ("$group->name") }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold">{{ ("$group->name") }}</h1>
                <a href="{{route('groups.index')}}" class="btn">Back</a>
            </div>
            <p class="text-lg">
                    {{$group->description ?? 'No description'}}
            </p>
            <div class="mx-auto mt-3 h-1 w-auto rounded bg-accent"></div>
            <div class="flex justify-between items-center mb-10" >
                <h2 class="text-2xl font-semibold">Lift Requests</h2>
                <a href="{{route('requests.create', $group)}}" class="btn btn-primary btn-md">Create Lift Request</a>
            </div>
            <div class="flex gap-2">
                <span class="text-lg font-semibold">Filter requests by:</span>
                <a href="{{route('groups.show', [$group, 'status'=> 'all'])}}" class="btn btn-sm {{$status === 'all' ? 'btn-primary' : 'btn-outline'}}">All</a>
                <a href="{{route('groups.show', [$group, 'status'=> 'open'])}}" class="btn btn-sm {{$status === 'open' ? 'btn-primary' : 'btn-outline'}}">Open</a>
                <a href="{{route('groups.show', [$group, 'status'=> 'accepted'])}}" class="btn btn-sm {{$status === 'accepted' ? 'btn-primary' : 'btn-outline'}}">Accepted</a>
                <a href="{{route('groups.show', [$group, 'status'=> 'completed'])}}" class="btn btn-sm {{$status === 'completed' ? 'btn-primary' : 'btn-outline'}}">Completed</a>
            </div>
            @if ($requests->isEmpty())
                <p class="text-lg">No Lift Requests Posted</p>
            @else
                @foreach($requests as $request)
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
                            <a href="{{route('requests.show', [$group, $request])}}" class="btn btn-accent btn-md">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
            {{$requests->links()}}
            <div class="flex justify-end pt-20 gap-5 pb-10">
            @if ($group->created_by === auth()->id())
                <a href="{{route('groups.edit', $group)}}" class="btn btn-accent btn-outline">Edit Group</a>
                <form method="POST" action="{{route('groups.destroy', $group)}}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-error">Delete Group</button>
                </form>
            @endif
            </div>
        </div>
    </div>
</x-app-layout>
