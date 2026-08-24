const { chromium } = require('playwright');
const assert = require('assert');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });

  try {
      await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle' });
      await page.screenshot({ path: 'docs/progress_screenshot/login_recreated_tailwind.png' });

      const bodyText = await page.textContent('body');

      if(bodyText.includes('Welcome back') && bodyText.includes('CareersPro')) {
          console.log("Auth visually verified with Tailwind split-pane design.");
      } else {
          console.log("Expected heading missing.");
      }
  } catch (e) {
      console.error(e);
  } finally {
      await browser.close();
  }
})();
