import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge, comp } from './shared.mjs';

const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

/* ── Tokens ─────────────────────────────────────────────────── */
const swatch = (name, val, note = '', dark = false) => `
<div style="display:grid;grid-template-columns:44px 1fr;gap:10px;align-items:center;padding:6px 0;border-bottom:1px solid var(--rule)">
  <i style="display:block;height:30px;background:${val};border:1px solid var(--rule-strong);border-radius:2px"></i>
  <div style="min-width:0">
    <span class="ident" style="font-size:12px">${name}</span>
    <span class="ident" style="font-size:12px;color:var(--ink-faint);margin-left:6px">${val}</span>
    ${note ? `<div class="t-caption" style="font-size:11px;color:${dark ? 'var(--ink-faint)' : 'var(--ink-muted)'}">${note}</div>` : ''}
  </div>
</div>`;

const set = (dark) => [
  ['Ground', [
    ['--paper', dark ? '#0D1210' : '#F7F8F6', 'page'],
    ['--paper-raised', dark ? '#141A17' : '#FFFFFF', 'record surface'],
    ['--paper-sunk', dark ? '#0A0F0C' : '#EEF0EC', 'table header, inset'],
  ]],
  ['Ink', [
    ['--ink', dark ? '#E8EDE6' : '#10150F', dark ? '13.4:1' : '16.8:1 · AAA'],
    ['--ink-muted', dark ? '#A6AFA4' : '#4A524A', '7.9:1 · AAA'],
    ['--ink-faint', dark ? '#737C71' : '#7B837A', '4.6:1 · captions only, never body'],
  ]],
  ['Rule — used more than any accent', [
    ['--rule', dark ? '#263029' : '#D3D8D0', dark ? 'weight rises to 1.5px on dark' : 'hairline, 1px'],
    ['--rule-strong', dark ? '#3A473C' : '#A9B2A6', 'column rule, section rule'],
  ]],
  ['Brand — given, not chosen', [
    ['--green', dark ? '#4FA97A' : '#0C4A2E', dark ? 'lifted — #0C4A2E fails on dark' : 'AMU forest green · 8.9:1'],
    ['--green-lift', dark ? '#6BC094' : '#10643E', 'hover'],
    ['--green-deep', dark ? '#2F7F55' : '#072F1D', dark ? 'added 28-08 — pressed, relative to the lifted base' : 'pressed'],
    ['--green-wash', dark ? '#16281E' : '#E4EDE7', 'selected row'],
  ]],
  ['Accent — brass, from the crest. Not terracotta', [
    ['--brass', dark ? '#C9A34E' : '#8A6B1F', ''],
    ['--brass-wash', dark ? '#2A2113' : '#F5EEDC', dark ? 'added 28-08 — the deficiency banner was a cream slab on dark' : 'the deficiency banner'],
  ]],
  ['Semantic — separable without hue', [
    ['--eligible', dark ? '#57B27C' : '#1B6B3A', 'glyph only, never a fill'],
    ['--rejected', dark ? '#E07A7A' : '#9A2C2C', 'outlined, never filled'],
    ['--pending', dark ? '#C9A34E' : '#8A6B1F', '= brass; pending is the neutral state'],
    ['--info', dark ? '#6FA8D6' : '#1F4E79', dark ? 'added 28-08 — #1F4E79 measured 2.1:1 here' : ''],
    ['--info-wash', dark ? '#12202C' : '#E8EEF4', 'added 28-08 — the one new token: neutral system statement'],
  ]],
];

const tokenCol = (dark) => `
<div class="art${dark ? ' slate' : ''}" style="width:452px;padding:20px;border:1px solid var(--rule-strong);border-radius:2px">
  <div class="t-label" style="margin-bottom:2px">${dark ? 'Slate ledger · [data-theme="dark"]' : 'Register · :root'}</div>
  <div class="t-section" style="margin-bottom:14px">${dark ? 'Dark' : 'Light'}</div>
  ${set(dark).map(([g, rows]) => `<div class="t-label" style="margin:16px 0 4px;color:var(--ink-faint)">${g}</div>${rows.map((r) => swatch(r[0], r[1], r[2], dark)).join('')}`).join('')}
</div>`;

