/**
 * Chart.js Configuration and Initialization
 */

// Chart.js global defaults
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#64748b';
Chart.defaults.borderColor = '#e2e8f0';

// Dark mode chart colors
const isDarkMode = () => document.documentElement.classList.contains('dark');

const getChartColors = () => {
    if (isDarkMode()) {
        return {
            text: '#cbd5e1',
            grid: '#334155',
            border: '#475569'
        };
    }
    return {
        text: '#64748b',
        grid: '#e2e8f0',
        border: '#94a3b8'
    };
};

// Update chart colors when theme changes
const updateChartColors = () => {
    const colors = getChartColors();
    Chart.defaults.color = colors.text;
    Chart.defaults.borderColor = colors.grid;
    
    // Update all existing charts
    Object.values(Chart.instances).forEach(chart => {
        if (chart.options.scales) {
            if (chart.options.scales.x) {
                chart.options.scales.x.grid.color = colors.grid;
                chart.options.scales.x.ticks.color = colors.text;
            }
            if (chart.options.scales.y) {
                chart.options.scales.y.grid.color = colors.grid;
                chart.options.scales.y.ticks.color = colors.text;
            }
        }
        chart.update();
    });
};

// Listen for theme changes
document.addEventListener('themeChanged', updateChartColors);

// Create patient trends chart
const createPatientTrendsChart = (ctx, data) => {
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Patients',
                data: data.values,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: getChartColors().grid
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
};

// Create department distribution chart
const createDepartmentChart = (ctx, data) => {
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 8
                }
            },
            cutout: '65%'
        }
    });
};

// Create appointment status chart
const createAppointmentChart = (ctx, data) => {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Appointments',
                data: data.values,
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: getChartColors().grid
                    }
                }
            }
        }
    });
};

// Initialize charts on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize patient trends chart if element exists
    const patientTrendsCtx = document.getElementById('patientTrendsChart');
    if (patientTrendsCtx) {
        const patientData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            values: [120, 150, 180, 220, 260, 300]
        };
        createPatientTrendsChart(patientTrendsCtx, patientData);
    }

    // Initialize department chart if element exists
    const departmentCtx = document.getElementById('departmentChart');
    if (departmentCtx) {
        const departmentData = {
            labels: ['General', 'Cardiology', 'Orthopedics', 'Pediatrics', 'Neurology', 'Oncology'],
            values: [35, 20, 15, 12, 10, 8]
        };
        createDepartmentChart(departmentCtx, departmentData);
    }

    // Initialize appointment chart if element exists
    const appointmentCtx = document.getElementById('appointmentChart');
    if (appointmentCtx) {
        const appointmentData = {
            labels: ['Scheduled', 'Pending', 'Cancelled'],
            values: [45, 12, 8]
        };
        createAppointmentChart(appointmentCtx, appointmentData);
    }
});
