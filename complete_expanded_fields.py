#!/usr/bin/env python3
"""
Script to complete all expanded row fields (28+ fields total)
"""

file_path = "/home/muhammad-hanzala/Downloads/shuwarma (3)/fms (2)/app (4) Backup with kiro/resources/views/air-import/index.blade.php"

# Read the file
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find the placeholder comment and replace with full fields
old_placeholder = '''                                                    </div>
                                                    <!-- More columns will be added in next phase -->
                                                </div>'''

# Full expanded row fields (all 3 columns complete)
full_fields = '''                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">D.G</label>
                                                            <div class="form-input-container">
                                                                <select x-model="charge.dg" class="form-control-gf" style="font-size: 11px;">
                                                                    <option value="No">No</option>
                                                                    <option value="Yes">Yes</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Unit</label>
                                                            <div class="form-input-container">
                                                                <select x-model="charge.unit" class="form-control-gf" style="font-size: 11px;">
                                                                    <option value="">Select...</option>
                                                                    <option value="KG">KG</option>
                                                                    <option value="LB">LB</option>
                                                                    <option value="CBM">CBM</option>
                                                                    <option value="CFT">CFT</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Temp</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.temp" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Vent</label>
                                                            <div class="form-input-container">
                                                                <select x-model="charge.vent" class="form-control-gf" style="font-size: 11px;">
                                                                    <option value="">Select...</option>
                                                                    <option value="Open">Open</option>
                                                                    <option value="Closed">Closed</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Storage Start</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.storage_start_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Storage End</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.storage_end_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Column 2 -->
                                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Carrier Release</label>
                                                            <div class="form-input-container">
                                                                <input type="checkbox" x-model="charge.carrier_release" style="width: 14px; height: 14px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Yard Location</label>
                                                            <div class="form-input-container">
                                                                <input type="text" x-model="charge.yard_location" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Unload from Vessel</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.unload_vessel_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Gate In</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.gate_in_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Rail Start</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.rail_start_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Place of Delivery ETA</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.pod_eta_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Available for Pickup</label>
                                                            <div class="form-input-container">
                                                                <input type="checkbox" x-model="charge.available_pickup" style="width: 14px; height: 14px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 130px;">Weight (LB)</label>
                                                            <div class="form-input-container">
                                                                <input type="number" x-model="charge.weight_lb" class="form-control-gf" style="font-size: 11px;" step="0.01">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Column 3 -->
                                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Appt.</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.appt_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Trucker</label>
                                                            <div class="form-input-container">
                                                                <select x-model="charge.trucker_id" class="form-control-gf" style="font-size: 11px;">
                                                                    <option value="">Select...</option>
                                                                    @foreach($truckers as $trucker)
                                                                        <option value="{{ $trucker->id }}">{{ $trucker->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Pick Up</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.pickup_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Gate Out</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.pickup_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">F.Dest ETA</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.fdest_eta_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">ETA Door</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.eta_door_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">ATA Door</label>
                                                            <div class="form-input-container">
                                                                <input type="date" x-model="charge.ata_door_date" class="form-control-gf" style="font-size: 11px;">
                                                            </div>
                                                        </div>
                                                        <div class="form-group-gf" style="margin-bottom: 0;">
                                                            <label class="form-label-gf" style="width: 120px;">Measurement (CFT)</label>
                                                            <div class="form-input-container">
                                                                <input type="number" x-model="charge.measurement_cft" class="form-control-gf" style="font-size: 11px;" step="0.01">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Remarks & Additional Fields -->
                                                <div style="margin-top: 15px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                                    <div>
                                                        <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 5px;">Remarks</label>
                                                        <textarea x-model="charge.remarks" class="form-control-gf" style="height: 50px; font-size: 11px; resize: vertical;"></textarea>
                                                    </div>
                                                    <div>
                                                        <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 5px;">Internal Remarks</label>
                                                        <textarea x-model="charge.internal_remarks" class="form-control-gf" style="height: 50px; font-size: 11px; resize: vertical;"></textarea>
                                                    </div>
                                                </div>
                                                <div style="margin-top: 10px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                                                    <div class="form-group-gf" style="margin-bottom: 0;">
                                                        <label class="form-label-gf" style="width: 130px;">Empty Confirmed</label>
                                                        <div class="form-input-container">
                                                            <input type="date" x-model="charge.empty_confirmed_date" class="form-control-gf" style="font-size: 11px;">
                                                        </div>
                                                    </div>
                                                    <div class="form-group-gf" style="margin-bottom: 0;">
                                                        <label class="form-label-gf" style="width: 120px;">Empty Return</label>
                                                        <div class="form-input-container">
                                                            <input type="date" x-model="charge.empty_return_date" class="form-control-gf" style="font-size: 11px;">
                                                        </div>
                                                    </div>
                                                    <div class="form-group-gf" style="margin-bottom: 0;">
                                                        <label class="form-label-gf" style="width: 100px;">Complete</label>
                                                        <div class="form-input-container">
                                                            <input type="checkbox" x-model="charge.complete" style="width: 14px; height: 14px;">
                                                        </div>
                                                    </div>
                                                </div>'''

if old_placeholder in content:
    content = content.replace(old_placeholder, full_fields, 1)
    print("✓ Added all 28+ expanded fields")
    
    # Write back
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print("✓ Expandable rows complete!")
else:
    print("ERROR: Could not find placeholder")
