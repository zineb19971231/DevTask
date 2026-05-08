---
name: DevTrack Core
colors:
  surface: '#13131b'
  surface-dim: '#13131b'
  surface-bright: '#393841'
  surface-container-lowest: '#0d0d15'
  surface-container-low: '#1b1b23'
  surface-container: '#1f1f27'
  surface-container-high: '#292932'
  surface-container-highest: '#34343d'
  on-surface: '#e4e1ed'
  on-surface-variant: '#c7c4d7'
  inverse-surface: '#e4e1ed'
  inverse-on-surface: '#303038'
  outline: '#908fa0'
  outline-variant: '#464554'
  surface-tint: '#c0c1ff'
  primary: '#c0c1ff'
  on-primary: '#1000a9'
  primary-container: '#8083ff'
  on-primary-container: '#0d0096'
  inverse-primary: '#494bd6'
  secondary: '#c2c6d7'
  on-secondary: '#2b303d'
  secondary-container: '#444956'
  on-secondary-container: '#b4b8c8'
  tertiary: '#ffb783'
  on-tertiary: '#4f2500'
  tertiary-container: '#d97721'
  on-tertiary-container: '#452000'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#e1e0ff'
  primary-fixed-dim: '#c0c1ff'
  on-primary-fixed: '#07006c'
  on-primary-fixed-variant: '#2f2ebe'
  secondary-fixed: '#dee2f3'
  secondary-fixed-dim: '#c2c6d7'
  on-secondary-fixed: '#161b27'
  on-secondary-fixed-variant: '#424754'
  tertiary-fixed: '#ffdcc5'
  tertiary-fixed-dim: '#ffb783'
  on-tertiary-fixed: '#301400'
  on-tertiary-fixed-variant: '#703700'
  background: '#13131b'
  on-background: '#e4e1ed'
  surface-variant: '#34343d'
typography:
  h1:
    fontFamily: DM Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
    letterSpacing: -0.02em
  h2:
    fontFamily: DM Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  h3:
    fontFamily: DM Sans
    fontSize: 20px
    fontWeight: '700'
    lineHeight: 28px
    letterSpacing: '0'
  body:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
    letterSpacing: '0'
  body-strong:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: '0'
  label-mono:
    fontFamily: JetBrains Mono
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.02em
  caption:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
    letterSpacing: '0'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  sidebar_width: 240px
  navbar_height: 56px
  base_unit: 4px
  container_padding: 24px
  gutter: 16px
---

## Brand & Style

This design system is engineered for high-velocity development environments. The aesthetic is rooted in **Minimalism** and **Modern SaaS** principles, prioritizing information density and cognitive ease for technical users. It evokes a sense of "Developer Tooling" through precision, dark-mode-first interfaces, and a focus on functional clarity over decorative flair. 

The emotional response is one of controlled efficiency. By utilizing a subtle grain texture on deep primary backgrounds, the UI avoids the "hollow" feeling of flat dark themes, providing a tactile, high-end hardware feel akin to modern terminal emulators or premium code editors.

## Colors

The color palette is optimized for long-duration focus sessions. The primary background is a deep, desaturated charcoal that reduces eye strain, while surface cards use a slightly lifted navy-tinted gray to create a clear visual hierarchy.

Accent indigo is reserved for primary actions and active states to guide the eye without overwhelming the content. Status colors (Success, Warning, Danger) are saturated enough to stand out against the dark canvas, ensuring that system alerts and task priorities are immediately scannable.

## Typography

This design system employs a tiered typographic approach. **DM Sans** provides a modern, geometric feel for headings, ensuring that page titles and section headers feel authoritative. **Inter** handles the heavy lifting of task descriptions and data tables, chosen for its exceptional legibility at small sizes. 

For technical metadata, such as Git hashes, file paths, or keyboard shortcuts, **JetBrains Mono** is used to provide a clear distinction from narrative text, reinforcing the developer-centric nature of the tool.

## Layout & Spacing

The layout utilizes a **Fixed Grid** logic for global navigation and a **Fluid Grid** for content areas. The sidebar remains locked at 240px to ensure navigation remains a persistent anchor, while the top navbar maintains a slim 56px profile to maximize vertical screen real estate for task lists and Kanban boards.

A 4px base unit governs the spacing rhythm. Components and containers should favor 16px (4x) and 24px (6x) increments to maintain a compact, "pro-tool" density that developers expect. Large-scale layouts should prioritize horizontal alignment with the navbar's inner margins.

## Elevation & Depth

In this design system, depth is primarily conveyed through **Tonal Layers** and **Low-Contrast Outlines**. 

- **Level 0 (Base):** The primary background (#0D0F14) with a subtle grain texture.
- **Level 1 (Surface):** Cards and panels use #161B27 with a 1px border of #1E2535.
- **Level 2 (Overlays):** Modals and dropdowns use a slightly lighter surface or a subtle 10% indigo-tinted shadow to lift them off the canvas.

Shadows should be used sparingly. When necessary, they should be extra-diffused and near-black to avoid a "muddy" appearance on the dark background.

## Shapes

The shape language balances modern approachability with structured engineering. 

- **Cards & Containers:** Use an 8px (0.5rem) radius to define major content areas.
- **Buttons & Inputs:** Use a slightly tighter 6px radius to make them feel more like precise "tools" within the larger layout.
- **Status Badges:** Use a full pill shape (100px radius) to differentiate status indicators from clickable buttons or structural cards.

## Components

### Buttons
Primary buttons use the Indigo accent with white text and a 6px radius. Secondary buttons should use a ghost style with the standard border (#1E2535) and a subtle hover lift.

### Badges
- **Status:** Pill-shaped. 
    - *Todo:* Gray background, light gray text.
    - *In Progress:* Indigo background (low opacity), indigo text.
    - *Done:* Green background (low opacity), green text.
- **Priority:** Rectangular with 4px radius.
    - *Low:* Blue.
    - *Medium:* Amber.
    - *High:* Red.

### Cards
Cards are the primary container. They feature a #161B27 background and #1E2535 border. The subtle grain texture should be applied to the background level behind cards to create a sense of the cards "sitting" on a textured desk.

### Inputs
Search bars and task inputs use the surface color (#161B27) with a JetBrains Mono font for placeholder text to emphasize the "command-line" feel. Focus states must trigger a 1px indigo glow or border.

### Sidebar
The sidebar is purely functional, using the primary background (#0D0F14) to blend into the application frame, with active items indicated by a vertical indigo stripe on the left edge.