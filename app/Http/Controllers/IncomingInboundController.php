<?php


namespace App\Http\Controllers;

use App\Gudang;
use App\Jobs\ProsesIncoming;
use App\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;


date_default_timezone_set("Asia/Jakarta");

class IncomingInboundController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function getIncomingDetail($kodeproject, $kodegudang, $noinbound, $noincoming)
    {
        try {
            $incoming_detail = DB::table('tbl_incoming_inbound as ii')
                ->join('tbl_incoming_detail as id', 'ii.id_incoming_inbound', '=', 'id.id_incoming_inbound')
                ->join('tbl_inbound_detail as ind', 'id.id_inbound_detail', '=', 'ind.id_inbound_detail')
                ->join('tbl_item as i', 'ind.id_item', '=', 'i.id_item')
                ->where('ii.no_incoming', $noincoming)
                ->orderBy('id.tanggal_update', 'desc')
                ->get();

            return response()->json($incoming_detail);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function storeToIncomingDetail(Request $request, $kodeproject, $kodegudang, $noinbound, $noincoming)
    {
        $newRequest = new stdClass();
        $newRequest->id_inbound_detail = $request->id_inbound_detail;
        $newRequest->quantity = $request->quantity;
        $newRequest->locator = $request->locator;
        $newRequest->id_item = $request->id_item;
        // return json_encode($newRequest);

        $job = new ProsesIncoming($newRequest, $kodeproject, $kodegudang, $noinbound, $noincoming);
        // ProsesIncoming::dispatchNow()
        dispatch_now($job);

        return response()->json($job->getResponse());
    }

    public function prosesIncoming($kodeproject, $kodegudang, $noinbound)
    {
        try {
            $list_item_in_inbound = DB::table('tbl_inbound as i')
                ->select('it.kode_item as kode_item', 'it.id_item as id_item', 'it.nama_item as nama_item', 'id.qty as quantity', 'it.cbm as cbm', 'it.berat_bersih as berat_bersih', 'id.label as label', 'id.qty_aktual as qty_aktual', 'id.custom_field_td as custom_field_td', 'id.id_inbound_detail as id_inbound_detail')
                ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                ->join('tbl_item as it', 'it.id_Item', '=', 'id.id_item')
                ->where('i.no_inbound', $noinbound)
                ->get();

            return response()->json($list_item_in_inbound);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage());
        }
    }

    public function finishDocumentIncoming(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {

            $list_id_incoming = json_decode($request->list_id_incoming, true);
            $list_item = DB::table('tbl_temporary_inventory_detail')
                ->whereIn('id_incoming_inbound', $list_id_incoming)
                ->get();
            $incoming = DB::table('tbl_incoming_inbound')
                ->where('id_incoming_inbound', $list_item[0]->id_incoming_inbound)
                ->get();
            $count = count($list_item);
            $i = 0;
            for ($i; $i < $count; $i++) {
                DB::table('tbl_inventory_detail')
                    ->insert([
                        'id_inventory' => $list_item[$i]->id_inventory,
                        'id_locator' => $list_item[$i]->id_locator,
                        'available' => $list_item[$i]->available,
                        'allocated' => 0,
                        'picked' => 0,

                    ]);
                DB::table('tbl_temporary_inventory_detail')
                    ->where('id_incoming_inbound', $list_item[$i]->id_incoming_inbound)
                    ->delete();
            }

            DB::table('tbl_inbound')
                ->where('id_inbound', $incoming[0]->id_inbound)
                ->update([
                    'id_status' => 6
                ]);

            $inbound = DB::table('tbl_inbound as i')
                ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                ->where('id_inbound', $incoming[0]->id_inbound)
                ->get();

            DB::table('tbl_history_inbound')
                ->insert([
                    'id_inbound' => $inbound[0]->id_inbound,
                    'status' => $inbound[0]->nama_status,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'nama_user' => Auth::user()->name
                ]);



            return json_encode('success');
        } catch (\Throwable $th) {
            return json_encode($th->getMessage() . " - " . $th->getCode());
        }
    }

    public function generateNoIncoming($kodeproject, $kodegudang,  $noinbound)
    {
        $no_incoming = "";
        $date = date('dmy');
        try {
            $last_no_incoming = DB::table('tbl_incoming_inbound')
                ->where('no_incoming', 'like', '%' . $date . '%')
                ->orderBy('no_incoming', 'desc')
                ->limit(1)
                ->get();
            if (count($last_no_incoming) != 0) {
                $no_incoming = substr($last_no_incoming[0]->no_incoming, -4);
                $no_incoming_baru = (int) $no_incoming + 1;
                $no_incoming_baru = "Inc" . $date . strval(sprintf("%'.04d", $no_incoming_baru));
                // $no_incoming_baru = "Inc" . date('dmy') . "0001";
                return response()->json($no_incoming_baru);
            }

            $no_incoming_baru = "Inc" . date('dmy') . "0001";
            return response()->json("Inc" . date('dmy') . "0001");
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function getListIncoming($kodeproject, $kodegudang, $noinbound)
    {
        $gudang = Gudang::where('kode_gudang', $kodegudang)->get();
        $project = Project::where('kode_project', $kodeproject)->get();

        $list_incoming_inbound = DB::table('tbl_incoming_inbound as ii')
            ->join('tbl_inbound as i', 'ii.id_inbound', '=', 'i.id_inbound')
            ->join('tbl_project_has_gudang as gp', 'i.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
            ->where('i.no_inbound', $noinbound)
            ->where('gp.id_project', $project[0]->id_project)
            ->where('gp.id_gudang', $gudang[0]->id_gudang)
            ->get();

        return response()->json($list_incoming_inbound);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kodeproject, $kodegudang,  $noinbound)
    {
        try {
            $inbound = app(InboundController::class)->getInformationInbound($noinbound)->getData();
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $list_incoming_inbound = $this->getListIncoming($kodeproject, $kodegudang, $noinbound)->getData();

            $list_item = $this->prosesIncoming($kodeproject, $kodegudang, $noinbound)->getData();
            $qty = 0;
            $qty_aktual = 0;
            foreach ($list_item as $item) {
                $qty += $item->quantity;
                $qty_aktual += $item->qty_aktual;
            }

            $list_locator = app(LocatorController::class)->getAllLocator($kodegudang)->getData();
            $template = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();

            $last_inserted_incoming = "";
            if (session()->exists('last_inserted_incoming')) {
                $last_inserted_incoming = session()->pull('last_inserted_incoming');
            }

            return view('daftar_incoming_inbound', compact(['kodegudang', 'kodeproject', 'noinbound', 'list_incoming_inbound', 'template', 'list_locator', 'qty', 'qty_aktual', 'last_inserted_incoming', 'projectGudang', 'inbound']));
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject, $kodegudang, $noinbound)
    {
        $inbound = app(InboundController::class)->getInformationInbound($noinbound)->getData();
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $nama_file_template_inbound = file_get_contents("./template_inbound/inbound" . "_" . $kodeproject . ".json");
        $json = json_decode($nama_file_template_inbound, true);
        $form_incoming_inbound = $json['form_incoming_inbound'];

        $new_noincoming = $this->generateNoIncoming($kodeproject, $kodegudang, $noinbound)->getData();
        // dd($new_noincoming);
        return view('tambah_incoming_inbound', compact(['kodegudang', 'kodeproject', 'noinbound', 'form_incoming_inbound', 'new_noincoming', 'projectGudang', 'inbound']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {
            $inbound = DB::table('tbl_inbound')
                ->where('no_inbound', $noinbound)
                ->get();

            $query = DB::table('tbl_incoming_inbound')
                ->insert([
                    'no_incoming' => $request->no_incoming_inbound,
                    'tanggal' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_incoming_inbound))),
                    'id_inbound' => $inbound[0]->id_inbound,
                    'custom_field' => $request->incoming_inbound_value
                ]);

            $new_request = new Request([
                'action' => 'tambah_incoming',
                'no_inbound' => $noinbound,
                'no_incoming' => $request->no_incoming_inbound
            ]);
            app(LogController::class)->log($new_request);

            session(['last_inserted_incoming' => $request->no_incoming_inbound]);
            return response()->json("success");
        } catch (Exception $ex) {
            return response()->json($ex->getMessage());
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
    public function edit($kodeproject, $kodegudang,  $noinbound, $noincoming)
    {
        $inbound = app(InboundController::class)->getInformationInbound($noinbound)->getData();
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $object_incoming = DB::table('tbl_incoming_inbound')
            ->where('no_incoming', $noincoming)
            ->get();

        $nama_file_template_inbound = file_get_contents("./template_inbound/inbound" . "_" . $kodeproject . ".json");
        $json = json_decode($nama_file_template_inbound, true);
        $form_incoming_inbound = $json['form_incoming_inbound'];
        return view('edit_incoming', compact(['object_incoming', 'kodegudang', 'kodeproject', 'noinbound', 'noincoming', 'form_incoming_inbound', 'json', 'projectGudang', 'inbound']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang, $noinbound, $noincoming)
    {
        try {
            $object_incoming = DB::table('tbl_incoming_inbound')
                ->where('no_incoming', $noincoming)
                ->update([
                    'no_incoming' => $request->no_incoming_inbound,
                    'tanggal' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_incoming_inbound))),
                    'custom_field' => $request->incoming_inbound_value
                ]);

            $new_request = new Request(['action' => 'edit_incoming', 'no_inbound' => $noinbound, 'no_incoming' => $noincoming]);
            app(LogController::class)->log($new_request);
            return json_encode("success");
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
    public function destroy($kodeproject, $kodegudang, $noinbound, $noincoming)
    {
        try {
            $incoming_inbound_has_detail = DB::table('tbl_incoming_inbound as inc')
                ->join('tbl_incoming_detail as incd', 'inc.id_incoming_inbound', '=', 'incd.id_incoming_inbound')
                ->where('inc.no_incoming', $noincoming)
                ->get();

            if (count($incoming_inbound_has_detail) > 0) {
                return json_encode('Incoming gagal dihapus karena incoming inbound sedang digunakan!');
            } else {
                $new_request = new Request([
                    'action' => 'hapus_incoming', 'no_inbound' => $noinbound, 'no_incoming' => $noincoming
                ]);
                app(LogController::class)->log($new_request);

                DB::table('tbl_incoming_inbound')
                    ->where('no_incoming', $noincoming)
                    ->delete();

                return json_encode("success");
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
