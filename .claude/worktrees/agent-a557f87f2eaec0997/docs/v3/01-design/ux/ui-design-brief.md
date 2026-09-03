# UI Design Brief — the prompt to hand Claude

**Status:** ready to use · **Created:** 2026-08-28 · **Owner:** project sponsor
**Use with:** the `/design` canvas skill (multi-artboard visual design), or as the opening prompt of a
dedicated design session.

---

## How to use this file *(not part of the prompt)*

1. Open a **fresh** Claude session in this repository.
2. Type `/design` and paste **everything from "§0 — Your brief" to the end of the file.**
3. Attach or point at the twelve reference images in `docs/images/`. They are AMU's own production
   screens — a requirements source, not a style target.
4. Run it in **two passes**, exactly as §20 demands: the written direction first, artboards second.
   Do not let it skip to pixels.

Deliberately split into passes because the failure mode here is a beautiful sign-in page attached to
a generic admin table. The table is the product.

---

# §0 — Your brief

You are the design lead for **CareersPro**, the recruitment portal of **Aligarh Muslim University**.
You are replacing a live system that serves **55,050 registered candidates**, **78,232 applications**,
**1,045 advertisements** and **2,874 posts** — and that currently renders **33 of its 34 admin tables
empty** because it loads markup for a JavaScript library it never includes.

You are not decorating a CRUD app. You are designing **a statutory register**: a candidate spends
three or more hours entering a dossier that may be produced in court in 2031, and a scrutiny officer
reads tens of thousands of them against a Gazette-notified rulebook. Every screen you draw is
potentially an exhibit.

Design the whole product: the **candidate-facing portal** and the **staff administration console**,
including both dashboards. Make it beautiful. Beautiful here means *authoritative, dense, and
effortless to read at 13px* — not airy, not playful, not another SaaS dashboard.

---

## §1 — Read before you draw

| Source | What to take from it |
|---|---|
| `docs/v3/01-design/ux/design-system.md` | **The binding token system.** Palette, type, the spine, the gate-rule signature. Do not invent a second palette. |
| `docs/v3/01-design/ux/data-table.md` | The single largest UI work item. The 7-column dossier record and the gate control live here. |
| `docs/v3/01-design/ux/screens.md` | All twelve reference screens, plus the 13-step candidate journey. |
| `docs/v3/02-plan/M10-applicant-dashboard.md` | Everything the candidate dashboard must show — and the three things it is legally forbidden to show. |
| `docs/v3/02-plan/M23-analytics-reporting.md` | Everything the admin dashboard must show, with real production figures. |
| `docs/v3/01-design/security/security-model.md` §3.1 | The **13 roles**. The console is not one console; see §5. |
| `docs/images/*.png` | The system you are replacing. Keep the *information design*. Rebuild the *visual language* entirely. |

**Treat the screenshots as a requirements list, not a mood board.** They tell you a scrutiny officer
needs per-column filters, a composite `106 / 63 / 58 / 13⚑` cell, and a three-panel pipeline widget.
They also show a TailAdmin e-commerce demo skin with the customer-demographic map still wired in.
Take the first. Burn the second.

---

## §2 — The two audiences, and why they pull in opposite directions

| | **Candidate** | **Staff**|
|---|---|---|
| Session | One long sitting, 3+ hours, often once a year, often on a phone | All day, every day, keyboard-first, two monitors |
| Emotional state | Anxious. This is a job. Fees are non-refundable. | Fatigued. Row 4,000 of a queue of 78,232. |
| Failure mode to design against | Losing three hours of work; paying for a post they were never eligible for | Rejecting the wrong candidate because two states shared one label |
| Density | **Comfortable — 44px rows.** Generous targets, one decision per screen. | **Compact — 32px rows.** Maximum information per scroll. |
| What earns trust | Knowing exactly where they stand and what happens next | Knowing exactly which rule, which version, which date a figure came from |

**One design system, two densities, one honest voice.** The candidate surface is calmer and larger.
It is not a different product, and it is not "the friendly one" — a candidate reading a rejection
deserves the same precision a scrutiny officer gets.

---

## §3 — The direction: *Register*

**The thesis: this is a document of record, not a dashboard.**

Both audiences are working inside what is, legally, a register kept by the Registrar. So the design
takes its cue from the **bound official register** — ruled rows, column rules, a visible spine, a
margin for annotation, tabular figures that align down a column — and from **AMU's own
architecture**, which is where the single ornamental gesture comes from.

This direction is already chosen and it is not up for renegotiation. Your job is to execute it far
better than a written spec can, and to extend it into the surfaces it does not yet cover.

**Hard rule-outs.** Card grids. Gradient KPI tiles. Rounded-everything chrome. Decorative
iconography. Illustrated empty states. Donut charts. Any drop shadow doing work a hairline should do.

---

## §4 — Tokens you inherit (do not redesign these)

