@extends('test')

@section('main')
    <h1>{{ $title }}</h1>
    {{-- this is a comment yes yes yes --}}
    <p>
        @if (true == false)
            <p>true is false</p>
        @elseif ($_GET['not_sure'] ?? false)
            <p>i don't know</p>
        @else
            true is not false
        @endif
    </p>
    <p>
        @forelse ($_GET as $key => $value)
            <b style="display: inline-block; margin: 5px; background: lime">$_GET['{{ $key }}'] => {{ $value }}</b>
        @empty
            <b>no get parameters found sorry</b>
        @endforelse
    </p>
    <table border="1">
        @foreach ($_SERVER as $key => $item)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $item }}</td>
            </tr>
        @endforeach
    </table>
@endsection