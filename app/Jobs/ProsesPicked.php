<?php

namespace App\Jobs;

use App\Http\Controllers\LogController;
use App\Http\Controllers\OutboundController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProsesPicked implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $request;
    protected $kodeproject;
    protected $kodegudang;
    protected $nooutbound;
    protected $response;
    protected $testing;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $kodeproject, $kodegudang, $nooutbound)
    {
        $this->request = $request;
        $this->kodeproject = $kodeproject;
        $this->kodegudang = $kodegudang;
        $this->nooutbound = $nooutbound;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $current_status_outbound = "";
            $continue = false;
            // $outbound = DB::table('tbl_outbound')
            //     ->where('no_outbound', $this->request->nooutbound)
            //     ->get();

            $qty_allocated = DB::table('tbl_inventory_detail')
                ->where('id_inventory_detail', $this->request->idinventorydetail)
                ->get();

            $current_outbounddetail = DB::table('tbl_outbound_detail')
                ->where('id_outbound_detail', $this->request->idoutbounddetail)
                ->get();

            $action = "";
            if ($this->request->action == "Picked" && $current_outbounddetail[0]->id_status == 13) {
                DB::table('tbl_inventory_detail')
                    ->where("id_inventory_detail", $this->request->idinventorydetail)
                    ->update([
                        'allocated' => ($qty_allocated[0]->allocated - $this->request->qty),
                        'picked' => ($qty_allocated[0]->picked + $this->request->qty)
                    ]);

                DB::table('tbl_outbound_detail')
                    ->where('id_outbound_detail', $this->request->idoutbounddetail)
                    ->update([
                        'id_status' => 14
                    ]);

                $action = "proses_picking";

                $continue = true;
            } else if ($this->request->action == "Undo" && $current_outbounddetail[0]->id_status == 14) {
                DB::table('tbl_inventory_detail')
                    ->where("id_inventory_detail", $this->request->idinventorydetail)
                    ->update([
                        'allocated' => ($qty_allocated[0]->allocated + $this->request->qty),
                        'picked' => ($qty_allocated[0]->picked - $this->request->qty)
                    ]);

                DB::table('tbl_outbound_detail')
                    ->where('id_outbound_detail', $this->request->idoutbounddetail)
                    ->update([
                        'id_status' => 13
                    ]);

                $action = "undo_picking";

                $continue = true;
            } else {
                $this->response = ['status' => 'item sudah dipicking/allocated'];
                $continue = false;
            }

            if ($continue == true) {
                $item = DB::table('tbl_inventory_detail as id')
                    ->join('tbl_inventory as inv', 'inv.id_inventory', '=', 'id.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join('tbl_item as item', 'inbd.id_item', '=', 'item.id_item')
                    ->where('id.id_inventory_detail', $this->request->idinventorydetail)
                    ->get();

                $new_request = new Request([
                    'action' => $action,
                    'kode_item' => $item[0]->kode_item,
                    'nama_item' => $item[0]->nama_item
                ]);
                app(LogController::class)->log($new_request);
                // $this->insertHistoryOutbound($this->nooutbound);

                $list_item_in_outbound_detail = DB::table('tbl_outbound as o')
                    ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                    ->where('o.no_outbound', $this->nooutbound)
                    ->get();

                $count_allocated_item = DB::table('tbl_outbound_detail as od')
                    ->join('tbl_outbound as o', 'od.id_outbound', '=', 'o.id_outbound')
                    ->where('o.no_outbound', $this->nooutbound)
                    ->where('od.id_status', 13)
                    ->count();

                if ($count_allocated_item == 0) {
                    DB::table('tbl_outbound')
                        ->where('no_outbound', $this->nooutbound)
                        ->update([
                            'id_status' => 5
                        ]);
                } else if ($count_allocated_item == count($list_item_in_outbound_detail)) {
                    DB::table('tbl_outbound')
                        ->where('no_outbound', $this->nooutbound)
                        ->update([
                            'id_status' => 7
                        ]);
                } else {
                    DB::table('tbl_outbound')
                        ->where('no_outbound', $this->nooutbound)
                        ->update([
                            'id_status' => 4
                        ]);
                }

                $nama_status_current_nooutbound = DB::table('tbl_outbound as o')
                    ->select('so.nama_status')
                    ->join('tbl_status_outbound as so', 'o.id_status', 'so.id_status')
                    ->where('o.no_outbound', $this->nooutbound)
                    ->get();

                app(OutboundController::class)->insertHistoryOutbound($this->nooutbound);

                $this->response = json_encode(['status' => "success", 'nama_status' => $nama_status_current_nooutbound[0]]);
            }
        } catch (\Throwable $th) {
            $this->response =  json_encode($th->getMessage());
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
