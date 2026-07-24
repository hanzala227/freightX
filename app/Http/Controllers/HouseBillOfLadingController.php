<?php

namespace App\Http\Controllers;

use App\Models\HouseBillOfLading;
use Illuminate\Http\Request;

class HouseBillOfLadingController extends Controller
{
    public function index()
    {
        return response()->json(HouseBillOfLading::with('oceanImport')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ocean_import_id' => 'required|exists:ocean_imports,id',
            'hbl_number' => 'required|unique:house_bill_of_ladings',
            'customer_id' => 'nullable|exists:trade_partners,id',
        ]);
        return response()->json(HouseBillOfLading::create($validated), 201);
    }
}
