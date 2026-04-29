<!-- GSD:project-start source:PROJECT.md -->
## Project

**Escardar Mail**

A full instance of Nextcloud, rebranded and recolored to create a distinct identity as "Escardar Mail". It maintains all standard Nextcloud capabilities while presenting a unique professional brand.

**Core Value:** A seamless and consistent white-label experience that transforms Nextcloud into the Escardar Mail brand.

### Constraints

- **Tech Stack**: Must stay compatible with the base Nextcloud architecture to allow for future updates.
- **Branding**: Strict adherence to the provided HEX color palette.
<!-- GSD:project-end -->

<!-- GSD:stack-start source:research/STACK.md -->
## Technology Stack

## Recommended Stack
### Core Framework
| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| Nextcloud Hub | Latest (2025/26) | Base Platform | Provides all required cloud capabilities and the official theming engine. |
### Branding & Theming
| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| Nextcloud Theming App | Built-in | Base Branding | Official method for changing Name, Logo, and Primary Color. High compatibility. |
| Custom Branding App | v1.0 (Custom) | Deep Visuals | Necessary for overriding CSS variables and injecting custom styles without modifying core files. |
| CSS Variables | Native | Palette Control | Nextcloud's native styling system allows for dynamic color updates via `--color-primary` etc. |
### Assets & Configuration
| Technology | Version | Purpose | Why |
|------------|---------|---------|-----|
| `occ` CLI | Native | Automation | Essential for programmatic asset replacement (logos, favicons) and configuration. |
| SVG | 1.1 | Brand Assets | Ensures logos and icons remain crisp across all resolutions. |
## Alternatives Considered
| Category | Recommended | Alternative | Why Not |
|----------|-------------|-------------|---------|
| Styling | Custom App | Core CSS Mod | Modifying core files breaks updates and is not maintainable. |
| String Replace | Theming App | Search & Replace | Global search/replace in source is high-risk and breaks updates. |
## Installation
# 1. Set base branding via OCC
# 2. Deploy Custom Branding App
# Place the 'escardar_branding' app in /apps/
# The app should include a style.css that overrides:
# --color-primary: #2C363F;
# --color-background-plain: #F8F7E5;
# --color-primary-light: #E1E5F2;
# --color-text: #111111;
# --color-background-white: #FFFFFF;
## Sources
- Nextcloud Official Documentation (Theming section)
- Nextcloud Developer Manual (CSS variables and App styling)
- `occ` command reference
<!-- GSD:stack-end -->

<!-- GSD:conventions-start source:CONVENTIONS.md -->
## Conventions

Conventions not yet established. Will populate as patterns emerge during development.
<!-- GSD:conventions-end -->

<!-- GSD:architecture-start source:ARCHITECTURE.md -->
## Architecture

Architecture not yet mapped. Follow existing patterns found in the codebase.
<!-- GSD:architecture-end -->

<!-- GSD:skills-start source:skills/ -->
## Project Skills

No project skills found. Add skills to any of: `.claude/skills/`, `.agents/skills/`, `.cursor/skills/`, or `.github/skills/` with a `SKILL.md` index file.
<!-- GSD:skills-end -->

<!-- GSD:workflow-start source:GSD defaults -->
## GSD Workflow Enforcement

Before using Edit, Write, or other file-changing tools, start work through a GSD command so planning artifacts and execution context stay in sync.

Use these entry points:
- `/gsd-quick` for small fixes, doc updates, and ad-hoc tasks
- `/gsd-debug` for investigation and bug fixing
- `/gsd-execute-phase` for planned phase work

Do not make direct repo edits outside a GSD workflow unless the user explicitly asks to bypass it.
<!-- GSD:workflow-end -->



<!-- GSD:profile-start -->
## Developer Profile

> Profile not yet configured. Run `/gsd-profile-user` to generate your developer profile.
> This section is managed by `generate-claude-profile` -- do not edit manually.
<!-- GSD:profile-end -->
