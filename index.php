<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.:fotoaufbrecherli:.</title>
    <style type="text/css">
      body {margin:30px auto; max-width:650px; line-height:1.6; font-size:18px;
        color:#444; padding:0 10px;
        background:#eee;
        -webkit-font-smoothing:antialiased;
        font-family:"Helvetica Neue",Helvetica,Arial,Verdana,sans-serif }
      h1,h2,h3 {line-height:1.2}
      input {font-size:18px}
    </style>
  </head>
  <body>
  	<?php
  		//####################################################################
      echo "<h1>Hallo! ❤️</h1>";
      echo "<p>Hier kannst du einfach dein Wochen- oder Monatsbild skalieren auf 2048 Pixel (lange Kante) und deine EXIF Beschreibung als Tag setzen. Das Ergebnis lädst du auf dein Gerät wieder herunter und danach auf <a href='https://upload.weeklypic.de/'>https://upload.weeklypic.de/</a> hoch.</p>";
      echo "<p>Disclaimer: Ja, die Bilder werden zumindest aktuell noch auf diesem Server hier gespeichert und sind mit dem selbem Passwort geschützt abrufbar. Ich lösche die Bilder periodisch von Hand. Für die Funktionalität der Script und auch die Verfügbarkeit übernehme ich weder Garantie noch Haftung.</p>";
    ?>

    <p>
      <form action="doit.php" method="post" enctype="multipart/form-data">
        Datei auswählen:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <br/>
        Benutzername:
        <input type="text" id="user" name="user" required>
        <br/>
        Bildbeschreibung (optional):
        <input type="text" id="description" name="description">
        <br/>
        <input type="submit" value="Upload Image" name="submit">
      </form>
  </p>

  </body>
</html>
