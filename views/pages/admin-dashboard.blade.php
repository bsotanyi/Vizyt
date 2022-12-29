@extends('layout')

@section('title', $title)

@section('content')
    <small>Hello</small>
    <h2>Administrator</h2>
    <div class="parent grid">
        <div>
            <h3>Page views</h3>
            <small>by day</small>
            <canvas id="page_views_count"></canvas>
        </div>
    </div>
    <div class="parent grid-xxl-fill">
        <div>
            <h3>Page views</h3>
            <small>by device</small>
            <canvas id="page_views_device"></canvas>
        </div>
        <div>
            <h3>Page views</h3>
            <small>by country</small>
            <canvas id="page_views_country"></canvas>
        </div>
    </div>
    <script>
        function pageScript() {
            let c = new Chart(qs('#page_views_device'), {
                type: 'pie',
                data: {
                    labels: ['Mobile', 'PC', 'Smart fridge'],
                    datasets: [
                        {
                            label: 'Page views',
                            data: [60, 35, 5],
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
            let c2 = new Chart(qs('#page_views_count'), {
                type: 'line',
                data: {
                    labels: ['May 1','May 2','May 3','May 4','May 5','May 1','May 2','May 3','May 4','May 5'],
                    datasets: [
                        {
                            label: 'Idk',
                            data: [50,60,70,50,60,71,88,57,59,57],
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
            let c3 = new Chart(qs('#page_views_country'), {
                type: 'pie',
                data: {
                    labels: ['Serbia', 'Hungary', 'Kosovo'],
                    datasets: [
                        {
                            label: 'Page views',
                            data: [150, 35, 4],
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
        }
    </script>
@endsection