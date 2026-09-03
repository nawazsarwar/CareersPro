import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, comp, NAV } from './shared.mjs';
import { mg } from './build-admin-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

const ROWS = [
  ['10087779', 'MOHAMMAD BASIM ZAHID', 'General', '41y 3m', '23-01-2026', 'el', 'Eligible'],
  ['10087780', 'AISHA KHAN', 'OBC-NCL', '41y 3m', '23-01-2026', 'pe', 'Pending'],
  ['10087781', 'RAKESH KUMAR VERMA', 'SC', '36y 8m', '24-01-2026', 'el', 'Eligible'],
  ['10087782', 'FATIMA SIDDIQUI', 'OBC-NCL', '29y 1m', '24-01-2026', 're', 'Not eligible'],
  ['10087783', 'S. ANANTHAKRISHNAN', 'General', '44y 11m', '25-01-2026', 'pe', 'Pending'],
  ['10087784', 'PRIYA RAMACHANDRAN', 'OBC-NCL', '33y 5m', '25-01-2026', 'pe', 'Pending'],
  ['10087785', 'MOHD ARSHAD ALI', 'General', '38y 2m', '26-01-2026', 'el', 'Eligible'],
  ['10087786', 'SUNITA DEVI', 'ST', '31y 7m', '26-01-2026', 'pe', 'Pending'],
];

export const TBAR = (sel = 12) => `<div class="tbar">
  <div style="display:flex;gap:10px;align-items:center">
    <span class="filterchip"><b>${sel} selected</b><a href="#">Delete</a><a href="#">Clear</a></span>
    <span class="t-caption">Selection announced politely to screen readers</span></div>
  <div style="display:flex;gap:12px;align-items:center">
    <span class="exp">${['Copy', 'CSV', 'Excel', 'PDF', 'Print', 'Columns'].map((e) => `<a href="#">${e}</a>`).join('')}</span>
    <input class="search" placeholder="Search all columns"></div>
</div>`;

export const THEAD = `<thead>
  <tr><th style="width:34px"><input type="checkbox" aria-label="Select all rows on this page"></th>
    <th style="width:110px" aria-sort="descending"><button style="all:unset;cursor:pointer" class="sortable">APP NO</button></th>
    <th>CANDIDATE</th><th style="width:104px">CATEGORY</th>
    <th style="width:126px">AGE <span style="text-transform:none;letter-spacing:0;font-weight:400">· computed</span></th>
    <th style="width:106px" aria-sort="none"><button style="all:unset;cursor:pointer">SUBMITTED</button></th>
    <th style="width:132px">SCRUTINY</th><th style="width:118px">ACTIONS</th></tr>
  <tr class="filters"><td></td><td><input aria-label="Filter by application number" placeholder="search"></td>
    <td><input aria-label="Filter by candidate name" placeholder="search"></td>
    <td><select aria-label="Filter by category"><option>All</option><option>General</option><option>OBC-NCL</option><option>SC</option><option>ST</option><option>PwD</option></select></td>
    <td><input aria-label="Filter by age" placeholder="min – max"></td>
    <td><input aria-label="Filter by submitted date" placeholder="range"></td>
    <td><select aria-label="Filter by scrutiny decision"><option>All</option><option>✓ Eligible</option><option>✕ Not eligible</option><option>◦ Pending</option></select></td><td></td></tr>
</thead>`;

const row = ([no, name, cat, age, sub, s, word], i, focused = -1) => `<tr class="${i === focused ? 'sel' : ''}"${i === focused ? ' style="outline:2px solid var(--green);outline-offset:-2px"' : ''}>
  <td><input type="checkbox" ${i < 2 ? 'checked' : ''} aria-label="Select application ${no}"></td>
  <td class="ident">${no}</td><td>${name}</td><td>${cat}</td>
  <td class="num">${age}</td><td class="num">${sub}</td><td>${badge(s, word)}</td>
  <td><a href="#" aria-label="Update eligibility for application ${no}">Eligibility</a></td></tr>`;

