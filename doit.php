<?PHP session_start(); ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.:fotoaufbrecherli:.</title>
    <style type="text/css">
      body {margin:5% auto; line-height:1.6; font-size:18px;
        color:#444; padding:0 10px;
        background:#eee;
        -webkit-font-smoothing:antialiased;
        font-family:"Helvetica Neue",Helvetica,Arial,Verdana,sans-serif }
      h1,h2,h3 {line-height:1.2}
      input {font-size:18px}
      table{ border-collapse: collapse; }
      th, td { padding: 3px;  text-align: left; }
      table, th, td { border: 1px solid black; }
    </style>
  </head>
  <body>

    <?php

      // some constans

      $debugging = FALSE;

      $upload_folder = '_files/'; //Das Upload-Verzeichnis
      $convert_command = '/usr/local/bin/convert';  // imagemagick covert
      $exiftool_command = '/usr/local/bin/exiftool'; // EXIFtool

      include 'src/functions.php';
      include 'src/exif_parsing.php';

      // IDEA: validate picture date against parameters week/month and w/m-number

      //####################################################################
      // _POST Var Handling

      // IDEA: Restrict $user to  characters? [a-z,A-Z,_,0-9]

      $user         = sanitize_input("user", TRUE);
      $creator      = sanitize_input("creator", FALSE);
      $license      = sanitize_input("license", FALSE);
      $description  = sanitize_input("description", FALSE);
      $description_isset = false;
      if(array_key_exists("nogeo", $_POST)){
        $no_geo = true;
      } else {
        $no_geo = false;
      }

      echo "<h1>Hallo! ❤️</h1>";
      echo "<p>Grüezi $user.</p>";

      if (!empty($description))
      {
        echo "<p>Dein Bild soll also <i>$description</i> heissen?</p>";
        $description_isset = true;
      }


      //####################################################################
      // Store common values cookies for next time, if requested
      //$_COOKIE['varname'] = $var_value;

      // delete cookie if no storing requested (in case there was a cookie before)


      //####################################################################
      // generate filename from parameters

      if(empty($user)) {
        cancel_processing("Fehler! Kein Benutzer angegeben.");
      }

      if($_POST["timeframe"] == "Monat") {
        $filename = 'm_' . validate_number_and_return_string(sanitize_input("month_number", TRUE), 1, 12) . '_' . $user ;
      } else { // asume Woche
        $filename = 'w_' . validate_number_and_return_string(sanitize_input("week_number", TRUE), 1, 52) . '_' . $user ;
      }

      //####################################################################
      // File Upload Handling

      $fileToUpload  = $_FILES["fileToUpload"];
      $file_basename = basename($fileToUpload["name"]);
      $upload_file   = $upload_folder . $file_basename;
      // $filename      = pathinfo($fileToUpload['name'], PATHINFO_FILENAME);
      if($debugging == true) {
        echo "<p>file name: " .      $filename ;
        echo "<br>file type: " .     $fileToUpload["type"];
        echo "<br>file tmp name: " . $fileToUpload["tmp_name"];
        echo "<br>file basename:     $file_basename";
        echo "<br>upload filename:   $upload_file</p>";
      }

      //Überprüfung der Dateiendung
      $extension = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
      $allowed_extensions = array('png', 'jpg', 'jpeg', 'JPG', 'JPEG');
      if(!in_array($extension, $allowed_extensions)) {
        cancel_processing("Fehler! Ungültige Dateiendung.");
      }

      //Überprüfung der Dateigröße
      $max_size = 10000*1024; //10MB
      if($_FILES['fileToUpload']['size'] > $max_size) {
        cancel_processing("Bitte keine Dateien größer 10 MB hochladen.");
      }

      //Überprüfung dass das Bild keine Fehler enthält
      if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
        $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detected_type = exif_imagetype($fileToUpload["tmp_name"]);
        if($debugging == true) {
          echo "<p>allowed extensions: ";
            foreach ($allowed_types as $x) echo $x . ", ";
          echo "<br>detected type: " . $detected_type . "</p>";
        }
        if(!in_array($detected_type, $allowed_types)) {
          cancel_processing("Nur der Upload von Bilddateien ist gestattet. Du verwendest $detected_type .");
        }
      }
      else {
        cancel_processing("Fehler! Keine PHP-EXIF functions verfügbar. Bitte Admins informieren!");
      }

      // Pfad zum Upload
      $new_path = $upload_folder.$filename.'.'.$extension;
      $tmp_file = $upload_folder.$filename.'_tmp.'.$extension;

      // delete existing file(s)
      if(file_exists($new_path)) {
        echo "<p>Bereits vorhandenes Bild wird gelöscht.</p>";
        if(!delete_file($new_path)) {
          cancel_processing("Fehler! Konnte vorhandene Datei nicht löschen. Bitte Admins informieren!");
        }
      }
      if(file_exists($tmp_file)) {
        echo "<p>Bereits vorhandenes Bild (tmp) wird gelöscht.</p>";
        if(!delete_file($tmp_file)) {
          cancel_processing("Fehler! Konnte vorhandene Datei nicht löschen. Bitte Admins informieren!");
        }
      }

      //Alles okay, verschiebe Datei an neuen Pfad
      move_uploaded_file($fileToUpload['tmp_name'], $new_path);
      echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';

      //####################################################################
      // generate requestet EXIF values

      $requested['.FileName']              = '';

      $requested['Title']                  = $description;
      $requested['ObjectName']             = $requested['Title'];

      $requested['ImageDescription']       = $user . ' / ' . $description;
      $requested['Description']            = $requested['ImageDescription'];
      $requested['Caption-Abstract']       = $requested['ImageDescription'];

      $requested['.ImageWidth']            = '2000';
      $requested['.ImageHeight']           = '2000';
      $requested['ExifImageWidth']         = $requested['.ImageWidth'];
      $requested['ExifImageHeight']        = $requested['.ImageHeight'];
      $requested['Orientation']            = '';

      $requested['Artist']                 = $creator;
      $requested['Creator']                = $requested['Artist'];
      $requested['By-line']                = $requested['Artist'];

      $requested['Copyright']              = $license;
      $requested['Rights']                 = $requested['Copyright'];
      $requested['CopyrightNotice']        = $requested['Copyright'];
      // $requested['ProfileCopyright']       = ''; // not user specific

      $requested['URL']                    = '';
      $requested['WebStatement']           = $requested['URL'];
      $requested['CreatorWorkURL']         = $requested['URL'];

      $requested['?GPS']                   = $no_geo ? 'Nein' : 'Ja';

      //####################################################################
      // display picture attributes (EXIF) existing compared to requested

      echo '<h2>Eckdaten des <i>hochgeladenen</i> Bildes</h2>';
      exif_display($new_path, $requested);

      //####################################################################
      // resize picture

      $command =  $convert_command . ' ' . escapeshellarg($new_path) .
                  ' -resize 2000x2000 ' . escapeshellarg($tmp_file);
      exec($command, $data, $result);
      if($debugging) { // debug
        echo "<p>command: "; print_r($command);
        echo "<br>data: <br><pre>"; print_r($data); echo "</pre>";
        echo "<br>result: "; print_r($result);
        echo "</p>";
      }
      if($result !== 0) {
        cancel_processing('Fehler bei der Größenänderung.');
      }
      if(unlink($new_path) == false) {
        cancel_processing('Fehler beim Löschen der alten Datei. (resize)');
      }
      if(rename($tmp_file, $new_path) == false) {
        cancel_processing('Fehler beim Umbennen der temporärern Datei. (resize)');
      }

      //####################################################################
      // update picture EXIF to requested/required attributes

      // build exiftool commandline parameters
      $et_param = ' ';
      foreach($requested as $tag=>$tag_value) {
        if($debugging and false) { echo "<p>TAG:$tag:VALUE:$tag_value:</p>"; }
        if( (substr($tag,0,1) == '.') or (substr($tag,0,1) == '?') ) { continue; }
        if( strlen($tag_value) == 0 ) { continue; }
        $et_param = $et_param . ' -' . $tag . '=' . escapeshellarg($tag_value);
      }
      // remove GEO data
      if($no_geo) {
        $et_param = $et_param . ' -gps:all= -xmp:geotag= ';
      }
      // run command
      if(strlen($et_param)==0) {
        echo '<p>Keine Metadaten-Anpassung notwendig.<p>';
      } else {
        $command =  $exiftool_command . ' -s -overwrite_original ' . $et_param . ' ' . escapeshellarg($new_path);
        exec($command, $data, $result);
        if($debugging) { // debug
          echo "<p>command: "; print_r($command);
          echo "<br>data: <br><pre>"; print_r($data); echo "</pre>";
          echo "<br>result: "; print_r($result);
          echo "</p>";
        }
        if($result !== 0) { echo '<p>Problem bei der Änderung der Metadataten aufgetreten.</p>'; }
      }


      //####################################################################
      // display picture attributes (EXIF) existing compared to requested

      echo '<h2>Eckdaten des <i>überarbeiteten</i> Bildes</h2>';
      exif_display($new_path, $requested);

      //####################################################################
      // display picture  and  furhter actions (buttons) to delete picture
      // IDEA: maybe directly send them to tim peters owncloud?

      echo '<h2>Das überarbeitete Bild! </h2>';
      echo '<p><img src="' . $new_path . '" alt="Your processed WeeklyPic" width="600"></p> ';

      $_SESSION['filename'] = $new_path;
    ?>

    <h2>Und nun?</h2>
    <p>Nachdem du das Bild (mit einem Rechtsklick auf das Bild)
       heruntergeladen hast, lädst du es wieder auf
       <a href='https://upload.weeklypic.de/' target="_blank">https://upload.weeklypic.de/</a> hoch. </p>
    <p>Hier solltest du das Bild löschen. (Sonst wird es auch irgendwann später gelöscht.)</p>
    <form method="post" action="final.php?<?php echo htmlspecialchars(SID); ?>">
      <input type="submit" name="delete" value="Löschen" ?>
    </form>

  </body>
</html>
