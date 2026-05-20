 <div class="page active" id="pg-nilai">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Nilai & Hasil Tryout</h2>
        <p>Monitor performa seluruh peserta per sesi tryout.</p>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:16px;" class="anim anim-d1">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;flex:1;">
          <div class="form-group" style="margin-bottom:0;min-width:180px;">
            <select class="form-select" style="font-size:12.5px;padding:7px 32px 7px 11px;">
              <option>Semua Sesi</option>
              <option>Sesi 3 — Simulasi Penuh</option>
              <option>Sesi 2 — Latihan TWK</option>
              <option>Sesi 1 — Pembukaan</option>
            </select>
          </div>
          <div class="filter-bar" style="margin-bottom:0;">
            <button class="filter-btn active" onclick="setFilter(this)">Semua</button>
            <button class="filter-btn" onclick="setFilter(this)">Lulus</button>
            <button class="filter-btn" onclick="setFilter(this)">Tidak Lulus</button>
          </div>
          <div class="search-bar" style="max-width:220px;flex:1;">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari nama..."/>
          </div>
        </div>
        <button class="btn btn-ghost"><i class="bi bi-download"></i> Ekspor Excel</button>
      </div>

      <!-- Stat ringkasan -->
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:11px;margin-bottom:16px;" class="anim anim-d2">
        <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--emerald);">
          <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata Total</div>
          <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--emerald);">334</div>
          <div style="font-size:10.5px;color:var(--ash);">dari 500 poin</div>
        </div>
        <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--blue-main);">
          <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TWK</div>
          <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--blue-main);">72</div>
          <div style="font-size:10.5px;color:var(--ash);">min. 65 · ✓ Lulus</div>
        </div>
        <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--emerald);">
          <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TIU</div>
          <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--emerald);">85</div>
          <div style="font-size:10.5px;color:var(--ash);">min. 80 · ✓ Lulus</div>
        </div>
        <div class="card" style="padding:13px 15px;border-top:2.5px solid var(--amber);">
          <div style="font-size:10px;color:var(--ash);margin-bottom:4px;">Rata-rata TKP</div>
          <div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--amber);">166</div>
          <div style="font-size:10.5px;color:var(--ash);">min. 166 · ⚠ Batas</div>
        </div>
      </div>

      <div class="card anim anim-d3">
        <div style="overflow-x:auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Peserta</th>
                <th>Sesi</th>
                <th>TWK</th>
                <th>TIU</th>
                <th>TKP</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tbody-nilai"></tbody>
          </table>
        </div>
        <div style="padding:12px 18px;border-top:1px solid var(--smoke);display:flex;align-items:center;justify-content:space-between;">
          <div style="font-size:12px;color:var(--ash);">Menampilkan <strong>10</strong> dari 60 hasil</div>
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