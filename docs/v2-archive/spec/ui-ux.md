# UI/UX & Design System Specification

## Technology Stack
*   Tailwind CSS v4 (No Bootstrap/jQuery permitted).
*   Alpine.js for lightweight interactions.
*   Laravel Blade for server-side rendering (SPA frameworks are banned).

## Design System Core
1.  **Themes:** Full support for semantic light and dark modes. The Theme Manager allows admins to define institutional palettes that guarantee contrast ratios.
2.  **Accessibility:**
    *   WCAG 2.2 AA compliance required on all applicant-facing pages.
    *   Full keyboard navigability.
    *   Screen-reader optimized ARIA labels.
3.  **Responsive Design:** Mobile-first logic. The application wizard must be perfectly usable on low-end mobile devices on 3G connections.
4.  **UX Patterns:**
    *   Progressive disclosure on long forms.
    *   Loading skeletons for async calls.
    *   Inline validation immediately on field blur.
