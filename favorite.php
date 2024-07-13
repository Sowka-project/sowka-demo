<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || !isset($_POST['dataname'])) {
    http_response_code(400);
    exit;
}

$email = $_SESSION['email'];
$dataname = mysqli_real_escape_string($conn, $_POST['dataname']);

// Pobierz aktualne ulubione
$query = "SELECT favorites FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $favorites = $row['favorites'];
    $favoritesArray = explode(',', $favorites);

    if (in_array($dataname, $favoritesArray)) {
        // Usuń z ulubionych
        $key = array_search($dataname, $favoritesArray);
        unset($favoritesArray[$key]);
    } else {
        // Dodaj do ulubionych
        $favoritesArray[] = $dataname;
    }

    $newFavorites = implode(',', $favoritesArray);
    $updateQuery = "UPDATE users SET favorites = '$newFavorites' WHERE email = '$email'";
    mysqli_query($conn, $updateQuery);
}

mysqli_close($conn);
?>
