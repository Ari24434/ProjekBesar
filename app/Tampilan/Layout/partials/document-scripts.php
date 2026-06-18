<script>
    window.APP_BASE_URL = '<?= rtrim(BASE_URL, '/') ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<?php if (!empty($includeSweetAlert)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php endif; ?>
<script src="<?= asset_url('Scripts/app.js') ?>"></script>
<?php if (!empty($extraScripts) && is_array($extraScripts)): ?>
    <?php foreach ($extraScripts as $scriptPath): ?>
        <script src="<?= asset_url($scriptPath) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
