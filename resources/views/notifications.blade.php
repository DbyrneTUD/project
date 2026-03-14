<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Notifications') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <h1 class="text-2xl font-bold">Notifications</h1>
            <div class="flex flex-col gap-6">
                @foreach($notifications as $notification)

                    <div class="card bg-base-200 shadow-lg border border-base-300 ">
                         <div class="card-body">
                             <div class="flex justify-between items-center">
                                 <div class="flex items-center gap-3">
                                    @if(!$notification->read_at)
                                         <span class="badge badge-primary badge-sm">New</span>
                                    @endif
                                    <p>{{$notification->data['message']}}</p>
                                 </div>

                                 <a href="{{$notification->data['url']}}" class="btn btn-accent btn-sm">View</a>
                             </div>
                         </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>


</x-app-layout>
