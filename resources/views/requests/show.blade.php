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

                <a href="{{url()->previous()}}" class="btn">Back</a>
            </div>
                <div class="card bg-base-200 shadow-lg border border-base-300 ">
                        <div class="card-body space-y-6">
                            <!-- Request Card Details -->
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
                                <span class="text-lg font-semibold" >Requested by:</span><a href="{{route('profile.show', $request->requester)}}" class="link link-hover"> {{$request->requester->name}} </a>
                            </div>
                            <div class="flex flex-col justify-between gap-5">

                                @if (! $request->trip)
                                    <!-- Accept button for everyone except requester -->
                                    @if (auth()->id() !== $request->requester_id)
                                        <form method="POST" action="{{route('requests.accept', [$group, $request])}}">
                                            @csrf
                                            <button class="btn btn-primary">Accept Request</button>
                                        </form>
                                    @else
                                        <!-- Requester buttons to edit or delete -->
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
                                    <!-- View Trip button for requester/driver -->
                                    <p><span class="text-lg font-semibold" >Driver:</span><a href="{{route('profile.show', $request->trip->driver)}}" class="link link-hover"> {{$request->trip->driver->name}} </a></p>
                                    <div class="card-actions justify-end gap-5">
                                        @if($request->trip->driver_id === auth()->id() || $request->requester_id === auth()->id())
                                            <a href="{{route('trips.show', $request->trip)}}" class="btn btn-accent btn-wide">View Trip</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div id="map" style="height: 400px;"></div>
                        </div>
                </div>
        </div>
    </div>


    <!-- Google Maps Load Route Script -->
    <script>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "{{config('services.google_maps.key')}}", v: "weekly"});

        // Initialize and add the map.
        let map;
        let mapPolylines = [];
        const center= { lat: 53.333, lng: -6.248 };

        // Initialize and add the map.
        async function initMap() {

            //  Request the needed libraries.
            const [{Map}, {Place}, {Route}] = await Promise.all([
                google.maps.importLibrary('maps'),
                google.maps.importLibrary('places'),
                google.maps.importLibrary('routes'),
            ]);

            map = new Map(document.getElementById('map'), {
                center: center,
                zoom:10,
                mapTypeControl: false,
                mapId: 'DEMO_MAP_ID',
            });

            // Define a routes request
            const request = {
                origin: "{{$request->origin}}, Ireland",
                destination: "{{$request->destination}}, Ireland",
                travelMode: 'DRIVING',
                fields: ['path'],
            };

            // Call computeRoutes to get the directions.
            const { routes, fallbackInfo, geocodingResults } = await Route.computeRoutes(request);
            // Use createPolylines to create polylines for the route.
            mapPolylines = routes[0].createPolylines();
            // Add polylines to the map.
            mapPolylines.forEach((polyline) => polyline.setMap(map));
            // Create markers to start and end points.
            const markers = await routes[0].createWaypointAdvancedMarkers();
            // Add markers to the map
            markers.forEach((marker) => marker.setMap(map));
            // Display the raw JSON for the result in the console.
            console.log(`Response:\n ${JSON.stringify(routes, null, 2)}`);
            // Fit the map to the path.
            fitMapToPath(routes[0].path);

            // Helper function to fit the map to the path.
            async function fitMapToPath(path) {
                const { LatLngBounds } = (await google.maps.importLibrary('core'));
                const bounds = new LatLngBounds();
                path.forEach((point) => {
                    bounds.extend(point);
                });
                map.fitBounds(bounds);
            }
        }
        initMap();

    </script>
</x-app-layout>
