<?php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pesertaRows = [];
$participantStats = [
  'semua' => 0,
  'aktif' => 0,
  'nonaktif' => 0,
];
$perPage = 10;
$currentPage = max(1, (int) ($_GET['page'] ?? 1));
$totalPages = 1;
$offset = 0;
$startNumber = 0;
$endNumber = 0;

try {
  $statRows = db_fetch_all("
    SELECT status, COUNT(*) AS total
    FROM user
    WHERE role = 'peserta'
    GROUP BY status
  ");

  foreach ($statRows as $row) {
    if (isset($participantStats[$row['status']])) {
      $participantStats[$row['status']] = (int) $row['total'];
    }
  }

  $participantStats['semua'] = $participantStats['aktif'] + $participantStats['nonaktif'];
  $totalPages = max(1, (int) ceil($participantStats['semua'] / $perPage));
  $currentPage = min($currentPage, $totalPages);
  $offset = ($currentPage - 1) * $perPage;
  $startNumber = $participantStats['semua'] ? $offset + 1 : 0;

  $pesertaRows = db_fetch_all("
    SELECT
      u.id_user,
      u.nama,
      u.email,
      u.no_hp,
      u.status,
      DATE(u.created_at) AS bergabung,
      COUNT(h.id_hasil) AS tryout_diikuti,
      COALESCE(MAX(h.total_nilai), 0) AS nilai_terbaik
    FROM user u
    LEFT JOIN hasil h ON h.id_user = u.id_user AND h.status_pengerjaan IN ('selesai', 'timeout')
    WHERE u.role = 'peserta'
    GROUP BY u.id_user, u.nama, u.email, u.no_hp, u.status, u.created_at
    ORDER BY u.created_at DESC, u.id_user DESC
    LIMIT {$perPage} OFFSET {$offset}
  ");

  $endNumber = min($offset + count($pesertaRows), $participantStats['semua']);
} catch (Throwable $e) {
  $flash = [
    'type' => 'error',
    'message' => 'Data peserta belum bisa dibaca: ' . $e->getMessage(),
  ];
}

$pesertaUrl = function (int $page): string {
  return BASE_URL . '/Admin/kelola-peserta?' . http_build_query(['page' => max(1, $page)]);
};

$pageStart = max(1, $currentPage - 2);
$pageEnd = min($totalPages, $currentPage + 2);

if ($pageEnd - $pageStart < 4) {
  $pageStart = max(1, $pageEnd - 4);
  $pageEnd = min($totalPages, $pageStart + 4);
}
?>

<div class="page active" id="pg-peserta">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Manajemen Peserta</h2>
        <p>Kelola data peserta tryout CPNS Oman's Club Academy.</p>
      </div>

      <?php if ($flash): ?>
        <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:<?= ($flash['type'] ?? '') === 'error' ? 'rgba(239,68,68,.35)' : 'rgba(16,185,129,.35)' ?>;background:<?= ($flash['type'] ?? '') === 'error' ? '#FEF2F2' : '#ECFDF5' ?>;color:<?= ($flash['type'] ?? '') === 'error' ? '#991B1B' : '#065F46' ?>;font-size:12.5px;font-weight:700;">
          <?= htmlspecialchars($flash['message']) ?>
        </div>
      <?php endif; ?>

      <!-- Filter & Aksi -->
      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;" class="anim anim-d1">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
          <div class="search-bar" style="max-width:280px;flex:1;">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari nama atau email..." oninput="filterPeserta(this.value)" id="srch-peserta"/>
          </div>
          <div class="filter-bar" style="margin-bottom:0;">
            <button class="filter-btn active" onclick="filterStatus('semua',this)">Semua (<?= $participantStats['semua'] ?>)</button>
            <button class="filter-btn" onclick="filterStatus('aktif',this)">Aktif (<?= $participantStats['aktif'] ?>)</button>
            <button class="filter-btn" onclick="filterStatus('nonaktif',this)">Nonaktif (<?= $participantStats['nonaktif'] ?>)</button>
          </div>
        </div>
        <a class="btn btn-primary" href="<?= BASE_URL ?>/Admin/tambah-peserta">
          <i class="bi bi-person-plus-fill"></i> Tambah Peserta
        </a>
      </div>

      <div class="card anim anim-d2">
        <div style="overflow-x:auto;">
          <table class="data-table" id="tbl-peserta">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Tryout Diikuti</th>
                <th>Nilai Terbaik</th>
                <th>Status</th>
                <th>Bergabung</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-peserta">
              <?php if (!$pesertaRows): ?>
                <tr>
                  <td colspan="9" style="text-align:center;color:var(--ash);padding:24px;">Belum ada peserta yang tersimpan.</td>
                </tr>
              <?php endif; ?>

              <?php foreach ($pesertaRows as $index => $peserta): ?>
                <tr data-status="<?= htmlspecialchars($peserta['status']) ?>" data-search="<?= htmlspecialchars(strtolower($peserta['nama'] . ' ' . $peserta['email'] . ' ' . ($peserta['no_hp'] ?? ''))) ?>">
                  <td style="color:var(--ash);"><?= $startNumber + $index ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($peserta['nama']) ?></td>
                  <td><?= htmlspecialchars($peserta['email']) ?></td>
                  <td><?= htmlspecialchars($peserta['no_hp'] ?? '-') ?></td>
                  <td><?= (int) $peserta['tryout_diikuti'] ?> sesi</td>
                  <td style="font-weight:800;color:var(--blue-main);"><?= number_format((float) $peserta['nilai_terbaik'], 0) ?></td>
                  <td><span class="badge <?= $peserta['status'] === 'aktif' ? 'badge-pass' : 'badge-fail' ?>"><?= ucfirst($peserta['status']) ?></span></td>
                  <td style="color:var(--ash);font-size:11.5px;white-space:nowrap;"><?= htmlspecialchars($peserta['bergabung']) ?></td>
                  <td>
                    <div style="display:inline-flex;gap:6px;align-items:center;">
                      <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/edit-peserta?id=<?= (int) $peserta['id_user'] ?>" title="Edit peserta"><i class="bi bi-pencil"></i></a>
                      <form method="post" action="<?= BASE_URL ?>/Admin/peserta" onsubmit="return confirm('Hapus peserta ini? Data hasil tryout peserta juga dapat ikut terdampak oleh relasi database.');" style="display:inline-flex;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_user" value="<?= (int) $peserta['id_user'] ?>">
                        <button class="btn btn-danger btn-sm" type="submit" title="Hapus peserta"><i class="bi bi-trash"></i></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:12px;color:var(--ash);">
            Menampilkan <strong><?= $startNumber ?>-<?= $endNumber ?></strong> dari <?= $participantStats['semua'] ?> peserta
            <span style="color:var(--ash);">(<strong id="peserta-count"><?= count($pesertaRows) ?></strong> terlihat di halaman ini)</span>
          </div>
          <div style="display:flex;gap:6px;">
            <?php if ($currentPage > 1): ?>
              <a class="btn btn-ghost btn-sm" href="<?= $pesertaUrl($currentPage - 1) ?>" title="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></a>
            <?php else: ?>
              <button class="btn btn-ghost btn-sm" type="button" disabled title="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></button>
            <?php endif; ?>

            <?php for ($page = $pageStart; $page <= $pageEnd; $page++): ?>
              <a class="btn <?= $page === $currentPage ? 'btn-primary' : 'btn-ghost' ?> btn-sm" href="<?= $pesertaUrl($page) ?>"><?= $page ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
              <a class="btn btn-ghost btn-sm" href="<?= $pesertaUrl($currentPage + 1) ?>" title="Halaman berikutnya"><i class="bi bi-chevron-right"></i></a>
            <?php else: ?>
              <button class="btn btn-ghost btn-sm" type="button" disabled title="Halaman berikutnya"><i class="bi bi-chevron-right"></i></button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
  const pesertaSearch = document.getElementById('srch-peserta');
  const pesertaRows = [...document.querySelectorAll('#tbody-peserta tr[data-status]')];
  const pesertaCount = document.getElementById('peserta-count');
  let activeStatus = 'semua';

  function applyPesertaFilter() {
    const keyword = (pesertaSearch?.value || '').toLowerCase();
    let visible = 0;

    pesertaRows.forEach((row) => {
      const matchStatus = activeStatus === 'semua' || row.dataset.status === activeStatus;
      const matchSearch = !keyword || row.dataset.search.includes(keyword);
      const show = matchStatus && matchSearch;

      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    if (pesertaCount) {
      pesertaCount.textContent = visible;
    }
  }

  function filterPeserta(keyword) {
    applyPesertaFilter();
  }

  function filterStatus(status, button) {
    activeStatus = status;
    document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active'));
    button.classList.add('active');
    applyPesertaFilter();
  }
</script>
