import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, NAV } from './shared.mjs';
import { mg } from './build-admin-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

const fld = (label, id, val, help = '', width = '100%', bad = false) => `<div style="margin-bottom:13px">
  <label class="t-label" style="display:block;margin-bottom:5px" for="${id}">${label}</label>
  <input class="inp sm ${bad ? 'bad' : ''}" id="${id}" value="${val}" style="width:${width}" ${help ? `aria-describedby="${id}h"` : ''}>
  ${help ? `<div class="help" id="${id}h" style="font-size:12px">${help}</div>` : ''}</div>`;
const sel = (label, id, opts, help = '') => `<div style="margin-bottom:13px">
  <label class="t-label" style="display:block;margin-bottom:5px" for="${id}">${label}</label>
  <select class="inp sm" id="${id}">${opts.map((o) => `<option>${o}</option>`).join('')}</select>
  ${help ? `<div class="help" style="font-size:12px">${help}</div>` : ''}</div>`;

/* ── A8 · Advertisement builder ─────────────────────────────── */
w('AdvertBuilder.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Publishing')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">New advertisement</h1>
        <div class="sub">Draft <span class="ident">2/2026/NT</span> · 4 posts · not published · nothing here is visible to a candidate yet</div></div>
      <div class="mh-tools"><button class="btn sm">Save draft</button><button class="btn p sm">Review and publish</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['draft', 'not frozen', '-', 'ruleset in', 'force today', 'ugc-nt-2026@1', '-', 'OU tree', 'live', '-', 'author', 'r.admin'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div class="rec"><div class="rec-b">
            ${gateRule('', 'The advertisement')}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 20px">
              ${fld('Advertisement number', 'an', '2/2026/NT', 'Faculty-issued. It becomes part of every application number under it.')}
              ${fld('Dated', 'dt', '22-01-2026')}
              <div style="grid-column:1/3">${fld('Title', 'ti', 'Non-teaching posts in the University')}</div>
              ${sel('Type', 'ty', ['General', 'Local'], 'Local advertisements are created and published by a Dean’s office within its own subtree.')}
              ${sel('Appointment nature', 'na', ['Regular', 'Contractual', 'Guest', 'Deputation'])}
              <div style="grid-column:1/3">${fld('Slug', 'sl', '2-2026-nt-non-teaching-posts', 'Derived from the advertisement number and the title. Unique by advertisement number — no timestamp suffix.')}</div>
            </div>
          </div></div>

          <div style="height:20px"></div>
          ${gateRule('', 'Child posts')}
          <div class="rec"><table class="tbl">
            <caption>Each post is a designation in an organisational unit, checked against sanctioned strength</caption>
            <thead><tr><th style="width:58px">POST</th><th style="width:180px">DESIGNATION</th><th>ORGANISATIONAL UNIT</th>
              <th style="width:96px">PAY LEVEL</th><th style="width:150px">SANCTIONED</th><th style="width:82px">VACANCIES</th><th style="width:96px"></th></tr></thead><tbody>
            ${[['2599', 'System Manager', 'Prof. M.N. Farooqui Computer Centre', '/1/44/', 'Level-12', 1, 0, 1, 1, true],
    ['2604', 'Senior Technical Assistant', 'Electronics Engineering, ZHCET', '/1/27/119/', 'Level-6', 4, 2, 2, 2, true],
    ['2607', 'Junior Assistant', 'Office of the Controller of Examinations', '/1/03/', 'Level-2', 14, 8, 6, 6, true],
    ['2609', 'Laboratory Assistant', 'Department of Physics, Faculty of Science', '/1/19/207/', 'Level-4', 5, 3, 2, 3, false]]
      .map(([id, d, ou, path, lvl, s, f, vac, want, ok]) => `<tr>
      <td class="ident">${id}</td><td><b>${d}</b></td>
      <td>${ou}<div class="t-caption ident" style="font-size:11px">${path}</div></td><td>${lvl}</td>
      <td class="num">${s} sanctioned · ${f} filled<div class="t-caption">${vac} vacant</div></td>
      <td class="r"><b class="num" style="${ok ? '' : 'color:var(--rejected)'}">${want}</b></td>
      <td><a href="#">Edit</a> · <a href="#">Remove</a></td></tr>
      ${ok ? '' : `<tr><td colspan="7" style="background:var(--paper);border-left:3px solid var(--rejected);height:auto;padding:9px 12px">
        <b class="b re"><span class="g">✕</span> 3 vacancies advertised against 2 vacant posts.</b>
        <span class="t-caption"> Sanctioned strength for Laboratory Assistant in Physics is 5, of which 3 are filled. Reduce to 2, or attach the sanction that creates the third — <a href="#">record a sanction</a>.</span></td></tr>`}`).join('')}
          </tbody></table>
          <div style="padding:9px 12px;border-top:1px solid var(--rule)"><button class="btn sm">Add a post</button></div></div>

          <div style="height:20px"></div>
          ${gateRule('', 'Dates')}
          <div class="rec"><div class="rec-b">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 20px">
              ${fld('Opening', 'op', '22-01-2026')}
              ${fld('Closing', 'cl', '07-03-2026')}
              ${fld('Payment last date', 'pl', '07-03-2026', 'Cannot be later than closing.')}
            </div>
            <div class="notice" style="border-left-color:var(--eligible);background:var(--green-wash)">
              <b>✓ The window is 45 days.</b> The statutory minimum is 30.<span class="cite" style="margin-top:5px">CRR · DoPT O.M. Misc.14017/15/2015-Estt.(RR)</span></div>
            <div style="margin-top:14px">
              <div class="t-label" style="margin-bottom:6px">If you shorten it</div>
              <div class="rec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="rec-b">
                <b class="t-sub">✕ A 22-day window cannot be published.</b>
                <p class="t-body" style="font-size:14px;margin-top:4px">Closing 12-02-2026 is 22 days after opening. The minimum is <b>30 days</b>, so the earliest closing date is <b>21-02-2026</b>.</p>
                <p class="t-caption" style="margin-top:6px">This is refused at publication, not flagged afterwards. A short window is the single most common ground on which a recruitment is set aside.</p>
                <div style="margin-top:10px"><button class="btn sm">Set closing to 21-02-2026</button></div>
              </div></div>
            </div>
          </div></div>
        </div>

        <aside style="flex:0 0 340px">
          <div class="rec" style="border-color:var(--brass);border-left:4px solid var(--brass)"><div class="rec-h"><span class="t-label">Publishing freezes two things</span></div><div class="rec-b">
            <p class="t-body" style="font-size:14px">Publication is the moment this advertisement stops being editable in the ways that matter. Two things are copied and sealed:</p>
            <div style="margin-top:12px;padding:10px 12px;background:var(--paper-sunk);border:1px solid var(--rule)">
              <div class="t-label">The ruleset</div>
              <div class="ident" style="margin-top:3px">ugc-nt-2026@1</div>
              <p class="t-caption" style="margin-top:4px">Every candidate under this advertisement is judged by this version, even if it is superseded tomorrow. Version @2 becomes effective 01-07-2026 and will not touch these posts.</p></div>
            <div style="margin-top:10px;padding:10px 12px;background:var(--paper-sunk);border:1px solid var(--rule)">
              <div class="t-label">The organisational-unit tree</div>
              <div class="ident" style="margin-top:3px">snapshot #1,208</div>
              <p class="t-caption" style="margin-top:4px">301 units, 111 departments, as they stand today. If Physics is merged into a school next year, these four posts still name the unit that advertised them.</p></div>
            <div class="field" style="margin-top:16px"><label for="pc">Type <b>PUBLISH</b> to confirm</label>
              <input class="inp" id="pc" placeholder="PUBLISH"></div>
            <button class="btn p" style="width:100%">Publish 4 posts</button>
            <p class="t-caption" style="margin-top:9px">Publishing makes 4 posts visible to 55,050 registered candidates and writes one audit entry per post. Withdrawal after publication is possible and is itself published as a corrigendum.</p>
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">Before it can publish</span></div><div class="rec-b" style="padding:0">
            ${[['Advertisement number and title', 'el', 'Set'], ['At least one child post', 'el', '4 posts'],
      ['Every post has a designation and a unit', 'el', 'Checked'],
      ['Vacancies within sanctioned strength', 're', '1 breach'],
      ['Window of 30 days or more', 'el', '45 days'],
      ['Notification document attached', 'pe', 'Not attached']]
        .map(([t, s, word]) => `<div style="display:flex;justify-content:space-between;gap:10px;padding:8px 14px;border-bottom:1px solid var(--rule);font-size:13px">
        <span>${t}</span>${badge(s, word)}</div>`).join('')}
          </div></div>
        </aside>
      </div>
    </div>
  </main></div>` }));

/* ── A10 · Attendance sheet generator ───────────────────────── */
w('Attendance.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Attendance')}
  <main class="main">
    <div class="masthead"><h1 class="t-page">Attendance sheets</h1>
      <div class="sub">Printable attendance registers for a written test or an interview · generated as a queued job</div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['roll numbers', 'generated', '18-03-2026', '-', 'template', 'attendance-nt', '-', 'jobs audited'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <form style="flex:0 0 340px" method="POST">
          ${gateRule('', 'Generate a sheet')}
          ${sel('Advertisement', 'ad1', ['2/2026/NT — Non-teaching posts'])}
          ${sel('Post — depends on the advertisement', 'po1', ['2599 · System Manager'], 'Only posts under the chosen advertisement are listed.')}
          <div style="margin-bottom:13px">
            <div class="t-label" style="margin-bottom:6px">Have roll numbers been generated?</div>
            <div class="notice" style="border-left-color:var(--eligible);background:var(--green-wash);font-size:13px">
              <b>✓ Yes — 58 roll numbers, generated 18-03-2026.</b> Range <span class="ident" style="font-size:12px">25990001–25990058</span>.</div>
          </div>
          ${sel('Report type', 'rt', ['Scrutiny eligible only', 'All applicants', 'Interview eligible only'], 'Two of these three read the eligibility gates directly.')}
          <div style="margin-bottom:13px"><div class="t-label" style="margin-bottom:6px">Include</div>
            <label style="display:flex;gap:9px;align-items:center;font-size:14px;height:30px"><input type="checkbox" checked style="width:18px;height:18px;accent-color:var(--green)"> Photograph</label>
            <label style="display:flex;gap:9px;align-items:center;font-size:14px;height:30px"><input type="checkbox" checked style="width:18px;height:18px;accent-color:var(--green)"> Barcode of the roll number</label>
            <label style="display:flex;gap:9px;align-items:center;font-size:14px;height:30px"><input type="checkbox" style="width:18px;height:18px;accent-color:var(--green)"> Blank column for remarks</label>
          </div>
          ${fld('Centre', 'ce', 'Kennedy Hall Complex')}
          ${fld('Date and session', 'ds', '22-03-2026 · 10:00–12:00')}
          <button class="btn p" style="width:100%">Generate the sheet</button>
          <p class="t-caption" style="margin-top:9px">A 106-row sheet with photographs is not a synchronous response. It runs as a job and each generation is audited.</p>
        </form>

        <div style="flex:1 1 auto;min-width:0;max-width:720px">
          <div class="rec" style="border-left:4px solid var(--pending)"><div class="rec-b">
            <span class="t-label">Queued — job 4,221</span>
            <h2 class="t-section" style="margin:6px 0 4px;font-size:18px">Building an attendance register for <b class="num">7</b> candidates.</h2>
            <p class="t-body" style="font-size:14px">Post 2599 · scrutiny eligible only · with photographs and barcodes.</p>
            <div class="pbar thin" style="margin-top:11px"><span class="awa" style="width:71%"></span></div>
            <div style="display:flex;justify-content:space-between;margin-top:5px"><span class="t-caption">5 of 7 rows composed</span><span class="t-caption num">started 04:31 · about 15 seconds left</span></div>
            <p class="t-caption" style="margin-top:10px">You can leave this page. The sheet appears below when it is ready and the link lives for 48 hours.</p>
          </div></div>

          <div class="rec" style="border-left:4px solid var(--rejected);margin-top:16px"><div class="rec-b">
            <span class="t-label">Blocked — roll numbers are not generated</span>
            <p class="t-body" style="font-size:14px;margin-top:5px">Post <b>2604 · Senior Technical Assistant</b> has 224 paid applications and <b>no roll numbers</b>. An attendance register is a list of roll numbers, so it cannot be built until they exist.</p>
            <p class="t-caption" style="margin-top:6px">Roll numbers are allocated in one run per post, in order of application number, and cannot be re-allocated once printed on an admit card.</p>
            <div style="margin-top:11px;display:flex;gap:9px"><a class="btn p sm" href="#">Generate roll numbers for post 2604</a><a class="btn sm" href="#">Choose another post</a></div>
          </div></div>

          <div style="height:20px"></div>
          ${gateRule('', 'Recent sheets')}
          <div class="rec"><table class="tbl"><thead><tr><th style="width:66px">JOB</th><th>SHEET</th>
            <th style="width:64px">ROWS</th><th style="width:120px">RUN</th><th style="width:120px">STATE</th><th style="width:96px"></th></tr></thead><tbody>
            <tr><td class="ident">4,221</td><td>Post 2599 · scrutiny eligible · photo, barcode<div class="t-caption">Kennedy Hall Complex · 22-03-2026 10:00</div></td>
              <td class="r num">7</td><td class="num">28-08 04:31</td><td>${badge('pe', 'Running')}</td><td>—</td></tr>
            <tr><td class="ident">4,204</td><td>Post 2607 · all applicants · photo<div class="t-caption">Sir Syed Hall · 20-03-2026 14:00</div></td>
              <td class="r num">954</td><td class="num">27-08 16:12</td><td>${badge('el', 'Ready')}</td><td><a href="#">Download</a></td></tr>
            <tr><td class="ident">4,158</td><td>Post 2604 · interview eligible only<div class="t-caption">Committee Room, ZHCET · 24-03-2026 11:00</div></td>
              <td class="r num">0</td><td class="num">25-08 10:40</td><td>${badge('re', 'Empty — no rows')}</td><td><a href="#">Why?</a></td></tr>
          </tbody></table></div>
          <div class="notice" style="margin-top:14px"><b>An empty sheet is a result, not a failure.</b> Job 4,158 returned nothing because no candidate for post 2604 is yet marked ✓ Eligible at interview — the written test has not been held. The job is kept with its parameters so the question “why was this empty?” has an answer months later.</div>
        </div>
      </div>
    </div>
  </main></div>` }));

