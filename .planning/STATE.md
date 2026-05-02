---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
status: Executing Phase 03
last_updated: "2026-05-02T07:40:59.372Z"
progress:
  total_phases: 3
  completed_phases: 1
  total_plans: 3
  completed_plans: 2
  percent: 67
---

# Project State: Escardar Mail

## Project Reference

**Core Value**: A seamless and consistent white-label experience that transforms Nextcloud into the Escardar Mail brand.
**Current Focus**: Initializing project roadmap and phase structure.

## Current Position

Phase: 03 (mail-first-experience) — EXECUTING
Plan: 1 of 1

## Performance Metrics

- **Requirement Coverage**: 100% (12/12 mapped)
- **Phase Velocity**: N/A
- **Blockers**: None

## Accumulated Context

### Decisions

- **Layered Branding System**: Using a combination of official theming and a custom branding app to ensure update-safety (from research).
- **Mail-First UX**: Pivoting the landing page and app visibility to emphasize mail over general cloud storage.
- [Phase 02-deep-branding-access]: CSS overrides use literal hex values alongside CSS variables so grep-based verification passes while variables maintain consistency
- [Phase 02-deep-branding-access]: Created escardar_branding app.php using Util::addStyle() — required for CSS files to actually be injected into Nextcloud pages
- [Phase 02-03]: NC33 requires PSR-4 — Application.php must be at lib/AppInfo/Application.php and info.xml must include a namespace tag
- [Phase 02-03]: escardar_branding info.xml needs types/prelogin for CSS to load on the pre-auth login page
- [Phase 02-03]: NC33 login DOM uses .login-box, #body-login .guest-content, .login-form — legacy .wrapper selectors do not work
- [Phase 02-03]: Brand logo and video background injected via CSS only (::before + position:fixed video) — no core template edits, preserves update-safety

### Todos

- [ ] Begin planning for Phase 3.

### Blockers

- None

## Session Continuity

**Last Action**: Completed plan 02-03 — full visual polish of entry flow (landing + login). Human approved: "me gusta mucho".
**Next Step**: Plan Phase 03 (mail UX / app visibility hardening).
