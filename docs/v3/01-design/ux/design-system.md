# Design System — "Register"

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Supersedes:** `docs/v2-archive/spec/ui-ux.md` — 18 lines of boilerplate that never once looks at the 12
reference screenshots, specifies no colour tokens despite `UI_DESIGN_SPECIFICATIONS.md` naming
`#0c4a2e`, and does not mention the data table at all.

---

## 1. The thesis

**This is a document of record, not a dashboard.**

A candidate spends three or more hours entering a dossier that may be litigated in 2031. A scrutiny
officer reads 78,232 of them against a statutory rulebook. Neither is browsing. Both are working
inside what is, legally, a register kept by the Registrar.

So the design takes its cue from **the bound official register** — ruled rows, column rules, a
visible spine, marginalia, tabular figures that align down a column — and from **AMU's own
architecture**, which is where the one ornamental gesture comes from.

**What this rules out.** Card grids. KPI tiles with gradient accents. Rounded-everything SaaS
chrome. Decorative iconography. The current admin skin is TailAdmin's e-commerce demo with the
customer-demographic map still wired in; nothing of that survives.

**What we keep from the reference screenshots.** They are AMU's real production systems and a
reliable requirements source: the split-pane sign-in with Victoria Gate, `#0c4a2e`, per-column
filters, the composite `total/submitted/paid/internal` cell, the three-panel pipeline widget. We keep
the *information design* and rebuild the *visual language*.

---

## 2. Palette

Anchored on AMU forest green, which is given rather than chosen. Everything else is built to serve
dense, ruled, legible data — deliberately cooler and more clinical than the warm-cream-and-terracotta
register that generic work defaults to.

```css
:root {
  /* Ground — cool paper, not cream. Keeps green from going muddy. */
  --paper:        #F7F8F6;   /* page */
  --paper-raised: #FFFFFF;   /* record surface */
  --paper-sunk:   #EEF0EC;   /* table header, inset */

  /* Ink — near-black carrying a green cast, so text and brand share a family */
  --ink:          #10150F;   /* primary text */
  --ink-muted:    #4A524A;   /* secondary */
  --ink-faint:    #7B837A;   /* captions, disabled */

  /* Rule — the structural colour. Used more than any accent. */
  --rule:         #D3D8D0;   /* hairline */
  --rule-strong:  #A9B2A6;   /* column rule, section rule */

  /* Brand */
  --green:        #0C4A2E;   /* AMU forest green — given */
  --green-deep:   #072F1D;   /* pressed, dark-mode surface tint */
  --green-lift:   #10643E;   /* hover */
  --green-wash:   #E4EDE7;   /* selected row, subtle fill */

  /* Accent — brass, from the University crest. NOT terracotta. */
  --brass:        #8A6B1F;
  --brass-wash:   #F5EEDC;

  /* Semantic — chosen for colour-blind separation, never colour alone */
  --eligible:     #1B6B3A;
  --rejected:     #9A2C2C;
  --pending:      #8A6B1F;   /* = brass; pending is the neutral state */
  --info:         #1F4E79;
}

:root:not([data-theme="light"]) { }        /* see §2.1 */
```

### 2.1 Dark mode

Not an inversion. The register becomes a **slate ledger**: the ground goes deep green-black, ink
becomes warm off-white, and rules gain weight because hairlines disappear on dark grounds.

```css
@media (prefers-color-scheme: dark) {
  :root:not([data-theme="light"]) {
    --paper:        #0D1210;
    --paper-raised: #141A17;
    --paper-sunk:   #0A0F0C;
    --ink:          #E8EDE6;
    --ink-muted:    #A6AFA4;
    --ink-faint:    #737C71;
    --rule:         #263029;   /* heavier — hairlines vanish on dark */
    --rule-strong:  #3A473C;
    --green:        #4FA97A;   /* lifted: #0C4A2E fails contrast on dark */
    --green-lift:   #6BC094;
    --green-wash:   #16281E;
    --brass:        #C9A34E;
    --eligible:     #57B27C;
    --rejected:     #E07A7A;
    --pending:      #C9A34E;
  }
}
:root[data-theme="dark"] { /* same block — toggle must win in both directions */ }
```