/* ── A13 · Rules authoring ──────────────────────────────────── */
const ruleLine = (n, text, ref, changed = false) => `<div style="display:grid;grid-template-columns:44px 1fr 210px;gap:12px;padding:9px 0;border-bottom:1px solid var(--rule);${changed ? 'background:var(--brass-wash);margin:0 -12px;padding-left:12px;padding-right:12px;border-left:3px solid var(--brass)' : ''}">
  <span class="ident faint" style="font-size:12px">${n}</span>
  <span style="font-size:13px;line-height:1.5">${text}</span>
  <span class="ident faint" style="font-size:12px">${ref}</span></div>`;

w('RulesAuthoring.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Versions')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">ugc-teaching-2018</h1>
        <div class="sub">Version <b>@2</b> · draft · effective from <b>01-07-2026</b> · authored by <span class="ident">r.admin</span> on 27-08-2026</div></div>
      <div class="mh-tools"><span class="ro">Activate · you authored this version</span><button class="btn sm">Save draft</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['@1 active', 'since', '22-01-2018', '-', '@2 draft', 'effective', '01-07-2026', '-', 'author', 'r.admin', '-', 'verifier', 'not yet'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div class="rec" style="border-color:var(--brass);border-left:4px solid var(--brass);margin-bottom:20px"><div class="rec-b">
            <div style="display:flex;justify-content:space-between;gap:20px;align-items:flex-start">
              <div>
                <b class="t-sub">Awaiting a second reader.</b>
                <p class="t-body" style="font-size:14px;margin-top:4px;max-width:74ch">You wrote this version, so you cannot activate it. It needs a <span class="ident" style="font-size:12px">rules_verifier</span> who is not you to read it line by line and activate it. That is a rule about people, not a permission error — the separation exists because a statutory ruleset that one person can both write and switch on is a ruleset with no second reader.</p>
                <div style="margin-top:12px;display:flex;gap:26px">
                  <div><div class="t-label">Authored</div><div style="font-size:13px">r.admin · 27-08-2026 11:04</div></div>
                  <div><div class="t-label">Verified</div><div style="font-size:13px" class="faint">— not yet</div></div>
                  <div><div class="t-label">Activates</div><div style="font-size:13px" class="faint">01-07-2026, on verification</div></div>
                </div>
              </div>
              <div style="flex:0 0 auto;text-align:right">
                <button class="btn p sm">Send to a verifier</button>
                <div class="t-caption" style="margin-top:6px">2 verifiers available</div></div>
            </div>
          </div></div>

          ${gateRule('', 'Version @2 · what changed')}
          <div class="rec"><div class="rec-b">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px">
              <span class="t-label">Academic score, Column II · 5 lines · 2 changed</span>
              <span class="t-caption">Compared against <span class="ident" style="font-size:12px">@1</span>, active since 22-01-2018</span></div>
            ${ruleLine('4.1.II.a', 'Research papers in UGC-CARE listed journals — <b>10</b> points each, sole author; <b>5</b> where there is more than one author', 'App. II Table 2 row 1')}
            ${ruleLine('4.1.II.b', 'Books published by a national publisher — <b>10</b> points; international publisher — <b>12</b> points', 'row 2(a)')}
            ${ruleLine('4.1.II.c', 'Journal impact factor — <b>scoring not applied</b>, pending Executive Council ratification of two points of interpretation. Claims are recorded in full and left unscored.', 'row 3 · blocked', true)}
            ${ruleLine('4.1.II.d', 'Completed research projects — <b>10</b> points above ₹10 lakh, <b>5</b> below; a Co-Principal Investigator scores <b>50%</b> of the figure', 'row 4(b)')}
            ${ruleLine('4.1.II.e', 'Minimum research score for Associate Professor raised from <b>50</b> to <b>75</b>', 'cl. 4.1 II', true)}
            <div style="margin-top:14px;padding:11px 13px;background:var(--paper-sunk);border:1px solid var(--rule)">
              <div class="t-label" style="margin-bottom:4px">Every line carries its citation, and the citation is the point</div>
              <p class="t-caption">A rule without a clause reference cannot be defended, cannot be audited, and cannot be explained to the candidate it was applied to. A line that has no citation cannot be saved.</p>
            </div>
          </div></div>

          <div style="height:20px"></div>
          ${gateRule('', 'What activating @2 would do')}
          <div class="rec"><div class="rec-b">
            <table class="tbl" style="background:transparent"><tbody>
              <tr class="comfy"><td style="width:280px">Advertisements published before 01-07-2026</td><td><b>Unaffected.</b> They carry a frozen reference to @1 and keep it for their whole life, including litigation years later.</td></tr>
              <tr class="comfy"><td>Advertisements published on or after 01-07-2026</td><td>Freeze <span class="ident">ugc-teaching-2018@2</span> at publication.</td></tr>
              <tr class="comfy"><td>Scores already computed</td><td><b>Not recomputed.</b> A score is evidence of a decision taken under a stated rule; recomputing it would erase that.</td></tr>
              <tr class="comfy"><td>The audit chain</td><td>One <span class="ident" style="font-size:12px">ruleset.activated</span> entry naming the author, the verifier and the effective date.</td></tr>
            </tbody></table>
          </div></div>
        </div>

        <aside style="flex:0 0 320px">
          <div class="rec"><div class="rec-h"><span class="t-label">Versions</span></div><div class="rec-b" style="padding:0">
            ${[['@2', 'draft · awaiting a second reader', '01-07-2026', 'pe', 'Draft'],
    ['@1', 'active · governs 1,041 advertisements', '22-01-2018', 'el', 'Active'],
    ['@0', 'superseded', '18-07-2016', 'in', 'Superseded']]
      .map(([v, note, eff, s, word]) => `<div style="padding:11px 14px;border-bottom:1px solid var(--rule)">
      <div style="display:flex;justify-content:space-between;align-items:baseline"><span class="ident"><b>${v}</b></span>${badge(s, word)}</div>
      <div class="t-caption" style="margin-top:2px">${note}</div>
      <div class="t-caption num">effective ${eff}</div></div>`).join('')}
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">The verifier’s view</span></div><div class="rec-b">
            <p class="t-caption" style="margin-bottom:11px">The same screen, seen by <span class="ident" style="font-size:12px">r.verifier</span>:</p>
            <div class="rec" style="border-color:var(--eligible);border-left:4px solid var(--eligible)"><div class="rec-b">
              <b class="t-sub">You are the second reader for @2.</b>
              <p class="t-caption" style="margin-top:4px">Authored by r.admin, who cannot activate it. Read all five lines; activation records your name against every score computed under this version.</p>
              <div class="field" style="margin-top:12px"><label for="vc" style="font-size:14px">Type <b>ACTIVATE @2</b> to confirm</label>
                <input class="inp sm" id="vc" placeholder="ACTIVATE @2"></div>
              <button class="btn p sm" style="width:100%">Activate from 01-07-2026</button>
              <div style="margin-top:8px"><button class="btn d sm" style="width:100%">Return to the author with a note</button></div>
            </div></div>
          </div></div>
        </aside>
      </div>
    </div>
  </main></div>` }));

/* ── A15 · Master data ──────────────────────────────────────── */
const node = (depth, name, meta, state = '') => `<div style="display:flex;align-items:center;gap:8px;padding:5px 12px 5px ${12 + depth * 20}px;border-bottom:1px solid var(--rule);${state === 'on' ? 'background:var(--green-wash);border-left:3px solid var(--brass)' : ''}">
  <span style="color:var(--ink-faint);font-size:11px;width:10px">${state === 'leaf' ? '' : state === 'on' ? '▾' : '▸'}</span>
  <span style="flex:1;font-size:13px;${state === 'on' ? 'font-weight:600' : ''}">${name}</span>
  <span class="t-caption num">${meta}</span></div>`;

w('MasterData.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Organisational units')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Organisational units</h1>
        <div class="sub"><b class="num">301</b> units · 13 faculties · <b class="num">111</b> departments · 5 levels deep · snapshot #1,208 taken 22-01-2026</div></div>
      <div class="mh-tools"><button class="btn sm">Export the tree</button><button class="btn p sm">New unit</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['live tree', 'not frozen', '-', 'published', 'posts use', 'snapshot', '#1,208', '-', 'seq 3,102'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:0 0 470px">
          <div style="display:flex;gap:8px;margin-bottom:10px">
            <input class="search" style="flex:1;width:auto" placeholder="Find a unit — “physics”, “/1/19/”, code PHY">
            <button class="btn sm">Find</button></div>
          <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;margin-bottom:10px" class="t-caption">
            <a href="#">Aligarh Muslim University</a> ›
            <a href="#">Faculty of Science</a> ›
            <b>Department of Physics</b>
            <span class="ident" style="font-size:11px;margin-left:6px">/1/19/207/</span></div>
          <div class="rec">
            ${node(0, '<b>Aligarh Muslim University</b>', '301 units', 'on')}
            ${node(1, 'Faculty of Arts', '3 departments')}
            ${node(1, 'Faculty of Science', '11 departments', 'on')}
            ${node(2, 'Department of Chemistry', '4 sections')}
            ${node(2, 'Department of Physics', '6 sections', 'on')}
            ${node(3, 'Solid State Physics Section', '—', 'leaf')}
            ${node(3, 'Nuclear Physics Section', '—', 'leaf')}
            ${node(3, 'High Energy Physics Laboratory', '2 units')}
            ${node(4, 'Detector Development Unit', '—', 'leaf')}
            ${node(4, 'Computing Facility', '—', 'leaf')}
            ${node(2, 'Department of Mathematics', '3 sections')}
            ${node(1, 'Faculty of Engineering and Technology', '9 departments')}
            ${node(1, 'Prof. M.N. Farooqui Computer Centre', '—', 'leaf')}
            ${node(1, 'Office of the Controller of Examinations', '4 sections')}
            <div style="padding:8px 12px" class="t-caption">…287 more units. Collapsed branches stay collapsed when you come back.</div>
          </div>
          <p class="t-caption" style="margin-top:10px">Depth is the problem a tree of 301 nodes has. Three things keep it navigable: the path is always shown and is a <span class="ident" style="font-size:12px">/1/19/207/</span> you can paste, search jumps straight to a node and opens its ancestors, and the open state persists — an officer who works in Physics does not re-open five levels every morning.</p>
        </div>

        <div style="flex:1 1 auto;min-width:0">
          <div class="rec"><div class="rec-h"><span class="t-sub">Department of Physics</span><span class="ident">/1/19/207/</span></div>
            <div class="rec-b">
              <dl class="dl" style="grid-template-columns:180px 1fr;font-size:14px;row-gap:7px">
                <dt>Parent</dt><dd>Faculty of Science</dd>
                <dt>Code</dt><dd class="ident">PHY</dd>
                <dt>Kind</dt><dd>Department</dd>
                <dt>Sanctioned posts</dt><dd><b class="num">38</b> across 9 designations · <a href="#">view sanctioned strength</a></dd>
                <dt>Open recruitment</dt><dd><b class="num">1</b> post · 2609 Laboratory Assistant</dd>
                <dt>Dean’s-office scope</dt><dd>Users scoped to <span class="ident" style="font-size:12px">/1/19/</span> reach this unit and everything under it</dd>
              </dl>
              <div class="notice" style="margin-top:14px"><b>Renaming or moving a unit does not rewrite history.</b> Published posts hold a snapshot of the path they were advertised under. If Physics moves to a School of Basic Sciences next year, post 2609 still reads <span class="ident" style="font-size:12px">/1/19/207/</span> — and a report for 2026 still counts it where it was.</div>
            </div></div>

          <div style="height:20px"></div>
          ${gateRule('', 'The other lookup tables')}
          <div class="rec"><table class="tbl"><caption>15 lookup tables · every one audited, none deletable while a row references it</caption>
            <thead><tr><th>TABLE</th><th style="width:78px">ROWS</th><th style="width:130px">LAST CHANGED</th><th style="width:150px">CHANGED BY</th><th style="width:96px"></th></tr></thead><tbody>
            ${[['Designations', '284', '14-08-2026', 'r.admin'], ['Sanctioned strength', '1,902', '14-08-2026', 'r.admin'],
      ['Categories', '6', '02-01-2026', 'r.admin'], ['Pay levels', '18', '02-01-2026', 'r.admin'],
      ['Disability types', '21', '18-11-2025', 'r.admin'], ['Religions', '9', '18-11-2025', 'r.admin'],
      ['States and districts', '806', '30-06-2025', 'system'], ['Examination centres', '34', '11-08-2026', 'exam.admin'],
      ['Subjects', '147', '04-07-2026', 'r.admin'], ['Relaxation rules', '11', '22-01-2026', 'r.verifier']]
        .map(([t, n, d, by]) => `<tr><td><a href="#">${t}</a></td><td class="r num">${n}</td><td class="num">${d}</td>
        <td class="ident" style="font-size:12px">${by}</td><td><a href="#">Open</a></td></tr>`).join('')}
          </tbody></table>
          <div class="pager"><span>Showing 10 of 15</span><span></span><span><a href="#">Show all</a></span></div></div>
        </div>
      </div>
    </div>
  </main></div>` }));

