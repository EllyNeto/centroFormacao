/**
 * Script responsável pelas interações dinâmicas nos formulários de pagamento (Payment Create / Edit).
 * Suporta:
 * 1. Seleção de formando via Select2.
 * 2. Geração de boxes de valor dinâmicas por emolumento com placeholder="0,00".
 * 3. Formatação em tempo real ao digitar (dezenas, milhares, milhões).
 * 4. Abatimento automático de Saldo do Aluno e cálculo da diferença a cobrar.
 * 5. Preservação e sincronização rigorosa do tipo de pagamento (type_of_payment).
 */

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes           = document.querySelectorAll('.emolumento-check');
    const typeHiddenInput      = document.getElementById('type_of_payment');
    const valueInput           = document.getElementById('value');
    const usedBalanceInput     = document.getElementById('used_balance');
    const addToBalanceInput    = document.getElementById('add_to_balance');
    
    const studentSelect        = document.getElementById('student_id');
    const balanceDisplayCard   = document.getElementById('student_balance_card');
    const currentBalanceSpan   = document.getElementById('current_balance_text');
    
    const dynamicBoxesCard     = document.getElementById('dynamic_emolument_boxes');
    const emolumentsInputsList = document.getElementById('emoluments_inputs_list');

    let currentStudentBalance = 0;

    // Inicializa o plugin Select2 no campo de seleção de formando
    if (window.jQuery && jQuery.fn.select2 && studentSelect) {
        jQuery(studentSelect).select2({
            placeholder: "Pesquise e selecione o formando...",
            allowClear: true,
            width: '100%'
        });
    }

    /**
     * Recalcula a soma de todos os emolumentos ativos e aplica o abatimento do saldo do aluno.
     * Atualiza o campo oculto 'type_of_payment' com os nomes de todos os emolumentos selecionados.
     */
    function calculateTotalsAndBalance() {
        let totalEmoluments = 0;
        let selectedNames = [];

        // Obtém os nomes e soma os preços base de todos os emolumentos cujas checkboxes estão marcadas
        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                let name = cb.value;
                let price = parseFloat(cb.getAttribute('data-price')) || 0;
                totalEmoluments += price;

                if (name === 'Outro Emolumento') {
                    const customDescInput = document.getElementById('custom_emolument_desc');
                    if (customDescInput && customDescInput.value.trim()) {
                        name = customDescInput.value.trim();
                    }
                }
                if (name && !selectedNames.includes(name)) {
                    selectedNames.push(name);
                }
            }
        });

        // Atualiza o campo oculto do tipo de pagamento com os nomes dos emolumentos
        if (typeHiddenInput) {
            typeHiddenInput.value = selectedNames.join(', ');
        }

        // Lógica de Abatimento Automático do Saldo de Crédito do Aluno
        let usedBalance = 0;
        let diferencaACobrar = totalEmoluments;

        if (currentStudentBalance > 0 && totalEmoluments > 0) {
            usedBalance = Math.min(totalEmoluments, currentStudentBalance);
            diferencaACobrar = Math.max(0, totalEmoluments - usedBalance);
        }

        if (usedBalanceInput) {
            usedBalanceInput.value = usedBalance.toFixed(2);
        }

        // Atualiza o valor total a cobrar no campo principal (diferença em dinheiro)
        if (valueInput) {
            if (diferencaACobrar > 0) {
                valueInput.value = window.CurrencyFormatter ? window.CurrencyFormatter.format(diferencaACobrar) : diferencaACobrar.toFixed(2);
            } else if (totalEmoluments > 0 && diferencaACobrar === 0) {
                valueInput.value = "0,00";
            }
        }

        // Atualiza o aviso informativo no cartão de saldo
        const balanceNotice = document.getElementById('balance_notice_text');
        if (balanceNotice) {
            if (usedBalance > 0) {
                let fmtUsed = window.CurrencyFormatter ? window.CurrencyFormatter.format(usedBalance) : usedBalance.toFixed(2);
                let fmtDif  = window.CurrencyFormatter ? window.CurrencyFormatter.format(diferencaACobrar) : diferencaACobrar.toFixed(2);
                balanceNotice.innerHTML = `<span class="text-success font-w600"><i class="fa fa-info-circle me-1"></i> Saldo de ${fmtUsed} Kz aplicado automaticamente. Cobrar apenas a diferença de ${fmtDif} Kz ao formando.</span>`;
            } else {
                balanceNotice.textContent = "O formando pode utilizar este crédito acumulado em futuros emolumentos ou gerar novo saldo ao pagar a mais.";
            }
        }
    }

    /**
     * Alterna a visibilidade do campo de descrição do "Outro Emolumento" quando selecionado.
     */
    function renderDynamicEmolumentBoxes() {
        const outroCb = document.querySelector('.emolumento-check[value="Outro Emolumento"]');
        const outroContainer = document.getElementById('outro_emolumento_container');

        if (outroContainer) {
            if (outroCb && outroCb.checked) {
                outroContainer.classList.remove('d-none');
            } else {
                outroContainer.classList.add('d-none');
            }
        }

        calculateTotalsAndBalance();
    }

    /**
     * Sincroniza o estado inicial dos checkboxes com base no tipo de pagamento já existente (old input ou edit model)
     * ou automaticamente pre-seleciona "Inscrição" quando vindo de uma Inscrição.
     */
    function syncInitialState() {
        const initialTypeValue = typeHiddenInput ? typeHiddenInput.value.trim() : '';

        if (initialTypeValue) {
            const names = initialTypeValue.split(',').map(s => s.trim()).filter(Boolean);
            const standardValues = ["Inscrição", "Valor do Curso", "Cartão do Formando", "Certificado", "Exame de Recurso"];

            names.forEach(name => {
                let matched = false;
                checkboxes.forEach(cb => {
                    if (cb.value === name) {
                        cb.checked = true;
                        matched = true;
                    }
                });
                if (!matched) {
                    const outroCb = document.querySelector('.emolumento-check[value="Outro Emolumento"]');
                    if (outroCb) {
                        outroCb.checked = true;
                        outroCb.setAttribute('data-custom-name', name);
                    }
                }
            });
        } else {
            // Se veio da rota de inscrição (com enrollment_id), marca automaticamente 'Inscrição'
            const enrollmentHidden = document.querySelector('input[name="enrollment_id"]');
            if (enrollmentHidden && enrollmentHidden.value) {
                const inscricaoCb = document.querySelector('.emolumento-check[value="Inscrição"]');
                if (inscricaoCb) {
                    inscricaoCb.checked = true;
                }
            }
        }

        renderDynamicEmolumentBoxes();
    }

    /**
     * Atualiza o saldo do formando selecionado via Select2
     */
    function handleStudentSelection() {
        if (!studentSelect) return;

        const selectedOption = studentSelect.options[studentSelect.selectedIndex];
        currentStudentBalance = 0;

        if (selectedOption && selectedOption.value) {
            currentStudentBalance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
        }

        if (currentBalanceSpan) {
            currentBalanceSpan.textContent = (window.CurrencyFormatter ? window.CurrencyFormatter.format(currentStudentBalance) : currentStudentBalance.toFixed(2)) + ' Kz';
        }

        if (balanceDisplayCard) {
            if (studentSelect.value && currentStudentBalance > 0) {
                balanceDisplayCard.classList.remove('d-none');
            } else {
                balanceDisplayCard.classList.add('d-none');
            }
        }

        calculateTotalsAndBalance();
    }

    // Regista ouvintes de eventos para os checkboxes de emolumentos
    if (checkboxes && checkboxes.length > 0) {
        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', renderDynamicEmolumentBoxes);
        });
    }

    // Regista ouvinte de evento para a seleção do formando
    if (studentSelect) {
        studentSelect.addEventListener('change', handleStudentSelection);
        if (window.jQuery) {
            jQuery(studentSelect).on('change.select2', handleStudentSelection);
        }
    }

    // Validação antes do envio do formulário de pagamento
    const paymentForm = typeHiddenInput ? typeHiddenInput.closest('form') : null;
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            calculateTotalsAndBalance();
            if (!typeHiddenInput || !typeHiddenInput.value.trim()) {
                e.preventDefault();
                alert('Por favor, selecione pelo menos um emolumento a pagar.');
                const emolCard = document.querySelector('.emolumento-check');
                if (emolCard) {
                    emolCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // Executa a sincronização do estado inicial
    handleStudentSelection();
    syncInitialState();
});

