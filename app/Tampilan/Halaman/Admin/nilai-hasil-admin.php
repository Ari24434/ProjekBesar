<?php
$tryoutOptions = [];
$resultRows = [];
$perPage = 10;
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$selectedTryout = max(0, (int) ($_GET['tryout'] ?? 0));
$selectedStatus = strtolower(trim($_GET['status'] ?? 'semua'));
$selectedStatus = in_array($selectedStatus, ['semua', 'lulus', 'tidak-lulus'], true) ? $selectedStatus : 'semua';
$keyword = trim($_GET['q'] ?? '');
$totalRows = 0;
$totalPages = 1;
$offset = 0;
$stats = [
  'avg_total' => 0,
  'avg_twk' => 0,
  'avg_tiu' => 0,
  'avg_tkp' => 0,
];

$nilaiUrl = function (array $params = []) use ($selectedTryout, $selectedStatus, $keyword): string {
  $query = array_merge([
    'tryout' => $selectedTryout > 0 ? $selectedTryout : null,
    'status' => $selectedStatus !== 'semua' ? $selectedStatus : null,
    'q' => $keyword !== '' ? $keyword : null,
  ], $params);

  $query = array_filter($query, fn($value) => $value !== null && $value !== '' && $value !== 'semua');

  return BASE_URL . '/Admin/nilai-hasil' . ($query ? '?' . http_build_query($query) : '');
};

