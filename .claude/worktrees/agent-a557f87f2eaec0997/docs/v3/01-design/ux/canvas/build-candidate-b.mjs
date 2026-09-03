import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge } from './shared.mjs';
import { CCSS, CHEAD, rail } from './build-candidate-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

const mg = (lines) => `<div class="margin">${lines.map((l) => l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`).join('')}</div>`;

/* ── the deficiency banner — the highest-priority object in the product ── */
export const DEFICIENCY = `<div class="banner">
  <div style="display:flex;justify-content:space-between;gap:20px;align-items:flex-start">
    <div>
      <h3>Action needed — <span class="cd">5 days remaining</span></h3>
      <p class="t-body" style="max-width:64ch">Your experience certificate is illegible. Re-upload it in <b>Employment history</b>.</p>
      <p class="t-caption" style="margin-top:7px">Application <span class="ident" style="font-size:12px">2599/2026/00412</span> · System Manager · raised 12 Mar 2026 by the scrutiny office · <b>closes 19 Mar 2026, 5:00 pm</b></p>
    </div>
    <a class="btn p" href="#" style="flex:0 0 auto">Re-upload the certificate</a>
  </div>
</div>`;

/* ── the score breakdown ────────────────────────────────────── */
const line = (t, fig, ref) => `<tr class="comfy"><td>${t}</td><td class="r num" style="width:86px"><b>${fig}</b></td>
  <td style="width:250px"><span class="ident faint" style="font-size:12px">${ref}</span></td></tr>`;

export const SCORE = `<div style="border-top:1px solid var(--rule-strong);margin-top:16px;padding-top:14px">
  <div class="t-label" style="margin-bottom:8px">Your score — every line carries the rule it comes from</div>
  <table class="tbl" style="background:transparent"><caption>Provisional academic score for application 884/2026/01109, computed under ugc-teaching-2018@1</caption><tbody>
  ${line('Ph.D. awarded', '20', 'App. II Table 2 row 1')}
  ${line('Research papers, Column II — 5 sole-authored × 10', '50', 'App. II Table 2 row 1')}
  ${line('Book, national publisher', '10', 'row 2(a)')}
  ${line('Conference papers — 5 × 2', '10', 'row 3(b)')}
  ${line('Project completed, Co-PI, ₹8 lakh — 5 × 0.50', '2.5', 'row 4(b) · PI/Co-PI 50% each')}
  <tr><td style="border-top:1px solid var(--rule-strong)"><b>Provisional total</b></td>
      <td class="r num" style="border-top:1px solid var(--rule-strong)"><b style="font-size:16px">92.5</b></td>
      <td style="border-top:1px solid var(--rule-strong)"><span class="t-caption">threshold 75</span> <span class="ident faint" style="font-size:12px">cl. 4.1 II</span></td></tr>
  </tbody></table>
  <div class="notice" style="margin-top:12px">
    <b>Impact-factor scoring is not applied.</b> It awaits Executive Council ratification of two points of interpretation. <b>Your claims are recorded in full</b> and will be scored once the interpretation is ratified. No partial figure is shown for it, because a partial figure would be a number you could not rely on.
  </div>
</div>`;

/* ── C11 · Applicant dashboard ★★ ───────────────────────────── */
const appRecord = ({ no, title, ou, adv, type, meta, railItems, next, extra = '', gates }) => `
<div class="crec">
  <div class="hd" style="display:flex;justify-content:space-between;gap:18px;align-items:flex-start">
    <div><a href="#" class="t-section" style="font-size:18px">${title}</a>
      <div class="t-caption" style="margin-top:3px">${ou}</div>
      <div style="margin-top:8px;display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <span class="ident">${no}</span><span class="chip">${adv}</span><span class="chip">${type}</span></div></div>
    <div style="text-align:right;flex:0 0 auto"><div class="t-label">Next</div>
      <div style="font-size:14px;max-width:200px">${next}</div></div>
  </div>
  <div class="bd" style="display:flex;gap:26px;align-items:flex-start">
    <div style="flex:1 1 auto;min-width:0">
      <div class="dl" style="grid-template-columns:150px 1fr;font-size:14px">${meta}</div>
      <div style="margin-top:14px;display:flex;gap:22px;flex-wrap:wrap">${gates}</div>
      ${extra}
    </div>
    <div style="flex:0 0 236px;border-left:1px solid var(--rule);padding-left:20px">
      <div class="t-label" style="margin-bottom:10px">Stage</div>
      ${rail(railItems)}
    </div>
  </div>
