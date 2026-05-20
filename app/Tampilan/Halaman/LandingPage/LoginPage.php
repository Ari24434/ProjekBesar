<div class="d-flex vh-100">
  <div class="left-panel">
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>
  <div class="blob blob-3"></div>

  <!-- Brand -->
  <div class="lp-brand">
   <a class="d-flex gap-2 align-items-center" href="<?= BASE_URL ?>">
     <div class="lp-brand-icon"><i class="bi bi-mortarboard-fill text-white" style="font-size:.95rem;"></i></div>
    <span class="lp-brand-text">Oman's <span>Club</span> Academy</span>
   </a>
  </div>

  <!-- Center -->
  <div class="lp-center">
    <div class="lp-tagline-badge"><i class="bi bi-shield-check-fill"></i> Platform Tryout CPNS Terpercaya</div>
    <h2 class="lp-heading">Satu Langkah Lebih<br/>Dekat Menuju<br/><span class="acc">Karir PNS</span></h2>
    <p class="lp-desc">Masuk ke akun kamu dan mulai berlatih dengan ribuan soal SKD CPNS yang terstruktur dan realistis.</p>
    <div class="lp-stats">
      <div class="lp-stat-pill">
        <div class="lp-stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div>
          <div class="lp-stat-val">60+ Peserta Aktif</div>
          <div class="lp-stat-lbl">Bergabung dan berlatih bersama</div>
        </div>
      </div>
      <div class="lp-stat-pill">
        <div class="lp-stat-icon green"><i class="bi bi-patch-check-fill"></i></div>
        <div>
          <div class="lp-stat-val">75% Tingkat Kelulusan</div>
          <div class="lp-stat-lbl">Peserta kami lolos SKD CPNS 2024</div>
        </div>
      </div>
      <div class="lp-stat-pill">
        <div class="lp-stat-icon gold"><i class="bi bi-lightning-charge-fill"></i></div>
        <div>
          <div class="lp-stat-val">Penilaian Otomatis</div>
          <div class="lp-stat-lbl">Hasil langsung keluar setelah tryout</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer note -->
  <div class="lp-footer-note">© 2026 Oman's Club Academy</div>
</div>

<!-- ══════════ RIGHT PANEL ══════════ -->
<div class="right-panel">
  <div class="login-box">

    <!-- Header -->
    <div class="login-header">
      <div class="login-welcome">Selamat Datang Kembali</div>
      <h1 class="login-title">Masuk ke Akun<br/>Kamu</h1>
    </div>


    <!-- Form -->
    <form id="loginForm" onsubmit="handleLogin(event)" novalidate>

      <!-- Email -->
      <div>
        <label class="field-label" for="email">Alamat Email</label>
        <div class="field-wrap">
          <i class="bi bi-envelope field-icon"></i>
          <input type="email" id="email" class="field-input" placeholder="contoh@email.com" autocomplete="email" required/>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="field-label" for="password">Kata Sandi</label>
        <div class="field-wrap">
          <i class="bi bi-lock field-icon"></i>
          <input type="password" id="password" class="field-input has-toggle" placeholder="Masukkan kata sandi" autocomplete="current-password" required/>
          <button type="button" class="field-toggle" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
        </div>
      </div>

      <!-- Error -->
      <div class="error-box" id="errorBox">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span id="errorTxt">Email atau kata sandi salah. Silakan coba lagi.</span>
      </div>

      <!-- Submit -->
      <a href="<?= BASE_URL; ?>/user/beranda" type="submit" class="btn-submit" id="submitBtn">
        <i class="bi bi-box-arrow-in-right"></i>
        <span id="btnTxt">Masuk Sekarang</span>
      </a>
    </form>


    <!-- Register -->
    <div class="reg-link">Belum punya akun? <a href="https://www.instagram.com/omansclub" target="_blank">Daftar di sini</a></div>

    <!-- Security -->
    <div class="sec-note"><i class="bi bi-shield-lock-fill" style="color:var(--emerald);font-size:.8rem;"></i> Koneksi aman — data kamu terenkripsi SSL</div>

  </div>
</div>
</div>