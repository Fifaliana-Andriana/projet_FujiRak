/**
 * TrendChart : graphique gains/pertes dans le temps, avec :
 *  - 3 modes d'affichage : barres / courbe / circulaire
 *  - filtre par période : jour / mois / année (refetch AJAX vers `endpoint`)
 *
 * Utilisation :
 *   new TrendChart('financeChart', initialData, 'index.php?route=admin/stats-json');
 *
 * `initialData` = { labels: [...], gains: [...], pertes: [...] }
 */
class TrendChart {
    constructor(canvasId, initialData, endpoint) {
        this.canvasId = canvasId;
        this.data = initialData;
        this.endpoint = endpoint;
        this.chartType = 'bar';
        this.period = 'month';
        this.chart = null;
    }

    init() {
        const canvas = document.getElementById(this.canvasId);
        if (!canvas) return;

        // Empêche la boucle de redimensionnement infinie de Chart.js :
        // le canvas responsive a besoin d'un parent à hauteur FIXE.
        const wrapper = document.createElement('div');
        wrapper.className = 'chart-canvas-wrapper';
        canvas.parentNode.insertBefore(wrapper, canvas);
        wrapper.appendChild(canvas);

        this.buildControls(wrapper);
        this.render();
    }

    buildControls(wrapperEl) {
        const controls = document.createElement('div');
        controls.className = 'chart-controls mb-3';

        controls.innerHTML = `
            <div class="chart-type-buttons">
                <button class="btn-chart-type active" data-type="bar">
                    <i class="bi bi-bar-chart-fill"></i> Barres
                </button>
                <button class="btn-chart-type" data-type="line">
                    <i class="bi bi-graph-up"></i> Courbe
                </button>
                <button class="btn-chart-type" data-type="doughnut">
                    <i class="bi bi-pie-chart-fill"></i> Circulaire
                </button>
            </div>
            <div class="period-buttons">
                <button class="btn-period" data-period="day">Jour</button>
                <button class="btn-period active" data-period="month">Mois</button>
                <button class="btn-period" data-period="year">Année</button>
            </div>
        `;

        wrapperEl.parentNode.insertBefore(controls, wrapperEl);

        controls.querySelectorAll('.btn-chart-type').forEach((btn) => {
            btn.addEventListener('click', () => {
                controls.querySelectorAll('.btn-chart-type').forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                this.chartType = btn.dataset.type;
                this.render();
            });
        });

        controls.querySelectorAll('.btn-period').forEach((btn) => {
            btn.addEventListener('click', () => {
                controls.querySelectorAll('.btn-period').forEach((b) => b.classList.remove('active'));
                btn.classList.add('active');
                this.period = btn.dataset.period;
                this.fetchAndRender();
            });
        });
    }

    async fetchAndRender() {
        if (!this.endpoint) {
            this.render();
            return;
        }
        try {
            const separator = this.endpoint.includes('?') ? '&' : '?';
            const response = await fetch(`${this.endpoint}${separator}period=${this.period}`);
            this.data = await response.json();
            this.render();
        } catch (err) {
            console.error('Impossible de charger les statistiques :', err);
        }
    }

    render() {
        const ctx = document.getElementById(this.canvasId);
        if (!ctx) return;

        if (this.chart) {
            this.chart.destroy();
        }

        const isCircular = this.chartType === 'doughnut';

        const totalGains = this.data.gains.reduce((a, b) => a + b, 0);
        const totalPertes = this.data.pertes.reduce((a, b) => a + b, 0);

        const config = isCircular
          ? {
              type: "doughnut",
              data: {
                labels: ["Gains", "Pertes"],
                datasets: [
                  {
                    data: [totalGains, totalPertes],
                    backgroundColor: ["#28a745", "#C83F49"],
                  },
                ],
              },
              options: { responsive: true, maintainAspectRatio: false },
            }
          : {
              type: this.chartType,
              data: {
                labels: this.data.labels,
                datasets: [
                  {
                    label: "Gains",
                    data: this.data.gains,
                    backgroundColor: "rgba(40, 167, 69, 0.7)",
                    borderColor: "#28a745",
                    borderWidth: 2,
                    tension: 0.4,
                  },
                  {
                    label: "Pertes",
                    data: this.data.pertes,
                    backgroundColor: "rgba(220, 53, 69, 0.7)",
                    borderColor: "#C83F49",
                    borderWidth: 2,
                    tension: 0.4,
                  },
                ],
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true } },
              },
            };

        this.chart = new Chart(ctx, config);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('canvas[data-trend-chart]').forEach((canvas) => {
        const initialData = JSON.parse(canvas.dataset.initial || '{"labels":[],"gains":[],"pertes":[]}');
        const endpoint = canvas.dataset.endpoint || null;
        new TrendChart(canvas.id, initialData, endpoint).init();
    });
});
