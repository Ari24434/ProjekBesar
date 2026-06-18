<?php
$activeQuestionStock = $activeQuestionStock ?? ['TWK' => 0, 'TIU' => 0, 'TKP' => 0];
$tryoutSettings = $tryoutSettings ?? app_default_tryout_settings();
$defaultTwk = min((int) $tryoutSettings['soal_twk'], (int) $activeQuestionStock['TWK']);
$defaultTiu = min((int) $tryoutSettings['soal_tiu'], (int) $activeQuestionStock['TIU']);
$defaultTkp = min((int) $tryoutSettings['soal_tkp'], (int) $activeQuestionStock['TKP']);
?>

<div class="page active" id="pg-buat-tryout">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Buat Tryout Baru</h2>
      <p>Atur informasi sesi, jadwal, jumlah soal, dan publikasi tryout.</p>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:16px;align-items:start;" class="anim anim-d1 add-peserta-grid">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-journal-plus"></i> Informasi Tryout</div>
          <a href="<?= BASE_URL ?>/Admin/kelola-tryout" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form id="formBuatTryout" method="post" action="<?= BASE_URL ?>/Admin/tryout" style="padding:18px;">
          <?= csrf_field() ?>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="nama_tryout">Nama Tryout <span class="req">*</span></label>
              <input class="form-input" id="nama_tryout" name="nama_tryout" type="text" placeholder="Contoh: Tryout SKD - Sesi 5" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="status">Status <span class="req">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="draft">Draft</option>
                <option value="aktif">Aktif / Berjalan</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi</label>
            <textarea class="form-input form-textarea" id="deskripsi" name="deskripsi" placeholder="Tuliskan fokus tryout atau catatan sesi."></textarea>
          </div>

          <div class="form-row-3">
            <div class="form-group">
              <label class="form-label" for="jml_soal_twk">Soal TWK <span class="req">*</span></label>
              <input class="form-input" id="jml_soal_twk" name="jml_soal_twk" type="number" min="0" max="<?= (int) $activeQuestionStock['TWK'] ?>" value="<?= $defaultTwk ?>" required data-stock="<?= (int) $activeQuestionStock['TWK'] ?>">
              <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TWK'] ?> soal.</div>
            </div>
            <div class="form-group">
              <label class="form-label" for="jml_soal_tiu">Soal TIU <span class="req">*</span></label>
              <input class="form-input" id="jml_soal_tiu" name="jml_soal_tiu" type="number" min="0" max="<?= (int) $activeQuestionStock['TIU'] ?>" value="<?= $defaultTiu ?>" required data-stock="<?= (int) $activeQuestionStock['TIU'] ?>">
              <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TIU'] ?> soal.</div>
            </div>
            <div class="form-group">
              <label class="form-label" for="jml_soal_tkp">Soal TKP <span class="req">*</span></label>
              <input class="form-input" id="jml_soal_tkp" name="jml_soal_tkp" type="number" min="0" max="<?= (int) $activeQuestionStock['TKP'] ?>" value="<?= $defaultTkp ?>" required data-stock="<?= (int) $activeQuestionStock['TKP'] ?>">
              <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TKP'] ?> soal.</div>
            </div>
          </div>

          <div class="form-row-3">
            <div class="form-group">
              <label class="form-label" for="waktu">Durasi Menit <span class="req">*</span></label>
              <input class="form-input" id="waktu" name="waktu" type="number" min="1" value="<?= (int) $tryoutSettings['durasi_default'] ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="tanggal_mulai">Tanggal Mulai <span class="req">*</span></label>
              <input class="form-input" id="tanggal_mulai" name="tanggal_mulai" type="datetime-local" required>
            </div>
            <div class="form-group">
              <label class="form-label" for="tanggal_selesai">Tanggal Selesai <span class="req">*</span></label>
              <input class="form-input" id="tanggal_selesai" name="tanggal_selesai" type="datetime-local" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="acak_soal">Pengacakan Soal</label>
              <select class="form-select" id="acak_soal" name="acak_soal">
                <option value="1" <?= (int) $tryoutSettings['acak_soal'] === 1 ? 'selected' : '' ?>>Aktif - soal diacak</option>
                <option value="0" <?= (int) $tryoutSettings['acak_soal'] === 0 ? 'selected' : '' ?>>Nonaktif - urutan tetap</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label" for="acak_opsi">Pengacakan Opsi</label>
              <select class="form-select" id="acak_opsi" name="acak_opsi">
                <option value="0" <?= (int) $tryoutSettings['acak_opsi'] === 0 ? 'selected' : '' ?>>Nonaktif - opsi tetap</option>
                <option value="1" <?= (int) $tryoutSettings['acak_opsi'] === 1 ? 'selected' : '' ?>>Aktif - opsi diacak</option>
              </select>
            </div>
          </div>

          <div style="display:flex;justify-content:flex-end;gap:8px;padding-top:6px;border-top:1px solid var(--smoke);margin-top:16px;">
            <a href="<?= BASE_URL ?>/Admin/kelola-tryout" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Tryout</button>
          </div>
        </form>
      </div>

      <div class="card" style="padding:17px 18px;">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px;">
          <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:6px;">Standar SKD</div>
        <div style="font-size:12px;color:var(--ash);line-height:1.6;margin-bottom:14px;">
          Jumlah soal tidak boleh melebihi stok soal aktif di bank soal. Tambahkan atau aktifkan soal terlebih dahulu jika ingin memakai komposisi SKD penuh.
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
          <span class="badge badge-twk">TWK <?= (int) $activeQuestionStock['TWK'] ?></span>
          <span class="badge badge-tiu">TIU <?= (int) $activeQuestionStock['TIU'] ?></span>
          <span class="badge badge-tkp">TKP <?= (int) $activeQuestionStock['TKP'] ?></span>
          <span class="badge badge-pending"><?= (int) $tryoutSettings['durasi_default'] ?> Menit</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('formBuatTryout').addEventListener('submit', function (event) {
    const mulai = document.getElementById('tanggal_mulai').value;
    const selesai = document.getElementById('tanggal_selesai').value;
    const inputs = [
      ['TWK', document.getElementById('jml_soal_twk')],
      ['TIU', document.getElementById('jml_soal_tiu')],
      ['TKP', document.getElementById('jml_soal_tkp')],
    ];
    const totalSoal = inputs.reduce((sum, item) => sum + Math.max(0, Number(item[1].value || 0)), 0);

    for (const [label, input] of inputs) {
      const value = Math.max(0, Number(input.value || 0));
      const stock = Math.max(0, Number(input.dataset.stock || 0));

      if (value > stock) {
        event.preventDefault();
        const message = `Jumlah soal ${label} melebihi stok aktif. Maksimal ${stock} soal.`;
        window.Swal ? Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: message }) : alert(message);
        return;
      }
    }

    if (totalSoal <= 0) {
      event.preventDefault();
      const message = 'Jumlah soal minimal 1.';
      window.Swal ? Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: message }) : alert(message);
      return;
    }

    if (mulai && selesai && new Date(selesai) <= new Date(mulai)) {
      event.preventDefault();
      const message = 'Tanggal selesai harus setelah tanggal mulai.';
      window.Swal ? Swal.fire({ icon: 'error', title: 'Validasi Gagal', text: message }) : alert(message);
    }
  });
</script>
