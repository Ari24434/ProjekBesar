<?php
$systemInfo = $systemInfo ?? [];
$settingsError = $settingsError ?? null;
$adminAccount = $adminAccount ?? null;
$tryoutSettings = $tryoutSettings ?? app_default_tryout_settings();

$infoRows = [
  'Nama Aplikasi' => $systemInfo['app_name'] ?? "Oman's Club Academy",
  'Versi Sistem' => $systemInfo['app_version'] ?? 'v1.0.0-dev',
  'Environment' => strtoupper($systemInfo['app_env'] ?? 'development'),
  'URL Aplikasi' => $systemInfo['app_url'] ?? '-',
  'Database' => $systemInfo['db_name'] ?? '-',
  'MySQL' => $systemInfo['db_version'] ?? '-',
  'PHP' => $systemInfo['php_version'] ?? PHP_VERSION,
  'Server' => $systemInfo['server_software'] ?? '-',
  'Backup Terakhir' => $systemInfo['backup_terakhir'] ?? '-',
  'Update Data Terakhir' => $systemInfo['update_terakhir'] ?? '-',
];
?>

<div class="page active" id="pg-pengaturan">
  <div class="page-body">
    <div class="page-heading anim">
      <h2>Pengaturan Sistem</h2>
      <p>Konfigurasi akun administrator dan sistem tryout.</p>
    </div>

    <?php if ($settingsError): ?>
      <div class="card anim" style="padding:11px 14px;margin-bottom:14px;border-color:rgba(239,68,68,.35);background:#FEF2F2;color:#991B1B;font-size:12.5px;font-weight:700;">
        <?= htmlspecialchars($settingsError) ?>
      </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 330px;gap:15px;" class="anim anim-d1">
      <div style="display:flex;flex-direction:column;gap:15px;">
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-person-fill"></i> Informasi Administrator</div></div>
          <div style="padding:18px;">
            <?php if (!$adminAccount): ?>
              <div style="padding:12px;border:1px solid rgba(239,68,68,.35);border-radius:8px;background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
                Akun admin belum tersedia di database.
              </div>
            <?php else: ?>
              <form method="post" action="<?= BASE_URL ?>/Admin/pengaturan/profil" id="formAdminProfile">
                <?= csrf_field() ?>
                <input type="hidden" name="id_user" value="<?= (int) $adminAccount['id_user'] ?>">
                <div class="form-row">
                  <div class="form-group">
                    <label class="form-label" for="admin_nama">Nama Admin <span class="req">*</span></label>
                    <input type="text" class="form-input" id="admin_nama" name="nama" value="<?= htmlspecialchars($adminAccount['nama'] ?? '') ?>" minlength="3" maxlength="100" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="admin_email">Email <span class="req">*</span></label>
                    <input type="email" class="form-input" id="admin_email" name="email" value="<?= htmlspecialchars($adminAccount['email'] ?? '') ?>" maxlength="150" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="admin_no_hp">Nomor HP</label>
                    <input type="tel" class="form-input" id="admin_no_hp" name="no_hp" value="<?= htmlspecialchars($adminAccount['no_hp'] ?? '') ?>" maxlength="20" pattern="[0-9+\-\s()]{8,20}" placeholder="+62 812-0000-0001">
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="admin_status">Status <span class="req">*</span></label>
                    <select class="form-select" id="admin_status" name="status" required>
                      <?php foreach (['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($adminAccount['status'] ?? 'aktif') === $value ? 'selected' : '' ?>><?= $label ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-input" value="Administrator" disabled>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Bergabung</label>
                    <input type="text" class="form-input" value="<?= !empty($adminAccount['created_at']) ? htmlspecialchars(date('d M Y H:i', strtotime($adminAccount['created_at']))) : '-' ?>" disabled>
                  </div>
                </div>
                <button class="btn btn-primary" type="submit"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
              </form>
            <?php endif; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-sliders"></i> Konfigurasi Tryout</div></div>
          <div style="padding:18px;">
            <form method="post" action="<?= BASE_URL ?>/Admin/pengaturan/tryout" id="formTryoutSettings">
              <?= csrf_field() ?>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="durasi_default">Durasi Default (menit)</label>
                  <input type="number" class="form-input" id="durasi_default" name="durasi_default" min="1" max="300" value="<?= (int) $tryoutSettings['durasi_default'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="jumlah_soal_per_sesi">Jumlah Soal per Sesi</label>
                  <input type="number" class="form-input" id="jumlah_soal_per_sesi" value="<?= (int) $tryoutSettings['jumlah_soal_per_sesi'] ?>" readonly>
                </div>
                <div class="form-group">
                  <label class="form-label" for="soal_twk">Soal TWK</label>
                  <input type="number" class="form-input tryout-count-input" id="soal_twk" name="soal_twk" min="0" max="200" value="<?= (int) $tryoutSettings['soal_twk'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="soal_tiu">Soal TIU</label>
                  <input type="number" class="form-input tryout-count-input" id="soal_tiu" name="soal_tiu" min="0" max="200" value="<?= (int) $tryoutSettings['soal_tiu'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="soal_tkp">Soal TKP</label>
                  <input type="number" class="form-input tryout-count-input" id="soal_tkp" name="soal_tkp" min="0" max="200" value="<?= (int) $tryoutSettings['soal_tkp'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="passing_twk">Passing Grade TWK</label>
                  <input type="number" class="form-input" id="passing_twk" name="passing_twk" min="0" max="500" value="<?= (int) $tryoutSettings['passing_twk'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="passing_tiu">Passing Grade TIU</label>
                  <input type="number" class="form-input" id="passing_tiu" name="passing_tiu" min="0" max="500" value="<?= (int) $tryoutSettings['passing_tiu'] ?>" required>
                </div>
                <div class="form-group">
                  <label class="form-label" for="passing_tkp">Passing Grade TKP</label>
                  <input type="number" class="form-input" id="passing_tkp" name="passing_tkp" min="0" max="500" value="<?= (int) $tryoutSettings['passing_tkp'] ?>" required>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="acak_soal">Acak Soal Otomatis</label>
                  <select class="form-select" id="acak_soal" name="acak_soal">
                    <option value="1" <?= (int) $tryoutSettings['acak_soal'] === 1 ? 'selected' : '' ?>>Ya - Soal diacak per peserta</option>
                    <option value="0" <?= (int) $tryoutSettings['acak_soal'] === 0 ? 'selected' : '' ?>>Tidak - Urutan soal tetap</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label" for="acak_opsi">Acak Opsi Jawaban</label>
                  <select class="form-select" id="acak_opsi" name="acak_opsi">
                    <option value="0" <?= (int) $tryoutSettings['acak_opsi'] === 0 ? 'selected' : '' ?>>Tidak - Urutan opsi tetap</option>
                    <option value="1" <?= (int) $tryoutSettings['acak_opsi'] === 1 ? 'selected' : '' ?>>Ya - Opsi ikut diacak</option>
                  </select>
                </div>
              </div>
              <button class="btn btn-primary" type="submit"><i class="bi bi-check-circle"></i> Simpan Konfigurasi</button>
            </form>
          </div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:15px;">
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-lock-fill"></i> Ubah Kata Sandi</div></div>
          <div style="padding:14px;">
            <?php if (!$adminAccount): ?>
              <div style="padding:12px;border:1px solid rgba(239,68,68,.35);border-radius:8px;background:#FEF2F2;color:#991B1B;font-size:12px;font-weight:700;">
                Akun admin belum tersedia.
              </div>
            <?php else: ?>
              <form method="post" action="<?= BASE_URL ?>/Admin/pengaturan/password" id="formAdminPassword">
                <?= csrf_field() ?>
                <input type="hidden" name="id_user" value="<?= (int) $adminAccount['id_user'] ?>">
                <div class="form-group">
                  <label class="form-label" for="password_lama">Kata Sandi Lama <span class="req">*</span></label>
                  <input type="password" class="form-input" id="password_lama" name="password_lama" placeholder="Password saat ini" required autocomplete="current-password">
                </div>
                <div class="form-group">
                  <label class="form-label" for="password_baru">Kata Sandi Baru <span class="req">*</span></label>
                  <input type="password" class="form-input" id="password_baru" name="password_baru" placeholder="Minimal 8 karakter" minlength="8" maxlength="72" required autocomplete="new-password">
                </div>
                <div class="form-group">
                  <label class="form-label" for="konfirmasi_password">Konfirmasi <span class="req">*</span></label>
                  <input type="password" class="form-input" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password baru" minlength="8" maxlength="72" required autocomplete="new-password">
                </div>
                <button class="btn btn-ghost" type="submit" style="width:100%;justify-content:center;"><i class="bi bi-shield-lock"></i> Ubah Kata Sandi</button>
              </form>
              <div style="font-size:10.8px;color:var(--ash);line-height:1.5;margin-top:9px;">
                Untuk tahap development, password admin saat ini diset ke <strong>admin123</strong>.
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-info-circle-fill"></i> Info Sistem</div></div>
          <div style="padding:4px 0;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px 16px;border-bottom:1px solid var(--smoke);">
              <div style="background:var(--cloud);border-radius:8px;padding:9px 10px;">
                <div style="font-size:10.5px;color:var(--ash);">Peserta</div>
                <strong style="font-size:15px;color:var(--ink);"><?= (int) ($systemInfo['total_peserta'] ?? 0) ?></strong>
                <div style="font-size:10.5px;color:var(--emerald);"><?= (int) ($systemInfo['peserta_aktif'] ?? 0) ?> aktif</div>
              </div>
              <div style="background:var(--cloud);border-radius:8px;padding:9px 10px;">
                <div style="font-size:10.5px;color:var(--ash);">Soal</div>
                <strong style="font-size:15px;color:var(--ink);"><?= (int) ($systemInfo['total_soal'] ?? 0) ?></strong>
                <div style="font-size:10.5px;color:var(--emerald);"><?= (int) ($systemInfo['soal_aktif'] ?? 0) ?> aktif</div>
              </div>
              <div style="background:var(--cloud);border-radius:8px;padding:9px 10px;">
                <div style="font-size:10.5px;color:var(--ash);">Tryout</div>
                <strong style="font-size:15px;color:var(--ink);"><?= (int) ($systemInfo['total_tryout'] ?? 0) ?></strong>
                <div style="font-size:10.5px;color:var(--blue-main);"><?= (int) ($systemInfo['tryout_aktif'] ?? 0) ?> aktif</div>
              </div>
              <div style="background:var(--cloud);border-radius:8px;padding:9px 10px;">
                <div style="font-size:10.5px;color:var(--ash);">Hasil</div>
                <strong style="font-size:15px;color:var(--ink);"><?= (int) ($systemInfo['total_hasil'] ?? 0) ?></strong>
                <div style="font-size:10.5px;color:var(--ash);">selesai/timeout</div>
              </div>
            </div>

            <?php $rowIndex = 0; ?>
            <?php foreach ($infoRows as $label => $value): ?>
              <?php $rowIndex++; ?>
              <div style="display:flex;justify-content:space-between;gap:12px;padding:9px 16px;<?= $rowIndex < count($infoRows) ? 'border-bottom:1px solid var(--smoke);' : '' ?>font-size:12px;">
                <span style="color:var(--ash);"><?= htmlspecialchars($label) ?></span>
                <strong style="text-align:right;color:var(--ink);word-break:break-word;"><?= htmlspecialchars((string) $value) ?></strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const countInputs = document.querySelectorAll('.tryout-count-input');
  const totalInput = document.getElementById('jumlah_soal_per_sesi');

  function syncTotalQuestions() {
    let total = 0;
    countInputs.forEach((input) => {
      total += Math.max(0, parseInt(input.value || '0', 10));
    });
    totalInput.value = total;
  }

  countInputs.forEach((input) => input.addEventListener('input', syncTotalQuestions));
  syncTotalQuestions();
</script>
