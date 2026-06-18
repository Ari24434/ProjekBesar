<?php
$dashboardStats = $dashboardStats ?? [];
$dashboardTryouts = $dashboardTryouts ?? [];
$dashboardActivities = $dashboardActivities ?? [];
$dashboardError = $dashboardError ?? null;

$statusMeta = [
  'draft' => ['label' => 'Draft', 'class' => 'badge-draft', 'icon' => 'bi-pencil-square', 'color' => 'var(--ash)', 'bg' => 'var(--cloud)'],
  'aktif' => ['label' => 'Berjalan', 'class' => 'badge-ongoing', 'icon' => 'bi-broadcast', 'color' => 'var(--blue-main)', 'bg' => 'var(--frost)'],
  'selesai' => ['label' => 'Selesai', 'class' => 'badge-done', 'icon' => 'bi-check-circle-fill', 'color' => 'var(--emerald)', 'bg' => '#ECFDF5'],
  'diarsipkan' => ['label' => 'Diarsipkan', 'class' => 'badge-fail', 'icon' => 'bi-archive-fill', 'color' => 'var(--crimson)', 'bg' => '#FEF2F2'],
];

$formatDate = function (?string $value, bool $withTime = false): string {
  if (!$value) {
    return '-';
  }

  $timestamp = strtotime($value);

  if ($timestamp === false) {
    return '-';
  }

  return date($withTime ? 'd M Y H:i' : 'd M Y', $timestamp);
};

$todayLabel = date('l, d M Y');
$passRate = (int) ($dashboardStats['hasil_selesai'] ?? 0) > 0
  ? round(((int) ($dashboardStats['lulus'] ?? 0) / (int) $dashboardStats['hasil_selesai']) * 100)
  : 0;
?>

