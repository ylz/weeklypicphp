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

  	<h1>Fertig! 😀</h1>
    <?php



      session_id($_REQUEST['PHPSESSID']);
      session_start();

            //debugging
      echo '<h2>_SESSION</h2><pre>';
      print_r($_SESSION);
      echo '</pre><h2>SID</h2><pre>';
      print_r(SID);
      echo '</pre><h2>_POST</h2><pre>';
      print_r($_POST);
      echo '</pre>';

      $filename = $_SESSION['filename']; 
      if (isset($_POST['delete'])) { // delete button was klicked
        if(file_exists($filename)) {
          if(unlink($filename) == false) {
            echo '<p>⚠️ Fehler beim Löschen der Bild-Datei. (1)</p>';
          } else {
            if(file_exists($filename)) {
              echo '<p>⚠️ Fehler beim Löschen der Bild-Datei. (2)</p>';
            } else {
              echo '<p>♻️ Dein Bild wurde vom Server gelöscht.</p>';
            }
          }
        } else {
          echo '<p>⚠️ Die zu löschende Bild-Datei existiert nicht. 🤔</p>';
        }
      }
      
    ?>

    <p>Sollte etwas nicht wie erwartet funktionieren, informiere bitte den Admin dieses Servers.</p>
    <p>Geh an den <a href="index.php">Anfang</a> zurück um ein weiteres Bild zu bearbeiten.</p>
    <p>Hier kommst Du zum <a href="https://upload.weeklypic.de/">Upload</a> eines Bildes auf WeeklyPic.
    <p>Oder vielleicht möchtest du dich auf <a href="https://www.weeklypic.de/">Weeklypic</a> umschauen.</p>

  </body>
</html>
