<?php
require_once('/var/www/html/lib/base.php');
try {
    $imageManager = \OC::$server->get(\OCA\Theming\ImageManager::class);
    $imageContent = file_get_contents('/var/www/html/apps/escardar_branding/img/logo.png');
    $imageManager->setImage('logo', 'image/png', $imageContent);
    $imageManager->setImage('logoheader', 'image/png', $imageContent);
    $imageManager->setImage('favicon', 'image/png', $imageContent);
    echo "Logo set via Theming ImageManager!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
