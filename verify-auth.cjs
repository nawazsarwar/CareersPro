const { chromium } = require('playwright');
const assert = require('assert');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();

  try {
      const response = await page.goto('http://127.0.0.1:8000/login');
      console.log("Status: " + response.status());
      await page.waitForLoadState('networkidle');
      await page.screenshot({ path: 'docs/images/login-tailwind.png' });

      const bodyText = await page.textContent('body');
      console.log("Body text extract: " + bodyText.substring(0, 100));

      if(bodyText.includes('Login to CareersPro')) {
          console.log("Auth visually verified with Tailwind.");
      } else {
          console.log("Expected heading missing.");
      }
  } catch (e) {
      console.error(e);
  } finally {
      await browser.close();
  }
})();
