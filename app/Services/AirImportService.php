<?php

namespace App\Services;

use App\Models\AirImport;
use App\Models\AirImportHbl;
use App\Models\AirImportContainer;
use App\Models\ShipmentStatusLog;
use Illuminate\Support\Facades\DB;

class AirImportService
{
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $airImport = AirImport::create($data);

            // Handle HBLs
            if (isset($data['hbls'])) {
                foreach ($data['hbls'] as $hblData) {
                    $airImport->hbls()->create($hblData);
                }
            }

            // Handle containers
            if (isset($data['containers'])) {
                foreach ($data['containers'] as $containerData) {
                    $containerData['air_import_id'] = $airImport->id;
                    unset($containerData['id'], $containerData['selected'], $containerData['expanded']);
                    AirImportContainer::create($containerData);
                }
            }

            // Handle charges
            if (isset($data['charges'])) {
                foreach ($data['charges'] as $chargeData) {
                    $this->createCharge($airImport, $chargeData);
                }
            }

            // Log history
            ShipmentStatusLog::create([
                'shipment_type' => AirImport::class,
                'shipment_id' => $airImport->id,
                'status_code' => 'CREATED',
                'status_name' => 'Created',
                'details' => 'Air Import shipment created.',
                'user_id' => auth()->id(),
                'event_time' => now(),
            ]);

            return $airImport;
        });
    }

    public function update(AirImport $airImport, array $data)
    {
        return DB::transaction(function () use ($airImport, $data) {
            $airImport->update($data);

            // Handle HBLs - delete removed ones, update existing, add new
            if (isset($data['hbls'])) {
                $submittedHblIds = collect($data['hbls'])->pluck('id')->filter()->toArray();
                $airImport->hbls()->whereNotIn('id', $submittedHblIds)->delete();

                foreach ($data['hbls'] as $hblData) {
                    if (isset($hblData['id'])) {
                        $hbl = AirImportHbl::find($hblData['id']);
                        if ($hbl) $hbl->update($hblData);
                    } else {
                        $airImport->hbls()->create($hblData);
                    }
                }
            }

            // Handle containers - delete removed ones, update existing, add new
            if (isset($data['containers'])) {
                $submittedContainerIds = collect($data['containers'])->pluck('id')->filter()->toArray();
                $airImport->containers()->whereNotIn('id', $submittedContainerIds)->delete();

                foreach ($data['containers'] as $containerData) {
                    unset($containerData['selected'], $containerData['expanded']);
                    if (isset($containerData['id'])) {
                        $container = AirImportContainer::find($containerData['id']);
                        if ($container) $container->update($containerData);
                    } else {
                        $containerData['air_import_id'] = $airImport->id;
                        AirImportContainer::create($containerData);
                    }
                }
            }

            // Log history
            ShipmentStatusLog::create([
                'shipment_type' => AirImport::class,
                'shipment_id' => $airImport->id,
                'status_code' => 'UPDATED',
                'status_name' => 'Updated',
                'details' => 'Shipment details updated.',
                'user_id' => auth()->id(),
                'event_time' => now(),
            ]);

            return $airImport;
        });
    }

    public function createCharge(AirImport $airImport, array $data)
    {
        $currencyId = null;
        if (isset($data['currency'])) {
            $currency = \App\Models\Currency::where('code', $data['currency'])->first();
            $currencyId = $currency ? $currency->id : null;
        }

        $type = (isset($data['pr']) && $data['pr'] === 'Pay') ? 'AP' : 'AR';
        $pc = (isset($data['ppc']) && $data['ppc'] === 'Prepaid') ? 'PREPAID' : 'COLLECT';

        $rate = floatval($data['rate'] ?? 0);
        $qty = floatval($data['qty'] ?? 1);
        $amount = $rate * $qty;
        $taxPercent = floatval($data['vat'] ?? 0);
        $taxAmount = $amount * ($taxPercent / 100);
        $totalAmount = $amount + $taxAmount;

        return $airImport->charges()->create([
            'type' => $type,
            'charge_code' => $data['chrg_code'] ?? '',
            'charge_name' => $data['chrg_code'] ?? 'Charge',
            'pc' => $pc,
            'qty' => $qty,
            'unit' => $data['qty_type'] ?? 'UNIT',
            'currency_id' => $currencyId,
            'rate' => $rate,
            'amount' => $amount,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'remark' => $data['eq_bl_no'] ?? null,
        ]);
    }

    public function updateCharge(\App\Models\Charge $charge, array $data)
    {
        $currencyId = null;
        if (isset($data['currency'])) {
            $currency = \App\Models\Currency::where('code', $data['currency'])->first();
            $currencyId = $currency ? $currency->id : null;
        }

        $type = (isset($data['pr']) && $data['pr'] === 'Pay') ? 'AP' : 'AR';
        $pc = (isset($data['ppc']) && $data['ppc'] === 'Prepaid') ? 'PREPAID' : 'COLLECT';

        $rate = floatval($data['rate'] ?? $charge->rate);
        $qty = floatval($data['qty'] ?? $charge->qty);
        $amount = $rate * $qty;
        $taxPercent = floatval($data['vat'] ?? $charge->tax_percent);
        $taxAmount = $amount * ($taxPercent / 100);
        $totalAmount = $amount + $taxAmount;

        $charge->update([
            'type' => $type,
            'charge_code' => $data['chrg_code'] ?? $charge->charge_code,
            'charge_name' => $data['chrg_code'] ?? $charge->charge_name,
            'pc' => $pc,
            'qty' => $qty,
            'unit' => $data['qty_type'] ?? $charge->unit,
            'currency_id' => $currencyId ?? $charge->currency_id,
            'rate' => $rate,
            'amount' => $amount,
            'tax_percent' => $taxPercent,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'remark' => $data['eq_bl_no'] ?? $charge->remark,
        ]);

        return $charge;
    }
}
