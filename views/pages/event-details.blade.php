@extends('layout')

@section('title', $title)

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
                        <a href="{{ $item['url'] }}" target="_blank" class="wl-content">{{ $item['name'] }}</a>
                    @endforeach
                </div>
            @else
                <p>Wishilst is not visible at this event.</p>
            @endif
        </div>
    </div>
    @if (!empty($event['is_public']))
        <div class="parent grid">
            <a href="" class="btn bg-primary">I want to participate</a>
        </div>    
    @endif
    <div class="parent grid-xl-fill">
        <div>
            <table id="invitees_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event['invites'] as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td style="text-transform: capitalize;">
                                {!! $invites[$item['email']]['response'] ?? '<i>No answer yet</i>' !!}
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
@endsection

@push('scripts')
    <script>
        const users_table = new simpleDatatables.DataTable('#invitees_table', {
            layout: {
                top: '',
                bottom: '{info}{pager}{select}',
            }
        });
    </script>
@endpush