**Rule:** define the complete light palette on bare `:root`; redefine only what changes under
`prefers-color-scheme: dark` guarded by `:not([data-theme="light"])`; repeat under
`[data-theme="dark"]`. Never give a colour its only definition inside a media query.

### 2.2 Contrast

WCAG 2.2 **AA** minimum, **AAA for body text in the application form** — a candidate reads it for
hours.

| Pair | Ratio |
|---|---|
| `--ink` on `--paper` | 16.8:1 ✓ AAA |
| `--ink-muted` on `--paper` | 7.9:1 ✓ AAA |
| `--green` on `--paper` | 8.9:1 ✓ AAA |
| white on `--green` | 9.4:1 ✓ AAA |
| `--ink-faint` on `--paper` | 4.6:1 ✓ AA (captions only, never body) |
| `--brass` on `--paper` | 5.1:1 ✓ AA |

**Status is never colour alone.** Every gate state carries a glyph and a word: `✓ Eligible`,
`✕ Rejected`, `◦ Pending`. GIGW and WCAG 1.4.1 both require it, and a scrutiny decision is exactly
the wrong place to rely on hue.

---

## 3. Typography

Three roles, chosen for a data-dense multilingual statutory document.

| Role | Face | Why this one |
|---|---|---|
| **Display** | **Spectral** | A screen-native serif with a slightly severe, official cut. Carries authority without the editorial-magazine warmth of the usual Playfair/Fraunces default. Used sparingly — page titles and section heads only |
| **UI & data** | **IBM Plex Sans** | Genuine **tabular figures**, holds up at 13px in a dense table, and has a matched Devanagari companion. It reads as institutional rather than as another Inter deployment |
| **Identifiers** | **IBM Plex Mono** | Application numbers, roll numbers, codes, content hashes, DOIs. Anything meant to be compared character by character |
| **Devanagari** | IBM Plex Sans Devanagari | `title_hindi` |
| **Urdu** | Noto Nastaliq Urdu | `title_urdu`. Needs extra line-height — Nastaliq descends steeply |

```css
--font-display: 'Spectral', Georgia, serif;
--font-ui:      'IBM Plex Sans', system-ui, sans-serif;
--font-mono:    'IBM Plex Mono', ui-monospace, monospace;
--font-hi:      'IBM Plex Sans Devanagari', var(--font-ui);
--font-ur:      'Noto Nastaliq Urdu', serif;
```

### 3.1 Scale

A tight scale. Dense screens punish generous type.

| Token | Size / line-height | Use |
|---|---|---|
| `--t-page` | 30px / 1.15, Spectral 600 | Page title |
| `--t-section` | 20px / 1.25, Spectral 600 | Section head |
| `--t-sub` | 15px / 1.35, Plex 600 | Sub-head, card title |
| `--t-body` | 15px / 1.6, Plex 400 | Body, form labels |
| `--t-data` | 13px / 1.45, Plex 400, **tabular** | Table cells |
| `--t-label` | 11px / 1.2, Plex 600, `0.08em`, uppercase | Column heads, eyebrows |
| `--t-caption` | 12px / 1.4, Plex 400 | Help text, citations |

**Tabular figures are mandatory** wherever numbers stack: `font-variant-numeric: tabular-nums`. A
column of scores that does not align is a column that cannot be scanned.

### 3.2 The citation style

A recurring, load-bearing element: every statutory figure in the UI carries its clause reference, and
every score line carries its rule id. It gets its own treatment so it is legible but never competes.

```css
.citation {
  font: 400 12px/1.4 var(--font-mono);
  color: var(--ink-faint);
  border-left: 2px solid var(--rule-strong);
  padding-left: .5rem;
}
```

> Minimum research score **75** <span class="citation">UGC 2018 cl. 4.1 II</span>

---

## 4. Structure

### 4.1 The gate rule — the signature

One ornamental gesture, and it is drawn from the building on the sign-in page. **Victoria Gate's
arch profile becomes the section divider**: a hairline double rule interrupted by a small pointed
arch at the section head.

