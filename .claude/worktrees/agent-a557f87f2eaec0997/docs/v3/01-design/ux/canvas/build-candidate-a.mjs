import { writeFileSync } from 'node:fs';
import { artboard, gateRule, cite, badge } from './shared.mjs';
const w = (f, s) => writeFileSync(new URL(f, import.meta.url), s);

/* Candidate chrome — comfortable density, 44px targets, 15px data baseline. */
export const CCSS = `<style>
.chead{display:flex;justify-content:space-between;align-items:center;gap:24px;
  padding:14px 40px;border-bottom:1px solid var(--rule-strong);background:var(--paper-raised)}
.chead .bd{display:flex;gap:11px;align-items:center}
.chead .bd b{font:600 16px/1.1 var(--font-display);display:block}
.chead .bd span{font:400 11px/1.3 var(--font-ui);color:var(--ink-faint);letter-spacing:.04em}
.cnav{display:flex;gap:22px;font-size:14px}
.cnav a{color:var(--ink-muted);padding:6px 0;border-bottom:2px solid transparent}
.cnav a.on{color:var(--ink);font-weight:600;border-bottom-color:var(--green)}
.cpage{padding:26px 40px 40px}
.crec{background:var(--paper-raised);border:1px solid var(--rule);border-radius:2px;margin-bottom:14px}
.crec .hd{padding:14px 18px;border-bottom:1px solid var(--rule)}
.crec .bd{padding:16px 18px}
.dl{display:grid;grid-template-columns:180px 1fr;row-gap:9px;column-gap:16px;font-size:15px}
.dl dt{color:var(--ink-muted);font-size:14px}
.meter{display:flex;flex-direction:column-reverse;gap:2px}
.meter i{display:block;height:17px;border:1px solid var(--rule);border-left:3px solid var(--rule-strong);
  background:var(--paper-sunk)}
.meter i.f{background:var(--green-wash);border-left-color:var(--green);border-color:var(--rule)}
.meter i.p{background:var(--brass-wash);border-left-color:var(--brass)}
.crest{width:34px;height:34px;border-radius:50%;border:1px solid var(--green);
  display:flex;align-items:center;justify-content:center;color:var(--green);
  font:600 9px/1 var(--font-ui);text-align:center;background:var(--paper-raised)}
</style>`;

export const CHEAD = (on = 'My applications') => `<header class="chead">
  <div class="bd"><div class="crest">AMU</div><div><b>CareersPro</b><span>ALIGARH MUSLIM UNIVERSITY</span></div></div>
  <nav class="cnav" aria-label="Main">${['Vacancies', 'My applications', 'Profile', 'Documents', 'Grievance']
    .map((n) => `<a href="#" class="${n === on ? 'on' : ''}">${n}</a>`).join('')}</nav>
  <div style="font-size:14px" class="muted">Aisha Khan · <a href="#">Sign out</a></div>
</header>`;

export const rail = (items) => `<ul class="rail">${items.map(([st, label, when]) => `
  <li class="${st}"><span class="tick"><span class="dot">${st === 'done' ? '✓' : st === 'cur' ? '▌' : '◦'}</span><span class="seg"></span></span>
  <span class="lbl">${label}</span><span class="when">${when || ''}</span></li>`).join('')}</ul>`;

