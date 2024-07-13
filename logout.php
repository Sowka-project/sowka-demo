<?php
session_start();

// Zakończenie sesji
session_unset();
session_destroy();

// Przekierowanie na stronę logowania lub gdziekolwiek indziej
header("Location: index.php");
exit;
?>
