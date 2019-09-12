<?php

  $debugging        = FALSE;

  $cookie_name      = "WeeklyPicPHPParam";
  $cookie_split     = "§%§";
  $cookie_expires   = time() + 60 * 60 * 24 * 100;  // now + 100 days (in seconds)

  $upload_folder    = '_files/';                    // Das Upload-Verzeichnis
  $command_log      = $upload_folder . 'exec_cmd.log';
  $convert_command  = '/usr/local/bin/convert';     // imagemagick convert
  $exiftool_command = '/usr/local/bin/exiftool';    // EXIFtool
  $curl_command     = '/usr/bin/curl';              // curl

  $tag_is_set       = 'ja';
  $tag_not_set      = 'nein';

  // The upload server is a secret - you must create a file with just the URL manually!
  // The file must contain two lines:
  // ----
  // server=<URL of the server>
  // login=<login to server>
  // ----
  // Of course you could set the parameters directly here as well - but that's
  // not handy if you use github. ;)
  // Don't forget to put the upload_server.config into the .gitignore file.
  $upload_server_f  = 'src/upload_server.config';
  if (file_exists($upload_server_f)) {
    // TODO: Implement file parsing of server parameters
    $upload_server = trim(file_get_contents($upload_server_f));
  } else {
    $upload_server = '';
    $upload_login  = '';
  }

?>
