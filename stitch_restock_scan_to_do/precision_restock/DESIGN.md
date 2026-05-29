---
name: Precision Restock
colors:
  surface: '#faf8ff'
  surface-dim: '#d9d9e6'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f2ff'
  surface-container: '#ededfa'
  surface-container-high: '#e7e7f4'
  surface-container-highest: '#e1e1ef'
  on-surface: '#191b24'
  on-surface-variant: '#434656'
  inverse-surface: '#2e303a'
  inverse-on-surface: '#f0f0fd'
  outline: '#737688'
  outline-variant: '#c3c5d9'
  surface-tint: '#004cec'
  primary: '#0044d3'
  on-primary: '#ffffff'
  primary-container: '#1e5bff'
  on-primary-container: '#ecedff'
  inverse-primary: '#b7c4ff'
  secondary: '#575f6a'
  on-secondary: '#ffffff'
  secondary-container: '#dce3f1'
  on-secondary-container: '#5d6570'
  tertiary: '#992f00'
  on-tertiary: '#ffffff'
  tertiary-container: '#c33e00'
  on-tertiary-container: '#ffebe5'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dce1ff'
  primary-fixed-dim: '#b7c4ff'
  on-primary-fixed: '#001551'
  on-primary-fixed-variant: '#0039b5'
  secondary-fixed: '#dce3f1'
  secondary-fixed-dim: '#c0c7d4'
  on-secondary-fixed: '#151c26'
  on-secondary-fixed-variant: '#404752'
  tertiary-fixed: '#ffdbd0'
  tertiary-fixed-dim: '#ffb59c'
  on-tertiary-fixed: '#390c00'
  on-tertiary-fixed-variant: '#832700'
  background: '#faf8ff'
  on-background: '#191b24'
  surface-variant: '#e1e1ef'
typography:
  headline-lg:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '700'
    lineHeight: 28px
  headline-md:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '700'
    lineHeight: 24px
  body-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-bold:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  xs: 8px
  md: 12px
  lg: 16px
  xl: 24px
  margin-mobile: 16px
  gutter: 12px
---

## Brand & Style
The design system focuses on high-utility, professional efficiency for minimarket inventory management. The brand personality is systematic, reliable, and functional, designed to minimize cognitive load during repetitive scanning and restocking tasks. 

The aesthetic is **Corporate Modern** with a strong emphasis on clarity. It utilizes a flat design language to ensure the interface remains performant on mobile devices while maintaining a high-quality, professional feel. Key visual drivers are generous whitespace, crisp line-work, and a strict adherence to a logic-driven color application.

## Colors
This design system uses a logic-based color palette to differentiate between interactive actions and structural information.

- **Primary Blue (#1E5BFF):** Reserved for primary actions, active navigation states, and critical "Scan" triggers.
- **Secondary Light Blue (#EAF1FF):** Used as a subtle background for highlighted list items, badges, or container backgrounds to separate content sections without adding visual weight.
- **Text Hierarchy:** Primary Dark Gray is used for item names and counts to ensure maximum legibility. Secondary Medium Gray is used for metadata, SKU numbers, and timestamps.
- **Borders:** Thin, subtle strokes define boundaries, replacing shadows to maintain a clean, flat aesthetic.

## Typography
The system uses **Inter** for its exceptional legibility on small screens and neutral, professional tone. 

- **Headlines:** Limited to 18-20px to ensure long product names do not wrap excessively on mobile portrait views.
- **Body:** 16px is the standard for item counts and primary lists, while 14px is used for descriptions or form labels.
- **Labels:** Uppercase bold labels are used for status indicators (e.g., "LOW STOCK") to provide immediate visual recognition.

## Layout & Spacing
The layout follows a fluid 1-column model optimized for one-handed mobile use. 

- **Grid:** Content is contained within a 16px side margin.
- **Rhythm:** An 8px/12px/16px baseline is used for all internal component padding. Use 12px for spacing between related items in a list and 16px for vertical gaps between distinct functional blocks.
- **Safe Areas:** Ensure the fixed bottom navigation respects the mobile device's bottom notch/home indicator area, adding a minimum of 24px bottom padding.

## Elevation & Depth
In accordance with the flat design requirement, this design system avoids shadows. Depth is achieved through **Tonal Layers** and **Low-Contrast Outlines**.

- **Level 0 (Base):** White (#FFFFFF) background.
- **Level 1 (Cards/Containers):** Defined by a 1px solid border (#E5E7EB) or a secondary fill (#EAF1FF).
- **Dividers:** Used only when necessary to separate list items, using a 1px height with the border color.

## Shapes
The shape language is friendly yet structured. 

- **Standard Elements:** Buttons and input fields use a 0.5rem (8px) radius.
- **Prominent Containers:** Large functional blocks, specifically the "Camera/Scanner Card" and "Restock To-Do" list containers, use a **16px radius** to create a distinct visual hierarchy for high-priority areas.
- **Interactive Indicators:** Small circular shapes (pill-style) are reserved for numeric badges and status chips.

## Components

### Buttons
- **Primary:** Solid #1E5BFF with white text. 16px vertical padding, bold weight.
- **Secondary:** Solid #EAF1FF with #1E5BFF text. No border.

### Scanner Card
- A 16px rounded container with a thin #E5E7EB border. The viewfinder should be a high-contrast stroke area within the card.

### To-Do List Items
- Cards or rows with a 1px border. Use a 12px internal padding. 
- Include a 24x24px checkbox using the Primary Blue when checked.

### Bottom Navigation
- Fixed height of 64px. 
- Background: #FFFFFF with a 1px top border (#E5E7EB).
- Icons: 24px line-style. Active state uses Primary Blue; inactive uses Secondary Text Gray.
- Labels: 12px weight, positioned directly under icons.

### Input Fields
- Height of 48px. 1px border (#E5E7EB). Text starts with a 12px horizontal inset. Use Secondary Light Blue for the background when the field is focused.

### Iconography
- All icons must be **2px stroke width**, linear, and non-filled. Use consistent 24x24px bounding boxes.