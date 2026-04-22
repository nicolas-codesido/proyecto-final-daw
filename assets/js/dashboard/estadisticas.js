let chartInstances = {};

Chart.defaults.font.family = "Arial, sans-serif";
Chart.defaults.font.size = 12;

function setupTabs() {
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".chart-wrapper");

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const tabName = this.dataset.tab;

      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      this.classList.add("active");
      document.getElementById("tab-" + tabName).classList.add("active");

      if (!chartInstances[tabName]) {
        createChart(tabName);
      }
    });
  });
}

/**
 *
 * @param {string} tabName
 */
function createChart(tabName) {
  try {
    switch (tabName) {
      case "patrimonio":
        chartInstances.patrimonio = crearGraficoPatrimonio();
        break;
      case "riesgo":
        chartInstances.riesgo = crearGraficoRiesgo();
        break;
      case "clientes":
        chartInstances.clientes = crearGraficoBurbujas();
        break;
      case "top-clientes":
        chartInstances["top-clientes"] = crearGraficoTopClientes();
        break;
      default:
        console.error("Tab no reconocido:", tabName);
    }
  } catch (error) {
    console.error("Error creando gráfico:", error);
    showError("Error cargando el gráfico: " + error.message);
  }
}

/**
 * Crear gráfico de patrimonio medio por sucursal
 * @returns {Chart} Instancia del gráfico
 */
function crearGraficoPatrimonio() {
  const ctx = document.getElementById("patrimonioChart").getContext("2d");

  return new Chart(ctx, {
    type: "bar",
    data: window.datosGraficos.patrimonio,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              return `€${context.parsed.y.toLocaleString("es-ES", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })}`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Patrimonio Medio (€)",
          },
          ticks: {
            callback: function (value) {
              return "€" + value.toLocaleString("es-ES");
            },
          },
        },
        x: {
          title: {
            display: true,
            text: "Sucursales",
          },
        },
      },
      animation: {
        duration: 1000,
        easing: "easeOutQuart",
      },
    },
  });
}

/**
 * Crear gráfico de distribución por perfil de riesgo
 * @returns {Chart} Instancia del gráfico
 */
function crearGraficoRiesgo() {
  const ctx = document.getElementById("riesgoChart").getContext("2d");

  return new Chart(ctx, {
    type: "doughnut",
    data: window.datosGraficos.riesgo,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
          labels: {
            padding: 20,
            usePointStyle: true,
            font: {
              size: 14,
            },
          },
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed * 100) / total).toFixed(1);
              return (
                context.label +
                ": " +
                percentage +
                "% (" +
                context.parsed +
                " clientes)"
              );
            },
          },
        },
      },
      animation: {
        animateRotate: true,
        duration: 1500,
      },
    },
  });
}

/**
 * Crear gráfico de burbujas por sucursal
 * @returns {Chart} Instancia del gráfico
 */
function crearGraficoBurbujas() {
  const ctx = document.getElementById("clientesChart").getContext("2d");

  if (
    !window.datosGraficos.burbujas ||
    !window.datosGraficos.burbujas.datasets ||
    window.datosGraficos.burbujas.datasets.length === 0
  ) {
    showError("No hay datos disponibles para el análisis por sucursal");
    return null;
  }

  return new Chart(ctx, {
    type: "bubble",
    data: window.datosGraficos.burbujas,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: "bottom",
          labels: {
            padding: 20,
            usePointStyle: true,
            font: {
              size: 12,
              weight: "500",
            },
          },
        },
        tooltip: {
          callbacks: {
            title: function (context) {
              return context[0].dataset.label;
            },
            label: function (context) {
              const data = context.raw;
              if (!data || typeof data.r === "undefined") {
                return "Sin datos";
              }
              const empleados = Math.max(1, Math.round(data.r / 4));
              return [
                `Clientes: ${data.x || 0}`,
                `Patrimonio: €${data.y || 0}K`,
                `Empleados: ${empleados}`,
              ];
            },
          },
          backgroundColor: "rgba(0,0,0,0.8)",
          titleColor: "#fff",
          bodyColor: "#fff",
          borderColor: "rgba(255,255,255,0.1)",
          borderWidth: 1,
        },
      },
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Número de Clientes",
            font: {
              size: 14,
              weight: "bold",
            },
          },
          grid: {
            color: "rgba(0,0,0,0.05)",
            lineWidth: 1,
          },
          ticks: {
            font: {
              size: 11,
            },
          },
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Patrimonio Total (Miles €)",
            font: {
              size: 14,
              weight: "bold",
            },
          },
          grid: {
            color: "rgba(0,0,0,0.05)",
            lineWidth: 1,
          },
          ticks: {
            font: {
              size: 11,
            },
            callback: function (value) {
              return "€" + value + "K";
            },
          },
        },
      },
      elements: {
        point: {
          hoverRadius: 12,
        },
      },
      animation: {
        duration: 2000,
        easing: "easeOutElastic",
      },
      interaction: {
        intersect: false,
      },
    },
  });
}

/**
 * Crear gráfico de top clientes por patrimonio
 * @returns {Chart} Instancia del gráfico
 */
function crearGraficoTopClientes() {
  const ctx = document.getElementById("topClientesChart").getContext("2d");

  return new Chart(ctx, {
    type: "bar",
    data: window.datosGraficos.topClientes,
    options: {
      indexAxis: "y",
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              return `€${context.parsed.x.toLocaleString("es-ES", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })}`;
            },
          },
        },
      },
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: true,
            text: "Patrimonio (€)",
          },
          ticks: {
            callback: function (value) {
              return "€" + value.toLocaleString("es-ES");
            },
          },
        },
        y: {
          title: {
            display: true,
            text: "Clientes",
          },
        },
      },
      animation: {
        duration: 1000,
        easing: "easeOutBounce",
      },
    },
  });
}

/**
 * Mostrar mensaje de error
 * @param {string} message - Mensaje de error
 * @param {string} containerId - ID del contenedor específico (opcional)
 */
function showError(message, containerId = null) {
  const errorDiv = document.createElement("div");
  errorDiv.className = "error-message";
  errorDiv.textContent = message;

  let container;
  if (containerId) {
    container = document.getElementById(containerId);
    if (container) {
      container.innerHTML = "";
      container.appendChild(errorDiv);
      return;
    }
  }

  // Si no hay contenedor específico, usar el principal
  container = document.querySelector(".dashboard-container");
  container.innerHTML = "";
  container.appendChild(errorDiv);
}

/**
 * Destruir todos los gráficos (útil para limpieza)
 */
function destroyAllCharts() {
  Object.values(chartInstances).forEach((chart) => {
    if (chart && typeof chart.destroy === "function") {
      chart.destroy();
    }
  });
  chartInstances = {};
}

/**
 * Inicializar el dashboard cuando la página esté lista
 */
document.addEventListener("DOMContentLoaded", function () {
  // Verificar si hay datos disponibles
  if (!window.datosGraficos) {
    showError(window.errorDB || "Error cargando datos");
    return;
  }

  try {
    setupTabs();
    // Crear solo el primer gráfico inicialmente
    chartInstances.patrimonio = crearGraficoPatrimonio();
  } catch (error) {
    console.error("Error inicializando dashboard:", error);
    showError("Error cargando el dashboard");
  }
});

// Limpiar gráficos al salir de la página
window.addEventListener("beforeunload", function () {
  destroyAllCharts();
});
