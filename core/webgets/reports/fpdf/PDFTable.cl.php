/// CLASSE PER IL MOMENTO PARCHEGGIATA


<?php
class PDFTable
{
	function PDFTable($attrs) 
	{
		// importa le variabili globali di MWE
		global $DC,$DG;

		// recupera ogni proprietà passata dall'XML
		foreach($attrs as $key=>$value){
			eval('$this->'.$key.' = "$value";');
		}

		// dichiara i valori di default...
		$default['COLS'][] = 1; // numero di ripetizioni orrizzontali dell'area di disegno

		// ... e li imposta
		foreach($default as $key=>$value){
			foreach ($value as $local){ eval('if ($local  and !$this->'.$key.') { $this->'.$key.' = "'.$local.'"; }'); 	}
		}
		
		// dichiara la lista degli attributi obbligatori...
		$reqiured[] = "GEOMETRY";
		$reqiured[] = "ROWS";
		$reqiured[] = "DSN";
		
		// ... e li verifica
		foreach($reqiured as $value){
			eval('if(!isset($this->'.$value.')){die ("The property ".$value." in PDFTable ID -> ".$this->ID." is required.");}');
		}

		$this->GEOMETRY = explode(',',$this->GEOMETRY);

		$this->LEFT = $this->GEOMETRY[0];
		$this->TOP = $this->GEOMETRY[1];
		$this->WIDTH = $this->GEOMETRY[2];
		$this->HEIGHT = $this->GEOMETRY[3];
	}

	function flush() // attua lo scopo della classe
	{
		// importa le variabili globali di MWE
		global $DC,$DG;

		// esegue il codice contenuto nella proprieta' (evento) :  s:onBeforeFlush
		eval($this->ONBEFOREFLUSH);
			
		// ottiene la larghezza della cella in base al rapporto tra larghezza tabella e numero di ripetizioni orrizzontali
		$this->CELL_WIDTH = $this->WIDTH/$this->COLS;
		// ottiene l'altezza della riga in base al rapporto fra altezza della tabella e numero di righe
		$this->LINE_HEIGHT = $this->HEIGHT/$this->ROWS;

		// se il numero di pagine da stampare non è impostato la quantita' richiesta di record sara' massimo
		// oppure il prodotta fra le righe, le colonne e le pagine
		!isset($this->PAGES) ? $this->RQTY = 'max' : $this->RQTY = $this->ROWS*$this->COLS*$this->PAGES;

		// ottiene dal DNS i record
		$this->Data = $DC[$this->DSN]->GET(0,$this->RQTY);

		// per ogni PDFTAREA individuata crea un array con tanti riferimenti quante sono il numero di ripetizioni 
		// indicate nella prorpieta' REPEAT della stessa. Nel contempo elimina il riferimento trovato dal
		// gruppo di classi figlie. Le rimanenti verrano ignorate.
		foreach($this->CHILDS as $key => $CChild){
			if(get_class($CChild)=='pdftarea'){
			  for($i=0;$i<$CChild->REPEAT;$i++) {$this->Areas[] =& $this->CHILDS[$key];}
			}			 
		}

		// memorizza il numero di record ottenuti
		$MaxRecords = count($this->Data['rs']);

		// calcola, in base al numero di record ottenuti, il numero pagine massimo che verranno visualizzate
		$MaxPages = $MaxRecords/($this->ROWS*$this->COLS);
		is_float($MaxPages) ? $MaxPages = intval($MaxPages)+1 : null ;

		// ottiene il numero di celle per pagina
		$PPageCell = $this->ROWS*$this->COLS;

		// azzera il puntatore del record
		$CurrRecord = 0;

		// avvia il ciclo di pagina che disegnia le porzioni di risultato per ogni pagina
		while($CurrPage < $MaxPages){

			if(isset($CurrPage)){$DG[pdf]->AddPage('p');}
			// azzera i puntatori dell'area 
			$CurrCell = 0; $CurrArea=0;$CurrX=$this->LEFT;$CurrY=$this->TOP;
			
			// avvia il ciclo riga/colonna che disegna le celle
			while($CurrCell < $PPageCell){
				for ($icol=0;$icol<$this->COLS;$icol++){
	
					// genera localmente un array contenente il record corrente in modo che gli elementi
					// contenuti nell'area possano riferirsi a questo per visualizzare le informazioni
					$this->CurrRecord = $this->Data['rs'][$CurrRecord];

					// carica il contenuto dell'area corrente				
					$this->Areas[$CurrArea]->flush($CurrX,$CurrY);
	
					// incremente il puntatore dell'area corrente				
					$this->Areas[$CurrArea+1] ? $CurrArea++ : $CurrArea = 0;
					
					//incremente il puntatore dei record e il puntatore locale della cella
					$CurrRecord++;$CurrCell++;
	
					$CurrX+=$this->CELL_WIDTH;
	
				}
				$CurrX = $this->LEFT;
				$CurrY+=$this->LINE_HEIGHT;
			}
			$CurrPage ++;
			
		}

		// esegue il codice contenuto nella proprieta' (evento) :  s:onAfterFlush
		eval($this->ONAFTERFLUSH);
	}	
}
?>