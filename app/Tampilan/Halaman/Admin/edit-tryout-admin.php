<?php
$id = (int) $tryout['id_tryout'];
$tanggalMulai = date('Y-m-d\TH:i', strtotime($tryout['tanggal_mulai']));
$tanggalSelesai = date('Y-m-d\TH:i', strtotime($tryout['tanggal_selesai']));
$soalTerpasang = db_fetch('SELECT COUNT(*) AS total FROM tryout_soal WHERE id_tryout = ?', [$id]);
$hasilCount = db_fetch('SELECT COUNT(*) AS total FROM hasil WHERE id_tryout = ?', [$id]);
$activeQuestionStock = $activeQuestionStock ?? ['TWK' => 0, 'TIU' => 0, 'TKP' => 0];
?>

<div class="page active" id="pg-edit-tryout">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Edit Tryout</h2>
      <p>Perbarui jadwal, komposisi soal, dan status publikasi tryout.</p>
    </div>

    <div class="card anim anim-d1">
      <div class="card-head">
        <div class="card-title"><i class="bi bi-pencil-square"></i> <?= htmlspecialchars($tryout['nama_tryout']) ?></div>
        <a href="<?= BASE_URL ?>/Admin/kelola-tryout" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
      </div>

      <form id="formEditTryout" method="post" action="<?= BASE_URL ?>/Admin/tryout" style="padding:18px;">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id_tryout" value="<?= $id ?>">

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="nama_tryout">Nama Tryout <span class="req">*</span></label>
            <input class="form-input" id="nama_tryout" name="nama_tryout" type="text" value="<?= htmlspecialchars($tryout['nama_tryout']) ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="status">Status <span class="req">*</span></label>
            <select class="form-select" id="status" name="status" required>
              <?php foreach (['draft' => 'Draft', 'aktif' => 'Aktif / Berjalan', 'selesai' => 'Selesai', 'diarsipkan' => 'Diarsipkan'] as $value => $label): ?>
                <option value="<?= $value ?>" <?= $tryout['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="deskripsi">Deskripsi</label>
          <textarea class="form-input form-textarea" id="deskripsi" name="deskripsi"><?= htmlspecialchars($tryout['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-row-3">
          <div class="form-group">
            <label class="form-label" for="jml_soal_twk">Soal TWK</label>
            <input class="form-input" id="jml_soal_twk" name="jml_soal_twk" type="number" min="0" max="<?= (int) $activeQuestionStock['TWK'] ?>" value="<?= (int) $tryout['jml_soal_twk'] ?>" data-stock="<?= (int) $activeQuestionStock['TWK'] ?>">
            <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TWK'] ?> soal.</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="jml_soal_tiu">Soal TIU</label>
            <input class="form-input" id="jml_soal_tiu" name="jml_soal_tiu" type="number" min="0" max="<?= (int) $activeQuestionStock['TIU'] ?>" value="<?= (int) $tryout['jml_soal_tiu'] ?>" data-stock="<?= (int) $activeQuestionStock['TIU'] ?>">
            <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TIU'] ?> soal.</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="jml_soal_tkp">Soal TKP</label>
            <input class="form-input" id="jml_soal_tkp" name="jml_soal_tkp" type="number" min="0" max="<?= (int) $activeQuestionStock['TKP'] ?>" value="<?= (int) $tryout['jml_soal_tkp'] ?>" data-stock="<?= (int) $activeQuestionStock['TKP'] ?>">
            <div style="font-size:10.5px;color:var(--ash);margin-top:4px;">Stok aktif: <?= (int) $activeQuestionStock['TKP'] ?> soal.</div>
          </div>
        </div>

        <div class="form-row-3">
          <div class="form-group">
            <label class="form-label" for="waktu">Durasi Menit</label>
            <input class="form-input" id="waktu" name="waktu" type="number" min="1" value="<?= (int) $tryout['waktu'] ?>">
          </div>
          <div class="form-group">
            <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
            <input class="form-input" id="tanggal_mulai" name="tanggal_mulai" type="datetime-local" value="<?= $tanggalMulai ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label" for="tanggal_selesai">Tanggal Selesai</label>
            <input class="form-input" id="tanggal_selesai" name="tanggal_selesai" type="datetime-local" value="<?= $tanggalSelesai ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="acak_soal">Pengacakan Soal</label>
            <select class="form-select" id="acak_soal" name="acak_soal">
              <option value="1" <?= (int) $tryout['acak_soal'] === 1 ? 'selected' : '' ?>>Aktif - soal diacak</option>
              <option value="0" <?= (int) $tryout['acak_soal'] === 0 ? 'selected' : '' ?>>Nonaktif - urutan tetap</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label" for="acak_opsi">Pengacakan Opsi</label>
            <select class="form-select" id="acak_opsi" name="acak_opsi">
              <option value="0" <?= (int) $tryout['acak_opsi'] === 0 ? 'selected' : '' ?>>Nonaktif - opsi tetap</option>
              <option value="1" <?= (int) $tryout['acak_opsi'] === 1 ? 'selected' : '' ?>>Aktif - opsi diacak</option>
            </select>
          </div>
        </div>

        <div style="background:var(--cloud);border:1px solid var(--smoke);border-radius:var(--r-md);padding:12px;margin:4px 0 16px;font-size:12px;color:var(--slate);">
          Soal terpasang saat ini: <strong><?= (int) ($soalTerpasang['total'] ?? 0) ?></strong>.
          Hasil peserta terkait: <strong><?= (int) ($hasilCount['total'] ?? 0) ?></strong>.
          Saat disimpan, komposisi soal akan disinkronkan ulang dari bank soal aktif.
        </div>

        <div style="display:flex;justify-content:space-between;gap:8px;padding-top:6px;border-top:1px solid var(--smoke);margin-top:16px;flex-wrap:wrap;">
          <a href="<?= BASE_URL ?>/Admin/hasil-tryout?id=<?= $id ?>" class="btn btn-ghost"><i class="bi bi-bar-chart"></i> Lihat Hasil</a>
          <div style="display:flex;gap:8px;">
            <a href="<?= BASE_URL ?>/Admin/kelola-tryout" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('formEditTryout').addEventListener('submit', function (event) {
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
