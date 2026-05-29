<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= url('Styles/index.css') ?>">
</head>
<body>
    <?php
        $nav_items = [
            'dashboard' => [
                'url' => 'dashboard/beranda',
                'icon' => 'bi-grid-fill',
                'label' => 'Dashboard',
                'section' => 'utama',
                'badge' => null
            ],
            'peserta' => [
                'url' => 'dashboard/kelola-peserta',
                'icon' => 'bi-people-fill',
                'label' => 'Manajemen Peserta',
                'section' => 'utama',
                'badge' => $total_peserta ?? 60
            ],    'tryout' => [
                'url' => 'dashboard/kelola-tryout',
                'icon' => 'bi-journal-text',
                'label' => 'Manajemen Tryout',
                'section' => 'utama',
                'badge' => null
            ],
            'soal' => [
                'url' => 'dashboard/kelola-soal',
                'icon' => 'bi-question-circle-fill',
                'label' => 'Bank Soal',
                'section' => 'utama',
                'badge' => $total_soal ?? 330,
                'badge_class' => 'gold'
            ],
            // Laporan
            'nilai' => [
                'url' => 'dashboard/nilai-hasil',
                'icon' => 'bi-bar-chart-fill',
                'label' => 'Nilai & Hasil',
                'section' => 'laporan',
                'badge' => null
            ],
            'laporan' => [
                'url' => 'dashboard/laporan-rekap',
                'icon' => 'bi-file-earmark-bar-graph-fill',
                'label' => 'Laporan Rekap',
                'section' => 'laporan',
                'badge' => null
            ],
            // Sistem
            'pengaturan' => [
                'url' => 'dashboard/pengaturan',
                'icon' => 'bi-gear-fill',
                'label' => 'Pengaturan',
                'section' => 'sistem',
                'badge' => null
            ]
        ];
        $active_menu = $active_menu ?? 'beranda';
        include BASE_PATH . "/app/Tampilan/Widget/dashboard/sidebar.php";
    ?>
    <div class="main-wrap">
        <?php
            include $topbar;
            include $content;

        ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>