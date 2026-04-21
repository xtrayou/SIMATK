<!-- Modal: Login -->
<div class="modal fade" id="modalMasuk" tabindex="-1" aria-labelledby="judulModalMasuk" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModalMasuk">
                    <i class="bi bi-box-seam-fill me-2" style="color:var(--primary-color);"></i>Masuk ke <span class="font-accent">SIMATK</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?php if (session('loginError')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-2"></i><?= session('loginError') ?>
                    </div>
                <?php endif ?>

                <form action="<?= base_url('auth/login') ?>" method="POST" id="formLogin">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="inputUsername" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="inputUsername" name="username"
                                value="<?= old('username') ?>" placeholder="Masukkan username" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inputPassword" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="inputPassword" name="password"
                                placeholder="Masukkan password" required>
                            <button class="btn btn-outline-secondary" type="button" id="btnTogglePassword" onclick="togglePassword()">
                                <i class="bi bi-eye" id="ikonPassword"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ingatSaya" name="remember">
                            <label class="form-check-label" for="ingatSaya">Ingat saya</label>
                        </div>
                        <a href="#" class="text-decoration-none" style="color:var(--primary-color);font-size:0.9rem;">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login-submit">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </button>
                </form>

                <hr class="my-4">
                <div class="text-center">
                    <p class="mb-0 text-muted" style="font-size:0.9rem;">
                        Belum punya akun? <a href="#" class="text-decoration-none" style="color:var(--primary-color);">Hubungi Admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
