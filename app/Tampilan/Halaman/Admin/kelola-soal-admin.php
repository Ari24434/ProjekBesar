<?php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$categoryStats = [
  'TWK' => ['label' => 'Tes Wawasan Kebangsaan', 'color' => 'var(--blue-main)', 'count' => 0, 'target' => 165],
  'TIU' => ['label' => 'Tes Intelegensia Umum', 'color' => 'var(--emerald)', 'count' => 0, 'target' => 165],
  'TKP' => ['label' => 'Tes Karakteristik Pribadi', 'color' => 'var(--amber)', 'count' => 0, 'target' => 165],
];
$questions = [];
$perPage = 10;
$activeFilter = strtoupper(trim($_GET['kategori'] ?? 'all'));
$activeFilter = isset($categoryStats[$activeFilter]) ? $activeFilter : 'all';
$searchKeyword = trim($_GET['q'] ?? '');
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$totalFilteredQuestions = 0;
$totalPages = 1;
$offset = 0;

$soalUrl = function (array $params = []) use ($activeFilter, $searchKeyword) {
  $query = array_merge([
    'kategori' => $activeFilter !== 'all' ? $activeFilter : null,
    'q' => $searchKeyword !== '' ? $searchKeyword : null,
  ], $params);

  $query = array_filter($query, function ($value) {
    return $value !== null && $value !== '' && $value !== 'all';
  });

  return BASE_URL . '/Admin/kelola-soal' . ($query ? '?' . http_build_query($query) : '');
};

