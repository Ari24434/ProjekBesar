<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD
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
    <script>
        window.APP_BASE_URL = '<?= rtrim(BASE_URL, '/') ?>';
    </script>
=======
<?php include BASE_PATH . "/app/Tampilan/Layout/partials/document-head.php"; ?>
<body class="layout-user">
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
    <?php
        $userFlash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
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
                'badge' => null
            ],
            'riwayat' => [
                'url' => 'user/riwayat',
                'icon' => 'bi-clock-history',
                'label' => 'Riwayat',
                'badge' => null
            ],
            'profil' => [
                'url' => 'user/profil',
                'icon' => 'bi-person-fill',
                'label' => 'Profil & Analisis',
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
    
<<<<<<< HEAD
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= url('Scripts/app.js') ?>"></script>
=======
    <?php
        $includeSweetAlert = true;
        include BASE_PATH . "/app/Tampilan/Layout/partials/document-scripts.php";
    ?>
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
    <?php if ($userFlash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flash = <?= json_encode($userFlash, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const type = (flash.type || 'info').toLowerCase();

            if (window.showToast) {
                window.showToast(flash.message || '', type);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
