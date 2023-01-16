@extends('layout')

@section('title', $title)

@section('content')
    <h2>Users</h2>
    <div class="parent grid">
        <table id="users_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Events created</th>
                    <th>Events attended</th>
                    <th class="nowrap">Operations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $users)
                <tr>
                    <td>{{ $users['firstname'] . ' ' . $users['lastname'] }}</td>
                    <td>{{ $users['email'] }}</td>
                    <td>{{ getCreatedEventCount($users['id']) }}</td>
                    <td>3</td>
                    <td class="nowrap">
                        @if (rand() % 2 == 0)
                            <a href="" class="btn bg-primary">
                                <i icon-name="x-octagon"></i> Block
                            </a>
                        @else
                            <a href="" class="btn bg-success">
                                <i icon-name="check-circle-2"></i> Allow
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        const users_table = new simpleDatatables.DataTable('#users_table', {
        });
    </script>
@endpush