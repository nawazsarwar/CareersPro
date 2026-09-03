import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge } from './shared.mjs';
import { CCSS, CHEAD, rail } from './build-candidate-a.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);
const mg = (l) => `<div class="margin">${l.map((x) => x === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${x}</span>`).join('')}</div>`;

/* ── C8 · Document vault ────────────────────────────────────── */
w('Documents.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Documents')}
<div class="cpage">
  <h1 class="t-page">Documents</h1>
  <div class="t-caption" style="margin-top:4px">Aisha Khan · 9 documents · 1 needs replacing</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">${mg(['stored', 'encrypted', '-', 'retention', '7 years', 'DPDP 2023', '-', 'audit seq', '4,180'])}
  <div class="body" style="display:flex;gap:28px;align-items:flex-start">
    <div style="flex:1 1 auto;max-width:760px">
      ${gateRule('', 'Photograph, signature and thumb impression')}
      <div class="crec"><div class="bd" style="display:flex;gap:26px">
        <div style="flex:0 0 200px">
          <div style="width:175px;height:225px;border:1px solid var(--rule-strong);background:var(--paper-sunk);position:relative;overflow:hidden">
            <div style="position:absolute;inset:0;border:2px dashed var(--brass);margin:14px 22px"></div>
            <div style="position:absolute;left:0;right:0;bottom:0;background:rgba(16,21,15,.72);color:#fff;font:400 11px var(--font-mono);padding:4px 6px;text-align:center">350 × 450 · 7 : 9</div>
          </div>
          <div style="display:flex;gap:7px;margin-top:9px"><button class="btn sm">Reposition</button><button class="btn sm">Replace</button></div>
        </div>
        <div style="flex:1">
          <div class="t-label" style="margin-bottom:8px">Passport photograph</div>
          <table class="tbl" style="background:transparent"><tbody>
            <tr><td style="width:190px">Required size</td><td class="ident">350 × 450 px</td><td>${badge('el', 'Met')}</td></tr>
            <tr><td>Aspect ratio</td><td class="ident">7 : 9</td><td>${badge('el', 'Met')}</td></tr>
            <tr><td>File size</td><td class="ident">10–100 KB · yours 62 KB</td><td>${badge('el', 'Met')}</td></tr>
            <tr><td>Self-attested</td><td class="t-caption">Signed across the face of the copy</td><td>${badge('el', 'Declared')}</td></tr>
          </tbody></table>
          <span class="cite" style="margin-top:10px">The crop is enforced here, not checked later. A photograph that fails these specifications cannot be printed on an admit card.</span>
        </div>
      </div></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:14px">
        ${[['Signature', '300 × 150 px · 6 : 3', 'el', 'Accepted'], ['Thumb impression', '300 × 150 px · 6 : 3', 'el', 'Accepted']]
      .map(([t, spec, s, word]) => `<div class="crec"><div class="bd">
          <div class="t-label" style="margin-bottom:8px">${t}</div>
          <div style="height:78px;border:1px solid var(--rule-strong);background:var(--paper-sunk);display:flex;align-items:flex-end;justify-content:center">
            <div style="background:rgba(16,21,15,.72);color:#fff;font:400 11px var(--font-mono);padding:3px 6px;width:100%;text-align:center">${spec}</div></div>
          <div style="margin-top:9px;display:flex;justify-content:space-between;align-items:center">${badge(s, word)}<button class="btn sm">Replace</button></div>
        </div></div>`).join('')}
      </div>

      <div style="height:24px"></div>
      ${gateRule('', 'Certificates and testimonials')}
      <div class="crec"><table class="tbl">
        <caption>Six documents · viewed in the browser, never downloaded as loose files</caption>
        <thead><tr><th>DOCUMENT</th><th style="width:120px">UPLOADED</th><th style="width:86px">SIZE</th><th style="width:150px">STATE</th><th style="width:120px"></th></tr></thead><tbody>
        ${[['Matriculation certificate', '21-01-2026', '480 KB', 'el', 'Accepted'],
      ['Senior secondary certificate', '21-01-2026', '512 KB', 'el', 'Accepted'],
      ['B.Tech degree', '21-01-2026', '1.1 MB', 'el', 'Accepted'],
      ['M.C.A. degree', '21-01-2026', '980 KB', 'el', 'Accepted'],
      ['Category certificate — OBC-NCL', '22-01-2026', '640 KB', 'el', 'Accepted'],
      ['Experience certificate', '23-01-2026', '220 KB', 're', 'Illegible']]
      .map(([n, d, s, st, word]) => `<tr class="comfy"><td>${n}</td><td class="num">${d}</td><td class="num">${s}</td>
          <td>${badge(st, word)}</td><td class="r"><a href="#">${st === 're' ? 'Replace' : 'View'}</a></td></tr>`).join('')}
        </tbody></table></div>
    </div>
    <aside style="flex:0 0 300px">
      <div class="crec"><div class="hd"><span class="t-label">Inline viewer</span></div><div class="bd">
        <div style="height:190px;background:var(--paper-sunk);border:1px solid var(--rule);display:flex;align-items:center;justify-content:center;color:var(--ink-faint);font:400 12px var(--font-mono)">experience-certificate.pdf · p1 of 2</div>
        <div style="display:flex;gap:7px;margin-top:9px"><button class="btn sm">−</button><button class="btn sm">+</button><button class="btn sm">Rotate</button><span class="t-caption" style="margin-left:auto;align-self:center">100%</span></div>
        <p class="t-caption" style="margin-top:10px">Documents are read in the page. There is no download link, because a loose PDF of someone’s degree certificate is a copy of special-category data that leaves the system unaudited.</p>
      </div></div>
      <div class="crec"><div class="hd"><span class="t-label">Self-attestation</span></div><div class="bd">
        <p class="t-caption">Every uploaded copy must be signed across its face. You declared this on 23-01-2026 at 21:19; the declaration is part of snapshot #1.</p>
        <span class="cite" style="margin-top:8px">CRR Rule 22.3</span>
      </div></div>
    </aside>
  </div></div>
</div>` }));

