<?php

namespace App\Http\Controllers;

use App\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\Project;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Exception;
use stdClass;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Spatie\WebhookServer\WebhookCall;

class ItemsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function importExcelItem(Request $request, $kodeproject, $kodegudang)
    {
        $index = 1;

        $list_item_import_excel = array();
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $reader->setReadDataOnly(TRUE);
        $reader->setReadEmptyCells(false);
        $sheet = $reader->load($_FILES['excel_inbound']['tmp_name']);
        $active_sheet = $sheet->getActiveSheet();
        $highest_row = $active_sheet->getHighestRow();
        $highest_column = $active_sheet->getHighestColumn();

        $list_item = array();
        for ($i = 2; $i <= $highest_row; $i++) {
            $item = new stdClass();
            //looping untuk mengambil value inbound header
            for ($column = "A"; $column <= $highest_column; $column++) {
                $property_name = str_replace(" ", '', $active_sheet->getCell($column . 1)->getValue());
                $item->$property_name = $active_sheet->getCell($column . $i)->getValue();
                // array_push($item, [$active_sheet->getCell($column . 1)->getValue() => $active_sheet->getCell($column . $i)->getValue()]);
                array_push($list_item, $active_sheet->getCell('A' . $i)->getValue());
            }
            array_push($list_item_import_excel, $item);
        }

        /* Cek apakah item yang ada didalam excel terdaftar di DB */
        $projet_has_gudang = DB::table('tbl_project_has_gudang as pg')
            ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
            ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
            ->where('g.kode_gudang', $kodegudang)
            ->where('p.kode_project', $kodeproject)
            ->get();


        $list_item_exist = DB::table('tbl_item')
            ->where('id_project_has_gudang', $projet_has_gudang[0]->id_project_has_gudang)
            ->whereIn('kode_item', $list_item)
            ->get();
        // dd($list_item_exist);
        /* menghapus item yagn sudah terdaftar di db */
        $temp_list_item_imported = $list_item_import_excel;
        if (count($list_item_exist) != 0) {
            foreach ($list_item_exist as $item) {
                $index_item = 0;
                foreach ($list_item_import_excel as $item_imported) {
                    if ($item->kode_item == $item_imported->MaterialCode) {
                        // array_splice($list_item_in_inbound, $index_item_inbound_detail, 1);
                        unset($temp_list_item_imported[$index_item]);
                    }
                    $index_item++;
                }
            }
        }
        // dd($temp_list_item_imported);
        // dd($list_item_in_inbound);
        /* Memasukkan item baru ke database */
        foreach ($temp_list_item_imported as $item_baru) {
            DB::table('tbl_item')
                ->insert([
                    'kode_item' => $item_baru->MaterialCode,
                    'label_barcode' => $item_baru->MaterialCode,
                    'nama_item' => $item_baru->Description,
                    'minimal_qty' => 1,
                    'cara_hitung_cbm' => 'manual',
                    'panjang' => $item_baru->P,
                    'lebar' => $item_baru->L,
                    'tinggi' => $item_baru->T,
                    'berat_bersih' => $item_baru->NETWeight,
                    'berat_kotor' => $item_baru->GROSSWeight,
                    'tonase' => 0,
                    'nama_uom' => $item_baru->Uom,
                    'id_project_has_gudang' => $projet_has_gudang[0]->id_project_has_gudang,
                    'cbm' => $item_baru->TotalCBM
                ]);
        }

        return redirect()->back();
    }

    public function exportExcelItem($kodeproject, $kodegudang)
    {
        $filename = date('d-m-Y_H:i:s') . '_' . 'template_import_item' . ".xls";
        $format_inbound_header = "
            <table>
                <tr>
                    <th>Material Code</th>
                    <th>Label Barcode</th>
                    <th>Description</th>
                    <th>Uom</th>
                    <th>NET Weight</th>
                    <th>GROSS Weight</th>
                    <th>P</th>
                    <th>L</th>
                    <th>T</th>
                    <th>Total CBM</th>
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

    public function printBarcode($kodeproject, $kodegudang, $kodeitem)
    {
        $item = DB::table('tbl_item')
            ->where('kode_item', $kodeitem)
            ->get();

        return view('print_barcode', compact('item'));
    }

    public function generateKodeItem($kodeproject, $kodegudang)
    {
        try {
            $kode_project = substr($kodeproject, 0, 4);
            $project = DB::table('tbl_project')
                ->where('kode_project', $kodeproject)
                ->get();
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();

            $project_has_gudang = DB::table('tbl_project_has_gudang')
                ->where('id_project', $project[0]->id_project)
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->get();
            $item = DB::table('tbl_item')
                ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->orderBy('kode_item', 'desc')
                ->limit(1)
                ->get();

            $project_has_gudang = DB::table('tbl_project_has_gudang as pg')
                ->join('tbl_project as p', 'pg.id_project', '=', 'p.id_project')
                ->join('tbl_gudang as g', 'pg.id_gudang', '=', 'g.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            if (count($item) != 0) {
                $lastKodeItem = substr($item[0]->kode_item, -4);
                $lastKodeItem = (int) $lastKodeItem + 1;
                $lastKodeItem = str_pad($lastKodeItem, (5 - strlen($lastKodeItem)), "0", STR_PAD_LEFT);
                $new_kode_item = $kode_project . $project_has_gudang[0]->id_project_has_gudang . $lastKodeItem;
                return response()->json($new_kode_item);
            }

            return response()->json($kode_project . $project_has_gudang[0]->id_project_has_gudang  . '0001');
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function getInfoItem($kodeproject, $kodegudang, $kodeitem)
    {
        try {
            $item = DB::table('tbl_item')
                ->where('id_item', '=', $kodeitem)
                ->get();
            return json_encode($item);
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    public function getAllItem($kodeproject, $kodegudang)
    {
        try {
            $list_item = DB::table('tbl_item as i')
                ->Join('tbl_project_has_gudang as gp', 'i.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'gp.id_project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            return response()->json($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllItemBaseLabel($kodeproject, $kodegudang)
    {
        try {
            // echo $kodegudang;
            $list_item = DB::table('tbl_item as i')
                ->join('tbl_inbound_detail as id', 'id.id_item', '=', 'i.id_item')
                ->Join('tbl_project_has_gudang as gp', 'i.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
                ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                ->join('tbl_inventory_detail as invd', 'invd.id_inventory', '=', 'inv.id_inventory')
                ->join('tbl_project as p', 'p.id_project', '=', 'gp.id_project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->where('invd.available', '<>', '0')
                ->groupBy('id.label')
                ->get();
            // dd($list_item);
            return  response()->json($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getItemBaseLocator(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = Gudang::where("kode_gudang", $kodegudang)->get();
            $project = Project::where("kode_project", $kodeproject)->get();

            $get_id_project_has_gudang = DB::table('tbl_project_has_gudang')
                ->select('id_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();
            $list_item = "";
            if ($request->filter == "label") {
                // $list_item = DB::table('tbl_item as i')
                //     ->select('i.id_item as id_item', 'i.nama_item as nama_item', 'i.kode_item as kode_item', 'od.label')
                //     ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'i.id_project_has_gudang')
                //     ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                //     ->join('tbl_locator as l', 'g.id_gudang', '=', 'l.id_gudang')
                //     ->join('tbl_inventory_detail as invd', 'l.id_locator', '=', 'invd.id_locator')
                //     ->join('tbl_inbound_detail as od', 'od.id_item', '=', 'i.id_item')
                //     // ->where('gp.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                //     ->where('l.id_locator', $request->id_locator)
                //     ->distinct()
                //     ->get();
                $list_item = DB::table('tbl_inventory_detail as id')
                    ->select('itm.id_item as id_item', 'itm.kode_item as kode_item', 'inbd.label as label')
                    ->join('tbl_inventory as i', 'i.id_inventory', '=', 'id.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'i.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                    ->join('tbl_item as itm', 'itm.id_item', '=', 'inbd.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'itm.id_project_has_gudang')
                    ->where('id.id_locator', $request->id_locator)
                    ->where('gp.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                    ->groupBy('id.id_inventory_detail')
                    ->get();
            } else {
                // $list_item = DB::table('tbl_item as i')
                //     ->select('i.id_item as id_item', 'i.nama_item as nama_item', 'i.kode_item as kode_item')
                //     ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'i.id_project_has_gudang')
                //     ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                //     ->join('tbl_locator as l', 'g.id_gudang', '=', 'l.id_gudang')
                //     ->join('tbl_inventory_detail as invd', 'l.id_locator', '=', 'invd.id_locator')
                //     // ->where('gp.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                //     ->where('l.id_locator', $request->id_locator)
                //     ->distinct()
                //     ->get();
                $list_item = DB::table('tbl_inventory_detail as id')
                    ->select('itm.id_item as id_item', 'itm.nama_item as nama_item', 'itm.kode_item as kode_item')
                    ->join('tbl_inventory as i', 'i.id_inventory', '=', 'id.id_inventory')
                    ->join('tbl_inbound_detail as inbd', 'i.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                    ->join('tbl_item as itm', 'itm.id_item', '=', 'inbd.id_item')
                    ->join('tbl_project_has_gudang as gp', 'gp.id_project_has_gudang', '=', 'itm.id_project_has_gudang')
                    ->where('id.id_locator', $request->id_locator)
                    ->where('gp.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                    ->distinct()
                    ->get();
            }

            return json_encode($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllItemBaseLocator(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $gudang = Gudang::where("kode_gudang", $kodegudang)->get();
            $project = Project::where("kode_project", $kodeproject)->get();

            $get_id_project_has_gudang = DB::table('tbl_project_has_gudang')
                ->select('id_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();
            $list_item = "";
            if ($request->filter == "label") {
                $list_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as inbd', 'i.id_item', '=', 'inbd.id_item')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_locator as l', 'invd.id_locator', '=', 'l.id_locator')
                    ->where('l.id_locator', $request->idlocator)
                    ->where('inbd.label', $request->iditem)
                    ->where('i.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                    ->groupBy('invd.id_inventory_detail')
                    ->get(['inbd.label', 'invd.id_inventory_detail', 'invd.id_inventory', 'i.id_item', 'i.kode_item', 'i.nama_item', 'l.nama_locator', 'invd.available']);
            } else {
                $list_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as inbd', 'i.id_item', '=', 'inbd.id_item')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'inbd.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_locator as l', 'invd.id_locator', '=', 'l.id_locator')
                    ->where('l.id_locator', $request->idlocator)
                    ->where('i.kode_item', $request->iditem)
                    ->where('i.id_project_has_gudang', $get_id_project_has_gudang[0]->id_project_has_gudang)
                    ->groupBy('inv.id_inventory', 'invd.id_locator', 'i.kode_item')
                    ->get(['inbd.label', 'invd.id_inventory_detail', 'invd.id_inventory', 'i.id_item', 'i.kode_item', 'i.nama_item', 'l.nama_locator', 'invd.available']);
            }

            return json_encode($list_item);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllItemFromInventory($kodeproject, $kodegudang)
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

            $list_item_master = DB::table('tbl_item as i')
                ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                ->where('i.id_project_has_gudang', $gudang_has_project[0]->id_project_has_gudang)
                ->groupBy('i.kode_item')
                ->get();
            return response()->json($list_item_master);
        } catch (\Throwable $th) {
            return response()->json($list_item_master);
        }
    }

    public function getAllItemFromInboundDetail(Request $request, $kodeproject, $kodegudang)
    {
        try {
            $list_item = "";
            $project = DB::table('tbl_project')->where('kode_project', $kodeproject)->get();
            $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();
            $id_project_has_gudang = DB::table('tbl_project_has_gudang')
                ->where('id_project', $project[0]->id_project)
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->get();
            if ($request->filter == "label") {
                $list_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                    ->where('id.label', $request->filter_value)
                    ->where('i.id_project_has_gudang', $id_project_has_gudang[0]->id_project_has_gudang)
                    ->where('invd.available', '!=', 0)
                    ->get();
            } else {
                $list_item = DB::table('tbl_item as i')
                    ->join('tbl_inbound_detail as id', 'i.id_item', '=', 'id.id_item')
                    ->join('tbl_inventory as inv', 'inv.id_inbound_detail', '=', 'id.id_inbound_detail')
                    ->join('tbl_inventory_detail as invd', 'inv.id_inventory', '=', 'invd.id_inventory')
                    ->join('tbl_locator as l', 'l.id_locator', '=', 'invd.id_locator')
                    ->where('i.id_project_has_gudang', $id_project_has_gudang[0]->id_project_has_gudang)
                    ->where('i.kode_item', $request->filter_value)
                    ->where('invd.available', '<>', 0)
                    ->get();
            }


            //echo $list_item;
            return response()->json($list_item);
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
        $items = DB::table('tbl_item as i')
            ->Join('tbl_project_has_gudang as gp', 'i.id_project_has_gudang', '=', 'gp.id_project_has_gudang')
            ->join('tbl_project as p', 'p.id_project', '=', 'gp.id_project')
            ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
            ->where('p.kode_project', $kodeproject)
            ->where('g.kode_gudang', $kodegudang)
            ->get();
        return view('daftar_item', compact(['items', 'kodegudang', 'kodeproject', 'projectGudang']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        return view('tambah_item', compact(['kodegudang', 'kodeproject', 'projectGudang']));
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
            $gudang = Gudang::where("kode_gudang", $kodegudang)->get();
            $project = Project::where("kode_project", $kodeproject)->get();

            $get_id_project_has_gudang = DB::table('tbl_project_has_gudang')
                ->select('id_project_has_gudang')
                ->where('id_gudang', $gudang[0]->id_gudang)
                ->where('id_project', $project[0]->id_project)
                ->get();

            $item_exist = DB::table('tbl_item')
                ->where('id_project_has_gudang', '=', $get_id_project_has_gudang[0]->id_project_has_gudang)
                ->where('kode_item', $request->kode_item)
                ->get();

            if (count($item_exist) == 0) {
                $item = new Item();
                $item->kode_item = $request->kode_item;
                $item->label_barcode = $request->label_barcode;
                $item->nama_item = $request->nama_item;
                $item->minimal_qty = 1;
                $item->cara_hitung_cbm = $request->hitung_cbm;
                $item->panjang = $request->panjang;
                $item->lebar = $request->lebar;
                $item->tinggi = $request->tinggi;
                $item->cbm = $request->cbm;
                $item->tonase = $request->tonase;
                $item->berat_bersih = $request->berat_bersih;
                $item->berat_kotor = $request->berat_kotor;
                $item->nama_uom = $request->uom;
                $item->id_project_has_gudang = $get_id_project_has_gudang[0]->id_project_has_gudang;
                $item->save();
            } else {
                return response()->json('Kode item sudah digunakan');
            }

            $new_request = new Request([
                'action' => 'tambah_item',
                'kode_item' => $request->kode_item,
                'nama_item' => $request->nama_item
            ]);
            app(LogController::class)->log($new_request);


            // WebhookCall::create()
            //     ->url('http://127.0.0.1:8090/webhook-receiving-url')
            //     ->payload(['response' => "success"])
            //     ->useSecret('makannasigoreng')
            //     ->dispatch();

            return response()->json("success");
        } catch (Exception $ex) {
            return response()->json($ex->getMessage());
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
    public function edit($kodeproject, $kodegudang, $kodeitem)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $project_has_gudang = DB::table('tbl_project_has_gudang as pg')
            ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
            ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
            ->where('p.kode_project', $kodeproject)
            ->where('g.kode_gudang', $kodegudang)
            ->get();

        $item = DB::table('tbl_item as i')
            ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
            ->where('kode_Item', $kodeitem)
            ->get();

        return view('edit_item', compact(['item', 'kodegudang', 'kodeproject', 'kodeitem', 'projectGudang']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodeproject, $kodegudang, $kodeitem)
    {
        try {
            $project_has_gudang = DB::table('tbl_project_has_gudang as pg')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();
            $item = DB::table('tbl_item')->where('kode_item', $kodeitem)->get();
            $update = DB::table('tbl_item')
                ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->where('kode_item', $kodeitem)
                ->update([
                    'nama_item' => $request->nama_item,
                    'cara_hitung_cbm' => $request->hitung_cbm,
                    'panjang' => $request->panjang,
                    'lebar' => $request->lebar,
                    'tinggi' => $request->tinggi,
                    'cbm' => $request->cbm,
                    'berat_bersih' => $request->berat_bersih,
                    'berat_kotor' => $request->berat_kotor,
                    'tonase' => $request->tonase,
                    'nama_uom' => $request->uom,
                    'tonase' => $request->tonase
                ]);

            $new_request = new Request([
                'action' => 'edit_item', 'kode_item' => $item[0]->kode_item, 'nama_item' => $item[0]->nama_item
            ]);
            app(LogController::class)->log($new_request);

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
    public function destroy($kodeproject, $kodegudang, $kodeitem)
    {
        try {
            $project_has_gudang = DB::table('tbl_gudang as g')
                ->join('tbl_project_has_gudang as pg', 'pg.id_gudang', '=', 'g.id_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                ->where('p.kode_project', $kodeproject)
                ->where('g.kode_gudang', $kodegudang)
                ->get();

            $inbound_use_item = DB::table('tbl_inbound_detail as id')
                ->join('tbl_item as i', 'id.id_item', '=', 'i.id_item')
                ->where('i.kode_item', $kodeitem)
                ->where('i.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->get();

            $outbound_use_item = DB::table('tbl_outbound_detail as od')
                ->join('tbl_item as i', 'od.id_item', '=', 'i.id_item')
                ->where('i.id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                ->where('i.kode_item', $kodeitem)
                ->get();

            if (count($inbound_use_item) == 0 && count($outbound_use_item) == 0) {
                $item = DB::table('tbl_item')->where('kode_item', $kodeitem)->get();
                DB::table('tbl_item')
                    ->where('kode_item', $kodeitem)
                    ->where('id_project_has_gudang', $project_has_gudang[0]->id_project_has_gudang)
                    ->delete();

                $new_request = new Request([
                    'action' => 'hapus_item', 'kode_item' => $item[0]->kode_item, 'nama_item' => $item[0]->nama_item
                ]);
                app(LogController::class)->log($new_request);

                return json_encode('success');
            } else {
                return json_encode("Item gagal dihapus karena item sedang diguanakan");
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
