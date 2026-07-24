<?php
$file = __DIR__ . '/routes/web.php';
$content = file_get_contents($file);

$old = "        Route::get('/quotation/list', function () { return view('sales.quotation.list'); });";

$new = "        Route::get('/quotation/list', function () {
            \$statusColors = [
                'Draft' => '#888', 'Sent' => '#3498db', 'Pending' => '#f1c40f',
                'Won' => '#1abc9c', 'Lost' => '#e74c3c', 'Expired' => '#e74c3c',
                'Cancelled' => '#e85a5a', 'Ghosted' => '#e85a5a',
            ];

            \$query = \\App\\Models\\Quotation::with(['customer', 'agent', 'salesPerson', 'pol', 'pod', 'items']);

            if (request('search')) {
                \$s = request('search');
                \$query->where(function (\$q) use (\$s) {
                    \$q->where('quote_no', 'like', \"%{\$s}%\")
                      ->orWhere('commodity', 'like', \"%{\$s}%\")
                      ->orWhereHas('customer', fn (\$q) => \$q->where('name', 'like', \"%{\$s}%\"));
                });
            }
            if (request('quote_no')) \$query->where('quote_no', 'like', '%' . request('quote_no') . '%');
            if (request('date')) \$query->whereDate('created_at', request('date'));
            if (request('status')) \$query->where('status', request('status'));
            if (request('customer')) \$query->whereHas('customer', fn (\$q) => \$q->where('name', 'like', '%' . request('customer') . '%'));
            if (request('agent')) \$query->whereHas('agent', fn (\$q) => \$q->where('name', 'like', '%' . request('agent') . '%'));
            if (request('term')) \$query->where('service_term', 'like', '%' . request('term') . '%');
            if (request('type')) \$query->where('transport_mode', 'like', '%' . request('type') . '%');
            if (request('pol')) \$query->whereHas('pol', fn (\$q) => \$q->where('name', 'like', '%' . request('pol') . '%'));
            if (request('pod')) \$query->whereHas('pod', fn (\$q) => \$q->where('name', 'like', '%' . request('pod') . '%'));

            \$quotes = \$query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

            if (request('export') === 'csv') {
                \$headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=\"quotations.csv\"'];
                \$handle = fopen('php://output', 'w');
                fputcsv(\$handle, ['Quote No.', 'Create Date', 'Status', 'Customer', 'Agent', 'Service Term', 'Shipping Type', 'POL', 'POD']);
                foreach (\$quotes as \$q) {
                    fputcsv(\$handle, [
                        \$q->quote_no, \$q->created_at?->format('Y-m-d'), \$q->status,
                        \$q->customer->name ?? '', \$q->agent?->name ?? '', \$q->service_term ?? '',
                        \$q->transport_mode ?? '', \$q->pol?->name ?? '', \$q->pod?->name ?? '',
                    ]);
                }
                fclose(\$handle);
                return response('', 200, \$headers);
            }

            return view('sales.quotation.list', compact('quotes', 'statusColors'));
        })->name('sales.quotations.list');";

if (strpos($content, $old) === false) {
    echo "ERROR: Could not find the exact string to replace.\n";
    echo "Searching for partial match...\n";
    $pos = strpos($content, "Route::get('/quotation/list'");
    if ($pos !== false) {
        $end = strpos($content, ");", $pos) + 2;
        echo "Found at position $pos, ends at $end\n";
        echo "Exact content: " . substr($content, $pos, $end - $pos) . "\n";
        exit(1);
    }
    echo "Not found even with partial match.\n";
    exit(1);
}

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
echo "Route replaced successfully.\n";
