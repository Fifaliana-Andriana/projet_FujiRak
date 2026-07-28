class DashboardCharts {
  constructor() {
    this.chart = null;
    this.chartType = "bar";
  }

  init(canvasId, chartData) {
    this.canvasId = canvasId;
    this.data = chartData;

    this.createButtons();
    this.render();
  }

  createButtons() {
    const canvas = document.getElementById(this.canvasId);

    if (!canvas) return;

    const controls = document.createElement("div");
    controls.className = "chart-controls mb-3";

    controls.innerHTML = `
            <button class="btn btn-primary btn-sm me-2 active"
                data-type="bar">
                <i class="bi bi-bar-chart-fill"></i>
                Barres
            </button>

            <button class="btn btn-outline-primary btn-sm me-2"
                data-type="line">
                <i class="bi bi-graph-up"></i>
                Courbe
            </button>

            <button class="btn btn-outline-primary btn-sm"
                data-type="doughnut">
                <i class="bi bi-pie-chart-fill"></i>
                Circulaire
            </button>
        `;

    canvas.parentNode.insertBefore(controls, canvas);

    controls.querySelectorAll("button").forEach((btn) => {
      btn.addEventListener("click", () => {
        controls.querySelectorAll("button").forEach((b) => {
          b.classList.remove("btn-primary");
          b.classList.remove("active");

          b.classList.add("btn-outline-primary");
        });

        btn.classList.remove("btn-outline-primary");
        btn.classList.add("btn-primary");
        btn.classList.add("active");

        this.chartType = btn.dataset.type;

        this.render();
      });
    });
  }

  render() {
    const ctx = document.getElementById(this.canvasId);

    if (!ctx) return;

    if (this.chart) {
      this.chart.destroy();
    }

    let config = {
      type: this.chartType,

      data: {
        labels: this.data.labels,

        datasets: [],
      },

      options: {
        responsive: true,

        maintainAspectRatio: false,

        plugins: {
          legend: {
            display: true,
          },
        },
      },
    };

    if (this.chartType === "doughnut") {
      config.data.datasets = [
        {
          data: [this.data.gains, this.data.pertes],

          backgroundColor: ["#198754", "#dc3545"],
        },
      ];
    } else {
      config.data.datasets = [
        {
          label: "Montant",

          data: [this.data.gains, this.data.pertes, this.data.solde],

          backgroundColor: ["#198754", "#dc3545", "#0d6efd"],

          borderColor: ["#198754", "#dc3545", "#0d6efd"],

          borderWidth: 2,

          fill: false,

          tension: 0.4,
        },
      ];
    }

    this.chart = new Chart(ctx, config);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const canvas = document.getElementById("financeChart");

  if (!canvas) return;

  const chartData = {
    labels: ["Gains", "Pertes", "Solde"],

    gains: Number(canvas.dataset.gains),

    pertes: Number(canvas.dataset.pertes),

    solde: Number(canvas.dataset.solde),
  };

  const dashboardCharts = new DashboardCharts();

  dashboardCharts.init("financeChart", chartData);
});
