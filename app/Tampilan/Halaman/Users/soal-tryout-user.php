<?php
$exam = $exam ?? null;
$examQuestions = $examQuestions ?? [];
$examError = $examError ?? null;
$totalQuestions = count($examQuestions);
$categoryTotals = ['TWK' => 0, 'TIU' => 0, 'TKP' => 0];

foreach ($examQuestions as $question) {
  if (isset($categoryTotals[$question['kategori']])) {
    $categoryTotals[$question['kategori']]++;
  }
}

$startedAt = $exam ? strtotime($exam['waktu_mulai']) : time();
$durationSeconds = max(1, (int) ($exam['waktu'] ?? 100)) * 60;
$remainingSeconds = max(0, ($startedAt + $durationSeconds) - time());
?>

<div class="page active" id="pg-exam">
  <div class="page-body" style="max-width:1000px;">
    <?php if ($examError): ?>
      <div class="card anim" style="padding:12px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
        <?= htmlspecialchars($examError) ?>
      </div>
    <?php endif; ?>

    <?php if (!$exam || !$examQuestions): ?>
      <div class="card anim" style="padding:22px;text-align:center;">
        <div style="font-weight:800;color:var(--ink);margin-bottom:4px;">Soal belum tersedia</div>
        <div style="font-size:12px;color:var(--ash);margin-bottom:12px;">Sesi ini belum dapat dikerjakan karena data soal kosong.</div>
        <a href="<?= BASE_URL ?>/user/daftar-tryout" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>
    <?php else: ?>
      <form id="examForm" method="post" action="<?= BASE_URL ?>/user/tryout/submit">
        <?= csrf_field() ?>
        <input type="hidden" name="id_hasil" value="<?= (int) $exam['id_hasil'] ?>">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;" class="anim">
          <div>
            <div style="font-family:'Playfair Display',serif;font-size:17px;color:var(--ink);"><?= htmlspecialchars($exam['nama_tryout']) ?></div>
            <div style="font-size:12px;color:var(--ash);margin-top:2px;">Sedang mengerjakan - <span id="exam-cat-label" style="color:var(--blue-main);font-weight:600;">TWK</span></div>
          </div>
          <button class="btn btn-danger btn-sm" type="button" onclick="submitExam()"><i class="bi bi-send-check"></i> Kumpulkan</button>
        </div>

        <div id="answerInputs"></div>

        <div class="exam-wrap anim anim-d1">
          <div class="exam-card">
            <div class="exam-header">
              <span class="exam-cat-chip" id="ex-cat-chip">TWK</span>
              <span class="exam-qnum">Soal <strong id="ex-qnum">1</strong> dari <strong id="ex-qtotal"><?= $totalQuestions ?></strong></span>
            </div>
            <div class="exam-body">
              <div class="exam-question" id="ex-qtext"></div>
              <div id="ex-qimage" style="margin:12px 0;display:none;"></div>
              <div class="exam-options" id="ex-options"></div>
            </div>
            <div class="exam-footer">
              <button class="btn btn-ghost btn-sm" type="button" id="ex-prev" onclick="prevQ()" disabled><i class="bi bi-arrow-left"></i> Sebelumnya</button>
              <span class="answered-count" id="ex-answered">0 / <?= $totalQuestions ?> dijawab</span>
              <button class="btn btn-primary btn-sm" type="button" id="ex-next" onclick="nextQ()">Selanjutnya <i class="bi bi-arrow-right"></i></button>
            </div>
          </div>

          <div class="exam-panel">
            <div class="panel-timer">
              <div class="timer-num" id="ex-timer">00:00</div>
              <div class="timer-sub">Sisa waktu</div>
            </div>
            <div class="panel-section">
              <div class="panel-label">Navigasi Soal</div>
              <div class="panel-cats">
                <?php $offset = 0; ?>
                <?php foreach ($categoryTotals as $cat => $count): ?>
                  <?php if ($count > 0): ?>
                    <button class="cat-tab <?= $offset === 0 ? 'active' : '' ?>" type="button" id="cat-<?= $offset ?>" onclick="jumpCat(<?= $offset ?>)"><?= $cat ?></button>
                    <?php $offset += $count; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
              <div class="q-nav-grid" id="ex-qgrid"></div>
            </div>
            <div class="panel-stats">
              <div class="pstat-row"><span class="pstat-key">TWK</span><span class="pstat-val" id="ps-twk">0 / <?= $categoryTotals['TWK'] ?></span></div>
              <div class="pstat-row"><span class="pstat-key">TIU</span><span class="pstat-val" id="ps-tiu">0 / <?= $categoryTotals['TIU'] ?></span></div>
              <div class="pstat-row"><span class="pstat-key">TKP</span><span class="pstat-val" id="ps-tkp">0 / <?= $categoryTotals['TKP'] ?></span></div>
            </div>
            <div style="padding:9px 12px 12px;">
              <div style="display:flex;gap:7px;font-size:10.5px;color:var(--ash);">
                <div style="display:flex;align-items:center;gap:4px;"><div style="width:9px;height:9px;border-radius:2px;background:var(--blue-main);"></div>Dijawab</div>
                <div style="display:flex;align-items:center;gap:4px;"><div style="width:9px;height:9px;border-radius:2px;border:1.5px solid var(--smoke);"></div>Kosong</div>
              </div>
            </div>
          </div>
        </div>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php if ($exam && $examQuestions): ?>
