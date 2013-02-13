$(function() {
  $('.vbx-applet-weather .radio-cell input').live('click', function(event) {
    var tr = $(this).closest('tr');
    $('tr', tr.closest('table')).each(function (index, element) {
      $(element).removeClass('on').addClass('off');
    });
    tr.addClass('on').removeClass('off');
  });
});