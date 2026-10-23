/**
 * Theme Init, Dark Mode Persistence & Global UX Enhancements
 * Centro de Formação Profissional
 *
 * Responsabilidades:
 * 1. Persistência imediata de Modo Claro / Escuro (cookies + localStorage).
 * 2. Alternador de tema (.dz-theme-mode) e ecrã inteiro (.dz-fullscreen).
 * 3. Modais de confirmação elegantes com SweetAlert2 em formulários de eliminação.
 * 4. Estado de carregamento automático (spinner) nos botões de submissão de formulários.
 */

// 1. Funções globais de manipulação de cookies e localStorage
function setCookie(cname, cvalue, exdays) {
    exdays = exdays || 365;
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Lax";
    if (cname === 'version') {
        try { localStorage.setItem('version', cvalue); } catch (e) {}
    }
}

function getCookie(cname) {
    if (cname === 'version') {
        try {
            var localVal = localStorage.getItem('version');
            if (localVal) return localVal;
        } catch (e) {}
    }
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return cname === 'version' ? 'light' : "";
}

// 2. Aplicar o tema guardado imediatamente ao carregar o ficheiro JS
(function () {
    var savedVersion = getCookie('version');
    if (savedVersion !== 'dark' && savedVersion !== 'light') {
        savedVersion = 'light';
    }
    window.themeVersion = savedVersion;
    document.documentElement.setAttribute('data-theme-version', savedVersion);
    if (document.body) {
        document.body.setAttribute('data-theme-version', savedVersion);
    }
})();

// 3. Gestão de eventos e UX global ao carregar o DOM
(function () {
    'use strict';

    if (window.__cfThemeInitBound) {
        return;
    }
    window.__cfThemeInitBound = true;

    function formatDataTablesSelects() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.selectpicker) {
            jQuery('.dataTables_length select').each(function () {
                var $this = jQuery(this);
                if (!$this.hasClass('selectpicker') && !$this.parent().hasClass('bootstrap-select')) {
                    $this.addClass('default-select').selectpicker();
                }
            });
        }
    }

    function applyThemeState(theme) {
        if (theme !== 'dark' && theme !== 'light') {
            theme = 'light';
        }
        window.themeVersion = theme;
        document.documentElement.setAttribute('data-theme-version', theme);
        if (document.body) {
            document.body.setAttribute('data-theme-version', theme);
        }
        if (typeof window.dlabSettingsOptions === 'object' && window.dlabSettingsOptions !== null) {
            window.dlabSettingsOptions.version = theme;
        }
        setCookie('version', theme);

        document.querySelectorAll('.dz-theme-mode').forEach(function (el) {
            if (theme === 'dark') {
                el.classList.add('active');
                el.setAttribute('title', 'Mudar para Modo Claro');
            } else {
                el.classList.remove('active');
                el.setAttribute('title', 'Mudar para Modo Escuro');
            }
        });

        var labelEl = document.getElementById('cf-theme-label');
        if (labelEl) {
            labelEl.textContent = theme === 'dark' ? 'Modo Claro' : 'Modo Escuro';
        }

        if (typeof jQuery !== 'undefined' && jQuery.fn.selectpicker) {
            try { jQuery('.default-select').selectpicker('refresh'); } catch (err) {}
        }

        try {
            window.dispatchEvent(new CustomEvent('cf:theme-changed', { detail: { theme: theme } }));
        } catch (err) {}
    }

    document.addEventListener('DOMContentLoaded', function () {
        var savedVersion = getCookie('version');
        if (savedVersion !== 'dark' && savedVersion !== 'light') {
            savedVersion = 'light';
        }
        applyThemeState(savedVersion);

        // Remove duplicate jQuery handlers registered by custom.min.js after ready
        setTimeout(function () {
            if (typeof jQuery !== 'undefined') {
                jQuery('.dz-theme-mode').off('click');
                jQuery('.dz-fullscreen').off('click');
            }
            applyThemeState(getCookie('version'));
        }, 100);

        setTimeout(function () {
            if (typeof jQuery !== 'undefined') {
                jQuery('.dz-theme-mode').off('click');
            }
            applyThemeState(getCookie('version'));
        }, 1600);

        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('init.dt', function () {
                formatDataTablesSelects();
            });

            setTimeout(formatDataTablesSelects, 200);
            setTimeout(formatDataTablesSelects, 600);
        }

        // Alternador de Modo Claro / Escuro (Capture phase para impedir duplo toggle do custom.min.js)
        document.addEventListener('click', function (e) {
            var themeBtn = e.target.closest('.dz-theme-mode');
            if (themeBtn) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }

                var currentTheme = document.body.getAttribute('data-theme-version') || getCookie('version') || 'light';
                var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                applyThemeState(newTheme);
                return;
            }

            // Alternador de Ecrã Inteiro (.dz-fullscreen)
            var fullBtn = e.target.closest('.dz-fullscreen');
            if (fullBtn) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }

                if (!document.fullscreenElement) {
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }
        }, true);

        // Intercetor elegante de confirmação de eliminação (SweetAlert2)
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(function (form) {
            var rawOnsubmit = form.getAttribute('onsubmit') || '';
            var match = rawOnsubmit.match(/confirm\(['"](.+?)['"]\)/);
            if (match && match[1]) {
                form.setAttribute('data-confirm', match[1].replace(/\\'/g, "'"));
                form.removeAttribute('onsubmit');
            }
        });

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            var confirmMsg = form.getAttribute('data-confirm');
            if (confirmMsg && !form.getAttribute('data-confirmed')) {
                e.preventDefault();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Confirmar Operação',
                        text: confirmMsg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Sim, confirmar',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true
                    }).then(function (result) {
                        var isConfirmed = result && (result.isConfirmed || result.value === true);
                        if (isConfirmed) {
                            form.setAttribute('data-confirmed', 'true');
                            form.submit();
                        }
                    });
                } else {
                    if (window.confirm(confirmMsg)) {
                        form.setAttribute('data-confirmed', 'true');
                        form.submit();
                    }
                }
                return;
            }

            // Feedback visual de carregamento no botão de submissão (anti double-click)
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled && !submitBtn.classList.contains('dropdown-item')) {
                setTimeout(function () {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> A guardar...';
                }, 10);
            }
        });
    });
})();