try {
  foreach (db_fetch_all("
    SELECT k.kode, COUNT(s.id_soal) AS total
    FROM kategori k
    LEFT JOIN soal s ON s.id_kategori = k.id_kategori
    GROUP BY k.kode
  ") as $row) {
    if (isset($categoryStats[$row['kode']])) {
      $categoryStats[$row['kode']]['count'] = (int) $row['total'];
    }
  }

  $whereParts = [];
  $params = [];

  if ($activeFilter !== 'all') {
    $whereParts[] = 'k.kode = ?';
    $params[] = $activeFilter;
  }

  if ($searchKeyword !== '') {
    $whereParts[] = '(s.pertanyaan LIKE ? OR s.tingkat_kesulitan LIKE ? OR k.kode LIKE ? OR k.nama_kategori LIKE ?)';
    $keywordParam = '%' . $searchKeyword . '%';
    array_push($params, $keywordParam, $keywordParam, $keywordParam, $keywordParam);
  }

  $whereSql = $whereParts ? 'WHERE ' . implode(' AND ', $whereParts) : '';
  $countRow = db_fetch("
    SELECT COUNT(*) AS total
    FROM soal s
    JOIN kategori k ON k.id_kategori = s.id_kategori
    {$whereSql}
  ", $params);

  $totalFilteredQuestions = (int) ($countRow['total'] ?? 0);
  $totalPages = max(1, (int) ceil($totalFilteredQuestions / $perPage));
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;

  $questions = db_fetch_all("
    SELECT
      s.id_soal,
      s.pertanyaan,
      s.gambar,
      s.tingkat_kesulitan,
      s.status,
      k.kode AS kategori,
      k.nama_kategori,
      COALESCE(COUNT(DISTINCT ts.id), 0) AS digunakan,
      MAX(CASE WHEN oj.is_kunci = 1 THEN oj.kode_opsi ELSE NULL END) AS jawaban_benar,
      MAX(CASE WHEN oj.poin = (
        SELECT MAX(oj2.poin) FROM opsi_jawaban oj2 WHERE oj2.id_soal = s.id_soal
      ) THEN oj.kode_opsi ELSE NULL END) AS opsi_poin_tertinggi
    FROM soal s
    JOIN kategori k ON k.id_kategori = s.id_kategori
    LEFT JOIN opsi_jawaban oj ON oj.id_soal = s.id_soal
    LEFT JOIN tryout_soal ts ON ts.id_soal = s.id_soal
    {$whereSql}
    GROUP BY s.id_soal, s.pertanyaan, s.gambar, s.tingkat_kesulitan, s.status, k.kode, k.nama_kategori
    ORDER BY s.created_at DESC, s.id_soal DESC
    LIMIT {$perPage} OFFSET {$offset}
  ", $params);
} catch (Throwable $e) {
  $flash = [
    'type' => 'error',
    'message' => 'Data bank soal belum bisa dibaca: ' . $e->getMessage(),
  ];
}

$totalQuestions = array_sum(array_column($categoryStats, 'count'));
$startNumber = $totalFilteredQuestions ? $offset + 1 : 0;
$endNumber = min($offset + count($questions), $totalFilteredQuestions);
$pageStart = max(1, $currentPage - 2);
$pageEnd = min($totalPages, $currentPage + 2);

if ($pageEnd - $pageStart < 4) {
  $pageStart = max(1, $pageEnd - 4);
  $pageEnd = min($totalPages, $pageStart + 4);
}
?>

 <div class="page active" id="pg-soal">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Bank Soal</h2>
        <p>Kelola soal TWK, TIU, dan TKP untuk tryout CPNS.</p>
      </div>

      <?php if ($flash): ?>
        <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:<?= ($flash['type'] ?? '') === 'error' ? 'rgba(239,68,68,.35)' : 'rgba(16,185,129,.35)' ?>;background:<?= ($flash['type'] ?? '') === 'error' ? '#FEF2F2' : '#ECFDF5' ?>;color:<?= ($flash['type'] ?? '') === 'error' ? '#991B1B' : '#065F46' ?>;font-size:12.5px;font-weight:700;">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
      <?php endif; ?>

      <!-- Stat soal per kategori -->
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:13px;margin-bottom:18px;" class="anim anim-d1">
        <?php foreach ($categoryStats as $kode => $stat): ?>
          <?php $percent = min(100, round(($stat['count'] / max($stat['target'], 1)) * 100)); ?>
          <div class="card" style="padding:16px 18px;border-top:3px solid <?= $stat['color'] ?>;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;">
              <span class="badge badge-<?= strtolower($kode) ?>"><?= $kode ?></span>
              <span style="font-size:10.5px;color:var(--ash);"><?= htmlspecialchars($stat['label']) ?></span>
            </div>
            <div style="font-family:'Playfair Display',serif;font-size:28px;color:<?= $stat['color'] ?>;margin-bottom:3px;"><?= $stat['count'] ?></div>
            <div style="font-size:11.5px;color:var(--ash);">soal tersedia</div>
            <div style="margin-top:9px;"><div class="progress-bar"><div class="progress-fill" style="width:<?= $percent ?>%;background:<?= $stat['color'] ?>;"></div></div><div style="font-size:10.5px;color:var(--ash);margin-top:3px;"><?= $percent ?>% dari target <?= $stat['target'] ?> soal</div></div>
          </div>
        <?php endforeach; ?>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px;" class="anim anim-d2">
        <div style="display:flex;align-items:center;gap:8px;flex:1;flex-wrap:wrap;">
          <form method="get" action="<?= BASE_URL ?>/Admin/kelola-soal" style="display:flex;gap:8px;align-items:center;max-width:360px;flex:1;">
            <?php if ($activeFilter !== 'all'): ?>
              <input type="hidden" name="kategori" value="<?= htmlspecialchars($activeFilter) ?>">
            <?php endif; ?>
            <div class="search-bar" style="width:100%;">
              <i class="bi bi-search"></i>
              <input type="text" placeholder="Cari soal..." id="srch-soal" name="q" value="<?= htmlspecialchars($searchKeyword) ?>"/>
            </div>
            <button class="btn btn-ghost btn-sm" type="submit"><i class="bi bi-search"></i></button>
          </form>
          <div class="filter-bar" style="margin-bottom:0;">
            <a class="filter-btn <?= $activeFilter === 'all' ? 'active' : '' ?>" href="<?= $soalUrl(['kategori' => null, 'page' => null]) ?>">Semua (<?= $totalQuestions ?>)</a>
            <?php foreach ($categoryStats as $kode => $stat): ?>
              <a class="filter-btn <?= $activeFilter === $kode ? 'active' : '' ?>" href="<?= $soalUrl(['kategori' => $kode, 'page' => null]) ?>"><?= $kode ?> (<?= $stat['count'] ?>)</a>
            <?php endforeach; ?>
            <?php if ($activeFilter !== 'all' || $searchKeyword !== ''): ?>
              <a class="filter-btn" href="<?= BASE_URL ?>/Admin/kelola-soal">Reset</a>
            <?php endif; ?>
          </div>
        </div>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/Admin/tambah-soal">
          <i class="bi bi-plus-circle-fill"></i> Tambah Soal
        </a>
      </div>

      <div class="card anim anim-d3">
        <div style="overflow-x:auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Subtopik</th>
                <th>Pertanyaan (Ringkasan)</th>
                <th>Jawaban Benar</th>
                <th>Digunakan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-soal">
              <?php if (!$questions): ?>
                <tr>
                  <td colspan="7" style="text-align:center;color:var(--ash);padding:24px;">Belum ada soal yang tersimpan.</td>
                </tr>
              <?php endif; ?>
              <?php foreach ($questions as $index => $question): ?>
                <?php
                  $kategori = $question['kategori'];
                  $jawaban = $kategori === 'TKP'
                    ? 'Poin tertinggi: ' . ($question['opsi_poin_tertinggi'] ?? '-')
                    : ($question['jawaban_benar'] ?? '-');
                  $ringkasan = trim((string) $question['pertanyaan']);
                  $ringkasan = $ringkasan !== '' ? $ringkasan : '[Soal bergambar]';
                  $ringkasan = mb_strlen($ringkasan) > 110
                    ? mb_substr($ringkasan, 0, 110) . '...'
                    : $ringkasan;
                ?>
                <tr>
                  <td style="color:var(--ash);"><?= $startNumber + $index ?></td>
                  <td><span class="badge badge-<?= strtolower($kategori) ?>"><?= htmlspecialchars($kategori) ?></span></td>
                  <td><?= htmlspecialchars(ucfirst($question['tingkat_kesulitan'])) ?></td>
                  <td style="min-width:300px;">
                    <?= htmlspecialchars($ringkasan) ?>
                    <?php if (!empty($question['gambar'])): ?>
                      <span class="badge badge-tiu" style="margin-left:6px;"><i class="bi bi-image"></i> Gambar</span>
                    <?php endif; ?>
                  </td>
                  <td style="font-weight:700;color:var(--blue-main);"><?= htmlspecialchars($jawaban) ?></td>
                  <td><?= (int) $question['digunakan'] ?> tryout</td>
                  <td>
                    <div style="display:inline-flex;gap:6px;align-items:center;">
                      <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/edit-soal?id=<?= (int) $question['id_soal'] ?>" title="Edit soal"><i class="bi bi-pencil"></i></a>
                      <form method="post" action="<?= BASE_URL ?>/Admin/soal" onsubmit="return confirm('Hapus soal ini? Opsi jawaban ikut terhapus.');" style="display:inline-flex;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_soal" value="<?= (int) $question['id_soal'] ?>">
                        <button class="btn btn-danger btn-sm" type="submit" title="Hapus soal"><i class="bi bi-trash"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:12px;color:var(--ash);">Menampilkan <strong><?= $startNumber ?>-<?= $endNumber ?></strong> dari <?= $totalFilteredQuestions ?> soal</div>
          <div style="display:flex;gap:6px;">
            <?php if ($currentPage > 1): ?>
              <a class="btn btn-ghost btn-sm" href="<?= $soalUrl(['page' => $currentPage - 1]) ?>"><i class="bi bi-chevron-left"></i></a>
            <?php else: ?>
              <button class="btn btn-ghost btn-sm" type="button" disabled><i class="bi bi-chevron-left"></i></button>
            <?php endif; ?>

            <?php for ($page = $pageStart; $page <= $pageEnd; $page++): ?>
              <a class="btn <?= $page === $currentPage ? 'btn-primary' : 'btn-ghost' ?> btn-sm" href="<?= $soalUrl(['page' => $page]) ?>"><?= $page ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
              <a class="btn btn-ghost btn-sm" href="<?= $soalUrl(['page' => $currentPage + 1]) ?>"><i class="bi bi-chevron-right"></i></a>
            <?php else: ?>
              <button class="btn btn-ghost btn-sm" type="button" disabled><i class="bi bi-chevron-right"></i></button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
