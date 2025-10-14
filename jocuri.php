<?php
session_start();
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

/*
 * Endpoint JSON: dacă se solicită prin GET parametru `json=1`,
 * interogăm view-ul `view_jocuriProiect` și returnăm rezultatele în format JSON.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json']) && $_GET['json'] == '1') {
    $stmt = $pdo->query("SELECT * FROM view_jocuriProiect ORDER BY id_joc ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

/*
 * Tratarea cererilor POST:
 * • Dacă `action` este "add", se adaugă un nou joc.
 * • Dacă `action` este "delete", se șterge un joc.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        // Preluăm datele pentru inserare
        $nume_produs = isset($_POST['nume_produs']) ? trim($_POST['nume_produs']) : '';
        $an_lansare  = isset($_POST['an_lansare']) ? intval($_POST['an_lansare']) : 0;
        $pret_zi     = isset($_POST['pret_zi']) ? floatval($_POST['pret_zi']) : 0;
        $stoc        = isset($_POST['stoc']) ? intval($_POST['stoc']) : 0;
        
        $platforma   = isset($_POST['platforma']) ? trim($_POST['platforma']) : '';
        $studio      = isset($_POST['studio']) ? trim($_POST['studio']) : '';
        $gen_1       = isset($_POST['gen_1']) ? trim($_POST['gen_1']) : '';
        $gen_2       = isset($_POST['gen_2']) ? trim($_POST['gen_2']) : '';
        $gen_3       = isset($_POST['gen_3']) ? trim($_POST['gen_3']) : '';
        
        // Validare: este necesar să se completeze câmpurile obligatorii.
        // Aici presupunem că: nume_produs, an_lansare, pret_zi, stoc, platforma, studio și gen_1 sunt obligatorii.
        if ($nume_produs === '' || $an_lansare <= 0 || $pret_zi <= 0 || $stoc < 0 ||
            $platforma === '' || $studio === '' || $gen_1 === '') {
            header("Content-Type: application/json");
            echo json_encode([
                "status" => "error",
                "message" => "Datele sunt incomplete sau invalide. Asigurați-vă că toate câmpurile obligatorii sunt completate."
            ]);
            exit;
        }
        
        try {
            // Începem o tranzacție pentru inserarea în ambele tabele
            $pdo->beginTransaction();
            
            // Inserare în tabela produseProiect; setăm tip_produs implicit 'joc'
            $stmt = $pdo->prepare("INSERT INTO produseProiect (nume_produs, an_lansare, pret_zi, stoc, tip_produs) 
                                   VALUES (:nume_produs, :an_lansare, :pret_zi, :stoc, 'joc')");
            $stmt->execute([
                ':nume_produs' => $nume_produs,
                ':an_lansare'  => $an_lansare,
                ':pret_zi'     => $pret_zi,
                ':stoc'        => $stoc
            ]);
            $id_produs = $pdo->lastInsertId();
            
            // Inserare în tabela jocProiect
            $stmt = $pdo->prepare("INSERT INTO jocProiect (id_produs, platforma, studio, gen_1, gen_2, gen_3) 
                                   VALUES (:id_produs, :platforma, :studio, :gen_1, :gen_2, :gen_3)");
            $stmt->execute([
                ':id_produs'  => $id_produs,
                ':platforma'  => $platforma,
                ':studio'     => $studio,
                ':gen_1'      => $gen_1,
                ':gen_2'      => $gen_2,
                ':gen_3'      => $gen_3
            ]);
            
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Joc adăugat cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la adăugarea jocului: " . $e->getMessage()]);
        }
        exit;
    } elseif ($action === 'delete') {
        // Ștergere joc: se așteaptă parametrii id_joc și id_produs
        $id_joc = isset($_POST['id_joc']) ? intval($_POST['id_joc']) : 0;
        $id_produs = isset($_POST['id_produs']) ? intval($_POST['id_produs']) : 0;
        if ($id_joc <= 0 || $id_produs <= 0) {
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "ID invalid"]);
            exit;
        }
        
        try {
            $pdo->beginTransaction();
            // Ștergem din tabela jocProiect, apoi din produseleProiect
            $stmt = $pdo->prepare("DELETE FROM jocProiect WHERE id_joc = :id_joc");
            $stmt->execute([':id_joc' => $id_joc]);
            $stmt = $pdo->prepare("DELETE FROM produseProiect WHERE id_produs = :id_produs");
            $stmt->execute([':id_produs' => $id_produs]);
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Joc șters cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la ștergerea jocului: " . $e->getMessage()]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Jocuri Proiect</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        .controls { text-align: center; margin-bottom: 20px; }
        .controls input, .controls button { margin: 5px; padding: 5px 10px; }
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #fafafa; }
    </style>
    <script>
        // Funcția loadTable preia datele din view prin fetch (GET ?json=1) și reconstruiește tabelul
        function loadTable() {
            fetch('jocuri.php?json=1')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('gameTableBody');
                    tbody.innerHTML = '';
                    data.forEach(game => {
                        let tr = document.createElement('tr');
                        // Construim rândul: afișăm coloanele de la view
                        tr.innerHTML = `<td>${game.id_joc}</td>
                                        <td>${game.nume_produs}</td>
                                        <td>${game.an_lansare}</td>
                                        <td>${game.pret_zi}</td>
                                        <td>${game.stoc}</td>
                                        <td>${game.platforma}</td>
                                        <td>${game.studio}</td>
                                        <td>${game.gen_1}</td>
                                        <td>${game.gen_2}</td>
                                        <td>${game.gen_3}</td>
                                        <td><button onclick="deleteGame(${game.id_joc}, ${game.id_produs})">Șterge</button></td>`;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Eroare la încărcarea datelor:', error));
        }

        // Funcția addGame:
        // Preia valorile din formularul de adăugare și trimite cerere POST cu action "add"
        function addGame() {
            const nume = document.getElementById('add_nume').value;
            const an = document.getElementById('add_an').value;
            const pret = document.getElementById('add_pret').value;
            const stoc = document.getElementById('add_stoc').value;
            const platforma = document.getElementById('add_platforma').value;
            const studio = document.getElementById('add_studio').value;
            const gen1 = document.getElementById('add_gen1').value;
            const gen2 = document.getElementById('add_gen2').value;
            const gen3 = document.getElementById('add_gen3').value;
            
            if(nume === '' || an === '' || pret === '' || stoc === '' || platforma === '' || studio === '' || gen1 === ''){
                alert("Toate câmpurile obligatorii (nume, an, pret, stoc, platforma, studio, gen1) trebuie completate!");
                return;
            }
            
            const formData = new URLSearchParams();
            formData.append('action', 'add');
            formData.append('nume_produs', nume);
            formData.append('an_lansare', an);
            formData.append('pret_zi', pret);
            formData.append('stoc', stoc);
            formData.append('platforma', platforma);
            formData.append('studio', studio);
            formData.append('gen_1', gen1);
            formData.append('gen_2', gen2);
            formData.append('gen_3', gen3);
            
            fetch('jocuri.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                loadTable();
            })
            .catch(error => console.error('Eroare la adăugare:', error));
        }

        // Funcția deleteGame:
        // Trimite o cerere POST cu action "delete", specificând id_joc și id_produs pentru ștergere.
        function deleteGame(id_joc, id_produs) {
            if (!confirm("Sigur doriți să ștergeți acest joc?")) return;
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id_joc', id_joc);
            formData.append('id_produs', id_produs);
            fetch('jocuri.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                loadTable();
            })
            .catch(error => console.error('Eroare la ștergere:', error));
        }

        document.addEventListener('DOMContentLoaded', loadTable);
    </script>
</head>
<body>
    <h1>Jocuri Proiect</h1>
    <div class="controls">
        <h3>Adaugă joc</h3>
        <input type="text" id="add_nume" placeholder="Nume produs">
        <input type="number" id="add_an" placeholder="An lansare">
        <input type="number" id="add_pret" placeholder="Preț">
        <input type="number" id="add_stoc" placeholder="Stoc">
        <input type="text" id="add_platforma" placeholder="Platformă">
        <input type="text" id="add_studio" placeholder="Studio">
        <input type="text" id="add_gen1" placeholder="Gen 1">
        <input type="text" id="add_gen2" placeholder="Gen 2">
        <input type="text" id="add_gen3" placeholder="Gen 3">
        <button onclick="addGame()">Adaugă Joc</button>
        <br><br>
        <button onclick="loadTable()">Refresh Tabel</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Joc</th>
                <th>Nume Produs</th>
                <th>An Lansare</th>
                <th>Preț</th>
                <th>Stoc</th>
                <th>Platformă</th>
                <th>Studio</th>
                <th>Gen 1</th>
                <th>Gen 2</th>
                <th>Gen 3</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody id="gameTableBody">
            <!-- Datele se vor încărca dinamic -->
        </tbody>
    </table>
</body>
</html>
