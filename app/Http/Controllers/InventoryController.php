<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function displayReport(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();

            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            $project_has_gudang = DB::table('tbl_project_has_gudang')
                ->where('id_project', $project[0]->id_project)
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->get();
            $list_item_in_inventory = "";

            if ($request->filter == "Label") {
                $list_item_in_inventory = DB::select("
                SELECT inbd.label as label, i.kode_item as kode_item, inbd.custom_field_td as custom_field_td, i.nama_item as nama_item, sum(invd.available) as available, sum(invd.allocated) as allocated, sum(invd.picked) as picked, i.nama_uom as nama_uom, i.berat_bersih as berat_bersih ,i.berat_kotor as berat_kotor, l.nama_locator as nama_locator, inb.tanggal_inbound as tanggal_inbound, i.cbm as cbm, inb.no_inbound
                from tbl_item as i
                inner join tbl_project_has_gudang as pg on pg.id_project_has_gudang = i.id_project_has_gudang
                inner join tbl_inbound_detail as inbd on i.id_item = inbd.id_item
                inner join tbl_inbound as inb on inb.id_inbound = inbd.id_inbound
                inner join tbl_inventory as inv on inv.id_inbound_detail = inbd.id_inbound_detail
                inner join tbl_inventory_detail as invd on inv.id_inventory = invd.id_inventory
                inner join tbl_locator as l on invd.id_locator = l.id_locator
                where i.id_project_has_gudang = " . $project_has_gudang[0]->id_project_has_gudang . " and available + allocated + picked <> 0
                GROUP BY invd.id_inventory_detail, l.id_locator
                ");
            } else {
                $list_item_in_inventory = DB::select("
                SELECT inbd.label as label, i.kode_item as kode_item, inbd.custom_field_td as custom_field_td, i.nama_item as nama_item, sum(invd.available) as available, sum(invd.allocated) as allocated, sum(invd.picked) as picked, i.nama_uom as nama_uom, i.berat_bersih as berat_bersih, i.berat_kotor as berat_kotor, l.nama_locator as nama_locator, inb.tanggal_inbound as tanggal_inbound, i.cbm as cbm, inb.no_inbound
                from tbl_item as i
                inner join tbl_project_has_gudang as pg on pg.id_project_has_gudang = i.id_project_has_gudang
                inner join tbl_inbound_detail as inbd on i.id_item = inbd.id_item
                inner join tbl_inbound as inb on inb.id_inbound = inbd.id_inbound
                inner join tbl_inventory as inv on inv.id_inbound_detail = inbd.id_inbound_detail
                inner join tbl_inventory_detail as invd on inv.id_inventory = invd.id_inventory
                inner join tbl_locator as l on invd.id_locator = l.id_locator
                where i.id_project_has_gudang = " . $project_has_gudang[0]->id_project_has_gudang . " and available + allocated + picked <> 0 GROUP BY i.kode_item, l.id_locator");
            }

            $new_request = new Request([
                'action' => "display_report", 'type_report' => "inventory"
            ]);
            app(LogController::class)->log($new_request);

            return json_encode($list_item_in_inventory);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function displayReportBalanceInventory(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();
            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            $project_has_gudang = DB::table('tbl_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();
            $id_project_has_gudang = $project_has_gudang[0]->id_project_has_gudang;
            $report = [];



            if ($request->filter == "label") {
                $report_inbound = DB::select(
                    "
                    SELECT 
                        null as outLabel,
                        inbd.label AS label,
                        inbd.qty_aktual AS qty_in,
                        null as qty_out
                    FROM
                        tbl_inventory_detail AS invd
                            INNER JOIN
                        tbl_inventory AS inv ON invd.id_inventory = inv.id_inventory
                            INNER JOIN
                        tbl_inbound_detail AS inbd ON inbd.id_inbound_detail = inv.id_inbound_detail
                            INNER JOIN
                        tbl_inbound as inb on inb.id_inbound = inbd.id_inbound
                            INNER JOIN
                        tbl_item AS itm ON itm.id_item = inbd.id_item
                    where itm.id_project_has_gudang = " . $id_project_has_gudang . "
                        and inb.tanggal_inbound between '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))) . "'
                        and '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))) . "'
                    GROUP BY invd.id_inventory_detail ,inbd.id_inbound_detail
                    "
                );

                $report_outbound = DB::select("
                SELECT 
                        od.label AS outLabel,
                        inbd.label AS inLabel,
                        null as qty_in,
                        SUM(od.qty_load) AS qty_out
                    FROM
                        tbl_inbound_detail AS inbd
                            INNER JOIN
                        tbl_inventory AS inv ON inbd.id_inbound_detail = inv.id_inbound_detail
                            INNER JOIN
                        tbl_inventory_detail AS invd ON invd.id_inventory = inv.id_inventory
                            INNER JOIN
                        tbl_outbound_detail AS od ON invd.id_inventory_detail = od.id_inventory_detail
                            INNER JOIN
                        tbl_outbound AS o ON o.id_outbound = od.id_outbound
                            INNER JOIN
                        tbl_item AS item ON item.id_item = od.id_item
                    WHERE
                        item.id_project_has_gudang = " . $id_project_has_gudang . " and o.tanggal_outbound between '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))) . "' and '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))) . "'
                    GROUP BY invd.id_inventory_detail
                ");
                foreach ($report_inbound as $inbound) {
                    $not_outbound_exist = false;
                    foreach ($report_outbound as $outbound) {
                        if ($inbound->label == $outbound->inLabel) {
                            $not_outbound_exist = false;
                            array_push($report, ['label' => $inbound->label, 'qty_in' => $inbound->qty_in, 'qty_out' => $outbound->qty_out]);
                            break;
                        } else {
                            $not_outbound_exist = true;
                        }
                    }
                    if ($not_outbound_exist == true) {
                        array_push($report, ['label' => $inbound->label, 'qty_in' => $inbound->qty_in, 'qty_out' => 0]);
                    }
                }
            } else {
                $report = "";
                $report = DB::select(
                    "SELECT i.id_item, i.kode_item, i.nama_item, COALESCE(inboundIn.qty_aktual, 0) as `in`, COALESCE(outboundOut.qty_load, 0) as `out` 
                    from tbl_item as i 
                    left JOIN 
                        (SELECT i.id_item as iditem, SUM(inbd.qty_aktual) as qty_aktual 
                            from tbl_item as i INNER JOIN tbl_inbound_detail as inbd on inbd.id_item = i.id_item left JOIN tbl_inbound as inb on inb.id_inbound = inbd.id_inbound where inb.tanggal_inbound between '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))) . "' and '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))) . "' GROUP BY i.id_item) as inboundIn on i.id_item = inboundIn.iditem LEFT join (SELECT i.id_item as iditm, SUM(outb.qty_load) qty_load from tbl_item as i INNER jOIN tbl_outbound_detail as outb on outb.id_item = i.id_item left JOIN tbl_outbound as o on o.id_outbound=  outb.id_outbound where o.tanggal_outbound between '" . date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))) . "' and '" .  date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))) . "' GROUP BY i.id_item) as outboundOut on i.id_item = outboundOut.iditm WHERE  (inboundIn.qty_aktual != 0 or outboundOut.qty_load != 0) AND i.id_project_has_gudang = $id_project_has_gudang GROUP BY i.id_item, i.kode_item, i.nama_item, inboundIn.qty_aktual, outboundOut.qty_load"

                );
            }

            $new_request = new Request([
                'action' => "display_report", 'type_report' => "balance inventory"
            ]);
            app(LogController::class)->log($new_request);

            return json_encode($report);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAksesProjectGudang($kodeproject, $kodegudang)
    {
        try {
            $list_project = "";
            if (Auth::user()->role->nama_role == "Admin") {
                $list_project = DB::table('tbl_project as p')
                    ->join('tbl_project_has_gudang as pg', 'pg.id_project', '=', 'p.id_project')
                    ->join('tbl_gudang as g', 'pg.id_gudang', '=', 'g.id_gudang')
                    ->distinct()
                    ->get(['p.id_project', 'p.kode_project', 'p.nama_project']);
            } else {
                $list_project = DB::table('tbl_project as p')
                    ->join('tbl_project_has_gudang as pg', 'pg.id_project', '=', 'p.id_project')
                    ->join('tbl_hak_akses as ha', 'ha.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
                    ->where('ha.id_user', Auth::user()->id)
                    ->distinct()
                    ->get(['p.id_project', 'p.kode_project', 'p.nama_project']);
            }


            return response()->json($list_project);
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
        $list_item = app(ItemsController::class)->getAllItemFromInventory($kodeproject, $kodegudang)->getData();
        return view('inventory', compact(['kodegudang', 'kodeproject', 'list_item', 'projectGudang']));
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