</div>`;

const gate = (name, state, word, note = '') => `<div><div class="t-label" style="margin-bottom:3px">${name}</div>
  ${badge(state, word)}${note ? `<div class="t-caption">${note}</div>` : ''}</div>`;

w('CandDashboard.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div class="cpage">
  <h1 class="t-page">Your applications</h1>
  <div class="t-caption" style="margin-top:4px">Aisha Khan · 2 applications · <b style="color:var(--brass)">1 needs you now</b> · figures as at 28 Aug 2026, 04:14</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    ${mg(['ugc-nt-2026@1', 'ugc-teaching', '-2018@1', '-', 'snapshots', '#2 · #1', '-', 'audit seq', '4,182'])}
    <div class="body" style="display:flex;gap:32px;align-items:flex-start">
      <div style="flex:1 1 auto;min-width:0;max-width:760px">

        ${gateRule('', 'Needs you now')}
        ${DEFICIENCY}
        <div class="crec" style="margin-top:12px"><div class="bd" style="display:flex;justify-content:space-between;gap:20px;align-items:center">
          <div><b class="t-sub">Post your printed application</b>
            <p class="t-caption" style="margin-top:3px">884/2026/01109 · the signed print with enclosures must reach the Office of the Registrar by <b>21 Mar 2026</b>.</p></div>
          <a class="btn" href="#">Print the form</a>
        </div></div>

        <div style="height:26px"></div>
        ${gateRule('', 'Your applications')}

        ${appRecord({
    no: '2599/2026/00412', title: 'System Manager', ou: 'Prof. M.N. Farooqui Computer Centre',
    adv: 'Advertisement 2/2026/NT', type: 'General (Non-Teaching)',
    meta: `<dt>Submitted</dt><dd class="num">23-01-2026, 21:24</dd>
           <dt>Fee</dt><dd>${badge('el', 'Paid')} <span class="num">₹500</span> · 23-01-2026 · <span class="ident" style="font-size:12px">rzp_QK4t81nHc</span></dd>
           <dt>Selection method</dt><dd>Written test, then interview</dd>`,
    gates: gate('Scrutiny', 'pe', 'Pending', 'deficiency raised 12 Mar')
      + gate('Written test', 'pe', 'Pending')
      + gate('Interview', 'pe', 'Pending'),
    next: 'Re-upload your experience certificate by <b>19 Mar</b>',
    railItems: [['done', 'Registered', '21 Jan'], ['done', 'Submitted', '23 Jan'], ['done', 'Paid', '23 Jan'],
    ['cur', 'Under scrutiny', '11 Mar'], ['fut', 'Screened', ''], ['fut', 'Written test', ''], ['fut', 'Interview', ''], ['fut', 'Result', '']],
    extra: `<div class="notice" style="margin-top:14px;border-left-color:var(--brass);background:var(--brass-wash)">
        <b>Employment history is open until 19 Mar 2026, 5:00 pm.</b> The other ten sections stay locked — a deficiency window re-opens only what the scrutiny office named, and everything you submitted on 23 Jan is preserved exactly as it was. <a href="#">Open Employment history</a></div>`,
  })}

        ${appRecord({
    no: '884/2026/01109', title: 'Assistant Professor — Computer Science', ou: 'Department of Computer Science, Faculty of Science',
    adv: 'Advertisement 8/2025/T', type: 'Teaching',
    meta: `<dt>Submitted</dt><dd class="num">04-12-2025, 16:02</dd>
           <dt>Fee</dt><dd>${badge('el', 'Paid')} <span class="num">₹500</span> · 04-12-2025 · <span class="ident" style="font-size:12px">rzp_PW9a02mZt</span></dd>
           <dt>Selection method</dt><dd>Interview<div class="t-caption">This post is decided by interview. There is no written test, so no written-test decision exists for it.</div></dd>`,
    gates: gate('Scrutiny', 'el', 'Eligible', 'cleared 26-02-2026')
      + gate('Interview', 'pe', 'Pending', 'letters not yet issued'),
    next: 'Nothing right now. Watch for the interview letter.',
    railItems: [['done', 'Registered', '02 Dec'], ['done', 'Submitted', '04 Dec'], ['done', 'Paid', '04 Dec'],
    ['done', 'Under scrutiny', '19 Feb'], ['cur', 'Screened', '26 Feb'], ['fut', 'Interview', ''], ['fut', 'Result', '']],
    extra: SCORE,
  })}

        <p class="t-caption" style="max-width:70ch;margin-top:8px">You are shown your own score and your own lines. The cut-off, the size of the shortlist and your position in it are not disclosed to any candidate at this stage, and are not withheld from you in particular.</p>
      </div>

      <aside style="flex:0 0 300px">
        <div class="crec"><div class="hd"><span class="t-label">Your profile</span></div><div class="bd">
          <div style="display:flex;gap:18px;align-items:flex-end">
            <div class="meter" aria-hidden="true">
              ${['f', 'f', 'f', 'f', 'f', 'p', '', '', '', '', ''].map((c) => `<i class="${c}"></i>`).join('')}
            </div>
            <div><div class="t-figure num">64<span style="font-size:18px">%</span></div>
              <div class="t-caption">7 of 11 sections<br>complete</div></div>
          </div>
          <table class="tbl" style="margin-top:16px;background:transparent"><caption>Part A, in order</caption><tbody>
            ${[['A1', 'Personal details', 'el', 'Complete'], ['A2', 'Photographs & signature', 'el', 'Complete'],
    ['A3', 'Addresses', 'el', 'Complete'], ['A4', 'Institutions attended', 'el', 'Complete'],
    ['A5', 'Academic qualifications', 'el', 'Complete'], ['A6', 'Employment history', 'pe', '4 of 9'],
    ['A7', 'Research summary', 'el', 'Complete'], ['A8', 'Referees', 'el', 'Complete'],
    ['A9', 'Testimonials', 'pe', 'Not started'], ['A10', 'Declarations', 'pe', 'Not started'],
    ['A11', 'Other information', 'pe', 'Not started']].map(([n, t, s, word]) => `
            <tr><td style="width:34px"><span class="ident faint" style="font-size:12px">${n}</span></td>
            <td><a href="#">${t}</a></td><td class="r" style="width:96px">${badge(s, word)}</td></tr>`).join('')}
          </tbody></table>
          <p class="t-caption" style="margin-top:12px">Your profile is entered once and reused by every application you make. Nothing here is asked for twice.<span class="cite">WCAG 2.2 · 3.3.7 Redundant entry</span></p>
        </div></div>

        <div class="crec"><div class="hd"><span class="t-label">Open now</span></div><div class="bd">
          <p class="t-caption" style="margin-bottom:10px">28 posts are open. Four close within seven days.</p>
          <a class="btn" href="#" style="width:100%">Browse vacancies</a>
        </div></div>
      </aside>
    </div>
  </div>
</div>` }));

