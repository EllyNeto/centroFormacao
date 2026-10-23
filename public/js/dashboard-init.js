/**
 * Script Externo de Inicialização Analítica do Dashboard (dashboard-init.js)
 * Centro de Formação Profissional
 *
 * Lê os dados JSON a partir do elemento #dashboardAnalyticsData e inicializa os
 * gráficos interativos Chart.js (Evolução Semestral e Distribuição por Turno),
 * com suporte reativo ao Modo Claro / Escuro.
 */
(function () {
    'use strict';

    var mainChartInstance = null;
    var shiftChartInstance = null;

    function getThemeColors() {
        var isDark = document.body.getAttribute('data-theme-version') === 'dark';
        return {
            textColor: isDark ? '#cbd5e1' : '#64748b',
            gridColor: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(15, 23, 42, 0.06)',
            tooltipBg: isDark ? '#1e293b' : '#0f172a'
        };
    }

    function initDashboardCharts() {
        var dataEl = document.getElementById('dashboardAnalyticsData');
        if (!dataEl || typeof Chart === 'undefined') {
            return;
        }

        var rawData = dataEl.getAttribute('data-chart');
        if (!rawData) {
            return;
        }

        var chartData;
        try {
            chartData = JSON.parse(rawData);
        } catch (e) {
            return;
        }

        var colors = getThemeColors();

        // 1. Gráfico Combinado: Inscrições vs Matrículas (Últimos 6 Meses)
        var ctxMain = document.getElementById('enrollmentRevenueChart');
        if (ctxMain) {
            if (mainChartInstance) {
                mainChartInstance.destroy();
            }

            mainChartInstance = new Chart(ctxMain.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartData.monthsLabels || [],
                    datasets: [
                        {
                            label: 'Inscrições Registadas',
                            backgroundColor: 'rgba(30, 64, 175, 0.85)',
                            hoverBackgroundColor: '#1E40AF',
                            borderColor: '#1E40AF',
                            borderWidth: 1,
                            borderRadius: 6,
                            data: chartData.monthlyEnrollments || [],
                            barPercentage: 0.55,
                            categoryPercentage: 0.65
                        },
                        {
                            label: 'Formandos Matriculados',
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            hoverBackgroundColor: '#059669',
                            borderColor: '#10b981',
                            borderWidth: 1,
                            borderRadius: 6,
                            data: chartData.monthlyStudents || [],
                            barPercentage: 0.55,
                            categoryPercentage: 0.65
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            fontColor: colors.textColor,
                            fontFamily: "'Poppins', sans-serif",
                            fontSize: 12,
                            boxWidth: 12,
                            padding: 16
                        }
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: colors.tooltipBg,
                        titleFontFamily: "'Poppins', sans-serif",
                        bodyFontFamily: "'Poppins', sans-serif",
                        cornerRadius: 8,
                        xPadding: 12,
                        yPadding: 10,
                        callbacks: {
                            afterBody: function (tooltipItems) {
                                var idx = tooltipItems[0].index;
                                var rev = (chartData.monthlyRevenue && chartData.monthlyRevenue[idx]) ? chartData.monthlyRevenue[idx] : 0;
                                var formatted = parseFloat(rev).toLocaleString('pt-PT', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                return 'Receita no mês: ' + formatted + ' Kz';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                fontColor: colors.textColor,
                                fontFamily: "'Poppins', sans-serif",
                                fontSize: 12
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: colors.gridColor,
                                drawBorder: false,
                                zeroLineColor: colors.gridColor
                            },
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                stepSize: 1,
                                fontColor: colors.textColor,
                                fontFamily: "'Poppins', sans-serif",
                                fontSize: 12
                            }
                        }]
                    }
                }
            });
        }

        // 2. Gráfico Doughnut: Distribuição de Turmas por Turno
        var ctxShift = document.getElementById('shiftDistributionChart');
        if (ctxShift) {
            if (shiftChartInstance) {
                shiftChartInstance.destroy();
            }

            var shiftSeries = chartData.shiftSeries || [0, 0, 0];
            var totalShifts = shiftSeries.reduce(function (a, b) { return a + b; }, 0);

            shiftChartInstance = new Chart(ctxShift.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: chartData.shiftLabels || ['Manhã', 'Tarde', 'Pós-Laboral'],
                    datasets: [{
                        data: totalShifts > 0 ? shiftSeries : [1, 1, 1],
                        backgroundColor: totalShifts > 0
                            ? ['#1E40AF', '#0ea5e9', '#f59e0b']
                            : ['#e2e8f0', '#cbd5e1', '#94a3b8'],
                        borderWidth: 2,
                        borderColor: document.body.getAttribute('data-theme-version') === 'dark' ? '#1e293b' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 68,
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            fontColor: colors.textColor,
                            fontFamily: "'Poppins', sans-serif",
                            fontSize: 12,
                            boxWidth: 12,
                            padding: 12
                        }
                    },
                    tooltips: {
                        backgroundColor: colors.tooltipBg,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var idx = tooltipItem.index;
                                var label = data.labels[idx] || '';
                                var actualVal = shiftSeries[idx] || 0;
                                return ' ' + label + ': ' + actualVal + (actualVal === 1 ? ' turma' : ' turmas');
                            }
                        }
                    }
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initDashboardCharts();

        // Re-renderiza com cores adequadas ao alternar entre Modo Claro e Escuro
        window.addEventListener('cf:theme-changed', function () {
            setTimeout(initDashboardCharts, 30);
        });
    });
})();
