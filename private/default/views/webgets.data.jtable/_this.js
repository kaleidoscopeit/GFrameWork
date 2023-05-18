window.getImage = function(target,query){
  console.log(query);
  var url={
    'query' : query + "+poster+film",
  }

  $_.call('data.google.get_image', url,null,function(buffer){
    target.src=buffer[0];
  });


};

$_.bind('webget.icon.define', function()
{
  this.setAttribute("eval_field_command","getImage(this,'{0}')");
});

$_.bind('webget.jtable.datarequired', function()
{
  //_.jsimport('system.phpjs.var_dump');

  var ner=this.getNewlyExposedRecords();

  while(ner.length>0) {
    var recordset = {
      'range' : ner[0],
      'filter' : record_filter
    };

    $_.call('sakila.film', recordset);
    //console.log(recordset);
    //var_dump(recordset.items);
    //  $_.ajaxGet("test","foo");
    /*$_.each(recordset.items, function(item,i){
      //recordset.items[i].

    });*/
    this.fillData(recordset.items,ner.shift());
  }
});

$_.bind('webget.infobutt.click', function()
{
  subv_cont.goto('view_source&source=webgets.data.jtable');
  $_.removeClass(view_source, 'diag_hidden');
});

$_.bind('webget.cerca.keyup', function()
{
  record_filter = this.value;
  jtable.clear();
  jtable.dispatchEvent(jtable.datarequired);
});

record_filter="";
