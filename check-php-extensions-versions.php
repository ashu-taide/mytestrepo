<?php
/**
 * Performs a simple version check for php extensions.
 *
 * The versions are hard-coded from container specs.
 */
function get_php_ext_versions() {
  $phpVersion = 'default';
  if (preg_match('/^(\d.\d)/', phpversion(), $matches)) {
    $phpVersion = 'php' . $matches[1];
  }

  $extensions = [];
  $file = fopen('container-versions.log','r');
  while (!feof($file)) {
    $line = fgets($file);
    if (empty($line)) {
      continue;
    }
    list($pkg, $version) = explode('|', $line);
    $version = trim($version);
    if (preg_match('/((php(\d.\d)?) extension version (.*))/', $pkg, $matches)) {
      if ($matches[2] == $phpVersion) {
        $extensions[$matches[4]] = $version;
      }
    }
  }
  fclose($file);

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
