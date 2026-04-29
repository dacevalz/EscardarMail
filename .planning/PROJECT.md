# Escardar Mail

## What This Is

A full instance of Nextcloud, rebranded and recolored to create a distinct identity as "Escardar Mail". It maintains all standard Nextcloud capabilities while presenting a unique professional brand.

## Core Value

A seamless and consistent white-label experience that transforms Nextcloud into the Escardar Mail brand.

## Requirements

### Validated

(None yet — ship to validate)

### Active

- [ ] Replace all "Nextcloud" mentions with "Escardar Mail" across the user interface.
- [ ] Implement the brand color palette:
  - `#F8F7E5` (Cream)
  - `#2C363F` (Dark Grey/Blue)
  - `#E1E5F2` (Light Blue/Grey)
  - `#111111` (Black)
  - `#FFFFFF` (White)
- [ ] Integrate the Escardar Mail custom logo.
- [ ] Ensure all standard Nextcloud core features and apps remain fully functional after rebranding.

### Out of Scope

- Modification of Nextcloud core business logic — The project is strictly visual/branding.
- Development of new core features — Only uses existing Nextcloud capabilities.

## Context

- **Technical Ecosystem**: Based on Nextcloud (PHP, Vue.js, CSS).
- **Goal**: Create a "white-label" version of the software to establish a proprietary brand identity.

## Constraints

- **Tech Stack**: Must stay compatible with the base Nextcloud architecture to allow for future updates.
- **Branding**: Strict adherence to the provided HEX color palette.

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Full Nextcloud Base | User explicitly requested all standard capabilities be maintained. | — Pending |

## Evolution

This document evolves at phase transitions and milestone boundaries.

**After each phase transition** (via `/gsd-transition`):
1. Requirements invalidated? → Move to Out of Scope with reason
2. Requirements validated? → Move to Validated with phase reference
3. New requirements emerged? → Add to Active
4. Decisions to log? → Add to Key Decisions
5. "What This Is" still accurate? → Update if drifted

**After each milestone** (via `/gsd-complete-milestone`):
1. Full review of all sections
2. Core Value check — still the right priority?
3. Audit Out of Scope — reasons still valid?
4. Update Context with current state

---
*Last updated: 2026-04-29 after initialization*
