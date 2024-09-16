<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\KriteriaVariabel;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\TahunActive;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private function trapmf($x, $a, $b, $c, $d)
    {
        return max(min(($x - $a) / ($b - $a), 1, ($d - $x) / ($d - $c)), 0);
    }

    private function trimf($x, $a, $b, $c)
    {
        return max(min(($x - $a) / ($b - $a), ($c - $x) / ($c - $b)), 0);
    }
    // public function index()
    // {
    //     //
    //     // dd('love ida ');
    //     $value = 45; // Contoh nilai input
    //     $fuzzySets = [
    //         'Kurang' => $this->trapmf($value, -10, 10, 30, 55),
    //         'Cukup' => $this->trimf($value, 30, 55, 80),
    //         'Baik' => $this->trapmf($value, 55, 80, 100, 110),
    //     ];
    //     dd($fuzzySets);
    //     return view('admin.dashboard');
    // }


    public function periode()
    {
        $karyawan = Karyawan::orderBy('updated_at', 'asc')->paginate(5);
        $periode = Periode::orderBy('bulan', 'desc')->paginate(5);

        return view('admin.periode.index', compact('karyawan', 'periode'));
    }

    public function inputPeriode(Request $request)
    {
        $validatedData = $request->validate([
            'periode' => 'required|date',
        ]);
        $databaru = TahunActive::find(1);
        $databaru->tahun_active = $request->periode;
        $databaru->save();


        Periode::where('is_active', 1)->update(['is_active' => 0]);
        $p = new Periode();
        $p->bulan = $request->periode;
        $p->is_active = 1;
        $p->save();

        $karyawanList = Karyawan::all();
        foreach ($karyawanList as $karyawan) {
            $nilai = new Nilai();
            $nilai->id_karyawan = $karyawan->id;
            $nilai->id_periode = $p->id;
            $nilai->periodes = $request->periode;
            $nilai->status = 'menunggu dikartap';
            $nilai->save();
        }

        Session::flash('message', 'Periode Baru berhasil disimpan!');
        return redirect()->back();
    }

    public function updatePeriode(Request $request, $id)
    {
        $validatedData = $request->validate([
            'periode' => 'required|date',
        ]);

        $periode = Periode::find($id);
        $periode->bulan = $request->periode;
        $periode->save();

        Session::flash('message', 'Periode berhasil diupdate!');
        return redirect()->back();
    }

    public function checkPeriode(Request $request)
    {
        // Retrieve the active year from the TahunActive model
        $data = TahunActive::find(1);
        $activeYear = $data->tahun_active;

        // Get the 'periode' input from the request
        $tanggal = $request->input('periode');

        // Extract the month and year from the provided date
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        $ac = date('Y', strtotime($activeYear));

        // Determine if the input year is a new year compared to the active year
        $isNewYear = ($tahun != $ac);
        $isCurrentYear = ($tahun == Carbon::now()->year);

        // Check if a record exists in the Periode model for the given month and year only if it's not a new year
        $exists = false;
        if (!$isNewYear) {
            $exists = Periode::whereMonth('bulan', $bulan)->whereYear('bulan', $tahun)->exists();
        }

        // Check if all fields (n1 to n9) are filled for the specific year
        $allFilled = true;
        $missingData = [];
        if (!$isNewYear) {
            // Get all distinct employee IDs for the specified year
            $employeeIds = Nilai::whereYear('periodes', $tahun)->distinct()->pluck('id_karyawan');

            foreach ($employeeIds as $employeeId) {
                $records = Nilai::where('id_karyawan', $employeeId)
                    ->whereYear('periodes', $tahun)
                    ->get(['n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'n7', 'n8', 'n9']);

                // Check if all fields from n1 to n9 are filled
                foreach ($records as $record) {
                    if (in_array(null, $record->toArray(), true)) {
                        $allFilled = false;
                        $missingData[] = $employeeId; // Track employee IDs with missing data
                        break 2; // Exit both loops if any record is not fully filled
                    }
                }
            }
        }

        // Return the result as a JSON response
        return response()->json([
            'exists' => $exists,
            'isNewYear' => $isNewYear,
            'allFilled' => $allFilled,
            'isCurrentYear' => $isCurrentYear,

            'missingData' => $missingData, // Send the list of employees with missing data
            'message' => $isNewYear ? 'New year detected!' : ($allFilled ? 'All fields are filled.' : 'Some data is missing.')
        ]);
    }

    public function updateNilai(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // 'id' => 'required|integer|exists:nilai,id',
            'bekerja_sama' => 'required|integer|min:10|max:100',
            'sesuai_tugas' => 'required|integer|min:10|max:100',
            'kepedulian' => 'required|integer|min:10|max:100',
            'k3' => 'required|integer|min:10|max:100',
            'patuh_dan_taat' => 'required|integer|min:10|max:100',
            'tepat_waktu' => 'required|integer|min:10|max:100',
            'kesiapan' => 'required|integer|min:10|max:100',
            'apel_shift' => 'required|integer|min:10|max:100',
            'kelengkapan_atribut' => 'required|integer|min:10|max:100',
        ]);

        try {
            // Find the record by ID and update it
            $nilai = Nilai::findOrFail($request->idadmin);
            $nilai->update([
                'n1' => $request->bekerja_sama,
                'n2' => $request->sesuai_tugas,
                'n3' => $request->kepedulian,
                'n4' => $request->k3,
                'n5' => $request->patuh_dan_taat,
                'n6' => $request->tepat_waktu,
                'n7' => $request->kesiapan,
                'n8' => $request->apel_shift,
                'n9' => $request->kelengkapan_atribut,
                'n9' => $request->kelengkapan_atribut,
            ]);
            Session::flash('message', 'Nilai Berhasil di update.');
            return response()->json([
                'message' => 'Data berhasil diperbarui!',
                'data' => $nilai
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data!',], 500);
        }
    }
    public function filterKaryawan(Request $request)
    {
        $bulan = $request->input('bulan');
        $karyawan = Karyawan::with(['nilais' => function ($query) use ($bulan) {
            $query->whereHas('periode', function ($q) use ($bulan) {
                $q->where('bulan', $bulan);
            });
        }])->get();
        dd($karyawan);

        return response()->json($karyawan);
    }


    // app/Http/Controllers/AdminController.php
    public function dashboard()
    {
        // Retrieve the active period
        $active_periode = Periode::where('is_active', 1)->first();

        if (!$active_periode) {
            // Handle the case where there is no active period
            $karyawan_nilai = collect(); // Return an empty collection or handle as needed
        } else {
            // Get the year of the active period
            $activeYear = \Carbon\Carbon::parse($active_periode->bulan)->year;

            // Retrieve `Nilai` records where the `periode` year matches the active year
            $karyawan_nilai = Nilai::whereHas('periode', function ($query) use ($activeYear) {
                $query->whereYear('bulan', $activeYear);
            })->with('periode')->get();
        }

        // Count total karyawan
        $totalKaryawan = Karyawan::count();

        // Initialize arrays for storing data
        $dataPeriode = [
            'Jan' => [],
            'Feb' => [],
            'Mar' => [],
            'Apr' => [],
            'Mei' => [],
            'Jun' => [],
            'Jul' => [],
            'Aug' => [],
            'Sep' => [],
            'Okt' => [],
            'Nov' => [],
            'Des' => []
        ];
        $inputCount = [];
        $uninputCount = [];

        foreach ($karyawan_nilai as $nilai) {
            if ($nilai->periode) {
                $bulan = date('n', strtotime($nilai->periode->bulan));
                $namaBulan = date('M', mktime(0, 0, 0, $bulan, 10));

                // Format bulan
                $namaBulan = match ($namaBulan) {
                    'Dec' => 'Des',
                    'Oct' => 'Okt',
                    'May' => 'Mei',
                    default => $namaBulan,
                };

                // Cek nilai tidak null
                if (
                    $nilai->n1 !== null || $nilai->n2 !== null || $nilai->n3 !== null ||
                    $nilai->n4 !== null || $nilai->n5 !== null || $nilai->n6 !== null ||
                    $nilai->n7 !== null || $nilai->n8 !== null || $nilai->n9 !== null
                ) {
                    $inputCount[$namaBulan] = ($inputCount[$namaBulan] ?? 0) + 1; // Count input
                }

                // Hitung rata-rata nilai
                $rataRataNilai = (
                    $nilai->n1 + $nilai->n2 + $nilai->n3 +
                    $nilai->n4 + $nilai->n5 + $nilai->n6 +
                    $nilai->n7 + $nilai->n8 + $nilai->n9
                ) / 9;

                $dataPeriode[$namaBulan][] = $rataRataNilai;
            }
        }

        // Hitung rata-rata nilai per periode
        $averagePerPeriode = [];
        foreach ($dataPeriode as $bulan => $nilai) {
            $averagePerPeriode[$bulan] = count($nilai) > 0
                ? array_sum($nilai) / count($nilai)
                : 0;

            // Hitung jumlah karyawan yang belum terinput
            $uninputCount[$bulan] = $totalKaryawan - ($inputCount[$bulan] ?? 0);
        }

        // Fuzzy sets example
        $value = 45;
        $fuzzySets = [
            'Kurang' => $this->trapmf($value, -10, 10, 30, 55),
            'Cukup' => $this->trimf($value, 30, 55, 80),
            'Baik' => $this->trapmf($value, 55, 80, 100, 110),
        ];

        // Count penilai
        $penilaiCount = User::where('role', 'penilai')->count();

        // Fetch the active period
        $periode = Periode::where('is_active', 1)->first();

        // Fetch the count of employees
        $karyawanCount = $totalKaryawan;
        $data = TahunActive::find(1);
        $datak = $data->tahun_active;
        // Fetch the active position
        $jabatan = auth()->user()->role;
        $years = ['2023', '2024', '2025', '2026']; // or dynamically generate if needed

        // Pass all variables to the view
        return view('admin.dashboard', compact('periode', 'years', 'datak', 'jabatan', 'karyawanCount', 'penilaiCount', 'fuzzySets', 'averagePerPeriode', 'uninputCount'));
    }
    public function getChartData(Request $request)
    {
        // Retrieve the selected year from the request
        $selectedYear = $request->input('year');

        // Define the years to fetch data for: the selected year, previous year, and next year
        $years = [
            $selectedYear,
            $selectedYear - 1,
            $selectedYear + 1
        ];

        // Initialize arrays to hold data for each year
        $dataPerYear = [];
        $totalKaryawan = Karyawan::count(); // Total karyawan to use in calculations

        foreach ($years as $year) {
            // Fetch the active period for the given year
            $periode = Periode::whereYear('bulan', $year)
                //   ->where('is_active', 1) // Uncomment if you need to filter by active period
                ->first();

            if ($periode) {
                // Retrieve `Nilai` records for the found period
                $karyawan_nilai = Nilai::whereHas('periode', function ($query) use ($year) {
                    $query->whereYear('bulan', $year);
                })->with('periode')->get();
            } else {
                // Handle the case where there is no period for the year
                $karyawan_nilai = collect();
            }

            // Initialize arrays for monthly data
            $dataPeriode = [
                'Jan' => [],
                'Feb' => [],
                'Mar' => [],
                'Apr' => [],
                'Mei' => [],
                'Jun' => [],
                'Jul' => [],
                'Aug' => [],
                'Sep' => [],
                'Okt' => [],
                'Nov' => [],
                'Des' => []
            ];

            $inputCount = [];

            // Process each Nilai record
            foreach ($karyawan_nilai as $nilai) {
                if ($nilai->periode) {
                    // Determine the month
                    $bulan = date('n', strtotime($nilai->periode->bulan));
                    $namaBulan = date('M', mktime(0, 0, 0, $bulan, 10));

                    // Map month names to the desired format
                    $namaBulan = match ($namaBulan) {
                        'Dec' => 'Des',
                        'Oct' => 'Okt',
                        'May' => 'Mei',
                        default => $namaBulan,
                    };

                    // Count input data
                    if (
                        $nilai->n1 !== null || $nilai->n2 !== null || $nilai->n3 !== null ||
                        $nilai->n4 !== null || $nilai->n5 !== null || $nilai->n6 !== null ||
                        $nilai->n7 !== null || $nilai->n8 !== null || $nilai->n9 !== null
                    ) {
                        $inputCount[$namaBulan] = ($inputCount[$namaBulan] ?? 0) + 1;
                    }

                    // Calculate average value
                    $rataRataNilai = (
                        $nilai->n1 + $nilai->n2 + $nilai->n3 +
                        $nilai->n4 + $nilai->n5 + $nilai->n6 +
                        $nilai->n7 + $nilai->n8 + $nilai->n9
                    ) / 9;

                    // Add average to the corresponding month
                    $dataPeriode[$namaBulan][] = $rataRataNilai;
                }
            }

            // Calculate averages and uninput counts
            $averagePerPeriode = [];
            $uninputCount = [];
            foreach ($dataPeriode as $bulan => $nilai) {
                $averagePerPeriode[$bulan] = count($nilai) > 0
                    ? array_sum($nilai) / count($nilai)
                    : 0;

                $uninputCount[$bulan] = $totalKaryawan - ($inputCount[$bulan] ?? 0);
            }

            // Store data for the current year
            $dataPerYear[$year] = [
                'averagePerPeriode' => array_values($averagePerPeriode),
                'uninputCount' => array_values($uninputCount)
            ];
        }

        // Return the processed data as JSON
        return response()->json($dataPerYear);
    }



    public function updateVariable(Request $request)
    {
        $validated = $request->validate([
            // 'id' => 'required|integer',
            'variabel' => 'required|string|max:255',
        ]);
        // dd($request->all());
        $a = $request->key;
        // dd($request->all());
        $variable = KriteriaVariabel::find(1);
        $variable->$a = $request->variabel;
        $variable->save();
        Session::flash('message', 'Data Berhasil di Perbarui.');



        return response()->json(['success' => true, 'message' => 'Data updated successfully']);
    }

    public function getVariable($id)
    {
        $variable = KriteriaVariabel::find($id);
        return response()->json($variable);
    }


    public function datakaryawan(Request $request)
    {
        $search = $request->input('search');
        $query = Karyawan::query();

        if ($search) {
            $query->where('nama_karyawan', 'like', "%{$search}%");
        }

        $karyawan = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json(['karyawan' => $karyawan]);
        }

        $kr = Nilai::with('karyawan')->get();
        $active_periode = Periode::where('is_active', 1)->first();
        $karyawan_nilai = Nilai::with('periode')->get();
        $v = KriteriaVariabel::all();
        $years = ['2024', '2025', '2026']; // or dynamically generate if needed


        $activeYear = \Carbon\Carbon::parse($active_periode->bulan)->year;
        $activeMonth = \Carbon\Carbon::parse($active_periode->bulan)->month;
        if ($active_periode) {
            // Retrieve `id_periode` for the active period
            $active_periode_id = $active_periode->id;

            // Get all `Nilai` records where `id_periode` matches the active period
            $nilaiRecords = Nilai::where('id_periode', $active_periode_id)->get();

            // Filter records where all columns from `n1` to `n9` are null
            $nilaiWithAllNullValues = $nilaiRecords->filter(function ($nilai) {
                return is_null($nilai->n1) &&
                    is_null($nilai->n2) &&
                    is_null($nilai->n3) &&
                    is_null($nilai->n4) &&
                    is_null($nilai->n5) &&
                    is_null($nilai->n6) &&
                    is_null($nilai->n7) &&
                    is_null($nilai->n8) &&
                    is_null($nilai->n9);
            });
            $karyawanIds = $nilaiWithAllNullValues->pluck('id_karyawan')->unique();

            // Fetch the names of the associated karyawan
            $karyawanWithAllNullValues = Karyawan::whereIn('id', $karyawanIds)->get();
            // dd($karyawanWithAllNullValues);
        } else {
            $nilaiWithAllNullValues = collect(); // Return an empty collection if no active period is found
        }




        return view('admin.karyawan.index', compact('karyawan', 'years', 'karyawanWithAllNullValues'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $validatedData = $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'bidang' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nrp' => 'required|string|max:255',
        ]);

        $karyawan = Karyawan::create($validatedData);

        $allPeriods = Periode::all();
        foreach ($allPeriods as $periode) {
            $nilai = new Nilai();
            $nilai->id_karyawan = $karyawan->id;
            $nilai->id_periode = $periode->id;
            $nilai->periodes = $periode->bulan;
            $nilai->save();
        }

        Session::flash('message', 'Data karyawan berhasil disimpan!');
        Session::flash('alert-type', 'success');
        return redirect('admin/datakaryawann');
    }
    public function edit(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'bidang' => 'required|string',
            'jabatan' => 'required|string',
            'nrp' => 'required|numeric',
        ]);

        // Cari data karyawan berdasarkan ID
        $karyawan = Karyawan::find($id);
        // dd($karyawan->nama_karyawan);
        // Update data karyawan
        $karyawan->nama_karyawan = $request->nama_karyawan;
        $karyawan->bidang = $request->bidang;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->nrp = $request->nrp;
        $karyawan->save();

        Session::flash('message', 'Data karyawan berhasil diupdate!');
        Session::flash('alert-type', 'success');
        return redirect('admin/datakaryawann');
    }
    public function editKriteria(Request $request, $id)
    {


        // Cari data karyawan berdasarkan ID
        $kriteria = kriteria::find($id);

        // dd($request->all());
        $kriteria->kriteria = $request->kriteriaName;
        $kriteria->save();


        Session::flash('message', 'Data karyawan berhasil diupdate!');
        Session::flash('alert-type', 'success');
        return redirect('admin/kriteria');
    }


    /**
     * Display the specified resource.
     */
    public function detailkaryawan($id)
    {
        $karyawan = Karyawan::find($id);
        $tahunaktive = TahunActive::find(1);
        $active_periode = Periode::where('is_active', 1)->first();
        $karyawan_nilai = Nilai::with('periode')->where('id_karyawan', $id)->get();
        $v = KriteriaVariabel::all();

        // Check and update status
        // $this->updateKaryawanStatus($karyawan_nilai);
        $periode_tahun = \Carbon\Carbon::parse($active_periode->bulan)->year;

        // dd($karyawan_nilai);
        $nilai_per_periode = [];
        foreach ($karyawan_nilai as $nilai) {
            $tahun_nilai = \Carbon\Carbon::parse($nilai->periode->bulan)->year;
            if (!isset($nilai_per_periode[$tahun_nilai])) {
                $nilai_per_periode[$tahun_nilai] = [];
            }
            $nilai_per_periode[$tahun_nilai][] = $nilai;
        }
        // Count the nilai per period
        $count_nilai_per_periode = [];
        foreach ($nilai_per_periode as $bulan => $nilais) {
            $count_nilai_per_periode[$bulan] = count($nilais);
        }

        // dd($count_nilai_per_periode);


        return view('admin.karyawan.show', compact('nilai_per_periode', 'count_nilai_per_periode', 'karyawan', 'active_periode', 'karyawan_nilai', 'v', 'id'));
    }

    private function updateKaryawanStatus($karyawan_nilai)
    {
        $currentYear = date('Y');
        $nilaiCount = $karyawan_nilai->filter(function ($nilai) use ($currentYear) {
            return date('Y', strtotime($nilai->periode->bulan)) == $currentYear &&
                !is_null($nilai->n1) && !is_null($nilai->n2) && !is_null($nilai->n3) &&
                !is_null($nilai->n4) && !is_null($nilai->n5) && !is_null($nilai->n6) &&
                !is_null($nilai->n7) && !is_null($nilai->n8) && !is_null($nilai->n9);
        })->groupBy(function ($item) {
            return date('m', strtotime($item->periode->bulan));
        })->count();

        if ($nilaiCount >= 12) {
            foreach ($karyawan_nilai as $nilai) {
                $nilai->status = 'sudah dikartap';
                $nilai->save();
            }
        } else {
            foreach ($karyawan_nilai as $nilai) {
                $nilai->status = 'belum dikartap';
                $nilai->save();
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
