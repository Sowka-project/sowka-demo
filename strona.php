<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
include("config.php");

$email = $_SESSION['email'];
$email = mysqli_real_escape_string($conn, $email);

// Obsługa dodawania/usuwania z ulubionych
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dataname'])) {
    $dataname = mysqli_real_escape_string($conn, $_POST['dataname']);

    $query = "SELECT favorites FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $favorites = $row['favorites'];
        $favoritesArray = explode(',', $favorites);

        if (in_array($dataname, $favoritesArray)) {
            // Usuń z ulubionych
            $favoritesArray = array_diff($favoritesArray, array($dataname));
        } else {
            // Dodaj do ulubionych
            $favoritesArray[] = $dataname;
        }

        $newFavorites = implode(',', $favoritesArray);
        $updateQuery = "UPDATE users SET favorites='$newFavorites' WHERE email='$email'";
        mysqli_query($conn, $updateQuery);
    }
}

// Obsługa wyszukiwania i filtrowania
$search = null;
$showfavorites = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['showfavorites']) && $_POST['showfavorites'] === 'true') {
        $showfavorites = true;
    }

    if (isset($_POST['search'])) {
        $search = strtolower(filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING));
    }
}

$query = "SELECT dataname, series, grade, subjectt, publisher, img FROM textbooks WHERE 1=1";

if ($search) {
    $query .= " AND LOWER(dataname) LIKE '%$search%'";
}

if ($showfavorites) {
    $query .= " AND dataname IN (SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(favorites, ',', numbers.n), ',', -1)) AS favorite 
                                 FROM users 
                                 JOIN (SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
                                       UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 
                                       UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 
                                       UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20 
                                       UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24 UNION ALL SELECT 25 
                                       UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30 
                                       UNION ALL SELECT 31 UNION ALL SELECT 32 UNION ALL SELECT 33 UNION ALL SELECT 34 UNION ALL SELECT 35 
                                       UNION ALL SELECT 36 UNION ALL SELECT 37 UNION ALL SELECT 38 UNION ALL SELECT 39 UNION ALL SELECT 40) numbers 
                                 ON CHAR_LENGTH(favorites) - CHAR_LENGTH(REPLACE(favorites, ',', '')) >= numbers.n - 1 
                                 WHERE email='$email')";
}

$grade = null;
$gradeNumber = null;
if (isset($_POST['grade'])) {
    $grade = $_POST['grade'];
    $gradeNumber = intval(preg_replace('/[^0-9]+/', '', $grade));
}
$subject = null;
if (isset($_POST['subject'])) {
    $subject = strtolower($_POST['subject']);
}
$publisher = null;
if (isset($_POST['publisher'])) {
    $publisher = strtolower($_POST['publisher']);
}

if ($gradeNumber != null && $grade != "Wszystkie" && $grade != "Klasa") {
    $query .= " AND grade LIKE '%$gradeNumber%'";
}
if ($subject != null && $subject != "wszystkie" && $subject != "przedmiot") {
    $query .= " AND subjectt LIKE '%$subject%'";
}
if ($publisher != null && $publisher != "wszystkie" && $publisher != "wydawnictwo") {
    $query .= " AND LOWER(publisher) LIKE '%$publisher%'";
}

// Dodaj sortowanie według serii i innych kryteriów
$query .= " ORDER BY series, grade, subjectt, publisher, dataname";

$result = mysqli_query($conn, $query);
$displayed_series = array();