/* ── A16 · Committee workspace ──────────────────────────────── */
w('Committee.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Committees')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Selection committee · post 884</h1>
        <div class="sub">Assistant Professor — Computer Science · meeting <b>14 Apr 2026, 10:00</b> · window open until 16 Apr 2026, 17:00 · <b>12 candidates</b></div></div>
      <div class="mh-tools"><span class="chip">Confidential</span><button class="btn sm">Agenda (PDF)</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-teaching', '-2018@1', 'frozen', '04-12-2025', '-', 'window', '14–16 Apr', '-', 'quorum 4 of 4', '-', 'seq 4,190'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div class="rec" style="border-color:var(--eligible);border-left:4px solid var(--eligible);margin-bottom:18px"><div class="rec-b" style="display:flex;justify-content:space-between;gap:20px;align-items:center">
            <div><b class="t-sub">✓ Quorum is met — 4 of 4 members present.</b>
              <p class="t-caption" style="margin-top:3px">Scoring is open. It closes automatically if a member signs out and the count falls below four.</p></div>
            <div style="display:flex;gap:16px">
              ${[['Prof. S. Ahmad', 'Chair'], ['Prof. M. Iqbal', 'Subject expert'], ['Dr R. Menon', 'Subject expert'], ['Dr A. Qureshi', 'Registrar’s nominee']]
    .map(([n, r]) => `<div style="text-align:center"><div style="width:32px;height:32px;border-radius:50%;background:var(--paper-sunk);border:1px solid var(--rule-strong);margin:0 auto 4px;display:flex;align-items:center;justify-content:center;font:600 11px var(--font-ui);color:var(--ink-faint)">${n.split(' ').slice(-1)[0].slice(0, 2).toUpperCase()}</div>
      <div class="t-caption" style="line-height:1.3">${n}<br><span class="faint">${r}</span></div></div>`).join('')}
            </div>
          </div></div>

          ${gateRule('', 'Candidates')}
          <div class="rec"><table class="tbl">
            <caption>12 candidates, scrutiny cleared · your scores only · other members’ scores are sealed until every member has signed off</caption>
            <thead><tr><th style="width:130px">APPLICATION</th><th>CANDIDATE</th>
              <th style="width:96px">ACADEMIC</th><th style="width:112px">RESEARCH</th>
              <th style="width:112px">INTERVIEW</th><th style="width:96px">YOUR TOTAL</th><th style="width:110px"></th></tr></thead><tbody>
            ${[['884/2026/01109', 'AISHA KHAN', '92.5', '18', '110.5', true],
    ['884/2026/01142', 'T. VENKATARAMAN', '81.0', '16', '97.0', false],
    ['884/2026/01188', 'SHAZIA PARVEEN', '78.5', '', '—', false],
    ['884/2026/01203', 'D. BHATTACHARYA', '96.0', '19', '115.0', false]]
      .map(([app, n, acad, iv, tot, cur]) => `<tr class="${cur ? 'sel' : ''}">
      <td class="ident">${app}</td><td><b>${n}</b><div class="t-caption">provisional academic score, computed under the frozen ruleset</div></td>
      <td class="r num">${acad}</td>
      <td class="r"><span class="t-caption">included in</span></td>
      <td class="r"><input class="inp sm" value="${iv}" style="width:62px;text-align:right;height:26px" aria-label="Interview score for ${n}"><div class="t-caption">of 20</div></td>
      <td class="r num"><b>${tot}</b></td>
      <td><a href="#">Open dossier</a></td></tr>`).join('')}
          </tbody></table>
          <div style="padding:10px 12px;border-top:1px solid var(--rule);display:flex;justify-content:space-between;align-items:center">
            <span class="t-caption">3 of 12 scored · scores save as you type and are visible to no one else</span>
            <span class="t-caption">Showing 1–4 of 12</span></div></div>

          <div class="notice" style="margin-top:16px"><b>Confidential means confidential between members, not opaque afterwards.</b> While the window is open each member sees only their own interview scores. On sign-off all four sheets are sealed together, the aggregate is computed once, and the whole set — every member’s marks — becomes part of the record an auditor and a court can read.</div>
        </div>

        <aside style="flex:0 0 330px">
          <div class="rec"><div class="rec-h"><span class="t-label">Your sign-off</span></div><div class="rec-b">
            <p class="t-body" style="font-size:14px">You are signing that these are your marks for all 12 candidates and that you have declared any interest.</p>
            <div style="margin:13px 0">
              <label style="display:flex;gap:9px;align-items:flex-start;font-size:13px;margin-bottom:9px"><input type="checkbox" checked style="width:18px;height:18px;margin-top:1px;accent-color:var(--green)">
                <span>I have no personal or professional interest in any candidate that I have not declared.</span></label>
              <label style="display:flex;gap:9px;align-items:flex-start;font-size:13px"><input type="checkbox" style="width:18px;height:18px;margin-top:1px;accent-color:var(--green)">
                <span>These marks are final. After sign-off I cannot change them.</span></label>
            </div>
            <div class="field"><label for="so" style="font-size:14px">Type <b>SIGN OFF</b> to confirm</label>
              <input class="inp sm" id="so" placeholder="SIGN OFF"></div>
            <button class="btn p" style="width:100%">Sign off · 12 candidates</button>
            <div style="margin-top:14px;border-top:1px solid var(--rule);padding-top:11px">
              <div class="t-label" style="margin-bottom:7px">Signed so far</div>
              ${[['Prof. M. Iqbal', 'el', 'Signed 14 Apr 16:22'], ['Dr R. Menon', 'el', 'Signed 14 Apr 16:31'],
    ['Prof. S. Ahmad', 'pe', 'Scoring'], ['Dr A. Qureshi', 'pe', 'Scoring']]
      .map(([n, s, word]) => `<div style="display:flex;justify-content:space-between;gap:10px;padding:5px 0;border-bottom:1px solid var(--rule);font-size:13px"><span>${n}</span>${badge(s, word)}</div>`).join('')}
              <p class="t-caption" style="margin-top:9px">The result is computed when the fourth signature lands, not before. Two signatures and an aggregate would be a shortlist drawn by half a committee.</p>
            </div>
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">If quorum breaks</span></div><div class="rec-b">
            <div class="rec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="rec-b">
              <b class="t-sub">✕ Quorum lost — 3 of 4 present.</b>
              <p class="t-body" style="font-size:13px;margin-top:4px">Dr A. Qureshi signed out at 16:44. Scoring is closed for every member until a fourth is present. Marks entered so far are held as a draft and are not part of the record.</p>
              <div style="margin-top:9px"><button class="btn sm" style="width:100%">Record an apology and a substitute</button></div>
            </div></div>
            <p class="t-caption" style="margin-top:10px">It blocks the meeting, so it is drawn as a block. A committee that scores without quorum has produced nothing, and finding that out afterwards is how a selection is set aside.</p>
          </div></div>
        </aside>
      </div>
    </div>
  </main></div>` }));

console.log('admin D written');
