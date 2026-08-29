# Screen Specifications

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27

The 12 reference screenshots in `docs/images/` are **AMU's own production systems**, not competitor
guesses — the ERP footer reads *"Designed by Nawaz Sarwar, AMU · Version 3.0.0"*. They are therefore
a reliable requirements source, and **no document in `docs/v2-archive/spec/` looks at them.**

We keep the information design and rebuild the visual language (`design-system.md` §1).

**One structural change runs through everything:** the admin experience today is split across **two
applications** — configuration in the ERP (`datalake.amuonline.ac.in`), operations in Manage Careers
(`mcareers.amuonline.ac.in`). Staff move between them to finish one task. **v1 consolidates them.**

---

## 1. Sign-in — `media_1787424976961.png`

Split pane. **Left:** AMU roundel and *"Aligarh Muslim University / Office of the Controller of
Examinations"* top-left; a floating white card lower-left holding the crest, **"Sign In"** in
Spectral, `USERNAME OR EMAIL` and `PASSWORD` in `--t-label`, a password reveal toggle, *Keep me
signed in*, a full-width `--green` button, and *Need help signing in?*. Footer: `© 2026 Aligarh
Muslim University`. **Right:** Victoria Gate photography in a container whose left edge is a soft
concave curve, with a dark translucent pill reading `● Victoria Gate · AMU Aligarh`.

**Implementation notes**

- **One field, resolved server-side** (DR-008): applicants type an email; staff may type an email or
  an employee ID. Placeholder: `you@example.com — or your employee ID`.
- Photograph: `<picture>` with AVIF/WebP, `srcset`, `loading="eager"`, `fetchpriority="high"`.
  **Below 900px the image becomes a 30vh band above the card**, never a background behind text.
- Errors are generic — *"Those credentials don't match our records."* Never *"no such user"*.
- Lockout after 5 attempts states when to retry.
- **Secondary submit: *Send me a code instead*** (DR-023), directly below the primary button, in the
  quiet link style — not a tab, not a toggle, not a second screen. The password field stays the
  default; the code path is one click away. It posts the same identifier to `login.otp.request`.
- **Code entry** replaces the card's body on the next step: the masked destination (`•••••• 4821`),
  six digit boxes, a resend control with a live countdown, and a *Use my password instead* link back.
  With JavaScript disabled the six boxes are one `inputmode="numeric"` field and resend is a plain
  submit that re-renders the countdown server-side — nothing here needs scripting (DR-021).
- **The second-factor challenge reuses the same card**, with a channel picker where more than one
  method is enrolled. One screen serves TOTP, SMS and email; the method decides the copy, not the
  layout.
- **Every timed refusal states the time**, never "try later" — the resend cooldown and the hourly cap
  follow the lockout notice's rule.
- **What we are replacing:** `docs/v2-archive/login-tailwind.png`, the previous developer's output — an
  unstyled centred card titled "Login to CareersPro" **with no submit button rendered at all**.

---

## 2. Master dashboard — `media_1787422488869.png`

Sidebar: Dashboard · Advertisements · Reports · Scrutiny · Application Receipt · Attendance Sheets ·
Bulk Documents · Profiles.

| Region | Content |
|---|---|
| **Figures** | Advertisements **1,045** · Total posts **2,874** · Total applications **79,659** · Registered users **55,050**. Ruled row, tabular Plex at `--t-page`, `--t-label` captions. **Not gradient tiles** |
| **Application trends** | 12-month dual-series area chart, *Submitted* vs *Paid*, Sep 2025 → Aug 2026. **Inline SVG**, not ApexCharts |
| **Goal completions** | Three labelled bars with `n/total`: Paid 48,381 / 79,659 · Submitted 63,907 / 79,659 · In review 15,752 / 79,659 |
| **Financial strip** | **₹2,29,94,500 received** · **₹22,25,500 awaited** · **₹93,14,500 failed** |
| **Latest applications** | App no (linked, mono) · post · status badge · date |
| **Latest members** | Count pill, avatar grid, name and date |

> **The financial strip is the business case for M08.** ₹93.14 lakh failed against ₹2.29 crore
> received is a **~29% failure ratio**. Make it prominent and make it *clickable* — it should open
> the reconciliation queue, not merely report a number.

**Scoping:** every figure respects the actor's OU scope. A Dean's-office dashboard shows that
faculty's local recruitment only, and says so in the page subtitle.

---

## 3. Advertisement list — `media_1787422144443.png`, `media_1787422218339.png`

