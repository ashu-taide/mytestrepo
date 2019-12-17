#!/usr/bin/env php
<?php

$packages = [];
$file = fopen('container-versions.log','r');
while (!feof($file)) {
  $line = fgets($file);
  if (empty($line)) {
    continue;
  }
  list($pkg, $version, $command) = explode('|', $line);
  $version = trim($version);
  if (!preg_match('/((php(\d.\d)?) extension version (.*))/', $pkg, $matches)
    && !preg_match('/((php(\d.\d)?) extension (.*))/', $pkg, $matches)) {
    $packages[$pkg] = [
      'env' => strtoupper("pipelines_" . str_replace([' ', '.', '-'], '_', $pkg)),
      'version' => $version,
    ];
    putenv($packages[$pkg]['env']. "=" . $packages[$pkg]['version']);
  }
}
fclose($file);
var_dump($packages);
