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

  	<h1>Hallo! ❤️</h1>
    <p>Hier kannst du einfach dein Wochen- oder Monatsbild skalieren auf
       2048 Pixel (lange Kante) und deine EXIF Beschreibung als Tag setzen.
       Das Ergebnis lädst du auf dein Gerät wieder herunter und danach auf
       <a href='https://upload.weeklypic.de/'>https://upload.weeklypic.de/</a>
       hoch.</p>
    <p>Disclaimer: Ja, die Bilder werden zumindest aktuell noch auf diesem
       Server hier gespeichert und sind mit dem selbem Passwort geschützt
       abrufbar. Ich lösche die Bilder periodisch von Hand. Für die
       Funktionalität der Script und auch die Verfügbarkeit übernehme
       ich weder Garantie noch Haftung.</p>

    <?PHP
      // configuration constants
      include 'src/config.php';

      // read cookie storing common values (Weekly-Pic-Name, Creator, license)
      if(isset($_COOKIE[$cookie_name])) {
        $cookie_value  = explode( $cookie_split , $_COOKIE[$cookie_name] , 3 );
        $val_user      = $cookie_value[0];
        $val_creator   = $cookie_value[1];
        $val_license   = $cookie_value[2];
        $val_usecookie = ' checked ';
      } else {
        $val_user      = '';
        $val_creator   = '';
        $val_license   = '';
        $val_usecookie = ' ';
      }

      // set default week and month numbers
      $default_month = date('n');
      $default_week  = date('W');

    ?>

    <p>
      <form action="doit.php" method="post" enctype="multipart/form-data">
        <p>
          Datei auswählen:<br/>
          <input type="file" name="fileToUpload" id="fileToUpload" required>
        </p>
        <p>
          WeeklyPic-Benutzername △:<br/>
          <input type="text" id="user" name="user" value="<?= $val_user ?>" required><br/>
          Bildbeschreibung (wird von WeeklyPic genutzt, optional):<br/>
          <input type="text" id="description" name="description">
        </p>
        <p>
          Bild-Zeitraum (für den Dateinamen):<br>
          <input type="radio" id="timeframe" name="timeframe" value="Woche" checked required>
          Woche <input type="number" name="week_number" min="1" max="52" step="1=" value="<?= $default_week ?>"><br>
          <input type="radio" id="timeframe" name="timeframe" value="Monat" required>
          Monat <input type="number" name="month_number" min="1" max="12" step="1=" value="<?= $default_month ?>"><br/>
        </p>
        <p>
          Urheber △ (optional):<br/>
          <input type="text" id="creator" name="creator" value="<?= $val_creator ?>"><br/>
          Lizenz △ (optional):<br/>
          <input type="text" id="license" name="license" value="<?= $val_license ?>"><br/>
          <input type="checkbox" id="nogeo" name="nogeo" value="nogeo"> Lösche Geo-Daten aus dem Bild.<br>
        </p>
        <p>
          <input type="checkbox" id="usecookie" name="usecookie" value="usecookie" <?= $val_usecookie ?> > Nutze ein Cookie für deine △-Daten.
        </p>
        <p>
          <input type="submit" value="Bild hochladen und bearbeiten" name="submit">
        </p>
      </form>
    </p>

  </body>
</html>
