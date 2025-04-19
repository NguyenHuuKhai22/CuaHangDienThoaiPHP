// Revenue Chart
function initRevenueChart(labels, revenueData) {
    return new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: revenueData,
                backgroundColor: '#4338ca',
                borderRadius: 8,
                maxBarThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#e2e8f0'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(value);
                        },
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND',
                                maximumFractionDigits: 0
                            }).format(context.raw);
                        }
                    }
                }
            }
        }
    });
}

// Category Distribution Chart
function initCategoryChart(categoryLabels, categoryValues) {
    return new Chart(document.getElementById('categoryDistribution'), {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryValues,
                backgroundColor: ['#4338ca', '#059669', '#d97706', '#dc2626', '#7c3aed'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 20,
                    bottom: 20
                }
            }
        }
    });
}

// Initialize all charts when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get data from data attributes
    const revenueChartEl = document.getElementById('revenueChart');
    const categoryChartEl = document.getElementById('categoryDistribution');

    if (revenueChartEl) {
        const labels = JSON.parse(revenueChartEl.dataset.labels);
        const revenueData = JSON.parse(revenueChartEl.dataset.revenueData);
        initRevenueChart(labels, revenueData);
    }

    if (categoryChartEl) {
        const categoryLabels = JSON.parse(categoryChartEl.dataset.labels);
        const categoryValues = JSON.parse(categoryChartEl.dataset.values);
        initCategoryChart(categoryLabels, categoryValues);
    }
}); 