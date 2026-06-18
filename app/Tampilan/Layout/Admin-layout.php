<!DOCTYPE html>
<html lang="en">
<?php include BASE_PATH . "/app/Tampilan/Layout/partials/document-head.php"; ?>
<body class="layout-admin">
    <?php
        $adminFlash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $total_peserta = 0;
        $total_soal = 0;

        try {
            $totalPesertaRow = db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'peserta'");
            $totalSoalRow = db_fetch("SELECT COUNT(*) AS total FROM soal");

            $total_peserta = (int) ($totalPesertaRow['total'] ?? 0);
            $total_soal = (int) ($totalSoalRow['total'] ?? 0);
        } catch (Throwable $e) {
            $total_peserta = 0;
            $total_soal = 0;
        }

        $nav_items = [
            'beranda' => [
                'url' => 'Admin/beranda',
                'icon' => 'bi-grid-fill',
                'label' => 'Dashboard',
                'section' => 'utama',
                'badge' => null
            ],
            'peserta' => [
                'url' => 'Admin/kelola-peserta',
                'icon' => 'bi-people-fill',
                'label' => 'Manajemen Peserta',
                'section' => 'utama',
                'badge' => $total_peserta
            ],    'tryout' => [
                'url' => 'Admin/kelola-tryout',
                'icon' => 'bi-journal-text',
                'label' => 'Manajemen Tryout',
                'section' => 'utama',
                'badge' => null
            ],
            'soal' => [
                'url' => 'Admin/kelola-soal',
                'icon' => 'bi-question-circle-fill',
                'label' => 'Bank Soal',
                'section' => 'utama',
                'badge' => $total_soal,
                'badge_class' => 'gold'
            ],
            // Laporan
            'nilai' => [
                'url' => 'Admin/nilai-hasil',
                'icon' => 'bi-bar-chart-fill',
                'label' => 'Nilai & Hasil',
                'section' => 'laporan',
                'badge' => null
            ],
            'analisis' => [
                'url' => 'Admin/analisis',
                'icon' => 'bi-graph-up-arrow',
                'label' => 'Analisis',
                'section' => 'laporan',
                'badge' => null
            ],
            'laporan' => [
                'url' => 'Admin/laporan-rekap',
                'icon' => 'bi-file-earmark-bar-graph-fill',
                'label' => 'Laporan Rekap',
                'section' => 'laporan',
                'badge' => null
            ],
            // Sistem
            'pengaturan' => [
                'url' => 'Admin/pengaturan',
                'icon' => 'bi-gear-fill',
                'label' => 'Pengaturan',
                'section' => 'sistem',
                'badge' => null
            ]
        ];
        $active_menu = $active_menu ?? 'beranda';
        include BASE_PATH . "/app/Tampilan/Widget/Admin/sidebar.php";
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
    <?php if ($adminFlash): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flash = <?= json_encode($adminFlash, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const isError = (flash.type || '').toLowerCase() === 'error';

            if (window.Swal) {
                Swal.fire({
                    icon: isError ? 'error' : 'success',
                    title: isError ? 'Operasi Gagal' : 'Operasi Berhasil',
                    text: flash.message || '',
                    confirmButtonText: 'OK',
                    confirmButtonColor: isError ? '#DC2626' : '#1E54B7',
                    timer: isError ? undefined : 2600,
                    timerProgressBar: !isError
                });
                return;
            }

            if (window.showToast) {
                window.showToast(flash.message || '', isError ? 'error' : 'success');
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
