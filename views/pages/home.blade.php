@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent grid-xl-fill">
        @if (!empty($_SESSION['nearby']))
        <div id="geoloc">
            <button class="btn btn-primary" onclick="getLocation()">Nearby events are being shown</button>
        </div>
        @else
        <div id="geoloc">
            <button class="btn btn-primary">See nearby events</button>
        </div>
        @endif
        <div>
            <small>Aspernatur, nisi?</small>
            <h3>Earum, a? Libero?</h3>
            <p>Vitae optio, rerum nobis magni ullam quasi maxime voluptatem, quibusdam minus laudantium quia, enim vel ut consequatur  laborum vero hic blanditiis perferendis unde voluptas sunt. Aperiam expedita a ut dolorum.</p>
        </div>
    </div>
    @if (!empty($_SESSION['nearby']))
    <div class="parent grid-xl-fill">
        <div id="event-thumbnail">
            <small>There are {{ count($_SESSION['nearby']) }} public events near your location</small>
            <h3>Public events</h3>
            @foreach ($_SESSION['nearby'] as $item)
                <a href="/events/details">
                    <div class="list-event">
                        <small class="e-owner">Created {{ time_elapsed($item['created_date']) }} by <span>{{ getUserNameFromId($item['user_id']) }}</span></small>
                        <h3 class="e-name">{{ $item['name'] }}</h3>
                        <div id="e-links">
                            <i icon-name="users"></i><small> participants</small>
                            <i icon-name="calendar"></i><small class="e-comments">{{ $item['datetime'] }}</small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
        
    @endif
    
@endsection
@push('scripts')
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                geoloc.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            console.log(latitude);
            console.log(longitude);
            window.location.href = "/events/nearby/" + position.coords.latitude + "/" + position.coords.longitude;

        }
    </script>
@endpush