/* ── C12 · Application detail + timeline ────────────────────── */
w('AppDetail.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div class="cpage">
  <h1 class="t-page">System Manager, Prof. M.N. Farooqui Computer Centre</h1>
  <div class="t-caption" style="margin-top:4px"><span class="ident">2599/2026/00412</span> · Advertisement 2/2026/NT dated 22.01.2026 · General (Non-Teaching) · Pay Level-12</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    ${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'snapshot #2', 'written', '14-03-2026', '-', 'audit seq', '4,182', '-', 'measured', '07-03-2026'])}
    <div class="body" style="display:flex;gap:30px;align-items:flex-start">
      <div style="flex:1 1 auto;min-width:0;max-width:700px">
        ${gateRule('', 'Timeline')}
        <div class="crec"><div class="bd">
          <ul class="rail" style="font-size:14px">
            ${[['done', '<b>Registered</b> — account created', '21-01-2026 19:40'],
    ['done', '<b>Submitted</b> — snapshot #1 written, dossier locked', '23-01-2026 21:24'],
    ['done', '<b>Paid</b> — ₹500, Razorpay, order rzp_QK4t81nHc', '23-01-2026 21:31'],
    ['done', '<b>Under scrutiny</b> — assigned to the scrutiny office', '11-03-2026 10:02'],
    ['cur', '<b>Deficiency raised</b> — experience certificate illegible, Employment history re-opened until 19-03-2026 17:00', '12-03-2026 15:18'],
    ['fut', 'Rectified — snapshot #2 written, state returns to under scrutiny', 'awaiting you'],
    ['fut', 'Screened', ''], ['fut', 'Written test', ''], ['fut', 'Interview', ''], ['fut', 'Result', '']]
      .map(([st, label, when]) => `<li class="${st}"><span class="tick"><span class="dot">${st === 'done' ? '✓' : st === 'cur' ? '▌' : '◦'}</span><span class="seg"></span></span>
              <span class="lbl">${label}</span><span class="when">${when}</span></li>`).join('')}
          </ul>
          <p class="t-caption" style="margin-top:10px;border-top:1px solid var(--rule);padding-top:10px">Every entry is written to an append-only chain. Nothing on this timeline can be edited or removed, by you or by the University.</p>
        </div></div>

        <div style="height:22px"></div>
        ${gateRule('', 'Decisions')}
        <div class="crec"><div class="bd">
          <table class="tbl" style="background:transparent"><caption>Only the gates that apply to this post type are shown</caption>
          <thead><tr><th>GATE</th><th style="width:150px">DECISION</th><th style="width:130px">DECIDED</th><th>REMARK</th></tr></thead><tbody>
          <tr class="comfy"><td>Scrutiny</td><td>${badge('pe', 'Pending')}</td><td class="num">—</td><td class="t-caption">A deficiency was raised on 12 Mar. The decision is taken after you rectify.</td></tr>
          <tr class="comfy"><td>Written test</td><td>${badge('pe', 'Pending')}</td><td class="num">—</td><td class="t-caption">—</td></tr>
          <tr class="comfy"><td>Interview</td><td>${badge('pe', 'Pending')}</td><td class="num">—</td><td class="t-caption">—</td></tr>
          </tbody></table>
        </div></div>
      </div>

      <aside style="flex:0 0 320px">
        ${DEFICIENCY}
        <div class="crec" style="margin-top:14px"><div class="hd"><span class="t-label">Your submitted form</span></div><div class="bd">
          <p class="t-caption" style="margin-bottom:10px">This is snapshot #1, exactly as you submitted it on 23-01-2026. It is what the scrutiny office reads.</p>
          <a class="btn" href="#" style="width:100%;margin-bottom:8px">View the form</a>
          <a class="btn" href="#" style="width:100%">Download the print copy (PDF)</a>
        </div></div>
        <div class="crec"><div class="hd"><span class="t-label">Documents</span></div><div class="bd">
          <ul class="rows" style="margin:-14px -18px">
            ${[['Photograph', 'el', 'Accepted'], ['Signature', 'el', 'Accepted'], ['Thumb impression', 'el', 'Accepted'],
    ['Matriculation certificate', 'el', 'Accepted'], ['M.C.A. degree', 'el', 'Accepted'],
    ['Experience certificate', 're', 'Illegible']].map(([n, s, word]) => `
            <li style="display:flex;justify-content:space-between;gap:10px;font-size:14px">${n} ${badge(s, word)}</li>`).join('')}
          </ul>
        </div></div>
      </aside>
    </div>
  </div>
