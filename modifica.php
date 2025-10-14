<?php
session_start();
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

// Partea de actualizare: dacă se primește POST, se procesează update-ul
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preluăm datele trimise prin formular
    $id_produs  = isset($_POST['id_produs']) ? intval($_POST['id_produs']) : 0;
    $nume_produs = isset($_POST['nume_produs']) ? trim($_POST['nume_produs']) : '';
    $an_lansare  = isset($_POST['an_lansare']) ? intval($_POST['an_lansare']) : 0;
    $pret_zi     = isset($_POST['pret_zi']) ? floatval($_POST['pret_zi']) : 0;
    $stoc        = isset($_POST['stoc']) ? intval($_POST['stoc']) : 0;
    $tip_produs  = isset($_POST['tip_produs']) ? trim($_POST['tip_produs']) : '';

    // Validare: toate câmpurile trebuie completate (poți extinde validarea după nevoie)
    if ($id_produs > 0 && $nume_produs !== '' && $an_lansare > 0 && $pret_zi > 0 && $stoc >= 0 && $tip_produs !== '') {
        try {
            $stmt = $pdo->prepare("UPDATE produseProiect 
                                   SET nume_produs = :nume_produs, 
                                       an_lansare = :an_lansare, 
                                       pret_zi = :pret_zi, 
                                       stoc = :stoc, 
                                       tip_produs = :tip_produs 
                                   WHERE id_produs = :id_produs");
            $stmt->execute([
                ':nume_produs' => $nume_produs,
                ':an_lansare'  => $an_lansare,
                ':pret_zi'     => $pret_zi,
                ':stoc'        => $stoc,
                ':tip_produs'  => $tip_produs,
                ':id_produs'   => $id_produs
            ]);
            // Redirecționează utilizatorul la pagina de stoc după actualizare
            header("Location: stoc.php?msg=produs_modificat");
            exit;
        } catch (PDOException $e) {
            $error = "Eroare la actualizarea produsului: " . $e->getMessage();
        }
    } else {
        $error = "Toate câmpurile sunt obligatorii!";
    }
} else {
    // Partea GET: preia ID-ul produsului din URL
    if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
        die("ID produs invalid.");
    }
    $id_produs = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM produseProiect WHERE id_produs = :id_produs");
    $stmt->execute([':id_produs' => $id_produs]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        die("Produsul nu a fost găsit.");
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Modifică Produs</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
        }
        form { 
            max-width: 500px; 
            margin: auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px;
        }
        label { 
            display: block; 
            margin-top: 10px; 
        }
        input, select { 
            width: 100%; 
            padding: 8px; 
            margin-top: 5px; 
            box-sizing: border-box;
        }
        button { 
            margin-top: 15px; 
            padding: 10px 20px; 
            display: block; 
            width: 100%;
        }
        .error { 
            color: red; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <h1>Modifică Produs</h1>
    <?php if(isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="modifica.php" method="post">
        <!-- ID-ul produsului se transmite invizibil -->
        <input type="hidden" name="id_produs" value="<?php echo isset($product['id_produs']) ? htmlspecialchars($product['id_produs']) : $id_produs; ?>">
        
        <label for="nume_produs">Nume Produs:</label>
        <input type="text" id="nume_produs" name="nume_produs" value="<?php echo isset($product['nume_produs']) ? htmlspecialchars($product['nume_produs']) : ''; ?>" required>
        
        <label for="an_lansare">An Lansare:</label>
        <input type="number" id="an_lansare" name="an_lansare" value="<?php echo isset($product['an_lansare']) ? htmlspecialchars($product['an_lansare']) : ''; ?>" required>
        
        <label for="pret_zi">Preț pe Zi:</label>
        <input type="number" id="pret_zi" name="pret_zi" step="0.01" value="<?php echo isset($product['pret_zi']) ? htmlspecialchars($product['pret_zi']) : ''; ?>" required>
        
        <label for="stoc">Stoc:</label>
        <input type="number" id="stoc" name="stoc" value="<?php echo isset($product['stoc']) ? htmlspecialchars($product['stoc']) : ''; ?>" required>
        
        <label for="tip_produs">Tip Produs:</label>
        <select name="tip_produs" id="tip_produs" required>
            <option value="">Alege tipul</option>
            <option value="film" <?php if(isset($product['tip_produs']) && $product['tip_produs'] === 'film') echo 'selected'; ?>>Film</option>
            <option value="joc" <?php if(isset($product['tip_produs']) && $product['tip_produs'] === 'joc') echo 'selected'; ?>>Joc</option>
            <option value="muzica" <?php if(isset($product['tip_produs']) && $product['tip_produs'] === 'muzica') echo 'selected'; ?>>Muzica</option>
        </select>
        
        <button type="submit">Modifică Produsul</button>
    </form>
</body>
</html>
