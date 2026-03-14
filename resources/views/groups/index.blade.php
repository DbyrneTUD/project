<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Groups List') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10 pb-10">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Groups</h1>

                <a href="{{route('groups.create')}}" class="btn btn-primary btn-wide">Create Group</a>
            </div>
            <form method="GET" action="{{route('groups.index')}}">
                <div>
                    <input name="q" value="{{request('q')}}" placeholder="Search Groups..." class="px-4 py-2 w-full border border-base-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" />
                </div>
            </form>

            <div class="flex flex-col gap-10">
                @foreach($groups as $group)
                    <div class="card bg-base-200 shadow-lg border border-base-300 overflow-hidden">
                        <div class="flex">
                            @if($group->photo_path)
                                <div>
                                    <img src="{{asset('storage/' . $group->photo_path)}}" alt="{{$group->name}}" class="object-cover h-full w-60">
                                </div>
                            @endif
                            <div class="card-body space-y-6">
                                <div class="flex justify-between">
                                    <h2 class="card-title text-2xl">
                                        {{$group->name}}
                                    </h2>
                                    @if($group->created_by === auth()->id())
                                        <span class="badge badge-outline badge-primary h-8 font-semibold">
                                            Admin
                                        </span>
                                    @elseif(auth()->user()->groups->contains($group))
                                        <span class="badge badge-outline badge-primary h-8 font-semibold">
                                            Member
                                        </span>
                                    @endif
                                </div>

                                <p class="text-md">
                                    {{$group->description ?? 'No description provided for this group'}}
                                </p>
                                <p class="text-xs">
                                    Members: {{$group->members->count()}}
                                </p>

                                <div class="card-actions justify-end">
                                    @if (auth()->user()->groups->contains($group))
                                        <a href="{{route('groups.show', $group)}}" class="btn btn-accent btn-md">
                                            View Group
                                        </a>
                                    @else
                                        <form method="POST" action="{{route('groups.join', $group)}}">
                                            @csrf
                                            <button class="btn btn-primary btn-md">
                                                Join Group
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{$groups->links()}}
        </div>
    </div>
</x-app-layout>
