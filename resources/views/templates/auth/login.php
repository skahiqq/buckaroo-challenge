<?php
include 'resources/views/partials/navbar.php';
include 'resources/views/partials/errors.php';
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-6">
            <form action="login" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email"
                           value="<?php if (isset($_SESSION['old']['email'])) {
                               echo $_SESSION['old']['email'];
                               unset($_SESSION['old']['email']);
                           } else {
                               echo null;
                           } ?>"
                    >
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <div href="/" id="emailHelp" class="form-text text-center mt-2">Do not have an account? <a href="/register">Register</a>.</div>
            </form>
        </div>
    </div>
</div>
<?php
?>