<?php

namespace App\Http\Controllers;

use App\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_modul = DB::table('tbl_modul')->get();
        return view('daftar_modul', compact('list_modul'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tambah_modul');
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
            $modul = new Modul();
            $modul->nama_modul = $request->nama_modul;
            $modul->save();

            return json_encode('success');
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($idmodul)
    {
        $modul = Modul::find($idmodul);
        return view('edit_modul', compact('modul', 'idmodul'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idmodul)
    {
        try {
            $modul = Modul::find($idmodul);;
            $modul->nama_modul = $request->nama_modul;
            $modul->save();

            return json_encode('success');
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($idmodul)
    {
        try {
            DB::table('tbl_modul')
                ->where('id_modul', $idmodul)
                ->delete();

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
