<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-content leading-tight">
            {{ ("Profile") }}
        </h2>
    </x-slot>
    <div class="bg-base-100 min-h-screen">
        <div class="mx-auto max-w-4xl space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold">{{ ("$user->name") }}</h1>
                @if(auth()->id() === $user->id)
                    <a href="{{route('profile.edit')}}" class="btn btn-outline">Edit Profile</a>
                @endif
            </div>
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body flex flex-row justify-start gap-10">
                    <!-- Display profile photo -->
                    <div>
                        @if($user->photo_path)
                            <div>
                                <img src="{{asset('storage/' . $user->photo_path)}}" alt="{{$user->name}}" class="object-cover h-60 w-50 rounded ">
                            </div>
                        @else
                            <p> No profile photo uploaded yet</p>
                        @endif
                    </div>
                    <!-- Display profile details -->
                    <div class="flex flex-col gap-5 mb-5">
                        <div ><span class="text-lg font-semibold">Name:</span> {{$user->name}}</div>
                        <div ><span class="text-lg font-semibold">Joined:</span> {{$user->created_at->format('j M Y')}}</div>
                        <div>
                            @if($user->bio)
                                <p><span class="text-lg font-semibold">Bio:</span> {{$user->bio}}</p>
                            @else
                                <p><span class="text-lg font-semibold">Bio:</span> No bio added yet</p>
                            @endif
                        </div>

                        <div><span class="text-lg font-semibold">Lifts given:</span> {{$liftsGiven}}</div>
                        <div><span class="text-lg font-semibold">Lifts taken:</span> {{$liftsTaken}}</div>

                        <div><span class="text-lg font-semibold">Average Rating:</span> {{round($avgRating, 1) ?? 'No rating yet'}}</div>
                        <div><span class="text-lg font-semibold">Ratings Received:</span> {{$reviewsReceivedCount ?? '0'}}</div>

                    </div>


                </div>
            </div>
            <!-- Display Driver Reviews -->
            <div class="card bg-base-200 shadow-lg border border-base-300 ">
                <div class="card-body">
                        <h3 class="font-semibold text-xl">Reviews</h3>
                        @foreach($reviews as $review)
                            <div class="card bg-base-100 shadow-lg border border-base-300 my-1 ">
                                <div class="card-body">
                                    <div class="flex justify-between">
                                        <span class="font-semibold">Reviewed by:  <a href="{{route('profile.show',$review->reviewer)}}" class="link link-hover"> {{$review->reviewer->name}} </a></span>
                                        {{$review->created_at->format('j M Y')}}
                                        <div class="rating rating-sm">
                                            <div class="mask mask-star-2 bg-green-500" aria-label="1 star" @if ($review->rating === 1 )aria-current="true" @endif></div>
                                            <div class="mask mask-star-2 bg-green-500" aria-label="2 star" @if ($review->rating === 2 )aria-current="true" @endif></div>
                                            <div class="mask mask-star-2 bg-green-500" aria-label="3 star" @if ($review->rating === 3 )aria-current="true" @endif></div>
                                            <div class="mask mask-star-2 bg-green-500" aria-label="4 star" @if ($review->rating === 4 )aria-current="true" @endif></div>
                                            <div class="mask mask-star-2 bg-green-500" aria-label="5 star" @if ($review->rating === 5 )aria-current="true" @endif></div>
                                        </div>
                                    </div>
                                    <!-- Display comment -->
                                    @if($review->comment)
                                        <p class="mt-3">{{$review->comment}}</p>
                                    @else
                                        <p class="mt-3">No comment left for this review</p>
                                    @endif
                                    <!-- Display reply / form-->
                                    @if($review->reply)
                                        <div class="border border-base-300 p-3 mt-3 ">
                                            <p class="font-semibold opacity-75">Reply from driver:  <a href="{{route('profile.show',$review->driver)}}" class="link link-hover"> {{$review->driver->name}} </a></p>
                                            <p class="mt-3 opacity-75">{{$review->reply}}</p>
                                        </div>
                                    @elseif(auth()->id() === $review->driver_id)
                                         <form method="POST" action="{{ route('reviews.reply', $review->trip) }}" class="mt-2">
                                            @csrf
                                            <!-- Reply-->
                                            <div class="mt-4">
                                                <x-input-label for="reply" :value="('Reply')" />
                                                <textarea id="reply" name="reply" class="textarea textarea-lg textarea-accent w-full" placeholder="Type your reply here..">{{old('reply')}}</textarea>
                                                <x-input-error :messages="$errors->get('reply')" class="mt-2" />
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-sm mt-2">Submit Reply</button>
                                         </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    {{$reviews->links()}}

                    @if ($reviews->isEmpty())
                        <p>No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
