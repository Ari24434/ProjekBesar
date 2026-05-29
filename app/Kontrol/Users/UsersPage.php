<?php
function UBeranda(){
    $title = "OC Tryout - Beranda";
    $topbarTitle = "Dashboard";
    $active_menu = 'beranda';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/beranda-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UDTryout(){
    $title = "OC Tryout - Daftar Tryout";
    $topbarTitle = "Daftar Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/users/daftar-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function URiwayat(){
    $title = "OC Tryout - Riwayat Tryout";
    $topbarTitle = "Riwayat Tryout";
    $active_menu = 'riwayat';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/riwayat-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UProfil(){
    $title = "OC Tryout - Profil";
    $topbarTitle = "Profil Saya";
    $active_menu = 'profil';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/profil-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UHTryout(){
    $title = "OC Tryout - Hasil Tryout";
    $topbarTitle = "Hasil Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/hasil-tryout.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function USTryout(){
    $title = "OC Tryout - Soal Tryout";
    $topbarTitle = "Soal Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/soal-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

?>