/* ── C1 · Sign-in ───────────────────────────────────────────── */
w('SignIn.dc.html', artboard({
  w: 1440, body: `${CCSS}
<div style="display:flex;height:900px">
  <div style="flex:1 1 auto;position:relative;padding:38px 0 24px 56px;display:flex;flex-direction:column">
    <div class="bd" style="display:flex;gap:11px;align-items:center">
      <div class="crest">AMU</div>
      <div><b style="font:600 15px/1.2 var(--font-display);display:block;letter-spacing:.02em">ALIGARH MUSLIM UNIVERSITY</b>
      <span style="font:400 12px/1.3 var(--font-ui);color:var(--ink-faint)">Office of the Controller of Examinations</span></div>
    </div>
    <div style="flex:1;display:flex;align-items:center">
      <div style="width:392px;background:var(--paper-raised);border:1px solid var(--rule);border-radius:3px;padding:34px 36px 28px;margin-left:96px;box-shadow:0 1px 0 var(--rule)">
        <div style="display:flex;justify-content:center;margin-bottom:12px"><div class="crest" style="width:44px;height:44px;font-size:10px">AMU</div></div>
        <h1 class="t-page" style="text-align:center;font-size:26px;margin-bottom:22px">Sign in</h1>
        <div class="field"><label class="t-label" for="id" style="display:block;margin-bottom:6px">Email or employee ID</label>
          <input class="inp" id="id" placeholder="you@example.com — or your employee ID" aria-describedby="idh">
          <div class="help" id="idh">Applicants sign in with the email they registered.</div></div>
        <div class="field"><label class="t-label" for="pw" style="display:block;margin-bottom:6px">Password</label>
          <div style="position:relative"><input class="inp" id="pw" type="password" value="••••••••••••" style="padding-right:70px">
          <button class="btn sm" style="position:absolute;right:6px;top:6px;height:32px;border:0;background:transparent;color:var(--green);font-weight:600">Show</button></div></div>
        <label style="display:flex;gap:9px;align-items:center;font-size:14px;margin:4px 0 20px">
          <input type="checkbox" style="width:18px;height:18px;accent-color:var(--green)"> Keep me signed in</label>
        <button class="btn p lg">Sign in</button>
        <div style="text-align:center;margin-top:13px"><a href="#" style="font-size:14px;font-weight:600">Send me a code instead</a></div>
        <div style="text-align:center;margin-top:14px;padding-top:14px;border-top:1px solid var(--rule)"><a href="#" style="font-size:14px">Need help signing in?</a></div>
      </div>
    </div>
    <div class="t-caption" style="color:var(--ink-faint)">© 2026 Aligarh Muslim University · <a href="#" class="muted">Accessibility statement</a> · <a href="#" class="muted">Help</a> · Last updated 28 Aug 2026</div>
  </div>
  <div style="flex:0 0 660px;position:relative;overflow:hidden;
              clip-path:polygon(9% 0, 100% 0, 100% 100%, 0% 100%, 0% 88%);">
    <img src="victoria-gate.jpg" alt="Victoria Gate, Aligarh Muslim University"
         style="width:100%;height:100%;object-fit:cover;object-position:52% 40%;display:block">
    <div style="position:absolute;left:24px;bottom:22px;background:rgba(16,21,15,.72);color:#fff;
                border-radius:99px;padding:7px 15px;font:400 12px var(--font-ui);backdrop-filter:blur(3px)">
      <span style="color:#8FD3AC">●</span> Victoria Gate · AMU Aligarh</div>
  </div>
</div>` }));

/* Sign-in — error and lockout, drawn as their own artboard */
w('SignInStates.dc.html', artboard({
  w: 900, pad: true, body: `${CCSS}
${gateRule('', 'Sign-in · the states that matter')}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:6px">
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Credentials rejected</div>
    <div class="field"><label class="t-label" style="display:block;margin-bottom:6px">Email or employee ID</label>
      <input class="inp bad" value="aisha.khan@example.com" aria-describedby="e1">
      <div class="err" id="e1"><b>✕</b> Those credentials don’t match our records.</div></div>
    <p class="t-caption">Generic by design. It never says <i>no such user</i>, because that answer enumerates 55,050 registered accounts to anyone who asks.</p>
  </div></div>
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Locked after 5 attempts</div>
    <div class="notice" style="border-left-color:var(--rejected)">
      <b>Sign-in is locked for this account.</b><br>
      Five attempts failed. You can try again after <b>04:12 pm</b>, or reset your password now.
      <div style="margin-top:10px;display:flex;gap:8px"><a class="btn sm" href="#">Reset password</a><a class="btn sm" href="#">Get help</a></div>
    </div>
    <p class="t-caption" style="margin-top:10px">It states <b>when</b>, not “try later”. A candidate on a deadline needs the clock time.</p>
  </div></div>
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Verification — check your inbox</div>
    <p class="t-body" style="font-size:14px">We sent a verification link to <b>aisha.khan@example.com</b>. It is valid for <b>60 minutes</b>.</p>
    <p class="t-caption" style="margin-top:8px">Nothing signs you out while you wait. <a href="#">Send it again</a> · <a href="#">Use a different email</a></p>
  </div></div>
  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Link expired · already verified</div>
    <div class="notice">This link expired on <b>23 Jan 2026, 11:04 am</b>. <a href="#">Send a new one</a> — it takes about a minute.</div>
    <div class="notice" style="margin-top:10px;border-left-color:var(--eligible)"><b>✓</b> This email is already verified. <a href="#">Sign in</a>.</div>
    <p class="t-caption" style="margin-top:10px">The flow terminates in all three directions. The system being replaced logs every unverified user out permanently, so no one can ever verify.</p>
  </div></div>
</div>` }));

