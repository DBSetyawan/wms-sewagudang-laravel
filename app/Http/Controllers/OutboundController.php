<?php

namespace App\Http\Controllers;

use App\Gudang;
use App\Jobs\ProsesAllocated;
use App\Jobs\ProsesPicked;
use App\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use stdClass;
use Symfony\Component\HttpFoundation\StreamedResponse;
// use \Browser;

class OutboundController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function printPickNote($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = $this->getOutboundDetail($kodeproject, $kodegudang, $nooutbound)->getData();
            $template_inbound = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();
            return view('pickNote_print', compact('outbound', 'template_inbound'));
        } catch (\Throwable $th) {
        }
    }

    public function getInformationOutbound($nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->get();

            return response()->json($outbound, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 200);
        }
    }

    public function saveNoteOutbound(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->update([
                    'note' => $request->note
                ]);

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function printInvoice($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound as o')
                ->select('itm.kode_item as kode_item', 'id.label as label_inv', 'od.label as label_out', 'itm.nama_item as nama_item', 'qty_load as qty_load', 'itm.nama_uom as nama_uom', 'itm.cbm as cbm', 'o.no_outbound as no_outbound', 'o.referensi as referensi', 'o.custom_field', 'o.tanggal_outbound as tanggal_outbound', 'pc.nama_perusahaan', 'pc.alamat_perusahaan', 'pc.email_perusahaan', 'o.destination as tujuan')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join("tbl_inventory_detail as invd", 'invd.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->join('tbl_inventory as inv', 'invd.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_inbound_detail as id', 'inv.id_inbound_detail', 'id.id_inbound_detail')
                ->join('tbl_item as itm', 'id.id_item', '=', 'itm.id_item')
                ->join('tbl_project_has_gudang as pg', 'o.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_perusahaan_customer as pc', 'p.id_perusahaan', '=', 'pc.id_perusahaan')
                ->where('o.no_outbound', $nooutbound)
                ->get();

            $subtotalCBM = 0;
            $subtotalTotalCBM = 0;
            $subtotalQty = 0;
            foreach ($outbound as $item) {
                $subtotalCBM += $item->cbm;
                $subtotalTotalCBM += $item->cbm * $item->qty_load;
                $subtotalQty += $item->qty_load;
            }

            $new_request = new Request([
                'action' => "print_invoice_outbound", 'no_outbound' => $nooutbound
            ]);
            app(LogController::class)->log($new_request);

            return view('invoice_print_outbound', compact(['outbound', 'subtotalCBM', 'subtotalTotalCBM', 'subtotalQty']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function importExceOutbound(Request $request, $kodeproject, $kodegudang)
    {
        $index = 1;
        $column_custom_label = "";

        $template_outbound = $this->loadTemplateOutbound($kodeproject)->getData();
        $count_custom_label = count($template_outbound->daftar_custom_label_outbound_header);
        $daftar_custom_label = $template_outbound->daftar_custom_label_outbound_header;
        $total_column_outbound_header = 3;


        $outbound_header_value = array();
        $outbound_detail_value = array();
        $list_item_from_outbound = array();
        $list_label_inv = array();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $reader->setReadDataOnly(TRUE);
        $reader->setReadEmptyCells(false);
        $sheet = $reader->load($_FILES['excel_outbound']['tmp_name']);
        $active_sheet = $sheet->getActiveSheet();
        $highest_row = $active_sheet->getHighestRow();
        $highest_column = $active_sheet->getHighestColumn();

        //looping untuk mengambil value outbound header
        for ($column = "A"; $column <= $highest_column; $column++) {
            if ($index > ($total_column_outbound_header)) {
                $column_custom_label = $column;
                break;
            } else {
                /* Mengecek apakah index yang saat ini adalah index A (tanggal) ? */
                if ($index == 1) {
                    /* Mengecek apakah nilai pada kolom A2 bertipe integer? (karena nilai cell yang diterima akan berupa int/timestamps jika file excel pada kolom tersebut bertype date) */
                    if (is_int($active_sheet->getCellByColumnAndRow($index, 2)->getValue()) == true) {
                        $date_php = Date::excelToDateTimeObject($active_sheet->getCellByColumnAndRow($index, 2)->getValue());
                        $date_php = date('Y-m-d', $date_php->getTimestamp());
                        array_push($outbound_header_value,  $date_php);
                    } else if ($active_sheet->getCellByColumnAndRow($index, 2)->getValue() == "") {
                        $date_php = date('Y-m-d');
                    } else {
                        $date_php = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $active_sheet->getCellByColumnAndRow($index, 2)->getValue())));
                        array_push($outbound_header_value,  $date_php);
                    }
                } else {
                    array_push($outbound_header_value,  $active_sheet->getCellByColumnAndRow($index, 2)->getValue());
                }
                $index++;
            }
        }

        /* Looping untuk mengambil custom label pada outbound header */
        $custom_label = array();
        $index = 0;
        for ($column = $column_custom_label; $column < $highest_column; $column++) {
            if ($index >= ($count_custom_label)) {
                break;
            } else {
                /* Mencari type custom label saat ini */
                $type_custom_label = $this->getCustomLabelType($daftar_custom_label, $active_sheet->getCell($column . 1)->getValue());

                /* Mengecek apakah type custom label bertype date / text */
                if ($type_custom_label == "date") {
                    $result_date_php = "";

                    /* Mengecek apakah nilai pada cell saat ini bersifat integer */
                    if (is_int($active_sheet->getCell($column . 2)->getValue()) == true) {
                        $date_php = Date::excelToDateTimeObject($active_sheet->getCell($column . 2)->getValue());
                        $date_php = date('Y-m-d', $date_php->getTimestamp());
                        $result_date_php = $date_php;
                    } else if ($active_sheet->getCell($column . 2)->getValue() == "") {
                        $result_date_php = date('Y-m-d');
                    } else {
                        $date_php = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $active_sheet->getCell($column . 2)->getValue())));
                        $result_date_php = $date_php;
                    }
                    array_push($custom_label,  ['custom_label' => $active_sheet->getCell($column . 1)->getValue(), 'value' => $result_date_php]);
                } else {
                    array_push($custom_label,  ['custom_label' => $active_sheet->getCell($column . 1)->getValue(), 'value' => $active_sheet->getCell($column . 2)->getValue()]);
                }
            }
            $index++;
        }
        array_push($outbound_header_value, $custom_label);

        /*loopong untuk mengambil value outbound detail*/
        $item_object_value = array();
        $array_custom_label_value = array();
        for ($row = 6; $row <= $highest_row; ++$row) {
            $index = 1;
            for ($col = 'A'; $col <= $highest_column; ++$col) {
                if ($index > 3) {
                    $column_custom_label = $col;
                    break;
                } else {
                    /* Memasukkan setiap value cell pada sebuah array */
                    array_push($item_object_value, $active_sheet->getCell($col . $row)->getValue());
                }
                $index++;
            }

            /* Memasukkan kode item pada sebuah array */
            array_push($list_item_from_outbound, $active_sheet->getCell("A" . $row)->getValue());

            /* Memasukkan label inventory ke dalam sebuah array */
            array_push($list_label_inv, $active_sheet->getCell("C" . $row)->getValue());

            /* Memasukkan array item ke dalam sebuah array */
            array_push($outbound_detail_value, $item_object_value);
            $item_object_value = array();
        }
        // dd($outbound_detail_value);

        /* Cek apakah item yang ada didalam excel terdaftar di DB */
        $projet_has_gudang = DB::table('tbl_project_has_gudang as pg')
            ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
            ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
            ->where('g.kode_gudang', $kodegudang)
            ->where('p.kode_project', $kodeproject)
            ->get();



        // $list_item_exist = DB::table('tbl_item as i')
        //     ->select(['i.kode_item as kode_item', 'i.id_item as id_item', 'invd.id_inventory_detail as id_inventory_detail', 'inv.id_inventory as id_inventory', 'invd.available as available', 'invd.id_locator as id_locator', 'invd.allocated as allocated', 'id.label as label'])
        //     ->join('tbl_inbound_detail as id', 'id.id_item', '=', 'i.id_item')
        //     ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
        //     ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
        //     ->where('i.id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
        //     ->where('invd.available', '<>', 0)
        //     ->whereIn('i.kode_item', $list_item_from_outbound)
        //     ->whereIn('id.label', $list_label_inv)
        //     ->orderBy('id.label', 'desc')
        //     ->groupBy('id.label', 'invd.id_inventory_detail')
        //     ->get();
        $list_item_used = [];
        $id_inventory_detail_item_used = [];
        $remain_qty_previous_item = 0;
        $qty = 0;
        $is_exist = true;
        $previous_item_used = "";
        $previous_item_outbound = "";
        foreach ($outbound_detail_value as $item_outbound) {
            $qty = $item_outbound[1];
            if ($previous_item_outbound != "") {
                if ($previous_item_outbound != $item_outbound[2]) {
                    $remain_qty_previous_item = 0;
                }
            }

            if ($remain_qty_previous_item < $qty) {
                $list_item_exist = DB::table('tbl_item as i')
                    ->select(['i.kode_item as kode_item', 'i.id_item as id_item', 'invd.id_inventory_detail as id_inventory_detail', 'inv.id_inventory as id_inventory', 'invd.available as available', 'invd.id_locator as id_locator', 'invd.allocated as allocated', 'id.label as label', DB::raw('max(invd.available) as available')])
                    ->join('tbl_inbound_detail as id', 'id.id_item', '=', 'i.id_item')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                    ->where('i.id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                    ->where('invd.available', '<>', 0)
                    ->where('id.label', $item_outbound[2])
                    ->whereNotIn('invd.id_inventory_detail', $id_inventory_detail_item_used)
                    ->orderBy('invd.available', 'desc')
                    ->groupBy('invd.id_inventory_detail')
                    ->get();

                if (count($list_item_exist) > 0) {
                    foreach ($list_item_exist as $item_exist) {
                        if ($item_exist->available >= $qty) {
                            $previous_item_used = $item_exist;
                            array_push($list_item_used, $item_exist);
                            $remain_qty_previous_item = $item_exist->available - $qty;
                            array_push($id_inventory_detail_item_used, $item_exist->id_inventory_detail);
                            break;
                        }
                    }
                } else {
                    $is_exist = false;
                    break;
                }
            } else {
                array_push($list_item_used, $previous_item_used);
            }

            $previous_item_outbound = $item_outbound;
        }
        // dd($list_item_used);
        /* menghapus item yagn sudah terdaftar di db */
        if ($is_exist == false) {
            return redirect()->back()->withErrors('Item belum terdaftar');
        } else {
            /* Membuat Outbound */
            DB::table('tbl_outbound')
                ->insert([
                    'no_outbound' => $this->generateNomorOutbound($kodeproject, $kodegudang, 'O'),
                    'tanggal_outbound' => $outbound_header_value[0],
                    'destination' => $outbound_header_value[1],
                    'referensi' => $outbound_header_value[2],
                    'custom_field' => json_encode($outbound_header_value[3]),
                    'id_project_has_gudang' => $projet_has_gudang[0]->id_project_has_gudang,
                    'id_status' => 7
                ]);


            /* Membuat outbound Detail */
            $lastest_inserted_outbound = DB::table('tbl_outbound as o')
                ->join('tbl_status_outbound as si', 'si.id_status', '=', 'o.id_status')
                ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                ->orderBy('id_outbound', 'desc')
                ->get();
            // dd($lastest_inserted_outbound);
            // dd($list_item_exist);
            $index = 0;
            foreach ($outbound_detail_value as $item_outbound) {
                DB::table('tbl_outbound_detail')
                    ->insert([
                        'qty' => $item_outbound[1],
                        'qty_load' => 0,
                        'label' => $lastest_inserted_outbound[0]->no_outbound . sprintf("%'.04d", ($index + 1)),
                        'id_outbound' => $lastest_inserted_outbound[0]->id_outbound,
                        'id_status' => 13,
                        'id_item' => $list_item_used[$index]->id_item,
                        'id_inventory_detail' => $list_item_used[$index]->id_inventory_detail,
                        'id_inventory' => $list_item_used[$index]->id_inventory,
                        'id_locator' => $list_item_used[$index]->id_locator
                    ]);

                DB::table('tbl_inventory_detail')
                    ->where('id_inventory_detail', $list_item_used[$index]->id_inventory_detail)
                    ->update([
                        'available' => $list_item_used[$index]->available - $item_outbound[1],
                        'allocated' => $list_item_used[$index]->allocated + $item_outbound[1],
                    ]);
                $index++;
            }

            DB::table('tbl_history_outbound')
                ->insert([
                    'id_outbound' => $lastest_inserted_outbound[0]->id_outbound,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => $lastest_inserted_outbound[0]->nama_status,
                    'nama_user' => Auth::user()->name
                ]);

            return redirect()->back();
        }
    }

    public function exportExcelOutbound($kodeproject, $kodegudang)
    {
        $custom_field_outbound_header = "";
        $custom_field_outbound_detail = "";
        $filename = date('d-m-Y_H:i:s') . '_' . 'template_outbound_project_' . $kodeproject . ".xls";

        $template = $this->loadTemplateOutbound($kodeproject)->getData();
        //Mengambil custom field outboud header
        foreach ($template->daftar_custom_label_outbound_header as $label) {
            $custom_field_outbound_header = $custom_field_outbound_header . "<th>" . $label->nama_label . "</th>";
        }

        //mengambil custom field outbound detail
        foreach ($template->daftar_custom_label_outbound_header as $label) {
            $custom_field_outbound_detail = $custom_field_outbound_detail . "<th>" . $label->nama_label . "</th>";
        }

        $format_outbound_header = "
            <table>
                <tr>
                    <th>Date Outbound (DD/MM/YYYY)</th>
                    <th>Destination/Tujuan</th>
                    <th>Referensi</th>
                    $custom_field_outbound_header
                </tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td>Kode Item</td>
                    <td>Quantity</td>
                    <td>Label Inventory</td>
                    $custom_field_outbound_detail
                </tr>
            </table>
        ";

        $reader = new Html();
        $speardsheet = $reader->loadFromString($format_outbound_header);
        $writer = IOFactory::createWriter($speardsheet, "Xls");
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        return $response;
    }

    public function uploadPOD(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound')->where('no_outbound', $nooutbound)->get();
            $i = 0;
            $files_length = count($_FILES['pod']['name']);
            for ($i; $i < $files_length; $i++) {
                DB::table('tbl_pod_outbound')
                    ->insert([
                        'nama_file' => $_FILES['pod']['name'][$i],
                        'waktu_upload' => date('Y-m-d H:i:s'),
                        'id_outbound' => $outbound[0]->id_outbound
                    ]);

                move_uploaded_file($_FILES['pod']['tmp_name'][$i], './pod_outbound/' . $_FILES['pod']['name'][$i]);

                $new_request = new Request([
                    'action' => "upload_pod_outbound",
                    'no_outbound' => $nooutbound,
                    'nama_file' => $_FILES['pod']['name'][$i]
                ]);
                app(LogController::class)->log($new_request);
            }

            $list_pod_files = DB::table('tbl_outbound as o')
                ->join('tbl_pod_outbound as po', 'po.id_outbound', '=', 'o.id_outbound')
                ->where('o.no_outbound', $nooutbound)
                ->get();


            return json_encode(['success', $list_pod_files]);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllPODList($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $list_pod_files = DB::table('tbl_outbound as o')
                ->join('tbl_pod_outbound as po', 'po.id_outbound', '=', 'o.id_outbound')
                ->where('o.no_outbound', $nooutbound)
                ->get();

            return json_encode($list_pod_files);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function downloadFile($kodeproject, $kodegudang, $nooutbound, $idpod)
    {
        $file = DB::table('tbl_pod_outbound')->where('id_pod', $idpod)->get();

        $filename = "./pod_outbound/" . $file[0]->nama_file;

        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        }
    }

    public function hapusFile($kodeproject, $kodegudang, $nooutbound, $idpod)
    {
        try {
            $file = DB::table('tbl_pod_outbound')->where('id_pod', $idpod)->get();

            $filename = "./pod_outbound/" . $file[0]->nama_file;

            if (file_exists($filename)) {
                unlink($filename);
                DB::table('tbl_pod_outbound')->where('id_pod', $idpod)->delete();
            }
            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function removeItemFromOutbound(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {

            $inventory_detail = DB::table("tbl_inventory_detail")
                ->where('id_inventory_detail', $request->idinventorydetail)
                ->get();

            DB::table('tbl_inventory_detail')
                ->where('id_inventory_detail', $request->idinventorydetail)
                ->update([
                    'allocated' => $inventory_detail[0]->allocated - $request->qty,
                    'available' => $inventory_detail[0]->available + $request->qty
                ]);

            DB::table('tbl_outbound_detail')
                ->where('id_outbound_detail', $request->idoutbounddetail)
                ->delete();

            $list_outbound_detail = DB::table('tbl_outbound as o')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->where('no_outbound', $nooutbound)
                ->get();

            if (count($list_outbound_detail) == 0) {
                $outbound = DB::table('tbl_outbound')
                    ->where('no_outbound', $nooutbound)
                    ->update([
                        'id_status' => 8
                    ]);
            }

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function insertHistoryOutbound($nooutbound)
    {
        $outbound = DB::table('tbl_outbound as o')
            ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
            ->where('o.no_outbound', $nooutbound)
            ->get();

        $check_status_is_exist = DB::table('tbl_outbound as o')
            ->join('tbl_status_outbound as so', 'so.id_status', '=', 'o.id_status')
            ->join('tbl_history_outbound as ho', 'ho.id_outbound', '=', 'o.id_outbound')
            ->where('o.no_outbound', $nooutbound)
            ->where('ho.status', $outbound[0]->nama_status)
            ->get();

        if (count($check_status_is_exist) == 0) {
            $outbound = DB::table('tbl_outbound as o')
                ->join('tbl_status_outbound as so',  'o.id_status', 'so.id_status')
                ->where('no_outbound', $nooutbound)
                ->get();
            DB::table('tbl_history_outbound')
                ->insert([
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => $outbound[0]->nama_status,
                    'id_outbound' => $outbound[0]->id_outbound,
                    'nama_user' => Auth::user()->name
                ]);
        } else {
            DB::table('tbl_history_outbound')
                ->where('id_history_outbound', $check_status_is_exist[0]->id_history_outbound)
                ->update([
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'nama_user' => Auth::user()->name
                ]);
        }
    }

    public function displayReport(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where("kode_gudang", $kodegudang)
                ->get();

            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();

            if ($request->filter == "Label") {
                $result = DB::table('tbl_outbound as o')
                    ->select('o.referensi as referensi', 'od.id_outbound_detail', 'o.no_outbound', 'od.label', 'o.tanggal_outbound', 'itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm', DB::raw('sum(od.qty) as qty_doc, sum(od.qty_load) as qty_load'), 'itm.berat_kotor as berat_kotor')
                    ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                    ->join('tbl_item as itm', 'od.id_item', '=', 'itm.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'o.id_project_has_gudang')
                    ->where('o.tanggal_outbound', '>=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))))
                    ->where('o.tanggal_outbound', '<=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))))
                    ->where('gp.id_project', $project[0]->id_project)
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->groupBy('od.id_outbound_detail', 'o.no_outbound', 'o.tanggal_outbound', 'itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm')
                    ->get();
            } else {
                $result = DB::table('tbl_outbound as o')
                    ->select('itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm', DB::raw('sum(od.qty) as qty_doc, sum(od.qty_load) as qty_load'), 'itm.berat_kotor as berat_kotor')
                    ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                    ->join('tbl_item as itm', 'od.id_item', '=', 'itm.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'o.id_project_has_gudang')
                    ->where('o.tanggal_outbound', '>=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))))
                    ->where('o.tanggal_outbound', '<=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))))
                    ->where('gp.id_project', $project[0]->id_project)
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->groupBy('itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm')
                    ->get();
            }
            $new_request = new Request([
                'action' => "display_report", 'type_report' => "outbound"
            ]);
            app(LogController::class)->log($new_request);
            return json_encode($result);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
    public function getAllItemStilLAvailable($kodeproject, $kodegudang)
    {
        try {
            $list_item = DB::table('tbl_item as i')
                ->Join('tbl_project_has_gudang as gp', 'i.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'gp.id_project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                ->join('tbl_inventory as inv', 'id.id_inbound_detail', '=', 'inv.id_inbound_detail')
                ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->where('invd.available', '!=', 0)
                ->groupBy('i.kode_item')
                ->get();

            return json_encode($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function cancelOutbound(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $list_item_outbound = DB::table('tbl_outbound as o')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->where('o.id_outbound', $request->idoutbound)
                ->get();

            $i = 0;
            $count = count($list_item_outbound);

            for ($i; $i < $count; $i++) {
                DB::table('tbl_outbound as o')
                    ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                    ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                    ->where('id.id_inventory_detail', $list_item_outbound[$i]->id_inventory_detail)
                    ->update([
                        'picked' => (int) $list_item_outbound[$i]->picked - $list_item_outbound[$i]->qty,
                        'available' => (int) $list_item_outbound[$i]->available + $list_item_outbound[$i]->qty
                    ]);
            }

            DB::table('tbl_outbound as o')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->where('o.id_outbound', $request->idoutbound)
                ->update([
                    'o.id_status' => 10
                ]);
            $this->insertHistoryOutbound($list_item_outbound[0]->no_outbound);

            $new_request = new Request([
                'action' => "cancel_outbound", 'no_outbound' => $list_item_outbound[0]->no_outbound
            ]);
            app(LogController::class)->log($new_request);

            $current_outbound = DB::table('tbl_outbound as o')
                ->join('tbl_status_outbound as so', 'o.id_status', '=', 'so.id_status')
                ->where('o.id_outbound', $request->idoutbound)
                ->get();
            return json_encode(['status' => 'success', 'nama_status' => $current_outbound[0]->nama_status]);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function pickingProses(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        $newRequest = new stdClass();
        $newRequest->idinventorydetail = $request->idinventorydetail;
        $newRequest->qty = $request->qty;
        $newRequest->idoutbounddetail = $request->idoutbounddetail;
        $newRequest->action = $request->action;
        // return json_encode($newRequest);

        $job = new ProsesPicked($newRequest, $kodeproject, $kodegudang, $nooutbound);
        // ProcessIncoming::dispatchNow()
        dispatch_now($job);

        return $job->getResponse();
    }

    public function transferItem($kodeproject, $kodegudang)
    {
        try {
            $result_project = DB::table('tbl_project as p')
                ->join('tbl_project_has_gudang as gp', 'p.id_project', '=', 'gp.id_Project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', "!=", $kodegudang)
                ->get();

            $template = $this->loadTemplateOutbound($kodeproject)->getData();
            $template_inbound = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();

            $list_item = app(ItemsController::class)->getAllItem($kodeproject, $kodegudang)->getData();

            return view('transfer', compact(['kodeproject', 'kodegudang', 'result_project', 'template', 'template_inbound', 'list_item']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getOutboundDetail($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
            $outbound_detail = DB::table("tbl_outbound as o")
                ->select("i.kode_item as kode_item", 'i.nama_item as nama_item', 'od.label as label_outbound', 'od.qty as qty', 'od.qty_load as qty_load', 'i.nama_uom as nama_uom', 'msi.nama_status as nama_status', 'l.nama_locator as nama_locator', 'inbd.label as label_inventory', 'inbd.custom_field_td as inbound_custom_field_td', 'id.id_inventory_detail as id_inventory_detail', 'od.id_outbound_detail as id_outbound_detail', 'o.no_outbound as no_outbound', 'od.id_status', 'o.referensi as referensi')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join('tbl_item as i', 'i.id_item', '=', 'od.id_item')
                ->join('tbl_inventory_detail as id', 'id.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->join('tbl_inventory as inv', 'id.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'inv.id_inbound_detail')
                ->join('tbl_locator as l', 'id.id_locator', '=', 'l.id_locator')
                ->join('tbl_master_status_item as msi', 'od.id_status', '=', 'msi.id_status')
                ->where('o.no_outbound', $nooutbound)
                ->groupBy('od.label')
                ->get();
            $history_outbound = DB::table('tbl_history_outbound as ho')
                ->join('tbl_outbound as o', 'o.id_outbound', '=', 'ho.id_outbound')
                ->where('o.no_outbound', $nooutbound)
                ->get();
            return response()->json(['outbound_detail' => $outbound_detail, 'history_outbound' => $history_outbound, 'project' => $project]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    public function updateAvailableQuantity(Request $request, $kodeproject, $kodegudang)
    {
        $newRequest = new stdClass();
        $newRequest->idinventorydetail = $request->idinventorydetail;
        $newRequest->qty = $request->qty;
        $newRequest->item = $request->item;
        $newRequest->idlocator = $request->idlocator;
        $newRequest->action = $request->action;
        $newRequest->filter = $request->filter;
        $newRequest->nooutbound = $request->nooutbound;
        $newRequest->idinventory = $request->idinventory;
        // return json_encode($newRequest);

        $job = new ProsesAllocated($newRequest, $kodeproject, $kodegudang);
        // ProcessIncoming::dispatchNow()
        dispatch_now($job);

        return json_encode($job->getResponse());
    }

    public function getUnsaveListItem(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $request->nooutbound)
                ->get();

            $list_item = DB::table('tbl_outbound_detail as od')
                ->select('id.allocated as allocated', 'it.id_item as id_item', 'it.kode_item as kode_item', 'it.nama_item as nama_item', 'l.nama_locator as nama_locator', 'l.id_locator as id_locator', 'inbd.custom_field_td as custom_field_td', 'i.id_inventory as id_inventory', 'id.id_inventory_detail as id_inventory_detail', 'o.id_outbound as id_outbound', 'o.no_outbound as no_outbound', 'o.tanggal_outbound as tanggal_outbound', 'o.destination as destination', 'o.id_status as id_status', 'o.custom_field as custom_field', 'od.id_outbound_detail', 'od.qty', 'od.label as label')
                ->join('tbl_outbound as o', 'od.id_outbound', '=', 'o.id_outbound')
                ->join('tbl_inventory_detail as id', 'od.id_inventory_detail', '=', 'id.id_inventory_detail')
                ->join('tbl_inventory as i', 'id.id_inventory', '=', 'i.id_inventory')
                ->join('tbl_locator as l', 'id.id_locator', '=', 'l.id_locator')
                ->join('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'i.id_inbound_detail')
                ->join('tbl_item as it', 'it.id_item', '=', 'inbd.id_item')
                ->where('o.no_outbound', $nooutbound)
                ->get();

            return json_encode($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function buatTemplateOutbound($kodeproject)
    {
        $myfile = "./template_outbound/outbound" . "_" . $kodeproject . ".json";
        $template = "";
        if (file_exists($myfile) == true) {
            $file = file_get_contents($myfile);
            $template = json_decode($file);
        }
        return view('buat_template_outbound', compact(['kodeproject', 'template']));
    }

    public function saveTemplateOutbound(Request $request, $kodeproject)
    {
        try {
            $template_outbound = $request->template;

            $myfile = "outbound" . "_" . $kodeproject . ".json";
            $fh = fopen("./template_outbound/" . DIRECTORY_SEPARATOR . $myfile, 'w') or die('cant open file');
            fwrite($fh, $template_outbound);
            fclose($fh);

            return json_encode('success');
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function loadTemplateOutbound($kodeproject)
    {
        try {
            $myfile = "./template_outbound/outbound" . "_" . $kodeproject . ".json";
            $template = "";
            if (file_exists($myfile) == true) {
                $file = file_get_contents($myfile);
                $template = json_decode($file);
            }
            return response()->json($template);
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function generateNomorOutbound($kodeproject, $kodegudang, $type_outbound)
    {
        $currentDate = date("dmy");
        $latestId_in_db = "";
        $awalan_kode_outbound = "";
        if ($type_outbound == "transfer") {
            $latestId_in_db = DB::table('tbl_outbound')
                ->where('no_outbound', 'like', "%OT" . $currentDate . "%")
                ->where('type_outbound', 'Transfer')
                ->orderBy('no_outbound', 'desc')
                ->limit(1)
                ->get();
            $awalan_kode_outbound = "OT";
        } else {
            $latestId_in_db = DB::table('tbl_outbound')
                ->where('no_outbound', 'like', "%O" . $currentDate . "%")
                ->orderBy('no_outbound', 'desc')
                ->limit(1)
                ->get();

            $awalan_kode_outbound = "O";
        }


        if (count($latestId_in_db) != 0) {

            $num_no_outbound = substr($latestId_in_db[0]->no_outbound, 7);
            $new_num_no_outbound = (int) $num_no_outbound + 1;
            $new_num_no_outbound = sprintf("%'.04d", $new_num_no_outbound);
            $new_no_outbound = $awalan_kode_outbound . $currentDate . $new_num_no_outbound;

            return $new_no_outbound;
        }

        return $awalan_kode_outbound . $currentDate . "0001";
    }

    public function getListOutbound(Request $request, $kodeproject, $kodegudang)
    {
        $filter_value = [];
        if ($request->has("filter_value") == false) {
            $filter_value = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        } else {
            $filter_value = json_decode($request->filter_value, true);
        }

        $list_outbound = DB::table('tbl_outbound as o')
            ->select(
                'o.id_outbound as id_outbound',
                'o.no_outbound as no_outbound',
                'o.referensi as referensi',
                'o.tanggal_outbound as tanggal_outbound',
                'o.destination as destination',
                'o.referensi as referens',
                'o.type_outbound as type_outbound',
                'o.custom_field as custom_field',
                'gp.id_project_has_gudang as id_project_has_gudang',
                'p.kode_project as kode_project',
                'g.kode_gudang as kode_gudang',
                'so.nama_status as nama_status',
                DB::raw('COALESCE(sum(od.qty), 0) as qty, COALESCE(sum(qty_load), 0) as qty_load')
            )
            ->leftJoin('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
            ->join('tbl_project_has_gudang as gp', 'o.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
            ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
            ->join('tbl_project as p', 'gp.id_project', '=', 'p.id_project')
            ->join('tbl_status_outbound as so', 'o.id_status', '=', 'so.id_status')
            ->where('g.kode_gudang', $kodegudang)
            ->where('p.kode_project', $kodeproject)
            ->whereIn('so.id_status', $filter_value)
            ->groupBy('o.no_outbound')
            ->get();

        return response()->json($list_outbound);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $kodeproject, $kodegudang)
    {
        $list_outbound = $this->getListOutbound($request, $kodeproject, $kodegudang)->getData();
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $template = $this->loadTemplateOutbound($kodeproject)->getData();
        $template_inbound = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();

        return view('daftar_outbound', compact(['list_outbound', 'kodegudang', 'kodeproject', 'template', 'template_inbound', 'projectGudang']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $template = $this->loadTemplateOutbound($kodeproject)->getData();
        $template_inbound = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();

        $list_item = app(ItemsController::class)->getAllItem($kodeproject, $kodegudang)->getData();
        return view('tambah_outbound', compact(['kodegudang', 'kodeproject', 'template', 'template_inbound', 'list_item', 'projectGudang']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = Gudang::where('kode_gudang', $kodegudang)->get();
            $project = Project::where('kode_project', $kodeproject)->get();

            if ($request->action == "save_outbound_header") {
                $idgudang_has_project = DB::table("tbl_project_has_gudang")
                    ->where('id_gudang', '=', $gudang[0]->id_gudang)
                    ->where('id_project', '=', $project[0]->id_project)
                    ->get();

                $type_outbound = "";
                $generated_no_outbound = "";
                if ($request->type_outbound == "Transfer") {
                    $generated_no_outbound = $this->generateNomorOutbound($kodeproject, $kodegudang, 'transfer');
                    $type_outbound = "Transfer";
                } else {
                    $generated_no_outbound = $this->generateNomorOutbound($kodeproject, $kodegudang, '');
                }

                DB::table('tbl_outbound')->insert([
                    "no_outbound" => $generated_no_outbound,
                    "tanggal_outbound" => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_outbound))),
                    'destination' => $request->tujuan,
                    'custom_field' => $request->outbound_header_value,
                    'id_project_has_gudang' => $idgudang_has_project[0]->id_project_has_gudang,
                    'id_status' => 8,
                    'type_outbound' => $type_outbound,
                    'referensi' => $request->referensi
                ]);

                $last_inserted_id_outbound = DB::table('tbl_outbound as o')
                    ->join('tbl_status_outbound as so', 'o.id_status', '=', 'so.id_status')
                    ->orderBy('id_outbound', 'desc')
                    ->get();

                $new_request = new Request([
                    'action' => "tambah_outbound", 'no_outbound' => $last_inserted_id_outbound[0]->no_outbound
                ]);
                app(LogController::class)->log($new_request);

                return json_encode($last_inserted_id_outbound[0]->no_outbound);
            } else {
                $outbound = DB::table('tbl_outbound')
                    ->where('no_outbound', $request->nooutbound)
                    ->get();

                $list_item = json_decode($request->list_item);

                $count_item = count($list_item);
                $i = 0;
                $j = 0;
                // for ($i; $i < $count_item; $i++) {
                //     $insert = DB::table('tbl_outbound_detail')->insert([
                //         'qty' => $list_item[$i]->qty,
                //         'qty_load' => 0,
                //         'label' => $outbound[0]->no_outbound . sprintf("%'.04d", ($j + 1)),
                //         'id_outbound' => $outbound[0]->id_outbound,
                //         'id_item' => $list_item[$i]->id_item,
                //         'id_status' => 13,
                //         'id_inventory_detail' => $list_item[$i]->idinventorydetail,
                //         'id_inventory' => $list_item[$i]->idinventory,
                //         'id_locator' => $list_item[$i]->idlocator
                //     ]);
                // }

                DB::table('tbl_outbound')
                    ->where('id_outbound', $outbound[0]->id_outbound)
                    ->update([
                        'id_status' => 7
                    ]);

                $this->insertHistoryOutbound($request->nooutbound);

                return json_encode("success");
            }
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound = DB::table('tbl_outbound')
                ->where("no_outbound", '=', $nooutbound)
                ->get();

            return json_encode($outbound);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->get();

            $outbound_detail = "";
            if ($outbound[0]->id_status == 9) {
                $outbound = DB::table('tbl_outbound')
                    ->where('no_outbound', $nooutbound)
                    ->get();
                $outbound_detail = DB::table('tbl_outbound as o')
                    ->select('id.allocated as allocated', 'it.id_item as id_item', 'it.kode_item as kode_item', 'it.nama_item as nama_item', 'l.nama_locator as nama_locator', 'l.id_locator as id_locator', 'inbd.custom_field_td as custom_field_td', 'i.id_inventory as id_inventory', 'id.id_inventory_detail as id_inventory_detail', 'o.id_outbound as id_outbound', 'o.no_outbound as no_outbound', 'o.tanggal_outbound as tanggal_outbound', 'o.destination as destination', 'o.id_status as id_status', 'o.custom_field as custom_field', 'o.referensi as referensi')
                    ->leftJoin('tbl_outbound_detail as od', 'od.id_outbound', '=', 'o.id_outbound')
                    ->leftJoin('tbl_inventory_detail as id', 'od.id_inventory_detail', '=', 'id.id_inventory_detail')
                    ->leftJoin('tbl_inventory as i', 'id.id_inventory', '=', 'i.id_inventory')
                    ->leftJoin('tbl_locator as l', 'id.id_locator', '=', 'l.id_locator')
                    ->leftJoin('tbl_inbound_detail as inbd', 'inbd.id_inbound_detail', '=', 'i.id_inbound_detail')
                    ->leftJoin('tbl_item as it', 'it.id_item', '=', 'inbd.id_item')
                    ->where('o.id_outbound', $outbound[0]->id_outbound)
                    ->get();
            } else if ($outbound[0]->id_status == 8) {
                $outbound_detail = $outbound;
            } else {
                $outbound_detail = DB::table('tbl_outbound as o')
                    ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                    ->join('tbl_item as i', 'od.id_item', '=', 'i.id_item')
                    ->join('tbl_inventory_detail as id', 'od.id_inventory_detail', '=', 'id.id_inventory_detail')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'id.id_locator')
                    ->where('no_outbound', $nooutbound)
                    ->get();
            }

            $template = $this->loadTemplateOutbound($kodeproject)->getData();
            $template_inbound = app(InboundController::class)->loadTemplateInbound($kodeproject)->getData();

            return view('edit_outbound', compact(['outbound_detail', 'kodegudang', 'kodeproject', 'nooutbound', 'template', 'template_inbound', 'projectGudang']));
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $gudang = Gudang::where('kode_gudang', $kodegudang)->get();
            $project = Project::where('kode_project', $kodeproject)->get();
            $idgudang_has_project = DB::table("tbl_project_has_gudang")
                ->where('id_gudang', '=', $gudang[0]->id_gudang)
                ->where('id_project', '=', $project[0]->id_project)
                ->get();

            $inbound = DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->update([
                    "no_outbound" => $request->no_outbound,
                    "tanggal_outbound" => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_outbound))),
                    'destination' => $request->tujuan,
                    'custom_field' => $request->outbound_header_value,
                    'referensi' => $request->referensi
                ]);

            $new_request = new Request([
                'action' => "edit_outbound", 'no_outbound' => $nooutbound
            ]);
            app(LogController::class)->log($new_request);


            $outbound = DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->get();

            // $list_item = json_decode($request->list_item);

            // $count_item = count($list_item);
            // $i = 0;
            // for ($i; $i < $count_item; $i++) {
            //     $insert = DB::table('tbl_outbound_detail')->insert([
            //         'qty' => $list_item[$i]->qty,
            //         'qty_load' => 0,
            //         'id_outbound' => $outbound[0]->id_outbound,
            //         'id_item' => $list_item[$i]->id_item,
            //         'id_status' => 13,
            //         'id_inventory_detail' => $list_item[$i]->idinventorydetail,
            //         'id_inventory' => $list_item[$i]->idinventory,
            //         'id_locator' => $list_item[$i]->idlocator
            //     ]);

            //     $item = DB::table('tbl_item')->where('id_item', $list_item[$i]->id_item)->get();

            //     $new_request = new Request(['action' => "allocated", 'no_outbound' => $nooutbound, 'qty' => $list_item[$i]->qty, 'kode_item' =>  $item[0]->kode_item, 'nama_item' =>  $item[0]->nama_item, 'nama_uom' => $item[0]->nama_uom]);
            //     app(LogController::class)->log($new_request);
            // }

            DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->update([
                    'id_status' => 7
                ]);

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
    public function destroy($kodeproject, $kodegudang, $nooutbound)
    {
        try {
            $outbound_detail = DB::table('tbl_outbound as o')
                ->join('tbl_outbound_detail as od', 'o.id_outbound', '=', 'od.id_outbound')
                ->join('tbl_inventory_detail as invd', 'invd.id_inventory_detail', '=', 'od.id_inventory_detail')
                ->where('o.no_outbound', $nooutbound)
                ->get();

            $i = 0;
            $count = count($outbound_detail);
            if ($count != 0) {
                for ($i; $i < $count; $i++) {
                    DB::table('tbl_inventory_detail')
                        ->where('id_inventory_detail', $outbound_detail[$i]->id_inventory_detail)
                        ->update([
                            'allocated' => $outbound_detail[$i]->allocated - $outbound_detail[$i]->qty,
                            'available' => $outbound_detail[$i]->available + $outbound_detail[$i]->qty
                        ]);
                }
            } else {
                $outbound_detail = DB::table('tbl_outbound')
                    ->where('no_outbound', $nooutbound)
                    ->get();
            }

            DB::table('tbl_history_outbound')
                ->where('id_outbound', $outbound_detail[0]->id_outbound)
                ->delete();

            DB::table('tbl_outbound_detail')
                ->where('id_outbound', $outbound_detail[0]->id_outbound)
                ->delete();

            DB::table('tbl_outbound')
                ->where('no_outbound', $nooutbound)
                ->delete();

            $new_request = new Request([
                'action' => "hapus_outbound", 'no_outbound' => $nooutbound
            ]);
            app(LogController::class)->log($new_request);
            return json_encode("success");
            // return json_encode($outbound_detail);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
