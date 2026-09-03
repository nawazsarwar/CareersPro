import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge } from './shared.mjs';
import { CCSS, CHEAD, rail } from './build-candidate-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

const mg = (lines) => `<div class="margin">${lines.map((l) => l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`).join('')}</div>`;

/* The spine, in its rectification state — one section open, ten legible and locked. */
const SPINE_LOCKED = () => {
  const secs = [['A1', 'Personal details'], ['A2', 'Photographs &amp; signature'], ['A3', 'Addresses'],
  ['A4', 'Institutions attended'], ['A5', 'Academic qualifications'], ['A6', 'Employment history'],
  ['A7', 'Research summary'], ['A8', 'Referees'], ['A9', 'Testimonials'], ['A10', 'Declarations'],
  ['A11', 'Other information']];
  return `<nav class="spine" aria-label="Application sections">
  <div class="spine-note"><b>Rectification window</b><br>Employment history only.<br>Closes 19 Mar 2026, 5:00 pm.
    <div class="cd" style="margin-top:4px;font:500 13px var(--font-mono);color:var(--brass)">5 days remaining</div></div>
  <h2>Part A · 11 sections</h2>
  <ol>${secs.map(([n, t]) => n === 'A6'
    ? `<li class="on"><a href="#" aria-current="page"><span class="n">${n}</span><span>${t}</span><span class="st" style="color:var(--brass)">▌ open</span></a></li>`
    : `<li><a href="#"><span class="n">${n}</span><span style="color:var(--ink-faint)">${t}</span><span class="st">▪ locked</span></a></li>`).join('')}</ol>
  <h2>Part B1 · Research claims</h2>
  <ol><li><a href="#"><span class="n">—</span><span style="color:var(--ink-faint)">Journal articles, books, projects</span><span class="st">▪ locked</span></a></li></ol>
  <h2>Part C · Declarations</h2>
  <ol><li><a href="#"><span class="n">—</span><span style="color:var(--ink-faint)">Undertakings and consent</span><span class="st">▪ locked</span></a></li></ol>
  <div style="padding:0 18px;margin-top:14px" class="t-caption">A locked section still opens and still reads. It simply has no fields. You submitted it on 23 Jan and it stands exactly as you left it.</div>
