<?php
$id = (int) $tryout['id_tryout'];

$formatDurasi = function (?int $detik): string {
  if (!$detik) {
    return '-';
  }

  $menit = intdiv($detik, 60);
  $sisaDetik = $detik % 60;

  return $sisaDetik > 0 ? "{$menit} menit {$sisaDetik} detik" : "{$menit} menit";
};
?>

<div class="page active" id="pg-hasil-tryout">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Lihat Hasil</h2>
      <p>Rekap nilai peserta untuk <?= htmlspecialchars($tryout['nama_tryout']) ?>.</p>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px;" class="anim anim-d1">
      <div class="filter-bar" style="margin-bottom:0;">
        <button class="filter-btn active" type="button" onclick="filterHasil('semua', this)">Semua</button>
        <button class="filter-btn" type="button" onclick="filterHasil('lulus', this)">Lulus</button>
        <button class="filter-btn" type="button" onclick="filterHasil('tidak-lulus', this)">Tidak Lulus</button>
      </div>
      <div style="display:flex;gap:8px;">
        <a href="<?= BASE_URL ?>/Admin/kelola-tryout" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button class="btn btn-primary" type="button" onclick="exportHasilTryoutCsv()"><i class="bi bi-download"></i> Ekspor</button>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:13px;margin-bottom:16px;" class="anim anim-d2 stat-grid">
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-people-fill"></i></div></div>
        <div class="stat-number"><?= (int) $hasilStats['submit'] ?></div>
        <div class="stat-label">Peserta submit</div>
      </div>
      <div class="stat-block c-green">
        <div class="stat-top"><div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div></div>
        <div class="stat-number"><?= (int) $hasilStats['lulus'] ?></div>
        <div class="stat-label">Peserta lulus</div>
      </div>
      <div class="stat-block c-gold">
        <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-trophy-fill"></i></div></div>
        <div class="stat-number"><?= number_format((float) $hasilStats['tertinggi'], 0) ?></div>
        <div class="stat-label">Nilai tertinggi</div>
      </div>
      <div class="stat-block c-amber">
        <div class="stat-top"><div class="stat-icon amber"><i class="bi bi-graph-up-arrow"></i></div></div>
        <div class="stat-number"><?= number_format((float) $hasilStats['rata'], 0) ?></div>
        <div class="stat-label">Rata-rata nilai</div>
      </div>
    </div>

    <div class="card anim anim-d3">
      <div class="card-head">
        <div>
          <div class="card-title"><i class="bi bi-table"></i> Daftar Hasil Peserta</div>
          <div style="font-size:11px;color:var(--ash);margin-top:3px;">
            <?= htmlspecialchars(date('d M Y H:i', strtotime($tryout['tanggal_mulai']))) ?> -
            <?= htmlspecialchars(date('d M Y H:i', strtotime($tryout['tanggal_selesai']))) ?>
          </div>
        </div>
        <a href="<?= BASE_URL ?>/Admin/edit-tryout?id=<?= $id ?>" class="btn btn-ghost btn-sm"><i class="bi bi-pencil"></i> Edit Tryout</a>
      </div>
      <div style="overflow-x:auto;">
        <table class="data-table" id="table-hasil-tryout">
          <thead>
            <tr>
              <th>Ranking</th>
              <th>Nama Peserta</th>
              <th>TWK</th>
              <th>TIU</th>
              <th>TKP</th>
              <th>Total</th>
              <th>Status</th>
              <th>Durasi</th>
              <th>Selesai</th>
            </tr>
          </thead>
          <tbody id="tbody-hasil-tryout">
            <?php if (!$hasilRows): ?>
              <tr>
                <td colspan="9" style="text-align:center;color:var(--ash);padding:24px;">Belum ada peserta yang menyelesaikan tryout ini.</td>
              </tr>
            <?php endif; ?>

            <?php foreach ($hasilRows as $index => $hasil): ?>
              <?php
                $isLulus = (int) $hasil['lulus_total'] === 1;
                $ranking = $hasil['ranking'] ? '#' . (int) $hasil['ranking'] : '#' . ($index + 1);
                $selesai = $hasil['waktu_selesai'] ? date('d M Y H:i', strtotime($hasil['waktu_selesai'])) : '-';
              ?>
              <tr data-status="<?= $isLulus ? 'lulus' : 'tidak-lulus' ?>">
                <td><?= htmlspecialchars($ranking) ?></td>
                <td style="font-weight:700;">
                  <?= htmlspecialchars($hasil['nama']) ?>
                  <div style="font-size:10.5px;color:var(--ash);font-weight:500;"><?= htmlspecialchars($hasil['email']) ?></div>
                </td>
                <td><?= number_format((float) $hasil['nilai_twk'], 0) ?></td>
                <td><?= number_format((float) $hasil['nilai_tiu'], 0) ?></td>
                <td><?= number_format((float) $hasil['nilai_tkp'], 0) ?></td>
                <td style="font-weight:800;color:<?= $isLulus ? 'var(--blue-main)' : 'var(--crimson)' ?>;"><?= number_format((float) $hasil['total_nilai'], 0) ?></td>
                <td><span class="badge <?= $isLulus ? 'badge-pass' : 'badge-fail' ?>"><?= $isLulus ? 'Lulus' : 'Belum' ?></span></td>
                <td><?= htmlspecialchars($formatDurasi((int) ($hasil['durasi_detik'] ?? 0))) ?></td>
                <td style="color:var(--ash);white-space:nowrap;"><?= htmlspecialchars($selesai) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function filterHasil(status, button) {
    document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active'));
    button.classList.add('active');

    document.querySelectorAll('#tbody-hasil-tryout tr[data-status]').forEach((row) => {
      row.style.display = status === 'semua' || row.dataset.status === status ? '' : 'none';
    });
  }

  function exportHasilTryoutCsv() {
    const rows = Array.from(document.querySelectorAll('#table-hasil-tryout tr'));
    const csv = rows.map((row) => {
      return Array.from(row.children).map((cell) => `"${cell.innerText.replace(/"/g, '""')}"`).join(',');
    }).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'hasil-tryout-<?= $id ?>.csv';
    link.click();
    URL.revokeObjectURL(link.href);
  }
</script>