```css
/* Ground — cool paper, never cream. Keeps the green from going muddy. */
--paper: #F7F8F6;  --paper-raised: #FFFFFF;  --paper-sunk: #EEF0EC;
/* Ink — near-black with a green cast, so text and brand share a family */
--ink: #10150F;  --ink-muted: #4A524A;  --ink-faint: #7B837A;
/* Rule — the structural colour. Used more than any accent. */
--rule: #D3D8D0;  --rule-strong: #A9B2A6;
/* Brand — AMU forest green. Given, not chosen. */
--green: #0C4A2E;  --green-deep: #072F1D;  --green-lift: #10643E;  --green-wash: #E4EDE7;
/* Accent — brass, from the University crest. NOT terracotta. */
--brass: #8A6B1F;  --brass-wash: #F5EEDC;
/* Semantic — separable without hue */
--eligible: #1B6B3A;  --rejected: #9A2C2C;  --pending: #8A6B1F;  --info: #1F4E79;
```

Dark mode is **not an inversion** — the register becomes a **slate ledger**: ground `#0D1210`,
raised `#141A17`, ink `#E8EDE6`, green lifted to `#4FA97A` (the forest green fails contrast on dark),
and **rules gain weight**, because hairlines vanish on dark grounds. Full values in `design-system.md`
§2.1. Define the light palette on bare `:root`; redefine under
`@media (prefers-color-scheme: dark)` guarded by `:not([data-theme="light"])`; repeat under
`[data-theme="dark"]`. **Never give a colour its only definition inside a media query.**

**Type.** Spectral for display (a screen-native serif with an official cut — used only for page and
section titles). IBM Plex Sans for UI and data (genuine tabular figures, holds at 13px). IBM Plex
Mono for **identifiers** — application numbers, roll numbers, rule ids, content hashes, DOIs; anything
compared character by character. IBM Plex Sans Devanagari and Noto Nastaliq Urdu for `title_hindi`
and `title_urdu` (Nastaliq descends steeply — give it line-height and `dir="rtl"`).

`font-variant-numeric: tabular-nums` is **mandatory** wherever numbers stack.

**The citation style is load-bearing, not a footnote.** Every statutory figure in this product carries
its clause reference, and every score line carries its rule id. Design it properly — mono, 12px,
`--ink-faint`, 2px `--rule-strong` left border. You will place it hundreds of times. It must be
legible and must never compete:

> Minimum research score **75** · `UGC 2018 cl. 4.1 II`

---

## §5 — The console is thirteen consoles

There is no generic "admin". The same routes serve thirteen roles with **two orthogonal row-level
scopes** — ownership, and organisational-unit subtree. Design for this explicitly:

- A **Dean's-office** user sees only their faculty's local recruitment. **The page subtitle must say
  so**, in words: *"Faculty of Arts and 3 departments — local recruitment."* A scoped view that looks
  identical to an unscoped one is how someone reports the wrong number to a court.
- A **finance_admin** sees payments with **no PII beyond name and application number**. Design the
  redacted state as a first-class layout, not a table with holes in it.
- An **auditor** is read-only, university-wide. Every mutating control must have a considered
  read-only rendering — disabled controls with tooltips are not a design.
- A **committee_member** sees one committee's applications, only during its window. Design the
  before-window and after-window states.
- **Impersonation is always audited.** Design the impersonation banner. It should be impossible to
  forget you are inside someone else's session.

---

## §6 — The signature, and the discipline around it

**One ornamental gesture in the entire product**, and it comes from the building on the sign-in page.

**The gate rule.** Victoria Gate's arch profile becomes the section divider: a hairline double rule
interrupted by a small pointed arch at the section head. Inline SVG, `currentColor`, ~24×10px,
`aria-hidden`.

```
─────────────────────────────╱‾╲──────────────────────────────
═════════════════════════════════════════════════════════════
  A5  ACADEMIC QUALIFICATIONS
```

It appears **once per section head and nowhere else.** That restraint is the entire point. It is the
thing this portal is remembered by, and it stops being memorable the moment you repeat it.

**Two structural devices you should develop further** — these are where I want your invention:

1. **The margin.** A real register has a margin where the clerk annotates. Give the product a
   persistent narrow left gutter that carries **provenance**: which ruleset version governs this
   record, which snapshot number, which audit sequence, when it was frozen. Today that information
   is either invisible or buried in a modal. In the margin it becomes ambient — an officer never has
   to ask *"which rules apply here?"* Design it so it reads as annotation, not as a second sidebar.

2. **The spine.** The application form is 11 sequential sections in Part A, plus up to 16 sub-forms
   in Part B1 and 5 in Part C. The candidate needs to know where they are in a long bound document.
   A persistent left column — the fore-edge of the register — shows every section, its completion
   state, and the current position. **Sections are numbered because Part A genuinely is a sequence.**
   Do not number anything that is not a sequence.

   The legacy system **gates tabs**: a section unlocks only when the previous one is saved, with no
   resumability. **We keep the visible order and drop the gating.** Every section is reachable,
   completion is shown, submission validates the whole.

