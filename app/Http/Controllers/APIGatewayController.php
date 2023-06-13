<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class APIGatewayController extends Controller
{
    public function index()
    {
        $client = new Client();
        $response = $client -> request('GET', config('app.api.poliklinik') . '/pasien');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $client2 = new Client();
        $response2 = $client2 -> request('GET', config('app.api.administrasi') . '/PermintaanObat');
        $body2 = $response2 -> getBody() -> getContents();
        $data2 = json_decode($body2, true);
        $client3 = new Client();
        $response3 = $client3 -> request('GET', config('app.api.apoteker') . '/obat');
        $body3 = $response3 -> getBody() -> getContents();
        $data3 = json_decode($body3, true);
        return response()->json([
            'status' => 200,
            'message' => 'berhasil',
            'data_pasien_poliklinik' => $data,
            'data_permintaan_obat_administrasi' => $data2,
            'data_obat_apoteker' => $data3,
        ]);
    }
    public function isi_data_pasien_administrasi(Request $request) // isi data pasien di database administrasi
    {
        $values = [
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'waktu_masuk' => $request->waktu_masuk
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.administrasi') . '/pasien', [
                'form_params' => $values
            ]);
            return response()->json([
                'status' => 200,
                'response' => $values
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }
    public function isi_data_pasien_poliklinik(Request $request) // isi data pasien di database poliklinik
    {
        $client = new Client();
        $response = $client -> request('GET', config('app.api.administrasi') . '/pasien');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $isi = $data['data']; // ambil isi database pasien administrasi
        $data_terbaru = count($data['data']) - 1; // ambil data terbaru pasien administrasi
        $values = [
            'nama_pasien' => $isi[$data_terbaru]['nama_pasien'],
            'jenis_kelamin' => $isi[$data_terbaru]['jenis_kelamin'],
            'waktu_masuk' => $isi[$data_terbaru]['waktu_masuk'],
            'poli' => $request->poli
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.poliklinik') . '/pasien', [
                'form_params' => $values
            ]);
            return response()->json([
                'status' => 200,
                'response' => $values
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }
    public function isi_data_keterangan_pasien_poliklinik(Request $request, $id_pasien) // isi data tabel-tabel di poliklinik
    {
        $values = [
            'id_pasien' => $id_pasien,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'tekanan_darah' => $request->tekanan_darah,
            'detak_jantung' => $request->detak_jantung,
            'frekuensi_pernapasan' => $request->frekuensi_pernapasan,
            'suhu' => $request->suhu,
            'keluhan' => $request->keluhan
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.poliklinik') . '/rm', [
                'form_params' => $values
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
        $client = new Client();
        $response = $client -> request('GET', config('app.api.poliklinik') . '/rm');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $values2 = [
            'id_pasien' => $id_pasien,
            'dokter_pj' => $request->dokter_pj,
            'hasil_diagnosis' => $request->hasil_diagnosis,
            'tindakan_medis' => $request->tindakan_medis
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.poliklinik') . '/konsul', [
                'form_params' => $values2
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
        $client = new Client();
        $response = $client -> request('GET', config('app.api.poliklinik') . '/konsul');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $isi = $data['response'];
        $values3 = [
            'id_pasien' => $id_pasien,
            'id_konsul' => $isi[$id_pasien-1]['id_konsul'],
            'resep_obat' => $request->resep_obat,
            'jumlah_pembayaran' => $request->jumlah_pembayaran,
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.poliklinik') . '/resep', [
                'form_params' => $values3
            ]);
            return response()->json([
                'status' => 200,
                'data_rm' => $values,
                'data_konsul' => $values2,
                'data_resep' => $values3
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }
    public function isi_data_resep_apoteker(Request $request)
    {
        $client = new Client();
        $response = $client -> request('GET', config('app.api.poliklinik') . '/resep');
        $body = $response -> getBody() -> getContents();
        $data = json_decode($body, true);
        $isi = $data['response'];
        $data_terbaru = count($data['response']) - 1;
        $client2 = new Client();
        $response2 = $client2 -> request('GET', config('app.api.poliklinik') . '/konsul');
        $body2 = $response2 -> getBody() -> getContents();
        $data2 = json_decode($body2, true);
        $isi2 = $data2['response'];
        $data_terbaru2 = count($data2['response']) - 1;
        $values = [
            'id_pasien' => $isi[$data_terbaru]['id_pasien'],
            'tanggal_resep' => Carbon::parse($isi[$data_terbaru]['created_at'])->toDateString(),
            'resep' => $isi[$data_terbaru]['resep_obat'],
            'nama_dokter' => $isi2[$data_terbaru2]['dokter_pj']
        ];
        try{
            $client = new Client();
            $response = $client -> request('POST', config('app.api.apoteker') . '/resep', [
                'form_params' => $values
            ]);
            return response()->json([
                'status' => 200,
                'response' => $values
            ]);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getResponse()->getBody()->getContents();
        }
    }
}