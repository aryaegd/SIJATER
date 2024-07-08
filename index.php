<?php
include 'koneksi.php';

// Query untuk mendapatkan kecamatan dengan jumlah laporan terbanyak
$queryKecamatanTerbanyak = "
    SELECT kecamatan, COUNT(*) as jumlah_laporan
FROM laporan
JOIN jalan ON laporan.id_jalan = jalan.id_jalan
WHERE YEAR(laporan.tgl_laporan) = YEAR(CURDATE())
GROUP BY kecamatan
ORDER BY jumlah_laporan DESC
LIMIT 1
";
$resultKecamatanTerbanyak = $conn->query($queryKecamatanTerbanyak);
$kecamatanTerbanyak = $resultKecamatanTerbanyak->fetch_assoc();

// Query untuk mendapatkan kecamatan dengan jumlah laporan paling sedikit
$queryKecamatanTersedikit = "
    SELECT kecamatan, COUNT(*) as jumlah_laporan
FROM laporan
JOIN jalan ON laporan.id_jalan = jalan.id_jalan
WHERE YEAR(laporan.tgl_laporan) = YEAR(CURDATE())
GROUP BY kecamatan
ORDER BY jumlah_laporan ASC
LIMIT 1
";
$resultKecamatanTersedikit = $conn->query($queryKecamatanTersedikit);
$kecamatanTersedikit = $resultKecamatanTersedikit->fetch_assoc();

// Query untuk mendapatkan data laporan
$query = "
    SELECT l.id_laporan, l.id_jalan, j.nama as nama_jalan, j.kecamatan
FROM laporan l
JOIN jalan j ON l.id_jalan = j.id_jalan
WHERE YEAR(l.tgl_laporan) = YEAR(CURDATE())
";
$result = $conn->query($query);
$data = [];
while ($row = $result->fetch_assoc()) {
    // Koordinat untuk jalan-jalan di Jakarta
    switch ($row['nama_jalan']) {
        case 'Jalan Merdeka':
            $row['lat'] = -6.175110;
            $row['lng'] = 106.865039;
            break;
        case 'Jalan Sudirman':
            $row['lat'] = -6.21462;
            $row['lng'] = 106.84513;
            break;
        case 'Jalan Thamrin':
            $row['lat'] = -6.20199;
            $row['lng'] = 106.82266;
            break;
        case 'Jalan Gatot Subroto':
            $row['lat'] = -6.23394;
            $row['lng'] = 106.82745;
            break;
        case 'Jalan MH Thamrin':
            $row['lat'] = -6.20876;
            $row['lng'] = 106.82154;
            break;
        case 'Jalan Medan Merdeka':
            $row['lat'] = -6.17194;
            $row['lng'] = 106.82238;
            break;
        case 'Jalan Jendral Sudirman':
            $row['lat'] = -6.2137;
            $row['lng'] = 106.814;
            break;
        case 'Jalan Diponegoro':
            $row['lat'] = -6.19595;
            $row['lng'] = 106.83313;
            break;
        case 'Jalan S Parman':
            $row['lat'] = -6.18377;
            $row['lng'] = 106.7903;
            break;
        case 'Jalan Ahmad Yani':
            $row['lat'] = -6.17255;
            $row['lng'] = 106.88145;
            break;
        default:
            $row['lat'] = -6.2;
            $row['lng'] = 106.8;
            break;
    }
    $data[] = $row;
}

$status_laporan_query = "SELECT
    SUM(CASE WHEN id_status = 1 THEN 1 ELSE 0 END) as belum_dikerjakan,
    SUM(CASE WHEN id_status = 2 THEN 1 ELSE 0 END) as sedang_dikerjakan,
    SUM(CASE WHEN id_status = 3 THEN 1 ELSE 0 END) as selesai_dikerjakan,
    SUM(CASE WHEN id_status = 4 THEN 1 ELSE 0 END) as tidak_selesai
FROM laporan 
WHERE status_verifikasi='Disetujui' AND YEAR(tgl_laporan) = YEAR(CURDATE())
";
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
    <title>SIJATER - Dashboard</title>
    <link rel="icon" href="assets/img/SIJATER Logo.png" type="image/png">  
    <link href="css/font.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/sidebar-styles.css" rel="stylesheet" />
    <link href="css/colors.css" rel="stylesheet" />
    

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-header"> <!-- warna header -->
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

    <div id="layoutSidenav" class="bg-main">  <!-- warna background-->
        <?php include 'sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 space-grotesk-semi-bold">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card mb-4">
                                <div class="card-header dm-sans-medium">
                                    <i class="fas fa-chart-pie me-1"></i>
                                    Tahun ini
                                </div>
                                <div class="card-body"><canvas id="myPieChart" width="170%" height="40"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card bg-white text-primary shadow border border-primary mb-4">
                                <div class="card-body dm-sans-medium">
                                    Kecamatan dengan Jumlah Laporan Terbanyak
                                </div>
                                <div class="card-body">
                                    <div class="display-2 space-grotesk-semi-bold"><?php echo $kecamatanTerbanyak['kecamatan']; ?></div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Laporan: <?php echo $kecamatanTerbanyak['jumlah_laporan']; ?></div>
                                </div>
                            </div>
                            <div class="card bg-white text-primary shadow border border-primary">
                                <div class="card-body dm-sans-medium">
                                    Kecamatan dengan Jumlah Laporan Paling Sedikit
                                </div>
                                <div class="card-body">
                                    <div class="display-2 space-grotesk-semi-bold"><?php echo $kecamatanTersedikit['kecamatan']; ?></div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Laporan: <?php echo $kecamatanTersedikit['jumlah_laporan']; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card-header dm-sans-medium">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Heatmap Kepadatan Laporan
                            </div>
                            <div class="card-body">
                                <div id="mapid" style="width: 100%; height: 430px;"></div>
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
        // Data Pie Chart dari file data_tahun_ini.php
        var ctxPie = document.getElementById("myPieChart").getContext('2d');
        var myPieChart = new Chart(ctxPie, {
            type: 'pie', // Mengubah tipe chart menjadi doughnut
            data: {
                labels: ["Belum Dikerjakan", "Sedang Dikerjakan", "Selesai Dikerjakan", "Tidak Selesai"],
                datasets: [{
                    data: [<?php echo $status_laporan['belum_dikerjakan']; ?>,
                        <?php echo $status_laporan['sedang_dikerjakan']; ?>,
                        <?php echo $status_laporan['selesai_dikerjakan']; ?>,
                        <?php echo $status_laporan['tidak_selesai']; ?>],
                    backgroundColor: ['#4267EB', '#F6C951', '#8FECFF', '#ED9895'],
                }]
            },
            // options: {
            //     cutoutPercentage: 60 // Mengatur persentase potongan bagian tengah untuk tampilan donut
            // }
        });

        // Data untuk Heatmap
        var dataHeatmap = [
            <?php foreach ($data as $d): ?>
                [<?php echo $d['lat']; ?>, <?php echo $d['lng']; ?>, 1],
            <?php endforeach; ?>
        ];

        // Heatmap untuk Kepadatan Laporan
        var map = L.map('mapid').setView([-6.2, 106.8], 11); // Fokus pada Jakarta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var heat = L.heatLayer(dataHeatmap, {radius: 25}).addTo(map);
    </script>
</body>
</html>
