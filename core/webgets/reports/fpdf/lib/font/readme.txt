IT

Sono stati aggiunti i fonts free con grazie, senza grazie e a spaziatura fissa
in formato incorporabile, in modo da poter cominciare subito a creare documenti
completi.

I fonts sono stati ridotti per supportare solo la mappa di caratteri cp1252,
questo per consentire la generazione di documenti con fonts incorporati non
troppo pesanti. Per aggiungere caratteri seguire la guida inclusa nel progetto
FPDF.

Riporto solo come riferimento una breve lista di operazioni per creare un nuovo
font usabile in FPDF :

- Recuperare il file del font desiderato
- Nel caso si voglia usare anche le varianti (Corsivo, Grassetto, ecc...) recu-
  perare i relativi files TTF
- posizionarsi nella directory "/core/webgets/reports/fpdf/lib/support/tutorial"


  Opzione 1 - utilizzare tutti i caratteri disponibili
  
  Questa opzione porta molto spesso ad ottenere un file molto grosso che verrà
  poi incluso nel PDF.
  
  - caricare il file TTF nella directory corrente
  - rinominare in [nome font minuscolo][variante (b/i/bi)].ttf
  - modificare il file "makefont.php" inserendo il nome del font appena creato
    nel primo parametro della chiamata alla funzione MakeFont
  - raggiungere dal browser il percorso del file "makefont.php" ed eseguirlo
  - verranno generati 2 files : con il nome del font e con estensione rispetti-
    vamente ".php" e ".z"
  - copiarli nella directory "/core/webgets/reports/fpdf/lib/font"
  - ripetere per tutte le varianti a disposizione


  Opzione 2 - utilizzare solo un set minimale di caratteri
  
  Questa opzione rende il file del font molto compatto, ma non sono inclusi
  caratteri esotici. Sarà quindi necessario valutare in base alle necessità cosa
  fare.
  
  - ottenere il software da linea di comando "ttf2pt1"
  - ottenere la mappa dei caratteri desiderata compiandola nello stesso percor-
    so dei fonts appena ottenuti. Una copia può essere ottenuta in 
    "/core/webgets/reports/fpdf/lib/support/makefont" (es. "cp1252.map")
  - aprire un terminale nella directory di lavoro
  - eseguire il comando "ttf2pt1" come segue :
  
    ttf2pt1 -b -L cp1252.map [NomeFont][variante].ttf [nomefont][b/i/bi]
    
  - verranno genreati 2 files con il nome [nomefont][b/i/bi] ed estensione ri-
    spettivamente ".pfb" e ".afm"    
  - caricare i files nella directory sul server
  - modificare il file "makefont.php" inserendo il nome del font con estensione
    ".pfb" appena creato nel primo parametro della chiamata alla funzione
    MakeFont.
  - raggiungere dal browser il percorso del file "makefont.php" ed eseguirlo
  - verranno generati 2 files : con il nome del font e con estensione rispetti-
    vamente ".php" e ".z"
  - copiarli nella directory "/core/webgets/reports/fpdf/lib/font"
  - ripetere per tutte le varianti a disposizione


Una idea potrebbe essere quella di creare una utilità da interfaccia Web per
fare tutte queste operazioni in automatico.