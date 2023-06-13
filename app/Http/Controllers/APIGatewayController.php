<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class APIGatewayController extends Controller
{
    public function index() // Ambil data dari API
    {
        $client = new Client();
        $response = $client -> request('GET', config('app.api.poliklinik') . '/pasien');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $client2 = new Client();
        $response2 = $client2 -> request('GET', config('app.api.administrasi') . '/PermintaanObat');
        $body2 = $response2 -> getBody() -> getContents();
        $data2 = json_decode($body2, true);

        return response()->json([
            'status' => 200,
            'message' => 'berhasil',
            'data_poliklinik' => $data,
            'data_administrasi' => $data2,
        ]);
    }
    public function show($id_konsul)
    {
    }
    public function store(Request $request)
    {
    }
    public function update(Request $request, $id_konsul)
    {
    }
    public function delete(Request $request, $id_konsul)
    {
    }
}
