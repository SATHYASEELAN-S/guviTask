// Profile page navigation buttons
$(document).ready(function () {
  $(".nav-btn").click(function () {
    $(".nav-btn").removeClass("active");
    $(this).addClass("active");
    var target = $(this).data("target");
    $(".content").removeClass("active");
    $("#" + target).addClass("active");
  });
});
