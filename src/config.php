<?php

  $debugging        = FALSE;

  $cookie_name      = "WeeklyPicPHPParam";
  $cookie_split     = "§%§";
  $cookie_expires   = time() + 60 * 60 * 24 * 100;  // now + 100 days (in seconds)

  $upload_folder    = '_files/';                    // Das Upload-Verzeichnis
  $command_log      = $upload_folder . 'exec_cmd.log';
  $convert_command  = '/usr/local/bin/convert';     // imagemagick convert
  $exiftool_command = '/usr/local/bin/exiftool';    // EXIFtool

  $tag_is_set       = 'ja';
  $tag_not_set      = 'nein';

?>
