<x-layout>
    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .btn-print {
            background-color: #5b9bd1;
            color: #fff;
            border: none;
            padding: 8px 24px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 2px;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0.8;
        }
        .btn-print:hover {
            opacity: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-print:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        input[type="checkbox"], input[type="radio"] {
            accent-color: #4b77be;
        }
        
        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 12px 16px;
            min-width: 300px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid #32c5d2;
        }
        .toast.success { border-left-color: #36c6d3; }
        .toast.error { border-left-color: #e7505a; }
        .toast.warning { border-left-color: #f1c40f; }
        .toast.info { border-left-color: #3598dc; }
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    @endpush

    <div style="background: #eef1f5; min-height: 100vh; padding: 15px;" x-data="containerStorageApp()" x-cloak>
        <!-- Breadcrumb -->
        <div style="font-size: 11px; color: #8e9eae; margin-bottom: 15px;">
            <a href="/" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';" target="_blank"><i class="fa fa-home"></i> Home</a> <i class="fa fa-angle-right" style="margin: 0 5px; opacity: 0.5;"></i> 
            <a href="/report" style="color: #8e9eae; text-decoration: none; transition: color 0.15s;" onmouseover="this.style.color='#337ab7';" onmouseout="this.style.color='#8e9eae';">Reports</a> <i class="fa fa-angle-right" style="margin: 0 5px; opacity: 0.5;"></i> 
            <span style="color: #333; font-weight: 700;">Container Storage Report</span>
        </div>

        <!-- Main Portlet -->
        <div class="portlet box" style="background: #fff; border: 1px solid #e7ecf1; border-radius: 4px; overflow: hidden;">
            <div style="background: #4b4b4b; padding: 10px 15px; color: #fff; font-size: 14px; font-weight: 600;">
                Container Storage Report
            </div>
            
            <div class="portlet-body" style="padding: 15px;">
                <form @submit.prevent="generateReport">
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <colgroup>
                            <col style="width: 12%;">
                            <col style="width: 88%;">
                        </colgroup>
                        <tbody>
                            <!-- Period -->
                            <tr style="border: 1px solid #e7ecf1;">
                                <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                    <span style="color: #ed6b75; margin-right: 3px;">*</span>Period
                                </td>
                                <td style="padding: 10px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <input type="date" x-model="filters.date_from" style="border: 1px solid #c2cad8; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;" required>
                                        <span style="font-size: 11px; color: #666;">to</span>
                                        <input type="date" x-model="filters.date_to" style="border: 1px solid #c2cad8; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;" required>
                                    </div>
                                </td>
                            </tr>

                            <tr style="height: 10px;"><td></td><td></td></tr>

                            <!-- Department Type -->
                            <tr style="border: 1px solid #e7ecf1;">
                                <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                    <span style="color: #ed6b75; margin-right: 3px;">*</span>Department Type
                                </td>
                                <td style="padding: 10px; font-size: 11px; color: #333;">
                                    <template x-for="(dept, idx) in departments" :key="dept">
                                        <label style="margin-right: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                            <input type="checkbox" x-model="filters.departments" :value="dept" style="margin: 0; width: 13px; height: 13px;"> 
                                            <span x-text="dept"></span>
                                        </label>
                                    </template>
                                </td>
                            </tr>

                            <tr style="height: 10px;"><td></td><td></td></tr>

                            <!-- Office -->
                            <tr style="border: 1px solid #e7ecf1;">
                                <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">Office</td>
                                <td style="padding: 10px;">
                                    <select x-model="filters.office_id" style="width: 200px; border: 1px solid #c2cad8; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;">
                                        <option value="">All</option>
                                        <template x-for="office in offices" :key="office.id">
                                            <option :value="office.id" x-text="office.name"></option>
                                        </template>
                                    </select>
                                </td>
                            </tr>

                            <tr style="height: 10px;"><td></td><td></td></tr>

                            <!-- Party -->
                            <tr style="border: 1px solid #e7ecf1;">
                                <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">
                                    <span style="color: #ed6b75; margin-right: 3px;">*</span>Party
                                </td>
                                <td style="padding: 10px;">
                                    <div style="margin-bottom: 8px;">
                                        <label style="margin-right: 15px; cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 5px;">
                                            <input type="radio" name="party_type" value="customer" x-model="filters.party_type"> Customer
                                        </label>
                                        <label style="cursor: pointer; font-size: 11px; display: inline-flex; align-items: center; gap: 5px;">
                                            <input type="radio" name="party_type" value="oversea_agent" x-model="filters.party_type"> Oversea Agent
                                        </label>
                                    </div>
                                    <div style="position: relative; width: 250px;">
                                        <select x-model="filters.party_id" style="width: 100%; border: 1px solid #c2cad8; padding: 4px 8px; font-size: 11px; height: 28px; border-radius: 2px;" required>
                                            <option value="">Select...</option>
                                            <template x-for="agent in filteredAgents" :key="agent.id">
                                                <option :value="agent.id" x-text="agent.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div x-show="filteredAgents.length === 0 && agents.length > 0" style="font-size: 10px; color: #e74c3c; margin-top: 4px;">
                                        No <span x-text="filters.party_type === 'customer' ? 'customers' : 'oversea agents'"></span> found in the database
                                    </div>
                                </td>
                            </tr>

                            <tr style="height: 10px;"><td></td><td></td></tr>

                            <!-- View Option -->
                            <tr style="border: 1px solid #e7ecf1;">
                                <td style="background: #eef1f5; padding: 10px; font-size: 11px; font-weight: 700; color: #575962; text-transform: uppercase; border-right: 1px solid #e7ecf1;">View Option</td>
                                <td style="padding: 10px; font-size: 11px; color: #333;">
                                    <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                        <input type="checkbox" x-model="filters.show_without_start_date" style="margin: 0; width: 13px; height: 13px;"> 
                                        Show containers without storage Start Date
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                    <div style="padding-bottom: 10px;">
                        <button type="submit" class="btn-print" :disabled="loading">
                            <span x-show="!loading">Print</span>
                            <span x-show="loading">Generating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <script>
        function containerStorageApp() {
            return {
                loading: false,
                offices: [],
                agents: [],
                departments: ['Ocean Import', 'Ocean Export', 'Trucker', 'Misc'],
                
                filters: {
                    date_from: '{{ Carbon\Carbon::now()->startOfMonth()->format("Y-m-d") }}',
                    date_to: '{{ Carbon\Carbon::now()->endOfMonth()->format("Y-m-d") }}',
                    departments: ['Ocean Import', 'Ocean Export', 'Trucker', 'Misc'],
                    office_id: '',
                    party_type: 'customer',
                    party_id: '',
                    show_without_start_date: false
                },
                
                get filteredAgents() {
                    if (this.filters.party_type === 'customer') {
                        return this.agents.filter(a => a.is_customer);
                    } else {
                        return this.agents.filter(a => a.is_oversea_agent);
                    }
                },
                
                init() {
                    this.loadDropdownOptions();
                },
                
                async loadDropdownOptions() {
                    try {
                        console.log('Loading dropdown options...');
                        const [offices, agents] = await Promise.all([
                            fetch('/api/dropdown-options/offices').then(r => {
                                console.log('Offices response status:', r.status);
                                return r.json();
                            }).catch(e => {
                                console.error('Offices fetch error:', e);
                                return { data: [] };
                            }),
                            fetch('/api/dropdown-options/agents').then(r => {
                                console.log('Agents response status:', r.status);
                                return r.json();
                            }).catch(e => {
                                console.error('Agents fetch error:', e);
                                return { data: [] };
                            })
                        ]);
                        
                        this.offices = offices.data || offices || [];
                        this.agents = agents.data || agents || [];
                        
                        console.log('Loaded offices:', this.offices.length);
                        console.log('Loaded agents:', this.agents.length);
                        console.log('Sample agent:', this.agents[0]);
                        console.log('Filtered agents (customer):', this.filteredAgents.length);
                    } catch (e) {
                        console.error('Failed to load dropdown options:', e);
                        showToast('error', 'Failed to load dropdown options');
                    }
                },
                
                async generateReport() {
                    // Validation
                    if (this.filters.departments.length === 0) {
                        showToast('warning', 'Please select at least one department type');
                        return;
                    }
                    
                    if (!this.filters.party_id) {
                        showToast('warning', 'Please select a ' + (this.filters.party_type === 'customer' ? 'customer' : 'oversea agent'));
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        // Build query parameters
                        const params = new URLSearchParams();
                        params.append('date_from', this.filters.date_from);
                        params.append('date_to', this.filters.date_to);
                        params.append('departments', JSON.stringify(this.filters.departments));
                        if (this.filters.office_id) params.append('office_id', this.filters.office_id);
                        params.append('party_type', this.filters.party_type);
                        params.append('party_id', this.filters.party_id);
                        params.append('show_without_start_date', this.filters.show_without_start_date ? '1' : '0');
                        
                        // Open report in new window
                        const reportUrl = `/report/container-storage/print?${params.toString()}`;
                        window.open(reportUrl, '_blank', 'width=1200,height=800');
                        
                        showToast('success', 'Report opened in new window');
                    } catch (e) {
                        console.error('Failed to generate report:', e);
                        showToast('error', 'Failed to generate report');
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
        
        // Toast Notification System
        function showToast(type, msg) {
            const icons = { 
                success: 'check-circle', 
                error: 'times-circle', 
                info: 'info-circle', 
                warning: 'exclamation-triangle' 
            };
            
            const container = document.getElementById('toast-container') || (() => {
                const c = document.createElement('div');
                c.id = 'toast-container';
                c.className = 'toast-container';
                document.body.appendChild(c);
                return c;
            })();
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fa fa-${icons[type]}" style="font-size: 18px; color: ${type === 'success' ? '#36c6d3' : type === 'error' ? '#e7505a' : type === 'warning' ? '#f1c40f' : '#3598dc'};"></i>
                <span style="flex: 1; font-size: 12px; color: #333;">${msg}</span>
                <i class="fa fa-times" style="cursor: pointer; opacity: 0.5; font-size: 14px;" onclick="this.parentElement.remove()"></i>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => toast.remove(), 300);
            }, 7000);
        }
    </script>
</x-layout>
