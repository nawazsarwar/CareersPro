import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, NAV } from './shared.mjs';
import { mg } from './build-admin-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);
const em = '<span class="faint">—</span>';

/* ── A9 · Post types configuration ──────────────────────────── */
const PT = [
  ['1', 'General (Non Teaching Post)', 'form-nt-general', 'Written test + Interview', 'admit-nt-general', null, 'Office of the Registrar', 'Active'],
  ['2', 'Teaching Post', 'form-teaching', 'Interview only', null, null, 'Office of the Registrar', 'Active'],
  ['3', 'Guest Teacher', 'form-guest', 'Interview only', null, 'letter-guest-interview', 'Dean of the Faculty', 'Active'],
  ['4', 'Contractual Teaching', 'form-contractual', 'Interview only', null, 'letter-contractual', 'Dean of the Faculty', 'Active'],
  ['5', 'Non Teaching (Technical)', 'form-nt-technical', 'Written test + Skill test + Interview', 'admit-nt-technical', 'letter-nt-technical', 'Controller of Examinations', 'Active'],
  ['6', 'Local Non Teaching', 'form-local-nt', 'Written test + Interview', 'admit-local-nt', 'letter-local-nt', 'Faculty office', 'Active'],
  ['7', 'Deputation', 'form-deputation', 'Interview only', null, null, 'Office of the Registrar', 'Inactive'],
];

