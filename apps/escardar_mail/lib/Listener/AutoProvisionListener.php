<?php
namespace OCA\EscardarMail\Listener;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IDBConnection;
use OCP\Security\ICrypto;
use OCP\User\Events\UserLoggedInEvent;
use Psr\Log\LoggerInterface;

class AutoProvisionListener implements IEventListener {

    private const MAIL_DOMAIN  = 'escardar.com';
    private const IMAP_HOST    = 'mail';
    private const IMAP_PORT    = 143;
    private const IMAP_SSL     = 'none';
    private const SMTP_HOST    = 'mail';
    private const SMTP_PORT    = 587;
    private const SMTP_SSL     = 'none';

    public function __construct(
        private IDBConnection $db,
        private ICrypto       $crypto,
        private LoggerInterface $logger
    ) {}

    public function handle(Event $event): void {
        if (!$event instanceof UserLoggedInEvent) {
            return;
        }

        $userId      = $event->getUser()->getUID();
        $displayName = $event->getUser()->getDisplayName() ?: $userId;
        $password    = $event->getPassword();
        $email       = $userId . '@' . self::MAIL_DOMAIN;

        try {
            if ($this->accountExists($userId)) {
                return;
            }
            $this->createAccount($userId, $displayName, $email, $password);
            $this->logger->info('[EscardarMail] Cuenta de correo auto-provisionada para ' . $userId);
        } catch (\Throwable $e) {
            // No romper el login si algo falla
            $this->logger->warning('[EscardarMail] No se pudo provisionar correo para ' . $userId . ': ' . $e->getMessage());
        }
    }

    private function accountExists(string $userId): bool {
        $qb = $this->db->getQueryBuilder();
        $result = $qb->select('id')
            ->from('mail_accounts')
            ->where($qb->expr()->eq('user_id', $qb->createNamedParameter($userId)))
            ->executeQuery();
        $exists = $result->fetchOne() !== false;
        $result->closeCursor();
        return $exists;
    }

    private function createAccount(string $userId, string $displayName, string $email, string $password): void {
        $enc = $this->crypto->encrypt($password);

        $qb = $this->db->getQueryBuilder();
        $qb->insert('mail_accounts')
            ->values([
                'user_id'           => $qb->createNamedParameter($userId),
                'name'              => $qb->createNamedParameter($displayName),
                'email'             => $qb->createNamedParameter($email),
                'inbound_host'      => $qb->createNamedParameter(self::IMAP_HOST),
                'inbound_port'      => $qb->createNamedParameter(self::IMAP_PORT, IQueryBuilder::PARAM_INT),
                'inbound_ssl_mode'  => $qb->createNamedParameter(self::IMAP_SSL),
                'inbound_user'      => $qb->createNamedParameter($email),
                'inbound_password'  => $qb->createNamedParameter($enc),
                'outbound_host'     => $qb->createNamedParameter(self::SMTP_HOST),
                'outbound_port'     => $qb->createNamedParameter(self::SMTP_PORT, IQueryBuilder::PARAM_INT),
                'outbound_ssl_mode' => $qb->createNamedParameter(self::SMTP_SSL),
                'outbound_user'     => $qb->createNamedParameter($email),
                'outbound_password' => $qb->createNamedParameter($enc),
                'auth_method'       => $qb->createNamedParameter('password'),
                'order'             => $qb->createNamedParameter(0, IQueryBuilder::PARAM_INT),
            ])
            ->executeStatement();
    }
}
