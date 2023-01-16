@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent headline">
        <div>
            <h1>The <span class="color-primary">#1</span> event management software</h1>
            <p class="subtitle">For all your planning needs</p>
        </div>
    </div>
    <div class="parent grid-xl-fill">
        @if (!empty($_SESSION['nearby']))
        <div id="geoloc">
            <button class="btn bg-primary" onclick="getLocation()">Nearby events are being shown</button>
        </div>
        @else
        <div id="geoloc">
            <button class="btn bg-primary">See nearby events</button>
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
    <div class="parent grid-xl-fill">
        <div id="event-thumbnail">
            <small>There are {{ count($_SESSION['nearby']) }} public events near your location</small>
            <h3>Public events</h3>
            @foreach ($_SESSION['nearby'] as $item)
                <a href="/events/{{ $item['id'] }}">
                    <div class="list-event">
                        <small class="e-owner">Created {{ time_elapsed($item['created_date']) }} by <span>{{ $item['fname'] . ' ' . $item['lname'] }}</span></small>
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