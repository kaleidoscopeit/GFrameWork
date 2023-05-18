$_.js.reg['02C0'] = {
  a : ['rqid'],
  f : ['progress','load','error','abort','onrefresh'],
  b : function(n) {with (n) {
    n.refresh=function(){
      var f = $_._getFormattedFields(n.eval_field,n.eval_field_command);
      if(f !== false) eval(f);
      f = $_._getFormattedFields(n.field,n.field_format);
      if(f !== false) n.rqid(f);
      $_.dispatchEvent(n,"onrefresh");
    }}

    n.select=function(){
      n.click();
    };

    n.send = function(d,c){
      var fd = new FormData();

      fd.append(this.rqid, n.files[0]);
      $_.each(d, function(o,i){fd.append(i, o);});

      var xhr = new XMLHttpRequest();
      xhr.addEventListener("progress", n.ups, false);
      xhr.addEventListener("load", function(e){
        n.responseText = e.target.responseText;
        $_.dispatchEvent(n,"load");
        c("load",e.target.responseText);
      }, false);

      xhr.addEventListener("error", function(e){
        n.responseText = e.target.responseText;
        $_.dispatchEvent(n,"error");
        c("error",e.target.responseText);
      }, false);

      xhr.addEventListener("abort", function(e){
        n.responseText = e.target.responseText;
        $_.dispatchEvent(n,"abort");
        c("abort",e.target.responseText);
      }, false);
      xhr.open("POST", "?upload/" + this.rqid);
      xhr.send(fd);
    };

    n.ups=function(evt){
      if (evt.lengthComputable) {
        this.sentData = evt.loaded;
        this.percentComplete = evt.loaded / evt.total;
      }

      $_.dispatchEvent(n,"progress");
    }
  },
  fs : function(n) {
  }
};
