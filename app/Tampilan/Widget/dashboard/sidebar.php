<aside class="sidebar" id="sidebar">
  <div class="sb-header">
    <div class="sb-logo-row">
      <div class="sb-logo-mark"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="sb-brand-name">Oman's<span> Club</span></div>
    </div>
    <div class="sb-portal-tag">Portal Administrator</div>
  </div>
  <div class="sb-divider"></div>
  <nav class="sb-nav">
    <div class="sb-section-label">Menu Utama</div>
    <div class="nav-item active" onclick="nav('dashboard')">
      <div class="nav-icon"><i class="bi bi-grid-fill"></i></div> Dashboard
    </div>
    <div class="nav-item" onclick="nav('peserta')">
      <div class="nav-icon"><i class="bi bi-people-fill"></i></div> Manajemen Peserta
      <span class="nav-badge" id="badge-peserta">60</span>
    </div>
    <div class="nav-item" onclick="nav('tryout')">
      <div class="nav-icon"><i class="bi bi-journal-text"></i></div> Manajemen Tryout
    </div>
    <div class="nav-item" onclick="nav('soal')">
      <div class="nav-icon"><i class="bi bi-question-circle-fill"></i></div> Bank Soal
      <span class="nav-badge gold" id="badge-soal">330</span>
    </div>
    <div class="sb-section-label" style="margin-top:8px;">Laporan</div>
    <div class="nav-item" onclick="nav('nilai')">
      <div class="nav-icon"><i class="bi bi-bar-chart-fill"></i></div> Nilai & Hasil
    </div>
    <div class="nav-item" onclick="nav('laporan')">
      <div class="nav-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></div> Laporan Rekap
    </div>
    <div class="sb-section-label" style="margin-top:8px;">Sistem</div>
    <div class="nav-item" onclick="nav('pengaturan')">
      <div class="nav-icon"><i class="bi bi-gear-fill"></i></div> Pengaturan
    </div>
    <div class="nav-item danger" onclick="konfirmKeluar()">
      <div class="nav-icon"><i class="bi bi-box-arrow-left"></i></div> Keluar
    </div>
  </nav>
  <div class="sb-footer">
    <div class="sb-user-card">
      <div class="sb-avatar">A</div>
      <div class="sb-user-info">
        <div class="sb-user-name">Admin Oman's</div>
        <div class="sb-user-role">Super Administrator</div>
      </div>
      <div class="sb-logout-btn" onclick="konfirmKeluar()"><i class="bi bi-power"></i></div>
    </div>
  </div>
</aside>