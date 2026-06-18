<?php
function UBeranda(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $userStats = [
        'total_tryout' => 0,
        'best_score' => 0,
        'avg_score' => 0,
        'passed_count' => 0,
        'score_delta' => 0,
    ];
    $latestResult = null;
    $availableTryouts = [];
    $recentResults = [];
    $userDashboardError = null;

    try {
        if ($user) {
            $idUser = (int) $user['id_user'];
            $statsRow = db_fetch("
                SELECT
                    COUNT(*) AS total_tryout,
                    COALESCE(MAX(total_nilai), 0) AS best_score,
                    COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_score,
                    SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) AS passed_count
                FROM hasil
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
            ", [$idUser]);

            if ($statsRow) {
                $userStats = [
                    'total_tryout' => (int) ($statsRow['total_tryout'] ?? 0),
                    'best_score' => (float) ($statsRow['best_score'] ?? 0),
                    'avg_score' => (float) ($statsRow['avg_score'] ?? 0),
                    'passed_count' => (int) ($statsRow['passed_count'] ?? 0),
                    'score_delta' => 0,
                ];
            }

            $recentScores = db_fetch_all("
                SELECT total_nilai
                FROM hasil h
                JOIN tryout t ON t.id_tryout = h.id_tryout
                WHERE h.id_user = ?
                  AND h.status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(h.waktu_selesai, h.created_at) DESC, t.tanggal_mulai DESC
                LIMIT 2
            ", [$idUser]);

            if (count($recentScores) >= 2) {
                $userStats['score_delta'] = (float) $recentScores[0]['total_nilai'] - (float) $recentScores[1]['total_nilai'];
            }

            $latestResult = db_fetch("
                SELECT
                    h.*,
                    t.nama_tryout,
                    t.tanggal_mulai
                FROM hasil h
                JOIN tryout t ON t.id_tryout = h.id_tryout
                WHERE h.id_user = ?
                  AND h.status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(h.waktu_selesai, h.created_at) DESC, t.tanggal_mulai DESC
                LIMIT 1
            ", [$idUser]);

            $availableTryouts = db_fetch_all("
                SELECT
                    t.*,
                    COUNT(ts.id_soal) AS total_soal,
                    h.id_hasil,
                    h.status_pengerjaan,
                    h.total_nilai,
                    h.lulus_total
                FROM tryout t
                LEFT JOIN tryout_soal ts ON ts.id_tryout = t.id_tryout
                LEFT JOIN hasil h ON h.id_tryout = t.id_tryout AND h.id_user = ?
                WHERE t.status IN ('aktif', 'selesai')
                GROUP BY t.id_tryout, h.id_hasil, h.status_pengerjaan, h.total_nilai, h.lulus_total
                ORDER BY FIELD(t.status, 'aktif', 'selesai'), t.tanggal_mulai DESC, t.id_tryout DESC
                LIMIT 4
            ", [$idUser]);

            $recentResults = db_fetch_all("
                SELECT *
                FROM v_rekap_nilai
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(waktu_selesai, waktu_mulai) DESC, tanggal_mulai DESC
                LIMIT 5
            ", [$idUser]);
        }
    } catch (Throwable $e) {
        $userDashboardError = 'Data dashboard peserta belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Beranda";
    $topbarTitle = "Dashboard";
    $active_menu = 'beranda';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/beranda-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UDTryout(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $tryoutRows = [];
    $tryoutError = null;

    try {
        $tryoutRows = db_fetch_all("
            SELECT
                t.*,
                COUNT(ts.id_soal) AS total_soal,
                h.id_hasil,
                h.status_pengerjaan,
                h.total_nilai,
                h.lulus_total,
                h.waktu_selesai
            FROM tryout t
            LEFT JOIN tryout_soal ts ON ts.id_tryout = t.id_tryout
            LEFT JOIN hasil h ON h.id_tryout = t.id_tryout AND h.id_user = ?
            WHERE t.status = 'aktif'
              AND NOW() BETWEEN t.tanggal_mulai AND t.tanggal_selesai
            GROUP BY t.id_tryout, h.id_hasil, h.status_pengerjaan, h.total_nilai, h.lulus_total, h.waktu_selesai
            ORDER BY t.tanggal_mulai DESC, t.id_tryout DESC
        ", [(int) ($user['id_user'] ?? 0)]);
    } catch (Throwable $e) {
        $tryoutError = 'Daftar tryout belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Daftar Tryout";
    $topbarTitle = "Daftar Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/users/daftar-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function URiwayat(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $historyRows = [];
    $historyStats = [
        'total_tryout' => 0,
        'passed_count' => 0,
        'best_score' => 0,
        'avg_score' => 0,
    ];
    $historyError = null;

    try {
        if ($user) {
            $idUser = (int) $user['id_user'];
            $historyRows = db_fetch_all("
                SELECT *
                FROM v_rekap_nilai
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(waktu_selesai, waktu_mulai) DESC, tanggal_mulai DESC, id_hasil DESC
            ", [$idUser]);

            $statsRow = db_fetch("
                SELECT
                    COUNT(*) AS total_tryout,
                    SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) AS passed_count,
                    COALESCE(MAX(total_nilai), 0) AS best_score,
                    COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_score
                FROM v_rekap_nilai
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
            ", [$idUser]);

            if ($statsRow) {
                $historyStats = [
                    'total_tryout' => (int) ($statsRow['total_tryout'] ?? 0),
                    'passed_count' => (int) ($statsRow['passed_count'] ?? 0),
                    'best_score' => (float) ($statsRow['best_score'] ?? 0),
                    'avg_score' => (float) ($statsRow['avg_score'] ?? 0),
                ];
            }
        }
    } catch (Throwable $e) {
        $historyError = 'Data riwayat belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Riwayat Tryout";
    $topbarTitle = "Riwayat Tryout";
    $active_menu = 'riwayat';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/riwayat-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function user_current_context(): array{
    $sessionUser = auth_user();
    $user = null;

    if ($sessionUser && ($sessionUser['role'] ?? '') === 'peserta') {
        $user = db_fetch(
            "SELECT id_user, nama, email, no_hp, role, status, created_at, last_login
             FROM user
             WHERE id_user = ? AND role = 'peserta' AND status = 'aktif'",
            [(int) $sessionUser['id_user']]
        );
    }

    return [
        'user' => $user,
        'initial' => user_initial($user['nama'] ?? 'P'),
        'first_name' => user_first_name($user['nama'] ?? 'Peserta'),
    ];
}

function user_initial(string $name): string{
    $name = trim($name);
    return $name !== '' ? strtoupper(substr($name, 0, 1)) : 'P';
}

function user_first_name(string $name): string{
    $parts = preg_split('/\s+/', trim($name));
    return $parts && $parts[0] !== '' ? $parts[0] : 'Peserta';
}

function UAnalisis(){
    header('Location: ' . BASE_URL . '/user/profil');
    exit;
}

function UProfil(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $profileStats = [
        'total_tryout' => 0,
        'passed_count' => 0,
        'best_score' => 0,
        'avg_score' => 0,
        'first_score' => 0,
        'last_score' => 0,
        'improvement' => 0,
        'pass_rate' => 0,
    ];
    $profileCategoryStats = [
        'TWK' => ['avg' => 0, 'min' => 65, 'max' => 150, 'color' => 'var(--blue-main)'],
        'TIU' => ['avg' => 0, 'min' => 80, 'max' => 175, 'color' => 'var(--emerald)'],
        'TKP' => ['avg' => 0, 'min' => 166, 'max' => 225, 'color' => 'var(--gold)'],
    ];
    $profileResults = [];
    $profileError = null;

    try {
        if ($user) {
            $idUser = (int) $user['id_user'];

            $profileResults = db_fetch_all("
                SELECT *
                FROM v_rekap_nilai
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY tanggal_mulai ASC, COALESCE(waktu_selesai, waktu_mulai) ASC
            ", [$idUser]);

            $statsRow = db_fetch("
                SELECT
                    COUNT(*) AS total_tryout,
                    SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) AS passed_count,
                    COALESCE(MAX(total_nilai), 0) AS best_score,
                    COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_score,
                    COALESCE(ROUND(AVG(nilai_twk), 1), 0) AS avg_twk,
                    COALESCE(ROUND(AVG(nilai_tiu), 1), 0) AS avg_tiu,
                    COALESCE(ROUND(AVG(nilai_tkp), 1), 0) AS avg_tkp
                FROM v_rekap_nilai
                WHERE id_user = ?
                  AND status_pengerjaan IN ('selesai', 'timeout')
            ", [$idUser]);

            if ($statsRow) {
                $profileStats['total_tryout'] = (int) ($statsRow['total_tryout'] ?? 0);
                $profileStats['passed_count'] = (int) ($statsRow['passed_count'] ?? 0);
                $profileStats['best_score'] = (float) ($statsRow['best_score'] ?? 0);
                $profileStats['avg_score'] = (float) ($statsRow['avg_score'] ?? 0);
                $profileStats['pass_rate'] = $profileStats['total_tryout'] > 0
                    ? round(($profileStats['passed_count'] / $profileStats['total_tryout']) * 100, 1)
                    : 0;

                $profileCategoryStats['TWK']['avg'] = (float) ($statsRow['avg_twk'] ?? 0);
                $profileCategoryStats['TIU']['avg'] = (float) ($statsRow['avg_tiu'] ?? 0);
                $profileCategoryStats['TKP']['avg'] = (float) ($statsRow['avg_tkp'] ?? 0);
            }

            if ($profileResults) {
                $first = reset($profileResults);
                $last = end($profileResults);
                $profileStats['first_score'] = (float) $first['total_nilai'];
                $profileStats['last_score'] = (float) $last['total_nilai'];
                $profileStats['improvement'] = $profileStats['last_score'] - $profileStats['first_score'];
            }
        }
    } catch (Throwable $e) {
        $profileError = 'Data profil belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Profil";
    $topbarTitle = "Profil & Analisis";
    $active_menu = 'profil';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/profil-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UHTryout(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $idHasil = (int) ($_GET['id'] ?? 0);
    $result = null;
    $detailRows = [];
<<<<<<< HEAD
    $resultError = null;

    try {
        if ($idHasil > 0) {
            $result = db_fetch("
                SELECT *
                FROM v_rekap_nilai
                WHERE id_hasil = ? AND id_user = ?
=======
    $detailOptions = [];
    $resultError = null;

    try {
        app_ensure_soal_review_schema();

        if ($idHasil > 0) {
            $result = db_fetch("
                SELECT v.*, t.jml_soal_twk, t.jml_soal_tiu, t.jml_soal_tkp
                FROM v_rekap_nilai v
                JOIN tryout t ON t.id_tryout = v.id_tryout
                WHERE v.id_hasil = ? AND v.id_user = ?
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
                LIMIT 1
            ", [$idHasil, (int) $user['id_user']]);
        } else {
            $result = db_fetch("
<<<<<<< HEAD
                SELECT *
                FROM v_rekap_nilai
                WHERE id_user = ? AND status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(waktu_selesai, waktu_mulai) DESC, id_hasil DESC
=======
                SELECT v.*, t.jml_soal_twk, t.jml_soal_tiu, t.jml_soal_tkp
                FROM v_rekap_nilai v
                JOIN tryout t ON t.id_tryout = v.id_tryout
                WHERE v.id_user = ? AND v.status_pengerjaan IN ('selesai', 'timeout')
                ORDER BY COALESCE(v.waktu_selesai, v.waktu_mulai) DESC, v.id_hasil DESC
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
                LIMIT 1
            ", [(int) $user['id_user']]);
        }

        if ($result) {
            $detailRows = db_fetch_all("
                SELECT
                    dh.*,
                    s.pertanyaan,
<<<<<<< HEAD
                    k.kode AS kategori,
                    okj.kode_opsi AS kode_kunci,
                    od.teks_opsi AS teks_dipilih,
                    okj.teks_opsi AS teks_kunci
=======
                    s.gambar,
                    s.subtopik,
                    s.pembahasan,
                    k.kode AS kategori,
                    okj.kode_opsi AS kode_kunci,
                    od.teks_opsi AS teks_dipilih,
                    od.gambar_opsi AS gambar_dipilih,
                    okj.teks_opsi AS teks_kunci,
                    okj.gambar_opsi AS gambar_kunci
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
                FROM detail_hasil dh
                JOIN soal s ON s.id_soal = dh.id_soal
                JOIN kategori k ON k.id_kategori = s.id_kategori
                LEFT JOIN opsi_jawaban od ON od.id_opsi = dh.id_opsi_dipilih
                LEFT JOIN opsi_jawaban okj ON okj.id_soal = s.id_soal AND okj.is_kunci = 1
                WHERE dh.id_hasil = ?
                ORDER BY dh.urutan_tampil ASC, dh.id_detail ASC
            ", [(int) $result['id_hasil']]);
<<<<<<< HEAD
=======

            $soalIds = array_values(array_unique(array_map(static fn($row) => (int) $row['id_soal'], $detailRows)));

            if ($soalIds) {
                $placeholders = implode(',', array_fill(0, count($soalIds), '?'));
                $optionRows = db_fetch_all("
                    SELECT id_opsi, id_soal, kode_opsi, teks_opsi, gambar_opsi, poin, is_kunci
                    FROM opsi_jawaban
                    WHERE id_soal IN ({$placeholders})
                    ORDER BY id_soal ASC, kode_opsi ASC
                ", $soalIds);

                foreach ($optionRows as $option) {
                    $detailOptions[(int) $option['id_soal']][] = $option;
                }
            }
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
        }
    } catch (Throwable $e) {
        $resultError = 'Data hasil belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Hasil Tryout";
    $topbarTitle = "Hasil Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/hasil-tryout.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function USTryout(){
    $userContext = user_current_context();
    $user = $userContext['user'];
    $idHasil = (int) ($_GET['id'] ?? 0);
    $exam = null;
    $examQuestions = [];
    $examError = null;

    try {
        $exam = db_fetch("
<<<<<<< HEAD
            SELECT h.*, t.nama_tryout, t.deskripsi, t.waktu, t.acak_opsi, t.jml_soal_twk, t.jml_soal_tiu, t.jml_soal_tkp
=======
            SELECT
                h.*,
                t.nama_tryout,
                t.deskripsi,
                t.waktu,
                t.tanggal_selesai,
                t.acak_opsi,
                t.jml_soal_twk,
                t.jml_soal_tiu,
                t.jml_soal_tkp,
                UNIX_TIMESTAMP(NOW()) AS server_now_ts,
                UNIX_TIMESTAMP(h.waktu_mulai) AS waktu_mulai_ts,
                UNIX_TIMESTAMP(t.tanggal_selesai) AS tanggal_selesai_ts
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
            FROM hasil h
            JOIN tryout t ON t.id_tryout = h.id_tryout
            WHERE h.id_hasil = ? AND h.id_user = ? AND h.status_pengerjaan = 'sedang'
            LIMIT 1
        ", [$idHasil, (int) ($user['id_user'] ?? 0)]);

        if (!$exam) {
            user_flash('Sesi tryout tidak ditemukan atau sudah selesai.', 'error');
            user_redirect(BASE_URL . '/user/daftar-tryout');
        }

        $examQuestions = user_exam_public_questions(user_exam_questions((int) $exam['id_tryout'], (bool) $exam['acak_opsi']));
    } catch (Throwable $e) {
        $examError = 'Soal tryout belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Soal Tryout";
    $topbarTitle = "Soal Tryout";
    $active_menu = 'daftar-tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/users/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Users/soal-tryout-user.php";
    include BASE_PATH . "/app/Tampilan/Layout/users-layout.php";
}

function UStartTryout(){
    $user = auth_user();
    $idTryout = (int) ($_POST['id_tryout'] ?? 0);

    if ($idTryout <= 0) {
        user_flash('ID tryout tidak valid.', 'error');
        user_redirect(BASE_URL . '/user/daftar-tryout');
    }

    try {
        $tryout = db_fetch("
            SELECT t.*, COUNT(ts.id_soal) AS total_soal
            FROM tryout t
            LEFT JOIN tryout_soal ts ON ts.id_tryout = t.id_tryout
            WHERE t.id_tryout = ?
              AND t.status = 'aktif'
              AND NOW() BETWEEN t.tanggal_mulai AND t.tanggal_selesai
            GROUP BY t.id_tryout
            LIMIT 1
        ", [$idTryout]);

        if (!$tryout) {
            user_flash('Tryout tidak tersedia atau sudah melewati jadwal.', 'error');
            user_redirect(BASE_URL . '/user/daftar-tryout');
        }

        if ((int) ($tryout['total_soal'] ?? 0) <= 0) {
            user_flash('Tryout belum memiliki soal.', 'error');
            user_redirect(BASE_URL . '/user/daftar-tryout');
        }

        $existing = db_fetch('SELECT id_hasil, status_pengerjaan FROM hasil WHERE id_user = ? AND id_tryout = ?', [(int) $user['id_user'], $idTryout]);

        if ($existing && in_array($existing['status_pengerjaan'], ['selesai', 'timeout'], true)) {
            user_redirect(BASE_URL . '/user/hasil-tryout?id=' . (int) $existing['id_hasil']);
        }

        if ($existing) {
            user_redirect(BASE_URL . '/user/soal-tryout?id=' . (int) $existing['id_hasil']);
        }

        db_execute(
            "INSERT INTO hasil (id_user, id_tryout, waktu_mulai, status_pengerjaan) VALUES (?, ?, NOW(), 'sedang')",
            [(int) $user['id_user'], $idTryout]
        );

        user_redirect(BASE_URL . '/user/soal-tryout?id=' . (int) db()->lastInsertId());
    } catch (Throwable $e) {
        user_flash('Gagal memulai tryout: ' . $e->getMessage(), 'error');
        user_redirect(BASE_URL . '/user/daftar-tryout');
    }
}

function USubmitTryout(){
    $user = auth_user();
    $idHasil = (int) ($_POST['id_hasil'] ?? 0);
    $answers = $_POST['jawaban'] ?? [];

    if ($idHasil <= 0 || !is_array($answers)) {
        user_flash('Data jawaban tidak valid.', 'error');
        user_redirect(BASE_URL . '/user/daftar-tryout');
    }

    try {
        $hasil = db_fetch("
<<<<<<< HEAD
            SELECT h.*, t.waktu
=======
            SELECT h.*, t.waktu, t.tanggal_selesai
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
            FROM hasil h
            JOIN tryout t ON t.id_tryout = h.id_tryout
            WHERE h.id_hasil = ? AND h.id_user = ? AND h.status_pengerjaan = 'sedang'
            LIMIT 1
        ", [$idHasil, (int) $user['id_user']]);

        if (!$hasil) {
            user_flash('Sesi tryout tidak ditemukan atau sudah dikumpulkan.', 'error');
            user_redirect(BASE_URL . '/user/daftar-tryout');
        }

        $questions = user_exam_questions((int) $hasil['id_tryout'], false);
<<<<<<< HEAD
=======
        $tryoutSettings = app_tryout_settings();
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
        $scores = [
            'nilai_twk' => 0,
            'nilai_tiu' => 0,
            'nilai_tkp' => 0,
            'benar_twk' => 0,
            'benar_tiu' => 0,
        ];

<<<<<<< HEAD
        DatabasePool::transaction(function () use ($idHasil, $hasil, $answers, $questions, &$scores) {
=======
        DatabasePool::transaction(function () use ($idHasil, $hasil, $answers, $questions, $tryoutSettings, &$scores) {
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
            db_execute('DELETE FROM detail_hasil WHERE id_hasil = ?', [$idHasil]);

            foreach ($questions as $index => $question) {
                $idSoal = (int) $question['id_soal'];
                $idOpsi = isset($answers[$idSoal]) ? (int) $answers[$idSoal] : 0;
                $chosen = null;

                foreach ($question['opsi'] as $option) {
                    if ((int) $option['id_opsi'] === $idOpsi) {
                        $chosen = $option;
                        break;
                    }
                }

                $kategori = $question['kategori'];
                $poin = $chosen ? (float) $chosen['poin'] : 0;
                $isBenar = null;

                if ($kategori === 'TWK' || $kategori === 'TIU') {
                    $isBenar = $chosen && (int) $chosen['is_kunci'] === 1 ? 1 : 0;
                    $poin = $isBenar ? 5 : 0;

                    if ($isBenar && $kategori === 'TWK') {
                        $scores['benar_twk']++;
                    }

                    if ($isBenar && $kategori === 'TIU') {
                        $scores['benar_tiu']++;
                    }
                }

                $scores['nilai_' . strtolower($kategori)] += $poin;

                db_execute(
                    'INSERT INTO detail_hasil (id_hasil, id_soal, id_opsi_dipilih, jawaban_peserta, poin_didapat, is_benar, urutan_tampil)
                     VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [
                        $idHasil,
                        $idSoal,
                        $chosen ? (int) $chosen['id_opsi'] : null,
                        $chosen['kode_opsi'] ?? null,
                        $poin,
                        $isBenar,
                        $index + 1,
                    ]
                );
            }

            $total = $scores['nilai_twk'] + $scores['nilai_tiu'] + $scores['nilai_tkp'];
<<<<<<< HEAD
            $lulusTwk = $scores['nilai_twk'] >= 65 ? 1 : 0;
            $lulusTiu = $scores['nilai_tiu'] >= 80 ? 1 : 0;
            $lulusTkp = $scores['nilai_tkp'] >= 166 ? 1 : 0;
=======
            $lulusTwk = $scores['nilai_twk'] >= (int) $tryoutSettings['passing_twk'] ? 1 : 0;
            $lulusTiu = $scores['nilai_tiu'] >= (int) $tryoutSettings['passing_tiu'] ? 1 : 0;
            $lulusTkp = $scores['nilai_tkp'] >= (int) $tryoutSettings['passing_tkp'] ? 1 : 0;
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
            $lulusTotal = ($lulusTwk && $lulusTiu && $lulusTkp) ? 1 : 0;
            $status = user_exam_is_timeout($hasil) ? 'timeout' : 'selesai';

            db_execute(
                'UPDATE hasil
                 SET nilai_twk = ?, nilai_tiu = ?, nilai_tkp = ?, total_nilai = ?, benar_twk = ?, benar_tiu = ?,
                     lulus_twk = ?, lulus_tiu = ?, lulus_tkp = ?, lulus_total = ?,
                     waktu_selesai = NOW(), durasi_detik = TIMESTAMPDIFF(SECOND, waktu_mulai, NOW()), status_pengerjaan = ?
                 WHERE id_hasil = ?',
                [
                    $scores['nilai_twk'],
                    $scores['nilai_tiu'],
                    $scores['nilai_tkp'],
                    $total,
                    $scores['benar_twk'],
                    $scores['benar_tiu'],
                    $lulusTwk,
                    $lulusTiu,
                    $lulusTkp,
                    $lulusTotal,
                    $status,
                    $idHasil,
                ]
            );
        });

        user_update_rankings((int) $hasil['id_tryout']);
        user_redirect(BASE_URL . '/user/hasil-tryout?id=' . $idHasil);
    } catch (Throwable $e) {
        user_flash('Gagal mengumpulkan tryout: ' . $e->getMessage(), 'error');
        user_redirect(BASE_URL . '/user/soal-tryout?id=' . $idHasil);
    }
}

function user_exam_questions(int $idTryout, bool $shuffleOptions = false): array{
    $rows = db_fetch_all("
        SELECT
            ts.urutan,
            s.id_soal,
            s.pertanyaan,
            s.gambar,
            k.kode AS kategori,
            oj.id_opsi,
            oj.kode_opsi,
            oj.teks_opsi,
            oj.gambar_opsi,
            oj.poin,
            oj.is_kunci
        FROM tryout_soal ts
        JOIN soal s ON s.id_soal = ts.id_soal
        JOIN kategori k ON k.id_kategori = s.id_kategori
        JOIN opsi_jawaban oj ON oj.id_soal = s.id_soal
        WHERE ts.id_tryout = ?
        ORDER BY ts.urutan ASC, oj.kode_opsi ASC
    ", [$idTryout]);

    $questions = [];

    foreach ($rows as $row) {
        $idSoal = (int) $row['id_soal'];

        if (!isset($questions[$idSoal])) {
            $questions[$idSoal] = [
                'id_soal' => $idSoal,
                'urutan' => (int) $row['urutan'],
                'kategori' => $row['kategori'],
                'pertanyaan' => $row['pertanyaan'],
                'gambar' => $row['gambar'],
                'opsi' => [],
            ];
        }

        $questions[$idSoal]['opsi'][] = [
            'id_opsi' => (int) $row['id_opsi'],
            'kode_opsi' => $row['kode_opsi'],
            'teks_opsi' => $row['teks_opsi'],
            'gambar_opsi' => $row['gambar_opsi'],
            'poin' => (float) $row['poin'],
            'is_kunci' => (int) $row['is_kunci'],
        ];
    }

    $questions = array_values($questions);

    if ($shuffleOptions) {
        foreach ($questions as &$question) {
            shuffle($question['opsi']);
        }
        unset($question);
    }

    return $questions;
}

function user_exam_public_questions(array $questions): array{
    return array_map(function (array $question): array {
        $question['opsi'] = array_map(function (array $option): array {
            return [
                'id_opsi' => $option['id_opsi'],
                'kode_opsi' => $option['kode_opsi'],
                'teks_opsi' => $option['teks_opsi'],
                'gambar_opsi' => $option['gambar_opsi'],
            ];
        }, $question['opsi']);

        return $question;
    }, $questions);
}

function user_exam_is_timeout(array $hasil): bool{
<<<<<<< HEAD
    $started = strtotime($hasil['waktu_mulai'] ?? 'now');
    $duration = max(1, (int) ($hasil['waktu'] ?? 100)) * 60;

    return time() > ($started + $duration);
=======
    $deadline = user_exam_deadline_timestamp($hasil);
    $nowRow = db_fetch('SELECT UNIX_TIMESTAMP(NOW()) AS now_ts');
    $now = (int) ($nowRow['now_ts'] ?? time());

    return $now >= $deadline;
}

function user_exam_deadline_timestamp(array $hasil): int{
    $started = strtotime($hasil['waktu_mulai'] ?? 'now');
    $durationDeadline = $started + (max(1, (int) ($hasil['waktu'] ?? 100)) * 60);
    $scheduleDeadline = !empty($hasil['tanggal_selesai']) ? strtotime($hasil['tanggal_selesai']) : null;

    if ($scheduleDeadline && $scheduleDeadline > 0) {
        return min($durationDeadline, $scheduleDeadline);
    }

    return $durationDeadline;
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
}

function user_update_rankings(int $idTryout): void{
    $rows = db_fetch_all("
        SELECT id_hasil
        FROM hasil
        WHERE id_tryout = ? AND status_pengerjaan IN ('selesai', 'timeout')
        ORDER BY total_nilai DESC, durasi_detik ASC, waktu_selesai ASC, id_hasil ASC
    ", [$idTryout]);

    $rank = 1;

    foreach ($rows as $row) {
        db_execute('UPDATE hasil SET ranking = ? WHERE id_hasil = ?', [$rank++, (int) $row['id_hasil']]);
    }
}

function user_flash(string $message, string $type = 'success'): void{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function user_redirect(string $url): void{
    header('Location: ' . $url);
    exit;
}

?>
