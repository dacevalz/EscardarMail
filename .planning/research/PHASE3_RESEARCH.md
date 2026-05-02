# Research: Phase 3 - Mail-First Experience

**Project:** Escardar Mail
**Phase:** 3 - Professional Polish
**Researched:** 2026-05-02

## Phase Goal

Refine the user experience by eliminating brand leakage in system emails and curating the app experience to emphasize mail-related functionality.

## Research Areas

### 1. System Email Rebranding

**Question:** How to customize system email templates to remove "Nextcloud" branding?

**Answer:** Nextcloud provides a template extension mechanism via custom apps.

**Implementation Pattern:**
1. Create a custom app (e.g., `escardar_mail`)
2. Create a PHP class that extends `OC\Mail\EMailTemplate`
3. Override `protected string $header` and/or `protected string $footer` properties
4. Register via `config/config.php` with `'mail_template_class'`

**Class Structure:**
```php
<?php
namespace OCA\EscardarMail;

use OC\Mail\EMailTemplate;

class EmailTemplate extends EMailTemplate
{
    protected string $header = <<<EOF
        <table align="center" class="wrapper" style="background: #2C363F;">
            <tr>
                <td align="center">
                    <img src="..." alt="Escardar Mail" />
                </td>
            </tr>
        </table>
    EOF;

    protected string $footer = <<<EOF
        <p style="color: #666; font-size: 12px;">
            &copy; 2026 Escardar Mail
        </p>
    EOF;
}
```

**Configuration:**
```php
// config/config.php
'mail_template_class' => 'OCA\\EscardarMail\\EmailTemplate',
```

**Interface Reference:**
- Base interface: `lib/public/Mail/IEMailTemplate.php`
- Implementation: `lib/private/Mail/EMailTemplate.php`

**Verification:** Need to confirm exact property names in current Nextcloud version during implementation.

### 2. Curated App Experience

**Question:** How to hide/disable non-mail apps from the navigation?

**Answer:** Use `occ app:disable` to disable unwanted apps.

**Commands:**
```bash
# Disable non-essential apps
occ app:disable files_external
occ app:disable photos
occ app:disable notes
occ app:disable tasks
occ app:disable calendar
occ app:disable contacts
occ app:disable talk
occ app:disable Deck

# Keep only mail essentials
occ app:enable mail
occ app:enable files
occ app:enable user_management
```

**Limitation:** Nextcloud does not have a built-in "hide" feature. Apps are either enabled (visible) or disabled (hidden). There is an open feature request (#42328) for hideable apps, but not yet implemented.

**Workaround:** Disable all non-essential apps. This creates a curated experience but removes them entirely from the server.

### 3. Mail-First Home Page

**Question:** How to make mail the default landing page?

**Answer:** Various approaches depending on desired behavior:

**Option A - Redirect via `.htaccess`:**
```apache
Redirect 301 /index.php /index.php/apps/mail/
```

**Option B - Override defaultapp.config:**
```bash
occ config:system set defaultapp --value "mail"
```

**Option C - Custom Branding App JS:**
Use the custom branding app to inject JavaScript that redirects to mail on load.

## Implementation Approach

### Task Breakdown

1. **Create custom email template class**
   - Location: `/apps/escardar_mail/lib/EmailTemplate.php`
   - Override header with brand logo
   - Override footer with copyright

2. **Configure mail_template_class**
   - Edit `config/config.php`
   - Add `'mail_template_class' => 'OCA\\EscardarMail\\EmailTemplate'`

3. **Disable non-essential apps**
   - Identify apps to disable
   - Run `occ app:disable` commands

4. **Set mail as default**
   - Configure `defaultapp` system setting

## Dependencies

- Phase 1: Base Identity completed
- Phase 2: Custom Branding App deployed

## Risks

| Risk | Likelihood | Mitigation |
|------|------------|-------------|
| Email template class incompatible with NC update | Medium | Test after every NC update |
| Disabling critical apps breaks functionality | Low | Document required apps |
| Redirect causes login loop | Low | Test thoroughly |

## Sources

- [Nextcloud Admin Manual - Email Configuration](https://docs.nextcloud.com/server/latest/admin_manual/configuration_server/email_configuration.html)
- [Nextcloud Portal - Customized Email Templates](https://portal.nextcloud.com/article/Branding/Customized-email-templates)
- [Nextcloud Admin Manual - Apps Management](https://docs.nextcloud.com/server/latest/admin_manual/apps_management.html)
- [GitHub Issue #42328 - Hideable apps](https://github.com/nextcloud/server/issues/42328)

## Follow-up during Planning

- Verify exact email template class structure in current NC version
- Determine complete list of apps to disable for "mail-first" experience
- Test redirect behavior for login loop edge cases