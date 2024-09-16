<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Nilai;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HasilController extends Controller
{
    public function index()
    {
        // $nilai = Nilai::select('nilai.*')
        //     ->join(DB::raw('(SELECT id_karyawan, MAX(created_at) as latest FROM nilai GROUP BY id_karyawan, YEAR(created_at)) as latest_nilai'), function($join) {
        //         $join->on('nilai.id_karyawan', '=', 'latest_nilai.id_karyawan')
        //              ->on('nilai.created_at', '=', 'latest_nilai.latest');
        //     })
        //     ->where('status', 'sudah dikartap')
        //     ->get();
        $years = ['2023','2024', '2025', '2026']; // or dynamically generate if needed

        $nilai = Nilai::with('periode')->get();
        $karyawan = Karyawan::get();
        // dd($nilai);
        return view('admin.hasil', compact('nilai', 'karyawan', 'years'));
    }
    public function getChartData(Request $request)
    {
        $selectedYear = $request->input('year');
        $years = [$selectedYear, $selectedYear - 1, $selectedYear + 1];

        $dataPerYear = [];
        $totalKaryawan = Karyawan::count();

        foreach ($years as $year) {
            $periode = Periode::whereYear('bulan', $year)->first();

            if ($periode) {
                $karyawan_nilai = Nilai::where('id_periode', $periode->id)
                    ->orderBy('n_final', 'desc')
                    ->get();
                $dataPerYear[$year] = $karyawan_nilai->map(function ($nilai) use ($year) {
                    $karyawan = Karyawan::find($nilai->id_karyawan);
                    return [
                        'nama_karyawan' => $karyawan ? $karyawan->nama_karyawan : 'Unknown',
                        'jabatan' => $karyawan ? $karyawan->jabatan : 'Unknown',
                        'n_final' => $nilai->n_final
                    ];
                });
            } else {
                $dataPerYear[$year] = []; // No data for this year
            }
        }

        return response()->json($dataPerYear);
    }


    public function exportPdf()
    {
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $nilai = DB::table('nilai')
        ->where('status', 'sudah dikartap')
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('id_karyawan')
        ->map(function (Collection $group) {
            return $group->first(); // Ambil nilai terbaru untuk setiap karyawan
        });
    
    
    // dd($nilai);
    

// dd($nilai);
        // $nilai = Nilai::select('nilai.*')
        //     ->join(DB::raw('(SELECT id_karyawan, MAX(created_at) as latest FROM nilai GROUP BY id_karyawan, YEAR(created_at)) as latest_nilai'), function ($join) {
        //         $join->on('nilai.id_karyawan', '=', 'latest_nilai.id_karyawan')
        //             ->on('nilai.created_at', '=', 'latest_nilai.latest');
        //     })
        //     ->where('status', 'sudah dikartap')
        //     ->get();
        $karyawan = Karyawan::get();
       $path = base_path('header.png');
       $type = pathinfo($path, PATHINFO_EXTENSION);
       $data = file_get_contents($path);
       $pic= 'data:image/png'. $type . ';base64,'. base64_encode($data);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf', compact('nilai', 'karyawan'));
        // $pdf->getDomPDF()->setHttpContext(
        //     stream_context_create([
        //         'ssl' => [
        //             'allow_self_signed'=> TRUE,
        //             'verify_peer' => FALSE,
        //             'verify_peer_name' => FALSE,
        //         ]
        //     ])
        // );
        // return $pdf->download('Rekomendasi.pdf');
        return $pdf->download('Rekomendasi.pdf');
    }
}
