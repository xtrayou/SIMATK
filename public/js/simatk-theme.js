document.addEventListener('DOMContentLoaded', function () {
    initSidebarToggle();
    initNavbarNotifications();
});

function initSidebarToggle() {
    var sidebarToggle = document.getElementById('sidebarToggle');
    if (!sidebarToggle) {
        return;
    }

    sidebarToggle.addEventListener('click', function (event) {
        event.preventDefault();

        if (window.innerWidth <= 992) {
            document.body.classList.toggle('sidebar-open');
            return;
        }

        document.body.classList.toggle('sidebar-hidden');
    });

    document.addEventListener('click', function (event) {
        if (window.innerWidth > 992 || !document.body.classList.contains('sidebar-open')) {
            return;
        }

        var sidebar = document.getElementById('sidebar');
        var clickedInSidebar = sidebar && sidebar.contains(event.target);
        var clickedToggle = sidebarToggle.contains(event.target);

        if (!clickedInSidebar && !clickedToggle) {
            document.body.classList.remove('sidebar-open');
        }
    });
}

function initNavbarNotifications() {
    var trigger = document.getElementById('navbarNotifTrigger');
    var badge = document.getElementById('navbarNotifBadge');
    var list = document.getElementById('navbarNotifList');
    var empty = document.getElementById('navbarNotifEmpty');

    if (!trigger || !badge || !list || !empty) {
        return;
    }

    var latestEndpoint = trigger.getAttribute('data-api-latest');
    var notificationsPage = trigger.getAttribute('data-notif-page') || '/notifications';

    if (!latestEndpoint) {
        return;
    }

    var previousUnreadCount = null;
    var hasLoadedOnce = false;
    var audioContext = null;

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function formatCreatedAt(value) {
        if (!value) {
            return '';
        }

        var date = new Date(String(value).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) {
            return '';
        }

        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function updateBadge(count) {
        var safeCount = Number.isFinite(count) ? count : 0;
        badge.textContent = safeCount > 99 ? '99+' : String(safeCount);
        badge.classList.toggle('d-none', safeCount <= 0);
    }

    function playNotificationSound() {
        try {
            if (!audioContext) {
                var AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) {
                    return;
                }
                audioContext = new AudioCtx();
            }

            if (audioContext.state === 'suspended') {
                audioContext.resume();
            }

            var now = audioContext.currentTime;
            var oscillator = audioContext.createOscillator();
            var gainNode = audioContext.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.setValueAtTime(880, now);

            gainNode.gain.setValueAtTime(0.0001, now);
            gainNode.gain.exponentialRampToValueAtTime(0.12, now + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.0001, now + 0.22);

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.start(now);
            oscillator.stop(now + 0.24);
        } catch (error) {
            // Abaikan error audio agar tidak mengganggu fungsi utama notifikasi.
        }
    }

    function renderNotificationItems(items) {
        if (!Array.isArray(items) || items.length === 0) {
            list.innerHTML = '';
            empty.style.display = 'block';
            return;
        }

        empty.style.display = 'none';

        list.innerHTML = items.map(function (item) {
            var itemUrl = item && item.url ? item.url : notificationsPage;
            var icon = item && item.icon ? item.icon : 'bi-bell';
            var color = item && item.color ? item.color : 'secondary';
            var title = item && item.title ? item.title : 'Notifikasi';
            var message = item && item.message ? item.message : '';
            var createdAt = formatCreatedAt(item ? item.created_at : '');

            return (
                '<a class="dropdown-item notif-item" href="' + escapeHtml(itemUrl) + '">' +
                    '<div class="d-flex align-items-start gap-2">' +
                        '<div class="notif-icon text-' + escapeHtml(color) + '"><i class="bi ' + escapeHtml(icon) + '"></i></div>' +
                        '<div class="notif-content">' +
                            '<div class="notif-title">' + escapeHtml(title) + '</div>' +
                            '<div class="notif-message">' + escapeHtml(message) + '</div>' +
                            (createdAt ? '<div class="notif-time">' + escapeHtml(createdAt) + '</div>' : '') +
                        '</div>' +
                    '</div>' +
                '</a>'
            );
        }).join('');
    }

    function fetchNotifications() {
        fetch(latestEndpoint + '?limit=6', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.json();
            })
            .then(function (payload) {
                if (!payload || payload.status !== true) {
                    return;
                }

                var unreadCount = parseInt(payload.unread_count, 10) || 0;
                updateBadge(unreadCount);
                renderNotificationItems(payload.data || []);

                if (hasLoadedOnce && previousUnreadCount !== null && unreadCount > previousUnreadCount) {
                    playNotificationSound();
                }

                previousUnreadCount = unreadCount;
                hasLoadedOnce = true;
            })
            .catch(function () {
                // Diamkan error network sementara; polling berikutnya akan mencoba lagi.
            });
    }

    trigger.addEventListener('shown.bs.dropdown', fetchNotifications);

    fetchNotifications();
    window.setInterval(fetchNotifications, 30000);
}
