<script>
    // ── Inisialisasi ─────────────────────────────────────────────────
    AOS.init({ once: true, offset: 100 });

    // Data dari PHP
    const kodeResiBaru        = <?= json_encode(session()->getFlashdata('kode_resi') ?: service('request')->getGet('resi')) ?>;
    const bukaModalCekStatus  = <?= json_encode((bool) session('_open_track_modal')) ?>;
    const bukaModalHasilTrack = <?= json_encode((bool) session('_open_track_result_modal')) ?>;

    // ── Modal: Kode Resi ─────────────────────────────────────────────
    if (kodeResiBaru) {
        setTimeout(() => {
            const modalEl    = document.getElementById('modalKodeResi');
            const resiText   = document.getElementById('resiCodeText');
            const btnSalin   = document.getElementById('btnSalinResi');

            if (resiText) resiText.textContent = kodeResiBaru;

            if (btnSalin) {
                btnSalin.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(kodeResiBaru);
                        btnSalin.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin';
                    } catch {
                        btnSalin.innerHTML = '<i class="bi bi-x-circle me-1"></i>Gagal Salin';
                    }
                }, { once: true });
            }

            if (modalEl) new bootstrap.Modal(modalEl).show();

            // Hapus ?resi= dari URL agar tidak muncul lagi saat refresh
            const url = new URL(window.location.href);
            if (url.searchParams.has('resi')) {
                url.searchParams.delete('resi');
                history.replaceState({}, document.title, url.toString());
            }
        }, 250);
    }

    // ── Modal: Cek Status & Hasil ────────────────────────────────────
    if (bukaModalCekStatus) {
        const el = document.getElementById('modalCekStatus');
        if (el) new bootstrap.Modal(el).show();
    }

    if (bukaModalHasilTrack) {
        const el = document.getElementById('modalHasilCekStatus');
        if (el) new bootstrap.Modal(el).show();
    }

    // ── Navbar scroll effect ─────────────────────────────────────────
    const navbar = document.getElementById('navbarUtama');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // ── Scroll to Top ────────────────────────────────────────────────
    const btnScrollAtas = document.getElementById('tombolScrollAtas');
    window.addEventListener('scroll', () => {
        btnScrollAtas.classList.toggle('show', window.scrollY > 300);
    });
    btnScrollAtas.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ── Smooth scroll navigasi ───────────────────────────────────────
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - navbar.offsetHeight, behavior: 'smooth' });
            }
        });
    });

    // ── Counter animasi ──────────────────────────────────────────────
    function animasiCounter(el) {
        const target    = +el.getAttribute('data-count');
        const increment = target / 200;
        let current     = 0;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.textContent = target + '+';
                clearInterval(timer);
            } else {
                el.textContent = Math.ceil(current);
            }
        }, 8);
    }

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    document.querySelectorAll('.stat-number').forEach(animasiCounter);
                    entry.target.__observer?.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 }).observe(statsSection);
    }

    // ── Set tanggal minimal ke hari ini ─────────────────────────────
    const inputTanggal = document.getElementById('tanggalPermintaan');
    if (inputTanggal) {
        const today = new Date().toISOString().split('T')[0];
        if (!inputTanggal.value) inputTanggal.value = today;
        inputTanggal.setAttribute('min', today);
    }

    // ── Autocomplete barang & filter kategori ────────────────────────
    const selectKategori  = document.getElementById('filterKategori');
    const inputBarang     = document.getElementById('barangDiminta');
    const inputBarangId   = document.getElementById('barangDimintaId');
    const datalistBarang  = document.getElementById('daftarBarangAutocomplete');

    const semuaBarang = datalistBarang
        ? Array.from(datalistBarang.options).map(opt => ({
            id      : String(opt.dataset.id      || ''),
            nama    : String(opt.value           || '').trim(),
            kategori: String(opt.dataset.kategori || ''),
            stok    : Number(opt.dataset.stok     || 0),
        }))
        : [];

    function statusStok(stok) {
        if (stok <= 0)  return '🔴 Perlu pengadaan';
        if (stok <= 10) return '🟡 Terbatas';
        return '🟢 Tersedia';
    }

    function renderDatalist(kategoriId = '') {
        if (!datalistBarang) return;
        const filtered = semuaBarang.filter(b => !kategoriId || b.kategori === kategoriId);
        datalistBarang.innerHTML = filtered.map(b => {
            const nama = b.nama.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return `<option value="${nama}" label="Status: ${statusStok(b.stok)}"></option>`;
        }).join('');
    }

    function sinkronBarang() {
        if (!inputBarang || !inputBarangId) return;
        const keyword = inputBarang.value.trim();

        if (!keyword) {
            inputBarangId.value = '';
            inputBarang.setCustomValidity('');
            return;
        }

        const keywordLower   = keyword.toLowerCase();
        const hasil          = semuaBarang.find(b => b.nama.toLowerCase() === keywordLower);
        const parsial        = semuaBarang.filter(b => b.nama.toLowerCase().includes(keywordLower));
        const kandidat       = hasil ?? (parsial.length === 1 ? parsial[0] : null);

        if (kandidat && selectKategori && selectKategori.value !== kandidat.kategori) {
            selectKategori.value = kandidat.kategori;
            renderDatalist(kandidat.kategori);
        }

        if (!hasil) {
            inputBarangId.value = '';
            inputBarang.setCustomValidity('Silakan pilih barang dari daftar autocomplete agar data valid.');
            return;
        }

        inputBarangId.value = hasil.id;
        inputBarang.setCustomValidity('');
    }

    if (selectKategori) {
        selectKategori.addEventListener('change', function () {
            renderDatalist(this.value);
            if (inputBarang)   { inputBarang.value = ''; inputBarang.setCustomValidity(''); }
            if (inputBarangId) { inputBarangId.value = ''; }
        });
    }

    if (inputBarang) {
        ['input', 'change', 'blur'].forEach(ev => inputBarang.addEventListener(ev, sinkronBarang));
    }

    renderDatalist(selectKategori?.value ?? '');
    sinkronBarang();

    // ── Submit: cegah double-submit ──────────────────────────────────
    const formPermintaan = document.getElementById('formPermintaan');
    const btnAjukan      = document.getElementById('btnAjukan');
    if (formPermintaan) {
        formPermintaan.addEventListener('submit', function (e) {
            sinkronBarang();
            if (!inputBarangId?.value) {
                e.preventDefault();
                inputBarang?.setCustomValidity('Silakan pilih barang dari daftar autocomplete.');
                inputBarang?.reportValidity();
                return;
            }
            inputBarang?.setCustomValidity('');
            btnAjukan.disabled     = true;
            btnAjukan.innerHTML    = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
        });
    }

    // ── Toggle password visibility ───────────────────────────────────
    function togglePassword() {
        const inputPw = document.getElementById('inputPassword');
        const ikon    = document.getElementById('ikonPassword');
        const isPass  = inputPw.type === 'password';
        inputPw.type  = isPass ? 'text'    : 'password';
        ikon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
    }
</script>
