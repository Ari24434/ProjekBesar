<?php
function ABeranda(){
    $title = "OC Tryout - Dashboard Admin";
    $topbarTitle = "Dashboard Admin";
    $active_menu = 'beranda';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/beranda-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function AKelolaTryout(){
    $title = "OC Tryout - Kelola Tryout";
    $topbarTitle = "Kelola Tryout";
    $active_menu = 'kelola-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/kelola-tryout-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function AKelolaSoal(){
    $title = "OC Tryout - Kelola Soal";
    $topbarTitle = "Kelola Soal";
    $active_menu = 'kelola-soal';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/kelola-soal-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function AKelolaPeserta(){
    $title = "OC Tryout - Kelola Peserta";
    $topbarTitle = "Kelola Peserta";
    $active_menu = 'kelola-peserta';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/kelola-peserta-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function AAnalisis(){
    $title = "OC Tryout - Analisis";
    $topbarTitle = "Analisis";
    $active_menu = 'analisis';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/analisis-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function ANilaiHasil(){
    $title = "OC Tryout - Nilai & Hasil";
    $topbarTitle = "Nilai & Hasil";
    $active_menu = 'nilai';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/nilai-hasil-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function ALaporanRekap(){
    $title = "OC Tryout - Laporan Rekap";
    $topbarTitle = "Laporan Rekap";
    $active_menu = 'laporan';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/laporan-rekap.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

function APengaturan(){
    $title = "OC Tryout - Pengaturan";
    $topbarTitle = "Pengaturan";
    $active_menu = 'pengaturan';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/dashboard/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/dashboard/pengaturan-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/dashboard-layout.php";
}

?>