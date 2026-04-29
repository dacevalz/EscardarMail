# Feature Research

**Domain:** Branded Nextcloud (Escardar Mail)
**Researched:** 2026-04-29
**Confidence:** HIGH

## Feature Landscape

### Table Stakes (Users Expect These)

Features users assume exist. Missing these = product feels incomplete.

| Feature | Why Expected | Complexity | Notes |
|---------|--------------|------------|-------|
| Custom Logo (Header, Login) | Fundamental for white-label identity | LOW | Managed via `occ theming:config logo` |
| Custom Favicon | Standard browser branding | LOW | Managed via `occ theming:config favicon` |
| Brand Colors (Primary/Background) | Visual alignment with brand palette | LOW | Managed via `occ theming:set-color` |
| Instance Name ("Escardar Mail") | Replaces "Nextcloud" as the product name | LOW | Managed via `occ theming:config name` |
| Custom Login Page (Bg/Slogan) | First impression for the user | LOW | Managed via theming config |
| Sender Domain Configuration | Ensures emails come from `@escardarmail.com` | LOW | `mail_domain` parameter in `config.php` |
| System Email Rebranding | Prevents "Nextcloud" from appearing in automated emails | MEDIUM | Requires customizing system email templates |

### Differentiators (Competitive Advantage)

Features that set the product apart. Not required, but valuable.

| Feature | Value Proposition | Complexity | Notes |
|---------|-------------------|------------|-------|
| Mail-First Home Page | Instant access to email, removes "cloud" generic feel | LOW | Use `occ config:app:set core defaultpage` |
| Brand-Aligned Mail UI | Makes the mail client feel proprietary and premium | MEDIUM | Requires "Custom CSS" app and CSS variables |
| Branded Pre-Login Landing Page | Establishes identity before authentication | MEDIUM | Custom HTML/CSS landing page implementation |
| Curated App Experience | Simplifies UX by hiding non-mail related apps | LOW | Admin-level app disabling |
| Branded Welcome Onboarding | Professional welcoming sequence for new users | MEDIUM | Custom welcome email + landing page |

### Anti-Features (Commonly Requested, Often Problematic)

Features that seem good but create problems.

| Feature | Why Requested | Why Problematic | Alternative |
|---------|---------------|-----------------|-------------|
| Modifying Core Logic | Desire for custom mail features | Breaks updates; maintenance nightmare | Stick to existing Nextcloud app capabilities |
| Custom Mail Engine | To "own" the mail stack | Extreme complexity; overkill for branding | Use and style the official Nextcloud Mail app |
| Hard-coded CSS overrides | Faster "pixel perfect" results | Brittle; breaks on Nextcloud updates | Use Nextcloud CSS variables |

## Feature Dependencies

```
[Mail-First Home Page]
    └──requires──> [Nextcloud Mail App]

[Brand-Aligned Mail UI]
    └──requires──> [Custom CSS App]

[System Email Rebranding]
    └──requires──> [SMTP Configuration]

[Branded Welcome Onboarding] ──enhances──> [System Email Rebranding]
```

### Dependency Notes

- **Mail-First Home Page requires Nextcloud Mail App:** The target page must exist for it to be set as default.
- **Brand-Aligned Mail UI requires Custom CSS App:** To inject brand-specific styles into the Mail app without modifying core files.
- **System Email Rebranding requires SMTP Configuration:** Email templates only matter if the system can actually send branded mail.

## MVP Definition

### Launch With (v1)

Minimum viable product — what's needed to validate the concept.

- [ ] Custom Logo/Favicon/Colors — Fundamental white-labeling
- [ ] Instance Name "Escardar Mail" — Basic identity
- [ ] Sender Domain Configuration — Professional email delivery
- [ ] Mail-First Home Page — Primary differentiator for "Mail-first" brand

### Add After Validation (v1.x)

Features to add once core is working.

- [ ] System Email Rebranding — Removing remaining "Nextcloud" mentions from emails
- [ ] Brand-Aligned Mail UI — Visual polish of the email client
- [ ] Curated App Experience — Reducing UI clutter

### Future Consideration (v2+)

Features to defer until product-market fit is established.

- [ ] Branded Pre-Login Landing Page — Full proprietary feel
- [ ] Branded Welcome Onboarding — High-touch user experience

## Feature Prioritization Matrix

| Feature | User Value | Implementation Cost | Priority |
|---------|------------|---------------------|----------|
| Table Stakes (Logo/Color/Name) | HIGH | LOW | P1 |
| Mail-First Home Page | HIGH | LOW | P1 |
| Sender Domain | HIGH | LOW | P1 |
| System Email Rebranding | MEDIUM | MEDIUM | P2 |
| Brand-Aligned Mail UI | MEDIUM | MEDIUM | P2 |
| Curated App Experience | LOW | LOW | P2 |
| Custom Landing Page | MEDIUM | MEDIUM | P3 |

**Priority key:**
- P1: Must have for launch
- P2: Should have, add when possible
- P3: Nice to have, future consideration

## Sources

- Nextcloud Official Documentation (Theming & Configuration)
- Context7 Nextcloud Documentation Search
- Industry standards for white-labeled SaaS products

---
*Feature research for: Escardar Mail*
*Researched: 2026-04-29*