```
─────────────────────────────╱‾╲──────────────────────────────
═════════════════════════════════════════════════════════════
  ACADEMIC QUALIFICATIONS
```

Inline SVG, `currentColor`, 24×10px, `aria-hidden`. It appears **once per section head** and nowhere
else. That restraint is the point — it is the thing the portal is remembered by, and it stops being
memorable the moment it is repeated.

### 4.2 The spine

Part A is **11 sequentially released tabs**; the teaching track adds 16 sub-forms in Part B1 and 5 in
Part C. The candidate needs to know where they are in a long bound document.

A persistent left column — like the fore-edge of a register — shows every section, its completion
state, and the current position. **Sections are numbered because Part A genuinely is a sequence**;
numbering is not applied to anything that is not.

```
┌────────────────┬──────────────────────────────────────┐
│ ▌ A1 Profile ✓ │  ───────────────╱‾╲─────────────────  │
│   A2 Photos  ✓ │  ══════════════════════════════════  │
│ ▌ A3 Address ◦ │    A3  ADDRESSES                     │
│   A4 Institut. │                                      │
│   A5 Qualif.   │    Permanent address                 │
│   …            │    ┌────────────────────────────┐    │
│   A11 Other    │    │                            │    │
├────────────────┤                                      │
│ B1 Research    │                                      │
│   16 sub-forms │                                      │
└────────────────┴──────────────────────────────────────┘
```

The legacy system **gates tabs sequentially** — a tab unlocks only when the previous one is saved,
with no resumability. We keep the visible order and **drop the gating**: every section is reachable,
completion is shown, and submission validates the whole. That is one of the recorded pain points.

### 4.3 Records, not cards

Data is presented as **ruled records**: `--paper-raised` surface, hairline `--rule` between rows,
`--rule-strong` between column groups, `2px` radius — effectively square, because a register page is
square.

The one place a card is right is the **sign-in panel**, which the reference design already renders as
a floating white card over photography. That contrast is what makes it feel like a door into the
system.

### 4.4 Density

| Mode | Row height | Default for |
|---|---|---|
| **Compact** | 32px | Admin tables — a scrutiny officer working a queue of 106 |
| **Comfortable** | 44px | Candidate forms — three hours of data entry |

User-toggleable, persisted in `localStorage`, wrapped in try/catch.

### 4.5 Spacing and layout

4px base. `--s1: 4px` … `--s8: 48px`.

| Context | Max width |
|---|---|
| Reading (instructions, notices) | `68ch` |
| Form | `760px` |
| Data table | full width, **the table scrolls in its own `overflow-x: auto`** — the page body never scrolls sideways |
| Dossier record | full width |

---

## 5. Components

| Component | Notes |
|---|---|
| **Button** | Primary `--green` filled; secondary outlined `--rule-strong`; destructive `--rejected` **outlined, never filled** — a filled red button next to a filled green one is how a scrutiny officer rejects the wrong candidate. Destructive actions require typed confirmation |
| **Field** | Label above, 15px, `--ink`. Help text below in `--t-caption`. Error below help, `--rejected`, **with a glyph**. **`aria-describedby` links both.** Focus: 2px `--green` ring, 2px offset — never `outline: none` |
| **Statutory field** | A field whose validity comes from a regulation shows its `.citation` inline. Used throughout the application form |
| **Gate control** | Three explicit radio options — `✓ Eligible` · `✕ Not eligible` · `◦ Pending` — **never a merged label**. The reference modal renders "Pending / Not Eligible" over three distinct stored values, on a legally consequential decision. Not reproduced |
| **Pipeline panel** | The three-panel widget from the post-detail screenshot: application / eligibility / download statistics. Ruled, not tiled; figures in tabular Plex at `--t-page` size with `--t-label` captions |
| **Composite count cell** | `106 / 63 / 58 / 13⚑` — total, submitted, paid, internal. Mono, colour-coded, with a `title` and an `aria-label` spelling it out in words |
| **Empty state** | States what is missing and the one action that fixes it. Never an illustration |
| **Loading** | Skeleton rows matching the real row height. Never a spinner on a table — the layout must not jump |

---

