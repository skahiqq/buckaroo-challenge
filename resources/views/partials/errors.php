<?php
if (isset($_SESSION['errors'])) {
    echo '<div class="position-absolute col-3" style="right: 1%; top: 10%;">';
    foreach ($_SESSION['errors'] as $key => $error) {
        echo '<div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                                    '. $_SESSION['errors'][$key] .'
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
    }
    echo '</div>';
    unset($_SESSION['errors']);
}




