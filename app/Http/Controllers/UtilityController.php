<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kecamatan;
use App\Kabupaten;
use App\Provinsi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UtilityController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function tambahDataVersionAppsMobile(Request $request)
    {
        try {
            DB::table('tbl_version_apps_mobile')
                ->insert([
                    'version_code' => $request->version_code,
                    'version_name' => $request->version_name,
                    'priority' => $request->priority,
                    'link' => $request->link,
                    'desc' => $request->desc
                ]);

            return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function landingPageNoHakAkses()
    {
        try {
            $list_project_and_gudang = DB::table('tbl_gudang as g')
                ->join('tbl_project_has_gudang as pg', 'pg.id_gudang', '=', 'g.id_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                ->join('tbl_hak_akses as ha', 'ha.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
                ->where('ha.id_user', Auth::user()->id)
                ->get();

            if (count($list_project_and_gudang) == 0) {
                return view('errornoakses');
            } else {
                $kodeproject = $list_project_and_gudang[0]->kode_project;
                $kodegudang = $list_project_and_gudang[0]->kode_gudang;
                return Redirect::route('inventory.index', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]);
            }
            // return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function editDataVersionAppsMobile(Request $request, $versionCode)
    {
        try {
            DB::table('tbl_version_apps_mobile')
                ->where('version_code', $versionCode)
                ->update([
                    'version_name' => $request->version_name,
                    'priority' => $request->priority,
                    'link' => $request->link,
                    'desc' => $request->desc
                ]);

            return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function deleteDataVersionAppsMobile($versionCode)
    {
        try {
            DB::table('tbl_version_apps_mobile')
                ->where('version_code', $versionCode)
                ->delete();

            return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function getAllVersionApps()
    {
        try {
            $list_verson_apps = DB::table('tbl_version_apps_mobile')
                ->get();

            return response()->json($list_verson_apps);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function checkerDataVersionAppsMobile(Request $request)
    {
        try {
            $lastest_version_in_DB = DB::table('tbl_version_apps_mobile')
                ->orderBy('version_code', 'desc')
                ->limit(1)
                ->get();

            $response = [];
            if (count($lastest_version_in_DB) > 0) {
                if ($lastest_version_in_DB[0]->version_code > $request->version_code) {
                    array_push($response, [
                        'ket' => "update",
                        'desc' => $lastest_version_in_DB[0]->desc,
                        'priority' => $lastest_version_in_DB[0]->priority,
                        'versionName' => $lastest_version_in_DB[0]->version_name,
                        'link' => $lastest_version_in_DB[0]->link
                    ]);
                } else if ($lastest_version_in_DB[0]->version_code <= $request->version_code) {
                    array_push($response, [
                        'ket' => "terbaru",
                        'desc' => "",
                        'priority' => "",
                        'versionName' => ""
                    ]);
                }
            } else {
                array_push($response, ['error' => 'Version belum ada']);
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function getProvinsi()
    {
        $list_provinsi = Provinsi::all();

        return \json_encode($list_provinsi);
    }

    public function getKabupaten(Request $request)
    {
        $list_kabupaten = DB::table('tbl_kabupaten')->where('id_provinsi', '=', $request->id_provinsi)->get();

        return \json_encode($list_kabupaten);
    }

    public function getKecamatan(Request $request)
    {
        $list_kecamatan = DB::table('tbl_kecamatan')->where('id_kabupaten', '=', $request->id_kabupaten)->get();
        return \json_encode($list_kecamatan);
    }

    public function getLatestIdGudang()
    {
        $latest_id_gudang = DB::table('tbl_gudang')->orderBy('id_gudang', 'DESC')->limit(1)->get();
        $new_id_gudang = "";
        if (count($latest_id_gudang) == 0) {
            $new_id_gudang = "SG.180001";
        } else {
            $explode_kodegudang = explode(".", $latest_id_gudang[0]->kode_gudang);
            $new_id_gudang = (int) $explode_kodegudang + 1;
            $new_id_gudang = sprintf("%'.04d", $new_id_gudang);
            $new_id_gudang = "SG.18" . $new_id_gudang;
        }
        return \json_encode($new_id_gudang);
    }

    public function openPageDownloadDokumentasi()
    {
        try {
            return view('downloaddokumentasi');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function downloadDocumentasiManualBook($platform)
    {
        $filename = "";
        if ($platform == "Mobile") {
            $filename = "./doc/usermanual_mobile.pdf";
        } else {
            $filename = "./doc/usermanual_web.pdf";
        }
        try {
            if (file_exists($filename)) {

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename));
                readfile($filename);
                exit;
            }
        } catch (\Throwable $th) {
            return response()->json($filename);
        }
    }

    public function updateCBM()
    {
        try {
            $array = [
                ["0.111671", "pthar0001-5235"],
                ["0.13432", "EC5"],
                ["0.10913175", "ECL"],
                ["0.046529", "pthar0001-5233"],
                ["0.146264375", "pthar0001-5234"],
                ["0.095472", "pthar0001-5611"],
                ["0.061006", "pthar0001-5230"],
                ["0.04381875", "pthar0001-5232"],
                ["0.0576", "pthar0001-5619"],
                ["0.1124235", "pthar0001-5431"],
                ["0.0934605", "pthar0001-5610"],
                ["0.1124235", "HIMFNC01"],
                ["0.08", "pthar0001-5411"],
                [0, "pthar0001-5346"],
                [0, "FNC-01HIM"],
                [0, "NAC"],
                [0, "FNC-01"],
                [0, "NAC-01"],
                ["0.0934605", "HIMFNC02"],
                ["0.0576", "JLBL0001"],
                ["0.12036", "JLBL0004"],
                ["0.12036", "JLBL0005"],
                ["0.15111", "EC.5"],
                ["0.08", "pthar0001-5140"],
                ["0.113094", "AST-1000"],
                ["0.15111", "EC.5"],
                [0, "NAC-01"],
                [0, "FNC-01"],
                ["0.113094", "AST-750"],
                ["0.09520875", "E-TFT"],
                ["0.1083915", "E-2C"]
            ];

            for ($i = 0; $i < count($array); $i++) {
                DB::table('tbl_item')
                    ->where('kode_item', $array[$i][1])
                    ->update([
                        'cbm' => doubleval($array[$i][0])
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    // public function getUOM()
    // {
    //     $list_uom = DB::table('tbl_uom')->get();

    //     return json_encode($list_uom);
    // }
}
