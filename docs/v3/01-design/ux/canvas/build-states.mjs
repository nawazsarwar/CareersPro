import { writeFileSync, readFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, NAV } from './shared.mjs';
import { dashboardBody, mg } from './build-admin-a.mjs';
import { TBAR, THEAD } from './build-admin-b.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);
const read = (f) => readFileSync(new URL(f, import.meta.url), 'utf8');

/* ── Dark — the same screens as a slate ledger ──────────────── */
const darken = (src, out) => w(out, read(src).replace('class="art"', 'class="art slate"'));
w('DarkDashboard.dc.html', artboard({ w: 1440, dark: true, body: dashboardBody() }));
darken('AppsTable.dc.html', 'DarkAppsTable.dc.html');
darken('CandDashboard.dc.html', 'DarkCandDashboard.dc.html');

/* ── A1 scoped — the Dean's-office dashboard ────────────────── */
w('StateScoped.dc.html', artboard({ w: 1440, body: dashboardBody({ scoped: true }) }));

/* ── table states ───────────────────────────────────────────── */
const skRow = (a, b, c, d, e) => `<tr><td><i class="sk" style="width:14px"></i></td><td><i class="sk" style="width:${a}px"></i></td>
  <td><i class="sk" style="width:${b}px"></i></td><td><i class="sk" style="width:${c}px"></i></td>
  <td><i class="sk" style="width:${d}px"></i></td><td><i class="sk" style="width:${e}px"></i></td>
  <td><i class="sk" style="width:76px"></i></td><td><i class="sk" style="width:56px"></i></td></tr>`;

w('StateTable.dc.html', artboard({
  w: 1240, pad: true, body: `
${gateRule('', 'The table, when it has nothing to show')}
<p class="t-caption" style="max-width:74ch;margin-bottom:22px">Four states that are routinely drawn as the same grey box. They are four different messages, and each names the one action that resolves it.</p>

<div class="t-label" style="margin-bottom:8px">Empty · first use</div>
<div class="rec" style="margin-bottom:26px"><table class="tbl">${THEAD}</table>
  <div class="empty" style="border:0;border-top:1px solid var(--rule);padding:34px 24px">
    <div class="t-section" style="font-size:18px">No applications have been received for this post yet.</div>
    <p class="t-body" style="font-size:14px;max-width:66ch;margin-top:6px">Post 2599 opened on <b>22-01-2026</b> and closes on <b>07-03-2026</b>. Applications will appear here as candidates submit them, and the counters on the post page update at the same time.</p>
    <div style="margin-top:14px;display:flex;gap:10px"><a class="btn" href="#">View post 2599</a><a class="btn" href="#">Advertisement 2/2026/NT</a></div>
  </div></div>

<div class="t-label" style="margin-bottom:8px">Empty · filtered to nothing — a different message, because it is a different problem</div>
<div class="rec" style="margin-bottom:26px">
  <div style="padding:9px 12px;border-bottom:1px solid var(--rule)"><span class="filterchip"><b>Filtered view</b> · category ST · scrutiny ✓ Eligible · submitted 01-08-2026 to 28-08-2026<a href="#">Clear all</a></span></div>
  <table class="tbl">${THEAD}</table>
  <div class="empty" style="border:0;border-top:1px solid var(--rule);padding:34px 24px">
    <div class="t-section" style="font-size:18px">No applications match these three filters.</div>
    <p class="t-body" style="font-size:14px;max-width:66ch;margin-top:6px">There are <b class="num">31</b> applications from ST candidates for this post, and <b class="num">7</b> marked ✓ Eligible at scrutiny. None of them was submitted in August 2026 — the post closed in March.</p>
    <div style="margin-top:14px;display:flex;gap:10px"><a class="btn p" href="#">Clear the date range</a><a class="btn" href="#">Clear all filters</a></div>
  </div></div>

<div class="t-label" style="margin-bottom:8px">Loading — skeleton rows at the true 32px height, and no spinner over a table</div>
<div class="rec" style="margin-bottom:26px"><table class="tbl">${THEAD}
  <tbody>${[[86, 168, 64, 72, 78], [86, 132, 78, 68, 78], [86, 196, 56, 74, 78], [86, 148, 70, 70, 78],
  [86, 176, 62, 76, 78], [86, 120, 74, 66, 78], [86, 158, 58, 72, 78], [86, 184, 66, 70, 78]]
      .map((r) => skRow(...r)).join('')}</tbody></table>
  <div class="pager"><span><i class="sk" style="width:150px"></i></span><span><i class="sk" style="width:120px"></i></span><span><i class="sk" style="width:90px"></i></span></div></div>
<p class="t-caption" style="margin:-14px 0 26px;max-width:74ch">The skeleton occupies exactly the space the rows will occupy, so nothing moves when the data arrives. It does not shimmer: §14 permits no animation here, and a shimmer is motion pretending to be feedback.</p>

<div class="t-label" style="margin-bottom:8px">Past page 100 — guidance, not a failure</div>
<div class="rec"><table class="tbl">${THEAD}</table>
  <div style="padding:28px 24px;border-top:1px solid var(--rule);background:var(--info-wash)">
    <div class="t-section" style="font-size:18px">Add a filter to reach these rows.</div>
    <p class="t-body" style="font-size:14px;max-width:70ch;margin-top:6px">You are asking for rows <b class="num">10,001</b> onwards of <b class="num">78,232</b>. Reaching them by page number means reading every row before them, which is slow enough to time out. A date range, a post or an advertisement narrows this to something a page can hold — and the filtered view is linkable, so you can send it to a colleague.</p>
    <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
      <a class="btn p" href="#">Filter by advertisement</a><a class="btn" href="#">Filter by date range</a>
      <span class="t-caption">Or <a href="#">export the whole table</a> — over 5,000 rows it runs as a queued job.</span></div>
  </div></div>` }));

