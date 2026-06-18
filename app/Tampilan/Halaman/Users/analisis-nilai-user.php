<div class="page active" id="pg-analisis">
  <div class="page-body analysis-page">
    <div class="page-heading anim">
      <h2>Analisis Nilai</h2>
      <p>Pantau perkembangan dan akurasi belajarmu.</p>
    </div>

    <div class="analysis-summary anim anim-d1">
      <div class="analysis-stat card">
        <div class="analysis-stat-icon blue"><i class="bi bi-trophy-fill"></i></div>
        <div>
          <div class="analysis-stat-value">355</div>
          <div class="analysis-stat-label">Nilai terbaik</div>
        </div>
      </div>
      <div class="analysis-stat card">
        <div class="analysis-stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
          <div class="analysis-stat-value">+65</div>
          <div class="analysis-stat-label">Kenaikan total</div>
        </div>
      </div>
      <div class="analysis-stat card">
        <div class="analysis-stat-icon gold"><i class="bi bi-bullseye"></i></div>
        <div>
          <div class="analysis-stat-value">78%</div>
          <div class="analysis-stat-label">Akurasi rata-rata</div>
        </div>
      </div>
      <div class="analysis-stat card">
        <div class="analysis-stat-icon red"><i class="bi bi-lightbulb-fill"></i></div>
        <div>
          <div class="analysis-stat-value">TWK</div>
          <div class="analysis-stat-label">Prioritas belajar</div>
        </div>
      </div>
    </div>

    <div class="analysis-grid anim anim-d2">
      <section class="card analysis-card trend-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-graph-up-arrow"></i> Grafik Perkembangan Nilai</div>
        </div>
        <div class="analysis-chart-wrap">
          <svg class="line-chart" viewBox="0 0 760 460" role="img" aria-label="Grafik perkembangan nilai tryout dari sesi 1 sampai sesi 5">
            <defs>
              <linearGradient id="totalArea" x1="0" x2="0" y1="0" y2="1">
                <stop offset="0%" stop-color="#2D72D9" stop-opacity=".18"/>
                <stop offset="100%" stop-color="#2D72D9" stop-opacity=".05"/>
              </linearGradient>
            </defs>

            <g class="chart-grid">
              <line x1="70" y1="40" x2="720" y2="40"/>
              <line x1="70" y1="82" x2="720" y2="82"/>
              <line x1="70" y1="124" x2="720" y2="124"/>
              <line x1="70" y1="166" x2="720" y2="166"/>
              <line x1="70" y1="208" x2="720" y2="208"/>
              <line x1="70" y1="250" x2="720" y2="250"/>
              <line x1="70" y1="292" x2="720" y2="292"/>
              <line x1="70" y1="334" x2="720" y2="334"/>
              <line x1="70" y1="376" x2="720" y2="376"/>
              <line x1="70" y1="418" x2="720" y2="418"/>
            </g>

            <g class="chart-axis">
              <line x1="70" y1="40" x2="70" y2="418"/>
              <line x1="70" y1="418" x2="720" y2="418"/>
            </g>

            <g class="chart-labels y-labels">
              <text x="58" y="423">0</text>
              <text x="54" y="381">50</text>
              <text x="47" y="339">100</text>
              <text x="47" y="297">150</text>
              <text x="47" y="255">200</text>
              <text x="47" y="213">250</text>
              <text x="47" y="171">300</text>
              <text x="47" y="129">350</text>
              <text x="47" y="87">400</text>
              <text x="47" y="45">420</text>
            </g>

            <path class="area total-area" d="M70 157 C150 139 200 122 232 126 C300 130 332 149 395 145 C460 141 506 120 558 111 C610 102 666 99 720 94 L720 418 L70 418 Z"/>
            <path class="line total-line" d="M70 157 C150 139 200 122 232 126 C300 130 332 149 395 145 C460 141 506 120 558 111 C610 102 666 99 720 94"/>
            <path class="line twk-line dashed" d="M70 376 C150 366 205 359 232 359 C302 359 340 371 395 367 C468 363 505 353 558 346 C616 340 673 339 720 336"/>
            <path class="line tiu-line dashed" d="M70 362 C150 354 195 347 232 344 C310 342 345 350 395 350 C462 350 498 339 558 337 C618 334 669 332 720 330"/>
            <path class="line tkp-line dashed" d="M70 291 C145 281 195 277 232 278 C305 279 338 288 395 287 C465 285 505 278 558 277 C619 275 673 274 720 273"/>

            <g class="chart-points total-points">
              <circle cx="70" cy="157" r="6"/><circle cx="232" cy="126" r="6"/><circle cx="395" cy="145" r="6"/><circle cx="558" cy="111" r="6"/><circle cx="720" cy="94" r="6"/>
            </g>
            <g class="chart-points twk-points">
              <circle cx="70" cy="376" r="4"/><circle cx="232" cy="359" r="4"/><circle cx="395" cy="367" r="4"/><circle cx="558" cy="346" r="4"/><circle cx="720" cy="336" r="4"/>
            </g>
            <g class="chart-points tiu-points">
              <circle cx="70" cy="362" r="4"/><circle cx="232" cy="344" r="4"/><circle cx="395" cy="350" r="4"/><circle cx="558" cy="337" r="4"/><circle cx="720" cy="330" r="4"/>
            </g>
            <g class="chart-points tkp-points">
              <circle cx="70" cy="291" r="4"/><circle cx="232" cy="278" r="4"/><circle cx="395" cy="287" r="4"/><circle cx="558" cy="277" r="4"/><circle cx="720" cy="273" r="4"/>
            </g>

            <g class="chart-labels x-labels">
              <text x="70" y="442">Sesi 1</text>
              <text x="232" y="442">Sesi 2</text>
              <text x="395" y="442">Sesi 3</text>
              <text x="558" y="442">Sesi 4</text>
              <text x="720" y="442">Sesi 5</text>
            </g>
          </svg>
        </div>
        <div class="chart-legend">
          <span><i class="legend-line total"></i>Total</span>
          <span><i class="legend-line twk"></i>TWK</span>
          <span><i class="legend-line tiu"></i>TIU</span>
          <span><i class="legend-line tkp"></i>TKP</span>
        </div>
      </section>

      <section class="card analysis-card radar-card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-pie-chart-fill"></i> Akurasi per Kategori</div>
        </div>
        <div class="radar-wrap">
          <svg class="radar-chart" viewBox="0 0 360 360" role="img" aria-label="Grafik radar akurasi per kategori materi">
            <g class="radar-grid">
              <polygon points="180,70 275,110 315,180 275,250 180,290 85,250 45,180 85,110"/>
              <polygon points="180,98 251,128 281,180 251,232 180,262 109,232 79,180 109,128"/>
              <polygon points="180,126 227,146 247,180 227,214 180,234 133,214 113,180 133,146"/>
              <polygon points="180,154 203,164 213,180 203,196 180,206 157,196 147,180 157,164"/>
              <line x1="180" y1="180" x2="180" y2="70"/>
              <line x1="180" y1="180" x2="275" y2="110"/>
              <line x1="180" y1="180" x2="315" y2="180"/>
              <line x1="180" y1="180" x2="275" y2="250"/>
              <line x1="180" y1="180" x2="180" y2="290"/>
              <line x1="180" y1="180" x2="85" y2="250"/>
              <line x1="180" y1="180" x2="45" y2="180"/>
              <line x1="180" y1="180" x2="85" y2="110"/>
            </g>
            <g class="radar-values">
              <polygon points="180,100 243,134 282,180 248,230 180,257 109,232 76,180 96,118"/>
              <polyline points="180,100 243,134 282,180 248,230 180,257 109,232 76,180 96,118 180,100"/>
              <circle cx="180" cy="100" r="4"/><circle cx="243" cy="134" r="4"/><circle cx="282" cy="180" r="4"/><circle cx="248" cy="230" r="4"/><circle cx="180" cy="257" r="4"/><circle cx="109" cy="232" r="4"/><circle cx="76" cy="180" r="4"/><circle cx="96" cy="118" r="4"/>
            </g>
            <g class="radar-scale">
              <text x="188" y="74">100</text>
              <text x="188" y="116">75</text>
              <text x="188" y="158">50</text>
              <text x="188" y="184">25</text>
            </g>
            <g class="radar-labels">
              <text x="180" y="42">Pancasila</text>
              <text x="294" y="91">UUD 1945</text>
              <text x="334" y="184">NKRI</text>
              <text x="295" y="284">Bhineka</text>
              <text x="180" y="326">Verbal</text>
              <text x="66" y="284">Numerik</text>
              <text x="26" y="184">Figural</text>
              <text x="66" y="91">Pelayanan</text>
            </g>
          </svg>
        </div>
        <div class="radar-note">
          Area terkuatmu ada di <strong>Pelayanan</strong> dan <strong>Verbal</strong>. Fokus berikutnya: Pancasila dan UUD 1945.
        </div>
      </section>
    </div>

    <div class="analysis-detail-grid anim anim-d3">
      <section class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-clipboard-data"></i> Ringkasan Akurasi</div>
        </div>
        <div class="accuracy-list">
          <div class="accuracy-row"><span>TWK</span><div class="accuracy-bar"><i style="width:72%;"></i></div><strong>72%</strong></div>
          <div class="accuracy-row"><span>TIU</span><div class="accuracy-bar"><i style="width:82%;"></i></div><strong>82%</strong></div>
          <div class="accuracy-row"><span>TKP</span><div class="accuracy-bar"><i style="width:79%;"></i></div><strong>79%</strong></div>
        </div>
      </section>

      <section class="card">
        <div class="card-head">
          <div class="card-title"><i class="bi bi-stars"></i> Rekomendasi Belajar</div>
        </div>
        <div class="recommendation-list">
          <div class="recommendation-item warning"><strong>TWK</strong><span>Perkuat Pancasila, UUD 1945, dan NKRI untuk mengejar stabilitas skor.</span></div>
          <div class="recommendation-item info"><strong>TIU</strong><span>Pertahankan latihan verbal, lalu tambah porsi numerik harian.</span></div>
          <div class="recommendation-item success"><strong>TKP</strong><span>Skor sudah aman. Ulangi simulasi waktu agar konsisten.</span></div>
        </div>
      </section>
    </div>
  </div>
</div>
