$_.js.reg['02C0'] = {
  a : ['rqid'],
  f : ['progress','load','error','abort'],
  b : function(n) {with (n) {
    n.refresh=function(){
      fs = $$._getFormattedFields(n.eval_field,n.eval_field_command);
      if(fs !== false) eval(fs);
      fs = $$._getFormattedFields(n.field,n.field_format);
      if(fs !== false) n.rqid(fs);
    }}

    n.select=function(){
      n.click();
    };

    n.send=function(d){
      var fd = new FormData();

      fd.append(this.rqid, n.files[0]);
      $$.each(d, function(o,i){fd.append(i, o);});

      var xhr = new XMLHttpRequest();
      xhr.addEventListener("progress", n.ups, false);
      xhr.addEventListener("load", function(e){
        n.responseText = e.target.responseText;
        n.dispatchEvent(n.load)
      }, false);

      xhr.addEventListener("error", function(e){
        n.responseText = e.target.responseText;
        n.dispatchEvent(n.error)
      }, false);

      xhr.addEventListener("abort", function(e){
        n.responseText = e.target.responseText;
        n.dispatchEvent(n.abort)
      }, false);
      xhr.open("POST", "?upload/" + this.rqid);
      xhr.send(fd);
    };

    n.ups=function(evt){
      if (evt.lengthComputable) {
        this.sentData = evt.loaded;
        this.percentComplete = evt.loaded / evt.total;
      }

      n.dispatchEvent(n.progress);
    }
  },
  fs : function(n) {
  }
};
