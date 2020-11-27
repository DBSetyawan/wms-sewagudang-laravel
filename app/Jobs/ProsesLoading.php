<?php

namespace App\Jobs;

use App\Http\Controllers\LogController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProsesLoading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $request;
    protected $kodeproject;
    protected $kodegudang;
    protected $nooutbound;
    protected $nooutgoing;
    protected $response;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $kodeproject, $kodegudang, $nooutbound, $nooutgoing)
    {
        $this->request = $request;
        $this->kodeproject = $kodeproject;
        $this->kodegudang = $kodegudang;
        $this->nooutbound = $nooutbound;
        $this->nooutgoing = $nooutgoing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $this->nooutbound)
                ->get();

            $inventory_detail = DB::table('tbl_inventory_detail')
                ->where('id_inventory_detail', $this->request->idinventorydetail)
                ->get();

            $outbound_detail = DB::table('tbl_outbound_detail')
                ->where('id_outbound_detail', $this->request->idoutbounddetail)
                ->get();

            if (($outbound_detail[0]->qty_load + $this->request->qty) > $outbound_detail[0]->qty) {
                $this->response = json_encode("Quantity tidak mencukupi");
            } else {

                $update_inventory_detail = DB::table('tbl_inventory_detail')
                    ->where('id_inventory_detail', $this->request->idinventorydetail)
                    ->update([
                        'picked' => (int) ($inventory_detail[0]->picked - $this->request->qty)
                    ]);

                $update_outbound_detail = DB::table('tbl_outbound_detail')
                    ->where('id_outbound_detail', $this->request->idoutbounddetail)
                    ->update([
                        'qty_load' => (int) ($outbound_detail[0]->qty_load + $this->request->qty)
                    ]);

                $outgoing_outbound = DB::table('tbl_outgoing_outbound as o')
                    ->join('tbl_outgoing_detail as od', 'od.id_outgoing_outbound', '=', 'o.id_outgoing_outbound')
                    ->where('o.no_outgoing', $this->nooutgoing)
                    ->where('od.id_outbound_detail', $this->request->idoutbounddetail)
                    ->get();

                $outgoing_detail = "";
                if (count($outgoing_outbound) == 0) {
                    $outgoing_outbound =  DB::table('tbl_outgoing_outbound')
                        ->where('no_outgoing', '=', $this->nooutgoing)
                        ->get();

                    $insert_outgoing_detail = DB::table('tbl_outgoing_detail')
                        ->insert([
                            'id_outgoing_outbound' => $outgoing_outbound[0]->id_outgoing_outbound,
                            'id_outbound_detail' => $this->request->idoutbounddetail,
                            'id_outbound' => $outbound[0]->id_outbound,
                            'id_item' => $this->request->iditem,
                            'qty' => $this->request->qty,
                            'tanggal_update' => date('Y-m-d H:i:s')
                        ]);

                    $item = DB::table('tbl_item')->where('id_item', $this->request->iditem)->get();

                    $new_request = new Request([
                        'action' => "proses_loading",
                        'qty' => $this->request->qty,
                        'kode_project' => $this->kodeproject,
                        'qty' => $this->request->qty,
                        'nama_item' => $item[0]->nama_item,
                        'kode_item' => $item[0]->kode_item,
                        'nama_uom' => $item[0]->nama_uom,
                        'no_outgoing' => $this->nooutgoing,
                        'no_outbound' => $this->nooutbound
                    ]);
                    app(LogController::class)->log($new_request);
                } else {
                    $outgoing_detail = DB::table('tbl_outgoing_detail')
                        ->where('id_outbound_detail', $this->request->idoutbounddetail)
                        ->where('id_outgoing_outbound', $outgoing_outbound[0]->id_outgoing_outbound)
                        ->where('id_item', $this->request->iditem)
                        ->get();

                    DB::table('tbl_outgoing_detail')
                        ->where('id_outbound_detail', $this->request->idoutbounddetail)
                        ->where('id_outgoing_outbound', $outgoing_outbound[0]->id_outgoing_outbound)
                        ->where('id_item', $this->request->iditem)
                        ->update([
                            'qty' => (int) ($outgoing_detail[0]->qty + $this->request->qty)
                        ]);
                }

                $list_outbound_detail = DB::table('tbl_outbound_detail as od')
                    ->join('tbl_outbound as o', 'od.id_outbound', '=', 'o.id_outbound')
                    ->where('o.no_outbound', $this->nooutbound)
                    ->whereRaw('od.qty != od.qty_load')
                    ->get();

                if (count($list_outbound_detail) == 0) {
                    DB::table('tbl_outbound')
                        ->where('no_outbound', $this->nooutbound)
                        ->update([
                            'id_status' => 3
                        ]);

                    $outbound = DB::table('tbl_outbound as o')
                        ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                        ->where('o.no_outbound', $this->nooutbound)
                        ->get();

                    DB::table('tbl_history_outbound')
                        ->insert([
                            'tanggal_update' => date('Y-m-d H:i:s'),
                            'status' => $outbound[0]->nama_status,
                            'id_outbound' => $outbound[0]->id_outbound,
                            'nama_user' => Auth::user()->name
                        ]);

                    $outbound = DB::table('tbl_outbound')->where('no_outbound', $this->nooutbound)->get();
                    if ($outbound[0]->type_outbound == "Transfer") {
                        $currentDate = date("dmy");
                        $latestId_in_db = "";

                        $latestId_in_db = DB::table('tbl_inbound')
                            ->where('no_inbound', 'like', "%$currentDate%")
                            ->where('type_inbound', 'Transfer')
                            ->orderBy('no_inbound', 'desc')
                            ->limit(1)
                            ->get();

                        $new_no_inbound = "";
                        if (count($latestId_in_db) != 0) {
                            $num_no_inbound = substr($latestId_in_db[0]->no_inbound, 7);
                            $new_num_no_inbound = $num_no_inbound + 1;
                            $new_num_no_inbound = sprintf("%'.04d", $new_num_no_inbound);
                            $new_no_inbound = "IT" . $currentDate . $new_num_no_inbound;
                        } else {
                            $new_no_inbound = "IT" . $currentDate . "0001";
                        }

                        $gudang = DB::table('tbl_gudang')
                            ->where('kode_gudang', $this->kodegudang)
                            ->get();

                        $project_has_gudang = DB::table('tbl_project_has_gudang as pg')
                            ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                            ->join('tbl_gudang as g', 'pg.id_gudang', '=', 'g.id_gudang')
                            ->where('p.kode_project', $this->kodeproject)
                            ->where('g.nama_gudang', $outbound[0]->destination)
                            ->get();


                        DB::table('tbl_inbound')
                            ->insert([
                                'no_inbound' => $new_no_inbound,
                                'tanggal_inbound' => $outbound[0]->tanggal_outbound,
                                'referensi' => $outbound[0]->no_outbound,
                                'origin' => $gudang[0]->nama_gudang,
                                'id_project_has_gudang'  => $project_has_gudang[0]->id_project_has_gudang,
                                'id_status' => 8,
                                'type_inbound' => "Transfer"
                            ]);

                        $list_item_in_outgoing_outbound = DB::table('tbl_outgoing_outbound as o')
                            ->join('tbl_outgoing_detail as oo', 'o.id_outgoing_outbound', '=', 'oo.id_outgoing_outbound')
                            ->join('tbl_outbound as out', 'out.id_outbound', '=', 'o.id_outbound')
                            ->join('tbl_outbound_detail as od', 'od.id_outbound', '=', 'out.id_outbound')
                            ->where('out.no_outbound', $this->nooutbound)
                            ->groupBy('od.label')
                            ->get();

                        $i = 0;
                        $count = count($list_item_in_outgoing_outbound);

                        $inbound = DB::table("tbl_inbound as i")
                            ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
                            ->where('i.no_inbound', $new_no_inbound)
                            ->get();

                        for ($i; $i < $count; $i++) {
                            $item_custom_field = DB::table('tbl_outbound_detail as od')
                                ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                                ->join('tbl_inventory as i', 'i.id_inventory', '=', 'id.id_inventory')
                                ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'i.id_inbound_detail')
                                ->where('id.id_inventory_detail', $list_item_in_outgoing_outbound[$i]->id_inventory_detail)
                                ->get();

                            // $item = DB::table('tbl_item as i')
                            //     ->where('i.nama_item', $list_item_in_outgoing_outbound[$i]->nama_item)
                            //     ->where('i.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                            //     ->get();

                            $item = DB::table('tbl_item as i')
                                ->where('i.id_item', $list_item_in_outgoing_outbound[$i]->id_item)
                                ->get();

                            // $kode_item = $this->generateKodeItem($kodeproject, $project_has_gudang[0]->kode_gudang);
                            $kode_last_inserted_item = $item[0]->kode_item;
                            DB::table('tbl_item')
                                ->insert([
                                    'kode_item' => $item[0]->kode_item,
                                    'label_barcode' => $item[0]->label_barcode,
                                    'nama_item' => $item[0]->nama_item,
                                    'minimal_qty' => $item[0]->minimal_qty,
                                    'cara_hitung_cbm' => $item[0]->cara_hitung_cbm,
                                    'panjang' => $item[0]->panjang,
                                    'lebar' => $item[0]->lebar,
                                    'tinggi' => $item[0]->tinggi,
                                    'cbm' => $item[0]->cbm,
                                    'berat_bersih' => $item[0]->berat_bersih,
                                    'berat_kotor' => $item[0]->berat_kotor,
                                    'nama_uom' => $item[0]->nama_uom,
                                    'id_project_has_gudang' => $project_has_gudang[0]->id_project_has_gudang
                                ]);

                            $inserted_item =  DB::table('tbl_item as i')
                                ->where('i.kode_item', $kode_last_inserted_item)
                                ->where('i.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                                ->get();

                            DB::table('tbl_inbound_detail')
                                ->insert([
                                    'qty' => $list_item_in_outgoing_outbound[$i]->qty,
                                    'qty_aktual' => 0,
                                    'label' =>  $new_no_inbound . sprintf("%'.04d", ($i + 1)),
                                    'id_inbound' => $inbound[0]->id_inbound,
                                    'id_status' => 2,
                                    'id_item' => $inserted_item[0]->id_item,
                                    'custom_field_td' => $item_custom_field[0]->custom_field_td,
                                    'custom_field_th' => $item_custom_field[0]->custom_field_th,
                                ]);

                            $inbound_detail = DB::table('tbl_inbound_detail')
                                ->where('id_inbound', $inbound[0]->id_inbound)
                                ->orderBy('id_inbound_detail', 'desc')
                                ->get();

                            DB::table('tbl_inventory')
                                ->insert([
                                    'id_inbound_detail' => $inbound_detail[0]->id_inbound_detail
                                ]);

                            $inventory = DB::table('tbl_inventory')
                                ->where('id_inbound_detail', $inbound_detail[0]->id_inbound)
                                ->orderBy('id_inventory', 'desc')
                                ->get();
                        }
                        DB::table('tbl_history_inbound')
                            ->insert([
                                'tanggal_update' => date('Y-m-d H:i:s'),
                                'status' => $inbound[0]->nama_status,
                                'id_inbound' => $inbound[0]->id_inbound,
                                'nama_user' => Auth::user()->name
                            ]);
                    }


                    $this->response = json_encode('success');
                } else {
                    DB::table('tbl_outbound')
                        ->where('no_outbound', $this->nooutbound)
                        ->update([
                            'id_status' => 6
                        ]);
                    $outbound = DB::table('tbl_outbound as o')
                        ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                        ->where('o.no_outbound', $this->nooutbound)
                        ->get();

                    DB::table('tbl_history_outbound')
                        ->insert([
                            'tanggal_update' => date('Y-m-d H:i:s'),
                            'status' => $outbound[0]->nama_status,
                            'id_outbound' => $outbound[0]->id_outbound,
                            'nama_user' => Auth::user()->name
                        ]);
                    $this->response = json_encode("partial loading");
                }
            }
        } catch (\Throwable $th) {
            $this->response = json_encode($th->getMessage());
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
