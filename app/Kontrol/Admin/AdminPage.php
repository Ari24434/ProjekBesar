<?php
function ABeranda(){
    $dashboardStats = [
        'peserta_aktif' => 0,
        'tryout_aktif' => 0,
        'soal_aktif' => 0,
        'hasil_selesai' => 0,
        'rata_nilai' => 0,
        'lulus' => 0,
    ];
    $dashboardTryouts = [];
    $dashboardActivities = [];
    $dashboardError = null;

    try {
        $dashboardStats['peserta_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'peserta' AND status = 'aktif'")['total'] ?? 0);
        $dashboardStats['tryout_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM tryout WHERE status = 'aktif'")['total'] ?? 0);
        $dashboardStats['soal_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM soal WHERE status = 'aktif'")['total'] ?? 0);

        $hasilStat = db_fetch("
            SELECT
                COUNT(*) AS total,
                COALESCE(ROUND(AVG(total_nilai), 1), 0) AS rata_nilai,
                SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) AS lulus
            FROM hasil
            WHERE status_pengerjaan IN ('selesai', 'timeout')
        ");

        $dashboardStats['hasil_selesai'] = (int) ($hasilStat['total'] ?? 0);
        $dashboardStats['rata_nilai'] = (float) ($hasilStat['rata_nilai'] ?? 0);
        $dashboardStats['lulus'] = (int) ($hasilStat['lulus'] ?? 0);

        $dashboardTryouts = db_fetch_all("
            SELECT
                t.*,
                COUNT(DISTINCT ts.id_soal) AS total_soal,
                COUNT(DISTINCT h.id_user) AS peserta_submit,
                COALESCE(ROUND(AVG(CASE WHEN h.status_pengerjaan IN ('selesai','timeout') THEN h.total_nilai END), 1), 0) AS rata_nilai
            FROM tryout t
            LEFT JOIN tryout_soal ts ON ts.id_tryout = t.id_tryout
            LEFT JOIN hasil h ON h.id_tryout = t.id_tryout
            GROUP BY t.id_tryout
            ORDER BY FIELD(t.status, 'aktif', 'draft', 'selesai', 'diarsipkan'), t.tanggal_mulai DESC, t.id_tryout DESC
            LIMIT 5
        ");

        $dashboardActivities = db_fetch_all("
            SELECT
                h.id_hasil,
                h.id_tryout,
                h.total_nilai,
                h.lulus_total,
                h.waktu_selesai,
                h.created_at,
                u.nama AS nama_peserta,
                t.nama_tryout
            FROM hasil h
            JOIN user u ON u.id_user = h.id_user
            JOIN tryout t ON t.id_tryout = h.id_tryout
            WHERE h.status_pengerjaan IN ('selesai', 'timeout')
            ORDER BY COALESCE(h.waktu_selesai, h.created_at) DESC, h.id_hasil DESC
            LIMIT 6
        ");
    } catch (Throwable $e) {
        $dashboardError = 'Data dashboard belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Dashboard Admin";
    $topbarTitle = "Dashboard Admin";
    $active_menu = 'beranda';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/beranda-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AKelolaTryout(){
    $title = "OC Tryout - Kelola Tryout";
    $topbarTitle = "Kelola Tryout";
    $active_menu = 'tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/kelola-tryout-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function ABuatTryout(){
    $activeQuestionStock = admin_active_question_stock();
    $title = "OC Tryout - Buat Tryout Baru";
    $topbarTitle = "Buat Tryout Baru";
    $active_menu = 'tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/buat-tryout-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AHasilTryout(){
    $idTryout = (int) ($_GET['id'] ?? 0);

    if ($idTryout <= 0) {
        redirect_admin_tryout('ID tryout tidak valid.', 'error');
    }

    $tryout = db_fetch('SELECT * FROM tryout WHERE id_tryout = ?', [$idTryout]);

    if (!$tryout) {
        redirect_admin_tryout('Tryout tidak ditemukan.', 'error');
    }

    $hasilRows = db_fetch_all("
        SELECT
            h.*,
            u.nama,
            u.email,
            u.no_hp
        FROM hasil h
        JOIN user u ON u.id_user = h.id_user
        WHERE h.id_tryout = ?
          AND h.status_pengerjaan IN ('selesai', 'timeout')
        ORDER BY h.ranking IS NULL ASC, h.ranking ASC, h.total_nilai DESC, h.waktu_selesai ASC
    ", [$idTryout]);

    $hasilStats = [
        'submit' => count($hasilRows),
        'lulus' => 0,
        'tertinggi' => 0,
        'rata' => 0,
    ];

    $totalNilai = 0;

    foreach ($hasilRows as $row) {
        $nilai = (float) $row['total_nilai'];
        $totalNilai += $nilai;
        $hasilStats['tertinggi'] = max($hasilStats['tertinggi'], $nilai);

        if ((int) $row['lulus_total'] === 1) {
            $hasilStats['lulus']++;
        }
    }

    if ($hasilStats['submit'] > 0) {
        $hasilStats['rata'] = round($totalNilai / $hasilStats['submit'], 1);
    }

    $title = "OC Tryout - Hasil Tryout";
    $topbarTitle = "Hasil Tryout";
    $active_menu = 'tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/hasil-tryout-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AEditTryout(){
    $idTryout = (int) ($_GET['id'] ?? 0);

    if ($idTryout <= 0) {
        redirect_admin_tryout('ID tryout tidak valid.', 'error');
    }

    $tryout = db_fetch('SELECT * FROM tryout WHERE id_tryout = ?', [$idTryout]);

    if (!$tryout) {
        redirect_admin_tryout('Tryout tidak ditemukan.', 'error');
    }

    $activeQuestionStock = admin_active_question_stock();
    $title = "OC Tryout - Edit Tryout";
    $topbarTitle = "Edit Tryout";
    $active_menu = 'tryout';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/edit-tryout-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AStoreTryout(){
    $data = admin_tryout_payload();

    try {
        DatabasePool::transaction(function () use ($data) {
            db_execute(
                'INSERT INTO tryout (nama_tryout, deskripsi, waktu, jml_soal_twk, jml_soal_tiu, jml_soal_tkp, tanggal_mulai, tanggal_selesai, status, acak_soal, acak_opsi, dibuat_oleh)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['nama_tryout'],
                    $data['deskripsi'],
                    $data['waktu'],
                    $data['jml_soal_twk'],
                    $data['jml_soal_tiu'],
                    $data['jml_soal_tkp'],
                    $data['tanggal_mulai'],
                    $data['tanggal_selesai'],
                    $data['status'],
                    $data['acak_soal'],
                    $data['acak_opsi'],
                    1,
                ]
            );

            $idTryout = (int) db()->lastInsertId();
            admin_sync_tryout_soal($idTryout, $data);
        });

        redirect_admin_tryout('Tryout berhasil dibuat.', 'success');
    } catch (Throwable $e) {
        redirect_admin_tryout('Gagal membuat tryout: ' . $e->getMessage(), 'error');
    }
}

function AUpdateTryout(){
    $idTryout = (int) ($_POST['id_tryout'] ?? 0);

    if ($idTryout <= 0) {
        redirect_admin_tryout('ID tryout tidak valid.', 'error');
    }

    $data = admin_tryout_payload();

    try {
        $existing = db_fetch('SELECT id_tryout FROM tryout WHERE id_tryout = ?', [$idTryout]);

        if (!$existing) {
            redirect_admin_tryout('Tryout tidak ditemukan.', 'error');
        }

        $hasilCount = db_fetch('SELECT COUNT(*) AS total FROM hasil WHERE id_tryout = ?', [$idTryout]);
        $canSyncSoal = (int) ($hasilCount['total'] ?? 0) === 0;

        DatabasePool::transaction(function () use ($idTryout, $data, $canSyncSoal) {
            db_execute(
                'UPDATE tryout
                 SET nama_tryout = ?, deskripsi = ?, waktu = ?, jml_soal_twk = ?, jml_soal_tiu = ?, jml_soal_tkp = ?, tanggal_mulai = ?, tanggal_selesai = ?, status = ?, acak_soal = ?, acak_opsi = ?
                 WHERE id_tryout = ?',
                [
                    $data['nama_tryout'],
                    $data['deskripsi'],
                    $data['waktu'],
                    $data['jml_soal_twk'],
                    $data['jml_soal_tiu'],
                    $data['jml_soal_tkp'],
                    $data['tanggal_mulai'],
                    $data['tanggal_selesai'],
                    $data['status'],
                    $data['acak_soal'],
                    $data['acak_opsi'],
                    $idTryout,
                ]
            );

            if ($canSyncSoal) {
                admin_sync_tryout_soal($idTryout, $data);
            }
        });

        $message = $canSyncSoal
            ? 'Tryout berhasil diperbarui dan komposisi soal disinkronkan.'
            : 'Tryout berhasil diperbarui. Komposisi soal tidak diubah karena sudah ada hasil peserta.';

        redirect_admin_tryout($message, 'success');
    } catch (Throwable $e) {
        redirect_admin_tryout('Gagal memperbarui tryout: ' . $e->getMessage(), 'error');
    }
}

function ADeleteTryout(){
    $idTryout = (int) ($_POST['id_tryout'] ?? $_GET['id_tryout'] ?? 0);

    if ($idTryout <= 0) {
        redirect_admin_tryout('ID tryout tidak valid.', 'error');
    }

    try {
        $hasil = db_fetch('SELECT COUNT(*) AS total FROM hasil WHERE id_tryout = ?', [$idTryout]);

        if ((int) ($hasil['total'] ?? 0) > 0) {
            db_execute('UPDATE tryout SET status = ? WHERE id_tryout = ?', ['diarsipkan', $idTryout]);
            redirect_admin_tryout('Tryout sudah memiliki hasil, jadi statusnya diarsipkan.', 'success');
        }

        db_execute('DELETE FROM tryout WHERE id_tryout = ?', [$idTryout]);
        redirect_admin_tryout('Tryout berhasil dihapus.', 'success');
    } catch (Throwable $e) {
        redirect_admin_tryout('Gagal menghapus tryout: ' . $e->getMessage(), 'error');
    }
}

function admin_tryout_payload(): array{
    $nama = trim($_POST['nama_tryout'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = strtolower(trim($_POST['status'] ?? 'draft'));
    $validStatus = ['draft', 'aktif', 'selesai', 'diarsipkan'];
    $tanggalMulai = admin_normalize_datetime($_POST['tanggal_mulai'] ?? '');
    $tanggalSelesai = admin_normalize_datetime($_POST['tanggal_selesai'] ?? '');
    $waktu = max(1, (int) ($_POST['waktu'] ?? 100));
    $twk = max(0, (int) ($_POST['jml_soal_twk'] ?? 30));
    $tiu = max(0, (int) ($_POST['jml_soal_tiu'] ?? 35));
    $tkp = max(0, (int) ($_POST['jml_soal_tkp'] ?? 45));

    if ($nama === '') {
        redirect_admin_tryout('Nama tryout wajib diisi.', 'error');
    }

    if (!$tanggalMulai || !$tanggalSelesai) {
        redirect_admin_tryout('Tanggal mulai dan selesai wajib diisi.', 'error');
    }

    if (strtotime($tanggalSelesai) <= strtotime($tanggalMulai)) {
        redirect_admin_tryout('Tanggal selesai harus setelah tanggal mulai.', 'error');
    }

    if (!in_array($status, $validStatus, true)) {
        $status = 'draft';
    }

    if (($twk + $tiu + $tkp) <= 0) {
        redirect_admin_tryout('Jumlah soal minimal 1.', 'error');
    }

    return [
        'nama_tryout' => $nama,
        'deskripsi' => $deskripsi !== '' ? $deskripsi : null,
        'status' => $status,
        'waktu' => $waktu,
        'jml_soal_twk' => $twk,
        'jml_soal_tiu' => $tiu,
        'jml_soal_tkp' => $tkp,
        'tanggal_mulai' => $tanggalMulai,
        'tanggal_selesai' => $tanggalSelesai,
        'acak_soal' => (int) ($_POST['acak_soal'] ?? 1) === 1 ? 1 : 0,
        'acak_opsi' => (int) ($_POST['acak_opsi'] ?? 0) === 1 ? 1 : 0,
    ];
}

function admin_normalize_datetime(string $value): ?string{
    $value = trim($value);

    if ($value === '') {
        return null;
    }

    $timestamp = strtotime($value);

    return $timestamp === false ? null : date('Y-m-d H:i:s', $timestamp);
}

function admin_sync_tryout_soal(int $idTryout, array $data): void{
    db_execute('DELETE FROM tryout_soal WHERE id_tryout = ?', [$idTryout]);

    $targets = [
        'TWK' => $data['jml_soal_twk'],
        'TIU' => $data['jml_soal_tiu'],
        'TKP' => $data['jml_soal_tkp'],
    ];
    $urutan = 1;

    foreach ($targets as $kategori => $limit) {
        if ($limit <= 0) {
            continue;
        }

        $orderSql = $data['acak_soal'] ? 'RAND()' : 's.id_soal ASC';
        $rows = db_fetch_all("
            SELECT s.id_soal
            FROM soal s
            JOIN kategori k ON k.id_kategori = s.id_kategori
            WHERE k.kode = ? AND s.status = 'aktif'
            ORDER BY {$orderSql}
            LIMIT {$limit}
        ", [$kategori]);

        if (count($rows) < $limit) {
            throw new RuntimeException("Soal aktif kategori {$kategori} tidak mencukupi. Dibutuhkan {$limit}, tersedia " . count($rows) . '.');
        }

        foreach ($rows as $row) {
            db_execute(
                'INSERT INTO tryout_soal (id_tryout, id_soal, urutan) VALUES (?, ?, ?)',
                [$idTryout, (int) $row['id_soal'], $urutan++]
            );
        }
    }
}

function admin_active_question_stock(): array{
    $stock = ['TWK' => 0, 'TIU' => 0, 'TKP' => 0];

    foreach (db_fetch_all("
        SELECT k.kode, COUNT(s.id_soal) AS total
        FROM kategori k
        LEFT JOIN soal s ON s.id_kategori = k.id_kategori AND s.status = 'aktif'
        WHERE k.kode IN ('TWK', 'TIU', 'TKP')
        GROUP BY k.kode
    ") as $row) {
        if (isset($stock[$row['kode']])) {
            $stock[$row['kode']] = (int) $row['total'];
        }
    }

    return $stock;
}

function redirect_admin_tryout(string $message, string $type = 'success'){
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: ' . BASE_URL . '/Admin/kelola-tryout');
    exit;
}

function AKelolaSoal(){
    $title = "OC Tryout - Kelola Soal";
    $topbarTitle = "Kelola Soal";
    $active_menu = 'soal';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/kelola-soal-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function ATambahSoal(){
    $title = "OC Tryout - Tambah Soal";
    $topbarTitle = "Tambah Soal";
    $active_menu = 'soal';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/tambah-soal-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AEditSoal(){
    $idSoal = (int) ($_GET['id'] ?? 0);

    if ($idSoal <= 0) {
        redirect_admin_soal('ID soal tidak valid.', 'error');
    }

    admin_ensure_opsi_media_schema();

    $soal = db_fetch("
        SELECT s.*, k.kode AS kategori
        FROM soal s
        JOIN kategori k ON k.id_kategori = s.id_kategori
        WHERE s.id_soal = ?
    ", [$idSoal]);

    if (!$soal) {
        redirect_admin_soal('Soal tidak ditemukan.', 'error');
    }

    $opsiRows = db_fetch_all('SELECT * FROM opsi_jawaban WHERE id_soal = ? ORDER BY kode_opsi ASC', [$idSoal]);
    $opsi = [];

    foreach ($opsiRows as $row) {
        $opsi[$row['kode_opsi']] = $row;
    }

    $title = "OC Tryout - Edit Soal";
    $topbarTitle = "Edit Soal";
    $active_menu = 'soal';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/edit-soal-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AStoreSoal(){
    admin_ensure_opsi_media_schema();
    $data = admin_soal_payload();

    try {
        DatabasePool::transaction(function () use ($data) {
            $kategoriRow = db_fetch('SELECT id_kategori FROM kategori WHERE kode = ?', [$data['kategori']]);

            if (!$kategoriRow) {
                throw new RuntimeException('Kategori tidak ditemukan di database.');
            }

            db_execute(
                'INSERT INTO soal (id_kategori, pertanyaan, gambar, tingkat_kesulitan, status, dibuat_oleh) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    (int) $kategoriRow['id_kategori'],
                    $data['pertanyaan'],
                    $data['gambar'] !== '' ? $data['gambar'] : null,
                    $data['tingkat_kesulitan'],
                    $data['status'],
                    1,
                ]
            );

            $idSoal = (int) db()->lastInsertId();

            foreach (admin_soal_options($data) as $option) {
                db_execute(
                    'INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, gambar_opsi, poin, is_kunci) VALUES (?, ?, ?, ?, ?, ?)',
                    [$idSoal, $option['kode'], $option['teks'], $option['gambar'], $option['poin'], $option['is_kunci']]
                );
            }
        });

        redirect_admin_soal('Soal berhasil ditambahkan.', 'success');
    } catch (Throwable $e) {
        redirect_admin_soal('Gagal menambahkan soal: ' . $e->getMessage(), 'error');
    }
}

function AUpdateSoal(){
    admin_ensure_opsi_media_schema();
    $idSoal = (int) ($_POST['id_soal'] ?? 0);

    if ($idSoal <= 0) {
        redirect_admin_soal('ID soal tidak valid.', 'error');
    }

    try {
        $currentSoal = db_fetch('SELECT id_soal, gambar FROM soal WHERE id_soal = ?', [$idSoal]);

        if (!$currentSoal) {
            throw new RuntimeException('Soal tidak ditemukan.');
        }

        $existingOpsiRows = db_fetch_all('SELECT kode_opsi, gambar_opsi FROM opsi_jawaban WHERE id_soal = ?', [$idSoal]);
        $existingOpsiGambar = [];

        foreach ($existingOpsiRows as $row) {
            $existingOpsiGambar[$row['kode_opsi']] = $row['gambar_opsi'] ?? null;
        }

        $data = admin_soal_payload($currentSoal['gambar'] ?? null, $existingOpsiGambar);

        DatabasePool::transaction(function () use ($idSoal, $data) {
            $kategoriRow = db_fetch('SELECT id_kategori FROM kategori WHERE kode = ?', [$data['kategori']]);

            if (!$kategoriRow) {
                throw new RuntimeException('Kategori tidak ditemukan di database.');
            }

            db_execute(
                'UPDATE soal SET id_kategori = ?, pertanyaan = ?, gambar = ?, tingkat_kesulitan = ?, status = ? WHERE id_soal = ?',
                [
                    (int) $kategoriRow['id_kategori'],
                    $data['pertanyaan'],
                    $data['gambar'] !== '' ? $data['gambar'] : null,
                    $data['tingkat_kesulitan'],
                    $data['status'],
                    $idSoal,
                ]
            );

            foreach (admin_soal_options($data) as $option) {
                db_execute(
                    'INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, gambar_opsi, poin, is_kunci)
                     VALUES (?, ?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE teks_opsi = VALUES(teks_opsi), gambar_opsi = VALUES(gambar_opsi), poin = VALUES(poin), is_kunci = VALUES(is_kunci)',
                    [$idSoal, $option['kode'], $option['teks'], $option['gambar'], $option['poin'], $option['is_kunci']]
                );
            }
        });

        redirect_admin_soal('Soal berhasil diperbarui.', 'success');
    } catch (Throwable $e) {
        redirect_admin_soal('Gagal memperbarui soal: ' . $e->getMessage(), 'error');
    }
}

function ADeleteSoal(){
    $idSoal = (int) ($_POST['id_soal'] ?? $_GET['id_soal'] ?? 0);

    if ($idSoal <= 0) {
        redirect_admin_soal('ID soal tidak valid.', 'error');
    }

    try {
        db_execute('DELETE FROM soal WHERE id_soal = ?', [$idSoal]);
        redirect_admin_soal('Soal berhasil dihapus.', 'success');
    } catch (Throwable $e) {
        redirect_admin_soal('Gagal menghapus soal: ' . $e->getMessage(), 'error');
    }
}

function admin_soal_payload(?string $existingGambar = null, array $existingOpsiGambar = []): array{
    $kategori = strtoupper(trim($_POST['kategori'] ?? ''));
    $tingkatKesulitan = strtolower(trim($_POST['tingkat_kesulitan'] ?? 'sedang'));
    $status = strtolower(trim($_POST['status'] ?? 'aktif'));
    $pertanyaan = trim($_POST['pertanyaan'] ?? '');
    $jawabanBenar = strtoupper(trim($_POST['jawaban_benar'] ?? ''));
    $validKategori = ['TWK', 'TIU', 'TKP'];
    $validKesulitan = ['mudah', 'sedang', 'sulit'];
    $validStatus = ['aktif', 'nonaktif', 'draft'];
    $kodeOpsi = ['A', 'B', 'C', 'D', 'E'];

    if (!in_array($kategori, $validKategori, true)) {
        redirect_admin_soal('Kategori soal wajib dipilih.', 'error');
    }

    if (!in_array($tingkatKesulitan, $validKesulitan, true)) {
        $tingkatKesulitan = 'sedang';
    }

    if (!in_array($status, $validStatus, true)) {
        $status = 'aktif';
    }

    if ($kategori !== 'TKP' && !in_array($jawabanBenar, $kodeOpsi, true)) {
        redirect_admin_soal('Pilih jawaban benar untuk soal TWK/TIU.', 'error');
    }

    $gambar = admin_soal_uploaded_image($existingGambar);
    $gambarOpsi = [];

    foreach ($kodeOpsi as $kode) {
        $gambarOpsi[$kode] = admin_soal_uploaded_image($existingOpsiGambar[$kode] ?? null, "gambar_opsi_{$kode}", "hapus_gambar_opsi_{$kode}", "opsi_{$kode}");
        $teksOpsi = trim($_POST["opsi_{$kode}"] ?? '');

        if ($teksOpsi === '' && empty($gambarOpsi[$kode])) {
            redirect_admin_soal("Isi teks atau upload gambar untuk opsi {$kode}.", 'error');
        }
    }

    if ($pertanyaan === '' && empty($gambar)) {
        redirect_admin_soal('Isi teks soal atau upload gambar soal terlebih dahulu.', 'error');
    }

    return [
        'kategori' => $kategori,
        'tingkat_kesulitan' => $tingkatKesulitan,
        'status' => $status,
        'pertanyaan' => $pertanyaan,
        'gambar' => $gambar ?? '',
        'jawaban_benar' => $jawabanBenar,
        'kode_opsi' => $kodeOpsi,
        'gambar_opsi' => $gambarOpsi,
    ];
}

function admin_soal_uploaded_image(?string $existingGambar = null, string $fileKey = 'gambar', string $removeKey = 'hapus_gambar', string $prefix = 'soal'): ?string{
    $removeExisting = ($_POST[$removeKey] ?? '') === '1';

    if (empty($_FILES[$fileKey]['name'])) {
        if ($removeExisting) {
            admin_delete_soal_image($existingGambar);
            return null;
        }

        return $existingGambar;
    }

    $file = $_FILES[$fileKey];
    $maxSize = 1024 * 1024;
    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        redirect_admin_soal('Upload gambar gagal. Pastikan file gambar valid.', 'error');
    }

    if (($file['size'] ?? 0) > $maxSize) {
        redirect_admin_soal('Ukuran gambar maksimal 1 MB.', 'error');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset($allowedMime[$mime])) {
        redirect_admin_soal('Format gambar harus JPG, PNG, WEBP, atau GIF.', 'error');
    }

    $uploadDir = BASE_PATH . '/public/uploads/soal';

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
        redirect_admin_soal('Folder upload gambar tidak bisa dibuat.', 'error');
    }

    $safePrefix = preg_replace('/[^a-zA-Z0-9_]+/', '_', $prefix);
    $fileName = $safePrefix . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $allowedMime[$mime];
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        redirect_admin_soal('Gambar gagal disimpan ke server.', 'error');
    }

    admin_delete_soal_image($existingGambar);

    return 'uploads/soal/' . $fileName;
}

function admin_delete_soal_image(?string $path): void{
    if (!$path) {
        return;
    }

    $uploadRoot = realpath(BASE_PATH . '/public/uploads/soal');
    $target = realpath(BASE_PATH . '/public/' . ltrim($path, '/\\'));

    if (!$uploadRoot || !$target || strpos($target, $uploadRoot) !== 0 || !is_file($target)) {
        return;
    }

    @unlink($target);
}

function admin_soal_options(array $data): array{
    $options = [];

    foreach ($data['kode_opsi'] as $kode) {
        $isKunci = $data['kategori'] !== 'TKP' && $data['jawaban_benar'] === $kode ? 1 : 0;
        $poin = $data['kategori'] === 'TKP'
            ? max(1, min(5, (int) ($_POST["poin_{$kode}"] ?? 1)))
            : ($isKunci ? 5 : 0);

        $options[] = [
            'kode' => $kode,
            'teks' => trim($_POST["opsi_{$kode}"] ?? '') !== '' ? trim($_POST["opsi_{$kode}"] ?? '') : null,
            'gambar' => $data['gambar_opsi'][$kode] ?? null,
            'poin' => $poin,
            'is_kunci' => $isKunci,
        ];
    }

    return $options;
}

function admin_ensure_opsi_media_schema(): void{
    try {
        $gambarColumn = db_fetch("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'opsi_jawaban'
              AND COLUMN_NAME = 'gambar_opsi'
        ");

        if (!$gambarColumn) {
            db_execute("ALTER TABLE opsi_jawaban ADD COLUMN gambar_opsi VARCHAR(255) DEFAULT NULL COMMENT 'Path gambar opsi jawaban jika ada' AFTER teks_opsi");
        }

        $teksColumn = db_fetch("
            SELECT IS_NULLABLE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'opsi_jawaban'
              AND COLUMN_NAME = 'teks_opsi'
        ");

        if (($teksColumn['IS_NULLABLE'] ?? 'NO') !== 'YES') {
            db_execute('ALTER TABLE opsi_jawaban MODIFY teks_opsi TEXT NULL COMMENT "Isi teks pilihan jawaban jika ada"');
        }
    } catch (Throwable $e) {
        redirect_admin_soal('Struktur opsi jawaban belum mendukung gambar: ' . $e->getMessage(), 'error');
    }
}

function redirect_admin_soal(string $message, string $type = 'success'){
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: ' . BASE_URL . '/Admin/kelola-soal');
    exit;
}

function AKelolaPeserta(){
    $title = "OC Tryout - Kelola Peserta";
    $topbarTitle = "Kelola Peserta";
    $active_menu = 'peserta';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/kelola-peserta-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function ATambahPeserta(){
    $title = "OC Tryout - Tambah Peserta";
    $topbarTitle = "Tambah Peserta";
    $active_menu = 'peserta';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/tambah-peserta-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AEditPeserta(){
    $idUser = (int) ($_GET['id'] ?? 0);

    if ($idUser <= 0) {
        redirect_admin_peserta('ID peserta tidak valid.', 'error');
    }

    $peserta = db_fetch("SELECT id_user, nama, email, no_hp, status, created_at, last_login FROM user WHERE id_user = ? AND role = 'peserta'", [$idUser]);

    if (!$peserta) {
        redirect_admin_peserta('Peserta tidak ditemukan.', 'error');
    }

    $title = "OC Tryout - Edit Peserta";
    $topbarTitle = "Edit Peserta";
    $active_menu = 'peserta';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/edit-peserta-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AStorePeserta(){
    $nama = trim($_POST['nama'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $noHp = trim($_POST['no_hp'] ?? '');
    $status = strtolower(trim($_POST['status'] ?? 'aktif'));
    $password = (string) ($_POST['password'] ?? '');
    $konfirmasiPassword = (string) ($_POST['konfirmasi_password'] ?? '');
    $validStatus = ['aktif', 'nonaktif'];

    if ($nama === '' || $email === '' || $password === '') {
        redirect_admin_peserta('Nama, email, dan password wajib diisi.', 'error');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_admin_peserta('Format email peserta tidak valid.', 'error');
    }

    if (strlen($password) < 8) {
        redirect_admin_peserta('Password minimal 8 karakter.', 'error');
    }

    if ($password !== $konfirmasiPassword) {
        redirect_admin_peserta('Konfirmasi password belum sama.', 'error');
    }

    if (!in_array($status, $validStatus, true)) {
        $status = 'aktif';
    }

    try {
        $existing = db_fetch('SELECT id_user FROM user WHERE email = ?', [$email]);

        if ($existing) {
            redirect_admin_peserta('Email sudah terdaftar. Gunakan email lain.', 'error');
        }

        db_execute(
            'INSERT INTO user (nama, email, password, no_hp, role, status) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $nama,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $noHp !== '' ? $noHp : null,
                'peserta',
                $status,
            ]
        );

        redirect_admin_peserta('Peserta berhasil ditambahkan.', 'success');
    } catch (Throwable $e) {
        redirect_admin_peserta('Gagal menambahkan peserta: ' . $e->getMessage(), 'error');
    }
}

function AUpdatePeserta(){
    $idUser = (int) ($_POST['id_user'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $noHp = trim($_POST['no_hp'] ?? '');
    $status = strtolower(trim($_POST['status'] ?? 'aktif'));
    $password = (string) ($_POST['password'] ?? '');
    $konfirmasiPassword = (string) ($_POST['konfirmasi_password'] ?? '');
    $validStatus = ['aktif', 'nonaktif'];

    if ($idUser <= 0) {
        redirect_admin_peserta('ID peserta tidak valid.', 'error');
    }

    if ($nama === '' || $email === '') {
        redirect_admin_peserta('Nama dan email wajib diisi.', 'error');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_admin_peserta('Format email peserta tidak valid.', 'error');
    }

    if (!in_array($status, $validStatus, true)) {
        $status = 'aktif';
    }

    if ($password !== '' && strlen($password) < 8) {
        redirect_admin_peserta('Password baru minimal 8 karakter.', 'error');
    }

    if ($password !== '' && $password !== $konfirmasiPassword) {
        redirect_admin_peserta('Konfirmasi password baru belum sama.', 'error');
    }

    try {
        $peserta = db_fetch('SELECT id_user FROM user WHERE id_user = ? AND role = ?', [$idUser, 'peserta']);

        if (!$peserta) {
            redirect_admin_peserta('Peserta tidak ditemukan.', 'error');
        }

        $existing = db_fetch('SELECT id_user FROM user WHERE email = ? AND id_user <> ?', [$email, $idUser]);

        if ($existing) {
            redirect_admin_peserta('Email sudah digunakan akun lain.', 'error');
        }

        if ($password !== '') {
            db_execute(
                'UPDATE user SET nama = ?, email = ?, no_hp = ?, status = ?, password = ? WHERE id_user = ? AND role = ?',
                [
                    $nama,
                    $email,
                    $noHp !== '' ? $noHp : null,
                    $status,
                    password_hash($password, PASSWORD_DEFAULT),
                    $idUser,
                    'peserta',
                ]
            );
        } else {
            db_execute(
                'UPDATE user SET nama = ?, email = ?, no_hp = ?, status = ? WHERE id_user = ? AND role = ?',
                [
                    $nama,
                    $email,
                    $noHp !== '' ? $noHp : null,
                    $status,
                    $idUser,
                    'peserta',
                ]
            );
        }

        redirect_admin_peserta('Data peserta berhasil diperbarui.', 'success');
    } catch (Throwable $e) {
        redirect_admin_peserta('Gagal memperbarui peserta: ' . $e->getMessage(), 'error');
    }
}

function ADeletePeserta(){
    $idUser = (int) ($_POST['id_user'] ?? $_GET['id_user'] ?? 0);

    if ($idUser <= 0) {
        redirect_admin_peserta('ID peserta tidak valid.', 'error');
    }

    try {
        $peserta = db_fetch('SELECT id_user FROM user WHERE id_user = ? AND role = ?', [$idUser, 'peserta']);

        if (!$peserta) {
            redirect_admin_peserta('Peserta tidak ditemukan.', 'error');
        }

        db_execute('DELETE FROM user WHERE id_user = ? AND role = ?', [$idUser, 'peserta']);
        redirect_admin_peserta('Peserta berhasil dihapus.', 'success');
    } catch (Throwable $e) {
        redirect_admin_peserta('Gagal menghapus peserta. Pastikan data hasil tryout terkait sudah tidak dibutuhkan. Detail: ' . $e->getMessage(), 'error');
    }
}

function redirect_admin_peserta(string $message, string $type = 'success'){
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: ' . BASE_URL . '/Admin/kelola-peserta');
    exit;
}

function AAnalisis(){
    $analysisTryoutOptions = [];
    $selectedTryout = max(0, (int) ($_GET['tryout'] ?? 0));
    $analysisError = null;
    $analysisSummary = [
        'total_hasil' => 0,
        'total_peserta' => 0,
        'avg_total' => 0,
        'pass_rate' => 0,
    ];
    $analysisCategoryScores = [
        'TWK' => ['avg' => 0, 'passed' => 0, 'threshold' => 65, 'max' => 150, 'color' => '#1E54B7'],
        'TIU' => ['avg' => 0, 'passed' => 0, 'threshold' => 80, 'max' => 175, 'color' => '#10B981'],
        'TKP' => ['avg' => 0, 'passed' => 0, 'threshold' => 166, 'max' => 225, 'color' => '#F59E0B'],
    ];
    $analysisQuestionSummary = [];
    $analysisDifficultyRows = [];
    $analysisHardQuestions = [];
    $analysisUnusedQuestions = [];
    $analysisLeaderboard = [];

    try {
        $analysisTryoutOptions = db_fetch_all('SELECT id_tryout, nama_tryout FROM tryout ORDER BY tanggal_mulai DESC, id_tryout DESC');

        $whereParts = ["status_pengerjaan IN ('selesai', 'timeout')"];
        $params = [];

        if ($selectedTryout > 0) {
            $whereParts[] = 'id_tryout = ?';
            $params[] = $selectedTryout;
        }

        $whereSql = 'WHERE ' . implode(' AND ', $whereParts);
        $summaryRow = db_fetch("
            SELECT
                COUNT(*) AS total_hasil,
                COUNT(DISTINCT id_user) AS total_peserta,
                COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_total,
                COALESCE(ROUND(SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(*), 0) * 100, 1), 0) AS pass_rate,
                COALESCE(ROUND(AVG(nilai_twk), 1), 0) AS avg_twk,
                COALESCE(ROUND(AVG(nilai_tiu), 1), 0) AS avg_tiu,
                COALESCE(ROUND(AVG(nilai_tkp), 1), 0) AS avg_tkp,
                SUM(CASE WHEN lulus_twk = 1 THEN 1 ELSE 0 END) AS passed_twk,
                SUM(CASE WHEN lulus_tiu = 1 THEN 1 ELSE 0 END) AS passed_tiu,
                SUM(CASE WHEN lulus_tkp = 1 THEN 1 ELSE 0 END) AS passed_tkp
            FROM v_rekap_nilai
            {$whereSql}
        ", $params);

        if ($summaryRow) {
            $analysisSummary = [
                'total_hasil' => (int) ($summaryRow['total_hasil'] ?? 0),
                'total_peserta' => (int) ($summaryRow['total_peserta'] ?? 0),
                'avg_total' => (float) ($summaryRow['avg_total'] ?? 0),
                'pass_rate' => (float) ($summaryRow['pass_rate'] ?? 0),
            ];

            $analysisCategoryScores['TWK']['avg'] = (float) ($summaryRow['avg_twk'] ?? 0);
            $analysisCategoryScores['TIU']['avg'] = (float) ($summaryRow['avg_tiu'] ?? 0);
            $analysisCategoryScores['TKP']['avg'] = (float) ($summaryRow['avg_tkp'] ?? 0);
            $analysisCategoryScores['TWK']['passed'] = (int) ($summaryRow['passed_twk'] ?? 0);
            $analysisCategoryScores['TIU']['passed'] = (int) ($summaryRow['passed_tiu'] ?? 0);
            $analysisCategoryScores['TKP']['passed'] = (int) ($summaryRow['passed_tkp'] ?? 0);
        }

        $analysisQuestionSummary = db_fetch_all("
            SELECT
                kategori,
                COUNT(*) AS total_soal,
                SUM(CASE WHEN total_dijawab > 0 THEN 1 ELSE 0 END) AS soal_terjawab,
                SUM(COALESCE(total_dijawab, 0)) AS total_dijawab,
                COALESCE(ROUND(AVG(CASE WHEN total_dijawab > 0 THEN pct_benar END), 1), 0) AS avg_pct_benar,
                COALESCE(ROUND(AVG(CASE WHEN total_dijawab > 0 THEN rata_poin END), 2), 0) AS avg_poin
            FROM v_statistik_soal
            GROUP BY kategori
            ORDER BY FIELD(kategori, 'TWK', 'TIU', 'TKP')
        ");

        $analysisDifficultyRows = db_fetch_all("
            SELECT
                kategori,
                tingkat_kesulitan,
                COUNT(*) AS total_soal,
                COALESCE(ROUND(AVG(CASE WHEN total_dijawab > 0 THEN pct_benar END), 1), 0) AS avg_pct_benar
            FROM v_statistik_soal
            GROUP BY kategori, tingkat_kesulitan
            ORDER BY FIELD(kategori, 'TWK', 'TIU', 'TKP'), FIELD(tingkat_kesulitan, 'mudah', 'sedang', 'sulit')
        ");

        $analysisHardQuestions = db_fetch_all("
            SELECT id_soal, kategori, pertanyaan, tingkat_kesulitan, total_dijawab, pct_benar, rata_poin
            FROM v_statistik_soal
            WHERE total_dijawab > 0
            ORDER BY pct_benar ASC, total_dijawab DESC, id_soal DESC
            LIMIT 8
        ");

        $analysisUnusedQuestions = db_fetch_all("
            SELECT id_soal, kategori, pertanyaan, tingkat_kesulitan
            FROM v_statistik_soal
            WHERE total_dijawab = 0
            ORDER BY FIELD(kategori, 'TWK', 'TIU', 'TKP'), id_soal DESC
            LIMIT 8
        ");

        $analysisLeaderboard = db_fetch_all("
            SELECT
                id_user,
                nama_peserta,
                COUNT(*) AS total_sesi,
                COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_total,
                MAX(total_nilai) AS best_total,
                SUM(CASE WHEN lulus_total = 1 THEN 1 ELSE 0 END) AS total_lulus
            FROM v_rekap_nilai
            {$whereSql}
            GROUP BY id_user, nama_peserta
            ORDER BY avg_total DESC, best_total DESC, nama_peserta ASC
            LIMIT 8
        ", $params);
    } catch (Throwable $e) {
        $analysisError = 'Data analisis belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Analisis";
    $topbarTitle = "Analisis";
    $active_menu = 'analisis';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/analisis-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function ANilaiHasil(){
    $title = "OC Tryout - Nilai & Hasil";
    $topbarTitle = "Nilai & Hasil";
    $active_menu = 'nilai';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/nilai-hasil-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function ALaporanRekap(){
    $title = "OC Tryout - Laporan Rekap";
    $topbarTitle = "Laporan Rekap";
    $active_menu = 'laporan';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/laporan-rekap.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function APengaturan(){
    $adminAccount = admin_current_account();
    $systemInfo = [
        'app_name' => function_exists('app_env_value') ? app_env_value('APP_NAME', "Oman's Club Academy") : "Oman's Club Academy",
        'app_env' => function_exists('app_env_value') ? app_env_value('APP_ENV', 'development') : 'development',
        'app_url' => defined('BASE_URL') ? BASE_URL : '-',
        'app_version' => function_exists('app_env_value') ? app_env_value('APP_VERSION', 'v1.0.0-dev') : 'v1.0.0-dev',
        'php_version' => PHP_VERSION,
        'db_name' => '-',
        'db_version' => '-',
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? php_sapi_name(),
        'total_peserta' => 0,
        'peserta_aktif' => 0,
        'total_soal' => 0,
        'soal_aktif' => 0,
        'total_tryout' => 0,
        'tryout_aktif' => 0,
        'total_hasil' => 0,
        'backup_terakhir' => '-',
        'update_terakhir' => '-',
    ];
    $settingsError = null;

    try {
        $dbConfig = DatabasePool::config();
        $systemInfo['db_name'] = $dbConfig['database'] ?? '-';
        $systemInfo['db_version'] = db_fetch('SELECT VERSION() AS version')['version'] ?? '-';
        $systemInfo['total_peserta'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'peserta'")['total'] ?? 0);
        $systemInfo['peserta_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'peserta' AND status = 'aktif'")['total'] ?? 0);
        $systemInfo['total_soal'] = (int) (db_fetch('SELECT COUNT(*) AS total FROM soal')['total'] ?? 0);
        $systemInfo['soal_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM soal WHERE status = 'aktif'")['total'] ?? 0);
        $systemInfo['total_tryout'] = (int) (db_fetch('SELECT COUNT(*) AS total FROM tryout')['total'] ?? 0);
        $systemInfo['tryout_aktif'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM tryout WHERE status = 'aktif'")['total'] ?? 0);
        $systemInfo['total_hasil'] = (int) (db_fetch("SELECT COUNT(*) AS total FROM hasil WHERE status_pengerjaan IN ('selesai', 'timeout')")['total'] ?? 0);

        $lastUpdate = db_fetch("
            SELECT MAX(updated_at) AS waktu
            FROM (
                SELECT updated_at FROM user
                UNION ALL SELECT updated_at FROM soal
                UNION ALL SELECT updated_at FROM tryout
                UNION ALL SELECT updated_at FROM hasil
            ) updates
        ");

        if (!empty($lastUpdate['waktu'])) {
            $systemInfo['update_terakhir'] = date('d M Y H:i', strtotime($lastUpdate['waktu']));
        }

        $backupFile = BASE_PATH . '/database/database.sql';

        if (is_file($backupFile)) {
            $systemInfo['backup_terakhir'] = date('d M Y H:i', filemtime($backupFile));
        }
    } catch (Throwable $e) {
        $settingsError = 'Info sistem belum bisa dibaca: ' . $e->getMessage();
    }

    $title = "OC Tryout - Pengaturan";
    $topbarTitle = "Pengaturan";
    $active_menu = 'pengaturan';
    $topbar = BASE_PATH . "/app/Tampilan/Widget/Admin/topbar.php";
    $content = BASE_PATH . "/app/Tampilan/Halaman/Admin/pengaturan-admin.php";
    include BASE_PATH . "/app/Tampilan/Layout/Admin-layout.php";
}

function AUpdateAdminProfile(){
    $idAdmin = (int) ($_POST['id_user'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $noHp = trim($_POST['no_hp'] ?? '');
    $status = strtolower(trim($_POST['status'] ?? 'aktif'));
    $validStatus = ['aktif', 'nonaktif'];

    if ($idAdmin <= 0) {
        redirect_admin_settings('ID admin tidak valid.', 'error');
    }

    if ($nama === '' || mb_strlen($nama) < 3 || mb_strlen($nama) > 100) {
        redirect_admin_settings('Nama admin wajib 3 sampai 100 karakter.', 'error');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        redirect_admin_settings('Format email admin tidak valid.', 'error');
    }

    if ($noHp !== '' && !preg_match('/^[0-9+\-\s()]{8,20}$/', $noHp)) {
        redirect_admin_settings('Nomor HP hanya boleh angka, spasi, +, -, dan tanda kurung. Panjang 8-20 karakter.', 'error');
    }

    if (!in_array($status, $validStatus, true)) {
        redirect_admin_settings('Status admin tidak valid.', 'error');
    }

    try {
        $admin = db_fetch("SELECT id_user FROM user WHERE id_user = ? AND role = 'admin'", [$idAdmin]);

        if (!$admin) {
            redirect_admin_settings('Akun admin tidak ditemukan.', 'error');
        }

        $emailOwner = db_fetch('SELECT id_user FROM user WHERE email = ? AND id_user <> ?', [$email, $idAdmin]);

        if ($emailOwner) {
            redirect_admin_settings('Email sudah digunakan akun lain.', 'error');
        }

        if ($status === 'nonaktif') {
            $activeAdmins = db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'admin' AND status = 'aktif' AND id_user <> ?", [$idAdmin]);

            if ((int) ($activeAdmins['total'] ?? 0) === 0) {
                redirect_admin_settings('Tidak boleh menonaktifkan satu-satunya admin aktif.', 'error');
            }
        }

        db_execute(
            'UPDATE user SET nama = ?, email = ?, no_hp = ?, status = ? WHERE id_user = ? AND role = ?',
            [$nama, $email, $noHp !== '' ? $noHp : null, $status, $idAdmin, 'admin']
        );

        redirect_admin_settings('Profil administrator berhasil diperbarui.', 'success');
    } catch (Throwable $e) {
        redirect_admin_settings('Gagal memperbarui profil admin: ' . $e->getMessage(), 'error');
    }
}

function AUpdateAdminPassword(){
    $idAdmin = (int) ($_POST['id_user'] ?? 0);
    $passwordLama = (string) ($_POST['password_lama'] ?? '');
    $passwordBaru = (string) ($_POST['password_baru'] ?? '');
    $konfirmasi = (string) ($_POST['konfirmasi_password'] ?? '');

    if ($idAdmin <= 0) {
        redirect_admin_settings('ID admin tidak valid.', 'error');
    }

    if ($passwordLama === '' || $passwordBaru === '' || $konfirmasi === '') {
        redirect_admin_settings('Password lama, password baru, dan konfirmasi wajib diisi.', 'error');
    }

    if (strlen($passwordBaru) < 8) {
        redirect_admin_settings('Password baru minimal 8 karakter.', 'error');
    }

    if (strlen($passwordBaru) > 72) {
        redirect_admin_settings('Password baru maksimal 72 karakter.', 'error');
    }

    if ($passwordBaru !== $konfirmasi) {
        redirect_admin_settings('Konfirmasi password baru belum sama.', 'error');
    }

    try {
        $admin = db_fetch("SELECT id_user, password FROM user WHERE id_user = ? AND role = 'admin'", [$idAdmin]);

        if (!$admin) {
            redirect_admin_settings('Akun admin tidak ditemukan.', 'error');
        }

        if (!password_verify($passwordLama, $admin['password'])) {
            redirect_admin_settings('Password lama tidak sesuai.', 'error');
        }

        db_execute(
            "UPDATE user SET password = ? WHERE id_user = ? AND role = 'admin'",
            [password_hash($passwordBaru, PASSWORD_DEFAULT), $idAdmin]
        );

        redirect_admin_settings('Password administrator berhasil diperbarui.', 'success');
    } catch (Throwable $e) {
        redirect_admin_settings('Gagal memperbarui password admin: ' . $e->getMessage(), 'error');
    }
}

function admin_current_account(): ?array{
    $sessionUser = auth_user();
    $idAdmin = (int) ($sessionUser['id_user'] ?? 0);

    if ($idAdmin > 0) {
        $admin = db_fetch("SELECT id_user, nama, email, no_hp, role, status, created_at, last_login FROM user WHERE id_user = ? AND role = 'admin'", [$idAdmin]);

        if ($admin) {
            return $admin;
        }
    }

    return null;
}

function redirect_admin_settings(string $message, string $type = 'success'){
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];

    header('Location: ' . BASE_URL . '/Admin/pengaturan');
    exit;
}

?>
