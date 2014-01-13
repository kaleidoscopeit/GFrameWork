window.getImage = function(target,query){
  var url={
    'query' : query
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
      'range' : ner[0]
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

