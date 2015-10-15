<?php

/**
 * Performs a simple version check for php extensions.
 *
 * The versions are hard-coded from container specs.
 */
function get_php_ext_versions() {
  $extensions = array(
    'gd' => '2.1.0',
    'pdo_mysql' => '5.0.11-dev - 20120503 - $Id: 3c688b6bbc30d36af3ac34fdd4b7b5b787fe5555',
    'mcrypt' => '2.5.8',
    'gmp' => '6.0.0',
    'zip' => 'c203148334b6f80d27bc5d23fad5ec3ca7dcf444',
    'mysqli' => '5.0.11-dev - 20120503 - $Id: 3c688b6bbc30d36af3ac34fdd4b7b5b787fe5555',
  );

  foreach ($extensions as $extension => $version) {
    ob_start();
    $ext = new ReflectionExtension($extension);
    $ext->info();
    $ext_info = ob_get_contents();
    ob_end_clean();
    
    $version_matches = strpos($ext_info, $version);
    if ($version_matches === FALSE) {
      printf("Version check failed for %s. Expected %s, got %s.\n", $extension, $version, $ext_info);
      exit(1);
    }
    else {
      printf("Version check completed successfully for %s. Version: %s.\n", $extension, $version);
    }
  }
  printf("Version check completed successfully for all php extensions.");
}

get_php_ext_versions();
