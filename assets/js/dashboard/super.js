$(function () {
  $.getJSON("../../data/branches.php")
    .done(function (datos) {
      $.getJSON("../../data/branches-with-manager.php").done(function (
        sucursalesConAdmin
      ) {
        $("select").after("<select>");
        $("select").last().attr("name", "sucursal").append("<option>");

        $("select[name='sucursal']>option")
          .prop("disabled", true)
          .prop("selected", true)
          .text("Sucursal");

        $.each(datos, function () {
          if (!sucursalesConAdmin.includes(this.id)) {
            $("select[name='sucursal']").append("<option>");
            $("select[name='sucursal']>option")
              .last()
              .text(this.nombre)
              .val(this.id);
          }
        });
      });
    })
    .fail(function () {
      alert("Error en la base de datos: sucursales");
    });

  $(document).on("click", "#crearCuenta", function () {
    let bandera1 = true;
    let bandera2 = true;

    if ($("input").val() == "") {
      $("input").css("border-color", "red");
      bandera1 = false;
    } else {
      bandera1 = true;
      $("input").css("border-color", "black");
    }

    $("select").each(function () {
      if (!$(this).val()) {
        bandera2 = false;
        $(this).css("border-color", "red");
      } else {
        $(this).css("border-color", "black");
      }
    });

    if (bandera1 && bandera2) {
      $(this).attr("type", "submit");
    }
  });

  $(document).on("change blur", "select", function () {
    $(this).toggleClass("selected", $(this).val() !== "");
  });
});
