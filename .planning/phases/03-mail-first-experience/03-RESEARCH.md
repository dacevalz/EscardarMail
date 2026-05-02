# Phase 3: Mail-First Experience - Research

**Researched:** 2026-05-02
**Domain:** Nextcloud 34 navigation, CSS theming, app visibility management
**Confidence:** HIGH

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|------------------|
| EXP-01 | Mail app set as the default landing page upon login | `defaultapp` system config key; verified via NavigationManager.php source in NC34 repo |
| EXP-02 | Mail UI visually aligned with Escardar brand using custom CSS | `escardar_branding` app's `OCP\Util::addStyle()` mechanism; CSS injected post-login into authenticated pages |
| EXP-04 | Curated app list implemented (hiding non-mail related utilities) | `occ app:disable` for hard removal; CSS `li > a[href="/apps/APPID/"]` selector as fallback for UI-only hiding |
</phase_requirements>

---

## Summary

Phase 3 pivots the authenticated user experience from a generic cloud interface to a mail-first product. Three requirements must be satisfied: redirect the user to `/apps/mail` on every login (EXP-01), apply Escardar brand colors inside the Mail app UI (EXP-02), and reduce the navigation bar to only mail-relevant apps (EXP-04).

The technical surface for all three is small and well-understood. EXP-01 requires a single `occ config:system:set` command. EXP-02 extends the existing `escardar_branding` app's `boot()` method with a new CSS file targeting the authenticated-page DOM. EXP-04 combines `occ app:disable` for genuinely unneeded apps with CSS-based hiding for apps that cannot be disabled without breaking Mail (e.g., `files` is a Mail dependency).

**Primary recommendation:** Use `occ config:system:set defaultapp --value="mail"` for EXP-01, add `mail-overrides.css` to the `escardar_branding` app for EXP-02, and disable non-mail apps via `occ app:disable` for EXP-04.

---

## Standard Stack

### Core
| Library / Tool | Version | Purpose | Why Standard |
|----------------|---------|---------|--------------|
| Nextcloud `occ` CLI | NC34 (containerized) | Configure `defaultapp`, disable apps | Official, idempotent, no file edits |
| `escardar_branding` custom app | 1.0.0 (existing) | Inject CSS into authenticated pages | Already in place from Phase 2; established pattern |
| `OCP\Util::addStyle()` | NC34 | Load CSS files from custom app | Only reliable method for CSS injection in NC34 |
| Nextcloud Mail app | via `occ app:install mail` | Primary UX app | Official Nextcloud app, ID confirmed as `mail` |

### App Disable Targets (EXP-04)
| App ID | Name | Safe to Disable? | Notes |
|--------|------|-----------------|-------|
| `dashboard` | Dashboard | YES | Replace with `mail` as default |
| `photos` | Photos | YES | Not mail-related |
| `files_sharing` | File Sharing | CAUTION | May have mail attachment dependencies |
| `weather_status` | Weather Status | YES | Cosmetic only |
| `user_status` | User Status | YES | Not needed for mail-only UX |
| `activity` | Activity | YES | Not needed for mail-only UX |
| `files` | Files | NO — hide only | Mail app requires Files for attachment handling |
| `contacts` | Contacts | NO — hide only | Mail uses Contacts for address autocomplete |
| `calendar` | Calendar | NO — hide only | Mail integrates calendar invites |

**Installation (if Mail app not yet installed):**
```bash
docker exec -u www-data nextclaud-app-1 php occ app:install mail
docker exec -u www-data nextclaud-app-1 php occ app:enable mail
```

---

## Architecture Patterns

### Recommended File Structure (additions to existing app)
```
nextcloud_core/apps/escardar_branding/
├── css/
│   ├── login-overrides.css       # EXISTING — login page
│   └── mail-overrides.css        # NEW — authenticated Mail UI
├── lib/AppInfo/
│   └── Application.php           # MODIFY — add addStyle for mail-overrides
└── appinfo/
    └── info.xml                  # EXISTING — already has <prelogin/>
```

