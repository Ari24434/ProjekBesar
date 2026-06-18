  <div class="topbar">
    <button class="tb-hamburger" onclick="toggleSb()"><i class="bi bi-list"></i></button>
    <div class="tb-title" id="tb-title"><strong><?= $topbarTitle ?? 'Dashboard' ?></strong></div>
    <div class="tb-right">
      <div class="tb-icon-btn" title="Notifikasi" onclick="showToast('Ada 3 peserta baru mendaftar hari ini!','info')">
        <i class="bi bi-bell"></i>
        <div class="notif-dot"></div>
      </div>
      <div class="tb-admin-pill" onclick="nav('pengaturan')">
        <div class="tb-pill-av">A</div>
        <span class="tb-pill-name">Admin</span>
        <span class="tb-pill-role">· Admin</span>
      </div>
    </div>
  </div>