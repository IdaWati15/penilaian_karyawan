<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    //
    public function postData()
    {
        $averages = [
            'Bekerjasama' => 90.00,
            'Bekerja_sesuai_tugas' => 90.00,
            'Kepedulian' => 90.00,
            'K3' => 90.00,
            'Patuh_dan_taat' => 90.00,
            'Tepat_waktu' => 90.00,
            'Kesiapan' => 70.60	,
            'Apel_shift' => 90.00,
            'Kelengkapan_atribut' => 90.00	            ,
        ];
        $mappedData = [
            'input1' => $averages['Bekerjasama'] ?? null,
            'input2' => $averages['Bekerja_sesuai_tugas'] ?? null,
            'input3' => $averages['Kepedulian'] ?? null,
            'input4' => $averages['K3'] ?? null,
            'input5' => $averages['Patuh_dan_taat'] ?? null,
            'input6' => $averages['Tepat_waktu'] ?? null,
            'input7' => $averages['Kesiapan'] ?? null,
            'input8' => $averages['Apel_shift'] ?? null,
            'input9' => $averages['Kelengkapan_atribut'] ?? null,
        ];
        $response = Http::post('https://9706-103-191-196-54.ngrok-free.app/calculate',$mappedData);

        // Mendapatkan body response
        $responseBody = $response->body();

        // Mendapatkan data JSON
        $data = $response->json();

        return $data;
    }
}
