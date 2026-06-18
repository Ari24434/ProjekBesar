<div class="page active" id="pg-edit-peserta">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Edit Peserta</h2>
      <p>Perbarui data akun peserta yang tersimpan di database.</p>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1fr) 300px;gap:16px;align-items:start;" class="anim anim-d1 add-peserta-grid">
      <div class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-pencil-square"></i> Peserta #<?= (int) $peserta['id_user'] ?></div>
          <a href="<?= BASE_URL ?>/Admin/kelola-peserta" class="btn btn-ghost btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>

        <form id="formEditPeserta" method="post" action="<?= BASE_URL ?>/Admin/peserta" style="padding:18px;" autocomplete="off">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="id_user" value="<?= (int) $peserta['id_user'] ?>">

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="nama">Nama Lengkap <span class="req">*</span></label>
              <input class="form-input" type="text" id="nama" name="nama" value="<?= htmlspecialchars($peserta['nama']) ?>" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="email">Email <span class="req">*</span></label>
              <input class="form-input" type="email" id="email" name="email" value="<?= htmlspecialchars($peserta['email']) ?>" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="no_hp">No. HP</label>
              <input class="form-input" type="tel" id="no_hp" name="no_hp" value="<?= htmlspecialchars($peserta['no_hp'] ?? '') ?>" placeholder="+62 812-0000-0000">
            </div>

            <div class="form-group">
              <label class="form-label" for="status">Status Akun <span class="req">*</span></label>
              <select class="form-select" id="status" name="status" required>
                <option value="aktif" <?= $peserta['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= $peserta['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label" for="password">Password Baru</label>
              <input class="form-input" type="password" id="password" name="password" placeholder="Kosongkan jika tidak diubah" minlength="8">
            </div>

            <div class="form-group">
              <label class="form-label" for="konfirmasi_password">Konfirmasi Password Baru</label>
              <input class="form-input" type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password baru" minlength="8">
            </div>
          </div>

          <div style="display:flex;justify-content:flex-end;gap:8px;padding-top:6px;border-top:1px solid var(--smoke);margin-top:16px;">
            <a href="<?= BASE_URL ?>/Admin/kelola-peserta" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i> Simpan Perubahan</button>
          </div>
        </form>
      </div>

      <div class="card" style="padding:17px 18px;">
        <div style="width:40px;height:40px;border-radius:10px;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:12px;">
          <i class="bi bi-person-gear"></i>
        </div>
        <div style="font-size:13px;font-weight:700;color:var(--ink);margin-bottom:6px;">Data Akun</div>
        <div style="display:grid;gap:8px;font-size:12px;color:var(--ash);line-height:1.5;">
          <div>Bergabung: <strong style="color:var(--ink);"><?= htmlspecialchars(date('d M Y', strtotime($peserta['created_at']))) ?></strong></div>
          <div>Login terakhir: <strong style="color:var(--ink);"><?= $peserta['last_login'] ? htmlspecialchars(date('d M Y H:i', strtotime($peserta['last_login']))) : '-' ?></strong></div>
          <span class="badge <?= $peserta['status'] === 'aktif' ? 'badge-pass' : 'badge-fail' ?>"><?= ucfirst($peserta['status']) ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('formEditPeserta').addEventListener('submit', function (event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('konfirmasi_password').value;

    if (password && password !== confirmPassword) {
      event.preventDefault();
      alert('Konfirmasi password baru belum sama.');
    }
  });
</script>
