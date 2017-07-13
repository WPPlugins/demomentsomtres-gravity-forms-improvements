(function($) {
  var lang=$("html").attr("lang");
  $.getScript("https://raw.githubusercontent.com/jquery/jquery-ui/master/ui/i18n/datepicker-"+lang+".js");
  $(document).bind('gform_post_render', function() {
    $(".dateinline").each(function() {
      element = $(this);
      if (typeof element.parent().find("input").attr("dms3nodefault") !== "undefined") {
        element.datepicker({
          altField: element.parent().find('input'),
          onSelect: function(dateText,object){
            $(this).parent().find('input').keyup();
          },
        });
        element.find('.ui-state-default').removeClass('ui-state-default').removeClass("ui-state-highlight").removeClass("ui-state-active");
        element.parent().find('input').val("");
      } else {
        element.datepicker({
          altField: element.parent().find('input'),
        });
      }
      var options=$.datepicker.regional[lang];
      element.datepicker( "option",options);
    });
  });
})(jQuery);