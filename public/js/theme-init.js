/**
 * Theme Init & Dark Mode Persistence Script
 * 
 * Este ficheiro gere a persistência do modo escuro/claro e inicializações de UI
 * de forma desacoplada dos ficheiros de layout Blade.
 */

// 1. Funções globais de manipulação de cookies e localStorage
function setCookie(cname, cvalue, exdays) {
    exdays = exdays || 365;
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Lax";
    if (cname === 'version') {
        try { localStorage.setItem('version', cvalue); } catch(e){}
    }
}

function getCookie(cname) {
    if (cname === 'version') {
        try {
            var localVal = localStorage.getItem('version');
            if (localVal) return localVal;
        } catch(e){}
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
(function() {
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

// 3. Gestão de eventos ao carregar o DOM
if (typeof jQuery !== 'undefined') {
    function formatDataTablesSelects() {
        if (jQuery.fn.selectpicker) {
            jQuery('.dataTables_length select').each(function() {
                var $this = jQuery(this);
                if (!$this.hasClass('selectpicker') && !$this.parent().hasClass('bootstrap-select')) {
                    $this.addClass('default-select').selectpicker();
                }
            });
        }
    }

    jQuery(document).on('init.dt', function() {
        formatDataTablesSelects();
    });

    jQuery(document).ready(function() {
        // Desativar listeners anteriores do template para evitar conflitos/duplicação
        jQuery('.dz-theme-mode').off('click');
        jQuery(document).off('click', '.dz-theme-mode');

        // Sincronizar o estado dos ícones/botões de tema na inicialização
        var currentTheme = jQuery('body').attr('data-theme-version') || getCookie('version') || 'light';
        if (currentTheme === 'dark') {
            jQuery('.dz-theme-mode').addClass('active');
        } else {
            jQuery('.dz-theme-mode').removeClass('active');
        }

        // Manipulador único para alternar entre modo claro e escuro
        jQuery(document).on('click', '.dz-theme-mode', function(e) {
            e.preventDefault();
            var isDark = jQuery('body').attr('data-theme-version') === 'dark';
            var newTheme = isDark ? 'light' : 'dark';
            
            jQuery('body').attr('data-theme-version', newTheme);
            setCookie('version', newTheme);

            if (newTheme === 'dark') {
                jQuery('.dz-theme-mode').addClass('active');
            } else {
                jQuery('.dz-theme-mode').removeClass('active');
            }

            if (jQuery.fn.selectpicker) {
                jQuery('.default-select').selectpicker('refresh');
            }
        });

        // Formatar os selects de paginação de DataTables
        setTimeout(formatDataTablesSelects, 200);
        setTimeout(formatDataTablesSelects, 600);
    });
}
