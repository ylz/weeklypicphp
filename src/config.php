<?php

  $debugging        = FALSE;

  $cookie_name      = "WeeklyPicPHPParam";
  $cookie_split     = "§%§";
  $cookie_expires   = time() + 60 * 60 * 24 * 100;  // now + 100 days (in seconds)

  $tag_is_set       = 'ja';
  $tag_not_set      = 'nein';

  date_default_timezone_set('Europe/Berlin');  // see https://www.php.net/manual/en/timezones.php

  if($debugging) { // debug
    echo "<p>⚠️ DEBUGGING IS SET TO TRUE! DON'T DO THIS ON A PUBLIC SERVER! ⚠️</p>";
  }

  // The upload server is a secret - you must create a file with just the URL manually!
  // The file must contain at least the first two lines to enable upload.
  // There must be *no* space between the parametername and the '=' !
  // ----
  // server=<URL of the server>
  // login=<password>
  // # optional parameters
  // loglevel=<n>                   // 0=no logging, 1=only pages, 2=and user
  // upload_folder=<foldername>     // Das Upload-Verzeichnis
  // command_log=<filename>
  // usage_log=<filename>
  // convert_command=<programname>  // imagemagick convert
  // exiftool_command=<programname> // EXIFtool
  // curl_command=<programname>     // curl
  // ----
  // Of course you could set the parameters directly here as well - but that's
  // not handy if you use github. ;)
  // Don't forget to put the upload_server.config into the .gitignore file.

  $upload_server    = 'na';
  $upload_login     = 'na';
  $usage_logging    =  99;      //  99=unset
  $upload_folder    = 'na';     // Das Upload-Verzeichnis
  $command_log      = 'na';
  $usage_log        = 'na';
  $convert_command  = 'na';     // imagemagick convert
  $exiftool_command = 'na';     // EXIFtool
  $curl_command     = 'na';     // curl

  $upload_server_f  = 'src/config.config';
  $upload_ok        = FALSE;
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
      } elseif (substr($line, 0, 14) == 'upload_folder=') {
        if($upload_folder == 'na') {
          $upload_folder = trim(substr($line, 14));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, upload_folder already defined.';
        }
      } elseif (substr($line, 0, 12) == 'command_log=') {
        if($command_log == 'na') {
          $command_log = trim(substr($line, 12));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, command_log already defined.';
        }
      } elseif (substr($line, 0, 10) == 'usage_log=') {
        if($usage_log == 'na') {
          $usage_log = trim(substr($line, 10));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, usage_log already defined.';
        }
      } elseif (substr($line, 0, 16) == 'convert_command=') {
        if($convert_command == 'na') {
          $convert_command = trim(substr($line, 16));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, convert_command already defined.';
        }
      } elseif (substr($line, 0, 17) == 'exiftool_command=') {
        if($exiftool_command == 'na') {
          $exiftool_command = trim(substr($line, 17));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, exiftool_command already defined.';
        }
      } elseif (substr($line, 0, 13) == 'curl_command=') {
        if($curl_command == 'na') {
          $curl_command = trim(substr($line, 13));
        } else {
          echo '<p>⚠️ Error in Upload-Server-Configuration, curl_command already defined.';
        }
      }

    }

    if($upload_server == 'na' OR $upload_login == 'na') {
      echo '<p>⚠️ Upload-Server-Configuration incomplete!';
    } else {
      $upload_ok = TRUE;
    }

    // Set Defaults - if not imported from file
    if($usage_logging == 99)      { $usage_logging    = 1; }           // Default, log pages called
    if($upload_folder == 'na')    { $upload_folder    = '_files/'; }   // Das Upload-Verzeichnis
    if($command_log == 'na')      { $command_log      = '_log/exec_cmd.log'; }
    if($usage_log == 'na')        { $usage_log        = '_log/usage.log'; }
    if($convert_command == 'na')  { $convert_command  = '/usr/local/bin/convert'; }    // imagemagick convert
    if($exiftool_command == 'na') { $exiftool_command = '/usr/local/bin/exiftool'; }   // EXIFtool
    if($curl_command == 'na')     { $curl_command     = '/usr/bin/curl'; }             // curl

  } else {
    echo '<p>⚠️ Upload-Server-Configuration file is missing!';
  }

  if(!$upload_ok) {
    echo '<br>Es ist kein automatischer Upload zu WeekyPic möglich. Dies kann nur manuell (Download+Upload) erfolgen.</p>';
  }

  if(1 == 2) { // debug
    echo "<p>server: "; print_r($upload_server);
    echo "<br>login: "; print_r($upload_login);
    echo "</p>";
  }

?>
