<x-layout>
    <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="text-sm font-medium text-gray-500 mb-2">
                    <ol class="list-none p-0 inline-flex items-center text-xs">
                        <li><a href="/" class="hover:text-blue-600 transition-colors">Home</a></li>
                        <svg class="h-3 w-3 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <li class="text-gray-800 font-bold">Shipments</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Active Shipments</h1>
            </div>
            
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold px-4 py-2 rounded shadow-sm transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                <button class="bg-[#1a2138] hover:bg-slate-800 text-white text-sm font-bold px-4 py-2 rounded shadow transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Shipment
                </button>
            </div>
        </div>

        <!-- Data Table Wrapper -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative w-full md:w-80">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 bg-gray-50 rounded-md text-sm shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Search orders, clients, origins...">
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <span>Showing</span>
                    <select class="border-gray-200 bg-gray-50 rounded text-sm py-1 pl-2 pr-6">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span>results per page</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest cursor-pointer group">
                                Order ID <svg class="w-3 h-3 inline text-gray-300 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Client</th>
                            <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Origin <span class="mx-1">&rarr;</span> Dest</th>
                            <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest cursor-pointer group">
                                ETA <svg class="w-3 h-3 inline text-gray-300 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 font-medium" id="shipment-list">
                        
                        <!-- Stub Row -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/shipments/SHP-7829-HKG" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition">SHP-7829-HKG</a>
                                <div class="text-[0.65rem] text-gray-500 uppercase mt-0.5">2×40' HC Container</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/crm/clients/1" class="text-sm text-gray-900 font-bold hover:underline">Global Logistics Partners</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 flex items-center font-bold">
                                    HKG <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> LAX
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-[0.65rem] leading-5 font-bold rounded-full bg-teal-200 text-teal-800 uppercase tracking-widest">In Transit</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Nov 02, 2024
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/shipments/SHP-7829-HKG" class="text-[#1a2138] hover:text-blue-600 font-bold">View</a>
                            </td>
                        </tr>
                        
                        <!-- Stub Row 2 -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#" class="text-sm font-bold text-gray-900 hover:text-blue-600 transition">#FGT-98355</a>
                                <div class="text-[0.65rem] text-gray-500 uppercase mt-0.5">LCL Ocean Cargo</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#" class="text-sm text-gray-900 font-bold hover:underline">TechNova Supply</a>
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-[#1a2138] hover:text-blue-600 font-bold">View</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 border-t border-gray-100 px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing <span class="font-bold text-gray-900">1</span> to <span class="font-bold text-gray-900">2</span> of <span class="font-bold text-gray-900">482</span> results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 bg-white border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">Previous</button>
                    <button class="px-3 py-1 bg-white border border-gray-200 rounded text-sm font-bold text-gray-700 hover:text-gray-900 hover:bg-gray-50 shadow-sm">Next</button>
                </div>
            </div>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
             // Logic to populate shipments table via API fetch 
             // GET /api/shipments
        });
    </script>
</x-layout>