w('PostTypes.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Post types')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Post types</h1>
        <div class="sub">Seven rows. One row makes the application form, the selection pipeline, the generated documents and the physical submission office polymorphic.</div></div>
      <div class="mh-tools"><button class="btn sm">Columns ▾</button><button class="btn p sm">New post type</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['config', 'audited', '-', 'last change', '14-11-2025', 'by r.admin', '-', 'seq 3,102'])}
      <div class="body">
        <div class="rec" style="border-left:3px solid var(--brass);margin-bottom:18px"><div class="rec-b">
          <b class="t-sub">Changing a row here changes live recruitment.</b>
          <p class="t-caption" style="margin-top:4px;max-width:88ch">A post type decides which sections of the application form a candidate sees, which eligibility gates exist, which templates generate the admit card and the interview letter, and where the printed form must be posted. Every change is confirmed, audited, and refused silently nowhere.</p>
        </div></div>
        <table class="tbl"><caption>Post types · 7 rows · a blank template cell means no document of that kind is issued for this post type</caption>
          <thead><tr><th style="width:46px">ID</th><th style="width:210px">NAME</th><th style="width:150px">FORM TEMPLATE</th>
            <th style="width:240px">DEFAULT SELECTION METHOD</th><th style="width:160px">ADMIT CARD</th>
            <th style="width:170px">INTERVIEW LETTER</th><th>SUBMISSION VENUE</th><th style="width:92px">STATUS</th></tr></thead>
          <tbody>${PT.map(([id, n, f, sel, ac, il, v, st]) => `<tr>
            <td class="ident">${id}</td><td><b>${n}</b></td><td class="ident" style="font-size:12px">${f}</td>
            <td>${sel}<div class="t-caption">${sel.split(' + ').length} gate${sel.split(' + ').length > 1 ? 's' : ''} + scrutiny</div></td>
            <td class="ident" style="font-size:12px">${ac || em}</td><td class="ident" style="font-size:12px">${il || em}</td>
            <td>${v}</td><td>${badge(st === 'Active' ? 'el' : 'pe', st)}</td></tr>`).join('')}
          </tbody></table>
        <div style="display:grid;grid-template-columns:1.1fr 1fr;gap:24px;margin-top:24px">
          <div>
            ${gateRule('', 'Changing a row that is in use')}
            <div class="rec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="rec-b">
              <b class="t-sub">This change affects 3 published posts and 1,378 submitted applications.</b>
              <p class="t-body" style="font-size:14px;margin-top:6px">You are changing <b>Default selection method</b> on <b>General (Non Teaching Post)</b> from <i>Written test + Interview</i> to <i>Interview only</i>.</p>
              <ul style="margin:10px 0 0;font-size:13px">
                ${[['2599', 'System Manager', '106 applications, 7 scrutiny-eligible'],
    ['2604', 'Senior Technical Assistant', '318 applications, 41 scrutiny-eligible'],
    ['2607', 'Junior Assistant', '954 applications, 112 scrutiny-eligible']]
      .map(([id, t, c]) => `<li style="padding:6px 0;border-bottom:1px solid var(--rule)"><span class="ident">${id}</span> <b>${t}</b><div class="t-caption">${c}</div></li>`).join('')}
              </ul>
              <p class="t-caption" style="margin-top:10px">The written-test gate would stop existing on those posts. <b>Decisions already recorded are not deleted</b> — they are retained and marked as belonging to a gate that no longer applies, because a recorded decision is evidence.</p>
              <div class="field" style="margin-top:14px;max-width:320px"><label for="tc">Type <b>CHANGE 3 POSTS</b> to confirm</label>
                <input class="inp" id="tc" placeholder="CHANGE 3 POSTS"></div>
              <div style="display:flex;gap:10px"><button class="btn">Cancel</button><button class="btn d">Change the selection method</button></div>
            </div></div>
          </div>
          <div>
            ${gateRule('', 'What a row controls')}
            <div class="rec"><div class="rec-b">
              <table class="tbl" style="background:transparent"><tbody>
              ${[['Application form', 'which sections of Part A, B and C a candidate is shown'],
      ['Selection pipeline', 'which eligibility gates exist — and therefore which columns the gate control renders'],
      ['Admit card', 'the template, or none if the post type holds no written test'],
      ['Interview letter', 'the template, or none'],
      ['Submission venue', 'where the printed application must physically be posted'],
      ['Statistics', 'which figures the pipeline panel can compute at all']]
        .map(([k, v]) => `<tr class="comfy"><td style="width:150px"><b>${k}</b></td><td class="t-caption">${v}</td></tr>`).join('')}
              </tbody></table>
              <p class="t-caption" style="margin-top:10px">Two blank cells on row 2 and row 7 are not missing data. A teaching post issues no admit card and no interview letter through this system; the letters are issued by the Dean’s office. The specification document being replaced transcribed those cells as populated. The screenshot is authoritative.</p>
            </div></div>
          </div>
        </div>
      </div>
    </div>
  </main></div>` }));

/* ── A11 · Bulk document generator ──────────────────────────── */
const sel = (label, opts, id) => `<div style="margin-bottom:14px"><label class="t-label" style="display:block;margin-bottom:5px" for="${id}">${label}</label>
  <select class="inp sm" id="${id}">${opts.map((o) => `<option>${o}</option>`).join('')}</select></div>`;

w('BulkDocs.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Bulk documents')}
  <main class="main">
    <div class="masthead"><h1 class="t-page">Bulk documents</h1>
      <div class="sub">Admit cards and interview letters · generated as a queued job, never in the request</div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['admit window', '18-03-2026', 'to 26-03-2026', '-', 'template', 'admit-nt-general', '-', 'queued jobs', 'audited'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <form style="flex:0 0 330px" method="POST">
          ${gateRule('', 'Generate')}
          ${sel('Advertisement', ['2/2026/NT — Non-teaching posts'], 'ad')}
          ${sel('Post — depends on the advertisement', ['2599 · System Manager'], 'po')}
          ${sel('Document type', ['Admit card', 'Interview letter'], 'dt')}
          ${sel('Who', ['Scrutiny eligible only', 'All applicants', 'Interview eligible only'], 'wh')}
          <button class="btn p" style="width:100%">Count what this will generate</button>
          <p class="t-caption" style="margin-top:9px">The count runs first, always. Nothing is generated until you have seen the number.</p>
        </form>
        <div style="flex:1 1 auto;min-width:0;max-width:740px">
          <div class="rec" style="border-left:4px solid var(--info)"><div class="rec-b">
            <span class="t-label">Dry run</span>
            <h2 class="t-section" style="margin:6px 0 4px">This will generate <b class="num">58</b> admit cards.</h2>
            <p class="t-body" style="font-size:14px">Post 2599 · System Manager · candidates marked <b>✓ Eligible</b> at scrutiny and paid. Roll numbers are present for all 58.</p>
            <table class="tbl" style="margin-top:12px;background:transparent"><tbody>
              <tr><td style="width:230px">Applications for this post</td><td class="r num">106</td></tr>
              <tr><td>Submitted</td><td class="r num">63</td></tr>
              <tr><td>Paid</td><td class="r num">58</td></tr>
              <tr><td>Scrutiny eligible</td><td class="r num">7</td></tr>
              <tr><td><b>Will receive an admit card</b></td><td class="r num"><b>58</b></td></tr>
            </tbody></table>
            <p class="t-caption" style="margin-top:10px">Re-running <b>regenerates and supersedes</b>. It never duplicates: the previous set is marked superseded, both are retained, and each card carries the generation it belongs to.</p>
            <div style="margin-top:14px;display:flex;gap:10px"><button class="btn p">Generate 58 admit cards</button><button class="btn">Change the filter</button></div>
          </div></div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:18px">
            <div class="rec" style="border-left:4px solid var(--rejected)"><div class="rec-b">
              <span class="t-label">Refused — outside the window</span>
              <p class="t-body" style="font-size:14px;margin-top:6px">Admit cards for post 2599 can be generated between <b>18-03-2026</b> and <b>26-03-2026</b>. Today is 28-08-2026.</p>
              <p class="t-caption" style="margin-top:7px">The window is a property of the post, not a setting on this screen. To change it, <a href="#">edit post 2599</a> — which is audited.</p>
            </div></div>
            <div class="rec" style="border-left:4px solid var(--pending)"><div class="rec-b">
              <span class="t-label">Queued — job 4,217</span>
              <p class="t-body" style="font-size:14px;margin-top:6px">Generating 58 admit cards. <b class="num">31 of 58</b> done.</p>
              <div class="pbar thin" style="margin-top:9px"><span class="awa" style="width:53%"></span></div>
              <p class="t-caption" style="margin-top:7px">Started 04:14 · about 40 seconds remaining. You can leave this page; you will be told when it is ready, and the download link lives for 48 hours.</p>
            </div></div>
          </div>

          <div style="height:22px"></div>
          ${gateRule('', 'Recent generations')}
          <div class="rec"><table class="tbl"><thead><tr><th style="width:70px">JOB</th><th>WHAT</th><th style="width:74px">ROWS</th>
            <th style="width:130px">RUN</th><th style="width:130px">STATE</th><th style="width:96px"></th></tr></thead><tbody>
            <tr><td class="ident">4,217</td><td>Admit cards · post 2599 · scrutiny eligible</td><td class="r num">58</td><td class="num">28-08 04:14</td><td>${badge('pe', 'Running')}</td><td>—</td></tr>
            <tr><td class="ident">4,190</td><td>Interview letters · post 884 · interview eligible</td><td class="r num">12</td><td class="num">26-08 11:02</td><td>${badge('el', 'Ready')}</td><td><a href="#">Download</a></td></tr>
            <tr><td class="ident">4,102</td><td>Admit cards · post 2604 · all applicants</td><td class="r num">224</td><td class="num">19-08 09:41</td><td>${badge('pe', 'Superseded')}</td><td><a href="#">View</a></td></tr>
            <tr><td class="ident">4,061</td><td>Admit cards · post 2607 · scrutiny eligible</td><td class="r num">112</td><td class="num">12-08 15:20</td><td>${badge('in', 'Link expired')}</td><td><a href="#">Re-run</a></td></tr>
          </tbody></table></div>
        </div>
      </div>
    </div>
  </main></div>` }));