/* ── C9 · Preview and submit ────────────────────────────────── */
w('Preview.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div class="cpage">
  <h1 class="t-page">Review and submit</h1>
  <div class="t-caption" style="margin-top:4px">System Manager · post 2599 · this is the form exactly as it will print</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div style="display:flex;gap:30px;align-items:flex-start">
    <div style="flex:0 0 794px;border:1px solid var(--rule-strong);background:#fff">
      <div class="sheet" style="padding:34px 40px">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #000;padding-bottom:10px">
          <div><h1>ALIGARH MUSLIM UNIVERSITY, ALIGARH</h1>
            <div style="font-size:10.5px">Application for the post of <b>System Manager</b>, Prof. M.N. Farooqui Computer Centre</div>
            <div style="font-size:10.5px">Advertisement No. 2/2026/NT dated 22.01.2026 · Post 2599 · General (Non-Teaching)</div></div>
          <div style="width:74px;height:95px;border:1px solid #000;font-size:8px;text-align:center;padding-top:38px">PHOTO<br>35 × 45 mm</div>
        </div>
        <table style="margin-top:12px"><tbody>
          <tr><th style="width:26%">1. Name in full</th><td colspan="3">AISHA KHAN</td></tr>
          <tr><th>2. Father’s name</th><td style="width:24%">MOHAMMAD YUSUF KHAN</td><th style="width:26%">3. Date of birth</th><td>26-11-1984</td></tr>
          <tr><th>4. Category</th><td>OBC-NCL</td><th>5. Gender</th><td>Female</td></tr>
          <tr><th>6. Correspondence address</th><td colspan="3">506, 5th Floor, IT Palm Court Apartments, Dodhpur Road, opp. Noor Manzil, Civil Lines, Aligarh, Uttar Pradesh 202001</td></tr>
          <tr><th>7. Age on 07-03-2026</th><td colspan="3">41 years 3 months <i>(computed against the registration end date under CRR Rule 14)</i></td></tr>
        </tbody></table>
        <div style="font:600 10px/1 var(--font-ui);letter-spacing:.08em;margin:14px 0 5px">8. ACADEMIC QUALIFICATIONS</div>
        <table><thead><tr><th>Examination</th><th>Board or university</th><th>Year</th><th>%</th><th>CGPA</th></tr></thead><tbody>
          <tr><td>Secondary School Certificate</td><td>Aligarh Muslim University</td><td>2000</td><td>66.6</td><td>—</td></tr>
          <tr><td>Senior Secondary Certificate</td><td>Aligarh Muslim University</td><td>2002</td><td>60.75</td><td>—</td></tr>
          <tr><td>B.Tech (Computer Science)</td><td>Biju Patnaik University of Technology</td><td>2005</td><td>—</td><td>6.28</td></tr>
          <tr><td>M.C.A.</td><td>Biju Patnaik University of Technology</td><td>2009</td><td>—</td><td>7.10</td></tr>
        </tbody></table>
        <div style="font:600 10px/1 var(--font-ui);letter-spacing:.08em;margin:14px 0 5px">9. DECLARATION</div>
        <p style="font-size:10.5px">I declare that the particulars given above are true to the best of my knowledge and belief, and that no criminal proceedings are pending against me. I understand that any suppression of fact will render my candidature liable to cancellation at any stage.</p>
        <div style="display:flex;justify-content:space-between;margin-top:26px;font-size:10.5px">
          <span>Date: 23-01-2026 &nbsp; Place: Aligarh</span><span>Signature of the candidate</span></div>
        <div style="margin-top:18px;border-top:1px solid #000;padding-top:6px;font-size:9px;display:flex;justify-content:space-between">
          <span>Page 1 of 4 · generated from snapshot #1</span><span>ugc-nt-2026@1 · frozen 22-01-2026</span></div>
      </div>
    </div>
    <aside style="flex:1 1 auto;max-width:420px">
      <div class="crec" style="border-color:var(--brass);border-left:4px solid var(--brass)"><div class="bd">
        <h2 class="t-section" style="font-size:18px">Submitting is final.</h2>
        <p class="t-body" style="font-size:14px;margin-top:6px">When you submit, a snapshot of this form is written and sealed. You will not be able to change it afterwards, except in the named sections of a deficiency window if the scrutiny office opens one.</p>
        <div class="field" style="margin-top:16px"><label for="cf">Type <b>SUBMIT</b> to confirm</label>
          <input class="inp" id="cf" placeholder="SUBMIT" aria-describedby="cfh">
          <div class="help" id="cfh">Typed confirmation, because this cannot be undone.</div></div>
        <button class="btn p lg">Submit my application</button>
        <button class="btn" style="width:100%;margin-top:9px">Keep editing</button>
      </div></div>
      <div class="crec"><div class="hd"><span class="t-label">What happens next</span></div><div class="bd">
        <ul class="rail" style="font-size:14px">
          ${[['cur', 'You submit — snapshot #1 sealed', 'now'], ['fut', 'You pay ₹500 — the fee is not refundable', 'by 07-03-2026'],
      ['fut', 'You print, sign and post the form', 'by 14-03-2026'], ['fut', 'The scrutiny office reads your dossier', ''],
      ['fut', 'Written test, then interview', '']].map(([st, l, when]) => `<li class="${st}"><span class="tick"><span class="dot">${st === 'cur' ? '▌' : '◦'}</span><span class="seg"></span></span><span class="lbl">${l}</span><span class="when">${when}</span></li>`).join('')}
        </ul>
      </div></div>
      <div class="crec"><div class="hd"><span class="t-label">Before you submit</span></div><div class="bd">
        <div style="display:flex;justify-content:space-between;font-size:14px;padding:4px 0">All 11 sections of Part A ${badge('el', 'Complete')}</div>
        <div style="display:flex;justify-content:space-between;font-size:14px;padding:4px 0;border-top:1px solid var(--rule)">Documents ${badge('el', '9 uploaded')}</div>
        <div style="display:flex;justify-content:space-between;font-size:14px;padding:4px 0;border-top:1px solid var(--rule)">Eligibility pre-check ${badge('el', 'Passed 22-01')}</div>
      </div></div>
    </aside>
  </div>
</div>` }));

/* ── C10 · Payment ──────────────────────────────────────────── */
w('Payment.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD()}
<div class="cpage">
  <h1 class="t-page">Pay the application fee</h1>
  <div class="t-caption" style="margin-top:4px">System Manager · post 2599 · payment closes 07-03-2026, 11:59 pm</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">${mg(['order', 'rzp_QK4t81nHc', '-', 'idempotency', 'key held', '-', 'fee rule', 'EC 14-11-2025'])}
  <div class="body" style="display:flex;gap:28px;align-items:flex-start">
    <div style="flex:1 1 auto;max-width:640px">
      <div class="crec"><div class="bd">
        ${gateRule('', 'Your fee')}
        <table class="tbl" style="background:transparent"><tbody>
          <tr class="comfy"><td>Category</td><td class="r">OBC-NCL</td></tr>
          <tr class="comfy"><td>Application fee</td><td class="r num">₹500</td></tr>
          <tr class="comfy"><td>Exemption</td><td class="r">None${cite('PwD is the only fee exemption · EC 14-11-2025 item 6')}</td></tr>
          <tr><td style="border-top:1px solid var(--rule-strong)"><b>Payable now</b></td>
              <td class="r" style="border-top:1px solid var(--rule-strong)"><b class="t-figure num" style="font-size:24px">₹500</b></td></tr>
        </tbody></table>
        <p class="t-caption" style="margin-top:12px">The fee is <b>not refundable</b>, including if you are later found ineligible. Your eligibility was checked against the frozen rules on 22-01-2026 and all four criteria were met.</p>
        <div style="margin-top:16px;display:flex;gap:10px;align-items:center">
          <button class="btn p lg" style="width:auto">Pay ₹500 with Razorpay</button>
          <button class="btn lg" style="width:auto">Pay with BillDesk</button></div>
      </div></div>
    </div>
    <aside style="flex:0 0 420px">
      <div class="crec" style="border-left:4px solid var(--info)"><div class="bd">
        <span class="t-label">The state that matters — payment pending</span>
        <h3 class="t-sub" style="margin:7px 0 6px;font-size:16px">Your payment is being confirmed by the bank.</h3>
        <p class="t-body" style="font-size:14px">₹500 was debited at <b>21:31</b> against order <span class="ident">rzp_QK4t81nHc</span>. The bank has not yet sent us the confirmation. This usually takes a few minutes and can take up to <b>48 hours</b> on a bank holiday.</p>
        <div class="notice" style="margin-top:12px;background:var(--paper-sunk);border-left-color:var(--rule-strong)">
          <b>Do not pay again.</b> Your application is held against this order. If the bank confirms, your fee is recorded automatically and the state changes here. If the bank reverses it, the amount returns to your account and we will tell you by email — you will still have until <b>07-03-2026</b> to pay.
        </div>
        <div style="margin-top:12px;display:flex;gap:8px"><button class="btn sm">Check again</button><button class="btn sm">Email me the receipt</button></div>
        <p class="t-caption" style="margin-top:10px">₹93,14,500 of failed transactions sits in the system being replaced, much of it duplicate payments made because a lost callback looked like a failure. It never looks like a failure here.</p>
      </div></div>
      <div class="crec"><div class="hd"><span class="t-label">Your payments</span></div>
        <table class="tbl"><thead><tr><th>ORDER</th><th style="width:104px">DATE</th><th style="width:74px">AMOUNT</th><th style="width:130px">STATE</th></tr></thead><tbody>
        <tr><td class="ident">rzp_QK4t81nHc</td><td class="num">23-01-2026</td><td class="r num">₹500</td><td>${badge('pe', 'Awaiting bank')}</td></tr>
        <tr><td class="ident">rzp_PW9a02mZt</td><td class="num">04-12-2025</td><td class="r num">₹500</td><td>${badge('el', 'Received')}</td></tr>
        </tbody></table></div>
    </aside>
  </div></div>
</div>` }));

