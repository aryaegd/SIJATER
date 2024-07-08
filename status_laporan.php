<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SIJATER - Status Laporan</title>
    <link rel="icon" href="assets/img/SIJATER Logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/font.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/sidebar-styles.css" rel="stylesheet" />
    <link href="css/colors.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                    <h1 class="mt-4 space-grotesk-semi-bold">Status Laporan</h1>
                    <div class="card mb-4">
                        <div class="card-body dm-sans-medium">
                            Berikut adalah status laporan kerusakan jalan yang telah diverifikasi.
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header dm-sans-medium">
                            <i class="fas fa-table me-1"></i>
                            Data Laporan
                        </div>
                        <div class="card-body dm-sans-medium">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>TANGGAL</th>
                                        <th>NAMA JALAN</th>
                                        <th>DESKRIPSI</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT l.id_laporan, l.tgl_laporan, j.nama as nama_jalan, l.deskripsi, s.status 
                                            FROM laporan l 
                                            JOIN jalan j ON l.id_jalan = j.id_jalan
                                            JOIN status_laporan s ON l.id_status = s.id_status
                                            WHERE l.id_status IS NOT NULL AND YEAR(l.tgl_laporan) = YEAR(CURDATE())";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["id_laporan"]. "</td>
                                                    <td>" . $row["tgl_laporan"]. "</td>
                                                    <td>" . $row["nama_jalan"]. "</td>
                                                    <td>" . $row["deskripsi"]. "</td>
                                                    <td>" . $row["status"]. "</td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Tidak ada data ditemukan</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
