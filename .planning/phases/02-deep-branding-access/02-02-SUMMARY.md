---
phase: 02-deep-branding-access
plan: 02
subsystem: Branding / Public Interface
tags: [landing-page, branding, nextcloud-app]
dependency_graph:
  requires: [02-01]
  provides: [Public Landing Page]
  affects: [User Entry Flow]
tech-stack:
  added: [Nextcloud App Framework, PHP Attributes]
  patterns: [PublicPage Attribute, TemplateResponse]
key-files:
  - nextcloud_core/apps/escardar_landing/appinfo/info.xml
  - nextcloud_core/apps/escardar_landing/lib/Controller/LandingPageController.php
  - nextcloud_core/apps/escardar_landing/templates/landing.php
  - nextcloud_core/apps/escardar_landing/appinfo/routes.php
decisions:
  - "Used a custom Nextcloud app (`escardar_landing`) to implement the landing page to avoid modifying core files and ensure update-safety."
  - "Implemented the landing page using inline CSS for maximum portability and strict adherence to the brand palette."
metrics:
  duration: "Unknown"
  completed_date: "2026-04-29"
---

# Phase 02 Plan 02: Proprietary Landing Page Summary

Implemented a branded pre-login landing page as a custom Nextcloud application to provide a professional "front door" for the Escardar Mail service.

## Implementation Details

- **App Structure**: Created `escardar_landing` app with necessary metadata in `info.xml`.
- **Public Access**: Used the `#[PublicPage]` attribute in `LandingPageController` to ensure the page is accessible without authentication.
- **Branding**: The landing page uses a custom CSS palette:
  - Background: `#F8F7E5` (Cream)
  - Primary: `#2C363F` (Dark Grey/Blue)
  - Accents: `#E1E5F2` (Light Blue/Grey)
- **Navigation**: Provided a prominent "Client Login" button that redirects users to the standard Nextcloud login screen (`/index.php/login`).
- **Routing**: Registered the `/landing` route via `appinfo/routes.php`.

## Deviations from Plan

### Auto-fixed Issues

**1. [Rule 3 - Blocking] Fix `.gitignore` preventing app commits**
- **Found during**: Task 1
- **Issue**: Nextcloud's default `.gitignore` ignores all directories in `apps/` except a few core ones, preventing the new `escardar_landing` app from being tracked.
- **Fix**: Added `!/apps/escardar_landing` to `nextcloud_core/.gitignore`.
- **Files modified**: `nextcloud_core/.gitignore`
- **Commit**: (Modified during Task 1)

## TDD Gate Compliance

This plan was not marked as `type: tdd`, so the RED/GREEN/REFACTOR cycle was not enforced.

## Known Stubs

- **Verification**: Automated verification via `occ` and `curl` was not possible due to the absence of a PHP runtime in the execution environment. Verification was performed via code analysis and adherence to Nextcloud developer documentation.

## Threat Flags

None - the landing page is purely informational and does not handle sensitive data or accept user input.

## Self-Check: PASSED