/* ── errors ─────────────────────────────────────────────────── */
const errBox = (label, title, body, actions, colour) => `<div class="rec" style="border-left:4px solid ${colour}"><div class="rec-b">
  <span class="t-label">${label}</span>
  <div class="t-section" style="font-size:18px;margin:6px 0 5px">${title}</div>
  <p class="t-body" style="font-size:14px;max-width:62ch">${body}</p>
  <div style="margin-top:13px;display:flex;gap:9px;flex-wrap:wrap">${actions}</div></div></div>`;

w('StateErrors.dc.html', artboard({
  w: 1240, pad: true, body: `
${gateRule('', 'When something goes wrong')}
<p class="t-caption" style="max-width:74ch;margin-bottom:20px">Errors do not apologise and are never vague. Each says what happened, what it means for the work in hand, and the one thing that resolves it. Three failures that are routinely collapsed into one “Something went wrong” are kept distinct here, because they need three different responses.</p>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  ${errBox('Server error · 500', 'The applications list could not be read.',
    'The database did not answer within 30 seconds. Nothing you did caused this and nothing has been changed. The incident is recorded as <span class="ident" style="font-size:12px">err_9f31c204</span>; quote that reference if you report it.',
    '<a class="btn p" href="#">Try again</a><a class="btn" href="#">Report this to IT</a>', 'var(--rejected)')}
  ${errBox('Permission denied · 403', 'You cannot open application 10087779.',
    'It belongs to the <b>Faculty of Science</b>, and your access covers the <b>Faculty of Arts and its three departments</b>. This is a scope limit, not a mistake — ask the Registrar’s office if you need the wider scope, and the request is recorded.',
    '<a class="btn p" href="#">Back to your queue</a><a class="btn" href="#">Request wider access</a>', 'var(--brass)')}
  ${errBox('Window closed', 'The rectification window for this application closed on 19 Mar 2026, 5:00 pm.',
    'The candidate did not re-upload the experience certificate. The application returned to scrutiny with the deficiency unresolved, and that is recorded on its timeline. You can still decide the gate — the illegible document is the evidence you have.',
    '<a class="btn p" href="#">Open the dossier</a><a class="btn" href="#">Raise a fresh deficiency</a>', 'var(--info)')}
  ${errBox('Not yet open', 'Admit cards for post 2599 open on 18 Mar 2026.',
    'The window runs <b>18-03-2026</b> to <b>26-03-2026</b> and today is 28-08-2026. The window belongs to the post, so changing it means editing post 2599 — which is audited and visible to the candidate.',
    '<a class="btn" href="#">Edit post 2599</a><a class="btn" href="#">Back to bulk documents</a>', 'var(--pending)')}
</div>

<div style="height:26px"></div>
${gateRule('', 'With JavaScript disabled')}
<div class="rec"><div class="rec-b" style="display:flex;gap:30px;align-items:flex-start">
  <div style="flex:1">
    <p class="t-body" style="font-size:14px">Filtering, sorting, pagination and every form on both surfaces are plain HTML. The filter row is a <span class="ident" style="font-size:12px">&lt;form method="GET"&gt;</span> with a <b>Filter</b> button, sorting is a link, pagination is links, and the section picker is a native <span class="ident" style="font-size:12px">&lt;details&gt;</span>. Alpine adds selection, column visibility, density and the live countdown on top of a page that already works.</p>
    <p class="t-caption" style="margin-top:9px">Three things degrade honestly rather than disappearing: the countdown becomes a stated date and time, the dossier expander becomes a link to the record’s own page, and the counterfoil becomes a form that posts and returns to the next row in the queue.</p>
  </div>
  <div style="flex:0 0 420px">
    <div class="tbar" style="border-bottom:1px solid var(--rule)"><span class="t-caption">No JavaScript · everything still works</span>
      <span class="exp"><a href="#">CSV</a><a href="#">PDF</a><a href="#">Print</a></span></div>
    <table class="tbl"><thead>
      <tr><th style="width:100px"><a href="#" class="sortable">APP NO</a></th><th>CANDIDATE</th><th style="width:110px">SCRUTINY</th></tr>
      <tr class="filters"><td><input aria-label="Filter by application number"></td><td><input aria-label="Filter by candidate"></td>
        <td><button class="btn sm" style="width:100%">Filter</button></td></tr></thead>
      <tbody><tr><td class="ident">10087779</td><td>MOHAMMAD BASIM ZAHID</td><td>${badge('el', 'Eligible')}</td></tr>
        <tr><td class="ident">10087780</td><td>AISHA KHAN</td><td>${badge('pe', 'Pending')}</td></tr></tbody></table>
    <div class="pager"><span>1–100 of 78,232</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">›</a></span></div>
  </div>
</div></div>` }));