</div>` }));

/* ── C6 · Wizard, Part A ★★ ─────────────────────────────────── */
const SPINE = (current = 'A3', locked = false) => {
  const secs = [['A1', 'Personal details', 'done', '✓'], ['A2', 'Photographs & signature', 'done', '✓'],
  ['A3', 'Addresses', 'done', '✓'], ['A4', 'Institutions attended', 'done', '✓'],
  ['A5', 'Academic qualifications', 'done', '✓'], ['A6', 'Employment history', 'part', '4 of 9'],
  ['A7', 'Research summary', 'done', '✓'], ['A8', 'Referees', 'done', '✓'],
  ['A9', 'Testimonials', '', '◦'], ['A10', 'Declarations', 'err', '✕ 2 errors'], ['A11', 'Other information', '', '◦']];
  return `<nav class="spine" aria-label="Application sections">
  ${locked ? `<div class="spine-note"><b>Rectification window</b><br>Employment history only. Closes 19 Mar 2026, 5:00 pm.<div class="cd" style="margin-top:4px">5 days remaining</div></div>` : ''}
  <h2>Part A · 11 sections</h2>
  <ol>${secs.map(([n, t, st, mark]) => {
    const isCur = n === current;
    const lockedRow = locked && n !== 'A6';
    return `<li class="${isCur ? 'on ' : ''}${st === 'done' ? 'done' : st === 'err' ? 'err' : ''}">
      <a href="#"><span class="n">${n}</span><span>${t}</span>
      <span class="st">${lockedRow ? '▪ locked' : st === 'part' ? mark : mark}</span></a></li>`;
  }).join('')}</ol>
  <h2>Part B1 · Research claims</h2>
  <ol><li><a href="#"><span class="n">—</span><span>Journal articles, books, projects</span><span class="st">12 of 16</span></a></li></ol>
  <h2>Part C · Declarations</h2>
  <ol><li><a href="#"><span class="n">—</span><span>Undertakings and consent</span><span class="st">◦</span></a></li></ol>
  <div style="padding:0 18px;margin-top:14px" class="t-caption">Every section is reachable. Nothing unlocks in order — that was the old form’s worst habit — and submission validates the whole.</div>
