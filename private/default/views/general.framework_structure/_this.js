$_.bind('webget.infobutt.click', function()
{
  subv_cont.goto('view_source&source=general.framework_structure');
  $_.removeClass(view_source, 'diag_hidden');
});
