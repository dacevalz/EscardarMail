<?php
$CONFIG = array (
  'dbname' => 'nextcloud',
  'dbhost' => 'db',
  'dbuser' => 'nextcloud',
  'dbpassword' => 'escardar_db_pass',
  'dbtype' => 'mysql',
  'instanceid' => 'escardar_mail_id',
  'adminuser' => 'admin',
  'adminpass' => 'admin_pass',
  'trusted_domains' => array (
    0 => 'escardar.com',
  ),
  'mail_domain' => 'escardar.com',
  'themed' => true,
  'theme' => 'escardar_branding',
  'instance_name' => 'Escardar Mail',
  'mail_template_class' => 'OCA\\EscardarMail\\EmailTemplate',
  'installed' => true,
  'version' => '33.0.2.2',
  'passwordsalt' => 'escardar_salt_1234567890123456',
  'secret' => 'escardar_secret_12345678901234567890123456789012',
);