<div class="page active" id="pg-dashboard">
  <div class="page-body">
    <div class="admin-banner anim">
      <div class="ab-grid"></div>
      <div class="ab-glow-1"></div>
      <div class="ab-glow-2"></div>
      <div class="ab-content">
        <div class="ab-left">
          <div class="ab-greeting"><?= htmlspecialchars($todayLabel) ?> - Panel Administrator</div>
          <div class="ab-name">Selamat Datang, Admin</div>
          <div class="ab-sub">Kelola peserta, soal, tryout, dan hasil Oman's Club Academy dari sini.</div>
          <div class="ab-pills">
            <div class="ab-pill"><i class="bi bi-people-fill" style="color:#93C5FD;font-size:10px;"></i> <?= (int) ($dashboardStats['peserta_aktif'] ?? 0) ?> Peserta Aktif</div>
            <div class="ab-pill"><i class="bi bi-journal-text" style="color:#FCD34D;font-size:10px;"></i> <?= (int) ($dashboardStats['tryout_aktif'] ?? 0) ?> Tryout Berjalan</div>
            <div class="ab-pill"><i class="bi bi-question-circle-fill" style="color:#6EE7B7;font-size:10px;"></i> <?= (int) ($dashboardStats['soal_aktif'] ?? 0) ?> Soal Aktif</div>
          </div>
        </div>
        <div class="ab-right">
          <a class="btn btn-gold" href="<?= BASE_URL ?>/Admin/buat-tryout">
            <i class="bi bi-plus-circle-fill"></i> Buat Tryout Baru
          </a>
        </div>
      </div>
    </div>

    <?php if ($dashboardError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($dashboardError) ?>
      </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:15px;" class="anim anim-d1">
      <div class="card" style="padding:15px 16px;">
        <div style="font-size:11px;color:var(--ash);margin-bottom:6px;">Hasil Selesai</div>
        <div style="font-size:24px;font-weight:800;color:var(--ink);"><?= (int) ($dashboardStats['hasil_selesai'] ?? 0) ?></div>
      </div>
      <div class="card" style="padding:15px 16px;">
        <div style="font-size:11px;color:var(--ash);margin-bottom:6px;">Rata-rata Nilai</div>
        <div style="font-size:24px;font-weight:800;color:var(--blue-main);"><?= number_format((float) ($dashboardStats['rata_nilai'] ?? 0), 1, ',', '.') ?></div>
      </div>
      <div class="card" style="padding:15px 16px;">
        <div style="font-size:11px;color:var(--ash);margin-bottom:6px;">Kelulusan</div>
        <div style="font-size:24px;font-weight:800;color:var(--emerald);"><?= $passRate ?>%</div>
      </div>
      <div class="card" style="padding:15px 16px;">
        <div style="font-size:11px;color:var(--ash);margin-bottom:6px;">Bank Soal Aktif</div>
        <div style="font-size:24px;font-weight:800;color:var(--ink);"><?= (int) ($dashboardStats['soal_aktif'] ?? 0) ?></div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 320px;gap:15px;" class="anim anim-d3">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-journal-text"></i> Tryout Terkini</div>
          <a class="card-action" href="<?= BASE_URL ?>/Admin/kelola-tryout" style="text-decoration:none;">Kelola semua &rarr;</a>
        </div>

        <?php if (!$dashboardTryouts): ?>
          <div style="padding:24px;color:var(--ash);text-align:center;">Belum ada tryout yang tersimpan.</div>
        <?php endif; ?>

        <?php foreach ($dashboardTryouts as $tryout): ?>
          <?php
            $status = $tryout['status'] ?? 'draft';
            $meta = $statusMeta[$status] ?? $statusMeta['draft'];
            $targetSoal = (int) $tryout['jml_soal_twk'] + (int) $tryout['jml_soal_tiu'] + (int) $tryout['jml_soal_tkp'];
            $pesertaSubmit = (int) ($tryout['peserta_submit'] ?? 0);
          ?>
          <div class="tryout-item">
            <div class="ti-icon" style="background:<?= $meta['bg'] ?>;color:<?= $meta['color'] ?>;"><i class="bi <?= $meta['icon'] ?>"></i></div>
            <div style="flex:1;min-width:0;">
              <div class="ti-title">
                <?= htmlspecialchars($tryout['nama_tryout']) ?>
                <span class="badge <?= $meta['class'] ?>" style="font-size:10px;"><?= $meta['label'] ?></span>
              </div>
              <div class="ti-meta">
                <i class="bi bi-calendar3" style="font-size:10px;"></i>
                <?= htmlspecialchars($formatDate($tryout['tanggal_mulai'] ?? null)) ?> -
                <?= (int) ($tryout['total_soal'] ?? 0) ?>/<?= $targetSoal ?> soal -
                <strong style="color:<?= $meta['color'] ?>;"><?= $pesertaSubmit ?> peserta submit</strong>
              </div>
            </div>
            <div class="ti-actions">
              <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/hasil-tryout?id=<?= (int) $tryout['id_tryout'] ?>"><i class="bi bi-bar-chart"></i> Hasil</a>
              <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/edit-tryout?id=<?= (int) $tryout['id_tryout'] ?>" title="Edit"><i class="bi bi-pencil"></i></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-activity"></i> Aktivitas Terbaru</div>
        </div>
        <div style="padding:14px 18px;">
          <?php if (!$dashboardActivities): ?>
            <div style="color:var(--ash);font-size:12.5px;text-align:center;padding:18px 0;">Belum ada hasil tryout terbaru.</div>
          <?php endif; ?>

          <?php foreach ($dashboardActivities as $activity): ?>
            <?php
              $isPassed = (int) ($activity['lulus_total'] ?? 0) === 1;
              $dotClass = $isPassed ? 'green' : 'red';
              $icon = $isPassed ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
              $timeValue = $activity['waktu_selesai'] ?? $activity['created_at'] ?? null;
            ?>
            <div class="activity-item">
              <div class="act-dot <?= $dotClass ?>"><i class="bi <?= $icon ?>"></i></div>
              <div>
                <div class="act-text">
                  <span><?= htmlspecialchars($activity['nama_peserta']) ?></span>
                  menyelesaikan <?= htmlspecialchars($activity['nama_tryout']) ?> dengan nilai <?= number_format((float) $activity['total_nilai'], 0, ',', '.') ?>
                </div>
                <div class="act-time"><?= htmlspecialchars($formatDate($timeValue, true)) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
