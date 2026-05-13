<?php

function LPBeranda(){
    $title = "OC Tryout - Beranda";
    $navbar = BASE_PATH . "/app/Tampilan/Widget/navbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/LandingPage/page.php";
    $footer = BASE_PATH . "/app/Tampilan/Widget/footer.php";
    include BASE_PATH . "/app/Tampilan/Layout/main-layout.php";
}

function Tentang(){
    $title = "OC Tryout - Tentang";
    $navbar = BASE_PATH . "/app/Tampilan/Widget/navbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/LandingPage/about.html";
    $footer = BASE_PATH . "/app/Tampilan/Widget/footer.php";
    include BASE_PATH . "/app/Tampilan/Layout/main-layout.php";
}

function Login(){
    $title = "OC Tryout - Login";
    $content = BASE_PATH . "/app/Tampilan/Halaman/LandingPage/LoginPage.php";  
    include BASE_PATH . "/app/Tampilan/Layout/login-layout.php";
}

?>