</nav>`;
};

/* ── C13 · Deficiency rectification, desktop ────────────────── */
const empRow = (post, org, from, to, doc, state, word, note) => `
<tr class="comfy"><td><b>${post}</b><div class="t-caption">${org}</div></td>
  <td class="num">${from}</td><td class="num">${to}</td>
  <td>${doc}${note ? `<div class="t-caption">${note}</div>` : ''}</td>
  <td>${badge(state, word)}</td></tr>`;

w('Deficiency.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div style="display:flex;align-items:stretch;min-height:1180px">
  ${SPINE_LOCKED()}
  ${mg(['2599/2026', '/00412', '-', 'window', '12-03-2026', 'to 19-03', '-', 'raised by', 'scrutiny.arts', '-', 'snapshot', '#1 sealed', '-', 'ugc-nt-2026@1'])}
  <div style="flex:1 1 auto;padding:26px 36px 40px;min-width:0">
    <div style="display:flex;gap:30px;align-items:flex-start">
      <div style="flex:1 1 auto;min-width:0;max-width:720px">
        <h1 class="t-page" style="font-size:26px">Employment history</h1>
        <div class="t-caption" style="margin-top:4px">Application <span class="ident" style="font-size:12px">2599/2026/00412</span> · System Manager · Advertisement 2/2026/NT</div>
        <div class="mh-rule" style="margin-bottom:18px"></div>

        <div class="banner">
          <h3>Action needed — <span class="cd">5 days remaining</span></h3>
          <p class="t-body" style="max-width:64ch">Your experience certificate is illegible. Re-upload it here. This is the only section that is open.</p>
          <p class="t-caption" style="margin-top:7px">Raised 12 Mar 2026, 11:04 am by the scrutiny office · <b>closes 19 Mar 2026, 5:00 pm</b></p>
        </div>

        <div class="crec" style="margin-top:16px"><div class="bd">
          <div class="t-label" style="margin-bottom:8px">What the scrutiny officer wrote</div>
          <blockquote style="margin:0;padding:12px 16px;border-left:3px solid var(--rule-strong);background:var(--paper-sunk);font:400 15px/1.6 var(--font-ui)">
            “The experience certificate uploaded for the period 01-08-2019 to 31-03-2024 cannot be read — the seal and the signature block are not legible in the scan. A fresh, legible copy of the same certificate is required. No other document is in question.”
          </blockquote>
          <div class="t-caption" style="margin-top:8px">Recorded against <span class="ident" style="font-size:12px">deficiency.raised</span> · audit sequence 4,179 · the officer's note is disclosed to you in full, unedited.
            ${cite('CRR Rule 11.4 — a deficiency must name the document and the defect; a general request to “resubmit” is not a deficiency')}</div>
        </div></div>

        ${gateRule('A6', 'Your employment records')}
        <div class="crec"><div class="bd">
          <table class="tbl"><caption>Two records · only the certificate on the second is in question</caption>
          <thead><tr><th>POST HELD</th><th style="width:104px">FROM</th><th style="width:104px">TO</th><th>CERTIFICATE</th><th style="width:118px">STATE</th></tr></thead>
          <tbody>
            ${empRow('Systems Analyst', 'National Informatics Centre, Aligarh', '12-06-2017', '31-07-2019', 'experience-nic-2019.pdf<div class="t-caption">1.4 MB · uploaded 23-01-2026</div>', 'el', 'Accepted', '')}
            ${empRow('Senior Systems Analyst', 'Uttar Pradesh Electronics Corporation', '01-08-2019', '31-03-2024', 'experience-upleci.pdf<div class="t-caption">220 KB · uploaded 23-01-2026</div>', 're', 'Illegible', 'This is the file to replace')}
          </tbody></table>
          <p class="t-caption" style="margin-top:10px">The dates are not editable. The window reopened the section for the named defect, and changing a period would change the experience computation after the closing date — which the rules do not permit.
            ${cite('CRR Rule 11.4 proviso')}</p>
        </div></div>

        <div class="crec" style="border-color:var(--brass)"><div class="bd">
          <div class="t-label" style="margin-bottom:10px">Replace the certificate</div>
          <div style="display:flex;gap:16px;align-items:flex-start;padding:12px;background:var(--paper-sunk);border:1px solid var(--rule);border-radius:2px">
            <div style="width:58px;height:76px;border:1px solid var(--rule-strong);background:var(--paper-raised);flex:0 0 auto"></div>
            <div style="flex:1 1 auto">
              <b class="t-sub">experience-upleci.pdf</b>
              <div class="t-caption">The copy on record · 220 KB · 1 page · uploaded 23-01-2026, 21:19</div>
              <div style="margin-top:6px">${badge('re', 'Illegible')} <a href="#" style="font-size:13px;margin-left:8px">View what we received</a></div>
            </div>
          </div>
          <div class="field" style="margin:16px 0 8px">
            <label for="f1">Upload a legible copy of the same certificate</label>
            <input class="inp" id="f1" type="file" aria-describedby="f1h" style="padding:9px 12px">
            <div class="help" id="f1h">PDF or JPEG · up to 5 MB · self-attested · scan at 300 dpi or photograph in daylight so the seal and signature are readable.</div>
          </div>
          <p class="t-caption">Uploading here replaces the file on this application only. Your <a href="#">document vault</a> keeps both copies, and the copy the scrutiny office saw is never deleted.</p>
        </div></div>

        <div class="crec"><div class="bd">
          <div class="t-label" style="margin-bottom:8px">What happens when you submit</div>
          <ol style="margin:0;padding-left:20px;font:400 15px/1.7 var(--font-ui)">
            <li>Snapshot <b>#2</b> is written. Snapshot #1 — everything you submitted on 23 Jan — is sealed and kept.</li>
            <li>The application returns to <b>under scrutiny</b> and re-enters the queue at its original position.</li>
            <li>The window closes for you. A section reopens once per deficiency, and this is that once.</li>
          </ol>
          <div style="display:flex;gap:12px;align-items:center;border-top:1px solid var(--rule-strong);padding-top:18px;margin-top:16px">
            <button class="btn p lg" style="width:auto">Submit the replacement</button>
            <button class="btn">Save and come back</button>
            <span class="t-caption" style="margin-left:auto">Saved as a draft until you submit</span>
          </div>
        </div></div>
      </div>

      <aside style="flex:0 0 268px">
        <div class="crec"><div class="hd"><span class="t-label">The clock</span></div><div class="bd">
          <div class="t-figure" style="color:var(--brass)">5 days</div>
          <div class="t-caption" style="margin-top:2px">remaining · closes <b>19 Mar 2026, 5:00 pm</b> IST</div>
          <div class="meter" style="margin-top:14px" aria-hidden="true">
            ${Array.from({ length: 7 }, (_, i) => `<i class="${i < 2 ? 'p' : ''}"></i>`).join('')}
          </div>
          <div class="t-caption" style="margin-top:6px">Day 2 of a 7-day window.</div>
        </div></div>
        <div class="crec"><div class="hd"><span class="t-label">If you miss it</span></div><div class="bd">
          <p class="t-body" style="font-size:14px">The application is decided on the record as it stands — with the certificate ruled illegible. It is not rejected for lateness; it is scrutinised without that document, and the experience it evidences cannot be counted.
            ${cite('CRR Rule 11.5')}</p>
        </div></div>
        <div class="crec"><div class="hd"><span class="t-label">If you cannot obtain it in time</span></div><div class="bd">
          <p class="t-body" style="font-size:14px">Write to the scrutiny office before the window closes. An extension is at the Deputy Registrar's discretion, is recorded on the application, and is granted once.</p>
          <div style="margin-top:10px"><a class="btn sm" href="#">Write to the scrutiny office</a></div>
          <p class="t-caption" style="margin-top:10px">recruitment@amu.ac.in · Office of the Registrar, Room 12 · 10:00–17:00, Monday to Friday</p>
        </div></div>
        <div class="crec"><div class="hd"><span class="t-label">Stage</span></div><div class="bd">
          ${rail([['done', 'Registered', '21 Jan'], ['done', 'Submitted', '23 Jan'], ['done', 'Paid', '23 Jan'],
    ['cur', 'Under scrutiny', '11 Mar'], ['fut', 'Screened', ''], ['fut', 'Written test', ''], ['fut', 'Interview', ''], ['fut', 'Result', '']])}
        </div></div>
      </aside>
    </div>
  </div>
</div>` }));

