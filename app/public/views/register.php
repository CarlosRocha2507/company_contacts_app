<?php
if (isset($_GET["status"])) {
    $status = $_GET["status"];
    if ($status == "success") {
        echo '<div class="notification is-success">
            <button class="delete"></button>
            You have successfully registered! Welcome aboard.
            </div>';
    } else if ($status == "error") {
        echo '<div class="notification is-danger">
            <button class="delete"></button>
            An error occurred during registration. Please try again or contact support if the issue persists.
            </div>';
    }
}
?>
<section class="section">
    <div class="container">
        <h1 class="title">Register</h1>
        <form action="register_user" method="POST">
            <div class="field">
                <label class="label">Person Name</label>
                <div class="control">
                    <input class="input" type="text" name="name" placeholder="Enter your name" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Username</label>
                <div class="control">
                    <input class="input" type="text" name="user_name" placeholder="Enter your username" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Password</label>
                <div class="control">
                    <input class="input" type="password" id="password" name="user_password"
                        placeholder="Enter your password" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Creator code</label>
                <div class="control">
                    <input class="input" type="password" name="secret_code" placeholder="Confirm your secret code"
                        required>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit" onclick="passwordVerifycation();">Register</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script src="public/js/auth.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        (document.querySelectorAll('.notification .delete') || []).forEach(($delete) => {
            const $notification = $delete.parentNode;

            $delete.addEventListener('click', () => {
                $notification.parentNode.removeChild($notification);
            });
        });

        // Automatically close notifications after 5 seconds
        setTimeout(() => {
            (document.querySelectorAll('.notification') || []).forEach(($notification) => {
                if ($notification.parentNode) {
                    $notification.parentNode.removeChild($notification);
                }
            });
        }, 5000);
    });
</script>