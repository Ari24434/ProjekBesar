<?php
$tryoutRows = $tryoutRows ?? [];
$tryoutError = $tryoutError ?? null;
?>

<div class="page active" id="pg-tryout">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Daftar Tryout</h2>
      <p>Pilih sesi tryout yang tersedia dan kerjakan sesuai jadwal yang dibuka admin.</p>
    </div>

    <?php if ($tryoutError): ?>
      <div class="card anim" style="padding:12px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
        <?= htmlspecialchars($tryoutError) ?>
      </div>
    <?php endif; ?>

    <?php if (!$tryoutRows): ?>
      <div class="card anim" style="padding:22px;text-align:center;">
        <div style="width:46px;height:46px;border-radius:12px;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:22px;">
          <i class="bi bi-calendar-x"></i>
        </div>
        <div style="font-weight:800;color:var(--ink);margin-bottom:4px;">Belum ada tryout aktif</div>
        <div style="font-size:12px;color:var(--ash);">Tryout akan muncul di sini saat admin membuka jadwal pengerjaan.</div>
      </div>
    <?php else: ?>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:15px;">
        <?php foreach ($tryoutRows as $index => $tryout): ?>
          <?php
            $statusPengerjaan = $tryout['status_pengerjaan'] ?? null;
            $isDone = in_array($statusPengerjaan, ['selesai', 'timeout'], true);
            $isProgress = $statusPengerjaan === 'sedang';
            $totalSoal = (int) ($tryout['total_soal'] ?? 0);
            $score = (float) ($tryout['total_nilai'] ?? 0);
            $scorePct = min(100, round(($score / 550) * 100));
            $badgeClass = $isDone ? 'badge-done' : ($isProgress ? 'badge-warning' : 'badge-new');
            $badgeText = $isDone ? 'Selesai' : ($isProgress ? 'Sedang' : 'Baru');
          ?>
          <div class="card anim <?= $index > 0 ? 'anim-d1' : '' ?>" style="border-top:3px solid <?= $isDone ? 'var(--emerald)' : 'var(--blue-main)' ?>;">
            <div style="padding:18px;">
              <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:13px;">
                <div style="width:40px;height:40px;border-radius:10px;background:<?= $isDone ? '#ECFDF5' : 'var(--frost)' ?>;color:<?= $isDone ? 'var(--emerald)' : 'var(--blue-main)' ?>;display:flex;align-items:center;justify-content:center;font-size:19px;">
                  <i class="bi <?= $isDone ? 'bi-journal-check' : 'bi-journal-text' ?>"></i>
                </div>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeText) ?></span>
              </div>

              <div style="font-weight:700;font-size:14.5px;color:var(--ink);margin-bottom:5px;"><?= htmlspecialchars($tryout['nama_tryout']) ?></div>
              <div style="font-size:12px;color:var(--ash);line-height:1.7;margin-bottom:13px;min-height:42px;">
                <?= htmlspecialchars($tryout['deskripsi'] ?: 'Simulasi ujian SKD dengan komposisi soal dari bank soal aktif.') ?>
              </div>

              <div style="display:flex;gap:7px;flex-wrap:wrap;margin-bottom:15px;">
                <span style="font-size:10.5px;background:var(--cloud);border:1px solid var(--smoke);color:var(--slate);padding:3px 8px;border-radius:var(--r-sm);"><i class="bi bi-clock" style="font-size:9px;"></i> <?= (int) $tryout['waktu'] ?> menit</span>
                <span style="font-size:10.5px;background:var(--cloud);border:1px solid var(--smoke);color:var(--slate);padding:3px 8px;border-radius:var(--r-sm);"><i class="bi bi-list-ol" style="font-size:9px;"></i> <?= $totalSoal ?> soal</span>
                <span style="font-size:10.5px;background:var(--cloud);border:1px solid var(--smoke);color:var(--slate);padding:3px 8px;border-radius:var(--r-sm);"><i class="bi bi-calendar3" style="font-size:9px;"></i> <?= htmlspecialchars(date('d M Y', strtotime($tryout['tanggal_mulai']))) ?></span>
              </div>

              <?php if ($isDone): ?>
                <div style="background:var(--cloud);border:1px solid var(--smoke);border-radius:var(--r-md);padding:11px 13px;margin-bottom:13px;">
                  <div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:5px;"><span style="color:var(--ash);">Nilai kamu</span><strong style="color:var(--blue-main);font-size:15px;font-family:'Playfair Display',serif;"><?= number_format($score, 0) ?></strong></div>
                  <div class="progress-bar"><div class="progress-fill" style="width:<?= $scorePct ?>%;background:var(--blue-main);"></div></div>
                  <div style="font-size:10.5px;color:var(--ash);margin-top:4px;"><?= $tryout['lulus_total'] ? 'Lulus passing grade' : 'Belum lulus passing grade' ?></div>
                </div>
                <a href="<?= BASE_URL ?>/user/hasil-tryout?id=<?= (int) $tryout['id_hasil'] ?>" class="btn btn-ghost" style="width:100%;justify-content:center;"><i class="bi bi-eye"></i> Lihat Hasil Detail</a>
              <?php else: ?>
                <form method="post" action="<?= BASE_URL ?>/user/tryout/start">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id_tryout" value="<?= (int) $tryout['id_tryout'] ?>">
                  <button class="btn btn-primary" type="submit" style="width:100%;justify-content:center;" <?= $totalSoal <= 0 ? 'disabled' : '' ?>>
                    <i class="bi <?= $isProgress ? 'bi-arrow-repeat' : 'bi-play-circle-fill' ?>"></i>
                    <?= $isProgress ? 'Lanjutkan Tryout' : 'Mulai Tryout' ?>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
