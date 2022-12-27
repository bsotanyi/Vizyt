<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminers</title>
    <link rel="stylesheet" href="assets/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/lib/aos/aos.css">
    <link rel="stylesheet" href="assets/css/main.min.css?{{ microtime(true) }}">
</head>
<body>
    @include('components/header')
    @include('components/sidenav')

    <main id="content">
        {{-- @yield('content') --}}
        
        <div class="parent grid-lg-fill">
            <div>
                <small>Lorem, ipsum.</small>
                <h3>Lorem, ipsum dolor.</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eveniet illum eaque magni eius maxime voluptatem numquam tenetur qui earum exercitationem veritatis dolor, reiciendis unde facilis asperiores perspiciatis, itaque nam quasi.</p>
            </div>
            <div>
                <small>Officiis, nobis.</small>
                <h3>Deleniti, iure quae.</h3>
                <p>Nihil nulla asperiores eum ab obcaecati quidem ipsum aliquid dignissimos aut corporis, sit, a eaque fugiat repudiandae. Non obcaecati, et quam, nulla, sed officia voluptate molestiae perspiciatis rerum maiores libero.</p>
            </div>
            <div>
                <small>Eos, nesciunt.</small>
                <h3>Id, neque quis.</h3>
                <p>Fuga mollitia veniam doloremque ducimus, voluptatibus nesciunt nostrum iste odio aperiam incidunt, atque pariatur. Quam, vero consequatur. Amet commodi illum eius voluptate pariatur ex tempora nesciunt. Ullam, fuga nostrum. Eveniet?</p>
            </div>
        </div>
        <div class="parent grid">
            <div>
                <small>View & Edit</small>
                <h3>User profile</h3>
                <hr>
                <div class="grid-md-fill">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-control" value="21">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Education</label>
                        <select class="form-select">
                            <option>High school</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Yes or no</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                            <label class="form-check-label" for="flexCheckDefault">
                              Default checkbox
                            </label>
                          </div>
                    </div>
                </div>
                <div class="grid mt-3">
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" cols="30" rows="3" placeholder="Lorem ipsum..."></textarea>
                    </div>
                </div>
                <br>
                <button class="btn bg-primary">Submit</button>
            </div>
        </div>
        <div class="parent grid">
            <div>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Something</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lorem.</td>
                            <td>Quas?</td>
                            <td>Voluptatibus!</td>
                        </tr>
                        <tr>
                            <td>Pariatur.</td>
                            <td>Assumenda.</td>
                            <td>Ipsam.</td>
                        </tr>
                        <tr>
                            <td>Repellendus!</td>
                            <td>Placeat?</td>
                            <td>Aut?</td>
                        </tr>
                        <tr>
                            <td>Quod.</td>
                            <td>Possimus!</td>
                            <td>Eligendi.</td>
                        </tr>
                        <tr>
                            <td>Voluptates?</td>
                            <td>Voluptatibus.</td>
                            <td>Quaerat.</td>
                        </tr>
                        <tr>
                            <td>Consequuntur?</td>
                            <td>Mollitia.</td>
                            <td>Tempore.</td>
                        </tr>
                        <tr>
                            <td>Ea?</td>
                            <td>Doloribus!</td>
                            <td>Laborum?</td>
                        </tr>
                        <tr>
                            <td>Excepturi.</td>
                            <td>Accusantium!</td>
                            <td>Eos?</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="parent grid-xl-fill">
            <div>
                <small>Quasi, iste!</small>
                <h3>Ipsam, aliquid odit.</h3>
                <p>Unde iste quae, similique quod aliquam aliquid praesentium magnam corrupti, sunt aut quisquam iusto commodi illum minus inventore et aperiam sed suscipit reprehenderit laudantium! Unde omnis doloribus totam voluptas placeat.</p>
            </div>
            <div>
                <small>Aspernatur, nisi?</small>
                <h3>Earum, a? Libero?</h3>
                <p>Vitae optio, rerum nobis magni ullam quasi maxime voluptatem, quibusdam minus laudantium quia, enim vel ut consequatur laborum vero hic blanditiis perferendis unde voluptas sunt. Aperiam expedita a ut dolorum.</p>
            </div>
            <div>
                <small>Facilis, ut.</small>
                <h3>Obcaecati, ut eum.</h3>
                <p>Velit corrupti reiciendis voluptates, quidem ut iure corporis a deleniti harum eius? Laboriosam tenetur, aut animi veniam quis quam fugiat delectus ex fuga ipsam veritatis repellendus ab adipisci quod hic!</p>
            </div>
            <div>
                <small>Corporis, ullam.</small>
                <h3>Quidem, iusto quo!</h3>
                <p>Id culpa vero, quae rem error in dolorum, tempore inventore, consequatur fugit ea deserunt eligendi adipisci architecto ratione saepe repudiandae quisquam! Voluptate nam, quos illum magnam beatae nobis possimus similique.</p>
            </div>
        </div>
    </main>

    @include('components/footer')

    <script src="assets/lib/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/lucide/lucide.min.js"></script>
    <script src="assets/lib/aos/aos.js"></script>
    <script src="assets/lib/PristineJS/pristine.js"></script>
    <script src="assets/js/main.js?{{ microtime(true) }}"></script>
</body>
</html>