### Pattern 1: Setting the Default App (EXP-01)
**What:** Write `defaultapp = mail` into Nextcloud's system config via `occ`.
**When to use:** Any time you need a non-dashboard landing page for all users.
**Source confirmed:** `lib/private/NavigationManager.php` lines 455, 478 in NC34 source.

```bash
# Run inside the Nextcloud container as www-data
docker exec -u www-data nextclaud-app-1 php occ config:system:set defaultapp --value="mail"
```

**Verification:**
```bash
docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp
# Expected output: mail
```

**How the resolution works (from source):**
- `getDefaultEntryIds()` reads `config.getSystemValueString('defaultapp', 'dashboard,files')`
- It then checks whether each listed app ID exists in the registered navigation entries
- `mail` is the app ID registered by the Mail app's `appinfo/info.xml` `<navigations>` block
- First match wins; if `mail` is enabled, users land on `/apps/mail`

### Pattern 2: CSS Injection into Authenticated Pages (EXP-02)
**What:** Add a new CSS file to `escardar_branding` app and register it in `Application.php boot()`.
**When to use:** Any visual override needed on authenticated (post-login) pages.

```php
// lib/AppInfo/Application.php — boot() method, add one line:
\OCP\Util::addStyle(self::APP_ID, 'mail-overrides');
```

**Key selectors confirmed from NC34 source (`AppMenuEntry.vue`, `AppMenu.vue`):**

```css
/* NC34 App menu: li.app-menu-entry > a.app-menu-entry__link[href="/apps/APPID/"] */
/* No data-app-id attribute exists in NC34 — confirmed by AppMenuEntry.vue source */

/* Hide an app from the navigation bar */
.app-menu-entry__link[href*="/apps/files/"] {
    display: none !important;
}

/* Authenticated page background */
#body-user {
    background-color: var(--color-main-background) !important;
}

/* Mail app container (from mail.scss source) */
#mail-messages {
    /* message list overrides */
}

/* App navigation sidebar within Mail */
.app-navigation {
    background-color: #2C363F !important;
    color: #FFFFFF !important;
}
```

**CSS injection context:** `OCP\Util::addStyle()` in `boot()` fires on every authenticated page. The file will be present inside the Mail app and also globally. Use app-specific selectors (e.g., `body.app-mail` or scoped to `#mail-*` IDs) to avoid affecting other pages.

**Note on `body.app-mail`:** NC34 sets a `class="app-APPID"` on `<body>` when that app is active. Confirmed indirectly via NC core template behavior; use `body[data-requesttoken]` + `#content.app-mail` to scope safely if needed.

### Pattern 3: Disabling Non-Mail Apps (EXP-04)
**What:** Use `occ app:disable` to fully remove unwanted apps from all users.
**When to use:** App is genuinely unused and has no transitive dependencies.

```bash
docker exec -u www-data nextclaud-app-1 php occ app:disable dashboard
docker exec -u www-data nextclaud-app-1 php occ app:disable photos
docker exec -u www-data nextclaud-app-1 php occ app:disable weather_status
docker exec -u www-data nextclaud-app-1 php occ app:disable user_status
docker exec -u www-data nextclaud-app-1 php occ app:disable activity
```

**CSS-only hiding (for apps that cannot be disabled):**
```css
/* Confirmed reliable selector in NC30+ (data-app-id was removed in NC30, href is stable) */
/* Source: help.nextcloud.com/t/help-with-custom-css-to-hide-photos-app/201646 */
li > a[href*="/apps/files/"] {
    display: none !important;
}
li > a[href*="/apps/contacts/"] {
    display: none !important;
}
```