**Take exactly one risk beyond this**, and justify it in a sentence. Spend your boldness in one
place; keep everything around it quiet. Before you hand the design over, take one accessory off.

---

## §7 — Screens: the candidate portal

Design all of these. Starred screens get full fidelity; the rest can be tighter, but none may be a
placeholder.

| # | Screen | It must carry |
|---|---|---|
| C1 ★ | **Sign-in** | Split pane. Left: AMU roundel, *"Aligarh Muslim University / Office of the Controller of Examinations"*, a floating white card with the crest, **one** identifier field (`you@example.com — or your employee ID`), password with reveal, *Keep me signed in*, full-width `--green` button, *Need help signing in?*. Right: Victoria Gate photography, left edge a soft concave curve, dark translucent pill `● Victoria Gate · AMU Aligarh`. **Below 900px the photograph becomes a 30vh band above the card — never a background behind text.** Errors are generic: *"Those credentials don't match our records."* Lockout after 5 attempts states when to retry. |
| C2 | **Register + email verification** | The verification flow must **terminate**. Today every new user is logged out permanently and cannot verify. Design the "check your inbox", "link expired", and "already verified" states. |
| C3 ★ | **Browse vacancies** | Nine filters: post, department, category, pay level, location, dates, post type, track, subject. Full-text search. Filter state lives in the URL and is therefore shareable. Ruled result records — post title, organisational unit, pay level, vacancies, closing date with days remaining, fee. |
| C4 | **Advertisement detail** | Notification view, eligibility summary, relaxation breakdown, PDF download, child-post list. **Corrigenda are first-class dated objects, not edits** — design the corrigenda list. Show the frozen ruleset with its citation treatment: `Governed by ugc-teaching-2018@1 · frozen 2026-01-22`. |
| C5 ★ | **Eligibility pre-check** | Runs **before payment**. The legacy system evaluates age and experience at the payment deadline, so ineligible candidates pay first. This screen exists to stop someone spending ₹500 on a post they cannot hold. It must state each criterion, the candidate's value, the rule, and the verdict — with the clause. |
| C6 ★★ | **Application wizard — Part A** | The spine. 11 sections. Autosave with a truthful save indicator (*"Saved 14:32"*, not a spinner). Statutory fields carry inline citations. Field errors carry a glyph, sit below help text, and are linked by `aria-describedby`. An error summary at the top of the form links to each field. |
| C7 | **Research claims (Part B1)** | 13 evidence sub-tables, ~70 columns. **DOI/CrossRef lookup and UGC-CARE verification** — the loudest data-entry complaint in the sector is entering 20+ publications by hand with no import and no verification. Design the paste-a-DOI → resolved-record interaction, including the unresolved and the disputed states. |
| C8 | **Document vault** | Cropping to statutory specs — photo 350×450px, ratio 7:9, 10–100KB; signature and thumb impression 300×150px, ratio 6:3. **Inline viewer; no downloading loose PDFs.** Show self-attestation state. |
| C9 | **Preview & submit** | The full statutory print format, exactly as it will print. Submission is irreversible — the confirmation must earn that. |
| C10 | **Payment** | Category-based fee, exemptions (**PwD is the only fee exemption**), gateway hand-off, and — critically — the **pending / lost-callback** state. A lost callback must never look like a failure that invites a second payment. |
| C11 ★★ | **Applicant dashboard** | See §8. |
| C12 ★ | **Application detail + timeline** | See §8. |
| C13 | **Deficiency rectification** | Time-bound window, only the named sections re-open. See §8. |
| C14 | **Admit card / interview letter** | Download window enforced by dates. Design the "not yet open" and "window closed" states with the exact dates. |
| C15 | **Grievance** | SLA-tracked, named appellate authority, screening-stage only. |

---

## §8 — The candidate dashboard, in detail ★★

**This is the single most valuable screen in the product for a candidate**, because the system it
replaces locks irreversibly at payment with no rectification path at all. Design it as the answer to
three questions, in this order: *Do I need to do something right now? Where does each application
stand? What did they decide, and why?*

### 8.1 What it shows

**A. Action items — first, above everything.**
Not a notification list. A short list of things only this person can resolve, each with a deadline and
a direct link. When there are none, say so plainly and point at open vacancies.

**The deficiency banner is the highest-priority object in the product.** High contrast, brass ground,
a **live countdown**, the named sections, and one link:

> **Action needed — 5 days remaining.**
> Your experience certificate is illegible. Re-upload it in **Employment history**.
> Closes 19 Mar 2026, 5:00 pm.

Only the named sections are editable. A deficiency window is not a general re-open — design the
locked sections so that is obvious without being punitive.

**B. Applications — ruled records, not cards.**
Per application: post · advertisement · submitted date · payment state · **stage** · next action.
Give each record a **stage rail** — a vertical ruled progress spine, not a bar and not a stepper —
carrying the real states: Registered → Submitted → Paid → Under scrutiny → Screened → Interview →
Result. Passed states are ruled solid; the current state is marked; future states are hairline.

