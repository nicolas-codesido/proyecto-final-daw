$(function () {
  $(".ojo").hide();
  $(document).on("click", ".ojo", function () {
    $(this).toggleClass("fa-eye fa-eye-slash");

    let input = $(this).prev("input");
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
  });

  $(document).on("input", "input", function () {
    const ojo = $(this).next(".ojo");
    ojo.toggle($(this).val().length > 0);
  });
});
