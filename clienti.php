<?php
// Activează raportarea erorilor (doar în dezvoltare)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

// Endpoint JSON: Dacă se accesează cu ?json=1, preluăm și returnăm datele din tabela clientiProiect
if (isset($_GET['json']) && $_GET['json'] === '1') {
    $stmt = $pdo->query("SELECT * FROM clientiProiect");
    $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header("Content-Type: application/json");
    echo json_encode($clienti);
    exit;
}

// Tratarea cererii POST pentru inserarea unui client (folosit de cererea AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_client') {
    $nume_client = isset($_POST['nume_client']) ? trim($_POST['nume_client']) : '';
    $email       = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telefon     = isset($_POST['telefon']) ? trim($_POST['telefon']) : '';
    $username    = isset($_POST['username']) ? trim($_POST['username']) : '';

    // Validare: Numele clientului este obligatoriu
    if ($nume_client === "") {
        header("Content-Type: application/json");
        echo json_encode(["status" => "error", "message" => "Numele clientului este obligatoriu!"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO clientiProiect (nume_client, email, telefon, username) VALUES (:nume_client, :email, :telefon, :username)");
    $result = $stmt->execute([
        ':nume_client' => $nume_client,
        ':email'       => $email,
        ':telefon'     => $telefon,
        ':username'    => $username
    ]);

    header("Content-Type: application/json");
    if ($result) {
        echo json_encode(["status" => "success", "message" => "Client adăugat cu succes!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Eroare la adăugarea clientului!"]);
    }
    exit;
}

// Dacă nu se procesează o cerere POST pentru adăugare sau endpoint JSON,
// preluăm datele pentru afișarea în tabel.
$stmt = $pdo->query("SELECT * FROM clientiProiect");
$clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Clienti Proiect</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { text-align: center; }
    .controls { margin: 20px auto; width: 90%; text-align: center; }
    .controls input[type="text"],
    .controls input[type="email"],
    .controls input[type="tel"],
    .controls input[type="file"] { padding: 5px; margin: 5px; }
    .controls button { padding: 6px 12px; margin: 5px; }
    table { border-collapse: collapse; width: 90%; margin: auto; }
    th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
    th { background-color: #f2f2f2; }
    tr:nth-child(even) { background-color: #fafafa; }
    .message { text-align: center; font-weight: bold; color: green; }
  </style>
</head>
<body>
  <h1>Clienti Proiect</h1>
  
  <!-- Formular pentru import (opțional) – trimite datele către un script separată, ex. import_clienti.php -->
  <div class="controls">
    <form id="importForm" action="import_clienti.php" method="post" enctype="multipart/form-data">
      <label for="clientiFile">Selectează fișier Excel:</label>
      <input type="file" name="clientiFile" id="clientiFile" accept=".xls,.xlsx" required>
      <button type="submit">Import Clienti</button>
    </form>
  </div>
  
  <!-- Formularul pentru adăugarea unui client nou (folosind AJAX pentru a nu reîncărca pagina) -->
  <div class="controls">
    <h3>Adaugă Client</h3>
    <form id="addClientForm">
      <label for="nume_client">Nume Client:</label>
      <input type="text" name="nume_client" id="nume_client" required>
      <label for="email">Email:</label>
      <input type="email" name="email" id="email">
      <label for="telefon">Telefon:</label>
      <input type="tel" name="telefon" id="telefon">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username">
      <button type="submit">Adaugă Client</button>
    </form>
    <p id="addMessage" class="message"></p>
  </div>
  
  <!-- Tabelul de afișare a datelor -->
  <table>
    <thead>
      <tr>
        <?php
        if (count($clienti) > 0) {
            // Generăm anteturile tabelului pe baza cheilor primului element
            foreach (array_keys($clienti[0]) as $coloana) {
                // Folosim operatorul null coalescing pentru a evita avertismentele cu valori null
                echo "<th>" . htmlspecialchars($coloana ?? '') . "</th>";
            }
        } else {
            echo "<th>Nu există date</th>";
        }
        ?>
      </tr>
    </thead>
    <tbody id="clientTableBody">
      <?php
      if (count($clienti) > 0) {
          foreach ($clienti as $client) {
              echo "<tr>";
              foreach ($client as $valoare) {
                  echo "<td>" . htmlspecialchars($valoare ?? '') . "</td>";
              }
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='100%'>Nu se găsesc clienți în baza de date.</td></tr>";
      }
      ?>
    </tbody>
  </table>
  
  <!-- JavaScript pentru gestionarea funcționalităților AJAX -->
  <script>
    // Funcția de reîncărcare a clienților
    function loadClients() {
      fetch('clienti.php?json=1')
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById("clientTableBody");
          tbody.innerHTML = "";
          data.forEach(client => {
            let tr = document.createElement("tr");
            for (let key in client) {
              let td = document.createElement("td");
              td.textContent = client[key] ?? "";
              tr.appendChild(td);
            }
            tbody.appendChild(tr);
          });
        })
        .catch(error => console.error("Eroare la încărcarea datelor:", error));
    }
    
    // Interceptează trimiterea formularului de adăugare client
    document.getElementById("addClientForm").addEventListener("submit", function(e) {
      e.preventDefault(); // Previne reîncărcarea paginii
      const form = e.target;
      const formData = new FormData(form);
      formData.append("action", "add_client");
      
      fetch('clienti.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        document.getElementById("addMessage").textContent = result.message;
        if (result.status === "success") {
          form.reset();
          loadClients();
        }
      })
      .catch(error => console.error("Eroare la adăugare:", error));
    });
    
    // La încărcarea paginii, reîncarcă lista de clienți
    document.addEventListener("DOMContentLoaded", function() {
      loadClients();
    });
  </script>
</body>
</html>