w('Tokens.dc.html', artboard({
  w: 1000, pad: true, body: `
${gateRule('', 'Tokens')}
<p class="t-caption" style="max-width:64ch;margin-bottom:18px">Nineteen tokens. Eighteen inherited from <span class="ident" style="font-size:12px">design-system.md</span> §2 and taken as given; <b>--info-wash</b> added, and three values completed for dark where they had been defined only in light. Dark is not an inversion: the ground goes green-black, ink warms, and <b>rules gain weight</b>, because a hairline disappears on a dark ground.</p>
<div style="display:flex;gap:24px;align-items:flex-start">${tokenCol(false)}${tokenCol(true)}</div>
<div class="notice" style="margin-top:20px;max-width:920px">
  <b>Never give a colour its only definition inside a media query.</b> The complete light palette is declared on bare <span class="ident" style="font-size:12px">:root</span>; only what changes is redefined under <span class="ident" style="font-size:12px">@media (prefers-color-scheme: dark)</span> guarded by <span class="ident" style="font-size:12px">:not([data-theme="light"])</span>, and repeated under <span class="ident" style="font-size:12px">[data-theme="dark"]</span> so the toggle wins in both directions.
</div>` }));

/* ── Type specimen ──────────────────────────────────────────── */
const row = (tok, spec, sample, cls = '') => `
<tr>
  <td style="padding:14px 12px 14px 0;border-bottom:1px solid var(--rule);width:112px;vertical-align:top"><span class="ident" style="font-size:12px">${tok}</span></td>
  <td style="padding:14px 12px;border-bottom:1px solid var(--rule);width:210px;vertical-align:top"><span class="t-caption">${spec}</span></td>
  <td style="padding:14px 0 14px 12px;border-bottom:1px solid var(--rule);border-left:1px solid var(--rule)"><div class="${cls}">${sample}</div></td>
</tr>`;

w('Type.dc.html', artboard({
  w: 940, pad: true, body: `
${gateRule('', 'Type')}
<p class="t-caption" style="max-width:66ch;margin-bottom:16px">Spectral for display only. IBM Plex Sans for UI and data, because it has genuine tabular figures and holds at 13px. IBM Plex Mono for anything compared character by character. <b>Four tokens added</b> where the inherited scale had no entry for something that appears on nearly every screen.</p>
<table style="width:100%">
${row('--t-page', '30 / 1.15 · Spectral 600', 'Master register', 't-page')}
${row('--t-figure', '32 / 1.0 · Plex Sans 500 · tabular<br><b>added</b> — a figure must out-rank a title, and 30px Spectral 600 out-weighs 30px Plex', '79,659', 't-figure')}
${row('--t-section', '20 / 1.25 · Spectral 600', 'A5 Academic qualifications', 't-section')}
${row('--t-sub', '15 / 1.35 · Plex Sans 600', 'Correspondence address', 't-sub')}
${row('--t-body', '15 / 1.6 · Plex Sans 400 · AAA in the form', 'Your experience certificate is illegible. Re-upload it in Employment history.', 't-body')}
${row('--t-data', '13 / 1.45 · Plex Sans 400 · tabular', 'MOHAMMAD BASIM ZAHID &nbsp; General &nbsp; 23-01-2026 &nbsp; 92.5', 't-data')}
${row('--t-ident', '13 · Plex Mono 400 · −0.01em<br><b>added</b> — mono reads wider; negative tracking holds 2599/2026/00412 in its column', '10087779 &nbsp; 2599/2026/00412 &nbsp; 884/2026/01109', 'ident')}
${row('--t-label', '11 / 1.2 · Plex Sans 600 · 0.08em', 'TOTAL APPLICATIONS', 't-label')}
${row('--t-caption', '12 / 1.4 · Plex Sans 400', 'Age is measured against the post’s registration end date.', 't-caption')}
${row('.citation', '12 · Plex Mono 400 · 2px --rule-strong left border', 'UGC 2018 cl. 4.1 II', 'cite')}
${row('--t-hi', '15 / 1.7 · Plex Sans Devanagari<br><b>added</b> — leading for the shirorekha and matras', 'सिस्टम मैनेजर, प्रो. एम.एन. फ़ारूक़ी कंप्यूटर सेंटर', 'hi')}
${row('--t-ur', '17 / 2.0 · Noto Nastaliq Urdu · dir=rtl<br><b>added</b> — Nastaliq’s x-height is two-thirds of Plex’s and it descends steeply', '<span dir="rtl" lang="ur">سسٹم مینیجر، پروفیسر ایم این فاروقی کمپیوٹر سینٹر</span>', 'ur')}
</table>
<div style="display:flex;gap:22px;margin-top:22px">
  <div class="rec" style="flex:1"><div class="rec-h"><span class="t-label">Tabular figures are mandatory</span></div>
    <div class="rec-b"><div style="display:flex;gap:36px">
      <div><div class="t-label" style="margin-bottom:6px">Tabular · correct</div><div class="t-data" style="font-variant-numeric:tabular-nums">92.5<br>110.0<br>7.5<br>1,045</div></div>
      <div><div class="t-label" style="margin-bottom:6px">Proportional · unscannable</div><div class="t-data" style="font-variant-numeric:proportional-nums;color:var(--ink-faint)">92.5<br>110.0<br>7.5<br>1,045</div></div>
    </div></div></div>
  <div class="rec" style="flex:1"><div class="rec-h"><span class="t-label">One system, two type baselines</span></div>
    <div class="rec-b t-caption">Staff read data at <b>13px</b> in 32px rows. Candidates read it at <b>15px</b> in 44px rows. Those are the only two values that differ. <b>Identifiers and citations are the same size on both surfaces</b> — an application number and a clause reference are the same object whoever is reading them, and a candidate quoting <span class="ident" style="font-size:12px">App. II Table 2 row 1</span> in a grievance must be reading the string an officer reads.</div></div>
</div>` }));

