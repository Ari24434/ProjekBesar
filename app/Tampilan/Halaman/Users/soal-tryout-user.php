  <div class="page active" id="pg-exam">
    <div class="page-body" style="max-width:1000px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;" class="anim">
        <div>
          <div style="font-family:'Playfair Display',serif;font-size:17px;color:var(--ink);">Tryout SKD Sesi 3</div>
          <div style="font-size:12px;color:var(--ash);margin-top:2px;">Sedang mengerjakan — <span id="exam-cat-label" style="color:var(--blue-main);font-weight:600;">Tes Wawasan Kebangsaan (TWK)</span></div>
        </div>
        <button class="btn btn-danger btn-sm" onclick="if(confirm('Yakin kumpulkan jawaban?'))nav('hasil')"><i class="bi bi-send-check"></i> Kumpulkan</button>
      </div>
      <div class="exam-wrap anim anim-d1">
        <div class="exam-card">
          <div class="exam-header">
            <span class="exam-cat-chip" id="ex-cat-chip">TWK</span>
            <span class="exam-qnum">Soal <strong id="ex-qnum">1</strong> dari <strong id="ex-qtotal">35</strong></span>
          </div>
          <div class="exam-body">
            <div class="exam-question" id="ex-qtext">Salah satu nilai yang terkandung dalam sila ke-3 Pancasila "Persatuan Indonesia" adalah mengutamakan kepentingan bangsa dan negara di atas kepentingan pribadi. Sikap yang mencerminkan nilai tersebut dalam kehidupan sehari-hari adalah...</div>
            <div class="exam-options" id="ex-options"></div>
          </div>
          <div class="exam-footer">
            <button class="btn btn-ghost btn-sm" id="ex-prev" onclick="prevQ()" disabled><i class="bi bi-arrow-left"></i> Sebelumnya</button>
            <span class="answered-count" id="ex-answered">0 / 110 dijawab</span>
            <button class="btn btn-primary btn-sm" id="ex-next" onclick="nextQ()">Selanjutnya <i class="bi bi-arrow-right"></i></button>
          </div>
        </div>
        <div class="exam-panel">
          <div class="panel-timer">
            <div class="timer-num" id="ex-timer">99:42</div>
            <div class="timer-sub">Sisa waktu</div>
          </div>
          <div class="panel-section">
            <div class="panel-label">Navigasi Soal</div>
            <div class="panel-cats">
              <button class="cat-tab active" id="cat-0" onclick="jumpCat(0)">TWK</button>
              <button class="cat-tab" id="cat-35" onclick="jumpCat(35)">TIU</button>
              <button class="cat-tab" id="cat-70" onclick="jumpCat(70)">TKP</button>
            </div>
            <div class="q-nav-grid" id="ex-qgrid"></div>
          </div>
          <div class="panel-stats">
            <div class="pstat-row"><span class="pstat-key">TWK</span><span class="pstat-val" id="ps-twk">0 / 35</span></div>
            <div class="pstat-row"><span class="pstat-key">TIU</span><span class="pstat-val" id="ps-tiu">0 / 35</span></div>
            <div class="pstat-row"><span class="pstat-key">TKP</span><span class="pstat-val" id="ps-tkp">0 / 40</span></div>
          </div>
          <div style="padding:9px 12px 12px;">
            <div style="display:flex;gap:7px;font-size:10.5px;color:var(--ash);">
              <div style="display:flex;align-items:center;gap:4px;"><div style="width:9px;height:9px;border-radius:2px;background:var(--blue-main);"></div>Dijawab</div>
              <div style="display:flex;align-items:center;gap:4px;"><div style="width:9px;height:9px;border-radius:2px;border:1.5px solid var(--smoke);"></div>Kosong</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
    const CATS = Array(35).fill('TWK').concat(Array(35).fill('TIU')).concat(Array(40).fill('TKP'));
    const TOTAL = 110;
    const QS = [
        {q:"Salah satu nilai yang terkandung dalam sila ke-3 Pancasila 'Persatuan Indonesia' adalah mengutamakan kepentingan bangsa di atas kepentingan pribadi. Sikap yang mencerminkan nilai tersebut adalah...",opts:["Bersaing dengan sesama demi kemajuan diri","Menolong korban bencana tanpa memandang suku","Mengutamakan kepentingan kelompok sendiri","Menolak kerja sama dengan suku lain"],ans:1},
        {q:"UUD 1945 Pasal 30 ayat (1) menyatakan bahwa tiap-tiap warga negara berhak dan wajib ikut serta dalam usaha pertahanan negara. Hal ini mencerminkan prinsip...",opts:["Desentralisasi pertahanan","Pertahanan semesta","Militerisasi rakyat","Otonomi keamanan daerah"],ans:1},
        {q:"Nilai-nilai Pancasila dirumuskan oleh para pendiri bangsa dengan menggali dari...",opts:["Nilai-nilai agama Islam semata","Budaya Barat yang modern","Nilai-nilai budaya bangsa Indonesia sendiri","Ideologi negara lain yang sudah terbukti"],ans:2},
        {q:"Bhinneka Tunggal Ika sebagai semboyan NKRI berasal dari kitab...",opts:["Negarakertagama","Sutasoma","Arjunawiwaha","Ramayana"],ans:1},
        {q:"Berikut yang merupakan fungsi Pancasila sebagai dasar negara adalah...",opts:["Sebagai panduan gaya hidup modern","Sumber dari segala sumber hukum di Indonesia","Acuan penentuan kebijakan luar negeri","Pedoman hanya bagi pejabat pemerintah"],ans:1},
    ];

    for (let i = QS.length; i < TOTAL; i++) {
        QS.push({q:`[${CATS[i]}] Soal ke-${i+1}: Pilih pernyataan yang paling tepat berdasarkan materi ${CATS[i]}.`,opts:["Pilihan A","Pilihan B yang benar","Pilihan C","Pilihan D"],ans:1});
    }

    let curQ=0, answers=new Array(TOTAL).fill(-1), timerSec=5982, timerInt=null;

    function startExam() {
        if (timerInt) clearInterval(timerInt);
        timerSec = 5982;
        timerInt = setInterval(() => {
            timerSec--;
            if (timerSec <= 0) { clearInterval(timerInt); nav('hasil'); return; }
            const m = String(Math.floor(timerSec/60)).padStart(2,'0');
            const s = String(timerSec%60).padStart(2,'0');
            const el = document.getElementById('ex-timer');
            el.textContent = `${m}:${s}`;
            el.className = 'timer-num' + (timerSec < 600 ? ' warn' : '');
        }, 1000);
        buildGrid(); renderQ(curQ);
    }

    function renderQ(i) {
        curQ = i;
        const q = QS[i]; const cat = CATS[i];
        document.getElementById('ex-cat-chip').textContent = cat;
        document.getElementById('ex-qnum').textContent = i+1;
        document.getElementById('ex-qtotal').textContent = cat==='TWK'?35:cat==='TIU'?35:40;
        document.getElementById('exam-cat-label').textContent = cat==='TWK'?'Tes Wawasan Kebangsaan (TWK)':cat==='TIU'?'Tes Intelegensia Umum (TIU)':'Tes Karakteristik Pribadi (TKP)';
        document.getElementById('ex-qtext').textContent = q.q;
        const ol = document.getElementById('ex-options'); ol.innerHTML='';
        ['A','B','C','D'].forEach((k,j) => {
            const d = document.createElement('div');
            d.className = 'exam-opt' + (answers[i]===j?' selected':'');
            d.innerHTML = `<div class="opt-label">${k}</div><div class="opt-text">${q.opts[j]}</div>`;
            d.onclick = () => { answers[i]=j; buildGrid(); renderQ(i); };
            ol.appendChild(d);
        });
        document.getElementById('ex-prev').disabled = i===0;
        const nx = document.getElementById('ex-next');
        if (i===TOTAL-1) { nx.innerHTML='<i class="bi bi-send-check"></i> Kumpulkan'; nx.onclick=()=>{if(confirm('Kumpulkan?'))nav('hasil');}; }
        else { nx.innerHTML='Selanjutnya <i class="bi bi-arrow-right"></i>'; nx.onclick=nextQ; }
        ['0','35','70'].forEach(s=>{
            const active=(s==='0'&&cat==='TWK')||(s==='35'&&cat==='TIU')||(s==='70'&&cat==='TKP');
            document.getElementById('cat-'+s).classList.toggle('active',active);
        });
        updateCounts();
    }

    function buildGrid() {
        const g = document.getElementById('ex-qgrid'); g.innerHTML='';
        for (let i=0;i<TOTAL;i++) {
            const d=document.createElement('div');
            d.className='qn'+(answers[i]>=0?' done':'')+(i===curQ?' cur':'');
            d.textContent=i+1; d.onclick=()=>renderQ(i); g.appendChild(d);
        }
    }

    function updateCounts() {
        const twk=answers.slice(0,35).filter(a=>a>=0).length;
        const tiu=answers.slice(35,70).filter(a=>a>=0).length;
        const tkp=answers.slice(70).filter(a=>a>=0).length;
        document.getElementById('ps-twk').textContent=`${twk} / 35`;
        document.getElementById('ps-tiu').textContent=`${tiu} / 35`;
        document.getElementById('ps-tkp').textContent=`${tkp} / 40`;
        document.getElementById('ex-answered').textContent=`${twk+tiu+tkp} / ${TOTAL} dijawab`;
    }

    function nextQ(){if(curQ<TOTAL-1)renderQ(curQ+1);}
    function prevQ(){if(curQ>0)renderQ(curQ-1);}
    function jumpCat(s){renderQ(s);}

</script>