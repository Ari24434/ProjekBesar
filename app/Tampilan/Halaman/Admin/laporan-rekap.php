<?php
$results = [];
$reportError = null;

try {
  $sql = "
    SELECT
      id_user,
      nama_peserta AS nama,
      nama_tryout AS tryout,
      DATE(tanggal_mulai) AS tanggal,
      nilai_twk AS twk,
      nilai_tiu AS tiu,
      nilai_tkp AS tkp,
      total_nilai AS total,
      lulus_total AS lulus
    FROM v_rekap_nilai
    WHERE status_pengerjaan IN ('selesai', 'timeout')
    ORDER BY nama_peserta ASC, tanggal_mulai ASC
  ";

  foreach (db_fetch_all($sql) as $row) {
    $results[] = [
      'id_user' => (int) $row['id_user'],
      'nama' => $row['nama'],
      'tryout' => $row['tryout'],
      'tanggal' => $row['tanggal'],
      'twk' => (float) $row['twk'],
      'tiu' => (float) $row['tiu'],
      'tkp' => (float) $row['tkp'],
      'total' => (float) $row['total'],
      'lulus' => (int) $row['lulus'],
    ];
  }
} catch (Throwable $e) {
  $reportError = 'Data laporan belum bisa dibaca: ' . $e->getMessage();
}

$participants = [];
$sessionSummary = [];
$categoryTotals = ['twk' => 0, 'tiu' => 0, 'tkp' => 0, 'total' => 0];

foreach ($results as $result) {
  $id = $result['id_user'];

  if (!isset($participants[$id])) {
    $participants[$id] = [
      'nama' => $result['nama'],
      'results' => [],
      'twk' => 0,
      'tiu' => 0,
      'tkp' => 0,
      'total' => 0,
      'best' => 0,
      'passed' => 0,
    ];
  }

  $participants[$id]['results'][] = $result;
  $participants[$id]['twk'] += $result['twk'];
  $participants[$id]['tiu'] += $result['tiu'];
  $participants[$id]['tkp'] += $result['tkp'];
  $participants[$id]['total'] += $result['total'];
  $participants[$id]['best'] = max($participants[$id]['best'], $result['total']);
  $participants[$id]['passed'] += $result['lulus'];

  if (!isset($sessionSummary[$result['tryout']])) {
    $sessionSummary[$result['tryout']] = ['label' => $result['tryout'], 'total' => 0, 'count' => 0];
  }

  $sessionSummary[$result['tryout']]['total'] += $result['total'];
  $sessionSummary[$result['tryout']]['count']++;

  $categoryTotals['twk'] += $result['twk'];
  $categoryTotals['tiu'] += $result['tiu'];
  $categoryTotals['tkp'] += $result['tkp'];
  $categoryTotals['total'] += $result['total'];
}

$resultCount = count($results);
$participantCount = count($participants);
$passedCount = count(array_filter($participants, function ($participant) {
  return $participant['passed'] > 0;
}));

$avgTwk = $resultCount ? round($categoryTotals['twk'] / $resultCount) : 0;
$avgTiu = $resultCount ? round($categoryTotals['tiu'] / $resultCount) : 0;
$avgTkp = $resultCount ? round($categoryTotals['tkp'] / $resultCount) : 0;
$avgTotal = $resultCount ? round($categoryTotals['total'] / $resultCount) : 0;
$passRate = $participantCount ? round(($passedCount / $participantCount) * 100) : 0;
$bestScore = $results ? max(array_column($results, 'total')) : 0;

$sessionChart = array_map(function ($session) {
  return [
    'label' => preg_replace('/^Tryout SKD\s*/', '', $session['label']),
    'value' => round($session['total'] / max($session['count'], 1), 1),
  ];
}, array_values($sessionSummary));

