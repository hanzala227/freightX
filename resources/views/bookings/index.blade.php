<x-layout>
    <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="text-sm font-medium text-gray-500 mb-2">
                    <ol class="list-none p-0 inline-flex items-center">
                        <li><a href="/" class="hover:text-gray-900">Dashboard</a></li>
                        <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <li class="text-gray-800 font-bold">Bookings</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Booking Management</h1>
            </div>
            
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button class="bg-[#1a2138] hover:bg-slate-800 text-white text-sm font-bold px-4 py-2 rounded shadow transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Booking
                </button>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-100 overflow-hidden min-h-[400px] flex items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="mt-2 text-sm font-bold text-gray-900">No bookings scheduled</h3>
                <p class="mt-1 text-sm text-gray-500">API endpoints active, await incoming bookings.</p>
            </div>
        </div>

    </div>
</x-layout>
