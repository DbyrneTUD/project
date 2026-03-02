<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Edit Lift Request') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-lg">

            <div class="card bg-base-200 shadow-lg border border-base-300">
                <div class="card-body">
                    <h1 class="card-title text-3xl">Edit Lift Request</h1>
                    <p class="text-sm">Update below fields to update your request</p>
                    <form method="POST" action="{{ route('requests.update', [$group, $request]) }}" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <!-- Origin -->
                        <div>
                            <x-input-label for="origin" :value="('Origin')" />
                            <x-text-input id="origin" class="p-1 block mt-1 w-full h-8 bg-base-100" type="text" name="origin" :value="$request->origin" required autofocus />
                            <x-input-error :messages="$errors->get('origin')" class="mt-2" />
                        </div>

                        <!-- Destination-->
                        <div class="mt-4">
                            <x-input-label for="destination" :value="('Destination')" />
                            <x-text-input id="destination" class="p-1 block h-8 mt-1 w-full bg-base-100" type="text" name="destination" :value="$request->destination" />
                            <x-input-error :messages="$errors->get('destination')" class="mt-2" />
                        </div>

                        <!-- Earliest Departure-->
                        <div class="mt-4">
                            <x-input-label for="earliest_departure" :value="('Earliest Departure')" />
                            <x-text-input id="earliest_departure" class="p-1 block h-8 mt-1 w-full bg-base-100" type="datetime-local" name="earliest_departure" :value="$request->earliest_departure" />
                            <x-input-error :messages="$errors->get('earliest_departure')" class="mt-2" />
                        </div>

                        <!-- Latest Departure-->
                        <div class="mt-4">
                            <x-input-label for="latest_departure" :value="('Latest Departure')" />
                            <x-text-input id="latest_departure" class="p-1 block h-8 mt-1 w-full bg-base-100" type="datetime-local" name="latest_departure" :value="$request->latest_departure" />
                            <x-input-error :messages="$errors->get('latest_departure')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-5">
                            <a class="btn btn-outline btn-error btn-md" href="{{ route('requests.show', [$group, $request]) }}">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary btn-md">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
