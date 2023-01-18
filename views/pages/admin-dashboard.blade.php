@extends('layout')

@section('title', $title)

@section('content')
    <small>Hello</small>
    <h2>Administrator</h2>
    <div class="parent grid">
        <div>
            <h3>Page views</h3>
            <small>by day (last 30 days)</small>
            <canvas id="page_views_count"></canvas>
        </div>
    </div>
    <div class="parent grid-xxl-fill">
        <div>
            <h3>Page views</h3>
            <small>by device (last 30 days)</small>
            <canvas id="page_views_device"></canvas>
        </div>
        <div>
            <h3>Page views</h3>
            <small>by country (last 30 days)</small>
            <canvas id="page_views_country"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
<script>

    let page_views = @json($page_views);
    let page_views_device = @json($page_views_device);
    let page_views_country = @json($page_views_country);

    let c2 = new Chart(qs('#page_views_count'), {
        type: 'line',
        data: {
            labels: Object.keys(page_views),
            datasets: [
                {
                    label: 'Page view count',
                    data: Object.values(page_views),
                    borderColor: 'red',
                    fill: false,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Value'
                    },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            }
        },
    });
    let c = new Chart(qs('#page_views_device'), {
        type: 'pie',
        data: {
            labels: Object.keys(page_views_device),
            datasets: [
                {
                    label: 'Page view count',
                    data: Object.values(page_views_device),
                    backgroundColor: ['red', 'blue', 'green'],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
            }
        },
    });
    let c3 = new Chart(qs('#page_views_country'), {
        type: 'pie',
        data: {
            labels: Object.keys(page_views_country),
            datasets: [
                {
                    label: 'Page views',
                    data: Object.values(page_views_country),
                    backgroundColor: ['maroon', 'yellow', 'gray'],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
            }
        },
    });
</script>
@endpush