/* Main layout code-highlight initialization */
document.addEventListener('DOMContentLoaded', (event) => {
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
        hljs.configure({ ignoreUnescapedHTML: true });

        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el);
        });
    // Auto-detecção dinâmica de posicionamento (Dropup vs Dropdown) em tabelas
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('show.bs.dropdown', '.table .dropdown, .custom-dropdown', function (e) {
            var $dropdown = jQuery(this);
            var $btn = $dropdown.find('[data-bs-toggle="dropdown"]');
            
            if (!$btn.length) return;

            var btnOffset = $btn.offset();
            var btnHeight = $btn.outerHeight();
            var windowScrollTop = jQuery(window).scrollTop();
            var windowHeight = jQuery(window).height();
            
            // Distância até ao fundo da janela
            var spaceBelow = windowHeight - (btnOffset.top - windowScrollTop + btnHeight);
            
            // Linha e total de linhas na tabela
            var $tr = $dropdown.closest('tr');
            var $tbody = $dropdown.closest('tbody');
            var totalRows = $tbody.length ? $tbody.find('tr').length : 0;
            var rowIndex = $tr.length ? $tr.index() : 0;

            // Se a tabela tiver poucas linhas (1 ou 2), ou for a última linha, ou o espaço inferior for reduzido (< 220px):
            if (totalRows <= 2 || (totalRows > 0 && rowIndex >= totalRows - 1) || spaceBelow < 220) {
                $dropdown.addClass('dropup');
            } else {
                $dropdown.removeClass('dropup');
            }
        });
    }
});