/* ── mobile 390 ─────────────────────────────────────────────── */
const MHEAD = `<header style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--rule-strong);background:var(--paper-raised)">
  <div style="display:flex;gap:9px;align-items:center"><div class="crest" style="width:28px;height:28px;font-size:8px">AMU</div>
  <b style="font:600 14px/1 var(--font-display)">CareersPro</b></div>
  <button class="btn sm" aria-label="Menu">Menu</button></header>`;

w('MobileSignIn.dc.html', artboard({
  w: 390, body: `${CCSS}
<div style="height:253px;overflow:hidden;position:relative">
  <img src="victoria-gate.jpg" alt="Victoria Gate, Aligarh Muslim University" style="width:100%;height:100%;object-fit:cover;object-position:50% 42%;display:block">
  <div style="position:absolute;left:14px;bottom:12px;background:rgba(16,21,15,.72);color:#fff;border-radius:99px;padding:6px 13px;font:400 11px var(--font-ui)"><span style="color:#8FD3AC">●</span> Victoria Gate · AMU Aligarh</div>
</div>
<div style="padding:22px 20px 30px">
  <div style="display:flex;gap:10px;align-items:center;margin-bottom:20px"><div class="crest">AMU</div>
    <div><b style="font:600 13px/1.2 var(--font-display);display:block;letter-spacing:.02em">ALIGARH MUSLIM UNIVERSITY</b>
    <span style="font:400 11px/1.3 var(--font-ui);color:var(--ink-faint)">Office of the Controller of Examinations</span></div></div>
  <h1 class="t-page" style="font-size:24px;margin-bottom:18px">Sign in</h1>
  <div class="field"><label class="t-label" style="display:block;margin-bottom:6px">Email or employee ID</label>
    <input class="inp" placeholder="you@example.com — or your employee ID"></div>
  <div class="field"><label class="t-label" style="display:block;margin-bottom:6px">Password</label>
    <div style="position:relative"><input class="inp" type="password" value="••••••••••" style="padding-right:66px">
    <button class="btn sm" style="position:absolute;right:6px;top:6px;height:32px;border:0;background:transparent;color:var(--green);font-weight:600">Show</button></div></div>
  <label style="display:flex;gap:9px;align-items:center;font-size:14px;margin:4px 0 18px">
    <input type="checkbox" style="width:20px;height:20px;accent-color:var(--green)"> Keep me signed in</label>
  <button class="btn p lg">Sign in</button>
  <div style="text-align:center;margin-top:13px"><a href="#" style="font-size:14px;font-weight:600">Send me a code instead</a></div>
  <div style="text-align:center;margin-top:14px;padding-top:14px;border-top:1px solid var(--rule)"><a href="#" style="font-size:14px">Need help signing in?</a></div>
  <p class="t-caption" style="margin-top:22px;color:var(--ink-faint)">The photograph is a band above the card, never a background behind text. Below 900px it stops being decoration and becomes a header. The code path is a secondary submit on the same card — never a second screen.</p>
</div>` }));