/* Sign-in — one-time code and second factor (DR-023) */
w('OtpStates.dc.html', artboard({
  w: 900, pad: true, body: `${CCSS}
${gateRule('', 'One-time code · the states that matter')}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:6px">

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Code sent</div>
    <p class="t-body" style="font-size:14px">We sent a 6-digit code to <b>•••••• 4821</b>. It expires in <b>10 minutes</b>.</p>
    <div style="display:flex;gap:8px;margin:12px 0">
      ${[3, 9, 1, '', '', ''].map((d) => `<input class="inp" value="${d}" style="width:44px;height:48px;text-align:center;font:600 20px/1 var(--font-ui);padding:0">`).join('')}
    </div>
    <button class="btn p">Sign in</button>
    <p class="t-caption" style="margin-top:10px">Resend available in <b>2 minutes 14 seconds</b> · <a href="#">Use my password instead</a></p>
    <p class="t-caption" style="margin-top:8px">The destination is masked, so a shoulder-surfer learns nothing and the candidate still recognises their own handset.</p>
  </div></div>

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Wrong code · expired code</div>
    <div class="field">
      <input class="inp bad" value="391 044" aria-describedby="o1">
      <div class="err" id="o1"><b>✕</b> That code is not correct. You have 2 attempts left.</div></div>
    <div class="notice" style="margin-top:12px">That code has expired. <a href="#">Request a new one</a>.</div>
    <p class="t-caption" style="margin-top:10px">Attempts remaining are stated. A code is single-use and bound to its purpose — a sign-in code can never answer a second-factor prompt.</p>
  </div></div>

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">No mobile on file</div>
    <div class="notice" style="border-left-color:var(--brass)">
      <b>We cannot send a code to this account.</b><br>
      It has no verified mobile number. Sign in with your password, then add and verify a mobile number in your profile.
      <div style="margin-top:10px;display:flex;gap:8px"><a class="btn sm" href="#">Use my password</a><a class="btn sm" href="#">Get help</a></div>
    </div>
    <p class="t-caption" style="margin-top:10px">Shown only after the identifier has established a pending sign-in. Shown on the first response it would enumerate 55,050 accounts.</p>
  </div></div>

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Second factor · channel picker</div>
    <p class="t-body" style="font-size:14px">Confirm it is you. Choose how to receive the code:</p>
    <div style="display:flex;flex-direction:column;gap:9px;margin:12px 0">
      <label style="display:flex;gap:9px;align-items:center;font-size:14px"><input type="radio" name="f" checked style="width:18px;height:18px;accent-color:var(--green)"> Authenticator app <span class="muted">· recommended</span></label>
      <label style="display:flex;gap:9px;align-items:center;font-size:14px;color:var(--ink-faint)"><input type="radio" name="f" disabled style="width:18px;height:18px"> Text message to •••••• 4821 <span class="muted">· already used to sign in</span></label>
      <label style="display:flex;gap:9px;align-items:center;font-size:14px"><input type="radio" name="f" style="width:18px;height:18px;accent-color:var(--green)"> A recovery code</label>
    </div>
    <button class="btn p">Continue</button>
    <p class="t-caption" style="margin-top:10px">The channel that served as the first factor is <b>disabled, with the reason shown</b> — never silently missing. A code to one handset cannot be both factors.</p>
  </div></div>

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Too many codes</div>
    <div class="notice" style="border-left-color:var(--rejected)">
      <b>Too many codes requested.</b><br>
      Try again after <b>04:12 pm</b>, or sign in with your password now.
      <div style="margin-top:10px;display:flex;gap:8px"><a class="btn sm" href="#">Use my password</a><a class="btn sm" href="#">Reset password</a></div>
    </div>
    <p class="t-caption" style="margin-top:10px">Five per hour, counted against the destination rather than the account, so one handset cannot be flooded through several accounts.</p>
  </div></div>

  <div class="crec"><div class="bd">
    <div class="t-label" style="margin-bottom:10px">Gateway unavailable</div>
    <div class="notice" style="border-left-color:var(--rejected)">
      <b>We could not send a code just now.</b><br>
      Sign in with your password, or try again in a few minutes.
      <div style="margin-top:10px"><a class="btn sm" href="#">Use my password</a></div>
    </div>
    <p class="t-caption" style="margin-top:10px">It <b>fails closed</b> — no session, no partial sign-in, no "we will verify later". The failure is audited.</p>
  </div></div>

</div>
<p class="t-caption" style="margin-top:16px">Every one of these works with JavaScript disabled: the six boxes collapse to one numeric field, and resend is a plain submit that re-renders the countdown server-side.</p>` }));

