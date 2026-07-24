import re

with open('resources/views/truck/create.blade.php', 'r') as f:
    content = f.read()

# Fix Memo Modal Wrapper
old_memo_wrapper = """    <div class="modal-backdrop" x-show="memoModalOpen" style="display: none;"></div>
    <div class="modal" x-show="memoModalOpen" style="display: none;">
        <div class="modal-dialog" style="width:500px;" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" x-text="memoEditIndex === -1 ? 'Add Memo' : 'Edit Memo'"></h4>
                    <button type="button" class="close-btn" @click="memoModalOpen = false">&times;</button>
                </div>"""

new_memo_wrapper = """    <div x-show="memoModalOpen" class="modal-overlay" style="display:none;" x-cloak>
        <div class="modal-container" style="width: 500px; max-width: 95%; max-height: 95vh; display: flex; flex-direction: column; background: #fff; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);" @click.stop>
            <div class="modal-header" style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 300; font-size: 18px; color: #777;" x-text="memoEditIndex === -1 ? 'Add Memo' : 'Edit Memo'"></span>
                <i class="fa fa-times cursor-pointer text-gray-400 hover:text-gray-600" @click="memoModalOpen = false" style="font-size: 16px; cursor: pointer;"></i>
            </div>"""

content = content.replace(old_memo_wrapper, new_memo_wrapper)

old_memo_footer = """                    <div class="modal-footer" style="padding-top:10px;text-align:right;">
                        <button type="button" class="btn-default-gf dark" style="padding: 5px 15px; font-size: 12px; border-radius:3px;" @click="memoModalOpen = false">Cancel</button>
                        <button type="submit" class="btn-tool" style="padding: 5px 15px; font-size: 12px; border-radius:3px; background:#36c6d3; color:#fff; border:none;">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>"""

new_memo_footer = """                </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 8px; background: #f8f9fa; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;">
                <button type="button" class="btn-default-gf dark" style="padding: 6px 15px; font-size: 13px; background: #fff; border: 1px solid #ddd; color: #333;" @click="memoModalOpen = false">Cancel</button>
                <button type="submit" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;">Save</button>
            </div>
            </form>
        </div>
    </div>"""

content = content.replace(old_memo_footer, new_memo_footer)

# Fix Charge Modal Wrapper
old_charge_wrapper = """    <div class="modal-backdrop" x-show="chargeModalOpen" style="display: none;"></div>
    <div class="modal" x-show="chargeModalOpen" style="display: none;">
        <div class="modal-dialog" style="width:650px;" @click.stop>
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" x-text="chargeForm.is_dc_note ? 'Create D/C Note' : (chargeForm.type === 'AR' ? 'Create Invoice' : 'Create Cost')"></h4>
                    <button type="button" class="close-btn" @click="chargeModalOpen = false">&times;</button>
                </div>"""

new_charge_wrapper = """    <div x-show="chargeModalOpen" class="modal-overlay" style="display:none;" x-cloak>
        <div class="modal-container" style="width: 650px; max-width: 95%; max-height: 95vh; display: flex; flex-direction: column; background: #fff; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);" @click.stop>
            <div class="modal-header" style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 300; font-size: 18px; color: #777;" x-text="chargeForm.is_dc_note ? 'Create D/C Note' : (chargeForm.type === 'AR' ? 'Create Invoice' : 'Create Cost')"></span>
                <i class="fa fa-times cursor-pointer text-gray-400 hover:text-gray-600" @click="chargeModalOpen = false" style="font-size: 16px; cursor: pointer;"></i>
            </div>"""

content = content.replace(old_charge_wrapper, new_charge_wrapper)

old_charge_steps = """                        <!-- Steps Indicator -->
                        <div class="step-container" style="margin-bottom: 20px;">
                            <div class="step">
                                <div class="step-id" :class="chargeStep >= 1 ? 'active' : ''"><span>1</span></div>
                                <div class="step-title">Charge Info</div>
                            </div>
                            <div class="step-divider" :class="chargeStep > 1 ? 'active' : ''"></div>
                            <div class="step">
                                <div class="step-id" :class="chargeStep >= 2 ? 'active' : ''"><span>2</span></div>
                                <div class="step-title">Amounts</div>
                            </div>
                        </div>"""

new_charge_steps = """                        <!-- Steps Indicator -->
                        <div style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wizard-circle" :style="chargeStep >= 1 ? 'background: #36c6d3;' : 'background: #999;'">
                                    <template x-if="chargeStep > 1"><i class="fa fa-check"></i></template>
                                    <template x-if="chargeStep === 1"><span>1</span></template>
                                </div>
                                <span :style="chargeStep >= 1 ? 'color: #333; font-size: 12px;' : 'color: #999; font-size: 12px;'">Charge Info</span>
                            </div>
                            <div style="height: 1px; width: 30px; background: #ddd;"></div>
                            
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="wizard-circle" :style="chargeStep >= 2 ? 'background: #36c6d3;' : 'background: #999;'">
                                    <span>2</span>
                                </div>
                                <span :style="chargeStep >= 2 ? 'color: #333; font-size: 12px;' : 'color: #999; font-size: 12px;'">Amounts</span>
                            </div>
                        </div>"""

content = content.replace(old_charge_steps, new_charge_steps)

old_charge_footer = """                    <div class="modal-footer" style="padding-top:10px;text-align:right;">
                        <button type="button" class="btn-default-gf dark" style="padding: 5px 15px; font-size: 12px; border-radius:3px;" @click="chargeModalOpen = false">Cancel</button>
                        <button type="button" x-show="chargeStep === 2" class="btn-default-gf" style="padding: 5px 15px; font-size: 12px; border-radius:3px;" @click="chargeStep = 1">Back</button>
                        <button type="button" x-show="chargeStep === 1" class="btn-tool" style="padding: 5px 15px; font-size: 12px; border-radius:3px; background:#36c6d3; color:#fff; border:none;" @click="chargeStep = 2">Next</button>
                        <button type="submit" x-show="chargeStep === 2" class="btn-tool" style="padding: 5px 15px; font-size: 12px; border-radius:3px; background:#36c6d3; color:#fff; border:none;">Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>"""

new_charge_footer = """                </div>
            <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 8px; background: #f8f9fa; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;">
                <button type="button" class="btn-default-gf dark" style="padding: 6px 15px; font-size: 13px; background: #fff; border: 1px solid #ddd; color: #333;" @click="chargeModalOpen = false">Cancel</button>
                <button type="button" x-show="chargeStep === 2" class="btn-default-gf" style="padding: 6px 15px; font-size: 13px; background: #e5e5e5; border: none; color: #333;" @click="chargeStep = 1">Back</button>
                <button type="button" x-show="chargeStep === 1" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="chargeStep = 2">Next</button>
                <button type="submit" x-show="chargeStep === 2" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;">Save</button>
            </div>
            </form>
        </div>
    </div>"""

content = content.replace(old_charge_footer, new_charge_footer)


with open('resources/views/truck/create.blade.php', 'w') as f:
    f.write(content)

print("Modals patched.")
