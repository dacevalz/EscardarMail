# Plan: Phase 3 - Mail-First Experience

**Project:** Escardar Mail
**Phase:** 3 - Professional Polish
**Created:** 2026-05-02

## Phase Goal

Refinar la experiencia del usuario eliminando la filtración de marca en emails del sistema y curando la experiencia de apps para enfatizar la funcionalidad de correo.

## Deliverables

1. **Email Templates Rebranded** - Emails del sistema con branding Escardar
2. **Curated App Experience** - Solo apps esenciales visibles
3. **Mail as Default** - Mail como página de inicio

---

## Tasks

### Task 1: Create Custom Email Template Class

**Description:** Create PHP class to override system email templates with Escardar branding

**Commands:**
```bash
# Create app directory
mkdir -p /var/www/nextcloud/apps/escardar_mail/lib

# Create EmailTemplate.php
cat > /var/www/nextcloud/apps/escardar_mail/lib/EmailTemplate.php << 'EOF'
<?php
namespace OCA\EscardarMail;

use OC\Mail\EMailTemplate;

class EmailTemplate extends EMailTemplate
{
    protected string $header = <<<EOF
        <table align="center" class="wrapper" style="width: 100%; max-width: 600px; background: #2C363F;">
            <tr>
                <td align="center" style="padding: 20px;">
                    <img src="https://escardarmail.com/logo.png" alt="Escardar Mail" style="height: 40px;" />
                </td>
            </tr>
        </table>
    EOF;

    protected string $footer = <<<EOF
        <table align="center" class="wrapper" style="width: 100%; max-width: 600px;">
            <tr>
                <td style="padding: 20px; text-align: center; color: #666; font-size: 12px;">
                    <p>&copy; 2026 Escardar Mail. All rights reserved.</p>
                    <p style="font-size: 11px;">This is an automated message from Escardar Mail</p>
                </td>
            </tr>
        </table>
    EOF;
}
EOF
```

**Verification:** `cat /var/www/nextcloud/apps/escardar_mail/lib/EmailTemplate.php`

**Risks:**
- Class structure incompatible with Nextcloud update (Mitigation: Test after every NC update)

---

### Task 2: Configure mail_template_class

**Description:** Register custom email template class in Nextcloud config

**Commands:**
```bash
# Add to config/config.php
# Edit /var/www/nextcloud/config/config.php
# Add: 'mail_template_class' => 'OCA\\EscardarMail\\EmailTemplate',
```

**Verification:** `grep mail_template_class /var/www/nextcloud/config/config.php`

**Risks:**
- Syntax error breaks email delivery (Mitigation: Test with sample email)

---

### Task 3: Identify Apps to Disable

**Description:** Determine which apps to disable for mail-first experience

**Commands:**
```bash
# List all enabled apps
occ app:list --enabled
```

**Expected Apps to Keep:**
- mail (REQUIRED)
- files (REQUIRED - core)
- user_management (REQUIRED - admin)
- theming (REQUIRED - branding)
- settings (REQUIRED - user settings)

**Apps to Consider Disabling:**
- files_external
- photos
- notes
- tasks
- calendar
- contacts
- talk
- deck
- circles

---

### Task 4: Disable Non-Essential Apps

**Description:** Disable apps not needed for mail-first experience

**Commands:**
```bash
# Disable non-essential apps
occ app:disable photos
occ app:disable notes
occ app:disable tasks
occ app:disable calendar
occ app:disable contacts
occ app:disable talk
occ app:disable deck
occ app:disable circles
occ app:disable photos
```

**Verification:** `occ app:list --enabled`

**Risks:**
- Disabling wrong app breaks functionality (Mitigation: Keep critical apps enabled)

---

### Task 5: Set Mail as Default App

**Description:** Configure mail as the default landing page

**Commands:**
```bash
# Set default app
occ config:system set defaultapp --value "mail"

# Verify
occ config:system get defaultapp
```

**Alternative (if needed):**
```bash
# Add to .htaccess for redirect
# Edit /var/www/nextcloud/.htaccess
# Add: Redirect 301 /index.php /index.php/apps/mail/
```

**Verification:** Login and verify redirect to mail

**Risks:**
- Redirect causes login loop (Mitigation: Test thoroughly)

---

## Verification

After implementation, verify:

1. **Email Templates:**
   - Send test email from admin
   - Check that header/footer show Escardar branding

2. **App Experience:**
   - Login as regular user
   - Verify only mail and essential apps visible

3. **Default App:**
   - Login fresh session
   - Verify landing on mail app

---

## Dependencies

- Phase 1: Base Identity completed
- Phase 2: Custom Branding App deployed

## Notes

- Test all changes in staging before production
- Document any apps disabled for future reference
- Keep theming app enabled (required for branding)