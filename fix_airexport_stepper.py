import re

with open('resources/views/air-export/create.blade.php', 'r') as f:
    content = f.read()

old_stepper = """                    <!-- Stepper -->
                    <div style="display: flex; justify-content: center; margin-bottom: 30px; position: relative;">
                        <div style="display: flex; align-items: center; gap: 15px; z-index: 1; background: #fff; padding: 0 10px;">
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div style="width: 24px; height: 24px; border-radius: 50% !important; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: 600;" :style="quoteStep === 1 ? 'background: #1abc9c; color: #fff;' : 'background: #e5e5e5; color: #999;'">1</div>
                                <span :style="quoteStep === 1 ? 'color: #333; font-weight: 600; font-size: 12px;' : 'color: #999; font-size: 12px;'">Select Quotation</span>
                            </div>
                            <div style="width: 40px; height: 1px; background: #e5e5e5;"></div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div style="width: 24px; height: 24px; border-radius: 50% !important; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: 600;" :style="quoteStep === 2 ? 'background: #1abc9c; color: #fff;' : (quoteStep > 2 ? 'background: #1abc9c; color: #fff;' : 'background: #e5e5e5; color: #999;')">2</div>
                                <span :style="quoteStep === 2 ? 'color: #333; font-weight: 600; font-size: 12px;' : (quoteStep > 2 ? 'color: #333; font-weight: 600; font-size: 12px;' : 'color: #999; font-size: 12px;')">Fill in shipment data</span>
                            </div>
                            <div style="width: 40px; height: 1px; background: #e5e5e5;"></div>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <div style="width: 24px; height: 24px; border-radius: 50% !important; display: flex; justify-content: center; align-items: center; font-size: 12px; font-weight: 600;" :style="quoteStep === 3 ? 'background: #1abc9c; color: #fff;' : 'background: #e5e5e5; color: #999;'">3</div>
                                <span :style="quoteStep === 3 ? 'color: #333; font-weight: 600; font-size: 12px;' : 'color: #999; font-size: 12px;'">Select invoice items</span>
                            </div>
                        </div>
                    </div>"""

new_stepper = """                    <style>
                        .wizard-circle { width: 18px; height: 18px; min-width: 18px; min-height: 18px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; }
                    </style>
                    <!-- Wizard Steps Header -->
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

with open('resources/views/air-export/create.blade.php', 'w') as f:
    f.write(content)

print("Air Export stepper patched.")
