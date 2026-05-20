 <div class="page active" id="pg-tryout">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Manajemen Tryout</h2>
        <p>Buat, atur jadwal, dan kelola sesi tryout CPNS.</p>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:16px;" class="anim anim-d1">
        <div class="filter-bar" style="margin-bottom:0;">
          <button class="filter-btn active" onclick="setFilter(this)">Semua (5)</button>
          <button class="filter-btn" onclick="setFilter(this)">Berjalan (3)</button>
          <button class="filter-btn" onclick="setFilter(this)">Selesai (1)</button>
          <button class="filter-btn" onclick="setFilter(this)">Draft (1)</button>
        </div>
        <button class="btn btn-primary" onclick="openModal('modal-add-tryout')">
          <i class="bi bi-plus-circle-fill"></i> Buat Tryout Baru
        </button>
      </div>

      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(310px,1fr));gap:15px;" class="anim anim-d2">
        <!-- Card Tryout -->
        <div class="card" style="border-top:3px solid var(--blue-main);">
          <div style="padding:16px 18px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
              <span class="badge badge-ongoing"><i class="bi bi-broadcast"></i> Berjalan</span>
              <div style="display:flex;gap:5px;">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editTryout(3)" title="Edit"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="hapusTryout(3)" title="Hapus"><i class="bi bi-trash"></i></button>
              </div>
            </div>
            <div style="font-weight:700;font-size:14.5px;color:var(--ink);margin-bottom:2px;">Tryout SKD — Sesi 3</div>
            <div style="font-size:12px;color:var(--slate);font-style:italic;margin-bottom:11px;">Simulasi Penuh TWK + TIU + TKP</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Peserta</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;">42 <span style="font-size:10px;color:var(--ash);font-weight:400;">/ 60</span></div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Soal</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;">110</div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Durasi</div>
                <div style="font-weight:700;color:var(--ink);font-size:14px;">100 <span style="font-size:10px;color:var(--ash);font-weight:400;">menit</span></div>
              </div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;">
                <div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Jadwal</div>
                <div style="font-weight:700;color:var(--ink);font-size:12px;">15 Mei 2026</div>
              </div>
            </div>
            <div style="margin-bottom:13px;">
              <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--ash);margin-bottom:4px;"><span>Progress peserta</span><span>42/60</span></div>
              <div class="progress-bar"><div class="progress-fill" style="width:70%;background:var(--blue-main);"></div></div>
            </div>
            <div style="display:flex;gap:7px;">
              <button class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;" onclick="nav('nilai')"><i class="bi bi-bar-chart"></i> Lihat Hasil</button>
              <button class="btn btn-primary btn-sm" style="flex:1;justify-content:center;" onclick="editTryout(3)"><i class="bi bi-pencil"></i> Edit</button>
            </div>
          </div>
        </div>

        <div class="card" style="border-top:3px solid var(--emerald);">
          <div style="padding:16px 18px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
              <span class="badge badge-done">✓ Selesai</span>
              <div style="display:flex;gap:5px;">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editTryout(2)"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="hapusTryout(2)"><i class="bi bi-trash"></i></button>
              </div>
            </div>
            <div style="font-weight:700;font-size:14.5px;color:var(--ink);margin-bottom:2px;">Tryout SKD — Sesi 2</div>
            <div style="font-size:12px;color:var(--slate);font-style:italic;margin-bottom:11px;">Latihan TWK</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Peserta</div><div style="font-weight:700;color:var(--ink);font-size:14px;">60 <span style="font-size:10px;color:var(--ash);font-weight:400;">/ 60</span></div></div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Rata-rata</div><div style="font-weight:700;color:var(--emerald);font-size:14px;">334</div></div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Durasi</div><div style="font-weight:700;color:var(--ink);font-size:14px;">100 <span style="font-size:10px;color:var(--ash);font-weight:400;">menit</span></div></div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Tingkat Lulus</div><div style="font-weight:700;color:var(--emerald);font-size:14px;">68%</div></div>
            </div>
            <div style="display:flex;gap:7px;">
              <button class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;" onclick="nav('nilai')"><i class="bi bi-bar-chart"></i> Lihat Hasil</button>
              <button class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;" onclick="editTryout(2)"><i class="bi bi-pencil"></i> Edit</button>
            </div>
          </div>
        </div>

        <div class="card" style="border-top:3px solid var(--ash);opacity:.85;">
          <div style="padding:16px 18px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
              <span class="badge badge-draft"><i class="bi bi-pencil-square"></i> Draft</span>
              <div style="display:flex;gap:5px;">
                <button class="btn btn-ghost btn-sm btn-icon" onclick="editTryout(4)"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm btn-icon" onclick="hapusTryout(4)"><i class="bi bi-trash"></i></button>
              </div>
            </div>
            <div style="font-weight:700;font-size:14.5px;color:var(--ink);margin-bottom:2px;">Tryout SKD — Sesi 4</div>
            <div style="font-size:12px;color:var(--slate);font-style:italic;margin-bottom:11px;">Simulasi TIU + TKP</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Soal</div><div style="font-weight:700;color:var(--ash);font-size:14px;">0 / 110</div></div>
              <div style="background:var(--cloud);border-radius:var(--r-md);padding:8px 10px;"><div style="font-size:10px;color:var(--ash);margin-bottom:2px;">Jadwal</div><div style="font-weight:700;color:var(--ash);font-size:12px;">Belum Diatur</div></div>
            </div>
            <div style="display:flex;gap:7px;">
              <button class="btn btn-primary btn-sm" style="flex:1;justify-content:center;" onclick="editTryout(4)"><i class="bi bi-pencil-square"></i> Lanjutkan Draft</button>
            </div>
          </div>
        </div>

        <!-- Tambah Baru Card -->
        <div class="card" style="border:2px dashed var(--smoke);cursor:pointer;min-height:200px;" onclick="openModal('modal-add-tryout')">
          <div style="padding:30px;display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;text-align:center;gap:10px;">
            <div style="width:46px;height:46px;border-radius:50%;background:var(--frost);color:var(--blue-main);display:flex;align-items:center;justify-content:center;font-size:20px;"><i class="bi bi-plus-lg"></i></div>
            <div style="font-size:13px;font-weight:600;color:var(--blue-main);">Buat Tryout Baru</div>
            <div style="font-size:11.5px;color:var(--ash);">Klik untuk membuat sesi tryout baru</div>
          </div>
        </div>
      </div>
    </div>
  </div>