<x-layout>
    <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col md:flex-row md:items-start md:justify-between">
            <div class="flex-1">
                <nav class="text-sm font-medium text-gray-500 mb-2">
                    <ol class="list-none p-0 inline-flex items-center text-xs">
                        <li><a href="/" class="hover:text-blue-600 transition-colors">Home</a></li>
                        <svg class="h-3 w-3 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <li><a href="/shipments" class="hover:text-blue-600 transition-colors">Shipments</a></li>
                        <svg class="h-3 w-3 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <li class="text-gray-800 font-bold" id="shipment-id-crumb">{{ $id }}</li>
                    </ol>
                </nav>
                <div class="flex items-center space-x-4 mb-3">
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight" id="shipment-title">SHP-7829-HKG</h1>
                    <span class="px-3 py-1 bg-teal-200 text-teal-800 text-xs font-bold rounded-full border border-teal-300">
                        <span class="w-1.5 h-1.5 bg-teal-500 rounded-full inline-block mr-1"></span> In Transit - On Time
                    </span>
                </div>
                <div class="flex items-center text-sm font-semibold text-gray-600 space-x-6">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        2×40' HC Container
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                        Maersk Line
                    </div>
                </div>
                <div class="flex items-center text-sm font-bold text-gray-900 mt-2">
                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Hong Kong <svg class="w-3 h-3 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg> Los Angeles
                </div>
            </div>
            
            <div class="flex space-x-3 mt-4 md:mt-0">
                <button class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow-sm transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-5.368m0 5.368l3.89 2.14m-3.89-2.14l3.89-2.14M15 13a3 3 0 100-6 3 3 0 000 6zm0 10a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                    Share Tracking
                </button>
                <button class="bg-[#1a2138] hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2 rounded shadow transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Data
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8">
                <a href="#" class="border-gray-900 text-gray-900 whitespace-nowrap py-4 border-b-2 font-bold text-sm">Overview</a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 border-b-2 font-medium text-sm">Documents</a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 border-b-2 font-medium text-sm">Tracking</a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 border-b-2 font-medium text-sm">Compliance</a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 border-b-2 font-medium text-sm">Financials</a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 border-b-2 font-medium text-sm">Messages</a>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Milestones -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-10">Shipment Milestones</h2>
                    
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t-[3px] border-gray-200"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center" aria-hidden="true" style="width: 50%;">
                            <div class="w-full border-t-[3px] border-teal-600"></div>
                        </div>
                        
                        <ul class="relative flex justify-between">
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center ring-4 ring-white z-10">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="mt-3 text-xs font-bold text-gray-900 text-center">Booked</p>
                                    <p class="mt-0.5 text-[10px] text-gray-500 font-semibold text-center">Oct 12, 09:15</p>
                                </div>
                            </li>
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center ring-4 ring-white z-10">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="mt-3 text-xs font-bold text-gray-900 text-center">Received</p>
                                    <p class="mt-0.5 text-[10px] text-gray-500 font-semibold text-center">Oct 14, 14:30</p>
                                </div>
                            </li>
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center ring-4 ring-white z-10">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="mt-3 text-xs font-bold text-gray-900 text-center">Departed</p>
                                    <p class="mt-0.5 text-[10px] text-gray-500 font-semibold text-center">Oct 15, 22:00</p>
                                </div>
                            </li>
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-white border-2 border-teal-600 flex items-center justify-center ring-4 ring-white z-10 shadow-md">
                                        <svg class="h-5 w-5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <p class="mt-2 text-xs font-bold text-gray-900 text-center">In Transit</p>
                                    <p class="mt-0.5 text-[10px] font-bold text-teal-600 text-center">Live Tracking</p>
                                </div>
                            </li>
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center ring-4 ring-white z-10 mt-1">
                                    </div>
                                    <p class="mt-4 text-xs font-bold text-gray-400 text-center">ETA</p>
                                    <p class="mt-0.5 text-[10px] text-gray-400 font-semibold text-center">Nov 02, 18:00</p>
                                </div>
                            </li>
                            <li>
                                <div class="flex flex-col items-center">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center ring-4 ring-white z-10 mt-1">
                                    </div>
                                    <p class="mt-4 text-xs font-bold text-gray-400 text-center">Delivered</p>
                                    <p class="mt-0.5 text-[10px] text-gray-400 font-semibold text-center">Pending</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-80">
                    <!-- Live Vessel GPS -->
                    <div class="bg-gradient-to-br from-gray-200 to-gray-400 rounded-lg shadow-sm border border-gray-100 relative overflow-hidden flex flex-col h-full">
                        <!-- Dark Map Background Placeholder -->
                        <div class="absolute inset-0 bg-[#bdc3c7]">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/80/World_map_-_low_resolution.svg" class="w-80 h-full object-cover opacity-30 transform scale-150 origin-center" alt="Map">
                            <div class="absolute top-1/2 left-1/2 w-6 h-6 bg-gray-800 rounded-full flex items-center justify-center -translate-x-1/2 -translate-y-1/2 ring-8 ring-gray-800/30">
                                <span class="w-2 h-2 bg-white rounded-full block"></span>
                            </div>
                        </div>
                        
                        <div class="relative z-10 p-4">
                            <div class="bg-white rounded p-3 inline-block shadow-md border border-gray-100">
                                <h3 class="text-[0.65rem] font-bold text-gray-500 uppercase tracking-widest mb-1">Live Vessel GPS</h3>
                                <div class="flex items-center font-bold text-gray-900 text-sm">
                                    <svg class="w-4 h-4 text-teal-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    15.421° N, 121.031° E
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto relative z-10 p-4 flex justify-end">
                            <div class="bg-white/90 backdrop-blur rounded px-3 py-1 shadow-sm text-xs font-semibold text-gray-700">
                                Updated 4m ago
                            </div>
                        </div>
                    </div>

                    <!-- Cargo Specifications -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6 flex flex-col h-full overflow-y-auto">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Cargo Specifications</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                    <span class="text-gray-500 font-semibold text-xs uppercase w-16">Total Weight</span>
                                </div>
                                <span class="font-bold text-sm text-gray-900">48,250 kg</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span class="text-gray-500 font-semibold text-xs uppercase w-16">Total Volume</span>
                                </div>
                                <span class="font-bold text-sm text-gray-900">132.40 CBM</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <span class="text-gray-500 font-semibold text-xs uppercase w-16">Seal Number</span>
                                </div>
                                <span class="font-bold text-sm text-gray-900">MSK-892110-B</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-100 rounded">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span class="text-gray-500 font-semibold text-xs uppercase w-16">Container ID</span>
                                </div>
                                <span class="font-bold text-sm text-gray-900">MRKU 482910</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column -->
            <div class="space-y-6">
                <!-- Command Actions -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Command Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full flex justify-between items-center bg-gray-50 hover:bg-gray-100 border border-gray-100 p-3 rounded shadow-sm transition">
                            <div class="flex items-center font-semibold text-gray-900 text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Generate BOL
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <button class="w-full flex justify-between items-center bg-gray-50 hover:bg-gray-100 border border-gray-100 p-3 rounded shadow-sm transition">
                            <div class="flex items-center font-semibold text-gray-900 text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Upload Document
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <button class="w-full flex justify-between items-center bg-gray-50 hover:bg-gray-100 border border-gray-100 p-3 rounded shadow-sm transition">
                            <div class="flex items-center font-semibold text-gray-900 text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                Contact Carrier
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Document Compliance -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-100 p-6">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-5">Document Compliance</h3>
                    <ul class="space-y-4">
                        <li class="flex justify-between items-start">
                            <div class="flex items-start">
                                <div class="mt-0.5 bg-teal-500 rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0 mr-3">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-none">Bill of Lading</p>
                                    <p class="text-[10px] text-gray-500 mt-1">Verified by Customs on Oct 14</p>
                                </div>
                            </div>
                            <span class="bg-teal-200 text-teal-800 text-[0.60rem] font-bold uppercase tracking-widest px-2 py-0.5 rounded">Valid</span>
                        </li>
                        <li class="flex justify-between items-start">
                            <div class="flex items-start">
                                <div class="mt-0.5 bg-teal-500 rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0 mr-3">
                                    <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-none">AMS Filed</p>
                                    <p class="text-[10px] text-gray-500 mt-1">Transmission successful</p>
                                </div>
                            </div>
                            <span class="bg-teal-200 text-teal-800 text-[0.60rem] font-bold uppercase tracking-widest px-2 py-0.5 rounded">Filed</span>
                        </li>
                        <li class="flex justify-between items-start">
                            <div class="flex items-start">
                                <div class="mt-0.5 border-2 border-red-500 text-red-500 rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0 mr-3">
                                    <span class="text-[10px] font-bold leading-none select-none">!</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-none">Invoice #9012</p>
                                    <p class="text-[10px] text-gray-500 mt-1">Missing digital signature</p>
                                </div>
                            </div>
                            <span class="bg-red-100 text-red-600 border border-red-200 text-[0.60rem] font-bold uppercase tracking-widest px-2 py-0.5 rounded">Missing</span>
                        </li>
                        <li class="flex justify-between items-start">
                            <div class="flex items-start">
                                <div class="mt-0.5 border-2 border-gray-300 rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0 mr-3">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-none">Packing List</p>
                                    <p class="text-[10px] text-gray-500 mt-1">Awaiting carrier upload</p>
                                </div>
                            </div>
                            <span class="bg-gray-100 text-gray-500 border border-gray-200 text-[0.60rem] font-bold uppercase tracking-widest px-2 py-0.5 rounded">Pending</span>
                        </li>
                    </ul>
                </div>

                <!-- Logistics Forecast -->
                <div class="bg-[#1a2138] rounded-lg shadow-md p-6 relative overflow-hidden text-white">
                    <div class="absolute right-0 bottom-0 opacity-10">
                        <svg class="h-24 w-24 transform translate-x-4 translate-y-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-[0.65rem] font-bold text-gray-400 uppercase tracking-widest mb-3">Logistics Forecast</h3>
                        <p class="text-sm leading-relaxed mb-4 text-gray-200">
                            Early arrival predicted. Port congestion at LAX is at 12% capacity. Discharge likely within 24hrs of berthing.
                        </p>
                        <a href="#" class="text-xs font-bold text-white hover:text-white inline-flex items-center border-b border-white hover:border-transparent transition">
                            View Predictive Analytics
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // API Integration hook for /api/shipments/{{ $id }}
            const shipmentId = '{{ $id }}';
            console.log(`Shipment detail API logic ready for ID: ${shipmentId}`);
        });
    </script>
</x-layout>
