<!DOCTYPE html>
<html lang="en">
<?php include BASE_PATH . "/app/Tampilan/Layout/partials/document-head.php"; ?>
<body class="layout-user">
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
    
    <?php
        $includeSweetAlert = true;
        include BASE_PATH . "/app/Tampilan/Layout/partials/document-scripts.php";
    ?>
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
