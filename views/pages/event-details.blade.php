@extends('layout')

@section('title', $title)

@section('content')
    <h2>The Big New Year Party</h2>
    <div class="parent grid-xl-fill">
        <div>
            <small class="e-owner">Created 9 hours ago by <span>Tolcser Adam</span></small>
            <p>(description)Vitae optio, rerum nobis magni ullam quasi maxime voluptatem, quibusdam minus laudantium quia, enim vel ut consequatur laborum vero hic blanditiis perferendis unde voluptas sunt. Aperiam expedita a ut dolorum.</p> 
            <small>2 days until the start of the event</small>
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
    <div class="parent grid-xl-fill">
        <div>
            <p><b>Tolcser Adam</b></p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
            <small>31 minutes ago</small>
        </div>
        <div>
            <p><b>Tolcser Adam</b></p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
            <small>31 minutes ago</small>
        </div>
        <div>
            <p><b>Tolcser Adam</b></p>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Maxime mollitia quia vero sint, quas dolore at culpa? Quam blanditiis earum provident voluptas, laboriosam eveniet dicta. Repudiandae error dolorum debitis nam!</p>
            <small>31 minutes ago</small>
        </div>
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
    </div>
    <div class="parent grid-xxl-fill">
        <div id="newComment">
            <form action="/events/comment" method="post">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="comment"></textarea>
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