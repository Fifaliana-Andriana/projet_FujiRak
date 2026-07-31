/**
 * TrendChart : graphique dans le temps, avec :
 *  - jusqu'à 3 modes d'affichage : barres / courbe / circulaire (circulaire masqué si singleSeries)
 *  - filtre par période : jour / mois / année (refetch AJAX vers `endpoint`)
 *
 * Utilisation :
 *   new TrendChart('financeChart', initialData, 'index.php?route=admin/stats-json', 'Gains', 'Pertes');
 *   new TrendChart('usersChart', initialData, 'index.php?route=admin/statistics-json', 'Inscriptions', null, true);
 *
 * `initialData` = { labels: [...], gains: [...], pertes: [...] }  (pertes optionnel si singleSeries)
 */
class TrendChart {
  constructor(
    canvasId,
    initialData,
    endpoint,
    label1 = "Gains",
    label2 = null,
    singleSeries = false,
  ) {
    this.canvasId = canvasId;
    this.data = initialData;
    this.endpoint = endpoint;
    this.chartType = "bar";
    this.period = "month";
    this.chart = null;
    this.label1 = label1;
    this.label2 = label2;
    this.singleSeries = singleSeries;
  }

  init() {
    const canvas = document.getElementById(this.canvasId);
    if (!canvas) return;

    // Empêche la boucle de redimensionnement infinie de Chart.js :
    // le canvas responsive a besoin d'un parent à hauteur FIXE.
    const wrapper = document.createElement("div");
    wrapper.className = "chart-canvas-wrapper";
    canvas.parentNode.insertBefore(wrapper, canvas);
    wrapper.appendChild(canvas);

    this.buildControls(wrapper);
    this.render();
  }

  buildControls(wrapperEl) {
    const controls = document.createElement("div");
    controls.className = "chart-controls mb-3 bg-transparent";

    const doughnutButton = this.singleSeries
      ? ""
      : `
            <button class="btn-chart-type border-0" data-type="doughnut">
                <i class="bi bi-pie-chart-fill"></i> Circulaire
            </button>
        `;

    controls.innerHTML = `
            <div class="chart-type-buttons border-0">
                <button class="btn-chart-type border-0 active" data-type="bar">
                    <i class="bi bi-bar-chart-fill"></i> Barres
                </button>
                <button class="btn-chart-type border-0" data-type="line">
                    <i class="bi bi-graph-up"></i> Courbe
                </button>
                ${doughnutButton}
            </div>
            <div class="period-buttons border-0">
                <button class="btn-period border-0" data-period="day">Jour</button>
                <button class="btn-period border-0 active" data-period="month">Mois</button>
                <button class="btn-period border-0" data-period="year">Année</button>
            </div>
        `;

    wrapperEl.parentNode.insertBefore(controls, wrapperEl);

    controls.querySelectorAll(".btn-chart-type").forEach((btn) => {
      btn.addEventListener("click", () => {
        controls
          .querySelectorAll(".btn-chart-type")
          .forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
        this.chartType = btn.dataset.type;
        this.render();
      });
    });

    controls.querySelectorAll(".btn-period").forEach((btn) => {
      btn.addEventListener("click", () => {
        controls
          .querySelectorAll(".btn-period")
          .forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
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
      const separator = this.endpoint.includes("?") ? "&" : "?";
      const response = await fetch(
        `${this.endpoint}${separator}period=${this.period}`,
      );
      this.data = await response.json();
      this.render();
    } catch (err) {
      console.error("Impossible de charger les statistiques :", err);
    }
  }

  render() {
    const ctx = document.getElementById(this.canvasId);
    if (!ctx) return;

    if (this.chart) {
      this.chart.destroy();
    }

    const isCircular = this.chartType === "doughnut";

    const totalGains = this.data.gains.reduce((a, b) => a + b, 0);
    const totalPertes = (this.data.pertes || []).reduce((a, b) => a + b, 0);

    const config = isCircular
      ? {
          type: "doughnut",
          data: {
            labels: [this.label1, this.label2],
            datasets: [
              {
                data: [totalGains, totalPertes],
                backgroundColor: ["#28a745", "#7c1f25"],
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
                label: this.label1,
                data: this.data.gains,
                backgroundColor: "rgba(40, 167, 69, 0.7)",
                border: "none",
                borderWidth: 2,
                tension: 0.4,
              },
              ...(this.label2
                ? [
                    {
                      label: this.label2,
                      data: this.data.pertes || [],
                      backgroundColor: "#7c1f25",
                      border: "none",
                      borderWidth: 2,
                      tension: 0.4,
                    },
                  ]
                : []),
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: !this.singleSeries } },
            scales: { y: { beginAtZero: true } },
          },
        };

    this.chart = new Chart(ctx, config);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("canvas[data-trend-chart]").forEach((canvas) => {
    const initialData = JSON.parse(
      canvas.dataset.initial || '{"labels":[],"gains":[],"pertes":[]}',
    );
    const endpoint = canvas.dataset.endpoint || null;
    const label1 = canvas.dataset.label1 || "Gains";
    const label2 = canvas.dataset.label2 || null;
    const singleSeries = canvas.hasAttribute("data-single-series");
    new TrendChart(
      canvas.id,
      initialData,
      endpoint,
      label1,
      label2,
      singleSeries,
    ).init();
  });
});
