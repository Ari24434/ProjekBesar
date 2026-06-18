<?php
$result = $result ?? null;
$detailRows = $detailRows ?? [];
$detailOptions = $detailOptions ?? [];
$resultError = $resultError ?? null;
$totalDetail = count($detailRows);
$benarCount = 0;
$salahCount = 0;
$tryoutSettings = app_tryout_settings();
$maxScores = [
  'TWK' => max(1, (int) ($result['jml_soal_twk'] ?? $tryoutSettings['soal_twk']) * 5),
  'TIU' => max(1, (int) ($result['jml_soal_tiu'] ?? $tryoutSettings['soal_tiu']) * 5),
  'TKP' => max(1, (int) ($result['jml_soal_tkp'] ?? $tryoutSettings['soal_tkp']) * 5),
];
$maxTotalScore = array_sum($maxScores);

foreach ($detailRows as $row) {
  if ((int) ($row['is_benar'] ?? 0) === 1) {
    $benarCount++;
  } elseif (in_array($row['kategori'] ?? '', ['TWK', 'TIU'], true)) {
    $salahCount++;
  }
}

$categoryCards = [
  'TWK' => ['score' => (float) ($result['nilai_twk'] ?? 0), 'min' => (int) $tryoutSettings['passing_twk'], 'max' => $maxScores['TWK'], 'passed' => (int) ($result['lulus_twk'] ?? 0), 'color' => 'var(--blue-light)'],
  'TIU' => ['score' => (float) ($result['nilai_tiu'] ?? 0), 'min' => (int) $tryoutSettings['passing_tiu'], 'max' => $maxScores['TIU'], 'passed' => (int) ($result['lulus_tiu'] ?? 0), 'color' => '#6EE7B7'],
  'TKP' => ['score' => (float) ($result['nilai_tkp'] ?? 0), 'min' => (int) $tryoutSettings['passing_tkp'], 'max' => $maxScores['TKP'], 'passed' => (int) ($result['lulus_tkp'] ?? 0), 'color' => '#FCD34D'],
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
            <div class="res-score-sub">dari <?= number_format($maxTotalScore, 0) ?> poin</div>
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
              <thead><tr><th>No</th><th>Kategori</th><th>Jawaban</th><th>Kunci/Skor</th><th>Hasil</th><th>Detail</th></tr></thead>
              <tbody>
                <?php if (!$detailRows): ?>
                  <tr><td colspan="6" style="text-align:center;color:var(--ash);padding:18px;">Detail jawaban belum tersedia.</td></tr>
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
                    <td>
                      <button class="btn btn-ghost btn-sm btn-icon" type="button" data-bs-toggle="modal" data-bs-target="#detailSoalModal<?= (int) $i ?>" title="Lihat detail soal">
                        <i class="bi bi-search"></i>
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <?php foreach ($detailRows as $i => $row): ?>
            <?php
              $cat = $row['kategori'];
              $isTkp = $cat === 'TKP';
              $isCorrect = (int) ($row['is_benar'] ?? 0) === 1;
              $options = $detailOptions[(int) $row['id_soal']] ?? [];
              $modalId = 'detailSoalModal' . (int) $i;
              $statusLabel = $isTkp
                ? number_format((float) $row['poin_didapat'], 0) . ' poin'
                : ($isCorrect ? 'Benar' : 'Salah');
              $statusClass = $isTkp ? 'badge-new' : ($isCorrect ? 'badge-pass' : 'badge-fail');
            ?>
            <div class="modal fade result-detail-modal" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <div>
                      <div class="result-detail-kicker">
                        <?= htmlspecialchars($cat) ?>
                        <?php if (!empty($row['subtopik'])): ?>
                          <span>&middot;</span> <?= htmlspecialchars($row['subtopik']) ?>
                        <?php endif; ?>
                      </div>
                      <h5 class="modal-title" id="<?= $modalId ?>Label">Detail Soal <?= $i + 1 ?></h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <div class="modal-body">
                    <div class="result-detail-summary">
                      <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($statusLabel) ?></span>
                      <span>Jawaban kamu: <strong><?= htmlspecialchars($row['jawaban_peserta'] ?: '-') ?></strong></span>
                      <?php if ($isTkp): ?>
                        <span>Skor: <strong><?= number_format((float) $row['poin_didapat'], 0) ?> poin</strong></span>
                      <?php else: ?>
                        <span>Kunci: <strong><?= htmlspecialchars($row['kode_kunci'] ?: '-') ?></strong></span>
                      <?php endif; ?>
                    </div>

                    <div class="result-detail-block">
                      <div class="result-detail-label">Soal</div>
                      <div class="result-detail-question"><?= htmlspecialchars($row['pertanyaan'] ?? '') ?></div>
                      <?php if (!empty($row['gambar'])): ?>
                        <img class="result-detail-image" src="<?= BASE_URL . '/' . htmlspecialchars(ltrim($row['gambar'], '/')) ?>" alt="Gambar soal">
                      <?php endif; ?>
                    </div>

                    <div class="result-detail-block">
                      <div class="result-detail-label">Pilihan Jawaban</div>
                      <div class="result-option-list">
                        <?php foreach ($options as $option): ?>
                          <?php
                            $isChosen = (int) ($row['id_opsi_dipilih'] ?? 0) === (int) $option['id_opsi'];
                            $isKey = !$isTkp && (int) $option['is_kunci'] === 1;
                          ?>
                          <div class="result-option <?= $isChosen ? 'chosen' : '' ?> <?= $isKey ? 'key' : '' ?>">
                            <div class="result-option-code"><?= htmlspecialchars($option['kode_opsi']) ?></div>
                            <div class="result-option-body">
                              <div class="result-option-text"><?= htmlspecialchars($option['teks_opsi'] ?? '') ?></div>
                              <?php if (!empty($option['gambar_opsi'])): ?>
                                <img class="result-option-image" src="<?= BASE_URL . '/' . htmlspecialchars(ltrim($option['gambar_opsi'], '/')) ?>" alt="Gambar opsi <?= htmlspecialchars($option['kode_opsi']) ?>">
                              <?php endif; ?>
                              <div class="result-option-meta">
                                <?php if ($isChosen): ?><span>Jawaban kamu</span><?php endif; ?>
                                <?php if ($isKey): ?><span>Kunci benar</span><?php endif; ?>
                                <?php if ($isTkp): ?><span><?= number_format((float) $option['poin'], 0) ?> poin</span><?php endif; ?>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>

                    <div class="result-detail-block">
                      <div class="result-detail-label">Pembahasan</div>
                      <?php if (!empty($row['pembahasan'])): ?>
                        <div class="result-explanation"><?= htmlspecialchars($row['pembahasan']) ?></div>
                      <?php else: ?>
                        <div class="result-explanation empty">Pembahasan untuk soal ini belum tersedia.</div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                      <i class="bi bi-x-lg"></i> Tutup
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
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
<<<<<<< HEAD
=======

<style>
  .result-detail-modal {
    z-index: 1065;
  }

  .result-detail-modal .modal-dialog {
    width: min(880px, calc(100vw - 32px));
    max-width: none;
    margin-left: auto;
    margin-right: auto;
  }

  .result-detail-modal .modal-content {
    border: 0;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 24px 70px rgba(8, 21, 58, .28);
  }

  .result-detail-modal .modal-header {
    background: #fff;
    border-bottom: 1px solid var(--smoke);
    padding: 18px 22px;
  }

  .result-detail-modal .modal-body {
    padding: 20px 22px 22px;
    background: #fff;
  }

  .result-detail-modal .modal-footer {
    background: #fff;
    border-top: 1px solid var(--smoke);
    padding: 13px 22px;
  }

  .result-detail-kicker {
    font-size: 10.5px;
    color: var(--blue-main);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 3px;
  }

  .result-detail-summary {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding: 10px 12px;
    background: var(--cloud);
    border: 1px solid var(--smoke);
    border-radius: 8px;
    font-size: 12px;
    color: var(--ash);
    margin-bottom: 14px;
  }

  .result-detail-block + .result-detail-block {
    margin-top: 16px;
  }

  .result-detail-label {
    font-size: 10px;
    font-weight: 800;
    color: var(--ash);
    text-transform: uppercase;
    letter-spacing: .7px;
    margin-bottom: 7px;
  }

  .result-detail-question,
  .result-explanation {
    white-space: pre-line;
    font-size: 13.5px;
    line-height: 1.75;
    color: var(--ink);
  }

  .result-explanation {
    padding: 12px 13px;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 8px;
  }

  .result-explanation.empty {
    color: var(--ash);
    background: var(--cloud);
    border-color: var(--smoke);
  }

  .result-detail-image,
  .result-option-image {
    display: block;
    width: auto;
    max-width: 100%;
    height: auto;
    object-fit: contain;
    border: 1px solid var(--smoke);
    border-radius: 8px;
    margin-top: 10px;
  }

  .result-detail-image {
    max-height: 360px;
  }

  .result-option-list {
    display: grid;
    gap: 9px;
  }

  .result-option {
    display: grid;
    grid-template-columns: 34px minmax(0, 1fr);
    gap: 10px;
    padding: 10px 11px;
    border: 1.5px solid var(--smoke);
    border-radius: 8px;
    background: #fff;
  }

  .result-option.chosen {
    border-color: var(--blue-main);
    background: var(--frost);
  }

  .result-option.key {
    border-color: rgba(16, 185, 129, .5);
  }

  .result-option-code {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--cloud);
    color: var(--blue-main);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 800;
  }

  .result-option-text {
    white-space: pre-line;
    font-size: 13px;
    line-height: 1.55;
    color: var(--ink);
  }

  .result-option-image {
    max-height: 180px;
  }

  .result-option-meta {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 7px;
  }

  .result-option-meta span {
    font-size: 10px;
    font-weight: 700;
    color: var(--blue-main);
    background: #EAF2FF;
    border-radius: 999px;
    padding: 2px 7px;
  }

  @media (max-width: 640px) {
    .result-detail-modal .modal-dialog {
      width: calc(100vw - 18px);
      margin-top: 9px;
      margin-bottom: 9px;
    }

    .result-detail-modal .modal-header,
    .result-detail-modal .modal-body,
    .result-detail-modal .modal-footer {
      padding-left: 15px;
      padding-right: 15px;
    }

    .result-detail-modal .modal-footer .btn {
      width: 100%;
      justify-content: center;
    }

    .result-detail-summary {
      align-items: flex-start;
      flex-direction: column;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.result-detail-modal').forEach(function (modal) {
      document.body.appendChild(modal);
    });
  });
</script>
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
