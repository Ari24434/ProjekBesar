<?php
$kodeOpsi = ['A', 'B', 'C', 'D', 'E'];
$kategoriAktif = $soal['kategori'] ?? 'TWK';
$jawabanBenar = 'A';

foreach ($kodeOpsi as $kode) {
  if (!empty($opsi[$kode]['is_kunci'])) {
    $jawabanBenar = $kode;
    break;
  }
}
?>

<div class="page active" id="pg-edit-soal">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Edit Soal</h2>
      <p>Perbarui soal dan pilihan jawaban yang tersimpan di bank soal.</p>
    </div>

    <div class="soal-editor-grid anim anim-d1">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-pencil-square"></i> Soal #<?= (int) $soal['id_soal'] ?></div>
          <a href="<?= BASE_URL ?>/Admin/kelola-soal" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form id="formEditSoal" method="post" action="<?= BASE_URL ?>/Admin/soal" enctype="multipart/form-data" class="soal-form">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="id_soal" value="<?= (int) $soal['id_soal'] ?>">

          <div class="soal-section">
            <div class="soal-section-head">
              <div>
                <div class="soal-section-title">Identitas Soal</div>
                <div class="soal-section-subtitle">Perbarui kategori, level, dan status publikasi.</div>
              </div>
            </div>

            <div class="soal-field-grid three">
              <div class="form-group">
                <label class="form-label" for="kategori">Kategori <span class="req">*</span></label>
                <select class="form-select" id="kategori" name="kategori" required>
                  <option value="TWK" <?= $kategoriAktif === 'TWK' ? 'selected' : '' ?>>TWK - Tes Wawasan Kebangsaan</option>
                  <option value="TIU" <?= $kategoriAktif === 'TIU' ? 'selected' : '' ?>>TIU - Tes Intelegensia Umum</option>
                  <option value="TKP" <?= $kategoriAktif === 'TKP' ? 'selected' : '' ?>>TKP - Tes Karakteristik Pribadi</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="tingkat_kesulitan">Tingkat Kesulitan <span class="req">*</span></label>
                <select class="form-select" id="tingkat_kesulitan" name="tingkat_kesulitan" required>
                  <?php foreach (['mudah' => 'Mudah', 'sedang' => 'Sedang', 'sulit' => 'Sulit'] as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $soal['tingkat_kesulitan'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="status">Status <span class="req">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <?php foreach (['aktif' => 'Aktif', 'draft' => 'Draft', 'nonaktif' => 'Nonaktif'] as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $soal['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="soal-section">
            <div class="soal-section-head">
              <div>
                <div class="soal-section-title">Konten Soal</div>
                <div class="soal-section-subtitle">Isi teks, gambar, atau keduanya.</div>
              </div>
            </div>

            <div class="soal-field-grid two">
              <div class="form-group">
                <label class="form-label" for="subtopik">Subtopik</label>
                <input class="form-input" id="subtopik" name="subtopik" type="text" placeholder="Belum tersedia di database" disabled>
                <div class="field-help">Kolom subtopik belum tersedia di database.</div>
              </div>

              <div class="form-group">
                <label class="form-label" for="gambar">Gambar Soal</label>
                <input class="form-input" id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                <div class="field-help">
                  <?= !empty($soal['gambar']) ? 'Upload file baru untuk mengganti gambar saat ini.' : 'Belum ada gambar. Opsional, maksimal 1 MB.' ?>
                </div>
                <div class="image-preview soal-image-preview" id="gambarPreviewWrap" <?= empty($soal['gambar']) ? 'hidden' : '' ?>>
                  <img id="gambarPreview" src="<?= !empty($soal['gambar']) ? BASE_URL . '/' . htmlspecialchars(ltrim($soal['gambar'], '/')) : '' ?>" alt="Preview gambar soal">
                </div>
                <?php if (!empty($soal['gambar'])): ?>
                  <label class="check-row soal-check-row">
                    <input type="checkbox" id="hapus_gambar" name="hapus_gambar" value="1">
                    <span>Hapus gambar saat ini</span>
                  </label>
                <?php endif; ?>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="pertanyaan">Pertanyaan / Teks Soal</label>
              <textarea class="form-input form-textarea soal-question-input" id="pertanyaan" name="pertanyaan" placeholder="Tulis pertanyaan soal di sini jika soal memiliki teks."><?= htmlspecialchars($soal['pertanyaan']) ?></textarea>
              <div class="field-help">Soal boleh berisi teks saja, gambar saja, atau teks dan gambar sekaligus.</div>
            </div>
          </div>

          <div class="soal-section">
            <div class="soal-section-head">
              <div>
                <div class="soal-section-title">Pilihan Jawaban</div>
                <div class="soal-section-subtitle" id="modeHelp">Pilih satu opsi sebagai jawaban benar untuk TWK/TIU.</div>
              </div>
              <span class="badge badge-twk" id="modeBadge">Binary</span>
            </div>

            <div id="opsiWrap" class="option-list">
              <?php foreach ($kodeOpsi as $index => $kode): ?>
                <?php
                  $option = $opsi[$kode] ?? ['teks_opsi' => '', 'gambar_opsi' => '', 'poin' => $index + 1, 'is_kunci' => 0];
                  $isCorrect = $kategoriAktif !== 'TKP' && $jawabanBenar === $kode;
                ?>
                <div class="option-item option-item-media <?= $isCorrect ? 'correct' : '' ?>" data-option="<?= $kode ?>">
                  <div class="option-main-row">
                    <label class="option-label" for="kunci_<?= $kode ?>"><?= $kode ?></label>
                    <input type="radio" name="jawaban_benar" id="kunci_<?= $kode ?>" value="<?= $kode ?>" <?= $isCorrect ? 'checked' : '' ?> class="jawaban-radio" style="display:none;">
                    <input class="option-input" type="text" name="opsi_<?= $kode ?>" value="<?= htmlspecialchars($option['teks_opsi'] ?? '') ?>" placeholder="Teks opsi <?= $kode ?> jika ada">
                    <input class="form-input poin-input" type="number" name="poin_<?= $kode ?>" min="1" max="5" value="<?= (int) $option['poin'] ?>" style="display:none;" aria-label="Poin opsi <?= $kode ?>">
                  </div>
                  <div class="option-media-row">
                    <div class="option-upload">
                      <i class="bi bi-image"></i>
                      <input class="form-input option-image-input" id="gambar_opsi_<?= $kode ?>" name="gambar_opsi_<?= $kode ?>" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-preview="preview_opsi_<?= $kode ?>" data-wrap="preview_opsi_wrap_<?= $kode ?>" data-remove="hapus_gambar_opsi_<?= $kode ?>">
                    </div>
                    <div class="image-preview option-image-preview" id="preview_opsi_wrap_<?= $kode ?>" <?= empty($option['gambar_opsi']) ? 'hidden' : '' ?>>
                      <img id="preview_opsi_<?= $kode ?>" src="<?= !empty($option['gambar_opsi']) ? BASE_URL . '/' . htmlspecialchars(ltrim($option['gambar_opsi'], '/')) : '' ?>" alt="Preview gambar opsi <?= $kode ?>">
                    </div>
                    <?php if (!empty($option['gambar_opsi'])): ?>
                      <label class="check-row soal-check-row">
                        <input type="checkbox" id="hapus_gambar_opsi_<?= $kode ?>" name="hapus_gambar_opsi_<?= $kode ?>" value="1" data-preview="preview_opsi_<?= $kode ?>" data-wrap="preview_opsi_wrap_<?= $kode ?>" data-input="gambar_opsi_<?= $kode ?>">
                        <span>Hapus gambar opsi <?= $kode ?></span>
                      </label>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="soal-section">
            <div class="form-group">
              <label class="form-label" for="pembahasan">Pembahasan / Catatan</label>
              <textarea class="form-input form-textarea" id="pembahasan" name="pembahasan" placeholder="Belum tersedia di database." disabled></textarea>
              <div class="field-help">Kolom pembahasan belum tersedia di database.</div>
            </div>
          </div>

          <div class="soal-actions">
            <a href="<?= BASE_URL ?>/Admin/kelola-soal" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>

      <div class="card soal-side-card">
        <div class="soal-side-icon">
          <i class="bi bi-shield-check"></i>
        </div>
        <div class="soal-side-title">Mode Edit Aman</div>
        <div class="soal-side-text">
          Perubahan soal dan opsi disimpan dalam satu transaksi. Isi minimal teks soal atau gambar soal agar soal tetap dapat ditampilkan.
        </div>
        <div class="soal-side-badges">
          <span class="badge badge-twk">TWK/TIU: satu kunci benar</span>
          <span class="badge badge-tkp">TKP: poin opsi 1-5</span>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .soal-editor-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 16px;
    align-items: start;
  }

  .soal-form {
    padding: 18px;
  }

  .soal-section {
    padding: 16px;
    border: 1px solid var(--smoke);
    border-radius: 8px;
    background: #fff;
  }

  .soal-section + .soal-section {
    margin-top: 14px;
  }

  .soal-section-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 13px;
  }

  .soal-section-title {
    font-size: 13px;
    font-weight: 800;
    color: var(--ink);
  }

  .soal-section-subtitle,
  .field-help {
    font-size: 11px;
    color: var(--ash);
  }

  .field-help {
    margin-top: 5px;
    line-height: 1.45;
  }

  .soal-field-grid {
    display: grid;
    gap: 12px;
  }

  .soal-field-grid.three {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }

  .soal-field-grid.two {
    grid-template-columns: minmax(0, 1fr) minmax(260px, .75fr);
  }

  .soal-question-input {
    min-height: 150px;
    resize: vertical;
  }

  .soal-image-preview,
  .option-image-preview {
    margin-top: 9px;
    border: 1px solid var(--smoke);
    border-radius: 8px;
    background: var(--cloud);
    overflow: hidden;
  }

  .soal-image-preview img,
  .option-image-preview img {
    display: block;
    width: 100%;
    max-height: 220px;
    object-fit: contain;
  }

  .option-list {
    display: grid;
    gap: 10px;
  }

  .option-item {
    border: 1px solid var(--smoke);
    border-radius: 8px;
    padding: 12px;
    background: #fff;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
  }

  .option-item.correct {
    border-color: rgba(30, 84, 183, .4);
    background: #F8FBFF;
    box-shadow: 0 0 0 3px rgba(30, 84, 183, .07);
  }

  .option-main-row {
    display: grid;
    grid-template-columns: 38px minmax(0, 1fr) 82px;
    gap: 10px;
    align-items: center;
  }

  .option-label {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--cloud);
    color: var(--blue-main);
    font-weight: 800;
    cursor: pointer;
  }

  .option-input {
    width: 100%;
    min-height: 38px;
    border: 1px solid var(--smoke);
    border-radius: 8px;
    padding: 8px 10px;
    color: var(--ink);
    background: #fff;
    outline: none;
  }

  .option-input:focus {
    border-color: rgba(30, 84, 183, .45);
    box-shadow: 0 0 0 3px rgba(30, 84, 183, .08);
  }

  .poin-input {
    width: 82px;
  }

  .option-media-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 8px;
    margin-top: 9px;
    padding-left: 48px;
  }

  .option-upload {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--ash);
  }

  .option-upload .form-input {
    flex: 1;
  }

  .soal-check-row {
    margin-top: 8px;
  }

  .soal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 14px;
    border-top: 1px solid var(--smoke);
    margin-top: 16px;
  }

  .soal-side-card {
    padding: 17px 18px;
    position: sticky;
    top: 86px;
  }

  .soal-side-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--frost);
    color: var(--blue-main);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 12px;
  }

  .soal-side-title {
    font-size: 13px;
    font-weight: 800;
    color: var(--ink);
    margin-bottom: 6px;
  }

  .soal-side-text {
    font-size: 12px;
    color: var(--ash);
    line-height: 1.6;
    margin-bottom: 14px;
  }

  .soal-side-badges {
    display: grid;
    gap: 8px;
  }

  @media (max-width: 1060px) {
    .soal-editor-grid,
    .soal-field-grid.three,
    .soal-field-grid.two {
      grid-template-columns: 1fr;
    }

    .soal-side-card {
      position: static;
    }
  }

  @media (max-width: 640px) {
    .soal-form,
    .soal-section {
      padding: 14px;
    }

    .option-main-row {
      grid-template-columns: 38px minmax(0, 1fr);
    }

    .poin-input {
      grid-column: 2;
      width: 100%;
    }

    .option-media-row {
      padding-left: 0;
    }

    .soal-actions {
      flex-direction: column-reverse;
    }

    .soal-actions .btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>

<script>
  const kategoriEl = document.getElementById('kategori');
  const modeHelp = document.getElementById('modeHelp');
  const modeBadge = document.getElementById('modeBadge');
  const opsiWrap = document.getElementById('opsiWrap');
  const formEditSoal = document.getElementById('formEditSoal');
  const optionItems = [...document.querySelectorAll('.option-item')];
  const poinInputs = [...document.querySelectorAll('.poin-input')];
  const radioInputs = [...document.querySelectorAll('.jawaban-radio')];

  function ensureBinaryAnswer() {
    if (kategoriEl.value === 'TKP') return;

    const checkedRadio = radioInputs.find((input) => input.checked);

    if (!checkedRadio && radioInputs[0]) {
      radioInputs[0].checked = true;
    }
  }

  function setMode() {
    const isTkp = kategoriEl.value === 'TKP';
    modeHelp.textContent = isTkp
      ? 'Isi poin 1-5 untuk setiap opsi TKP. Tidak ada satu jawaban benar.'
      : 'Pilih satu opsi sebagai jawaban benar untuk TWK/TIU.';
    modeBadge.textContent = isTkp ? 'Gradual' : 'Binary';
    modeBadge.className = isTkp ? 'badge badge-tkp' : 'badge badge-twk';

    poinInputs.forEach((input) => {
      input.style.display = isTkp ? 'block' : 'none';
      input.required = isTkp;
      input.disabled = !isTkp;
    });

    radioInputs.forEach((input) => {
      input.disabled = isTkp;
    });

    ensureBinaryAnswer();

    optionItems.forEach((item) => {
      item.classList.toggle('correct', !isTkp && item.querySelector('.jawaban-radio').checked);
    });
  }

  opsiWrap.addEventListener('click', function (event) {
    const item = event.target.closest('.option-item');
    if (!item || kategoriEl.value === 'TKP' || event.target.closest('.option-media-row')) return;

    const radio = item.querySelector('.jawaban-radio');
    radio.checked = true;
    optionItems.forEach((row) => row.classList.toggle('correct', row === item));
  });

  kategoriEl.addEventListener('change', setMode);
  setMode();

  formEditSoal?.addEventListener('submit', function () {
    setMode();
    ensureBinaryAnswer();
  });

  const gambarInput = document.getElementById('gambar');
  const gambarPreviewWrap = document.getElementById('gambarPreviewWrap');
  const gambarPreview = document.getElementById('gambarPreview');
  const hapusGambar = document.getElementById('hapus_gambar');
  const existingPreviewSrc = gambarPreview?.getAttribute('src') || '';

  gambarInput.addEventListener('change', function () {
    const file = gambarInput.files?.[0];

    if (!file) {
      gambarPreview.src = existingPreviewSrc;
      gambarPreviewWrap.hidden = !existingPreviewSrc || hapusGambar?.checked;
      return;
    }

    if (hapusGambar) {
      hapusGambar.checked = false;
    }

    gambarPreview.src = URL.createObjectURL(file);
    gambarPreviewWrap.hidden = false;
  });

  hapusGambar?.addEventListener('change', function () {
    if (hapusGambar.checked) {
      gambarInput.value = '';
      gambarPreviewWrap.hidden = true;
      return;
    }

    gambarPreview.src = existingPreviewSrc;
    gambarPreviewWrap.hidden = !existingPreviewSrc;
  });

  document.querySelectorAll('.option-image-input').forEach((input) => {
    const preview = document.getElementById(input.dataset.preview);
    const previewWrap = document.getElementById(input.dataset.wrap);
    const removeInput = input.dataset.remove ? document.getElementById(input.dataset.remove) : null;
    const existingSrc = preview?.getAttribute('src') || '';

    input.addEventListener('change', function () {
      const file = input.files?.[0];

      if (!file) {
        preview.src = existingSrc;
        previewWrap.hidden = !existingSrc || removeInput?.checked;
        return;
      }

      if (removeInput) {
        removeInput.checked = false;
      }

      preview.src = URL.createObjectURL(file);
      previewWrap.hidden = false;
    });
  });

  document.querySelectorAll('[id^="hapus_gambar_opsi_"]').forEach((checkbox) => {
    const preview = document.getElementById(checkbox.dataset.preview);
    const previewWrap = document.getElementById(checkbox.dataset.wrap);
    const fileInput = document.getElementById(checkbox.dataset.input);
    const existingSrc = preview?.getAttribute('src') || '';

    checkbox.addEventListener('change', function () {
      if (checkbox.checked) {
        fileInput.value = '';
        previewWrap.hidden = true;
        return;
      }

      preview.src = existingSrc;
      previewWrap.hidden = !existingSrc;
    });
  });
</script>
