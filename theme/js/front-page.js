jQuery(function () {
  jQuery('.carousel').carousel({
    interval: false,
    keyboard: false,
    wrap: false
  });
  jQuery('.carousel-toggle').on('change', function (event) {
    console.log(event);
    jQuery('.carousel').carousel(jQuery(this).data("slide-to"));
  })
})