**C. Profile completeness.**
The profile is reusable across applications, so completing it once has compounding value. Show it as
a **filled column of the register** — a ruled vertical meter against the 11 sections of Part A — not
a donut, not a percentage ring. Percentage as a tabular figure alongside.

**D. The score breakdown — the transparency differentiator.**
Once scrutiny is cleared, the candidate sees **their own** breakdown, per line, with citations:

> Research papers, Column II — 5 sole-authored × 10 = **50** · `App. II Table 2 row 1`
> Book, national publisher — **10** · `row 2(a)`
> Project completed, Co-PI, ₹8 lakh — 5 × 0.50 = **2.5** · `row 4(b) · PI/Co-PI 50% each`
> **Provisional total 92.5** · threshold 75 · `cl. 4.1 II`

**E. Blocked scoring is stated, never approximated.**
Where a rule awaits ratification the dashboard **says so** and shows **no partial total** for it:

> **Impact-factor scoring is not applied.** It awaits Executive Council ratification of two points
> of interpretation. Your claims are recorded in full.

Design this as a considered, calm state. It is honest, and honesty here is the product's core claim.

### 8.2 What it must never show

Constrained by regulation, not by taste. The dashboard **never** reveals another candidate's score,
the cut-off, the shortlist size, or this candidate's rank. Design so those absences do not read as
gaps or as bugs — the layout should never leave a hole where a rank would go.

### 8.3 Worked content — use this, not lorem

Aisha Khan, two applications.

- **2599/2026/00412 — System Manager.** Timeline: Submitted 23 Jan · Paid 23 Jan · Under scrutiny
  11 Mar · **Deficiency raised 12 Mar**. Banner active, 5 days remaining. After rectification the
  timeline records **Rectified 14 Mar**, snapshot #2 is written, state returns to under scrutiny.
- **884/2026/01109 — Assistant Professor.** Scrutiny cleared. Score breakdown as above.

Gate states appear with **glyph and word** — `✓ Eligible`, `✕ Not eligible`, `◦ Pending` — never
colour alone. Three gates exist (scrutiny, written test, interview) but **only the gates active for
that post type are rendered.** On an interview-only post the written-test gate does not exist; do not
draw a greyed-out one.

---

## §9 — Screens: the administration console

**One structural change runs through everything.** Today staff work across *two* applications —
configuration in the ERP, operations in Manage Careers — moving between them to finish one task.
**We consolidate them.** Your navigation must absorb both without becoming a mega-menu.

| # | Screen | It must carry |
|---|---|---|
| A1 ★★ | **Master dashboard** | See §10. |
| A2 ★★ | **The data table** | See §11. The shared component behind ~34 list screens. |
| A3 ★★ | **Applicant dossier record** | See §11.2. Seven columns per row, collapsed to two. |
| A4 ★★ | **Gate control** | See §11.3. The most legally consequential control in the system. |
| A5 ★ | **Post detail + pipeline** | Two columns. Left: advertisement, post type, serial no, subject, slug, location, designation, organisational unit, appointment nature and tenure, pay and vacancies, **three dated markers** (opening, closing, payment last date), status. Right: the three-block pipeline — application / eligibility / download statistics. **Ruled, not tiled**; figures in tabular Plex at page-title size with label-caption beneath. Below: two dependent filters driving the dossier list. |
| A6 | **Advertisement list & detail** | Columns: ☐ · ID (default sort desc) · Title · Slug · Dated · Type (All/General/Local) · **Appointment nature** · Actions. Detail adds application statistics `Paid 710 / Submitted 765 / Total 954`, the frozen ruleset, corrigenda, and the child-post sub-grid with the composite count cell. |
| A7 ★ | **Scrutiny workbench** | Queue-based, **side-by-side claim vs. document**. This is where an officer spends the day: the claimed value, the uploaded evidence, and the rule, on one screen, with the gate control reachable without losing position in the queue. Design the keyboard path through a queue of 106 explicitly. |
| A8 | **Advertisement builder** | Advertisement → child posts, each a Designation in an Organisational Unit, linked to sanctioned strength. **30-day minimum window enforced** with breach alerting. Publish freezes the OU snapshot and the ruleset — design the publish confirmation to make that freeze legible. |
| A9 | **Post types configuration** | Seven rows. One row makes the application form, the selection pipeline, the generated documents and the physical submission office polymorphic. **The most consequential configuration screen in the system** — changes need confirmation, are audited, and the screen warns when a change affects **published** posts. Design that warning. |
| A10 | **Attendance sheet generator** | Advertisement → post → roll numbers present? → report type (All / Scrutiny eligible only / Interview eligible only) → with photo? → with barcode? → Generate. **Queued** with progress; a 106-row sheet with photographs is not a synchronous response. |
| A11 | **Bulk document generator** | Advertisement → post → Admit card / Interview letter → All / Eligible only / Interview eligible only → Generate. **A dry-run count first** — *"This will generate 58 admit cards."* Refused outside the admit-card window, with the dates shown. Re-running regenerates and supersedes; it never duplicates. |
| A12 ★ | **Reports & SLA monitor** | Reports grouped by category, statutory ones marked. Historical runs take an **as-at date** and state the reconstruction basis on the output. SLA monitor: advertisements approaching or breaching the **30-day window** and the **6-month cap**, with days remaining and the extension reference where one exists. |
| A13 | **Rules authoring (M20)** | Versioned, effective-dated rules with per-line citations. **`rules_admin` authors; `rules_verifier` activates, and must be a different person.** Design the two-person handoff as a visible state, not a permissions error. |
| A14 | **Audit trail** | A genuinely hash-chained append-only log. Design the chain as something a person can *verify*, not just scroll: sequence, previous hash, content hash, and a verify action. |
| A15 | **Master data** | ~15 lookup tables, organisational units as a 301-node tree with 111 departments. Tree navigation that survives depth. |
| A16 | **Committee workspace** | Confidential scoring, digital sign-off, quorum enforcement. Design the "quorum not met" state — it blocks the meeting, so it must be unmissable. |

