<x-layout>
    @push('styles')
    <x-form-styles />
    @endpush

    <div class="page-content" x-data="shippingModule()">

        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li><i class="fa fa-home"></i> <a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
                <li>Warehouse <i class="fa fa-angle-right"></i></li>
                <li><span style="color: #333; font-weight: 700;">Receiving / Shipping</span></li>
            </ul>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <div>
                <h1 class="caption-subject" style="font-size: 18px;">Shipment: #S-992019</h1>
                <div style="font-size: 10px; color: #888; font-weight: 700;">MB/L NO: <span style="color: #3b82f6;">MSC-U-902111-AF</span></div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn-gofreight"><i class="fa fa-paper-plane"></i> POST & SHIP</button>
            </div>
        </div>

        <!-- Main Tabs -->
        <ul class="gf-tabs">
            <template x-for="tab in tabs" :key="tab.id">
                <li :class="activeTab === tab.id ? 'active' : ''" @click="activeTab = tab.id">
                    <a x-text="tab.name"></a>
                </li>
            </template>
        </ul>

        <div style="padding-bottom: 50px;">
            <!-- BASIC TAB -->
            <div x-show="activeTab === 'basic'" style="display: grid; grid-template-columns: 3fr 1fr; gap: 10px;">
                <div class="space-y-5">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <span class="caption-subject"><i class="fa fa-barcode"></i> Manifested SKU Content</span>
                            <button @click="addSKU" class="btn-gofreight" style="padding: 2px 10px; font-size: 10px;"><i class="fa fa-plus"></i> ADD SKU</button>
                        </div>
                        <div class="portlet-body" style="padding: 0;">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align: center;">...</th>
                                        <th>Part No / SKU</th>
                                        <th>Product Description</th>
                                        <th style="width: 60px; text-align: center;">Qty</th>
                                        <th style="width: 80px;">Unit</th>
                                        <th style="width: 100px;">G.W (KG)</th>
                                        <th style="width: 80px;">CBM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(sku, index) in skus" :key="index">
                                        <tr @click="openSKUModal(index)">
                                            <td style="text-align: center;"><i class="fa fa-edit" style="color:#3b82f6; cursor:pointer;"></i></td>
                                            <td style="font-weight: 700; color: #4b77be; font-family: monospace;" x-text="sku.part_no"></td>
                                            <td x-text="sku.description"></td>
                                            <td style="text-align: center; font-weight: 700;" x-text="sku.qty"></td>
                                            <td x-text="sku.unit"></td>
                                            <td x-text="sku.gw"></td>
                                            <td style="font-weight: 700; color: #4b77be;" x-text="sku.cbm"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <span class="caption-subject"><i class="fa fa-truck"></i> Carrier Intel</span>
                        </div>
                        <div class="portlet-body">
                            <div style="margin-bottom: 12px;">
                                <label class="form-label-gf">TRUCK B/L #</label>
                                <input type="text" value="TBL-FL-902-11" class="form-control-gf">
                            </div>
                            <div>
                                <label class="form-label-gf">HAULIER</label>
                                <input type="text" value="Evergreen Drayage" class="form-control-gf">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCOUNTING TAB -->
            <div x-show="activeTab === 'accounting'" class="portlet light">
                <div class="portlet-title">
                    <span class="caption-subject"><i class="fa fa-calculator"></i> Financial Consolidation</span>
                </div>
                <div class="portlet-body" style="text-align: center; color: #8e9eae; padding: 40px;">
                    <i class="fa fa-info-circle" style="font-size: 32px; margin-bottom: 15px;"></i>
                    <h4 style="font-weight: 700; color: #333;">No records generated yet.</h4>
                    <p style="font-size: 11px;">Shipment manifests must be finalized to generate automated billing entries.</p>
                </div>
            </div>

            <!-- STATUS TAB -->
            <div x-show="activeTab === 'status'" class="portlet light">
                <div class="portlet-title">
                    <span class="caption-subject"><i class="fa fa-history"></i> Audit Trail</span>
                </div>
                <div class="portlet-body">
                    <div style="border-left: 2px solid #e7ecf1; margin-left: 15px; padding-left: 20px;">
                        <template x-for="log in logs" :key="log.time">
                            <div style="margin-bottom: 20px; position: relative;">
                                <div style="position: absolute; left: -26px; top: 0; width: 10px; height: 10px; background: #4b77be; border-radius: 50%;"></div>
                                <div style="font-size: 10px; color: #8e9eae; font-weight: 700;" x-text="log.time"></div>
                                <div style="font-size: 12px; font-weight: 700; color: #333;" x-text="log.action"></div>
                                <div style="font-size: 9px; color: #888;" x-text="'OPERATOR: ' + log.user"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- SKU MODAL -->
        <div x-show="showModal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000;" x-cloak>
            <div class="portlet light" style="width: 500px; margin: 0;">
                <div class="portlet-title" style="background: #111;">
                    <span class="caption-subject" style="color: #fff;">Modify SKU Manifest</span>
                    <i @click="showModal = false" class="fa fa-times" style="color: #fff; cursor: pointer;"></i>
                </div>
                <div class="portlet-body">
                    <div style="margin-bottom: 15px;">
                        <label class="form-label-gf">Part Number / SKU</label>
                        <input type="text" x-model="editingSku.part_no" class="form-control-gf">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label class="form-label-gf">Manifest Description</label>
                        <textarea x-model="editingSku.description" class="form-control-gf" style="height: 60px;"></textarea>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button @click="saveSKU" class="btn-gofreight" style="flex: 1;">SAVE CHANGES</button>
                        <button @click="showModal = false" class="btn-default-gf" style="flex: 1;">CANCEL</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function shippingModule() {
            return {
                activeTab: 'basic',
                showModal: false,
                editingIndex: null,
                editingSku: { part_no: '', description: '', qty: 1, unit: 'PCS', gw: 0, cbm: '0.00' },
                tabs: [
                    { id: 'basic', name: 'Basic (SKU Manifest)' },
                    { id: 'accounting', name: 'Accounting Control' },
                    { id: 'status', name: 'System Status' }
                ],
                skus: [
                    { part_no: 'TR-9021-001X', description: 'HIGH PERFORMANCE GPU CHIPSET V2', qty: 50, unit: 'PCS', gw: 25.5, cbm: '0.12' },
                    { part_no: 'PS-1102-M9', description: 'MODULAR POWER SUPPLY 850W GOLD', qty: 120, unit: 'PCS', gw: 180.2, cbm: '0.45' }
                ],
                logs: [
                    { time: '2023-11-23 11:45 AM', action: 'Shipment manifest finalized', user: 'David Chen (WH-9)' },
                    { time: '2023-11-23 10:20 AM', action: 'SKU Inventory inbound scan complete', user: 'Maria Garcia (Scan-02)' },
                    { time: '2023-11-23 09:15 AM', action: 'Shipment Record Created from Master B/L', user: 'System Auto-Processor' }
                ],
                addSKU() { this.skus.push({ part_no: 'NEW-SKU', description: 'Pending...', qty: 1, unit: 'PCS', gw: 0, cbm: '0.00' }); },
                openSKUModal(index) {
                    this.editingIndex = index;
                    this.editingSku = { ...this.skus[index] };
                    this.showModal = true;
                },
                saveSKU() {
                    if (this.editingIndex !== null) this.skus[this.editingIndex] = { ...this.editingSku };
                    this.showModal = false;
                }
            }
        }
    </script>
</x-layout>