/* ── C3 · Browse vacancies ──────────────────────────────────── */
const vac = (id, title, ou, lvl, vacs, close, days, fee, type) => `
<li style="border-bottom:1px solid var(--rule);padding:16px 0;display:grid;grid-template-columns:1fr 150px 120px 128px;gap:20px;align-items:start">
  <div>
    <a href="#" class="t-sub" style="font-size:16px">${title}</a>
    <div class="t-caption" style="margin-top:3px">${ou}</div>
    <div style="margin-top:7px;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
      <span class="chip">${type}</span><span class="chip">Advertisement 2/2026/NT</span>
      <span class="ident faint" style="font-size:12px">post ${id}</span></div>
  </div>
  <div><div class="t-label" style="margin-bottom:3px">Pay</div><div class="t-data">${lvl}</div></div>
  <div><div class="t-label" style="margin-bottom:3px">Vacancies</div><div class="t-data num">${vacs}</div>
       <div class="t-label" style="margin:8px 0 3px">Fee</div><div class="t-data num">₹${fee}</div></div>
  <div style="text-align:right"><div class="t-label" style="margin-bottom:3px">Closes</div>
    <div class="t-data num">${close}</div>
    <div class="t-caption" style="color:${days <= 7 ? 'var(--brass)' : 'var(--ink-muted)'};margin-top:2px">${days} days remaining</div>
    <a class="btn sm" href="#" style="margin-top:9px">Check eligibility</a></div>
</li>`;

