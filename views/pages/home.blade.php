@extends('layout')

@section('title', $title)

@push('styles')
    <link rel="stylesheet" href="/assets/lib/leaflet/leaflet.min.css">
    <style>
        #map {
            border-radius: .375rem;
            min-height: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="parent headline">
        <div>
            <h1>The <span class="color-primary">#1</span> event management software</h1>
            <p class="subtitle">For all your planning needs</p>
        </div>
    </div>
    <div class="parent grid-xl-fill">
        @if (empty($_SESSION['nearby']))
        <div id="geoloc">
            <button class="btn bg-primary" onclick="getLocation()">See nearby events</button>
        </div>
        @else
        <div id="event-thumbnail">
            <small>There are {{ count($_SESSION['nearby']) }} public events near your location</small>
            <h3>Public events</h3>
            @foreach ($_SESSION['nearby'] as $item)
                <a href="/events/{{ $item['id'] }}">
                    <div class="list-event">
                        <small class="e-owner">Created {{ time_elapsed($item['created_date']) }} by <span>{{ $item['fname'] . ' ' . $item['lname'] }}</span></small>
                        <h3 class="e-name">{{ $item['name'] }}</h3>
                        <div id="e-links">
                            <i icon-name="users"></i><small>{{ $item['invite_count'] ?? 0 }} participants</small>
                            <i icon-name="calendar"></i><small class="e-comments">{{ $item['datetime'] }}</small>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @endif
        <div>
            <h3>Do you have an upcoming party?</h3>
            <p>Vizyt is <u>the</u> event management tool that enables users to organize events online without much hassle.<br>It is designed for people who want to see nearby events based on their location and get reminders for upcoming events. Vizyt also allows users to add a custom wishlist to their events.</p>
            <p class="text-center">
                <a href="#" class="color-primary" data-bs-toggle="modal" data-bs-target="#registerModal">Register now</a> and start organizing!
            </p>
        </div>
    </div>
    @if (!empty($_SESSION['nearby']))
        <div class="parent grid">
            <div id="map"></div>
        </div>
    @endif
    
@endsection
@push('scripts')
    <script src="/assets/lib/leaflet/leaflet.js"></script>
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

        @if (!empty($_SESSION['nearby']))

            var map_instance;
            var latitude = {{ $_CONFIG['latitude'] }};
            var longitude = {{ $_CONFIG['longitude'] }};

            map_instance = L.map('map').setView([latitude, longitude], 14);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map_instance);

            var nearby = @json($_SESSION['nearby']);
            for (item of nearby) {
                var marker = L.marker([item.latitude, item.longitude]).addTo(map_instance);
                marker.bindPopup(`<p>${item.name}</p>`);
            }

        @endif
    </script>
@endpush