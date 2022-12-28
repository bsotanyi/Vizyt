<?php $__sections['title'] =  'Profile'; ?>
<?php ob_start(); ?>
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
<?php $__sections['content'] = ob_get_clean(); ?>
<?php view('layout', ['__sections' => $__sections ?? [], '__pushes' => $__pushes ?? [], '__all_vars' => $__all_vars]); ?>