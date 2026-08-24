const { chromium } = require('playwright');
const fs = require('fs');

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage();

  try {
      console.log('Fetching URL...');
      await page.goto('https://amuonline.ac.in/nawaz', { waitUntil: 'networkidle' });
      await page.goto('https://amuonline.ac.in/login', { waitUntil: 'networkidle' });

      const html = await page.content();
      fs.writeFileSync('docs/research/amu_login_source.html', html);

      await page.screenshot({ path: 'docs/progress_screenshot/amu_login_target.png' });
      console.log('Successfully captured AMU login UI structure and screenshot.');
  } catch (e) {
      console.error(e);
  } finally {
      await browser.close();
  }
})();
