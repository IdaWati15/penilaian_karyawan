<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\Nilai;

class FuzzyController extends Controller
{
    public function fuzzy($id, $years)
    {
        try {
            // Ambil data berdasarkan id_karyawan
            $employees = Nilai::where('id_karyawan', $id)
                // ->whereIn('periode', $years) // Pastikan periode sesuai dengan tahun yang diberikan
                ->get();

            // Array untuk menyimpan rata-rata per variabel
            $averages = [
                'Bekerjasama' => [],
                'Bekerja_sesuai_tugas' => [],
                'Kepedulian' => [],
                'K3' => [],
                'Patuh_dan_taat' => [],
                'Tepat_waktu' => [],
                'Kesiapan' => [],
                'Apel_shift' => [],
                'Kelengkapan_atribut' => []
            ];

            // Menghitung rata-rata per variabel
            foreach ($employees as $employee) {
                $values = [
                    'Bekerjasama' => $employee->n1,
                    'Bekerja_sesuai_tugas' => $employee->n2,
                    'Kepedulian' => $employee->n3,
                    'K3' => $employee->n4,
                    'Patuh_dan_taat' => $employee->n5,
                    'Tepat_waktu' => $employee->n6,
                    'Kesiapan' => $employee->n7,
                    'Apel_shift' => $employee->n8,
                    'Kelengkapan_atribut' => $employee->n9,
                ];

                foreach ($values as $key => $value) {
                    $averages[$key][] = $value;
                }
            }

            // Hitung rata-rata untuk setiap variabel
            foreach ($averages as $key => $values) {
                $averages[$key] = array_sum($values) / count($values);
            }

            // // Definisikan fungsi keanggotaan
            // $membershipFunctions = [
            //     'Kurang' => function ($value) {
            //         return $value <= 70 ? 1 : ($value >= 0 && $value < 70 ? (70 - $value) / 70 : 0);
            //     },
            //     'Cukup' => function ($value) {
            //         return ($value >= 71 && $value <= 80) ? ($value - 71) / 9 : ($value >= 81 && $value <= 80 ? (80 - $value) / 9 : 0);
            //     },
            //     'Baik' => function ($value) {
            //         return $value >= 81 ? 1 : ($value >= 71 && $value < 81 ? ($value - 71) / 10 : 0);
            //     },
            // ];
            $membershipFunctions = [
                'Kurang' => function ($value) {
                    // Trapezoidal function: trapmf([-10 10 30 55])
                    if ($value <= -10 || $value >= 55) return 0;
                    if ($value >= 10 && $value <= 30) return 1;
                    if ($value > 30 && $value < 55) return (55 - $value) / (55 - 30);
                    return 0;
                },
                'Cukup' => function ($value) {
                    // Triangular function: trimf([30 55 80])
                    if ($value <= 30 || $value >= 80) return 0;
                    if ($value > 30 && $value <= 55) return ($value - 30) / (55 - 30);
                    if ($value > 55 && $value <= 80) return (80 - $value) / (80 - 55);
                    return 0;
                },
                'Baik' => function ($value) {
                    // Trapezoidal function: trapmf([55 80 100 110])
                    if ($value <= 55 || $value >= 110) return 0;
                    if ($value > 55 && $value <= 80) return ($value - 55) / (80 - 55);
                    if ($value > 80 && $value <= 100) return 1;
                    if ($value > 100 && $value < 110) return (110 - $value) / (110 - 100);
                    return 0;
                },
            ];
            $variables = ['Bekerjasama', 'Bekerja_sesuai_tugas', 'Kepedulian', 'K3', 'Patuh_dan_taat', 'Tepat_waktu', 'Kesiapan', 'Apel_shift', 'Kelengkapan_atribut'];

            // Hitung hasil keanggotaan untuk setiap variabel
            $results = [];
            foreach ($variables as $variable) {
                $values = [$averages[$variable]]; // Menggunakan rata-rata untuk perhitungan fuzzy
                $results[$variable] = [
                    'Kurang' => array_sum(array_map(fn ($avg) => $membershipFunctions['Kurang']($avg), $values)) / count($values),
                    'Cukup' => array_sum(array_map(fn ($avg) => $membershipFunctions['Cukup']($avg), $values)) / count($values),
                    'Baik' => array_sum(array_map(fn ($avg) => $membershipFunctions['Baik']($avg), $values)) / count($values),
                ];
            }

            // Aturan fuzzy
            $rules = [
                ['1 1 1 1 1 1 1 1 1', 'Tidak_Rekomendasi'],
                ['1 2 2 2 2 2 2 2 2', 'Rekomendasi'],
                ['1 3 3 3 3 3 3 3 3', 'Rekomendasi'],
                ['2 1 1 1 1 1 1 1 1', 'Tidak_Rekomendasi'],
                ['2 2 2 2 2 2 2 2 2', 'Tidak_Rekomendasi'],
                ['2 3 3 3 3 3 3 3 3', 'Rekomendasi'],
                ['3 1 1 1 1 1 1 1 1', 'Tidak_Rekomendasi'],
                ['3 2 2 2 2 2 2 2 2', 'Rekomendasi'],
                ['3 3 3 3 3 3 3 3 3', 'Rekomendasi'],
                ['1 1 1 1 1 2 2 2 2', 'Tidak_Rekomendasi'],
                ['2 2 2 2 2 3 3 3 3', 'Rekomendasi'],
                ['3 3 3 3 3 3 2 2 3', 'Rekomendasi'],
                ['3 1 1 1 1 2 2 2 2', 'Tidak_Rekomendasi'],
                ['3 2 2 3 3 3 3 3 2', 'Rekomendasi'],
                ['1 2 3 3 2 2 2 3 3', 'Rekomendasi'],
                ['2 2 1 1 1 2 1 1 3', 'Tidak_Rekomendasi'],
                ['2 3 3 2 3 2 2 3 2', 'Rekomendasi'],
                ['1 3 3 1 1 1 1 2 2', 'Rekomendasi'],
                ['2 2 3 3 3 3 3 3 3', 'Rekomendasi'],
            ];

            // Evaluasi aturan
            $outputs = [
                'Tidak_Rekomendasi' => [],
                'Rekomendasi' => [],
            ];

            foreach ($rules as $rule) {
                $conditions = explode(' ', $rule[0]);
                $output = $rule[1];
                $minValue = PHP_INT_MAX;

                foreach ($variables as $index => $variable) {
                    $condition = $conditions[$index];
                    $minValue = min($minValue, $results[$variable][array_keys($membershipFunctions)[$condition - 1]]);
                }

                $outputs[$output][] = $minValue;
            }

            // Agregasi hasil
            $aggregatedResults = [];
            foreach ($outputs as $key => $values) {
                $aggregatedResults[$key] = max($values) * 100; // Konversi ke persentase
            }

            // Keputusan akhir
            $decision = $aggregatedResults['Rekomendasi'] > $aggregatedResults['Tidak_Rekomendasi'] ? 'Rekomendasi' : 'Tidak_Rekomendasi';
            $results = [
                [
                    'Nilai_Output' => round(array_sum($averages) / count($averages), 2), // Contoh output
                    'Keputusan' => $decision
                ]
            ];
            $fuzzySets = [];
            foreach ($averages as $variable => $average) {
                $fuzzySets[$variable] = [
                    'Kurang' => $membershipFunctions['Kurang']($average),
                    'Cukup' => $membershipFunctions['Cukup']($average),
                    'Baik' => $membershipFunctions['Baik']($average),
                ];
            }
            // sebelum ini panggil fungsi PostData
            $postDataResponse = $this->postData($averages);

            // Mendapatkan hasil dari respons postData
            $postDataResult = $postDataResponse['result'] ?? null;
            // dd($postDataResponse);

            // Save data to the nilai table
            $karyawanIds = $id;
            // $dataUpdate = ['n_final' => $postDataResult];

            $nilai = Nilai::where('id_karyawan', $karyawanIds)
                ->whereYear('periodes', $years);
            $nilai->update([
                'status' => 'sudah dikartap',
                'n_final' => number_format($postDataResult, 2)
            ]);

            return view('admin.fuzzy', [
                'averages' => $averages,
                'kriteria' => Kriteria::get(),
                'fuzzySets' => $fuzzySets,
                'results' => $postDataResult, 
                'postDataResult' => $postDataResult, // Include the result from postData

                'aggregatedResults' => $aggregatedResults,
                'decision' => $decision,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    // Controller method
    public function viewFuzzy(Request $request)
    {
        $averages = $request->input('averages');
        $kriteria = $request->input('kriteria');
        $fuzzySets = $request->input('fuzzySets');
        $postDataResult = $request->input('postDataResult');
        $aggregatedResults = $request->input('aggregatedResults');
        $decision = $request->input('decision');

        // Pass the data to the view
        return view('admin.fuzzy', compact(
            'averages',
            'kriteria',
            'fuzzySets',
            'postDataResult',
            'aggregatedResults',
            'decision'
        ));
    }
    public function postData($average)
    {
        // Logging data yang dikirim

        // Memetakan data input
        $mappedData = [
            'input1' => $average['Bekerjasama'] ?? null,
            'input2' => $average['Bekerja_sesuai_tugas'] ?? null,
            'input3' => $average['Kepedulian'] ?? null,
            'input4' => $average['K3'] ?? null,
            'input5' => $average['Patuh_dan_taat'] ?? null,
            'input6' => $average['Tepat_waktu'] ?? null,
            'input7' => $average['Kesiapan'] ?? null,
            'input8' => $average['Apel_shift'] ?? null,
            'input9' => $average['Kelengkapan_atribut'] ?? null,
        ];

        try {
            // Mengirimkan request
            $url = "http://127.0.0.1:5000";
            $response = Http::post($url . '/calculate', $mappedData);

            // Memeriksa status responsed
            // dd($response->body());
            // if ($response->successful()) {
            //     // Mendapatkan data JSON
            $data = $response->json();

            // Logging data respons
            // \Log::info('Response Data:', $data);

            return $data;
            return view('admin.fuzzy');
        } catch (\Exception $e) {
            // Menangani exception jika terjadi kesalahan pada request

            return [
                'error' => 'An error occurred',
                'message' => $e->getMessage(),
            ];
        }
    }
}
