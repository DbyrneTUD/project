<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Create Group') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-lg">

            <div class="card bg-base-200 shadow-lg border border-base-300">
                <div class="card-body">
                    <h1 class="card-title text-3xl">Create a group</h1>
                    <p class="text-sm">Fill in the below fields to create your own group</p>
                    <form method="POST" action="{{ route('groups.store') }}" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="('Group Name')" />
                            <x-text-input id="name" class="p-1 block mt-1 w-full h-8 bg-base-100" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Destination-->
                        <div class="mt-4">
                            <x-input-label for="description" :value="('Description (Optional)')" />
                            <x-text-input id="description" class="p-1 block h-8 mt-1 w-full bg-base-100" type="text" name="description" :value="old('description')" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Group photo upload-->
                        <div class="mt-4">
                            <x-input-label for="photo" :value="('Group Photo (Optional)')" />
                            <input type="file" name="photo" class="file-input file-input-accent w-full bg-base-100">
                            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-5">
                            <a class="btn btn-outline btn-error btn-md" href="{{ route('groups.index') }}">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary btn-md">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
