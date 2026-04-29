# Domain Pitfalls

**Domain:** Nextcloud Branding/White-labeling
**Researched:** 2026-04-29

## Critical Pitfalls

Mistakes that cause rewrites or major issues.

### Pitfall 1: Modifying Core Source Code
**What goes wrong:** Changes to CSS or PHP files in the `core` or `lib` directories.
**Why it happens:** Desire for a "perfect" white-label that the Theming app cannot achieve.
**Consequences:** All branding is lost upon update. Updating the server becomes a manual, error-prone process.
**Prevention:** Strictly use the `theming` app and a custom branding app.
**Detection:** Check if any files in the core directory have been modified (using `git status` or file checksums).

### Pitfall 2: Ignoring CSS Variable Specificity
**What goes wrong:** Custom colors not applying to all elements.
**Why it happens:** Nextcloud's internal CSS specificity is high in some areas.
**Consequences:** "Leaking" Nextcloud blue in certain buttons or menus.
**Prevention:** Use `:root` overrides with `!important` where necessary in the custom branding app.
**Detection:** Inspect elements in the browser and look for variables being overridden by more specific selectors.

## Moderate Pitfalls

### Pitfall 1: Asset Path Errors
**What goes wrong:** Broken logos or favicons after migration.
**Prevention:** Use absolute paths or the `occ` config tool to ensure paths are correct.

### Pitfall 2: Cache Invalidation
**What goes wrong:** Users seeing old branding after a change.
**Prevention:** Implement versioning for custom CSS files (e.g., `style.css?v=1.1`).

## Minor Pitfalls

### Pitfall 1: Logo Aspect Ratio
**What goes wrong:** Logos appearing stretched or clipped.
**Prevention:** Follow Nextcloud's recommended logo size (62x34 px) and use SVG for flexibility.

## Phase-Specific Warnings

| Phase Topic | Likely Pitfall | Mitigation |
|-------------|---------------|------------|
| Base Setup | Core modification | Enforce "No Core Edits" rule. |
| Color Palette | Variable leakage | Audit the UI across multiple apps (Files, Calendar, Talk). |
| Logo Integration | Imagick dependencies | Ensure `php-imagick` and SVG support are installed on the server. |

## Sources

- Nextcloud Community Forum (Update issues)
- Nextcloud GitHub Issues (Theming bugs)
