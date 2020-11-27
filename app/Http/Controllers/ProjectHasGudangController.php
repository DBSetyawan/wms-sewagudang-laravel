<?php

namespace App\Http\Controllers;

use App\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectHasGudangController extends Controller
{
    public function getInformationProjectGudang($kodeproject, $kodegudang)
    {
        try {
            $projectgudang = DB::table('tbl_project as p')
                ->join("tbl_project_has_gudang as pg", 'p.id_project', '=', 'pg.id_project')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where("p.kode_project", $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            return response()->json($projectgudang, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function getGudangFromProject(Request $request)
    {
        try {
            $list_project = (gettype($request->list_project) == "array") ? $request->list_project  :  json_decode($request->list_project, true);
            $result_project = DB::table('tbl_project as p')
                ->join('tbl_project_has_gudang as gp', 'p.id_project', '=', 'gp.id_Project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->whereIn('p.id_project', $list_project)
                ->get();

            return response()->json($result_project);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kodeproject)
    {
        $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
        $list_gudang = DB::table('tbl_gudang as g')
            ->join('tbl_project_has_gudang as pg', 'pg.id_gudang', '=', 'g.id_gudang')
            ->join('tbl_kabupaten as kb', 'g.id_kabupaten', '=', 'kb.id_kabupaten')
            ->join('tbl_provinsi as pr', 'kb.id_provinsi', '=', 'pr.id_provinsi')
            // ->join('tbl_hak_akses as ha', 'ha.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
            ->where('pg.id_project', '=', $project[0]->id_project)
            // ->where('ha.id_user', Auth::user()->id)
            ->get();



        return view('daftar_project_gudang', compact(['list_gudang', 'kodeproject']));
        // dd($list_gudang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject)
    {
        $list_gudang = DB::table('tbl_gudang')
            ->get();
        $list_user = DB::table('users as u')
            ->join('tbl_role as r', 'u.id_role', '=', 'r.id_role')
            ->where('nama_role', '!=', 'Admin')
            ->get();
        return view('tambah_project_gudang', compact(['list_gudang', 'kodeproject', 'list_user']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodeproject)
    {
        try {
            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            $is_projectgudang_exist = DB::table('tbl_project_has_gudang')
                ->where('id_gudang', $request->idgudang)
                ->where('id_project', $project[0]->id_project)
                ->count();

            if ($is_projectgudang_exist == 0) {
                $project = DB::table('tbl_project')
                    ->where('kode_project', $kodeproject)
                    ->get();
                $insertToGudangHasProject = DB::table('tbl_project_has_gudang')->insert([
                    [
                        'id_project' =>  $project[0]->id_project,
                        'id_gudang' => $request->idgudang
                    ],
                ]);

                $last_inserted_project_gudang = DB::table('tbl_project_has_gudang')
                    ->where('id_project', $project[0]->id_project)
                    ->orderBy("id_project_has_gudang", 'desc')
                    ->get();

                $list_user = json_decode($request->list_user, true);
                foreach ($list_user as $user) {
                    DB::table('tbl_hak_akses')
                        ->insert([
                            'id_user' => $user,
                            'id_project_has_gudang' => $last_inserted_project_gudang[0]->id_project_has_gudang
                        ]);
                }

                return json_encode("success");
            } else {
                return json_encode('gagal');
            }
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
    public function edit($kodeproject, $kodegudang)
    {
        $project_has_gudang = DB::table('tbl_gudang as g')
            ->join("tbl_project_has_gudang as pg", 'g.id_gudang', '=', 'pg.id_gudang')
            ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
            ->where('p.kode_project', $kodeproject)
            ->where('g.kode_gudang', $kodegudang)
            ->get();

        $list_gudang = DB::table('tbl_gudang')
            ->get();

        $list_user = DB::table('users')
            ->where('id_role', '<>', 4)
            ->get();

        $list_hak_akses_user = DB::table('tbl_hak_akses as ha')
            ->join('tbl_project_has_gudang as pg', 'pg.id_project_has_gudang', '=', 'ha.id_project_has_gudang')
            ->join('users as u', 'ha.id_user', '=', 'u.id')
            ->where('ha.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
            ->get();

        return view('edit_project_gudang', compact(['list_gudang', 'kodeproject', 'kodegudang', 'list_hak_akses_user', 'list_user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $list_user = json_decode($request->list_user, true);

            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();



            $project_has_gudang = DB::table('tbl_gudang as g')
                ->join("tbl_project_has_gudang as pg", 'g.id_gudang', '=', 'pg.id_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            $hak_akses_project_gudang = DB::table('tbl_project_has_gudang as pg')
                ->join('tbl_hak_akses as ha', 'pg.id_project_has_gudang', '=', 'ha.id_project_has_gudang')
                ->join('users as u', 'u.id', '=', 'ha.id_user')
                ->where('pg.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->whereIn('u.id', $list_user)
                ->get();

            $is_projectgudang_exist = DB::table('tbl_project_has_gudang')
                ->where('id_gudang', $request->idgudang)
                ->where('id_project', $project[0]->id_project)
                ->get();

            $action = "";
            if (count($is_projectgudang_exist) < 1) {
                $action = "update_gudang";
            } else if ($project_has_gudang[0]->id_gudang != $is_projectgudang_exist[0]->id_gudang) {
                $action = "";
            } else if ($project_has_gudang[0]->id_gudang == $is_projectgudang_exist[0]->id_gudang) {
                $action = "update_hak_akses";
            }


            if ($action == "update_gudang") {
                $gudang = DB::table('tbl_gudang')
                    ->where('kode_gudang', $kodegudang)
                    ->get();

                DB::table('tbl_project_has_gudang')
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->update([
                        'id_gudang' => $request->idgudang,
                    ]);

                DB::table('tbl_hak_akses')
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->delete();

                foreach ($list_user as $user) {
                    DB::table('tbl_hak_akses')
                        ->insert([
                            'id_user' => $user,
                            'id_project_has_gudang' => $project_has_gudang[0]->id_project_has_gudang
                        ]);
                }
                return json_encode("success");
            } else if ($action == "update_hak_akses") {
                DB::table('tbl_hak_akses')
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->delete();

                foreach ($list_user as $user) {
                    DB::table('tbl_hak_akses')
                        ->insert([
                            'id_user' => $user,
                            'id_project_has_gudang' => $project_has_gudang[0]->id_project_has_gudang
                        ]);
                }
                return json_encode("success");
            } else {
                return json_encode('gagal');
            }
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
    public function destroy($kodeproject, $kodegudang)
    {
        try {
            $gudang_has_item = DB::table("tbl_gudang as g")
                ->join('tbl_project_has_gudang as pg', 'g.id_gudang', '=', 'pg.id_gudang')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_item as i', 'pg.id_project_has_gudang', '=', 'i.id_project_has_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            if (count($gudang_has_item) > 0) {
                return json_encode("Gudang tidak dapat dihapus karena gudang masih digunakan!");
            } else {
                $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
                $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();

                $project_has_gudang = DB::table('tbl_project_has_gudang')
                    ->where('id_gudang', $gudang[0]->id_gudang)
                    ->where('id_project', $project[0]->id_project)
                    ->get();

                DB::table('tbl_hak_akses')
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->delete();

                DB::table('tbl_project_has_gudang')
                    ->where('id_gudang', $gudang[0]->id_gudang)
                    ->where('id_project', $project[0]->id_project)
                    ->delete();

                return json_encode("success");
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
