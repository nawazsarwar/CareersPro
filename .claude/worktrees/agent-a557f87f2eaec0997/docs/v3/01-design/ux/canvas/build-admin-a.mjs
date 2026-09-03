import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, comp, NAV, marginalia } from './shared.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);
export const mg = (l) => `<div class="margin">${l.map((x) => x === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${x}</span>`).join('')}</div>`;

/* ── the trend, drawn as a page of the register ─────────────── */
const SUB = [1880, 980, 620, 700, 1180, 1740, 2314, 1520, 690, 520, 1120, 1810];
const PAID = [1490, 760, 470, 540, 900, 1380, 1902, 1180, 520, 390, 860, 1420];
const MONTHS = ['Sep 25', 'Oct', 'Nov', 'Dec', 'Jan 26', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'];

export const chart = (cw = 720, ch = 210) => {
  const L = 44, R = 96, T = 10, B = 26, max = 2400;
  const px = (i) => L + (i * (cw - L - R)) / (SUB.length - 1);
  const py = (v) => T + (ch - T - B) * (1 - v / max);
  const line = (a) => a.map((v, i) => `${i ? 'L' : 'M'}${px(i).toFixed(1)} ${py(v).toFixed(1)}`).join(' ');
  const area = (a) => `${line(a)} L${px(a.length - 1).toFixed(1)} ${py(0)} L${px(0)} ${py(0)} Z`;
  const grid = [600, 1200, 1800, 2400];
  return `<svg class="chart" width="${cw}" height="${ch}" viewBox="0 0 ${cw} ${ch}" role="img"
    aria-label="Applications submitted and paid, September 2025 to August 2026. Submitted peaks at 2,314 in March 2026; paid peaks at 1,902 in the same month.">
    ${grid.map((g) => `<line class="gl" x1="${L}" y1="${py(g)}" x2="${cw - R}" y2="${py(g)}"/>
      <text x="${L - 8}" y="${py(g) + 4}" text-anchor="end">${g.toLocaleString('en-IN')}</text>`).join('')}
    <line class="base" x1="${L}" y1="${py(0)}" x2="${cw - R}" y2="${py(0)}"/>
    <path class="f-sub" d="${area(SUB)}"/><path class="f-paid" d="${area(PAID)}"/>
    <path class="s-sub" d="${line(SUB)}"/><path class="s-paid" d="${line(PAID)}"/>
    ${MONTHS.map((m, i) => `<text x="${px(i)}" y="${ch - 8}" text-anchor="middle">${m}</text>`).join('')}
    <text class="lab" x="${cw - R + 8}" y="${py(SUB[11]) + 4}" style="fill:var(--info)">Submitted 1,810</text>
    <text class="lab" x="${cw - R + 8}" y="${py(PAID[11]) + 16}" style="fill:var(--green)">Paid 1,420</text>
    <text x="${px(6)}" y="${py(2314) - 8}" text-anchor="middle" class="lab">2,314</text>
  </svg>`;
};

/* ── the financial strip — the centrepiece ──────────────────── */
export const STRIP = () => {
  const seg = [['Received', '₹2,29,94,500', 66.6, 'rcv', '✓'], ['Awaited', '₹22,25,500', 6.4, 'awa', '◦'], ['Failed', '₹93,14,500', 27.0, 'fal', '✕']];
  return `<a href="#" style="display:block;color:inherit;text-decoration:none">
  <div style="display:flex;align-items:flex-end;margin-bottom:7px">
    ${seg.map(([n, f, p, , g]) => `<div style="flex:0 0 ${p}%;min-width:0;padding-right:10px">
      <div class="t-figure num" style="font-family:var(--font-mono);font-size:${n === 'Received' ? 28 : 21}px;white-space:nowrap">${f}</div>
      <div class="t-label" style="margin-top:3px;white-space:nowrap">${g} ${n} · ${p}%</div></div>`).join('')}
  </div>
  <div class="pbar" style="height:30px">
    ${seg.map(([, , p, c]) => `<span class="${c}" style="width:${p}%"></span><span class="gap"></span>`).join('')}
  </div>
  <div style="display:flex;justify-content:space-between;align-items:baseline;margin-top:7px">
    <span class="t-caption">Segments sum to <b class="num">₹3,45,34,500</b> attempted. Failed is <b>27.0%</b> of everything attempted, and <b>28.8%</b> of received plus failed — both are quoted because a bar whose segments do not sum to its stated total is the kind of figure that gets read out at a hearing.</span>
    <span class="t-sub" style="color:var(--green);white-space:nowrap;padding-left:16px">Open the reconciliation queue, failed only →</span>
  </div></a>`;
};

const figure = (n, label, href) => `<a href="#" style="flex:1;min-width:0;padding:0 20px;color:inherit;text-decoration:none;display:block">
  <div class="t-figure num">${n}</div><div class="t-label" style="margin-top:5px">${label}</div></a>`;

export const FIGURES = `<div style="display:flex;border-top:1px solid var(--rule-strong);border-bottom:1px solid var(--rule-strong);padding:16px 0;margin-bottom:22px">
  <div style="flex:1;min-width:0;padding:0 20px 0 0">${figure('1,045', 'Advertisements').replace('padding:0 20px', 'padding:0')}</div>
  <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong)">${figure('2,874', 'Total posts')}</div>
  <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong)">${figure('79,659', 'Total applications')}</div>
  <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong)">${figure('55,050', 'Registered users')}</div>
