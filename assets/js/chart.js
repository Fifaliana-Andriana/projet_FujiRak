/**
 * TrendChart : tableau de bord de graphique, affichage permanent, sans choix de vue.
 *  - Barres + Courbe : toujours affichés (deux séries ou une seule)
 *  - Circulaire avec pourcentage au centre : affiché uniquement si 2 séries (masqué si singleSeries)
 *  - Filtre par période : jour / mois / année (refetch AJAX vers `endpoint`)
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
    this.baseId = canvasId;
    this.data = initialData;
    this.endpoint = endpoint;
    this.period = "month";
    this.barChart = null;
    this.lineChart = null;
    this.doughnutChart = null;
    this.label1 = label1;
    this.label2 = label2;
    this.singleSeries = singleSeries || !label2;
  }

  init() {
    const originalCanvas = document.getElementById(this.baseId);
    if (!originalCanvas) return;

    const container = document.createElement("div");
    container.className = "chart-dashboard";

    container.innerHTML = `
            <div class="chart-period-bar">
                <div class="period-buttons d-flex justify-content-end gap-2">
                    <button class="btn-period" data-period="day">Jour</button>
                    <button class="btn-period active" data-period="month">Mois</button>
                    <button class="btn-period" data-period="year">Année</button>
                </div>
            </div>
            <div class="chart-dashboard-body">
                <div class="chart-main-panel">
                    <div class="chart-sub-block">
                        <div class="chart-canvas-wrapper chart-canvas-wrapper-sm">
                            <canvas id="${this.baseId}-bar"></canvas>
                        </div>
                    </div>
                    <div class="chart-sub-block">
                        <div class="chart-canvas-wrapper chart-canvas-wrapper-sm">
                            <canvas id="${this.baseId}-line"></canvas>
                        </div>
                    </div>
                </div>
                ${
                  this.singleSeries
                    ? ""
                    : `
                <div class="chart-side-panel">
                    <div class="chart-doughnut-wrap">
                        <canvas id="${this.baseId}-doughnut"></canvas>
                        <div class="chart-doughnut-center" id="${this.baseId}-percent">0%</div>
                    </div>
                    <ul class="chart-mini-legend">
                        <li><span class="chart-dot chart-dot-gain"></span> ${this.label1}</li>
                        <li><span class="chart-dot chart-dot-perte"></span> ${this.label2}</li>
                    </ul>
                </div>`
                }
            </div>
        `;

    originalCanvas.parentNode.replaceChild(container, originalCanvas);

    container.querySelectorAll(".btn-period").forEach((btn) => {
      btn.addEventListener("click", () => {
        container
          .querySelectorAll(".btn-period")
          .forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");
        this.period = btn.dataset.period;
        this.fetchAndRender();
      });
    });

    this.render();
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
    this.renderBar();
    this.renderLine();
    if (!this.singleSeries) {
      this.renderDoughnut();
    }
  }

  buildDatasets(styleType) {
    const isLine = styleType === "line";

    const datasets = [
      {
        label: this.label1,
        data: this.data.gains,
        backgroundColor: isLine
          ? "rgba(40, 167, 69, 0.15)"
          : "rgba(40, 167, 69, 0.75)",
        borderColor: "#28a745",
        borderWidth: 2,
        borderRadius: isLine ? 0 : 6,
        tension: 0.4,
        fill: isLine,
      },
    ];

    if (!this.singleSeries) {
      datasets.push({
        label: this.label2,
        data: this.data.pertes || [],
        backgroundColor: isLine
          ? "rgba(200, 63, 73, 0.15)"
          : "rgba(200, 63, 73, 0.75)",
        borderColor: "#C83F49",
        borderWidth: 2,
        borderRadius: isLine ? 0 : 6,
        tension: 0.4,
        fill: isLine,
      });
    }

    return datasets;
  }

  renderBar() {
    const ctx = document.getElementById(`${this.baseId}-bar`);
    if (!ctx) return;

    if (this.barChart) {
      this.barChart.destroy();
    }

    this.barChart = new Chart(ctx, {
      type: "bar",
      data: { labels: this.data.labels, datasets: this.buildDatasets("bar") },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: this.singleSeries } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  renderLine() {
    const ctx = document.getElementById(`${this.baseId}-line`);
    if (!ctx) return;

    if (this.lineChart) {
      this.lineChart.destroy();
    }

    this.lineChart = new Chart(ctx, {
      type: "line",
      data: { labels: this.data.labels, datasets: this.buildDatasets("line") },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: this.singleSeries } },
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  renderDoughnut() {
    const ctx = document.getElementById(`${this.baseId}-doughnut`);
    if (!ctx) return;

    if (this.doughnutChart) {
      this.doughnutChart.destroy();
    }

    const totalGains = this.data.gains.reduce((a, b) => a + b, 0);
    const totalPertes = (this.data.pertes || []).reduce((a, b) => a + b, 0);
    const total = totalGains + totalPertes;
    const percent = total > 0 ? Math.round((totalGains / total) * 100) : 0;

    const percentEl = document.getElementById(`${this.baseId}-percent`);
    if (percentEl) {
      percentEl.textContent = `${percent}%`;
    }

    this.doughnutChart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: [this.label1, this.label2],
        datasets: [
          {
            data: [totalGains, totalPertes],
            backgroundColor: ["#28a745", "#C83F49"],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "70%",
        plugins: { legend: { display: false } },
      },
    });
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
