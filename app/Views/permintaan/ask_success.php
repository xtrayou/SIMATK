<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Permintaan Terkirim | SIMATK' ?></title>

    <link rel="icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="shortcut icon" href="<?= esc(app_favicon_url(), 'attr') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/sweetalert2.min.css') ?>">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff 0%, #f8faff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script>
        window.addEventListener('load', function() {
            const resi = "<?= esc((string) ($kode_resi ?? '-')) ?>";
            
            Swal.fire({
                title: '✅ Permintaan Berhasil!',
                html: `
                    <div style="text-align: left; font-size: 0.95rem;">
                        <p><strong>Permohonan Anda telah diterima dan tercatat dalam sistem.</strong></p>
                        <div style="background: #f8f9fa; padding: 1.25rem; border-radius: 12px; margin: 1.5rem 0; border: 1px solid #dee2e6; text-align: center;">
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 1px;">Kode Resi:</p>
                            <p style="margin: 0; font-size: 1.6rem; font-weight: 800; color: #3B5BDB; font-family: 'Courier New', monospace; letter-spacing: 2px;">
                                ${resi}
                            </p>
                        </div>
                        <p><i class="bi bi-chat-dots me-1 text-primary"></i> <strong>Admin akan membalas permintaan Anda via chat atau email dalam 1-2 hari kerja.</strong></p>
                        <p style="color: #666; font-size: 0.85rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #dee2e6;">
                            <i class="bi bi-info-circle me-1"></i> Simpan kode resi di atas untuk melacak status permintaan Anda di menu "Lacak Status".
                        </p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'Saya Mengerti',
                confirmButtonColor: '#3B5BDB',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.getPopup().style.borderRadius = '16px';
                }
            }).then((result) => {
                window.location.href = "<?= base_url('/') ?>";
            });
        });
    </script>
</body>
</html>