/* ── A2 · The data table ★★ ─────────────────────────────────── */
w('AppsTable.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Applications')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Application forms</h1>
        <div class="sub">University-wide · <b class="num">78,232</b> rows · sorted by application number, newest first</div></div>
      <div class="mh-tools"><button class="btn sm">Compact ⇕</button><button class="btn sm">Columns ▾</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['78,232 rows', 'page 1 of 783', '-', 'count cached', '5 min', '-', 'scope', 'university', '-', 'exports', 'audited'])}
      <div class="body">
        ${TBAR()}
        <table class="tbl">
          <caption>Application forms · 78,232 rows · no filters applied · sorted by application number, descending</caption>
          ${THEAD}
          <tbody>${ROWS.map((r, i) => row(r, i, 3)).join('')}</tbody>
        </table>
        <div class="pager">
          <span>Showing <b class="num">1–100</b> of <b class="num">78,232</b></span>
          <span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">…</a><a href="#">100</a><a href="#">›</a></span>
          <span>100 per page ▾</span></div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:22px">
          <div class="notice"><b>Row 4 is focused, and the sticky header does not cover it.</b> Rows carry <span class="ident" style="font-size:12px">scroll-margin-block-start</span> equal to the header’s height, so keyboard focus never scrolls a row underneath the header — WCAG 2.2, 2.4.11.</div>
          <div class="notice"><b>Beyond page 100 the table asks for a filter.</b> <span class="ident" style="font-size:12px">OFFSET 78000</span> is a table scan, so past 10,000 rows the pager says: <i>“Add a filter to reach these rows — a date range or a post narrows 78,232 to something a page can hold.”</i> Guidance, not an error.</div>
        </div>

        <div style="height:26px"></div>
        ${gateRule('', 'The same table, filtered')}
        <div style="display:flex;gap:12px;align-items:center;margin-bottom:10px">
          <span class="filterchip"><b>Filtered view</b> · post 2599 · category OBC-NCL · scrutiny ◦ Pending<a href="#">Clear all</a></span>
          <span class="t-caption">The filter is in the URL: <span class="ident" style="font-size:12px">?f[post]=2599&amp;f[category]=obc-ncl&amp;f[scrutiny]=pending&amp;sort=-app_no</span> — so this view can be sent to a colleague, which is what happens when a case is escalated.</span>
        </div>
        <table class="tbl">
          <caption>Application forms · filtered to post 2599, category OBC-NCL, scrutiny pending · 2 rows</caption>
          ${THEAD}
          <tbody>${[ROWS[1], ROWS[5]].map((r, i) => row(r, i)).join('')}</tbody>
        </table>
        <div class="pager"><span>Showing <b class="num">1–2</b> of <b class="num">2</b> · filtered from 78,232</span>
          <span class="pp"><a href="#" class="on">1</a></span><span>100 per page ▾</span></div>

        <div class="notice" style="margin-top:20px"><b>Exports over 5,000 rows are queued.</b> An export of this table runs as a job; you are told when it is ready and the link expires after 48 hours. Every export writes <span class="ident" style="font-size:12px">export.generated</span> with the actor, the row count and the filters applied — an export is bulk personal data leaving the system, which is exactly the event an audit chain exists for.</div>
      </div>
    </div>
  </main></div>` }));

/* ── A3 · Dossier record ★★ ─────────────────────────────────── */
const dossierHead = `<thead><tr>
  <th style="width:34px"><input type="checkbox" aria-label="Select all rows on this page"></th>
  <th style="width:26px"></th>
  <th style="width:106px">APP NO</th><th>CANDIDATE</th><th style="width:92px">CATEGORY</th>
  <th style="width:118px">AGE · computed</th><th style="width:104px">EXPERIENCE</th>
  <th style="width:104px">SUBMITTED</th><th style="width:126px">SCRUTINY</th><th style="width:112px">ACTIONS</th></tr></thead>`;

const dossierRow = (no, name, cat, age, exp, sub, s, word, open = false) => `<tr${open ? ' class="sel"' : ''}>
  <td><input type="checkbox" aria-label="Select application ${no}"></td>
  <td><button style="all:unset;cursor:pointer;color:var(--green);font-size:13px" aria-expanded="${open}" aria-label="Show the full record for application ${no}">${open ? '▾' : '▸'}</button></td>
  <td class="ident">${no}</td><td>${name}</td><td>${cat}</td>
  <td class="num">${age}</td><td class="num">${exp}</td><td class="num">${sub}</td>
  <td>${badge(s, word)}</td>
  <td><a href="#" aria-label="Update eligibility for application ${no}">Eligibility</a></td></tr>`;

const col = (n, title, body) => `<div style="flex:1;min-width:0;padding:0 16px;border-left:1px solid var(--rule-strong)">
  <div class="t-label" style="margin-bottom:8px"><span class="faint ident" style="font-size:11px">${n}</span> ${title}</div>${body}</div>`;

const kv = (rows) => `<table class="tbl" style="background:transparent"><tbody>${rows.map(([k, v]) => `<tr><td style="width:104px;padding:0;height:22px;border:0;color:var(--ink-muted)">${k}</td><td style="padding:0;height:22px;border:0">${v}</td></tr>`).join('')}</tbody></table>`;

const EXPANDED = `<tr><td colspan="10" style="padding:0;background:var(--paper);height:auto">
  <div style="display:flex;padding:18px 0 20px;align-items:flex-start">
    <div style="flex:1.35;min-width:0;padding:0 16px;display:flex;gap:14px">
      <div>
        <div style="width:80px;height:100px;background:var(--paper-sunk);border:1px solid var(--rule-strong)"></div>
        <div class="t-caption" style="width:80px;margin-top:4px;line-height:1.3">80×100 conversion, lazy-loaded</div>
      </div>
      <div style="min-width:0">
        <div class="t-label" style="margin-bottom:8px"><span class="faint ident" style="font-size:11px">1</span> Identity</div>
        ${kv([['User id', '<span class="ident">48760</span>'], ['Application', '<span class="ident">10087779</span>'],
  ['Name', '<b>MOHAMMAD BASIM ZAHID</b>'], ['Father', 'MOHAMMAD ZAHID'], ['Mother', 'SHEEBA ZAHID'],
  ['Spouse', 'NIDA SIDDIQUI'], ['Email', '<span class="ident" style="font-size:12px">m_basim_z@hotmail.com</span>'],
  ['Mobile', '<span class="ident">9999886246</span>'], ['Gender', 'Male'], ['Religion', 'Islam'],
  ['Category', 'General'], ['Caste', '—'], ['Disability', 'None · 0%']])}
        <div style="margin-top:8px;padding:7px 9px;background:var(--paper-sunk);border:1px solid var(--rule)">
          <div style="font-size:13px"><b>DOB 26-11-1984</b> · <b class="num">41 years 3 months</b></div>
          <div class="t-caption">measured against <b>07-03-2026</b>, the registration end date for post 2599<span class="cite" style="margin-top:3px">CRR Rule 14 — never against today</span></div>
        </div>
        <div style="margin-top:8px;padding:7px 9px;background:var(--paper-sunk);border:1px solid var(--rule)">
          <div style="font-size:13px"><b>Total experience 0 years 0 months</b></div>
          <div class="t-caption">computed from 0 employment records · post requires 8 years</div>
        </div>
        <div style="margin-top:9px"><div class="t-label" style="margin-bottom:4px">Other applications by this candidate</div>
          <div style="font-size:12px;line-height:1.7">
            <a href="#" class="ident">2601/2026/00318</a> System Engineer, M.N. Farooqui Computer Centre — 05-02-2026<br>
            <a href="#" class="ident">2612/2026/00402</a> Electronics Engineer, Electronics Engineering — 05-02-2026<br>
            <a href="#" class="ident">2599/2026/00119</a> System Engineer, M.N. Farooqui Computer Centre — 23-01-2026</div></div>
        <div class="t-caption" style="margin-top:8px">Submitted 23-01-2026 21:24:55</div>
      </div>
    </div>
    ${col('2', 'Address', kv([['Correspondence', '506, 5th Floor, IT Palm Court Apartments, Dodhpur Road, opp. Noor Manzil, Civil Lines, Aligarh, Uttar Pradesh 202001'],
    ['Permanent', 'Same as correspondence'], ['Domicile district', 'Aligarh'], ['Domicile state', 'Uttar Pradesh']]))}
    ${col('3', 'Qualifications', `<table class="tbl" style="background:transparent"><tbody>
      ${[['Secondary School Certificate', 'Aligarh Muslim University', '2000', '66.6%', '—'],
    ['Senior Secondary Certificate', 'Aligarh Muslim University', '2002', '60.75%', '—'],
    ['B.Tech', 'Biju Patnaik University of Technology', '2005', '—', 'CGPA 6.28'],
    ['M.C.A.', 'Biju Patnaik University of Technology', '2009', '—', 'CGPA 7.10']]
    .map(([d, i, y, p, c]) => `<tr><td style="padding:4px 0;border:0;border-bottom:1px solid var(--rule)">
      <b>${d}</b><div class="t-caption">${i}</div>
      <div class="t-caption num">${y} · ${p} ${c !== '—' ? '· ' + c : ''}</div></td></tr>`).join('')}
    </tbody></table>`)}
    ${col('4', 'Experience', `<div class="empty" style="padding:14px 12px">
      <b style="font-size:13px">No employment recorded.</b>
      <div class="t-caption" style="margin-top:4px">The post requires eight years of supervisory experience, so this is decisive rather than incidental.</div></div>`)}
    ${col('5', 'Referees & testimonials', `<div class="t-label" style="margin-bottom:4px;color:var(--ink-faint)">Referees</div>
      <div class="t-caption" style="margin-bottom:10px">None recorded</div>
      <div class="t-label" style="margin-bottom:4px;color:var(--ink-faint)">Testimonials</div>
      <div class="t-caption">None recorded</div>`)}
    ${col('6', 'Institutions attended', `${[['City High School, Aligarh', 'Aligarh Muslim University, Aligarh, Uttar Pradesh', '2000–2002'],
      ['Senior Secondary School (Boys)', 'Aligarh Muslim University, Aligarh, Uttar Pradesh', '2002–2005'],
      ['C.V. Raman College of Engineering', 'Biju Patnaik University of Technology, Rourkela', '2005–2009']]
      .map(([n, u, y]) => `<div style="padding:5px 0;border-bottom:1px solid var(--rule)"><b style="font-size:13px">${n}</b>
        <div class="t-caption">${u}</div><div class="t-caption num">${y}</div></div>`).join('')}`)}
    <div style="flex:0 0 168px;padding:0 16px;border-left:1px solid var(--rule-strong)">
      <div class="t-label" style="margin-bottom:8px"><span class="faint ident" style="font-size:11px">7</span> Action</div>
      <div style="margin-bottom:6px">${badge('el', 'Eligible')}<div class="t-caption">scrutiny · 26-02-2026</div></div>
      <div style="margin-bottom:10px">${badge('pe', 'Pending')}<div class="t-caption">written test</div></div>
      <button class="btn p sm" style="width:100%">Update eligibility</button>
      <div class="t-caption" style="margin-top:8px">Opens the counterfoil below, without losing your position in the queue.</div>
    </div>
  </div>