---

## §10 — The master dashboard, in detail ★★

The screen you will be judged on. The current one is four gradient tiles, an ApexCharts area chart,
three progress bars and two lists — a competent e-commerce dashboard wearing a university's colours.
Replace it with a **register masthead**.

### 10.1 The figures

Four, ruled into a single band separated by column rules — **not four tiles**:

> Advertisements **1,045** · Total posts **2,874** · Total applications **79,659** · Registered users **55,050**

Tabular Plex at page-title size, `--t-label` caption beneath each, every figure a link to its filtered
list. A figure you cannot click is a figure you have to go find.

### 10.2 The financial strip — make this the centrepiece

Real production figures:

> **₹2,29,94,500 received** · **₹22,25,500 awaited** · **₹93,14,500 failed**

That is a **~29% failure ratio**. It is the business case for the whole payment module.

**Design it as one proportional ruled bar**, full width, three segments — received, awaited, failed —
with the failed segment in `--rejected`, the figures set in tabular mono above their segments, and the
whole thing **clickable straight into the reconciliation queue with the failed filter applied**.

A 29% failure rate that only reports itself is a number. One that opens the work is a control. This
is the strongest single idea available on this screen — spend real design effort on it.

### 10.3 The trend

12-month dual-series area, *Submitted* vs *Paid*, Sep 2025 → Aug 2026, peaking ~2,300 in Mar 2026.
**Inline SVG against the tokens** — no charting library is being reinstated for four charts.

Draw it as **a page of the register**: hairline gridlines on the same rhythm as the table rules,
tabular axis figures, the two series separated by weight and pattern as well as hue, and a baseline
that aligns with the ruled structure around it. Charts here are ruled records that happen to be
plotted.

### 10.4 Goal completions

Three labelled bars with `n/total`: Paid **48,381 / 79,659** · Submitted **63,907 / 79,659** ·
In review **15,752 / 79,659**. Ruled, tabular, percentage as a figure and not only as length.

### 10.5 SLA and scope

- **SLA breaches belong on this screen**, not only in a report. An advertisement inside 5 days of the
  6-month cap is the most urgent thing an administrator can be told.
- **Every figure respects the actor's OU scope**, and the page subtitle says which scope is in force —
  *"Faculty of Arts and 3 departments — local recruitment."* Design the scoped and unscoped headers as
  visibly different objects.

### 10.6 The two lists

**Latest applications** — app no (linked, mono) · post · status badge · date.
**Latest members** — count pill, avatar grid, name, date. Photographs are served at small fixed sizes
from pre-generated conversions and lazy-loaded; design for the missing-avatar case, which is the
common one.

---

## §11 — The data table, the dossier, and the gate

### 11.1 The table

**One component, ~34 screens, 78,232 rows.** Get this right and the console is done; get it wrong and
nothing else matters.

```
┌──────────────────────────────────────────────────────────────────────┐
│ Application Forms                        [Density ⇕] [Columns ▾]     │
│ ┌─────────────────────────┐   Copy CSV Excel PDF Print   [Search  ]  │
│ │ 12 selected   Delete ▾  │                                          │
│ └─────────────────────────┘                                          │
├──┬──────────┬───────────────────┬──────────┬───────────┬─────────────┤
│☐ │ APP NO   │ CANDIDATE         │ CATEGORY │ SUBMITTED │ SCRUTINY    │
├──┼──────────┼───────────────────┼──────────┼───────────┼─────────────┤
│  │ [search] │ [search         ] │ [All  ▾] │ [range  ] │ [All     ▾] │
├──┼──────────┼───────────────────┼──────────┼───────────┼─────────────┤
│☐ │ 10087779 │ MOHAMMAD B. ZAHID │ General  │ 23-01-2026│ ✓ Eligible  │
│☐ │ 10087780 │ AISHA KHAN        │ OBC-NCL  │ 23-01-2026│ ◦ Pending   │
├──┴──────────┴───────────────────┴──────────┴───────────┴─────────────┤
│ Showing 1–100 of 78,232            ‹ 1 2 3 … ›      100 per page ▾   │
└──────────────────────────────────────────────────────────────────────┘
```

