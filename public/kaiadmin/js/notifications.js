document.addEventListener('DOMContentLoaded', function() {
    const markAsReadBtn = document.getElementById('markAsReadBtn');
    
    // 1. Mark All As Read
    if (markAsReadBtn) {
        markAsReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markAsReadBtn.style.pointerEvents = 'none';
            markAsReadBtn.style.opacity = '0.5';

            fetch(window.NotificationRoutes.markAllAsRead, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.NotificationRoutes.csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notifBadge');
                    if (badge) badge.remove();
                    const countText = document.getElementById('notifCountText');
                    if (countText) countText.innerText = '0';
                    markAsReadBtn.remove();
                    
                    // remove unread class from all unread items
                    document.querySelectorAll('.notif-center a.unread').forEach(el => {
                        el.classList.remove('unread');
                    });
                } else {
                    throw new Error(data.message || 'Gagal menandai dibaca');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                markAsReadBtn.style.pointerEvents = 'auto';
                markAsReadBtn.style.opacity = '1';
                
                let errorSpan = document.getElementById('markAsReadError');
                if (!errorSpan) {
                    errorSpan = document.createElement('span');
                    errorSpan.id = 'markAsReadError';
                    errorSpan.className = 'text-danger small ms-2';
                    errorSpan.innerText = 'Gagal memproses';
                    markAsReadBtn.parentNode.insertBefore(errorSpan, markAsReadBtn.nextSibling);
                    
                    setTimeout(() => {
                        if (errorSpan.parentNode) {
                            errorSpan.parentNode.removeChild(errorSpan);
                        }
                    }, 3000);
                }
            });
        });
    }

    // 2. Mark Single Notification As Read
    const notifLinks = document.querySelectorAll('.single-notif-link');
    notifLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.classList.contains('unread')) {
                e.preventDefault();
                const notifId = this.dataset.id;
                const targetUrl = this.href;

                // add loading state
                const overlay = document.createElement('div');
                overlay.className = 'notif-loading-overlay';
                overlay.innerHTML = '<div class="notif-spinner"></div>';
                this.appendChild(overlay);
                this.style.pointerEvents = 'none';

                const fetchUrl = window.NotificationRoutes.markSingleAsRead.replace(':id', notifId);

                fetch(fetchUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.NotificationRoutes.csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => {
                    // Navigate to the target URL regardless of success to not block user flow
                    window.location.href = targetUrl;
                })
                .catch(error => {
                    console.error('Error marking as read:', error);
                    window.location.href = targetUrl;
                });
            }
        });
    });
});
