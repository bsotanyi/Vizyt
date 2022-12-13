<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link rel="stylesheet" href="resources/css/verima.css">
    <style>
        span {
            /* border: 1px solid #ccc; */
            border-radius: .5rem;
            padding: 1rem;
        }
        </style>
</head>
<body>
    <div id="nav" class="spread box">
        <a href="#" class="link">Link1</a>
        <div class="spread">
            <a href="#" class="link">Link 2</a>
            <a href="#" class="link">Link 3</a>
            <a href="#" class="link">Link 4</a>
            <div class="center-items">
                <input type="search" name="" id="" placeholder="Search...">
            </div>
        </div>
    </div>
    <div class="box gaps">
        <h1>{{ $title }}</h1>
        <button data-tooltip="why" class="bg-primary">Tooltip button</button>
        <br>
        <button class="bg-primary">bg-primary</button>
        <button class="bg-warning">bg-warning</button>
        <button class="bg-danger">bg-danger</button>
        <button class="bg-info">bg-info</button>
        <button class="bg-success">bg-success</button>
        <button class="bg-light">bg-light</button>
        <button class="bg-light-accent">bg-light-accent</button>
        <button class="bg-dark">bg-dark</button>
        <button class="bg-dark-accent">bg-dark-accent</button>
        <div class="grid-md">
            <div class="blocks gaps">
                <label for="">Név</label>
                <input type="text">
            </div>
            <div class="blocks gaps">
                <label for="">Cikkszám</label>
                <input type="text">
            </div>
            <div class="blocks gaps">
                <label for="">Márka</label>
                <input type="text">
            </div>
            <div class="blocks gaps">
                <label for="">Lejárati dátum</label>
                <input type="text">
            </div>
            <div class="blocks gaps">
                <label for="">Lejárati dátum</label>
                <select name="" id="">
                    <option>Choose</option>
                    <option>Lorem, ipsum dolor.</option>
                    <option>Quidem, asperiores quod!</option>
                    <option>Beatae, saepe consequuntur?</option>
                </select>
            </div>
        </div>
        <div class="blocks gaps">
            <label for="">Textarea</label>
            <textarea name="" id="" cols="30" rows="3"></textarea>
        </div>
        <section class="gaps">
            <header><h1>Form elements</h1></header>
            @for($i = 0; $i < 2; $i++)
            <div>
                <label><input type="checkbox" name="hungry" id="" checked> lorem</label>
                <label><input type="checkbox" name="hungry" id="" checked> Hungry</label>
                <label><input type="checkbox" name="hungry" id="" > Hungry</label>
                <label><input type="checkbox" name="hungry" id="" > Hungry</label>
            </div>
            @endfor
            @for($i = 0; $i < 2; $i++)
                <div>
                    <label><input type="checkbox" role="switch" name="hungry" id="" > lorem</label>
                    <label><input type="checkbox" role="switch" name="hungry" id="" checked> Hungry</label>
                    <label><input type="checkbox" role="switch" name="hungry" id="" > Hungry</label>
                    <label><input type="checkbox" role="switch" name="hungry" id="" > Hungry</label>
                </div>
            @endfor
            <div>
                <label><input type="radio" name="hungry" id="" checked> lorem</label>
                <label><input type="radio" name="hungry" id="" > Hungry</label>
                <label><input type="radio" name="hungry" id="" > Hungry</label>
                <label><input type="radio" name="hungry" id="" > Hungry</label>
            </div>
            <br>
            <div class="box shadow">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Szia</th>
                            <th>Hello</th>
                            <th>Hmm</th>
                            <th>Lol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lorem.</td>
                            <td>Facere!</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Lorem.</td>
                            <td>Iusto.</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Lorem.</td>
                            <td>Necessitatibus.</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Lorem.</td>
                            <td>Vel?</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Lorem.</td>
                            <td>Vero?</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Lorem.</td>
                            <td>Officia.</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore, debitis optio laudantium quae, neque qui sapiente ab enim, similique recusandae architecto vitae illo consequatur quo quaerat excepturi dolores! Nam, ab?</p>
            <p>Debitis dignissimos, ab excepturi animi veniam tenetur similique ipsa odio eligendi et quia veritatis perspiciatis, molestias neque nam velit? Quod asperiores similique, repellat maiores omnis magnam aspernatur harum incidunt sit?</p>
            <p>Enim culpa voluptate eveniet! Obcaecati delectus similique enim rerum deleniti? Qui veniam aspernatur delectus saepe, temporibus dicta at optio vel laudantium magni minima libero officiis deserunt possimus atque maiores placeat!</p>
            <p>Praesentium cum officia, numquam autem non nulla ex quaerat asperiores natus assumenda neque earum suscipit! Labore, sequi tempora sed delectus error nisi, culpa sapiente quam iusto illum tenetur omnis ipsum?</p>
            <p>Quia delectus veritatis repellat nisi ex nostrum qui esse culpa quos omnis odit quas commodi ipsam quasi harum, accusantium minima, minus, similique nam maxime illum fugit magnam a atque. Et!</p>
        </section>
        <h2>Grids</h2>
        <hr>
        @php
            $sizes = ['xs' => 6, 'sm' => 6, 'md' => 6, 'lg' => 4, 'xl' => 3, 'xxl' => 2];
        @endphp
        @foreach ($sizes as $size => $n)
            <br>
            <div class="gaps">
                <h3>grid-{{ $size }}</h3>
                <div class=" grid-{{ $size }}">
                    @for ($i = 0; $i < $n; $i++)
                        <span class="shadow bg-primary"></span>
                    @endfor
                </div>
                <h3>grid-{{ $size }}-fill</h3>
                <div class=" grid-{{ $size }}-fill">
                    @for ($i = 0; $i < $n; $i++)
                        <span class="shadow bg-primary"></span>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>