<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.:fotoaufbrecherli:.</title>
  </head>
  <body>
  	<?php
  		//####################################################################
      echo "<h1>Hallo! <3</h1>";
      echo "<p>Hier kannst du einfach dein Wochen- oder Monatsbild skalieren auf 2048 Pixel (lange Kante) und deine EXIF Beschreibung als Tag setzen. Das Ergebnis lädst du auf dein Gerät wieder herunter und danach auf <a href='https://upload.weeklypic.de/'>https://upload.weeklypic.de/</a> hoch.</p>";
      echo "<p>Disclaimer: Ja, die Bilder werden zumindest aktuell noch auf diesem Server hier gespeichert und sind mit dem selbem Passwort geschützt abrufbar. Ich lösche die Bilder periodisch von Hand.</p>";
    ?>


    <form action="doit.php" method="post" enctype="multipart/form-data">
      Datei auswählen:
      <input type="file" name="fileToUpload" id="fileToUpload" required>
      Benutzername:
      <input type="text" id="user" name="user" required>
      Bildbeschreibung (optional):
      <input type="text" id="description" name="description">
      <input type="submit" value="Upload Image" name="submit">
    </form>


    <?php
      //####################################################################
  	?>
  </body>
</html>