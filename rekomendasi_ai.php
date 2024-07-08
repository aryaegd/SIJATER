<?php
include 'koneksi.php';
include 'groq_api.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah ada input dari textarea
    $inputData = trim($_POST['inputData']);
    $temperature = 0.8;  // Tetapkan suhu default di sini

    // Query untuk mendapatkan data laporan jalan rusak
    $sql = "
        SELECT kecamatan, COUNT(*) as jumlah_laporan
        FROM laporan
        JOIN jalan ON laporan.id_jalan = jalan.id_jalan
        WHERE YEAR(tgl_laporan) = YEAR(CURDATE())
        GROUP BY kecamatan
        ORDER BY jumlah_laporan DESC
    ";
    $outputSQL = $conn->query($sql);

    // Menyusun prompt default untuk LLM dalam Bahasa Indonesia
    $llmPrompt = "Analisis data laporan kerusakan jalan berikut: ";
    while ($row = $outputSQL->fetch_assoc()) {
        $llmPrompt .= "Kecamatan: " . $row['kecamatan'] . ", Jumlah Laporan: " . $row['jumlah_laporan'] . "; ";
    }

    // Tambahkan pertanyaan custom jika ada
    if (!empty($inputData)) {
        $llmPrompt .= "Pertanyaan pengguna: " . $inputData . "; ";
    }

    $llmPrompt .= "Tentukan kecamatan mana yang terdampak paling parah, masalah apa yang paling banyak dilaporkan di kecamatan tersebut (misalnya jalan berlubang, rusaknya rambu, genangan air, dll.), dan berikan saran serta solusi yang tepat. Mohon jawab dalam bahasa Indonesia.";

    $messages = [
        [
            'role' => 'system',
            'content' => 'Anda adalah ahli dalam masalah perbaikan jalan. Berikan analisis mendalam, saran, dan solusi terkait masalah jalan yang ada terkait analisa anda. Dahulukan dari kecamatan paling parah hingga ringan.'
        ],
        [
            'role' => 'user',
            'content' => $llmPrompt,
        ]
    ];

    // Memanggil fungsi Groq API dengan parameter suhu
    $ai_response = callGroqAPI($messages, $temperature);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>SIJATER - Rekomendasi AI</title>
    <link rel="icon" href="assets/img/SIJATER Logo.png" type="image/png">  
    <link href="css/font.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/sidebar-styles.css" rel="stylesheet" />
    <link href="css/colors.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-header">
    <a class="navbar-brand ps-3 space-grotesk-semi-bold" href="index.php">
        <img src="assets/img/SIJATER Logo.png" alt="SIJATER Logo" class="me-2">
        SIJATER
    </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
                    <h1 class="mt-4 space-grotesk-semi-bold">Rekomendasi AI</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">
                            AI akan menganalisa dan memberi saran tentang perbaikan jalan di sini. Jika Anda ingin membuat perintah sesuai keinginan, ketikkan perintah di textbox.
                            Jika tidak, anda bisa langsung meminta analisa data pada tombol Dapatkan Rekomendasi.
                        </li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title space-grotesk-regular">Dapatkan Rekomendasi AI untuk Penanganan Jalan Rusak</h5>
                            <form method="post">
                                <div class="mb-3">
                                    <label for="inputData" class="form-label">Anda dapat menambahkan perintah sesuai keinginan di sini</label>
                                    <textarea class="form-control" id="inputData" name="inputData" rows="3" placeholder="Masukkan perintah di sini..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Dapatkan Rekomendasi</button>
                            </form>
                            <hr>
                            <h5 class="card-title space-grotesk-semi-bold">Rekomendasi AI</h5>
                            <div id="aiRecommendation" class="alert alert-info dm-sans-medium" role="alert">
                                <?php if (isset($ai_response)) echo nl2br($ai_response); else echo "Rekomendasi AI akan muncul di sini."; ?>
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
                            <a href="https://www.instagram.com/aryaegd" target="_blank">Privacy Policy</a>
                            &middot;
                            <a href="https://www.instagram.com/aryaegd" target="_blank">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
