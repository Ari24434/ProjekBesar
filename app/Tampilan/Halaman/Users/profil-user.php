<?php
$userContext = $userContext ?? ['user' => null, 'initial' => 'P', 'first_name' => 'Peserta'];
$user = $user ?? ($userContext['user'] ?? null);
$profileStats = $profileStats ?? ['total_tryout' => 0, 'passed_count' => 0, 'best_score' => 0, 'avg_score' => 0, 'improvement' => 0, 'pass_rate' => 0];
$profileCategoryStats = $profileCategoryStats ?? [];
$profileResults = $profileResults ?? [];
$profileError = $profileError ?? null;

$formatDate = function (?string $value): string {
  if (!$value) {
    return '-';
  }

  $timestamp = strtotime($value);
  return $timestamp ? date('d M Y', $timestamp) : '-';
};

$joinedDate = $formatDate($user['created_at'] ?? null);
$phone = $user['no_hp'] ?? '-';
$improvement = (float) ($profileStats['improvement'] ?? 0);
$improvementLabel = $improvement > 0 ? '+' . number_format($improvement, 0, ',', '.') : number_format($improvement, 0, ',', '.');
$priority = (int) ($profileStats['total_tryout'] ?? 0) > 0 ? 'TWK' : 'Belum ada';
$lowestRatio = null;

if ((int) ($profileStats['total_tryout'] ?? 0) > 0) {
  foreach ($profileCategoryStats as $code => $stat) {
    $ratio = (float) $stat['avg'] / max((float) $stat['min'], 1);

    if ($lowestRatio === null || $ratio < $lowestRatio) {
      $lowestRatio = $ratio;
      $priority = $code;
    }
  }
}

$chartPoints = [];
$maxScore = 500;
$minScore = 0;
$chartCount = count($profileResults);

foreach ($profileResults as $index => $result) {
  $x = $chartCount <= 1 ? 50 : 8 + (($index / max($chartCount - 1, 1)) * 84);
  $y = 92 - (((float) $result['total_nilai'] - $minScore) / ($maxScore - $minScore) * 76);
  $chartPoints[] = [
    'x' => round($x, 2),
    'y' => round($y, 2),
    'score' => (float) $result['total_nilai'],
    'label' => $result['nama_tryout'],
  ];
}

$polylinePoints = implode(' ', array_map(fn($point) => $point['x'] . ',' . $point['y'], $chartPoints));
$areaPoints = $polylinePoints ? '8,92 ' . $polylinePoints . ' 92,92' : '';
?>

