/**
 * ClinixPro - Modern JavaScript
 * Enhanced interactions and animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const modernSidebar = document.getElementById('modernSidebar');
    
    if (sidebarToggle && modernSidebar) {
        sidebarToggle.addEventListener('click', function() {
            modernSidebar.classList.toggle('open');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024) {
            if (modernSidebar && modernSidebar.classList.contains('open')) {
                if (!modernSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    modernSidebar.classList.remove('open');
                }
            }
        }
    });
    
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.animate-slide-up, .animate-slide-in, .animate-fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(el);
    });
    
    // Card hover effects
    document.querySelectorAll('.modern-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Button ripple effect
    document.querySelectorAll('.modern-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Input focus effects
    document.querySelectorAll('.modern-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform 0.2s ease';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });
    
    // Table row hover effects
    document.querySelectorAll('.modern-table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.background = '#f3f4f6';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.background = '';
        });
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
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
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.modern-card[style*="background: #d1fae5"], .modern-card[style*="background: #fee2e2"], .modern-card[style*="background: #fef3c7"], .modern-card[style*="background: #dbeafe"]').forEach(card => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-10px)';
            setTimeout(() => card.remove(), 500);
        });
    }, 5000);
    
    // Loading state for forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise" style="animation: spin 1s linear infinite;"></i> Processing...';
            }
        });
    });
    
    // Add spin animation
    const spinStyle = document.createElement('style');
    spinStyle.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(spinStyle);
    
    // Sidebar active state indicator
    const currentPath = window.location.pathname;
    document.querySelectorAll('.modern-sidebar-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
    
    // Responsive sidebar
    function handleResize() {
        if (window.innerWidth >= 1024) {
            if (modernSidebar) {
                modernSidebar.classList.remove('open');
            }
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize();
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Escape key closes sidebar on mobile
        if (e.key === 'Escape' && window.innerWidth < 1024) {
            if (modernSidebar && modernSidebar.classList.contains('open')) {
                modernSidebar.classList.remove('open');
            }
        }
    });
    
    // Lazy load images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Add smooth page transitions
    document.querySelectorAll('a:not([target="_blank"])').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript:') && !href.startsWith('mailto:')) {
                e.preventDefault();
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
    });
    
    // Initialize page with fade-in
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});

// Utility functions
const ModernUtils = {
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    },
    
    formatTime(date) {
        return new Date(date).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    }
};

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernUtils;
}
