-- ============================================================
--  DATABASE: SISTEM TRYOUT CPNS — OMAN'S CLUB ACADEMY
--  Engine   : MySQL 8.0+
--  Charset  : utf8mb4 (support emoji & karakter penuh)
--  Collation: utf8mb4_unicode_ci
--  Dibuat   : 2026
--  Versi    : 2.0 (Opsi B — OPSI_JAWABAN terpisah per baris)
-- ============================================================

-- ── Buat & pilih database ────────────────────────────────────
CREATE DATABASE IF NOT EXISTS db_tryout_cpns
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE db_tryout_cpns;

-- ── Nonaktifkan cek FK sementara (untuk urutan DROP) ─────────
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
--  DROP TABLES (urutan: child dulu, lalu parent)
-- ============================================================
DROP TABLE IF EXISTS detail_hasil;
DROP TABLE IF EXISTS hasil;
DROP TABLE IF EXISTS tryout_soal;
DROP TABLE IF EXISTS opsi_jawaban;
DROP TABLE IF EXISTS soal;
DROP TABLE IF EXISTS tryout;
DROP TABLE IF EXISTS pengaturan;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS user;

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
--  1. TABEL: user
--     Menyimpan semua akun (admin + peserta)
-- ============================================================
CREATE TABLE user (
    id_user       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nama          VARCHAR(100)    NOT NULL,
    email         VARCHAR(150)    NOT NULL,
    password      VARCHAR(255)    NOT NULL COMMENT 'bcrypt hash',
    no_hp         VARCHAR(20)     DEFAULT NULL,
    role          ENUM('admin','peserta')
                                  NOT NULL DEFAULT 'peserta',
    status        ENUM('aktif','nonaktif')
                                  NOT NULL DEFAULT 'aktif',
    foto          VARCHAR(255)    DEFAULT NULL COMMENT 'path file foto profil',
    last_login    DATETIME        DEFAULT NULL,
    created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                  ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_user),
    UNIQUE KEY uq_email (email),
    INDEX idx_role   (role),
    INDEX idx_status (status)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Akun pengguna sistem (admin dan peserta)';


-- ============================================================
--  2. TABEL: kategori
--     TWK, TIU, TKP — masing-masing beda aturan penilaian
-- ============================================================
CREATE TABLE kategori (
    id_kategori     INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    kode            ENUM('TWK','TIU','TKP')
                                    NOT NULL,
    nama_kategori   VARCHAR(100)    NOT NULL,
    jumlah_soal     TINYINT UNSIGNED NOT NULL DEFAULT 0
                    COMMENT 'Jumlah soal standar: TWK=30, TIU=35, TKP=45',
    nilai_min       SMALLINT UNSIGNED NOT NULL DEFAULT 0
                    COMMENT 'Passing grade: TWK=65, TIU=80, TKP=166',
    nilai_maks      SMALLINT UNSIGNED NOT NULL DEFAULT 0
                    COMMENT 'Nilai maks: TWK=150, TIU=175, TKP=225',
    tipe_penilaian  ENUM('binary','gradual') NOT NULL
                    COMMENT 'binary=TWK/TIU (benar/salah), gradual=TKP (poin per opsi)',
    deskripsi       TEXT            DEFAULT NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_kategori),
    UNIQUE KEY uq_kode (kode)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Kategori soal SKD: TWK, TIU, TKP';


