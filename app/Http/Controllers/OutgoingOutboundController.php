<?php

namespace App\Http\Controllers;

use App\Jobs\ProsesLoading;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;


class OutgoingOutboundController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function generateKodeItem($kodeproject, $kodegudang)
    {
        try {
            $kode_project = substr($kodeproject, 0, 4);



            $project_has_gudang = DB::table('tbl_project_has_gudang as pg')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_gudang as g', 'pg.id_gudang', '=', 'g.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            $item = DB::table('tbl_item')
                ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->orderBy('kode_item', 'desc')
                ->limit(1)
                ->get();

            if (count($item) != 0) {
                $lastKodeItem = substr($item[0]->kode_item, -4);
                $lastKodeItem = (int) $lastKodeItem + 1;
                $lastKodeItem = str_pad($lastKodeItem, (5 - strlen($lastKodeItem)), "0", STR_PAD_LEFT);
                $new_kode_item = $kode_project . $project_has_gudang[0]->id_project_has_gudang . $lastKodeItem;
                return $new_kode_item;
            }

            return $kode_project . $project_has_gudang[0]->id_project_has_gudang . '0001';
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }


    public function prosesLoading(Request $request, $kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        $newRequest = new stdClass();
        $newRequest->idinventorydetail = $request->idinventorydetail;
        $newRequest->qty = $request->qty;
        $newRequest->idoutbounddetail = $request->idoutbounddetail;
        $newRequest->iditem = $request->iditem;
        $newRequest->nooutgoingoutbound = $request->nooutgoingoutbound;
        // return json_encode($newRequest);

        $job = new ProsesLoading($newRequest, $kodeproject, $kodegudang, $nooutbound, $nooutgoing);
        // ProcessIncoming::dispatchNow()
        dispatch_now($job);

        return $job->getResponse();
    }

    public function getOutgoingDetail($kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        try {
            $info_outbound = DB::table('tbl_outgoing_outbound as oo')
                ->join('tbl_outbound as o', 'oo.id_outbound', '=', 'o.id_outbound')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join('tbl_item as i', 'i.id_item', '=', 'od.id_item')
                ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->join('tbl_locator as l', 'l.id_locator', '=', 'id.id_locator')
                ->where('oo.no_outgoing', $nooutgoing)
                ->where('id.picked', '!=', 0)
                ->get();

            return response()->json($info_outbound);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function generateNoOutgoingOutbound()
    {
        $currentDate = date("dmy");
        $latestId_in_db = DB::table('tbl_outgoing_outbound')
            ->where('no_outgoing', 'like', "%$currentDate%")
            ->orderBy('no_outgoing', 'desc')
            ->limit(1)
            ->get();

        if (count($latestId_in_db) != 0) {
            $num_no_outgoing_outbound = substr($latestId_in_db[0]->no_outgoing, 7);
            $new_num_no_outgoing_outbound = $num_no_outgoing_outbound + 1;
            $new_num_no_outgoing_outbound = sprintf("%'.04d", $new_num_no_outgoing_outbound);
            $new_no_outbound = "OO" . $currentDate . $new_num_no_outgoing_outbound;

            return response()->json($new_no_outbound);
        }

        return response()->json("OO" . $currentDate . "0001");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getListOutgoing(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        $list_outgoing = DB::table('tbl_outgoing_outbound as oo')
            ->join('tbl_outbound as o', 'oo.id_outbound', '=', 'o.id_outbound')
            ->where('o.no_outbound', $nooutbound)
            ->get();

        return response()->json($list_outgoing);
    }

    public function index(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $list_outgoing = $this->getListOutgoing($request, $kodeproject, $kodegudang, $nooutbound)->getData();
        $outbound = app(OutboundController::class)->getInformationOutbound($nooutbound)->getData();
        return view('daftar_outgoing_outbound', compact(['list_outgoing', 'kodegudang', 'kodeproject', 'nooutbound', 'projectGudang', 'outbound']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject, $kodegudang, $nooutbound)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $nama_file_template_inbound = file_get_contents("./template_outbound/outbound" . "_" . $kodeproject . ".json");
        $json = json_decode($nama_file_template_inbound, true);
        $form_outgoing_outbound = $json['form_outgoing_outbound'];
        $outbound = app(OutboundController::class)->getInformationOutbound($nooutbound)->getData();

        $new_nooutgoing = $this->generateNoOutgoingOutbound()->getData();
        return view('tambah_ougoing_outbound', compact(['kodegudang', 'kodeproject', 'nooutbound', 'form_outgoing_outbound', 'new_nooutgoing', 'projectGudang', 'outbound']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->get();

            DB::table('tbl_outgoing_outbound')
                ->insert([
                    'no_outgoing' => $request->nooutgoingoutbound,
                    'tanggal' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_outgoing))),
                    'custom_field' => $request->outgoing_outbound_value,
                    'id_outbound' => $outbound[0]->id_outbound
                ]);

            $new_request = new Request([
                'action' => 'tambah_outgoing',
                'no_outbound' => $nooutbound,
                'no_outgoing' => $request->nooutgoingoutbound
            ]);
            app(LogController::class)->log($new_request);

            return response()->json("success");
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
    public function edit($kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        try {
            $outbound = app(OutboundController::class)->getInformationOutbound($nooutbound)->getData();
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $outgoing = DB::table('tbl_outgoing_outbound')
                ->where('no_outgoing', $nooutgoing)
                ->get();

            $nama_file_template_inbound = file_get_contents("./template_outbound/outbound" . "_" . $kodeproject . ".json");
            $json = json_decode($nama_file_template_inbound, true);
            // echo $json;
            $form_outgoing_outbound = $json['form_outgoing_outbound'];



            return view('edit_outgoing_outbound', compact(['outgoing', 'kodegudang', 'kodeproject', 'nooutbound', 'nooutgoing', 'form_outgoing_outbound', 'projectGudang', 'outbound']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        try {
            $outgoing = DB::table('tbl_outgoing_outbound')
                ->where('no_outgoing', $nooutgoing)
                ->update([
                    'tanggal' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_outgoing))),
                    'custom_field' => $request->custom_field
                ]);

            $new_request = new Request([
                'action' => 'edit_outgoing', 'no_outbound' => $nooutbound, 'no_outgoing' => $nooutgoing
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
    public function destroy($kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        try {
            $outgoing_detail = DB::table('tbl_outbound as o')
                ->join('tbl_outgoing_outbound as oo', 'oo.id_outbound', '=', 'o.id_outbound')
                ->join('tbl_outgoing_detail as od', 'od.id_outgoing_outbound', '=', 'oo.id_outgoing_outbound')
                ->where('oo.no_outgoing', $nooutgoing)
                ->get();

            if (count($outgoing_detail) > 0) {
                return json_encode("Outgoing outbound gagal dihapus karena outgoing outbound sedang digunakan!");
            } else {
                DB::table('tbl_outgoing_outbound')
                    ->where('no_outgoing', $nooutgoing)
                    ->delete();

                $new_request = new Request([
                    'action' => 'hapus_outgoing', 'no_outbound' => $nooutbound, 'no_outgoing' => $nooutgoing
                ]);
                app(LogController::class)->log($new_request);

                return json_encode('success');
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
