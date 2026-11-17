(function($) {
    "use strict";

    var dlabChartlist = function() {
        var screenWidth = $(window).width();

        // 1. Gráfico de Desempenho Mensal (Inscrições vs Matrículas)
        var marketChart = function() {
            var chartElement = document.querySelector("#marketChart");
            if (!chartElement) return;

            var d = window.dashboardData || {};
            var categories = (d.monthsLabels && d.monthsLabels.length > 0)
                ? d.monthsLabels
                : ["Mês 1", "Mês 2", "Mês 3", "Mês 4", "Mês 5", "Mês 6"];

            var enrollmentsData = (d.monthlyEnrollments && d.monthlyEnrollments.length > 0)
                ? d.monthlyEnrollments
                : [0, 0, 0, 0, 0, 0];

            var studentsData = (d.monthlyStudents && d.monthlyStudents.length > 0)
                ? d.monthlyStudents
                : [0, 0, 0, 0, 0, 0];

            var options = {
                series: [{
                    name: 'Inscrições',
                    data: enrollmentsData
                }, {
                    name: 'Matrículas',
                    data: studentsData
                }],
                chart: {
                    height: 280,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                colors: ["var(--primary)", "#3AC977"],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ["var(--primary)", "#3AC977"],
                },
                legend: {
                    show: false
                },
                grid: {
                    show: true,
                    strokeDashArray: 6,
                    borderColor: '#dadada',
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#B5B5C3',
                            fontSize: '12px',
                            fontFamily: 'Poppins',
                            fontWeight: 400
                        },
                        formatter: function(value) {
                            return Math.round(value);
                        }
                    },
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        style: {
                            colors: '#B5B5C3',
                            fontSize: '12px',
                            fontFamily: 'Poppins',
                            fontWeight: 400
                        },
                    }
                },
                fill: {
                    type: 'solid',
                    opacity: 0.08
                },
                tooltip: {
                    x: {
                        show: true
                    },
                    y: {
                        formatter: function(val) {
                            return val + " registos";
                        }
                    }
                },
            };

            var chart = new ApexCharts(chartElement, options);
            chart.render();

            jQuery('#dzOldSeries').off('change').on('change', function() {
                jQuery(this).toggleClass('disabled');
                chart.toggleSeries('Inscrições');
            });

            jQuery('#dzNewSeries').off('change').on('change', function() {
                jQuery(this).toggleClass('disabled');
                chart.toggleSeries('Matrículas');
            });
        };

        // 2. Gráfico de Visão Geral (Turnos, Cursos e Faturação Mensal)
        var overiewChart = function() {
            var chartElement = document.querySelector("#overiewChart");
            if (!chartElement) return;

            var d = window.dashboardData || {};
            var months = (d.monthsLabels && d.monthsLabels.length > 0)
                ? d.monthsLabels
                : ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];

            var enrollments = (d.monthlyEnrollments && d.monthlyEnrollments.length > 0)
                ? d.monthlyEnrollments
                : [0, 0, 0, 0, 0, 0];

            var students = (d.monthlyStudents && d.monthlyStudents.length > 0)
                ? d.monthlyStudents
                : [0, 0, 0, 0, 0, 0];

            var paymentsCount = (d.monthlyPaymentsCount && d.monthlyPaymentsCount.length > 0)
                ? d.monthlyPaymentsCount
                : [0, 0, 0, 0, 0, 0];

            var options = {
                series: [{
                    name: 'Inscrições',
                    type: 'column',
                    data: enrollments
                }, {
                    name: 'Matrículas',
                    type: 'area',
                    data: students
                }, {
                    name: 'Pagamentos Confirmados',
                    type: 'line',
                    data: paymentsCount
                }],
                chart: {
                    height: 300,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: false
                    },
                },
                stroke: {
                    width: [0, 2, 3],
                    curve: 'smooth',
                    dashArray: [0, 0, 5]
                },
                legend: {
                    fontSize: '13px',
                    fontFamily: 'Poppins',
                    labels: {
                        colors: '#888888',
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '30%',
                        borderRadius: 6,
                    }
                },
                fill: {
                    type: ['solid', 'gradient', 'solid'],
                    opacity: [1, 0.25, 1],
                    gradient: {
                        inverseColors: false,
                        shade: 'light',
                        type: "vertical",
                        colorStops: [
                            [
                                { offset: 0, color: 'var(--primary)', opacity: 1 },
                                { offset: 100, color: 'var(--primary)', opacity: 1 }
                            ],
                            [
                                { offset: 0, color: '#3AC977', opacity: 0.35 },
                                { offset: 100, color: '#3AC977', opacity: 0 }
                            ],
                            [
                                { offset: 0, color: '#FF5E5E', opacity: 1 },
                                { offset: 100, color: '#FF5E5E', opacity: 1 }
                            ],
                        ],
                        stops: [0, 100, 100]
                    }
                },
                colors: ["var(--primary)", "#3AC977", "#FF5E5E"],
                labels: months,
                markers: {
                    size: 0
                },
                xaxis: {
                    type: 'category',
                    labels: {
                        style: {
                            colors: '#888888',
                            fontSize: '12px',
                            fontFamily: 'Poppins',
                            fontWeight: 400
                        },
                    }
                },
                yaxis: {
                    min: 0,
                    tickAmount: 4,
                    labels: {
                        style: {
                            colors: '#888888',
                            fontSize: '12px',
                            fontFamily: 'Poppins',
                            fontWeight: 400
                        },
                        formatter: function(value) {
                            return Math.round(value);
                        }
                    },
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(val) {
                            return (typeof val !== "undefined") ? val + " registos" : val;
                        }
                    }
                }
            };

            var chart = new ApexCharts(chartElement, options);
            chart.render();

            $(".mix-chart-tab .nav-link").off('click').on('click', function(e) {
                e.preventDefault();
                $(".mix-chart-tab .nav-link").removeClass('active');
                $(this).addClass('active');

                var seriesType = $(this).attr('data-series');

                if (seriesType === "turnos" && d.shiftLabels && d.shiftSeries) {
                    chart.updateOptions({
                        labels: d.shiftLabels,
                        plotOptions: { bar: { columnWidth: '35%' } }
                    });
                    chart.updateSeries([{
                        name: "Turmas por Turno",
                        type: 'column',
                        data: d.shiftSeries
                    }]);
                } else if (seriesType === "cursos" && d.topCoursesLabels && d.topCoursesLabels.length > 0) {
                    chart.updateOptions({
                        labels: d.topCoursesLabels,
                        plotOptions: { bar: { columnWidth: '35%' } }
                    });
                    chart.updateSeries([{
                        name: "Candidatos Inscritos",
                        type: 'column',
                        data: d.topCoursesSeries
                    }]);
                } else {
                    // Padrão: Visão Mensal Completa
                    chart.updateOptions({
                        labels: months,
                        plotOptions: { bar: { columnWidth: '30%' } }
                    });
                    chart.updateSeries([{
                        name: "Inscrições",
                        type: 'column',
                        data: enrollments
                    }, {
                        name: "Matrículas",
                        type: 'area',
                        data: students
                    }, {
                        name: "Pagamentos Confirmados",
                        type: 'line',
                        data: paymentsCount
                    }]);
                }
            });
        };

        // 3. Inicializador do Datepicker em Português
        var handleDatetimepicker = function() {
            if (jQuery("#datetimepicker1").length > 0 && jQuery.fn.datetimepicker) {
                $('#datetimepicker1').datetimepicker({
                    inline: true,
                });
            }
        };

        // 4. Swiper caso exista no layout
        var swipercard = function() {
            if (jQuery('.mySwiper-counter').length > 0 && typeof Swiper !== 'undefined') {
                new Swiper('.mySwiper-counter', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                });
            }
        };

        return {
            init: function() {},
            load: function() {
                handleDatetimepicker();
                swipercard();
                marketChart();
                overiewChart();
            },
            resize: function() {}
        };
    }();

    jQuery(window).on('load', function() {
        setTimeout(function() {
            dlabChartlist.load();
        }, 300);
    });

})(jQuery);