import { writeFileSync } from 'node:fs';

const A = [];
const add = (file, x, y, w, h, page, title, extra = {}) => A.push({ file, x, y, w, h, page, title, ...extra });

/* ── Foundations ────────────────────────────────────────────── */
add('Tokens.dc.html', 0, 0, 1000, 1500, 'page-1', 'Tokens · light and slate');
add('Type.dc.html', 1100, 0, 940, 1400, 'page-1', 'Type specimen');
add('GateRule.dc.html', 2140, 0, 940, 900, 'page-1', 'The gate rule · three sizes');
add('Shelf.dc.html', 3180, 0, 1120, 1600, 'page-1', 'Component shelf');

/* ── Candidate ──────────────────────────────────────────────── */
add('SignIn.dc.html', 0, 0, 1440, 900, 'page-2', 'C1 · Sign-in');
add('Vacancies.dc.html', 1560, 0, 1440, 1200, 'page-2', 'C3 · Browse vacancies');
add('AdvertDetail.dc.html', 3120, 0, 1440, 1350, 'page-2', 'C4 · Advertisement detail');
add('PreCheck.dc.html', 4680, 0, 1440, 1100, 'page-2', 'C5 · Eligibility pre-check');
add('CandDashboard.dc.html', 6240, 0, 1440, 1800, 'page-2', 'C11 · Applicant dashboard ★★');
add('AppDetail.dc.html', 0, 2000, 1440, 1200, 'page-2', 'C12 · Application detail + timeline');
add('WizardA.dc.html', 1560, 2000, 1440, 1350, 'page-2', 'C6 · Wizard, Part A ★★');
add('Research.dc.html', 3120, 2000, 1440, 1300, 'page-2', 'C7 · Research claims');
add('Documents.dc.html', 4680, 2000, 1440, 1350, 'page-2', 'C8 · Document vault');
add('Preview.dc.html', 6240, 2000, 1440, 1150, 'page-2', 'C9 · Preview and submit');
add('Payment.dc.html', 7800, 2000, 1440, 950, 'page-2', 'C10 · Payment');
add('Deficiency.dc.html', 9360, 2000, 1440, 1300, 'page-2', 'C13 · Deficiency rectification ★★');
add('AdmitCard.dc.html', 10920, 2000, 1440, 1400, 'page-2', 'C14 · Admit card and interview letter');
add('Grievance.dc.html', 12480, 2000, 1440, 1500, 'page-2', 'C15 · Grievance, SLA-tracked');
add('SignInStates.dc.html', 0, 3500, 900, 900, 'page-2', 'C1 / C2 · Sign-in and verification states');
add('OtpStates.dc.html', 0, 4500, 900, 1400, 'page-2', 'C1 · One-time code and second-factor states');
add('MobileSignIn.dc.html', 1060, 3500, 390, 900, 'page-2', 'Mobile 390 · Sign-in');
add('MobileDashboard.dc.html', 1560, 3500, 390, 2000, 'page-2', 'Mobile 390 · Dashboard ★★');
add('MobileWizard.dc.html', 2060, 3500, 390, 1450, 'page-2', 'Mobile 390 · Wizard Part A');
add('MobileDeficiency.dc.html', 2560, 3500, 390, 1300, 'page-2', 'Mobile 390 · Deficiency rectification');

/* ── Admin ──────────────────────────────────────────────────── */
add('Main.dc.html', 0, 0, 1440, 1500, 'page-3', 'A1 · Master dashboard ★★');
add('AppsTable.dc.html', 1560, 0, 1440, 1250, 'page-3', 'A2 · The data table ★★');
add('DossierCollapsed.dc.html', 3120, 0, 1440, 1050, 'page-3', 'A3 · Dossier, collapsed ★★');
add('DossierExpanded.dc.html', 4680, 0, 1440, 1250, 'page-3', 'A3 · Dossier, expanded ★★');
add('GateControl.dc.html', 6240, 0, 1440, 1400, 'page-3', 'A4 · The counterfoil ★★');
add('Workbench.dc.html', 7800, 0, 1440, 1300, 'page-3', 'A7 · Scrutiny workbench ★');
add('PostDetail.dc.html', 0, 1700, 1440, 1250, 'page-3', 'A5 · Post detail + pipeline ★');
add('AdvertList.dc.html', 1560, 1700, 1440, 1050, 'page-3', 'A6 · Advertisement list');
add('AdvertDetailAdmin.dc.html', 3120, 1700, 1440, 1300, 'page-3', 'A6 · Advertisement detail');
add('PostTypes.dc.html', 4680, 1700, 1440, 1200, 'page-3', 'A9 · Post types configuration');
add('BulkDocs.dc.html', 6240, 1700, 1440, 1200, 'page-3', 'A11 · Bulk documents');
add('Reports.dc.html', 7800, 1700, 1440, 1250, 'page-3', 'A12 · Reports and SLA ★');
add('AuditTrail.dc.html', 9360, 1700, 1440, 1300, 'page-3', 'A14 · Audit trail');
add('AdvertBuilder.dc.html', 0, 3200, 1440, 1450, 'page-3', 'A8 · Advertisement builder ★');
add('Attendance.dc.html', 1560, 3200, 1440, 1300, 'page-3', 'A10 · Attendance register generator');
add('RulesAuthoring.dc.html', 3120, 3200, 1440, 1400, 'page-3', 'A13 · Rules authoring, two-person ★');
add('MasterData.dc.html', 4680, 3200, 1440, 1300, 'page-3', 'A15 · Master data and the OU tree');
add('Committee.dc.html', 6240, 3200, 1440, 1350, 'page-3', 'A16 · Selection committee');

