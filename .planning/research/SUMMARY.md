# Project Research Summary

**Project:** Escardar Mail
**Domain:** White-labeled Cloud Mail Platform (Nextcloud)
**Researched:** 2026-04-29
**Confidence:** HIGH

## Executive Summary

Escardar Mail is a professional white-label implementation of Nextcloud Hub, designed to feel like a proprietary mail service rather than a generic cloud suite. The goal is to achieve a high-fidelity brand identity while maintaining the ability to update the underlying platform without losing customizations.

The recommended approach is a **Layered Branding System**. By combining the official Nextcloud Theming App for baseline identity with a custom-built Branding App for deep visual overrides and DOM manipulation, we can achieve a 100% white-label experience. This strategy ensures that core files remain untouched, eliminating the risk of "branding regression" during system updates.

The primary risk is "Core Hacking"—the temptation to modify source code for pixel-perfect results. This is strictly forbidden. Mitigation involves utilizing CSS variables and JavaScript MutationObservers to inject branding and replace remaining "Nextcloud" strings dynamically.

## Key Findings

### Recommended Stack

The stack focuses on stability and update-safety, leveraging the official Nextcloud ecosystem.

**Core technologies:**
- **Nextcloud Hub (Latest):** Base Platform — Provides all required cloud capabilities and the official theming engine.
- **Nextcloud Theming App:** Base Branding — Official method for changing Name, Logo, and Primary Color with high compatibility.
- **Custom Branding App:** Deep Visuals — Necessary for overriding CSS variables and injecting custom styles/scripts without modifying core files.
- **`occ` CLI:** Automation — Essential for programmatic asset replacement and configuration.

### Expected Features

The feature set prioritizes a "Mail-First" identity to differentiate the product from a standard cloud drive.

**Must have (table stakes):**
- **Fundamental Branding** (Logo, Favicon, Colors, Instance Name) — Essential for white-label identity.
- **Sender Domain Configuration** — Ensures professional email delivery from `@escardarmail.com`.
- **Mail-First Home Page** — Primary differentiator; removes the "generic cloud" feel.

**Should have (competitive):**
- **System Email Rebranding** — Removes "Nextcloud" from automated system emails.
- **Brand-Aligned Mail UI** — Visual polish of the email client via custom CSS variables.
- **Curated App Experience** — Simplifies UX by hiding non-mail related apps.

**Defer (v2+):**
- **Branded Pre-Login Landing Page** — Full proprietary feel before authentication.
- **Branded Welcome Onboarding** — High-touch professional welcoming sequence.

### Architecture Approach

The architecture follows a **Layered Branding System** that treats the brand as an extension rather than a modification.

**Major components:**
1. **Nextcloud Core** — Provides functionality and base UI templates.
2. **Theming App (Official)** — Handles baseline identity (name, colors, logos) via DB/UI.
3. **Escardar Branding App (Custom)** — Injects deep identity: CSS variable overrides and JS-based string replacements.
4. **Static Asset Store** — Centralized storage for high-resolution SVG brand assets.

### Critical Pitfalls

1. **Modifying Core Source Code** — Avoid by strictly using the `theming` app and a custom branding app in the `/apps` directory.
2. **CSS Variable Leakage** — Avoid by auditing the UI across all apps and using `:root` overrides with `!important` only where strictly necessary.
3. **Branding Regression on Update** — Avoid by ensuring no files in `/core` or `/lib` are ever edited.
4. **Asset Path Errors** — Avoid by using absolute paths or `occ` config tools for logo/favicon registration.

## Implications for Roadmap

Based on research, suggested phase structure:

### Phase 1: Base Identity & Environment
**Rationale:** Establish the functional foundation and basic identity before adding complexity.
**Delivers:** A working Nextcloud instance with basic branding and professional email delivery.
**Addresses:** Custom Logo/Favicon/Colors, Instance Name, Sender Domain.
**Avoids:** Core hacking (enforce "No Core Edits" from day one).

### Phase 2: Deep Brand Integration
**Rationale:** Transition from "themed Nextcloud" to "Escardar Mail" using the custom branding layer.
**Delivers:** The custom Branding App with CSS variable overrides and JS string replacements.
**Uses:** Custom Branding App, CSS Variables, JS MutationObserver.
**Implements:** Mail-First Home Page, Brand-Aligned Mail UI.

### Phase 3: Professional Polish
**Rationale:** Refine the user experience and eliminate remaining brand leakage.
**Delivers:** A fully curated, professional white-label experience.
**Addresses:** System Email Rebranding, Curated App Experience.
**Avoids:** Cache invalidation issues (implement CSS versioning).

### Phase Ordering Rationale

- **Dependency-Driven:** Base environment and sender domain must be active before branding can be validated.
- **Risk-Mitigated:** By implementing the Custom Branding App (Phase 2) after the base setup, we can isolate visual bugs from system configuration issues.
- **UX-Focused:** Moving from basic identity $\rightarrow$ deep visuals $\rightarrow$ polish ensures the most critical "Table Stakes" are met first.

### Research Flags

Phases likely needing deeper research during planning:
- **Phase 3:** System Email Rebranding (Need to locate and verify the template structure in the latest Nextcloud version).

Phases with standard patterns (skip research-phase):
- **Phase 1:** Baseline Nextcloud setup and official theming are well-documented.
- **Phase 2:** CSS variable overriding is a standard Nextcloud development pattern.

## Confidence Assessment

| Area | Confidence | Notes |
|------|------------|-------|
| Stack | HIGH | Verified via official Nextcloud developer documentation. |
| Features | HIGH | Based on industry standards for white-labeled SaaS. |
| Architecture | HIGH | Layered approach is the gold standard for update-safe NC mods. |
| Pitfalls | MEDIUM | Based on community consensus; needs verification during audit. |

**Overall confidence:** HIGH

### Gaps to Address

- **Email Template Location:** The exact path and format of system email templates for rebranding need validation during Phase 3 planning.
- **CSS Specificity Audit:** A comprehensive list of "leaky" CSS selectors in the Mail app needs to be compiled during implementation.

## Sources

### Primary (HIGH confidence)
- Nextcloud Official Documentation — Theming, Configuration, and Developer Manual.
- `occ` command reference — Asset and config management.

### Secondary (MEDIUM confidence)
- Nextcloud Community Forums — Update issues and theming bugs.
- GitHub Issues — Identified "leaks" in core CSS.

---
*Research completed: 2026-04-29*
*Ready for roadmap: yes*
