<?php
include 'resources/views/partials/errors.php';
include 'resources/views/partials/navbar.php';
?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-6">
            <form action="/user/register" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full name</label>
                    <input type="text" class="form-control" id="name" aria-describedby="emailHelp" name="name" value="<?php echo $_SESSION['old']['name'] ?? null ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email" value="<?php echo $_SESSION['old']['email'] ?? null ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" aria-describedby="emailHelp" name="password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <div href="/" id="emailHelp" class="form-text text-center mt-2">Have an account? <a href="/login">Login</a>.</div>
            </form>
        </div>
    </div>
</div>