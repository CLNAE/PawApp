<?php
session_start();
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

/*
 * Endpoint JSON: Dacă se face o cerere GET cu ?json=1,
 * interogăm view‑ul view_filmeProiect și returnăm rezultatul în format JSON.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json']) && $_GET['json'] == '1') {
    $stmt = $pdo->query("SELECT * FROM view_filmeProiect ORDER BY id_film ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

/*
 * Tratarea cererilor POST:
 * Pentru action == 'add' se adaugă un nou film.
 * Pentru action == 'delete' se șterge filmul specificat.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'add') {
        // Preluăm datele pentru adăugarea filmului
        $nume_produs = isset($_POST['nume_produs']) ? trim($_POST['nume_produs']) : '';
        $an_lansare  = isset($_POST['an_lansare']) ? intval($_POST['an_lansare']) : 0;
        $pret_zi     = isset($_POST['pret_zi']) ? floatval($_POST['pret_zi']) : 0;
        $stoc        = isset($_POST['stoc']) ? intval($_POST['stoc']) : 0;
        $regizor     = isset($_POST['regizor']) ? trim($_POST['regizor']) : '';
        $gen_1       = isset($_POST['gen_1']) ? trim($_POST['gen_1']) : '';
        $gen_2       = isset($_POST['gen_2']) ? trim($_POST['gen_2']) : '';
        $gen_3       = isset($_POST['gen_3']) ? trim($_POST['gen_3']) : '';
        $actor1      = isset($_POST['actor1']) ? trim($_POST['actor1']) : '';
        $actor2      = isset($_POST['actor2']) ? trim($_POST['actor2']) : '';
        $actor3      = isset($_POST['actor3']) ? trim($_POST['actor3']) : '';
        
        // Validare: se consideră obligatorii cel puțin câmpurile: nume_produs, an_lansare, pret_zi, stoc, regizor, gen_1 și actor1.
        if ($nume_produs === '' || $an_lansare <= 0 || $pret_zi <= 0 || $stoc < 0 ||
            $regizor === '' || $gen_1 === '' || $actor1 === '') {
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Date incomplete sau invalide. Verificați câmpurile obligatorii."]);
            exit;
        }
        
        try {
            // Începem o tranzacție pentru inserarea în ambele tabele
            $pdo->beginTransaction();
            
            // Inserare în tabela produseProiect cu tip_produs = 'film'
            $stmt = $pdo->prepare("INSERT INTO produseProiect (nume_produs, an_lansare, pret_zi, stoc, tip_produs)
                                   VALUES (:nume_produs, :an_lansare, :pret_zi, :stoc, 'film')");
            $stmt->execute([
                ':nume_produs' => $nume_produs,
                ':an_lansare'  => $an_lansare,
                ':pret_zi'     => $pret_zi,
                ':stoc'        => $stoc
            ]);
            $id_produs = $pdo->lastInsertId();
            
            // Inserare în tabela filmProiect
            $stmt = $pdo->prepare("INSERT INTO filmProiect (id_produs, regizor, gen_1, gen_2, gen_3, 
                                                           actor_cunoscut_1, actor_cunoscut_2, actor_cunoscut_3)
                                   VALUES (:id_produs, :regizor, :gen_1, :gen_2, :gen_3, :actor1, :actor2, :actor3)");
            $stmt->execute([
                ':id_produs'  => $id_produs,
                ':regizor'    => $regizor,
                ':gen_1'      => $gen_1,
                ':gen_2'      => $gen_2,
                ':gen_3'      => $gen_3,
                ':actor1'     => $actor1,
                ':actor2'     => $actor2,
                ':actor3'     => $actor3
            ]);
            
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Film adăugat cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la adăugarea filmului: " . $e->getMessage()]);
        }
        exit;
    } elseif ($action === 'delete') {
        // Ștergere film: se așteaptă parametrii id_film și id_produs
        $id_film = isset($_POST['id_film']) ? intval($_POST['id_film']) : 0;
        $id_produs = isset($_POST['id_produs']) ? intval($_POST['id_produs']) : 0;
        if ($id_film <= 0 || $id_produs <= 0) {
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "ID invalid"]);
            exit;
        }
        try {
            $pdo->beginTransaction();
            // Ștergem întâi înregistrarea din tabela filmProiect, apoi din produseProiect
            $stmt = $pdo->prepare("DELETE FROM filmProiect WHERE id_film = :id_film");
            $stmt->execute([':id_film' => $id_film]);
            $stmt = $pdo->prepare("DELETE FROM produseProiect WHERE id_produs = :id_produs");
            $stmt->execute([':id_produs' => $id_produs]);
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Film șters cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la ștergerea filmului: " . $e->getMessage()]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Filme Proiect</title>
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
        // Funcția loadTable preia datele JSON din view și construiește tabelul
        function loadTable() {
            fetch('filme.php?json=1')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('filmTableBody');
                    tbody.innerHTML = '';
                    data.forEach(film => {
                        let tr = document.createElement('tr');
                        tr.innerHTML = `<td>${film.id_film}</td>
                                        <td>${film.nume_produs}</td>
                                        <td>${film.an_lansare}</td>
                                        <td>${film.pret_zi}</td>
                                        <td>${film.stoc}</td>
                                        <td>${film.regizor}</td>
                                        <td>${film.gen_1}</td>
                                        <td>${film.gen_2}</td>
                                        <td>${film.gen_3}</td>
                                        <td>${film.actor_cunoscut_1}</td>
                                        <td>${film.actor_cunoscut_2}</td>
                                        <td>${film.actor_cunoscut_3}</td>
                                        <td><button onclick="deleteFilm(${film.id_film}, ${film.id_produs})">Șterge</button></td>`;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Eroare la încărcarea datelor:', error));
        }

        // Funcția addFilm colectează datele introduse și trimite un POST cu action "add"
        function addFilm() {
            const nume = document.getElementById('add_nume').value;
            const an = document.getElementById('add_an').value;
            const pret = document.getElementById('add_pret').value;
            const stoc = document.getElementById('add_stoc').value;
            const regizor = document.getElementById('add_regizor').value;
            const gen1 = document.getElementById('add_gen1').value;
            const gen2 = document.getElementById('add_gen2').value;
            const gen3 = document.getElementById('add_gen3').value;
            const actor1 = document.getElementById('add_actor1').value;
            const actor2 = document.getElementById('add_actor2').value;
            const actor3 = document.getElementById('add_actor3').value;
            
            if(nume === '' || an === '' || pret === '' || stoc === '' || regizor === '' || gen1 === '' || actor1 === ''){
                alert("Toate câmpurile obligatorii (nume, an, pret, stoc, regizor, gen1, actor1) trebuie completate!");
                return;
            }
            
            const formData = new URLSearchParams();
            formData.append('action', 'add');
            formData.append('nume_produs', nume);
            formData.append('an_lansare', an);
            formData.append('pret_zi', pret);
            formData.append('stoc', stoc);
            formData.append('regizor', regizor);
            formData.append('gen_1', gen1);
            formData.append('gen_2', gen2);
            formData.append('gen_3', gen3);
            formData.append('actor1', actor1);
            formData.append('actor2', actor2);
            formData.append('actor3', actor3);
            
            fetch('filme.php', {
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

        // Funcția deleteFilm trimite un POST cu action "delete" și ID-urile necesare
        function deleteFilm(id_film, id_produs) {
            if (!confirm("Sigur doriți să ștergeți acest film?")) return;
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id_film', id_film);
            formData.append('id_produs', id_produs);
            
            fetch('filme.php', {
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
    <h1>Filme Proiect</h1>
    <div class="controls">
        <h3>Adaugă Film</h3>
        <input type="text" id="add_nume" placeholder="Nume Film">
        <input type="number" id="add_an" placeholder="An Lansare">
        <input type="number" id="add_pret" placeholder="Preț">
        <input type="number" id="add_stoc" placeholder="Stoc">
        <input type="text" id="add_regizor" placeholder="Regizor">
        <input type="text" id="add_gen1" placeholder="Gen 1">
        <input type="text" id="add_gen2" placeholder="Gen 2">
        <input type="text" id="add_gen3" placeholder="Gen 3">
        <input type="text" id="add_actor1" placeholder="Actor Cunoscut 1">
        <input type="text" id="add_actor2" placeholder="Actor Cunoscut 2">
        <input type="text" id="add_actor3" placeholder="Actor Cunoscut 3">
        <button onclick="addFilm()">Adaugă Film</button>
        <br><br>
        <button onclick="loadTable()">Refresh Tabel</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Film</th>
                <th>Nume Film</th>
                <th>An Lansare</th>
                <th>Preț</th>
                <th>Stoc</th>
                <th>Regizor</th>
                <th>Gen 1</th>
                <th>Gen 2</th>
                <th>Gen 3</th>
                <th>Actor Cunoscut 1</th>
                <th>Actor Cunoscut 2</th>
                <th>Actor Cunoscut 3</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody id="filmTableBody">
            <!-- Datele se vor încărca dinamic -->
        </tbody>
    </table>
</body>
</html>