</td></tr>`;

w('DossierCollapsed.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Applications')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Applicant dossiers — collapsed</h1>
        <div class="sub">Post 2599 · System Manager · <b class="num">106</b> applications · 32px compact rows</div></div>
      <div class="mh-tools"><button class="btn sm">Compact ⇕</button><button class="btn sm">Columns ▾</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-nt-2026@1', '-', 'row marks', 'align to', 'their row', '-', 'measured', '07-03-2026'])}
      <div class="body">
        ${TBAR(0).replace('<b>0 selected</b><a href="#">Delete</a><a href="#">Clear</a>', 'Select rows to act on them')}
        <table class="tbl"><caption>Applicant dossiers for post 2599 · columns 1 and 7 shown; the remaining five load when a row is expanded</caption>
          ${dossierHead}
          <tbody>
            ${dossierRow('10087779', 'MOHAMMAD BASIM ZAHID', 'General', '41y 3m', '0y 0m', '23-01-2026', 'el', 'Eligible')}
            ${dossierRow('10087780', 'AISHA KHAN', 'OBC-NCL', '41y 3m', '6y 4m', '23-01-2026', 'pe', 'Pending')}
            ${dossierRow('10087781', 'RAKESH KUMAR VERMA', 'SC', '36y 8m', '11y 2m', '24-01-2026', 'el', 'Eligible')}
            ${dossierRow('10087782', 'FATIMA SIDDIQUI', 'OBC-NCL', '29y 1m', '3y 0m', '24-01-2026', 're', 'Not eligible')}
            ${dossierRow('10087783', 'S. ANANTHAKRISHNAN', 'General', '44y 11m', '19y 6m', '25-01-2026', 'pe', 'Pending')}
            ${dossierRow('10087784', 'PRIYA RAMACHANDRAN', 'OBC-NCL', '33y 5m', '8y 1m', '25-01-2026', 'pe', 'Pending')}
          </tbody></table>
        <div class="pager"><span>Showing 1–6 of 106</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">›</a></span><span>100 per page ▾</span></div>
        <div class="notice" style="margin-top:18px"><b>Why the collapsed row is a row.</b> The seven-column record touches nine relations. At 100 rows that is 900 queries and a page no one can scan, so the collapsed state carries identity and decision at a true 32px height and the rest is fetched on expand. <b>Age and experience are labelled as computed</b> in the column head, and the reference date is stated in the record — never in a tooltip.</div>
      </div>
    </div>
  </main></div>` }));

