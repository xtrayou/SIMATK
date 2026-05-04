/**
 * SIMATK Home Page Scripts
 */

document.addEventListener('DOMContentLoaded', function () {
    // -- Init AOS --
    if (typeof AOS !== 'undefined') {
        AOS.init({
            once: true,
            offset: 100
        });
    }

    const SIMATK_HOME = window.SIMATK_HOME || {};

    // -- Modal Resi --
    if (SIMATK_HOME.kodeResi) {
        setTimeout(() => {
            const modalResiEl = document.getElementById('modalKodeResi');
            const resiCodeText = document.getElementById('resiCodeText');
            const btnSalinResi = document.getElementById('btnSalinResi');

            if (resiCodeText) {
                resiCodeText.textContent = SIMATK_HOME.kodeResi;
            }

            if (btnSalinResi) {
                btnSalinResi.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(SIMATK_HOME.kodeResi);
                        btnSalinResi.innerHTML = '<i class="bi bi-check2 me-1"></i>Tersalin';
                    } catch (e) {
                        btnSalinResi.innerHTML = '<i class="bi bi-x-circle me-1"></i>Gagal Salin';
                    }
                }, { once: true });
            }

            if (modalResiEl) {
                const modalResi = new bootstrap.Modal(modalResiEl);
                modalResi.show();
            }

            // Clean URL
            const url = new URL(window.location.href);
            if (url.searchParams.has('resi')) {
                url.searchParams.delete('resi');
                history.replaceState({}, document.title, url.toString());
            }
        }, 250);
    }

    // -- Other Modals --
    if (SIMATK_HOME.bukaModalCekStatus) {
        const modalCekStatusEl = document.getElementById('modalCekStatus');
        if (modalCekStatusEl) {
            const modalCekStatus = new bootstrap.Modal(modalCekStatusEl);
            modalCekStatus.show();
        }
    }

    if (SIMATK_HOME.bukaModalHasilTrack) {
        const modalHasilCekStatusEl = document.getElementById('modalHasilCekStatus');
        if (modalHasilCekStatusEl) {
            const modalHasilCekStatus = new bootstrap.Modal(modalHasilCekStatusEl);
            modalHasilCekStatus.show();
        }
    }

    // -- Navbar --
    const navbar = document.getElementById('navbarUtama');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // -- Scroll to top --
    const tombolScrollAtas = document.getElementById('tombolScrollAtas');
    if (tombolScrollAtas) {
        window.addEventListener('scroll', () => {
            tombolScrollAtas.classList.toggle('show', window.scrollY > 300);
        });
        tombolScrollAtas.addEventListener('click', () => window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }));
    }

    // -- Smooth scroll --
    document.querySelectorAll('a[href^="#"]').forEach(tautan => {
        tautan.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offsetNavbar = navbar ? navbar.offsetHeight : 0;
                window.scrollTo({
                    top: target.offsetTop - offsetNavbar,
                    behavior: 'smooth'
                });
            }
        });
    });

    // -- Counter animation --
    function animasiCounter(el) {
        const target = +el.getAttribute('data-count');
        const increment = target / 200;
        let current = 0;

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

    const observerCounter = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                document.querySelectorAll('.stat-number').forEach(animasiCounter);
                observerCounter.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const seksiStats = document.querySelector('.stats-section');
    if (seksiStats) observerCounter.observe(seksiStats);

    // -- Date Input --
    const inputTanggal = document.getElementById('tanggalPermintaan');
    if (inputTanggal) {
        const today = new Date().toISOString().split('T')[0];
        if (!inputTanggal.value) {
            inputTanggal.value = today;
        }
        inputTanggal.setAttribute('min', today);
    }

    // -- Autocomplete logic --
    const selectKategori = document.getElementById('filterKategori');
    const inputBarang = document.getElementById('barangDiminta');
    const inputBarangId = document.getElementById('barangDimintaId');
    const datalistBarang = document.getElementById('daftarBarangAutocomplete');

    const semuaBarang = datalistBarang ?
        Array.from(datalistBarang.options).map((opt) => ({
            id: String(opt.dataset.id || ''),
            nama: String(opt.value || '').trim(),
            kategori: String(opt.dataset.kategori || ''),
            stok: Number(opt.dataset.stok || 0),
        })) : [];

    function getStockStatusLabel(stok) {
        if (stok <= 0) return '🔴 Perlu pengadaan';
        if (stok <= 10) return '🟡 Terbatas';
        return '🟢 Tersedia';
    }

    function renderDatalist(kategoriId = '') {
        if (!datalistBarang) return;
        const filtered = semuaBarang.filter((item) => !kategoriId || item.kategori === kategoriId);
        datalistBarang.innerHTML = filtered.map((item) => {
            const safeNama = item.nama
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            return `<option value="${safeNama}" label="Status: ${getStockStatusLabel(item.stok)}"></option>`;
        }).join('');
    }

    function sinkronBarangTerpilih() {
        if (!inputBarang || !inputBarangId) return;

        const keyword = inputBarang.value.trim();

        if (!keyword) {
            inputBarangId.value = '';
            inputBarang.setCustomValidity('');
            return;
        }

        const keywordLower = keyword.toLowerCase();
        const cocokParsial = semuaBarang.filter((item) => item.nama.toLowerCase().includes(keywordLower));
        const ditemukan = semuaBarang.find((item) => item.nama.toLowerCase() === keywordLower);

        const kandidatKategori = ditemukan ?? (cocokParsial.length === 1 ? cocokParsial[0] : null);
        if (kandidatKategori && selectKategori && selectKategori.value !== kandidatKategori.kategori) {
            selectKategori.value = kandidatKategori.kategori;
            renderDatalist(kandidatKategori.kategori);
        }

        if (!ditemukan) {
            inputBarangId.value = '';
            inputBarang.setCustomValidity('Silakan pilih barang dari daftar autocomplete agar data valid.');
            return;
        }

        inputBarangId.value = ditemukan.id;
        inputBarang.setCustomValidity('');
    }

    if (selectKategori) {
        selectKategori.addEventListener('change', function () {
            renderDatalist(this.value);
            if (inputBarang) {
                inputBarang.value = '';
                inputBarang.setCustomValidity('');
            }
            if (inputBarangId) inputBarangId.value = '';
        });
    }

    if (inputBarang) {
        inputBarang.addEventListener('input', sinkronBarangTerpilih);
        inputBarang.addEventListener('change', sinkronBarangTerpilih);
        inputBarang.addEventListener('blur', sinkronBarangTerpilih);
    }

    renderDatalist(selectKategori ? selectKategori.value : '');
    sinkronBarangTerpilih();

    // -- Form Submit --
    const formPermintaan = document.getElementById('formPermintaan');
    const btnAjukan = document.getElementById('btnAjukan');
    if (formPermintaan) {
        formPermintaan.addEventListener('submit', function (event) {
            sinkronBarangTerpilih();

            if (!inputBarangId || !inputBarangId.value) {
                event.preventDefault();
                if (inputBarang) {
                    inputBarang.setCustomValidity('Silakan pilih barang dari daftar autocomplete.');
                    inputBarang.reportValidity();
                }
                return;
            }

            if (inputBarang) {
                inputBarang.setCustomValidity('');
            }

            if (btnAjukan) {
                btnAjukan.disabled = true;
                btnAjukan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
            }
        });
    }

    // -- Success Modal Swal --
    if (SIMATK_HOME.openSuccessModal && typeof Swal !== 'undefined') {
        const resi = SIMATK_HOME.kodeResi;
        Swal.fire({
            title: '<div class="mt-3"><i class="bi bi-check-circle-fill text-success display-4"></i></div><div class="mt-3 fw-bold">Permintaan Berhasil!</div>',
            html: `
            <div class="text-start px-2" style="font-size: 0.95rem;">
                <p class="text-center text-muted mb-4">Permohonan Anda telah diterima dan tercatat dalam sistem.</p>
                
                <div class="p-3 mb-4 rounded-3 border-start border-4 border-primary bg-light position-relative">
                    <label class="small text-muted text-uppercase fw-bold mb-1 d-block" style="letter-spacing: 0.5px;">Kode Resi:</label>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fs-4 fw-bold font-monospace text-primary tracking-wider" id="resiCode">${resi}</span>
                        <button type="button" class="btn btn-outline-primary btn-sm border-0" onclick="copyResi('${resi}')" title="Salin Kode">
                            <i class="bi bi-clipboard fs-5" id="copyIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-3">
                    <div class="flex-shrink-0">
                        <i class="bi bi-chat-dots-fill text-primary fs-5"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold small">Admin akan membalas permintaan Anda</p>
                        <p class="mb-0 text-muted small">via chat atau email dalam 1-2 hari kerja.</p>
                    </div>
                </div>

                <div class="p-2 text-center text-muted border-top pt-3 mt-3" style="font-size: 0.8rem;">
                    Simpan kode resi di atas untuk referensi Anda.
                </div>
            </div>
        `,
            showConfirmButton: true,
            confirmButtonText: 'Saya Mengerti',
            confirmButtonColor: '#3B5BDB',
            allowOutsideClick: false,
            width: '450px',
            customClass: {
                popup: 'rounded-4 shadow-lg border-0 px-2'
            }
        });
    }
});

// -- Global Helper Functions --
window.copyResi = function (text) {
    if (typeof navigator.clipboard !== 'undefined') {
        navigator.clipboard.writeText(text).then(() => {
            const icon = document.getElementById('copyIcon');
            if (icon) {
                icon.className = 'bi bi-check-lg text-success';
                setTimeout(() => {
                    icon.className = 'bi bi-clipboard';
                }, 2000);
            }
        });
    }
};

window.togglePassword = function() {
    const inputPw = document.getElementById('inputPassword');
    const ikon = document.getElementById('ikonPassword');
    if (!inputPw || !ikon) return;
    if (inputPw.type === 'password') {
        inputPw.type = 'text';
        ikon.className = 'bi bi-eye-slash';
    } else {
        inputPw.type = 'password';
        ikon.className = 'bi bi-eye';
    }
};
