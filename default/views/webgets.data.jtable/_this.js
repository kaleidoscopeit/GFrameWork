$$.bind('webget.jtable.datarequired', function()
{
  //$$.jsimport('system.phpjs.var_dump');

  var ner=this.getNewlyExposedRecords();

  while(ner.length>0) {
    var recordset = {
      'range' : ner[0]
    };

    $$.call('user.sakila.film', recordset);
    
    //var_dump(recordset.items);
    //  $$.ajaxGet("test","foo");
    /*$$.each(recordset.items, function(item,i){
      //recordset.items[i].

    });*/
    this.fillData(recordset.items,ner.shift());
  }
});

$$.bind('webget.icon.ready', function()
{
  alert('a');
});