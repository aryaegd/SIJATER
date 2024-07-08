<?php
include 'koneksi.php';

$year = 2022;
$query = "
    SELECT
        MONTH(tgl_laporan) as bulan,
        SUM(CASE WHEN id_status = 3 THEN 1 ELSE 0 END) as selesai,
        SUM(CASE WHEN id_status = 4 THEN 1 ELSE 0 END) as tidak_selesai
    FROM laporan
    WHERE YEAR(tgl_laporan) = $year
    GROUP BY MONTH(tgl_laporan)
    ORDER BY bulan
";
$result = $conn->query($query);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SIJATER - Data Tahun 2022</title>
    <link rel="icon" href="assets/img/SIJATER Logo.png" type="image/png">  
    <link href="css/font.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/sidebar-styles.css" rel="stylesheet" />
    <link href="css/colors.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-header">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3 space-grotesk-semi-bold" href="index.php">
        <img src="assets/img/SIJATER Logo.png" alt="SIJATER Logo" class="me-2">
        SIJATER
    </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav" class="bg-main">
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 dm-sans-medium">
                    <h1 class="mt-4 space-grotesk-semi-bold">Data Tahun 2022</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Tahun 2022</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-line me-1"></i>
                            Line Chart Tahun 2022
                        </div>
                        <div class="card-body">
                            <canvas id="lineChart2022" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small space-grotesk-regular">
                        <div class="text-muted">Copyright &copy; SIJATER 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        var ctx = document.getElementById("lineChart2022").getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                datasets: [{
                    label: 'Selesai',
                    data: [
                        <?php
                        foreach ($data as $row) {
                            echo $row['selesai'] . ", ";
                        }
                        ?>
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                }, {
                    label: 'Tidak Selesai',
                    data: [
                        <?php
                        foreach ($data as $row) {
                            echo $row['tidak_selesai'] . ", ";
                        }
                        ?>
                    ],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Bulan'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Jumlah Laporan'
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 10,
                            max: 50
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
