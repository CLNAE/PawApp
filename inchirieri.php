<?php
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

// Execută interogarea pentru a prelua toate datele din tabela inchirieriCalcul
$stmt = $pdo->query("SELECT * FROM inchirieriCalcul");
$conturi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dacă se solicită JSON (ex: conturi.php?json=1)
if (isset($_GET['json']) && $_GET['json'] == '1') {
    header("Content-Type: application/json");
    echo json_encode($conturi);
    exit;
}

// Escape pentru PDF
function pdfEscape($text) {
    $text = str_replace('\\', '\\\\', $text);
    $text = str_replace('(', '\\(', $text);
    $text = str_replace(')', '\\)', $text);
    return $text;
}

// Generare PDF cu mai multe linii
function generatePDF($lines) {
    $stream = "BT\n/F1 12 Tf\n70 750 Td\n";
    foreach ($lines as $index => $line) {
        $escaped = pdfEscape($line);
        if ($index > 0) {
            $stream .= "0 -15 Td\n";
        }
        $stream .= "({$escaped}) Tj\n";
    }
    $stream .= "ET";

    $length = strlen($stream);

    $objects = [];
    $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
    $objects[] = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
    $objects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>\nendobj\n";
    $objects[] = "4 0 obj\n<< /Length $length >>\nstream\n$stream\nendstream\nendobj\n";
    $objects[] = "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

    $pdf = "%PDF-1.4\n";
    $offsets = [];
    foreach ($objects as $obj) {
        $offsets[] = strlen($pdf);
        $pdf .= $obj;
    }

    $xrefOffset = strlen($pdf);
    $xref = "xref\n0 " . (count($objects) + 1) . "\n";
    $xref .= "0000000000 65535 f \n";
    foreach ($offsets as $off) {
        $xref .= sprintf("%010d 00000 n \n", $off);
    }

    $pdf .= $xref;
    $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

    return $pdf;
}


// Dacă se solicită exportul în PDF (ex: conturi.php?export=pdf)
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    $pdfLines = ["Conturi Proiect", ""];

    if (count($conturi) > 0) {
        $pdfLines[] = implode("    ", array_keys($conturi[0]));
        foreach ($conturi as $row) {
            $pdfLines[] = implode("    ", $row);
        }
    } else {
        $pdfLines[] = "Nu se găsesc date.";
    }

    $pdfContent = generatePDF($pdfLines);

    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"export.pdf\"");
    echo $pdfContent;
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Inchirieri</title>
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
        .export-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #4285f4;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Inchirieri</h1>
    <a href="?export=pdf" class="export-btn">Export în PDF</a>
    <table>
        <thead>
            <tr>
                <?php
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
