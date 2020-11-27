<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


date_default_timezone_set("Asia/Jakarta");
class PutAwayController extends Controller
{

    public function prosesPutAway(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $inventory_detail = DB::table('tbl_inventory_detail as id')
                ->join('tbl_inventory as inv', 'id.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_inbound_detail as inbd', 'inv.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                ->join('tbl_item as item', 'inbd.id_item', '=', 'item.id_item')
                ->where('id.id_inventory_detail', $request->idinventorydetail)
                ->where("id.id_locator", $request->locator_lama)
                ->get();

            if (($inventory_detail[0]->available - $request->quantity_move) < 0) {
                return response()->json("Quantity yang diputaway tidak mencukupi");
            }

            DB::table('tbl_inventory_detail')

                ->where('id_inventory_detail', $request->idinventorydetail)
                ->update([
                    'available' => (int) $inventory_detail[0]->available - (int) $request->quantity_move
                ]);


            $inventory = DB::table('tbl_item as i')
                ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                ->where("l.id_locator", $request->locator_baru)
                ->where('id.label', $inventory_detail[0]->label)
                ->get();
            if (count($inventory) == 0) {
                DB::table('tbl_inventory_detail')
                    ->insert([
                        'id_inventory' => $request->idinventory,
                        'available' => $request->quantity_move,
                        'allocated' => 0,
                        'picked' => 0,
                        'id_locator' => $request->locator_baru
                    ]);
            } else {
                $inventory_detail = DB::table('tbl_inventory_detail as id')
                    ->join('tbl_inventory as i', 'id.id_inventory', '=', 'i.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'id.id_locator')
                    ->where('l.id_locator', $request->locator_baru)
                    ->where('id.id_inventory', $inventory[0]->id_inventory)
                    ->get();

                DB::table('tbl_inventory_detail')
                    ->where('id_locator', $request->locator_baru)
                    ->where('id_inventory_detail', $inventory_detail[0]->id_inventory_detail)
                    ->update([
                        'available' => $inventory_detail[0]->available + $request->quantity_move
                    ]);
            }

            $item = DB::table('tbl_inventory_detail as invd')
                ->join('tbl_inventory as inv', 'inv.id_inventory', '=', 'invd.id_inventory')
                ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                ->join('tbl_item as item', 'item.id_item', '=', 'inbd.id_item')
                ->where('invd.id_inventory_detail', $request->idinventorydetail)
                ->get();


            $locator_lama = DB::table('tbl_locator')->where('id_locator', $request->locator_lama)->get();
            $locator_baru = DB::table('tbl_locator')->where('id_locator', $request->locator_baru)->get();

            $new_request = new Request([
                'action' => 'proses_putaway',
                'kode_item' => $item[0]->kode_item,
                'nama_item' => $item[0]->nama_item,
                'label' => $item[0]->label,
                'locator_tujuan' => $locator_baru[0]->nama_locator,
                'locator_awal' => $locator_lama[0]->nama_locator,
                'qty' => $request->quantity_move,
                'nama_uom' => $item[0]->nama_uom
            ]);
            app(LogController::class)->log($new_request);

            DB::table('tbl_history_putaway')
                ->insert([
                    'qty' => $request->quantity_move,
                    'id_locator_asal' => $request->locator_lama,
                    'id_locator_tujuan' => $request->locator_baru,
                    'id_inventory_detail' => $request->idinventorydetail,
                    'tanggal' => date('Y-m-d H:i:s'),
                    'nama_user' => Auth::user()->name,
                ]);
            return response()->json("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function displayReport($kodeproject, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();

            $id_project_has_gudang = DB::table('tbl_project_has_gudang')->where('id_gudang', $gudang[0]->id_gudang)->where('id_project', $project[0]->id_project)->get();
            $list_history_putaway = DB::table('tbl_history_putaway as hp')
                ->join('tbl_locator as l', 'hp.id_locator_asal', '=', 'l.id_locator')
                ->leftJoin('tbl_locator as lp', 'hp.id_locator_tujuan', '=', 'lp.id_locator')
                ->join('tbl_inventory_detail as invd', 'hp.id_inventory_detail', 'invd.id_inventory_detail')
                ->join('tbl_inventory as inv', 'invd.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_inbound_detail as inbd', 'inv.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                ->join('tbl_item as item', 'item.id_item', '=', 'inbd.id_item')
                ->join('tbl_project_has_gudang as pg', 'pg.id_project_has_gudang', 'item.id_project_has_gudang')
                ->where('pg.id_project_has_gudang', $id_project_has_gudang[0]->id_project_has_gudang)
                ->distinct()
                ->get(['hp.id_history_putaway', 'hp.tanggal', 'inbd.label', 'item.kode_item', 'item.nama_item', 'hp.qty', 'l.nama_locator as nama_locator_asal', 'lp.nama_locator as nama_locator_tujuan', 'hp.nama_user']);

            $new_request = new Request([
                'action' => "display_report", 'type_report' => "putaway"
            ]);
            app(LogController::class)->log($new_request);

            return response()->json($list_history_putaway);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $list_item = app(ItemsController::class)->getAllItem($kodeproject, $kodegudang)->getData();
        $list_locator = app(LocatorController::class)->getAllLocator($kodegudang)->getData();
        return view('putaway', compact(['kodegudang', 'kodeproject', 'list_item', 'list_locator', 'projectGudang']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
