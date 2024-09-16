<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
// use App\Console\Commands\AddKriteriaColumn;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::all();
        return view('admin.kriteria.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $kriteria = Kriteria::create($request->all());

        // Run the command to add a new column
        Artisan::call('add:kriteria-column', ['name' => 'n' . $kriteria->id]);

        return response()->json(['success' => true, 'message' => 'Kriteria added successfully.']);
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'kriteria' => 'required|string|max:255',
        ]);

        // Update Kriteria record
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->kriteria = $request->kriteria; // Corrected field name
        $kriteria->save();

        // Return success response
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $jabatan = Kriteria::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('admin.kriteria')->with('success', 'Jabatan deleted successfully');
    }
}
