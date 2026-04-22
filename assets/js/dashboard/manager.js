$(function () {
  $(document).on("click", "#crearAsesor", function () {
    let bandera = true;
    const nombre = $('input[name="usuario"]').val().trim();
    const especialidad = $('select[name="especialidad"]').val();

    if (nombre === "") {
      bandera = false;
      $('input[name="usuario"]').css("border-color", "red").focus();
    }

    if (!especialidad || especialidad === "") {
      bandera = false;
      $('select[name="especialidad"]').css("border-color", "red").focus();
    }
    if (bandera) {
      $(this).attr("type", "submit");
    }
  });
  $(document).on("change blur", "select", function () {
    $(this).toggleClass("selected", $(this).val() !== "");
  });
});
