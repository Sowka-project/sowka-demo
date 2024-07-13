<?php
session_start();

    include("config.php");
    
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
                    <a href="index.php">
                        <img id="logoshort" src="img/logoshort.png" height="60px">
                        <img id="logo" src="img/logo.png" height="60px">
                    </a>
                </div>

                <div id="login-box">
                    
                    <a style="text-decoration: none;" href="login.php"><div id="subscription">Subskrypcja</div></a>

                    <div id="vertical-line"></div>
                    <a style="text-decoration: none;" href="login.php"><div id="login-button">Zaloguj się</div></a>
                    <a style="text-decoration: none;" href="signup.php"><div id="signup-button">Załóż konto</div></a>
                </div>


            </div>
        </header>
        
        <main>
            <div id="main">
                <div id="main-block">
                    
                    <div id="form-box">
                        <form id="form" method="post">
                            
                            <div id="search-box" >
                                <svg onclick="document.getElementById('form').submit();" style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg"width="20"height="20"viewBox="0 0 24 24" fill="none"stroke="rgb(200, 200, 200)" stroke-width="2"stroke-linecap="round"  stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="search" name="search" placeholder="Wyszukaj..." maxlength="50" >
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

                            $search = null;
                                if(isset($_POST['search'])){
                                    $search = $_POST['search'];
                                    $search = strtolower($search);
                                    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
                                }

                            $grade = null;
                            $gradeNumber = null;
                                if(isset($_POST['grade'])){
                                    $grade = $_POST['grade'];
                                    $gradeNumber = intval(preg_replace('/[^0-9]+/', '', $grade));
                                }
                            
                                $subject = null;
                                if(isset($_POST['subject'])){
                                    $subject = $_POST['subject'];
                                    $subject = strtolower($subject);
                                }

                                $publisher = null;
                                if(isset($_POST['publisher'])){
                                    $publisher = $_POST['publisher'];
                                    $publisher = strtolower($publisher);
                                }

                                

                                $query = "SELECT dataname, series, grade, subjectt, publisher, img FROM textbooks WHERE LOWER(dataname) LIKE '%$search%'";
                               
                                if ($gradeNumber != null && $grade != "Wszystkie" && $grade != "Klasa") {
                                    $query .= " AND grade LIKE '%$gradeNumber%'";
                                }

                                if($subject != null && $subject != "wszystkie" && $subject !="przedmiot"){
                                    $query .= " AND subjectt LIKE '%$subject%'";
                                }

                                if($publisher != null && $publisher != "wszystkie" && $publisher !="wydawnictwo"){
                                    $query .= " AND LOWER(publisher) LIKE '%$publisher%'";
                                }

                                $result = mysqli_query($conn, $query);






                                $displayed_series = array(); // Tablica przechowująca wyświetlone serie

                                $i = 0;

                                while ($row = mysqli_fetch_array($result)) {
                                    $series = $row['series'];
                                    
                                    // Sprawdzanie, czy seria została już wyświetlona
                                    if (!in_array($series, $displayed_series)) {
                                        
                                        if($i > 0){
                                            echo "</div>";
                                        }


                                        echo "<div class='section'><label class='label-style'>".$series."</label><br/>";
                                        
                                        // Dodanie wyświetlonej serii do tablicy
                                        $displayed_series[] = $series;
                                        $i++;
                                    }

                                    echo "<div class='book-box'><a href='login.php'><img class='book' width='230' height='324.68' src='textbooksimg/".$row['img']."'></a></div>";
                                    
                                }

                                if(mysqli_num_rows($result) == null){
                                    echo "<p class='text-style'>Brak wyników</p>";
                                    
                                }



                        ?>

                        <div class="section"></div>
                        <div class="section"></div>
                        <div class="section"></div>
                    </div>

                </div>
            </div>
        </main>

        <footer>
            <div id="footer">

                

            </div>
        </footer>

    </div>
    <script src="subscription.js"></script>
</body>
</html>

<?php
    mysqli_close($conn);
?>
