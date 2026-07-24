const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const ARTIFACT_DIR = 'C:\\Users\\Dr.pc\\.gemini\\antigravity\\brain\\1b68cfbb-5472-4ff3-a87a-6c6071afc7c2';
const APP_URL = 'http://127.0.0.1:8001';

(async () => {
    console.log('Starting Puppeteer automation...');
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

        // Auto accept dialogs (alerts, prompts) and print them
        page.on('dialog', async dialog => {
            console.log('PAGE DIALOG/ALERT:', dialog.message());
            await dialog.accept();
        });

        // 1. Navigate to Login Page
        console.log(`Navigating to ${APP_URL}/login`);
        await page.goto(`${APP_URL}/login`, { waitUntil: 'domcontentloaded' });
        
        console.log('Logging in...');
        await page.type('input[name="email"]', 'sardar@gmail.com');
        await page.type('input[name="password"]', 'password');
        
        await Promise.all([
            page.click('button[type="submit"]'),
            page.waitForNavigation({ waitUntil: 'domcontentloaded' })
        ]);

        await page.screenshot({ path: path.join(ARTIFACT_DIR, '1_login_success.png') });
        console.log('Logged in successfully. Screenshot saved.');

        // 2. Navigate to Ocean Import Create Page
        console.log(`Navigating to ${APP_URL}/ocean-import/create`);
        await page.goto(`${APP_URL}/ocean-import/create`, { waitUntil: 'domcontentloaded' });
        await page.waitForSelector('input[name="mbl_no"]');
        await page.waitForFunction(() => typeof window.Alpine !== 'undefined' && typeof window.Alpine.$data !== 'undefined');

        // 3. Verify tab locking initially (try clicking Container & Items tab, check it doesn't change activeTab)
        console.log('Testing tab locking...');
        await page.evaluate(() => {
            // Find Container tab list item and click it
            const tabs = Array.from(document.querySelectorAll('.gf-tabs li'));
            const containerTab = tabs.find(t => t.textContent.includes('Container & Items'));
            if (containerTab) containerTab.click();
        });
        await new Promise(r => setTimeout(r, 500));
        
        let activeTab = await page.evaluate(() => {
            return window.Alpine.$data(document.querySelector('.page-content')).activeTab;
        });
        console.log('Active tab after trying to click container tab without required fields:', activeTab);
        await page.screenshot({ path: path.join(ARTIFACT_DIR, '2_tab_disabled_check.png') });

        // 4. Fill required MBL fields
        console.log('Filling required MB/L fields...');
        const timestamp = Date.now();
        const mblNo = `MBL-AUTO-${timestamp}`;
        // Set all required MBL fields directly in the Alpine state
        await page.evaluate((mblNo) => {
            const data = window.Alpine.$data(document.querySelector('.page-content'));
            data.form.mbl_no = mblNo;
            data.form.office_id = '1';
            data.form.eta = '2026-07-20';
            data.form.etd = '2026-07-01';
            data.form.voyage = 'VOY-AUTO-999';
            data.form.internal_remark = 'Auto-generated test shipment memo notes for validation.';
        }, mblNo);
        await new Promise(r => setTimeout(r, 500));

        // 5. Add dynamic HBL
        console.log('Adding HB/L...');
        await page.evaluate(() => {
            // Click ADD HBL button
            window.Alpine.$data(document.querySelector('.page-content')).addHbl();
        });
        await new Promise(r => setTimeout(r, 500));

        console.log('Filling HB/L fields...');
        const hblNo = `HBL-AUTO-${timestamp}`;
        await page.evaluate((hblNo) => {
            const data = window.Alpine.$data(document.querySelector('.page-content'));
            const hbl = data.hbls[0];
            hbl.hbl_no = hblNo;
            hbl.is_rail = true;
            hbl.hbl_remark = 'Auto-generated HBL memo notes.';
            // Set some dropdowns
            hbl.customer_id = '1';
            hbl.shipper_id = '1';
            hbl.consignee_id = '1';
            hbl.cfs_location_id = '1';
        }, hblNo);
        await new Promise(r => setTimeout(r, 500));
        await page.screenshot({ path: path.join(ARTIFACT_DIR, '3_form_filled_main_tab.png') });

        // 6. Test tab switching after required fields are valid
        console.log('Switching to Container & Items tab...');
        await page.evaluate(() => {
            window.Alpine.$data(document.querySelector('.page-content')).activeTab = 'container';
        });
        await new Promise(r => setTimeout(r, 500));
        
        activeTab = await page.evaluate(() => {
            return window.Alpine.$data(document.querySelector('.page-content')).activeTab;
        });
        console.log('Active tab after validation fill:', activeTab);

        // 7. Add container
        console.log('Adding container...');
        await page.evaluate(() => {
            window.Alpine.$data(document.querySelector('.page-content')).addContainer();
        });
        await new Promise(r => setTimeout(r, 500));
        
        await page.evaluate(() => {
            const data = window.Alpine.$data(document.querySelector('.page-content'));
            const container = data.form.containers[0];
            container.container_no = 'TCNU-1234567';
            container.seal_no = 'SEAL-9988';
            container.weight_kg = 5400;
        });
        await new Promise(r => setTimeout(r, 500));
        await page.screenshot({ path: path.join(ARTIFACT_DIR, '4_form_filled_container_tab.png') });

        // Go back to Main tab for submission and expand MB/L section
        await page.evaluate(() => {
            const data = window.Alpine.$data(document.querySelector('.page-content'));
            data.activeTab = 'basic';
            data.showMblSection = true;
        });
        await new Promise(r => setTimeout(r, 500));

        // 8. Submit Form
        console.log('Submitting the form...');
        await Promise.all([
            page.evaluate(() => {
                const buttons = Array.from(document.querySelectorAll('button'));
                const saveBtn = buttons.find(b => b.textContent.includes('SAVE SHIPMENT'));
                if (saveBtn) saveBtn.click();
                else throw new Error('SAVE SHIPMENT button not found');
            }),
            page.waitForNavigation({ waitUntil: 'domcontentloaded' })
        ]);
        
        const finalUrl = page.url();
        console.log('Final URL after save:', finalUrl);
        await page.screenshot({ path: path.join(ARTIFACT_DIR, '5_shipment_saved.png') });
        console.log('Form submitted successfully. Final screenshot saved.');

    } catch (error) {
        console.error('Error during automation:', error);
        if (page) {
            await page.screenshot({ path: path.join(ARTIFACT_DIR, 'error_state.png') });
            console.log('Error screenshot saved.');
        }
    } finally {
        console.log('Keeping browser open for 20 seconds...');
        await new Promise(r => setTimeout(r, 20000));
        await browser.close();
        console.log('Browser closed.');
    }
})();
