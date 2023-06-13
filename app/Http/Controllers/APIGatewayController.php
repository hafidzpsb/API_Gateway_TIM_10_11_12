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
        $response2 = $client2 -> request('GET', config('app.api.api2') . '/endpointapi2');
        $body2 = $response2 -> getBody() -> getContents();
        $data2 = json_decode($body2, true);
        $client3 = new Client();
        $response3 = $client3 -> request('GET', config('app.api.api3') . '/endpointapi3');
        $body3 = $response3 -> getBody() -> getContents();
        $data3 = json_decode($body3, true);
        return response()->json([
            'status' => 200,
            'message' => 'berhasil',
            'data_poliklinik' => $data
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