/* ── Gate rule ──────────────────────────────────────────────── */
w('GateRule.dc.html', artboard({
  w: 940, pad: true, body: `
${gateRule('', 'The gate rule')}
<p class="t-caption" style="max-width:66ch;margin-bottom:22px">One ornamental gesture in the entire product, traced from Victoria Gate’s four-centred arch. A hairline <span class="ident" style="font-size:12px">--rule-strong</span> interrupted by the arch, a second <span class="ident" style="font-size:12px">--rule</span> hairline 3px beneath, the label 12px below that. <b>Left-inset over the section number</b>, not centred: centred on a 1024px rule it floats as decoration; over the number it is a paraph — the mark a clerk puts at the head of an entry.</p>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:34px 40px">
  <div>${gateRule('A5', 'Academic qualifications')}<div class="t-caption">24 × 10 — section head. The default, and the only one used inside the application form.</div></div>
  <div>${gateRule('', 'Part B1 · Research claims', 'lg')}<div class="t-caption">36 × 15 — page head and print. Used once per printed statutory form.</div></div>
  <div>${gateRule('', '(v) Journal articles', 'sm')}<div class="t-caption">16 × 7 — sub-form head, and the mobile section head at 390px.</div></div>
  <div style="align-self:end">
    <div class="rec"><div class="rec-b">
      <div class="t-sub" style="margin-bottom:6px">Where it never appears</div>
      <div class="t-caption">Table headers · navigation · the footer · empty states · the sign-in card · bullets · the favicon · loading states · toasts. A build test asserts <span class="ident" style="font-size:12px">count(gate-rule) === count(layout.section)</span> in every rendered view, because restraint that depends on memory is not restraint.</div>
    </div></div>
  </div>
</div>

<div style="margin-top:30px;display:flex;gap:26px;align-items:flex-start">
  <div class="rec" style="flex:0 0 300px"><div class="rec-h"><span class="t-label">Construction</span></div><div class="rec-b">
    <svg width="252" height="120" viewBox="0 0 252 120" aria-hidden="true">
      <path d="M0 99.5 H68 C97 99.5 109 60 123 19 C137 60 149 99.5 178 99.5 H252" fill="none" stroke="var(--rule-strong)" stroke-width="1.5"/>
      <line x1="0" y1="118" x2="252" y2="118" stroke="var(--rule)" stroke-width="1.5"/>
      <line x1="123" y1="19" x2="123" y2="112" stroke="var(--brass)" stroke-width="1" stroke-dasharray="3 3"/>
      <text x="129" y="34" style="font:400 11px var(--font-mono);fill:var(--brass)">apex x=12</text>
      <text x="0" y="113" style="font:400 11px var(--font-mono);fill:var(--ink-faint)">y=9.5</text>
    </svg>
    <div class="t-caption" style="margin-top:8px">Inline SVG, <span class="ident" style="font-size:12px">currentColor</span>, <span class="ident" style="font-size:12px">aria-hidden</span>, <span class="ident" style="font-size:12px">vector-effect: non-scaling-stroke</span>. The path carries its own horizontal segments so the rule reads as continuous through the arch.</div>
  </div></div>
  <div class="rec" style="flex:1"><div class="rec-h"><span class="t-label">The one accessory removed</span></div><div class="rec-b t-caption">
    A fore-edge texture on the spine — a stack of ruled ticks suggesting the edge of a bound block — was drawn and cut. It was a <b>second</b> ornament, and there is one. The command palette went the same way: it fails without JavaScript, and it lets navigation stay bad by giving power users a private escape hatch. Eight ruled nav groups plus a plain <span class="ident" style="font-size:12px">/admin/index</span> “All sections” page carry the same load and work with JavaScript off.
  </div></div>
</div>` }));