w('DossierExpanded.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Applications')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Applicant dossiers — expanded</h1>
        <div class="sub">Post 2599 · System Manager · row 1 of 106 open · 160ms height transition, no reflow of the rows below</div></div>
      <div class="mh-tools"><button class="btn sm">Compact ⇕</button><button class="btn sm">Columns ▾</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'snapshot #1', '-', 'measured', '07-03-2026', 'CRR Rule 14', '-', 'seq 4,182'])}
      <div class="body">
        ${TBAR(0).replace('<b>0 selected</b><a href="#">Delete</a><a href="#">Clear</a>', 'Select rows to act on them')}
        <table class="tbl"><caption>Applicant dossiers for post 2599 · application 10087779 expanded to all seven columns</caption>
          ${dossierHead}
          <tbody>
            ${dossierRow('10087779', 'MOHAMMAD BASIM ZAHID', 'General', '41y 3m', '0y 0m', '23-01-2026', 'el', 'Eligible', true)}
            ${EXPANDED}
            ${dossierRow('10087780', 'AISHA KHAN', 'OBC-NCL', '41y 3m', '6y 4m', '23-01-2026', 'pe', 'Pending')}
            ${dossierRow('10087781', 'RAKESH KUMAR VERMA', 'SC', '36y 8m', '11y 2m', '24-01-2026', 'el', 'Eligible')}
          </tbody></table>
        <div class="pager"><span>Showing 1–3 of 106</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">›</a></span><span>100 per page ▾</span></div>
      </div>
    </div>
  </main></div>` }));

/* ── A4 · The gate control — the counterfoil ★★ ─────────────── */
const gateCol = (name, sel, remark = '', bad = false) => `<div style="flex:1;min-width:0;padding:0 18px;border-left:1px solid var(--rule-strong)">
  <div class="t-label" style="margin-bottom:9px">${name}</div>
  ${[['el', '✓ Eligible'], ['re', '✕ Not eligible'], ['pe', '◦ Pending']].map(([k, label]) => `
  <label style="display:flex;gap:9px;align-items:center;height:26px;font-size:13px;cursor:pointer">
    <input type="radio" name="${name}" ${sel === k ? 'checked' : ''} style="width:16px;height:16px;accent-color:var(--green)">
    <span class="b ${k}"><span class="g">${label.slice(0, 1)}</span>${label.slice(1)}</span></label>`).join('')}
  <label class="t-label" style="display:block;margin:11px 0 4px" for="rm-${name}">Remark${sel === 're' ? ' · required' : ''}</label>
  <textarea id="rm-${name}" class="inp sm ${bad ? 'bad' : ''}" style="height:56px;padding:6px 8px;resize:none;width:100%">${remark}</textarea>
  ${bad ? '<div class="err" style="margin-top:4px">✕ A rejection carries the reason it was refused. Say what is missing.</div>' : ''}
