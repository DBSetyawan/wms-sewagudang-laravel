<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


date_default_timezone_set('Asia/Jakarta');
class StockCountController extends Controller
{

    public function setNote(Request $request, $kodeproject, $kodegudang)
    {
        try {
            DB::table('tbl_stock_count_detail')
                ->where('id_stock_detail', $request->id_stock_count_detail)
                ->update([
                    'note' => $request->note
                ]);

            return response()->json("success", 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function generateKodeStockCount($kodeproject, $kodegudang)
    {
        try {
            $current_date = date('dmy');
            $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
            $last_stock_count_inserted = DB::table('tbl_stock_count as sc')
                ->where('sc.kode_stock_count', 'like', "%SC$current_date%")
                ->orderBy('sc.id_stock_count', 'desc')
                ->get();

            if (count($last_stock_count_inserted) > 0) {
                $kode_stock_last_inserted = substr($last_stock_count_inserted[0]->kode_stock_count, 8);
                $new_kode_stock = (int) $kode_stock_last_inserted + 1;
                $new_kode_stock = sprintf("%'.04d", $new_kode_stock);
                $new_kode_stock = "SC" . $current_date . $new_kode_stock;

                return response()->json($new_kode_stock);
            }

            return response()->json("SC" . $current_date . "0001");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function viewCheckStatusInventoryBaseItem($kodeproject, $kodegudang, $kodestock)
    {
        $request = new Request();
        $request->replace(['stock_count_by' => 'item']);
        $list_item = $this->getStockCountDetail($request, $kodeproject, $kodegudang, $kodestock)->getData();
        // dd($list_item);
        $list_item_master = app(ItemsController::class)->getAllItemFromInventory($kodeproject, $kodegudang)->getData();
        $list_locator = app(LocatorController::class)->getAllLocator($kodegudang)->getData();
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        return view('check_stock_count_by_item', compact(['list_item', 'kodegudang', 'kodeproject', 'kodestock', 'list_locator', 'list_item_master', 'projectGudang']));
    }

    public function viewCheckStatusInventoryBaseLabel($kodeproject, $kodegudang, $kodestock)
    {
        $request = new Request();
        $request->replace(['stock_count_by' => 'label']);
        $list_item = $this->getStockCountDetail($request, $kodeproject, $kodegudang, $kodestock)->getData();
        $list_item_master = app(ItemsController::class)->getAllItemBaseLabel($kodeproject, $kodegudang)->getData();
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $list_locator = app(LocatorController::class)->getAllLocator($kodegudang)->getData();
        return view('check_stock_count_by_label', compact(['list_item', 'kodegudang', 'kodeproject', 'kodestock', 'list_locator', 'list_item_master', 'projectGudang']));
    }

    public function getStockCountDetail(Request $request, $kodeproject, $kodegudang, $kodestock)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();

            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            $gudang_has_project = DB::table('tbl_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();

            $list_item = "";

            if ($request->stock_count_by == "label") {
                // $list_item = DB::table('tbl_stock_count as sc')
                //     ->join('tbl_stock_count_detail as scd', 'sc.id_stock_count', '=', 'scd.id_stock_count')
                //     ->join('tbl_item as i', 'scd.id_item', '=', 'i.id_item')
                //     ->join('tbl_inventory_detail as invd', 'invd.id_inventory_detail', '=', 'scd.id_inventory_detail')
                //     ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'sc.id_project_has_gudang')
                //     ->where('sc.kode_stock_count', $kodestock)
                //     ->where('scd.stock_count_by', 'label')
                //     ->groupBy('scd.id_stock_detail')
                //     ->get();
                $list_item = DB::table('tbl_stock_count as sc')
                    ->join('tbl_stock_count_detail as scd', 'sc.id_stock_count', '=', 'scd.id_stock_count')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory_detail', '=', 'scd.id_inventory_detail')
                    ->join('tbl_inventory as inv', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join("tbl_item as item", 'item.id_item', '=', 'inbd.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'sc.id_project_has_gudang')
                    ->where('sc.id_project_has_gudang', $gudang_has_project[0]->id_project_has_gudang)
                    ->where('sc.kode_stock_count', $kodestock)
                    ->where('scd.stock_count_by', 'label')
                    ->groupBy('scd.id_stock_detail')
                    ->get();
            } else {
                $list_item = DB::table('tbl_stock_count as sc')
                    ->join('tbl_stock_count_detail as scd', 'sc.id_stock_count', '=', 'scd.id_stock_count')
                    ->join('tbl_item as i', 'scd.id_item', '=', 'i.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'sc.id_project_has_gudang')
                    ->where('sc.id_project_has_gudang', $gudang_has_project[0]->id_project_has_gudang)
                    ->where('sc.kode_stock_count', $kodestock)
                    ->where('scd.stock_count_by', 'item')
                    ->get();
            }

            // dd($list_item);
            return response()->json($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function checkStatusInventory(Request $request, $kodeproject, $kodegudang, $kodestock)
    {
        try {

            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();

            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            $gudang_has_project = DB::table('tbl_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();

            $stock = DB::table('tbl_stock_count')
                ->where('kode_stock_count', $kodestock)
                ->where('id_project_has_gudang', $gudang_has_project[0]->id_project_has_gudang)
                ->get();

            $locator = DB::table('tbl_locator')->where('id_locator', $request->id_locator)->get();
            $sum_qty_aktual_item = "";
            if ($request->stock_count_by == "item") {
                $sum_qty_aktual_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                    ->join('tbl_inventory as inv', 'id.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                    ->where('l.id_locator', $request->id_locator)
                    ->where('i.id_item', $request->id_item)
                    ->sum('invd.available');
            } else {
                $sum_qty_aktual_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                    ->join('tbl_inventory as inv', 'id.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                    ->where('l.id_locator', $request->id_locator)
                    ->where('invd.id_inventory_detail', $request->idinventorydetail)
                    ->sum('invd.available');
            }

            $status = "";

            if ($sum_qty_aktual_item == $request->qty) {
                $status = "match";
            } else {
                $status = "unmatch";
            }

            DB::table('tbl_stock_count_detail')
                ->insert([
                    'id_stock_count' => $stock[0]->id_stock_count,
                    'id_item' => $request->id_item,
                    'qty_input' => $request->qty,
                    'qty_available' => $sum_qty_aktual_item,
                    'status' => $status,
                    'nama_locator' => $locator[0]->nama_locator,
                    'tanggal' => date('Y-m-d H:i:s'),
                    'stock_count_by' => $request->stock_count_by,
                    'id_inventory_detail' => $request->idinventorydetail
                ]);
            $last_inserted_stock_count = "";

            if ($request->stock_count_by == "item") {
                $last_inserted_stock_count  = DB::table('tbl_stock_count_detail as scd')
                    ->join('tbl_stock_count as sc', 'scd.id_stock_count', '=', 'sc.id_stock_count')
                    ->join('tbl_item as i', 'scd.id_item', '=', 'i.id_item')
                    ->where('sc.kode_stock_count', $kodestock)
                    ->where("scd.stock_count_by", 'item')
                    ->where('i.id_item', $request->id_item)
                    ->orderBy('scd.id_stock_detail', 'desc')
                    ->limit(1)
                    ->get();
            } else {
                // $last_inserted_stock_count = DB::table('tbl_stock_count_detail as scd')
                //     ->join('tbl_stock_count as sc', 'scd.id_stock_count', '=', 'sc.id_stock_count')
                //     ->join('tbl_item as i', 'scd.id_item', '=', 'i.id_item')
                //     ->join('tbl_inventory_detail as invd', 'invd.id_inventory_detail', '=', 'scd.id_inventory_detail')
                //     ->where('sc.kode_stock_count', $kodestock)
                //     ->where("scd.stock_count_by", 'label')
                //     ->where('invd.id_inventory_detail', $request->idinventorydetail)
                //     ->orderBy('scd.id_stock_detail', 'desc')
                //     ->limit(1)
                //     ->get();

                $last_inserted_stock_count = DB::table('tbl_stock_count as sc')
                    ->join('tbl_stock_count_detail as scd', 'scd.id_stock_count', '=', 'sc.id_stock_count')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory_detail', '=', 'scd.id_inventory_detail')
                    ->join('tbl_inventory as inv', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join('tbl_item as i', 'inbd.id_item', '=', 'i.id_item')
                    ->where('sc.kode_stock_count', $kodestock)
                    ->where("scd.stock_count_by", 'label')
                    ->where('invd.id_inventory_detail', $request->idinventorydetail)
                    ->orderBy('scd.id_stock_detail', 'desc')
                    ->limit(1)
                    ->get();
            }

            $item = DB::table('tbl_item')->where('id_item', $request->id_item)->get();

            $new_request = new Request([
                'action' => 'stock_count_check',
                'kode_item' => $item[0]->kode_item,
                'nama_item' => $item[0]->nama_item,
                'qty' => $request->qty,
                'nama_uom' => $item[0]->nama_uom,
                'nama_locator' => $locator[0]->nama_locator,
                'no_stock_count' => $kodestock
            ]);
            app(LogController::class)->log($new_request);

            return response()->json(['status' => "success", 'stock_count_detail' => $last_inserted_stock_count]);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getListStockCount(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();

            if ($request->exists('tanggal_awal') && $request->exists('tanggal_akhir')) {
                $list_stock_count = DB::table('tbl_stock_count as sc')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'sc.id_project_has_gudang')
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->where('gp.id_project', $project[0]->id_project)
                    ->whereBetween("sc.tanggal_buat_stock", [$request->tanggal_awal, $request->tanggal_akhir])
                    ->get();
            } else {
                $list_stock_count = DB::table('tbl_stock_count as sc')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'sc.id_project_has_gudang')
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->where('gp.id_project', $project[0]->id_project)
                    ->get();
            }


            return response()->json($list_stock_count);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kodeproject, $kodegudang)
    {
        try {
            $request = new Request();
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $list_stock_count = $this->getListStockCount($request, $kodeproject, $kodegudang)->getData();
            // dd($list_stock_count);
            return view("daftar_stock_count", compact(['list_stock_count', 'kodegudang', 'kodeproject', 'projectGudang']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject, $kodegudang)
    {

        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $new_nostockcount = $this->generateKodeStockCount($kodeproject, $kodegudang)->getData();
        return view('tambah_stock_count', compact(['kodegudang', 'kodeproject', 'new_nostockcount', 'projectGudang']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodeproject, $kodegudang)
    {
        $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
        $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
        $gudang_has_project = DB::table('tbl_project_has_gudang')->where('id_gudang', $gudang[0]->id_gudang)->where('id_project', $project[0]->id_project)->get();

        try {
            $user = DB::table('users')->where('id', Auth::user()->id)->get();
            DB::table('tbl_stock_count')
                ->insert([
                    'kode_stock_count' => $request->kode_stock,
                    'tanggal_buat_stock' => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal))),
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'nama_user' => $user[0]->name,
                    'id_project_has_gudang' => $gudang_has_project[0]->id_project_has_gudang
                ]);

            $last_inserted_stock_count = DB::table('tbl_stock_count')->where('id_project_has_gudang', $gudang_has_project[0]->id_project_has_gudang)->orderBy('id_stock_count', 'desc')->get();

            $new_request = new Request([
                'action' => 'buat_stock_count',
                'no_stock_count' => $last_inserted_stock_count[0]->kode_stock_count
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
