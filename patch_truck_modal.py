import re

with open('resources/views/truck/create.blade.php', 'r') as f:
    content = f.read()

# Replace the outer shell of the modal
old_header = """        <div class="modal-backdrop" x-show="showQuoteModal" style="display: none;"></div>
        <div class="modal" x-show="showQuoteModal" style="display: none;">
            <div class="modal-dialog" style="width: 800px; max-width: 95%;" @click.stop>
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Load Quotation Data</h4>
                        <button type="button" class="close-btn" @click="closeQuoteModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>"""

new_header = """        <div x-show="showQuoteModal" class="modal-overlay" style="display:none;" x-cloak>
            <div class="modal-container" style="max-width: 950px; width: 95%; max-height: 95vh; display: flex; flex-direction: column; background: #fff; border-radius: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);" @click.stop>
                <div class="modal-header" style="padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 300; font-size: 18px; color: #777;">Load Quotation Data</span>
                    <i class="fa fa-times cursor-pointer text-gray-400 hover:text-gray-600" @click="closeQuoteModal()" style="font-size: 16px; cursor: pointer;"></i>
                </div>"""

content = content.replace(old_header, new_header)

old_body_start = """                    <div class="modal-body">"""
new_body_start = """                <div class="modal-body hide-scrollbar" style="padding: 15px 20px; background: #fff; overflow-y: auto; flex: 1;">
                    <style>
                        .hide-scrollbar::-webkit-scrollbar { display: none; }
                        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
                        .modal-body .table-custom th, .modal-body .table-custom td { padding: 3px 6px !important; font-size: 11px !important; }
                        .modal-body .form-label-gf { font-size: 11px !important; }
                        .modal-body h4 { font-size: 13px !important; margin-bottom: 6px !important; }
                        .modal-body .form-control-gf { height: 20px !important; font-size: 11px !important; padding: 0 4px !important; }
                        .wizard-circle { width: 24px; height: 24px; min-width: 24px; min-height: 24px; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: bold; }
                    </style>"""

content = content.replace(old_body_start, new_body_start)

old_footer = """                    <div class="modal-footer">
                        <button type="button" class="btn-default-gf dark" style="padding: 6px 12px; font-size: 12px;" @click="closeQuoteModal()">Cancel</button>
                        <button type="button" x-show="quoteStep > 1" class="btn-default-gf" style="padding: 6px 15px; font-size: 13px; background: #e5e5e5; border: none; color: #333;" @click="quoteStep--">Back</button>
                        <button type="button" x-show="quoteStep === 1" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" :disabled="!quoteSearch.selected_id" @click="loadSelectedQuote()">Next</button>
                        <button type="button" x-show="quoteStep === 2" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="quoteStep++">Next</button>
                        <button type="button" x-show="quoteStep === 3" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="confirmQuoteSelection()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>"""

new_footer = """                </div>
                <div class="modal-footer" style="padding: 15px 20px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 8px; background: #f8f9fa; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px;">
                    <button type="button" class="btn-default-gf dark" style="padding: 6px 15px; font-size: 13px; background: #fff; border: 1px solid #ddd; color: #333;" @click="closeQuoteModal()">Cancel</button>
                    <button type="button" x-show="quoteStep > 1" class="btn-default-gf" style="padding: 6px 15px; font-size: 13px; background: #e5e5e5; border: none; color: #333;" @click="quoteStep--">Back</button>
                    <button type="button" x-show="quoteStep === 1" class="btn-tool" :style="!quoteSearch.selected_id ? 'padding: 6px 15px; font-size: 13px; background: #ccc; border: none; color: #666; cursor: not-allowed; opacity: 0.7;' : 'padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;'" :disabled="!quoteSearch.selected_id" @click="loadSelectedQuote()">Next</button>
                    <button type="button" x-show="quoteStep === 2" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="quoteStep++">Next</button>
                    <button type="button" x-show="quoteStep === 3" class="btn-tool" style="padding: 6px 15px; font-size: 13px; background: #36c6d3; border: none; color: #fff;" @click="confirmQuoteSelection()">Confirm</button>
                </div>
            </div>
        </div>"""

content = content.replace(old_footer, new_footer)

with open('resources/views/truck/create.blade.php', 'w') as f:
    f.write(content)

print("Modal patched.")
