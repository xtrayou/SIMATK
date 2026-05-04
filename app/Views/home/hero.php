<?php
$heroBg       = (string) app_setting('hero_bg', '');
$heroBgStyle  = '';
if (!empty($heroBg) && file_exists(FCPATH . 'img/' . $heroBg)) {
    $bgUrl       = base_url('img/' . esc((string) $heroBg, 'url'));
    $heroBgStyle = ' style="background-image: linear-gradient(135deg, rgba(0,0,0,0.42) 0%, rgba(0,0,0,0.34) 40%, rgba(0,0,0,0.26) 100%), url(\'' . $bgUrl . '\');"';
}
?>
<section class="hero-section" id="beranda" <?= $heroBgStyle ?>>
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-8 col-xl-7" data-aos="fade-up" data-aos-duration="1000">
                <div class="hero-content text-center">
                    <?php
                    $heroTitle  = (string) app_setting('hero_title', 'Sistem Inventaris ATK');
                    $heroAccent = (string) app_setting('hero_accent', 'Inventaris');
                    $heroSub    = app_setting('hero_subtitle', 'Kelola alat tulis kantor dengan <span class="font-accent">mudah, efisien,</span> dan terintegrasi.<br>Pantau stok, lacak penggunaan, dan buat laporan secara <span class="font-accent">real-time.</span>');
                    $institution = (string) app_setting('institution', 'Fakultas Ilmu Komputer');
                    // Buat judul dengan aksen dinamis
                    $heroTitleHtml = str_replace($heroAccent, '<span class="font-accent text-highlight">' . esc((string) $heroAccent) . '</span>', esc((string) $heroTitle));
                    ?>
                    <h1 class="hero-title">
                        <?= $heroTitleHtml ?><br>
                        <span style="color:rgba(255,255,255,0.9);">
                            <?= esc((string) $institution) ?>
                        </span>
                    </h1>
                    <p class="hero-subtitle">
                        <?= $heroSub ?>
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="#permintaan" class="btn btn-hero btn-hero-primary">
                            <i class="bi bi-rocket-takeoff me-2"></i>Klik untuk membuat permintaan
                        </a>
                        <a href="#kontak" class="btn btn-hero btn-hero-outline">
                            <i class="bi bi-chat-dots me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>