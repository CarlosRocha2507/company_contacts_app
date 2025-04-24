<?php

function generateModal($modal_id, $title, $content, $buttons)
{
    $modal = "
    <div id='{$modal_id}' class='modal'>
    <div class='modal-background'></div>
    <div class='modal-card'>
        <header class='modal-card-head'>
        <p class='modal-card-title'>{$title}</p>
        <button class='delete' aria-label='close'></button>
        </header>
        <section class='modal-card-body'>
            {$content}
        </section>
        <footer class='modal-card-foot'>
        <div class='buttons'>
            {$buttons}
        </div>
        </footer>
    </div>
    </div>
    ";
    $modal .= '
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        function openModal($el) {
            $el.classList.add("is-active");
        }

        function closeModal($el) {
            $el.classList.remove("is-active");
        }

        function closeAllModals() {
            document.querySelectorAll(".modal").forEach(($modal) => {
                closeModal($modal);
            });
        }

        document.addEventListener("click", (event) => {
            const $trigger = event.target.closest(".js-modal-trigger");
            if ($trigger) {
                const modalId = $trigger.dataset.target;
                const $target = document.getElementById(modalId);
                if ($target) openModal($target);
            }
        });

        document.addEventListener("click", (event) => {
            if (
                event.target.matches(".modal-background") ||
                event.target.matches(".modal-close") ||
                event.target.closest(".modal-card-head .delete") ||
                event.target.closest(".modal-card-foot .button")
            ) {
                const $modal = event.target.closest(".modal");
                if ($modal) closeModal($modal);
            }
        });

        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                closeAllModals();
            }
        });
    });
    </script>';
    return $modal;
}