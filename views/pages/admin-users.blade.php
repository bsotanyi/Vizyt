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
                @for ($i = 0; $i < 30; $i++)
                    <tr>
                        <td>John Doe</td>
                        <td>user@example.org</td>
                        <td>5</td>
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
                @endfor
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        const users_table = new simpleDatatables.DataTable('#users_table', {
            layout: {
                top: '',
                bottom: '{info}{pager}{select}',
            }
        });
    </script>
@endpush