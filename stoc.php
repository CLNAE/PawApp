<?php
$role = isset($_GET['role']) && $_GET['role'] === 'user' ? 'user' : 'admin';
require_once 'db.php';
$pdo = Database::getInstance()->getConnection();

// Se interoghează tabela produseProiect
$stmt = $pdo->query("SELECT * FROM produseProiect");
$produse = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dacă se solicită JSON (endpointul folosit pentru refresh), se returnează doar datele
if (isset($_GET['json']) && $_GET['json'] == '1') {
    header("Content-Type: application/json");
    echo json_encode($produse);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Stoc - Produse Proiect</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 90%; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #fafafa; }
        .controls { margin: 20px auto; width: 90%; text-align: center; }
        .controls select, .controls input[type="text"] { padding: 5px; margin: 5px; }
        .controls button { padding: 6px 12px; margin: 5px; }
        #chartContainer { width: 50%; margin: 20px auto; display: none; }
		th.sort-asc::after {
    content: " ▲";
		}
		th.sort-desc::after {
    content: " ▼";
		}

    </style>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Produse Proiect</h1>
    <div class="controls">
    <!-- Dropdown pentru filtrare după tipul produsului -->
    <label for="filterTip">Filtrare tip:</label>
    <select id="filterTip">
        <option value="">Toate</option>
        <option value="film">Film</option>
        <option value="joc">Joc</option>
        <option value="muzica">Muzica</option>
    </select>

    <!-- Câmp text pentru filtrare după nume (nume_produs) -->
    <label for="filterNume">Filtrare nume:</label>
    <input type="text" id="filterNume" placeholder="Introdu numele produsului">

    <?php if($role !== 'user'): ?>
        <button id="refreshButton">Refresh Tabel</button>
        <button id="pieChartButton">Afișează Pie Chart</button>
    <?php endif; ?>
</div>


    <!-- Tabelul de afișare a datelor -->
    <table id="prodTable">
    <thead>
        <tr>
            <?php
            if(count($produse) > 0){
                // Generăm anteturile pe baza cheilor primului rând
                foreach(array_keys($produse[0]) as $coloana) {
                    echo "<th data-sort='none'>" . htmlspecialchars($coloana) . "</th>";
                }
                // Afișăm coloana "Acțiuni" doar dacă rolul nu e "user"
                if($role !== 'user'){
                    echo "<th>Acțiuni</th>";
                }
            } else {
                echo "<th>Nu există date</th>";
            }
            ?>
        </tr>
    </thead>
            <tbody id="prodTableBody">
        <?php
        if(count($produse) > 0){
            foreach($produse as $produs) {
                echo "<tr>";
                foreach($produs as $valoare) {
                    echo "<td>" . htmlspecialchars($valoare) . "</td>";
                }
                // Afișăm butoanele de acțiuni doar pentru admini (nu pentru user)
                echo "<td>";
if($role === 'user'){
    echo "<button onclick=\"placeOrder(" . $produs['id_produs'] . ")\">Rezervă</button>";
} else {
    echo "<button onclick=\"deleteProduct(" . $produs['id_produs'] . ")\">Șterge</button> ";
    echo "<button onclick=\"window.location.href='modifica.php?id=" . $produs['id_produs'] . "'\">Modifica Produs</button>";
}
echo "</td>";

                }
                echo "</tr>";
            
        } else {
            echo "<tr><td colspan='100%'>Nu s-au găsit produse în baza de date.</td></tr>";
        }
        ?>
    </tbody>
</table>


    <!-- Container pentru graficul pie chart -->
    <div id="chartContainer">
        <canvas id="pieChartCanvas"></canvas>
    </div>

    <script>
        // Variabila globală pentru stocarea produselor
        let productData = <?php echo json_encode($produse); ?>;
        // Indexurile coloanelor pentru "tip_produs" și "nume_produs"
        let tipIndex = -1;
        let numeIndex = -1;
        
        document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementById("prodTable");
    const ths = table.tHead.rows[0].cells;
    
    // Atașăm evenimente de click pentru fiecare antet, cu excepția coloanei "Acțiuni"
    for (let i = 0; i < ths.length; i++){
        // Dacă nu este ultima coloană (presupunem că ultima este "Acțiuni")
        if (i !== ths.length - 1) {
            // Inițializează atributul de sortare pe "none"
            ths[i].setAttribute("data-sort", "none");
            ths[i].addEventListener("click", function() {
                // Obține starea curentă și determină starea nouă
                let currentOrder = ths[i].getAttribute("data-sort") || "none";
                let newOrder = (currentOrder === "asc") ? "desc" : "asc";
                // Resetăm starea de sortare pentru toate anteturile
                for (let j = 0; j < ths.length; j++){
                    ths[j].setAttribute("data-sort", "none");
                    ths[j].classList.remove("sort-asc", "sort-desc");
                }
                // Setăm starea nouă pe antetul clicat
                ths[i].setAttribute("data-sort", newOrder);
                ths[i].classList.add(newOrder === "asc" ? "sort-asc" : "sort-desc");
                // Apelează funcția de sortare pe coloana i, cu ordinea newOrder
                sortTable(i, newOrder);
            });
        }
        // De asemenea, actualizăm indexurile pentru "tip_produs" și "nume_produs"
        let header = ths[i].textContent.trim().toLowerCase();
        if(header === "tip_produs"){
            tipIndex = i;
        }
        if(header === "nume_produs"){
            numeIndex = i;
        }
    }
            
    // Evenimentele pentru filtrare, refresh și afișarea graficului:
    document.getElementById("filterTip").addEventListener("change", filterTable);
    document.getElementById("filterNume").addEventListener("keyup", filterTable);
    document.getElementById("refreshButton").addEventListener("click", refreshTable);
    document.getElementById("pieChartButton").addEventListener("click", showPieChart);
});


		function sortTable(columnIndex, order) {
    const tbody = document.getElementById("prodTableBody");
    let rowsArray = Array.from(tbody.getElementsByTagName("tr"));
    
    rowsArray.sort(function(rowA, rowB) {
        let cellA = rowA.cells[columnIndex].textContent.trim().toLowerCase();
        let cellB = rowB.cells[columnIndex].textContent.trim().toLowerCase();
        
        // Dacă valorile sunt numerice, le convertim la număr:
        let numA = parseFloat(cellA);
        let numB = parseFloat(cellB);
        if (!isNaN(numA) && !isNaN(numB)) {
            cellA = numA;
            cellB = numB;
        }
        
        if (cellA < cellB) return order === 'asc' ? -1 : 1;
        if (cellA > cellB) return order === 'asc' ? 1 : -1;
        return 0;
    });
    
    // Reatașăm rândurile sortate în tbody
    rowsArray.forEach(row => tbody.appendChild(row));
}

        // Funcția de filtrare: verifică după tip și nume
        function filterTable() {
            const filterTipVal = document.getElementById("filterTip").value.toLowerCase();
            const filterNumeVal = document.getElementById("filterNume").value.toLowerCase();
            const rows = document.getElementById("prodTableBody").rows;
            for (let i = 0; i < rows.length; i++){
                let cells = rows[i].cells;
                let show = true;
                if(filterTipVal !== "" && tipIndex !== -1){
                    let tipVal = cells[tipIndex].textContent.trim().toLowerCase();
                    if(tipVal !== filterTipVal) {
                        show = false;
                    }
                }
                if(filterNumeVal !== "" && numeIndex !== -1){
                    let numeVal = cells[numeIndex].textContent.trim().toLowerCase();
                    if(numeVal.indexOf(filterNumeVal) === -1) {
                        show = false;
                    }
                }
                rows[i].style.display = show ? "" : "none";
            }
        }

        // Funcția de refresh: recuperează datele din server și reconstruiește tabelul
        function refreshTable() {
            fetch('stoc.php?json=1')
                .then(response => {
                    if (!response.ok) throw new Error("Răspunsul rețelei nu este valid.");
                    return response.json();
                })
                .then(data => {
                    productData = data;
                    const tbody = document.getElementById("prodTableBody");
                    tbody.innerHTML = "";
                    data.forEach(produs => {
                        let tr = document.createElement("tr");
                        for (let key in produs) {
                            let td = document.createElement("td");
                            td.textContent = produs[key];
                            tr.appendChild(td);
                        }
                        // Adăugăm celula pentru acțiuni: buton pentru ștergere și buton pentru modificare
                        tr.innerHTML += `<td>
                            <button onclick="deleteProduct(${produs.id_produs})">Șterge</button>
                            <button onclick="window.location.href='modifica.php?id=${produs.id_produs}'">Modifica Produs</button>
                        </td>`;
                        tbody.appendChild(tr);
                    });
                    // Re-calculează indexurile pentru filtrare
                    const ths = document.getElementById("prodTable").tHead.rows[0].cells;
                    tipIndex = -1;
                    numeIndex = -1;
                    for (let i = 0; i < ths.length; i++){
                        let header = ths[i].textContent.trim().toLowerCase();
                        if(header === "tip_produs"){
                            tipIndex = i;
                        }
                        if(header === "nume_produs"){
                            numeIndex = i;
                        }
                    }
                    filterTable();
                })
                .catch(error => console.error("Eroare la refresh:", error));
        }

        // Funcția pentru afișarea unui pie chart cu distribuția tipurilor de produs
        function showPieChart() {
            let countFilm = 0, countJoc = 0, countMuzica = 0;
            productData.forEach(produs => {
                if (produs.tip_produs) {
                    let tipVal = produs.tip_produs.toLowerCase();
                    if (tipVal === "film") countFilm++;
                    if (tipVal === "joc") countJoc++;
                    if (tipVal === "muzica") countMuzica++;
                }
            });
            let total = countFilm + countJoc + countMuzica;
            if (total === 0) {
                alert("Nu există produse de tip film, joc sau muzica pentru a genera graficul.");
                return;
            }
            const chartData = {
                labels: ["Film", "Joc", "Muzica"],
                datasets: [{
                    data: [countFilm, countJoc, countMuzica],
                    backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
                }]
            };
            const config = {
                type: 'pie',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Distribuția tipurilor de produse (%)' }
                    }
                },
            };

            if (window.myPieChart instanceof Chart) {
                window.myPieChart.destroy();
            }
            const ctx = document.getElementById('pieChartCanvas').getContext('2d');
            window.myPieChart = new Chart(ctx, config);
            document.getElementById("chartContainer").style.display = 'block';
        }

        // Funcția deleteProduct trimite cerere POST cu action "delete" și id_produs
        function deleteProduct(id_produs) {
            if (!confirm("Sigur doriți să ștergeți acest produs?")) return;
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id_produs', id_produs);
            fetch('stoc.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                refreshTable();
            })
            .catch(error => console.error("Eroare la ștergere:", error));
        }
        // Funcția pentru user - plasare comandă
function placeOrder(id_produs) {
    alert("Produsul cu ID-ul " + id_produs+" va asteapta in magazin");
}

    </script>
</body>
</html>
