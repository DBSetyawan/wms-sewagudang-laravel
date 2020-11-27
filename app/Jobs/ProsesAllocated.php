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

class ProsesAllocated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $request;
    protected $kodeproject;
    protected $kodegudang;
    protected $response;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $kodeproject, $kodegudang)
    {
        $this->request = $request;
        $this->kodeproject = $kodeproject;
        $this->kodegudang = $kodegudang;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $last_inserted_id_outbound_detail = 0;
            $label_inserted_outbound_detail = 0;
            $qty_available = DB::table('tbl_inventory_detail')
                ->where('id_inventory_detail', $this->request->idinventorydetail)
                ->get();

            $id_item = 0;

            $project_has_gudang = DB::table('tbl_project as p')
                ->join('tbl_project_has_gudang as pg', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where('p.kode_project', $this->kodeproject)
                ->where('g.kode_gudang', $this->kodegudang)
                ->get();

            if ($this->request->filter == "label") {
                // $item = DB::table('tbl_inbound_detail as id')
                //     ->join('tbl_item as i', 'i.id_item', '=', 'id.id_item')
                //     ->where('id.label', $this->request->item)
                //     ->get();
                $item = DB::table('tbl_inventory_detail as invd')
                    ->join('tbl_inventory as inv', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                    ->join('tbl_item as item', 'item.id_item', '=', 'inbd.id_item')
                    ->where('invd.id_inventory_detail', $this->request->idinventorydetail)
                    ->get();

                $id_item = $item[0]->id_item;
            } else {
                $item = DB::table('tbl_item')
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->where('kode_item', $this->request->item)
                    ->get();

                $id_item = $item[0]->id_item;
            }

            if ($this->request->action == "allocated") {
                if (($qty_available[0]->available - $this->request->qty) < 0) {
                    $this->response = ['status' => "Quantity available tidak mencukupi"];
                } else {
                    DB::table('tbl_inventory_detail')
                        ->where("id_inventory_detail", $this->request->idinventorydetail)
                        ->update([
                            'available' => ($qty_available[0]->available - (int) $this->request->qty),
                            'allocated' => ($qty_available[0]->allocated + (int) $this->request->qty)
                        ]);

                    app(OutboundController::class)->insertHistoryOutbound($this->request->nooutbound);

                    $outbound = DB::table('tbl_outbound as o')
                        ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                        ->join('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
                        ->where('o.no_outbound', $this->request->nooutbound)
                        ->orderBy('od.label', 'desc')
                        ->get();
                    // return json_encode($this->request->nooutbound);
                    if (count($outbound) == 0) {
                        $outbound = DB::table('tbl_outbound')
                            ->where('no_outbound', $this->request->nooutbound)
                            ->get();
                        DB::table('tbl_outbound_detail')
                            ->insert([
                                'id_outbound' => $outbound[0]->id_outbound,
                                'id_item' => $id_item,
                                'id_locator' => $this->request->idlocator,
                                'id_inventory' => $this->request->idinventory,
                                'id_inventory_detail' => $this->request->idinventorydetail,
                                'qty' => (int) $this->request->qty,
                                'qty_load' => 0,
                                'id_status' => 13,
                                'label' =>  $this->request->nooutbound . sprintf("%'.04d", (1))
                            ]);

                        $outbound = DB::table('tbl_outbound as o')
                            ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                            ->join('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
                            ->where('o.no_outbound', $this->request->nooutbound)
                            ->orderBy('od.label', 'desc')
                            ->get();
                        $last_inserted_id_outbound_detail = $outbound[0]->id_outbound_detail;
                        $label_inserted_outbound_detail = $outbound[0]->label;
                    } else {
                        $increment_num_for_label_outbound_detail = substr($outbound[0]->label, 12);
                        DB::table('tbl_outbound_detail')
                            ->insert([
                                'id_outbound' => $outbound[0]->id_outbound,
                                'id_item' => $id_item,
                                'id_locator' => $this->request->idlocator,
                                'id_inventory' => $this->request->idinventory,
                                'id_inventory_detail' => $this->request->idinventorydetail,
                                'qty' => $this->request->qty,
                                'qty_load' => 0,
                                'id_status' => 13,
                                'label' =>  $outbound[0]->no_outbound . sprintf("%'.04d", ((int) ($increment_num_for_label_outbound_detail) + 1))
                            ]);
                        $outbound = DB::table('tbl_outbound as o')
                            ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                            ->join('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
                            ->where('o.no_outbound', $this->request->nooutbound)
                            ->orderBy('od.label', 'desc')
                            ->get();
                        $label_inserted_outbound_detail = $outbound[0]->label;
                        $last_inserted_id_outbound_detail = $outbound[0]->id_outbound_detail;
                        // return json_encode((int)($increment_num_for_label_outbound_detail) + 1);
                    }

                    $item = DB::table('tbl_item')->where('id_item', $id_item)->get();
                    $new_request = new Request([
                        'action' => "allocated", 'no_outbound' => $this->request->nooutbound, 'qty' => $this->request->qty, 'kode_item' => $item[0]->kode_item, 'nama_item' => $item[0]->nama_item, 'nama_uom' => $item[0]->nama_uom
                    ]);
                    app(LogController::class)->log($new_request);
                }
            } else {
                if (($qty_available[0]->allocated - $this->request->qty) < 0) {
                    $this->response = ['status' => "Quantity allocated tidak mencukupi"];
                } else {
                    DB::table('tbl_inventory_detail')
                        ->where("id_inventory_detail", $this->request->idinventorydetail)
                        ->update([
                            'available' => ($qty_available[0]->available + $this->request->qty),
                            'allocated' => ($qty_available[0]->allocated - $this->request->qty)
                        ]);

                    // DB::table('tbl_outbound_detail')
                    //     ->where('')
                    //     ->insert([
                    //         'id_outbound' => $outbound[0]->id_outbound,
                    //         'id_item' => $this->request->iditem,
                    //         'id_locator' => $this->request->idlocator,
                    //         'id_inventory' => $this->request->idinventory,
                    //         'id_inventory_detail' => $this->request->idinventorydetail,
                    //         'qty' => $this->request->qty,
                    //         'qty_load' => 0,
                    //         'id_status' => 13,
                    //         'label' =>  $outbound[0]->no_outbound . sprintf("%'.04d", ($increment_num_for_label_outbound_detail + 1))
                    //     ]);

                    $outbound = DB::table('tbl_outbound as o')
                        ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
                        ->join('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
                        ->where('no_outbound', $this->request->nooutbound)
                        ->orderBy('o.no_outbound', 'desc')
                        ->get();


                    $last_inserted_id_outbound_detail = $outbound[0]->id_outbound_detail;
                }
            }

            DB::table('tbl_outbound')
                ->where('no_outbound', $this->request->nooutbound)
                ->update([
                    'id_status' => 9
                ]);

            app(OutboundController::class)->insertHistoryOutbound($this->request->nooutbound);


            // $qty_available = DB::table('tbl_inventory_detail')
            //     ->where('id_inventory_detail', $this->request->idinventorydetail)
            //     ->get();

            $item = DB::table('tbl_inbound_detail as id')
                ->join("tbl_inventory as inv", 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                ->join('tbl_item as item', 'item.id_item', '=', 'id.id_item')
                ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                ->where('invd.id_inventory_detail', $this->request->idinventorydetail)
                ->get();

            $this->response = ['item' => $item, 'id_outbound_detail' => $last_inserted_id_outbound_detail, 'label' => $label_inserted_outbound_detail, 'status' => "success"];
        } catch (\Throwable $th) {
            $this->response = json_encode($th->getMessage() . " - " . $th->getLine());
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