$query2 = "SELECT favorites FROM users WHERE email = '$email'";
$result2 = mysqli_query($conn, $query2);
$favorites = "";
while ($row = mysqli_fetch_array($result2)) {
    $favorites = $row['favorites'];
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sówka | Strona główna</title>
    <link rel="stylesheet" type="text/css" href="style/darkstyle.css">
</head>

<body>
    <div id="root">
        <header>
            <div id="banner">
                <div id="img-box">
                    <a href="strona.php">
                        <img id="logoshort" src="img/logoshort.png" height="60px">
                        <img id="logo" src="img/logo.png" height="60px">
                    </a>
                </div>

                <div id="login-favorites-box">
                    <form id="favorites-form" method="POST">
                        <input type="hidden" name="showfavorites" value="true">
                        <div id="favorites-box">
                            <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="35" height="35">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="rgb(27, 0, 102)" stroke-width="2"/>
                            </svg>
                        </div>
                    </form>
                    <div id="subscription">Subskrypcja</div>

                    <div id="vertical-line"></div>
                    <a style="text-decoration: none;" href="logout.php"><div id="signup-button">Wyloguj się</div></a>
                </div>
            </div>
        </header>

        <section>
        <div id="subscription-box">
            <div id="cencle"><svg id="sub-x" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
  <line x1="4" y1="4" x2="16" y2="16" stroke="rgb(130, 130, 130)" stroke-width="3" stroke-linecap="round"/>
  <line x1="4" y1="16" x2="16" y2="4" stroke="rgb(130, 130, 130)" stroke-width="3" stroke-linecap="round"/>
</svg>


</div>
    <h1 id="subscription-title">Subskrypcja <span style="color: rgb(45, 0, 117); font-weight: 900; text-shadow: 0px 0px 0px black;">SÓWKA</span></h1>
    <p id="sub-description">Odblokuj nieograniczony dostęp do wszykich podręczniów!</p>
    <div id="subscriptions-container">
        <div class="subscriptions">
                        <script async
                src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                buy-button-id="buy_btn_1PWf0IP9JemHmeEGWjgvX8w8"
                publishable-key="pk_live_51PUoUOP9JemHmeEGdA6J6YQAtzLAUCdwLs2sG7RVf2a23lLkHOXyjcqSk3oeUP5nfwwsAl1EvwPenFGXLxRezMQo003pPCB4dx"
                >
                </stripe-buy-button>
        </div>
        <div class="subscriptions">
                        <script async
                src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                buy-button-id="buy_btn_1PWf8TP9JemHmeEGl5jnyAai"
                publishable-key="pk_live_51PUoUOP9JemHmeEGdA6J6YQAtzLAUCdwLs2sG7RVf2a23lLkHOXyjcqSk3oeUP5nfwwsAl1EvwPenFGXLxRezMQo003pPCB4dx"
                >
                </stripe-buy-button>
</div>
        <div class="subscriptions">
                        <script async
                src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                buy-button-id="buy_btn_1PWfEMP9JemHmeEGOC58OZJL"
                publishable-key="pk_live_51PUoUOP9JemHmeEGdA6J6YQAtzLAUCdwLs2sG7RVf2a23lLkHOXyjcqSk3oeUP5nfwwsAl1EvwPenFGXLxRezMQo003pPCB4dx"
                >
                </stripe-buy-button>
</div>
    </div>
</div>

        <section>

        <main>
            <div id="main">
                <div id="main-block">
                    <div id="form-box">
                        <form id="form" method="post">
                            <div id="search-box">
                                <svg onclick="document.getElementById('form').submit();" style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgb(200, 200, 200)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="search" name="search" placeholder="Wyszukaj..." maxlength="50">
                            </div>
                            <div id="select-box">
                                <select class="filters" name="grade">
                                    <option hidden selected>Klasa</option>
                                    <option>Wszystkie</option>
                                    <option>Klasa 1</option>
                                    <option>Klasa 2</option>
                                    <option>Klasa 3</option>
                                    <option>Klasa 4</option>
                                </select>
                                <select class="filters" id="middle-filter" name="subject">
                                    <option hidden selected>Przedmiot</option>
                                    <option>Wszystkie</option>
                                    <option>Matematyka</option>
                                    <option>Język polski</option>
                                    <option>Język angielski</option>
                                    <option>Język niemiecki</option>
                                    <option>Fizyka</option>
                                    <option>Chemia</option>
                                    <option>Historia</option>
                                    <option>Geografia</option>
                                    <option>Biologia</option>
                                </select>
                                <select class="filters" name="publisher">
                                    <option hidden selected>Wydawnictwo</option>
                                    <option>Wszystkie</option>
                                    <option>WSiP</option>
                                    <option>Nowa Era</option>
                                    <option>MAC Edukacja</option>
                                    <option>Operon</option>
                                    <option>Pearson</option>
                                </select>
                                <div id="submit-box">
                                <input id="submit-button" type="submit" value="Zatwierdź">
                            </div>
                            </div>
                            
                        </form>
                    </div>
                    <hr style="width: 90%;"/>
                    <div id="section-wrapper">
                        <?php
                            $i = 0;
                            while ($row = mysqli_fetch_array($result)) {
                                $series = $row['series'];
                                if (!in_array($series, $displayed_series)) {
                                    if ($i > 0) {
                                        echo "</div>";
                                    }
                                    echo "<div class='section'><label class='label-style'>".$series."</label><br/>";
                                    $displayed_series[] = $series;
                                    $i++;
                                }
                                $fill = (strpos($favorites, $row['dataname']) !== false) ? "red" : "none";
                                echo '<div class="book-box"><svg onclick="toggleFavorite(this)" class="heart-icon" data-name="'.$row['dataname'].'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="35" height="35">
                                    <path style="fill:'.$fill.';" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="rgb(58, 58, 58)" stroke-width="2"/>
                                </svg><a href="bookspage/'.$row['dataname'].'.php"><img class="book" src="textbooksimg/'.$row['img'].'" width="230" height="324.68"></a></div>';
                            }
                            if (mysqli_num_rows($result) == 0) {
                                echo "<p class='text-style'>Brak wyników</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>



        <footer>
            <div id="footer">

                

            </div>
        </footer>
    </div>
    <script src="favorites.js"></script>
    <script src="subscription.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
?>
