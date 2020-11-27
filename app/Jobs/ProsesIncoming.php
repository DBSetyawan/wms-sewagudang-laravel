<?php

namespace App\Jobs;

use App\Gudang;
use App\Http\Controllers\LogController;
use App\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProsesIncoming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $kodeproject;
    protected $kodegudang;
    protected $noinbound;
    protected $noincoming;
    protected $response;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $kodeproject, $kodegudang, $noinbound, $noincoming)
    {
        $this->request = $request;
        $this->kodeproject = $kodeproject;
        $this->kodegudang = $kodegudang;
        $this->noinbound = $noinbound;
        $this->noincoming = $noincoming;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //AMBIL OBJECT GUDANG UNTUK ID GUDANG
            $gudang = Gudang::where('kode_gudang', $this->kodegudang);

            //AMBIL OBJECT PROJECT UNTUK ID PROJECT
            $project = Project::where('kode_project', $this->kodeproject);

            $inbound_detail = DB::table('tbl_inbound_detail as id')
                ->join('tbl_inbound as i', 'id.id_inbound', '=', 'i.id_inbound')
                ->where('id.id_inbound_detail', $this->request->id_inbound_detail)
                ->get();

            if (($inbound_detail[0]->qty_aktual + $this->request->quantity) > $inbound_detail[0]->qty) {
                return response()->json("quantity tidak mencukupi");
            }

            $id_incoming_inbound = DB::table('tbl_incoming_inbound')
                ->where("no_incoming", $this->noincoming)
                ->get();

            $existed_incoming_detail = DB::table('tbl_incoming_detail')
                ->where('id_inbound_detail', $this->request->id_inbound_detail)
                ->get();

            if (count($existed_incoming_detail) > 0) {
                //UPDATE TABLE INCOMING INBOUND JIKA SUDAH TERDAFTAR
                DB::table('tbl_incoming_detail')
                    ->where('id_inbound_detail', $this->request->id_inbound_detail)
                    ->update([
                        'quantity' => $existed_incoming_detail[0]->quantity + $this->request->quantity,
                        'tanggal_update' => date("Y-m-d H:i:s")
                    ]);
            } else {
                //INSERT KE TABEL INCOMING DETAIL
                DB::table('tbl_incoming_detail')
                    ->insert([
                        'id_inbound_detail' => $this->request->id_inbound_detail,
                        'id_item' => $this->request->id_item,
                        'id_incoming_inbound' => $id_incoming_inbound[0]->id_incoming_inbound,
                        'quantity' => $this->request->quantity,
                        'tanggal_update' => date("Y-m-d H:i:s")
                    ]);
            }

            //MENGAMBIL NILAI QTY_AKTUAL SAAT INI
            $get_current_qty = DB::table('tbl_inbound_detail')
                ->select('qty_aktual')
                ->where('id_inbound_detail', $this->request->id_inbound_detail)
                ->get();

            //MENAMBAHKAN QTY AKTUAL SAAT INI DENGAN QTY YANG DIINPUTKAN
            $new_qty_aktual = (int) $get_current_qty[0]->qty_aktual + $this->request->quantity;

            //MENGUPDATE QTY AKTUAL PADA TBL INBOUND DETAIL
            $update_inbound_detail = DB::table('tbl_inbound_detail')
                ->where('id_inbound_detail', $this->request->id_inbound_detail)
                ->update(['qty_aktual' => $new_qty_aktual]);

            //MENGAMBIL OBJECT INVENTORY UNTUK ID INVENTORY
            $id_inv = DB::table('tbl_incoming_inbound as ii')
                ->select('inv.id_inventory as id_inventory')
                ->join('tbl_inbound as i', 'ii.id_inbound', '=', 'i.id_inbound')
                ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                ->join('tbl_inventory as inv', 'id.id_inbound_detail', '=', 'inv.id_inbound_detail')
                ->where('id.id_inbound_detail', $this->request->id_inbound_detail)
                ->get();

            $idlocator = $this->request->locator;


            $get_inserted_item_temp_inv_detail = DB::table('tbl_temporary_inventory_detail')
                ->where('id_inventory', $id_inv[0]->id_inventory)
                ->where('id_incoming_inbound', $id_incoming_inbound[0]->id_incoming_inbound)
                ->where('id_locator', $idlocator)
                ->get();

            if (count($get_inserted_item_temp_inv_detail) > 0) {
                $update_item_in_temp_inv_detail = DB::table("tbl_temporary_inventory_detail")
                    ->where('id_inventory', $id_inv[0]->id_inventory)
                    ->where('id_incoming_inbound', $id_incoming_inbound[0]->id_incoming_inbound)
                    ->update([
                        'available' => ($get_inserted_item_temp_inv_detail[0]->available + $this->request->quantity),
                    ]);
            } else {
                $insert_item_in_temp_inv_detail = DB::table("tbl_temporary_inventory_detail")
                    ->insert([
                        'id_inventory' => $id_inv[0]->id_inventory,
                        'id_locator' => $idlocator,
                        'available' => $this->request->quantity,
                        'allocated' => 0,
                        'picked' => 0,
                        'id_incoming_inbound' => $id_incoming_inbound[0]->id_incoming_inbound,
                    ]);
            }

            $item = DB::table('tbl_item')->where('id_item', $this->request->id_item)->get();

            $locator = DB::table('tbl_locator')->where('id_locator', $this->request->locator)->get();
            $new_request = new Request([
                'action' => 'proses_putaway_incoming',
                'kode_item' => $item[0]->kode_item,
                'nama_item' => $item[0]->nama_item,
                'qty' => $this->request->quantity,
                'nama_uom' => $item[0]->nama_uom,
                'nama_locator' => $locator[0]->nama_locator,
                'no_inbound' => $this->noinbound
            ]);
            app(LogController::class)->log($new_request);

            // // $get_current_inbound_detail = DB::table('tbl_inbound_detail')
            // //     ->where('id_inbound_detail', $request->id_inbound_detail)
            // //     ->get();
            $get_current_inbound_detail = DB::table('tbl_inbound_detail as id')
                ->join('tbl_inbound as i', 'i.id_inbound', '=', 'id.id_inbound')
                ->where('i.no_inbound', $this->noinbound)
                ->whereRaw('qty != qty_aktual')
                ->get();

            if (count($get_current_inbound_detail) > 0) {
                DB::table('tbl_inbound')
                    ->where('id_inbound', $get_current_inbound_detail[0]->id_inbound)
                    ->update([
                        'id_status' => 4
                    ]);

                $inbound = DB::table('tbl_inbound as i')
                    ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                    ->where('id_inbound', $get_current_inbound_detail[0]->id_inbound)
                    ->get();
                $history_inbound = DB::table('tbl_history_inbound')
                    ->where('id_inbound', $inbound[0]->id_inbound)
                    ->where('status', $inbound[0]->nama_status)
                    ->orderBy('tanggal_update', 'desc')
                    ->get();

                if (count($history_inbound) > 0) {
                    DB::table('tbl_history_inbound')
                        ->where('id_inbound', $history_inbound[0]->id_inbound)
                        ->where('status', $history_inbound[0]->status)
                        ->update([
                            'tanggal_update' => date('Y-m-d H:i:s'),
                            'nama_user' => Auth::user()->name
                        ]);
                } else {
                    DB::table('tbl_history_inbound')
                        ->insert([
                            'id_inbound' => $inbound[0]->id_inbound,
                            'status' => $inbound[0]->nama_status,
                            'tanggal_update' => date('Y-m-d H:i:s'),
                            'nama_user' => Auth::user()->name
                        ]);
                }
            } else {
                $get_inbound_detail = DB::select('select * from tbl_inbound as i inner join tbl_inbound_detail as id on i.id_inbound = id.id_inbound where i.no_inbound = "' . $this->noinbound . '" and id.qty = id.qty_aktual');
                if (count($get_inbound_detail) > 0) {
                    DB::table('tbl_inbound')
                        ->where('id_inbound', $get_inbound_detail[0]->id_inbound)
                        ->update([
                            'id_status' => 2
                        ]);

                    $inbound = DB::table('tbl_inbound as i')
                        ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                        ->where('id_inbound', $get_inbound_detail[0]->id_inbound)
                        ->get();

                    DB::table('tbl_history_inbound')
                        ->insert([
                            'id_inbound' => $inbound[0]->id_inbound,
                            'status' => $inbound[0]->nama_status,
                            'tanggal_update' => date('Y-m-d H:i:s'),
                            'nama_user' => Auth::user()->name
                        ]);
                }
            }

            $this->response = "success";
            // dd($request->all());
            // return response()->json($get_current_inbound_detail);
        } catch (\Throwable $th) {
            $this->response = $th->getMessage();
        }
    }

    public function getResponse()
    {
        return $this->response;
    }
}