</nav>`;
};

w('WizardA.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Profile')}
<div style="display:flex;align-items:stretch;min-height:1120px">
  ${SPINE('A3')}
  ${mg(['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'draft', 'not submitted', '-', 'autosaved', '14:32', '-', 'measured', '07-03-2026'])}
  <div style="flex:1 1 auto;padding:26px 40px 40px;min-width:0">
    <div style="max-width:760px">
      <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:16px">
        <div><h1 class="t-page" style="font-size:26px">Your profile</h1>
          <div class="t-caption">Entered once, reused by every application you make.</div></div>
        <div style="text-align:right"><span class="b el"><span class="g">✓</span> Saved 14:32</span>
          <div class="t-caption">Every change is saved as you type.</div></div>
      </div>

      <div class="crec" style="border-color:var(--rejected);border-left:4px solid var(--rejected);margin-bottom:20px"><div class="bd">
        <b class="t-sub">Two things need your attention before you can submit</b>
        <ol style="margin:8px 0 0;padding-left:18px;list-style:decimal;font-size:14px">
          <li><a href="#">A10 Declarations — answer the question about criminal proceedings</a></li>
          <li><a href="#">A10 Declarations — confirm you have read the undertaking</a></li>
        </ol>
        <p class="t-caption" style="margin-top:8px">You can keep working in any section. This list stays until both are resolved.</p>
      </div></div>

      ${gateRule('A3', 'Addresses')}

      <div class="field"><label for="c1">Correspondence address</label>
        <input class="inp" id="c1" value="506, 5th Floor, IT Palm Court Apartments" aria-describedby="c1h">
        <div class="help" id="c1h">House or flat number, building, street.</div></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="field"><label for="c2">Locality</label><input class="inp" id="c2" value="Dodhpur Road, opp. Noor Manzil, Civil Lines"></div>
        <div class="field"><label for="c3">City or town</label><input class="inp" id="c3" value="Aligarh"></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px">
        <div class="field"><label for="c4">District</label><input class="inp" id="c4" value="Aligarh"></div>
        <div class="field"><label for="c5">State</label><select class="inp" id="c5"><option>Uttar Pradesh</option></select></div>
        <div class="field"><label for="c6">PIN</label><input class="inp" id="c6" value="202001" inputmode="numeric"></div>
      </div>

      <label style="display:flex;gap:10px;align-items:flex-start;font-size:15px;margin:4px 0 22px;padding:12px 14px;background:var(--paper-sunk);border:1px solid var(--rule);border-radius:2px">
        <input type="checkbox" checked style="width:20px;height:20px;margin-top:1px;accent-color:var(--green)">
        <span>My permanent address is the same as my correspondence address.
        <span class="t-caption" style="display:block">Untick this to enter a different permanent address. Both are printed on the statutory form.</span></span></label>

      <div class="field"><label for="d1">Domicile district</label>
        <input class="inp" id="d1" value="Aligarh" aria-describedby="d1h d1c">
        <div class="help" id="d1h">The district named on your domicile certificate.</div>
        <span class="cite" id="d1c">Domicile governs the local-candidate concession under CRR Rule 9.2 and must match the certificate you upload in A2.</span></div>

      <div class="field"><label for="d2">Domicile state</label>
        <select class="inp bad" id="d2" aria-describedby="d2h d2e"><option>Select a state</option></select>
        <div class="help" id="d2h">Select the state that issued the certificate.</div>
        <div class="err" id="d2e"><b>✕</b> Choose the state that issued your domicile certificate.</div></div>

      <div style="display:flex;gap:12px;align-items:center;border-top:1px solid var(--rule-strong);padding-top:18px;margin-top:8px">
        <button class="btn p">Save and continue to A4</button>
        <button class="btn">Save and close</button>
        <span class="t-caption" style="margin-left:auto">Section A3 of 11 · nothing is submitted until you review the whole form</span>
      </div>
    </div>
  </div>
</div>` }));

