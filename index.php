<?php include("db.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Consommation</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Tab1')">Applications</button>
  <button class="tablinks" onclick="openTab(event, 'Tab2')">Evo</button>
  <button class="tablinks" onclick="openTab(event, 'Tab3')">Compa</button>
</div>

<!-- ================= TAB 1 ================= -->
<div id="Tab1" class="tabcontent">
<h3>Top 5 Applications</h3>
<div class="chart-container">
<canvas id="chart1"></canvas>
</div>
</div>

<!-- ================= TAB 2 ================= -->
<div id="Tab2" class="tabcontent">
<h3>Evolution mensuelle</h3>
<div class="chart-container">
<canvas id="chart2"></canvas>
</div>
</div>

<!-- ================= TAB 3 ================= -->
<div id="Tab3" class="tabcontent">
<h3>Stockage vs Réseau</h3>
<div class="chart-container">
<canvas id="chart3"></canvas>
</div>
</div>

<?php
// ================= DONNÉES TAB 1 =================
$sql1 = "
SELECT a.nom, SUM(c.volume) as total
FROM consommation c
JOIN application a ON c.app_id = a.app_id
GROUP BY a.app_id
ORDER BY total DESC
LIMIT 5
";
$result1 = $conn->query($sql1);

$applications = [];
$totaux = [];

while($row = $result1->fetch()){
    $applications[] = $row['nom'];
    $totaux[] = $row['total'];
}

// ================= DONNÉES TAB 2 =================
$sql2 = "
SELECT DATE_FORMAT(mois, '%Y-%m') as mois,
SUM(volume) as total
FROM consommation
WHERE mois BETWEEN '2025-01-01' AND '2025-06-30'
GROUP BY mois
ORDER BY mois
";
$result2 = $conn->query($sql2);

$mois = [];
$totaux_mois = [];

while($row = $result2->fetch()){
    $mois[] = $row['mois'];
    $totaux_mois[] = $row['total'];
}

// ================= DONNÉES TAB 3 =================
$sql3 = "
SELECT DATE_FORMAT(c.mois, '%Y-%m') as mois,
SUM(CASE WHEN r.nom = 'Stockage' THEN c.volume ELSE 0 END) as stockage,
SUM(CASE WHEN r.nom = 'Réseau' THEN c.volume ELSE 0 END) as reseau
FROM consommation c
JOIN ressource r ON c.res_id = r.res_id
GROUP BY mois
ORDER BY mois
";
$result3 = $conn->query($sql3);

$mois_compa = [];
$stockage = [];
$reseau = [];

while($row = $result3->fetch()){
    $mois_compa[] = $row['mois'];
    $stockage[] = $row['stockage'];
    $reseau[] = $row['reseau'];
}
?>

<script>

// ================== GESTION DES ONGLETS ==================
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("active");
  }

  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.classList.add("active");
}

// Ouvre le premier onglet par défaut
document.addEventListener("DOMContentLoaded", function() {
  document.getElementsByClassName("tablinks")[0].click();

  // ================== CHART 1 ==================
  new Chart(document.getElementById("chart1"), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($applications); ?>,
      datasets: [{
        label: 'Consommation totale',
        data: <?php echo json_encode($totaux); ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgb(54, 162, 235)',
        borderWidth: 1
      }]
    }
  });

  // ================== CHART 2 ==================
  new Chart(document.getElementById("chart2"), {
    type: 'line',
    data: {
      labels: <?php echo json_encode($mois); ?>,
      datasets: [{
        label: 'Consommation mensuelle',
        data: <?php echo json_encode($totaux_mois); ?>,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.3,
        fill: false
      }]
    }
  });

  // ================== CHART 3 ==================
  new Chart(document.getElementById("chart3"), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($mois_compa); ?>,
      datasets: [
        {
          label: 'Stockage',
          data: <?php echo json_encode($stockage); ?>,
          backgroundColor: 'rgba(255, 99, 132, 0.5)'
        },
        {
          label: 'Réseau',
          data: <?php echo json_encode($reseau); ?>,
          backgroundColor: 'rgba(153, 102, 255, 0.5)'
        }
      ]
    }
  });

});
</script>

</body>
</html>
