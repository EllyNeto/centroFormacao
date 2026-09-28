/**
 * Utilitário de Formatação de Números por Milhares em Tempo Real (Currency & Thousands Formatter)
 * 
 * Finalidade:
 * Formata automaticamente os campos de entrada de valores monetários (ex: Valor do Curso em Kz)
 * organizando os dígitos em grupos de milhares com pontos (ex: 120.000 ou 123.344.555) enquanto o utilizador digita.
 * 
 * Funcionamento:
 * 1. Procura inputs com a classe .currency-mask ou atributo data-mask="currency".
 * 2. Converte o tipo do input para 'text' com inputmode='numeric' (evitando mensagens de erro nativas de HTML5).
 * 3. Formata dinamicamente os valores à medida que são introduzidos.
 * 4. Antes da submissão do formulário, remove os pontos de milhar para enviar o valor numérico limpo para o Laravel.
 */
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Auxiliar que recebe uma string/número e devolve formatado com pontos de milhar (pt-PT).
     */
    function formatThousands(value) {
        if (value === null || value === undefined) return '';
        // Extrai estritamente os dígitos numéricos
        let digitsOnly = value.toString().replace(/\D/g, '');
        if (!digitsOnly) return '';
        // Formata em grupos de 3 dígitos (ex: 120.000)
        return parseInt(digitsOnly, 10).toLocaleString('pt-PT');
    }

    /**
     * Inicializa os eventos nos campos de input mapeados.
     */
    function initCurrencyInputs() {
        const inputs = document.querySelectorAll('.currency-mask, input[data-mask="currency"]');

        inputs.forEach(function (input) {
            // Altera o tipo de 'number' para 'text' para permitir pontuação visual sem erro HTML5
            if (input.type === 'number') {
                input.type = 'text';
                input.setAttribute('inputmode', 'numeric');
            }

            // Se o campo já contiver um valor inicial (ex: na edição), formata imediatamente
            if (input.value) {
                input.value = formatThousands(input.value);
            }

            // Evento 'input': dispara a cada tecla digitada para formatar em tempo real
            input.addEventListener('input', function () {
                let formatted = formatThousands(this.value);
                this.value = formatted;
            });

            // Evento 'submit': limpa os pontos visuais no momento do envio ao servidor
            if (input.form && !input.form.dataset.currencyCleanAttached) {
                input.form.dataset.currencyCleanAttached = 'true';
                input.form.addEventListener('submit', function () {
                    inputs.forEach(function (inp) {
                        if (inp.value) {
                            inp.value = inp.value.replace(/\./g, '');
                        }
                    });
                });
            }
        });
    }

    initCurrencyInputs();
});
