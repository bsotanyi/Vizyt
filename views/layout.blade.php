<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') • {{ $_CONFIG['app_name'] }}</title>
    <link rel="stylesheet" href="{{ SITE_ROOT }}/assets/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="{{ SITE_ROOT }}/assets/lib/aos/aos.css">
    <link rel="stylesheet" href="{{ SITE_ROOT }}/assets/lib/SimpleDataTables/style.min.css">
    <link rel="stylesheet" href="{{ SITE_ROOT }}/assets/css/main.min.css?{{ microtime(true) }}">
</head>
<body>
    @include('components/header')
    @include('components/sidenav')

    <main id="content">
        @yield('content')
    </main>

    @include('components/footer')

    <script src="{{ SITE_ROOT }}/assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="{{ SITE_ROOT }}/assets/lib/lucide/lucide.min.js"></script>
    <script src="{{ SITE_ROOT }}/assets/lib/aos/aos.js"></script>
    <script src="{{ SITE_ROOT }}/assets/lib/SimpleDataTables/simple-datatables.js"></script>
    <script src="{{ SITE_ROOT }}/assets/lib/chartjs/chart.umd.min.js"></script>
    <script src="{{ SITE_ROOT }}/assets/lib/PristineJS/pristine.js"></script>
    <script src="{{ SITE_ROOT }}/assets/js/main.js?{{ microtime(true) }}"></script>
    @stack('scripts')
</body>
</html>