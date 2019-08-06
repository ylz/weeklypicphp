<?php

  function exif_get_tag_value($list, $tag) {
// from a $list of EXIF-tags (returned by exiftool -s) pick the first one
// *starting* with $tag and return its value (after the colon, trimmed).
// To get the exact tag add a space to $tag.
    if($tag == '.Geo') {  // Extra processing for Meta-Tag  // BUG: funktioniert nicht :(
      if(exif_get_tag_value($list, "GPS") !== '') {
        return 'Ja';
      } else {
        return 'Nein';
      }
    } else {
      foreach($list as $line) {
        $tl = strlen($tag); // tag length
        if(strncmp( $line, $tag, $tl ) == 0) {
          return trim( substr($line, strpos($line,':')+1) );
        }
      }
      return '';
    }
  }


  function scale_to($to, $me, $other) {
    // If $me is scaled $to, then the $other side is sclaqed to return value
    return (int) ( $other / ( ( $me * 1.0 ) / $to ) );  // must convert to float (* 1.0) and back to int
  }


  function exif_display($filename, $requested) {
    // reads EXIF data using exiftool -s (-s uses the technical names)
    // displays EXIF data given by the requested hash compared to the requested data.
    // returns: "Width" or "Height" for the direction to be 2000 pixels. (so the larger side)

    // PHP function "exif_read_data" doesn't read all tags, i.e. "Title", so this is deactivated.
    //$exif_data = exif_read_data($new_path, "FILE,COMPUTED,ANY_TAG,IFDO,COMMENT,EXIF", true);
    //if($debugging == true) { print_r($exif_data); };

    exec("/usr/local/bin/exiftool -s $filename", $exif_data, $exiftool_result);
    if(false == true) { // debug
      echo "<p>filename: "; print_r($filename);
      echo "<br>exif_data: <br><pre>"; print_r($exif_data); echo "</pre>";
      echo "<br>exiftool_result: "; print_r($exiftool_result);
      echo "</p>";
    }
    if($exiftool_result !== 0) { die('Fehler beim Aufruf des EXIF-Tools!'); }

    // Calculate new size
    $pic_width  = exif_get_tag_value($exif_data, 'ImageWidth');
    $pic_height = exif_get_tag_value($exif_data, 'ImageHeight');
    if($pic_width > $pic_height) {
      $requested['ImageHeight'] = scale_to(2000, $pic_width, $pic_height);
      $requested['ImageWidth']  = '2000';
      $fixed_size = 'Width';
    } else {
      $requested['ImageWidth']  = scale_to(2000, $pic_height, $pic_width);
      $requested['ImageHeight'] = '2000';
      $fixed_size = 'Height';
    }
    $requested['ExifImageWidth']  = $requested['ImageWidth'];
    $requested['ExifImageHeight'] = $requested['ImageHeight'];

    // Display comparisom table
    // TODO: Maybe its better to have a line by line compare on small displays?
    echo '<p><table style="border:1">';
    echo "<tr><th>EXIF Tag</th><th>aktuell</th><th>soll</th><th>?</th></tr>";
    foreach($requested as $exif_tag=>$exif_value) {
      $exif_tag_is = exif_get_tag_value($exif_data, $exif_tag);
      echo "<tr><td>$exif_tag</td><td>$exif_tag_is</td><td>$exif_value</td><td>";
      if($exif_tag_is == $exif_value) { echo '✅'; } else { echo '⚠️'; }
      echo "</td></tr>";
    }
    echo "</table></p>";

    return $fixed_size;
  }

?>
