<?php

require BASE_PATH . '/app/Kontrol//Publik/LandingPage.php';
require BASE_PATH . '/app/Kontrol//Users/UsersPage.php';
require BASE_PATH . '/app/Kontrol//Admin/AdminPage.php';

get('/', 'LPBeranda');
get('/login', 'Login');
post('/login', 'LoginStore');
post('/logout', 'Logout');
get('/user/beranda', 'UBeranda');
get('/user/daftar-tryout', 'UDTryout');
get('/user/hasil-tryout', 'UHTryout');
get('/user/soal-tryout', 'USTryout');
post('/user/tryout/start', 'UStartTryout');
post('/user/tryout/submit', 'USubmitTryout');
get('/user/riwayat', 'URiwayat');
get('/user/analisis', 'UAnalisis');
get('/user/profil', 'UProfil');
get('/Admin/beranda', 'ABeranda');
get('/Admin/kelola-tryout', 'AKelolaTryout');
get('/Admin/kelola-soal', 'AKelolaSoal');
get('/Admin/tambah-soal', 'ATambahSoal');
get('/Admin/edit-soal', 'AEditSoal');
post('/Admin/soal', 'AStoreSoal');
put('/Admin/soal', 'AUpdateSoal');
delete('/Admin/soal', 'ADeleteSoal');
get('/Admin/kelola-peserta', 'AKelolaPeserta');
get('/Admin/tambah-peserta', 'ATambahPeserta');
get('/Admin/edit-peserta', 'AEditPeserta');
post('/Admin/peserta', 'AStorePeserta');
put('/Admin/peserta', 'AUpdatePeserta');
delete('/Admin/peserta', 'ADeletePeserta');
get('/Admin/buat-tryout', 'ABuatTryout');
post('/Admin/tryout', 'AStoreTryout');
put('/Admin/tryout', 'AUpdateTryout');
delete('/Admin/tryout', 'ADeleteTryout');
get('/Admin/hasil-tryout', 'AHasilTryout');
get('/Admin/edit-tryout', 'AEditTryout');
get('/Admin/analisis', 'AAnalisis');
get('/Admin/nilai-hasil', 'ANilaiHasil');
get('/Admin/laporan-rekap', 'ALaporanRekap');
get('/Admin/pengaturan', 'APengaturan');
post('/Admin/pengaturan/profil', 'AUpdateAdminProfile');
post('/Admin/pengaturan/password', 'AUpdateAdminPassword');
post('/Admin/pengaturan/tryout', 'AUpdateTryoutSettings');
?>
