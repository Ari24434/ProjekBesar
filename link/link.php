<?php

require BASE_PATH . '/app/Kontrol//Publik/LandingPage.php';
require BASE_PATH . '/app/Kontrol//Users/UsersPage.php';
require BASE_PATH . '/app/Kontrol//Admin/AdminPage.php';

get('/', 'LPBeranda');
get('/login', 'Login');
get('/user/beranda', 'UBeranda');
get('/user/daftar-tryout', 'UDTryout');
get('/user/riwayat', 'URiwayat');
get('/user/profil', 'UProfil');
get('/dashboard/beranda', 'ABeranda');
get('/dashboard/kelola-tryout', 'AKelolaTryout');
get('/dashboard/kelola-soal', 'AKelolaSoal');
get('/dashboard/kelola-peserta', 'AKelolaPeserta');
get('/dashboard/analisis', 'AAnalisis');
get('/dashboard/nilai-hasil', 'ANilaiHasil');
get('/dashboard/laporan-rekap', 'ALaporanRekap');
get('/dashboard/pengaturan', 'APengaturan');
?>