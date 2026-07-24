<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Standard Scaffolding created in Phase 1
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\BillOfLadingController;
use App\Http\Controllers\AmsFilingController;
use App\Http\Controllers\InvoiceController;

// GoFreight Expanded Operations
use App\Http\Controllers\OceanImportController;
use App\Http\Controllers\OceanExportController;
use App\Http\Controllers\AirImportController;
use App\Http\Controllers\AirExportController;
use App\Http\Controllers\TruckShipmentController;

// Supply Chain & Financial
use App\Http\Controllers\OperationController;
use App\Http\Controllers\WarehouseReceiptController;
use App\Http\Controllers\AccountingPaymentController;
use App\Http\Controllers\AccountingJournalController;
use App\Http\Controllers\TradePartnerController;
use App\Http\Controllers\TradePartnerCreditController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\HouseBillOfLadingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\QuotationController;

Route::apiResource('ports', PortController::class);
Route::apiResource('vessels', VesselController::class);
Route::apiResource('offices', OfficeController::class);
Route::apiResource('house-bills-of-lading', HouseBillOfLadingController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Standard
Route::apiResource('clients', ClientController::class);
Route::apiResource('shipments', ShipmentController::class);
Route::apiResource('bills-of-lading', BillOfLadingController::class);
Route::apiResource('ams-filings', AmsFilingController::class);
Route::apiResource('invoices', InvoiceController::class);

// GoFreight Comprehensive Mapping
Route::apiResource('ocean-imports', OceanImportController::class);
Route::apiResource('ocean-exports', OceanExportController::class);
Route::apiResource('air-imports', AirImportController::class);
Route::apiResource('air-exports', AirExportController::class);
Route::apiResource('truck-shipments', TruckShipmentController::class);

// Truck Shipment Sub-resources
Route::prefix('truck-shipments/{truck_shipment}')->group(function () {
    Route::post('/documents', [TruckShipmentController::class, 'uploadDocument'])->name('truck-shipments.documents.store');
    Route::post('/memos', [TruckShipmentController::class, 'addMemo'])->name('truck-shipments.memos.store');
    Route::put('/memos/{memo}', [TruckShipmentController::class, 'updateMemo'])->name('truck-shipments.memos.update');
    Route::delete('/memos/{memo}', [TruckShipmentController::class, 'deleteMemo'])->name('truck-shipments.memos.destroy');
});
Route::delete('/truck-shipments/documents/{document}', [TruckShipmentController::class, 'deleteDocument'])->name('truck-shipments.documents.destroy');
Route::get('/truck-shipments/documents/{document}/download', [TruckShipmentController::class, 'downloadDocument'])->name('truck-shipments.documents.download');

// Truck Shipment Charge routes
Route::prefix('truck-shipments/{truck_shipment}')->group(function () {
    Route::post('/charges', [TruckShipmentController::class, 'storeCharge'])->name('truck-shipments.charges.store');
    Route::put('/charges/{charge}', [TruckShipmentController::class, 'updateCharge'])->name('truck-shipments.charges.update');
    Route::delete('/charges/{charge}', [TruckShipmentController::class, 'deleteCharge'])->name('truck-shipments.charges.destroy');
    Route::post('/create-invoice', [TruckShipmentController::class, 'createInvoiceFromCharges'])->name('truck-shipments.charges.create-invoice');
});

Route::apiResource('operations', OperationController::class);
Route::apiResource('warehouse-receipts', WarehouseReceiptController::class);

Route::apiResource('accounting-payments', AccountingPaymentController::class);
Route::apiResource('accounting-journals', AccountingJournalController::class);

Route::apiResource('trade-partners', TradePartnerController::class);
Route::apiResource('trade-partner-credits', TradePartnerCreditController::class);

Route::apiResource('bookings', BookingController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('quotations', QuotationController::class)->only(['index', 'show']);
Route::get('/work-orders', [App\Http\Controllers\WorkOrderController::class, 'apiIndex']);

