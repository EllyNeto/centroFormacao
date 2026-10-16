/**
 * Script para acionamento da impressão na página de Lista de Formandos
 * Ficheiro externo de JavaScript para desvincular scripts do Blade.
 */
document.addEventListener('DOMContentLoaded', function () {
    const btnPrint = document.getElementById('btnPrintDocument');
    if (btnPrint) {
        btnPrint.addEventListener('click', function () {
            window.print();
        });
    }
});
