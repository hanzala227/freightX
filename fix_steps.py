import re

with open('resources/views/truck/create.blade.php', 'r') as f:
    content = f.read()

old_steps = """                        <div class="step-container">
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 1 ? 'active' : ''">
                                    <template x-if="quoteStep > 1"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep === 1"><span>1</span></template>
                                </div>
                                <div class="step-title" :style="quoteStep >= 1 ? 'color:#333' : 'color:#999'">Select Quotation</div>
                            </div>
                            <div class="step-divider" :class="quoteStep > 1 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 2 ? 'active' : ''">
                                    <template x-if="quoteStep > 2"><i class="fa fa-check"></i></template>
                                    <template x-if="quoteStep <= 2"><span>2</span></template>
                                </div>
                                <div class="step-title" :style="quoteStep >= 2 ? 'color:#333' : 'color:#999'">Fill in shipment data</div>
                            </div>
                            <div class="step-divider" :class="quoteStep > 2 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="quoteStep >= 3 ? 'active' : ''"><span>3</span></div>
                                <div class="step-title" :style="quoteStep >= 3 ? 'color:#333' : 'color:#999'">Select invoice items</div>
                            </div>
                        </div>"""

new_steps = """                        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 15px;">
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

content = content.replace(old_steps, new_steps)

with open('resources/views/truck/create.blade.php', 'w') as f:
    f.write(content)

print("Steps patched.")
