<?php
$userContext = $userContext ?? ['user' => null, 'first_name' => 'Peserta', 'initial' => 'P'];
$user = $userContext['user'] ?? null;
$userStats = $userStats ?? ['total_tryout' => 0, 'best_score' => 0, 'avg_score' => 0, 'passed_count' => 0, 'score_delta' => 0];
$latestResult = $latestResult ?? null;
$availableTryouts = $availableTryouts ?? [];
$recentResults = $recentResults ?? [];
$userDashboardError = $userDashboardError ?? null;
$tryoutSettings = app_tryout_settings();

$formatDate = function (?string $value, bool $withTime = false): string {
  if (!$value) {
    return '-';
  }

  $timestamp = strtotime($value);

  return $timestamp ? date($withTime ? 'd M Y H:i' : 'd M Y', $timestamp) : '-';
};

$scorePct = $latestResult ? min(100, round(((float) $latestResult['total_nilai'] / 550) * 100)) : 0;
$delta = (float) ($userStats['score_delta'] ?? 0);
$deltaLabel = $delta > 0 ? '+' . number_format($delta, 0, ',', '.') : number_format($delta, 0, ',', '.');
$passingTotal = (int) $tryoutSettings['passing_twk'] + (int) $tryoutSettings['passing_tiu'] + (int) $tryoutSettings['passing_tkp'];
?>

