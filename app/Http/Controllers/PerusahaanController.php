<?php

namespace App\Http\Controllers;

use App\Perusahaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerusahaanController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function getAllPerusahaan()
    {
        $list_perusahaan = Perusahaan::all();

        return json_encode($list_perusahaan);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_perusahaan = Perusahaan::all();

        return view('daftar_perusahaan_penyewa', compact('list_perusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tambah_perusahaan_penyewa');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $perusahaan = new Perusahaan();
            $perusahaan->nama_perusahaan = $request->nama_perusahaan;
            $perusahaan->alamat_perusahaan = $request->alamat;
            $perusahaan->email_perusahaan = $request->email;
            $perusahaan->NPWP = $request->npwp;
            $perusahaan->KTP = $request->ktp;
            $perusahaan->telepon = $request->notelp;
            $perusahaan->save();

            return json_encode('success');
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $perusahaan = Perusahaan::find($id);
        return view('edit_perusahaan_penyewa', compact('perusahaan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $perusahaan = Perusahaan::find($id);
            $perusahaan->nama_perusahaan = $request->nama_perusahaan;
            $perusahaan->alamat_perusahaan = $request->alamat;
            $perusahaan->email_perusahaan = $request->email;
            $perusahaan->NPWP = $request->npwp;
            $perusahaan->KTP = $request->ktp;
            $perusahaan->telepon = $request->notelp;
            $perusahaan->save();

            return json_encode('success');
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idperusahaan)
    {
        try {
            $perusahaan = DB::table('tbl_perusahaan_customer as pc')
                ->join('tbl_project as p', 'p.id_perusahaan', '=', 'pc.id_perusahaan')
                ->where('pc.id_perusahaan', $idperusahaan)
                ->get();

            if (count($perusahaan) == 0) {
                $perusahaan = DB::table('tbl_perusahaan_customer')
                    ->where('id_perusahaan', $idperusahaan)
                    ->delete();

                return response()->json("success");
            }

            return response()->json("Perusahaan memiliki project!");
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
