function crel(x){return(document.createElement(x));};

function start(){
	tbx.build();
	tbx.switch('COMMON');
}

tbx=new function(){
	/* crea il contenuto delle schede con i pulsanti
		---------------------------------------------------------------- */
	this.build=function(){
	with(this){
		/* cattura gli elementi HTML per l'interazione */
		this.area=document.getElementById('tobox');
		this.select=document.getElementById('selector');
		/* riempie la combo con i nomi delle collezioni di librerie */
		for(var sub in wtr){
			this.opt=crel('option');
			opt.value=sub;
			opt.text=sub;
			select.add(opt,null);
		};
		
		/* Imposta COMMON come valore predefinito */
		this.select.value="COMMON";
		
		this.pages={};this.sub=0;this.ssub=0;
		for(var sub in wtr){
			this.lcount=0;
			
			pages[sub] = crel('div');
			pages[sub].style.cssText = "border-top:1px solid lightgrey;position:absolute;left:0px;top:0px;visibility:hidden;overflow:auto;height:100%;width:100%;";

			for(var ssub in wtr[sub]){
				lcount>4 ? lcount=0 : null;
				
				this.tr = crel('tr');

				this.img = crel('img');
				img.style.cssText='height: 30px;width: 30px;';
				img.src='?101&f=i&c='+sub+'&l='+ssub;
				this.td=crel('td');
				td.width='40';
				td.align='center';
				td.appendChild(img);
				tr.appendChild(td);

				td=crel('td');
				td.align="left";
				td.style.cssText='font: 12px arial,sans;';
				td.innerHTML=wtr[sub][ssub][0];
				td.noWrap=true;
				tr.appendChild(td);

				table = crel('table');
				table.cellPadding=0;
				table.cellSpacing=0;
				table.width='100%';				
				table.style.borderBottom='1px solid lightgrey';
				table.appendChild(tr);
				table.className="widbutt";
				table.TAGTYPE=wtr[sub][ssub][1];
				table.TARGET=wtr[sub][ssub][2];
				table.onmousedown = function onMouseDown(event){
					window.opener.wman.cfnc('wbt.placeStart("'+this.TAGTYPE+'")','forms');
					return false;
				};										
				pages[sub].appendChild(table);
				lcount++;
			}
			area.appendChild(pages[sub]);			
		}
	}};
	
	/* gestore delle schede visualizzate
		----------------------------------------------- */
	this.switch =  function(page){
		for(var sub in this.pages){
			sub != page ? this.pages[sub].style.visibility='hidden' : this.pages[sub].style.visibility='visible'; 
		}
	};
};