<?php
$analysisTryoutOptions = $analysisTryoutOptions ?? [];
$selectedTryout = $selectedTryout ?? 0;
$analysisSummary = $analysisSummary ?? ['total_hasil' => 0, 'total_peserta' => 0, 'avg_total' => 0, 'pass_rate' => 0];
$analysisCategoryScores = $analysisCategoryScores ?? [];
$analysisQuestionSummary = $analysisQuestionSummary ?? [];
$analysisDifficultyRows = $analysisDifficultyRows ?? [];
$analysisHardQuestions = $analysisHardQuestions ?? [];
$analysisUnusedQuestions = $analysisUnusedQuestions ?? [];
$analysisLeaderboard = $analysisLeaderboard ?? [];
$analysisError = $analysisError ?? null;

$questionSummaryByCategory = [];
foreach ($analysisQuestionSummary as $row) {
  $questionSummaryByCategory[$row['kategori']] = $row;
}

$difficultyByCategory = [];
foreach ($analysisDifficultyRows as $row) {
  $difficultyByCategory[$row['kategori']][] = $row;
}

$categoryLabels = [
  'TWK' => 'Tes Wawasan Kebangsaan',
  'TIU' => 'Tes Intelegensia Umum',
  'TKP' => 'Tes Karakteristik Pribadi',
];

$shortText = function (?string $text, int $limit = 115): string {
  $text = trim((string) $text);

  if ($text === '') {
    return '[Soal bergambar atau tanpa teks]';
  }

  if (mb_strlen($text) <= $limit) {
    return $text;
  }

  return mb_substr($text, 0, $limit) . '...';
};
?>

