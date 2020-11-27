<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

date_default_timezone_set("Asia/Jakarta");
class LogController extends Controller
{
    public function log(Request $request)
    {
        $log = "";
        $user = "<b>" . Auth::user()->name . "</b>";
        switch ($request->action) {
            case 'login':
                $log = $user . " telah login.";
                break;

            case 'logout':
                $log = $user . " telah logout";
                break;

            case 'tambah_project':
                $lastest_inserted_project = DB::table('tbl_project as p')
                    ->join('tbl_perusahaan_customer as pp', 'p.id_perusahaan', '=', 'pp.id_perusahaan')
                    ->where('pp.id_perusahaan', $request->id_perusahaan)
                    ->orderBy('p.id_project', 'desc')
                    ->get();

                $log = $user . " menambahkan project <b>" . $lastest_inserted_project[0]->nama_project . "</b> di perusahaan <b>" . $lastest_inserted_project[0]->nama_perusahaan . "</b>";

                break;

            case 'edit_project':
                $project = DB::table('tbl_project as p')
                    ->join('tbl_perusahaan_customer as pp', 'p.id_perusahaan', '=', 'pp.id_perusahaan')
                    ->where('p.kode_project', $request->kodeproject)
                    ->get();

                $log = $user . " mengubah informasi project <b>" . $project[0]->nama_project . "</b>";
                break;

            case 'hapus_project':
                $project = DB::table('tbl_project as p')
                    ->where('p.kode_project', $request->kodeproject)
                    ->get();

                $log = $user . " menghapus project <b>" . $project[0]->nama_project . "</b>";
                break;

            case 'tambah_gudang':
                $log = $user . " menambahkan pada gudang <b>" . $request->nama_gudang . "</b> ke dalam master gudang";
                break;

            case 'edit_gudang':
                $gudang = DB::table('tbl_gudang')
                    ->where('id_gudang', $request->id_gudang)
                    ->get();

                $log = $user . " mengubah informasi pada gudang <b>" . $gudang[0]->nama_gudang . "</b>";
                break;

            case 'hapus_gudang':
                $gudang = DB::table('tbl_gudang')
                    ->where('kode_gudang', $request->kodegudang)
                    ->get();

                $log = $user . " menghapus gudang <b>" . $gudang[0]->nama_gudang . "</b>";
                break;

            case 'tambah_locator':
                $log = $user . " menambahkan locator <b>" . $request->namalocator . "</b> ke dalam gudang <b>" . $request->nama_gudang . "</b>";
                break;

            case 'edit_locator':
                $log = $user . " mengubah informasi locator <b>" . $request->namalocator . "</b>";
                break;

            case 'hapus_locator':
                $log = $user . " menghapus locator <b>" . $request->namalocator . "</b>";
                break;

            case 'tambah_user':
                $log = $user . " menambahkan user pada user <b>" . $request->nama_user . "</b> dan role sebagai <b>" . $request->nama_role . "</b>";
                break;

            case 'edit_user':
                $log = $user . " mengubah informasi user pada user <b>" . $request->nama_user . "</b>";
                break;

            case 'hapus_user':
                $log = $user . " menghapus user <b>" . $request->nama_user . "</b>";
                break;

            case 'tambah_role':
                $log = $user . " menambahkan role pada role <b>" . $request->nama_role . "</b>";
                break;

            case 'edit_role':
                $log = $user . " mengubah informasi role pada role <b>" . $request->nama_role . "</b>";
                break;

            case 'hapus_role':
                $log = $user . " menghapus role <b>" . $request->nama_role . "</b>";
                break;

            case 'tambah_item':
                $log = $user . " menambah item dengan kode  <b>" . $request->kode_item . '</b> dan nama item <b>' . $request->nama_item . "</b>";
                break;

            case 'edit_item':
                $log = $user . " mengubah informasi pada item <b>" . $request->kode_item . '</b> dan nama item <b>' . $request->nama_item . "</b>";
                break;

            case 'hapus_item':
                $log = $user . " menghapus item  <b>" . $request->kode_item . '</b> dan nama item <b>' . $request->nama_item . "</b>";
                break;

            case 'tambah_inbound':
                $log = $user . " menambah inbound dengan nomor inbound  <b>" . $request->no_inbound . '</b>';
                break;

            case 'tambah_item_inbound':
                $log = $user . " memasukkan item  <b>" . $request->kode_item . '</b> dan nama item <b>' . $request->nama_item . "</b> sebanyak <b>" . $request->qty . " " . $request->nama_uom . "</b> pada inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'edit_inbound':
                $log = $user . " mengubah informasi pada inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'hapus_inbound':
                $log = $user . " menghapus inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'add_item_to_existing_inbound':
                $log = $request->list_item;
                break;

            case 'tambah_incoming':
                $log = $user . " menambah incoming dengan nomor <b>" . $request->no_incoming . "</b> pada inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'edit_incoming':
                $log = $user . " mengubah informasi incoming <b>" . $request->no_incoming . "</b>";
                break;

            case 'hapus_incoming':
                $log = $user . " menghapus incoming <b>" . $request->no_incoming . "</b> dari inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'proses_putaway_incoming':
                $log = $user . " memindahkan item <b>" . $request->kode_item . ' - ' . $request->nama_item . "</b> sebanyak <b>" . $request->qty . " " . $request->nama_uom . "</b>" . " ke locator <b>" . $request->nama_locator . "</b> pada inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'print_invoice_inbound':
                $log = $user . " mencetak invoice pada inbound <b>" . $request->no_inbound;
                break;

            case 'upload_pod_inbound':
                $log = $user . " mengupload file pod dengan nama file <b>" . $request->nama_file . "</b>" . " pada inbound <b>" . $request->no_inbound . "</b>";
                break;

            case 'tambah_outbound':
                $log = $user . " menambahkan outbound dengan nomor outbound " . $request->no_outbound;
                break;

            case 'allocated':
                $log = $user . " mengallocated item <b>" . $request->kode_item . " - " . $request->nama_item . " sebanyak " . $request->qty . " " . $request->nama_uom . " ke dalam outbound " . $request->no_outbound;
                break;

            case 'edit_outbound':
                $log = $user . " mengubah informasi outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'hapus_outbound':
                $log = $user . " menghapus outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'proses_picking':
                $log = $user . " melakukan pick up item <b>" . $request->kode_item . " - " . $request->nama_item . " " . $request->nama_uom . "</b> pada outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'undo_picking':
                $log = $user . " membatalkan pick up item <b>" . $request->kode_item . " - " . $request->nama_item . " " . $request->nama_uom . "</b> pada outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'upload_pod_outbound':
                $log = $user . " mengupload file pod dengan nama file <b>" . $request->nama_file . "</b>" . " pada outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'print_invoice_outbound':
                $log = $user . " mencetak invoice pada outbound <b>" . $request->no_outbound;
                break;

            case 'cancel_outbound':
                $log = $user . " membatalkan outbound <b>" . $request->no_outbound;
                break;

            case 'tambah_outgoing':
                $log = $user . " menambah outgoing dengan nomor <b>" . $request->no_outgoing . "</b> pada outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'edit_outgoing':
                $log = $user . " mengubah informasi outgoing <b>" . $request->no_outgoing . "</b>";
                break;

            case 'hapus_outgoing':
                $log = $user . " menghapus outgoing <b>" . $request->no_outgoing . "</b> dari outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'proses_loading':
                $log = $user . " melakukan loading item dengan kode item <b>" . $request->kode_item . " - " . $request->nama_item . "</b> sebanyak <b>" . $request->qty . " " . $request->nama_uom . "</b> pada outgoing <b>" . $request->no_outgoing . "</b> dari outbound <b>" . $request->no_outbound . "</b>";
                break;

            case 'proses_putaway':
                $log = $user . " melakukan putaway item <b>" . $request->kode_item . " - " . $request->nama_item . "</b> dengan label inventory <b>" . $request->label . "</b> sebanyak <b>" . $request->qty . " " . $request->nama_uom . "</b> dari locator <b>" . $request->locator_awal . "</b> ke locator <b>" . $request->locator_tujuan . "</b>";
                break;

            case 'buat_stock_count':
                $log = $user . " membuat stock count dengan nomor stock count <b>" . $request->no_stock_count . "</b>";
                break;

            case 'stock_count_check':
                $log = $user . " menambahkan item <b>" . $request->kode_item . " - " . $request->nama_item . "</b> sebanyak <b>" . $request->qty . " " . $request->nama_uom . "</b> dari locator <b>"  . $request->nama_locator .  "</b> pada data stock count <b>" . $request->no_stock_count . "</b>";
                break;

            case 'display_report':
                $log = $user . " menampilkan report " . $request->type_report;
                break;
        }
        // return json_encode($log);
        DB::table('tbl_log')
            ->insert([
                'log' => $log,
                'nama_user' => Auth::user()->name,
                'waktu_update' => date('Y-m-d H:i:s'),
                'device_type' => "",
                'device_model' => ""
            ]);
    }

    public function index(Request $request)
    {
        $timeline = $this->getLog($request)->getData();

        return view('log', compact('timeline'));
    }

    public function getLog(Request $request)
    {
        // $date = date('Y-m-d H:i:s', strtotime($request->tanggal_max));
        $timeline = "";
        if ($request->has("tanggal_min") == false && $request->has('tanggal_max') == false) {
            $timeline = DB::table('tbl_log')
                ->where('waktu_update', '>', Carbon::now()->subDays(30))
                ->orderBy('waktu_update', 'desc')
                ->limit(1000)
                ->get();
        } else {
            $timeline = DB::table('tbl_log')
                ->where('waktu_update', '>=', date('Y-m-d H:i:s', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_min) . " " . date("H:i:s"))))
                ->where('waktu_update', '<=', date('Y-m-d H:i:s', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_max) . " " . date("H:i:s"))))
                ->orderBy('waktu_update', 'desc')
                ->limit(1000)
                ->get();
        }
        //date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_inbound)))
        return response()->json($timeline);
    }
}
