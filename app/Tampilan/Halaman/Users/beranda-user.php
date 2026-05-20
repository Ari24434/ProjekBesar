  <div class="page active" id="pg-dashboard">
    <div class="page-body">
      <!-- Welcome -->
      <div class="welcome-banner anim">
        <div class="wb-grid"></div>
        <div class="wb-glow"></div>
        <div class="wb-content">
          <div>
            <div class="wb-greeting">Senin, 11 Mei 2026</div>
            <div class="wb-name">Halo, Rafi! 👋</div>
            <div class="wb-sub">Kamu sudah menyelesaikan <strong>5 tryout</strong>. Terus semangat!</div>
            <div class="wb-pills">
              <div class="wb-pill"><i class="bi bi-patch-check-fill" style="color:#6EE7B7;font-size:10px;"></i> 4 sesi lulus</div>
              <div class="wb-pill"><i class="bi bi-trophy-fill" style="color:#FCD34D;font-size:10px;"></i> Nilai terbaik: 355</div>
              <div class="wb-pill"><i class="bi bi-graph-up" style="color:#93C5FD;font-size:10px;"></i> +12 dari sesi sebelumnya</div>
            </div>
          </div>
          <button class="btn btn-lg" onclick="nav('tryout')" style="background:rgba(255,255,255,.11);border:1.5px solid rgba(255,255,255,.2);color:#fff;flex-shrink:0;">
            <i class="bi bi-play-circle-fill" style="color:#93C5FD;"></i> Mulai Tryout
          </button>
        </div>
      </div>

      <!-- Stats -->
      <div class="stat-grid anim anim-d1" style="margin-bottom:18px;">
        <div class="stat-block c-blue">
          <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-journal-check"></i></div><div class="stat-change up"><i class="bi bi-arrow-up-short"></i>2 bulan ini</div></div>
          <div class="stat-number">5</div><div class="stat-label">Tryout Diikuti</div>
        </div>
        <div class="stat-block c-gold">
          <div class="stat-top"><div class="stat-icon gold"><i class="bi bi-trophy-fill"></i></div><div class="stat-change up"><i class="bi bi-check2"></i>Di atas PG</div></div>
          <div class="stat-number">355</div><div class="stat-label">Nilai Terbaik</div>
        </div>
        <div class="stat-block c-blue">
          <div class="stat-top"><div class="stat-icon blue"><i class="bi bi-graph-up"></i></div><div class="stat-change up"><i class="bi bi-arrow-up-short"></i>+12</div></div>
          <div class="stat-number">334</div><div class="stat-label">Rata-rata Nilai</div>
        </div>
        <div class="stat-block c-green">
          <div class="stat-top"><div class="stat-icon green"><i class="bi bi-patch-check-fill"></i></div><div class="stat-change neutral">dari 5 sesi</div></div>
          <div class="stat-number">4</div><div class="stat-label">Sesi Lulus</div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;" class="anim anim-d2">
        <!-- Skor terakhir -->
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="bi bi-speedometer2"></i> Skor Terakhir</div>
            <span class="badge badge-pass">✓ Lulus</span>
          </div>
          <div style="padding:18px;">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
              <div style="position:relative;width:86px;height:86px;flex-shrink:0;">
                <svg width="86" height="86" viewBox="0 0 86 86" style="transform:rotate(-90deg);">
                  <circle cx="43" cy="43" r="34" fill="none" stroke="var(--smoke)" stroke-width="7"/>
                  <circle cx="43" cy="43" r="34" fill="none" stroke="var(--blue-main)" stroke-width="7" stroke-linecap="round" stroke-dasharray="150 214" stroke-dashoffset="0"/>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                  <div style="font-family:'Playfair Display',serif;font-size:17px;font-weight:700;color:var(--ink);line-height:1;">355</div>
                  <div style="font-size:9px;color:var(--ash);margin-top:1px;">/ 500</div>
                </div>
              </div>
              <div>
                <div style="font-size:12.5px;font-weight:600;color:var(--emerald);margin-bottom:4px;">✓ Di Atas Passing Grade</div>
                <div style="font-size:11.5px;color:var(--ash);line-height:1.65;">Passing grade: <strong style="color:var(--ink);">311</strong> poin<br/>Selisih: <strong style="color:var(--emerald);">+44 poin</strong></div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:11px;">
              <div>
                <div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:4px;">
                  <span style="font-weight:600;color:var(--slate);">TWK <span style="font-weight:400;color:var(--ash);">min.65</span></span>
                  <strong style="color:var(--blue-main);">85</strong>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:57%;background:var(--blue-main);"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:4px;">
                  <span style="font-weight:600;color:var(--slate);">TIU <span style="font-weight:400;color:var(--ash);">min.80</span></span>
                  <strong style="color:var(--emerald);">95</strong>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:54%;background:var(--emerald);"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:11.5px;margin-bottom:4px;">
                  <span style="font-weight:600;color:var(--slate);">TKP <span style="font-weight:400;color:var(--ash);">min.166</span></span>
                  <strong style="color:var(--gold);">175</strong>
                </div>
                <div class="progress-bar"><div class="progress-fill" style="width:78%;background:var(--gold);"></div></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tryout tersedia -->
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="bi bi-calendar3"></i> Tryout Tersedia</div>
            <span class="card-action" onclick="nav('tryout')">Semua →</span>
          </div>
          <div class="tryout-row">
            <div class="tr-icon" style="background:var(--frost);color:var(--blue-main);"><i class="bi bi-journal-text"></i></div>
            <div>
              <div class="tr-title">Sesi 3 — Simulasi Penuh <span class="badge badge-new" style="font-size:10px;">Baru</span></div>
              <div class="tr-meta"><i class="bi bi-clock" style="font-size:10px;"></i> 100 menit · 110 soal · 15 Mei</div>
            </div>
            <div class="tr-action"><button class="btn btn-primary btn-sm" onclick="nav('exam')"><i class="bi bi-play-fill"></i> Mulai</button></div>
          </div>
          <div class="tryout-row">
            <div class="tr-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-journal-check"></i></div>
            <div>
              <div class="tr-title">Sesi 2 — Latihan TWK</div>
              <div class="tr-meta"><i class="bi bi-clock" style="font-size:10px;"></i> 100 menit · 110 soal · 8 Mei</div>
            </div>
            <div class="tr-action"><span class="badge badge-done">✓ Selesai</span></div>
          </div>
          <div class="tryout-row">
            <div class="tr-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-journal-check"></i></div>
            <div>
              <div class="tr-title">Sesi 1 — Pembukaan</div>
              <div class="tr-meta"><i class="bi bi-clock" style="font-size:10px;"></i> 100 menit · 110 soal · 1 Mei</div>
            </div>
            <div class="tr-action"><span class="badge badge-done">✓ Selesai</span></div>
          </div>
        </div>
      </div>

      <!-- Riwayat singkat -->
      <div class="card anim anim-d3" style="margin-top:15px;">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-clock-history"></i> Riwayat Terakhir</div>
          <span class="card-action" onclick="nav('riwayat')">Lihat semua →</span>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table">
            <thead><tr><th>Tryout</th><th>TWK</th><th>TIU</th><th>TKP</th><th>Total</th><th>Status</th><th>Tanggal</th></tr></thead>
            <tbody>
              <tr><td style="font-weight:600;">Sesi 2 – Latihan TWK</td><td>85</td><td>95</td><td>175</td><td style="font-weight:700;color:var(--blue-main);">355</td><td><span class="badge badge-pass">✓ Lulus</span></td><td style="color:var(--ash);">08 Mei 2026</td></tr>
              <tr><td style="font-weight:600;">Sesi 1 – Pembukaan</td><td>80</td><td>90</td><td>170</td><td style="font-weight:700;color:var(--blue-main);">340</td><td><span class="badge badge-pass">✓ Lulus</span></td><td style="color:var(--ash);">01 Mei 2026</td></tr>
              <tr><td style="font-weight:600;">Latihan Mandiri #3</td><td>65</td><td>80</td><td>160</td><td style="font-weight:700;color:var(--crimson);">305</td><td><span class="badge badge-fail">✗ Belum</span></td><td style="color:var(--ash);">24 Apr 2026</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>