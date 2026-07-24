<x-layout>
    @push('styles')
    <x-list-styles />
    <style>
        .portlet-body { padding: 0 !important; }

        .jr-table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .jr-table th { background: #f8fafc; color: #475569; font-weight: 600; border-bottom: 1px solid #cbd5e1; border-right: 1px solid #e2e8f0; border-top: 1px solid #cbd5e1; padding: 3px 4px; white-space: nowrap; height: 24px; position: sticky; top: 0; z-index: 10; text-align: left; user-select: none; }
        .jr-table td { padding: 2px 3px; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; white-space: nowrap; height: 24px; vertical-align: middle; color: #334155; overflow: hidden; }
        .jr-table tr:hover td { background: #f8fafc; }
        .jr-table tr.row-selected td { background: #eff6ff; }

        .jr-table .col-chk { width: 24px; text-align: center; border-left: 3px solid #cbd5e1; }
        .jr-table .col-no { width: 32px; text-align: center; }
        .jr-table .col-gl { width: 175px; }
        .jr-table .col-sub { width: 48px; }
        .jr-table .col-type { width: 92px; }
        .jr-table .col-entity { width: 140px; }
        .jr-table .col-desc { width: 130px; }
        .jr-table .col-office { width: 72px; }
        .jr-table .col-amt { width: 78px; text-align: right; }
        .jr-table .col-cur { width: 68px; }
        .jr-table .col-rate { width: 52px; text-align: right; }

        .jr-table input[type="checkbox"] { width: 13px; height: 13px; margin: 0; cursor: pointer; accent-color: #3b82f6; vertical-align: middle; }
        .jr-table input[type="text"],
        .jr-table select { height: 20px; border: 1px solid transparent; font-size: 10px; padding: 0 3px; border-radius: 2px; color: #334155; background: transparent; width: 100%; box-sizing: border-box; outline: none; font-family: inherit; }
        .jr-table input[type="text"]:focus,
        .jr-table select:focus { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 1px rgba(59,130,246,0.15); }
        .jr-table select { cursor: pointer; appearance: auto; }
        .jr-table input[type="text"][readonly] { background: #f1f5f9; color: #64748b; }
        .jr-table .num { font-family: 'Courier New', monospace; text-align: right; }

        .jr-table tfoot td { background: #f8fafc; font-weight: 700; border-top: 1px solid #cbd5e1; }

        .jr-bottom { display: flex; align-items: flex-start; gap: 24px; padding: 10px 12px; border-top: 1px solid #e2e8f0; background: #fff; }
        .jr-field { display: flex; flex-direction: column; gap: 2px; }
        .jr-field label { font-size: 11px; font-weight: 600; color: #334155; }
        .jr-field label .req { color: #ef4444; }
        .jr-field input { height: 22px; border: 1px solid #cbd5e1; padding: 0 6px; font-size: 11px; border-radius: 2px; color: #334155; outline: none; font-family: inherit; }
        .jr-field input:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,0.1); }
        .jr-field input[readonly] { background: #f1f5f9; color: #64748b; cursor: default; }
        .jr-field input[type="date"] { width: 140px; }
        .jr-field .entry-no { width: 160px; }
        .jr-field .remark-input { width: 100%; min-width: 300px; }

        .jr-savebar { display: flex; gap: 10px; padding: 12px; background: #fff; justify-content: center; border-top: 1px solid #e2e8f0; }
        .jr-btn-save { height: 32px; padding: 0 28px; border: none; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.15s; }
        .jr-btn-save.blue { background: #3b82f6; color: #fff; }
        .jr-btn-save.blue:hover { background: #2563eb; }
        .jr-btn-save.gray { background: #e2e8f0; color: #334155; border: 1px solid #cbd5e1; }
        .jr-btn-save.gray:hover { background: #cbd5e1; }

        .empty-msg { text-align: center; padding: 20px; color: #94a3b8; font-size: 11px; }

        .loading-overlay { display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .loading-overlay.active { display: flex; }
        .loading-spinner { width: 36px; height: 36px; border: 3px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
    @endpush

    <div class="toast-container" id="toast-container"></div>

    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Accounting <i class="fa fa-angle-right"></i></li>
                <li>Journal <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">Journal Entry</span></li>
            </ul>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption" style="display:flex;align-items:center;gap:8px;">
                    <span class="caption-subject">New Journal Entry</span>
                </div>
                <div class="actions" style="display:flex;gap:4px;align-items:center;">
                    <span style="font-size:10px;color:#64748b;font-weight:600;" id="entry-no-display">{{ $nextEntryNo }}</span>
                </div>
            </div>

            <div class="portlet-tool">
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <div class="btn-group">
                        <button class="btn-tool green" id="btnAddRow" title="Add Row"><i class="fa fa-plus"></i></button>
                        <button class="btn-tool" id="btnDelRow" title="Delete Selected Rows" disabled><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="btn-group">
                        <button class="btn-tool" id="btnBalanced" style="padding:0 10px;"><i class="fa fa-plus-circle"></i> Balanced Entry</button>
                        <button class="btn-tool" id="btnImport" style="padding:0 10px;"><i class="fa fa-upload"></i> Import Journal</button>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <span style="font-size:10px;color:#64748b;" id="line-count">0 lines</span>
                </div>
            </div>

            <div style="width:100%;overflow-x:auto;background:#fff;">
                <table class="jr-table" id="jTable">
                    <thead>
                        <tr>
                            <th class="col-chk"><input type="checkbox" id="selAll"></th>
                            <th class="col-no">No.</th>
                            <th class="col-gl">G/L Account</th>
                            <th class="col-sub">Sub</th>
                            <th class="col-type">Type</th>
                            <th class="col-entity">Entity (Customer)</th>
                            <th class="col-desc">Description</th>
                            <th class="col-office">Office</th>
                            <th class="col-amt num">Local Dr</th>
                            <th class="col-amt num">Local Cr</th>
                            <th class="col-cur">Currency</th>
                            <th class="col-rate num">Rate</th>
                            <th class="col-amt num">Foreign Dr</th>
                            <th class="col-amt num">Foreign Cr</th>
                        </tr>
                    </thead>
                    <tbody id="jBody">
                        <tr id="emptyRow">
                            <td colspan="14" class="empty-msg">Click <i class="fa fa-plus" style="color:#3b82f6;"></i> to add a line</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="col-chk"></td>
                            <td class="col-no"></td>
                            <td class="col-gl"></td>
                            <td class="col-sub"></td>
                            <td class="col-type"></td>
                            <td class="col-entity"></td>
                            <td class="col-desc"></td>
                            <td class="col-office" style="text-align:right;font-weight:700;">Total</td>
                            <td class="col-amt num" id="tLD" style="color:#2563eb;">0.00</td>
                            <td class="col-amt num" id="tLC" style="color:#2563eb;">0.00</td>
                            <td class="col-cur"></td>
                            <td class="col-rate"></td>
                            <td class="col-amt num" id="tFD">0.00</td>
                            <td class="col-amt num" id="tFC">0.00</td>
                        </tr>
                        <tr>
                            <td class="col-chk"></td>
                            <td class="col-no"></td>
                            <td class="col-gl"></td>
                            <td class="col-sub"></td>
                            <td class="col-type"></td>
                            <td class="col-entity"></td>
                            <td class="col-desc"></td>
                            <td class="col-office" style="text-align:right;font-weight:700;">Balance</td>
                            <td class="col-amt num" id="balAmt" style="color:#22c55e;" colspan="6">0.00</td>
                            <td class="col-amt"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="jr-bottom">
                <div class="jr-field">
                    <label style="font-size:10px;font-weight:600;color:#475569;display:block;margin-bottom:2px;"><span style="color:#ef4444;">*</span> Date</label>
                    <input type="date" id="fDate" value="{{ date('Y-m-d') }}">
                </div>
                <div class="jr-field">
                    <label style="font-size:10px;font-weight:600;color:#475569;display:block;margin-bottom:2px;">Entry No</label>
                    <input type="text" id="fEntryNo" value="{{ $nextEntryNo }}" readonly class="entry-no">
                </div>
                <div class="jr-field" style="flex:1;">
                    <label style="font-size:10px;font-weight:600;color:#475569;display:block;margin-bottom:2px;">Remark</label>
                    <input type="text" id="fRemark" class="remark-input" placeholder="Enter remark...">
                </div>
            </div>

            <div class="jr-savebar">
                <button type="button" class="jr-btn-save blue" id="btnSave"><i class="fa fa-check"></i> Save</button>
                <button type="button" class="jr-btn-save gray" id="btnSaveNew">Save &amp; Create Another</button>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="ldg"><div class="loading-spinner"></div></div>

    <script>
    (function(){
        var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var offices = @json($offices);
        var currencies = @json($currencies);
        var partners = @json($partners);
        var glAccounts = @json($glAccounts);
        var rowNum = 0;

        function $(sel, ctx) { return (ctx || document).querySelector(sel); }
        function $$(sel, ctx) { return (ctx || document).querySelectorAll(sel); }

        function toast(type, msg) {
            var c = document.getElementById('toast-container');
            var t = document.createElement('div');
            t.className = 'toast ' + type;
            t.textContent = msg;
            c.appendChild(t);
            setTimeout(function(){ t.remove(); }, 4000);
        }
        function showLdg() { document.getElementById('ldg').classList.add('active'); }
        function hideLdg() { document.getElementById('ldg').classList.remove('active'); }
        function fmt(n) { return Number(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
        function esc(s) { if(!s)return''; var d=document.createElement('div'); d.appendChild(document.createTextNode(String(s))); return d.innerHTML; }

        function removeEmptyMsg() {
            var er = document.getElementById('emptyRow');
            if (er) er.remove();
        }

        function addEmptyMsg() {
            if ($$('#jBody tr').length === 0) {
                var er = document.createElement('tr');
                er.id = 'emptyRow';
                er.innerHTML = '<td colspan="14" class="empty-msg">Click <i class="fa fa-plus" style="color:#3b82f6;"></i> to add a line</td>';
                $('#jBody').appendChild(er);
            }
        }

        function glOpts(selId) {
            var h = '<option value="">-- Select G/L --</option>';
            glAccounts.forEach(function(a){ h += '<option value="'+a.id+'"'+(selId&&selId==a.id?' selected':'')+'>'+esc(a.code)+' - '+esc(a.name)+'</option>'; });
            return h;
        }
        function officeOpts(selId) {
            var h = '<option value="">--</option>';
            offices.forEach(function(o){ h += '<option value="'+o.id+'"'+(selId&&selId==o.id?' selected':'')+'>'+esc(o.code||o.name)+'</option>'; });
            return h;
        }
        function curOpts(selId) {
            var h = '<option value="">--</option>';
            currencies.forEach(function(c){ h += '<option value="'+c.id+'"'+(selId&&selId==c.id?' selected':'')+'>'+esc(c.code)+'</option>'; });
            return h;
        }
        function entityOpts(sel) {
            var opts = ['COMPANY','BANK'];
            var h = '';
            opts.forEach(function(o){ h += '<option value="'+o+'"'+(sel===o?' selected':'')+'>'+o+'</option>'; });
            return h;
        }
        function partnerOpts(selId) {
            var h = '<option value="">--</option>';
            partners.forEach(function(p){ h += '<option value="'+p.id+'"'+(selId&&selId==p.id?' selected':'')+'>'+esc(p.name)+'</option>'; });
            return h;
        }

        function addLine(d) {
            removeEmptyMsg();
            rowNum++;
            d = d || {};
            var tr = document.createElement('tr');
            tr.dataset.row = rowNum;
            tr.innerHTML =
                '<td class="col-chk"><input type="checkbox" class="rchk"></td>' +
                '<td class="col-no" style="text-align:center;font-weight:600;color:#64748b;">'+rowNum+'</td>' +
                '<td class="col-gl"><select class="gl-sel">'+glOpts(d.gl_id)+'</select></td>' +
                '<td class="col-sub"><input type="text" class="f-sub" value="'+esc(d.sub||'')+'" maxlength="50"></td>' +
                '<td class="col-type"><select class="f-etype">'+entityOpts(d.entity_type)+'</select></td>' +
                '<td class="col-entity"><select class="f-partner">'+partnerOpts(d.partner_id)+'</select></td>' +
                '<td class="col-desc"><input type="text" class="f-desc" value="'+esc(d.desc||'')+'"></td>' +
                '<td class="col-office"><select class="f-office">'+officeOpts(d.office_id)+'</select></td>' +
                '<td class="col-amt"><input type="text" class="num f-ld" value="'+esc(d.ld||'')+'" style="text-align:right"></td>' +
                '<td class="col-amt"><input type="text" class="num f-lc" value="'+esc(d.lc||'')+'" style="text-align:right"></td>' +
                '<td class="col-cur"><select class="f-cur">'+curOpts(d.cur_id)+'</select></td>' +
                '<td class="col-rate"><input type="text" class="num f-rate" value="'+(d.rate||'1')+'" style="text-align:right"></td>' +
                '<td class="col-amt"><input type="text" class="num f-fd" value="'+esc(d.fd||'')+'" style="text-align:right"></td>' +
                '<td class="col-amt"><input type="text" class="num f-fc" value="'+esc(d.fc||'')+'" style="text-align:right"></td>';
            $('#jBody').appendChild(tr);
            wireRow(tr);
            updateCount();
            recalc();
        }

        function wireRow(tr) {
            var chk = $('.rchk', tr);
            chk.addEventListener('change', function(){
                tr.classList.toggle('row-selected', this.checked);
                updateDelBtn();
            });
            tr.addEventListener('click', function(e){
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'A' || e.target.tagName === 'BUTTON') return;
                chk.checked = !chk.checked;
                chk.dispatchEvent(new Event('change'));
            });
            $('.f-ld', tr).addEventListener('input', recalc);
            $('.f-lc', tr).addEventListener('input', recalc);
            $('.f-fd', tr).addEventListener('input', recalc);
            $('.f-fc', tr).addEventListener('input', recalc);
        }

        function recalc() {
            var ld=0,lc=0,fd=0,fc=0;
            $$('#jBody tr').forEach(function(tr){
                if (tr.id === 'emptyRow') return;
                ld += parseFloat($('.f-ld',tr).value)||0;
                lc += parseFloat($('.f-lc',tr).value)||0;
                fd += parseFloat($('.f-fd',tr).value)||0;
                fc += parseFloat($('.f-fc',tr).value)||0;
            });
            $('#tLD').textContent = fmt(ld);
            $('#tLC').textContent = fmt(lc);
            $('#tFD').textContent = fmt(fd);
            $('#tFC').textContent = fmt(fc);
            var bal = ld - lc;
            $('#balAmt').textContent = fmt(Math.abs(bal));
            $('#balAmt').style.color = Math.abs(bal)<0.01 ? '#22c55e' : '#ef4444';
        }

        function renum() {
            var rows = $$('#jBody tr');
            var idx = 0;
            rows.forEach(function(tr){
                if (tr.id === 'emptyRow') return;
                idx++;
                tr.dataset.row = idx;
                $$('.col-no', tr)[0].textContent = idx;
            });
            rowNum = idx;
        }

        function updateCount() {
            var n = $$('#jBody tr').length;
            var empty = document.getElementById('emptyRow');
            if (empty) n--;
            $('#line-count').textContent = n + ' line' + (n !== 1 ? 's' : '');
        }

        function updateDelBtn() {
            var n = $$('.rchk:checked').length;
            $('#btnDelRow').disabled = n === 0;
        }

        function gatherData() {
            var lines = [];
            $$('#jBody tr').forEach(function(tr){
                if (tr.id === 'emptyRow') return;
                var glId = $('.gl-sel',tr).value;
                var ld = parseFloat($('.f-ld',tr).value)||0;
                var lc = parseFloat($('.f-lc',tr).value)||0;
                lines.push({
                    gl_account_id: glId || null,
                    sub: $('.f-sub',tr).value||null,
                    entity_type: $('.f-etype',tr).value,
                    trade_partner_id: $('.f-partner',tr).value||null,
                    description: $('.f-desc',tr).value||null,
                    office_id: $('.f-office',tr).value||null,
                    local_debit: ld,
                    local_credit: lc,
                    currency_id: $('.f-cur',tr).value||null,
                    foreign_rate: parseFloat($('.f-rate',tr).value)||1,
                    foreign_debit: parseFloat($('.f-fd',tr).value)||0,
                    foreign_credit: parseFloat($('.f-fc',tr).value)||0,
                });
            });
            return {
                entry_date: $('#fDate').value,
                remark: $('#fRemark').value||null,
                office_id: offices.length ? offices[0].id : null,
                lines: lines
            };
        }

        function doSave(andAnother) {
            var data = gatherData();
            if (!data.lines.length) { toast('error','Please add at least one line.'); return; }
            var missingIdx = -1;
            data.lines.some(function(l, i){
                if (!l.gl_account_id) { missingIdx = i; return true; }
                return false;
            });
            if (missingIdx !== -1) { toast('error','Please select a G/L account for line ' + (missingIdx + 1) + '.'); return; }
            var tDr = data.lines.reduce(function(s,l){return s+l.local_debit},0);
            var tCr = data.lines.reduce(function(s,l){return s+l.local_credit},0);
            if (Math.abs(tDr-tCr) > 0.01) { toast('error','Total debit must equal total credit.'); return; }
            showLdg();
            fetch('{{ route("accounting.journal.entry.store") }}',{
                method:'POST',
                headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json','Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
                body: JSON.stringify(data)
            })
            .then(function(r){return r.json();})
            .then(function(resp){
                hideLdg();
                if(!resp.success){toast('error',resp.message||'Save failed.');return;}
                toast('success',resp.message||'Saved!');
                if(andAnother){resetForm();}else{window.location='{{ route("accounting.journal.entry") }}';}
            })
            .catch(function(){hideLdg();toast('error','Network error.');});
        }

        function resetForm(){
            $('#jBody').innerHTML='';
            rowNum=0;
            $('#fRemark').value='';
            $('#fDate').value='{{ date("Y-m-d") }}';
            updateCount();
            addEmptyMsg();
            fetch('{{ route("accounting.next-entry-no") }}',{headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}})
                .then(function(r){return r.json();})
                .then(function(d){$('#fEntryNo').value=d.entry_no;$('#entry-no-display').textContent=d.entry_no;});
        }

        $('#btnAddRow').addEventListener('click', function(){ addLine(); });
        $('#btnDelRow').addEventListener('click', function(){
            var checked = $$('.rchk:checked');
            if (!checked.length) { toast('error','Please select row(s) to delete.'); return; }
            checked.forEach(function(cb){ cb.closest('tr').remove(); });
            renum();
            recalc();
            updateCount();
            addEmptyMsg();
            updateDelBtn();
        });
        $('#btnBalanced').addEventListener('click', function(){
            addLine({ld:'0.00',lc:'0.00'});
            addLine({ld:'0.00',lc:'0.00'});
        });
        $('#btnImport').addEventListener('click', function(){ toast('info','Import Journal coming soon.'); });
        $('#selAll').addEventListener('change', function(){
            var c=this.checked; $$('.rchk').forEach(function(cb){cb.checked=c;cb.closest('tr').classList.toggle('row-selected',c);});
            updateDelBtn();
        });
        $('#btnSave').addEventListener('click', function(){ doSave(false); });
        $('#btnSaveNew').addEventListener('click', function(){ doSave(true); });
    })();
    </script>
</x-layout>
