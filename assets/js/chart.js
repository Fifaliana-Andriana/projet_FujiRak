
class DashboardCharts {
    constructor() {
        this.charts = {};
        this.currentType = 'line'; 
        this.currentPeriod = 'month';
    }

    init(chartContainerId, data, options = {}) {
        this.containerId = chartContainerId;
        this.data = data;
        this.options = options;
        

        this.createTypeControls();
        

        this.createPeriodControls();
        

        this.renderChart();
    }


    createTypeControls() {
        const controlsHtml = `
            <div class="chart-controls">
                <div class="chart-type-buttons">
                    <button class="btn-chart-type active" data-type="line" onclick="dashboardCharts.changeChartType('line')">
                         Courbe
                    </button>
                    <button class="btn-chart-type" data-type="bar" onclick="dashboardCharts.changeChartType('bar')">
                         Bâtonner
                    </button>
                    <button class="btn-chart-type" data-type="pie" onclick="dashboardCharts.changeChartType('pie')">
                         Circulaire
                    </button>
                </div>
            </div>
        `;
        
        const container = document.getElementById(this.containerId);
        container.insertAdjacentHTML('beforebegin', controlsHtml);
    }

    createPeriodControls() {
        const periodHtml = `
            <div class="chart-filters">
                <div class="period-buttons">
                    <button class="btn-period active" data-period="day" onclick="dashboardCharts.filterByPeriod('day')">
                        Jour
                    </button>
                    <button class="btn-period" data-period="month" onclick="dashboardCharts.filterByPeriod('month')">
                        Mois
                    </button>
                    <button class="btn-period" data-period="year" onclick="dashboardCharts.filterByPeriod('year')">
                        Année
                    </button>
                </div>
            </div>
        `;
        
        const container = document.getElementById(this.containerId);
        container.insertAdjacentHTML('beforebegin', periodHtml);
    }

    changeChartType(type) {
        this.currentType = type;
        

        document.querySelectorAll('.btn-chart-type').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-type="${type}"]`).classList.add('active');
        
        this.renderChart();
    }


    filterByPeriod(period) {
        this.currentPeriod = period;
        

        document.querySelectorAll('.btn-period').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-period="${period}"]`).classList.add('active');
        

        this.loadDataByPeriod(period);
    }

    loadDataByPeriod(period) {
        const url = `controllers/FinanceController.php?action=getChartData&period=${period}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                this.data = data;
                this.renderChart();
            })
            .catch(error => {
                console.error('Erreur chargement données:', error);
            });
    }


    renderChart() {
        const ctx = document.getElementById(this.containerId).getContext('2d');

        if (this.charts[this.containerId]) {
            this.charts[this.containerId].destroy();
        }


        const config = this.getChartConfig();
        

        this.charts[this.containerId] = new Chart(ctx, config);
    }


    getChartConfig() {
        const baseConfig = {
            type: this.currentType,
            data: {
                labels: this.data.labels || [],
                datasets: this.getDatasets()
            },
            options: this.getChartOptions()
        };

        return baseConfig;
    }


    getDatasets() {

        if (this.currentType === 'pie' || this.currentType === 'doughnut') {
            return [{
                data: [
                    this.data.totalGains || 0,
                    this.data.totalPertes || 0,
                    this.data.soldeActuel || 0
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',  
                    'rgba(255, 99, 132, 0.8)', 
                    'rgba(54, 162, 235, 0.8)' 
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 2
            }];
        }


        return [
            {
                label: 'Gains',
                data: this.data.gains || [],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            },
            {
                label: 'Pertes',
                data: this.data.pertes || [],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            },
            {
                label: 'Solde',
                data: this.data.soldes || [],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }
        ];
    }


    getChartOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            family: 'Arial'
                        },
                        padding: 20,
                        usePointStyle: true
                    }
                },
                title: {
                    display: true,
                    text: this.getChartTitle(),
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 30
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'EUR'
                            }).format(context.parsed.y || context.parsed);
                            return label;
                        }
                    }
                }
            },
            scales: this.currentType === 'pie' ? {} : {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                style: 'currency',
                                currency: 'EUR',
                                maximumSignificantDigits: 3
                            }).format(value);
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        };
    }


    getChartTitle() {
        const periodText = {
            'day': 'du jour',
            'month': 'du mois',
            'year': 'annuel'
        };
        
        const typeText = {
            'line': 'Évolution',
            'bar': 'Comparaison',
            'pie': 'Répartition'
        };
        
        return `${typeText[this.currentType]} des gains, pertes et soldes ${periodText[this.currentPeriod]}`;
    }


    updateData(newData) {
        this.data = newData;
        this.renderChart();
    }


    exportChart() {
        const chart = this.charts[this.containerId];
        if (chart) {
            const url = chart.toBase64Image();
            const link = document.createElement('a');
            link.download = `graphique_${this.currentPeriod}_${Date.now()}.png`;
            link.href = url;
            link.click();
        }
    }
}



let dashboardCharts;


document.addEventListener('DOMContentLoaded', function() {
    dashboardCharts = new DashboardCharts();
    

    const chartCanvas = document.getElementById('financeChart');
    if (chartCanvas) {

        const initialData = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            gains: [5000, 7500, 8000, 9500, 11000, 12500],
            pertes: [1000, 1500, 2000, 1800, 2200, 2500],
            soldes: [4000, 6000, 6000, 7700, 8800, 10000],
            totalGains: 53500,
            totalPertes: 11000,
            soldeActuel: 42500
        };
        
        dashboardCharts.init('financeChart', initialData);
    }
});


function loadChartData(userId, period = 'month') {
    fetch(`controllers/FinanceController.php?action=getUserChartData&user_id=${userId}&period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (dashboardCharts) {
                dashboardCharts.updateData(data);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}


function updateStats(data) {
    document.getElementById('totalGains').textContent = 
        new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(data.totalGains);
    document.getElementById('totalPertes').textContent = 
        new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(data.totalPertes);
    document.getElementById('soldeActuel').textContent = 
        new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(data.soldeActuel);
}