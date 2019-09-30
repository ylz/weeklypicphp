<?php

  $debugging        = FALSE;

  $cookie_name      = "WeeklyPicPHPParam";
  $cookie_split     = "§%§";
  $cookie_expires   = time() + 60 * 60 * 24 * 100;  // now + 100 days (in seconds)

  $upload_folder    = '_files/';                    // Das Upload-Verzeichnis
  $log_folder       = '_log/';                      // Das Log-Verzeichnis
  $command_log      = $log_folder . 'exec_cmd.log';
  $usage_log        = $log_folder . 'usage.log';
  $convert_command  = '/usr/local/bin/convert';     // imagemagick convert
  $exiftool_command = '/usr/local/bin/exiftool';    // EXIFtool
  $curl_command     = '/usr/bin/curl';              // curl

  $tag_is_set       = 'ja';
  $tag_not_set      = 'nein';

  date_default_timezone_set('Europe/Berlin');  // see https://www.php.net/manual/en/timezones.php

  if($debugging) { // debug
    echo "<p>⚠️ DEBUGGING IS SET TO TRUE! DON'T DO THIS ON A PUBLIC SERVER! ⚠️</p>";
  }

  // The upload server is a secret - you must create a file with just the URL manually!
  // The file must contain two lines:
  // ----
  // server=<URL of the server>
  // login=<login to server>
  // loglevel=<n>   // 0=no logging, 1=only pages, 2=and user
  // ----
  // Of course you could set the parameters directly here as well - but that's
  // not handy if you use github. ;)
  // Don't forget to put the upload_server.config into the .gitignore file.
  $upload_server_f  = 'src/upload_server.config';
  $upload_server = 'na';
  $upload_login  = 'na';
  $usage_logging = 99;  //  99=unset
  $upload_ok     = FALSE;
  if (file_exists($upload_server_f)) {
    $server_config_lines = explode(PHP_EOL, file_get_contents($upload_server_f));
    foreach ($server_config_lines as $line) {
      if(substr($line, 0, 7) == 'server=') {
        if($upload_server == 'na') {
          $upload_server = trim(substr($line, 7));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, server already defined.';
        }
      } elseif (substr($line, 0, 6) == 'login=') {
        if($upload_login == 'na') {
          $upload_login = trim(substr($line, 6));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, login already defined.';
        }
      } elseif (substr($line, 0, 9) == 'loglevel=') {
        if($usage_logging == 99) {
          $usage_logging = intval(trim(substr($line, 9)));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, loglevel already defined.';
        }
      }
    }
    if($upload_server == 'na' OR $upload_login == 'na') {
      echo '<p>⚠️ Upload-Server-Configuration incomplete!';
    } else {
      $upload_ok = TRUE;
    }
    if($usage_logging == 99) { $usage_logging = 1; } // Default, log pages called
  } else {
    echo '<p>⚠️ Upload-Server-Configuration file is missing!';
  }
  if(!$upload_ok) {
    echo '<br>Es ist kein automatischer Upload zu WeekyPic möglich. Dies kann nur manuell (Download+Upload) erfolgen.</p>';
  }

  if($debugging OR FALSE) { // debug
    echo "<p>server: "; print_r($upload_server);
    echo "<br>result: "; print_r($upload_login);
    echo "</p>";
  }

?>