/* ── A12 · Reports and SLA ★ ────────────────────────────────── */
const rep = (t, statutory, note) => `<li style="display:flex;justify-content:space-between;gap:14px;align-items:baseline;padding:9px 0;border-bottom:1px solid var(--rule)">
  <span><a href="#">${t}</a>${statutory ? ' <span class="chip" style="border-color:var(--brass);color:var(--brass)">Statutory</span>' : ''}
    <div class="t-caption">${note}</div></span><a href="#" class="btn sm">Run</a></li>`;

w('Reports.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Reports')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Reports and statutory deadlines</h1>
        <div class="sub">University-wide · 24 report definitions · 9 statutory</div></div>
      <div class="mh-tools"><button class="btn sm">Run history</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['earliest', 'snapshot', '22-01-2026', '-', 'as-at runs', 'rebuild from', 'the chain', '-', 'exports', 'audited'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          ${gateRule('', 'Statutory deadlines')}
          <div class="rec" style="border-left:3px solid var(--rejected);margin-bottom:24px">
            <table class="tbl"><caption>Advertisements approaching or breaching a statutory limit · refreshed hourly</caption>
            <thead><tr><th style="width:130px">ADVERTISEMENT</th><th style="width:210px">LIMIT</th><th style="width:104px">DUE</th>
              <th style="width:150px">REMAINING</th><th style="width:190px">PROGRESS</th><th>EXTENSION</th></tr></thead><tbody>
            <tr><td><a href="#" class="ident">8/2025/T</a><div class="t-caption">Faculty of Medicine</div></td>
              <td>Six-month process cap<div class="t-caption">DoPT O.M. Misc.14017/15/2015-Estt.(RR)</div></td>
              <td class="num">03-09-2026</td><td><span class="b re"><span class="g">✕</span> <b>4 days</b></span></td>
              <td><div class="pbar thin"><span class="fal" style="width:97.8%"></span></div><div class="t-caption">176 of 180 days elapsed</div></td>
              <td class="t-caption">None recorded. <a href="#">Record a VC approval</a></td></tr>
            <tr><td><a href="#" class="ident">2/2026/NT</a><div class="t-caption">Non-teaching posts</div></td>
              <td>Six-month process cap</td><td class="num">22-07-2026</td>
              <td><span class="b re"><span class="g">✕</span> <b>breached by 37 days</b></span></td>
              <td><div class="pbar thin"><span class="fal" style="width:100%"></span></div><div class="t-caption">217 of 180 days elapsed</div></td>
              <td class="t-caption">VC approval <span class="ident" style="font-size:12px">VC/2026/118</span> dated 18-07-2026</td></tr>
            <tr><td><a href="#" class="ident">11/2026/T</a><div class="t-caption">Faculty of Arts</div></td>
              <td>Thirty-day advertisement window<div class="t-caption">CRR</div></td><td class="num">06-09-2026</td>
              <td><span class="b pe"><span class="g">◦</span> <b>9 days</b></span></td>
              <td><div class="pbar thin"><span class="awa" style="width:70%"></span></div><div class="t-caption">21 of 30 days elapsed</div></td>
              <td class="t-caption">—</td></tr>
            </tbody></table></div>

          ${gateRule('', 'Reports')}
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
            <div><div class="t-label" style="margin-bottom:6px">Statutory</div><ul>
              ${rep('Category-wise applications per advertisement', true, 'RTI Act 2005 · answers the most-asked request verbatim')}
              ${rep('Roster compliance', true, 'CRR Rule 15.1 · DOC-003')}
              ${rep('Gender-wise applications and selections', true, 'RTI Act 2005')}
              ${rep('Reservation and relaxation applied', true, 'DoPT O.M. 15012/2/2010-Estt.(D)')}
            </ul></div>
            <div><div class="t-label" style="margin-bottom:6px">Operational</div><ul>
              ${rep('Scrutiny throughput by officer', false, 'decisions per day, with the median time to decide')}
              ${rep('Payment reconciliation — failed and awaited', false, 'the queue behind the financial strip')}
              ${rep('Deficiencies raised and rectified', false, 'with time-to-rectify')}
              ${rep('Advertisements by faculty and appointment nature', false, 'general against local')}
            </ul></div>
          </div>
        </div>
        <aside style="flex:0 0 330px">
          <div class="rec" style="border-left:4px solid var(--info)"><div class="rec-h"><span class="t-label">Run a report as at a past date</span></div><div class="rec-b">
            <div style="margin-bottom:12px"><label class="t-label" style="display:block;margin-bottom:5px" for="asat">As-at date</label>
              <input class="inp sm" id="asat" value="12-08-2026" aria-describedby="asath">
              <div class="help" id="asath" style="font-size:12px">The date the figures should be reconstructed to.</div></div>
            <div class="err" style="margin-bottom:12px">✕ No records exist before 22 Jan 2026.</div>
            <p class="t-caption">A historical run rebuilds from the snapshots and the audit chain as they stood on that date — not from today’s state. An RTI answer about 2026, asked in 2029, must not be computed from 2029 data.</p>
            <button class="btn p sm" style="width:100%;margin-top:12px">Run as at 12-08-2026</button>
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">Reconstructing</span></div><div class="rec-b">
            <p class="t-body" style="font-size:14px">Rebuilding <b>Category-wise applications</b> for advertisement 2/2026/NT as at <b>12-08-2026</b>.</p>
            <div class="pbar thin" style="margin-top:9px"><span class="awa" style="width:38%"></span></div>
            <p class="t-caption" style="margin-top:7px">Reading 4,182 audit entries and 1,208 snapshots. This is a queued job; you will be told when it is ready.</p>
            <div style="margin-top:12px;padding:9px 11px;background:var(--paper-sunk);border:1px solid var(--rule)">
              <div class="t-label">The output will state its basis</div>
              <p class="t-caption" style="margin-top:4px">“Reconstructed from snapshots and the audit chain as they stood at 12-08-2026 23:59 IST. 41 records created after that date are excluded. Nothing has been deleted from the register.”</p>
            </div>
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">Export queued</span></div><div class="rec-b">
            <p class="t-body" style="font-size:14px"><b>63,907 rows</b> is over the 5,000-row threshold, so this export runs as a job.</p>
            <ul class="rail" style="margin-top:10px;font-size:13px">
              <li class="done"><span class="tick"><span class="dot">✓</span><span class="seg"></span></span><span class="lbl">Queued</span><span class="when">04:12</span></li>
              <li class="cur"><span class="tick"><span class="dot">▌</span><span class="seg"></span></span><span class="lbl">Ready · <a href="#">download (18 MB)</a></span><span class="when">04:14</span></li>
              <li class="fut"><span class="tick"><span class="dot">◦</span><span class="seg"></span></span><span class="lbl">Link expires</span><span class="when">30-08 04:14</span></li>
            </ul>
            <p class="t-caption" style="margin-top:9px">An expired link is re-run, not recovered. The export event stays in the chain either way.</p>
          </div></div>
        </aside>
      </div>
    </div>
  </main></div>` }));

/* ── A14 · Audit trail ──────────────────────────────────────── */
const H = (s) => `<span class="ident" style="font-size:12px">${s}</span>`;
const entry = (seq, when, actor, what, prev, hash, ok = true) => `<tr>
  <td class="ident">${seq}</td><td class="num">${when}</td><td>${actor}</td>
  <td>${what}</td>
  <td>${H(prev)}</td><td>${H(hash)}</td>
  <td>${ok ? badge('el', 'Verified') : badge('re', 'Chain broken')}</td></tr>`;

w('AuditTrail.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Audit trail')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Audit trail</h1>
        <div class="sub">Append-only, hash-chained · <b class="num">4,182</b> entries · nothing in this table can be edited or removed by anyone, including a super administrator</div></div>
      <div class="mh-tools"><button class="btn sm">Columns ▾</button><button class="btn p sm">Verify the whole chain</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['chain head', '4,182', '-', 'verified', '04:00 today', 'by cron', '-', 'genesis', '22-01-2026'])}
      <div class="body">
        <div class="rec" style="border-left:4px solid var(--eligible);margin-bottom:18px"><div class="rec-b" style="display:flex;justify-content:space-between;gap:24px;align-items:center">
          <div><b class="t-sub">✓ The chain verifies from entry 1 to entry 4,182.</b>
            <p class="t-caption" style="margin-top:4px">Each entry stores the hash of the one before it, so a single altered row breaks every hash after it. Last full verification 28-08-2026 04:00, 41 seconds, by the scheduled task.</p></div>
          <div style="text-align:right;flex:0 0 auto"><div class="t-figure num">4,182</div><div class="t-label">entries verified</div></div>
        </div></div>
        <table class="tbl"><caption>Audit entries 4,178–4,182 · newest first · filtered to none</caption>
          <thead><tr><th style="width:66px">SEQ</th><th style="width:130px">WHEN</th><th style="width:150px">ACTOR</th>
            <th>EVENT</th><th style="width:150px">PREVIOUS HASH</th><th style="width:150px">CONTENT HASH</th><th style="width:120px">CHAIN</th></tr>
            <tr class="filters"><td><input aria-label="Filter by sequence"></td><td><input aria-label="Filter by date" placeholder="range"></td>
              <td><input aria-label="Filter by actor" placeholder="search"></td>
              <td><select aria-label="Filter by event"><option>All events</option><option>eligibility.decided</option><option>export.generated</option><option>ruleset.activated</option></select></td>
              <td></td><td></td><td><select aria-label="Filter by chain state"><option>All</option></select></td></tr></thead>
          <tbody>
            ${entry('4,182', '28-08 04:14', 'n.sarwar <span class="chip">impersonating</span>', '<b>eligibility.decided</b> · application 10087779 · scrutiny <b>◦ Pending → ✓ Eligible</b><div class="t-caption">remark: “Qualification verified against the M.C.A. degree certificate.”</div>', 'a91f…c4d2', '7be0…19af')}
            ${entry('4,181', '28-08 04:12', 'f.admin', '<b>export.generated</b> · payment reconciliation · 63,907 rows<div class="t-caption">filters: state=failed · range 01-01-2026 to 28-08-2026</div>', '3c07…8b51', 'a91f…c4d2')}
            ${entry('4,180', '28-08 03:58', 'r.verifier', '<b>ruleset.activated</b> · ugc-teaching-2018@2<div class="t-caption">authored by r.admin; activated by a second person, as required</div>', 'd4aa…20fe', '3c07…8b51')}
            ${entry('4,179', '27-08 18:20', 'scrutiny.arts', '<b>deficiency.raised</b> · application 2599/2026/00412 · Employment history<div class="t-caption">window closes 19-03-2026 17:00</div>', '11c9…7d33', 'd4aa…20fe')}
            ${entry('4,178', '27-08 18:02', 'system', '<b>snapshot.written</b> · application 2599/2026/00412 · snapshot #2', '0f2e…aa10', '11c9…7d33')}
          </tbody></table>
        <div class="pager"><span>Showing 4,178–4,182 of 4,182</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">…</a><a href="#">42</a><a href="#">›</a></span><span>100 per page ▾</span></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:24px">
          <div>${gateRule('', 'Verify one entry')}
            <div class="rec"><div class="rec-b">
              <div class="dl" style="grid-template-columns:150px 1fr;font-size:13px">
                <dt>Sequence</dt><dd class="ident">4,182</dd>
                <dt>Recorded at</dt><dd class="num">28-08-2026 04:14:09.221 IST</dd>
                <dt>Actor</dt><dd>n.sarwar, acting as scrutiny.arts <span class="chip">impersonation</span></dd>
                <dt>Event</dt><dd class="ident" style="font-size:12px">eligibility.decided</dd>
                <dt>Subject</dt><dd><a href="#" class="ident">10087779</a> · gate scrutiny</dd>
                <dt>Previous value</dt><dd>◦ Pending</dd>
                <dt>New value</dt><dd>✓ Eligible</dd>
                <dt>Previous hash</dt><dd>${H('a91f…c4d2')}</dd>
                <dt>Content hash</dt><dd>${H('7be0…19af')}</dd>
              </div>
              <div style="margin-top:14px;padding:11px 13px;background:var(--green-wash);border:1px solid var(--rule)">
                <b class="b el"><span class="g">✓</span> Recomputed and matched</b>
                <p class="t-caption" style="margin-top:4px">The content hash was recomputed from the stored payload and the previous hash, and it matches. Checked just now, in your browser session, on demand.</p>
              </div>
              <div style="margin-top:12px;display:flex;gap:10px"><button class="btn">Verify again</button><button class="btn">Verify from here to the head</button></div>
            </div></div>
          </div>
          <div>${gateRule('', 'If a link ever breaks')}
            <div class="rec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="rec-b">
              <b class="t-sub">✕ Entry 3,004 does not match its stored hash.</b>
              <p class="t-body" style="font-size:14px;margin-top:5px">Everything from 3,004 to the head is therefore unverified. Entries 1 to 3,003 are unaffected and remain verified.</p>
              <div style="margin-top:12px;font-family:var(--font-mono);font-size:12px;line-height:1.9">
                <div>3,003 &nbsp; <span style="color:var(--eligible)">✓</span> &nbsp; 0f2e…aa10</div>
                <div style="color:var(--rejected)">3,004 &nbsp; ✕ &nbsp; expected 6d31…04b7, found 8a19…ee52</div>
                <div class="faint">3,005 &nbsp; ◦ &nbsp; unverifiable — depends on 3,004</div>
              </div>
              <p class="t-caption" style="margin-top:11px">The chain is designed so this is discoverable rather than deniable. A break is an incident: it is reported to the Registrar, the affected range is stated exactly, and nothing is repaired in place — a repair would be indistinguishable from the tampering it claims to fix.</p>
            </div></div>
          </div>
        </div>
      </div>
    </div>
  </main></div>` }));

console.log('admin C written');