<script>
  const QS = <?= json_encode($examQuestions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
  const TOTAL = QS.length;
  const CAT_TOTALS = <?= json_encode($categoryTotals) ?>;
  const CAT_LABELS = {
    TWK: 'Tes Wawasan Kebangsaan (TWK)',
    TIU: 'Tes Intelegensia Umum (TIU)',
    TKP: 'Tes Karakteristik Pribadi (TKP)'
  };
  let curQ = 0;
  let answers = {};
  let timerSec = <?= (int) $remainingSeconds ?>;
  let timerInt = null;

  function renderTimer() {
    const m = String(Math.floor(timerSec / 60)).padStart(2, '0');
    const s = String(timerSec % 60).padStart(2, '0');
    const el = document.getElementById('ex-timer');
    el.textContent = `${m}:${s}`;
    el.className = 'timer-num' + (timerSec < 600 ? ' warn' : '');
  }

  function startTimer() {
    renderTimer();
    timerInt = setInterval(function () {
      timerSec--;
      renderTimer();

      if (timerSec <= 0) {
        clearInterval(timerInt);
        document.getElementById('examForm').submit();
      }
    }, 1000);
  }

  function renderQ(i) {
    curQ = i;
    const q = QS[i];
    const cat = q.kategori;
    document.getElementById('ex-cat-chip').textContent = cat;
    document.getElementById('ex-qnum').textContent = i + 1;
    document.getElementById('ex-qtotal').textContent = CAT_TOTALS[cat] || TOTAL;
    document.getElementById('exam-cat-label').textContent = CAT_LABELS[cat] || cat;
    document.getElementById('ex-qtext').textContent = q.pertanyaan;

    const imgWrap = document.getElementById('ex-qimage');
    imgWrap.innerHTML = '';
    imgWrap.style.display = 'none';

    if (q.gambar) {
      imgWrap.style.display = 'block';
      imgWrap.innerHTML = `<img src="<?= BASE_URL ?>/${q.gambar}" alt="Gambar soal" style="max-width:100%;border-radius:8px;border:1px solid var(--smoke);">`;
    }

    const ol = document.getElementById('ex-options');
    ol.innerHTML = '';

    q.opsi.forEach(function (opt) {
      const selected = Number(answers[q.id_soal] || 0) === Number(opt.id_opsi);
      const d = document.createElement('div');
      d.className = 'exam-opt' + (selected ? ' selected' : '');
      d.innerHTML = `<div class="opt-label">${escapeHtml(opt.kode_opsi || '')}</div><div class="opt-text">${escapeHtml(opt.teks_opsi || '')}</div>`;
      d.onclick = function () {
        answers[q.id_soal] = opt.id_opsi;
        syncAnswerInputs();
        buildGrid();
        renderQ(i);
      };
      ol.appendChild(d);
    });

    document.getElementById('ex-prev').disabled = i === 0;
    const nx = document.getElementById('ex-next');

    if (i === TOTAL - 1) {
      nx.innerHTML = '<i class="bi bi-send-check"></i> Kumpulkan';
      nx.onclick = submitExam;
    } else {
      nx.innerHTML = 'Selanjutnya <i class="bi bi-arrow-right"></i>';
      nx.onclick = nextQ;
    }

    document.querySelectorAll('.cat-tab').forEach(function (tab) {
      tab.classList.remove('active');
    });

    const firstIndex = QS.findIndex(function (item) {
      return item.kategori === cat;
    });
    const catTab = document.getElementById('cat-' + firstIndex);

    if (catTab) {
      catTab.classList.add('active');
    }

    updateCounts();
  }

  function buildGrid() {
    const g = document.getElementById('ex-qgrid');
    g.innerHTML = '';

    for (let i = 0; i < TOTAL; i++) {
      const d = document.createElement('div');
      d.className = 'qn' + (answers[QS[i].id_soal] ? ' done' : '') + (i === curQ ? ' cur' : '');
      d.textContent = i + 1;
      d.onclick = function () {
        renderQ(i);
      };
      g.appendChild(d);
    }
  }

  function updateCounts() {
    const counts = {TWK: 0, TIU: 0, TKP: 0};

    QS.forEach(function (q) {
      if (answers[q.id_soal]) {
        counts[q.kategori] = (counts[q.kategori] || 0) + 1;
      }
    });

    document.getElementById('ps-twk').textContent = `${counts.TWK || 0} / ${CAT_TOTALS.TWK || 0}`;
    document.getElementById('ps-tiu').textContent = `${counts.TIU || 0} / ${CAT_TOTALS.TIU || 0}`;
    document.getElementById('ps-tkp').textContent = `${counts.TKP || 0} / ${CAT_TOTALS.TKP || 0}`;
    document.getElementById('ex-answered').textContent = `${Object.keys(answers).length} / ${TOTAL} dijawab`;
  }

  function syncAnswerInputs() {
    const wrap = document.getElementById('answerInputs');
    wrap.innerHTML = '';

    Object.keys(answers).forEach(function (idSoal) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = `jawaban[${idSoal}]`;
      input.value = answers[idSoal];
      wrap.appendChild(input);
    });
  }

  function submitExam() {
    const answered = Object.keys(answers).length;
    const message = answered < TOTAL
      ? `Masih ada ${TOTAL - answered} soal belum dijawab. Tetap kumpulkan?`
      : 'Kumpulkan jawaban sekarang?';

    if (confirm(message)) {
      document.getElementById('examForm').submit();
    }
  }

  function nextQ() {
    if (curQ < TOTAL - 1) renderQ(curQ + 1);
  }

  function prevQ() {
    if (curQ > 0) renderQ(curQ - 1);
  }

  function jumpCat(index) {
    renderQ(index);
  }

  function escapeHtml(value) {
    return String(value)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  buildGrid();
  renderQ(0);
  startTimer();
</script>
<?php endif; ?>
