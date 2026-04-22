$(function () {
  $(document).on("click", "#activarCuenta", function () {
    let relleno = true;
    let contraseña = $("#contraseñaToken").val();
    let confirmar = $("#confirmarToken").val();
    /* const regexSegura = /^(?=.*[\W_]).{8,}$/; */
    let regexSegura = /^.{3,}$/;

    if (!regexSegura.test(contraseña)) {
      $(".obligatorio").text("Contraseña débil.");
      relleno = false;
    } else if (contraseña !== confirmar) {
      $(".obligatorio").text("Las contraseñas deben coincidir.");
      relleno = false;
    } else {
      $(".obligatorio").text("");
    }

    $("input").each(function () {
      if ($(this).val() == "") {
        $(this).css("border-color", "red");
        $(".obligatorio").text("");
        relleno = false;
      } else {
        $(this).css("border-color", "black");
      }
    });

    if (relleno) {
      $(this).attr("type", "submit");
    }
  });
});
