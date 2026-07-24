import re

with open('resources/views/air-export/new-booking.blade.php', 'r') as f:
    content = f.read()

old_stepper = """                    <!-- Stepper -->
                    <div class="stepper-container">
                        <div class="step-item" :class="step >= 1 ? 'active' : ''">
                            <div class="step-circle" x-text="step > 1 ? '✓' : '1'"></div>
                            <span class="step-label">Select Quotation</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item" :class="step >= 2 ? 'active' : ''">
                            <div class="step-circle" x-text="step > 2 ? '✓' : '2'"></div>
                            <span class="step-label">Fill Shipment Data</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item" :class="step >= 3 ? 'active' : ''">
                            <div class="step-circle" x-text="step > 3 ? '✓' : '3'"></div>
                            <span class="step-label">Select Invoice Items</span>
                        </div>
                    </div>"""

new_stepper = """                    <style>
                        .wizard-circle { width: 18px; height: 18px; min-width: 18px; min-height: 18px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: bold; }
                    </style>
                    <!-- Wizard Steps Header -->
                    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px; margin-top: 15px;">
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 1 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="step > 1"><i class="fa fa-check"></i></template>
                                <template x-if="step === 1"><span>1</span></template>
                            </div>
                            <span :style="step >= 1 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select Quotation</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 2 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <template x-if="step > 2"><i class="fa fa-check"></i></template>
                                <template x-if="step <= 2"><span>2</span></template>
                            </div>
                            <span :style="step >= 2 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Fill Shipment Data</span>
                        </div>
                        <div style="height: 1px; width: 20px; background: #e2e8f0;"></div>

                        <div style="display: flex; align-items: center; gap: 5px;">
                            <div class="wizard-circle" :style="step >= 3 ? 'background: #3b82f6;' : 'background: #cbd5e1;'">
                                <span>3</span>
                            </div>
                            <span :style="step >= 3 ? 'color: #1e293b; font-size: 10px; font-weight: 600;' : 'color: #94a3b8; font-size: 10px;'">Select Invoice Items</span>
                        </div>
                    </div>"""

content = content.replace(old_stepper, new_stepper)

with open('resources/views/air-export/new-booking.blade.php', 'w') as f:
    f.write(content)

print("Air Export Booking stepper patched.")
