<?php
session_start();
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

/*
 * Endpoint JSON: 
 * Dacă se face o cerere GET cu parametru ?json=1, se interoghează view‑ul view_muzicaProiect 
 * și se returnează rezultatul în format JSON.
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json']) && $_GET['json'] == '1') {
    $stmt = $pdo->query("SELECT * FROM view_muzicaProiect ORDER BY id_album ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($data);
    exit;
}

/*
 * Tratarea cererilor POST pentru adăugare şi ştergere.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'add') {
        // Preluăm valorile pentru inserare
        $nume_produs = isset($_POST['nume_produs']) ? trim($_POST['nume_produs']) : '';
        $an_lansare  = isset($_POST['an_lansare']) ? intval($_POST['an_lansare']) : 0;
        $pret_zi     = isset($_POST['pret_zi']) ? floatval($_POST['pret_zi']) : 0;
        $stoc        = isset($_POST['stoc']) ? intval($_POST['stoc']) : 0;
        
        $gen         = isset($_POST['gen']) ? trim($_POST['gen']) : '';
        $subgen      = isset($_POST['subgen']) ? trim($_POST['subgen']) : '';
        $artist      = isset($_POST['artist']) ? trim($_POST['artist']) : '';
        $artist_2    = isset($_POST['artist_2']) ? trim($_POST['artist_2']) : '';
        $casa_discuri= isset($_POST['casa_discuri']) ? trim($_POST['casa_discuri']) : '';
        
        // Validare: se consideră obligatorii câmpurile: nume_produs, an_lansare, pret_zi, stoc, gen, artist, casa_discuri.
        if ($nume_produs === '' || $an_lansare <= 0 || $pret_zi <= 0 || $stoc < 0 ||
            $gen === '' || $artist === '' || $casa_discuri === '') {
            header("Content-Type: application/json");
            echo json_encode([
                "status" => "error",
                "message" => "Datele sunt incomplete sau invalide. Asigurați-vă că toate câmpurile obligatorii sunt completate."
            ]);
            exit;
        }
        
        try {
            // Începem o tranzacție
            $pdo->beginTransaction();
            
            // Inserare în tabela produseProiect (tip_produs va fi 'muzica')
            $stmt = $pdo->prepare("INSERT INTO produseProiect (nume_produs, an_lansare, pret_zi, stoc, tip_produs)
                                   VALUES (:nume_produs, :an_lansare, :pret_zi, :stoc, 'muzica')");
            $stmt->execute([
                ':nume_produs' => $nume_produs,
                ':an_lansare'  => $an_lansare,
                ':pret_zi'     => $pret_zi,
                ':stoc'        => $stoc
            ]);
            $id_produs = $pdo->lastInsertId();
            
            // Inserare în tabela muzicaProiect
            $stmt = $pdo->prepare("INSERT INTO muzicaProiect (id_produs, gen, subgen, artist, artist_2, casa_discuri)
                                   VALUES (:id_produs, :gen, :subgen, :artist, :artist_2, :casa_discuri)");
            $stmt->execute([
                ':id_produs'   => $id_produs,
                ':gen'         => $gen,
                ':subgen'      => $subgen,
                ':artist'      => $artist,
                ':artist_2'    => $artist_2,
                ':casa_discuri'=> $casa_discuri
            ]);
            
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Albumul a fost adăugat cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la adăugarea albumului: " . $e->getMessage()]);
        }
        exit;
        
    } elseif ($action === 'delete') {
        // Ștergere: se așteaptă ca parametrii id_album și id_produs să fie transmiși.
        $id_album  = isset($_POST['id_album']) ? intval($_POST['id_album']) : 0;
        $id_produs = isset($_POST['id_produs']) ? intval($_POST['id_produs']) : 0;
        if ($id_album <= 0 || $id_produs <= 0) {
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "ID invalid"]);
            exit;
        }
        try {
            $pdo->beginTransaction();
            // Ștergem din tabela muzicaProiect, apoi din produseProiect
            $stmt = $pdo->prepare("DELETE FROM muzicaProiect WHERE id_album = :id_album");
            $stmt->execute([':id_album' => $id_album]);
            $stmt = $pdo->prepare("DELETE FROM produseProiect WHERE id_produs = :id_produs");
            $stmt->execute([':id_produs' => $id_produs]);
            $pdo->commit();
            header("Content-Type: application/json");
            echo json_encode(["status" => "success", "message" => "Albumul a fost șters cu succes"]);
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Content-Type: application/json");
            echo json_encode(["status" => "error", "message" => "Eroare la ștergerea albumului: " . $e->getMessage()]);
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Muzică Proiect</title>
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
        // Funcția loadTable preia datele din view (GET ?json=1) și reconstruiește tabelul
        function loadTable() {
            fetch('muzica.php?json=1')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('musicTableBody');
                    tbody.innerHTML = '';
                    data.forEach(item => {
                        let tr = document.createElement('tr');
                        tr.innerHTML = `<td>${item.id_album}</td>
                                        <td>${item.nume_produs}</td>
                                        <td>${item.an_lansare}</td>
                                        <td>${item.pret_zi}</td>
                                        <td>${item.stoc}</td>
                                        <td>${item.gen}</td>
                                        <td>${item.subgen}</td>
                                        <td>${item.artist}</td>
                                        <td>${item.artist_2}</td>
                                        <td>${item.casa_discuri}</td>
                                        <td><button onclick="deleteMusic(${item.id_album}, ${item.id_produs})">Șterge</button></td>`;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Eroare la încărcarea datelor:', error));
        }

        // Funcția addMusic preia datele din formular și trimite o cerere POST cu action "add"
        function addMusic() {
            const nume = document.getElementById('add_nume').value;
            const an = document.getElementById('add_an').value;
            const pret = document.getElementById('add_pret').value;
            const stoc = document.getElementById('add_stoc').value;
            const gen = document.getElementById('add_gen').value;
            const subgen = document.getElementById('add_subgen').value;
            const artist = document.getElementById('add_artist').value;
            const artist2 = document.getElementById('add_artist2').value;
            const casa = document.getElementById('add_casa').value;
            
            // Campurile obligatorii: nume, an, pret, stoc, gen, artist, casa_discuri
            if(nume === '' || an === '' || pret === '' || stoc === '' || gen === '' || artist === '' || casa === ''){
                alert("Toate câmpurile obligatorii (nume, an, preț, stoc, gen, artist, casa discuri) trebuie completate!");
                return;
            }
            
            const formData = new URLSearchParams();
            formData.append('action', 'add');
            formData.append('nume_produs', nume);
            formData.append('an_lansare', an);
            formData.append('pret_zi', pret);
            formData.append('stoc', stoc);
            formData.append('gen', gen);
            formData.append('subgen', subgen);
            formData.append('artist', artist);
            formData.append('artist_2', artist2);
            formData.append('casa_discuri', casa);
            
            fetch('muzica.php', {
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

        // Funcția deleteMusic trimite cerere POST cu action "delete" și ID-urile necesare
        function deleteMusic(id_album, id_produs) {
            if (!confirm("Sigur doriți să ștergeți acest album?")) return;
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id_album', id_album);
            formData.append('id_produs', id_produs);
            
            fetch('muzica.php', {
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
    <h1>Muzică Proiect</h1>
    <div class="controls">
        <h3>Adaugă Album</h3>
        <input type="text" id="add_nume" placeholder="Nume Album">
        <input type="number" id="add_an" placeholder="An Lansare">
        <input type="number" id="add_pret" placeholder="Preț">
        <input type="number" id="add_stoc" placeholder="Stoc">
        <input type="text" id="add_gen" placeholder="Gen">
        <input type="text" id="add_subgen" placeholder="Subgen (opțional)">
        <input type="text" id="add_artist" placeholder="Artist">
        <input type="text" id="add_artist2" placeholder="Artist 2 (opțional)">
        <input type="text" id="add_casa" placeholder="Casa Discuri">
        <button onclick="addMusic()">Adaugă Album</button>
        <br><br>
        <button onclick="loadTable()">Refresh Tabel</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Album</th>
                <th>Nume Album</th>
                <th>An Lansare</th>
                <th>Preț</th>
                <th>Stoc</th>
                <th>Gen</th>
                <th>Subgen</th>
                <th>Artist</th>
                <th>Artist 2</th>
                <th>Casa Discuri</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody id="musicTableBody">
            <!-- Datele se vor încărca dinamic -->
        </tbody>
    </table>
</body>
</html>
