<?php
include_once __DIR__ . '/../helpers/HSession.php';
HSession::startSession();
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos</title>
    <link rel="icon" type="image/x-icon" href="public/favicon.ico">
    <!-- Styles links -->
    <link rel="stylesheet" href="public/styles/styles.css">
    <!-- Bulma CSS Framework -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <?php
            if (isset($_SESSION['id']) && HSession::isNotLoginPage()) {
                echo '<a class="navbar-item" href="/dashboard">
                        <img src="public/images/logo.png" alt="Logo" width="112" height="28">
                    </svg>
                    </a>';
            } else {
                echo '<a class="navbar-item" href="/">
                       <img src="public/images/logo.png" alt="Logo" width="112" height="28">
                    </svg>
                    </a>';
            }
            ?>
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <?php
                if (isset($_SESSION['id']) && HSession::isNotLoginPage()) {
                    echo '<a class="navbar-item" href="/dashboard ">
                        Contacts
                    </a>';
                } else {
                    echo '<a class="navbar-item" href="/contacts">
                        Contacts
                    </a>';
                }
                ?>
                <?php
                if (isset($_SESSION['id']) && HSession::isNotLoginPage()) {
                    echo '<div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            Utilities
                        </a>

                        <div class="navbar-dropdown">
                            <a class="navbar-item" href="/department">
                                <i class="fa-solid fa-building-user"></i> Departments
                            </a>
                            <hr class="navbar-divider">
                            <a class="navbar-item" href="/report">
                                <i class="fa-solid fa-bug"></i> Report an issue
                            </a>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <div class="navbar-end">
                <?php
                if (isset($_SESSION['id']) && HSession::isNotLoginPage()) {
                    echo '<div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            <span class="icon is-small">
                                <i class="fas fa-user"></i>
                            </span>
                            <span>' . $_SESSION['person_name'] . '</span>
                        </a>
                        <div class="navbar-dropdown is-right">
                            <a class="navbar-item" href="/logout">
                                Logout
                               <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- projects scripts -->
    <script src="public/js/scripts.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // This is the code to make the navbar burger work
        document.addEventListener('DOMContentLoaded', () => {

            // Get all "navbar-burger" elements
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            // Add a click event on each of them
            $navbarBurgers.forEach(el => {
                el.addEventListener('click', () => {

                    // Get the target from the "data-target" attribute
                    const target = el.dataset.target;
                    const $target = document.getElementById(target);

                    // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                    el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                });
            });

        });
    </script>
</body>

</html>