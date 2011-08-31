Admin Tools 2
===============================

Die Admin Tools 2 sind eine große Sammlung von nützlichen Werkzeugen für Administratoren. Über die Funktionsschnittstelle kann es einfach um neue Funktionen erweitert werden.

Die Admin Tools 2 sind eine Neuentwicklung mit den Admin Tools von MailMan als Ideenbasis. Es besteht im wesentlichen aus folgenden Teilen:

    Funktionen - Auf dieser Seite können die dynamisch über Erweiterungen installierten Funktion konfiguriert und ausgeführt werden.
    Cronjob - Auf dieser Seite können Cronjobs erstellt werden, die Admin Tools Funktionen, die cronjobfähig sind, ausführen
    Spiderverwaltung - Hier können eigene Spider/Suchmaschinenroboter hinzugefügt und mit der WCF-eigenen Spidertabelle synchronisiert werden.
    Admin Links - Wie das Plugin "Erweitertes Headermenü" nur für das ACP Menü. Das gesamte ACP Menü bis auf Optionskategorien kann umstrukturiert und erweitert werden. Außerdem kann man iFrame Seiten erstellen. In diesem Fall wird der für den Menüpunkt eingegebene Link in einer iFrame geöffnet wird.

Dieses Plugin enthält außerdem 4 Unterpakete, die einen Grundstock an Funktionen mitbringen.

    WCF LGPL Funktionen
        Cacheleerung
        Benutzergruppen kopieren
        Datenbankoptimierung
        Spracheinestellungen überschreiben
        Inaktive Benutzer löschen ( + schreibfaule Benutzer löschen per Zusatzplugin)
    WCF kommerzielle Funktionen
        PN Autolöschung
    WBB Funktionen
        Forenrechte kopieren
    WBB 3 Funktionen
        Präfixe kopieren
        Abonnements löschen
        Abonnements zuweisen

Bitte beachten:

    Nach der Installation/Update muss wie bei den alten Admintools die Gruppenberechtigung gesetzt werden, die die Verwendung der Admin Tools erlaubt. (administrative Rechte -> Systemfunktionen -> Kann Admin Tools benutzen
    Sollten die AdminTools deinstalliert werden, sollten vorher alle Admin Tools Cronjobs deinstalliert werden
    Optionskategorien können nicht bearbeitet werden.



Wie kann man das Projekt unterstützen:
Momentan suche ich vor allem Übersetzer, die das Plugin und Unterpakete übersetzten können. Desweiteren wäre es schön, wenn Entwickler eigene Funktionen programmieren würde ;)

Danksagungen

    Sani für die englische Übersetzung
    MailMan für die Admin Tools 1, die Ideen und die Vorlagen

-------------

The currently available Version is stable. You can install this version in an productive system. But the author assumes no liability for any damages. 
A potential liability under the German Product Liability Act ("Produkthaftungsgesetz") remains unaffected. 

Contribution
------------

Developers are always welcome to fork TacHawkes/net.hawkes.admintools  and provide features or bug fixes using pull requests. If you make changes or add classes it is mandatory to follow the requirements below:

* Testing is key, you MUST try out your changes before submitting pull requests
* You MUST save your files with Unix-style line endings (\n)
* You MUST NOT include the closing tag of a PHP block at the end of file, provide an empty newline instead
* You MUST use tabs for indentation
    * Tab size of 8 is required
    * Empty lines MUST be indented equal to previous line
* All comments within source code MUST be written in English language

Follow the above conventions if you want your pull requests accepted.

License
-------

ToDo