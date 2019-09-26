# weeklypicphp

Ein (einfaches) PHP-Skript, um ein JPG-Bild auf die richtigen Proportionen zu skalieren, EXIF-Daten für unsere Weeklypic.de-Community zu setzen und das Bild hochzuladen.

# Features

* Bild auf 2000px skalieren (längste Seite)
* EXIF-Daten einstellen für
  * *Titel*
  * *Beschreibung* (= weekliy-pic-username / title)
  * *Urheber*
  * *Lizenz*
* Tabelle der vorhandenen/erforderlichen/neuen EXIF-Daten anzeigen
* Speicherung der Parameter *Weekly-Pic-Benutzername*, *Urheber* und *Lizenz* in einem Cookie, für deinen Komfort, falls gewünscht.
* Wahlmöglichkeit zwischen *wöchentlich* und *monatlich*
* Voreinstellung der Wochen- und Monatsnummern für den Dateinamen (änderbar)
* Automatische Generierung eines gültigen Weekly-Pic Dateinamens
* Direkter Upload des Bildes an upload.weeklypic.de

# Roadmap

* Aufruf einer Karte mit den GPS Koordinaten des Bildes (sofern vorhanden)
* Prüfen der des Aufnahmedatums gegen die Woche, bzw. den Monat

# Hinweis

Es sind nicht nur EXIF-Tags in Bildern gespeichert, sondern auch IPTC, GPS und andere Tags.
Wann immer wir EXIF sagen/schreiben, meinen wir im Allgemeinen auch alle anderen Tags.

So sind beispielsweise, wenn der Künstler geändert werden soll, die folgenden Tags betroffen:
* EXIF:Artist
* IPTC:by-line
* XMP:creator