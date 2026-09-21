/**
 * Currency Formatter Utility
 * Formatação automática em tempo real para campos numéricos/monetários do Centro de Formação.
 * Suporta separadores de milhares ao digitar (dezenas, milhares, milhões), casas decimais e sanitização pré-submissão.
 */

window.CurrencyFormatter = {
    /**
     * Formata um número (float ou string) para representação visual com separadores de milhares e 2 casas decimais.
     * Exemplo: 15000.5 -> "15 000,50" ou "15.000,50"
     */
    format: function(val) {
        if (val === null || val === undefined || val === '') return '';
        
        let clean = val.toString().replace(/[^0-9.,]/g, '');
        if (!clean) return '';

        clean = clean.replace(',', '.');
        let number = parseFloat(clean);
        if (isNaN(number)) return '';

        return new Intl.NumberFormat('pt-AO', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(number);
    },

    /**
     * Formata apenas a parte inteira com milhares durante a digitação em tempo real.
     * Exemplo: "15000" -> "15 000", "1500000" -> "1 500 000"
     */
    formatLiveDigits: function(valStr) {
        if (!valStr) return '';
        
        // Separa parte inteira e parte decimal (se já houver vírgula ou ponto)
        let parts = valStr.toString().split(/[,.]/);
        let integerPart = parts[0].replace(/\D/g, '');

        if (!integerPart) return '';

        // Aplica separador de milhares por expressão regular (espaços ou pontos)
        let formattedInt = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, " ");

        // Se o utilizador já digitou vírgula/ponto e decimais, mantém
        if (parts.length > 1) {
            let decimalPart = parts[1].replace(/\D/g, '').substring(0, 2);
            return formattedInt + ',' + decimalPart;
        }

        return formattedInt;
    },

    /**
     * Converte um valor formatado visualmente em float numérico limpo.
     * Exemplo: "1 500 000,50 Kz" ou "1.500.000,50" -> 1500000.50
     */
    parseRaw: function(formattedVal) {
        if (!formattedVal) return 0;
        let str = formattedVal.toString().trim();
        str = str.replace(/[^0-9.,-]/g, '');
        
        if (str.includes(',')) {
            str = str.replace(/\s/g, '').replace(/\./g, '').replace(',', '.');
        } else {
            str = str.replace(/\s/g, '');
        }
        
        let num = parseFloat(str);
        return isNaN(num) ? 0 : num;
    },

    /**
     * Aplica a autoformatação dinâmica e o bloqueio estrito de letras aos campos especificados.
     * @param {string|NodeList} selector - Seletor CSS ou coleção de elementos
     */
    attach: function(selector) {
        const inputs = typeof selector === 'string' 
            ? document.querySelectorAll(selector) 
            : selector;

        if (!inputs || inputs.length === 0) return;

        inputs.forEach(function(input) {
            // Define o modo de entrada numérico para telemóveis e dispositivos móveis
            input.setAttribute('inputmode', 'decimal');
            input.removeAttribute('pattern');

            // Bloqueia diretamente no teclado qualquer tecla de letra (A-Z) ou caracteres não numéricos
            input.addEventListener('keydown', function(e) {
                // Permite teclas de navegação e controlo (Backspace, Delete, Tab, Escape, Enter, Setas, etc)
                if (['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End'].includes(e.key)) {
                    return;
                }
                // Permite atalhos de teclado (Ctrl+A, Ctrl+C, Ctrl+V, etc)
                if (e.ctrlKey || e.metaKey) {
                    return;
                }
                // Impede explicitamente letras e caracteres que não sejam dígitos (0-9), vírgula (,), ponto (.) ou espaço (' ')
                if (!/^[0-9., ]$/.test(e.key)) {
                    e.preventDefault();
                    return false;
                }
            });

            // Ao colar conteúdo via Clipboard (paste), remove automaticamente qualquer letra
            input.addEventListener('paste', function(e) {
                let pastedData = (e.clipboardData || window.clipboardData).getData('text');
                if (pastedData) {
                    let sanitized = pastedData.replace(/[^0-9., ]/g, '');
                    if (sanitized !== pastedData) {
                        e.preventDefault();
                        this.value = window.CurrencyFormatter.formatLiveDigits(sanitized);
                        this.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            });

            // Ao digitar (input event), purga letras residualmente e aplica a autoformatação em tempo real
            input.addEventListener('input', function() {
                let sanitized = this.value.replace(/[^0-9., ]/g, '');
                if (sanitized !== this.value) {
                    this.value = sanitized;
                }
                if (this.value) {
                    let liveFormatted = window.CurrencyFormatter.formatLiveDigits(this.value);
                    this.value = liveFormatted;
                }
            });

            // Ao desfocar o campo (blur), garante as 2 casas decimais formais se for um valor numérico válido
            input.addEventListener('blur', function() {
                if (this.value) {
                    let raw = window.CurrencyFormatter.parseRaw(this.value);
                    if (raw > 0) {
                        this.value = window.CurrencyFormatter.format(raw);
                    }
                }
            });

            // Ao focar (focus), seleciona todo o conteúdo para facilitar a edição
            input.addEventListener('focus', function() {
                this.select();
            });

            // Garante que, ao submeter o formulário pai, o valor enviado seja numérico limpo (float: 1500.00)
            const form = input.closest('form');
            if (form && !form.dataset.currencySubmitAttached) {
                form.dataset.currencySubmitAttached = 'true';
                form.addEventListener('submit', function() {
                    const currencyInputs = form.querySelectorAll('.currency-input, [data-currency]');
                    currencyInputs.forEach(function(ci) {
                        if (ci.value) {
                            let cleanValue = window.CurrencyFormatter.parseRaw(ci.value);
                            ci.value = cleanValue.toFixed(2);
                        }
                    });
                });
            }
        });
    }
};

// Inicialização automática ao carregar o DOM
document.addEventListener('DOMContentLoaded', function() {
    window.CurrencyFormatter.attach('.currency-input, [data-currency]');
});