### Anti-Patterns to Avoid
- **Using `data-app-id` CSS selector:** Removed in Nextcloud 30. The `AppMenuEntry.vue` source in NC34 confirms the `<li>` only has class `app-menu-entry` with no ID attribute. Use `href` selectors instead.
- **Editing `config/config.php` directly in the container:** The container's `config.php` is the live database-connected config. Always use `occ config:system:set` to ensure proper escaping and cache invalidation.
- **Disabling `files` app:** The Mail app attachment system depends on `files`. Disabling it will break mail attachment functionality. Hide it from navigation via CSS instead.
- **Adding `mail-overrides.css` without updating `Application.php`:** CSS files are silently ignored unless registered via `addStyle()`. Phase 2 learned this lesson — `landing-overrides.css` was orphaned for exactly this reason (see 02-VERIFICATION.md line 46).
- **Scoping CSS globally without app-specific selectors:** CSS injected by `escardar_branding` runs on all authenticated pages. Always scope Mail UI overrides to Mail-specific selectors (`#mail-messages`, `.navigation-account`, `#app-content-vue`, etc.) to avoid visual regressions in admin settings or other apps.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Default landing page | PHP redirect middleware | `occ config:system:set defaultapp --value="mail"` | NavigationManager handles fallbacks, user preferences, and multi-app ordering correctly |
| App visibility | Custom app that intercepts navigation API | `occ app:disable` + CSS `href` selectors | Disabling is permanent and correct; CSS handles the visual layer |
| Mail UI theming | Overriding Mail app source files | `escardar_branding` CSS injection | Source edits break on Mail app updates; injection approach is update-safe |
| Authenticated CSS injection | Adding CSS to Nextcloud core | `OCP\Util::addStyle()` in boot() | Core edits are wiped by image rebuilds; the app volume mount is persistent |

**Key insight:** The `escardar_branding` app is mounted as a Docker volume (`./nextcloud_core/apps/escardar_branding:/var/www/html/apps/escardar_branding`), which means all changes to this app are immediately live in the container without any rebuild.

---

## Common Pitfalls

### Pitfall 1: Mail App Not Installed
**What goes wrong:** `occ config:system:set defaultapp --value="mail"` succeeds, but users still land on Dashboard or Files because `mail` is not in the navigation entry list.
**Why it happens:** `getDefaultEntryIds()` silently ignores any ID that is not present in `$this->entries`. The fallback `dashboard,files` kicks in.
**How to avoid:** Always run `occ app:enable mail` before setting it as default. Verify with `occ navigation:list` or by checking the app list.
**Warning signs:** User logs in and lands on Dashboard instead of Mail despite the config being set correctly.

