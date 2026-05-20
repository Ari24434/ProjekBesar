 <div class="page active" id="pg-dashboard">
    <div class="page-body">
      <!-- Admin Banner -->
      <div class="admin-banner anim">
        <div class="ab-grid"></div>
        <div class="ab-glow-1"></div>
        <div class="ab-glow-2"></div>
        <div class="ab-content">
          <div class="ab-left">
            <div class="ab-greeting">Senin, 11 Mei 2026 · Panel Administrator</div>
            <div class="ab-name">Selamat Datang, Admin 👋</div>
            <div class="ab-sub">Kelola peserta, soal, dan tryout Oman's Club Academy dari sini.</div>
            <div class="ab-pills">
              <div class="ab-pill"><i class="bi bi-people-fill" style="color:#93C5FD;font-size:10px;"></i> 60 Peserta Aktif</div>
              <div class="ab-pill"><i class="bi bi-journal-text" style="color:#FCD34D;font-size:10px;"></i> 3 Tryout Berjalan</div>
              <div class="ab-pill"><i class="bi bi-question-circle-fill" style="color:#6EE7B7;font-size:10px;"></i> 330 Soal Tersedia</div>
            </div>
          </div>
          <div class="ab-right">
            <button class="btn btn-gold" onclick="openModal('modal-add-tryout')">
              <i class="bi bi-plus-circle-fill"></i> Buat Tryout Baru
            </button>
            <button class="btn" onclick="nav('laporan')" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:#fff;">
              <i class="bi bi-download"></i> Ekspor Laporan
            </button>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 320px;gap:15px;" class="anim anim-d3">
        <!-- Tryout terbaru -->
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="bi bi-journal-text"></i> Tryout Terkini</div>
            <button class="card-action" onclick="nav('tryout')">Kelola semua →</button>
          </div>
          <div class="tryout-item">
            <div class="ti-icon" style="background:var(--frost);color:var(--blue-main);"><i class="bi bi-journal-text"></i></div>
            <div style="flex:1;min-width:0;">
              <div class="ti-title">Sesi 3 — Simulasi Penuh <span class="badge badge-ongoing" style="font-size:10px;">Berjalan</span></div>
              <div class="ti-meta"><i class="bi bi-calendar3" style="font-size:10px;"></i> 15 Mei 2026 · 110 soal · <strong style="color:var(--blue-main);">42 peserta terdaftar</strong></div>
            </div>
            <div class="ti-actions">
              <button class="btn btn-ghost btn-sm" onclick="nav('nilai')"><i class="bi bi-bar-chart"></i> Hasil</button>
              <button class="btn btn-ghost btn-sm" onclick="editTryout(3)"><i class="bi bi-pencil"></i></button>
            </div>
          </div>
          <div class="tryout-item">
            <div class="ti-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-journal-check"></i></div>
            <div style="flex:1;min-width:0;">
              <div class="ti-title">Sesi 2 — Latihan TWK <span class="badge badge-done" style="font-size:10px;">Selesai</span></div>
              <div class="ti-meta"><i class="bi bi-calendar3" style="font-size:10px;"></i> 8 Mei 2026 · 110 soal · <strong style="color:var(--emerald);">60 peserta selesai</strong></div>
            </div>
            <div class="ti-actions">
              <button class="btn btn-ghost btn-sm" onclick="nav('nilai')"><i class="bi bi-bar-chart"></i> Hasil</button>
              <button class="btn btn-ghost btn-sm" onclick="editTryout(2)"><i class="bi bi-pencil"></i></button>
            </div>
          </div>
          <div class="tryout-item">
            <div class="ti-icon" style="background:#ECFDF5;color:var(--emerald);"><i class="bi bi-journal-check"></i></div>
            <div style="flex:1;min-width:0;">
              <div class="ti-title">Sesi 1 — Pembukaan <span class="badge badge-done" style="font-size:10px;">Selesai</span></div>
              <div class="ti-meta"><i class="bi bi-calendar3" style="font-size:10px;"></i> 1 Mei 2026 · 110 soal · <strong style="color:var(--emerald);">60 peserta selesai</strong></div>
            </div>
            <div class="ti-actions">
              <button class="btn btn-ghost btn-sm" onclick="nav('nilai')"><i class="bi bi-bar-chart"></i> Hasil</button>
              <button class="btn btn-ghost btn-sm" onclick="editTryout(1)"><i class="bi bi-pencil"></i></button>
            </div>
          </div>
          <div style="padding:12px 18px;border-top:1px solid var(--smoke);">
            <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-tryout')"><i class="bi bi-plus-circle-fill"></i> Buat Tryout Baru</button>
          </div>
        </div>

        <!-- Aktivitas terbaru -->
        <div class="card">
          <div class="card-head">
            <div class="card-title"><i class="bi bi-activity"></i> Aktivitas Terbaru</div>
          </div>
          <div style="padding:14px 18px;">
            <div class="activity-item">
              <div class="act-dot blue"><i class="bi bi-person-plus-fill"></i></div>
              <div><div class="act-text"><span>Budi Santoso</span> mendaftar sebagai peserta baru</div><div class="act-time">5 menit lalu</div></div>
            </div>
            <div class="activity-item">
              <div class="act-dot green"><i class="bi bi-check-circle-fill"></i></div>
              <div><div class="act-text"><span>Rafi Firmansyah</span> menyelesaikan Sesi 2 · nilai 355</div><div class="act-time">23 menit lalu</div></div>
            </div>
            <div class="activity-item">
              <div class="act-dot gold"><i class="bi bi-question-circle-fill"></i></div>
              <div><div class="act-text">20 soal baru ditambahkan ke Bank Soal <span>TWK</span></div><div class="act-time">1 jam lalu</div></div>
            </div>
            <div class="activity-item">
              <div class="act-dot blue"><i class="bi bi-journal-plus"></i></div>
              <div><div class="act-text">Tryout <span>Sesi 3</span> berhasil dibuat dan dipublikasi</div><div class="act-time">2 jam lalu</div></div>
            </div>
            <div class="activity-item">
              <div class="act-dot green"><i class="bi bi-patch-check-fill"></i></div>
              <div><div class="act-text"><span>Dewi Rahayu</span> lulus SKD dengan nilai 340</div><div class="act-time">3 jam lalu</div></div>
            </div>
            <div class="activity-item">
              <div class="act-dot red"><i class="bi bi-person-x-fill"></i></div>
              <div><div class="act-text">Akun <span>Ahmad Fauzi</span> dinonaktifkan oleh admin</div><div class="act-time">kemarin</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>