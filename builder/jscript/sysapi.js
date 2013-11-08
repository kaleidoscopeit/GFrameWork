/*
	dcr		:	funzione scorciatoia per creare un elemento
	dge		:	funzione scorgiatoia per catturare un elemento HTML
*/

/* funzioni che riducono la quantità di codice scritto */
function dcr(name){return document.createElement(name);}
function dge(name){return document.getElementById(name);}
function apc(name){return document.appendChild(name);}

function isArray(obj) {
	if (obj.constructor.toString().indexOf("Array") == -1) return false;
	else return true;
}