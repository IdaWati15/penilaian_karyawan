<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index(){
        $jabatan = Jabatan::get();
        $bidang = Bidang::get();
        return view('admin.jabatan_bidang.index', compact('jabatan', 'bidang'));
    }

    public function storeJabatan(Request $request)
    {
        // Validate input
        $request->validate([
            'jabatan' => 'required|string|max:255',
        ]);

        // Create new Jabatan record
        Jabatan::create([
            'jabatan' => $request->jabatan,
        ]);

        // Return success response
        return response()->json(['success' => true]);
    }

    public function updateJabatan(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:jabatan,id',
            'jabatan' => 'required|string|max:255',
        ]);

        // Update Jabatan record
        $jabatan = Jabatan::findOrFail($request->id);
        $jabatan->jabatan = $request->jabatan;
        $jabatan->save();

        // Return success response
        return response()->json(['success' => true]);
    }

    public function storeBidang(Request $request)
    {
        // Validate input
        $request->validate([
            'bidang' => 'required|string|max:255',
        ]);

        // Create new Bidang record
        Bidang::create([
            'bidang' => $request->bidang,
        ]);

        // Return success response
        return response()->json(['success' => true]);
    }

    public function updateBidang(Request $request)
    {
        // Validate input
        $request->validate([
            'id' => 'required|exists:bidang,id',
            'bidang' => 'required|string|max:255',
        ]);

        // Update Bidang record
        $bidang = Bidang::findOrFail($request->id);
        $bidang->bidang = $request->bidang;
        $bidang->save();

        // Return success response
        return response()->json(['success' => true]);
    }

    public function destroyJabatan($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        
        return redirect()->route('index')->with('success', 'Jabatan deleted successfully');
    }

    public function destroyBidang($id)
    {
        $bidang = Bidang::findOrFail($id);
        $bidang->delete();
        
        return redirect()->route('index')->with('success', 'Bidang deleted successfully');
    }
}