w('Vacancies.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Vacancies')}
<div class="cpage">
  <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:4px">
    <div><h1 class="t-page">Open vacancies</h1>
      <div class="t-caption" style="margin-top:4px">28 posts open · 4 close within 7 days · figures as at 28 Aug 2026, 04:10</div></div>
    <div class="filterchip"><span><b>3 filters</b> · Non-teaching · Pay Level-12 · closing within 30 days</span><a href="#">Clear</a></div>
  </div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div style="display:flex;gap:28px;align-items:flex-start">
    <form style="flex:0 0 268px" method="GET">
      <div class="t-label" style="margin-bottom:10px">Filters — nine, and they live in the URL</div>
      ${[['Search', 'text', 'system manager'], ['Post', 'select', 'All posts'], ['Department or centre', 'select', 'All organisational units'],
    ['Category', 'select', 'OBC-NCL'], ['Pay level', 'select', 'Level-12'], ['Location', 'select', 'AMU Aligarh'],
    ['Post type', 'select', 'General (Non-Teaching)'], ['Track', 'select', 'All tracks'], ['Subject', 'select', 'All subjects'],
    ['Closing', 'select', 'Within 30 days']].map(([l, t, v]) => `
      <div class="field" style="margin-bottom:11px"><label class="t-label" style="display:block;margin-bottom:5px">${l}</label>
      ${t === 'select' ? `<select class="inp sm">${['<option>' + v + '</option>']}</select>` : `<input class="inp sm" value="${v}">`}</div>`).join('')}
      <button class="btn p" style="width:100%">Apply filters</button>
      <p class="t-caption" style="margin-top:10px">This is a plain <span class="ident" style="font-size:12px">GET</span> form. It works with JavaScript off, and the result is a link you can send to someone.</p>
    </form>
    <div style="flex:1 1 auto;min-width:0">
      <div class="withmargin">
        <div class="margin">${['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'counts as at', '04:10 today'].map((l) => l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`).join('')}</div>
        <div class="body">
          <div style="display:flex;justify-content:space-between;align-items:baseline;border-bottom:1px solid var(--rule-strong);padding-bottom:8px">
            <span class="t-label">Showing 1–8 of 28 · sorted by closing date</span>
            <span class="t-caption">Sort: <a href="#">Closing date ↑</a> · <a href="#" class="muted">Pay level</a> · <a href="#" class="muted">Newest</a></span></div>
          <ul>
            ${vac('2599', 'System Manager', 'Prof. M.N. Farooqui Computer Centre', 'Level-12<br><span class="t-caption">₹78,800–2,09,200</span>', 1, '07-03-2026', 5, '500', 'General (Non-Teaching)')}
            ${vac('2601', 'Assistant Professor — Computer Science', 'Department of Computer Science, Faculty of Science', 'Academic Level-10<br><span class="t-caption">₹57,700–1,82,400</span>', 4, '14-03-2026', 12, '500', 'Teaching')}
            ${vac('2604', 'Senior Technical Assistant', 'Department of Electronics Engineering, ZHCET', 'Level-6<br><span class="t-caption">₹35,400–1,12,400</span>', 2, '07-03-2026', 5, '500', 'General (Non-Teaching)')}
            ${vac('2612', 'Professor — History', 'Department of History, Faculty of Arts', 'Academic Level-14<br><span class="t-caption">₹1,44,200–2,18,200</span>', 1, '21-03-2026', 19, '500', 'Teaching · Local')}
          </ul>
          <div class="pager" style="border:0;padding-left:0"><span>Showing 1–8 of 28</span>
            <span class="pp"><a href="#" class="on">1</a><a href="#">2</a><a href="#">3</a><a href="#">›</a></span>
            <span>8 per page</span></div>
        </div>
      </div>
    </div>
  </div>
</div>` }));

/* ── C4 · Advertisement detail ──────────────────────────────── */
w('AdvertDetail.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Vacancies')}
<div class="cpage">
  <h1 class="t-page">Advertisement No. 2/2026/NT</h1>
  <div class="t-caption" style="margin-top:4px">Non-teaching posts · dated 22.01.2026 · Office of the Registrar</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    <div class="margin">${['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'OU snapshot', '#1,208', '-', 'corrigenda 2'].map((l) => l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`).join('')}</div>
    <div class="body" style="display:flex;gap:28px;align-items:flex-start">
      <div style="flex:1 1 auto;min-width:0;max-width:760px">
        <div class="crec"><div class="bd">
          ${gateRule('', 'Notification')}
          <p class="t-body">Applications are invited from Indian nationals for the following non-teaching posts in the University. Applications must be submitted through this portal on or before <b>07 March 2026</b>, and the printed application with enclosures must reach the office named on the post by <b>14 March 2026</b>.</p>
          <div style="margin-top:14px;display:flex;gap:10px"><a class="btn" href="#">Download the notification (PDF, 412 KB)</a><a class="btn" href="#">View in Hindi / اردو</a></div>
          <div style="margin-top:12px">Governed by <span class="ident">ugc-nt-2026@1</span>${cite('frozen 2026-01-22 · the rules in force on the date of publication govern this advertisement for its life')}</div>
        </div></div>

        <div class="crec"><div class="bd">
          ${gateRule('', 'Corrigenda')}
          <p class="t-caption" style="margin:-4px 0 12px">Corrigenda are dated objects, not edits. The original notification is never altered; each correction is recorded, numbered and published on its own date.</p>
          <ul class="rows" style="border-top:1px solid var(--rule)">
            <li style="display:grid;grid-template-columns:96px 1fr;gap:14px"><span class="ident" style="color:var(--brass)">05-02-2026</span>
              <span><b>Corrigendum 1</b> — Post 2604, Senior Technical Assistant: essential qualification amended to read “B.E./B.Tech in Electronics”. <a href="#">Read</a></span></li>
            <li style="display:grid;grid-template-columns:96px 1fr;gap:14px"><span class="ident" style="color:var(--brass)">19-02-2026</span>
              <span><b>Corrigendum 2</b> — Last date for online submission extended from 28-02-2026 to <b>07-03-2026</b>. <a href="#">Read</a></span></li>
          </ul>
        </div></div>

        <div class="crec"><div class="bd">
          ${gateRule('', 'Posts under this advertisement')}
          <table class="tbl"><caption>4 posts · click a post for its eligibility and fee</caption>
          <thead><tr><th style="width:64px">POST</th><th>TITLE</th><th style="width:118px">PAY LEVEL</th><th style="width:74px">VAC.</th><th style="width:104px">LAST DATE</th></tr></thead>
          <tbody>
            <tr class="comfy"><td class="ident">2599</td><td><a href="#">System Manager</a><div class="t-caption">Prof. M.N. Farooqui Computer Centre</div></td><td>Level-12</td><td class="num">1</td><td class="num">07-03-2026</td></tr>
            <tr class="comfy"><td class="ident">2604</td><td><a href="#">Senior Technical Assistant</a><div class="t-caption">Electronics Engineering, ZHCET</div></td><td>Level-6</td><td class="num">2</td><td class="num">07-03-2026</td></tr>
            <tr class="comfy"><td class="ident">2607</td><td><a href="#">Junior Assistant</a><div class="t-caption">Office of the Controller of Examinations</div></td><td>Level-2</td><td class="num">6</td><td class="num">07-03-2026</td></tr>
            <tr class="comfy"><td class="ident">2609</td><td><a href="#">Laboratory Assistant</a><div class="t-caption">Department of Physics, Faculty of Science</div></td><td>Level-4</td><td class="num">3</td><td class="num">07-03-2026</td></tr>
          </tbody></table>
        </div></div>
      </div>

      <aside style="flex:0 0 300px">
        <div class="crec"><div class="hd"><span class="t-label">Eligibility summary · post 2599</span></div><div class="bd">
          <div class="dl" style="grid-template-columns:1fr;row-gap:12px;font-size:14px">
            <div><div class="t-label">Essential qualification</div>M.C.A. or M.Sc. (Computer Science) or B.E./B.Tech (CS/IT) with 55%${cite('CRR Sch. II item 14')}</div>
            <div><div class="t-label">Experience</div>Eight years in a supervisory IT post${cite('CRR Sch. II item 14 · proviso')}</div>
            <div><div class="t-label">Maximum age</div>50 years on 07-03-2026${cite('CRR Rule 14 — measured against the registration end date, never against today')}</div>
          </div>
        </div></div>
        <div class="crec"><div class="hd"><span class="t-label">Relaxations</span></div><div class="bd">
          <table class="tbl"><tbody>
            <tr><td>OBC-NCL</td><td class="r">3 years</td></tr>
            <tr><td>SC / ST</td><td class="r">5 years</td></tr>
            <tr><td>PwD</td><td class="r">10 years</td></tr>
            <tr><td>AMU employee</td><td class="r">5 years</td></tr>
          </tbody></table>
          <span class="cite" style="margin-top:10px">DoPT O.M. 15012/2/2010-Estt.(D) · cumulative relaxations are not permitted</span>
          <p class="t-caption" style="margin-top:10px"><b>PwD is the only fee exemption.</b> Every other category pays ₹500.</p>
        </div></div>
      </aside>
    </div>
  </div>
