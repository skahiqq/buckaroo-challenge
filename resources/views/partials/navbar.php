

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Mafia Game</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!--<li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>-->
            </ul>
            <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  '. $user['name'] .'
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/logout">Logout</a></li>
                                </ul>
                            </li>';
                } else {
                    echo '<span class="navbar-text">
                                <a href="/login" style="color: #75A6DA">Login</a>
                                <a href="/register" style="color: #75A6DA">Register</a>
                          </span>';
                }
            ?>

        </div>
    </div>
</nav>