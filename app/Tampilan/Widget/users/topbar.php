<div class="topbar">
    <button class="tb-hamburger" onclick="toggleSb()"><i class="bi bi-list"></i></button>
    <div class="tb-title" id="tb-title"><strong><?= $topbarTitle ?></strong></div>
    <div class="tb-right">
        
        <div class="tb-user-pill" onclick="nav('profil')">
        <div class="tb-pill-av"><?= htmlspecialchars($userContext['initial'] ?? 'P') ?></div>
        <span class="tb-pill-name"><?= htmlspecialchars($userContext['first_name'] ?? 'Peserta') ?></span>
        </div>
    </div>
</div>
