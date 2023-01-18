@extends('layout')

@section('title', $title)

@section('content')
    <a href="/events/edit/new" class="btn bg-primary float-end">
        <i icon-name="calendar-plus"></i>
        Create new event
    </a>
    <small>Your personal</small>
    <h2>Events</h2>
    <div class="parent grid">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event name</th>
                        <th>Date</th>
                        <th>Invitees</th>
                        <th class="nowrap">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['datetime'] }}</td>
                            <td>{{ count(json_decode($item['invites'] ?? '[]', true)) }}</td>
                            <td class="nowrap">
                                <a href="/events/{{ $item['id'] }}" class="btn btn-warning" role="button">
                                    <i icon-name="eye"></i> Details
                                </a>
                                <a href="/events/pdf/{{ $item['id'] }}" class="btn btn-secondary" role="button" target="_blank">
                                    <i icon-name="printer"></i> PDF
                                </a>
                                <a href="/events/qr/{{ $item['id'] }}" class="btn btn-dark" role="button" target="_blank">
                                    <i icon-name="qr-code"></i> QR
                                </a>
                                <a href="/events/edit/{{ $item['id'] }}" class="btn btn-light" role="button">
                                    <i icon-name="edit"></i> Edit
                                </a>
                                <a href="/events/delete/{{ $item['id'] }}" class="btn btn-danger" role="button" onclick="return confirm('Are you sure you want to delete this item?')">
                                    <i icon-name="trash-2"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">You have no active events.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <h2>Archive</h2>
    <div class="parent grid mt-3">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event name</th>
                        <th>Date</th>
                        <th>Invitees</th>
                        <th class="nowrap">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($archive as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['datetime'] }}</td>
                            <td>{{ count(json_decode($item['invites'] ?? '[]', true)) }}</td>
                            <td class="nowrap">
                                <a href="/events/{{ $item['id'] }}" class="btn btn-warning" role="button">
                                    <i icon-name="eye"></i> Details
                                </a>
                                <a href="#" class="btn btn-secondary" role="button">
                                    <i icon-name="printer"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Your archive is empty.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection