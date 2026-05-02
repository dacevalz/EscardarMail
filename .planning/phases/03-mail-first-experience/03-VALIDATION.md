---
phase: 3
slug: mail-first-experience
status: draft
nyquist_compliant: false
wave_0_complete: false
created: 2026-05-02
---

# Phase 3 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | shell / occ CLI / docker exec |
| **Config file** | none — validation via occ commands and browser inspection |
| **Quick run command** | `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp` |
| **Full suite command** | `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp && docker exec -u www-data nextclaud-app-1 php occ app:list` |
| **Estimated runtime** | ~5 seconds |

---

## Sampling Rate

- **After every task commit:** Run `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp`
- **After every plan wave:** Run full suite command above
- **Before `/gsd:verify-work`:** Full suite must be green
- **Max feedback latency:** 10 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 3-01-01 | 01 | 1 | EXP-01 | cli | `docker exec -u www-data nextclaud-app-1 php occ config:system:get defaultapp` | ✅ | ⬜ pending |
| 3-01-02 | 01 | 1 | EXP-02 | file | `test -f nextcloud_core/custom_apps/escardar_branding/css/mail-overrides.css` | ❌ W0 | ⬜ pending |
| 3-01-03 | 01 | 1 | EXP-04 | cli | `docker exec -u www-data nextclaud-app-1 php occ app:list` | ✅ | ⬜ pending |

*Status: ⬜ pending · ✅ green · ❌ red · ⚠️ flaky*

---

## Wave 0 Requirements

- [ ] `nextcloud_core/custom_apps/escardar_branding/css/mail-overrides.css` — stub CSS file for EXP-02 mail branding
- [ ] `nextcloud_core/custom_apps/escardar_branding/lib/AppInfo/Application.php` — verify addStyle registration exists

*If none: "Existing infrastructure covers all phase requirements."*

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| Mail app loads as landing page after login | EXP-01 | Requires browser session | Log out and log back in; verify redirect to /apps/mail/ |
| Mail UI colors match Escardar brand | EXP-02 | Visual inspection required | Open Mail app; verify primary color matches brand palette |
| Non-mail apps hidden in app menu | EXP-04 | Requires browser inspection | Open app menu; verify only mail-relevant apps are shown |

---

## Validation Sign-Off

- [ ] All tasks have `<automated>` verify or Wave 0 dependencies
- [ ] Sampling continuity: no 3 consecutive tasks without automated verify
- [ ] Wave 0 covers all MISSING references
- [ ] No watch-mode flags
- [ ] Feedback latency < 10s
- [ ] `nyquist_compliant: true` set in frontmatter

**Approval:** pending
