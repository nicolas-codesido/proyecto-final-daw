document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  let fechaInicioSeleccionada, fechaFinSeleccionada;
  let vistaActual = "dayGridMonth";

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "es",
    firstDay: 1,
    height: "auto",

    displayEventTime: false,
    displayEventEnd: false,

    events: function (info, successCallback, failureCallback) {
      const fechaInicio = info.start.toISOString().split("T")[0];
      const fechaFin = info.end.toISOString().split("T")[0];

      fetch(
        `../../data/citas-api.php?vista=${vistaActual}&inicio=${fechaInicio}&fin=${fechaFin}`
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
          }
          return response.text();
        })
        .then((text) => {
          try {
            const data = JSON.parse(text);
            if (Array.isArray(data)) {
              successCallback(data);
            } else {
              console.error("La respuesta no es un array:", data);
              successCallback([]);
            }
          } catch (e) {
            console.error("Error parsing JSON:", e, "Texto:", text);
            successCallback([]);
          }
        })
        .catch((error) => {
          console.error("Error fetch:", error);
          successCallback([]);
        });
    },

    headerToolbar: {
      left: "prev,next",
      center: "",
      right: "",
    },

    buttonText: {
      prev: "←",
      next: "→",
    },

    validRange: {
      start: new Date(),
    },

    slotMinTime: "10:00:00",
    slotMaxTime: "20:00:00",
    slotDuration: "01:00:00",
    slotLabelInterval: "01:00:00",
    allDaySlot: false,

    dateClick: function (info) {
      const dayOfWeek = new Date(info.dateStr).getDay();
      if (dayOfWeek !== 0 && dayOfWeek !== 6) {
        calendar.changeView("timeGridDay", info.dateStr);
      }
    },

    selectable: true,
    selectMirror: true,

    selectAllow: function (selectInfo) {
      const dayOfWeek = selectInfo.start.getDay();

      if (dayOfWeek === 0 || dayOfWeek === 6) {
        return false;
      }

      const start = selectInfo.start.getHours();
      const end = selectInfo.end.getHours();

      if ((start >= 14 && start < 16) || (end > 14 && end <= 16)) {
        return false;
      }
      return true;
    },

    eventAllow: function (dropInfo, draggedEvent) {
      const dayOfWeek = dropInfo.start.getDay();
      return dayOfWeek !== 0 && dayOfWeek !== 6;
    },

    select: function (info) {
      if (vistaActual === "timeGridDay") {
        crearNuevaCita(info.start, info.end);
      }
      calendar.unselect();
    },

    viewDidMount: function (info) {
      vistaActual = info.view.type;
      calendar.refetchEvents();

      const backLink = document.getElementById("backLink");
      if (info.view.type === "timeGridDay") {
        backLink.textContent = "← Volver a vista mes";
        backLink.href = "#";
        backLink.onclick = function (e) {
          e.preventDefault();
          calendar.changeView("dayGridMonth");
        };
      } else {
        backLink.textContent = "← Volver a clientes";
        backLink.href = "asesor.php";
        backLink.onclick = null;
      }

      actualizarMesHeader();
    },

    businessHours: {
      daysOfWeek: [1, 2, 3, 4, 5],
      startTime: "10:00",
      endTime: "20:00",
    },

    views: {
      timeGridDay: {
        titleFormat: {
          day: "numeric",
          month: "long",
          year: "numeric",
        },
        slotLabelFormat: {
          hour: "2-digit",
          minute: "2-digit",
          hour12: false,
        },
        hiddenDays: [0, 6],
      },
    },
  });

  calendar.render();

  actualizarMesHeader();

  function actualizarMesHeader() {
    const fecha = calendar.getDate();
    const meses = [
      "enero",
      "febrero",
      "marzo",
      "abril",
      "mayo",
      "junio",
      "julio",
      "agosto",
      "septiembre",
      "octubre",
      "noviembre",
      "diciembre",
    ];

    const mesTexto = meses[fecha.getMonth()];
    const año = fecha.getFullYear();

    const mesHeader = document.getElementById("mesActual");

    if (vistaActual === "timeGridDay") {
      const dia = fecha.getDate();
      mesHeader.textContent = `${dia} ${mesTexto} ${año}`;
    } else {
      mesHeader.textContent = `${mesTexto} ${año}`;
    }
  }

  document.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("fc-prev-button") ||
      e.target.classList.contains("fc-next-button")
    ) {
      setTimeout(actualizarMesHeader, 100);
    }
  });

  function crearNuevaCita(fechaInicio, fechaFin) {
    fechaInicioSeleccionada = fechaInicio;
    fechaFinSeleccionada = fechaFin;

    const modal = document.getElementById("modalCita");
    const horarioInfo = document.getElementById("horarioSeleccionado");

    horarioInfo.textContent = `${formatearHorario(
      fechaInicio
    )} - ${formatearHorario(fechaFin)}`;

    document.getElementById("descripcion").value = "";
    document.getElementById("clienteBuscar").value = "";
    document.getElementById("clienteSeleccionado").value = "";

    cargarClientes();
    modal.style.display = "flex";
  }

  function formatearHorario(fecha) {
    return fecha.toLocaleTimeString("es-ES", {
      hour: "2-digit",
      minute: "2-digit",
      day: "numeric",
      month: "short",
    });
  }

  let todosLosClientes = [];
  let clienteSeleccionadoId = null;

  function cargarClientes() {
    fetch("../../data/clientes-api.php")
      .then((response) => response.json())
      .then((clientes) => {
        todosLosClientes = clientes;
        configurarBuscadorClientes();
      })
      .catch((error) => {
        console.error("Error cargando clientes:", error);
      });
  }

  function configurarBuscadorClientes() {
    const input = document.getElementById("clienteBuscar");
    const resultados = document.getElementById("resultadosClientes");
    const hiddenInput = document.getElementById("clienteSeleccionado");

    input.addEventListener("input", function () {
      const busqueda = this.value.toLowerCase().trim();

      if (busqueda.length < 2) {
        resultados.style.display = "none";
        return;
      }

      const clientesFiltrados = todosLosClientes
        .filter((cliente) => cliente.nombre.toLowerCase().includes(busqueda))
        .slice(0, 8);

      mostrarResultados(clientesFiltrados, resultados, input, hiddenInput);
    });

    document.addEventListener("click", function (e) {
      if (!input.contains(e.target) && !resultados.contains(e.target)) {
        resultados.style.display = "none";
      }
    });
  }

  function mostrarResultados(clientes, contenedor, input, hiddenInput) {
    if (clientes.length === 0) {
      contenedor.innerHTML =
        '<div class="cliente-resultado">No se encontraron clientes</div>';
      contenedor.style.display = "block";
      return;
    }

    contenedor.innerHTML = clientes
      .map(
        (cliente) =>
          `<div class="cliente-resultado" data-id="${cliente.id}">${cliente.nombre}</div>`
      )
      .join("");

    contenedor.querySelectorAll(".cliente-resultado").forEach((item) => {
      item.addEventListener("click", function () {
        const clienteId = this.dataset.id;
        const clienteNombre = this.textContent;

        if (clienteId) {
          input.value = clienteNombre;
          hiddenInput.value = clienteId;
          clienteSeleccionadoId = clienteId;
          contenedor.style.display = "none";
        }
      });
    });

    contenedor.style.display = "block";
  }

  function formatearFechaLocal(fecha) {
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, "0");
    const day = String(fecha.getDate()).padStart(2, "0");
    const hours = String(fecha.getHours()).padStart(2, "0");
    const minutes = String(fecha.getMinutes()).padStart(2, "0");
    const seconds = String(fecha.getSeconds()).padStart(2, "0");

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  }

  document.getElementById("cancelarCita").onclick = function () {
    document.getElementById("modalCita").style.display = "none";
  };

  document.getElementById("modalCita").onclick = function (e) {
    if (e.target === this) {
      this.style.display = "none";
    }
  };

  document.getElementById("formCita").onsubmit = function (e) {
    e.preventDefault();

    const clienteId = document.getElementById("clienteSeleccionado").value;
    const clienteBuscar = document.getElementById("clienteBuscar").value;

    if (!clienteId || clienteId.trim() === "") {
      alert("Por favor, selecciona un cliente de la lista");
      return;
    }

    const datos = {
      accion: "crear",
      descripcion: document.getElementById("descripcion").value,
      clienteId: clienteId,
      fechaInicio: formatearFechaLocal(fechaInicioSeleccionada),
      fechaFin: formatearFechaLocal(fechaFinSeleccionada),
    };

    fetch("../../data/citas-api.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(datos),
    })
      .then((response) => response.text())
      .then((data) => {
        try {
          const jsonData = JSON.parse(data);
          if (jsonData.success) {
            calendar.refetchEvents();
            document.getElementById("modalCita").style.display = "none";
            alert("Cita creada correctamente");
          } else {
            alert("Error: " + jsonData.message);
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
          alert("Error: " + data);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error de conexión");
      });
  };
});
