<?php

// @codingStandardsIgnoreFile

$settings['hash_salt'] = getenv('DRUPAL_SALT');
$settings['update_free_access'] = FALSE;
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
$settings['entity_update_batch_size'] = 50;
$settings['entity_update_backup'] = TRUE;

$settings['trusted_host_patterns'] = array(
  '^api\.hcpss\.localhost$',
  '^10\.216\.209\.84$',
  '^api\.hcpss\.org$',
);

if (getenv('DRUPAL_ENV') == 'dev') {
  $config['config_split.config_split.development_split']['status'] = TRUE;
}
else {
  $config['config_split.config_split.development_split']['status'] = FALSE;
}

$config_directories['sync'] = '../config/sync';
$databases['default']['default'] = array (
  'database' => getenv('MYSQL_DATABASE'),
  'driver' => 'mysql',
  'host' => getenv('MYSQL_HOSTNAME'),
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'password' => getenv('MYSQL_PASSWORD'),
  'port' => getenv('MYSQL_PORT'),
  'prefix' => '',
  'username' => getenv('MYSQL_USER'),
);
