<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject') • {{ $_CONFIG['app_name'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        #outer {
            margin-top: 2rem;
            display: inline-block;
            width: 70vw;
        }
        @media only screen and (max-width: 600px) {
            #outer {
                width: 95vw;
            }
        }
    </style>
</head>
<body>
    {{-- TODO - We can not really use classes in emails, so we have to achieve some similar styling by only using inline style tags on each element --}}
    <div id="outer">
        <main class="card text-center">
            <div class="card-header">
                <strong>@yield('subject')</strong>
            </div>
            <div class="card-body" style="text-align: left;">
                @yield('content')
            </div>
            <div class="card-footer text-muted">Copyright &copy; {{ date('Y') . ' ' . $_CONFIG['app_name'] }}</div>
        </main>
    </div>
</body>
</html>