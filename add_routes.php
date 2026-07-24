<?php
$f = file_get_contents('routes/web.php');
$insert = "\n    // Quotation bulk & inline operations\n    Route::post('/quotations/bulk-delete', [QuotationController::class, 'bulkDelete']);\n    Route::post('/quotations/bulk-status', [QuotationController::class, 'bulkUpdateStatus']);\n    Route::patch('/quotations/{id}/color', [QuotationController::class, 'updateColor']);\n    Route::patch('/quotations/{id}/status', [QuotationController::class, 'updateSingleStatus']);\n";
$search = "require __DIR__.'/auth.php';";
$pos = strrpos($f, $search);
if ($pos !== false) {
    $f = substr($f, 0, $pos) . $insert . "\n" . substr($f, $pos);
    file_put_contents('routes/web.php', $f);
    echo "Routes added successfully.\n";
} else {
    echo "ERROR: Could not find insertion point.\n";
    exit(1);
}
