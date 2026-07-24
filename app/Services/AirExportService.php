<?php

namespace App\Services;

use App\Models\AirExport;
use App\Models\AirExportHbl;
use App\Models\Charge;
use Illuminate\Support\Facades\DB;

class AirExportService
{
    private function coerceNumericDefaults(array $data): array
    {
        $zeroFields = ['pkg_qty', 'gross_weight', 'chargeable_weight', 'volume', 'buying_rate', 'selling_rate'];
        foreach ($zeroFields as $field) {
            if (array_key_exists($field, $data) && ($data[$field] === null || $data[$field] === '')) {
                $data[$field] = 0;
            }
        }
        return $data;
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data = $this->coerceNumericDefaults($data);
            $airExport = AirExport::create($data);

            if (isset($data['hbls'])) {
                foreach ($data['hbls'] as $hblData) {
                    $hblData = $this->coerceNumericDefaults($hblData);
                    $airExport->hbls()->create($hblData);
                }
            }

            if (isset($data['charges'])) {
                foreach ($data['charges'] as $chargeData) {
                    $chargeData['charge_name'] = $chargeData['charge_name'] ?? ($chargeData['description'] ?? '');
                    $airExport->charges()->create($chargeData);
                }
            }

            $airExport->statusLogs()->create([
                'user_id' => auth()->id(),
                'status_code' => 'CREATED',
                'status_name' => 'CREATED',
                'details' => 'Air Export shipment created.',
            ]);

            return $airExport;
        });
    }

    public function update(AirExport $airExport, array $data)
    {
        return DB::transaction(function () use ($airExport, $data) {
            $data = $this->coerceNumericDefaults($data);
            $airExport->update($data);

            $submittedHblIds = [];
            if (isset($data['hbls'])) {
                foreach ($data['hbls'] as $hblData) {
                    $hblData = $this->coerceNumericDefaults($hblData);
                    if (isset($hblData['id']) && $hblData['id']) {
                        $hbl = AirExportHbl::withTrashed()->find($hblData['id']);
                        if ($hbl && $hbl->air_export_id == $airExport->id) {
                            $hbl->restore();
                            $hbl->update($hblData);
                            $submittedHblIds[] = $hbl->id;
                            continue;
                        }
                    }
                    $newHbl = $airExport->hbls()->create($hblData);
                    $submittedHblIds[] = $newHbl->id;
                }
            }
            $airExport->hbls()->whereNotIn('id', $submittedHblIds)->delete();

            if (isset($data['charges'])) {
                $airExport->charges()->delete();
                foreach ($data['charges'] as $chargeData) {
                    $chargeData['charge_name'] = $chargeData['charge_name'] ?? ($chargeData['description'] ?? '');
                    $airExport->charges()->create($chargeData);
                }
            }

            $airExport->statusLogs()->create([
                'user_id' => auth()->id(),
                'status_code' => 'UPDATED',
                'status_name' => 'UPDATED',
                'details' => 'Air Export shipment updated.',
            ]);

            return $airExport;
        });
    }

    public function createCharge(AirExport $airExport, array $data)
    {
        return $airExport->charges()->create([
            'type' => $data['type'] ?? 'AR',
            'charge_code' => $data['charge_code'] ?? '',
            'charge_name' => $data['charge_name'] ?? '',
            'rate' => $data['rate'] ?? 0,
            'qty' => $data['qty'] ?? 1,
            'amount' => ($data['rate'] ?? 0) * ($data['qty'] ?? 1),
            'currency_id' => $data['currency_id'] ?? null,
            'pc' => $data['pc'] ?? 'COLLECT',
            'vendor_id' => $data['vendor_id'] ?? null,
            'bill_to_id' => $data['bill_to_id'] ?? null,
            'unit' => $data['unit'] ?? 'B/L',
            'remark' => $data['remark'] ?? '',
        ]);
    }

    public function updateCharge(Charge $charge, array $data)
    {
        $charge->update([
            'charge_code' => $data['charge_code'] ?? $charge->charge_code,
            'charge_name' => $data['charge_name'] ?? $charge->charge_name,
            'rate' => $data['rate'] ?? $charge->rate,
            'qty' => $data['qty'] ?? $charge->qty,
            'amount' => ($data['rate'] ?? $charge->rate) * ($data['qty'] ?? $charge->qty),
            'currency_id' => $data['currency_id'] ?? $charge->currency_id,
            'pc' => $data['pc'] ?? $charge->pc,
            'vendor_id' => $data['vendor_id'] ?? $charge->vendor_id,
            'bill_to_id' => $data['bill_to_id'] ?? $charge->bill_to_id,
            'unit' => $data['unit'] ?? $charge->unit,
            'remark' => $data['remark'] ?? $charge->remark,
        ]);

        return $charge;
    }
}
