# Mail Integrations

**Analysis Date:** 2026-05-02
**Focus:** Service Communication & Configuration

## Docker Service Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Docker Compose                         │
│                                                          │
│  ┌─────────┐    ┌─────────┐    ┌─────────────────────┐   │
│  │   db    │◄───│  app    │    │      mail          │   │
│  │mariadb  │    │nextcloud│    │docker-mailserver   │   │
│  │         │    │         │    │                   │   │
│  └────┬────┘    └────┬────┘    └─────────┬─────────┘   │
│       │              │                     │              │
│       └──────────────┴─────────────────────┘              │
│              Internal Network                            │
│                                                          │
│  Ports exposed to host:                                  │
│  - app: 80 → 80:80                                       │
│  - mail: 25,143,587,993 → 25,143,587,993                │
└─────────────────────────────────────────────────────────┘
```

## Service Communication

### Nextcloud → Mail Server
- **SMTP Connection:** Via host network to `mail:25` or `mail:587`
- **IMAP Connection:** Via host network to `mail:143` or `mail:993`
- **Config File:** `config.php` sets `mail_domain` to `escardar.com`

### Database → Nextcloud
- **Connection:** Docker internal DNS (`db` hostname)
- **Database Name:** `nextcloud`
- **User:** `nextcloud`

## mail.env Configuration

**File Location:** `./mail.env`

```env
# SMTP Configuration
smtp_host=mail.escardar.com
smtp_port=25

# IMAP Configuration
imap_host=mail.escardar.com
imap_port=143

# Domain Settings
mail_domain=escardar.com
```

## Network Aliases

**Mail Service:**
- Internal: `mail`
- External: `mail.escardar.com`

## Port Mapping

| Container Port | Host Port | Service | Access |
|--------------|----------|---------|---------|---------|
| 25 | 25 | SMTP | External mail relay |
| 143 | 143 | IMAP | Client access |
| 587 | 587 | Submission | Authenticated submission |
| 993 | 993 | IMAPS | Secure client access |

## Integration Points

### Nextcloud Mail App
- **Purpose:** Webmail client
- **Configuration:** Connects via `mail.escardar.com`
- **Protocols:** IMAP (143), IMAPS (993)

### System Emails
- **Domain:** `escardar.com`
- **Sender:** `noreply@escardar.com` (typical)
- **Relay:** Via mail container SMTP

## Known Configuration Issues

### SSL/TLS
- **Current:** `SSL_TYPE=` is empty
- **Implication:** Connections are plaintext on ports 25, 143
- **Mitigation:** Ports 587, 993 for secure access

### Docker Networking
- **Issue:** `PERMIT_DOCKER=host` allows mail from host
- **Impact:** Any host process can relay mail
- **Note:** Acceptable for development; production should restrict

## User Management

**Setup Script:** `setup.sh`
**Format:** `user|password|user@escardar.com`
**Note:** Users created via `docker exec escardar_mail setup email add <user@domain>`

## Volumes Data Flow

```
┌──────────────────────────────────────────────────┐
│              Mail Data Flow                       │
│                                                   │
│  Incoming Mail → Port 25 → docker-mailserver       │
│                    ↓                              │
│              /var/mail/<domain>/<user>/new/        │
│                    ↓                              │
│         IMAP Access via Port 143/993             │
│                    ↓                              │
│         Nextcloud Mail App                      │
└──────────────────────────────────────────────────┘
```