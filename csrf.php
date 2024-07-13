<?php
session_start();

function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}

function check_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}
?>
