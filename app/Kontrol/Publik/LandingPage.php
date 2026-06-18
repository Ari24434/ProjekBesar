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
    $loginFlash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    $title = "OC Tryout - Login";
    $content = BASE_PATH . "/app/Tampilan/Halaman/LandingPage/LoginPage.php";  
    include BASE_PATH . "/app/Tampilan/Layout/login-layout.php";
}

function LoginStore(){
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        auth_flash('Email dan kata sandi wajib diisi dengan benar.', 'error');
        auth_redirect(BASE_URL . '/login');
    }

    if (!auth_attempt($email, $password)) {
        auth_flash('Email atau kata sandi salah, atau akun sedang nonaktif.', 'error');
        auth_redirect(BASE_URL . '/login');
    }

    auth_redirect(auth_home_url());
}

function Logout(){
    auth_logout();
    auth_flash('Kamu sudah keluar dari sistem.', 'success');
    auth_redirect(BASE_URL . '/login');
}

?>
