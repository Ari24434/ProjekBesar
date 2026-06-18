<?php
$historyRows = $historyRows ?? [];
$historyStats = $historyStats ?? [
  'total_tryout' => 0,
  'passed_count' => 0,
  'best_score' => 0,
  'avg_score' => 0,
];
$historyError = $historyError ?? null;
?>

<div class="page active" id="pg-riwayat">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Riwayat Nilai</h2>
      <p>Semua riwayat tryout yang pernah kamu ikuti.</p>
    </div>

    <?php if ($historyError): ?>
      <div class="card anim" style="padding:12px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
        <?= htmlspecialchars($historyError) ?>
      </div>
    <?php endif; ?>

    <div style="display:flex;gap:11px;flex-wrap:wrap;margin-bottom:18px;" class="anim anim-d1">
      <div class="card" style="padding:13px 16px;display:flex;align-items:center;gap:11px;min-width:130px;">
        <div style="width:34px;height:34px;border-radius:9px;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:15px;"><i class="bi bi-journal-text"></i></div>
        <div><div style="font-family:'Playfair Display',serif;font-size:21px;color:var(--ink);"><?= (int) $historyStats['total_tryout'] ?></div><div style="font-size:10.5px;color:var(--ash);">Total Tryout</div></div>
      </div>
      <div class="card" style="padding:13px 16px;display:flex;align-items:center;gap:11px;min-width:130px;">
        <div style="width:34px;height:34px;border-radius:9px;background:#ECFDF5;color:var(--emerald);display:flex;align-items:center;justify-content:center;font-size:15px;"><i class="bi bi-patch-check-fill"></i></div>
        <div><div style="font-family:'Playfair Display',serif;font-size:21px;color:var(--ink);"><?= (int) $historyStats['passed_count'] ?></div><div style="font-size:10.5px;color:var(--ash);">Sesi Lulus</div></div>
      </div>
      <div class="card" style="padding:13px 16px;display:flex;align-items:center;gap:11px;min-width:130px;">
        <div style="width:34px;height:34px;border-radius:9px;background:#FFFBEB;color:var(--gold);display:flex;align-items:center;justify-content:center;font-size:15px;"><i class="bi bi-trophy-fill"></i></div>
        <div><div style="font-family:'Playfair Display',serif;font-size:21px;color:var(--ink);"><?= number_format((float) $historyStats['best_score'], 0) ?></div><div style="font-size:10.5px;color:var(--ash);">Nilai Terbaik</div></div>
      </div>
      <div class="card" style="padding:13px 16px;display:flex;align-items:center;gap:11px;min-width:130px;">
        <div style="width:34px;height:34px;border-radius:9px;background:var(--cloud);color:var(--slate);display:flex;align-items:center;justify-content:center;font-size:15px;"><i class="bi bi-graph-up"></i></div>
        <div><div style="font-family:'Playfair Display',serif;font-size:21px;color:var(--ink);"><?= number_format((float) $historyStats['avg_score'], 1) ?></div><div style="font-size:10.5px;color:var(--ash);">Rata-rata</div></div>
      </div>
    </div>

    <div class="card anim anim-d2">
      <div style="overflow-x:auto;">
        <table class="data-table">
          <thead><tr><th>#</th><th>Nama Tryout</th><th>TWK</th><th>TIU</th><th>TKP</th><th>Total</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
          <tbody>
            <?php if (!$historyRows): ?>
              <tr>
                <td colspan="9" style="text-align:center;color:var(--ash);padding:22px;">
                  Belum ada riwayat tryout selesai untuk akun ini.
                </td>
              </tr>
            <?php endif; ?>

            <?php foreach ($historyRows as $index => $row): ?>
              <?php
                $isPass = (int) ($row['lulus_total'] ?? 0) === 1;
                $totalColor = $isPass ? 'var(--blue-main)' : 'var(--crimson)';
                $finishedAt = $row['waktu_selesai'] ?: $row['waktu_mulai'];
              ?>
              <tr>
                <td style="color:var(--ash);"><?= count($historyRows) - $index ?></td>
                <td style="font-weight:600;min-width:180px;"><?= htmlspecialchars($row['nama_tryout'] ?? '-') ?></td>
                <td><?= number_format((float) ($row['nilai_twk'] ?? 0), 0) ?></td>
                <td><?= number_format((float) ($row['nilai_tiu'] ?? 0), 0) ?></td>
                <td><?= number_format((float) ($row['nilai_tkp'] ?? 0), 0) ?></td>
                <td style="font-weight:700;color:<?= $totalColor ?>;font-family:'Playfair Display',serif;font-size:15px;"><?= number_format((float) ($row['total_nilai'] ?? 0), 0) ?></td>
                <td>
                  <?php if ($isPass): ?>
                    <span class="badge badge-pass">Lulus</span>
                  <?php else: ?>
                    <span class="badge badge-fail">Belum</span>
                  <?php endif; ?>
                </td>
                <td style="color:var(--ash);font-size:11.5px;white-space:nowrap;">
                  <?= $finishedAt ? htmlspecialchars(date('d M Y', strtotime($finishedAt))) : '-' ?>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>/user/hasil-tryout?id=<?= (int) $row['id_hasil'] ?>" class="btn btn-ghost btn-sm" title="Lihat hasil detail"><i class="bi bi-eye"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
