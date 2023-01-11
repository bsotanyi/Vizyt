@extends('layout')

@section('title', $title)

@section('content')
    <div class="parent grid">
        <div>
            <form action="/user/save" method="post" class="js-validate">
                <h3>Editing {{ empty($event) ? 'new event' : 'event #'.$event['id'] }}</h3>
                <hr>
                <div class="grid-xl-fill">
                    <div class="grid">
                        <div class="form-group">
                            <label class="form-label" for="name">Event name *</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $model['name'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description *</label>
                            <textarea class="form-control" id="description" rows="5" name="description" required>{{ $model['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label class="form-label" for="invites">Invites *</label>
                            <textarea class="form-control" id="invites" rows="5" name="invites" placeholder="{{ 'user@example.com,John Doe' . PHP_EOL . 'user2@example.org,Bob Terry' }}" required>{{ $model['invites'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <br>
                <button class="btn bg-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection