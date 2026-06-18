<?php
$result = $result ?? null;
$detailRows = $detailRows ?? [];
$resultError = $resultError ?? null;
$totalDetail = count($detailRows);
$benarCount = 0;
$salahCount = 0;

foreach ($detailRows as $row) {
  if ((int) ($row['is_benar'] ?? 0) === 1) {
    $benarCount++;
  } elseif (in_array($row['kategori'] ?? '', ['TWK', 'TIU'], true)) {
    $salahCount++;
  }
}

$categoryCards = [
  'TWK' => ['score' => (float) ($result['nilai_twk'] ?? 0), 'min' => 65, 'max' => 150, 'passed' => (int) ($result['lulus_twk'] ?? 0), 'color' => 'var(--blue-light)'],
  'TIU' => ['score' => (float) ($result['nilai_tiu'] ?? 0), 'min' => 80, 'max' => 175, 'passed' => (int) ($result['lulus_tiu'] ?? 0), 'color' => '#6EE7B7'],
  'TKP' => ['score' => (float) ($result['nilai_tkp'] ?? 0), 'min' => 166, 'max' => 225, 'passed' => (int) ($result['lulus_tkp'] ?? 0), 'color' => '#FCD34D'],
];
?>

<div class="page active" id="pg-hasil">
  <div class="page-body">
    <?php if ($resultError): ?>
      <div class="card anim" style="padding:12px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
        <?= htmlspecialchars($resultError) ?>
      </div>
    <?php endif; ?>

    <?php if (!$result): ?>
      <div class="card anim" style="padding:22px;text-align:center;">
        <div style="font-weight:800;color:var(--ink);margin-bottom:4px;">Hasil belum tersedia</div>
        <div style="font-size:12px;color:var(--ash);margin-bottom:12px;">Kamu belum menyelesaikan tryout atau hasil yang diminta tidak ditemukan.</div>
        <a href="<?= BASE_URL ?>/user/daftar-tryout" class="btn btn-primary"><i class="bi bi-journal-text"></i> Lihat Tryout</a>
      </div>
    <?php else: ?>
      <div class="result-banner anim">
        <div class="result-bg">
          <div class="res-glow"></div>
          <div class="result-content">
            <div class="res-rank">
              <div class="res-rank-num">#<?= $result['ranking'] ? (int) $result['ranking'] : '-' ?></div>
              <div class="res-rank-label">ranking sesi</div>
            </div>
            <span class="res-status-chip <?= $result['lulus_total'] ? 'pass' : 'fail' ?>">
              <i class="bi <?= $result['lulus_total'] ? 'bi-patch-check-fill' : 'bi-x-circle-fill' ?>"></i>
              <?= $result['lulus_total'] ? 'Lulus Passing Grade SKD' : 'Belum Lulus Passing Grade' ?>
            </span>
            <div class="res-score"><?= number_format((float) $result['total_nilai'], 0) ?></div>
            <div class="res-score-sub">dari 550 poin</div>
            <div class="res-title">
              <?= htmlspecialchars($result['nama_tryout']) ?>
              &nbsp;&middot;&nbsp;
              <?= htmlspecialchars(date('d M Y', strtotime($result['tanggal_mulai']))) ?>
            </div>
          </div>

          <div class="res-breakdown">
            <?php foreach ($categoryCards as $cat => $card): ?>
              <?php $pct = min(100, round(($card['score'] / max(1, $card['max'])) * 100)); ?>
              <div class="res-sub">
                <div class="res-sub-label"><?= $cat ?></div>
                <div class="res-sub-score"><?= number_format($card['score'], 0) ?></div>
                <div class="res-sub-pg">Min. <?= $card['min'] ?> &nbsp;&middot;&nbsp; <span style="color:<?= $card['passed'] ? '#6EE7B7' : '#FCA5A5' ?>;"><?= $card['passed'] ? 'Lulus' : 'Tidak Lulus' ?></span></div>
                <div class="res-sub-bar"><div class="res-sub-fill" style="width:<?= $pct ?>%;background:<?= $card['color'] ?>;"></div></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 290px;gap:15px;" class="anim anim-d1">
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="bi bi-list-check"></i> Detail Jawaban</div>
            <div style="display:flex;gap:5px;flex-wrap:wrap;">
              <span style="font-size:10.5px;background:#ECFDF5;color:#065F46;padding:2px 8px;border-radius:20px;font-weight:600;">Benar: <?= $benarCount ?></span>
              <span style="font-size:10.5px;background:#FEF2F2;color:#991B1B;padding:2px 8px;border-radius:20px;font-weight:600;">Salah: <?= $salahCount ?></span>
            </div>
          </div>
          <div style="overflow-x:auto;">
            <table class="data-table">
              <thead><tr><th>No</th><th>Kategori</th><th>Jawaban</th><th>Kunci/Skor</th><th>Hasil</th></tr></thead>
              <tbody>
                <?php if (!$detailRows): ?>
                  <tr><td colspan="5" style="text-align:center;color:var(--ash);padding:18px;">Detail jawaban belum tersedia.</td></tr>
                <?php endif; ?>
                <?php foreach ($detailRows as $i => $row): ?>
                  <?php
                    $cat = $row['kategori'];
                    $isTkp = $cat === 'TKP';
                    $isCorrect = (int) ($row['is_benar'] ?? 0) === 1;
                    $catStyle = $cat === 'TWK'
                      ? 'background:var(--frost);color:var(--blue-main);'
                      : ($cat === 'TIU' ? 'background:#F0FDF4;color:#166534;' : 'background:#FFFBEB;color:#92400E;');
                  ?>
                  <tr>
                    <td style="color:var(--ash);"><?= $i + 1 ?></td>
                    <td><span style="font-size:10px;font-weight:700;<?= $catStyle ?>padding:2px 6px;border-radius:4px;"><?= htmlspecialchars($cat) ?></span></td>
                    <td><?= htmlspecialchars($row['jawaban_peserta'] ?: '-') ?></td>
                    <td style="font-weight:700;color:var(--emerald);">
                      <?= $isTkp ? number_format((float) $row['poin_didapat'], 0) . ' poin' : htmlspecialchars($row['kode_kunci'] ?: '-') ?>
                    </td>
                    <td>
                      <?php if ($isTkp): ?>
                        <span class="badge badge-new"><?= number_format((float) $row['poin_didapat'], 0) ?> poin</span>
                      <?php elseif ($isCorrect): ?>
                        <span class="badge badge-pass">Benar</span>
                      <?php else: ?>
                        <span class="badge badge-fail">Salah</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:13px;">
          <div class="card">
            <div class="card-head"><div class="card-title"><i class="bi bi-lightbulb-fill" style="color:var(--gold);"></i> Rekomendasi Belajar</div></div>
            <div style="padding:14px;display:flex;flex-direction:column;gap:7px;">
              <?php foreach ($categoryCards as $cat => $card): ?>
                <?php
                  $tone = $card['passed'] ? ['#ECFDF5', '#065F46', 'Pertahankan performa kategori ini.'] : ['#FEF2F2', '#991B1B', 'Prioritaskan latihan kategori ini sebelum tryout berikutnya.'];
                ?>
                <div style="padding:9px 11px;background:<?= $tone[0] ?>;border-radius:var(--r-md);font-size:12px;color:<?= $tone[1] ?>;line-height:1.5;">
                  <strong><?= $cat ?>:</strong> <?= $tone[2] ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="card" style="padding:14px;">
            <div style="font-size:11px;color:var(--ash);line-height:1.7;">
              Durasi pengerjaan:
              <strong style="color:var(--ink);"><?= $result['durasi_detik'] ? floor(((int) $result['durasi_detik']) / 60) . ' menit' : '-' ?></strong><br>
              Status:
              <strong style="color:var(--ink);"><?= htmlspecialchars(ucfirst($result['status_pengerjaan'])) ?></strong>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