</div>`;

w('GateControl.dc.html', artboard({
  w: 1440, pad: true, body: `
${gateRule('', 'The counterfoil — the gate control, docked')}
<p class="t-caption" style="max-width:78ch;margin-bottom:20px">The stub that stays bound in the register when the leaf is torn out. It replaces the modal: a decision this consequential must be made with the claim, the evidence and the rule visible, and a modal is by construction a screen that covers the thing you are deciding about. It also keeps focus in the page, so the queue path is never broken.</p>

<div style="border:1px solid var(--rule-strong);border-top:3px solid var(--brass);background:var(--paper-raised);border-radius:2px;margin-bottom:26px">
  <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 18px;border-bottom:1px solid var(--rule)">
    <div><b class="t-sub">Update eligibility</b>
      <span style="margin-left:12px" class="ident">10087779</span>
      <span style="margin-left:8px">MOHAMMAD BASIM ZAHID</span></div>
    <div class="t-caption">Post <span class="ident" style="font-size:12px">2599</span> System Manager · <b>Written test + Interview</b> · row 4 of 106</div>
  </div>
  <div style="display:flex;padding:16px 0">
    <div style="flex:0 0 250px;padding:0 18px">
      <div class="t-label" style="margin-bottom:9px">Governing rules</div>
      <div class="ident" style="font-size:12px">ugc-nt-2026@1</div>
      <span class="cite" style="margin-top:6px">frozen 22-01-2026 · CRR Sch. II item 14 requires eight years of supervisory experience; this candidate records none</span>
      <div class="t-caption" style="margin-top:10px">Previous decision: <b>◦ Pending</b>, set by the system on 23-01-2026. Saving records the previous value in the chain.</div>
    </div>
    ${gateCol('Scrutiny', 'el', 'Qualification verified against the M.C.A. degree certificate.')}
    ${gateCol('Written test', 'pe')}
    ${gateCol('Interview', 'pe')}
  </div>
  <div style="display:flex;justify-content:space-between;align-items:center;padding:11px 18px;border-top:1px solid var(--rule);background:var(--paper-sunk)">
    <span class="t-caption">Saving writes one decision row per gate and one hash-chained audit entry per change, each carrying the value it replaced.</span>
    <div style="display:flex;gap:10px"><button class="btn">Cancel</button><button class="btn p">Save decisions</button></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:26px">
  <div>
    <div class="t-label" style="margin-bottom:9px">An interview-only post — the written-test gate does not exist</div>
    <div style="border:1px solid var(--rule-strong);border-top:3px solid var(--brass);background:var(--paper-raised);border-radius:2px">
      <div style="padding:11px 18px;border-bottom:1px solid var(--rule)">
        <b class="t-sub">Update eligibility</b> <span class="ident" style="margin-left:10px">10087780</span> <span>AISHA KHAN</span>
        <div class="t-caption" style="margin-top:3px">Post <span class="ident" style="font-size:12px">884</span> Assistant Professor · <b>Interview only</b></div></div>
      <div style="display:flex;padding:16px 0">
        ${gateCol('Scrutiny', 'el')}
        ${gateCol('Interview', 'pe')}
      </div>
      <div style="padding:10px 18px;border-top:1px solid var(--rule);background:var(--paper-sunk)">
        <span class="t-caption">Two gates, because this post type has two. The written-test column is <b>absent</b> — not disabled, not greyed. A greyed control is still a claim that the decision exists.</span></div>
    </div>
  </div>
  <div>
    <div class="t-label" style="margin-bottom:9px">A rejection carries its reason</div>
    <div style="border:1px solid var(--rule-strong);border-top:3px solid var(--brass);background:var(--paper-raised);border-radius:2px">
      <div style="padding:11px 18px;border-bottom:1px solid var(--rule)">
        <b class="t-sub">Update eligibility</b> <span class="ident" style="margin-left:10px">10087782</span> <span>FATIMA SIDDIQUI</span></div>
      <div style="display:flex;padding:16px 0">${gateCol('Scrutiny ', 're', '', true)}
        <div style="flex:1;padding:0 18px;border-left:1px solid var(--rule-strong)">
          <div class="t-label" style="margin-bottom:9px">Why it reads as due process</div>
          <p class="t-caption">Choosing <b>✕ Not eligible</b> moves focus straight into the remark. The requirement announces itself as the next step rather than as a red message after the fact, and <b>Save decisions</b> stays available but refuses until the remark has content — server-side, not only in the browser.</p>
          <p class="t-caption" style="margin-top:9px">The remark is disclosed to the candidate. It is the reason they will read, and the reason a court will read.</p>
        </div></div>
      <div style="padding:10px 18px;border-top:1px solid var(--rule);background:var(--paper-sunk);display:flex;justify-content:space-between;align-items:center">
        <span class="t-caption">Destructive and consequential actions are outlined, never filled.</span>
        <div style="display:flex;gap:9px"><button class="btn">Cancel</button><button class="btn p">Save decisions</button></div></div>
    </div>
  </div>
