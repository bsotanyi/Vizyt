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
    <div class="parent grid">
        <div class="text-center">
            <p>You have been invited to</p>
            <h2><strong>{{ $event['name'] }}</strong></h2>
        </div>
        <div class="grid-xxl-fill">
            <div>
                <dl>
                    <dt>Hosted by:</dt>
                    <dd>{{ $event['creator'] }}</dd>
                    <dt>Event date and time:</dt>
                    <dd>{{ $event['datetime'] }}</dd>
                    <dt>Description:</dt>
                    <dd>{!! nl2br(e($event['description'])) !!}</dd>
                </dl>
                <a href="http://www.google.com/calendar/event?action=TEMPLATE&ctz=Europe/Belgrade&text={{ $event['name'] }}&dates={{ date('Ymd\THis', strtotime($event['datetime'])) }}/{{ date('Ymd\THis', strtotime($event['datetime'] . ' +3 hours')) }}&details={{ urlencode($event['description']) }}&location=&trp=false&sprop=&sprop=name:" class="btn bg-primary" target="_blank">Add to Google Calendar</a>
            </div>
            <div id="map"></div>
        </div>
    </div>
    <form class="parent grid-xxl-fill">
        <div>
            @if (!empty($event['wishlist_visible']))
                <p class="text-center">
                    <strong>{{ $event['creator'] }}'s wishlist</strong>
                </p>
                <div class="form-group">
                    <label for="selected_wishlist_item" class="form-label">I will bring:</label>
                    <select name="selected_wishlist_item" id="selected_wishlist_item" class="form-select js-wishlist js-input">
                        <option selected>Choose...</option>
                        @foreach ($event['wishlist'] as $item)
                            <option value="{{ $item['name'] }}" @if($event['selected_wishlist_item'] === $item['name']) selected @endif @if(!empty($item['taken'])) disabled @endif data-url="{{ $item['url'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                    <p class="js-wishlist-help hidden mt-2">You can buy this item here: <a target="_blank">Link</a></p>
                </div>
            @else
                No wishlist was provided for this event.
            @endif
        </div>
        <div>
            <div class="form-group grid center-items">
                <label for="response" class="form-label">My attendance</label>
                <select id="response" name="response" class="form-select js-input">
                    <option selected disabled>Choose...</option>
                    <option @if($event['response'] === 'yes') selected @endif value="yes">✅ I will be there</option>
                    <option @if($event['response'] === 'no') selected @endif value="no">❌ I can't go</option>
                    <option @if($event['response'] === 'undecided') selected @endif value="undecided">❓ I'm not sure yet</option>
                </select>
            </div>
        </div>
    </form>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-body">✅ Your response was saved successfully.</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/lib/leaflet/leaflet.js"></script>
    <script>
        qs('.js-wishlist').oninput = function() {
            let url = this.options[this.selectedIndex].dataset.url;
            qs('.js-wishlist-help').classList.remove('hidden');
            qs('.js-wishlist-help a').href = url;
        }

        for (let inp of qsa('.js-input')) {
            inp.oninput = function() {
                fetch(`/events/invite-save?token={{ $token }}&${this.name}=${this.value}`).then(data => {
                    const toast = new bootstrap.Toast(qs('#liveToast'));
                    toast.show();
                })
            }
        }

        var map_instance;
        var latitude = {{ $event['latitude'] }};
        var longitude = {{ $event['longitude'] }};

        map_instance = L.map('map').setView([latitude, longitude], 14);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map_instance);

        var marker = L.marker([latitude, longitude]).addTo(map_instance);
    </script>
@endpush