/* ── redacted · read-only · impersonated · locked ───────────── */
w('StateRedacted.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Reconciliation')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Payment reconciliation</h1>
        <div class="sub"><b>Finance access — name and application number only.</b> Candidate details are outside finance scope and are not loaded.</div></div>
      <div class="mh-tools"><button class="btn sm">Columns ▾</button><button class="btn sm">Export ▾</button></div>
    </div><div class="scoperule" style="margin-top:12px"></div></div>
    <div class="withmargin">${mg(['finance', 'scope', '-', 'PII withheld', 'DPDP 2023', '-', 'exports', 'audited', '-', 'seq 4,181'])}
      <div class="body">
        <div class="notice" style="margin-bottom:18px"><b>This is a redacted view, laid out for what it does contain.</b> It is not the applicant table with columns blanked out: category, date of birth, disability, religion, address and every document are not queried, not rendered and not exportable from here. What a reconciliation needs is the order, the amount, the state and enough identity to match a bank statement — which is the name and the application number.</div>
        <div style="display:flex;border-top:1px solid var(--rule-strong);border-bottom:1px solid var(--rule-strong);padding:16px 0;margin-bottom:20px">
          <div style="flex:1"><div class="t-figure num">₹93,14,500</div><div class="t-label" style="margin-top:5px">✕ Failed · 27.0%</div></div>
          <div style="flex:1;border-left:1px solid var(--rule-strong);padding-left:20px"><div class="t-figure num">₹22,25,500</div><div class="t-label" style="margin-top:5px">◦ Awaited · 6.4%</div></div>
          <div style="flex:1;border-left:1px solid var(--rule-strong);padding-left:20px"><div class="t-figure num">18,629</div><div class="t-label" style="margin-top:5px">Orders to reconcile</div></div>
          <div style="flex:1;border-left:1px solid var(--rule-strong);padding-left:20px"><div class="t-figure num">₹500</div><div class="t-label" style="margin-top:5px">Standard fee</div></div>
        </div>
        ${TBAR(0).replace('<b>0 selected</b><a href="#">Delete</a><a href="#">Clear</a>', 'Select orders to mark reconciled')}
        <table class="tbl"><caption>Payment orders · filtered to state ✕ Failed · 18,629 rows · finance scope, no personal data beyond name and application number</caption>
          <thead><tr><th style="width:34px"><input type="checkbox" aria-label="Select all rows on this page"></th>
            <th style="width:150px">ORDER</th><th style="width:110px">APP NO</th><th>NAME</th>
            <th style="width:88px">AMOUNT</th><th style="width:118px">ATTEMPTED</th><th style="width:130px">STATE</th>
            <th style="width:150px">GATEWAY REFERENCE</th><th style="width:120px">ACTIONS</th></tr>
            <tr class="filters"><td></td><td><input aria-label="Filter by order"></td><td><input aria-label="Filter by application number"></td>
              <td><input aria-label="Filter by name"></td><td><input aria-label="Filter by amount"></td>
              <td><input aria-label="Filter by date" placeholder="range"></td>
              <td><select aria-label="Filter by state"><option>✕ Failed</option></select></td><td></td><td></td></tr></thead>
          <tbody>
          ${[['rzp_QK4t81nHc', '10087780', 'AISHA KHAN', '23-01-2026', 're', 'Failed', 'BANK_TIMEOUT'],
      ['rzp_QK4t7wPmR', '10087779', 'MOHAMMAD BASIM ZAHID', '23-01-2026', 're', 'Failed', 'USER_DROPPED'],
      ['bd_5512908441', '10087781', 'RAKESH KUMAR VERMA', '24-01-2026', 're', 'Failed', 'BANK_TIMEOUT'],
      ['rzp_QK9m02aZx', '10087782', 'FATIMA SIDDIQUI', '24-01-2026', 'pe', 'Awaited', 'CALLBACK_LOST'],
      ['bd_5512911037', '10087783', 'S. ANANTHAKRISHNAN', '25-01-2026', 're', 'Failed', 'CARD_DECLINED']]
      .map(([o, a, n, d, s, word, ref]) => `<tr><td><input type="checkbox" aria-label="Select order ${o}"></td>
        <td class="ident">${o}</td><td class="ident">${a}</td><td>${n}</td><td class="r num">₹500</td>
        <td class="num">${d}</td><td>${badge(s, word)}</td><td class="ident faint" style="font-size:12px">${ref}</td>
        <td><a href="#">Re-check</a> · <a href="#">Note</a></td></tr>`).join('')}
          </tbody></table>
        <div class="pager"><span>Showing 1–5 of 18,629</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">…</a><a href="#">100</a><a href="#">›</a></span><span>100 per page ▾</span></div>
        <div class="notice" style="margin-top:16px"><b>CALLBACK_LOST is the row that matters.</b> The candidate was debited and the bank never told us. It is <b>awaited</b>, not failed, and the candidate is shown a state that never invites a second payment — which is where a large part of ₹93.14 lakh came from in the system being replaced.</div>
      </div>
    </div>
  </main></div>` }));

w('StateReadOnly.dc.html', artboard({
  w: 1440, body: `<div class="art">
  <div class="actorbar"><span><b>IMPERSONATING</b> · you are signed in as <b>AISHA KHAN</b>, started by n.sarwar at 14:02 · every action is recorded against both of you</span>
    <span style="display:flex;gap:16px;align-items:center"><span class="ident" style="font-size:12px;color:var(--paper)">08:12 elapsed</span><a href="#">End now</a></span></div>
  <div class="shell">${NAV('Applications')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Applicant dossiers</h1>
        <div class="sub"><b>Audit access — read only, university-wide.</b> You can read every record and verify every chain. You cannot change anything, and nothing here is disabled to imply otherwise.</div></div>
      <div class="mh-tools"><span class="ro">New application · not available in audit access</span></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['auditor', 'read only', '-', 'university', 'wide', '-', 'chain head', '4,182'])}
      <div class="body">
        <div class="tbar"><span class="t-caption">Selection and bulk actions are not part of audit access</span>
          <div style="display:flex;gap:12px;align-items:center"><span class="exp">${['Copy', 'CSV', 'Excel', 'PDF', 'Print', 'Columns'].map((e) => `<a href="#">${e}</a>`).join('')}</span>
          <input class="search" placeholder="Search all columns"></div></div>
        <table class="tbl"><caption>Applicant dossiers for post 2599 · audit access · 106 rows</caption>
          <thead><tr><th style="width:110px">APP NO</th><th>CANDIDATE</th><th style="width:104px">CATEGORY</th>
            <th style="width:118px">AGE · computed</th><th style="width:104px">SUBMITTED</th>
            <th style="width:128px">SCRUTINY</th><th style="width:170px">DECIDED BY</th><th style="width:200px">ACTIONS</th></tr></thead>
          <tbody>
          ${[['10087779', 'MOHAMMAD BASIM ZAHID', 'General', '41y 3m', '23-01-2026', 'el', 'Eligible', 'scrutiny.arts · 26-02'],
      ['10087780', 'AISHA KHAN', 'OBC-NCL', '41y 3m', '23-01-2026', 'pe', 'Pending', '—'],
      ['10087781', 'RAKESH KUMAR VERMA', 'SC', '36y 8m', '24-01-2026', 'el', 'Eligible', 'scrutiny.arts · 26-02'],
      ['10087782', 'FATIMA SIDDIQUI', 'OBC-NCL', '29y 1m', '24-01-2026', 're', 'Not eligible', 'scrutiny.arts · 27-02']]
      .map(([no, n, c, a, d, s, word, by]) => `<tr><td class="ident">${no}</td><td>${n}</td><td>${c}</td>
        <td class="num">${a}</td><td class="num">${d}</td><td>${badge(s, word)}</td><td class="t-caption">${by}</td>
        <td><a href="#">View record</a> · <a href="#">Audit entries</a></td></tr>`).join('')}
          </tbody></table>
        <div class="pager"><span>Showing 1–4 of 106</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">›</a></span><span>100 per page ▾</span></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:24px">
          <div>${gateRule('', 'What read-only looks like')}
            <div class="rec"><div class="rec-b">
              <p class="t-caption" style="margin-bottom:12px">A mutating control is not rendered and then disabled. It is replaced, at the same position and the same size, by a ruled statement of the control that would be there — so the page an auditor reads has the same shape as the page an officer works, and neither has to wonder what the other sees.</p>
              <div style="display:flex;gap:20px;align-items:flex-start">
                <div style="flex:1"><div class="t-label" style="margin-bottom:7px">Scrutiny officer</div>
                  <button class="btn p">Save decisions</button>
                  <div style="margin-top:9px"><button class="btn d">Withdraw advertisement</button></div></div>
                <div style="flex:1;border-left:1px solid var(--rule);padding-left:20px"><div class="t-label" style="margin-bottom:7px">Auditor</div>
                  <span class="ro">Save decisions · not available in audit access</span>
                  <div style="margin-top:9px"><span class="ro">Withdraw · not available in audit access</span></div></div>
              </div>
            </div></div>
          </div>
          <div>${gateRule('', 'A committee, outside its window')}
            <div class="rec"><div class="rec-b">
              <div class="notice" style="border-left-color:var(--pending);margin-bottom:12px"><b>This committee meets on 14 Apr 2026 at 10:00.</b> The 41 applications for post 2599 are listed below and will become readable when the window opens. Papers are not circulated in advance.</div>
              <div class="notice" style="border-left-color:var(--rule-strong);background:var(--paper-sunk);margin-bottom:12px"><b>The window closed on 16 Apr 2026 at 17:00.</b> Your scores are recorded and sealed. You can read what you submitted; you cannot change it.</div>
              <div class="rec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="rec-b">
                <b class="t-sub">✕ Quorum is not met. The meeting cannot proceed.</b>
                <p class="t-body" style="font-size:14px;margin-top:5px">Four members are required and <b>three</b> have signed in. Scoring is closed until a fourth member joins, and any score entered before quorum would be void.</p>
                <div style="margin-top:10px;font-size:13px">
                  <div style="padding:5px 0;border-bottom:1px solid var(--rule)">${badge('el', 'Present')} Prof. S. Ahmad · Chair</div>
                  <div style="padding:5px 0;border-bottom:1px solid var(--rule)">${badge('el', 'Present')} Prof. M. Iqbal · Subject expert</div>
                  <div style="padding:5px 0;border-bottom:1px solid var(--rule)">${badge('el', 'Present')} Dr R. Menon · Subject expert</div>
                  <div style="padding:5px 0">${badge('re', 'Absent')} Registrar’s nominee · <a href="#">record an apology and a substitute</a></div>
                </div>
              </div></div>
            </div></div>
          </div>
        </div>
      </div>
    </div>
  </main></div></div>` }));

w('StateLocked.dc.html', artboard({
  w: 1240, pad: true, body: `
