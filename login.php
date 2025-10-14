<?php
session_start();
require_once 'db.php';

// Dacă formularul este trimis (metoda POST), procesăm autentificarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preluăm datele introduse de utilizator
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $parola = isset($_POST['parola']) ? trim($_POST['parola']) : '';

    // Obținem conexiunea PDO
    $pdo = Database::getInstance()->getConnection();

    // Se pregătește interogarea pentru a selecta utilizatorul cu credențialele date
    $stmt = $pdo->prepare("SELECT * FROM login WHERE username = :username AND parola = :parola LIMIT 1");
    $stmt->execute([
        ':username' => $username,
        ':parola' => $parola
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Salvează informațiile despre utilizator în sesiune
        $_SESSION['user'] = $user;
		$_SESSION['username'] = $username;

        // Verificăm câmpul 'tip' și redirecționăm corespunzător
        if ($user['tip'] === 'admin') {
            header("Location: admin_panel.php");
            exit();
        } elseif ($user['tip'] === 'user') {
            header("Location: user_panel.php");
            exit();
        } else {
            $error = "Tip de utilizator necunoscut.";
        }
    } else {
        $error = "Autentificare eșuată. Verifică numele de utilizator și parola.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Login Utilizator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 300px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="parola"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px;
            padding: 8px 16px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if(isset($error)): ?>
      <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <label for="username">Nume de utilizator:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="parola">Parola:</label>
        <input type="parola" id="parola" name="parola" required>
        
        <input type="submit" value="Autentificare">
    </form>
</body>
</html>
