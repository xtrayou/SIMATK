/* analytics.js — Logika grafik dan filter analytics */
'use strict';

$(document).ready(function() {
    // Period selector change
    $('#periodSelector').on('change', function() {
        const period = $(this).val();
        window.location.href = `${window.ANALYTICS_CFG.baseUrl}?period=${period}`;
    });

    function generateDateLabels(days) {
        const labels = [];
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('id-ID', {
                month: 'short',
                day: 'numeric'
            }));
        }
        return labels;
    }

    function generateRandomData(points, min, max) {
        const data = [];
        for (let i = 0; i < points; i++) {
            data.push(Math.floor(Math.random() * (max - min + 1)) + min);
        }
        return data;
    }

    const lineChartBaseOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    function buildLineChart(ctx, data, extraOptions = {}) {
        return new Chart(ctx, {
            type: 'line',
            data,
            options: {
                ...lineChartBaseOptions,
                ...extraOptions
            }
        });
    }

    // ABC Analysis Chart
    const abcCanvas = document.getElementById('abcChart');
    if (abcCanvas) {
        const abcData = {
            labels: ['Category A (High Value)', 'Category B (Medium Value)', 'Category C (Low Value)'],
            datasets: [{
                data: [window.ANALYTICS_CFG.aCount, window.ANALYTICS_CFG.bCount, window.ANALYTICS_CFG.cCount],
                backgroundColor: ['#dc3545', '#ffc107', '#198754'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        };

        const abcCtx = abcCanvas.getContext('2d');
        new Chart(abcCtx, {
            type: 'doughnut',
            data: abcData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' products (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Performance Gauge Chart
    const performanceCanvas = document.getElementById('performanceGauge');
    if (performanceCanvas) {
        const performanceCtx = performanceCanvas.getContext('2d');
        new Chart(performanceCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [87, 13],
                    backgroundColor: ['#198754', '#e9ecef'],
                    borderWidth: 0,
                    circumference: 180,
                    rotation: 270
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    }

    // Movement Trend Chart
    const movementTrendCanvas = document.getElementById('movementTrendChart');
    if (movementTrendCanvas) {
        const movementTrendData = {
            labels: generateDateLabels(window.ANALYTICS_CFG.period),
            datasets: [{
                label: 'Stock In',
                data: generateRandomData(window.ANALYTICS_CFG.period, 10, 50),
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Stock Out',
                data: generateRandomData(window.ANALYTICS_CFG.period, 5, 30),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        };

        const movementTrendCtx = movementTrendCanvas.getContext('2d');
        buildLineChart(movementTrendCtx, movementTrendData, {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        });
    }

    // Value Trend Chart
    const valueTrendCanvas = document.getElementById('valueTrendChart');
    if (valueTrendCanvas) {
        const valueTrendData = {
            labels: generateDateLabels(window.ANALYTICS_CFG.period),
            datasets: [{
                label: 'Inventory Value',
                data: generateRandomData(window.ANALYTICS_CFG.period, 500000000, 750000000),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.4
            }]
        };

        const valueTrendCtx = valueTrendCanvas.getContext('2d');
        buildLineChart(valueTrendCtx, valueTrendData, {
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            // Simple formatting if formatCurrency is undefined
                            if (typeof formatCurrency === 'function') {
                                return formatCurrency(value);
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (typeof formatCurrency === 'function') {
                                return 'Value: ' + formatCurrency(context.parsed.y);
                            }
                            return 'Value: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        });
    }
});
