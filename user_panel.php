<?php session_start(); 
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Panou Utilizator</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="panel">
    <h2>Bine ai venit!</h2>
    <a href="stoc.php?role=user" class="btn">Produse</a>
  </div>
</body>
</html>