w('MobileDashboard.dc.html', artboard({
  w: 390, body: `${CCSS}${MHEAD}
<div style="padding:18px 16px 28px">
  <h1 class="t-page" style="font-size:24px">Your applications</h1>
  <div class="t-caption" style="margin:3px 0 14px">Aisha Khan · 2 applications · <b style="color:var(--brass)">1 needs you now</b></div>
  <div class="banner">
    <h3 style="font-size:16px">Action needed — <span class="cd">5 days remaining</span></h3>
    <p class="t-body" style="font-size:14px;margin-top:3px">Your experience certificate is illegible. Re-upload it in <b>Employment history</b>.</p>
    <p class="t-caption" style="margin-top:6px">Closes 19 Mar 2026, 5:00 pm</p>
    <a class="btn p" href="#" style="width:100%;margin-top:11px">Re-upload the certificate</a>
  </div>
  <div style="height:20px"></div>
  ${gateRule('', 'Your applications', 'sm')}
  <div class="crec"><div class="bd">
    <a href="#" class="t-sub" style="font-size:16px">System Manager</a>
    <div class="t-caption">Prof. M.N. Farooqui Computer Centre</div>
    <div class="ident" style="margin:7px 0 10px">2599/2026/00412</div>
    ${rail([['done', 'Registered', '21 Jan'], ['done', 'Submitted', '23 Jan'], ['done', 'Paid', '23 Jan'],
    ['cur', 'Under scrutiny', '11 Mar'], ['fut', 'Screened', ''], ['fut', 'Written test', ''], ['fut', 'Interview', ''], ['fut', 'Result', '']])}
    <div style="border-top:1px solid var(--rule);margin-top:8px;padding-top:10px;display:flex;gap:16px;flex-wrap:wrap">
      <div><div class="t-label">Scrutiny</div>${badge('pe', 'Pending')}</div>
      <div><div class="t-label">Written test</div>${badge('pe', 'Pending')}</div>
      <div><div class="t-label">Interview</div>${badge('pe', 'Pending')}</div>
    </div>
  </div></div>
  <div class="crec"><div class="bd">
    <a href="#" class="t-sub" style="font-size:16px">Assistant Professor — Computer Science</a>
    <div class="t-caption">Department of Computer Science</div>
    <div class="ident" style="margin:7px 0 10px">884/2026/01109</div>
    ${rail([['done', 'Submitted', '04 Dec'], ['done', 'Paid', '04 Dec'], ['done', 'Under scrutiny', '19 Feb'],
    ['cur', 'Screened', '26 Feb'], ['fut', 'Interview', ''], ['fut', 'Result', '']])}
    <div style="border-top:1px solid var(--rule);margin-top:8px;padding-top:10px">
      <div style="display:flex;gap:20px"><div><div class="t-label">Scrutiny</div>${badge('el', 'Eligible')}</div>
      <div><div class="t-label">Interview</div>${badge('pe', 'Pending')}</div></div>
      <p class="t-caption" style="margin-top:8px">Decided by interview. There is no written test for this post.</p>
      <div style="margin-top:12px;border-top:1px solid var(--rule-strong);padding-top:10px">
        <div class="t-label" style="margin-bottom:6px">Your score</div>
        <table class="tbl" style="background:transparent;font-size:13px"><tbody>
          <tr><td>Ph.D. awarded</td><td class="r num"><b>20</b></td></tr>
          <tr><td>Research papers, Column II<div class="ident faint" style="font-size:11px">App. II Table 2 row 1</div></td><td class="r num"><b>50</b></td></tr>
          <tr><td>Book, national publisher</td><td class="r num"><b>10</b></td></tr>
          <tr><td>Conference papers</td><td class="r num"><b>10</b></td></tr>
          <tr><td>Project, Co-PI<div class="ident faint" style="font-size:11px">row 4(b) · 50% each</div></td><td class="r num"><b>2.5</b></td></tr>
          <tr><td style="border-top:1px solid var(--rule-strong)"><b>Provisional total</b><div class="t-caption">threshold 75</div></td>
              <td class="r num" style="border-top:1px solid var(--rule-strong)"><b style="font-size:17px">92.5</b></td></tr>
        </tbody></table>
        <div class="notice" style="margin-top:10px;font-size:12px"><b>Impact-factor scoring is not applied.</b> It awaits Executive Council ratification. Your claims are recorded in full.</div>
      </div>
    </div>
  </div></div>
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:9px">Your profile</div>
    <div style="display:flex;gap:16px;align-items:flex-end">
      <div class="meter" aria-hidden="true">${['f', 'f', 'f', 'f', 'f', 'p', '', '', '', '', ''].map((c) => `<i class="${c}" style="width:56px"></i>`).join('')}</div>
      <div><div class="t-figure num" style="font-size:26px">64<span style="font-size:15px">%</span></div>
        <div class="t-caption">7 of 11 sections</div>
        <a class="btn sm" href="#" style="margin-top:8px">Continue</a></div>
    </div>
  </div></div>
</div>` }));

