<aside class="sidebar" id="sidebar">
  <div class="sb-header">
    <div class="sb-logo-row">
      <div class="sb-logo-mark"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="sb-brand-name">Oman's<span> Club</span></div>
    </div>
    <div class="sb-portal-tag">Portal Administrator</div>
  </div>
  <div class="sb-divider"></div>
  <nav class="sb-nav">
    <div class="sb-section-label">Menu Utama</div>
    <?php foreach ($nav_items as $key => $item): ?>
        <?php if ($item['section'] === 'utama'): ?>
        <a href="<?= BASE_URL . '/' . $item['url'] ?>" 
            class="nav-item <?= $active_menu === $key ? 'active' : '' ?>"
            data-menu="<?= $key ?>">
            <div class="nav-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
            <?= $item['label'] ?>
            <?php if ($item['badge'] !== null): ?>
            <span class="nav-badge <?= $item['badge_class'] ?? '' ?>"><?= (int) $item['badge'] ?></span>
            <?php endif; ?>
        </a>
        <?php elseif ($item['section'] === 'laporan'): ?>
        <a href="<?= BASE_URL . '/' . $item['url'] ?>" 
            class="nav-item <?= $active_menu === $key ? 'active' : '' ?>"
            data-menu="<?= $key ?>">
            <div class="nav-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
            <?= $item['label'] ?>
            <?php if ($item['badge'] !== null): ?>
            <span class="nav-badge <?= $item['badge_class'] ?? '' ?>"><?= (int) $item['badge'] ?></span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
    <?php endforeach; ?>
    <div class="sb-section-label" style="margin-top:8px;">Sistem</div>
    <?php foreach ($nav_items as $key => $item): ?>
        <?php if ($item['section'] === 'sistem'): ?>
        <a href="<?= BASE_URL . '/' . $item['url'] ?>" 
            class="nav-item <?= $active_menu === $key ? 'active' : '' ?>"
            data-menu="<?= $key ?>">
            <div class="nav-icon"><i class="bi <?= $item['icon'] ?>"></i></div>
            <?= $item['label'] ?>
            <?php if ($item['badge'] !== null): ?>
            <span class="nav-badge <?= $item['badge_class'] ?? '' ?>"><?= (int) $item['badge'] ?></span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
    <?php endforeach; ?>
    <form method="post" action="<?= BASE_URL ?>/logout" style="margin:0;">
      <?= csrf_field() ?>
      <button type="submit" class="nav-item danger" style="width:100%;border:0;text-align:left;">
        <div class="nav-icon"><i class="bi bi-box-arrow-left"></i></div> Keluar
      </button>
    </form>
  </nav>
  
</aside>