<div class="page active" id="pg-dashboard">
  <div class="page-body">
    <div class="welcome-banner anim">
      <div class="wb-grid"></div>
      <div class="wb-glow"></div>
      <div class="wb-content">
        <div>
          <div class="wb-greeting"><?= htmlspecialchars(date('l, d M Y')) ?></div>
          <div class="wb-name">Halo, <?= htmlspecialchars($userContext['first_name'] ?? 'Peserta') ?></div>
          <div class="wb-sub">
            Kamu sudah menyelesaikan <strong><?= (int) $userStats['total_tryout'] ?> tryout</strong>.
            <?= (int) $userStats['total_tryout'] > 0 ? 'Terus jaga ritmenya.' : 'Mulai dari tryout aktif yang tersedia.' ?>
          </div>
          <div class="wb-pills">
            <div class="wb-pill"><i class="bi bi-patch-check-fill" style="color:#6EE7B7;font-size:10px;"></i> <?= (int) $userStats['passed_count'] ?> sesi lulus</div>
            <div class="wb-pill"><i class="bi bi-trophy-fill" style="color:#FCD34D;font-size:10px;"></i> Nilai terbaik: <?= number_format((float) $userStats['best_score'], 0, ',', '.') ?></div>
            <div class="wb-pill"><i class="bi bi-graph-up" style="color:#93C5FD;font-size:10px;"></i> <?= $deltaLabel ?> dari sesi sebelumnya</div>
          </div>
        </div>
        <a href="<?= BASE_URL ?>/user/daftar-tryout" class="btn btn-lg user-hero-btn">
          <i class="bi bi-play-circle-fill" style="color:#93C5FD;"></i> Ikuti Tryout
        </a>
      </div>
    </div>

    <?php if ($userDashboardError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($userDashboardError) ?>
      </div>
    <?php endif; ?>

    <div class="stat-grid anim anim-d1" style="margin-bottom:18px;">
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-journal-check"></i></div><div class="stat-change neutral">selesai</div></div>
        <div class="stat-number"><?= (int) $userStats['total_tryout'] ?></div><div class="stat-label">Tryout Diikuti</div>
      </div>
      <div class="stat-block c-gold">
        <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-trophy-fill"></i></div><div class="stat-change <?= (float) $userStats['best_score'] >= $passingTotal ? 'up' : 'neutral' ?>">terbaik</div></div>
        <div class="stat-number"><?= number_format((float) $userStats['best_score'], 0, ',', '.') ?></div><div class="stat-label">Nilai Terbaik</div>
      </div>
      <div class="stat-block c-blue">
        <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-graph-up"></i></div><div class="stat-change <?= $delta >= 0 ? 'up' : 'down' ?>"><i class="bi <?= $delta >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' ?>"></i><?= $deltaLabel ?></div></div>
        <div class="stat-number"><?= number_format((float) $userStats['avg_score'], 1, ',', '.') ?></div><div class="stat-label">Rata-rata Nilai</div>
      </div>
      <div class="stat-block c-green">
        <div class="stat-top"><div class="stat-icon green"><i class="bi bi-patch-check-fill"></i></div><div class="stat-change neutral">dari <?= (int) $userStats['total_tryout'] ?> sesi</div></div>
        <div class="stat-number"><?= (int) $userStats['passed_count'] ?></div><div class="stat-label">Sesi Lulus</div>
      </div>
    </div>

    <div class="user-dashboard-grid anim anim-d2">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-speedometer2"></i> Skor Terakhir</div>
          <?php if ($latestResult): ?>
            <span class="badge <?= (int) $latestResult['lulus_total'] === 1 ? 'badge-pass' : 'badge-fail' ?>"><?= (int) $latestResult['lulus_total'] === 1 ? 'Lulus' : 'Belum' ?></span>
          <?php endif; ?>
        </div>
        <div style="padding:18px;">
          <?php if (!$latestResult): ?>
            <div class="user-empty-state">Belum ada hasil tryout. Ikuti tryout aktif untuk melihat ringkasan nilai.</div>
          <?php else: ?>
            <div class="user-score-wrap">
              <div class="user-score-ring">
                <svg width="86" height="86" viewBox="0 0 86 86">
                  <circle cx="43" cy="43" r="34" fill="none" stroke="var(--smoke)" stroke-width="7"/>
                  <circle cx="43" cy="43" r="34" fill="none" stroke="var(--blue-main)" stroke-width="7" stroke-linecap="round" stroke-dasharray="<?= round(($scorePct / 100) * 214) ?> 214" stroke-dashoffset="0"/>
                </svg>
                <div class="user-score-center">
                  <div><?= number_format((float) $latestResult['total_nilai'], 0, ',', '.') ?></div>
                  <span>/ 500</span>
                </div>
              </div>
              <div>
                <div class="user-score-title <?= (int) $latestResult['lulus_total'] === 1 ? 'pass' : 'fail' ?>">
                  <?= (int) $latestResult['lulus_total'] === 1 ? 'Di Atas Passing Grade' : 'Belum Lulus Passing Grade' ?>
                </div>
                <div class="user-score-meta">
                  <?= htmlspecialchars($latestResult['nama_tryout']) ?><br>
                  Passing grade: <strong>311</strong> poin<br>
                  Selisih: <strong><?= number_format((float) $latestResult['total_nilai'] - $passingTotal, 0, ',', '.') ?></strong> poin
                </div>
              </div>
            </div>

            <?php foreach ([
              'TWK' => ['value' => (float) $latestResult['nilai_twk'], 'min' => 65, 'max' => 150, 'color' => 'var(--blue-main)'],
              'TIU' => ['value' => (float) $latestResult['nilai_tiu'], 'min' => 80, 'max' => 175, 'color' => 'var(--emerald)'],
              'TKP' => ['value' => (float) $latestResult['nilai_tkp'], 'min' => 166, 'max' => 225, 'color' => 'var(--gold)'],
            ] as $label => $item): ?>
              <?php $pct = min(100, round(($item['value'] / $item['max']) * 100)); ?>
              <div class="user-score-bar">
                <div><span><?= $label ?> <em>min.<?= $item['min'] ?></em></span><strong style="color:<?= $item['color'] ?>;"><?= number_format($item['value'], 0, ',', '.') ?></strong></div>
                <div class="progress-bar"><div class="progress-fill" style="width:<?= $pct ?>%;background:<?= $item['color'] ?>;"></div></div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-calendar3"></i> Tryout Tersedia</div>
          <a class="card-action" href="<?= BASE_URL ?>/user/daftar-tryout" style="text-decoration:none;">Semua &rarr;</a>
        </div>
        <?php if (!$availableTryouts): ?>
          <div class="user-empty-state">Belum ada tryout aktif atau selesai yang tersedia.</div>
        <?php endif; ?>

        <?php foreach ($availableTryouts as $tryout): ?>
          <?php
            $hasResult = !empty($tryout['id_hasil']);
            $isActive = $tryout['status'] === 'aktif';
            $totalSoal = (int) ($tryout['total_soal'] ?? 0);
          ?>
          <div class="tryout-row">
            <div class="tr-icon" style="background:<?= $hasResult ? '#ECFDF5' : 'var(--frost)' ?>;color:<?= $hasResult ? 'var(--emerald)' : 'var(--blue-main)' ?>;"><i class="bi <?= $hasResult ? 'bi-journal-check' : 'bi-journal-text' ?>"></i></div>
            <div>
              <div class="tr-title">
                <?= htmlspecialchars($tryout['nama_tryout']) ?>
                <?php if ($isActive && !$hasResult): ?><span class="badge badge-new" style="font-size:10px;">Baru</span><?php endif; ?>
              </div>
              <div class="tr-meta"><i class="bi bi-clock" style="font-size:10px;"></i> <?= (int) $tryout['waktu'] ?> menit - <?= $totalSoal ?> soal - <?= htmlspecialchars($formatDate($tryout['tanggal_mulai'])) ?></div>
            </div>
            <div class="tr-action">
              <?php if ($hasResult): ?>
                <a class="btn btn-ghost btn-sm" href="<?= BASE_URL ?>/user/hasil-tryout?id=<?= (int) $tryout['id_hasil'] ?>"><i class="bi bi-eye"></i> Hasil</a>
              <?php elseif ($isActive): ?>
                <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/user/soal-tryout?id=<?= (int) $tryout['id_tryout'] ?>"><i class="bi bi-play-fill"></i> Mulai</a>
              <?php else: ?>
                <span class="badge badge-done">Selesai</span>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card anim anim-d3" style="margin-top:15px;">
      <div class="card-head">
        <div class="card-title"><i class="bi bi-clock-history"></i> Riwayat Terakhir</div>
        <a class="card-action" href="<?= BASE_URL ?>/user/riwayat" style="text-decoration:none;">Lihat semua &rarr;</a>
      </div>
      <div style="overflow-x:auto;">
        <table class="data-table">
          <thead><tr><th>Tryout</th><th>TWK</th><th>TIU</th><th>TKP</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
          <tbody>
            <?php if (!$recentResults): ?>
              <tr><td colspan="7" style="text-align:center;color:var(--ash);padding:22px;">Belum ada riwayat nilai.</td></tr>
            <?php endif; ?>
            <?php foreach ($recentResults as $result): ?>
              <?php $isPassed = (int) $result['lulus_total'] === 1; ?>
              <tr>
                <td style="font-weight:600;"><?= htmlspecialchars($result['nama_tryout']) ?></td>
                <td><?= number_format((float) $result['nilai_twk'], 0, ',', '.') ?></td>
                <td><?= number_format((float) $result['nilai_tiu'], 0, ',', '.') ?></td>
                <td><?= number_format((float) $result['nilai_tkp'], 0, ',', '.') ?></td>
                <td style="font-weight:700;color:<?= $isPassed ? 'var(--blue-main)' : 'var(--crimson)' ?>;"><?= number_format((float) $result['total_nilai'], 0, ',', '.') ?></td>
                <td><span class="badge <?= $isPassed ? 'badge-pass' : 'badge-fail' ?>"><?= $isPassed ? 'Lulus' : 'Belum' ?></span></td>
                <td style="color:var(--ash);"><?= htmlspecialchars($formatDate($result['tanggal_mulai'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .user-hero-btn {
    background: rgba(255,255,255,.11);
    border: 1.5px solid rgba(255,255,255,.2);
    color: #fff;
    flex-shrink: 0;
  }

  .user-dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
  }

  .user-empty-state {
    padding: 24px;
    color: var(--ash);
    font-size: 12.5px;
    text-align: center;
  }

  .user-score-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
  }

  .user-score-ring {
    position: relative;
    width: 86px;
    height: 86px;
    flex-shrink: 0;
  }

  .user-score-ring svg {
    transform: rotate(-90deg);
  }

  .user-score-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .user-score-center div {
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--ink);
    line-height: 1;
  }

  .user-score-center span,
  .user-score-meta,
  .user-score-bar em {
    color: var(--ash);
  }

  .user-score-center span {
    font-size: 9px;
    margin-top: 1px;
  }

  .user-score-title {
    font-size: 12.5px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .user-score-title.pass {
    color: var(--emerald);
  }

  .user-score-title.fail {
    color: var(--crimson);
  }

  .user-score-meta {
    font-size: 11.5px;
    line-height: 1.65;
  }

  .user-score-bar {
    margin-top: 11px;
  }

  .user-score-bar > div:first-child {
    display: flex;
    justify-content: space-between;
    font-size: 11.5px;
    margin-bottom: 4px;
  }

  .user-score-bar span {
    font-weight: 700;
    color: var(--slate);
  }

  .user-score-bar em {
    font-style: normal;
    font-weight: 400;
  }

  @media (max-width: 960px) {
    .user-dashboard-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .wb-content,
    .user-score-wrap {
      align-items: flex-start;
      flex-direction: column;
    }

    .user-hero-btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>
