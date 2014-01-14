window.getImage = function(target,query){
  var url={
    'query' : query + "+poster+film",
  }
 
  $$.call('user.data.google.get_image', url,null,function(buffer){
    target.src=buffer[0];
  });


};

$$.bind('webget.icon.define', function()
{
  this.setAttribute("eval_field_command","getImage(this,'%s')");
});

$$.bind('webget.jtable.datarequired', function()
{
  //$$.jsimport('system.phpjs.var_dump');

  var ner=this.getNewlyExposedRecords();

  while(ner.length>0) {
    var recordset = {
      'range' : ner[0],
      'filter' : record_filter
    };

    $$.call('user.sakila.film', recordset);
    //console.log(recordset);
    //var_dump(recordset.items);
    //  $$.ajaxGet("test","foo");
    /*$$.each(recordset.items, function(item,i){
      //recordset.items[i].

    });*/
    this.fillData(recordset.items,ner.shift());
  }
});

$$.bind('webget.infobutt.click', function()
{
  subv_cont.goto('view_source&source=webgets.data.jtable');
  $$.removeClass(view_source, 'diag_hidden');
});

$$.bind('webget.cerca.keyup', function()
{
  record_filter = this.value;
  jtable.clear();
  jtable.dispatchEvent(jtable.datarequired);
});

record_filter="";
