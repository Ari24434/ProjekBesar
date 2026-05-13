<?php

require BASE_PATH . '/app/Kontrol//Publik/LandingPage.php';

get('/', 'LPBeranda');
get('/login', 'Login');
get('/Tentang', 'Tentang');

?>