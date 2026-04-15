<?php if ($msg = (session('sukses') ?? session('success'))): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= esc($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($msg = (session('galat') ?? session('error'))): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= esc($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>