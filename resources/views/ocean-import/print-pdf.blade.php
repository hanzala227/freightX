<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipment Overview - {{ $shipment->file_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 30px; font-size: 12px; line-height: 1.4; }
        .container { max-width: 800px; margin: auto; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin: 0; color: #111; }
        .section-title { font-size: 14px; font-weight: bold; background: #f0f0f0; padding: 6px; margin: 15px 0 10px 0; border-left: 4px solid #3b82f6; text-transform: uppercase; }
        table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.info-table td { padding: 6px 4px; vertical-align: top; border-bottom: 1px dashed #eee; }
        table.info-table td.label { font-weight: bold; color: #555; width: 150px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data-table th { background: #333; color: #fff; padding: 8px; font-size: 11px; text-align: left; }
        table.data-table td { padding: 8px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .btn-print { background: #3b82f6; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer; float: right; margin-bottom: 20px; }
        @media print {
            .btn-print { display: none; }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Print Shipment PDF</button>
    <div class="container">
        <div class="header">
            <h1>OCEAN IMPORT SHIPMENT OVERVIEW</h1>
            <p><strong>File No:</strong> {{ $shipment->file_no }} | <strong>MBL No:</strong> {{ $shipment->mbl_no }}</p>
        </div>

        <div class="section-title">General Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Post Date:</td>
                <td>{{ $shipment->post_date ? $shipment->post_date->format('Y-m-d') : 'N/A' }}</td>
                <td class="label">Office:</td>
                <td>{{ $shipment->office->code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Carrier:</td>
                <td>{{ $shipment->carrier->name ?? 'N/A' }}</td>
                <td class="label">Vessel/Voyage:</td>
                <td>{{ $shipment->vessel->name ?? 'N/A' }} / {{ $shipment->voyage ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Port of Loading:</td>
                <td>{{ $shipment->portOfLoading->name ?? 'N/A' }}</td>
                <td class="label">Port of Discharge:</td>
                <td>{{ $shipment->portOfDischarge->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">ETD:</td>
                <td>{{ $shipment->etd ? $shipment->etd->format('Y-m-d') : 'N/A' }}</td>
                <td class="label">ETA:</td>
                <td>{{ $shipment->eta ? $shipment->eta->format('Y-m-d') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Overseas Agent:</td>
                <td>{{ $shipment->overseaAgent->name ?? 'N/A' }}</td>
                <td class="label">Billing Customer:</td>
                <td>{{ $shipment->dmCustomer->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="section-title">Containers</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Container No.</th>
                    <th>Type</th>
                    <th>Seal No.</th>
                    <th>PKG Qty</th>
                    <th>Weight (KG)</th>
                    <th>Measure (CBM)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipment->containers as $c)
                    <tr>
                        <td>{{ $c->container_no }}</td>
                        <td>{{ $c->containerType->code ?? 'N/A' }}</td>
                        <td>{{ $c->seal_no }}</td>
                        <td>{{ $c->pkg_qty }}</td>
                        <td>{{ number_format($c->weight_kg, 2) }}</td>
                        <td>{{ number_format($c->measure_cbm, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #777;">No containers listed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">House B/Ls</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>HBL No.</th>
                    <th>Customer</th>
                    <th>Shipper</th>
                    <th>Consignee</th>
                    <th>Destination</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipment->hbls as $h)
                    <tr>
                        <td>{{ $h->hbl_no }}</td>
                        <td>{{ $h->customer->name ?? 'N/A' }}</td>
                        <td>{{ $h->shipper->name ?? 'N/A' }}</td>
                        <td>{{ $h->consignee->name ?? 'N/A' }}</td>
                        <td>{{ $h->del_id ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">No House Bills listed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
