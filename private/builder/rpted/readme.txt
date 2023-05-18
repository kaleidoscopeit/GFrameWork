Struttura delle definizioni delle propritetà :

Etichetta
tipo di selettore
tipo di dati
limiti
nome proprietà


Come vengono gestiti gli oggetti js ed i  relativi webget :

La struttura dello spazio di lavoro corrente è composta da un albero.
per ogni ramo è presente la lista delle proprietà, degli eventi, dei webget, e
tutti gli altri oggetti dipendenti numerati in modo progressivo.
Tutto questo in maniera ricorsiva.

root 
  |
  |---- PRP
  |       |---- ID -> Valore
  |       |---- GEOMETRY[0] -> Valore
  |       |---- GEOMETRY[1] -> Valore
  |       |---- GEOMETRY[2] -> Valore
  |       |---- GEOMETRY[3] -> Valore
  |
  |---- EVE
  |       |---- ONCLICK -> Codice
  |
  |---- OBJ
  |       |---- OBJ1 -> ID Webget (elemento HTML)
  |       |---- OBJ2 -> ID Webget (elemento HTML)
  |
  |----- 0
  |       |---- PRP
  |       |       |---- ID -> Valore
  |       |       |---- GEOMETRY[0] -> Valore
  |       |       |---- GEOMETRY[1] -> Valore
  |       |       |---- GEOMETRY[2] -> Valore
  |       |       |---- GEOMETRY[3] -> Valore
  |       |
  |       |---- EVE
  |       |       |---- ONCLICK -> Codice
  |       |
  |       |---- OBJ
  |       |       |---- OBJ1 -> ID Webget (elemento HTML)
  |       |       |---- OBJ2 -> ID Webget (elemento HTML)
  
  
PRP : si trovano delle variabili che contengono il valore nominale della proprietà
EVE : eventi associabili al webget e relativo codice
OBJ : collegamenti all'oggetto/i html -->
				il primo è l'involucro principale, il secondo è lo spazio dove inserire gli altri nidificati,
				altri eventuali servono per la gestione dinamica degli eventuali aspetti del
				codice html.
  
  Degli script di default si occupano di aggiungere, cancellare, spostare i rami e le relative dipendenze. 
  
  Script di aggiunta :
  
  Specificare la posizione nell'albero.
  Leggere il numero più alto degli oggetti inseriti
  Appendere l'oggetto al ramo incrementendo l'enumeratore.
  
  