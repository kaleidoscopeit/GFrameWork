IT

DIRECTORY DEI PLUGINS
=====================

In questa directory vengono inseriti i plugin per FPDF. Sul sito ufficiale ven-
gono chiamati "scripts". Questi plugins sono inseriti perchè necessari al fun-
zionamento dei webgets che utilizzano la libreria FPDF per generare reports.

Durante lo sviluppo di nuovi webget potrebbe essere necessario inserire ulterio-
ri plugins, è quindi necessario seguire delle specifiche regole. Non ho trovato,
per ora, soluzioni migliori al fine di permettere un caricamento 'modulare' dei
plugins, se hai idee per migliorare fammelo sapere.



REGOLE PER AGGIUNGERE I PLUGIN
==============================

Nel webget "document.cl.php" è contentuto il codice che si occupa di caricare
la libreria principare (FPDF) e i vari plugins. Il codice si trova poco dopo il
metodo '__define'.

Il realtà è molto semplice : viene seguito uno schema alfabetico dove il nome
dei files dei plugins viene preceduto da 3 lettere maiuscole e da un 
'underscore' in modo che vengano caricati in ordine alfabetico e i nomi delle
classi vengono rinominati anch'essi con questo schema, ma senza includere il
nome originale.

Esempio :


- Questo sarebbe il metodo classico per estendere in cascata FPDF


    class FPDF {
    
      ... codice di FPDF ...
    
    }
    
    class plugin_1 extend FPDF {
    
      ... codice ...
    
    }

    class plugin_2 extend plugin_1 {
    
      ... codice ...
    
    }
    
    $fpdf = new plugin_2();
    
    
  Purtroppo questo metodo non è molto formale ed essendo una inclusione suddivi-
  sa tra molti files s rischi di non avere la certezza della classe precedente.
  

- Questo è come è stato implementato il caricamento in cascata :


    class FPDF {
    
      ... codice di FPDF ...
    
    }
    
    class AAA extend FPDF {
    
      ... codice ...
    
    }

    class AAB extend AAA {
    
      ... codice ...
    
    }
    
    $fpdf = new AAB();
    

  Questo metodo, combinato con la rinomina del file contenente il plugin con-
  sente di avere la certezza della sequenza di caricamento.
  

Se hai bisogno di aggiungere un nuovo plugin ti basta rinominare il file che lo
contiente anteponendo la tripletta di lettere successiva in ordine alfabetico,
quindi rinomini la classe con la stessa tripletta, ricordandoti che la nuova
tripletta estende la sua precedente.


Spero di essere stato chiaro!!!!