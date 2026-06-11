/**
 * ClinixPro - Hospital Management System
 * Enhanced JavaScript for Modern UX
 */

// ============================================
// Global Configuration
// ============================================
const ClinixPro = {
    version: '2.0.0',
    csrfToken: null,
    baseUrl: window.location.origin,
    
    // Initialize the application
    init: function() {
        this.setCsrfToken();
        this.initTooltips();
        this.initPopovers();
        this.initAlerts();
        this.initSidebar();
        this.initForms();
        this.initSearch();
        this.initDateInputs();
        this.initPhoneInputs();
        this.initDynamicFields();
        this.initLoadingStates();
        this.initAnimations();
    },
    
    // Set CSRF token
    setCsrfToken: function() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) {
            this.csrfToken = meta.getAttribute('content');
        }
    },
    
    // Initialize tooltips
    initTooltips: function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Initialize popovers
    initPopovers: function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function(popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl);
        });
    },
    
    // Initialize alerts with auto-hide
    initAlerts: function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    },
    
    // Initialize sidebar toggle
    initSidebar: function() {
        const sidebarToggle = document.querySelector('[data-toggle="sidebar"], #sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        // Create overlay if it doesn't exist
        let overlay = document.querySelector('.sidebar-overlay');
        if (!overlay && sidebar) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:999;display:none;opacity:0;transition:opacity 0.3s ease;';
            document.body.appendChild(overlay);
        }
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                if (overlay) {
                    overlay.style.display = 'block';
                    setTimeout(() => overlay.style.opacity = '1', 10);
                }
            });
        }
        
        // Close sidebar when clicking overlay
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 300);
            });
        }
        
        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                if (overlay) {
                    overlay.style.opacity = '0';
                    setTimeout(() => overlay.style.display = 'none', 300);
                }
            }
        });
        
        // Close sidebar when clicking a link on mobile
        if (sidebar) {
            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        sidebar.classList.remove('show');
                        if (overlay) {
                            overlay.style.opacity = '0';
                            setTimeout(() => overlay.style.display = 'none', 300);
                        }
                    }
                });
            });
        }
    },
    
    // Initialize form validation
    initForms: function() {
        // Confirm delete actions with custom modal
        document.querySelectorAll('form[data-confirm]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const message = form.getAttribute('data-confirm');
                ClinixPro.showConfirm(message, function() {
                    form.submit();
                });
                e.preventDefault();
            });
        });
        
        // Form validation with real-time feedback
        document.querySelectorAll('form[data-validate]').forEach(function(form) {
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    if (input.value.trim() !== '') {
                        input.classList.add('was-validated');
                        if (!input.checkValidity()) {
                            input.classList.add('is-invalid');
                            input.classList.remove('is-valid');
                        } else {
                            input.classList.add('is-valid');
                            input.classList.remove('is-invalid');
                        }
                    }
                });
                
                input.addEventListener('input', function() {
                    if (input.classList.contains('is-invalid')) {
                        input.classList.remove('is-invalid');
                    }
                });
            });
            
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show first invalid field
                    const firstInvalid = form.querySelector(':invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
                form.classList.add('was-validated');
            });
        });
        
        // Add CSRF token to all AJAX requests
        if (window.fetch) {
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                if (args[1] && args[1].method === 'POST') {
                    args[1].headers = args[1].headers || {};
                    if (ClinixPro.csrfToken) {
                        args[1].headers['X-CSRF-Token'] = ClinixPro.csrfToken;
                    }
                }
                return originalFetch.apply(this, args);
            };
        }
    },
    
    // Initialize search with debounce
    initSearch: function() {
        const searchInputs = document.querySelectorAll('[data-search]');
        searchInputs.forEach(function(input) {
            let timeout;
            let searchResults;
            
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = input.value.trim();
                const url = input.getAttribute('data-search');
                
                // Remove existing results
                if (searchResults) {
                    searchResults.remove();
                    searchResults = null;
                }
                
                timeout = setTimeout(function() {
                    if (query.length >= 2) {
                        ClinixPro.showLoading(input);
                        
                        fetch(url + '?q=' + encodeURIComponent(query) + '&ajax=1', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            ClinixPro.hideLoading(input);
                            if (data.results && data.results.length > 0) {
                                searchResults = ClinixPro.showSearchResults(input, data.results);
                            }
                        })
                        .catch(error => {
                            ClinixPro.hideLoading(input);
                            console.error('Search error:', error);
                        });
                    }
                }, 300);
            });
            
            // Hide results on click outside
            document.addEventListener('click', function(e) {
                if (searchResults && !input.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.remove();
                    searchResults = null;
                }
            });
        });
    },
    
    // Show search results dropdown
    showSearchResults: function(input, results) {
        const dropdown = document.createElement('div');
        dropdown.className = 'search-results dropdown-menu show';
        dropdown.style.position = 'absolute';
        dropdown.style.top = '100%';
        dropdown.style.left = '0';
        dropdown.style.right = '0';
        dropdown.style.zIndex = '1000';
        dropdown.style.maxHeight = '300px';
        dropdown.style.overflowY = 'auto';
        
        results.forEach(function(result) {
            const item = document.createElement('a');
            item.className = 'dropdown-item';
            item.href = result.url;
            item.textContent = result.name;
            dropdown.appendChild(item);
        });
        
        input.parentElement.style.position = 'relative';
        input.parentElement.appendChild(dropdown);
        
        return dropdown;
    },
    
    // Initialize date inputs with constraints
    initDateInputs: function() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        const today = new Date().toISOString().split('T')[0];
        
        dateInputs.forEach(function(input) {
            // Set max date to today for date of birth
            if (input.id === 'date_of_birth' || input.name === 'date_of_birth') {
                input.max = today;
            }
            
            // Set min date to today for future dates
            if (input.id === 'appointment_date' || input.name === 'appointment_date') {
                input.min = today;
            }
        });
    },
    
    // Initialize phone number formatting
    initPhoneInputs: function() {
        const phoneInputs = document.querySelectorAll('input[type="tel"], input[data-format="phone"]');
        
        phoneInputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                // Format as (XXX) XXX-XXXX
                if (value.length >= 10) {
                    value = '(' + value.slice(0, 3) + ') ' + value.slice(3, 6) + '-' + value.slice(6, 10);
                } else if (value.length >= 6) {
                    value = '(' + value.slice(0, 3) + ') ' + value.slice(3, 6);
                } else if (value.length >= 3) {
                    value = '(' + value.slice(0, 3);
                }
                
                e.target.value = value;
            });
            
            input.addEventListener('blur', function(e) {
                // Validate phone number format
                const value = e.target.value;
                const phoneRegex = /^\(\d{3}\) \d{3}-\d{4}$/;
                if (value && !phoneRegex.test(value)) {
                    e.target.classList.add('is-invalid');
                }
            });
        });
    },
    
    // Initialize dynamic form fields
    initDynamicFields: function() {
        // Add dynamic fields
        document.querySelectorAll('[data-add-field]').forEach(function(button) {
            button.addEventListener('click', function() {
                const template = document.querySelector(button.getAttribute('data-template'));
                const container = document.querySelector(button.getAttribute('data-container'));
                
                if (template && container) {
                    const clone = template.content.cloneNode(true);
                    const field = clone.firstElementChild;
                    
                    // Add animation
                    field.style.opacity = '0';
                    field.style.transform = 'translateY(-10px)';
                    container.appendChild(field);
                    
                    // Trigger animation
                    setTimeout(function() {
                        field.style.transition = 'all 0.3s ease';
                        field.style.opacity = '1';
                        field.style.transform = 'translateY(0)';
                    }, 10);
                    
                    // Initialize new field's validation
                    const newInputs = field.querySelectorAll('input, select, textarea');
                    newInputs.forEach(function(input) {
                        input.addEventListener('blur', function() {
                            if (input.value.trim() !== '') {
                                input.classList.add('was-validated');
                                if (!input.checkValidity()) {
                                    input.classList.add('is-invalid');
                                } else {
                                    input.classList.add('is-valid');
                                }
                            }
                        });
                    });
                }
            });
        });
        
        // Remove dynamic fields
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-remove-field]') || e.target.closest('[data-remove-field]')) {
                const button = e.target.matches('[data-remove-field]') ? e.target : e.target.closest('[data-remove-field]');
                const field = button.closest(button.getAttribute('data-field'));
                
                if (field) {
                    field.style.transition = 'all 0.3s ease';
                    field.style.opacity = '0';
                    field.style.transform = 'translateX(-20px)';
                    
                    setTimeout(function() {
                        field.remove();
                    }, 300);
                }
            }
        });
    },
    
    // Initialize loading states
    initLoadingStates: function() {
        // Add loading state to buttons on form submit
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.dataset.originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                }
            });
        });
    },
    
    // Initialize animations
    initAnimations: function() {
        // Add fade-in animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(function(card, index) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(function() {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Add hover effects to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(function(card) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    },
    
    // Show loading indicator
    showLoading: function(element) {
        const loading = document.createElement('span');
        loading.className = 'loading-indicator';
        loading.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        element.parentElement.appendChild(loading);
        element.dataset.loading = 'true';
    },
    
    // Hide loading indicator
    hideLoading: function(element) {
        const loading = element.parentElement.querySelector('.loading-indicator');
        if (loading) {
            loading.remove();
        }
        element.dataset.loading = 'false';
    },
    
    // Show toast notification
    showToast: function(type, message, duration = 5000) {
        const toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            const container = document.createElement('div');
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1100';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.querySelector('.toast-container').appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, { delay: duration });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    },
    
    // Show confirmation modal
    showConfirm: function(message, callback) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-hidden', 'true');
        
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary confirm-btn">Confirm</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        modal.querySelector('.confirm-btn').addEventListener('click', function() {
            bsModal.hide();
            callback();
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            modal.remove();
        });
    },
    
    // Format currency
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    // Format date
    formatDate: function(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    
    // Format date time
    formatDateTime: function(dateString) {
        return new Date(dateString).toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Format relative time
    formatRelativeTime: function(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (seconds < 60) return 'Just now';
        if (minutes < 60) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
        if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
        if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`;
        
        return this.formatDate(dateString);
    },
    
    // Debounce function
    debounce: function(func, wait) {
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
    
    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};

// ============================================
// Initialize on DOM ready
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    ClinixPro.init();
});

// ============================================
// Legacy function compatibility
// ============================================
function showAlert(type, message) {
    ClinixPro.showToast(type, message);
}

function formatCurrency(amount) {
    return ClinixPro.formatCurrency(amount);
}

function formatDate(dateString) {
    return ClinixPro.formatDate(dateString);
}

function formatDateTime(dateString) {
    return ClinixPro.formatDateTime(dateString);
}
