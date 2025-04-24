<?php
if (isset($_GET["status"])) {
    if ($_GET["status"] == "error") {
        echo '<div class="notification is-danger">
            <button class="delete"></button>
            Error during login. Please try again.
            </div>';
    }
}
?>
<section class="hero is-fullheight">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-8-tablet is-6-desktop is-5-widescreen">
                    <form action="/login" method="post" class="box">
                        <div class="field has-text-centered" style="font-size: 7rem;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="field">
                            <label class="label">Username</label>
                            <div class="control has-icons-left has-icons-right">
                                <input class="input" type="text" name="user_name" placeholder="Username" required>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>
                        <div class='control is-fullwidth has-icons-left has-icons-right mb-2'>
                            <label for="" class="label">Password</label>
                            <div class='field has-addons'>
                                <div class="control has-icons-left" style='width: 100%;'>
                                    <input type="password" id="password" name="user_password" placeholder="*******"
                                        class="input" required>
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <div class='control' onclick="showPasswordFieldText('password', 'password_icon_field');"
                                    style='width: 50px;'>
                                    <a class='button is-info' style='height: 100%;'>
                                        <i id='password_icon_field' class='fa-solid fa-eye-slash'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="remember" class="checkbox">
                                <input name="remember" type="checkbox">
                                Remember me
                            </label>
                        </div>
                        <div class="field">
                            <p class="control">
                                <a href="/register" class="is-link is-outlined">Not registed yet?</a>
                            </p>
                        </div>
                        <div class="field">
                            <button class="button is-success" name="login" type="submit">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;

            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });
    });
</script>