<div class="page active" id="pg-analisis">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Analisis Tryout</h2>
      <p>Evaluasi kualitas soal, performa peserta, dan capaian nilai per materi.</p>
    </div>

    <?php if ($analysisError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($analysisError) ?>
      </div>
    <?php endif; ?>

    <form method="get" action="<?= BASE_URL ?>/Admin/analisis" class="analysis-toolbar anim anim-d1">
      <div class="form-group analysis-select">
        <select class="form-select" name="tryout" onchange="this.form.submit()">
          <option value="0">Semua Tryout</option>
          <?php foreach ($analysisTryoutOptions as $tryout): ?>
            <option value="<?= (int) $tryout['id_tryout'] ?>" <?= (int) $selectedTryout === (int) $tryout['id_tryout'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($tryout['nama_tryout']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if ((int) $selectedTryout > 0): ?>
        <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/analisis">Reset</a>
      <?php endif; ?>
      <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/Admin/laporan-rekap"><i class="bi bi-file-earmark-bar-graph"></i> Laporan Rekap</a>
    </form>

    <div class="analysis-stat-grid anim anim-d2">
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-clipboard-check-fill"></i></div></div>
        <div class="stat-number"><?= (int) $analysisSummary['total_hasil'] ?></div>
        <div class="stat-label">Hasil masuk</div>
      </div>
      <div class="stat-block c-green">
        <div class="stat-top"><div class="stat-icon green"><i class="bi bi-people-fill"></i></div></div>
        <div class="stat-number"><?= (int) $analysisSummary['total_peserta'] ?></div>
        <div class="stat-label">Peserta terukur</div>
      </div>
      <div class="stat-block c-gold">
        <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-calculator-fill"></i></div></div>
        <div class="stat-number"><?= number_format((float) $analysisSummary['avg_total'], 1, ',', '.') ?></div>
        <div class="stat-label">Rata-rata total</div>
      </div>
      <div class="stat-block c-amber">
        <div class="stat-top"><div class="stat-icon amber"><i class="bi bi-patch-check-fill"></i></div></div>
        <div class="stat-number"><?= number_format((float) $analysisSummary['pass_rate'], 1, ',', '.') ?>%</div>
        <div class="stat-label">Tingkat kelulusan</div>
      </div>
    </div>

    <div class="analysis-category-grid anim anim-d3">
      <?php foreach ($analysisCategoryScores as $code => $score): ?>
        <?php
          $questionInfo = $questionSummaryByCategory[$code] ?? ['total_soal' => 0, 'soal_terjawab' => 0, 'total_dijawab' => 0, 'avg_pct_benar' => 0, 'avg_poin' => 0];
          $scorePct = min(100, round(((float) $score['avg'] / max((float) $score['max'], 1)) * 100));
          $answeredPct = min(100, round(((int) $questionInfo['soal_terjawab'] / max((int) $questionInfo['total_soal'], 1)) * 100));
        ?>
        <div class="card analysis-category-card" style="border-top-color:<?= htmlspecialchars($score['color']) ?>;">
          <div class="analysis-card-head">
            <div>
              <div class="analysis-code" style="color:<?= htmlspecialchars($score['color']) ?>;"><?= htmlspecialchars($code) ?></div>
              <div class="analysis-title"><?= htmlspecialchars($categoryLabels[$code] ?? $code) ?></div>
            </div>
            <span class="badge badge-<?= strtolower($code) ?>">Min. <?= (int) $score['threshold'] ?></span>
          </div>
          <div class="analysis-metric-row">
            <div>
              <div class="analysis-metric"><?= number_format((float) $score['avg'], 1, ',', '.') ?></div>
              <div class="analysis-caption">rata-rata nilai</div>
            </div>
            <div>
              <div class="analysis-metric"><?= (int) $score['passed'] ?></div>
              <div class="analysis-caption">hasil lulus batas</div>
            </div>
          </div>
          <div class="analysis-progress-label"><span>Capaian nilai</span><span><?= $scorePct ?>%</span></div>
          <div class="progress-bar"><div class="progress-fill" style="width:<?= $scorePct ?>%;background:<?= htmlspecialchars($score['color']) ?>;"></div></div>
          <div class="analysis-progress-label"><span>Soal pernah dijawab</span><span><?= $answeredPct ?>%</span></div>
          <div class="progress-bar"><div class="progress-fill" style="width:<?= $answeredPct ?>%;background:var(--emerald);"></div></div>
          <div class="analysis-foot">
            <?= (int) $questionInfo['soal_terjawab'] ?>/<?= (int) $questionInfo['total_soal'] ?> soal terjawab -
            akurasi <?= number_format((float) $questionInfo['avg_pct_benar'], 1, ',', '.') ?>%
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="analysis-main-grid anim anim-d4">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-bar-chart-steps"></i> Sebaran Tingkat Kesulitan</div>
        </div>
        <div class="analysis-difficulty-list">
          <?php if (!$analysisDifficultyRows): ?>
            <div class="analysis-empty">Belum ada data soal.</div>
          <?php endif; ?>
          <?php foreach ($difficultyByCategory as $category => $rows): ?>
            <div class="analysis-difficulty-group">
              <div class="analysis-group-title"><span class="badge badge-<?= strtolower($category) ?>"><?= htmlspecialchars($category) ?></span></div>
              <?php foreach ($rows as $row): ?>
                <?php
                  $maxCount = max(array_map(fn($item) => (int) $item['total_soal'], $rows));
                  $width = min(100, round(((int) $row['total_soal'] / max($maxCount, 1)) * 100));
                ?>
                <div class="analysis-difficulty-row">
                  <div class="analysis-diff-label"><?= htmlspecialchars(ucfirst($row['tingkat_kesulitan'])) ?></div>
                  <div class="analysis-diff-bar"><span style="width:<?= $width ?>%;"></span></div>
                  <div class="analysis-diff-value"><?= (int) $row['total_soal'] ?> soal</div>
                  <div class="analysis-diff-accuracy"><?= number_format((float) $row['avg_pct_benar'], 1, ',', '.') ?>%</div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-trophy-fill"></i> Peserta Teratas</div>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table analysis-compact-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Peserta</th>
                <th>Rata-rata</th>
                <th>Terbaik</th>
                <th>Lulus</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$analysisLeaderboard): ?>
                <tr><td colspan="5" class="analysis-empty-cell">Belum ada hasil peserta.</td></tr>
              <?php endif; ?>
              <?php foreach ($analysisLeaderboard as $index => $row): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td style="font-weight:700;"><?= htmlspecialchars($row['nama_peserta']) ?><div class="analysis-caption"><?= (int) $row['total_sesi'] ?> sesi</div></td>
                  <td><?= number_format((float) $row['avg_total'], 1, ',', '.') ?></td>
                  <td style="font-weight:800;color:var(--blue-main);"><?= number_format((float) $row['best_total'], 0, ',', '.') ?></td>
                  <td><?= (int) $row['total_lulus'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="analysis-main-grid anim anim-d5">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-exclamation-triangle-fill"></i> Soal Tersulit</div>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table analysis-compact-table">
            <thead>
              <tr>
                <th>Kategori</th>
                <th>Soal</th>
                <th>Dijawab</th>
                <th>Akurasi</th>
                <th>Poin</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$analysisHardQuestions): ?>
                <tr><td colspan="5" class="analysis-empty-cell">Belum ada detail jawaban peserta. Data ini akan muncul setelah `detail_hasil` terisi.</td></tr>
              <?php endif; ?>
              <?php foreach ($analysisHardQuestions as $row): ?>
                <tr>
                  <td><span class="badge badge-<?= strtolower($row['kategori']) ?>"><?= htmlspecialchars($row['kategori']) ?></span></td>
                  <td style="min-width:260px;"><?= htmlspecialchars($shortText($row['pertanyaan'])) ?><div class="analysis-caption"><?= htmlspecialchars(ucfirst($row['tingkat_kesulitan'])) ?></div></td>
                  <td><?= (int) $row['total_dijawab'] ?></td>
                  <td style="font-weight:800;color:var(--crimson);"><?= number_format((float) $row['pct_benar'], 1, ',', '.') ?>%</td>
                  <td><?= number_format((float) $row['rata_poin'], 2, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-inbox-fill"></i> Soal Belum Pernah Dijawab</div>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table analysis-compact-table">
            <thead>
              <tr>
                <th>Kategori</th>
                <th>Soal</th>
                <th>Level</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$analysisUnusedQuestions): ?>
                <tr><td colspan="4" class="analysis-empty-cell">Semua soal sudah pernah muncul di detail hasil.</td></tr>
              <?php endif; ?>
              <?php foreach ($analysisUnusedQuestions as $row): ?>
                <tr>
                  <td><span class="badge badge-<?= strtolower($row['kategori']) ?>"><?= htmlspecialchars($row['kategori']) ?></span></td>
                  <td style="min-width:260px;"><?= htmlspecialchars($shortText($row['pertanyaan'])) ?></td>
                  <td><?= htmlspecialchars(ucfirst($row['tingkat_kesulitan'])) ?></td>
                  <td><a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/Admin/edit-soal?id=<?= (int) $row['id_soal'] ?>"><i class="bi bi-pencil"></i></a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .analysis-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .analysis-select {
    min-width: 260px;
    margin-bottom: 0;
  }

  .analysis-stat-grid,
  .analysis-category-grid,
  .analysis-main-grid {
    display: grid;
    gap: 13px;
    margin-bottom: 16px;
  }

  .analysis-stat-grid {
    grid-template-columns: repeat(4, 1fr);
  }

  .analysis-category-grid {
    grid-template-columns: repeat(3, 1fr);
  }

  .analysis-main-grid {
    grid-template-columns: 1fr 1fr;
  }

  .analysis-category-card {
    padding: 16px 18px;
    border-top: 3px solid var(--blue-main);
  }

  .analysis-card-head,
  .analysis-metric-row,
  .analysis-progress-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }

  .analysis-card-head {
    margin-bottom: 13px;
  }

  .analysis-code {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 900;
    line-height: 1;
  }

  .analysis-title,
  .analysis-caption,
  .analysis-foot {
    color: var(--ash);
  }

  .analysis-title {
    font-size: 11.5px;
    margin-top: 3px;
  }

  .analysis-metric {
    font-size: 21px;
    font-weight: 800;
    color: var(--ink);
  }

  .analysis-caption,
  .analysis-foot {
    font-size: 10.8px;
  }

  .analysis-progress-label {
    margin: 10px 0 4px;
    font-size: 10.8px;
    color: var(--ash);
    font-weight: 700;
  }

  .analysis-foot {
    margin-top: 10px;
  }

  .analysis-difficulty-list {
    padding: 14px 18px 16px;
  }

  .analysis-difficulty-group + .analysis-difficulty-group {
    margin-top: 16px;
  }

  .analysis-group-title {
    margin-bottom: 8px;
  }

  .analysis-difficulty-row {
    display: grid;
    grid-template-columns: 72px 1fr 68px 54px;
    align-items: center;
    gap: 9px;
    padding: 7px 0;
    font-size: 12px;
  }

  .analysis-diff-label {
    font-weight: 700;
    color: var(--ink);
  }

  .analysis-diff-bar {
    height: 8px;
    border-radius: 999px;
    background: var(--cloud);
    overflow: hidden;
  }

  .analysis-diff-bar span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: var(--blue-main);
  }

  .analysis-diff-value,
  .analysis-diff-accuracy {
    color: var(--ash);
    font-size: 11px;
    text-align: right;
  }

  .analysis-empty,
  .analysis-empty-cell {
    color: var(--ash);
    font-size: 12.5px;
    text-align: center;
    padding: 22px;
  }

  .analysis-compact-table th,
  .analysis-compact-table td {
    font-size: 12px;
  }

  @media (max-width: 1100px) {
    .analysis-stat-grid,
    .analysis-category-grid,
    .analysis-main-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 780px) {
    .analysis-stat-grid,
    .analysis-category-grid,
    .analysis-main-grid {
      grid-template-columns: 1fr;
    }

    .analysis-toolbar,
    .analysis-select {
      width: 100%;
    }
  }
</style>