/* ── Component shelf ────────────────────────────────────────── */
const cell = (label, body, note = '') => `
<div style="border-bottom:1px solid var(--rule);padding:16px 0">
  <div class="t-label" style="margin-bottom:9px">${label}</div>
  ${body}
  ${note ? `<div class="t-caption" style="margin-top:8px;max-width:52ch">${note}</div>` : ''}
</div>`;

w('Shelf.dc.html', artboard({
  w: 1120, pad: true, body: `
${gateRule('', 'Component shelf')}
<p class="t-caption" style="max-width:66ch;margin-bottom:6px">The shelf named in the brief, plus seven components this design adds. Each addition is named with the reason it earns its place.</p>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:0 40px;margin-top:10px">
<div>
${cell('ui/button', `<div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
  <button class="btn p">Save decisions</button>
  <button class="btn">Cancel</button>
  <button class="btn d">Withdraw advertisement</button>
  <button class="btn off" disabled>Generate</button>
  <button class="btn p focus">Publish</button>
</div>`, 'Destructive is <b>outlined, never filled</b> — a filled red button beside a filled green one is how a tired officer rejects the wrong candidate. Focus is a 2px <span class="ident" style="font-size:12px">--green</span> ring at 2px offset, and <span class="ident" style="font-size:12px">outline:none</span> appears nowhere.')}

${cell('ui/badge — glyph and word, never colour alone', `<div style="display:flex;gap:18px;flex-wrap:wrap">
  ${badge('el', 'Eligible')} ${badge('re', 'Not eligible')} ${badge('pe', 'Pending')} ${badge('in', 'Submitted')}
</div>
<div style="margin-top:10px;filter:grayscale(1);display:flex;gap:18px;flex-wrap:wrap">
  ${badge('el', 'Eligible')} ${badge('re', 'Not eligible')} ${badge('pe', 'Pending')} ${badge('in', 'Submitted')}
</div>`, 'The second row is the first in greyscale. Nothing is lost, because the glyph and the word carry the state and the hue only reinforces it.')}

${cell('ui/field + ui/statutory-field', `
<div class="field" style="max-width:380px"><label for="x1">Date of birth</label>
  <input class="inp" id="x1" value="26-11-1984" aria-describedby="x1h x1c">
  <div class="help" id="x1h">As printed on your matriculation certificate.</div>
  <span class="cite" id="x1c">Age measured against 07-03-2026 · CRR Rule 14</span></div>
<div class="field" style="max-width:380px"><label for="x2">Percentage at Master’s</label>
  <input class="inp bad" id="x2" value="—" aria-describedby="x2h x2e">
  <div class="help" id="x2h">Enter the aggregate percentage, not the CGPA.</div>
  <div class="err" id="x2e"><b>✕</b> Enter a percentage between 0 and 100.</div></div>`,
  'Error sits below the help text, carries a glyph, and both are linked by <span class="ident" style="font-size:12px">aria-describedby</span>. A statutory field carries its clause inline — not in a tooltip.')}

${cell('ui/citation — added', `<div style="max-width:420px">Minimum research score <b class="num">75</b>${cite('UGC 2018 cl. 4.1 II')}</div>`,
  '<b>Why it earns its place:</b> it is placed hundreds of times outside a field — score lines, dashboard figures, the frozen-ruleset stamp, report footers. Three rules: below the figure and never inline after it; one per line of reasoning; and it links to the <b>frozen</b> version, never the current one.')}

${cell('ui/empty — never an illustration', `<div class="empty" style="max-width:460px">
  <div class="t-sub">No applications match these filters.</div>
  <p class="t-caption" style="margin:5px 0 12px">Category <b>OBC-NCL</b> and scrutiny <b>✓ Eligible</b> return nothing for this post.</p>
  <a href="#" class="btn sm">Clear filters</a></div>`)}

${cell('ui/skeleton — true row height, no shimmer', `<div class="rec" style="max-width:460px"><table class="tbl"><tbody>
${[68, 150, 92].map((x) => `<tr><td style="width:92px"><i class="sk" style="width:${x >= 92 ? 74 : 66}px"></i></td><td><i class="sk" style="width:${x}px"></i></td><td style="width:96px"><i class="sk" style="width:62px"></i></td></tr>`).join('')}
</tbody></table></div>`, 'Static <span class="ident" style="font-size:12px">--paper-sunk</span> blocks at the real 32px row height. Skeletons do not shimmer: §14 permits no animation here, and a shimmer is motion pretending to be feedback.')}
</div>

<div>
${cell('data/composite-count — the key lives in the header, not in the hue', `<table class="tbl" style="max-width:420px"><thead><tr>
  <th style="width:118px">Post</th><th style="width:150px">TOT / SUB / PAID / INT</th><th>Status</th></tr></thead>
<tbody><tr><td class="ident">2599</td><td>${comp('106', '63', '58', '13')}</td><td>${badge('in', 'Open')}</td></tr>
<tr><td class="ident">2601</td><td>${comp('954', '765', '710', '31')}</td><td>${badge('in', 'Open')}</td></tr></tbody></table>`,
  'The legacy cell distinguished its four figures by colour alone. The order is documented in the column header instead, and the cell carries <span class="ident" style="font-size:12px">aria-label="106 total, 63 submitted, 58 paid, 13 internal"</span>.')}

${cell('data/proportional-bar — added', `
<div style="max-width:440px">
  <div class="pbar" style="margin-bottom:10px"><span class="rcv" style="width:66.6%"></span><span class="gap"></span><span class="awa" style="width:6.4%"></span><span class="gap"></span><span class="fal" style="width:27%"></span></div>
  <div style="display:flex;justify-content:space-between" class="t-caption"><span>✓ Received 66.6%</span><span>◦ Awaited 6.4%</span><span>✕ Failed 27.0%</span></div>
  <div class="pbar thin" style="margin-top:14px"><span class="rcv" style="width:60.7%"></span></div>
  <div class="t-caption" style="margin-top:4px">Paid <span class="num">48,381 / 79,659</span> · <b>60.7%</b></div>
</div>`, '<b>Why it earns its place:</b> the financial strip, the three goal bars and the SLA days-remaining meter are one primitive in two configurations — segmented and single. Five uses across two screens, and the percentage is always a figure as well as a length.')}

${cell('ui/countdown — added', `<div class="banner" style="max-width:440px">
  <h3>Action needed — <span class="cd">5 days remaining</span></h3>
  <p class="t-body" style="font-size:14px">Your experience certificate is illegible. Re-upload it in <b>Employment history</b>.</p>
  <div class="t-caption" style="margin-top:6px">Closes 19 Mar 2026, 5:00 pm</div></div>`,
  '<b>Why it earns its place:</b> server-rendered <span class="ident" style="font-size:12px">&lt;time datetime&gt;</span> that Alpine upgrades to a live tick. A legal deadline is not something to reimplement per screen, and it must be right with JavaScript off.')}

${cell('layout/actor-bar — added', `<div class="actorbar" style="max-width:520px"><span><b>IMPERSONATING</b> · signed in as AISHA KHAN by n.sarwar · since 14:02</span><a href="#">End now</a></div>
<div style="max-width:520px;margin-top:14px"><div class="scoperule"></div><div class="t-caption">Faculty of Arts and 3 departments — local recruitment.</div></div>
<div style="margin-top:14px"><span class="ro">Save decisions · not available in audit access</span></div>`,
  '<b>Why it earns its place:</b> scoped, redacted, read-only, impersonated and out-of-window are one component with five renderings, so a scoped view can never converge with an unscoped one by drift. Impersonation is the only inverted band in the product; inversion carries the signal, so the palette stays closed.')}

${cell('layout/margin — added', `<div class="withmargin" style="max-width:460px;background:var(--paper)">
  <div class="margin">${['ugc-nt-2026@1', 'frozen 22-01-2026', 'snapshot #2', 'audit seq 4,182', '-', 'age measured', '07-03-2026', 'CRR Rule 14'].map((l) => (l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`)).join('')}</div>
  <div class="body"><div class="rec" style="height:100%"><div class="rec-b"><div class="t-sub">The record</div><p class="t-caption">The margin has no ground and no left border. It is the page showing through beside the ruled block, with one rule on its right — which is the record’s left edge.</p></div></div></div>
</div>`, '<b>Why it earns its place:</b> it appears on every record screen; without it the ruleset and the snapshot go back into a modal. Marginalia are right-aligned so they hang on the block, and on a table they align row by row to what they annotate.')}
</div>
</div>` }));

console.log('foundations written');
