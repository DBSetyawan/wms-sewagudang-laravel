<?php

namespace App\Http\Controllers;

use App\Gudang;
use App\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Spatie\WebhookServer\WebhookCall;

date_default_timezone_set("Asia/Jakarta");
class InboundController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function getInformationInbound($noinbound)
    {
        try {
            $inbound = DB::table('tbl_inbound')
                ->where('no_inbound', $noinbound)
                ->get();

            return response()->json($inbound, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 200);
        }
    }

    public function saveNoteInbound(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {
            DB::table('tbl_inbound')
                ->where('no_inbound', $noinbound)
                ->update([
                    'note' => $request->note
                ]);

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function printInvoice($kodeproject, $kodegudang, $noinbound)
    {
        try {
            $inbound = DB::table('tbl_inbound as i')
                ->join('tbl_inbound_detail as id', 'i.id_inbound', '=', 'id.id_inbound')
                ->join('tbl_item as itm', 'id.id_item', '=', 'itm.id_item')
                ->join('tbl_project_has_gudang as pg', 'i.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_perusahaan_customer as pc', 'p.id_perusahaan', '=', 'pc.id_perusahaan')
                ->where('i.no_inbound', $noinbound)
                ->get();

            $subtotalCBM = 0;
            $subtotalTotalCBM = 0;
            $subTotalBerat = 0;
            $subtotalTotalBerat = 0;
            $subtotalQty = 0;
            foreach ($inbound as $item) {
                $subtotalCBM += $item->cbm;
                $subtotalTotalCBM += $item->cbm * $item->qty_aktual;
                $subTotalBerat += $item->berat_kotor;
                $subtotalTotalBerat += $item->berat_kotor * $item->qty_aktual;
                $subtotalQty += $item->qty_aktual;
            }

            $template = $this->loadTemplateInbound($kodeproject)->getData();
            $daftar_inbound_header = $template->daftar_label_custom_inbound_header;

            $new_request = new Request([
                'action' => "print_invoice_inbound",
                'no_inbound' => $noinbound
            ]);
            app(LogController::class)->log($new_request);
            return view('invoice_print_inbound', compact(['inbound', 'subtotalCBM', 'subtotalTotalCBM', 'subTotalBerat', 'subtotalTotalBerat', 'daftar_inbound_header', 'subtotalQty']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function AddItemToExistingInbound(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {

            $inbound = DB::table('tbl_inbound as i')
                ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
                ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                ->where('i.no_inbound', $noinbound)
                ->orderBy('id.id_inbound_detail', 'desc')
                ->get();
            $count = count($inbound);
            if ($count > 0) {
                $index = $inbound[0]->index;
                $count_inbound_detail = count($inbound);
                if ($inbound[0]->nama_status != "Complete" || $inbound[0]->nama_status != "Done") {
                    $list_item = json_decode($request->list_item, true);
                    foreach ($list_item as $item) {
                        DB::table('tbl_inbound_detail')->insert([
                            'qty' => $item['qty'],
                            'qty_aktual' => 0,
                            'custom_field_td' => $item['custom_field_td'],
                            // 'batch' => $item['batch'],
                            'label' => ($item['label'] == "") ? $inbound[0]->no_inbound . sprintf("%'.04d", ($count_inbound_detail + 1)) : $item['label'],
                            'id_item' => $item['id_item'],
                            'id_inbound' => $inbound[0]->id_inbound,
                            'id_status' => 2,
                            'index' =>  $index + 1
                        ]);

                        $last_inserted_id_inbound_detail = DB::table('tbl_inbound_detail')
                            ->where('id_inbound', $inbound[0]->id_inbound)
                            ->orderBy('id_inbound_detail', 'desc')
                            ->get();

                        DB::table('tbl_inventory')
                            ->insert([
                                'id_inbound_detail' => $last_inserted_id_inbound_detail[0]->id_inbound_detail
                            ]);
                    }

                    return response()->json("sucess");
                }
            } else {
                $index = 1;
                $inbound = DB::table('tbl_inbound')->where('no_inbound', $noinbound)->get();
                $list_item = json_decode($request->list_item, true);
                foreach ($list_item as $item) {
                    DB::table('tbl_inbound_detail')->insert([
                        'qty' => $item['qty'],
                        'qty_aktual' => 0,
                        'custom_field_td' => $item['custom_field_td'],
                        // 'batch' => $item['batch'],
                        'label' => ($item['label'] == "") ? $inbound[0]->no_inbound . sprintf("%'.04d", ($index)) : $item['label'],
                        'id_item' => $item['id_item'],
                        'id_inbound' => $inbound[0]->id_inbound,
                        'id_status' => 2,
                        'index' =>  $index + 1
                    ]);

                    $last_inserted_id_inbound_detail = DB::table('tbl_inbound_detail')
                        ->where('id_inbound', $inbound[0]->id_inbound)
                        ->orderBy('id_inbound_detail', 'desc')
                        ->get();

                    DB::table('tbl_inventory')
                        ->insert([
                            'id_inbound_detail' => $last_inserted_id_inbound_detail[0]->id_inbound_detail
                        ]);
                }

                return response()->json("sucess");
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function getCustomLabelType($argDaftarCustomLabel, $argNamaCustomLabel)
    {
        foreach ($argDaftarCustomLabel as $label) {
            if ($label->nama_label == $argNamaCustomLabel) {
                return $label->type_label;
            }
        }
    }

    public function importExcelInbound(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $index = 1;
            $column_custom_label = "";

            $template_inbound = $this->loadTemplateInbound($kodeproject)->getData();
            $count_custom_label = count($template_inbound->daftar_label_custom_inbound_header);
            $daftar_custom_label = $template_inbound->daftar_label_custom_inbound_header;
            $total_column_inbound_header = 3;


            $inbound_header_value = array();
            $inbound_detail_value = array();
            $list_item_from_inbound = array();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
            $reader->setReadDataOnly(TRUE);
            $reader->setReadEmptyCells(false);
            $sheet = $reader->load($_FILES['excel_inbound']['tmp_name']);
            $active_sheet = $sheet->getActiveSheet();
            $highest_row = $active_sheet->getHighestRow();
            $highest_column = $active_sheet->getHighestColumn();

            //looping untuk mengambil value inbound header
            for ($column = "A"; $column <= $highest_column; $column++) {
                if ($index > ($total_column_inbound_header)) {
                    $column_custom_label = $column;
                    break;
                } else {
                    /* Mengecek apakah index yang saat ini adalah index A (tanggal) ? */
                    if ($index == 1) {
                        /* Mengecek apakah nilai pada kolom A2 bertipe integer? (karena nilai cell yang diterima akan berupa int/timestamps jika file excel pada kolom tersebut bertype date) */
                        if (is_int($active_sheet->getCellByColumnAndRow($index, 2)->getValue()) == true) {
                            $date_php = Date::excelToDateTimeObject($active_sheet->getCellByColumnAndRow($index, 2)->getValue());
                            $date_php = date('Y-m-d', $date_php->getTimestamp());
                            array_push($inbound_header_value,  $date_php);
                        } else if ($active_sheet->getCellByColumnAndRow($index, 2)->getValue() == "") {
                            array_push($inbound_header_value,  date('Y-m-d'));
                        } else {
                            $date_php = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $active_sheet->getCellByColumnAndRow($index, 2)->getValue())));
                            array_push($inbound_header_value,  $date_php);
                        }
                    } else {
                        array_push($inbound_header_value,  $active_sheet->getCellByColumnAndRow($index, 2)->getValue());
                    }
                    $index++;
                }
            }

            /* Looping untuk mengambil custom label pada inbound header */
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
            array_push($inbound_header_value, $custom_label);

            /*loopong untuk mengambil value inbound detail*/
            $item_object_value = array();
            $array_custom_label_value = array();
            for ($row = 6; $row <= $highest_row; ++$row) {
                $index = 1;
                for ($col = 'A'; $col <= $highest_column; ++$col) {
                    if ($index > 12) {
                        $column_custom_label = $col;
                        break;
                    }
                    // else if ($index == 12) {
                    //     $date = "";
                    //     if (is_int($active_sheet->getCell($col . $row)->getValue()) == true) {
                    //         $date_php = Date::excelToDateTimeObject($active_sheet->getCell($col . $row)->getValue());
                    //         $date_php = date('Y-m-d', $date_php->getTimestamp());
                    //         $date = $date_php;
                    //     } else {
                    //         $date_php = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $active_sheet->getCell($col . $row)->getValue())));
                    //         $date = $date_php;
                    //     }
                    //     array_push($item_object_value, $date);} 
                    else {
                        /* Memasukkan setiap value cell pada sebuah array */
                        array_push($item_object_value, $active_sheet->getCell($col . $row)->getValue());
                    }
                    $index++;
                }

                /* Looping untuk mengambil value custom label inbound detail */
                $item_custom_field_td = "";
                for ($col = $column_custom_label; $col <= $highest_column; $col++) {
                    /* Mengambil tipe custom label */
                    $type_custom_label = $this->getCustomLabelType($template_inbound->daftar_label_custom_detail_inbound, $active_sheet->getCell($col . 5)->getValue());

                    /* Mengecek apakah tipe custom label date /text */
                    if ($type_custom_label == "date") {
                        /* Mengecek apakah nilai cell bertipe integer */
                        if (is_int($active_sheet->getCell($col . $row)->getValue()) == true) {
                            $date_php = Date::excelToDateTimeObject($active_sheet->getCell($col . $row)->getValue());
                            $date_php = date('Y-m-d', $date_php->getTimestamp());
                            $date = $date_php;
                        } else {
                            $date_php = date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $active_sheet->getCell($col . $row)->getValue())));
                            $date = $date_php;
                        }

                        /* Menambahkan tag td pada variabel penampung */
                        $item_custom_field_td = $item_custom_field_td . '<td class="custom_label ' . $active_sheet->getCell($col . 5)->getValue() . '">' . $date . '</td>';
                    } else if ($type_custom_label == "text") {
                        $item_custom_field_td = $item_custom_field_td . '<td class="custom_label ' . $active_sheet->getCell($col . 5)->getValue() . '">' . $active_sheet->getCell($col . $row)->getValue() . '</td>';
                    }
                }

                /* Memasukkan custom field td ke dalam array item */
                array_push($item_object_value, $item_custom_field_td);
                $item_custom_field_td = "";

                /* Memasukkan kode item pada sebuah array */
                (in_array($active_sheet->getCell("A" . $row)->getValue(), $list_item_from_inbound) ?:  array_push($list_item_from_inbound, $active_sheet->getCell("A" . $row)->getValue()));

                /* Memasukkan array item ke dalam sebuah array */
                array_push($inbound_detail_value, $item_object_value);
                $item_object_value = array();
            }
            // dd($inbound_detail_value);

            /* Cek apakah item yang ada didalam excel terdaftar di DB */
            $projet_has_gudang = DB::table('tbl_project_has_gudang as pg')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where('g.kode_gudang', $kodegudang)
                ->where('p.kode_project', $kodeproject)
                ->get();

            $list_item_exist = DB::table('tbl_item')
                ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                ->whereIn('kode_item', $list_item_from_inbound)
                ->groupBy("kode_item")
                ->get();

            // dd($list_item_exist);
            /* menghapus item yagn sudah terdaftar di db */
            $list_item_in_inbound = $inbound_detail_value;
            // dd($list_item_exist);
            foreach ($list_item_exist as $item) {
                $index_item_inbound_detail = 0;
                foreach ($list_item_in_inbound as $item_inbound) {
                    if ($item->kode_item == $item_inbound[0]) {
                        array_splice($list_item_in_inbound, $index_item_inbound_detail, 1);
                        // echo $item_inbound[0]->kode_item;
                    }
                    $index_item_inbound_detail++;
                }
            }
            // dd($list_item_in_inbound);
            /* Memasukkan item baru ke database */
            foreach ($list_item_in_inbound as $item_baru) {
                $item_exist = DB::table('tbl_item')
                    ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                    ->where('kode_item', $item_baru[0])
                    ->get();

                if (count($item_exist) == 0) {
                    DB::table('tbl_item')
                        ->insert([
                            'kode_item' => $item_baru[0],
                            'label_barcode' => (explode(" ", $item_baru[1]) == "" || $item_baru[1] == null) ? $item_baru[0] : $item_baru[1],
                            'nama_item' => $item_baru[2],
                            'minimal_qty' => 1,
                            'cara_hitung_cbm' => 'manual',
                            'panjang' => ($item_baru[7] == null) ? 0 : $item_baru[7],
                            'lebar' => ($item_baru[8] == null) ? 0 : $item_baru[8],
                            'tinggi' => ($item_baru[9] == null) ? 0 : $item_baru[9],
                            'cbm' => ($item_baru[10] == null || $item_baru[3] == null) ? 0 : $item_baru[10] / $item_baru[3],
                            'berat_bersih' => ($item_baru[6] == null) ? 0 : $item_baru[6],
                            'berat_kotor' => ($item_baru[5] == null) ? 0 : $item_baru[5],
                            'tonase' => 0,
                            'nama_uom' => $item_baru[4],
                            'id_project_has_gudang' => $projet_has_gudang[0]->id_project_has_gudang
                        ]);
                }
            }

            /* Membuat Inbound */
            DB::table('tbl_inbound')
                ->insert([
                    'no_inbound' => $this->generateNomorInbound($kodeproject, $kodegudang),
                    'tanggal_inbound' => $inbound_header_value[0],
                    'origin' => $inbound_header_value[1],
                    'referensi' => $inbound_header_value[2],
                    'custom_field' => json_encode($inbound_header_value[3]),
                    'id_project_has_gudang' => $projet_has_gudang[0]->id_project_has_gudang,
                    'id_status' => 7
                ]);



            /* Membuat Inbound Detail */
            $lastest_inserted_inbound = DB::table('tbl_inbound as i')
                ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                ->orderBy('id_inbound', 'desc')
                ->limit(1)
                ->get();
            // dd($lastest_inserted_inbound);
            // dd($inbound_detail_value);
            $index = 1;
            foreach ($inbound_detail_value as $item) {
                $item_from_db = DB::table('tbl_item')
                    ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
                    ->where('kode_item', $item[0])
                    ->get();
                // dd($item_from_db);
                // echo $item[0];
                // echo $item_from_db[0]->id_item . " \n";
                DB::table('tbl_inbound_detail')
                    ->insert([
                        'qty' => $item[3],
                        'qty_aktual' => 0,
                        'custom_field_td' => $item[12],
                        'label' => ($item[11] == "") ? $lastest_inserted_inbound[0]->no_inbound . sprintf("%'.04d", $index) : $item[11],
                        'index' => $index,
                        'id_inbound' => $lastest_inserted_inbound[0]->id_inbound,
                        'id_status' => 2,
                        'id_item' => $item_from_db[0]->id_item
                    ]);

                $lastest_inserted_inbound_detail = DB::table('tbl_inbound_detail')
                    ->where('id_inbound', $lastest_inserted_inbound[0]->id_inbound)
                    ->orderBy('id_inbound_detail', 'desc')
                    ->limit(1)
                    ->get();

                // dd($lastest_inserted_inbound_detail);
                DB::table('tbl_inventory')
                    ->insert([
                        'id_inbound_detail' => $lastest_inserted_inbound_detail[0]->id_inbound_detail
                    ]);

                $index++;
            }

            DB::table('tbl_history_inbound')
                ->insert([
                    'id_inbound' => $lastest_inserted_inbound[0]->id_inbound,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => $lastest_inserted_inbound[0]->nama_status,
                    'nama_user' => Auth::user()->name
                ]);

            return redirect()->back();
        } catch (\Throwable $th) {
            // return response()->json($th->getMessage() . " - " . $th->getLine());
            abort(500, $th->getMessage());
        }
    }

    public function exportExcelInbound($kodeproject, $kodegudang)
    {
        $custom_field_inbound_header = "";
        $custom_field_inbound_detail = "";
        $filename = date('d-m-Y_H:i:s') . '_' . 'template_inbound_project_' . $kodeproject . ".xls";

        $template = $this->loadTemplateInbound($kodeproject)->getData();
        //Mengambil custom field inbound header
        foreach ($template->daftar_label_custom_inbound_header as $label) {
            $custom_field_inbound_header = $custom_field_inbound_header . "<th>" . $label->nama_label . "</th>";
        }

        //mengambil custom field inbound detail
        foreach ($template->daftar_label_custom_detail_inbound as $label) {
            $custom_field_inbound_detail = $custom_field_inbound_detail . "<th>" . $label->nama_label . "</th>";
        }

        $format_inbound_header = "
            <table>
                <tr>
                    <th>Date Inbound (DD/MM/YYYY)</th>
                    <th>Origin/Asal</th>
                    <th>Referensi</th>
                    $custom_field_inbound_header
                </tr>
                <tr></tr>
                <tr></tr>
                <tr></tr>
                <tr>
                    <td>Kode Item</td>
                    <td>Label Barcode Item</td>
                    <td>Deskripsi Item</td>
                    <td>Quantity</td>
                    <td>UOM</td>
                    <td>Berat Kotor</td>
                    <td>Berat Bersih</td>
                    <td>P</td>
                    <td>L</td>
                    <td>T</td>
                    <td>Total CBM</td>
                    <td>Label</td>
                    $custom_field_inbound_detail
                </tr>
            </table>
        ";

        $reader = new Html();
        $speardsheet = $reader->loadFromString($format_inbound_header);
        $writer = IOFactory::createWriter($speardsheet, "Xls");
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        return $response;
    }

    public function uploadPOD(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {
            $inbound = DB::table('tbl_inbound')->where('no_inbound', $noinbound)->get();
            $i = 0;
            $files_length = count($_FILES['pod']['name']);
            for ($i; $i < $files_length; $i++) {
                DB::table('tbl_pod_inbound')
                    ->insert([
                        'nama_file' => $_FILES['pod']['name'][$i],
                        'waktu_upload' => date('Y-m-d H:i:s'),
                        'id_inbound' => $inbound[0]->id_inbound
                    ]);

                move_uploaded_file($_FILES['pod']['tmp_name'][$i], './pod_inbound/' . $_FILES['pod']['name'][$i]);

                $new_request = new Request([
                    'action' => "upload_pod_inbound",
                    'no_inbound' => $noinbound,
                    'nama_file' => $_FILES['pod']['name'][$i],

                ]);
                app(LogController::class)->log($new_request);
            }

            $list_pod_files = DB::table('tbl_inbound as i')
                ->join('tbl_pod_inbound as pi', 'pi.id_inbound', '=', 'i.id_inbound')
                ->where('i.no_inbound', $noinbound)
                ->get();

            return json_encode(['success', $list_pod_files]);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllPODList($kodeproject, $kodegudang, $noinbound)
    {
        try {
            $list_pod_files = DB::table('tbl_inbound as i')
                ->join('tbl_pod_inbound as pi', 'pi.id_inbound', '=', 'i.id_inbound')
                ->where('i.no_inbound', $noinbound)
                ->get();

            return json_encode($list_pod_files);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function downloadFile($kodeproject, $kodegudang, $noinbound, $idpod)
    {
        $file = DB::table('tbl_pod_inbound')->where('id_pod', $idpod)->get();

        $filename = "./pod_inbound/" . $file[0]->nama_file;

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

    public function hapusFile($kodeproject, $kodegudang, $noinbound, $idpod)
    {
        try {
            $file = DB::table('tbl_pod_inbound')->where('id_pod', $idpod)->get();

            $filename = "./pod_inbound/" . $file[0]->nama_file;

            if (file_exists($filename)) {
                unlink($filename);
                DB::table('tbl_pod_inbound')->where('id_pod', $idpod)->delete();
            }
            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function removeItemFromDB(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {
            $item = DB::table('tbl_inbound_detail')
                ->where('id_inbound_detail', $request->idinbounddetail)
                ->get();

            if (count($item) > 0) {

                DB::table('tbl_inventory')
                    ->where('id_inbound_detail', $request->idinbounddetail)
                    ->delete();

                DB::table('tbl_inbound_detail')
                    ->where('id_inbound_detail', $request->idinbounddetail)
                    ->delete();

                return json_encode("success");
            }
            return json_encode("not_contain");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
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
                $result = DB::table('tbl_inbound as i')
                    ->select('id.id_inbound_detail', 'i.no_inbound', 'i.referensi as referensi', 'id.label', 'i.tanggal_inbound', 'i.no_inbound', 'id.custom_field_td', 'itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'l.nama_locator as nama_locator', 'itm.cbm', DB::raw('sum(id.qty) as qty_doc, sum(id.qty_aktual) as qty_aktual'), 'i.origin as origin', 'itm.berat_kotor as berat_kotor')
                    ->join('tbl_inbound_detail as id', 'i.id_inbound', '=', 'id.id_inbound')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                    ->join('tbl_item as itm', 'id.id_item', '=', 'itm.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'i.id_project_has_gudang')
                    ->where('i.tanggal_inbound', '>=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))))
                    ->where('i.tanggal_inbound', '<=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))))
                    ->where('gp.id_project', $project[0]->id_project)
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->where('i.id_status', 6)
                    ->groupBy('invd.id_inventory_detail', 'l.id_locator')
                    ->get();
            } else {
                $result = DB::table('tbl_inbound as i')
                    ->select('itm.id_item', 'id.custom_field_td', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm', DB::raw('sum(id.qty) as qty_doc, sum(id.qty_aktual) as qty_aktual'), 'i.origin as origin', 'i.referensi as referensi', 'itm.berat_kotor as berat_kotor')
                    ->join('tbl_inbound_detail as id', 'i.id_inbound', '=', 'id.id_inbound')
                    ->join('tbl_item as itm', 'id.id_item', '=', 'itm.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'i.id_project_has_gudang')
                    ->where('i.tanggal_inbound', '>=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_awal))))
                    ->where('i.tanggal_inbound', '<=', date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_akhir))))
                    ->where('gp.id_project', $project[0]->id_project)
                    ->where('gp.id_gudang', $gudang[0]->id_gudang)
                    ->where('i.id_status', 6)
                    ->groupBy('itm.id_item', 'itm.kode_item', 'itm.nama_item', 'itm.nama_uom', 'itm.cbm', 'id.custom_field_td')
                    ->get();
            }
            $new_request = new Request([
                'action' => "display_report",
                'type_report' => "inbound"
            ]);
            app(LogController::class)->log($new_request);
            return json_encode($result);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getDetailInbound($kodeproject, $kodegudang, $noinbound)
    {
        try {
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
            $query = DB::table('tbl_inbound as i')
                ->join('tbl_inbound_detail as id', "i.id_inbound", '=', "id.id_inbound")
                ->join('tbl_item as item', "item.id_item", "=", "id.id_item")
                ->where('i.no_inbound', $noinbound)
                ->get();

            $history = DB::table('tbl_history_inbound')
                ->where('id_inbound', $query[0]->id_inbound)
                ->get();

            // return json_encode([$query, $history, $project]);
            return response()->json(['inbound_detail' => $query, 'history' => $history, 'project' => $project]);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getItemInfoInInboundDetail(Request $request, $kodeproject, $kodegudang, $noinbound, $iditem)
    {
        try {
            // $indbound = DB::table('tbl_inbound')
            //     ->where("no_inbound", $noinbound)
            //     ->get();

            $query = DB::table('tbl_inbound_detail')
                ->where('id_inbound_detail', $request->idinbounddetail)
                ->where('id_item', $iditem)
                ->get();

            return json_encode($query);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function generateNomorInbound($kodeproject, $kodegudang)
    {
        $currentDate = date("dmy");
        $latestId_in_db = "";

        $latestId_in_db = DB::table('tbl_inbound')
            ->where('no_inbound', 'like', "%I$currentDate%")
            ->orderBy('no_inbound', 'desc')
            ->limit(1)
            ->get();

        if (count($latestId_in_db) != 0) {
            $num_no_inbound = substr($latestId_in_db[0]->no_inbound, 7);
            $new_num_no_inbound = $num_no_inbound + 1;
            $new_num_no_inbound = sprintf("%'.04d", $new_num_no_inbound);
            $new_no_inbound = "I" . $currentDate . $new_num_no_inbound;

            // dd($latestId_in_db);
            return $new_no_inbound;
        }

        return "I" . $currentDate . "0001";
    }

    public function buatTemplateInbound($kodeproject)
    {
        $myfile = "./template_inbound/inbound" . "_" . $kodeproject . ".json";
        $template = "";
        if (file_exists($myfile) == true) {
            $file = file_get_contents($myfile);
            $template = json_decode($file);
        }
        return view('buat_template_inbound', compact(['kodeproject', 'template']));
    }

    public function saveTemplateInbound(Request $request, $kodeproject)
    {
        try {
            $template_inbound = $request->template;

            $myfile = "inbound" . "_" . $kodeproject . ".json";
            $fh = fopen('./template_inbound/' . DIRECTORY_SEPARATOR . $myfile, 'w') or die('cant open file');
            fwrite($fh, $template_inbound);
            fclose($fh);

            return json_encode('success');
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function loadTemplateInbound($kodeproject)
    {
        try {
            $myfile = "./template_inbound/inbound" . "_" . $kodeproject . ".json";
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

    public function getListInbound(Request $request, $kodeproject, $kodegudang)
    {
        /*
        DB::raw("select sum(id.qty) as qty, sum(id.qty_aktual) as qty_aktual
                        from tbl_inbound as i 
                        inner join tbl_inbound_detail as id on id.id_inbound = i.id_inbound
                        inner join tbl_project_has_gudang as pg on pg.id_project_has_gudang = i.id_project_has_gudang
                        inner join tbl_project as p on p.id_project = pg.id_project_has_gudang
                        inner join tbl_gudang as g on g.id_gudang = pg.id_gudang
                        where p.kode_project = '" + $kodeproject + "' and g.kode_gudang = '" + $kodegudang + "'
                        group by i.no_inbound")
        */

        $filter_value = [];
        if ($request->has('filter_value') == false) {
            $filter_value = [1, 2, 3, 4, 5, 6, 7, 8];
        } else {
            $filter_value = json_decode($request->filter_value, true);
        }
        $list_inbound = DB::table("tbl_inbound as i")
            ->select(
                'i.id_inbound',
                'i.no_inbound',
                'i.tanggal_inbound',
                'i.referensi',
                'i.note',
                'i.origin',
                'i.metode',
                'i.type_inbound',
                'i.custom_field',
                'i.id_project_has_gudang',
                'si.id_status',
                'si.nama_status',
                'p.kode_project',
                'p.nama_project',
                'p.tanggal_project',
                'p.id_perusahaan',
                'g.kode_gudang',
                'g.nama_gudang',
                'g.alamat_gudang',
                DB::raw('COALESCE(sum(id.qty), 0) as qty, COALESCE(sum(qty_aktual), 0) as qty_aktual')
            )
            ->leftJoin("tbl_inbound_detail as id", 'id.id_inbound', '=', 'i.id_inbound')
            ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
            ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'i.id_project_has_gudang')
            ->join('tbl_project as p', 'gp.id_project', '=', 'p.id_project')
            ->join('tbl_gudang as g', 'g.id_gudang', '=', 'gp.id_gudang')
            ->where('p.kode_project', $kodeproject)
            ->where('g.kode_gudang', $kodegudang)
            ->whereIn('si.id_status', $filter_value)
            ->groupBy('i.no_inbound')
            ->get();

        return response()->json($list_inbound);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $list_inbound = $this->getListInbound($request, $kodeproject, $kodegudang)->getData();
            $template = $this->loadTemplateInbound($kodeproject)->getData();
            return view('daftar_inbound', compact(['list_inbound', 'kodegudang', 'kodeproject', 'template', 'projectGudang']));
        } catch (\Throwable $th) {
            return view($th->getMessage());
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
        $template = $this->loadTemplateInbound($kodeproject)->getData();
        $list_item = app(ItemsController::class)->getAllItem($kodeproject, $kodegudang)->getData();
        return view('tambah_inbound', compact(['kodegudang', 'kodeproject', 'template', 'list_item', 'projectGudang']));
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
            if ($request->action == "save_inbound_header") {
                $idgudang_has_project = DB::table("tbl_project_has_gudang")
                    ->where('id_gudang', '=', $gudang[0]->id_gudang)
                    ->where('id_project', '=', $project[0]->id_project)
                    ->get();

                $generated_no_inbound = $this->generateNomorInbound($kodeproject, $kodegudang);

                $inbound = DB::table('tbl_inbound')->insert([
                    "no_inbound" => $generated_no_inbound,
                    "tanggal_inbound" => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_inbound))),
                    'origin' => $request->asal_inbound,
                    'referensi' => $request->referensi,
                    'custom_field' => $request->inbound_header_value,
                    'id_project_has_gudang' => $idgudang_has_project[0]->id_project_has_gudang,
                    'id_status' => 1
                ]);

                $last_inserted_id_inbound = DB::table('tbl_inbound as i')
                    ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
                    ->orderBy('id_inbound', 'desc')
                    ->get();

                DB::table('tbl_history_inbound')
                    ->insert([
                        'id_inbound' => $last_inserted_id_inbound[0]->id_inbound,
                        'tanggal_update' => date('Y-m-d H:i:s'),
                        'status' => $last_inserted_id_inbound[0]->nama_status,
                        'nama_user' => Auth::user()->name
                    ]);

                $new_request = new Request([
                    'action' => "tambah_inbound",
                    'no_inbound' => $last_inserted_id_inbound[0]->no_inbound,

                ]);
                app(LogController::class)->log($new_request);

                return json_encode($last_inserted_id_inbound[0]->no_inbound);
            } else if ($request->action == "update_inbound_header") {
                $inbound = DB::table('tbl_inbound')
                    ->where('no_inbound', $request->noinbound)
                    ->update([
                        "tanggal_inbound" => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_inbound))),
                        'origin' => $request->asal_inbound,
                        'custom_field' => $request->inbound_header_value,
                    ]);

                return json_encode($request->noinbound);
            } else {
                $inbound = DB::table('tbl_inbound')
                    ->where('no_inbound', $request->no_inbound)
                    ->get();

                $list_item = json_decode($request->list_item);

                $count_item = count($list_item);
                $i = 0;
                $label = "";
                $j = 0;
                for ($i; $i < $count_item; $i++) {
                    // $j++;
                    // if (trim($list_item[$i]->label, " ") == "") {
                    //     $label = $inbound[0]->no_inbound . sprintf("%'.04d", ($j));
                    // } else {

                    // }
                    $label = $list_item[$i]->label;
                    $insert = DB::table('tbl_inbound_detail')->insert([
                        'qty' => $list_item[$i]->qty,
                        'qty_aktual' => 0,
                        'custom_field_td' => $list_item[$i]->custom_td,
                        // 'batch' => $list_item[$i]->batch,
                        'label' => $label,
                        'id_item' => $list_item[$i]->id_item,
                        'id_inbound' => $inbound[0]->id_inbound,
                        'id_status' => 2,
                        'index' => $list_item[$i]->index
                    ]);

                    $last_inserted_id_inbound_detail = DB::table('tbl_inbound as i')
                        ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                        ->join('tbl_item as itm', 'itm.id_item', '=', 'id.id_item')
                        ->where('i.id_inbound', $inbound[0]->id_inbound)
                        ->orderBy('id.id_inbound_detail', 'desc')
                        ->limit(1)
                        ->get();

                    $insert = DB::table('tbl_inventory')->insert([
                        'id_inbound_detail' => $last_inserted_id_inbound_detail[0]->id_inbound_detail,
                    ]);

                    $new_request = new Request([
                        'action' => "tambah_item_inbound", 'qty' => $last_inserted_id_inbound_detail[0]->qty, 'nama_item' => $last_inserted_id_inbound_detail[0]->nama_item, 'nama_uom' => $last_inserted_id_inbound_detail[0]->nama_uom, 'no_inbound' => $last_inserted_id_inbound_detail[0]->no_inbound
                    ]);
                    app(LogController::class)->log($new_request);
                }
                DB::table('tbl_inbound')
                    ->where('no_inbound', $request->no_inbound)
                    ->update([
                        'id_status' => 7
                    ]);
            }

            $inbound = DB::table('tbl_inbound as i')
                ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
                ->where('i.no_inbound', $request->no_inbound)
                ->get();

            DB::table('tbl_history_inbound')
                ->insert([
                    'id_inbound' => $inbound[0]->id_inbound,
                    'tanggal_update' => date('Y-m-d H:i:s'),
                    'status' => $inbound[0]->nama_status,
                    'nama_user' => Auth::user()->name
                ]);

            return json_encode("success");
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
    public function show($kodeproject, $kodegudang,  $noinbound)
    {
        try {
            $inbound = DB::table('tbl_inbound')
                ->where('no_inbound', '=', $noinbound)
                ->get();
            return json_encode($inbound);
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
    public function edit($kodeproject, $kodegudang,  $noinbound)
    {
        try {
            $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
            $query = DB::table('tbl_inbound as i')
                ->join('tbl_inbound_detail as id', "i.id_inbound", '=', "id.id_inbound")
                ->join('tbl_item as item', "item.id_item", "=", "id.id_item")
                ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                ->where('i.no_inbound', '=', $noinbound)
                ->get();

            if (count($query) == 0) {
                $query = DB::table('tbl_inbound as i')
                    ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                    ->where('i.no_inbound', $noinbound)
                    ->get();
            }

            $list_item = app(ItemsController::class)->getAllItem($kodeproject, $kodegudang)->getData();
            $template = $this->loadTemplateInbound($kodeproject)->getData();
            return view("edit_inbound", compact(['query', 'kodegudang', 'kodeproject', 'noinbound', 'template', 'list_item', 'projectGudang']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang, $noinbound)
    {
        try {
            $gudang = Gudang::where('kode_gudang', $kodegudang)->get();
            $project = Project::where('kode_project', $kodeproject)->get();
            $idgudang_has_project = DB::table("tbl_project_has_gudang")
                ->where('id_gudang', '=', $gudang[0]->id_gudang)
                ->where('id_project', '=', $project[0]->id_project)
                ->get();

            $inbound = DB::table('tbl_inbound')
                ->where('no_inbound', $noinbound)
                ->update([
                    "tanggal_inbound" => date('Y-m-d', strtotime(preg_replace('/[^a-zA-Z0-9-]/', '-', $request->tanggal_inbound))),
                    'origin' => $request->asal_inbound,
                    'referensi' => $request->referensi,
                    'custom_field' => $request->inbound_header_value,
                    'id_project_has_gudang' => $idgudang_has_project[0]->id_project_has_gudang,
                ]);
            $new_request = new Request([
                'action' => "edit_inbound", 'no_inbound' => $noinbound
            ]);
            app(LogController::class)->log($new_request);

            $id_inbound = DB::table('tbl_inbound')
                ->where('no_inbound', $noinbound)
                ->get();

            $list_item_inbound = DB::table('tbl_inbound as i')
                ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                ->where('i.no_inbound', $noinbound)
                ->get();

            $j = 0;
            $list_item = count($list_item_inbound);
            if ($list_item != 0) {
                if ($list_item_inbound[0]->nama_status == "Ready") {
                    // return json_encode("status ready");
                    // for ($j; $j < $list_item; $j++) {
                    //     // return json_encode($list_item_inbound[$j]->id_inbound_detail);
                    //     // DB::table('tbl_inventory')->where('id_inbound_detail', $list_item_inbound[$j]->id_inbound_detail)->delete();
                    //     // DB::table('tbl_inbound_detail')->where('id_inbound_detail', $list_item_inbound[$j]->id_inbound_detail)->delete();
                    // }
                    foreach ($list_item_inbound as $item) {
                        DB::table('tbl_inventory')->where('id_inbound_detail', $item->id_inbound_detail)->delete();
                        DB::table('tbl_inbound_detail')->where('id_inbound_detail', $item->id_inbound_detail)->delete();
                    }
                }
            }

            $list_item = json_decode($request->list_item);
            $count_item = count($list_item);
            $i = 0;
            for ($i; $i < $count_item; $i++) {
                if (trim($list_item[$i]->label, " ") == "") {
                    $label = $id_inbound[0]->no_inbound . sprintf("%'.04d", ($i));
                } else {
                    $label = $list_item[$i]->label;
                }

                $insert = DB::table('tbl_inbound_detail')->insert([
                    'qty' => $list_item[$i]->qty,
                    'qty_aktual' => 0,
                    'custom_field_td' => $list_item[$i]->custom_td,
                    // 'batch' => $list_item[$i]->batch,
                    'label' => $label,
                    'id_item' => $list_item[$i]->id_item,
                    'id_inbound' => $id_inbound[0]->id_inbound,
                    'id_status' => 2,
                    'index' =>  $list_item[$i]->index
                ]);

                $last_inserted_id_inbound_detail = DB::table('tbl_inbound_detail as id')
                    ->join('tbl_inbound as inb', 'inb.id_inbound', '=', 'id.id_inbound')
                    ->join('tbl_item as i', 'i.id_item', '=', 'id.id_item')
                    ->orderBy('id_inbound_detail', 'desc')
                    ->where('id.id_inbound', $id_inbound[0]->id_inbound)
                    ->limit(1)
                    ->get();
                // return json_encode($last_inserted_id_inbound_detail);
                $insert = DB::table('tbl_inventory')->insert([
                    'id_inbound_detail' => $last_inserted_id_inbound_detail[0]->id_inbound_detail,
                ]);

                $new_request = new Request([
                    'action' => "tambah_item_inbound",
                    'qty' => $last_inserted_id_inbound_detail[0]->qty,
                    'nama_item' => $last_inserted_id_inbound_detail[0]->nama_item,
                    'nama_uom' => $last_inserted_id_inbound_detail[0]->nama_uom,
                    'no_inbound' => $last_inserted_id_inbound_detail[0]->no_inbound,
                    'kode_item' => $last_inserted_id_inbound_detail[0]->kode_item,

                ]);
                app(LogController::class)->log($new_request);
            }

            if ($list_item != 0) {

                $list_item_inbound = DB::table('tbl_inbound as i')
                    ->join('tbl_inbound_detail as id', 'id.id_inbound', '=', 'i.id_inbound')
                    ->join('tbl_status_inbound as si', 'si.id_status', '=', 'i.id_status')
                    ->where('i.no_inbound', $noinbound)
                    ->get();
                if ($list_item_inbound[0]->nama_status != "Incomplete") {
                    if (count($list_item_inbound) == 0) {
                        DB::table('tbl_inbound')
                            ->where('no_inbound', $noinbound)
                            ->update([
                                'id_status' => 1
                            ]);
                    } else {
                        DB::table('tbl_inbound')
                            ->where('no_inbound', $noinbound)
                            ->update([
                                'id_status' => 7
                            ]);
                    }

                    $inbound = DB::table('tbl_inbound as i')
                        ->join('tbl_status_inbound as si', 'i.id_status', '=', 'si.id_status')
                        ->where('i.no_inbound', $noinbound)
                        ->get();

                    $history_inbound = DB::table('tbl_history_inbound')
                        ->where('status', $inbound[0]->nama_status)
                        ->where('id_inbound', $inbound[0]->id_inbound)
                        ->get();

                    if (count($history_inbound) == 0) {
                        DB::table('tbl_history_inbound')
                            ->insert([
                                'id_inbound' => $inbound[0]->id_inbound,
                                'tanggal_update' => date('Y-m-d H:i:s'),
                                'status' => $inbound[0]->nama_status,
                                'nama_user' => Auth::user()->name
                            ]);
                    } else {
                        DB::table('tbl_history_inbound')
                            ->where('id_inbound', $inbound[0]->id_inbound)
                            ->where('status', $inbound[0]->nama_status)
                            ->update([
                                'tanggal_update' => date('Y-m-d H:i:s'),
                                'nama_user' => Auth::user()->name
                            ]);
                    }
                }
            }

            return json_encode("success");
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kodeproject, $kodegudang, $noinbound)
    {
        try {
            $inbound_has_incoming = DB::table('tbl_inbound as inb')
                ->join('tbl_incoming_inbound as inc', 'inb.id_inbound', '=', 'inc.id_inbound')
                ->where('inb.no_inbound', $noinbound)
                ->get();

            if (count($inbound_has_incoming) > 0) {
                return json_encode("Inbound gagal dihapus karena inbound masih memiliki incoming inbound!");
            } else {
                $inbound = DB::table('tbl_inbound')->where('no_inbound', $noinbound)->get();
                if ($inbound[0]->id_status == 1) {
                    $new_request = new Request([
                        'action' => "hapus_inbound",
                        'no_inbound' => $inbound[0]->no_inbound
                    ]);
                    app(LogController::class)->log($new_request);

                    DB::table('tbl_inventory as inv')
                        ->join('tbl_inbound_detail as id', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                        ->where('id.id_inbound', $inbound[0]->id_inbound)
                        ->delete();

                    DB::table('tbl_history_inbound')
                        ->where('id_inbound', $inbound[0]->id_inbound)
                        ->delete();

                    DB::table('tbl_inbound_detail')
                        ->where('id_inbound', $inbound[0]->id_inbound)
                        ->delete();

                    DB::table('tbl_inbound')
                        ->where('no_inbound', $noinbound)
                        ->where('id_status', 1)
                        ->delete();
                    return json_encode("success");
                }
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
