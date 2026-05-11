<aside class="sidebar" id="sidebar">
  <div class="sb-header">
    <div class="sb-logo-row">
      <div class="sb-logo-mark"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="sb-brand-name">Oman's<span> Club</span></div>
    </div>
    <div class="sb-portal-tag">Portal Peserta</div>
  </div>
  <div class="sb-divider"></div>
  <nav class="sb-nav">
    <div class="sb-section-label">Menu Utama</div>
    <div class="nav-item active" onclick="nav('dashboard')">
      <div class="nav-icon"><i class="bi bi-grid-fill"></i></div> Dashboard
    </div>
    <div class="nav-item" onclick="nav('tryout')">
      <div class="nav-icon"><i class="bi bi-journal-text"></i></div> Ikuti Tryout
      <span class="nav-badge">3</span>
    </div>
    <div class="nav-item" onclick="nav('riwayat')">
      <div class="nav-icon"><i class="bi bi-clock-history"></i></div> Riwayat
    </div>
    <div class="nav-item" onclick="nav('analisis')">
      <div class="nav-icon"><i class="bi bi-bar-chart-line-fill"></i></div> Analisis
    </div>
    <div class="sb-section-label" style="margin-top:8px;">Akun</div>
    <div class="nav-item" onclick="nav('profil')">
      <div class="nav-icon"><i class="bi bi-person-fill"></i></div> Profil Saya
    </div>
    <div class="nav-item danger" onclick="konfirmKeluar()">
      <div class="nav-icon"><i class="bi bi-box-arrow-left"></i></div> Keluar
    </div>
  </nav>
  <div class="sb-footer">
    <div class="sb-user-card">
      <div class="sb-avatar">R</div>
      <div class="sb-user-info">
        <div class="sb-user-name">Rafi Firmansyah</div>
        <div class="sb-user-role">Peserta Aktif</div>
      </div>
      <div class="sb-logout-btn" onclick="konfirmKeluar()"><i class="bi bi-power"></i></div>
    </div>
  </div>
</aside>