</div>`;

const goal = (label, n, pct) => `<div style="margin-bottom:14px">
  <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:5px">
    <span class="t-sub">${label}</span><span class="t-data"><b class="num">${n}</b> <span class="faint">/ 79,659</span> · <b class="num">${pct}%</b></span></div>
  <div class="pbar thin"><span class="rcv" style="width:${pct}%"></span></div></div>`;

const avatar = (initials, has) => has
  ? `<div style="width:34px;height:34px;border-radius:50%;background:var(--green-wash);border:1px solid var(--rule-strong);background-image:url(victoria-gate.jpg);background-size:cover;background-position:50% 30%"></div>`
  : `<div style="width:34px;height:34px;border-radius:50%;background:var(--paper-sunk);border:1px solid var(--rule-strong);display:flex;align-items:center;justify-content:center;font:600 11px var(--font-ui);color:var(--ink-faint)">${initials}</div>`;

/* ── A1 · Master dashboard ★★ ───────────────────────────────── */
export const dashboardBody = ({ scoped = false, dark = false } = {}) => `
<div class="shell">${NAV('Dashboard')}
  <main class="main">
    <div class="masthead">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px">
        <div><h1 class="t-page">Master register</h1>
          <div class="sub">${scoped
    ? '<b>Faculty of Arts and 3 departments — local recruitment.</b> Every figure on this page is limited to that subtree.'
    : 'University-wide · all recruitment · 13 faculties, 111 departments, 301 organisational units'}
          <span class="faint"> · as at 28 Aug 2026, 04:14</span></div></div>
        <div class="mh-tools"><button class="btn sm">Compact ⇕</button><button class="btn sm">Export ▾</button></div>
      </div>
      ${scoped ? '<div class="scoperule" style="margin-top:12px"></div>' : '<div class="mh-rule"></div>'}
    </div>
    <div class="withmargin">
      ${mg(scoped
      ? ['scope', '/1/11/', 'dean_office', '-', 'counters', 'as at 04:14', '-', 'audit seq', '4,182']
      : ['counters', 'read, not', 'aggregated', '-', 'as at', '04:14 today', '-', 'audit seq', '4,182'])}
      <div class="body">
        ${scoped ? `<div class="notice" style="margin-bottom:18px;border-left-color:var(--brass);background:var(--brass-wash)">
          <b>This is a scoped view.</b> You are seeing 4 advertisements, 11 posts and 312 applications inside <span class="ident" style="font-size:12px">/1/11/</span> — the Faculty of Arts and its three departments. The university-wide figures are 1,045, 2,874 and 79,659. A scoped page that looks identical to an unscoped one is how the wrong number reaches a court.</div>` : ''}
        ${scoped
      ? `<div style="display:flex;border-top:1px solid var(--rule-strong);border-bottom:1px solid var(--rule-strong);padding:16px 0;margin-bottom:22px">
          <div style="flex:1;min-width:0"><a href="#" style="color:inherit;text-decoration:none"><div class="t-figure num">4</div><div class="t-label" style="margin-top:5px">Advertisements · local</div></a></div>
          <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong);padding-left:20px"><a href="#" style="color:inherit;text-decoration:none"><div class="t-figure num">11</div><div class="t-label" style="margin-top:5px">Posts</div></a></div>
          <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong);padding-left:20px"><a href="#" style="color:inherit;text-decoration:none"><div class="t-figure num">312</div><div class="t-label" style="margin-top:5px">Applications</div></a></div>
          <div style="flex:1;min-width:0;border-left:1px solid var(--rule-strong);padding-left:20px"><a href="#" style="color:inherit;text-decoration:none"><div class="t-figure num">3</div><div class="t-label" style="margin-top:5px">Departments in scope</div></a></div>
        </div>` : FIGURES}

        ${gateRule('', 'Fees')}
        ${STRIP()}

        <div style="height:26px"></div>
        <div style="display:flex;gap:26px;align-items:flex-start">
          <div style="flex:1 1 auto;min-width:0">
            <div class="t-label" style="margin-bottom:10px">Applications submitted and paid · last 12 months</div>
            ${chart(760, 220)}
            <p class="t-caption" style="margin-top:6px">Inline SVG against the tokens. The two series are separated by weight, dash pattern and hue, and the gridlines sit on the same rhythm as the table rules below — a chart here is a ruled record that happens to be plotted.</p>
          </div>
          <div style="flex:0 0 300px">
            <div class="t-label" style="margin-bottom:12px">Goal completions</div>
            ${goal('Paid', '48,381', '60.7')}
            ${goal('Submitted', '63,907', '80.2')}
            ${goal('In review', '15,752', '19.8')}
          </div>
        </div>

        <div style="height:28px"></div>
        ${gateRule('', 'Statutory deadlines')}
        <div class="rec" style="border-left:3px solid var(--rejected)">
          <table class="tbl"><caption>3 advertisements approaching or breaching a statutory limit · SLA belongs on this screen, not only in a report</caption>
          <thead><tr><th style="width:150px">ADVERTISEMENT</th><th style="width:190px">LIMIT</th><th style="width:120px">DUE</th><th style="width:130px">REMAINING</th><th>EXTENSION</th></tr></thead><tbody>
          <tr><td><a href="#" class="ident">8/2025/T</a></td><td>Six-month process cap<div class="t-caption">DoPT O.M. Misc.14017/15/2015</div></td><td class="num">03-09-2026</td>
              <td><b class="b re"><span class="g">✕</span> 4 days</b></td><td class="t-caption">None recorded. <a href="#">Record a Vice-Chancellor’s approval</a></td></tr>
          <tr><td><a href="#" class="ident">2/2026/NT</a></td><td>Six-month process cap</td><td class="num">22-07-2026</td>
              <td><b class="b re"><span class="g">✕</span> breached, 37 days</b></td><td class="t-caption">VC approval <span class="ident" style="font-size:12px">VC/2026/118</span> dated 18-07-2026</td></tr>
          <tr><td><a href="#" class="ident">11/2026/T</a></td><td>Thirty-day advertisement window<div class="t-caption">CRR</div></td><td class="num">06-09-2026</td>
              <td><b class="b pe"><span class="g">◦</span> 9 days</b></td><td class="t-caption">—</td></tr>
          </tbody></table></div>

        <div style="height:28px"></div>
        <div style="display:flex;gap:26px;align-items:flex-start">
          <div style="flex:1 1 auto;min-width:0">
            <div class="t-label" style="margin-bottom:10px">Latest applications</div>
            <div class="rec"><table class="tbl"><thead><tr><th style="width:110px">APP NO</th><th>POST</th><th style="width:130px">STATUS</th><th style="width:104px">DATE</th></tr></thead><tbody>
            ${[['10097909', 'Guest Teacher — Business Administration, Malappuram Centre (Kerala)', 'in', 'Submitted', '22-08-2026'],
      ['10097908', 'Guest Teacher(s) — Business Administration, Kishanganj Centre (Bihar)', 'in', 'Submitted', '22-08-2026'],
      ['10097907', 'Assistant Professor (Contractual) — Master of Business Administration', 'in', 'Submitted', '22-08-2026'],
      ['10097906', 'Guest Teacher — Commerce, Murshidabad Centre (West Bengal)', 'pe', 'Awaiting fee', '22-08-2026'],
      ['10097905', 'Junior Assistant — Office of the Controller of Examinations', 'el', 'Paid', '22-08-2026']]
      .map(([no, post, s, word, d]) => `<tr><td><a href="#" class="ident">${no}</a></td><td>${post}</td><td>${badge(s, word)}</td><td class="num">${d}</td></tr>`).join('')}
            </tbody></table></div>
          </div>
          <div style="flex:0 0 300px">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:10px">
              <span class="t-label">Latest members</span><span class="chip">20 new</span></div>
            <div class="rec"><ul class="rows">
            ${[['Mustafa Kamaluddin', 'MK', true, '22 Aug'], ['Lidar Nandkumar', 'LN', false, '22 Aug'],
      ['Mohd Haris', 'MH', false, '22 Aug'], ['Vikas Gupta', 'VG', false, '22 Aug'],
      ['Hiba Rahman', 'HR', false, '22 Aug'], ['Neha Tiwari', 'NT', false, '21 Aug']]
      .map(([n, i, has, d]) => `<li style="display:flex;gap:11px;align-items:center">
        ${avatar(i, has)}<span style="flex:1;font-size:13px">${n}</span><span class="t-caption num">${d}</span></li>`).join('')}
            </ul></div>
            <p class="t-caption" style="margin-top:8px">Photographs are pre-generated 34px conversions, lazy-loaded. Most users have none, so the initials block is the normal case, not the fallback.</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>`;

w('Main.dc.html', artboard({ w: 1440, body: dashboardBody() }));

/* ── A6 · Advertisement list ────────────────────────────────── */
const advRows = [
  ['1045', 'Non-teaching posts in the University', '2-2026-nt-non-teaching-posts', '22-01-2026', 'General', 'Regular'],
  ['1044', 'Faculty positions — Faculty of Medicine', '11-2026-t-faculty-of-medicine', '19-01-2026', 'General', 'Regular'],
  ['1043', 'Guest Teachers — Murshidabad Centre', '9-2026-l-guest-teachers-murshidabad', '14-01-2026', 'Local', 'Guest'],
  ['1042', 'Assistant Professor (Contractual) — MBA', '7-2026-l-assistant-professor-mba', '11-01-2026', 'Local', 'Contractual'],
  ['1041', 'Technical staff — Z.H. College of Engineering', '5-2026-nt-technical-staff-zhcet', '08-01-2026', 'General', 'Regular'],
];

w('AdvertList.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Advertisements')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Advertisements</h1>
        <div class="sub">University-wide · 1,045 in the register · sorted by ID, newest first</div></div>
      <div class="mh-tools"><button class="btn sm">Compact ⇕</button><button class="btn sm">Columns ▾</button><button class="btn p sm">New advertisement</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['sort', 'id desc', '-', '1,045 rows', 'page 1 of 11', '-', 'exports', 'audited'])}
      <div class="body">
        <div class="tbar">
          <div style="display:flex;gap:10px;align-items:center">
            <span class="filterchip"><b>3 selected</b><a href="#">Delete</a><a href="#">Clear</a></span>
            <span class="t-caption">Select all 100 on this page · <a href="#">select all 1,045</a></span></div>
          <div style="display:flex;gap:12px;align-items:center">
            <span class="exp">${['Copy', 'CSV', 'Excel', 'PDF', 'Print', 'Columns'].map((e) => `<a href="#">${e}</a>`).join('')}</span>
            <input class="search" placeholder="Search all columns" value=""></div>
        </div>
        <table class="tbl"><caption>Advertisements · no filters applied · 1,045 rows</caption>
          <thead><tr>
            <th style="width:34px"><input type="checkbox" aria-label="Select all rows on this page"></th>
            <th style="width:64px" aria-sort="descending"><button style="all:unset;cursor:pointer" class="sortable">ID</button></th>
            <th>TITLE</th><th style="width:250px">SLUG</th><th style="width:100px">DATED</th>
            <th style="width:92px">TYPE</th><th style="width:120px">APPOINTMENT</th><th style="width:130px">ACTIONS</th></tr>
            <tr class="filters"><td></td><td><input aria-label="Filter by ID"></td>
              <td><input aria-label="Filter by title" placeholder="search"></td>
              <td><input aria-label="Filter by slug" placeholder="search"></td>
              <td><input aria-label="Filter by date" placeholder="range"></td>
              <td><select aria-label="Filter by type"><option>All</option></select></td>
              <td><select aria-label="Filter by appointment nature"><option>All</option></select></td><td></td></tr>
          </thead>
          <tbody>${advRows.map(([id, t, slug, d, type, nat], i) => `<tr class="${i === 0 ? 'sel' : ''}">
            <td><input type="checkbox" ${i === 0 ? 'checked' : ''} aria-label="Select advertisement ${id}"></td>
            <td class="ident">${id}</td><td><a href="#">${t}</a></td>
            <td class="ident faint" style="font-size:12px">${slug}</td><td class="num">${d}</td>
            <td>${type}</td><td>${nat}</td>
            <td><a href="#">View</a> · <a href="#">Edit</a> · <a href="#">Delete</a></td></tr>`).join('')}
          </tbody></table>
        <div class="pager"><span>Showing 1–100 of 1,045</span>
          <span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">3</a><a href="#">…</a><a href="#">11</a><a href="#">›</a></span>
          <span>100 per page ▾</span></div>
        <div class="notice" style="margin-top:14px"><b>Two columns changed from the system being replaced.</b> The slug loses its unix-timestamp suffix — <span class="ident" style="font-size:12px">…-faculty-of-medicine-1787396468</span> was a de-duplication hack — and <b>Appointment nature</b> is now a column, because the Dean’s-office queues filter on it. <b>Advertisement URL</b> was dropped from the default view: it is empty on every row. It is still available under Columns.</div>
      </div>
    </div>
  </main></div>` }));

