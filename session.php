<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function regenerate_session_id() {
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } elseif (time() - $_SESSION['CREATED'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
    }
}

regenerate_session_id();
?>
