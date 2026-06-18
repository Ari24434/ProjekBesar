<div class="page active" id="pg-tambah-peserta">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Tambah Peserta</h2>
      <p>Daftarkan akun peserta baru untuk mengikuti tryout CPNS.</p>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:16px;align-items:start;" class="anim anim-d1 add-peserta-grid">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-person-plus-fill"></i> Data Akun Peserta</div>
          <a href="<?= BASE_URL ?>/Admin/kelola-peserta" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form id="formTambahPeserta" method="post" action="<?= BASE_URL ?>/Admin/peserta" style="padding:18px;" autocomplete="off">
          <?= csrf_field() ?>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="nama">Nama Lengkap <span class="req">*</span></label>
              <input class="form-input" type="text" id="nama" name="nama" placeholder="Masukkan nama peserta" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="email">Email <span class="req">*</span></label>
              <input class="form-input" type="email" id="email" name="email" placeholder="peserta@email.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="no_hp">No. HP</label>
              <input class="form-input" type="tel" id="no_hp" name="no_hp" placeholder="+62 812-0000-0000">
            </div>

            <div class="form-group">
              <label class="form-label" for="status">Status Akun <span class="req">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="password">Password Awal <span class="req">*</span></label>
              <input class="form-input" type="password" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="konfirmasi_password">Konfirmasi Password <span class="req">*</span></label>
              <input class="form-input" type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password" minlength="8" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="catatan">Catatan Admin</label>
            <textarea class="form-input form-textarea" id="catatan" name="catatan" placeholder="Contoh: peserta batch Mei 2026, kelas malam, atau catatan pembayaran."></textarea>
          </div>

          <div style="display:flex;justify-content:flex-end;gap:8px;padding-top:6px;border-top:1px solid var(--smoke);margin-top:16px;">
            <a href="<?= BASE_URL ?>/Admin/kelola-peserta" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Peserta</button>
          </div>
        </form>
      </div>

      <div class="card" style="padding:17px 18px;">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px;">
          <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:6px;">Akses Peserta</div>
        <div style="font-size:12px;color:var(--ash);line-height:1.6;margin-bottom:14px;">
          Akun yang dibuat akan digunakan peserta untuk masuk ke dashboard, mengerjakan tryout, dan melihat riwayat nilai.
        </div>
        <div style="background:var(--cloud);border:1px solid var(--smoke);border-radius:var(--r-md);padding:12px;">
          <div style="font-size:11px;color:var(--ash);margin-bottom:7px;">Role akun</div>
          <span class="badge badge-tiu"><i class="bi bi-person-fill"></i> Peserta</span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('formTambahPeserta').addEventListener('submit', function (event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('konfirmasi_password').value;

    if (password !== confirmPassword) {
      event.preventDefault();
      alert('Konfirmasi password belum sama.');
    }
  });
</script>
