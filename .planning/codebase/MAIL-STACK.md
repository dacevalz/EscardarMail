# Mail Technology Stack

**Analysis Date:** 2026-05-02
**Focus:** Email Server Infrastructure

## Mail Server Software

**Container Image:**
- `mailserver/docker-mailserver:latest`
- Purpose: Complete mail server stack (SMTP + IMAP + Sieve)
- Container Name: `escardar_mail`
- Hostname: `mail`
- Domain: `escardar.com`

## Protocol Services

| Service | Port | Protocol | Purpose |
|---------|------|----------|---------|
| SMTP | 25 | SMTP | Mail relay/receive |
| IMAP | 143 | IMAP | Mail access (unencrypted) |
| Submission | 587 | SMTP-TLS | Mail submission from clients |
| IMAPS | 993 | IMAP-SSL | Secure mail access |

## Database Backend

**MariaDB:**
- Image: `mariadb:10.11`
- Purpose: Nextcloud database storage
- Connection: Docker internal network (`db` hostname)
- Volumes: `nextcloud_db:/var/lib/mysql`

## Volumes

| Volume | Mount Point | Purpose |
|--------|------------|--------|
| `maildata` | `/var/mail` | Mail storage |
| `mailstate` | `/var/mail-state` | Runtime state |
| `mailconfig` | `/tmp/docker-mailserver` | Configuration directory |
| `maillogs` | `/var/log/mail` | Log files |
| `nextcloud_data` | `/var/www/html` | Nextcloud app files |
| `nextcloud_db` | `/var/lib/mysql` | Database files |

## Mail Environment Variables

**Timezone:**
- `TZ=America/Bogota`

**Domain Configuration:**
- `HOSTNAME=mail`
- `DOMAINNAME=escardar.com`

**Security Settings:**
- `SSL_TYPE=` (empty - SSL terminated elsewhere)
- `SPOOFING_PROTECTION=relaxed`

**Feature Flags:**
- `ENABLE_QUOTAS=1` - Mailbox quotas
- `ENABLE_MANAGESIEVE=1` - Sieve script management
- `ENABLE_SIEVE=1` - Server-side filtering
- `PERMIT_DOCKER=host` - Allow mail from Docker host

## Network Configuration

**Docker Network:**
- Default bridge network
- Alias: `mail.escardar.com`
- Accessible to: Host on ports 25, 143, 587, 993

## Nextcloud Integration

**Base Image:**
- `nextcloud:latest`
- Ports: `80:80`

**Branding Apps:**
- `escardar_branding` - Custom branding overlay
- `escardar_landing` - Pre-login landing page

**Environment:**
- `MYSQL_HOST=db`
- `NEXTCLOUD_ADMIN_USER=admin`
- `NEXTCLOUD_ADMIN_PASSWORD` (from env)