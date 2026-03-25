<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ('Rate your lift') }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-lg">
            <!-- Create Review Card -->
            <div class="card bg-base-200 shadow-lg border border-base-300">
                <div class="card-body">
                    <h1 class="card-title text-3xl">Rate your lift</h1>
                    <p>Your Driver for this trip was: <a href="{{route('profile.show',$trip->driver)}}" class="link link-hover"> {{$trip->driver->name}} </a></p>
                    <p class="text-sm">Please rate and leave a comment on your past trip</p>
                    <form method="POST" action="{{ route('reviews.store', $trip) }}" class="mt-4">
                        @csrf
                        <!-- Star Rating -->
                        <div>
                            <x-input-label for="rating" :value="('Rating')" />
                            <div class="rating">
                                <input type="radio" name="rating" value="1" class="mask mask-star-2 bg-green-500" aria-label="1 star" />
                                <input type="radio" name="rating" value="2" class="mask mask-star-2 bg-green-500" aria-label="2 star" />
                                <input type="radio" name="rating" value="3" class="mask mask-star-2 bg-green-500" aria-label="3 star" />
                                <input type="radio" name="rating" value="4" class="mask mask-star-2 bg-green-500" aria-label="4 star" />
                                <input type="radio" name="rating" value="5" class="mask mask-star-2 bg-green-500" aria-label="5 star" />
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                        </div>

                        <!-- Comment-->
                        <div class="mt-4">
                            <x-input-label for="comment" :value="('Comment')" />
                            <textarea id="comment" name="comment" class="textarea textarea-lg textarea-primary w-full" placeholder="Type your comment here..">{{old('comment')}}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>



                        <!-- Cancel / Submit -->
                        <div class="flex items-center justify-end mt-4 gap-5">
                            <a class="btn btn-outline btn-error btn-md" href="{{ route('trips.show', $trip) }}">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary btn-md">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