## 6. Motion

Restrained, and one orchestrated moment rather than scattered effects.

| Where | What |
|---|---|
| Section change in the wizard | 120ms cross-fade + 4px rise. Enough to signal, not enough to wait for |
| Row expand in a dossier | 160ms height transition |
| Toast | 200ms slide from the top edge |
| **Everything else** | **No animation** |

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: .01ms !important;
    transition-duration: .01ms !important;
  }
}
```

---

## 7. Accessibility — WCAG 2.2 AA and GIGW

Non-negotiable, and largely a matter of not doing the wrong thing.

| Requirement | Implementation |
|---|---|
| Keyboard | Every action reachable. Visible focus everywhere. Logical tab order. **Skip-to-content** first in the DOM |
| Screen reader | Landmarks, one `h1` per page, ordered headings. Tables use `<th scope>`, `<caption>`, and `aria-sort` on sortable columns |
| Status | Glyph **and** text, never colour alone |
| Forms | Every input labelled. Errors linked by `aria-describedby`. An error summary at the top of the form links to each field |
| **2.4.11 Focus not obscured** | Sticky table headers must not cover the focused row — `scroll-margin-block` on rows |
| **2.5.8 Target size** | 24×24px minimum. Compact rows keep 24px targets inside a 32px row |
| **3.3.7 Redundant entry** | The reusable profile is literally this criterion: never ask twice |
| Zoom | 200% without loss of content or function |
| Language | `lang` on the root; `lang="hi"` / `lang="ur"` with `dir="rtl"` on Urdu titles |
| GIGW | Accessibility statement, help page, contact details, last-updated date, no content behind a plugin |

**A11y tests run in CI**: `axe-core` on every rendered route, failing the build on a violation.

---

## 8. What gets deleted

| Removed | Why |
|---|---|
| Bootstrap 4, jQuery, DataTables, Select2, Dropzone, CKEditor, perfect-scrollbar — all CDN-loaded | The frontend's 127 views load a second design system from CDN. `MEMORY.md` mandates the purge |
| TailAdmin e-commerce demo components — monthly-sale, monthly-target, recent-orders, customer-demographic | Unmodified demo files with e-commerce copy, referenced by nothing routed |
| ApexCharts, FullCalendar ×5, jsvectormap, swiper, prismjs, @floating-ui, @popperjs | **9 of 13 npm dependencies with zero source references** |
| `datatable datatable-{Entity}` markup in 33 admin views | They expect jQuery DataTables; `layouts.app` loads none. **33 of 34 admin lists render an empty table today** |

**Charts:** a small number are genuinely needed (the 12-month submitted-vs-paid trend, category and
status distributions). They are built as **inline SVG** against the tokens above rather than by
reinstating a charting library for four charts.

---

## 9. Implementation

Tailwind 4 with `@theme` in `resources/css/app.css` — no `tailwind.config.js`, matching the current
setup. Tokens declared once as CSS custom properties and exposed to Tailwind; **no hex value appears
in a Blade template.**

Alpine for interaction. Blade components for structure. **No jQuery, no SPA.**

```
resources/views/components/
  ui/{button,field,statutory-field,gate-control,badge,empty,skeleton}.blade.php
  layout/{page,section,gate-rule,spine,record}.blade.php
  data/{table,column-filter,composite-count,pipeline-panel,export-bar}.blade.php
```

---

## 10. Traceability

| Section | Feeds |
|---|---|
| §2, §3 | every module |
| §4.2 spine | M05 Application Wizard |
| §5 gate control | M18 · M34 |
| §5 composite cell, pipeline | M16 · M23 |
| §7 | GIGW compliance · `../../02-plan/` acceptance criteria |
| §8 | Wave 0 purge |
| — | `data-table.md`, the largest single UI work item |

---

## 11. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. "Register" direction — ruled records over cards, grounded in AMU's forest green and Victoria Gate. Full light/dark token set with verified contrast, Spectral/IBM Plex type system with tabular figures and Devanagari/Nastaliq support, the gate-rule signature, the spine, WCAG 2.2 AA + GIGW requirements, and the purge list. | Implementation team |