-- ============================================================
--  3. TABEL: soal
--     Bank soal utama — TANPA data jawaban (ada di opsi_jawaban)
-- ============================================================
CREATE TABLE pengaturan (
    nama_pengaturan   VARCHAR(80)  NOT NULL,
    nilai_pengaturan  TEXT         NULL,
    deskripsi         VARCHAR(255) DEFAULT NULL,
    updated_at        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (nama_pengaturan)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Konfigurasi global aplikasi';

INSERT INTO pengaturan (nama_pengaturan, nilai_pengaturan, deskripsi) VALUES
('tryout_durasi_default', '100', 'Durasi default tryout dalam menit'),
('tryout_jumlah_soal_per_sesi', '110', 'Total soal default per sesi'),
('tryout_soal_twk', '30', 'Jumlah soal TWK default'),
('tryout_soal_tiu', '35', 'Jumlah soal TIU default'),
('tryout_soal_tkp', '45', 'Jumlah soal TKP default'),
('tryout_passing_twk', '65', 'Passing grade TWK'),
('tryout_passing_tiu', '80', 'Passing grade TIU'),
('tryout_passing_tkp', '166', 'Passing grade TKP'),
('tryout_acak_soal', '1', '1 = soal diacak saat tryout dibuat'),
('tryout_acak_opsi', '0', '1 = opsi jawaban diacak untuk peserta');


CREATE TABLE soal (
    id_soal         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_kategori     INT UNSIGNED    NOT NULL,
    pertanyaan      TEXT            NOT NULL,
    gambar          VARCHAR(255)    DEFAULT NULL
                    COMMENT 'Path gambar soal jika ada',
    subtopik        VARCHAR(120)    DEFAULT NULL
                    COMMENT 'Subtopik atau materi kecil soal',
    tingkat_kesulitan ENUM('mudah','sedang','sulit')
                    NOT NULL DEFAULT 'sedang',
    pembahasan      TEXT            NULL
                    COMMENT 'Pembahasan/evaluasi jawaban untuk peserta',
    status          ENUM('aktif','nonaktif','draft')
                    NOT NULL DEFAULT 'aktif',
    dibuat_oleh     INT UNSIGNED    DEFAULT NULL
                    COMMENT 'FK ke user (admin)',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_soal),
    CONSTRAINT fk_soal_kategori
        FOREIGN KEY (id_kategori)
        REFERENCES kategori (id_kategori)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_soal_user
        FOREIGN KEY (dibuat_oleh)
        REFERENCES user (id_user)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    INDEX idx_kategori (id_kategori),
    INDEX idx_status   (status)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Bank soal SKD CPNS';


-- ============================================================
--  4. TABEL: opsi_jawaban  ★ INTI OPSI B ★
--
--  Satu baris = satu opsi (A/B/C/D/E) dari satu soal.
--
--  Untuk TWK & TIU (tipe_penilaian = 'binary'):
--    • is_kunci = TRUE  pada opsi yang benar (hanya 1)
--    • poin     = nilai soal jika benar (default 5)
--    • Opsi salah: is_kunci = FALSE, poin = 0
--
--  Untuk TKP (tipe_penilaian = 'gradual'):
--    • is_kunci = FALSE pada SEMUA opsi
--    • poin berbeda tiap opsi: 1, 2, 3, 4, atau 5
--    • Tidak menjawab = 0 (ditangani di logika PHP)
-- ============================================================
CREATE TABLE opsi_jawaban (
    id_opsi         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_soal         INT UNSIGNED    NOT NULL,
    kode_opsi       CHAR(1)         NOT NULL
                    COMMENT 'A, B, C, D, atau E',
    teks_opsi       TEXT            NULL
                    COMMENT 'Isi teks pilihan jawaban jika ada',
    gambar_opsi     VARCHAR(255)    DEFAULT NULL
                    COMMENT 'Path gambar opsi jawaban jika ada',
    poin            DECIMAL(5,2)    NOT NULL DEFAULT 0.00
                    COMMENT 'TWK/TIU: 5 (benar) atau 0 (salah) | TKP: 1–5',
    is_kunci        TINYINT(1)      NOT NULL DEFAULT 0
                    COMMENT 'TRUE = jawaban benar (hanya untuk TWK/TIU)',

    PRIMARY KEY (id_opsi),
    CONSTRAINT fk_opsi_soal
        FOREIGN KEY (id_soal)
        REFERENCES soal (id_soal)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    -- Satu soal tidak boleh punya 2 opsi dengan kode sama
    UNIQUE KEY uq_soal_kode (id_soal, kode_opsi),
    INDEX idx_soal (id_soal)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Opsi jawaban per soal (1 baris per opsi A-E)';


-- ============================================================
--  5. TABEL: tryout
--     Sesi ujian yang dibuat oleh admin
-- ============================================================
CREATE TABLE tryout (
    id_tryout       INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nama_tryout     VARCHAR(150)    NOT NULL,
    deskripsi       TEXT            DEFAULT NULL,
    waktu           SMALLINT UNSIGNED NOT NULL DEFAULT 100
                    COMMENT 'Durasi ujian dalam menit',
    jml_soal_twk    TINYINT UNSIGNED NOT NULL DEFAULT 30,
    jml_soal_tiu    TINYINT UNSIGNED NOT NULL DEFAULT 35,
    jml_soal_tkp    TINYINT UNSIGNED NOT NULL DEFAULT 45,
    tanggal_mulai   DATETIME        NOT NULL,
    tanggal_selesai DATETIME        NOT NULL,
    status          ENUM('draft','aktif','selesai','diarsipkan')
                    NOT NULL DEFAULT 'draft',
    acak_soal       TINYINT(1)      NOT NULL DEFAULT 1
                    COMMENT '1 = soal diacak untuk setiap peserta',
    acak_opsi       TINYINT(1)      NOT NULL DEFAULT 0
                    COMMENT '1 = urutan opsi A-E ikut diacak',
    dibuat_oleh     INT UNSIGNED    DEFAULT NULL,
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_tryout),
    CONSTRAINT fk_tryout_user
        FOREIGN KEY (dibuat_oleh)
        REFERENCES user (id_user)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    INDEX idx_status         (status),
    INDEX idx_tanggal_mulai  (tanggal_mulai),
    INDEX idx_tanggal_selesai(tanggal_selesai)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Sesi tryout yang dibuat admin';


-- ============================================================
--  6. TABEL: tryout_soal
--     Pivot: soal mana saja yang masuk ke sesi tryout tertentu
--     + urutan tampil (bisa diacak saat generate sesi)
-- ============================================================
CREATE TABLE tryout_soal (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_tryout       INT UNSIGNED    NOT NULL,
    id_soal         INT UNSIGNED    NOT NULL,
    urutan          SMALLINT UNSIGNED NOT NULL DEFAULT 0
                    COMMENT 'Urutan tampil soal dalam tryout',

    PRIMARY KEY (id),
    CONSTRAINT fk_ts_tryout
        FOREIGN KEY (id_tryout)
        REFERENCES tryout (id_tryout)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_ts_soal
        FOREIGN KEY (id_soal)
        REFERENCES soal (id_soal)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    -- Satu soal tidak boleh duplikat dalam satu tryout
    UNIQUE KEY uq_tryout_soal (id_tryout, id_soal),
    INDEX idx_tryout (id_tryout),
    INDEX idx_soal   (id_soal)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Soal-soal yang digunakan dalam setiap sesi tryout';


-- ============================================================
--  7. TABEL: hasil
--     Rekap nilai akhir peserta per sesi tryout
-- ============================================================
CREATE TABLE hasil (
    id_hasil        INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_user         INT UNSIGNED    NOT NULL,
    id_tryout       INT UNSIGNED    NOT NULL,
    nilai_twk       DECIMAL(6,2)    NOT NULL DEFAULT 0.00,
    nilai_tiu       DECIMAL(6,2)    NOT NULL DEFAULT 0.00,
    nilai_tkp       DECIMAL(6,2)    NOT NULL DEFAULT 0.00,
    total_nilai     DECIMAL(6,2)    NOT NULL DEFAULT 0.00,

    -- Rincian jawaban per kategori
    benar_twk       TINYINT UNSIGNED NOT NULL DEFAULT 0,
    benar_tiu       TINYINT UNSIGNED NOT NULL DEFAULT 0,

    -- Status kelulusan per kategori
    lulus_twk       TINYINT(1)      NOT NULL DEFAULT 0,
    lulus_tiu       TINYINT(1)      NOT NULL DEFAULT 0,
    lulus_tkp       TINYINT(1)      NOT NULL DEFAULT 0,
    lulus_total     TINYINT(1)      NOT NULL DEFAULT 0
                    COMMENT '1 = lulus semua komponen SKD',

    -- Ranking (dihitung ulang tiap ada peserta baru submit)
    ranking         SMALLINT UNSIGNED DEFAULT NULL,

    -- Waktu pengerjaan
    waktu_mulai     DATETIME        NOT NULL,
    waktu_selesai   DATETIME        DEFAULT NULL,
    durasi_detik    MEDIUMINT UNSIGNED DEFAULT NULL
                    COMMENT 'Selisih waktu_selesai - waktu_mulai dalam detik',

    status_pengerjaan ENUM('sedang','selesai','timeout')
                    NOT NULL DEFAULT 'sedang',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id_hasil),
    CONSTRAINT fk_hasil_user
        FOREIGN KEY (id_user)
        REFERENCES user (id_user)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_hasil_tryout
        FOREIGN KEY (id_tryout)
        REFERENCES tryout (id_tryout)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    -- Satu peserta hanya bisa submit sekali per tryout
    UNIQUE KEY uq_user_tryout (id_user, id_tryout),
    INDEX idx_user       (id_user),
    INDEX idx_tryout     (id_tryout),
    INDEX idx_total      (total_nilai),
    INDEX idx_lulus      (lulus_total)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Rekap nilai akhir peserta per sesi tryout';


-- ============================================================
--  8. TABEL: detail_hasil
--     Jawaban peserta per soal — digunakan untuk:
--     • Halaman review jawaban (benar/salah per nomor)
--     • Rekomendasi belajar
--     • Pembahasan soal
--     • Audit & integritas data
-- ============================================================
CREATE TABLE detail_hasil (
    id_detail           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_hasil            INT UNSIGNED    NOT NULL,
    id_soal             INT UNSIGNED    NOT NULL,
    id_opsi_dipilih     INT UNSIGNED    DEFAULT NULL
                        COMMENT 'NULL = tidak menjawab',
    jawaban_peserta     CHAR(1)         DEFAULT NULL
                        COMMENT 'Kode opsi yang dipilih: A/B/C/D/E atau NULL',
    poin_didapat        DECIMAL(5,2)    NOT NULL DEFAULT 0.00
                        COMMENT 'TWK/TIU: 5 atau 0 | TKP: 1-5 atau 0',
    is_benar            TINYINT(1)      DEFAULT NULL
                        COMMENT 'TWK/TIU: 1=benar,0=salah | TKP: NULL (tidak relevan)',
    urutan_tampil       SMALLINT UNSIGNED DEFAULT NULL
                        COMMENT 'Urutan soal saat peserta mengerjakan (sudah diacak)',

    PRIMARY KEY (id_detail),
    CONSTRAINT fk_detail_hasil
        FOREIGN KEY (id_hasil)
        REFERENCES hasil (id_hasil)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_soal
        FOREIGN KEY (id_soal)
        REFERENCES soal (id_soal)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_opsi
        FOREIGN KEY (id_opsi_dipilih)
        REFERENCES opsi_jawaban (id_opsi)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    -- Satu soal hanya bisa dijawab sekali per sesi hasil
    UNIQUE KEY uq_hasil_soal (id_hasil, id_soal),
    INDEX idx_hasil (id_hasil),
    INDEX idx_soal  (id_soal)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail jawaban peserta per soal per sesi tryout';


-- ============================================================
--  DATA AWAL (SEED DATA)
-- ============================================================

-- ── 1. Akun Admin Default ────────────────────────────────────
-- Password: Admin@123 (bcrypt hash — ganti sebelum production!)
INSERT INTO user (nama, email, password, no_hp, role, status) VALUES
(
    'Administrator',
    'admin@omansclub.id',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '+62 812-0000-0001',
    'admin',
    'aktif'
);

-- ── 2. Akun Peserta Contoh ───────────────────────────────────
-- Password: Peserta@123
INSERT INTO user (nama, email, password, no_hp, role, status) VALUES
(
    'Rafi Firmansyah',
    'rafi@gmail.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '+62 812-0000-0002',
    'peserta',
    'aktif'
),
(
    'Andika Bagot',
    'andika@gmail.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '+62 812-0000-0003',
    'peserta',
    'aktif'
),
(
    'Syaiful Jambul',
    'syaiful@gmail.com',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '+62 812-0000-0004',
    'peserta',
    'aktif'
);


-- ── 3. Kategori SKD ─────────────────────────────────────────
INSERT INTO kategori
    (kode, nama_kategori, jumlah_soal, nilai_min, nilai_maks, tipe_penilaian, deskripsi)
VALUES
(
    'TWK',
    'Tes Wawasan Kebangsaan',
    30, 65, 150,
    'binary',
    'Mengukur pengetahuan dan kemampuan dalam mengimplementasikan nilai-nilai kebangsaan: Pancasila, UUD 1945, NKRI, dan Bhinneka Tunggal Ika. Penilaian: benar = 5 poin, salah = 0.'
),
(
    'TIU',
    'Tes Intelegensia Umum',
    35, 80, 175,
    'binary',
    'Mengukur kemampuan verbal, numerik, dan figural. Meliputi analogi, silogisme, deret angka, dan kemampuan berpikir logis. Penilaian: benar = 5 poin, salah = 0.'
),
(
    'TKP',
    'Tes Karakteristik Pribadi',
    45, 166, 225,
    'gradual',
    'Menilai karakter dan perilaku kerja ASN: pelayanan publik, jejaring kerja, sosial budaya, teknologi informasi, dan profesionalisme. Penilaian: tiap opsi memiliki poin 1-5, tidak ada jawaban salah.'
);


-- ============================================================
--  CONTOH DATA SOAL
--  (10 soal sampel: 4 TWK, 3 TIU, 3 TKP)
--  Format: INSERT soal → INSERT semua opsi_jawaban-nya
-- ============================================================

-- ─────────────────────────────────────────────────────────────
--  SOAL TWK #1
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (1,
    'Salah satu nilai yang terkandung dalam sila ke-3 Pancasila "Persatuan Indonesia" adalah mengutamakan kepentingan bangsa dan negara di atas kepentingan pribadi dan golongan. Sikap yang paling mencerminkan nilai tersebut dalam kehidupan sehari-hari adalah...',
    'mudah', 'aktif', 1);

-- id_soal = 1
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(1, 'A', 'Bersaing dengan sesama rekan kerja demi kemajuan diri sendiri',                   0, 0),
(1, 'B', 'Aktif membantu korban bencana alam tanpa memandang suku dan agama',               5, 1), -- ← KUNCI
(1, 'C', 'Mengutamakan kepentingan kelompok atau golongan sendiri dalam berorganisasi',     0, 0),
(1, 'D', 'Menolak bekerja sama dengan rekan yang berbeda latar belakang budaya',             0, 0),
(1, 'E', 'Memilih pemimpin berdasarkan kesamaan daerah asal',                               0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TWK #2
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (1,
    'UUD 1945 Pasal 30 ayat (1) menyatakan bahwa tiap-tiap warga negara berhak dan wajib ikut serta dalam usaha pertahanan dan keamanan negara. Hal ini mencerminkan prinsip...',
    'mudah', 'aktif', 1);

-- id_soal = 2
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(2, 'A', 'Desentralisasi pertahanan',             0, 0),
(2, 'B', 'Pertahanan semesta (Sishankamrata)',    5, 1), -- ← KUNCI
(2, 'C', 'Militerisasi seluruh rakyat',           0, 0),
(2, 'D', 'Otonomi keamanan daerah',               0, 0),
(2, 'E', 'Wajib militer penuh',                   0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TWK #3
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (1,
    'Semboyan Bhinneka Tunggal Ika yang menjadi semboyan NKRI berasal dari kitab kuno yang ditulis oleh Mpu Tantular. Kitab tersebut adalah...',
    'sedang', 'aktif', 1);

-- id_soal = 3
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(3, 'A', 'Kitab Negarakertagama',     0, 0),
(3, 'B', 'Kitab Sutasoma',            5, 1), -- ← KUNCI
(3, 'C', 'Kitab Arjunawiwaha',        0, 0),
(3, 'D', 'Kitab Ramayana',            0, 0),
(3, 'E', 'Kitab Pararaton',           0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TWK #4
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (1,
    'Nilai-nilai Pancasila dirumuskan oleh para pendiri bangsa dengan menggali dari berbagai sumber. Sumber utama nilai-nilai Pancasila adalah...',
    'mudah', 'aktif', 1);

-- id_soal = 4
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(4, 'A', 'Nilai-nilai agama Islam semata',                         0, 0),
(4, 'B', 'Budaya Barat yang sudah terbukti modern',               0, 0),
(4, 'C', 'Nilai-nilai luhur budaya dan kepribadian bangsa Indonesia sendiri', 5, 1), -- ← KUNCI
(4, 'D', 'Ideologi negara lain yang sudah terbukti berhasil',     0, 0),
(4, 'E', 'Hukum internasional yang berlaku universal',             0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TIU #1
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (2,
    'Perhatikan pola deret angka berikut: 2, 6, 12, 20, 30, ... Angka selanjutnya adalah...',
    'sedang', 'aktif', 1);

-- id_soal = 5
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(5, 'A', '38',   0, 0),
(5, 'B', '40',   0, 0),
(5, 'C', '42',   5, 1), -- ← KUNCI (selisih +4,+6,+8,+10,+12 → 30+12=42)
(5, 'D', '44',   0, 0),
(5, 'E', '46',   0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TIU #2
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (2,
    'ANALOGI: PANAS : API = DINGIN : ...',
    'mudah', 'aktif', 1);

-- id_soal = 6
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(6, 'A', 'Salju',    5, 1), -- ← KUNCI
(6, 'B', 'Angin',    0, 0),
(6, 'C', 'Hujan',    0, 0),
(6, 'D', 'Air',      0, 0),
(6, 'E', 'Embun',    0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TIU #3
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (2,
    'Semua pegawai negeri adalah abdi negara. Sebagian abdi negara adalah guru. Kesimpulan yang PASTI benar adalah...',
    'sulit', 'aktif', 1);

-- id_soal = 7
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(7, 'A', 'Semua guru adalah pegawai negeri',          0, 0),
(7, 'B', 'Sebagian pegawai negeri adalah guru',       5, 1), -- ← KUNCI
(7, 'C', 'Tidak ada guru yang bukan abdi negara',     0, 0),
(7, 'D', 'Semua abdi negara adalah pegawai negeri',   0, 0),
(7, 'E', 'Sebagian guru bukan abdi negara',           0, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TKP #1
--  ★ GRADUAL — Semua opsi punya poin 1-5, is_kunci = 0 semua
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (3,
    'Rekan kerja Anda melakukan kesalahan prosedur yang berpotensi merugikan instansi. Anda mengetahui hal tersebut secara tidak sengaja. Sikap Anda adalah...',
    'sedang', 'aktif', 1);

-- id_soal = 8
-- is_kunci = 0 untuk SEMUA opsi TKP (poin yang menentukan)
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(8, 'A', 'Diam saja karena itu bukan tanggung jawab saya',                                          1, 0),
(8, 'B', 'Langsung melaporkan ke atasan tanpa memberi tahu rekan tersebut terlebih dahulu',         2, 0),
(8, 'C', 'Menegur rekan tersebut secara langsung dan tegas di depan rekan kerja lainnya',           3, 0),
(8, 'D', 'Mengingatkan rekan tersebut secara pribadi dan sopan agar segera memperbaiki kesalahan',  4, 0),
(8, 'E', 'Mengingatkan rekan, membantu mencari solusi perbaikan, dan melaporkan ke atasan bersama', 5, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TKP #2
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (3,
    'Anda diberikan tugas mendadak oleh atasan dengan batas waktu yang sangat ketat, sementara Anda sedang mengerjakan pekerjaan rutin yang juga penting. Apa yang Anda lakukan?',
    'sedang', 'aktif', 1);

-- id_soal = 9
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(9, 'A', 'Menolak tugas mendadak tersebut karena sudah ada pekerjaan yang harus diselesaikan',                              1, 0),
(9, 'B', 'Meninggalkan pekerjaan rutin begitu saja dan mengerjakan tugas mendadak sepenuhnya',                               2, 0),
(9, 'C', 'Meminta rekan kerja menyelesaikan pekerjaan rutin tanpa memberikan penjelasan',                                    3, 0),
(9, 'D', 'Menginformasikan kondisi kepada atasan dan meminta arahan terkait prioritas pekerjaan',                            4, 0),
(9, 'E', 'Membuat skala prioritas, mendelegasikan pekerjaan rutin ke rekan, lalu fokus menyelesaikan tugas mendadak',        5, 0);


-- ─────────────────────────────────────────────────────────────
--  SOAL TKP #3
-- ─────────────────────────────────────────────────────────────
INSERT INTO soal (id_kategori, pertanyaan, tingkat_kesulitan, status, dibuat_oleh)
VALUES (3,
    'Anda baru saja dipindahtugaskan ke bagian baru yang memiliki budaya kerja sangat berbeda dari sebelumnya. Rekan-rekan di bagian baru tersebut tampak kurang ramah. Sikap Anda adalah...',
    'mudah', 'aktif', 1);

-- id_soal = 10
INSERT INTO opsi_jawaban (id_soal, kode_opsi, teks_opsi, poin, is_kunci) VALUES
(10, 'A', 'Meminta pindah kembali ke bagian lama karena tidak nyaman',                                               1, 0),
(10, 'B', 'Menunggu rekan-rekan yang berinisiatif mengajak berkenalan terlebih dahulu',                              2, 0),
(10, 'C', 'Tetap fokus bekerja sendiri dan tidak terlalu memedulikan suasana lingkungan',                            3, 0),
(10, 'D', 'Berusaha memperkenalkan diri dan bersikap ramah kepada rekan-rekan di bagian baru',                       4, 0),
(10, 'E', 'Aktif memperkenalkan diri, mengajak makan siang bersama, dan membangun komunikasi positif sejak awal',    5, 0);


-- ============================================================
--  CONTOH SESI TRYOUT
-- ============================================================
INSERT INTO tryout
    (nama_tryout, deskripsi, waktu, jml_soal_twk, jml_soal_tiu, jml_soal_tkp,
     tanggal_mulai, tanggal_selesai, status, acak_soal, dibuat_oleh)
VALUES
(
    'Tryout SKD Sesi 1 — Pembukaan',
    'Sesi perdana tryout SKD CPNS Oman''s Club Academy. Simulasi ujian 110 soal TWK, TIU, dan TKP lengkap dengan sistem penilaian standar BKN.',
    100, 30, 35, 45,
    '2026-05-01 08:00:00',
    '2026-05-01 17:00:00',
    'selesai', 1, 1
),
(
    'Tryout SKD Sesi 2 — Latihan TWK',
    'Fokus latihan Tes Wawasan Kebangsaan dengan soal-soal pilihan sesuai kisi-kisi SKD terbaru dari BKN.',
    100, 30, 35, 45,
    '2026-05-08 08:00:00',
    '2026-05-08 17:00:00',
    'selesai', 1, 1
),
(
    'Tryout SKD Sesi 3 — Simulasi Penuh',
    'Simulasi ujian SKD 110 soal mencakup TWK, TIU, dan TKP dengan durasi 100 menit. Soal diacak untuk setiap peserta.',
    100, 30, 35, 45,
    '2026-05-15 08:00:00',
    '2026-05-15 17:00:00',
    'aktif', 1, 1
);


-- ============================================================
--  ASSIGN SOAL KE TRYOUT (tryout_soal)
--  Untuk demo: assign semua 10 soal ke tryout 1 & 2
-- ============================================================

-- Tryout 1
INSERT INTO tryout_soal (id_tryout, id_soal, urutan) VALUES
(1,1,1),(1,2,2),(1,3,3),(1,4,4),   -- TWK
(1,5,5),(1,6,6),(1,7,7),           -- TIU
(1,8,8),(1,9,9),(1,10,10);         -- TKP

-- Tryout 2
INSERT INTO tryout_soal (id_tryout, id_soal, urutan) VALUES
(2,1,1),(2,2,2),(2,3,3),(2,4,4),
(2,5,5),(2,6,6),(2,7,7),
(2,8,8),(2,9,9),(2,10,10);

-- Tryout 3
INSERT INTO tryout_soal (id_tryout, id_soal, urutan) VALUES
(3,1,1),(3,2,2),(3,3,3),(3,4,4),
(3,5,5),(3,6,6),(3,7,7),
(3,8,8),(3,9,9),(3,10,10);


-- ============================================================
--  CONTOH DATA HASIL (peserta id=2 / Rafi, tryout 1 & 2)
-- ============================================================

-- Hasil tryout 1 (Rafi)
INSERT INTO hasil
    (id_user, id_tryout,
     nilai_twk, nilai_tiu, nilai_tkp, total_nilai,
     benar_twk, benar_tiu,
     lulus_twk, lulus_tiu, lulus_tkp, lulus_total,
     ranking, waktu_mulai, waktu_selesai, durasi_detik, status_pengerjaan)
VALUES
(2, 1,
 80, 90, 170, 340,
 16, 18,
 1, 1, 1, 1,
 2,
 '2026-05-01 08:05:00', '2026-05-01 09:42:00', 5820,
 'selesai');

-- Hasil tryout 2 (Rafi)
INSERT INTO hasil
    (id_user, id_tryout,
     nilai_twk, nilai_tiu, nilai_tkp, total_nilai,
     benar_twk, benar_tiu,
     lulus_twk, lulus_tiu, lulus_tkp, lulus_total,
     ranking, waktu_mulai, waktu_selesai, durasi_detik, status_pengerjaan)
VALUES
(2, 2,
 85, 95, 175, 355,
 17, 19,
 1, 1, 1, 1,
 1,
 '2026-05-08 08:03:00', '2026-05-08 09:38:00', 5700,
 'selesai');


-- ============================================================
--  STORED PROCEDURES
-- ============================================================

DELIMITER $$

-- ── Prosedur: Hitung & update nilai hasil ────────────────────
--  Dipanggil setelah peserta submit tryout
--  Parameter: p_id_hasil INT
CREATE PROCEDURE sp_hitung_nilai(IN p_id_hasil INT UNSIGNED)
BEGIN
    DECLARE v_twk   DECIMAL(6,2) DEFAULT 0;
    DECLARE v_tiu   DECIMAL(6,2) DEFAULT 0;
    DECLARE v_tkp   DECIMAL(6,2) DEFAULT 0;
    DECLARE v_total DECIMAL(6,2) DEFAULT 0;
    DECLARE v_benar_twk TINYINT  DEFAULT 0;
    DECLARE v_benar_tiu TINYINT  DEFAULT 0;

    -- Hitung nilai TWK (binary: poin dari opsi_jawaban)
    SELECT COALESCE(SUM(oj.poin), 0),
           COALESCE(SUM(CASE WHEN oj.is_kunci = 1 THEN 1 ELSE 0 END), 0)
    INTO v_twk, v_benar_twk
    FROM detail_hasil dh
    JOIN soal s         ON s.id_soal    = dh.id_soal
    JOIN kategori k     ON k.id_kategori= s.id_kategori
    JOIN opsi_jawaban oj ON oj.id_opsi  = dh.id_opsi_dipilih
    WHERE dh.id_hasil = p_id_hasil
      AND k.kode = 'TWK';

    -- Hitung nilai TIU (binary)
    SELECT COALESCE(SUM(oj.poin), 0),
           COALESCE(SUM(CASE WHEN oj.is_kunci = 1 THEN 1 ELSE 0 END), 0)
    INTO v_tiu, v_benar_tiu
    FROM detail_hasil dh
    JOIN soal s         ON s.id_soal    = dh.id_soal
    JOIN kategori k     ON k.id_kategori= s.id_kategori
    JOIN opsi_jawaban oj ON oj.id_opsi  = dh.id_opsi_dipilih
    WHERE dh.id_hasil = p_id_hasil
      AND k.kode = 'TIU';

    -- Hitung nilai TKP (gradual: semua poin dijumlah)
    SELECT COALESCE(SUM(oj.poin), 0)
    INTO v_tkp
    FROM detail_hasil dh
    JOIN soal s         ON s.id_soal    = dh.id_soal
    JOIN kategori k     ON k.id_kategori= s.id_kategori
    JOIN opsi_jawaban oj ON oj.id_opsi  = dh.id_opsi_dipilih
    WHERE dh.id_hasil = p_id_hasil
      AND k.kode = 'TKP';

    SET v_total = v_twk + v_tiu + v_tkp;

    -- Update tabel hasil
    UPDATE hasil SET
        nilai_twk    = v_twk,
        nilai_tiu    = v_tiu,
        nilai_tkp    = v_tkp,
        total_nilai  = v_total,
        benar_twk    = v_benar_twk,
        benar_tiu    = v_benar_tiu,
        lulus_twk    = IF(v_twk  >= 65,  1, 0),
        lulus_tiu    = IF(v_tiu  >= 80,  1, 0),
        lulus_tkp    = IF(v_tkp  >= 166, 1, 0),
        lulus_total  = IF(v_twk >= 65 AND v_tiu >= 80 AND v_tkp >= 166, 1, 0),
        status_pengerjaan = 'selesai',
        waktu_selesai     = NOW(),
        durasi_detik      = TIMESTAMPDIFF(SECOND, waktu_mulai, NOW())
    WHERE id_hasil = p_id_hasil;

    -- Update poin_didapat & is_benar di detail_hasil
    UPDATE detail_hasil dh
    JOIN opsi_jawaban oj ON oj.id_opsi   = dh.id_opsi_dipilih
    JOIN soal s          ON s.id_soal    = dh.id_soal
    JOIN kategori k      ON k.id_kategori= s.id_kategori
    SET
        dh.poin_didapat = oj.poin,
        dh.is_benar     = CASE
                            WHEN k.tipe_penilaian = 'binary'
                                 THEN oj.is_kunci
                            ELSE NULL   -- TKP: is_benar tidak relevan
                          END
    WHERE dh.id_hasil = p_id_hasil;

END$$


-- ── Prosedur: Update ranking dalam satu tryout ───────────────
CREATE PROCEDURE sp_update_ranking(IN p_id_tryout INT UNSIGNED)
BEGIN
    SET @rnk = 0;
    UPDATE hasil h
    JOIN (
        SELECT id_hasil,
               (@rnk := @rnk + 1) AS rnk
        FROM hasil
        WHERE id_tryout = p_id_tryout
          AND status_pengerjaan = 'selesai'
        ORDER BY total_nilai DESC, waktu_selesai ASC
    ) ranked ON ranked.id_hasil = h.id_hasil
    SET h.ranking = ranked.rnk;
END$$

DELIMITER ;


-- ============================================================
--  VIEWS (untuk kemudahan query di PHP)
-- ============================================================

-- ── View: Rekap nilai peserta lengkap ───────────────────────
CREATE OR REPLACE VIEW v_rekap_nilai AS
SELECT
    h.id_hasil,
    u.id_user,
    u.nama          AS nama_peserta,
    u.email,
    t.id_tryout,
    t.nama_tryout,
    t.tanggal_mulai,
    h.nilai_twk,
    h.nilai_tiu,
    h.nilai_tkp,
    h.total_nilai,
    h.benar_twk,
    h.benar_tiu,
    h.lulus_twk,
    h.lulus_tiu,
    h.lulus_tkp,
    h.lulus_total,
    h.ranking,
    h.durasi_detik,
    h.waktu_mulai,
    h.waktu_selesai,
    h.status_pengerjaan
FROM hasil h
JOIN user    u ON u.id_user   = h.id_user
JOIN tryout  t ON t.id_tryout = h.id_tryout;


-- ── View: Statistik soal (tingkat kesulitan & akurasi) ──────
CREATE OR REPLACE VIEW v_statistik_soal AS
SELECT
    s.id_soal,
    s.pertanyaan,
    k.kode          AS kategori,
    k.tipe_penilaian,
    s.tingkat_kesulitan,
    COUNT(dh.id_detail)                                     AS total_dijawab,
    SUM(CASE WHEN dh.is_benar = 1 THEN 1 ELSE 0 END)       AS total_benar,
    ROUND(
        SUM(CASE WHEN dh.is_benar = 1 THEN 1 ELSE 0 END)
        / NULLIF(COUNT(dh.id_detail), 0) * 100, 1
    )                                                       AS pct_benar,
    ROUND(AVG(dh.poin_didapat), 2)                          AS rata_poin
FROM soal s
JOIN kategori     k  ON k.id_kategori = s.id_kategori
LEFT JOIN detail_hasil dh ON dh.id_soal = s.id_soal
GROUP BY s.id_soal;


-- ── View: Ringkasan tryout untuk dashboard admin ─────────────
CREATE OR REPLACE VIEW v_dashboard_admin AS
SELECT
    t.id_tryout,
    t.nama_tryout,
    t.status,
    t.tanggal_mulai,
    t.tanggal_selesai,
    COUNT(DISTINCT h.id_user)                               AS total_peserta,
    ROUND(AVG(h.total_nilai), 1)                            AS rata_nilai,
    MAX(h.total_nilai)                                      AS nilai_tertinggi,
    MIN(h.total_nilai)                                      AS nilai_terendah,
    SUM(h.lulus_total)                                      AS jumlah_lulus,
    ROUND(SUM(h.lulus_total) / NULLIF(COUNT(h.id_hasil),0) * 100, 1) AS pct_lulus
FROM tryout t
LEFT JOIN hasil h ON h.id_tryout = t.id_tryout
                  AND h.status_pengerjaan = 'selesai'
GROUP BY t.id_tryout;


-- ============================================================
--  RINGKASAN STRUKTUR TABEL
-- ============================================================
/*
 Tabel            Relasi & Keterangan
 ─────────────── ──────────────────────────────────────────────
 user             Akun admin + peserta (field role)
 kategori         TWK / TIU / TKP + aturan penilaian
 soal             Bank soal, tipe binary/gradual
 opsi_jawaban     ★ Opsi B: 1 baris per opsi (A–E)
                    TWK/TIU: is_kunci=1 pada jawaban benar
                    TKP    : is_kunci=0 semua, poin 1-5
 tryout           Sesi ujian (jadwal, durasi, status)
 tryout_soal      Pivot soal ↔ tryout (+ urutan)
 hasil            Rekap nilai per peserta per tryout
 detail_hasil     Jawaban peserta per soal (audit trail)

 Stored Procedures:
   sp_hitung_nilai(id_hasil)   → hitung & simpan semua nilai
   sp_update_ranking(id_tryout)→ update ranking peserta

 Views:
   v_rekap_nilai       → gabungan hasil + user + tryout
   v_statistik_soal    → akurasi per soal
   v_dashboard_admin   → ringkasan per tryout
*/
