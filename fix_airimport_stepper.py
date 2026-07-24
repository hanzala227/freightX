import re

with open('resources/views/air-import/index.blade.php', 'r') as f:
    content = f.read()

old_stepper = """                        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wizard-circle" :style="quoteStep >= 1 ? 'background: #36c6d3;' : 'background: #999;'">
                                    <template x-if="quoteStep > 1"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep === 1"><span>1</span></template>
                                </div>
                                <span :style="quoteStep >= 1 ? 'color: #333; font-size: 12px;' : 'color: #999; font-size: 12px;'">Select Quotation</span>
                            </div>
                            <div style="height: 1px; width: 30px; background: #ddd;"></div>
                            
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wizard-circle" :style="quoteStep >= 2 ? 'background: #36c6d3;' : 'background: #999;'">
                                    <template x-if="quoteStep > 2"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep <= 2"><span>2</span></template>
                                </div>
                                <span :style="quoteStep >= 2 ? 'color: #333; font-size: 12px;' : 'color: #999; font-size: 12px;'">Fill in shipment data</span>
                            </div>
                            <div style="height: 1px; width: 30px; background: #ddd;"></div>
                            
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wizard-circle" :style="quoteStep >= 3 ? 'background: #36c6d3;' : 'background: #999;'">
                                    <span>3</span>
                                </div>
                                <span :style="quoteStep >= 3 ? 'color: #333; font-size: 12px;' : 'color: #999; font-size: 12px;'">Select invoice items</span>
                            </div>
                        </div>"""

new_stepper = """                    <!-- Wizard Steps Header -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 1 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="quoteStep > 1"><i class="fa fa-check"></i></template>
                                <template x-if="quoteStep === 1"><span>1</span></template>
                            </div>
                            <span :style="quoteStep >= 1 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select Quotation</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 2 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="quoteStep > 2"><i class="fa fa-check"></i></template>
                                <template x-if="quoteStep <= 2"><span>2</span></template>
                            </div>
                            <span :style="quoteStep >= 2 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Fill in shipment data</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="quoteStep >= 3 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <span>3</span>
                            </div>
                            <span :style="quoteStep >= 3 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select invoice items</span>
                        </div>
                    </div>"""

content = content.replace(old_stepper, new_stepper)

content = content.replace('.wizard-circle { width: 24px; height: 24px; min-width: 24px; min-height: 24px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: bold; }', 
                          '.wizard-circle { width: 18px; height: 18px; min-width: 18px; min-height: 18px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; }')

with open('resources/views/air-import/index.blade.php', 'w') as f:
    f.write(content)

print("Air Import stepper patched.")
