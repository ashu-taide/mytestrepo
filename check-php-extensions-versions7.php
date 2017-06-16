<?php

/**
 * Performs a simple version check for php extensions.
 *
 * The versions are hard-coded from container specs.
 */
function get_php_ext_versions() {
  $extensions = array(
    'gd' => '2.1.1-dev',
    'pdo_mysql' => '5.0.12-dev - 20150407 - $Id: b396954eeb2d1d9ed7902b8bae237b287f21ad9e',
    'mcrypt' => '2.5.8',
    'gmp' => '6.0.0',
    'zip' => '1.13.5',
    'mysqli' => '5.0.12-dev - 20150407 - $Id: b396954eeb2d1d9ed7902b8bae237b287f21ad9e',
    'bz2' => '1.0.6',
    'imagick' => '3.4.3',
    'curl' => '7.38.0',
  );

  foreach ($extensions as $extension => $version) {
    ob_start();
    $ext = new ReflectionExtension($extension);
    $ext->info();
    $ext_info = ob_get_contents();
    ob_end_clean();

    $version_matches = strpos($ext_info, $version);
    if ($version_matches === FALSE) {
      $message = sprintf("Version check failed for %s. Expected %s, got %s.\n", $extension, $version, $ext_info);
      file_put_contents('/tmp/version_check_failure', $message, FILE_APPEND);
    }
    else {
      $message = sprintf("Version check completed successfully for %s. Version: %s.\n", $extension, $version);
      file_put_contents('/tmp/version_check_success', $message, FILE_APPEND);
      print($message);
    }
  }
  printf('Version check completed for all php extensions.');
}

get_php_ext_versions();
