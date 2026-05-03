<?php
/**
 * Arregla apps_paths en apps.config.php e integrity check en config.php.
 * Uso: docker exec escardarmail-app-1 php /tmp/fix_config.php
 */

// --- 1. Arreglar apps.config.php ---
$appsConfigFile = '/var/www/html/config/apps.config.php';

$appsConfigContent = <<<'PHP'
<?php
$CONFIG = array (
  'apps_paths' => array (
      0 => array (
              'path'     => '/var/www/html/apps',
              'url'      => '/apps',
              'writable' => true,
      ),
      1 => array (
              'path'     => '/var/www/html/custom_apps',
              'url'      => '/custom_apps',
              'writable' => false,
      ),
  ),
);
PHP;

file_put_contents($appsConfigFile, $appsConfigContent);
echo "✓ apps.config.php: apps=writable, custom_apps=readonly\n";

// --- 2. Agregar integrity.check.disabled en config.php ---
$configFile = '/var/www/html/config/config.php';
require $configFile;
$CONFIG['integrity.check.disabled'] = true;
file_put_contents($configFile, "<?php\n\$CONFIG = " . var_export($CONFIG, true) . ";\n");
echo "✓ config.php: integrity.check.disabled = true\n";

echo "\nAhora corré:\n";
echo "  docker exec --user www-data escardarmail-app-1 php occ app:enable escardar_branding\n";
echo "  docker exec --user www-data escardarmail-app-1 php occ app:enable escardar_mail\n";
