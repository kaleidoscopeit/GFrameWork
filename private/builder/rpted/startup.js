startupFunct = {};

function startup(){
// contenitori
designAreas = new Array;

// elementi html utilizzati dal sistema
OBJS={};
OBJS.grid=document.getElementById('grid');
OBJS.floater=document.getElementById('floater');
OBJS.hilighter=document.getElementById('hilighter');
OBJS.dummy=document.getElementById('dummy');
OBJS.channel = document.getElementById('channel');
OBJS.hpoint = document.getElementById('hpoint');
OBJS.vpoint = document.getElementById('vpoint');
OBJS.designspace = document.getElementById('designspace');

// variabili di sistema
VARS= new Array();
VARS.wbgsel=0;
VARS.dragON=0;
VARS.reszON=0;
VARS.wgetsEnum = 0;
VARS.areaEnum = 0;
//x=0;y=0;
VARS.out='';

// dati della configurazione di sistema
CONF= new Array()
CONF.gridSnap=1;
CONF.gridStep = 8;
CONF.siteroot='/develop';
CONF.siteroot='';

STUF={1:''};

	for(var sub in startupFunct){
		startupFunct[sub]();
	}
}