Required: server-side pagination at **100 rows**; a **second header row of per-column filters**;
global search; sorting with a default; **six exports** (Copy, CSV, Excel, PDF, Print, Columns);
column visibility; select-all and bulk delete; row actions; dependent filters; density toggle.

Design decisions that matter more than they look:

- **Header `--paper-sunk`, sticky — and it must never cover a focused row.** Design the focused-row
  offset deliberately (WCAG 2.4.11).
- **Identifiers in mono, counts tabular.** A column of scores that does not align cannot be scanned.
- **Filter state lives in the URL**, so a filtered view is linkable and can be sent to a colleague —
  which is what happens when a scrutiny officer escalates a case. Show that the current view is a
  filtered one, and make clearing it one action.
- **Beyond page 100 the table requires a filter** rather than offering a deeper offset. Design that
  message — it is guidance, not an error, and it must not read as a failure.
- **Loading is skeleton rows at the real row height.** Never a spinner over a table; the layout must
  not jump.
- **The empty state names what is missing and the one action that fixes it.** Never an illustration.
- **Exports over 5,000 rows are queued** and the user is notified with a download link. Design the
  queued → ready → expired lifecycle.

### 11.2 The dossier record

The applicant list is not a table of scalars. Each row is a **7-column record**:

1. **Identity** — user id, application no (mono), **passport photograph**, name, father's/mother's/spouse name, email, gender, DOB with **computed age** (`41 years 3 months`), mobile, disability type and %, religion, category, caste, **computed total experience**, submitted-at, and a linked list of **this candidate's other applications**
2. **Address** — correspondence and permanent with PIN; domicile district and state
3. **Qualifications** — chronological: degree, institution, year, %, CGPA
4. **Experience** — organisation, designation, pay, duration, nature of duties
5. **Referees & testimonials** — two sub-blocks
6. **Institutions attended** — name, affiliating university, place, year range
7. **Action** — eligibility, opening the gate control

**Collapsed by default to columns 1 and 7.** Expanding fetches the rest. Design both states, and the
transition between them, at 32px compact density.

**Computed figures are labelled as computed.** `41 years 3 months` carries the reference date, because
age is calculated against the **post's registration end date** under CRR Rule 14 — never against today.
An officer must never have to work out which date a figure was measured from. Design that disclosure
inline; it is not a tooltip afterthought.

### 11.3 The gate control

```
┌─ Update eligibility ─────────────────────────────────────────┐
│ Application 10087779 · MOHAMMAD BASIM ZAHID                  │
│ Post 2599 System Manager · Written test + Interview          │
├──────────────────┬──────────────────┬────────────────────────┤
│ SCRUTINY         │ WRITTEN TEST     │ INTERVIEW              │
│ ◉ ✓ Eligible     │ ○ ✓ Eligible     │ ○ ✓ Eligible           │
│ ○ ✕ Not eligible │ ○ ✕ Not eligible │ ○ ✕ Not eligible       │
│ ○ ◦ Pending      │ ◉ ◦ Pending      │ ◉ ◦ Pending            │
│ Remark           │ Remark           │ Remark                 │
│ [             ]  │ [             ]  │ [             ]        │
├──────────────────┴──────────────────┴────────────────────────┤
│                                    Cancel   Save decisions   │
└──────────────────────────────────────────────────────────────┘
```

**Two defects in the current control are deliberately fixed, and your design must not reintroduce them:**

1. **Three explicit options, never a merged label.** The legacy control renders *"Pending / Not
   Eligible"* over three distinct stored values. On a decision that determines whether someone is
   considered for a job, that ambiguity is unacceptable.
2. **Only active gates are rendered.** The gate set comes from the post type. On an interview-only
   post the written-test column **does not exist** — not disabled, not greyed. Absent.

Plus: **a rejection requires a remark.** Design that requirement so it reads as due process rather
than as a validation nag.

**Destructive and consequential actions:** `--rejected` is **outlined, never filled**. A filled red
button beside a filled green one is how a tired officer rejects the wrong candidate. Destructive
actions require typed confirmation.

---

## §12 — Copy

Words are design material here, not decoration. Write every string on every artboard. Placeholder
copy will be read as the design.

- **Active voice, sentence case, plain verbs.** A control says exactly what happens: *"Save
  decisions"*, not *"Submit"*. The action keeps its name through the whole flow — the button that says
  *"Publish"* produces a toast that says *"Published"*.
