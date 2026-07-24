const fs = require('fs');
const path = require('path');

const files = [
    'e:/FMS/fms/resources/views/ocean-import/index.blade.php',
    'e:/FMS/fms/resources/views/ocean-export/index.blade.php',
    'e:/FMS/fms/resources/views/air-import/index.blade.php',
    'e:/FMS/fms/resources/views/air-export/index.blade.php',
    'e:/FMS/fms/resources/views/truck/create.blade.php'
];

const patterns = [
    { regex: /<label[^>]*>Customer<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Shipper<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Consignee<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Forwarding Agent<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Oversea Agent<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Carrier<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Co-loader<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Acct\. Carrier<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Bill To<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    { regex: /<label[^>]*>Notify<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'trade-partner', options: '$agents' },
    
    { regex: /<label[^>]*>Port of Loading<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Port of Discharge<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Place of Delivery<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Place of Receipt<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Final Destination<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Dep\. Port<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' },
    { regex: /<label[^>]*>Dst\. Port<\/label>.*?<select[^>]*>.*?<\/select>/gs, module: 'port', options: '$ports' }
];

files.forEach(f => {
    if (!fs.existsSync(f)) {
        console.log('File not found:', f);
        return;
    }
    let content = fs.readFileSync(f, 'utf8');
    let changed = false;
    
    // We will do a generic replace on the select elements inside those labels
    patterns.forEach(p => {
        content = content.replace(p.regex, (match) => {
            let selectMatch = match.match(/<select([^>]*)>.*?<\/select>/s);
            if (!selectMatch) return match;
            
            let attrs = selectMatch[1];
            let nameMatch = attrs.match(/name="([^"]+)"/);
            let xModelMatch = attrs.match(/x-model="([^"]+)"/);
            
            let nameAttr = nameMatch ? nameMatch[1] : '';
            let xModelAttr = xModelMatch ? xModelMatch[1] : '';
            
            // Generate a default name if missing based on label
            if (!nameAttr && xModelAttr) {
                nameAttr = xModelAttr.split('.').pop();
            } else if (!nameAttr) {
                let labelMatch = match.match(/<label[^>]*>(.*?)<\/label>/);
                if (labelMatch) {
                    nameAttr = labelMatch[1].toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/_+$/, '') + '_id';
                }
            }
            
            let xModelStr = xModelAttr ? `x-model="${xModelAttr}"` : '';
            let newSelect = `<x-inline-select name="${nameAttr}" :options="${p.options}" module="${p.module}" ${xModelStr} class="form-control-gf" />`;
            changed = true;
            return match.replace(selectMatch[0], newSelect);
        });
    });
    
    if (changed) {
        fs.writeFileSync(f, content);
        console.log('Updated:', f);
    }
});
