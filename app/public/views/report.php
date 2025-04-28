<section class="hero is-fullheight">
    <?php
    require_once __DIR__ . '/../../private/helpers/HSession.php';
    HSession::startSession();
    if (!HSession::isLoggedIn()) {
        header("Location: /");
        exit();
    }
    if (isset($_GET['status'])) {
        switch ($_GET['status']) {
            case 'success':
                echo '
                <div class="notification is-success">
                    <button class="delete"></button>
                    Issue reported successfully.
                </div>';
                break;
            case 'error':
                echo '
                <div class="notification is-danger">
                  <button class="delete"></button>
                    Error sending the message.
                </div>';
                break;
            default:
                echo '<div class="notification is-warnning">
                    <button class="delete"></button>
                    Sommething went wrong.
                </div>';
                break;
        }
    }
    ?>
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="columns is-8 is-variable ">
                <div class="column is-two-thirds has-text-left" style="margin-top: 6%;">
                    <h1 class="title is-1">Contact us</h1>
                    <p class="is-size-4">Contact us if you have any problems with the application.</p>
                    <div class="social-media">
                        <a href="https://facebook.com" target="_blank" class="button is-light is-large"><i
                                class="fa-brands fa-facebook"></i></i></a>
                        <a href="https://instagram.com" target="_blank" class="button is-light is-large"><i
                                class="fa-brands fa-square-instagram"></i></a>
                        <a href="https://twitter.com" target="_blank" class="button is-light is-large"><i
                                class="fa-brands fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="column is-one-third has-text-left">
                    <div class="field has-text-centered">
                        <img src="public/images/logo.png" alt="aplication logo"
                            style="max-width: 100%; height: auto; max-width: 250px;">
                    </div>
                    <form action="/send-report" method="POST" id="report-form">
                        <div class="field">
                            <label class="label">Name</label>
                            <div class="control">
                                <input class="input is-medium" type="text" name="name" placeholder="Your name..." required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input is-medium" type="email" name="email" placeholder="exemple@gmail.com"
                                    required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Message</label>
                            <div class="control">
                                <textarea class="textarea is-medium" name="message" required></textarea>
                            </div>
                        </div>
                        <div class="control">
                            <button type="submit"
                                class="button is-info is-fullwidth has-text-weight-medium is-medium">Send
                                Message</button>
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