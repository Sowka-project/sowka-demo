<?php
require 'config.php';
require 'csrf.php';
require 'session.php';

$username_err = $password_err = $confirm_password_err = "";

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
    $confirm_password = validate_input($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $confirm_password_err = "Potwierdzenie hasła nie pasuje.";
    }

    
    if (strlen($password) < 8) {
        $password_err = "Hasło musi mieć co najmniej 8 znaków.";
    }

    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $password_err = "Hasło musi zawierać co najmniej jedną wielką literę, jedną małą literę i jedną cyfrę.";
    }

    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            // Logowanie użytkownika po udanej rejestracji
            $sql_login = "SELECT * FROM users WHERE email = ?";
            $stmt_login = $conn->prepare($sql_login);
            $stmt_login->bind_param("s", $username);
            $stmt_login->execute();
            $result = $stmt_login->get_result();
            $user = $result->fetch_assoc();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login_time'] = time();
                $_SESSION['csrf_token'] = generate_csrf_token();
                header('Location: login.php');
                exit;
            } else {
                echo "Błąd logowania po rejestracji.";
            }
        } else {
            echo "<span style='color: red;'>Błąd rejestracji.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sówka | Strona rejestracji</title>
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
                            <span class="error"><?php echo $username_err; ?></span>
                            <h3>Hasło</h3>
                            <input class="login-inputs" type="password" name="password" placeholder="Twoje hasło" maxlength="20" required><br>
                            <span class="error"><?php echo $password_err; ?></span>
                            <h3>Powtórz hasło</h3>
                            <input class="login-inputs" type="password" name="confirm_password" placeholder="Twoje hasło" maxlength="20" required><br><br>
                            <span class="error"><?php echo $confirm_password_err; ?></span>
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"><br>
                            <a class="link" href="login.php">Mam już konto</a><br>
                            <input class="submit" type="submit" value="Zarejestruj">
                            
                    </form>
                </div>


            </div>
        </main>
    </div>
</body>
</html>