<?php
namespace OCA\EscardarMail\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
    public function __construct(array $urlParams = []) {
        parent::__construct('escardar_mail', $urlParams);
    }
}