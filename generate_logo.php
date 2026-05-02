<?php
$pngPath = '/var/www/html/apps/escardar_branding/img/logo.png';
$b64 = base64_encode(file_get_contents($pngPath));
$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><image href="data:image/png;base64,' . $b64 . '" x="0" y="0" width="100%" height="100%" /></svg>';
@mkdir('/var/www/html/themes/escardar_branding', 0777, true);
@mkdir('/var/www/html/themes/escardar_branding/core', 0777, true);
@mkdir('/var/www/html/themes/escardar_branding/core/img', 0777, true);
@mkdir('/var/www/html/themes/escardar_branding/core/img/logo', 0777, true);
file_put_contents('/var/www/html/themes/escardar_branding/core/img/logo/logo.svg', $svg);
file_put_contents('/var/www/html/themes/escardar_branding/core/img/logo/logo-icon.svg', $svg);
file_put_contents('/var/www/html/themes/escardar_branding/core/img/logo.svg', $svg);
file_put_contents('/var/www/html/themes/escardar_branding/core/img/logo-icon.svg', $svg);
echo "SVGs created successfully.";
