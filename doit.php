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
    </style>
  </head>
  <body>
    <?php
      //####################################################################
      // _POST Var Handling
      $user = $_POST["user"];
      $description = $_POST["description"];
      $description_isset = false;
      echo "<h1>Hallo! ❤️</h1>";
      echo "<p>Grüezi $user.</p>";
      if (!empty($description))
      {
        echo "<p>Dein Bild soll also **$description** heissen?</p>";
        $description_isset = true;
      }
      //####################################################################
      // File Upload Handling
      $upload_folder = '_files/'; //Das Upload-Verzeichnis
      $filename = pathinfo($_FILES['datei']['name'], PATHINFO_FILENAME);
      $extension = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));

      //Überprüfung der Dateiendung
      $allowed_extensions = array('png', 'jpg', 'jpeg', 'JPG', 'JPEG');
      if(!in_array($extension, $allowed_extensions)) {
       die("Ungültige Dateiendung.");
      }

      //Überprüfung der Dateigröße
      $max_size = 5000*1024; //5MB
      if($_FILES['datei']['size'] > $max_size) {
       die("Bitte keine Dateien größer 5MB hochladen");
      }

      //Überprüfung dass das Bild keine Fehler enthält
      if(function_exists('exif_imagetype')) { //Die exif_imagetype-Funktion erfordert die exif-Erweiterung auf dem Server
       $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
       $detected_type = exif_imagetype($_FILES['datei']['tmp_name']);
       if(!in_array($detected_type, $allowed_types)) {
       die("Nur der Upload von Bilddateien ist gestattet");
       }
      }
      else {
        die("No PHP-EXIF functions");
      }

      //Pfad zum Upload
      $new_path = $upload_folder.$filename.'.'.$extension;

      //Neuer Dateiname falls die Datei bereits existiert
      if(file_exists($new_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
       $id = 1;
       do {
       $new_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
       $id++;
       } while(file_exists($new_path));
      }

      //Alles okay, verschiebe Datei an neuen Pfad
      move_uploaded_file($_FILES['datei']['tmp_name'], $new_path);
      echo 'Bild erfolgreich hochgeladen: <a href="'.$new_path.'">'.$new_path.'</a>';


    ?>




    <?php
      //####################################################################
    ?>
  </body>
</html>