/* ── C14 · Admit card and interview letter ─────────────────── */
const stateCard = (viewed, title, kind, body, foot) => `
<div class="crec" style="margin:0"><div class="hd" style="display:flex;justify-content:space-between;align-items:baseline;gap:12px">
    <span class="t-label">${title}</span>
    <span class="ident faint" style="font-size:12px">viewed ${viewed}</span></div>
  <div class="bd" style="min-height:238px">${body}</div>
  ${foot ? `<div style="padding:11px 18px;border-top:1px solid var(--rule);background:var(--paper-sunk)"><span class="t-caption">${foot}</span></div>` : ''}
</div>`;

w('AdmitCard.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div class="cpage">
  <h1 class="t-page">Admit cards and interview letters</h1>
  <div class="t-caption" style="margin-top:4px">Aisha Khan · 2 applications · a document appears here only inside the window its post sets</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    ${mg(['post 2599', 'admit window', '18-03-2026', '10:00', 'to 26-03', '17:00', '-', 'template', 'admit-nt-', 'general', '-', 'job #4,217'])}
    <div class="body" style="max-width:1120px">

      ${gateRule('', 'Available to you now')}
      <div class="crec"><div class="bd" style="display:flex;gap:28px;align-items:flex-start">
        <div style="flex:1 1 auto;min-width:0">
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px">
            <b class="t-section" style="font-size:18px">Admit card — written test</b>${badge('el', 'Ready to download')}</div>
          <div class="t-caption" style="margin-bottom:14px">System Manager · post 2599 · application <span class="ident" style="font-size:12px">2599/2026/00412</span> · Advertisement 2/2026/NT</div>
          <div class="dl" style="grid-template-columns:196px 1fr;font-size:15px">
            <dt>Roll number</dt><dd><b class="ident" style="font-size:15px">2599-01142</b></dd>
            <dt>Date of the test</dt><dd class="num">Saturday, 28 March 2026</dd>
            <dt>Reporting time</dt><dd class="num">08:30 · the gate closes at <b>09:15</b></dd>
            <dt>Test begins</dt><dd class="num">09:30 · 2 hours</dd>
            <dt>Centre</dt><dd>Kennedy Auditorium Complex, AMU Aligarh<div class="t-caption">Hall 3 · seat allotment is printed on the card</div></dd>
            <dt>Bring with you</dt><dd>This card, printed · one original photo identity document<div class="t-caption">Nothing else is admitted to the hall. The card is not accepted on a phone screen.</div></dd>
          </div>
          <div style="margin-top:18px;display:flex;gap:10px;align-items:center">
            <a class="btn p lg" href="#" style="width:auto">Download the admit card (PDF, 96 KB)</a>
            <a class="btn" href="#">Instructions to candidates</a>
          </div>
          <p class="t-caption" style="margin-top:12px">Downloads close <b>26 Mar 2026, 5:00 pm</b>. Download it now and keep the file — the window closes two days before the test, and it does not reopen.</p>
        </div>
        <div style="flex:0 0 220px;border-left:1px solid var(--rule);padding-left:20px">
          <div class="t-label" style="margin-bottom:10px">The window</div>
          ${rail([['done', 'Screened', '16 Mar'], ['done', 'Card generated', '18 Mar'], ['cur', 'Downloads open', 'to 26 Mar'], ['fut', 'Written test', '28 Mar'], ['fut', 'Result', '']])}
          <p class="t-caption" style="margin-top:8px">Generated by job <span class="ident" style="font-size:12px">#4,217</span> from template <span class="ident" style="font-size:12px">admit-nt-general</span>. If the card is re-issued, this one is superseded and the new one appears here.</p>
        </div>
      </div></div>

      <div class="crec"><div class="bd" style="display:flex;justify-content:space-between;gap:24px;align-items:center">
        <div>
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap"><b class="t-sub">Interview letter</b>${badge('pe', 'Not yet issued')}</div>
          <div class="t-caption" style="margin-top:4px">Assistant Professor — Computer Science · application <span class="ident" style="font-size:12px">884/2026/01109</span> · cleared scrutiny 26-02-2026</div>
          <p class="t-body" style="font-size:14px;margin-top:8px;max-width:74ch">Interview letters for this post have not been issued. When they are, this row becomes a download and you are emailed at <b>aisha.khan@example.com</b>. There is nothing to do in the meantime, and no date has been fixed.</p>
        </div>
        <span class="btn off" style="flex:0 0 auto;cursor:not-allowed">Download</span>
      </div></div>

      <div style="height:26px"></div>
      ${gateRule('', 'The same row, at three moments')}
      <p class="t-caption" style="margin:-6px 0 14px;max-width:80ch">The window is a property of the post, not a state of the page. Before it, during it and after it, the row occupies the same space and states the same dates — so a candidate who returns on the wrong day reads a fact, not an absence.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px">
        ${stateCard('14 Mar 2026', 'Not yet open', 'closed', `
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px"><b class="t-sub">Admit card — written test</b>${badge('pe', 'Opens 18 Mar')}</div>
          <p class="t-body" style="font-size:14px">Downloads open <b>18 March 2026, 10:00 am</b> and close <b>26 March 2026, 5:00 pm</b>.</p>
          <div class="t-figure" style="color:var(--brass);margin-top:12px">4 days</div>
          <div class="t-caption">until downloads open · the test is on 28 March 2026</div>
          <div style="margin-top:14px"><span class="btn off" style="cursor:not-allowed">Download the admit card</span></div>`,
    'You are not late and nothing is missing. The card does not exist yet — it is generated for every screened candidate on 18 March.')}

        ${stateCard('20 Mar 2026', 'Open — the working state', 'open', `
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px"><b class="t-sub">Admit card — written test</b>${badge('el', 'Ready')}</div>
          <p class="t-body" style="font-size:14px">Roll number <b class="ident" style="font-size:14px">2599-01142</b> · 28 March 2026, report 08:30 · Kennedy Auditorium Complex, Hall 3.</p>
          <div class="t-figure" style="margin-top:12px">6 days</div>
          <div class="t-caption">left to download · closes 26 March 2026, 5:00 pm</div>
          <div style="margin-top:14px"><a class="btn p" href="#">Download (PDF, 96 KB)</a></div>`,
    'Downloaded twice, at 20-03 11:02 and 21-03 08:40. Every download is recorded against your application.')}

        ${stateCard('27 Mar 2026', 'Window closed', 'shut', `
          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-bottom:10px"><b class="t-sub">Admit card — written test</b>${badge('re', 'Downloads closed')}</div>
          <p class="t-body" style="font-size:14px">Downloads closed <b>26 March 2026, 5:00 pm</b>. The test is tomorrow, <b>28 March 2026</b>, and your roll number is <b class="ident" style="font-size:14px">2599-01142</b>.</p>
          <p class="t-body" style="font-size:14px;margin-top:8px"><b>If you have no printed card</b>, go to the Controller of Examinations before 5:00 pm today with a photo identity document. A duplicate is issued at the counter and recorded.</p>
          <div style="margin-top:12px"><a class="btn" href="#">Where to go, and when</a></div>`,
    'Controller of Examinations, Administrative Block, Room 12 · 10:00–17:00 · 0571-270-0920. This is not a grievance — grievances are heard at the screening stage only.')}
      </div>

      <div class="notice" style="margin-top:18px">
        <b>Why the window closes before the test.</b> The centre is issued a sealed attendance register generated from the same list, two days before the test, and a card downloaded after that register is printed would not have a seat against it. The counter exists for exactly that reason.
        <span class="cite" style="margin-top:6px">Controller of Examinations, standing instruction 4/2019 · the attendance register and the admit cards are generated from one list, in one job</span>
      </div>
    </div>
  </div>
</div>` }));

/* ── C15 · Grievance ───────────────────────────────────────── */
const slaRow = (step, due, actual, state, word) => `
<tr class="comfy"><td>${step}</td><td class="num">${due}</td><td class="num">${actual}</td><td>${badge(state, word)}</td></tr>`;

w('Grievance.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Grievance')}
<div class="cpage">
  <h1 class="t-page">Grievance</h1>
  <div class="t-caption" style="margin-top:4px">A grievance is heard against a <b>screening decision</b>, by a named officer, inside a stated time. Nothing here is informal.</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    ${mg(['GRV/2026', '/00114', '-', 'filed', '14-03-2026', '-', 'decided by', 'Dy. Registrar', '(Recruitment)', '-', 'appeal to', 'the Registrar'])}
    <div class="body" style="display:flex;gap:30px;align-items:flex-start">
      <div style="flex:1 1 auto;min-width:0;max-width:740px">

        ${gateRule('', 'Your open grievance')}
        <div class="crec"><div class="hd" style="display:flex;justify-content:space-between;align-items:baseline;gap:14px">
          <div><span class="ident">GRV/2026/00114</span> <span class="chip" style="margin-left:6px">Screening decision</span></div>
          <span>${badge('pe', 'Under consideration')}</span>
        </div><div class="bd">
          <div class="dl" style="grid-template-columns:176px 1fr;font-size:15px">
            <dt>Against</dt><dd>The screening decision on application <span class="ident" style="font-size:14px">2599/2026/00412</span><div class="t-caption">System Manager · post 2599 · decision recorded 11-03-2026</div></dd>
            <dt>Ground</dt><dd>Experience wrongly computed<div class="t-caption">One of six grounds. A ground is chosen, not typed, because the officer must decide under a rule.</div></dd>
            <dt>What you wrote</dt><dd style="font:400 15px/1.6 var(--font-ui)">“My service at UPLECI from 01-08-2019 to 31-03-2024 has not been counted. The certificate was uploaded with the application and is at page 4 of the enclosures. That period is 4 years 8 months and takes my total to 11 years.”</dd>
            <dt>Filed</dt><dd class="num">14-03-2026, 15:22 · <span class="t-caption">within the 7 days allowed from 11-03-2026</span></dd>
            <dt>Decided by</dt><dd><b>The Deputy Registrar (Recruitment)</b>, Office of the Registrar<div class="t-caption">Named under the Grievance Redressal notification of 14-11-2025. Not a committee, not an inbox — one officer, answerable for the decision.</div></dd>
          </div>

          <div style="border-top:1px solid var(--rule-strong);margin-top:16px;padding-top:14px">
            <div class="t-label" style="margin-bottom:8px">The time it is being held to</div>
            <table class="tbl"><caption>Working days, excluding Sundays and gazetted holidays · counted from 14-03-2026</caption>
            <thead><tr><th>STEP</th><th style="width:132px">DUE BY</th><th style="width:132px">DONE</th><th style="width:150px">STANDING</th></tr></thead><tbody>
              ${slaRow('Acknowledged, with the file number', '19-03-2026', '17-03-2026', 'el', 'Met · day 2 of 3')}
              ${slaRow('Papers called from the scrutiny office', '24-03-2026', '23-03-2026', 'el', 'Met · day 6 of 8')}
              ${slaRow('Reasoned decision issued', '04-04-2026', '—', 'pe', '12 days remain')}
            </tbody></table>
            <p class="t-caption" style="margin-top:10px">The standard is <b>3 working days to acknowledge</b> and <b>15 working days to decide</b>.
              ${cite('Grievance Redressal notification, Office of the Registrar, 14-11-2025 · para 6')}</p>
          </div>

          <div class="notice" style="margin-top:14px">
            <b>Filing this does not pause anything.</b> The written test on 28 March 2026 proceeds and you should sit it. If the grievance succeeds after the test, your paper is evaluated; if it fails, it is not. You are not asked to choose between the two.
          </div>
        </div></div>

        <div class="crec"><div class="hd"><span class="t-label">When the time is not kept</span></div><div class="bd">
          <div style="display:flex;justify-content:space-between;gap:20px;align-items:flex-start;padding:12px 14px;border:1px solid var(--rejected);border-left:4px solid var(--rejected);border-radius:2px">
            <div>
              <b class="t-sub" style="color:var(--rejected)">✕ Decision overdue — day 17 of 15</b>
              <p class="t-body" style="font-size:14px;margin-top:4px;max-width:64ch">The reasoned decision was due on 04-04-2026. The delay is recorded against this grievance and appears on the Registrar's weekly breach list. You do not have to ask for it to be counted.</p>
            </div>
            <a class="btn sm" href="#" style="flex:0 0 auto">Write to the Registrar</a>
          </div>
          <p class="t-caption" style="margin-top:10px">Drawn here because it is a state the candidate is entitled to see. A portal that shows only the promise, and never the breach, is not tracking an SLA — it is advertising one.</p>
        </div></div>

        <div class="crec"><div class="hd" style="display:flex;justify-content:space-between;align-items:baseline">
          <div><span class="ident">GRV/2025/00981</span> <span class="chip" style="margin-left:6px">Screening decision</span></div>
          <span>${badge('re', 'Not upheld · 09-01-2026')}</span>
        </div><div class="bd">
          <div class="t-label" style="margin-bottom:6px">The decision, in the officer's words</div>
          <blockquote style="margin:0;padding:12px 16px;border-left:3px solid var(--rule-strong);background:var(--paper-sunk);font:400 15px/1.6 var(--font-ui)">
            “The applicant's M.Sc. was awarded in 2011 with 52.4%. The advertisement requires 55% in the essential qualification and permits no relaxation of that figure for the OBC-NCL category, relaxation being available on age and fee only. The grievance is not upheld.”
          </blockquote>
          <div class="t-caption" style="margin-top:8px">Decided in 11 working days by the Deputy Registrar (Recruitment) ${cite('CRR Sch. II item 14 · DoPT O.M. 15012/2/2010-Estt.(D)')}</div>
          <div style="margin-top:14px;padding:12px 14px;background:var(--paper-sunk);border:1px solid var(--rule);border-radius:2px;display:flex;justify-content:space-between;gap:18px;align-items:center">
            <div><b class="t-sub">Appeal lies to the Registrar, within 30 days of this decision.</b>
              <div class="t-caption" style="margin-top:3px">That window closed on <b>08-02-2026</b>. It is stated because the right existed and you should be able to see that it did.</div></div>
            <span class="btn off" style="flex:0 0 auto;cursor:not-allowed">Appeal</span>
          </div>
        </div></div>
      </div>

      <aside style="flex:0 0 320px">
        <div class="crec"><div class="hd"><span class="t-label">File a grievance</span></div><div class="bd">
          <div class="field"><label for="g1">Which application</label>
            <select class="inp" id="g1" aria-describedby="g1h">
              <option>2599/2026/00412 — System Manager</option>
              <option disabled>884/2026/01109 — window closed 05-03-2026</option>
            </select>
            <div class="help" id="g1h">Only an application inside its 7-day window appears here.</div></div>
          <div class="field"><label for="g2">Ground</label>
            <select class="inp" id="g2">
              <option>Experience wrongly computed</option><option>Qualification wrongly assessed</option>
              <option>Age or relaxation wrongly applied</option><option>Category or reservation wrongly recorded</option>
              <option>A document on record was not considered</option><option>Fee or payment wrongly recorded</option>
            </select></div>
          <div class="field"><label for="g3">What you say, and why</label>
            <textarea class="inp" id="g3" rows="5" style="height:auto;padding:10px 12px;font:400 15px/1.5 var(--font-ui)" aria-describedby="g3h"></textarea>
            <div class="help" id="g3h">Give dates and the document. 0 of 1,500 characters.</div></div>
          <div class="field"><label for="g4">Anything further to enclose</label>
            <input class="inp" id="g4" type="file" style="padding:9px 12px" aria-describedby="g4h">
            <div class="help" id="g4h">Optional. Everything already on your application is before the officer — you need not upload it again.</div></div>
          <button class="btn p lg">File the grievance</button>
          <p class="t-caption" style="margin-top:10px">You may file <b>one</b> grievance per application. Choose the ground carefully; it fixes the rule the officer decides under.</p>
        </div></div>

        <div class="crec"><div class="hd"><span class="t-label">What is not heard here</span></div><div class="bd">
          <ul style="margin:0;padding-left:18px;font:400 14px/1.6 var(--font-ui);color:var(--ink-muted)">
            <li>The cut-off, the size of the shortlist, or your position in it — these are not disclosed at any stage, to anyone.</li>
            <li>Another candidate's application, score, or outcome.</li>
            <li>The conduct of the written test or the interview — that goes to the <b>Controller of Examinations</b>, within 7 days of the test.</li>
            <li>A deficiency you were asked to rectify — <a href="#">answer it in the window</a>, which is faster and is the intended route.</li>
          </ul>
          <p class="t-caption" style="margin-top:10px">Stated up front so a grievance is not filed into the wrong forum and lost there.</p>
        </div></div>

        <div class="crec"><div class="hd"><span class="t-label">The route, end to end</span></div><div class="bd">
          ${rail([['done', 'Screening decision', '11 Mar'], ['done', 'Grievance filed · 7 days', '14 Mar'],
    ['done', 'Acknowledged · 3 days', '17 Mar'], ['cur', 'Decision · 15 days', 'by 04 Apr'],
    ['fut', 'Appeal to the Registrar · 30 days', ''], ['fut', 'Closed', '']])}
        </div></div>
      </aside>
    </div>
  </div>
</div>` }));

console.log('candidate D written');
