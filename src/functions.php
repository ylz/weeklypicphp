<?php

  // Processing is stoped with "die", closing <body> and <hmtl> tags.
  function cancel_processing($msg) {
    echo "<p><strong>⚠️ " . $msg . "</strong><br/>";
    echo "<em>Die Verarbeitung wird abgebrochen.</em></p>";
    echo '<p>Gehe an den <a href="index.php">Anfang</a> zurück um es noch einmal zu probieren.</p>';
    echo '</body></hmtl>';
    die();
  }


  function validate_number_and_return_string($n, $min, $max) {
    $num = intval($n);
    if($num < $min OR $num > $max) {
      cancel_processing("Fehler! Wert $n ist nicht im Bereich $min - $max !");
    }
    return sprintf('%02d', $num);
  }


  function sanitize_input($param, $required) {
    if (empty($_POST[$param])) {
      if($required){
        cancel_processing("Parameter '$param' wurde nicht angegeben.");
      } else {
        return '';
      }
    } else {
      return htmlspecialchars(trim($_POST[$param]));
    }
  }


  function delete_file($filename) {
    if(file_exists($filename)) {
      if(unlink($filename) == false) {
        echo '<p>⚠️ Fehler beim Löschen der Bild-Datei. (1)</p>';
        echo '<p>Bitte informiere einen Admin über das Problem.</p>';
        return FALSE;
      } else {
        if(file_exists($filename)) {
          echo '<p>⚠️ Fehler beim Löschen der Bild-Datei. (2)</p>';
          echo '<p>Bitte informiere einen Admin über das Problem.</p>';
          return FALSE;
        } else {
          echo '<p>♻️ Das Bild wurde vom Server gelöscht.</p>';
          return TRUE;
        }
      }
    } else {
      echo '<p>⚠️ Die zu löschende Bild-Datei existiert nicht. 🤔</p>';
      echo '<p>Bitte informiere einen Admin über das Problem.</p>';
      return FALSE;
    }
  }


?>
