<?php
/**
 * Fix apps_paths config y habilita las apps custom de Escardar.
 * Uso: docker exec escardarmail-app-1 php /var/www/html/scripts/fix_config.php
 */

$configFile = '/var/www/html/config/config.php';

if (!file_exists($configFile)) {
    echo "ERROR: No se encontró config.php en $configFile\n";
    exit(1);
}

require $configFile;

$CONFIG['apps_paths'] = [
    ['path' => '/var/www/html/apps',        'url' => '/apps',        'writable' => true],
    ['path' => '/var/www/html/custom_apps', 'url' => '/custom_apps', 'writable' => false],
];

$CONFIG['integrity.check.disabled'] = true;

$content = "<?php\n\$CONFIG = " . var_export($CONFIG, true) . ";\n";
file_put_contents($configFile, $content);

echo "✓ apps_paths actualizado\n";
echo "✓ integrity.check.disabled = true\n";
echo "Listo. Ahora corré:\n";
echo "  docker exec --user www-data escardarmail-app-1 php occ app:enable escardar_branding\n";
echo "  docker exec --user www-data escardarmail-app-1 php occ app:enable escardar_mail\n";
