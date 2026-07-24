<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();
        return view('sales.leads', compact('leads'));
    }

    public function pipeline()
    {
        $leads = Lead::all();
        $pipeline = [
            'new' => $leads->where('status', 'new'),
            'qualified' => $leads->where('status', 'qualified'),
            'proposal_sent' => $leads->where('status', 'proposal_sent'),
            'won' => $leads->where('status', 'won'),
            'lost' => $leads->where('status', 'lost')
        ];
        return view('sales.pipeline', compact('pipeline'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'service_required' => 'nullable|string|max:255',
            'potential_volume' => 'nullable|string|max:255',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $validated['status'] = 'new';
        Lead::create($validated);

        return redirect()->back()->with('success', 'Lead created successfully.');
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255'
        ]);

        $lead->update($request->all());

        if($request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', 'Lead updated successfully.');
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully.');
    }
}