Standard data table (`data-table.md`). Columns: ☐ · **ID** (default sort desc) · **Title** (linked) ·
Slug · Dated · **Type** (select filter: All / General / Local) · Advertisement URL · Actions
(View / Edit / Delete). Second header row of per-column filters. Toolbar: `Show 100 entries`,
select/deselect all, six exports, delete selected, global search.

**Three changes**

1. **Slug loses its unix-timestamp suffix.** The reference shows
   `…-faculty-of-medicine-1787396468` — a de-dup hack. Slugs become
   `{advertisement_no}-{slugified-title}`, unique by advertisement number.
2. **Add an `Appointment nature` column** (General / Local) — DR-010 makes it a real domain concept,
   and the Dean's-office view filters on it.
3. **Drop the `Advertisement URL` column from the default view** — it is empty on every visible row
   in both reference screenshots. Available via the columns toggle.

---

## 4. Advertisement detail + child posts — `media_1787422168698.png`, `media_1787422248995.png`

Definition list: ID · Title · Slug · **Description** (behind a `Show description` toggle) · Dated ·
Type · **Appointment nature** · **Organisational unit** (from the snapshot) · URL · Document ·
**Application statistics** (`Paid 710 / Submitted 765 / Total 954`) · **Actions**
(`Download paid applications`, `Download all applications`).

**Add: the frozen ruleset.** Displayed with its `.citation` treatment —
`Governed by ugc-teaching-2018@1 · frozen 2026-01-22`. A scrutiny officer must be able to see which
rules apply without asking, and it makes I1 visible rather than implicit.

**Child post sub-grid.** ID · Post type · **Title** (bold link, with `Vacancies: n` and `Location:`
beneath) · Pay level · Fee · Open date · Last date · Withdrawn · Status · **composite count** ·
Actions (`View post`, `Download paid`, `Download all`).

The composite cell is `106 / 63 / 58 / 13⚑` — total / submitted / paid / internal — colour-coded,
mono, with a spelled-out `aria-label`. Backed by **counter columns**, never computed per render
(`data-table.md` §3).

**Add: corrigenda.** A dated list. Corrigenda are objects, not edits.

---

## 5. Post detail + pipeline — `media_1787422189821.png`, `media_1787422312810.png`

Two columns.

**Left — post details.** Advertisement · post type · serial no · subject · slug · location ·
**designation** (new — M35) · **organisational unit** (new) · **appointment nature and tenure** ·
pay and vacancies · **important dates** with three coloured markers (opening, closing, payment last
date) · status · `View description`.

**Right — the pipeline panel**, three grouped blocks:

```
APPLICATION STATISTICS      ELIGIBILITY STATISTICS     DOWNLOAD STATISTICS
┌──────────────────┐        ┌───────────────────┐      ┌──────────────────┐
│ TOTAL        106 │        │ SCRUTINY ELIGIBLE 7│      │ INTERVIEW LETTERS│
├────────┬─────────┤        ├───────────────────┤      │               0  │
│SUBMIT 63│ PAID 58│        │ ELIGIBLE FOR       │      └──────────────────┘
└────────┴─────────┘        │ INTERVIEW        0 │
                            └───────────────────┘
```

**Every figure in the eligibility block reads from `eligibility_decisions`.** On the collapsed
four-column schema these numbers cannot be computed at all — which is why the three-gate model is
load-bearing rather than a nicety.

Below: two dependent filters — `Eligibility type` and `Eligibility status` — driving the applicant
list, which is the dossier table (`data-table.md` §5).

---

## 6. Applicant dossier and gate control — `media_1787422365156.png`, `media_1787422376343.png`

Fully specified in `data-table.md` §5 and §6. The two defects fixed there: the merged
*"Pending / Not Eligible"* label, and rendering all three gates on interview-only post types.

---

## 7. Post types configuration — `media_1787422430108.png`

Standard table. Columns: ID · Name · **PDF template** · **Default selection method** · **Admit card
template** · **Interview letter template** · **Submission venue** · Status · Remark. Seven live rows.

> **This is the most important configuration screen in the system.** One row makes the application
> form, the selection pipeline, the generated documents and the physical submission office all
> polymorphic. Treat it as such: changes require confirmation, are audited, and the screen warns when
> a change affects **published** posts.

**Note a transcription error to avoid:** `UI_DESIGN_SPECIFICATIONS.md` §Screen 7 assigns
`interview_letter` to rows 1, 2 and 7. The screenshot shows those cells **blank**. The screenshot is
authoritative.

