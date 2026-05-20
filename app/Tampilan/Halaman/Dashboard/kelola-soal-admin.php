 <div class="page active" id="pg-soal">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Bank Soal</h2>
        <p>Kelola soal TWK, TIU, dan TKP untuk tryout CPNS.</p>
      </div>

      <!-- Stat soal per kategori -->
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:13px;margin-bottom:18px;" class="anim anim-d1">
        <div class="card" style="padding:16px 18px;border-top:3px solid var(--blue-main);">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;">
            <span class="badge badge-twk">TWK</span>
            <span style="font-size:10.5px;color:var(--ash);">Tes Wawasan Kebangsaan</span>
          </div>
          <div style="font-family:'Playfair Display',serif;font-size:28px;color:var(--blue-main);margin-bottom:3px;">120</div>
          <div style="font-size:11.5px;color:var(--ash);">soal tersedia</div>
          <div style="margin-top:9px;"><div class="progress-bar"><div class="progress-fill" style="width:73%;background:var(--blue-main);"></div></div><div style="font-size:10.5px;color:var(--ash);margin-top:3px;">73% dari target 165 soal</div></div>
        </div>
        <div class="card" style="padding:16px 18px;border-top:3px solid var(--emerald);">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;">
            <span class="badge badge-tiu">TIU</span>
            <span style="font-size:10.5px;color:var(--ash);">Tes Intelegensia Umum</span>
          </div>
          <div style="font-family:'Playfair Display',serif;font-size:28px;color:var(--emerald);margin-bottom:3px;">110</div>
          <div style="font-size:11.5px;color:var(--ash);">soal tersedia</div>
          <div style="margin-top:9px;"><div class="progress-bar"><div class="progress-fill" style="width:67%;background:var(--emerald);"></div></div><div style="font-size:10.5px;color:var(--ash);margin-top:3px;">67% dari target 165 soal</div></div>
        </div>
        <div class="card" style="padding:16px 18px;border-top:3px solid var(--amber);">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;">
            <span class="badge badge-tkp">TKP</span>
            <span style="font-size:10.5px;color:var(--ash);">Tes Karakteristik Pribadi</span>
          </div>
          <div style="font-family:'Playfair Display',serif;font-size:28px;color:var(--amber);margin-bottom:3px;">100</div>
          <div style="font-size:11.5px;color:var(--ash);">soal tersedia</div>
          <div style="margin-top:9px;"><div class="progress-bar"><div class="progress-fill" style="width:61%;background:var(--amber);"></div></div><div style="font-size:10.5px;color:var(--ash);margin-top:3px;">61% dari target 165 soal</div></div>
        </div>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:14px;" class="anim anim-d2">
        <div style="display:flex;align-items:center;gap:8px;flex:1;flex-wrap:wrap;">
          <div class="search-bar" style="max-width:260px;flex:1;">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari soal..." id="srch-soal"/>
          </div>
          <div class="filter-bar" style="margin-bottom:0;">
            <button class="filter-btn active" onclick="setFilter(this)">Semua (330)</button>
            <button class="filter-btn" onclick="setFilter(this)">TWK (120)</button>
            <button class="filter-btn" onclick="setFilter(this)">TIU (110)</button>
            <button class="filter-btn" onclick="setFilter(this)">TKP (100)</button>
          </div>
        </div>
        <button class="btn btn-primary" onclick="openModal('modal-add-soal')">
          <i class="bi bi-plus-circle-fill"></i> Tambah Soal
        </button>
      </div>

      <div class="card anim anim-d3">
        <div style="overflow-x:auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Subtopik</th>
                <th>Pertanyaan (Ringkasan)</th>
                <th>Jawaban Benar</th>
                <th>Digunakan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-soal"></tbody>
          </table>
        </div>
        <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:12px;color:var(--ash);">Menampilkan <strong>10</strong> dari 330 soal</div>
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