### Pitfall 2: `data-app-id` Selector Does Nothing
**What goes wrong:** CSS rule `.app-menu-entry[data-app-id="photos"] { display: none }` is written but apps remain visible.
**Why it happens:** `data-app-id` was removed in NC30 (GitHub issue #48645, closed "not planned"). The NC34 `AppMenuEntry.vue` source confirms the `<li>` has no ID attribute.
**How to avoid:** Use `li > a[href*="/apps/photos/"]` instead.
**Warning signs:** Opening DevTools shows `<li class="app-menu-entry">` with no `data-*` attributes on the element.

### Pitfall 3: CSS Affecting All Authenticated Pages
**What goes wrong:** Mail sidebar color override spills into the Settings or Files app, creating visual inconsistency.
**Why it happens:** `escardar_branding` CSS is injected globally via `OCP\Util::addStyle()` in the `boot()` method.
**How to avoid:** Prefix all Mail-specific rules with selectors that only match when the Mail app is active. The `<body>` element receives a class like `app-mail` only on Mail pages — verify in DevTools at runtime.
**Warning signs:** Admin panel has unexpected dark sidebar; Files app has wrong background color.

### Pitfall 4: Disabling a Mail Dependency
**What goes wrong:** `occ app:disable files` runs, Mail app attachment functionality stops working (cannot attach files to emails).
**Why it happens:** The NC Mail app uses the Files subsystem for local attachment handling and cloud-attachment features.
**How to avoid:** Only disable apps in the confirmed-safe list (dashboard, photos, weather_status, user_status, activity). Hide `files`, `contacts`, and `calendar` via CSS only.
**Warning signs:** Mail compose window shows error when trying to attach a file; contacts autocomplete stops working.

### Pitfall 5: config.php in Project Root vs Container
**What goes wrong:** Developer edits `config.php` in the project root (`/c/Users/Admin/Documents/NextClaud/config.php`) expecting changes to take effect.
**Why it happens:** This file is a reference template. The live config lives in the container at `/var/www/html/config/config.php` (Docker named volume `nextcloud_data`).
**How to avoid:** All system config changes MUST go through `docker exec -u www-data nextclaud-app-1 php occ config:system:set`.
**Warning signs:** `occ config:system:get defaultapp` returns the old value after editing the project root `config.php`.

---

## Code Examples

Verified patterns from source code and established Phase 2 patterns:

### EXP-01: Set Mail as Default App
```bash
# Set the default app
docker exec -u www-data nextclaud-app-1 php occ config:system:set defaultapp --value="mail"

# Verify
docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp
```
Source: `nextcloud_core/lib/private/NavigationManager.php` lines 455, 478

### EXP-02: Register New CSS File in escardar_branding
```php
// nextcloud_core/apps/escardar_branding/lib/AppInfo/Application.php
public function boot(IBootContext $context): void {
    \OCP\Util::addStyle(self::APP_ID, 'login-overrides');  // EXISTING
    \OCP\Util::addScript(self::APP_ID, 'login-video');     // EXISTING
    \OCP\Util::addStyle(self::APP_ID, 'mail-overrides');   // NEW for Phase 3
}
```

### EXP-02: Mail Override CSS Structure
```css
/* nextcloud_core/apps/escardar_branding/css/mail-overrides.css */
/* Escardar Mail — Mail App Visual Overrides (NC34)
   Scope: authenticated pages only, Mail-specific selectors where possible
*/

/* ── App navigation sidebar (Mail left panel) ──── */
/* #app-navigation is the sidebar nav within the Mail app */
#app-navigation {
    background-color: #2C363F !important;
}

#app-navigation .app-navigation-entry > a,
#app-navigation .app-navigation-entry__title {
    color: #F8F7E5 !important;
}

/* ── Curated nav bar: hide non-mail apps ── */
/* Confirmed selector for NC30+ (href-based, not data-app-id) */
li > a[href*="/apps/files/"],
li > a[href*="/apps/contacts/"],
li > a[href*="/apps/calendar/"] {
    display: none !important;
}

/* ── Primary action button (compose, send) ── */
.button-vue--vue-primary {
    background-color: #2C363F !important;
    border-color: #2C363F !important;
    color: #FFFFFF !important;
}
```

### EXP-04: Disable Non-Mail Apps
```bash
# Safe to disable — no Mail dependency
docker exec -u www-data nextclaud-app-1 php occ app:disable dashboard
docker exec -u www-data nextclaud-app-1 php occ app:disable photos
docker exec -u www-data nextclaud-app-1 php occ app:disable weather_status
docker exec -u www-data nextclaud-app-1 php occ app:disable user_status

# Verify Mail is enabled
docker exec -u www-data nextclaud-app-1 php occ app:list | grep mail
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| `data-app-id` CSS selector for app hiding | `li > a[href*="/apps/APPID/"]` | NC30 | Old selector silently does nothing in NC34 |
| `appinfo/app.php` for CSS injection | `lib/AppInfo/Application.php` with `IBootstrap` | NC25+ | PSR-4 path required; Phase 2 already uses correct pattern |
| `defaultapp` in config.php manually | `occ config:system:set defaultapp` | Always was occ | occ ensures cache flush and avoids PHP syntax errors |

**Deprecated/outdated:**
- `data-app-id` attribute on nav entries: Removed in NC30. Do not use.
- Direct `appinfo/app.php` for bootstrap logic: Deprecated in favor of `IBootstrap`. Phase 2 already migrated.

---

## Open Questions

1. **Mail app version/installation state**
   - What we know: Mail app is not in `nextcloud_core/apps/` (not bundled with NC34 core — it's an installable app from the store)
   - What's unclear: Whether the Mail app is currently installed in the running Docker container
   - Recommendation: Plan 03-01 should start with `occ app:list | grep mail` and include an install step if needed

2. **`body.app-mail` class presence in NC34**
   - What we know: NC templates historically add `app-APPID` class to body; not directly verified in NC34 source
   - What's unclear: Whether this class is reliable for CSS scoping in the NC34 Vue-rendered layout
   - Recommendation: Scope Mail CSS to `#mail-*` IDs (confirmed in mail.scss) rather than body class as primary anchor

3. **App container name**
   - What we know: `docker-compose.yml` defines service name `app`; container name is derived from directory + service name
   - What's unclear: Exact container name (`nextclaud-app-1` assumed from Docker Compose v2 naming convention)
   - Recommendation: Verify with `docker ps` at plan execution start; use `docker compose exec app` as the safer alias

---

## Validation Architecture

### Test Framework
| Property | Value |
|----------|-------|
| Framework | Manual verification + `occ` CLI assertions |
| Config file | None — no automated test framework in project |
| Quick run command | `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp` |
| Full suite command | HTTP + DevTools visual inspection per plan verification tasks |

### Phase Requirements → Test Map
| Req ID | Behavior | Test Type | Automated Command | Infrastructure Exists? |
|--------|----------|-----------|-------------------|----------------------|
| EXP-01 | `defaultapp` set to `mail` in system config | config assertion | `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp` | YES — occ available |
| EXP-01 | User lands on `/apps/mail` after login | smoke/manual | Browser login flow | Manual only |
| EXP-02 | `mail-overrides.css` file exists and contains brand colors | file assertion | `grep -E "color: #2C363F" nextcloud_core/apps/escardar_branding/css/mail-overrides.css` | YES — grep |
| EXP-02 | Mail UI visually aligned | visual/manual | Browser inspection at `/apps/mail` | Manual only |
| EXP-04 | `dashboard` disabled | config assertion | `docker exec -u www-data nextclaud-app-1 php occ app:list \| grep -v dashboard` | YES — occ |
| EXP-04 | Navigation shows only mail-relevant apps | visual/manual | Browser inspection of top nav | Manual only |

### Wave 0 Gaps
None — no test framework needed beyond occ CLI and grep assertions already available.

---

## Sources

### Primary (HIGH confidence)
- `nextcloud_core/lib/private/NavigationManager.php` (NC34 source, read directly) — `defaultapp` config key, resolution logic
- `nextcloud_core/core/src/components/AppMenuEntry.vue` (NC34 source, read directly) — confirmed NO `data-app-id` attribute in NC34; only class `app-menu-entry` and `href` on the anchor
- `nextcloud_core/core/src/components/AppMenu.vue` (NC34 source, read directly) — app list populated from `loadState('core', 'apps')`
- `https://github.com/nextcloud/mail/blob/main/appinfo/info.xml` — Mail app ID is `mail`, navigation route is `mail.page.index`
- Phase 2 summaries and VERIFICATION.md — established patterns for CSS injection, PSR-4 requirements, docker exec occ pattern

### Secondary (MEDIUM confidence)
- `https://docs.nextcloud.com/server/stable/admin_manual/configuration_server/config_sample_php_parameters.html` — `defaultapp` parameter documented as comma-separated fallback list
- `https://help.nextcloud.com/t/help-with-custom-css-to-hide-photos-app/201646` — confirmed `li > a[href*="/apps/APP/"]` works in modern NC for hiding nav entries

### Tertiary (LOW confidence)
- Community posts about `body.app-APPID` class — not directly verified in NC34 template source; flagged as open question

---

## Metadata

**Confidence breakdown:**
- EXP-01 (defaultapp config): HIGH — verified from NavigationManager.php source code
- EXP-02 (CSS injection): HIGH — established pattern from Phase 2, source-confirmed
- EXP-04 (app disable + CSS hide): HIGH for `occ app:disable`, MEDIUM for CSS selectors (href approach confirmed by community but not NC34 source)
- Mail app ID: HIGH — confirmed from official GitHub `appinfo/info.xml`
- `data-app-id` removal: HIGH — confirmed from NC30 GitHub issue and NC34 AppMenuEntry.vue source

**Research date:** 2026-05-02
**Valid until:** 2026-06-02 (stable Nextcloud APIs; CSS selectors may shift on major version upgrades)
