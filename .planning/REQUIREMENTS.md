# Requirements: Escardar Mail

## v1 Requirements

### Branding (BRAND)
- [ ] **BRAND-01**: Custom Logo integrated in Header and Login page.
- [ ] **BRAND-02**: Custom Favicon implemented globally.
- [ ] **BRAND-03**: Brand color palette implemented using CSS variables:
  - `#F8F7E5` (Cream)
  - `#2C363F` (Dark Grey/Blue)
  - `#E1E5F2` (Light Blue/Grey)
  - `#111111` (Black)
  - `#FFFFFF` (White)
- [ ] **BRAND-04**: Instance name set to "Escardar Mail" across all UI components.
- [ ] **BRAND-05**: Custom Login page with branded background and slogan.
- [ ] **BRAND-06**: Sender domain configured to use `@escardarmail.com` for system communications.
- [ ] **BRAND-07**: System email templates rebranded to remove "Nextcloud" mentions.

### Experience (EXP)
- [ ] **EXP-01**: Mail app set as the default landing page upon login.
- [ ] **EXP-02**: Mail UI visually aligned with Escardar brand using custom CSS.
- [ ] **EXP-03**: Proprietary pre-login landing page implemented.
- [ ] **EXP-04**: Curated app list implemented (hiding non-mail related utilities).
- [ ] **EXP-05**: Branded welcome onboarding sequence for new users.

## v2 Requirements (Deferred)
(None — all requested features included in v1)

## Out of Scope
- **Core Logic Modification**: No changes to Nextcloud's underlying PHP business logic.
- **Custom Mail Engine**: No replacement of the official Nextcloud Mail app with a custom engine.
- **Hard-coded CSS**: No use of brittle `!important` overrides; must use CSS variables for update-safety.

## Traceability
(To be filled by Roadmap)
