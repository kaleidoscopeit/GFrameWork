G-FRAMEWORK
===========

COSA E' :
=========

Un completo ambiente di sviluppo, con precise regole, sintassi e paradigma di
progettazione. E' open source, modulare ed estensibile, chiunque puo' lavorarci
sopra a qualsiasi livello.


A COSA SERVE :
==============

A sviluppare rapidamente applicazioni distribuite utilizzando tecnologie e pro
tocolli esistenti e molto diffusi, con il grande vantaggio di non dover distri
buire e mantenere aggiornati software client personalizzati e mono piattaorma.


QUALI VANTAGGI HO AD USARLO :
=============================

L'idea che guida il progetto e' quella di fornire un ambiente di sviluppo che
consenta di creare applicazioni notevolmente complesse focalizzandosi solo sul
risultato, senza perdersi nei neandri dello sviluppo Web. E' possibile passare
dal disegno delle varie 'viste' della propria applicazione, all'implementazione
reale in pochissimo tempo. G-Framework contiene gia' tutto cio' che serve per
creare complesse 'viste', far interagire i client con il server, autenticare
gli utenti, internazionalizzare e personalizzare l'aspetto delle proprie appli
cazioni.


OK, MI HAI INCURIOSITO, DIMMI ORA COSA FARE :
=============================================

Innanzitutto e' necessario capire cosa vuoi fare. Ci sono tre tipi di sviluppa
tori che possono operare con G-Framework :

- Solution Developer : e' colui che utilizza il GFwk per creare applicazioni,
                       non aggiunge nuove funzionalita', ma puo' contribuire
                       segnalando bugs o proponendo idee per nuove funzionalità

- Feauture Developer : e' colui che contribuisce creando principalmente nuovi
                       webgets, librerie JavaScript hot-plug e temi. 

- Core Developer     : si occupa del backend, di quella parte che fa funzionare
                       il framework. Sistema bachi, migliorara il codice, ag
                       giunge nuove caratteristiche.

Se vuoi provare a creare la tua applicazione e' necessario seguire la parte del
manuale che ti spiega come diventare un "Solution Developer". Se, dopo aver
compreso bene il paradigma di sviluppo, vuoi contribuire in maniera piu' decisa
al progetto, puoi fare un passo in piu' e provare a capire meglio cosa c'e' 
sotto il cofano, seguendo le due guide per diventare un "Feauture Developer" o
un "Core Developer"


COSA C'E' NEL PACCHETTO CHE SCARICHERO' :
========================================

Innanzitutto è necessario capire la struttura di base di una installazione del
framework. In effetti è molto semplice, la directory 'core' contiene il motore
di generazione dei contenuti mentre tutte le altre directory sono progetti.
G-Framework è multiprogetto, questo significa che puoi creare diverse
applicazioni utilizzanto una sola installazione. Il progetto che viene aperto in
assenza di un percorso specifico è 'default', quindi quando ti connetti via Web
al tuo server digitando il percorso 'http://mio_server/' verrai rediretto auto-
maticamente a 'http://mio_server/default'. se intendi connetterti ad un'altra
appllicazione ti basterà digitare 'http://mio_server/nome_applicazione/'  

A questo punto ti spiego cosa troverai nel pacchetto :

- core          : motore del framwork, se usi GitHub sarà sempre aggiornato

- default       : il progetto di presentazione del framework

- references    : contiente diverso codice che non forma un particolare proget
                  to, ma utile come riferimento per lo sviluppo.

- documentation : questa directory non contiene un progetto, ma tutta la docu
                  mentazione che riuscirò a scrivere



Di seguito c'è la vecchia presentazione in inglese del progetto che appena ho
tempo aggiornerò :)

Mi raccomando...

   scarica prova e se hai qualche domanda non esitare a contattarmi !!!!

===============================================================================





G-FRAMEWORK
===========
                                
G-Framework ToolKit is a system for creating Web applications. It is not a 
semple framework, but a new approach to Web programming.

With it Web applications are created 'vertically' : rather than merging multiple
languages (e.g. PHP, JavaScript, and HTML) in your pages, each view is designed
in XML, and server and client code is stored in a specific place and is linked
to the view at runtime.

Everything is strongly oriented to let developers focus their efforts only to
specific aspects of the project at time : XML for the GUI, RPCs for the
data handling.

The use of XML as a 'modified' version of the simple HTML let the developers
to benefit from the following enhancements :

- Clear desining rules, avoiding CSS mess up
- Subviews rather iframes where the injected code is merged in the master view
- Possibility to create new specialized tag composed by primitives webgets.
- Internationalization dramatically simplified
- and more...

The use of RPCs let developers to completely forget the underlying Ajax 
data exchange and focus only on the their own code.

With G-Bus Web applications come to life easly with an unexpected level of
interactivity, like Google Drive where users can modify documents in realtime.

G-Framework structure is fully modular. This approach let 3rd parties to develop
new components and made them available to others developers simply as write
a TAG in an XML View.