w('MobileWizard.dc.html', artboard({
  w: 390, body: `${CCSS}${MHEAD}
<div style="padding:16px 16px 28px">
  <details open style="border:1px solid var(--rule-strong);border-radius:2px;background:var(--paper-raised);margin-bottom:18px">
    <summary style="padding:12px 14px;cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;gap:10px">
      <span><span class="ident faint" style="font-size:12px">A3</span> <b>Addresses</b>
        <span class="t-caption" style="display:block">Part A · 7 of 11 complete</span></span>
      <span class="t-caption">Change section ▾</span></summary>
    <div style="border-top:1px solid var(--rule)">
      ${[['A1', 'Personal details', 'el', '✓'], ['A2', 'Photographs & signature', 'el', '✓'], ['A3', 'Addresses', 'el', '✓'],
    ['A4', 'Institutions attended', 'el', '✓'], ['A5', 'Academic qualifications', 'el', '✓'], ['A6', 'Employment history', 'pe', '4 of 9'],
    ['A7', 'Research summary', 'el', '✓'], ['A8', 'Referees', 'el', '✓'], ['A9', 'Testimonials', 'pe', '◦'],
    ['A10', 'Declarations', 're', '✕ 2 errors'], ['A11', 'Other information', 'pe', '◦']]
      .map(([n, t, s, mark]) => `<a href="#" style="display:flex;justify-content:space-between;gap:10px;padding:11px 14px;border-bottom:1px solid var(--rule);font-size:14px;color:var(--ink)">
        <span><span class="ident faint" style="font-size:12px">${n}</span> ${t}</span><span class="b ${s}"><span class="g">${mark}</span></span></a>`).join('')}
    </div>
  </details>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <span class="b el"><span class="g">✓</span> Saved 14:32</span>
    <span class="mg" style="text-align:right">ugc-nt-2026@1 · frozen 22-01-2026</span>
  </div>
  ${gateRule('A3', 'Addresses', 'sm')}
  <div class="field"><label for="m1">Correspondence address</label><input class="inp" id="m1" value="506, 5th Floor, IT Palm Court"></div>
  <div class="field"><label for="m2">Locality</label><input class="inp" id="m2" value="Dodhpur Road, Civil Lines"></div>
  <div class="field"><label for="m3">City or town</label><input class="inp" id="m3" value="Aligarh"></div>
  <div class="field"><label for="m4">PIN</label><input class="inp" id="m4" value="202001" inputmode="numeric" style="max-width:160px"></div>
  <div class="field"><label for="m5">Domicile district</label><input class="inp" id="m5" value="Aligarh" aria-describedby="m5c">
    <span class="cite" id="m5c">Domicile governs the local-candidate concession · CRR Rule 9.2</span></div>
  <button class="btn p lg">Save and continue to A4</button>
  <button class="btn lg" style="margin-top:9px">Save and close</button>
  <p class="t-caption" style="margin-top:14px">The section picker replaces the spine below 900px and still shows completion for all eleven. It is a native <span class="ident" style="font-size:12px">&lt;details&gt;</span>, so it opens with JavaScript off.</p>
</div>` }));

