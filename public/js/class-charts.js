/**
 * Inicialização dos Gráficos do Módulo de Turmas (ApexCharts)
 * Ficheiro JavaScript dedicado e independente do Blade.
 */

document.addEventListener("DOMContentLoaded", function () {
    // Verifica se o modo escuro está ativo para ajustar a paleta de cores dos rótulos
    var isDarkMode = document.body.getAttribute('data-theme-version') === 'dark';
    var labelColor = isDarkMode ? '#cbd5e1' : '#475569';

    // 1. Gráfico Donut de Distribuição por Turno (Valores estáticos puros)
    var optionsTurnos = {
        series: [14, 10, 7, 4],
        labels: ['Manhã', 'Tarde', 'Pós-Laboral', 'Noite'],
        chart: {
            type: 'donut',
            height: 260
        },
        colors: ['#4f46e5', '#10b981', '#f59e0b', '#0284c7'],
        legend: {
            position: 'bottom',
            labels: {
                colors: labelColor
            }
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            show: false
        },
        tooltip: {
            theme: isDarkMode ? 'dark' : 'light'
        }
    };

    var chartTurnosElement = document.querySelector("#chartTurnos");
    if (chartTurnosElement) {
        var chartTurnos = new ApexCharts(chartTurnosElement, optionsTurnos);
        chartTurnos.render();
    }

    // 2. Gráfico de Barras de Capacidade por Turma (Valores estáticos puros)
    var optionsCapacidade = {
        series: [{
            name: 'Capacidade (Alunos)',
            data: [28, 22, 18, 30, 25, 32]
        }],
        chart: {
            type: 'bar',
            height: 260,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                distributed: true
            }
        },
        colors: ['#4f46e5', '#10b981', '#f59e0b', '#0284c7', '#ec4899', '#8b5cf6'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        xaxis: {
            categories: ['Turma Web 01', 'Turma Redes 02', 'Turma Design 03', 'Turma Ciber 04', 'Turma Mobile 05', 'Turma Python 06'],
            labels: {
                style: {
                    colors: labelColor
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: labelColor
                }
            }
        },
        legend: { show: false },
        grid: {
            borderColor: isDarkMode ? '#334155' : '#e2e8f0'
        },
        tooltip: {
            theme: isDarkMode ? 'dark' : 'light'
        }
    };

    var chartCapacidadeElement = document.querySelector("#chartCapacidade");
    if (chartCapacidadeElement) {
        var chartCapacidade = new ApexCharts(chartCapacidadeElement, optionsCapacidade);
        chartCapacidade.render();
    }
});