try {
  $tryoutOptions = db_fetch_all('SELECT id_tryout, nama_tryout FROM tryout ORDER BY tanggal_mulai DESC, id_tryout DESC');

  $whereParts = ["status_pengerjaan IN ('selesai', 'timeout')"];
  $params = [];

  if ($selectedTryout > 0) {
    $whereParts[] = 'id_tryout = ?';
    $params[] = $selectedTryout;
  }

  if ($selectedStatus === 'lulus') {
    $whereParts[] = 'lulus_total = 1';
  } elseif ($selectedStatus === 'tidak-lulus') {
    $whereParts[] = 'lulus_total = 0';
  }

  if ($keyword !== '') {
    $whereParts[] = '(nama_peserta LIKE ? OR email LIKE ? OR nama_tryout LIKE ?)';
    $keywordParam = '%' . $keyword . '%';
    array_push($params, $keywordParam, $keywordParam, $keywordParam);
  }

  $whereSql = 'WHERE ' . implode(' AND ', $whereParts);
  $countRow = db_fetch("SELECT COUNT(*) AS total FROM v_rekap_nilai {$whereSql}", $params);
  $totalRows = (int) ($countRow['total'] ?? 0);
  $totalPages = max(1, (int) ceil($totalRows / $perPage));
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;

  $statsRow = db_fetch("
    SELECT
      COALESCE(ROUND(AVG(total_nilai), 1), 0) AS avg_total,
      COALESCE(ROUND(AVG(nilai_twk), 1), 0) AS avg_twk,
      COALESCE(ROUND(AVG(nilai_tiu), 1), 0) AS avg_tiu,
      COALESCE(ROUND(AVG(nilai_tkp), 1), 0) AS avg_tkp
    FROM v_rekap_nilai
    {$whereSql}
  ", $params);

  if ($statsRow) {
    $stats = [
      'avg_total' => (float) $statsRow['avg_total'],
      'avg_twk' => (float) $statsRow['avg_twk'],
      'avg_tiu' => (float) $statsRow['avg_tiu'],
      'avg_tkp' => (float) $statsRow['avg_tkp'],
    ];
  }

  $resultRows = db_fetch_all("
    SELECT *
    FROM v_rekap_nilai
    {$whereSql}
    ORDER BY tanggal_mulai DESC, ranking IS NULL ASC, ranking ASC, total_nilai DESC
    LIMIT {$perPage} OFFSET {$offset}
  ", $params);
} catch (Throwable $e) {
  $flash = [
    'type' => 'error',
    'message' => 'Data nilai belum bisa dibaca: ' . $e->getMessage(),
  ];
}

$startNumber = $totalRows ? $offset + 1 : 0;
$endNumber = min($offset + count($resultRows), $totalRows);
$pageStart = max(1, $currentPage - 2);
$pageEnd = min($totalPages, $currentPage + 2);

if ($pageEnd - $pageStart < 4) {
  $pageStart = max(1, $pageEnd - 4);
  $pageEnd = min($totalPages, $pageStart + 4);
}
?>

<div class="page active" id="pg-nilai">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Nilai & Hasil Tryout</h2>
      <p>Monitor performa seluruh peserta per sesi tryout.</p>
    </div>

    <?php if (!empty($flash)): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <form method="get" action="<?= BASE_URL ?>/Admin/nilai-hasil" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;" class="anim anim-d1">
      <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
        <div class="form-group" style="margin-bottom:0;min-width:220px;">
          <select class="form-select" name="tryout" onchange="this.form.submit()" style="font-size:12.5px;padding:7px 32px 7px 11px;">
            <option value="0">Semua Sesi</option>
            <?php foreach ($tryoutOptions as $tryout): ?>
              <option value="<?= (int) $tryout['id_tryout'] ?>" <?= $selectedTryout === (int) $tryout['id_tryout'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($tryout['nama_tryout']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-bar" style="margin-bottom:0;">
          <a class="filter-btn <?= $selectedStatus === 'semua' ? 'active' : '' ?>" href="<?= $nilaiUrl(['status' => null, 'page' => null]) ?>">Semua</a>
          <a class="filter-btn <?= $selectedStatus === 'lulus' ? 'active' : '' ?>" href="<?= $nilaiUrl(['status' => 'lulus', 'page' => null]) ?>">Lulus</a>
          <a class="filter-btn <?= $selectedStatus === 'tidak-lulus' ? 'active' : '' ?>" href="<?= $nilaiUrl(['status' => 'tidak-lulus', 'page' => null]) ?>">Tidak Lulus</a>
        </div>
        <div class="search-bar" style="max-width:260px;flex:1;">
          <i class="bi bi-search"></i>
          <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, email, sesi..."/>
        </div>
        <button class="btn btn-ghost btn-sm" type="submit"><i class="bi bi-search"></i></button>
        <?php if ($selectedTryout > 0 || $selectedStatus !== 'semua' || $keyword !== ''): ?>
          <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/nilai-hasil">Reset</a>
        <?php endif; ?>
      </div>
    </form>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:16px;" class="anim anim-d2">
      <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--emerald);">
        <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata Total</div>
        <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--emerald);"><?= number_format($stats['avg_total'], 0) ?></div>
        <div style="font-size:10.5px;color:var(--ash);">dari 500 poin</div>
      </div>
      <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--blue-main);">
        <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TWK</div>
        <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--blue-main);"><?= number_format($stats['avg_twk'], 0) ?></div>
        <div style="font-size:10.5px;color:var(--ash);">min. 65</div>
      </div>
      <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--emerald);">
        <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TIU</div>
        <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--emerald);"><?= number_format($stats['avg_tiu'], 0) ?></div>
        <div style="font-size:10.5px;color:var(--ash);">min. 80</div>
      </div>
      <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--amber);">
        <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TKP</div>
        <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--amber);"><?= number_format($stats['avg_tkp'], 0) ?></div>
        <div style="font-size:10.5px;color:var(--ash);">min. 166</div>
      </div>
    </div>

    <div class="card anim anim-d3">
      <div style="overflow-x:auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Peserta</th>
              <th>Sesi</th>
              <th>TWK</th>
              <th>TIU</th>
              <th>TKP</th>
              <th>Total</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tbody-nilai">
            <?php if (!$resultRows): ?>
              <tr>
                <td colspan="10" style="text-align:center;color:var(--ash);padding:24px;">Belum ada hasil tryout yang cocok dengan filter.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($resultRows as $index => $result): ?>
              <?php $isLulus = (int) $result['lulus_total'] === 1; ?>
              <tr>
                <td style="color:var(--ash);"><?= $startNumber + $index ?></td>
                <td style="font-weight:700;">
                  <?= htmlspecialchars($result['nama_peserta']) ?>
                  <div style="font-size:10.5px;color:var(--ash);font-weight:500;"><?= htmlspecialchars($result['email']) ?></div>
                </td>
                <td style="min-width:190px;"><?= htmlspecialchars($result['nama_tryout']) ?></td>
                <td><?= number_format((float) $result['nilai_twk'], 0) ?></td>
                <td><?= number_format((float) $result['nilai_tiu'], 0) ?></td>
                <td><?= number_format((float) $result['nilai_tkp'], 0) ?></td>
                <td style="font-weight:800;color:<?= $isLulus ? 'var(--blue-main)' : 'var(--crimson)' ?>;"><?= number_format((float) $result['total_nilai'], 0) ?></td>
                <td><span class="badge <?= $isLulus ? 'badge-pass' : 'badge-fail' ?>"><?= $isLulus ? 'Lulus' : 'Belum' ?></span></td>
                <td style="color:var(--ash);font-size:11.5px;white-space:nowrap;"><?= htmlspecialchars(date('d M Y', strtotime($result['tanggal_mulai']))) ?></td>
                <td><a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/hasil-tryout?id=<?= (int) $result['id_tryout'] ?>"><i class="bi bi-eye"></i></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:12px;color:var(--ash);">Menampilkan <strong><?= $startNumber ?>-<?= $endNumber ?></strong> dari <?= $totalRows ?> hasil</div>
        <div style="display:flex;gap:6px;">
          <?php if ($currentPage > 1): ?>
            <a class="btn btn-ghost btn-sm" href="<?= $nilaiUrl(['page' => $currentPage - 1]) ?>"><i class="bi bi-chevron-left"></i></a>
          <?php else: ?>
            <button class="btn btn-ghost btn-sm" type="button" disabled><i class="bi bi-chevron-left"></i></button>
          <?php endif; ?>

          <?php for ($page = $pageStart; $page <= $pageEnd; $page++): ?>
            <a class="btn <?= $page === $currentPage ? 'btn-primary' : 'btn-ghost' ?> btn-sm" href="<?= $nilaiUrl(['page' => $page]) ?>"><?= $page ?></a>
          <?php endfor; ?>

          <?php if ($currentPage < $totalPages): ?>
            <a class="btn btn-ghost btn-sm" href="<?= $nilaiUrl(['page' => $currentPage + 1]) ?>"><i class="bi bi-chevron-right"></i></a>
          <?php else: ?>
            <button class="btn btn-ghost btn-sm" type="button" disabled><i class="bi bi-chevron-right"></i></button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
