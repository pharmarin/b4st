jQuery(function () {
  jQuery('[data-toggle="popover"]').popover({
    title: function () {
      let slug = jQuery(this).data('slug');
      return popover[slug].title;
    },
    content: function () {
      let slug = jQuery(this).data('slug');
      return popover[slug].content;
    }
  })
})

function get_the_ID ($this) {
  console.log($this);
}

jQuery(document).ready(function(e) {
    jQuery('img[usemap]').rwdImageMaps();
});
