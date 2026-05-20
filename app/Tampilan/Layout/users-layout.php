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
            'beranda' => [
                'url' => 'user/beranda',
                'icon' => 'bi-house-fill',
                'label' => 'Beranda',
                'badge' => null
            ],
            'daftar-tryout' => [
                'url' => 'user/daftar-tryout',
                'icon' => 'bi-journal-text',
                'label' => 'Daftar Tryout',
                'badge' => $tryout_tersedia ?? 3
            ],
            'riwayat' => [
                'url' => 'user/riwayat',
                'icon' => 'bi-clock-history',
                'label' => 'Riwayat',
                'badge' => null
            ],
            'analisis' => [
                'url' => 'user/analisis',
                'icon' => 'bi-bar-chart-line-fill',
                'label' => 'Analisis',
                'badge' => null
            ],
            'profil' => [
                'url' => 'user/profil',
                'icon' => 'bi-person-fill',
                'label' => 'Profil Saya',
                'badge' => null
            ]
        ];
        $active_menu = $active_menu ?? 'beranda';
        include BASE_PATH . "/app/Tampilan/Widget/users/sidebar.php";
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