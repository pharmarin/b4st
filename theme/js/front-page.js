"use strict";jQuery(function(){jQuery(".carousel").carousel({interval:!1,keyboard:!1,wrap:!1}),jQuery(".carousel-toggle").on("change",function(e){console.log(e),jQuery(".carousel").carousel(jQuery(this).data("slide-to"))})});