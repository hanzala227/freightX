const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const ARTIFACT_DIR = 'C:\\Users\\Dr.pc\\.gemini\\antigravity\\brain\\1b68cfbb-5472-4ff3-a87a-6c6071afc7c2';
const APP_URL = 'http://127.0.0.1:8001';

(async () => {
    console.log('Starting Puppeteer automation for Trade Partner...');
    if (!fs.existsSync(ARTIFACT_DIR)) {
        fs.mkdirSync(ARTIFACT_DIR, { recursive: true });
    }
    const browser = await puppeteer.launch({
        headless: false,
        slowMo: 100,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    let page;
    try {
        page = await browser.newPage();
        await page.setViewport({ width: 1280, height: 1000 });

        // Capture page console logs
        page.on('console', msg => {
            console.log('PAGE CONSOLE:', msg.text());
        });

        // Auto accept dialogs
        page.on('dialog', async dialog => {
            console.log('PAGE DIALOG/ALERT:', dialog.message());
            try {
                await dialog.accept();
            } catch (e) {
                // ignore
            }
        });

        // 1. Login
        console.log(`Navigating to ${APP_URL}/login`);
        await page.goto(`${APP_URL}/login`, { waitUntil: 'domcontentloaded' });
        
        console.log('Logging in...');
        await page.type('input[name="email"]', 'sardar@gmail.com');
        await page.type('input[name="password"]', 'password');
        
        await Promise.all([
            page.click('button[type="submit"]'),
            page.waitForNavigation({ waitUntil: 'domcontentloaded' })
        ]);
        console.log('Logged in successfully.');

        // 2. Go to Trade Partner Create Page
        console.log(`Navigating to ${APP_URL}/trade-partner/create`);
        await page.goto(`${APP_URL}/trade-partner/create`, { waitUntil: 'domcontentloaded' });
        await page.waitForSelector('input[x-model="form.name"]');

        console.log('Page loaded. Checking Save button visibility...');
        await new Promise(r => setTimeout(r, 1000));
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'tp_create_page_loaded.png') });

        // 3. Fill required Trade Partner fields
        console.log('Filling form fields...');
        const timestamp = Date.now();
        const partnerName = `TP-AUTO-${timestamp}`;
        await page.evaluate((name) => {
            const data = window.Alpine.$data(document.querySelector('.page-content'));
            data.form.type = 'CS'; // CUSTOMER
            data.form.name = name;
            data.form.print_name = `${name} PRINT`;
            data.form.country_id = '1';
            data.form.phone = '1234567890';
            data.form.sales_office_id = '1';
            data.form.local_address = '123 Main Street Auto Lane';
        }, partnerName);
        await new Promise(r => setTimeout(r, 1000));
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'tp_form_filled.png') });

        // 4. Click Save
        console.log('Submitting the form...');
        await Promise.all([
            page.evaluate(() => {
                window.Alpine.$data(document.querySelector('.page-content')).submitForm();
            }),
            page.waitForNavigation({ waitUntil: 'domcontentloaded' })
        ]);

        console.log('Form submitted. Current URL:', page.url());
        await page.screenshot({ path: path.join(ARTIFACT_DIR, 'tp_saved_success.png') });

    } catch (error) {
        console.error('Error during Trade Partner automation:', error);
        if (page) {
            await page.screenshot({ path: path.join(ARTIFACT_DIR, 'tp_error_state.png') });
        }
    } finally {
        console.log('Keeping browser open for 15 seconds...');
        await new Promise(r => setTimeout(r, 15000));
        await browser.close();
        console.log('Browser closed.');
    }
})();
