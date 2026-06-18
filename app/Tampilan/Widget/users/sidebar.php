<aside class="sidebar" id="sidebar">
  <div class="sb-header">
    <div class="sb-logo-row">
      <div class="sb-logo-mark"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="sb-brand-name">Oman's<span> Club</span></div>
    </div>
    <div class="sb-portal-tag">Portal Peserta</div>
  </div>
  <div class="sb-divider"></div>
  <nav class="sb-nav">
    <div class="sb-section-label">Menu Utama</div>
      <?php foreach ($nav_items as $key => $item): ?>
      <a href="<?= BASE_URL . '/' . $item['url'] ?>" 
          class="nav-item <?= $active_menu === $key ? 'active' : '' ?>">
          <div class="nav-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
          <?= $item['label'] ?>
          <?php if ($item['badge']): ?>
          <span class="nav-badge"><?= $item['badge'] ?></span>
          <?php endif; ?>
      </a>
      <?php endforeach; ?>
  </nav>
  <div class="sb-footer">
    <div class="sb-user-card">
      <div class="sb-avatar"><?= htmlspecialchars($userContext['initial'] ?? 'P') ?></div>
      <div class="sb-user-info">
        <div class="sb-user-name"><?= htmlspecialchars($userContext['user']['nama'] ?? 'Peserta') ?></div>
        <div class="sb-user-role"><?= htmlspecialchars(ucfirst($userContext['user']['status'] ?? 'aktif')) ?></div>
      </div>
      <div class="sb-logout-btn" onclick="konfirmKeluar()"><i class="bi bi-power"></i></div>
    </div>
  </div>
</aside>
<form id="logoutForm" method="post" action="<?= BASE_URL ?>/logout" style="display:none;">
  <?= csrf_field() ?>
</form>
<script>
  function konfirmKeluar() {
    if (confirm('Yakin ingin keluar?')) {
      document.getElementById('logoutForm').submit();
    }
  }
</script>
