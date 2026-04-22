$(function () {
  const fecha = $('input[name="fecha_nacimiento"]');

  function actualizarColor() {
    const valor = fecha.val();
    if (valor && !isNaN(Date.parse(valor))) {
      fecha.css("color", "black").addClass("negro");
    } else {
      fecha.css("color", "var(--brd)").removeClass("negro");
    }
  }

  fecha.on("change input blur", actualizarColor);

  // Establecer fecha máxima como hoy
  function calcularMaxFecha() {
    const hoy = new Date();
    return hoy.toISOString().split("T")[0];
  }
  fecha.attr("max", calcularMaxFecha());

  $(document).on("click", "#añadirCliente", function () {
    let valido = true;

    function validarCampo(selector, condicion, esOpcional = false) {
      const campo = $(selector);
      const valor = campo.val().trim();

      if (!esOpcional && !valor) {
        campo.css("border-color", "red");
        return false;
      }

      if (valor && !condicion(valor)) {
        campo.css("border-color", "red");
        return false;
      }

      campo.css("border-color", "var(--brd)");
      return true;
    }

    const regexDNI = /^\d{8}[A-Za-z]$/;
    const regexTelefono = /^\d{9}$/;
    const regexPatrimonio = /^\d{1,3}(,\d{3})*(\.\d{1,2})?$|^\d+(\.\d{1,2})?$/;
    const regexComision = /^\d{1,2}(\.\d{1,2})?$|^100(\.0{1,2})?$/;

    valido &= validarCampo('input[name="nombre"]', () => true);
    valido &= validarCampo('input[name="dni"]', (valor) =>
      regexDNI.test(valor)
    );
    valido &= validarCampo('input[name="telefono"]', (valor) =>
      regexTelefono.test(valor)
    );
    valido &= validarCampo(
      'input[name="fecha_nacimiento"]',
      (valor) => !isNaN(Date.parse(valor))
    );
    valido &= validarCampo('input[name="patrimonio"]', (valor) =>
      regexPatrimonio.test(valor)
    );
    valido &= validarCampo('input[name="comision_porcentaje"]', (valor) => {
      return (
        regexComision.test(valor) &&
        parseFloat(valor) >= 0 &&
        parseFloat(valor) <= 100
      );
    });

    if (!$('select[name="perfil_riesgo"]').val()) {
      $('select[name="perfil_riesgo"]').css("border-color", "red");
      valido = false;
    } else {
      $('select[name="perfil_riesgo"]').css("border-color", "var(--brd)");
    }

    if (valido) {
      $(this).attr("type", "submit");
    }
  });

  $(document).on("change blur", "select", function () {
    $(this).toggleClass("selected", $(this).val() !== "");
  });
});