---

## 8. Attendance sheet generator — `media_1787422553896.png`

`Select advertisement` → `Select post` (dependent) → `Has roll no been uploaded/generated?` →
`Report type` (All / **Scrutiny eligible only** / **Interview eligible only**) → `With photo?` →
`With barcode?` → **Generate**.

Output: a printable PDF attendance register.

**Two of the three report types read the eligibility gates directly.** Another dependency that the
collapsed schema breaks.

**Add:** generation is **queued** with a progress indicator — a 106-row sheet with photographs is not
a synchronous response — and each generation is audited.

---

## 9. Bulk document generator — `media_1787422606175.png`

`Select advertisement` → `Select post` → `Document type` (Admit card / Interview letter) → `Filter`
(All applicants / **Eligible only** / **Interview eligible only**) → **Generate**.

Async PDF compilation from `post_types.admit_card_template` / `.interview_letter_template`, with
per-candidate details, schedule, venue, reporting instructions and a **QR verification stamp**.

**Add three things**

1. **Window enforcement.** Generation is refused outside
   `admit_card_opening_date … closing_date`. Those columns exist in production and were dropped in
   the redesign; they are restored.
2. **A dry-run count** before generating — *"This will generate 58 admit cards."*
3. **Idempotence.** Re-running does not duplicate; it regenerates and supersedes, recording both.

---

## 10. Candidate journey

Not in the screenshots — reconstructed from the AMU manual — and the half of the product with the
most to fix.

| Step | Screen | Changes from the legacy flow |
|---|---|---|
| 1 | Register | Email verification that **terminates**. Today every new user is logged out permanently and cannot verify |
| 2 | Dashboard | Stage timeline, action items, deficiencies with a live countdown |
| 3 | **Profile — Part A, 11 sections** | The **spine** (`design-system.md` §4.2). **Sequential tab gating is removed** — every section reachable, completion shown |
| 4 | Track sections — B1 / B2 / B3 / C | Rendered from the post type |
| 5 | **Research claims** | **DOI/CrossRef lookup** and **UGC-CARE verification**. CU-Chayan's loudest data-entry complaint is entering 20+ publications by hand with no import and no verification |
| 6 | Documents | Cropping to the statutory specs (photo 350×450 ratio 7:9; signature and thumb 300×150 ratio 6:3). **Inline viewer** — no downloading loose PDFs |
| 7 | Browse vacancies | Nine filters, matching or exceeding CU-Chayan |
| 8 | **Apply** | **Eligibility pre-check before payment.** The legacy system evaluates age and experience at the payment deadline, so ineligible candidates pay first |
| 9 | Preview | Full statutory format, exactly as it will print |
| 10 | Pay | Idempotent order; **a lost callback never causes a second charge** |
| 11 | Submit | Snapshot taken, dossier locked, application number issued |
| 12 | **Deficiency rectification** | Time-bound window, only the named sections re-open. **The legacy system locks irreversibly at payment with no rectification path at all** |
| 13 | Print and post | Retained (DR-011), with the custody register tracking receipt |

---

## 11. What we deliberately do not copy

| From | Why not |
|---|---|
| Merged *"Pending / Not Eligible"* control | Ambiguous over three stored values, on a legally consequential decision |
| All three gates on interview-only posts | Contradicts the post type; the schema comment says the field should be blank |
| Unix-timestamp slug suffixes | A de-dup hack; replaced by a real uniqueness rule |
| Sequential tab gating in Part A | No resumability, no save-and-skip — a recorded pain point |
| Irreversible lock at payment | *"Applicants are not allowed to update/modify… in any circumstances"* — CU-Chayan's and AMU's shared worst complaint |
| Two separate admin applications | Consolidated |
| Synchronous bulk generation with `memory_limit = -1` | Queued |
| Category question pre-filled `"No"` (F-3 item 9) | A legacy defect. Also, F-3 omits PwD entirely |

---

## 12. Traceability

| Section | Feeds |
|---|---|
| §1 | M03 |
| §2 | M23 |
| §3, §4 | M01 · M02 · M16 |
| §5 | M16 · M34 |
| §6 | M18 · M34 · `data-table.md` |
| §7 | M24 |
| §8 | M31 |
| §9 | M32 · M11 |
| §10 | M03–M10 |

---

## 13. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. All 12 reference screens specified with their real data, plus the candidate journey. Records what is deliberately not copied and why. | Implementation team |
