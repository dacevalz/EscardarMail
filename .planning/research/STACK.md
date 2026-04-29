# Technology Stack

**Project:** Escardar Mail
**Researched:** 2026-04-29

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

```bash
# 1. Set base branding via OCC
php occ theming:config name "Escardar Mail"
php occ theming:config primary_color "#2C363F"
php occ theming:config logo /path/to/escardar_logo.png
php occ theming:config favicon /path/to/escardar_favicon.png

# 2. Deploy Custom Branding App
# Place the 'escardar_branding' app in /apps/
# The app should include a style.css that overrides:
# --color-primary: #2C363F;
# --color-background-plain: #F8F7E5;
# --color-primary-light: #E1E5F2;
# --color-text: #111111;
# --color-background-white: #FFFFFF;
```

## Sources

- Nextcloud Official Documentation (Theming section)
- Nextcloud Developer Manual (CSS variables and App styling)
- `occ` command reference