${gateRule('', 'Locked, and why')}
<p class="t-caption" style="max-width:76ch;margin-bottom:22px">Two different locks, drawn differently on purpose. One is permanent and belongs to the register; the other is temporary and belongs to a deadline. Neither is punitive, and neither hides what it locked.</p>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:26px">
  <div>
    <div class="t-label" style="margin-bottom:8px">Sealed · the submitted dossier</div>
    <div class="rec"><div class="rec-h"><span class="t-sub">Application 2599/2026/00412 · snapshot #1</span><span class="chip">▪ sealed 23-01-2026 21:24</span></div>
      <div class="rec-b">
        <p class="t-body" style="font-size:14px">This is what the candidate submitted, and it is what the scrutiny office reads. It cannot be edited by the candidate, by an officer, or by a super administrator. A rectification does not change it — it writes snapshot #2 alongside it, and both are kept.</p>
        <div style="margin-top:14px">
          <div class="dl" style="grid-template-columns:170px 1fr;font-size:14px">
            <dt>Name</dt><dd>AISHA KHAN</dd>
            <dt>Category</dt><dd>OBC-NCL</dd>
            <dt>Experience claimed</dt><dd>6 years 4 months <span class="t-caption">(2 records)</span></dd>
          </div>
        </div>
        <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
          <span class="ro">Edit · the dossier is sealed</span>
          <span class="t-caption">Snapshot #2 · <a href="#">compare the two</a></span></div>
      </div></div>
    <p class="t-caption" style="margin-top:10px">Permanence is stated as a fact about the record, not as a refusal directed at the reader.</p>
  </div>
  <div>
    <div class="t-label" style="margin-bottom:8px">Temporarily open · a deficiency window</div>
    <div class="rec"><div class="rec-h"><span class="t-sub">Part A · during rectification</span>
      <span class="chip" style="border-color:var(--brass);color:var(--brass)">closes 19 Mar 2026, 5:00 pm</span></div>
      <div class="rec-b" style="padding:0">
        ${[['A1', 'Personal details', 0], ['A2', 'Photographs & signature', 0], ['A3', 'Addresses', 0],
    ['A4', 'Institutions attended', 0], ['A5', 'Academic qualifications', 0], ['A6', 'Employment history', 1],
    ['A7', 'Research summary', 0], ['A8', 'Referees', 0], ['A9', 'Testimonials', 0],
    ['A10', 'Declarations', 0], ['A11', 'Other information', 0]]
      .map(([n, t, open]) => `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 14px;border-bottom:1px solid var(--rule);${open ? 'background:var(--brass-wash);border-left:3px solid var(--brass)' : ''}">
        <span style="font-size:13px;${open ? 'font-weight:600' : 'color:var(--ink-faint)'}"><span class="ident" style="font-size:12px">${n}</span> ${t}</span>
        ${open ? '<span class="b pe"><span class="g">▌</span> open for rectification</span>' : '<span class="t-caption">▪ locked</span>'}</div>`).join('')}
      </div></div>
    <p class="t-caption" style="margin-top:10px">A locked section still opens and still reads. It simply has no fields — a deficiency window re-opens what the scrutiny office named, and nothing else. Trying to save an unnamed section is refused server-side, not merely hidden.</p>
  </div>
