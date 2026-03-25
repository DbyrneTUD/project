<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Trip Chat') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-10">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold">Trip Chat</h1>
                <a href="{{route('trips.show', $trip)}}" class="btn">Back</a>
            </div>
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body space-y-1">
                    <!-- Trip Chat Messages -->
                    @foreach($messages as $message)
                        <!-- Align logged in user mesages -->
                        <div class="chat {{$message->user_id === auth()->id() ? 'chat-end' : 'chat-start'}}">
                                <div class="chat-header">
                                    <a href="{{route('profile.show',$message->user_id)}}" class="link link-hover"> {{$message->user->name}} </a>
                                    <time class="text-xs opacity-50">
                                        {{$message->created_at->diffForHumans()}}
                                    </time>
                                </div>
                            <!-- change colour of chat bubble for logged in user-->
                                <div class="chat-bubble {{$message->user_id === auth()->id() ? 'chat-bubble-info' : 'chat-bubble-success'}}">
                                    {{$message->message}}
                                </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <!-- Send message form-->
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body">
                        <form method="POST" action="{{route('messages.store', $trip) }}">
                            @csrf
                            <x-input-label for="message" :value="('Message')" />
                            <textarea id="message" name="message" class="textarea textarea-lg textarea-primary w-full" placeholder="Type your message here..">{{old('message')}}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />

                            <div class="flex justify-end mt-4">
                                <button class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
