<div class="page active" id="pg-peserta">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Manajemen Peserta</h2>
        <p>Kelola data peserta tryout CPNS Oman's Club Academy.</p>
      </div>

      <!-- Filter & Aksi -->
      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;" class="anim anim-d1">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
          <div class="search-bar" style="max-width:280px;flex:1;">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari nama atau email..." oninput="filterPeserta(this.value)" id="srch-peserta"/>
          </div>
          <div class="filter-bar" style="margin-bottom:0;">
            <button class="filter-btn active" onclick="filterStatus('semua',this)">Semua (60)</button>
            <button class="filter-btn" onclick="filterStatus('aktif',this)">Aktif (55)</button>
            <button class="filter-btn" onclick="filterStatus('nonaktif',this)">Nonaktif (5)</button>
          </div>
        </div>
        <button class="btn btn-primary" onclick="openModal('modal-add-peserta')">
          <i class="bi bi-person-plus-fill"></i> Tambah Peserta
        </button>
      </div>

      <div class="card anim anim-d2">
        <div style="overflow-x:auto;">
          <table class="data-table" id="tbl-peserta">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Tryout Diikuti</th>
                <th>Nilai Terbaik</th>
                <th>Status</th>
                <th>Bergabung</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-peserta"></tbody>
          </table>
        </div>
        <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:12px;color:var(--ash);">Menampilkan <strong id="peserta-count">10</strong> dari 60 peserta</div>
          <div style="display:flex;gap:6px;">
            <button class="btn btn-ghost btn-sm"><i class="bi bi-chevron-left"></i></button>
            <button class="btn btn-primary btn-sm">1</button>
            <button class="btn btn-ghost btn-sm">2</button>
            <button class="btn btn-ghost btn-sm">3</button>
            <button class="btn btn-ghost btn-sm"><i class="bi bi-chevron-right"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