</div>

<div style="height:26px"></div>
<div class="rec" style="border-left:4px solid var(--info)"><div class="rec-b">
  <b class="t-sub">Scoring blocked pending ratification — the calm state</b>
  <p class="t-body" style="font-size:14px;max-width:78ch;margin-top:5px">Where a rule awaits Executive Council ratification, both surfaces say so and neither shows a partial total for it. The officer sees the same sentence the candidate sees, so nobody is working from a number the other cannot see.</p>
  <div style="display:flex;gap:24px;margin-top:14px">
    <div style="flex:1" class="notice"><b>Candidate:</b> Impact-factor scoring is not applied. It awaits Executive Council ratification of two points of interpretation. Your claims are recorded in full.</div>
    <div style="flex:1" class="notice"><b>Officer:</b> Impact-factor scoring is blocked at <span class="ident" style="font-size:12px">ugc-teaching-2018@1 rule 4.1.II.c</span>. 1,204 claims are recorded and unscored. Decide scrutiny on the remaining lines; the provisional total excludes this rule and says so on the printed sheet.</div>
  </div>
</div></div>` }));

/* ── print ──────────────────────────────────────────────────── */
w('PrintForm.dc.html', artboard({
  w: 794, body: `<div class="sheet" style="width:794px;min-height:1123px">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #000;padding-bottom:9px">
    <div><h1>ALIGARH MUSLIM UNIVERSITY, ALIGARH</h1>
      <div style="font-size:10.5px;margin-top:2px">Application for the post of <b>System Manager</b>, Prof. M.N. Farooqui Computer Centre</div>
      <div style="font-size:10.5px">Advertisement No. 2/2026/NT dated 22.01.2026 · Post 2599 · General (Non-Teaching) · Pay Level-12</div>
      <div style="font-size:10.5px">Application No. <b>2599/2026/00412</b> · submitted 23-01-2026 21:24 · snapshot #1</div></div>
    <div style="width:74px;height:95px;border:1px solid #000;font-size:8px;text-align:center;padding-top:38px;flex:0 0 auto">PHOTO<br>35 × 45 mm</div>
  </div>
  <table style="margin-top:11px"><tbody>
    <tr><th style="width:24%">1. Name in full</th><td colspan="3">AISHA KHAN</td></tr>
    <tr><th>2. Father’s name</th><td style="width:26%">MOHAMMAD YUSUF KHAN</td><th style="width:24%">3. Mother’s name</th><td>RUKHSANA KHAN</td></tr>
    <tr><th>4. Date of birth</th><td>26-11-1984</td><th>5. Age on 07-03-2026</th><td>41 years 3 months <i style="font-size:9px">(CRR Rule 14)</i></td></tr>
    <tr><th>6. Gender</th><td>Female</td><th>7. Category</th><td>OBC-NCL</td></tr>
    <tr><th>8. Religion</th><td>Islam</td><th>9. Disability</th><td>None · 0%</td></tr>
    <tr><th>10. Correspondence address</th><td colspan="3">506, 5th Floor, IT Palm Court Apartments, Dodhpur Road, opp. Noor Manzil, Civil Lines, Aligarh, Uttar Pradesh 202001</td></tr>
    <tr><th>11. Permanent address</th><td colspan="3">Same as correspondence address</td></tr>
    <tr><th>12. Domicile</th><td colspan="3">Aligarh, Uttar Pradesh</td></tr>
    <tr><th>13. Email and mobile</th><td colspan="3">aisha.khan@example.com · 9412 xxx 118</td></tr>
  </tbody></table>
  <div style="font:600 10px/1 var(--font-ui);letter-spacing:.08em;margin:13px 0 4px">14. ACADEMIC QUALIFICATIONS</div>
  <table><thead><tr><th>Examination</th><th>Board or university</th><th style="width:52px">Year</th><th style="width:52px">%</th><th style="width:62px">CGPA</th></tr></thead><tbody>
    <tr><td>Secondary School Certificate</td><td>Aligarh Muslim University</td><td>2000</td><td>66.60</td><td>—</td></tr>
    <tr><td>Senior Secondary Certificate</td><td>Aligarh Muslim University</td><td>2002</td><td>60.75</td><td>—</td></tr>
    <tr><td>B.Tech (Computer Science)</td><td>Biju Patnaik University of Technology</td><td>2005</td><td>—</td><td>6.28</td></tr>
    <tr><td>M.C.A.</td><td>Biju Patnaik University of Technology</td><td>2009</td><td>—</td><td>7.10</td></tr>
  </tbody></table>
  <div style="font:600 10px/1 var(--font-ui);letter-spacing:.08em;margin:13px 0 4px">15. EMPLOYMENT HISTORY</div>
  <table><thead><tr><th>Organisation</th><th>Designation</th><th style="width:78px">Pay</th><th style="width:130px">From — to</th><th style="width:62px">Duration</th></tr></thead><tbody>
    <tr><td>Aligarh Muslim University</td><td>Technical Assistant</td><td>Level-6</td><td>02-08-2019 — 31-03-2023</td><td>3y 8m</td></tr>
    <tr><td>Softlogic Systems Pvt Ltd, Noida</td><td>Systems Engineer</td><td>₹6.4 lakh p.a.</td><td>15-06-2016 — 30-07-2019</td><td>3y 1m</td></tr>
    <tr><td colspan="4" style="text-align:right"><b>Total experience computed on 07-03-2026</b></td><td><b>6y 4m</b></td></tr>
  </tbody></table>
  <div style="font:600 10px/1 var(--font-ui);letter-spacing:.08em;margin:13px 0 4px">16. DECLARATION</div>
  <p style="font-size:10.5px">I declare that the particulars given above are true to the best of my knowledge and belief, that no criminal proceedings are pending against me, and that I have not been dismissed or removed from service. I understand that any suppression of fact will render my candidature liable to cancellation at any stage of the process, and after appointment.</p>
  <div style="display:flex;justify-content:space-between;margin-top:34px;font-size:10.5px">
    <span>Date: 23-01-2026 &nbsp;&nbsp; Place: Aligarh</span><span>____________________________<br>Signature of the candidate</span></div>
  <div style="margin-top:20px;border:1px solid #000;padding:7px 9px;font-size:9.5px">
    <b>FOR OFFICE USE ONLY</b> &nbsp; Received on ____________ &nbsp; Receipt no ____________ &nbsp; Scrutinised by ____________ &nbsp; Eligible ☐ &nbsp; Not eligible ☐ &nbsp; Remark ______________________</div>
  <div style="margin-top:14px;border-top:1px solid #000;padding-top:5px;font-size:9px;display:flex;justify-content:space-between">
    <span>Page 1 of 4 · generated from snapshot #1 on 28-08-2026</span>
    <span>ugc-nt-2026@1 · frozen 22-01-2026 · audit seq 4,182</span></div>