/* ── A6b · Advertisement detail ─────────────────────────────── */
w('AdvertDetailAdmin.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Advertisements')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">Non-teaching posts in the University</h1>
        <div class="sub">Advertisement <b>2/2026/NT</b> dated 22.01.2026 · General · Regular · published</div></div>
      <div class="mh-tools"><button class="btn sm">Corrigendum</button><button class="btn sm">Download paid applications</button><button class="btn sm">Download all</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'OU snapshot', '#1,208', '-', 'published by', 'r.admin', '22-01-2026', '-', 'audit seq', '3,914'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div class="rec"><div class="rec-b">
            <dl class="dl" style="grid-template-columns:190px 1fr;font-size:14px">
              <dt>ID</dt><dd class="ident">1045</dd>
              <dt>Title</dt><dd>Non-teaching posts in the University</dd>
              <dt>Slug</dt><dd class="ident">2-2026-nt-non-teaching-posts</dd>
              <dt>Description</dt><dd><a href="#">Show description</a></dd>
              <dt>Dated</dt><dd class="num">22-01-2026</dd>
              <dt>Type</dt><dd>General</dd>
              <dt>Appointment nature</dt><dd>Regular</dd>
              <dt>Organisational unit</dt><dd>Aligarh Muslim University <span class="t-caption">(from the snapshot taken at publication)</span></dd>
              <dt>Document</dt><dd><a href="#">notification-2-2026-nt.pdf</a> <span class="t-caption">412 KB</span></dd>
              <dt>Governed by</dt><dd><span class="ident">ugc-nt-2026@1</span>${cite('frozen 2026-01-22 · the ruleset cannot change for this advertisement')}</dd>
            </dl>
          </div></div>
          <div style="height:20px"></div>
          ${gateRule('', 'Child posts')}
          <div class="rec"><table class="tbl">
            <caption>4 posts · TOT / SUB / PAID / INT counts are read from counter columns, never aggregated per render</caption>
            <thead><tr><th style="width:56px">ID</th><th style="width:150px">POST TYPE</th><th>TITLE</th>
              <th style="width:88px">PAY</th><th style="width:60px">FEE</th><th style="width:96px">LAST DATE</th>
              <th style="width:146px">TOT / SUB / PAID / INT</th><th style="width:96px">STATUS</th></tr></thead><tbody>
            <tr><td class="ident">2599</td><td>General (NT)</td>
              <td><a href="#"><b>System Manager</b></a><div class="t-caption">Vacancies: 1 · Location: AMU</div></td>
              <td>Level-12</td><td class="num">₹500</td><td class="num">07-03-2026</td><td>${comp('106', '63', '58', '13')}</td><td>${badge('in', 'Open')}</td></tr>
            <tr><td class="ident">2604</td><td>General (NT)</td>
              <td><a href="#"><b>Senior Technical Assistant</b></a><div class="t-caption">Vacancies: 2 · Location: ZHCET</div></td>
              <td>Level-6</td><td class="num">₹500</td><td class="num">07-03-2026</td><td>${comp('318', '241', '224', '7')}</td><td>${badge('in', 'Open')}</td></tr>
            <tr><td class="ident">2607</td><td>General (NT)</td>
              <td><a href="#"><b>Junior Assistant</b></a><div class="t-caption">Vacancies: 6 · Location: Controller of Examinations</div></td>
              <td>Level-2</td><td class="num">₹500</td><td class="num">07-03-2026</td><td>${comp('954', '765', '710', '31')}</td><td>${badge('in', 'Open')}</td></tr>
            <tr><td class="ident">2609</td><td>General (NT)</td>
              <td><a href="#"><b>Laboratory Assistant</b></a><div class="t-caption">Vacancies: 3 · Location: Physics</div></td>
              <td>Level-4</td><td class="num">₹500</td><td class="num">07-03-2026</td><td>${comp('402', '355', '331', '9')}</td><td>${badge('in', 'Open')}</td></tr>
          </tbody></table></div>
          <div style="height:20px"></div>
          ${gateRule('', 'Corrigenda')}
          <div class="rec"><ul class="rows">
            <li style="display:grid;grid-template-columns:110px 1fr 90px;gap:14px;align-items:baseline">
              <span class="ident" style="color:var(--brass)">05-02-2026</span>
              <span><b>Corrigendum 1</b> — Post 2604: essential qualification amended to read “B.E./B.Tech in Electronics”.<div class="t-caption">Published by r.admin · audit seq 3,988</div></span>
              <a href="#" style="text-align:right">View</a></li>
            <li style="display:grid;grid-template-columns:110px 1fr 90px;gap:14px;align-items:baseline">
              <span class="ident" style="color:var(--brass)">19-02-2026</span>
              <span><b>Corrigendum 2</b> — Last date extended from 28-02-2026 to 07-03-2026.<div class="t-caption">Published by r.admin · audit seq 4,051</div></span>
              <a href="#" style="text-align:right">View</a></li>
          </ul></div>
        </div>
        <aside style="flex:0 0 290px">
          <div class="rec"><div class="rec-h"><span class="t-label">Application statistics</span></div><div class="rec-b">
            <div style="border-bottom:1px solid var(--rule);padding-bottom:10px;margin-bottom:10px">
              <div class="t-figure num">954</div><div class="t-label">Total</div></div>
            <div style="display:flex"><div style="flex:1"><div class="t-figure num" style="font-size:24px">765</div><div class="t-label">Submitted</div></div>
              <div style="flex:1;border-left:1px solid var(--rule-strong);padding-left:14px"><div class="t-figure num" style="font-size:24px">710</div><div class="t-label">Paid</div></div></div>
          </div></div>
          <div class="rec"><div class="rec-h"><span class="t-label">Publication freeze</span></div><div class="rec-b">
            <p class="t-caption">Publishing froze two things on 22-01-2026 and neither can be changed while this advertisement lives:</p>
            <div style="margin-top:10px;font-size:13px">The ruleset <span class="ident" style="font-size:12px">ugc-nt-2026@1</span></div>
            <div style="font-size:13px">The organisational-unit tree, snapshot <span class="ident" style="font-size:12px">#1,208</span></div>
            <span class="cite" style="margin-top:10px">A candidate is judged by the rules in force on the day the post was advertised, not the rules in force when their file is read.</span>
          </div></div>
        </aside>
      </div>
    </div>
  </main></div>` }));

/* ── A5 · Post detail + pipeline ★ ──────────────────────────── */
const pipeFig = (n, label, sub = '') => `<div style="flex:1;min-width:0;padding:12px 14px">
  <div class="t-figure num">${n}</div><div class="t-label" style="margin-top:4px">${label}</div>
  ${sub ? `<div class="t-caption">${sub}</div>` : ''}</div>`;

w('PostDetail.dc.html', artboard({
  w: 1440, body: `<div class="shell">${NAV('Posts')}
  <main class="main">
    <div class="masthead"><div style="display:flex;justify-content:space-between;align-items:flex-start">
      <div><h1 class="t-page">System Manager, Prof. M.N. Farooqui Computer Centre</h1>
        <div class="sub">Post <b>2599</b> · Advertisement 2/2026/NT dated 22.01.2026 · open until 07-03-2026</div></div>
      <div class="mh-tools"><button class="btn sm">Edit post</button><button class="btn sm">Attendance sheet</button><button class="btn sm">Back to list</button></div>
    </div><div class="mh-rule"></div></div>
    <div class="withmargin">${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'OU snapshot', '/1/44/', '-', 'age measured', '07-03-2026', 'CRR Rule 14', '-', 'audit seq', '4,182'])}
      <div class="body" style="display:flex;gap:26px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div class="rec"><div class="rec-b">
            <dl class="dl" style="grid-template-columns:186px 1fr;font-size:14px;row-gap:8px">
              <dt>Advertisement</dt><dd><a href="#">2/2026/NT</a> dated 22.01.2026</dd>
              <dt>Post type</dt><dd>General (Non-Teaching)<div class="t-caption">Selection: written test, then interview</div></dd>
              <dt>Serial no</dt><dd class="num">1</dd>
              <dt>Subject</dt><dd class="faint">—</dd>
              <dt>Slug</dt><dd class="ident" style="font-size:12px">2599-system-manager-farooqui-computer-centre</dd>
              <dt>Location</dt><dd>AMU Aligarh</dd>
              <dt>Designation</dt><dd>System Manager <span class="t-caption">(sanctioned strength 1, filled 0)</span></dd>
              <dt>Organisational unit</dt><dd>Prof. M.N. Farooqui Computer Centre<div class="t-caption ident" style="font-size:12px">/1/44/ · from snapshot #1,208</div></dd>
              <dt>Appointment nature</dt><dd>Regular · permanent</dd>
              <dt>Pay and vacancies</dt><dd><b>Pay Level-12</b> — ₹78,800–2,09,200 plus allowances<div class="t-caption">Vacancies 1 · Fee ₹500</div></dd>
            </dl>
            <div style="border-top:1px solid var(--rule-strong);margin-top:14px;padding-top:12px">
              <div class="t-label" style="margin-bottom:9px">Important dates</div>
              <div style="display:flex;gap:0">
                ${[['Opening', '22-01-2026', '13:34', 'var(--eligible)'], ['Closing', '07-03-2026', '23:59', 'var(--brass)'], ['Payment last date', '07-03-2026', '23:59', 'var(--rejected)']]
      .map(([l, d, t, c], i) => `<div style="flex:1;padding-left:${i ? 16 : 0}px;${i ? 'border-left:1px solid var(--rule);' : ''}">
        <div style="display:flex;gap:7px;align-items:center"><i style="width:3px;height:17px;background:${c};display:block"></i>
        <b class="num" style="font-size:15px">${d}</b></div>
        <div class="t-label" style="margin-top:3px">${l} · ${t}</div></div>`).join('')}
              </div>
              <p class="t-caption" style="margin-top:9px">The window is <b>45 days</b>. The statutory minimum is 30.<span class="cite">CRR · DoPT O.M. Misc.14017/15/2015-Estt.(RR)</span></p>
            </div>
          </div></div>
        </div>
        <aside style="flex:0 0 380px">
          <div class="t-label" style="margin-bottom:8px">Application statistics</div>
          <div class="rec" style="margin-bottom:16px">
            <div style="border-bottom:1px solid var(--rule)">${pipeFig('106', 'Total applications')}</div>
            <div style="display:flex">${pipeFig('63', 'Submitted')}<div style="border-left:1px solid var(--rule-strong);flex:1;min-width:0">${pipeFig('58', 'Paid')}</div></div>
          </div>
          <div class="t-label" style="margin-bottom:8px">Eligibility statistics</div>
          <div class="rec" style="margin-bottom:16px">
            <div style="border-bottom:1px solid var(--rule)">${pipeFig('7', 'Scrutiny eligible', 'of 63 submitted')}</div>
            ${pipeFig('0', 'Eligible for interview', 'written test not yet held')}
          </div>
          <div class="t-label" style="margin-bottom:8px">Download statistics</div>
          <div class="rec" style="margin-bottom:16px">${pipeFig('0', 'Interview letters issued')}</div>
          <p class="t-caption">Every figure in the eligibility block reads from <span class="ident" style="font-size:12px">eligibility_decisions</span>. On the four-column schema being replaced these three numbers cannot be computed at all.</p>
        </aside>
      </div>
      <div class="body" style="margin-top:24px">
        ${gateRule('', 'Applications for this post')}
        <form class="tbar" method="GET" style="gap:18px">
          <div style="display:flex;gap:16px;align-items:flex-end">
            <div><label class="t-label" style="display:block;margin-bottom:4px" for="et">Eligibility type</label>
              <select class="inp sm" id="et" style="width:210px"><option>All applications</option><option>Scrutiny</option><option>Written test</option><option>Interview</option></select></div>
            <div><label class="t-label" style="display:block;margin-bottom:4px" for="es">Eligibility status</label>
              <select class="inp sm" id="es" style="width:190px"><option>Any status</option><option>✓ Eligible</option><option>✕ Not eligible</option><option>◦ Pending</option></select></div>
            <button class="btn p sm" style="height:32px">Filter</button>
            <span class="t-caption">The second filter depends on the first, and both are in the URL.</span>
          </div>
          <input class="search" placeholder="Search 106 applications">
        </form>
        <table class="tbl"><caption>106 applications for post 2599 · no eligibility filter applied</caption>
          <thead><tr><th style="width:34px"><input type="checkbox" aria-label="Select all rows on this page"></th>
            <th style="width:104px">APP NO</th><th>CANDIDATE</th><th style="width:104px">CATEGORY</th>
            <th style="width:104px">SUBMITTED</th><th style="width:126px">SCRUTINY</th><th style="width:126px">WRITTEN TEST</th>
            <th style="width:120px">INTERVIEW</th><th style="width:110px">ACTIONS</th></tr></thead>
          <tbody>
          ${[['10087779', 'MOHAMMAD BASIM ZAHID', 'General', '23-01-2026', 'el', 'Eligible'],
      ['10087780', 'AISHA KHAN', 'OBC-NCL', '23-01-2026', 'pe', 'Pending'],
      ['10087781', 'RAKESH KUMAR VERMA', 'SC', '24-01-2026', 'el', 'Eligible'],
      ['10087782', 'FATIMA SIDDIQUI', 'OBC-NCL', '24-01-2026', 're', 'Not eligible'],
      ['10087783', 'S. ANANTHAKRISHNAN', 'General', '25-01-2026', 'pe', 'Pending']]
      .map(([no, n, c, d, s, word]) => `<tr><td><input type="checkbox" aria-label="Select application ${no}"></td>
        <td class="ident">${no}</td><td>${n}</td><td>${c}</td><td class="num">${d}</td>
        <td>${badge(s, word)}</td><td>${badge('pe', 'Pending')}</td><td>${badge('pe', 'Pending')}</td>
        <td><a href="#" aria-label="Update eligibility for application ${no}">Eligibility</a></td></tr>`).join('')}
          </tbody></table>
        <div class="pager"><span>Showing 1–5 of 106</span><span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">›</a></span><span>100 per page ▾</span></div>
      </div>
    </div>
  </main></div>` }));

console.log('admin A written');
