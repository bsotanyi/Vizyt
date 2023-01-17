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
    <h2>{{ $event['name'] }}</h2>
    <div class="parent grid-xl-fill">
        <div>
            <small class="e-owner">Created {{ time_elapsed($event['created_date']) }} by <span>{{ $event['fname'] . ' ' . $event['lname'] }}</span></small>
            <p>{{ $event['description'] }}</p> 
            <small>{{ time_until($event['datetime']) }} until the start of the event</small>
        </div>
        <div>
            <small>Wishlist</small>
            @if (!empty($event['wishlist_visible']))
                <div class="parent wishlist">
                    @foreach ($event['wishlist'] as $item)
                        @if ($item['taken'] ?? false)
                            <a href="{{ $item['url'] }}" target="_blank" class="wl-content" data-bs-toggle="tooltip" data-bs-title="Someone already brings this item" data-bs-placement="bottom"><s>{{ $item['name'] }}</s></a>
                        @else
                            <a href="{{ $item['url'] }}" target="_blank" class="wl-content">{{ $item['name'] }}</a>
                        @endif
                            
                    @endforeach
                </div>
            @else
                <p>Wishlist is not visible at this event.</p>
            @endif
        </div>
    </div>
    <div class="parent grid">
        <div id="map"></div>
    </div>
    @if (!empty($event['is_public']))
        <div class="parent grid">
            <a class="btn bg-primary js-participate">I want to participate</a>
        </div>    
    @endif
    <div class="parent grid-xl-fill">
        <div>
            <table id="invitees_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invites as $invite)
                        <tr>
                            <td>{{ !empty($event['invites'][$invite['receiver_email']]) ? $event['invites'][$invite['receiver_email']]['name'] : 'Guest'  }}</td>
                            <td>{{ $invite['receiver_email'] }}</td>
                            <td style="text-transform: capitalize;">
                                {!! $invite['response'] ?? '<i>No answer yet</i>' !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <h3>Comments</h3>
    @forelse ($comments as $comment)
        <div class="parent grid-xl-fill">
            <div>
                <p><b>{{ $comment['fname'] . ' ' . $comment['lname'] }}</b></p>
                <p>{{ $comment['comment'] }}</p>
                <small>{{ time_elapsed($comment['datetime']) }}</small>
            </div>
        </div>
    @empty
        <p>There are no comments for this event.</p>
    @endforelse
    
    <div class="parent grid-xxl-fill">
        <div id="newComment">
            <form action="/events/comment" method="post">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="comment"></textarea>
                    <input type="hidden" name="id" value="{{ $_GET['id'] }}">
                    <label for="floatingTextarea">Comment</label>
                </div>
                <input type="submit" value="Post" class="btn btn-primary">
            </form>
        </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-body js-toast-text"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/assets/lib/leaflet/leaflet.js"></script>
    <script>
        qs('.js-participate').onclick = () => {
            let email = prompt('Please enter your email:');
            if (email) {
                fetch(`/events/self-invite?event_id={{ $event['id'] }}&email=${email}`).then(data => data.text()).then(txt => {
                    qs('.js-toast-text').innerText = txt;
                    const toast = new bootstrap.Toast(qs('#liveToast'));
                    toast.show();
                });
            }
        }
        const users_table = new simpleDatatables.DataTable('#invitees_table', {
            layout: {
                top: '',
                bottom: '{info}{pager}{select}',
            }
        });

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