/* ── C7 · Research claims ───────────────────────────────────── */
w('Research.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Profile')}
<div style="display:flex;align-items:stretch;min-height:1020px">
  ${SPINE('')}
  ${mg(['ugc-teaching', '-2018@1', '-', 'CrossRef', 'checked 04:09', '-', 'UGC-CARE', 'list I · 2026'])}
  <div style="flex:1 1 auto;padding:26px 40px 40px;min-width:0">
    <div style="max-width:860px">
      <h1 class="t-page" style="font-size:26px">Research claims</h1>
      <div class="t-caption" style="margin-bottom:18px">Part B1 · 16 sub-forms · 12 recorded. Paste a DOI and we fill the record for you.</div>
      ${gateRule('', 'Journal articles')}

      <div class="crec"><div class="bd">
        <div style="display:flex;gap:12px;align-items:flex-end;max-width:640px">
          <div class="field" style="flex:1;margin:0"><label for="doi">Paste a DOI</label>
            <input class="inp" id="doi" value="10.1016/j.compedu.2023.104812" aria-describedby="doih">
            <div class="help" id="doih">Or an ISBN for a book. We look it up in CrossRef and check the journal against the UGC-CARE list.</div></div>
          <button class="btn p" style="height:44px">Look it up</button>
        </div>
      </div></div>

      <div class="crec" style="border-left:4px solid var(--eligible)"><div class="bd">
        <div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start">
          <div><span class="t-label">Resolved from CrossRef</span>
            <p class="t-sub" style="margin-top:6px">Adaptive assessment in large engineering cohorts: a controlled study</p>
            <div class="t-caption">Khan, A. · <i>Computers &amp; Education</i>, vol. 204, 2023, pp. 104812 · Elsevier</div>
            <div style="margin-top:9px;display:flex;gap:9px;flex-wrap:wrap">
              <span class="chip">Sole author</span><span class="chip">Peer-reviewed</span>
              <span class="chip">ISSN 0360-1315</span><span class="ident faint" style="font-size:12px">10.1016/j.compedu.2023.104812</span></div>
          </div>
          <div style="text-align:right;flex:0 0 auto">
            ${badge('el', 'UGC-CARE listed')}<div class="t-caption">Group I · verified 04:09 today</div>
            <button class="btn p sm" style="margin-top:10px">Add this record</button></div>
        </div>
        <div style="margin-top:12px;border-top:1px solid var(--rule);padding-top:10px">
          <span class="t-caption">Scores as <b>Column II, sole-authored — 10 points</b></span>${cite('App. II Table 2 row 1')}</div>
      </div></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
        <div class="crec" style="border-left:4px solid var(--info)"><div class="bd">
          <span class="t-label">Not resolved</span>
          <p class="t-body" style="font-size:14px;margin-top:6px">CrossRef has no record for <span class="ident">10.9999/nonexistent.2024</span>.</p>
          <p class="t-caption" style="margin-top:6px">Check the DOI, or enter the publication by hand. A hand-entered record is accepted — it is simply marked as unverified, and the scrutiny officer will ask for the offprint.</p>
          <div style="margin-top:10px;display:flex;gap:8px"><button class="btn sm">Enter it by hand</button><button class="btn sm">Try another DOI</button></div>
        </div></div>
        <div class="crec" style="border-left:4px solid var(--pending)"><div class="bd">
          <span class="t-label">Resolved, but the journal is disputed</span>
          <p class="t-body" style="font-size:14px;margin-top:6px"><i>International Journal of Advanced Research Trends</i> was <b>removed from the UGC-CARE list on 30-06-2024</b>.</p>
          <p class="t-caption" style="margin-top:6px">Your paper was published in <b>March 2023</b>, while the journal was listed. Record it: the rule that applies is the list in force on the date of publication, and the scrutiny officer decides.</p>
          <span class="cite" style="margin-top:8px">UGC-CARE protocol cl. 3.4 · listing is assessed at the date of publication</span>
          <div style="margin-top:10px"><button class="btn sm">Record it with this note</button></div>
        </div></div>
      </div>

      <div style="height:20px"></div>
      <div class="crec"><div class="hd"><span class="t-label">Recorded — 12 journal articles</span><span class="t-caption">70 columns across 13 evidence tables. Only what this sub-form needs is shown.</span></div>
        <table class="tbl"><thead><tr><th style="width:34px">#</th><th>TITLE</th><th style="width:150px">JOURNAL</th><th style="width:64px">YEAR</th><th style="width:110px">AUTHORSHIP</th><th style="width:130px">UGC-CARE</th><th style="width:78px">POINTS</th></tr></thead><tbody>
        ${[['1', 'Adaptive assessment in large engineering cohorts', 'Computers & Education', '2023', 'Sole', 'el', 'Listed', '10.0'],
    ['2', 'A federated approach to examination scheduling', 'IEEE Access', '2022', 'First of 3', 'el', 'Listed', '10.0'],
    ['3', 'Detecting duplicate candidate records at scale', 'J. King Saud Univ. — CS', '2021', 'Second of 2', 'el', 'Listed', '5.0'],
    ['4', 'On roll-number allocation under constraints', 'Int. J. Adv. Res. Trends', '2023', 'Sole', 'pe', 'Disputed', '—']]
      .map(([n, t, j, y, a, s, word, p]) => `<tr><td class="ident faint">${n}</td><td>${t}</td><td class="t-caption">${j}</td>
        <td class="num">${y}</td><td>${a}</td><td>${badge(s, word)}</td><td class="r num">${p}</td></tr>`).join('')}
        </tbody></table>
        <div class="pager"><span>Showing 1–4 of 12</span><span class="t-caption">Points are provisional until scrutiny</span></div>
      </div>
    </div>
  </div>
</div>` }));

console.log('candidate B written');
