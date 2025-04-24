<?php
require __DIR__ . '/../../private/services/ContactsService.php';
require_once __DIR__ . '/../../private/helpers/HSession.php';
HSession::startSession();
if (!HSession::isLoggedIn()) {
    header("Location: /");
    exit();
}
echo "<div id='contacts'>";
echo ContactsService::getContacts();
echo "</div>";
echo ContactsService::getContactModal();
?>
<script src="public/js/contacts.js"></script>
