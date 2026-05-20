<div class="page active" id="pg-laporan">
    <div class="page-body">
      <div class="page-heading anim">
        <h2>Laporan Rekap</h2>
        <p>Ringkasan performa seluruh peserta dan analisis pencapaian.</p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-bottom:15px;" class="anim anim-d1">
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-graph-up-arrow"></i> Tren Nilai per Sesi</div></div>
          <div style="padding:18px;"><canvas id="chart-laporan" height="200"></canvas></div>
        </div>
        <div class="card">
          <div class="card-head"><div class="card-title"><i class="bi bi-bar-chart-fill"></i> Distribusi Nilai TWK · TIU · TKP</div></div>
          <div style="padding:18px;"><canvas id="chart-dist" height="200"></canvas></div>
        </div>
      </div>

      <!-- Tabel rekap per peserta -->
      <div class="card anim anim-d2">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-table"></i> Rekap Nilai Peserta</div>
          <button class="btn btn-ghost btn-sm" onclick="showToast('Rekap berhasil diekspor ke Excel','success')"><i class="bi bi-download"></i> Ekspor</button>
        </div>
        <div style="overflow-x:auto;">
          <table class="data-table">
            <thead>
              <tr><th>#</th><th>Peserta</th><th>Sesi 1</th><th>Sesi 2</th><th>Sesi 3</th><th>Nilai Terbaik</th><th>Rata-rata</th><th>Tren</th><th>Status Akhir</th></tr>
            </thead>
            <tbody id="tbody-laporan"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>