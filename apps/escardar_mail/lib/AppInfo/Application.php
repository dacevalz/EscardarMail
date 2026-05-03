<?php
namespace OCA\EscardarMail\AppInfo;

use OCA\EscardarMail\Listener\AutoProvisionListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\User\Events\UserLoggedInEvent;

class Application extends App implements IBootstrap {
    public const APP_ID = 'escardar_mail';

    public function __construct() {
        parent::__construct(self::APP_ID);
    }

    public function register(IRegistrationContext $context): void {
        $context->registerEventListener(UserLoggedInEvent::class, AutoProvisionListener::class);
    }

    public function boot(IBootContext $context): void {}
}
