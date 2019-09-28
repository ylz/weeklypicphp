<?PHP session_start(); ?>
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
      // configuration constants
      include 'src/config.php';
      // more functions
      include 'src/functions.php';

      // get values from Session
      $pathfilename = $_SESSION['pathfilename'];
      $filebasename = $_SESSION['filebasename'];
      $user         = $_SESSION['user'];
      log_usage('3', $user);

      // upload
      if (isset($_POST['upload'])) { // upload button was klicked
        $command = $curl_command . ' -u ' . $upload_login . ' -X PUT --data-binary @"' .
                   $pathfilename . '" "' . $upload_server . $filebasename . '.jpg" 2>&1';
        exec($command, $data, $result);
        if($debugging) { // debug
          echo "<p>command: "; print_r($command);
          echo "<br>data: <br><pre>"; print_r($data); echo "</pre>";
          echo "<br>result: "; print_r($result);
          echo "</p>";
        }
        if($result !== 0) {
          log_command_result($command, $result, $data);
          echo '<p>⚠️ Problem beim Upload aufgetreten.</p>';
        } else {
          echo '<p>✅ Das Bild wurde hochgeladen! 😃</p>';
        }
      }

      // delete - always
      delete_file($pathfilename);

    ?>

    <p>Sollte etwas nicht wie erwartet funktionieren, informiere bitte den Admin dieses Servers.</p>
    <p>Gehe an den <a href="index.php">Anfang</a> zurück um ein weiteres Bild zu bearbeiten.</p>
    <p>Hier kommst Du zum <a href="https://upload.weeklypic.de/">Upload</a> eines Bildes auf WeeklyPic.
    <p>Oder vielleicht möchtest du dich auf <a href="https://www.weeklypic.de/">Weeklypic</a> umschauen.</p>

  </body>
</html>
