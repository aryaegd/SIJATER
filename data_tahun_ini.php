<?php
include 'koneksi.php';

// Mengambil data dari database
$total_laporan_query = "SELECT COUNT(*) as total FROM laporan WHERE YEAR(tgl_laporan) = YEAR(CURDATE())";
$total_laporan_result = $conn->query($total_laporan_query);
$total_laporan = $total_laporan_result->fetch_assoc()['total'];

$laporan_disetujui_query = "SELECT COUNT(*) as disetujui FROM laporan WHERE status_verifikasi='Disetujui' AND YEAR(tgl_laporan) = YEAR(CURDATE())";
$laporan_disetujui_result = $conn->query($laporan_disetujui_query);
$laporan_disetujui = $laporan_disetujui_result->fetch_assoc()['disetujui'];

$laporan_ditolak_query = "SELECT COUNT(*) as ditolak FROM laporan WHERE status_verifikasi='Ditolak' AND YEAR(tgl_laporan) = YEAR(CURDATE())";
$laporan_ditolak_result = $conn->query($laporan_ditolak_query);
$laporan_ditolak = $laporan_ditolak_result->fetch_assoc()['ditolak'];

$status_laporan_query = "SELECT
    SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) as belum_dikerjakan,
    SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) as sedang_dikerjakan,
    SUM(CASE WHEN id_status = 3 THEN 1 ELSE 0 END) as selesai_dikerjakan,
    SUM(CASE WHEN id_status = 4 THEN 1 ELSE 0 END) as tidak_selesai
FROM laporan 
WHERE status_verifikasi='Disetujui' AND YEAR(tgl_laporan) = YEAR(CURDATE())";
$status_laporan_result = $conn->query($status_laporan_query);
$status_laporan = $status_laporan_result->fetch_assoc();

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
    <title>SIJATER - Data Tahun Ini</title>
    <link rel="icon" href="assets/img/SIJATER Logo.png" type="image/png">  
    <link href="css/font.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/sidebar-styles.css" rel="stylesheet" />
    <link href="css/colors.css" rel="stylesheet" /> <!-- Tambahkan ini -->
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
                <div class="container-fluid px-4">
                    <h1 class="mt-4 space-grotesk-semi-bold">Data Tahun Ini</h1>
                    <div class="card mb-4">
                        <div class="card-body dm-sans-medium">
                            Berikut adalah data laporan kerusakan jalan tahun ini.
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header space-grotesk-regular">
                            <i class="fas fa-chart-pie me-1"></i>
                            Visualisasi Pie Chart
                        </div>
                        <div class="card-body"><canvas id="myPieChart" width="180%" height="50"></canvas></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card bg-white text-primary shadow border border-primary">
                                <div class="card-body dm-sans-medium">
                                    Total Laporan Masuk
                                    <div class="display-2 fw-bold"><?php echo $total_laporan; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card bg-success text-white shadow border border-success">
                                <div class="card-body dm-sans-medium">
                                    Laporan Disetujui
                                    <div class="display-2 fw-bold"><?php echo $laporan_disetujui; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card bg-danger text-white shadow border border-danger">
                                <div class="card-body dm-sans-medium">
                                    Laporan Ditolak
                                    <div class="display-2 fw-bold"><?php echo $laporan_ditolak; ?></div>
                                </div>
                            </div>
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
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Belum Dikerjakan", "Sedang Dikerjakan", "Selesai Dikerjakan", "Tidak Selesai"],
                datasets: [{
                    data: [
                        <?php echo $status_laporan['belum_dikerjakan']; ?>,
                        <?php echo $status_laporan['sedang_dikerjakan']; ?>,
                        <?php echo $status_laporan['selesai_dikerjakan']; ?>,
                        <?php echo $status_laporan['tidak_selesai']; ?>
                    ],
                    backgroundColor: ['#4267EB', '#F6C951', '#8FECFF', '#ED9895'],
                }],
            },
        });
    </script>
</body>
</html>