<div class="page active" id="pg-profil">
  <div class="page-body">
    <?php if ($profileError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($profileError) ?>
      </div>
    <?php endif; ?>

    <div class="profile-header anim">
      <div class="prof-av"><?= htmlspecialchars($userContext['initial'] ?? 'P') ?></div>
      <div class="prof-info">
        <div class="prof-name"><?= htmlspecialchars($user['nama'] ?? 'Peserta') ?></div>
        <div class="prof-email"><?= htmlspecialchars($user['email'] ?? '-') ?> &nbsp;-&nbsp; <?= htmlspecialchars($phone ?: '-') ?></div>
        <div class="prof-chip"><i class="bi bi-mortarboard-fill" style="font-size:10px;"></i> <?= htmlspecialchars(ucfirst($user['status'] ?? 'aktif')) ?> - Bergabung <?= htmlspecialchars($joinedDate) ?></div>
      </div>
      <div class="profile-actions">
        <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/user/riwayat"><i class="bi bi-clock-history"></i> Riwayat</a>
        <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/user/daftar-tryout"><i class="bi bi-play-circle-fill"></i> Tryout</a>
      </div>
    </div>

    <div class="profile-stat-grid anim anim-d1">
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-journal-check"></i></div></div>
        <div class="stat-number"><?= (int) $profileStats['total_tryout'] ?></div>
        <div class="stat-label">Tryout selesai</div>
      </div>
      <div class="stat-block c-gold">
        <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-trophy-fill"></i></div></div>
        <div class="stat-number"><?= number_format((float) $profileStats['best_score'], 0, ',', '.') ?></div>
        <div class="stat-label">Nilai terbaik</div>
      </div>
      <div class="stat-block c-green">
        <div class="stat-top"><div class="stat-icon green"><i class="bi bi-patch-check-fill"></i></div></div>
        <div class="stat-number"><?= number_format((float) $profileStats['pass_rate'], 1, ',', '.') ?>%</div>
        <div class="stat-label">Kelulusan</div>
      </div>
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-graph-up-arrow"></i></div></div>
        <div class="stat-number"><?= $improvementLabel ?></div>
        <div class="stat-label">Perubahan nilai</div>
      </div>
    </div>

    <div class="profile-analysis-grid anim anim-d2">
      <section class="card profile-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-graph-up-arrow"></i> Tren Nilai</div>
        </div>
        <?php if (!$profileResults): ?>
          <div class="profile-empty">Belum ada hasil tryout untuk dianalisis.</div>
        <?php else: ?>
          <div class="profile-chart-wrap">
            <svg class="profile-line-chart" viewBox="0 0 100 100" preserveAspectRatio="none">
              <g class="profile-chart-grid">
                <line x1="8" y1="16" x2="92" y2="16"></line>
                <line x1="8" y1="35" x2="92" y2="35"></line>
                <line x1="8" y1="54" x2="92" y2="54"></line>
                <line x1="8" y1="73" x2="92" y2="73"></line>
                <line x1="8" y1="92" x2="92" y2="92"></line>
              </g>
              <?php if ($areaPoints): ?>
                <polygon class="profile-chart-area" points="<?= htmlspecialchars($areaPoints) ?>"></polygon>
              <?php endif; ?>
              <?php if ($polylinePoints): ?>
                <polyline class="profile-chart-line" points="<?= htmlspecialchars($polylinePoints) ?>"></polyline>
              <?php endif; ?>
              <?php foreach ($chartPoints as $point): ?>
                <circle class="profile-chart-dot" cx="<?= $point['x'] ?>" cy="<?= $point['y'] ?>" r="1.6"></circle>
              <?php endforeach; ?>
            </svg>
          </div>
          <div class="profile-chart-footer">
            <span>Awal: <?= number_format((float) ($profileStats['first_score'] ?? 0), 0, ',', '.') ?></span>
            <span>Terakhir: <?= number_format((float) ($profileStats['last_score'] ?? 0), 0, ',', '.') ?></span>
            <span>Rata-rata: <?= number_format((float) $profileStats['avg_score'], 1, ',', '.') ?></span>
          </div>
        <?php endif; ?>
      </section>

      <section class="card profile-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-bullseye"></i> Rata-rata Materi</div>
        </div>
        <div class="profile-category-list">
          <?php foreach ($profileCategoryStats as $code => $stat): ?>
            <?php
              $scorePct = min(100, round(((float) $stat['avg'] / max((float) $stat['max'], 1)) * 100));
              $minPassed = (float) $stat['avg'] >= (float) $stat['min'];
            ?>
            <div class="profile-category-row">
              <div class="profile-category-top">
                <span><?= htmlspecialchars($code) ?> <em>min. <?= (int) $stat['min'] ?></em></span>
                <strong style="color:<?= htmlspecialchars($stat['color']) ?>;"><?= number_format((float) $stat['avg'], 1, ',', '.') ?></strong>
              </div>
              <div class="progress-bar"><div class="progress-fill" style="width:<?= $scorePct ?>%;background:<?= htmlspecialchars($stat['color']) ?>;"></div></div>
              <div class="profile-category-note <?= $minPassed ? 'pass' : 'fail' ?>"><?= $minPassed ? 'Sudah melewati ambang batas' : 'Masih perlu diperkuat' ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </div>

    <div class="profile-analysis-grid anim anim-d3">
      <section class="card profile-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-lightbulb-fill"></i> Rekomendasi Belajar</div>
        </div>
        <div class="profile-recommendation">
          <div class="recommendation-badge"><?= htmlspecialchars($priority) ?></div>
          <div>
            <strong>Prioritas latihan berikutnya</strong>
            <span>
              <?= (int) $profileStats['total_tryout'] > 0
                ? 'Fokuskan latihan pada ' . htmlspecialchars($priority) . ' karena rata-ratanya paling dekat dengan ambang batas dibanding materi lain.'
                : 'Ikuti tryout pertama agar sistem bisa membaca pola nilai dan memberi rekomendasi yang lebih tepat.' ?>
            </span>
          </div>
        </div>
        <div class="profile-recommendation muted">
          <div class="recommendation-badge"><i class="bi bi-stopwatch-fill"></i></div>
          <div>
            <strong>Jaga konsistensi waktu</strong>
            <span>Kerjakan simulasi dengan durasi asli agar skor stabil saat tryout berikutnya.</span>
          </div>
        </div>
      </section>

      <section class="card profile-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-clock-history"></i> Hasil Terbaru</div>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table profile-table">
            <thead>
              <tr><th>Tryout</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
              <?php if (!$profileResults): ?>
                <tr><td colspan="4" style="text-align:center;color:var(--ash);padding:20px;">Belum ada hasil.</td></tr>
              <?php endif; ?>
              <?php foreach (array_reverse(array_slice($profileResults, -5)) as $result): ?>
                <?php $isPassed = (int) $result['lulus_total'] === 1; ?>
                <tr>
                  <td style="font-weight:700;min-width:180px;"><?= htmlspecialchars($result['nama_tryout']) ?></td>
                  <td style="font-weight:800;color:<?= $isPassed ? 'var(--blue-main)' : 'var(--crimson)' ?>;"><?= number_format((float) $result['total_nilai'], 0, ',', '.') ?></td>
                  <td><span class="badge <?= $isPassed ? 'badge-pass' : 'badge-fail' ?>"><?= $isPassed ? 'Lulus' : 'Belum' ?></span></td>
                  <td style="color:var(--ash);white-space:nowrap;"><?= htmlspecialchars($formatDate($result['tanggal_mulai'])) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</div>

<style>
  .profile-header {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }

  .profile-actions {
    margin-left: auto;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .profile-stat-grid,
  .profile-analysis-grid {
    display: grid;
    gap: 14px;
    margin-top: 16px;
  }

  .profile-stat-grid {
    grid-template-columns: repeat(4, 1fr);
  }

  .profile-analysis-grid {
    grid-template-columns: 1fr 1fr;
  }

  .profile-card {
    min-width: 0;
  }

  .profile-empty {
    padding: 28px;
    color: var(--ash);
    font-size: 12.5px;
    text-align: center;
  }

  .profile-chart-wrap {
    height: 260px;
    padding: 18px;
  }

  .profile-line-chart {
    width: 100%;
    height: 100%;
  }

  .profile-chart-grid line {
    stroke: #E2E8F5;
    stroke-width: .45;
  }

  .profile-chart-area {
    fill: rgba(45, 114, 217, .13);
  }

  .profile-chart-line {
    fill: none;
    stroke: #1E54B7;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .profile-chart-dot {
    fill: #1E54B7;
    stroke: #fff;
    stroke-width: .8;
  }

  .profile-chart-footer {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
    padding: 0 18px 16px;
    color: var(--ash);
    font-size: 11.5px;
    font-weight: 700;
  }

  .profile-category-list {
    padding: 18px;
    display: grid;
    gap: 16px;
  }

  .profile-category-top {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 800;
    color: var(--ink);
  }

  .profile-category-top em {
    color: var(--ash);
    font-style: normal;
    font-weight: 500;
  }

  .profile-category-note {
    margin-top: 5px;
    font-size: 10.8px;
    font-weight: 700;
  }

  .profile-category-note.pass {
    color: var(--emerald);
  }

  .profile-category-note.fail {
    color: var(--crimson);
  }

  .profile-recommendation {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px 18px;
    border-bottom: 1px solid var(--smoke);
  }

  .profile-recommendation:last-child {
    border-bottom: 0;
  }

  .profile-recommendation strong,
  .profile-recommendation span {
    display: block;
  }

  .profile-recommendation strong {
    color: var(--ink);
    font-size: 13px;
    margin-bottom: 3px;
  }

  .profile-recommendation span {
    color: var(--ash);
    font-size: 12px;
    line-height: 1.55;
  }

  .recommendation-badge {
    min-width: 42px;
    height: 42px;
    border-radius: 10px;
    background: var(--frost);
    color: var(--blue-main);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
  }

  .profile-recommendation.muted .recommendation-badge {
    background: #ECFDF5;
    color: var(--emerald);
  }

  .profile-table th,
  .profile-table td {
    font-size: 12px;
  }

  @media (max-width: 1020px) {
    .profile-stat-grid,
    .profile-analysis-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

  @media (max-width: 760px) {
    .profile-stat-grid,
    .profile-analysis-grid {
      grid-template-columns: 1fr;
    }

    .profile-actions {
      width: 100%;
      margin-left: 0;
    }

    .profile-actions .btn {
      flex: 1;
      justify-content: center;
    }
  }
</style>
