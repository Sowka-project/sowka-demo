<?php
require 'config.php';
require 'csrf.php';
require 'session.php';

$username_err = $password_err = $login_err = "";

function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!check_csrf_token($_POST['csrf_token'])) {
        echo "Nieprawidłowy token CSRF.";
        exit;
    }

    $username = validate_input($_POST['username']);
    $password = validate_input($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_time'] = time();
        $_SESSION['csrf_token'] = generate_csrf_token();
        $_SESSION['email'] = $username;
        header('Location: strona.php');
        exit;
    } else {
        $login_err = "Błędny email lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sówka | Strona logowania</title>
    <link rel="stylesheet" type="text/css" href="style/darkstyle.css">
</head>
<body id="login-body">
    <div id="root">
        <main>
            <div id="main-login-box">

                <div id="login-box-banner">
                    <a href="index.php">
                        <img src="img/logo.png" height="60px">
                    </a>
                </div>

                <div id="login-form-box">
                <form id="login-form" method="post">
                        <h3>E-mail</h3>
                        <input class="login-inputs" type="email" name="username" placeholder="Twój email" maxlength="30" required><br>
                        <span class="error"><?php echo $username_err; ?></span><br>
                        <h3>Hasło</h3>
                        <input class="login-inputs" type="password" name="password" placeholder="Twoje hasło" maxlength="20" required><br>
                        <span class="error"><?php echo $password_err; ?></span><br>
                        <span class="error"><?php echo $login_err; ?></span><br>
                        <a class="link" href="reset_password.php">Nie pamiętam hasła</a><br><br>
                        <a class="link" href="signup.php">Nie mam jeszcze konta</a><br>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input class="submit" type="submit" value="Zaloguj">
                    </form>
                </div>


            </div>
        </main>
    </div>
</body>
</html>