/* ── Dark and states ────────────────────────────────────────── */
add('DarkDashboard.dc.html', 0, 0, 1440, 1500, 'page-4', 'Dark · Master dashboard');
add('DarkAppsTable.dc.html', 1560, 0, 1440, 1250, 'page-4', 'Dark · Applications table');
add('DarkCandDashboard.dc.html', 3120, 0, 1440, 1800, 'page-4', 'Dark · Candidate dashboard');
add('StateScoped.dc.html', 0, 2000, 1440, 1500, 'page-4', 'Scoped · Dean’s office');
add('StateRedacted.dc.html', 1560, 2000, 1440, 1150, 'page-4', 'Redacted · finance_admin');
add('StateReadOnly.dc.html', 3120, 2000, 1440, 1400, 'page-4', 'Read-only, impersonated, committee');
add('StateLocked.dc.html', 4680, 2000, 1240, 1100, 'page-4', 'Locked and blocked');
add('StateTable.dc.html', 6100, 2000, 1240, 2000, 'page-4', 'Empty, filtered-empty, loading, deep page');
add('StateErrors.dc.html', 7520, 2000, 1240, 1050, 'page-4', 'Errors and no-JavaScript');

/* ── Print ──────────────────────────────────────────────────── */
add('PrintForm.dc.html', 0, 0, 794, 1123, 'page-5', 'Print · statutory application form', { print: 'fixed' });
add('PrintAttendance.dc.html', 920, 0, 1123, 794, 'page-5', 'Print · attendance register', { print: 'fixed' });

const canvas = {
  artboards: A,
  pages: [
    { id: 'page-1', name: 'Foundations' },
    { id: 'page-2', name: 'Candidate' },
    { id: 'page-3', name: 'Console' },
    { id: 'page-4', name: 'Dark & states' },
    { id: 'page-5', name: 'Print' },
  ],
  annotations: [
    { id: 'note-foundations', page: 'page-1', x: 0, y: -190, w: 1000, text: 'Register — the binding system.\nNineteen tokens: eighteen inherited, one added (--info-wash), three completed for dark where they had been defined only in light. The arch appears once per section head and nowhere else.' },
    { id: 'note-candidate', page: 'page-2', x: 0, y: -190, w: 1200, text: 'Candidate — comfortable density, 44px rows, 15px data baseline, AAA body text in the form.\nAisha Khan, two applications: 2599/2026/00412 under a deficiency window, and 884/2026/01109 cleared at scrutiny.\nSecond row, at the right: what happens after payment — rectification, the admit card at three moments in its window, and a grievance held to a stated time.' },
    { id: 'note-console', page: 'page-3', x: 0, y: -190, w: 1200, text: 'Console — compact density, 32px rows, 13px data baseline, keyboard first.\nThe margin carries provenance on every record screen. The counterfoil replaces the modal: claim, evidence and rule stay visible while the decision is made.\nThird row: the instruments — building an advertisement against sanctioned strength, generating the register, authoring a ruleset under two-person control, the 301-node organisational tree, and the committee.' },
    { id: 'note-states', page: 'page-4', x: 0, y: -190, w: 1200, text: 'Dark is a slate ledger, not an inverted page — the ground goes green-black and the rules gain weight.\nBelow: the role surfaces. A scoped view is a visibly different object from an unscoped one.' },
    { id: 'note-print', page: 'page-5', x: 0, y: -190, w: 900, text: 'Print — black on white, no interface furniture, repeating table headers.\nProvenance prints: every sheet carries the snapshot it was generated from and the ruleset in force.' },
  ],
  launch: { view: 'canvas', page: 'page-2' },
};

writeFileSync(new URL('canvas.json', import.meta.url), JSON.stringify(canvas, null, 2));
console.log('canvas.json written ·', A.length, 'artboards');
