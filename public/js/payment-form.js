/**
 * Script Externo de Suporte: Módulo de Pagamentos (Payment)
 * Centro de Formação Profissional
 *
 * Responsável por:
 * 1. Atualização dinâmica dos dados do candidato ao selecionar a inscrição.
 * 2. Atualização do campo de valor readonly com o montante fixado (R5).
 * 3. Exibição do aviso de repetição de curso (R3).
 * 4. Anti double-submit: desativa botão de submissão após 1.º clique.
 * 5. Mostrar/ocultar campo de motivo de anulação ao editar um Pendente.
 * 6. Geração automática de referência de recibo.
 * 7. Acionamento limpo de impressão do recibo de pagamento.
 *
 * Em conformidade estrita com as regras: ZERO JavaScript nos ficheiros Blade.
 */

(function () {
    'use strict';

    /**
     * Formata um valor numérico para representação monetária angolana (Kz).
     *
     * @param {number} value
     * @returns {string}
     */
    function formatKz(value) {
        var num = parseFloat(value) || 0;
        return num.toLocaleString('pt-PT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' Kz';
    }

    /**
     * Inicializa a interatividade do formulário de criação de pagamentos.
     * — Campo valor é readonly; é atualizado dinamicamente ao selecionar a inscrição.
     * — Aviso de repetição visível se data-is-repeat="1".
     */
    function initPaymentForm() {
        var enrollmentSelect = document.getElementById('enrollment_id');
        var valueDisplay     = document.getElementById('value_display');
        var paymentValueInput = document.getElementById('payment_value');
        var repeatAlert      = document.getElementById('repeatCourseAlert');
        var previewBox       = document.getElementById('candidatePreviewBox');
        var previewPlaceholder = document.getElementById('candidatePreviewPlaceholder');
        var previewContent   = document.getElementById('candidatePreviewContent');

        // Elementos de pré-visualização
        var previewName        = document.getElementById('previewCandidateName');
        var previewBi          = document.getElementById('previewCandidateBi');
        var previewPhone       = document.getElementById('previewCandidatePhone');
        var previewCourse      = document.getElementById('previewCourseName');
        var previewCoursePrice = document.getElementById('previewCoursePrice');
        var previewTotalPaid   = document.getElementById('previewTotalPaid');
        var previewBalance     = document.getElementById('previewBalance');
        var previewAvatar      = document.getElementById('previewCandidateAvatar');
        var previewAvatarIcon  = document.getElementById('previewCandidateAvatarIcon');

        // Botão de geração de referência
        var btnGenerateRef = document.getElementById('btnGenerateReference');
        var referenceInput = document.getElementById('reference');

        if (!enrollmentSelect) {
            return;
        }

        /**
         * Atualiza os cartões de informação do candidato selecionado.
         * Regra R5: o valor é fixado ao montante do saldo em dívida (ou preço integral em repetição).
         * Regra R3: mostra aviso de repetição se ciclo anterior concluído.
         */
        function updateCandidatePreview() {
            var selectedOption = enrollmentSelect.options[enrollmentSelect.selectedIndex];

            if (!selectedOption || !selectedOption.value) {
                if (previewPlaceholder) previewPlaceholder.classList.remove('d-none');
                if (previewContent)     previewContent.classList.add('d-none');
                if (previewBox)         previewBox.classList.remove('is-active');
                if (valueDisplay)       valueDisplay.value = '—';
                if (paymentValueInput)  paymentValueInput.value = 0;
                if (repeatAlert)        repeatAlert.classList.add('d-none');
                return;
            }

            var name        = selectedOption.getAttribute('data-name')         || '';
            var bi          = selectedOption.getAttribute('data-bi')           || '';
            var phone       = selectedOption.getAttribute('data-phone')        || '';
            var course      = selectedOption.getAttribute('data-course')       || 'Não atribuído';
            var courseValue = parseFloat(selectedOption.getAttribute('data-course-value')) || 0;
            var paid        = parseFloat(selectedOption.getAttribute('data-paid'))         || 0;
            var balance     = parseFloat(selectedOption.getAttribute('data-balance'))      || 0;
            var isRepeat    = selectedOption.getAttribute('data-is-repeat')    === '1';
            var image       = selectedOption.getAttribute('data-image')        || '';

            if (previewName)        previewName.textContent        = name;
            if (previewBi)          previewBi.textContent          = bi;
            if (previewPhone)       previewPhone.textContent       = phone;
            if (previewCourse)      previewCourse.textContent      = course;
            if (previewCoursePrice) previewCoursePrice.textContent = formatKz(courseValue);
            if (previewTotalPaid)   previewTotalPaid.textContent   = formatKz(paid);
            if (previewBalance)     previewBalance.textContent     = formatKz(balance);

            if (previewAvatar && previewAvatarIcon) {
                if (image) {
                    previewAvatar.src = image;
                    previewAvatar.classList.remove('d-none');
                    previewAvatarIcon.classList.add('d-none');
                } else {
                    previewAvatar.classList.add('d-none');
                    previewAvatarIcon.classList.remove('d-none');
                }
            }

            if (previewPlaceholder) previewPlaceholder.classList.add('d-none');
            if (previewContent)     previewContent.classList.remove('d-none');
            if (previewBox)         previewBox.classList.add('is-active');

            // R5: atualiza o campo de valor readonly e o input hidden
            var amount = isRepeat ? courseValue : balance;
            if (valueDisplay) {
                valueDisplay.value = formatKz(amount);
            }
            if (paymentValueInput) {
                paymentValueInput.value = amount;
            }

            // R3: mostra/oculta aviso de repetição
            if (repeatAlert) {
                if (isRepeat) {
                    repeatAlert.classList.remove('d-none');
                } else {
                    repeatAlert.classList.add('d-none');
                }
            }
        }

        // Event listener para alteração da seleção
        enrollmentSelect.addEventListener('change', updateCandidatePreview);

        // Inicializa Select2 no campo de seleção de inscrição
        if (window.jQuery) {
            if (window.jQuery.fn.select2) {
                window.jQuery(enrollmentSelect).select2({
                    placeholder: "Selecione o candidato...",
                    allowClear: true,
                    width: '100%'
                });
            }
            window.jQuery(enrollmentSelect).on('change select2:select select2:clear', updateCandidatePreview);
        }

        // Ação de geração de referência automática
        if (btnGenerateRef && referenceInput) {
            btnGenerateRef.addEventListener('click', function () {
                var dateStr   = new Date().toISOString().slice(0, 10).replace(/-/g, '');
                var randomNum = Math.floor(1000 + Math.random() * 9000);
                referenceInput.value = 'REC-' + dateStr + '-' + randomNum;
            });
        }

        // Executa na inicialização para caso de opção pré-selecionada
        updateCandidatePreview();
    }

    /**
     * Anti double-submit: desativa o botão de submissão após o 1.º clique
     * para evitar duplicação acidental de pagamentos.
     */
    function initAntiDoubleSubmit() {
        var forms = document.querySelectorAll('form');
        forms.forEach(function (form) {
            form.addEventListener('submit', function () {
                var submitBtn = form.querySelector('button[type="submit"][id^="btnSubmit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> A processar...';
                }
            });
        });
    }

    /**
     * Mostrar/ocultar campo de motivo de anulação no formulário de edição (Pendente).
     * Acionado quando o utilizador muda o status para 'Anulado'.
     */
    function initCancelReasonToggle() {
        var statusSelect      = document.getElementById('status');
        var cancelReasonBlock = document.getElementById('cancelReasonBlock');
        var cancelReasonInput = document.getElementById('cancellation_reason');

        if (!statusSelect || !cancelReasonBlock) {
            return;
        }

        function toggleCancelReason() {
            if (statusSelect.value === 'Anulado') {
                cancelReasonBlock.style.display = 'block';
                if (cancelReasonInput) cancelReasonInput.required = true;
            } else {
                cancelReasonBlock.style.display = 'none';
                if (cancelReasonInput) {
                    cancelReasonInput.required = false;
                    cancelReasonInput.value    = '';
                }
            }
        }

        statusSelect.addEventListener('change', toggleCancelReason);
        if (window.jQuery) {
            window.jQuery(statusSelect).on('change', toggleCancelReason);
        }
        toggleCancelReason(); // executa na inicialização
    }

    /**
     * Inicializa ações da página de detalhes / recibo de pagamento.
     */
    function initReceiptActions() {
        var btnPrint = document.getElementById('btnPrintReceipt');
        if (btnPrint) {
            btnPrint.addEventListener('click', function (e) {
                e.preventDefault();
                window.print();
            });
        }
    }

    // Inicialização ao carregar o DOM
    document.addEventListener('DOMContentLoaded', function () {
        initPaymentForm();
        initAntiDoubleSubmit();
        initCancelReasonToggle();
        initReceiptActions();
    });

})();
