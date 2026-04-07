<nav class="navbar navbar-expand-lg navbar-home fixed-top" id="navbarUtama">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
            <i class="bi bi-box-seam-fill me-2" style="font-size:1.8rem;"></i>
            <span>SIMA<span class="font-accent">TIK</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"><span></span></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#permintaan">Permintaan ATK</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <?php if (session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-login">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                <?php else: ?>
                    <a href="#" class="btn btn-login" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>