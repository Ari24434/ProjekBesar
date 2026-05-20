 <div class="page active" id="pg-pengaturan">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Pengaturan Sistem</h2>
        <p>Konfigurasi akun administrator dan sistem tryout.</p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 330px;gap:15px;" class="anim anim-d1">
        <div style="display:flex;flex-direction:column;gap:15px;">
          <div class="card">
            <div class="card-head"><div class="card-title"><i class="bi bi-person-fill"></i> Informasi Administrator</div></div>
            <div style="padding:18px;">
              <div class="form-row">
                <div class="form-group"><label class="form-label">Nama Admin</label><input type="text" class="form-input" value="Admin Oman's"/></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" value="admin@omanclub.ac.id"/></div>
                <div class="form-group"><label class="form-label">Nomor HP</label><input type="tel" class="form-input" value="+62 812-0000-0001"/></div>
                <div class="form-group"><label class="form-label">Role</label><input type="text" class="form-input" value="Super Administrator" disabled/></div>
              </div>
              <button class="btn btn-primary" onclick="showToast('Profil admin berhasil disimpan','success')"><i class="bi bi-check-lg"></i> Simpan Perubahan</button>
            </div>
          </div>

          <div class="card">
            <div class="card-head"><div class="card-title"><i class="bi bi-sliders"></i> Konfigurasi Tryout</div></div>
            <div style="padding:18px;">
              <div class="form-row">
                <div class="form-group"><label class="form-label">Durasi Default (menit)</label><input type="number" class="form-input" value="100"/></div>
                <div class="form-group"><label class="form-label">Jumlah Soal per Sesi</label><input type="number" class="form-input" value="110"/></div>
                <div class="form-group"><label class="form-label">Soal TWK</label><input type="number" class="form-input" value="35"/></div>
                <div class="form-group"><label class="form-label">Soal TIU</label><input type="number" class="form-input" value="35"/></div>
                <div class="form-group"><label class="form-label">Soal TKP</label><input type="number" class="form-input" value="40"/></div>
                <div class="form-group"><label class="form-label">Passing Grade TWK</label><input type="number" class="form-input" value="65"/></div>
                <div class="form-group"><label class="form-label">Passing Grade TIU</label><input type="number" class="form-input" value="80"/></div>
                <div class="form-group"><label class="form-label">Passing Grade TKP</label><input type="number" class="form-input" value="166"/></div>
              </div>
              <div class="form-group" style="margin-top:4px;">
                <label class="form-label">Acak Soal Otomatis</label>
                <select class="form-select">
                  <option selected>Ya — Soal diacak per peserta</option>
                  <option>Tidak — Urutan soal tetap</option>
                </select>
              </div>
              <button class="btn btn-primary" onclick="showToast('Konfigurasi berhasil disimpan','success')"><i class="bi bi-check-lg"></i> Simpan Konfigurasi</button>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:15px;">
          <div class="card">
            <div class="card-head"><div class="card-title"><i class="bi bi-lock-fill"></i> Ubah Kata Sandi</div></div>
            <div style="padding:14px;">
              <div class="form-group"><label class="form-label">Kata Sandi Lama</label><input type="password" class="form-input" placeholder="••••••••"/></div>
              <div class="form-group"><label class="form-label">Kata Sandi Baru</label><input type="password" class="form-input" placeholder="••••••••"/></div>
              <div class="form-group"><label class="form-label">Konfirmasi</label><input type="password" class="form-input" placeholder="••••••••"/></div>
              <button class="btn btn-ghost" style="width:100%;justify-content:center;" onclick="showToast('Kata sandi berhasil diubah','success')"><i class="bi bi-shield-lock"></i> Ubah Kata Sandi</button>
            </div>
          </div>
          <div class="card">
            <div class="card-head"><div class="card-title"><i class="bi bi-info-circle-fill"></i> Info Sistem</div></div>
            <div style="padding:4px 0;">
              <div style="display:flex;justify-content:space-between;padding:9px 16px;border-bottom:1px solid var(--smoke);font-size:12px;"><span style="color:var(--ash);">Versi Sistem</span><strong>v1.0.0</strong></div>
              <div style="display:flex;justify-content:space-between;padding:9px 16px;border-bottom:1px solid var(--smoke);font-size:12px;"><span style="color:var(--ash);">Total Peserta</span><strong>60</strong></div>
              <div style="display:flex;justify-content:space-between;padding:9px 16px;border-bottom:1px solid var(--smoke);font-size:12px;"><span style="color:var(--ash);">Total Soal</span><strong>330</strong></div>
              <div style="display:flex;justify-content:space-between;padding:9px 16px;border-bottom:1px solid var(--smoke);font-size:12px;"><span style="color:var(--ash);">Total Tryout</span><strong>5 sesi</strong></div>
              <div style="display:flex;justify-content:space-between;padding:9px 16px;font-size:12px;"><span style="color:var(--ash);">Backup Terakhir</span><strong>11 Mei 2026</strong></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>