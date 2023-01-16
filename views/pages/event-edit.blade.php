@extends('layout')

@section('title', $title)

@push('styles')
    <link rel="stylesheet" href="/assets/lib/leaflet/leaflet.min.css">
    <style>
        #map {
            cursor: crosshair;
            border-radius: .375rem;
        }
    </style>
@endpush

@section('content')
    <div class="parent grid">
        <div>
            <form action="/events/save/{{ $model['id'] ?? 'new' }}" method="post" class="js-validate">
                <h3>
                    Editing {{ empty($model) ? 'new event' : 'event #'.$model['id'] }}
                    <div class="form-group float-end">
                        <select id="template-select" class="form-select">
                            <option selected disabled>Choose template...</option>
                            @foreach ($templates as $id => $template)
                                <option value="{{ $id }}">{{ $template['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </h3>
                <hr>
                <div class="grid-xl-fill">
                    <div class="grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Event name *</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $model['name'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description *</label>
                            <textarea class="form-control" id="description" rows="7" name="description" required>{{ $model['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label class="form-label" for="invites">Invites *</label>
                            <textarea class="form-control" id="invites" rows="5" name="invites" placeholder="{{ 'user@example.com,John Doe' . PHP_EOL . 'user2@example.org,Bob Terry' }}" required>@foreach($model['invites'] ?? [] as $invite){{ $invite['email'].','.$invite['name'].PHP_EOL }}@endforeach</textarea>
                        </div>
                        <br>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" @if(!empty($model['is_active'])) checked @endif>
                            <label class="form-check-label" for="is_active">Event is active</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_public" id="is_public" value="1" @if(!empty($model['is_public'])) checked @endif>
                            <label class="form-check-label" for="is_public">Event is public</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_commentable" id="is_commentable" value="1" @if(!empty($model['is_commentable'])) checked @endif>
                            <label class="form-check-label" for="is_commentable">People can comment after the event</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="wishlist_visible" id="wishlist_visible" value="1" @if(!empty($model['wishlist_visible'])) checked @endif>
                            <label class="form-check-label" for="wishlist_visible">My wishlist is visible at this event</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_template" id="is_template" value="1" @if(!empty($model['is_template'])) checked @endif>
                            <label class="form-check-label" for="is_template">Save event as template</label>
                        </div>
                    </div>
                </div>
                <div class="grid-md-fill mt-3">
                    <div class="form-group">
                        <label class="form-label" for="datetime">Event date *</label>
                        <input type="datetime-local" class="form-control" id="datetime" name="datetime" required value="{{ date('Y-m-d\TH:i:s', strtotime($model['datetime'] ?? date('Y-m-d H:i:s'))) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="reminder">Reminder email date *</label>
                        <input type="datetime-local" class="form-control" id="reminder" name="reminder" required value="{{ date('Y-m-d\TH:i:s', strtotime($model['reminder'] ?? date('Y-m-d H:i:s'))) }}">
                    </div>
                </div>
                <div id="map" style="height: 40vw" class="mt-3"></div>
                <br>
                <input type="hidden" name="latitude" id="latitude" value="{{ $model['latitude'] ?? '' }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ $model['longitude'] ?? '' }}">
                <button class="btn bg-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/lib/leaflet/leaflet.js"></script>
    <script>
        var templates = @json($templates);
        var map_instance;
        var latitude = {{ !empty($model['latitude']) ? $model['latitude'] : $_CONFIG['latitude'] }};
        var longitude = {{ !empty($model['longitude']) ? $model['longitude'] : $_CONFIG['longitude'] }};
        
        qs('#latitude').value = latitude;
        qs('#longitude').value = longitude;

        qs('#template-select').oninput = function(e) {
            var t = templates[this.value];
            qs('#name').value = t.name;
            qs('#description').value = t.description;
            qs('#invites').value = t.invites;
            var ll = {
                lat: t.latitude,
                lng: t.longitude,
            };
            marker.setLatLng(ll);
            qs('#latitude').value = ll.lat;
            qs('#longitude').value = ll.lng;
            map_instance.setView([ll.lat, ll.lng], 14);
        }

        map_instance = L.map('map').setView([latitude, longitude], 14);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map_instance);

        map_instance.on('click', (e) => {
            marker.setLatLng(e.latlng);
            qs('#latitude').value = e.latlng.lat;
            qs('#longitude').value = e.latlng.lng;
        })

        var marker = L.marker([latitude, longitude]).addTo(map_instance);
        // marker.bindPopup('<p>Hello World.</p>').openPopup();
    </script>
@endpush