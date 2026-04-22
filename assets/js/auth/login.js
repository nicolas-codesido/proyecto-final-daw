$(function () {
  $(document).on("click", "#iniciarSesion", function () {
    let relleno = true;
    $("input").each(function () {
      if ($(this).val() == "") {
        $(this).css("border-color", "red");
        relleno = false;
      } else {
        $(".obligatorio").text("");
        $(this).css("border-color", "black");
      }
    });

    if (relleno) {
      $(this).attr("type", "submit");
    }
  });
});
