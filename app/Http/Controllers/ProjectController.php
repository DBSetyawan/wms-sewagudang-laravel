<?php

namespace App\Http\Controllers;

use App\Gudang;
use App\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ProjectController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function testingRole()
    {
        dd(Auth::user()->hakAkses);
    }

    public function getAllProject()
    {
        $list_project = DB::table('tbl_project as p')
            ->join('tbl_perusahaan_customer as pc', 'p.id_perusahaan', '=', 'pc.id_perusahaan')
            ->get();

        return response()->json($list_project);
    }

    public function checkTemplateIsExist($kodeproject)
    {
        try {
            $nama_file_template_inbound = "./template_inbound/inbound" . "_" . $kodeproject . ".json";
            $template_inbound = file_exists($nama_file_template_inbound);

            $nama_file_template_outbound = "./template_outbound/outbound" . "_" . $kodeproject . ".json";
            $template_outbound = file_exists($nama_file_template_outbound);

            if ($template_inbound == 1 && $template_outbound == 1) {
                return "";
            } else {
                return "disabled";
            }
            // echo $template_outbound;
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }



    public function generateKodeProject($idperusahaan)
    {
        try {
            $list_project = DB::table('tbl_project as p')
                ->join('tbl_perusahaan_customer as pc', 'pc.id_perusahaan', 'p.id_perusahaan')
                ->where('p.id_perusahaan', $idperusahaan)
                ->orderBy('p.id_project', 'desc')
                ->get();

            $short_kode_project = "";
            if (count($list_project) > 0) {
                $short_kode_project = substr(trim($list_project[0]->nama_perusahaan), 0, 2);
                $last_kode_project = substr($list_project[0]->kode_project, 5);
                $new_kode_project = (int) $last_kode_project + 1;
                $new_kode_project = sprintf("%'.04d", $new_kode_project);
                $new_kode_project = $short_kode_project . $idperusahaan . $new_kode_project;
                // dd($list_project);
                return $new_kode_project;
            } else {
                $perusahaan = DB::table('tbl_perusahaan_customer')->where('id_perusahaan', $idperusahaan)->get();
                $short_kode_project = substr(trim($perusahaan[0]->nama_perusahaan), 0, 2)  . $idperusahaan;
            }

            return $short_kode_project . "0001";
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
        $list_project = $this->getAllProject()->getData();

        foreach ($list_project as $project) {
            $project->template_exist = $this->checkTemplateIsExist($project->kode_project);
        }

        // dd($list_project);
        return view('daftar_project', compact('list_project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_perusahaan = json_decode(app(PerusahaanController::class)->getAllPerusahaan());
        return view('tambah_project', compact('list_perusahaan'));
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

            $kode_project = $this->generateKodeProject($request->perusahaan_penyewa);
            $project = new Project();
            $project->kode_project =  $kode_project;
            $project->nama_project = $request->nama_project;
            $project->tanggal_project = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_kontrak)));
            $project->referensi = $request->referensi;
            $project->id_perusahaan = $request->perusahaan_penyewa;
            $project->save();

            $project = DB::table('tbl_project')
                ->select('id_project')
                ->orderBy('id_project', 'DESC')
                ->limit(1)
                ->get();

            $new_request = new Request([
                'action' => "tambah_project", 'id_perusahaan' => $request->perusahaan_penyewa
            ]);
            app(LogController::class)->log($new_request);

            return json_encode("success");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($kodeproject)
    {
        $project = DB::table("tbl_project as p")
            ->join("tbl_perusahaan_customer as pp", "p.id_perusahaan", "=", "pp.id_Perusahaan")
            ->where('p.kode_project', $kodeproject)
            ->get();

        $list_perusahaan = json_decode(app(PerusahaanController::class)->getAllPerusahaan());
        return view('edit_project', compact(['project', 'kodeproject', 'list_perusahaan']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject)
    {
        try {
            $project = Project::where("kode_project", $kodeproject)->get();

            $update_project = DB::table('tbl_project')
                ->where('id_project', $project[0]->id_project)
                ->update([
                    'nama_project' => $request->nama_project,
                    'tanggal_project' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_kontrak))),
                    'referensi' => "",
                    'id_perusahaan' => $request->perusahaan_penyewa
                ]);

            $new_request = new Request([
                'action' => "edit_project", 'kodeproject' => $kodeproject
            ]);
            app(LogController::class)->log($new_request);

            return json_encode("success");
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
    public function destroy($kodeproject)
    {
        try {
            $project_has_gudang = DB::table('tbl_project as p')
                ->join('tbl_project_has_gudang as pg', 'p.id_project', '=', 'pg.id_project')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->get();

            if (count($project_has_gudang) > 0) {
                return json_encode("Project gagal dihapus karena project memiliki gudang!");
            } else {
                if (file_exists('./template_inbound/inbound_' . $kodeproject . '.json') == 1) {
                    unlink('./template_inbound/inbound_' . $kodeproject . '.json');
                }
                if (file_exists('./template_inbound/outbound_' . $kodeproject . '.json') == 1) {
                    unlink('./template_inbound/outbound_' . $kodeproject . '.json');
                }

                $new_request = new Request([
                    'action' => "hapus_project", 'kodeproject' => $kodeproject
                ]);
                app(LogController::class)->log($new_request);

                DB::table('tbl_project')
                    ->where('kode_project', $kodeproject)
                    ->delete();


                return json_encode("success");
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