</div>

<div class="notice" style="margin-top:24px;max-width:92ch"><b>The two defects this control exists to fix.</b> The control being replaced renders a single dropdown labelled <i>“Pending / Not Eligible”</i> over three distinct stored values (1 / 0 / NULL) — on a decision that determines whether someone is considered for a job. And it renders all three gates on every post type, including interview-only posts where the schema’s own comment says the written-test value should be blank. Three explicit options; only the gates that exist.</div>` }));

/* ── A7 · Scrutiny workbench ★ ──────────────────────────────── */
const key = (k, what) => `<div style="display:flex;gap:10px;align-items:baseline;padding:4px 0;border-bottom:1px solid var(--rule)">
  <kbd style="font:500 12px var(--font-mono);border:1px solid var(--rule-strong);border-bottom-width:2px;border-radius:3px;padding:1px 6px;background:var(--paper-sunk);min-width:26px;text-align:center">${k}</kbd>
  <span class="t-caption" style="color:var(--ink)">${what}</span></div>`;

w('Workbench.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Queue')}
  <main class="main" style="padding-bottom:0">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Scrutiny workbench</h1>
        <div class="sub">Post 2599 · System Manager · <b>row 4 of 106</b> · 7 decided, 99 to read</div></div>
      <div class="mh-tools"><span class="t-caption">Queue position is in the URL — reload, back and a shared link all return here</span><button class="btn sm">Keys ?</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'snapshot #1', 'sealed', '23-01-2026', '-', 'measured', '07-03-2026', '-', 'decided 7', 'of 106'])}
      <div class="body">
        <div style="display:flex;gap:18px;align-items:flex-start">
          <div style="flex:0 0 200px">
            <div class="t-label" style="margin-bottom:8px">Queue</div>
            <div class="rec"><ul class="rows" style="font-size:13px">
              ${[['10087776', 'el', '✓'], ['10087777', 'el', '✓'], ['10087778', 're', '✕'], ['10087779', 'cur', '▌'],
      ['10087780', 'pe', '◦'], ['10087781', 'pe', '◦'], ['10087782', 'pe', '◦'], ['10087783', 'pe', '◦']]
      .map(([no, s, g]) => `<li style="display:flex;justify-content:space-between;padding:6px 12px;${s === 'cur' ? 'background:var(--green-wash);border-left:3px solid var(--brass)' : ''}">
        <span class="ident" style="font-size:12px">${no}</span><span class="b ${s === 'cur' ? 'pe' : s}"><span class="g">${g}</span></span></li>`).join('')}
            </ul><div style="padding:8px 12px;border-top:1px solid var(--rule)" class="t-caption">…98 more</div></div>
            <div style="margin-top:14px" class="rec"><div class="rec-h"><span class="t-label">Keyboard</span></div><div class="rec-b" style="padding:10px 12px">
              ${key('j / k', 'move down and up the queue')}
              ${key('.', 'expand the dossier')}
              ${key('e', 'focus the counterfoil')}
              ${key('1', 'set the focused gate ✓ Eligible')}
              ${key('2', 'set ✕ Not eligible — focus jumps to the remark')}
              ${key('3', 'set ◦ Pending')}
              ${key('Tab', 'next gate')}
              ${key('⏎', 'save and advance to the next undecided row')}
              ${key('Esc', 'back to the queue, nothing saved')}
              ${key('?', 'this list')}
              <p class="t-caption" style="margin-top:8px">A queue of 106 can be worked end to end without a pointer, and nothing in that sequence ever covers the evidence.</p>
            </div></div>
          </div>

          <div style="flex:1 1 auto;min-width:0">
            <div style="display:flex;gap:16px;align-items:stretch">
              <div style="flex:1;min-width:0">
                <div class="t-label" style="margin-bottom:8px">The claim</div>
                <div class="rec"><div class="rec-b">
                  ${kv([['Application', '<span class="ident">10087779</span>'], ['Name', '<b>MOHAMMAD BASIM ZAHID</b>'],
      ['Category', 'General'], ['DOB', '26-11-1984']])}
                  <div style="margin-top:10px;padding:9px 11px;background:var(--paper-sunk);border:1px solid var(--rule)">
                    <div class="t-label">Claimed — essential qualification</div>
                    <div style="font-size:14px;margin-top:3px"><b>M.C.A.</b>, Biju Patnaik University of Technology, 2009 · CGPA 7.10</div>
                  </div>
                  <div style="margin-top:9px;padding:9px 11px;background:var(--paper-sunk);border:1px solid var(--rule)">
                    <div class="t-label">Claimed — experience</div>
                    <div style="font-size:14px;margin-top:3px"><b>None recorded</b> · the post requires 8 years</div>
                  </div>
                  <div style="margin-top:9px;padding:9px 11px;background:var(--paper-sunk);border:1px solid var(--rule)">
                    <div class="t-label">Computed — age on 07-03-2026</div>
                    <div style="font-size:14px;margin-top:3px"><b class="num">41 years 3 months</b> · maximum 50</div>
                  </div>
                </div></div>
              </div>
              <div style="flex:1.15;min-width:0">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:8px">
                  <span class="t-label">The evidence</span>
                  <span class="t-caption">mca-degree-certificate.pdf · 1 of 2 · <a href="#">next document</a></span></div>
                <div class="rec" style="height:352px;display:flex;align-items:center;justify-content:center;background:var(--paper-sunk);color:var(--ink-faint);font:400 12px var(--font-mono)">
                  document viewer · page 1 of 2 · 100%</div>
              </div>
              <div style="flex:0 0 230px">
                <div class="t-label" style="margin-bottom:8px">The rule</div>
                <div class="rec"><div class="rec-b">
                  <p style="font-size:13px;line-height:1.5">“M.C.A. or M.Sc. (Computer Science) or B.E./B.Tech (CS/IT) with at least 55% marks, <b>and eight years’ experience in a supervisory information-technology post</b>.”</p>
                  <span class="cite" style="margin-top:8px">CRR Sch. II item 14 · ugc-nt-2026@1 · frozen 22-01-2026</span>
                  <p class="t-caption" style="margin-top:10px">Quoted, never paraphrased. What you read here is the text that was in force on the day the post was advertised.</p>
                </div></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="margin-top:20px;border-top:3px solid var(--brass);background:var(--paper-raised);border-left:1px solid var(--rule-strong);border-right:1px solid var(--rule-strong)">
      <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 20px;border-bottom:1px solid var(--rule)">
        <div><b class="t-sub">Update eligibility</b> <span class="ident" style="margin-left:10px">10087779</span> <span>MOHAMMAD BASIM ZAHID</span></div>
        <div class="t-caption">Written test + Interview · <b>row 4 of 106</b> · <span class="ident" style="font-size:12px">e</span> to focus, <span class="ident" style="font-size:12px">⏎</span> to save and advance</div>
      </div>
      <div style="display:flex;padding:14px 0">
        ${gateCol('Scrutiny', 'el', 'Qualification verified. Experience is nil against a requirement of eight years — refer to the committee before deciding.')}
        ${gateCol('Written test', 'pe')}
        ${gateCol('Interview', 'pe')}
        <div style="flex:0 0 210px;padding:0 20px;border-left:1px solid var(--rule-strong);display:flex;flex-direction:column;justify-content:flex-end;gap:9px">
          <button class="btn">Cancel</button><button class="btn p">Save decisions</button>
          <span class="t-caption">Saving advances to row 5 and keeps the counterfoil focused.</span>
        </div>
      </div>
    </div>
  </main></div>` }));

console.log('admin B written');
