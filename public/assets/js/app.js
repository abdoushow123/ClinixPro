/**
 * ClinixPro - Application JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const appSidebar = document.getElementById('appSidebar');
    
    if (sidebarToggle && appSidebar) {
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth >= 1024) {
                document.body.classList.toggle('sidebar-mini');
            } else {
                appSidebar.classList.toggle('-translate-x-full');
                appSidebar.classList.toggle('translate-x-0');
            }
        });
    }

    // Notification Dropdown Toggle
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationCountText = document.getElementById('notificationCountText');
    
    console.log('Notification button found:', !!notificationBtn);
    console.log('Notification dropdown found:', !!notificationDropdown);
    
    if (notificationBtn && notificationDropdown) {
        // Check if there are unread notifications on page load
        const hasUnread = notificationCountText && parseInt(notificationCountText.textContent) > 0;
        if (hasUnread) {
            notificationDropdown.style.display = 'block';
            console.log('Dropdown opened automatically (unread notifications)');
        } else {
            notificationDropdown.style.display = 'none';
            console.log('Dropdown closed by default (no unread notifications)');
        }
        
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = notificationDropdown.style.display !== 'none';
            console.log('Dropdown clicked, currently visible:', isVisible);
            
            if (isVisible) {
                notificationDropdown.style.display = 'none';
                console.log('Dropdown closed');
            } else {
                notificationDropdown.style.display = 'block';
                console.log('Dropdown opened');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.style.display = 'none';
                console.log('Dropdown closed (clicked outside)');
            }
        });
    } else {
        console.error('Notification elements not found!');
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024) {
            if (appSidebar && !appSidebar.classList.contains('-translate-x-full')) {
                if (!appSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    appSidebar.classList.add('-translate-x-full');
                    appSidebar.classList.remove('translate-x-0');
                }
            }
        }
    });
    
    // Confirm delete actions
    document.querySelectorAll('[data-confirm]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.getAttribute('data-confirm');
            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Loading state for forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin"></i> Processing...';
            }
        });
    });
    
    // Responsive sidebar
    function handleResize() {
        if (window.innerWidth >= 1024) {
            if (appSidebar) {
                appSidebar.classList.remove('-translate-x-full');
                appSidebar.classList.add('translate-x-0');
            }
        } else {
            if (appSidebar) {
                appSidebar.classList.add('-translate-x-full');
                appSidebar.classList.remove('translate-x-0');
                document.body.classList.remove('sidebar-mini');
            }
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize();
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth < 1024) {
            if (appSidebar && !appSidebar.classList.contains('-translate-x-full')) {
                appSidebar.classList.add('-translate-x-full');
                appSidebar.classList.remove('translate-x-0');
            }
        }
    });

});

// Notifications AJAX functions
function markNotificationRead(id, element) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(res => res.json()).then(data => {
        if (data.success) {
            // Remove unread styling
            element.classList.remove('bg-primary-50/50', 'dark:bg-primary-900/10');
            const dot = element.querySelector('.bg-primary-500.rounded-full');
            if (dot) dot.parentElement.remove();
            
            // Update badge
            updateNotificationBadge(-1);
        }
    });
}

function markAllNotificationsRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(res => res.json()).then(data => {
        if (data.success) {
            // Remove unread styling from all
            document.querySelectorAll('[onclick^="markNotificationRead"]').forEach(el => {
                el.classList.remove('bg-primary-50/50', 'dark:bg-primary-900/10');
                const dot = el.querySelector('.bg-primary-500.rounded-full');
                if (dot) dot.parentElement.remove();
            });
            // Update badge to 0
            updateNotificationBadge(0, true);
        }
    });
}

function updateNotificationBadge(change, setZero = false) {
    const textEl = document.getElementById('notificationCountText');
    const badge = document.getElementById('notificationBadge');
    
    if (textEl) {
        let current = parseInt(textEl.textContent) || 0;
        let next = setZero ? 0 : Math.max(0, current + change);
        textEl.textContent = next + ' new';
        
        if (next === 0 && badge) {
            badge.remove();
        }
    }
}