w('MobileDeficiency.dc.html', artboard({
  w: 390, body: `${CCSS}${MHEAD}
<div style="padding:16px 16px 28px">
  <div class="banner" style="margin-bottom:16px">
    <h3 style="font-size:16px">Action needed — <span class="cd">5 days remaining</span></h3>
    <p class="t-body" style="font-size:14px;margin-top:3px">Your experience certificate is illegible. Re-upload it in <b>Employment history</b>.</p>
    <p class="t-caption" style="margin-top:6px">Closes 19 Mar 2026, 5:00 pm · raised by the scrutiny office on 12 Mar</p>
  </div>
  ${gateRule('A6', 'Employment history', 'sm')}
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:8px">The document to replace</div>
    <div style="display:flex;gap:12px;align-items:center;padding:10px;background:var(--paper-sunk);border:1px solid var(--rule)">
      <div style="width:44px;height:56px;border:1px solid var(--rule-strong);background:var(--paper-raised)"></div>
      <div><b style="font-size:14px">Experience certificate</b>
        <div class="t-caption">uploaded 23-01-2026 · 220 KB</div>${badge('re', 'Illegible')}</div>
    </div>
    <p class="t-caption" style="margin:10px 0">Scan or photograph the original at a higher resolution. PDF or JPEG, up to 5 MB, self-attested.</p>
    <button class="btn p lg">Choose a file</button>
  </div></div>
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:8px">Everything else stays as you submitted it</div>
    <div>
      ${[['A1', 'Personal details'], ['A2', 'Photographs & signature'], ['A3', 'Addresses'], ['A4', 'Institutions attended'],
    ['A5', 'Academic qualifications']].map(([n, t]) => `<div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--rule);font-size:14px;color:var(--ink-faint)">
      <span><span class="ident" style="font-size:12px">${n}</span> ${t}</span><span>▪ locked</span></div>`).join('')}
      <div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--rule);font-size:14px">
        <span><span class="ident" style="font-size:12px">A6</span> <b>Employment history</b></span><span class="b pe"><span class="g">▌</span> open</span></div>
      ${[['A7', 'Research summary'], ['A8', 'Referees']].map(([n, t]) => `<div style="display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--rule);font-size:14px;color:var(--ink-faint)">
      <span><span class="ident" style="font-size:12px">${n}</span> ${t}</span><span>▪ locked</span></div>`).join('')}
    </div>
    <p class="t-caption" style="margin-top:10px">A locked section can still be opened and read. It simply has no fields, because a deficiency window re-opens only what the scrutiny office named.</p>
  </div></div>
  <button class="btn p lg">Submit the replacement</button>
  <p class="t-caption" style="margin-top:10px">When you submit, snapshot #2 is written, your original submission is kept untouched, and the application returns to <b>under scrutiny</b>.</p>
</div>` }));

console.log('candidate C written');