- **Name things as the user recognises them**, never as the system stores them. *"Eligibility"*, not
  *"gate 1 flag"*.
- **Errors do not apologise and are never vague.** State what happened and what fixes it. *"Those
  credentials don't match our records."* *"Choose a range of five years or less."* *"No records exist
  before 22 Jan 2026."*
- **Empty states are invitations.** State what is missing and the one action that fixes it.
- **Statutory strings are quoted, never paraphrased.** If a figure comes from a regulation, its clause
  reference travels with it.
- **The candidate voice is calm and specific under pressure.** A deficiency notice is bad news; it
  should read like a clear instruction from a competent office, not like a warning label.

---

## §13 — States you must draw (not describe)

For the starred screens, every one of these:

| State | Note |
|---|---|
| Empty — first use | No applications yet; no advertisements yet |
| Empty — filtered to nothing | Different from first-use. Offer to clear the filter. |
| Loading | Skeleton rows at true height. No spinners on tables. |
| Error | Server error, permission denied, expired window — each distinct |
| Partial / blocked | Scoring blocked pending ratification; report reconstructing; export queued |
| Scoped | Dean's-office view with the subtitle stating the scope |
| Redacted | `finance_admin` view with PII withheld |
| Read-only | `auditor` view |
| Impersonated | The banner |
| Locked | Post-submission dossier; sections outside a deficiency window |
| Offline / no-JS | Filters, sorting and pagination are plain forms and links and **must work without JavaScript** |
| Dark | Every starred screen, as a slate ledger |
| Mobile | 390px, for the whole candidate journey |
| Print | The statutory application form, the attendance register, the merit list — black on white, no chrome, repeating table headers |

---

## §14 — Motion

Restrained. One orchestrated moment beats scattered effects, and excess animation is the fastest way
to make this look generated rather than designed.

| Where | What |
|---|---|
| Section change in the wizard | 120ms cross-fade + 4px rise |
| Row expand in a dossier | 160ms height transition |
| Toast | 200ms slide from the top edge |
| Everything else | **No animation** |

`prefers-reduced-motion: reduce` collapses all of it to `0.01ms`.

---

## §15 — The accessibility floor (WCAG 2.2 AA + GIGW, tested in CI)

Not a checklist you satisfy afterwards. `axe-core` runs on every rendered route and **fails the build**
on a violation, so a design that cannot pass is a design that cannot ship.

- **Contrast:** AA minimum; **AAA for body text in the application form** — someone reads it for hours.
- **Status is never colour alone.** Glyph **and** word, everywhere, always.
- **Visible focus everywhere** — 2px `--green` ring, 2px offset. `outline: none` appears nowhere.
- **Target size 24×24px minimum**, held inside 32px compact rows.
- **Skip-to-content first in the DOM.** One `h1` per page, ordered headings, real landmarks.
- **Tables are real tables** — `<caption>` naming the table and its current filters, `<th scope>`,
  `aria-sort` on sortable columns, row actions named with their subject
  (*"Update eligibility for application 10087779"*).
- **The composite cell** `106 / 63 / 58 / 13⚑` carries `aria-label="106 total, 63 submitted, 58 paid,
  13 internal"`.
- **200% zoom without loss.** Wide content scrolls in its own container; **the page body never scrolls
  sideways.**
- **Redundant entry (3.3.7):** the reusable profile is literally this criterion. Never ask twice.
- **Language:** `lang` on the root, `lang="hi"` and `lang="ur"` with `dir="rtl"` on Urdu titles.

---

## §16 — Responsive

Candidate surfaces are **mobile-first and must fully work at 390px** — a large share of applicants
apply on a phone, and the wizard is where they will spend three hours. Admin surfaces are
**desktop-first**, but must remain usable on a tablet for someone walking a queue.

The sign-in photograph becomes a band, not a backdrop, below 900px. Tables scroll horizontally inside
themselves. The spine collapses to a section picker that still shows completion.

---

## §17 — Do not