</div>` }));

w('PrintAttendance.dc.html', artboard({
  w: 1123, body: `<div class="sheet" style="width:1123px;min-height:794px;padding:36px 44px">
  <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #000;padding-bottom:8px">
    <div><h1>ALIGARH MUSLIM UNIVERSITY, ALIGARH</h1>
      <div style="font-size:10.5px">Attendance register — written test</div>
      <div style="font-size:10.5px">Post 2599 · System Manager, Prof. M.N. Farooqui Computer Centre · Advertisement 2/2026/NT</div>
      <div style="font-size:10.5px">Report type: <b>Scrutiny eligible only</b> · 7 candidates · with photograph · with barcode</div></div>
    <div style="text-align:right;font-size:10.5px">Centre: <b>Kennedy Hall Complex</b><br>Date: 22-03-2026 &nbsp; Session: 10:00–12:00<br>Sheet 1 of 1</div>
  </div>
  <table style="margin-top:10px"><thead><tr>
    <th style="width:34px">S.No</th><th style="width:82px">Roll no</th><th style="width:60px">Photo</th>
    <th>Name</th><th style="width:130px">Father’s name</th><th style="width:70px">Category</th>
    <th style="width:80px">Application</th><th style="width:96px">Barcode</th>
    <th style="width:120px">Signature</th><th style="width:66px">Present</th></tr></thead><tbody>
    ${[['1', '25990001', 'MOHAMMAD BASIM ZAHID', 'MOHAMMAD ZAHID', 'General', '10087779'],
    ['2', '25990002', 'AISHA KHAN', 'MOHAMMAD YUSUF KHAN', 'OBC-NCL', '10087780'],
    ['3', '25990003', 'RAKESH KUMAR VERMA', 'SHIV KUMAR VERMA', 'SC', '10087781'],
    ['4', '25990004', 'S. ANANTHAKRISHNAN', 'S. SUBRAMANIAN', 'General', '10087783'],
    ['5', '25990005', 'PRIYA RAMACHANDRAN', 'R. RAMACHANDRAN', 'OBC-NCL', '10087784'],
    ['6', '25990006', 'MOHD ARSHAD ALI', 'MOHD SHAKEEL ALI', 'General', '10087785'],
    ['7', '25990007', 'SUNITA DEVI', 'RAM PRASAD', 'ST', '10087786']]
      .map(([s, r, n, f, c, a]) => `<tr style="height:52px">
      <td style="text-align:center">${s}</td><td><b>${r}</b></td>
      <td style="padding:2px"><div style="width:38px;height:46px;border:1px solid #000"></div></td>
      <td>${n}</td><td>${f}</td><td>${c}</td><td>${a}</td>
      <td style="padding:2px"><div style="height:34px;background:repeating-linear-gradient(90deg,#000 0 2px,#fff 2px 4px,#000 4px 5px,#fff 5px 9px)"></div>
        <div style="font-size:7.5px;text-align:center">${r}</div></td>
      <td></td><td></td></tr>`).join('')}
  </tbody></table>
  <div style="display:flex;justify-content:space-between;margin-top:30px;font-size:10.5px">
    <span>Candidates present: ________ &nbsp;&nbsp; Absent: ________</span>
    <span>____________________________<br>Invigilator’s signature</span>
    <span>____________________________<br>Centre Superintendent</span></div>
  <div style="margin-top:16px;border-top:1px solid #000;padding-top:5px;font-size:9px;display:flex;justify-content:space-between">
    <span>Generated 28-08-2026 04:14 by exam.admin · job 4,217 · this sheet supersedes any earlier generation for this post</span>
    <span>ugc-nt-2026@1 · frozen 22-01-2026</span></div>
  <p style="font-size:9px;margin-top:8px">Printed black on white with no interface furniture. On a multi-page register the table header repeats on every sheet, and each sheet carries its own page number, the job it came from and the ruleset in force.</p>
</div>` }));

console.log('states, dark and print written');
