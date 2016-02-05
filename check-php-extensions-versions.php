<?php

/**
 * Performs a simple version check for php extensions.
 *
 * The versions are hard-coded from container specs.
 */
function get_php_ext_versions() {
  $extensions = array(
    'gd' => '2.1.0',
    'pdo_mysql' => '5.0.11-dev - 20120503 - $Id: 76b08b24596e12d4553bd41fc93cccd5bac2fe7a',
    'mcrypt' => '2.5.8',
    'gmp' => '6.0.0',
    'zip' => 'd040d206d1a9ea6ebaaa007ad186da532d8ba024',
    'mysqli' => '5.0.11-dev - 20120503 - $Id: 76b08b24596e12d4553bd41fc93cccd5bac2fe7a',
    'bz2' => '1.0.6',
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
  printf('Version check completed successfully for all php extensions.');
}

get_php_ext_versions();
