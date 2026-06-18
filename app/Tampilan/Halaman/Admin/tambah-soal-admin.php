<div class="page active" id="pg-tambah-soal">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Tambah Soal</h2>
      <p>Tambahkan soal TWK, TIU, atau TKP beserta pilihan jawaban A-E.</p>
    </div>

    <div class="soal-editor-grid anim anim-d1">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-question-circle-fill"></i> Data Soal</div>
          <a href="<?= BASE_URL ?>/Admin/kelola-soal" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form id="formTambahSoal" method="post" action="<?= BASE_URL ?>/Admin/soal" enctype="multipart/form-data" class="soal-form">
          <?= csrf_field() ?>
          <div class="soal-section">
            <div class="soal-section-head">
              <div>
                <div class="soal-section-title">Identitas Soal</div>
                <div class="soal-section-subtitle">Tentukan kategori, level, dan status publikasi.</div>
              </div>
            </div>

            <div class="soal-field-grid three">
              <div class="form-group">
                <label class="form-label" for="kategori">Kategori <span class="req">*</span></label>
                <select class="form-select" id="kategori" name="kategori" required>
                  <option value="TWK">TWK - Tes Wawasan Kebangsaan</option>
                  <option value="TIU">TIU - Tes Intelegensia Umum</option>
                  <option value="TKP">TKP - Tes Karakteristik Pribadi</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="tingkat_kesulitan">Tingkat Kesulitan <span class="req">*</span></label>
                <select class="form-select" id="tingkat_kesulitan" name="tingkat_kesulitan" required>
                  <option value="mudah">Mudah</option>
                  <option value="sedang" selected>Sedang</option>
                  <option value="sulit">Sulit</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="status">Status <span class="req">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="aktif">Aktif</option>
                  <option value="draft">Draft</option>
                  <option value="nonaktif">Nonaktif</option>
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
                <input class="form-input" id="subtopik" name="subtopik" type="text" placeholder="Contoh: Pancasila, Analogi, Pelayanan Publik">
<<<<<<< HEAD
                <div class="field-help">Catatan: kolom subtopik belum disimpan ke database.</div>
=======
                <div class="field-help">Opsional. Dipakai untuk evaluasi kelemahan peserta pada hasil tryout.</div>
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
              </div>

              <div class="form-group">
                <label class="form-label" for="gambar">Gambar Soal</label>
                <input class="form-input" id="gambar" name="gambar" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                <div class="field-help">Opsional. JPG, PNG, WEBP, atau GIF. Maksimal 1 MB.</div>
                <div class="image-preview soal-image-preview" id="gambarPreviewWrap" hidden>
                  <img id="gambarPreview" alt="Preview gambar soal">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="pertanyaan">Pertanyaan / Teks Soal</label>
              <textarea class="form-input form-textarea soal-question-input" id="pertanyaan" name="pertanyaan" placeholder="Tulis pertanyaan soal di sini jika soal memiliki teks."></textarea>
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
              <?php foreach (['A', 'B', 'C', 'D', 'E'] as $index => $kode): ?>
                <div class="option-item option-item-media <?= $index === 0 ? 'correct' : '' ?>" data-option="<?= $kode ?>">
                  <div class="option-main-row">
                    <label class="option-label" for="kunci_<?= $kode ?>"><?= $kode ?></label>
                    <input type="radio" name="jawaban_benar" id="kunci_<?= $kode ?>" value="<?= $kode ?>" <?= $index === 0 ? 'checked' : '' ?> class="jawaban-radio" style="display:none;">
                    <input class="option-input" type="text" name="opsi_<?= $kode ?>" placeholder="Teks opsi <?= $kode ?> jika ada">
                    <input class="form-input poin-input" type="number" name="poin_<?= $kode ?>" min="1" max="5" value="<?= $index + 1 ?>" style="display:none;" aria-label="Poin opsi <?= $kode ?>">
                  </div>
                  <div class="option-media-row">
                    <div class="option-upload">
                      <i class="bi bi-image"></i>
                      <input class="form-input option-image-input" id="gambar_opsi_<?= $kode ?>" name="gambar_opsi_<?= $kode ?>" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-preview="preview_opsi_<?= $kode ?>" data-wrap="preview_opsi_wrap_<?= $kode ?>">
                    </div>
                    <div class="image-preview option-image-preview" id="preview_opsi_wrap_<?= $kode ?>" hidden>
                      <img id="preview_opsi_<?= $kode ?>" alt="Preview gambar opsi <?= $kode ?>">
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="soal-section">
            <div class="form-group">
              <label class="form-label" for="pembahasan">Pembahasan / Catatan</label>
              <textarea class="form-input form-textarea" id="pembahasan" name="pembahasan" placeholder="Opsional, isi pembahasan singkat untuk kebutuhan review peserta."></textarea>
<<<<<<< HEAD
              <div class="field-help">Catatan: kolom pembahasan belum disimpan ke database.</div>
=======
              <div class="field-help">Opsional. Akan tampil pada detail soal setelah peserta menyelesaikan tryout.</div>
>>>>>>> 887a69fd937d4b82d85b5392eb79834a99f757db
            </div>
          </div>

          <div class="soal-actions">
            <a href="<?= BASE_URL ?>/Admin/kelola-soal" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Soal</button>
          </div>
        </form>
      </div>

      <div class="card soal-side-card">
        <div class="soal-side-icon">
          <i class="bi bi-list-check"></i>
        </div>
        <div class="soal-side-title">Aturan Penilaian</div>
        <div class="soal-side-text">
          TWK dan TIU memakai satu jawaban benar dengan poin 5. TKP memakai poin bertingkat 1 sampai 5 pada setiap opsi. Isi minimal teks soal atau gambar soal.
        </div>
        <div class="soal-side-badges">
          <span class="badge badge-twk">TWK: benar 5, salah 0</span>
          <span class="badge badge-tiu">TIU: benar 5, salah 0</span>
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
  const optionItems = [...document.querySelectorAll('.option-item')];
  const poinInputs = [...document.querySelectorAll('.poin-input')];
  const radioInputs = [...document.querySelectorAll('.jawaban-radio')];

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
    });

    radioInputs.forEach((input) => {
      input.disabled = isTkp;
    });

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

  const gambarInput = document.getElementById('gambar');
  const gambarPreviewWrap = document.getElementById('gambarPreviewWrap');
  const gambarPreview = document.getElementById('gambarPreview');

  gambarInput.addEventListener('change', function () {
    const file = gambarInput.files?.[0];

    if (!file) {
      gambarPreviewWrap.hidden = true;
      gambarPreview.removeAttribute('src');
      return;
    }

    gambarPreview.src = URL.createObjectURL(file);
    gambarPreviewWrap.hidden = false;
  });

  document.querySelectorAll('.option-image-input').forEach((input) => {
    input.addEventListener('change', function () {
      const preview = document.getElementById(input.dataset.preview);
      const previewWrap = document.getElementById(input.dataset.wrap);
      const file = input.files?.[0];

      if (!file) {
        preview.removeAttribute('src');
        previewWrap.hidden = true;
        return;
      }

      preview.src = URL.createObjectURL(file);
      previewWrap.hidden = false;
    });
  });

</script>