$categoryChart = [
  ['label' => 'TWK', 'value' => $avgTwk, 'max' => 150, 'color' => '#1E54B7'],
  ['label' => 'TIU', 'value' => $avgTiu, 'max' => 175, 'color' => '#10B981'],
  ['label' => 'TKP', 'value' => $avgTkp, 'max' => 225, 'color' => '#F59E0B'],
];
?>

<div class="page active" id="pg-laporan">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Laporan Rekap</h2>
      <p>Ringkasan performa seluruh peserta berdasarkan nilai TWK, TIU, dan TKP.</p>
    </div>

    <?php if ($reportError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($reportError) ?>
      </div>
    <?php endif; ?>

    <div class="laporan-stat-grid anim anim-d1">
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-people-fill"></i></div></div>
        <div class="stat-number"><?= $participantCount ?></div>
        <div class="stat-label">Peserta sudah tryout</div>
      </div>
      <div class="stat-block c-green">
        <div class="stat-top"><div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div></div>
        <div class="stat-number"><?= $passRate ?>%</div>
        <div class="stat-label">Status akhir lulus</div>
      </div>
      <div class="stat-block c-gold">
        <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-trophy-fill"></i></div></div>
        <div class="stat-number"><?= number_format($bestScore, 0) ?></div>
        <div class="stat-label">Nilai terbaik</div>
      </div>
      <div class="stat-block c-amber">
        <div class="stat-top"><div class="stat-icon amber"><i class="bi bi-calculator-fill"></i></div></div>
        <div class="stat-number"><?= $avgTotal ?></div>
        <div class="stat-label">Rata-rata total</div>
      </div>
    </div>

    <div class="laporan-chart-grid anim anim-d2">
      <div class="card">
        <div class="card-head"><div class="card-title"><i class="bi bi-graph-up-arrow"></i> Tren Nilai per Sesi</div></div>
        <div class="chart-box"><canvas id="chart-laporan" height="210"></canvas></div>
      </div>
      <div class="card">
        <div class="card-head"><div class="card-title"><i class="bi bi-bar-chart-fill"></i> Rata-rata Nilai per Materi</div></div>
        <div class="chart-box"><canvas id="chart-dist" height="210"></canvas></div>
      </div>
    </div>

    <div class="card anim anim-d3">
      <div class="card-head laporan-table-head">
        <div>
          <div class="card-title"><i class="bi bi-table"></i> Rekap Nilai Peserta</div>
          <div class="laporan-source">Hanya menampilkan data hasil tryout yang tersimpan di database.</div>
        </div>
        <div class="laporan-tools">
          <div class="search-bar laporan-search">
            <i class="bi bi-search"></i>
            <input type="text" id="laporan-search" placeholder="Cari peserta...">
          </div>
          <button class="btn btn-ghost btn-sm" type="button" onclick="exportLaporanCsv()"><i class="bi bi-download"></i> Ekspor</button>
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table class="data-table" id="table-laporan">
          <thead>
            <tr>
              <th>#</th>
              <th>Peserta</th>
              <th>Tryout</th>
              <th>Rata-rata TWK</th>
              <th>Rata-rata TIU</th>
              <th>Rata-rata TKP</th>
              <th>Nilai Terbaik</th>
              <th>Rata-rata Total</th>
              <th>Tren</th>
              <th>Status Akhir</th>
            </tr>
          </thead>
          <tbody id="tbody-laporan">
            <?php if (!$participants): ?>
              <tr>
                <td colspan="10" style="text-align:center;color:var(--ash);padding:24px;">Belum ada hasil tryout yang bisa direkap.</td>
              </tr>
            <?php endif; ?>
            <?php $number = 1; ?>
            <?php foreach ($participants as $participant): ?>
              <?php
                $count = count($participant['results']);
                $firstScore = $participant['results'][0]['total'];
                $lastScore = $participant['results'][$count - 1]['total'];
                $delta = $lastScore - $firstScore;
                $isPassed = $participant['passed'] > 0;
              ?>
              <tr>
                <td style="color:var(--ash);"><?= $number++ ?></td>
                <td style="font-weight:700;"><?= htmlspecialchars($participant['nama']) ?></td>
                <td><?= $count ?> sesi</td>
                <td><span class="score-chip twk"><?= round($participant['twk'] / $count) ?></span></td>
                <td><span class="score-chip tiu"><?= round($participant['tiu'] / $count) ?></span></td>
                <td><span class="score-chip tkp"><?= round($participant['tkp'] / $count) ?></span></td>
                <td style="font-weight:800;color:var(--blue-main);"><?= number_format($participant['best'], 0) ?></td>
                <td style="font-weight:700;"><?= round($participant['total'] / $count) ?></td>
                <td>
                  <span class="trend <?= $delta >= 0 ? 'up' : 'down' ?>">
                    <i class="bi <?= $delta >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' ?>"></i>
                    <?= $delta >= 0 ? '+' : '' ?><?= number_format($delta, 0) ?>
                  </span>
                </td>
                <td><span class="badge <?= $isPassed ? 'badge-pass' : 'badge-fail' ?>"><?= $isPassed ? 'Lulus' : 'Belum' ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .laporan-stat-grid,
  .laporan-chart-grid {
    display: grid;
    gap: 13px;
    margin-bottom: 16px;
  }

  .laporan-stat-grid {
    grid-template-columns: repeat(4, 1fr);
  }

  .laporan-chart-grid {
    grid-template-columns: 1fr 1fr;
  }

  .chart-box {
    height: 260px;
    padding: 18px;
  }

  .chart-box canvas {
    width: 100%;
    height: 100%;
  }

  .laporan-table-head,
  .laporan-tools {
    gap: 12px;
    flex-wrap: wrap;
  }

  .laporan-tools {
    display: flex;
    align-items: center;
  }

  .laporan-search {
    min-width: 220px;
    padding: 6px 10px;
  }

  .laporan-source {
    margin-top: 4px;
    font-size: 11px;
    color: var(--ash);
    font-weight: 500;
  }

  .score-chip {
    display: inline-flex;
    min-width: 38px;
    justify-content: center;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
  }

  .score-chip.twk { background: var(--frost); color: var(--blue-main); }
  .score-chip.tiu { background: #ECFDF5; color: #047857; }
  .score-chip.tkp { background: #FFFBEB; color: #B45309; }

  .trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 700;
  }

  .trend.up { color: var(--emerald); }
  .trend.down { color: var(--crimson); }

  @media (max-width: 1024px) {
    .laporan-stat-grid,
    .laporan-chart-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 780px) {
    .laporan-stat-grid,
    .laporan-chart-grid {
      grid-template-columns: 1fr;
    }

    .laporan-tools,
    .laporan-search {
      width: 100%;
    }
  }
</style>

<script>
  const laporanSessionData = <?= json_encode(array_values($sessionChart)) ?>;
  const laporanCategoryData = <?= json_encode($categoryChart) ?>;

  function fitCanvas(canvas) {
    const box = canvas.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    canvas.width = box.width * ratio;
    canvas.height = box.height * ratio;
    const ctx = canvas.getContext('2d');
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    return { ctx, width: box.width, height: box.height };
  }

  function drawLineChart() {
    const canvas = document.getElementById('chart-laporan');
    if (!canvas) return;

    const { ctx, width, height } = fitCanvas(canvas);
    const pad = { top: 20, right: 16, bottom: 42, left: 42 };
    const values = laporanSessionData.map(item => item.value);
    const max = Math.max(500, ...values);
    const min = 0;
    const plotW = width - pad.left - pad.right;
    const plotH = height - pad.top - pad.bottom;

    ctx.clearRect(0, 0, width, height);
    ctx.font = '11px Plus Jakarta Sans';
    ctx.strokeStyle = '#E2E8F5';
    ctx.fillStyle = '#94A3B8';

    for (let i = 0; i <= 4; i++) {
      const y = pad.top + (plotH / 4) * i;
      ctx.beginPath();
      ctx.moveTo(pad.left, y);
      ctx.lineTo(width - pad.right, y);
      ctx.stroke();
      ctx.fillText(String(Math.round(max - ((max - min) / 4) * i)), 8, y + 4);
    }

    if (!laporanSessionData.length) return;

    const points = laporanSessionData.map((item, index) => {
      const x = pad.left + (laporanSessionData.length === 1 ? plotW / 2 : (plotW / (laporanSessionData.length - 1)) * index);
      const y = pad.top + plotH - ((item.value - min) / (max - min)) * plotH;
      return { x, y, ...item };
    });

    ctx.strokeStyle = '#1E54B7';
    ctx.lineWidth = 3;
    ctx.beginPath();
    points.forEach((point, index) => index ? ctx.lineTo(point.x, point.y) : ctx.moveTo(point.x, point.y));
    ctx.stroke();

    points.forEach(point => {
      ctx.fillStyle = '#1E54B7';
      ctx.beginPath();
      ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
      ctx.fill();
      ctx.fillStyle = '#0F1729';
      ctx.font = '700 11px Plus Jakarta Sans';
      ctx.fillText(point.value, point.x - 10, point.y - 10);
      ctx.fillStyle = '#94A3B8';
      ctx.font = '10.5px Plus Jakarta Sans';
      ctx.fillText(point.label.replace(' - ', ' '), point.x - 22, height - 14);
    });
  }

  function drawCategoryChart() {
    const canvas = document.getElementById('chart-dist');
    if (!canvas) return;

    const { ctx, width, height } = fitCanvas(canvas);
    const pad = { top: 26, right: 18, bottom: 34, left: 34 };
    const gap = 26;
    const plotW = width - pad.left - pad.right;
    const plotH = height - pad.top - pad.bottom;
    const barW = (plotW - gap * (laporanCategoryData.length - 1)) / laporanCategoryData.length;

    ctx.clearRect(0, 0, width, height);
    ctx.font = '11px Plus Jakarta Sans';
    ctx.strokeStyle = '#E2E8F5';

    for (let i = 0; i <= 4; i++) {
      const y = pad.top + (plotH / 4) * i;
      ctx.beginPath();
      ctx.moveTo(pad.left, y);
      ctx.lineTo(width - pad.right, y);
      ctx.stroke();
    }

    laporanCategoryData.forEach((item, index) => {
      const x = pad.left + index * (barW + gap);
      const barH = (item.value / item.max) * plotH;
      const y = pad.top + plotH - barH;

      ctx.fillStyle = '#F8FAFF';
      ctx.fillRect(x, pad.top, barW, plotH);
      ctx.fillStyle = item.color;
      ctx.fillRect(x, y, barW, barH);
      ctx.fillStyle = '#0F1729';
      ctx.font = '700 12px Plus Jakarta Sans';
      ctx.fillText(item.value, x + barW / 2 - 8, y - 8);
      ctx.fillStyle = '#94A3B8';
      ctx.font = '700 11px Plus Jakarta Sans';
      ctx.fillText(item.label, x + barW / 2 - 10, height - 12);
    });
  }

  function exportLaporanCsv() {
    const rows = Array.from(document.querySelectorAll('#table-laporan tr'));
    const csv = rows.map(row => {
      return Array.from(row.children).map(cell => `"${cell.innerText.replace(/"/g, '""')}"`).join(',');
    }).join('\n');
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'laporan-rekap-tryout.csv';
    link.click();
    URL.revokeObjectURL(link.href);
  }

  document.getElementById('laporan-search')?.addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('#tbody-laporan tr').forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
    });
  });

  window.addEventListener('resize', () => {
    drawLineChart();
    drawCategoryChart();
  });

  drawLineChart();
  drawCategoryChart();
</script>