</div>` }));

/* ── C5 · Eligibility pre-check ─────────────────────────────── */
const crit = (name, yours, rule, verdict, word, note = '') => `
<tr class="comfy"><td style="width:190px"><b>${name}</b></td>
  <td style="width:230px">${yours}${note ? `<div class="t-caption">${note}</div>` : ''}</td>
  <td>${rule}</td>
  <td style="width:150px">${badge(verdict, word)}</td></tr>`;

w('PreCheck.dc.html', artboard({
  w: 1440, body: `${CCSS}${CHEAD('Vacancies')}
<div class="cpage">
  <h1 class="t-page">Before you pay — your eligibility for this post</h1>
  <div class="t-caption" style="margin-top:4px">Post 2599 · System Manager, Prof. M.N. Farooqui Computer Centre · Advertisement 2/2026/NT</div>
  <div class="mh-rule" style="margin-bottom:20px"></div>
  <div class="withmargin">
    <div class="margin">${['ugc-nt-2026@1', 'frozen', '22-01-2026', '-', 'measured', '07-03-2026', 'CRR Rule 14', '-', 'checked', '04:12 today'].map((l) => l === '-' ? '<div class="mg-sep"></div>' : `<span class="mg">${l}</span>`).join('')}</div>
    <div class="body" style="max-width:1000px">
      <div class="notice" style="margin-bottom:18px">
        This check runs <b>before payment</b> and against the rules frozen for this advertisement. Every value below is the one you entered in your profile, measured against <b>07-03-2026</b>, the registration end date for this post. The fee is <b>not refundable</b>, so read the four lines before you continue.
      </div>
      <div class="crec"><div class="bd">
        ${gateRule('', 'Statutory criteria')}
        <table class="tbl"><caption>Four criteria · your value, the rule, and the verdict</caption>
        <thead><tr><th>CRITERION</th><th>YOUR VALUE</th><th>THE RULE</th><th>VERDICT</th></tr></thead><tbody>
        ${crit('Age', '<b class="num">41 years 3 months</b>', 'Maximum 50 years, plus 3 years for OBC-NCL — 53 years<span class="cite">CRR Rule 14 · DoPT O.M. 15012/2/2010</span>', 'el', 'Eligible', 'computed from 26-11-1984 against 07-03-2026')}
        ${crit('Essential qualification', 'M.C.A., 2009<br><span class="t-caption">Biju Patnaik University of Technology · CGPA 6.28</span>', 'M.C.A. or M.Sc. (CS) or B.E./B.Tech (CS/IT), 55% or equivalent<span class="cite">CRR Sch. II item 14</span>', 'el', 'Eligible')}
        ${crit('Experience', '<b class="num">6 years 4 months</b>', 'Eight years in a supervisory information-technology post<span class="cite">CRR Sch. II item 14 · proviso</span>', 're', 'Not eligible', 'computed from 2 employment records')}
        ${crit('Fee', '₹500 · OBC-NCL', 'PwD is the only exemption<span class="cite">Executive Council 14-11-2025, item 6</span>', 'pe', 'Payable')}
        </tbody></table>
      </div></div>

      <div class="crec" style="border-color:var(--rejected);border-left:4px solid var(--rejected)"><div class="bd">
        <h2 class="t-section" style="font-size:18px;margin-bottom:6px">One criterion is not met, so you cannot be considered for this post.</h2>
        <p class="t-body" style="font-size:14px;max-width:70ch">The post requires <b>eight years</b> of supervisory experience on 07-03-2026 and your profile records <b>6 years 4 months</b>. If that is wrong — a period is missing, or an appointment is recorded with the wrong dates — correct it in <a href="#">Employment history</a> and run this check again. Nothing has been charged.</p>
        <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
          <a class="btn p" href="#">Correct my employment history</a>
          <a class="btn" href="#">Browse other open posts</a>
          <span class="btn off" style="cursor:not-allowed">Pay ₹500 and continue</span>
        </div>
        <p class="t-caption" style="margin-top:12px">The system being replaced evaluates age and experience <i>at the payment deadline</i>, so a candidate in this position pays ₹500 first and is told afterwards. That is the defect this screen exists to close.</p>
      </div></div>
    </div>
  </div>
</div>` }));

console.log('candidate A written');
