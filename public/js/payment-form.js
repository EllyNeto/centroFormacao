/**
 * Script responsável pelas interações dinâmicas nos formulários de pagamento (Payment Create / Edit).
 * Suporta:
 * 1. Seleção de formando via Select2.
 * 2. Geração de boxes de valor dinâmicas por emolumento com placeholder="0,00".
 * 3. Formatação em tempo real ao digitar (dezenas, milhares, milhões).
 * 4. Abatimento automático de Saldo do Aluno e cálculo da diferença a cobrar.
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
     */
    function calculateTotalsAndBalance() {
        let totalEmoluments = 0;
        let selectedNames = [];

        // Percorre todas as caixas de valor dinâmicas criadas para os emolumentos selecionados
        const activeItemInputs = document.querySelectorAll('.emolument-item-value');
        activeItemInputs.forEach(function(inp) {
            let itemVal = window.CurrencyFormatter ? window.CurrencyFormatter.parseRaw(inp.value) : (parseFloat(inp.value) || 0);
            totalEmoluments += itemVal;

            let itemName = inp.getAttribute('data-name');
            if (itemName === 'Outro Emolumento') {
                let customDescInput = document.getElementById('custom_emolument_desc');
                if (customDescInput && customDescInput.value.trim()) {
                    itemName = customDescInput.value.trim();
                }
            }
            if (itemName && !selectedNames.includes(itemName)) {
                selectedNames.push(itemName);
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
            } else {
                valueInput.value = "";
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
     * Atualiza a renderização das boxes dinâmicas de valor ao selecionar/desmarcar checkboxes de emolumentos.
     */
    function renderDynamicEmolumentBoxes() {
        if (!emolumentsInputsList || !dynamicBoxesCard) return;

        let anyChecked = false;
        checkboxes.forEach(function(cb) {
            if (cb.checked) anyChecked = true;
        });

        if (anyChecked) {
            dynamicBoxesCard.classList.remove('d-none');
        } else {
            dynamicBoxesCard.classList.add('d-none');
            emolumentsInputsList.innerHTML = '';
            calculateTotalsAndBalance();
            return;
        }

        checkboxes.forEach(function(cb, index) {
            const emolName = cb.value;
            const defaultPrice = parseFloat(cb.getAttribute('data-price')) || 0;
            const boxId = 'emol_box_' + index;
            let existingBox = document.getElementById(boxId);

            if (cb.checked) {
                if (!existingBox) {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-3';
                    col.id = boxId;

                    let isCustom = (emolName === 'Outro Emolumento');
                    let initialVal = defaultPrice > 0 ? (window.CurrencyFormatter ? window.CurrencyFormatter.format(defaultPrice) : defaultPrice.toFixed(2)) : '';

                    col.innerHTML = `
                        <div class="p-2 border rounded" style="background-color: #ffffff;">
                            <label class="form-label fs-12 font-w600 text-dark mb-1 d-block">
                                ${isCustom ? 'Descrição do Emolumento:' : emolName + ':'}
                            </label>
                            ${isCustom ? `
                                <input type="text" id="custom_emolument_desc" class="form-control form-control-sm mb-2" placeholder="Ex: Multa por Atraso, Segunda Via..." value="Outro Emolumento">
                            ` : ''}
                            <input type="text" 
                                   class="form-control form-control-sm currency-input emolument-item-value font-w600" 
                                   data-name="${emolName}" 
                                   placeholder="0,00" 
                                   value="${initialVal}">
                        </div>
                    `;

                    emolumentsInputsList.appendChild(col);

                    // Anexa o manipulador de formatação e cálculo live
                    const newInput = col.querySelector('.emolument-item-value');
                    if (newInput) {
                        window.CurrencyFormatter.attach([newInput]);
                        newInput.addEventListener('input', calculateTotalsAndBalance);
                        newInput.addEventListener('blur', calculateTotalsAndBalance);
                    }

                    if (isCustom) {
                        const customDescInput = col.querySelector('#custom_emolument_desc');
                        if (customDescInput) {
                            customDescInput.addEventListener('input', calculateTotalsAndBalance);
                        }
                    }
                }
            } else {
                if (existingBox) {
                    existingBox.remove();
                }
            }
        });

        calculateTotalsAndBalance();
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
        handleStudentSelection();
    }
});
