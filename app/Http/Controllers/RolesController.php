<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gudang;
use App\Modul;
use App\Project;
use Illuminate\Support\Facades\DB;


class RolesController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function getHakAkses($idrole)
    {
        try {
            $hak_akses_gudang = DB::table('tbl_user as u')
                ->join('tbl_role as r', 'u.id_role', '=', 'r.id_role')
                ->join('tbl_akses_gudang as ag', 'r.id_role', '=', 'ag.id_role')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'ag.id_gudang')
                ->where('r.id_role', $idrole)
                ->get();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getAllRole()
    {
        try {
            $list_role = DB::table('tbl_role')
                ->get();

            return json_encode($list_role);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_role = DB::table('tbl_role')->get();
        return view('daftar_role', compact('list_role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $list_modul = Modul::all();
        return view('tambah_role', compact(['list_modul']));
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
            // $list_gudang = json_decode($request->list_gudang, true);
            // $list_project = json_decode($request->list_project, true);
            $list_modul = json_decode($request->list_modul, true);

            DB::table('tbl_role')
                ->insert(['nama_role' => $request->nama_role]);

            $last_inserted_role = DB::table('tbl_role')->orderBy('id_role', 'desc')->limit(1)->get();

            // foreach ($list_gudang as $gudang) {
            //     DB::table('tbl_akses_gudang')
            //         ->insert([
            //             'id_role' => $last_inserted_role[0]->id_role,
            //             'id_gudang' => $gudang
            //         ]);
            // }

            // foreach ($list_project as $project) {
            //     DB::table('tbl_akses_project')
            //         ->insert([
            //             'id_project' => $project,
            //             'id_role' => $last_inserted_role[0]->id_role
            //         ]);
            // }

            foreach ($list_modul as $modul) {
                DB::table('tbl_akses_modul')
                    ->insert([
                        'id_role' => $last_inserted_role[0]->id_role,
                        'id_modul' => $modul
                    ]);
            }

            $new_request = new Request([
                'action' => 'tambah_role', 'nama_role' => $last_inserted_role[0]->nama_role
            ]);
            app(LogController::class)->log($new_request);

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
    public function edit($idrole)
    {
        try {
            $role = DB::table('tbl_role')->where('id_role', $idrole)->get();

            $list_modul = DB::table('tbl_modul')->get();
            $list_modul_selected = DB::table('tbl_modul as m')
                ->join('tbl_akses_modul as am', 'm.id_modul', '=', 'am.id_modul')
                ->join('tbl_role as r', 'am.id_role', '=', 'r.id_role')
                ->where("r.id_role", '=', $idrole)
                ->get();

            return view('edit_role', compact(['role', 'idrole', 'list_modul', 'list_modul_selected']));
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idrole)
    {
        try {
            $list_modul = json_decode($request->list_modul, true);

            DB::table('tbl_role')->where('id_role', $idrole)->update(['nama_role' => $request->nama_role]);

            DB::table('tbl_akses_modul')->where("id_role", $idrole)->delete();

            foreach ($list_modul as $modul) {
                DB::table('tbl_akses_modul')
                    ->insert([
                        'id_role' => $idrole,
                        'id_modul' => $modul
                    ]);
            }

            $new_request = new Request([
                'action' => 'edit_role', 'nama_role' => $request->nama_role
            ]);
            app(LogController::class)->log($new_request);
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
    public function destroy($idrole)
    {
        try {
            $list_user_use_role = DB::table('users as u')
                ->join('tbl_role as r', 'r.id_role', '=', 'u.id_role')
                ->where('r.id_role', $idrole)
                ->get();

            if (count($list_user_use_role) == 0) {
                $role = DB::table('tbl_role')->where('id_role', $idrole)->get();
                DB::table('tbl_akses_modul')->where("id_role", $idrole)->delete();
                DB::table('tbl_role')->where('id_role', $idrole)->delete();

                $new_request = new Request(['action' => 'hapus_role', 'nama_role' => $role[0]->nama_role]);
                app(LogController::class)->log($new_request);

                return "success";
            } else {
                return json_encode('User masih menggunakan role tersebut');
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
