<?php
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

// Execută interogarea pentru a prelua toate datele din tabela conturiProiect
$stmt = $pdo->query("SELECT * FROM login");
$conturi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dacă se solicită JSON (ex. conturi.php?json=1), se returnează doar datele în format JSON
if (isset($_GET['json']) && $_GET['json'] == '1') {
    header("Content-Type: application/json");
    echo json_encode($conturi);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Conturi Proiect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <h1>Conturi Proiect</h1>
    <table>
        <thead>
            <tr>
                <?php
                // Dacă există cel puțin o înregistrare, generăm antetul tabelului pe baza cheilor array-ului
                if (count($conturi) > 0) {
                    foreach (array_keys($conturi[0]) as $coloana) {
                        echo "<th>" . htmlspecialchars($coloana) . "</th>";
                    }
                } else {
                    echo "<th>Nu există date</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($conturi) > 0) {
                foreach ($conturi as $cont) {
                    echo "<tr>";
                    foreach ($cont as $valoare) {
                        echo "<td>" . htmlspecialchars($valoare) . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='100%'>Nu se găsesc conturi în baza de date.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
