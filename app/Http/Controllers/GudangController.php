<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gudang;
use App\Role;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GudangController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function generateKodeGudang()
    {
        try {
            $latest_id_gudang = DB::table('tbl_gudang')->orderBy('id_gudang', 'DESC')->limit(1)->get();
            $new_id_gudang = "";
            if (count($latest_id_gudang) == 0) {
                $new_id_gudang = "SG.180001";
            } else {
                $explode_kodegudang = explode(".", $latest_id_gudang[0]->kode_gudang);
                $new_id_gudang = (int) $explode_kodegudang[1] + 1;
                // $new_id_gudang = sprintf("%'.04d", $new_id_gudang);
                $new_id_gudang = "SG." . $new_id_gudang;
            }
            return $new_id_gudang;
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllGudang()
    {
        $list_gudang = DB::table('tbl_gudang as g')
            ->select('g.id_gudang as id_gudang', 'g.kode_gudang as kode_gudang', 'g.nama_gudang as nama_gudang', 'g.alamat_gudang as alamat_gudang', 'kb.nama_kabupaten as nama_kabupaten', 'p.nama_provinsi as nama_provinsi')
            ->join('tbl_kabupaten as kb', 'g.id_kabupaten', '=', 'kb.id_kabupaten')
            ->join('tbl_provinsi as p', 'kb.id_provinsi', '=', 'p.id_provinsi')
            ->orderBy('g.id_gudang', 'desc')
            ->get();

        return response()->json($list_gudang);
    }

    public function index()
    {
        $list_gudang = DB::table('tbl_gudang as g')
            ->select('g.id_gudang as id_gudang', 'g.kode_gudang as kode_gudang', 'g.nama_gudang as nama_gudang', 'g.alamat_gudang as alamat_gudang', 'kb.nama_kabupaten as nama_kabupaten', 'p.nama_provinsi as nama_provinsi')
            ->join('tbl_kabupaten as kb', 'g.id_kabupaten', '=', 'kb.id_kabupaten')
            ->join('tbl_provinsi as p', 'kb.id_provinsi', '=', 'p.id_provinsi')
            ->orderBy('g.id_gudang', 'desc')
            ->get();

        return view('daftar_gudang', compact('list_gudang'));
        // $view = view('daftar_gudang', compact('list_gudang'));
        // $view->renderSections()['content'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_provinsi = json_decode(app(UtilityController::class)->getProvinsi());
        return view('tambah_gudang', compact('list_provinsi'));
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
            $generate_kode_gudang = $this->generateKodeGudang();

            $gudang = new Gudang();
            $gudang->kode_gudang = $generate_kode_gudang;
            $gudang->nama_gudang = $request->nama_gudang;
            $gudang->alamat_gudang = $request->alamat_lengkap;
            $gudang->lat = 0;
            $gudang->long = 0;
            $gudang->id_kabupaten = $request->kabupaten;
            $gudang->save();

            $gudang = DB::table('tbl_gudang')
                ->orderBy('id_gudang', 'desc')
                ->limit(1)
                ->get();

            DB::table('tbl_locator')
                ->insert([
                    'nama_locator' => 'n/a',
                    'level' => 0,
                    'parent' => 0,
                    'status' => 1,
                    'id_type_locator' => 1,
                    'id_gudang' => $gudang[0]->id_gudang
                ]);

            $new_request = new Request([
                'action' => "tambah_gudang", 'nama_gudang' => $gudang[0]->nama_gudang
            ]);
            app(LogController::class)->log($new_request);

            return \json_encode('success');
        } catch (\Exception $error) {
            return \json_encode($error->getMessage());
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
    public function edit($id)
    {
        $gudang = DB::table('tbl_gudang as g')
            ->select('g.id_gudang as id_gudang', 'g.kode_gudang as kode_gudang', 'g.nama_gudang as nama_gudang', 'g.alamat_gudang as alamat_gudang', 'kb.id_kabupaten as id_kabupaten', 'kb.nama_kabupaten as nama_kabupaten', 'p.id_provinsi as id_provinsi', 'p.nama_provinsi as nama_provinsi')
            ->join('tbl_kabupaten as kb', 'g.id_kabupaten', '=', 'kb.id_kabupaten')
            ->join('tbl_provinsi as p', 'kb.id_provinsi', '=', 'p.id_provinsi')
            ->where('g.kode_gudang', '=', $id)
            ->get();


        $list_provinsi = json_decode(app(UtilityController::class)->getProvinsi());
        return view('edit_gudang', \compact('gudang', 'list_provinsi'));
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
            $gudang = Gudang::find($id);
            $gudang->nama_gudang = $request->nama_gudang;
            $gudang->alamat_gudang = $request->alamat_lengkap;
            $gudang->id_kabupaten = $request->kabupaten;
            $gudang->save();

            $new_request = new Request([
                'action' => "edit_gudang", 'id_gudang' => $id
            ]);
            app(LogController::class)->log($new_request);

            return \json_encode('success');
        } catch (Exception $error) {
            return \json_encode($error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kodegudang)
    {
        try {


            $gudang = DB::table('tbl_gudang as g')
                ->join('tbl_project_has_gudang as pg', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            if (count($gudang) > 0) {
                return json_encode(false);
            } else {
                $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();

                $locator = DB::table('tbl_locator')
                    ->where('id_gudang', $gudang[0]->id_gudang)
                    ->delete();

                $new_request = new Request([
                    'action' => "hapus_gudang", 'kodegudang' => $kodegudang
                ]);
                app(LogController::class)->log($new_request);

                $gudang = DB::table('tbl_gudang')
                    ->where('kode_gudang', $kodegudang)
                    ->delete();
            }

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
