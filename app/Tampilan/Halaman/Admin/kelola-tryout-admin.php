<?php
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$statusLabels = [
  'draft' => ['label' => 'Draft', 'class' => 'badge-draft', 'icon' => 'bi-pencil-square'],
  'aktif' => ['label' => 'Berjalan', 'class' => 'badge-ongoing', 'icon' => 'bi-broadcast'],
  'selesai' => ['label' => 'Selesai', 'class' => 'badge-done', 'icon' => 'bi-check-circle-fill'],
  'diarsipkan' => ['label' => 'Diarsipkan', 'class' => 'badge-fail', 'icon' => 'bi-archive-fill'],
];
$activeStatus = strtolower(trim($_GET['status'] ?? 'semua'));
$activeStatus = array_key_exists($activeStatus, $statusLabels) ? $activeStatus : 'semua';
$tryoutRows = [];
$statusCounts = ['semua' => 0, 'draft' => 0, 'aktif' => 0, 'selesai' => 0, 'diarsipkan' => 0];
$totalPesertaAktif = 0;

$tryoutUrl = function (?string $status = null): string {
  $query = $status && $status !== 'semua' ? '?' . http_build_query(['status' => $status]) : '';
  return BASE_URL . '/Admin/kelola-tryout' . $query;
};

try {
  $totalPeserta = db_fetch("SELECT COUNT(*) AS total FROM user WHERE role = 'peserta' AND status = 'aktif'");
  $totalPesertaAktif = (int) ($totalPeserta['total'] ?? 0);

  foreach (db_fetch_all('SELECT status, COUNT(*) AS total FROM tryout GROUP BY status') as $row) {
    if (isset($statusCounts[$row['status']])) {
      $statusCounts[$row['status']] = (int) $row['total'];
    }
  }

  $statusCounts['semua'] = array_sum(array_diff_key($statusCounts, ['semua' => true]));
  $whereSql = $activeStatus !== 'semua' ? 'WHERE t.status = ?' : '';
  $params = $activeStatus !== 'semua' ? [$activeStatus] : [];

  $tryoutRows = db_fetch_all("
    SELECT
      t.*,
      COUNT(DISTINCT ts.id_soal) AS total_soal_terpasang,
      COUNT(DISTINCT h.id_user) AS peserta_submit,
      COALESCE(ROUND(AVG(CASE WHEN h.status_pengerjaan IN ('selesai','timeout') THEN h.total_nilai END), 0), 0) AS rata_nilai,
      COALESCE(ROUND(SUM(CASE WHEN h.lulus_total = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(h.id_hasil), 0) * 100, 0), 0) AS persen_lulus
    FROM tryout t
    LEFT JOIN tryout_soal ts ON ts.id_tryout = t.id_tryout
    LEFT JOIN hasil h ON h.id_tryout = t.id_tryout
    {$whereSql}
    GROUP BY t.id_tryout
    ORDER BY t.tanggal_mulai DESC, t.id_tryout DESC
  ", $params);
} catch (Throwable $e) {
  $flash = [
    'type' => 'error',
    'message' => 'Data tryout belum bisa dibaca: ' . $e->getMessage(),
  ];
}
?>

<div class="page active" id="pg-tryout">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Manajemen Tryout</h2>
      <p>Buat, atur jadwal, dan kelola sesi tryout CPNS.</p>
    </div>

    <?php if ($flash): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:<?= ($flash['type'] ?? '') === 'error' ? 'rgba(239,68,68,.35)' : 'rgba(16,185,129,.35)' ?>;background:<?= ($flash['type'] ?? '') === 'error' ? '#FEF2F2' : '#ECFDF5' ?>;color:<?= ($flash['type'] ?? '') === 'error' ? '#991B1B' : '#065F46' ?>;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px;" class="anim anim-d1">
      <div class="filter-bar" style="margin-bottom:0;">
        <a class="filter-btn <?= $activeStatus === 'semua' ? 'active' : '' ?>" href="<?= $tryoutUrl('semua') ?>">Semua (<?= $statusCounts['semua'] ?>)</a>
        <?php foreach ($statusLabels as $status => $meta): ?>
          <a class="filter-btn <?= $activeStatus === $status ? 'active' : '' ?>" href="<?= $tryoutUrl($status) ?>"><?= $meta['label'] ?> (<?= $statusCounts[$status] ?>)</a>
        <?php endforeach; ?>
      </div>
      <a class="btn btn-primary" href="<?= BASE_URL ?>/Admin/buat-tryout">
        <i class="bi bi-plus-circle-fill"></i> Buat Tryout Baru
      </a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:15px;" class="anim anim-d2">
      <?php if (!$tryoutRows): ?>
        <div class="card" style="padding:24px;color:var(--ash);text-align:center;">Belum ada tryout yang tersimpan.</div>
      <?php endif; ?>

      <?php foreach ($tryoutRows as $tryout): ?>
        <?php
          $status = $tryout['status'];
          $meta = $statusLabels[$status] ?? $statusLabels['draft'];
          $targetSoal = (int) $tryout['jml_soal_twk'] + (int) $tryout['jml_soal_tiu'] + (int) $tryout['jml_soal_tkp'];
          $soalTerpasang = (int) $tryout['total_soal_terpasang'];
          $pesertaSubmit = (int) $tryout['peserta_submit'];
          $progressPeserta = $totalPesertaAktif > 0 ? min(100, round(($pesertaSubmit / $totalPesertaAktif) * 100)) : 0;
          $borderColor = $status === 'aktif' ? 'var(--blue-main)' : ($status === 'selesai' ? 'var(--emerald)' : ($status === 'draft' ? 'var(--ash)' : 'var(--crimson)'));
        ?>
        <div class="card" style="border-top:3px solid <?= $borderColor ?>;">
          <div style="padding:16px 18px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
              <span class="badge <?= $meta['class'] ?>"><i class="bi <?= $meta['icon'] ?>"></i> <?= $meta['label'] ?></span>
              <div style="display:flex;gap:5px;">
                <a class="btn btn-ghost btn-sm btn-icon" href="<?= BASE_URL ?>/Admin/edit-tryout?id=<?= (int) $tryout['id_tryout'] ?>" title="Edit"><i class="bi bi-pencil"></i></a>
                <form method="post" action="<?= BASE_URL ?>/Admin/tryout" onsubmit="return confirm('Hapus tryout ini? Jika sudah ada hasil, tryout akan diarsipkan.');" style="display:inline-flex;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <input type="hidden" name="id_tryout" value="<?= (int) $tryout['id_tryout'] ?>">
                  <button class="btn btn-danger btn-sm btn-icon" type="submit" title="Hapus atau arsipkan"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </div>
            <div style="font-weight:700;font-size:14.5px;color:var(--ink);margin-bottom:2px;"><?= htmlspecialchars($tryout['nama_tryout']) ?></div>
            <div style="font-size:12px;color:var(--slate);font-style:italic;margin-bottom:11px;"><?= htmlspecialchars($tryout['deskripsi'] ?: 'Tanpa deskripsi') ?></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Peserta Submit</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;"><?= $pesertaSubmit ?> <span style="font-size:10px;color:var(--ash);font-weight:400;">/ <?= $totalPesertaAktif ?></span></div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Soal</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;"><?= $soalTerpasang ?> <span style="font-size:10px;color:var(--ash);font-weight:400;">/ <?= $targetSoal ?></span></div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Durasi</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;"><?= (int) $tryout['waktu'] ?> <span style="font-size:10px;color:var(--ash);font-weight:400;">menit</span></div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Jadwal</div>
                <div style="font-weight:700;color:var(--ink);font-size:12px;"><?= htmlspecialchars(date('d M Y', strtotime($tryout['tanggal_mulai']))) ?></div>
              </div>
            </div>
            <div style="margin-bottom:13px;">
              <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--ash);margin-bottom:4px;"><span>Progress peserta</span><span><?= $pesertaSubmit ?>/<?= $totalPesertaAktif ?></span></div>
              <div class="progress-bar"><div class="progress-fill" style="width:<?= $progressPeserta ?>%;background:var(--blue-main);"></div></div>
            </div>
            <div style="display:flex;gap:7px;">
              <a class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;" href="<?= BASE_URL ?>/Admin/hasil-tryout?id=<?= (int) $tryout['id_tryout'] ?>"><i class="bi bi-bar-chart"></i> Lihat Hasil</a>
              <a class="btn btn-primary btn-sm" style="flex:1;justify-content:center;" href="<?= BASE_URL ?>/Admin/edit-tryout?id=<?= (int) $tryout['id_tryout'] ?>"><i class="bi bi-pencil"></i> Edit</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <a class="card" style="border:2px dashed var(--smoke);cursor:pointer;min-height:200px;text-decoration:none;" href="<?= BASE_URL ?>/Admin/buat-tryout">
        <div style="padding:30px;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;text-align:center;gap:10px;">
          <div style="width:46px;height:46px;border-radius:50%;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:20px;"><i class="bi bi-plus-lg"></i></div>
          <div style="font-size:13px;font-weight:600;color:var(--blue-main);">Buat Tryout Baru</div>
          <div style="font-size:11.5px;color:var(--ash);">Klik untuk membuat sesi tryout baru</div>
        </div>
      </a>
    </div>
  </div>
</div>
