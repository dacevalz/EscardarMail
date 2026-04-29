# Architecture Patterns: Escardar Mail Branding

**Domain:** Branded Nextcloud Instance
**Researched:** 2026-04-29

## Recommended Architecture: Layered Branding System

To ensure the "Escardar Mail" identity is consistent, professional, and—most importantly—update-safe, a **Layered Branding System** is recommended. This approach avoids modifying the Nextcloud core, treating branding as an extension rather than a modification.

### Component Boundaries

| Component | Responsibility | Communicates With |
|-----------|---------------|-------------------|
| **Nextcloud Core** | Provides all cloud functionality, routing, and base UI templates. | Theming App, Branding App |
| **Theming App** (Official) | Handles baseline identity: instance name, primary color, and basic logo uploads via UI/CLI. | Core UI, Database |
| **Escardar Branding App** (Custom) | Injects deep brand identity: specific HEX palette overrides, global string replacements ("Nextcloud" $\rightarrow$ "Escardar Mail"), and custom CSS. | Core UI, Browser DOM |
| **Static Asset Store** | Stores high-resolution logos, favicons, and brand-specific imagery. | Branding App, Theming App |

### Data Flow

1. **Configuration Flow**: Administrator $\rightarrow$ `occ` commands / Theming App $\rightarrow$ Nextcloud Database $\rightarrow$ Core UI.
2. **Styling Flow**: Branding App $\rightarrow$ CSS Variable Overrides $\rightarrow$ Browser Engine $\rightarrow$ Visual Render.
3. **Identity Correction Flow**: Branding App $\rightarrow$ JS MutationObserver $\rightarrow$ DOM $\rightarrow$ "Nextcloud" string replaced by "Escardar Mail".

## Patterns to Follow

### Pattern 1: CSS Variable Overriding
**What:** Instead of writing new CSS selectors for every UI element, the Branding App overrides the global CSS variables provided by Nextcloud.
**When:** To implement the brand color palette consistently across all core apps.
**Example:**
```css
:root {
    --color-primary: #2C363F;            /* Dark Grey/Blue */
    --color-background-plain: #F8F7E5;  /* Cream */
    --color-primary-text: #FFFFFF;      /* White */
    --color-primary-hover: #111111;     /* Black */
}
```

### Pattern 2: Non-Destructive String Replacement
**What:** Use a lightweight JavaScript snippet within the Branding App to scan the DOM for "Nextcloud" mentions that the Theming App misses.
**When:** To achieve a 100% white-label experience without hacking core translation files.
**Example:**
```javascript
// Simple conceptual replacement
document.body.innerHTML = document.body.innerHTML.replace(/Nextcloud/g, 'Escardar Mail');
// Note: In production, use a MutationObserver for dynamic content.
```

## Anti-Patterns to Avoid

### Anti-Pattern 1: Core Hacking
**What:** Modifying files inside the `/core` or `/lib` directories to change logos or text.
**Why bad:** These changes are wiped out immediately upon the next Nextcloud update, leading to "branding regression."
**Instead:** Use a custom app in the `/apps` directory.

### Anti-Pattern 2: CSS Specificity War
**What:** Using `!important` on hundreds of lines of CSS to force colors.
**Why bad:** Makes the UI brittle and hard to maintain when Nextcloud updates its DOM structure.
**Instead:** Override the core CSS variables (`--color-primary`, etc.).

## Scalability & Maintenance Considerations

| Concern | At Initial Setup | During Core Updates | For New Apps |
|---------|------------------|---------------------|--------------|
| **Branding** | Set via Theming + App | Preserved in `/apps` | Inherits via CSS Variables |
| **Strings** | Set via JS/Theming | Preserved in App | JS catches new mentions |
| **Assets** | Uploaded once | Preserved in App | Linked via App paths |

## Suggested Build Order

1. **Baseline Environment**: Deploy standard Nextcloud instance.
2. **Baseline Branding**: Execute `occ theming:config` for instance name and primary logo.
3. **Identity Layer**: Develop and install the `escardar_mail` custom branding app.
   - Register `style.css` for variable overrides.
   - Register `script.js` for string replacements.
4. **Asset Finalization**: Upload high-res assets to the branding app directory.

## Sources

- Nextcloud Official Documentation: `admin_manual/configuration_server/theming.md`
- Nextcloud Developer Manual: `admin_manual/basics/front-end/css.md`
- Nextcloud CSS Variable Reference: `admin_manual/html_css_design/css.md`
