@extends('layout')

@section('title', $title)

@section('content')
    <h2>{{ $data['name'] }}</h2>
    <div class="parent grid-xl-fill">
        <div>
            <small class="e-owner">Created {{ time_elapsed($data['created_date']) }} by <span>{{ $data['fname'] . ' ' . $data['lname'] }}</span></small>
            <p>{{ $data['description'] }}</p> 
            <small>{{ time_until($data['datetime']) }} until the start of the event</small>
        </div>
        <div>
            <small>Wishlist</small>
            <div class="parent  wishlist">
                <a href="https://placeholder.com" target="_blank" class="wl-content">Minecraft Java Edition</a>
                <a href="https://placeholder.com" target="_blank" class="wl-content">Minecraft</a>
                <a href="https://placeholder.com" target="_blank" class="wl-content">Fortnite Battle-Royale</a>
                <a href="https://placeholder.com" target="_blank" class="wl-content">GTA5</a>
                <a href="https://placeholder.com" target="_blank" class="wl-content">Logitech G Pro Wireless</a>
                <a href="https://placeholder.com" target="_blank" class="wl-content">Logitech G Pro Wireless</a>
            </div>
        </div>
    </div>
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
                    @for ($i = 0; $i < 30; $i++)
                        @if ($i % 3 == 0)
                        <tr>
                            <td>Joe Biden</td>
                            <td>Undecided</td>
                        </tr>
                        @elseif ($i % 5 == 0)
                        <tr>
                            <td>Joska Pista</td>
                            <td>Will Go</td>
                        </tr>
                        @else
                        <tr>
                            <td>Elon Musk</td>
                            <td>Won't Go</td>
                        </tr>
                        @endif
                    @endfor
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
    
        {{-- <div>
            <div>
                <p><b>Tolcser Adam</b></p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
                <small>31 minutes ago</small>
                <hr>
            </div>
            <div>
                <p><b>Tolcser Adam</b></p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
                <small>31 minutes ago</small>
                <hr>
            </div>
            <div>
                <p><b>Tolcser Adam</b></p>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
                <small>31 minutes ago</small>
            </div>
        </div> --}}
    
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