startupFunct.sysapi = function(){
	KEVN = {};
}

function ackData(){
	data = OBJS.channel.contentWindow.document.body.innerHTML;
	if(data.indexOf('<pre>')=='0'){data=OBJS.channel.contentWindow.document.body.firstChild.innerHTML;}
	data = eval(data);
	eval(callBackFunct+'(data)');
}


function keySens(e){
	if (!e) var e = window.event;
	if (e.keyCode) code = e.keyCode;
	//else if (e.which) code = e.which;
	if(KEVN[code])eval(KEVN[code]);
}

function callURL(url,cbf){
	//channel = document.getElementById('channel');
	callBackFunct = cbf;
	OBJS.channel.src=url;
}

function sendData(url,values){
	// crea il form per inviare i dati	
	form =  document.createElement('form'); 
	this.form.method= 'POST';
	this.form.action= url;


	// per ogni dato post crea una casella di testo nel form
  	for (var i in  values){
  		this.curr = values[i].replace(/\"/g,"%22");
  		this.curr = this.curr.replace(/'/g,"%27");
  		this.curr = this.curr.replace(/</g,"%3C");
  		this.curr = this.curr.replace(/>/g,"%3E");

  		tex = document.createElement('input');
  		tex.type = 'text';
  		tex.name = i;
  		tex.id = i;
  		tex.value = this.curr;
  		this.form.appendChild(tex);	
 	}

	OBJS.channel.contentWindow.document.body.appendChild(this.form);
	this.form.submit();
	
	//OBJS.channel.contentWindow.document.body.innerHTML = '';
	//alert(OBJS.channel.contentWindow.document.body.firstChild);
	//form.appendChild(this.text);
	//document.appendChild(this.text);
	//OBJS.channel.contentWindow.document.forms[0].submit();
}

// funzioni che riducono la quantità di codice scritto 
function CrEl(name){
	return document.createElement(name);
}


