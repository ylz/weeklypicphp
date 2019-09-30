<?php

  function exif_get_tag_value($list, $tag) {
  // from a $list of EXIF-tags (returned by exiftool -s) pick the first one
  // *starting* with $tag and return its value (after the colon, trimmed).
  // To get the exact tag add a space to $tag.
  // If the $tag starts with a ".", "=" or "?" this will be removed bevore the search.
  // If it starts with a "?", $tag_is_set will be returned if a tag was found, otherwise $tag_not_set
  // If it starts with a "=", it's value is calculated
    global $tag_is_set;
    global $tag_not_set;
    if(substr($tag,0,1) == '.') {  // Extra processing for Meta-Tag
      return exif_get_tag_value($list, substr($tag, 1));
    } elseif(substr($tag,0,1) == '?') {  // Extra processing for Meta-Tag yes/no
      if(exif_get_tag_value($list, substr($tag, 1)) !== '') {
        return $tag_is_set;
      } else {
        return $tag_not_set;
      }
    } elseif(substr($tag,0,1) == '=') {  // will be calculated
      switch ($tag) {
        case '=Month':
            return date('n', strtotime(exif_get_tag_value($list, 'CreateDate'))); // REVIEW: is this secure/stable?
            break;
        case '=Week':
            return date('W', strtotime(exif_get_tag_value($list, 'CreateDate'))); // REVIEW: is this secure/stable?
            break;
        default:
            return 'n/a';
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
    // If $me is scaled $to, then the $other side is scaled to return value
    return (int) ( $other / ( ( $me * 1.0 ) / $to ) );  // must convert to float (* 1.0) and back to int
  }


  function exif_display($filename, $requested) {
    // reads EXIF data using exiftool -s (-s uses the technical names)
    // displays EXIF data given by the requested hash compared to the requested data.
    // returns: "Width" or "Height" for the direction to be 2000 pixels. (so the larger side)

    // PHP function "exif_read_data" doesn't read all tags, i.e. "Title", so this is deactivated.
    //$exif_data = exif_read_data($new_path, "FILE,COMPUTED,ANY_TAG,IFDO,COMMENT,EXIF", true);
    //if($debugging == true) { print_r($exif_data); };

    global $exiftool_command;
    exec($exiftool_command . ' -s ' . escapeshellarg($filename), $exif_data, $exiftool_result);
    if(false == true) { // debug
      echo "<p>filename: "; print_r($filename);
      echo "<br>exif_data: <br><pre>"; print_r($exif_data); echo "</pre>";
      echo "<br>exiftool_result: "; print_r($exiftool_result);
      echo "</p>";
    }
    if($exiftool_result !== 0) { cancel_processing('Fehler beim Aufruf des EXIF-Tools!'); }

    // Calculate new size
    $pic_width  = exif_get_tag_value($exif_data, 'ImageWidth');
    $pic_height = exif_get_tag_value($exif_data, 'ImageHeight');
    if($pic_width > $pic_height) {
      $requested['.ImageHeight'] = scale_to(2000, $pic_width, $pic_height);
      $requested['.ImageWidth']  = '2000';
    } else {
      $requested['.ImageWidth']  = scale_to(2000, $pic_height, $pic_width);
      $requested['.ImageHeight'] = '2000';
    }
    $requested['ExifImageWidth']  = $requested['.ImageWidth'];
    $requested['ExifImageHeight'] = $requested['.ImageHeight'];

    // Display comparisom table
    echo '<p><table style="border:1">';
    echo "<tr><th>EXIF Tag</th><th>aktuell</th><th>soll</th><th>?</th></tr>";
    foreach($requested as $exif_tag=>$exif_value) {
      $exif_tag_is = exif_get_tag_value($exif_data, $exif_tag);
      echo "<tr><td>$exif_tag</td><td>$exif_tag_is</td><td>$exif_value</td><td>";
      if($exif_value == '') { echo '-'; }
      elseif($exif_tag_is == $exif_value) { echo '✅'; }
      else { echo '⚠️'; }
      echo "</td></tr>";
    }
    echo "</table></p>";
    // BUG: GPS detected even if deleted because of GPSVersionID Tag
    echo "<p><small>Achtung, es kann hier angezeigt werden, dass GPS Daten vorhanden sind, obwohl diese gelöscht wurden, weil noch eine GPS-Version-ID vorhanden ist, was in Ordnung ist. Die Fehlerbehebung ist in Arbeit.</small></p>";

    // link GPS data to OSM
    $geocoordinates = exif_get_tag_value($exif_data, 'GPSPosition');
    if($geocoordinates <> '') {
      $geocoordinates = str_ireplace ( ' deg' , '°' , $geocoordinates );
      $urlgeocoordinates = urlencode($geocoordinates);
      echo 'Die Geokoordinaten des Bildes' .
         '<a href="https://www.openstreetmap.org/search?query=' . $urlgeocoordinates .
         '" target="_blank">' . $geocoordinates . '</a> (Link in neuem Fenster zu Openstreetmap.org)';
    }

  }

?>
