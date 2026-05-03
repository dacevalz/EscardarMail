<?php
namespace OCA\EscardarBranding\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;

class Application extends App implements IBootstrap {
    public const APP_ID = 'escardar_branding';

    public function __construct() {
        parent::__construct(self::APP_ID);
    }

    public function register(IRegistrationContext $context): void {
    }

    public function boot(IBootContext $context): void {
        \OCP\Util::addStyle(self::APP_ID, 'login-overrides');
        \OCP\Util::addScript(self::APP_ID, 'login-video');
    }
}
