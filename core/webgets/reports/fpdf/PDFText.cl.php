<?php
class PDFText{
function PDFText($attrs){
		// importa le variabili globali di MWE
		global $DC,$DG;

		// recupera ogni proprietà passata dall'XML
		foreach($attrs as $key=>$value){$this->$key=$value;}

		// ricostruisce le caratteristiche spaziali
		$this->GEOMETRY = explode(',',$this->GEOMETRY);
		$this->LEFT = $this->GEOMETRY[0];
		$this->TOP = $this->GEOMETRY[1];
		$this->WIDTH = $this->GEOMETRY[2];
		$this->HEIGHT = $this->GEOMETRY[3];

		// dichiara i valori di default...
		$default['ROTATION'][]=0;
		// questi valori vengono recuperati dalla classe contenitore per ereditarietà
		$default['FONT'][]=$DC[$this->PARENT]->FONT;
		$default['FG_COLOR'][]=$DC[$this->PARENT]->FG_COLOR;
		$default['FG_COLOR'][]="0,0,0";
		
		// ... e li imposta
		foreach($default as $key=>$value){
			foreach ($value as $local){ if($local!=null and !$this->$key){$this->$key=$local;}}
		}

		// ricostruisce il font


		// lista dei campi obbligatori
// 		$reqiured[] = 'ID';
 		$reqiured[] = 'LEFT';
 		$reqiured[] = 'TOP';
		$reqiured[] = 'FONT';
		
		// ... e li verifica
		foreach($reqiured as $value){
			if(!isset($this->$value)){die ("The property ".$value." in ".get_class($this)." ID -> ".$this->ID." is required.");};
		}

 		eval($this->ONDECLARECLASS);
	}
	
	function flush(){
		// importa le variabili globali di MWE
		global $DC,$DG;

		// esegue il codice contenuto nella proprieta' (evento) :  s:onBeforeFlush
		eval($this->ONBEFOREFLUSH);

		// ottiene le informazioni sul colore di primo piano.
		// Se non viene impostato localmente, tenta di ereditarlo dal contenitore
		if(!isset($this->FG_COLOR) AND isset($DC[$this->PARENT]->FG_COLOR)){
			$this->FG_COLOR =  $DC[$this->PARENT]->FG_COLOR;
			$inherited_fg_color = true;
		}

		// ricostruisce le informazioni sul colore di sfondo
		if(isset($this->BG_COLOR)){
			$bg_color = explode(',',$this->BG_COLOR);
			$filling = '1';
		}

		// stile del bordo del riquadro della etichetta
		$DG[pdf]->SetDrawColor($this->BDR,$this->BDG,$this->BDB);
		$DG[pdf]->SetLineWidth($this->BDW);
		
		//$DG[pdf]->SetFillColor($bg_color[0],$bg_color[1],$bg_color[2]);

		eval('$DG[pdf]->SetTextColor('.$this->FG_COLOR.');');
		eval('$DG[pdf]->SetFont('.$this->FONT.');');

		// memorizza localmente il testo dell'etichetta		
		$caption=$this->CAPTION;
		// ottiene il valore dal database se è impostata una sorgente dati
		// se la sorgente dati non esite ritorna errore
		if($this->FIELD && $DC[$this->PARENT]->CurrRecord[$this->FIELD]){
				$this->CAPTION=$DC[$this->PARENT]->CurrRecord[$this->FIELD];
		}

		// disegna il testo
		$DG[pdf]->writeRotie($this->LEFT+$DG[pdf]->_MWE_OffsetX,
									$this->TOP+$DG[pdf]->_MWE_OffsetY,
									utf8_decode($this->CAPTION),
									$this->ROTATION,
									0);
									
		// resetta i valori riportandoli allo stato precedente o ereditato
		$DG[pdf]->SetDrawColor(0,0,0);$DG[pdf]->SetLineWidth(0);

		if($DC[$this->PARENT]->FG_COLOR)eval('$DG[pdf]->SetTextColor('.$DC[$this->PARENT]->FG_COLOR.');');
		if($DC[$this->PARENT]->FONT)eval('$DG[pdf]->SetFont('.$DC[$this->PARENT]->FONT.');');

		// riporta il valore di CAPTION allo stato originale
		$this->CAPTION=$caption;
		
		// esegue il codice contenuto nella proprieta' (evento) :  s:onAfterFlush
		eval($this->ONAFTERFLUSH);
	}
}
?>