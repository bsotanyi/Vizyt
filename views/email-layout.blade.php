<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject') • {{ $_CONFIG['app_name'] }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Monoton&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            font-weight: 300;
        }
        @media only screen and (max-width: 600px) {
            #outer {
                width: 95vw;
            }
        }
        .card {
            border: 1px solid #bbb;
            border-radius: .6rem;
            margin-top: 2rem;
            display: inline-block;
            width: 70vw;
            box-shadow: 0 0 10px 1px rgba(200, 200, 200, 0.5);
            overflow: hidden;
        }
        .card-header, .card-footer {
            text-align: center;
            padding: 1rem;
            background-color: #eee;
        }
        .card-header {
            margin: 0;
            font-weight: 300;
            font-size: 25px;
        }
        .card-body {
            padding: 1rem;
            border-top: 1px solid #bbb;
            border-bottom: 1px solid #bbb;
            text-align: left;
        }
        .card-footer {
            color: #666;
        }
        .logo {
            font-weight: 200;
            letter-spacing: .1em;
            display: inline-block;
            user-select: none;
            font-family: 'Monoton', sans-serif;
            margin: 0;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="card-header">
            <h2 class="logo">{{ $_CONFIG['app_name'] }}</h2>
        </div>
        <div class="card-body">
            @yield('content')
        </div>
        <div class="card-footer">Copyright &copy; {{ date('Y') . ' ' . $_CONFIG['app_name'] }}</div>
    </main>
</body>
</html>