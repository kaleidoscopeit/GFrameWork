array_merge=function(){var args=Array.prototype.slice.call(arguments),retObj={},k,j=0,i=0,retArr=true;for(i=0;i<args.length;i++){if(!(args[i]instanceof Array)){retArr=false;break;}}
if(retArr){retArr=[];for(i=0;i<args.length;i++){retArr=retArr.concat(args[i]);}
return retArr;}
var ct=0;for(i=0,ct=0;i<args.length;i++){if(args[i]instanceof Array){for(j=0;j<args[i].length;j++){retObj[ct++]=args[i][j];}}else{for(k in args[i]){if(args[i].hasOwnProperty(k)){if(parseInt(k,10)+''===k){retObj[ct++]=args[i][k];}else{retObj[k]=args[i][k];}}}}}
return retObj;}