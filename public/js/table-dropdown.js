/**
 * Table Dropdown Overflow Fixer (Posicionador Flutuante de Menus de Tabela)
 * 
 * Finalidade:
 * Resolve o problema comum em que os menus dropdown de acções dentro de tabelas responsivas
 * (.table-responsive) ficam cortados pela borda da tabela ou cabeçalho devido a overflow:hidden.
 * 
 * Funcionamento:
 * Ao abrir um dropdown em uma célula de acções (.action-cell), o menu (.dropdown-menu)
 * é temporariamente anexado ao <body> da página e posicionado com coordenadas absolutas
 * calculadas dinamicamente em relação ao botão (...), garantindo um z-index elevado (99999)
 * e 100% de visibilidade de todas as opções (Ver Detalhes, Editar, Eliminar).
 */
document.addEventListener('DOMContentLoaded', function () {
    // Intercepta o evento de abertura do dropdown no Bootstrap 5
    $(document).on('show.bs.dropdown', '.table-responsive .dropdown, .action-cell .dropdown', function (e) {
        var $dropdown = $(this);
        var $btn = $dropdown.find('[data-bs-toggle="dropdown"]');
        var $menu = $dropdown.find('.dropdown-menu');

        // Anexa o menu ao body apenas uma vez para sair do contexto de recorte da tabela
        if ($menu.length && !$dropdown.data('moved')) {
            $('body').append($menu);
            $dropdown.data('moved', true);
            $dropdown.data('menu-el', $menu);
        }

        // Calcula as dimensões e posição do botão no ecra
        var btnOffset = $btn.offset();
        var btnHeight = $btn.outerHeight();
        var btnWidth = $btn.outerWidth();
        var menuHeight = $menu.outerHeight();
        var menuWidth = $menu.outerWidth();

        // Determina o espaço disponível na janela (viewport)
        var windowHeight = $(window).height();
        var scrollTop = $(window).scrollTop();
        var spaceBelow = windowHeight - (btnOffset.top - scrollTop) - btnHeight;

        // Calcula se o menu deve abrir para baixo ou para cima (dropup)
        var topPos = btnOffset.top + btnHeight + 4;
        if (spaceBelow < menuHeight && (btnOffset.top - scrollTop) > menuHeight) {
            topPos = btnOffset.top - menuHeight - 4;
        }

        // Alinha o menu à direita do botão de acções
        var leftPos = btnOffset.left + btnWidth - menuWidth;
        if (leftPos < 10) leftPos = 10;

        // Aplica o posicionamento flutuante por cima de todos os elementos
        $menu.css({
            'position': 'absolute',
            'top': topPos + 'px',
            'left': leftPos + 'px',
            'z-index': '99999',
            'display': 'block'
        });
    });

    // Intercepta o fecho do dropdown para ocultar o menu anexado ao body
    $(document).on('hide.bs.dropdown', '.table-responsive .dropdown, .action-cell .dropdown', function (e) {
        var $dropdown = $(this);
        var $menu = $dropdown.data('menu-el');
        if ($menu) {
            $menu.css({
                'display': 'none',
                'top': '',
                'left': '',
                'position': '',
                'z-index': ''
            });
        }
    });
});
