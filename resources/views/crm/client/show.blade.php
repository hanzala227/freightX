<x-layout>
    <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <nav class="text-sm font-medium text-gray-500 mb-2">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="/crm" class="hover:text-gray-900">CRM</a>
                        <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li class="text-gray-800">Customer Profile</li>
                </ol>
            </nav>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight" id="client-name">Global Logistics Partners Inc.</h1>
                    <span class="px-3 py-1 bg-teal-200 text-teal-800 text-xs font-bold rounded-full uppercase tracking-widest">Premium Client</span>
                </div>
                <div class="flex space-x-3">
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded-md transition-colors">Edit Profile</button>
                    <button class="bg-[#1a2138] hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded-md transition-colors">New Order</button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Account Info -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6 flex flex-col items-center sm:items-start text-center sm:text-left">
                    <div class="flex items-center space-x-4 mb-4 font-sans w-full">
                        <div class="bg-[#1a2138] w-16 h-16 rounded flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold tracking-widest">GLP</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">GLP Global</h2>
                            <p class="text-sm text-gray-500">Account ID: GLP-0042-HQ</p>
                        </div>
                    </div>
                    <ul class="space-y-4 w-full">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Headquarters</p>
                                <p class="text-sm text-gray-500 mt-0.5">1200 Logistics Way, Suite 400<br>Rotterdam, Netherlands 3011</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <div>
                                <p class="text-xs font-bold text-gray-900">Account Manager</p>
                                <div class="flex items-center mt-1">
                                    <img src="https://i.pravatar.cc/150?img=5" class="w-6 h-6 rounded-full mr-2">
                                    <p class="text-sm text-gray-500">Sarah Chen (Senior Director)</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Financial Status -->
                <div class="bg-[#1a2138] shadow-md rounded-lg p-6 text-white grid gap-4 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10">
                        <svg class="h-40 w-40 transform translate-x-8 -translate-y-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Financial Status</h3>
                        <p class="text-sm text-gray-300">Credit Limit</p>
                        <p class="text-3xl font-bold mt-1">$500,000.00</p>
                        
                        <div class="mt-4">
                            <div class="w-full bg-slate-700 rounded-full h-1">
                                <div class="bg-teal-500 h-1 rounded-full" style="width: 82%"></div>
                            </div>
                            <p class="text-[0.65rem] text-gray-400 mt-1">82% of credit utilized</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Balance Due</p>
                                <p class="text-lg font-bold">$45,210</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">LTV Total</p>
                                <p class="text-lg font-bold text-teal-400">$3.2M</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center border-t border-slate-700 pt-4 mt-4">
                            <p class="text-xs text-gray-400">Avg. Profit Margin</p>
                            <p class="text-sm font-bold text-teal-400">18.4%</p>
                        </div>
                    </div>
                </div>

                <!-- Document Vault -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900">Document Vault</h3>
                        <a href="#" class="text-xs font-bold text-gray-900 hover:text-blue-600 flex items-center">
                            View All <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between p-3 border border-gray-100 rounded bg-gray-50 hover:bg-gray-100 transition shadow-sm">
                            <div class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                MSA_Contract_2024.pdf
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-900 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </li>
                        <li class="flex items-center justify-between p-3 border border-gray-100 rounded bg-gray-50 hover:bg-gray-100 transition shadow-sm">
                            <div class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001z" clip-rule="evenodd"></path></svg>
                                Compliance_KYC_Cert.zip
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-900 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </li>
                        <li class="flex items-center justify-between p-3 border border-gray-100 rounded bg-gray-50 hover:bg-gray-100 transition shadow-sm">
                            <div class="flex items-center text-sm font-semibold text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                Billing_Terms_Final.pdf
                            </div>
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-900 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-2 space-y-6 flex flex-col">
                <!-- Active Shipments -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 flex-1">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Active Shipments</h2>
                        <div class="relative w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-1.5 border-none bg-gray-50 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search orders...">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Order ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Origin/Dest</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">ETA</th>
                                    <th scope="col" class="px-6 py-3 text-right text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Value</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100 font-medium">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">#FGT-98210</div>
                                        <div class="text-[0.65rem] text-gray-500 uppercase mt-0.5">Standard Air Freight</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center font-bold">
                                            SHG <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> ROT
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-[0.65rem] leading-5 font-bold rounded-full bg-teal-200 text-teal-800 uppercase tracking-widest">In Transit</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Oct 24, 2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        $12,450.00
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">#FGT-98355</div>
                                        <div class="text-[0.65rem] text-gray-500 uppercase mt-0.5">LCL Ocean Cargo</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center font-bold">
                                            LAX <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> HAM
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-[0.65rem] leading-5 font-bold rounded-full bg-orange-100 text-orange-600 uppercase tracking-widest border border-orange-200">Delayed</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Nov 02, 2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        $8,120.00
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap border-b-2 border-transparent">
                                        <div class="text-sm font-bold text-gray-900">#FGT-98442</div>
                                        <div class="text-[0.65rem] text-gray-500 uppercase mt-0.5">Expedited Road</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 flex items-center font-bold">
                                            AMS <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> PAR
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-[0.65rem] leading-5 font-bold rounded-full bg-teal-200 text-teal-800 uppercase tracking-widest">In Transit</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Oct 21, 2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                        $2,800.00
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 border-t border-gray-100 p-3 text-center">
                        <a href="#" class="text-xs font-bold text-gray-600 hover:text-gray-900 uppercase tracking-widest transition-colors">View All Shipments</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1">
                    <!-- Quote History -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6 flex flex-col h-full">
                        <div class="flex items-center mb-6">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h3 class="font-bold text-gray-900">Quote History</h3>
                        </div>
                        <ul class="space-y-4 flex-1">
                            <li class="flex justify-between items-center group">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition">Ocean Freight EXW</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Ref: Q-88219</p>
                                </div>
                                <span class="px-2 py-0.5 text-[0.65rem] font-bold rounded bg-teal-100 text-teal-800 uppercase">Accepted</span>
                            </li>
                            <li class="flex justify-between items-center group pt-2 border-t border-gray-50">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition">Express Courier</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Ref: Q-88342</p>
                                </div>
                                <span class="px-2 py-0.5 text-[0.65rem] font-bold rounded bg-red-50 text-red-600 uppercase border border-red-100">Expired</span>
                            </li>
                            <li class="flex justify-between items-center group pt-2 border-t border-gray-50">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition">Road Haulage (3x FTL)</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Ref: Q-88401</p>
                                </div>
                                <span class="px-2 py-0.5 text-[0.65rem] font-bold rounded bg-gray-100 text-gray-600 uppercase border border-gray-200 tracking-wider">Pending</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Communications -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6 flex flex-col h-full">
                        <div class="flex items-center mb-6">
                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            <h3 class="font-bold text-gray-900">Communications</h3>
                        </div>
                        <div class="flex-1 relative">
                            <div class="absolute left-[5px] top-2 bottom-0 w-px bg-gray-200"></div>
                            <ul class="space-y-5">
                                <li class="relative pl-5">
                                    <div class="absolute left-0 top-1.5 w-[11px] h-[11px] rounded-full bg-gray-300 ring-4 ring-white"></div>
                                    <p class="text-sm font-bold text-gray-900">Call Log</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Discussed annual volume discount for Q4.</p>
                                    <p class="text-[0.60rem] font-bold text-gray-400 mt-1 uppercase tracking-wider">2 Hours Ago &bull; Sarah Chen</p>
                                </li>
                                <li class="relative pl-5 pt-1">
                                    <div class="absolute left-0 top-2.5 w-[11px] h-[11px] rounded-full bg-gray-300 ring-4 ring-white"></div>
                                    <p class="text-sm font-bold text-gray-900">Email Sent</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Rate confirmation for Rotterdam route.</p>
                                    <p class="text-[0.60rem] font-bold text-gray-400 mt-1 uppercase tracking-wider">Yesterday &bull; Automated System</p>
                                </li>
                                <li class="relative pl-5 pt-1 border-b-2 border-transparent">
                                    <div class="absolute left-0 top-2.5 w-[11px] h-[11px] rounded-full bg-teal-500 ring-4 ring-white"></div>
                                    <p class="text-sm font-bold text-gray-900">Meeting</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Onboarding for the new global portal.</p>
                                    <p class="text-[0.60rem] font-bold text-gray-400 mt-1 uppercase tracking-wider">Oct 18, 2024 &bull; James Wilson</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <!-- API Integration Stub -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // This is purely API based.
            // In a real scenario, this would fetch data from `/api/clients/{{ $id }}`.
            const clientId = '{{ $id }}';
            console.log(`Fetching data for Client ${clientId} from purely API endpoints...`);
            
            // fetch(`/api/clients/${clientId}`)
            //  .then(res => res.json())
            //  .then(data => {
            //      document.getElementById('client-name').innerText = data.name;
            //  });
        });
    </script>
</x-layout>