| Don't | Because |
|---|---|
| Gradient KPI tiles, card grids, rounded-everything | It is the skin we are removing, and it belongs to a different product |
| Decorative icons or illustrated empty states | Nothing here is illustrated; an empty queue is information |
| Donut charts, or any chart that needs a legend to be read | Ruled, labelled, tabular |
| Colour-only status | Fails WCAG 1.4.1 and GIGW, on a legally consequential decision |
| A filled red button next to a filled green one | That is how the wrong candidate gets rejected |
| Merging two states into one label | The specific defect this rebuild exists to fix |
| Numbering things that are not sequences | Numbering is information, not ornament |
| Repeating the arch | It is the signature. Once per section head. |
| **Warm cream (#F4F1EA) + high-contrast serif + terracotta accent** | The current default look of generated design. Our ground is deliberately cool and our accent is brass. |
| **Near-black with a single acid-green accent** | Same reason |
| **Broadsheet pastiche — hairline rules, zero radius, dense newspaper columns as a style** | We are ruled because a register is ruled, not because newspapers are fashionable. The difference must be visible in the work. |

---

## §18 — Real content to design with

Use these. Do not invent placeholder names or round numbers.

**Figures:** 1,045 advertisements · 2,874 posts · 79,659 applications · 55,050 registered users ·
78,232 rows in the applications table · ₹2,29,94,500 received · ₹22,25,500 awaited · ₹93,14,500 failed
· Paid 48,381/79,659 · Submitted 63,907/79,659 · In review 15,752/79,659.

**A post:** `2599 · System Manager, Prof. M.N. Farooqui Computer Centre` · Advertisement No.
2/2026/NT dated 22.01.2026 · General (Non-Teaching) · Pay Level-12 (₹78,800–2,09,200) · Fee ₹500 ·
Vacancies 1 · Opening 22-01-2026 · Closing 07-03-2026 · Payment last date 07-03-2026 · Total 106 /
Submitted 63 / Paid 58 · Scrutiny eligible 7 · Eligible for interview 0 · Interview letters 0.

**People:** Mohammad Basim Zahid (app 10087779, General) · Aisha Khan (app 10087780, OBC-NCL,
applications 2599/2026/00412 and 884/2026/01109).

**Organisational scope:** 13 Faculties, 111 Departments, 301 organisational units. Example subtitle:
*"Faculty of Arts and 3 departments — local recruitment."*

**Citations:** `UGC 2018 cl. 4.1 II` · `App. II Table 2 row 1` · `CRR Rule 14` ·
`ugc-teaching-2018@1 · frozen 2026-01-22`.

---

## §19 — Implementation constraints on your design

Design what can be built here, because it will be:

- **Tailwind 4** with `@theme` in `resources/css/app.css`. Tokens declared once as CSS custom
  properties. **No hex value appears in a template.**
- **Blade components + Alpine.** No SPA, no jQuery, no component library. Server-rendered, progressively
  enhanced.
- **Charts are inline SVG** against the tokens. No charting library.
- **Two fonts families plus their scripts**, self-hostable: Spectral, IBM Plex Sans / Mono / Sans
  Devanagari, Noto Nastaliq Urdu.
- The component shelf you are designing into:
  `ui/{button,field,statutory-field,gate-control,badge,empty,skeleton}` ·
  `layout/{page,section,gate-rule,spine,record}` ·
  `data/{table,column-filter,composite-count,pipeline-panel,export-bar}`.
  **If your design needs a component not on this list, name it and say why it earns its place.**

---

## §20 — How to work, and what to deliver

**Pass 1 — the written direction. Do not draw yet.** Produce, in prose and ASCII:

1. The **palette** as named values, showing what you inherited and anything you are adding, with the
   reason.
2. The **type system** in use — the scale, the weights, and what each role is doing on each surface.
3. **Two or three layout concepts** for the console shell and the candidate shell, compared, with a
   recommendation and ASCII wireframes.
4. Your treatment of the three structural devices: the **gate rule**, the **margin**, the **spine**.
5. **The one risk you are taking**, and the argument for it.
6. A **self-critique**: name every element that would appear in a generic design for any similar
   brief, and either remove it or justify it in one sentence.

**Then stop and show me Pass 1.** I will respond before you build.

**Pass 2 — the canvas.** One pan/zoom canvas, artboards laid out in these groups:

| Group | Artboards |
|---|---|
| Foundations | Tokens (light + dark), type specimen, component shelf, the gate rule at three sizes |
| Candidate — desktop 1440 | Sign-in · Browse vacancies · Advertisement detail · Eligibility pre-check · **Dashboard** · Application detail + timeline · Wizard Part A · Research claims · Documents · Preview · Payment |
| Candidate — mobile 390 | Sign-in · **Dashboard** · Wizard Part A · Deficiency rectification |
| Admin — desktop 1440 | **Master dashboard** · Advertisement list · Advertisement detail · **Post detail + pipeline** · **Applications table** · **Dossier record, collapsed and expanded** · **Gate control** · Scrutiny workbench · Post types config · Bulk documents · Reports & SLA · Audit trail |
| Dark | Master dashboard · Applications table · Candidate dashboard |
| States | Empty · filtered-empty · loading · error · scoped · redacted · read-only · impersonated · locked |
| Print | Statutory application form · attendance register |

Every artboard: real content from §18, real copy, tokens only, no placeholder rectangles.

---

## §21 — Done means

- A scrutiny officer can work a queue of 106 without touching the mouse, and can always see which
  ruleset governs the record in front of them.
- A candidate on a phone at 11pm knows exactly what they must do next and by when.
- Every number on screen can be traced to its rule, its version, and the date it was measured against.
- Nothing on any screen is colour-only, and nothing fails at 200% zoom.
- The dark theme is a slate ledger, not an inverted page.
- Someone who has seen this product once can describe the arch, the margin and the ruled record — and
  cannot mistake it for any other portal.

Impress me. Then take one accessory off.
