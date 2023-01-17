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
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user['firstname'] . ' ' . $user['lastname'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    @if (array_key_exists($user['id'], $created_count))
                        <td>{{ $created_count[$user['id']] }}</td>
                    @else
                        <td>0</td>
                    @endif
                    <td>{{ $attended_count[$user['id']] ?? 0 }}</td>
                    <td class="nowrap">
                        @if ($user['active'] == 1)
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
        users_table.on('datatable.page', () => {
            lucide.createIcons();
        })
    </script>
@endpush