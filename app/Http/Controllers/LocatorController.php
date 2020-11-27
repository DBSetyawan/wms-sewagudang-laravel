<?php

namespace App\Http\Controllers;

use App\Gudang;
use App\Locator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;


class LocatorController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    // public function importExcel(Request $request, $kodegudang)
    // {
    //     try {
    //         $list_locator_import_excel = array();
    //         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
    //         $reader->setReadDataOnly(TRUE);
    //         $reader->setReadEmptyCells(false);
    //         $sheet = $reader->load($_FILES['excel_inbound']['tmp_name']);
    //         $active_sheet = $sheet->getActiveSheet();
    //         $highest_row = $active_sheet->getHighestRow();
    //         $highest_column = $active_sheet->getHighestColumn();

    //         $list_item = array();
    //         for ($i = 2; $i <= $highest_row; $i++) {
    //             $item = new stdClass();
    //             //looping untuk mengambil value inbound header
    //             for ($column = "A"; $column <= $highest_column; $column++) {
    //                 $property_name = str_replace(" ", '', $active_sheet->getCell($column . 1)->getValue());
    //                 $item->$property_name = $active_sheet->getCell($column . $i)->getValue();
    //                 // array_push($item, [$active_sheet->getCell($column . 1)->getValue() => $active_sheet->getCell($column . $i)->getValue()]);
    //             }
    //             array_push($list_locator_import_excel, $item);
    //         }
    //         // dd($list_locator_import_excel);
    //         foreach ($list_locator_import_excel as $locator) {
    //             DB::table('tbl_locator')
    //                 ->insert([
    //                     'nama_locator' => $locator->nama_locator,
    //                     'level' => $locator->level,
    //                     'parent' => 1,
    //                     'status' => 1,
    //                     'id_type_locator' => 2,
    //                     'id_gudang' => 1
    //                 ]);

    //             $new_request = new Request(['action' => "tambah_locator", 'namalocator' => $locator->nama_locator, 'nama_gudang' => "Gudang Aloha"]);
    //             app(LogController::class)->log($new_request);
    //         }

    //         return redirect()->back();
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->withErrors($th->getMessage());
    //     }
    // }

    public function getAllLocator($kodegudang)
    {
        try {

            $gudang = Gudang::where('kode_gudang', $kodegudang)->get();

            $list_locator = DB::table('tbl_locator as l')
                ->join('tbl_gudang as g', 'l.id_gudang', '=', 'g.id_gudang')
                ->where('g.id_gudang', $gudang[0]->id_gudang)
                ->orderBy('id_locator', 'asc')
                ->get();

            return response()->json($list_locator);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($kodegudang)
    {
        try {
            $list_locator = DB::table('tbl_locator as l')
                ->select('l.id_locator as id_locator', 'tl.type_locator as type_locator', 'l.level as level', 'l.nama_locator as nama_locator', 'l.status as status', 'lp.nama_locator as nama_parent')
                ->leftJoin('tbl_locator as lp', 'lp.id_locator', '=', 'l.parent')
                ->leftJoin('tbl_type_locator as tl', 'tl.id_type_locator', '=', 'l.id_type_locator')
                ->leftJoin('tbl_gudang as g', 'g.id_gudang', '=', 'l.id_gudang')
                ->where('g.kode_gudang', $kodegudang)
                ->distinct()
                ->get(['l.id_locator']);


            // dd($list_locator_has_parent);
            return view('daftar_locator', compact('list_locator', 'kodegudang'));
        } catch (Exception $ex) {
            return json_encode($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($kodegudang)
    {
        $list_type_locator = DB::table('tbl_type_locator')
            ->get();

        $locator_parent = $this->getAllLocator($kodegudang)->getData();
        return view('tambah_locator', compact(['kodegudang', 'list_type_locator', 'locator_parent']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $kodegudang)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();
            $is_exist = DB::table('tbl_locator')->where('id_gudang', $gudang[0]->id_gudang)->where('nama_locator', $request->nama_locator)->get();
            if (count($is_exist) == 0) {
                $query = DB::table('tbl_locator')
                    ->insert(['nama_locator' => $request->nama_locator, 'level' => $request->locator_level, 'parent' => $request->locator_parent, 'status' => 1, 'id_type_locator' => $request->type_locator, 'id_gudang' => $gudang[0]->id_gudang]);

                $new_request = new Request([
                    'action' => "tambah_locator", 'namalocator' => $request->nama_locator, 'nama_gudang' => $gudang[0]->nama_gudang
                ]);
                app(LogController::class)->log($new_request);
            } else {
                return json_encode("gagal");
            }


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
    public function edit($kodegudang, $idlocator)
    {
        try {
            $locator = Locator::where('id_locator', $idlocator)->get();
            $locator_parent = $this->getAllLocator($kodegudang)->getData();
            $type_locator = json_decode(app(TypeLocatorController::class)->getAllTypeLocator());
            return view('edit_locator', compact('locator', 'kodegudang', 'type_locator', 'locator_parent'));
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodegudang, $idlocator)
    {
        try {
            $gudang = DB::table('tbl_gudang')
                ->where('kode_gudang', $kodegudang)
                ->get();

            $locator = DB::table('tbl_locator')->where('id_gudang', $gudang[0]->id_gudang)->where('nama_locator', $request->nama_locator)->get();
            if (count($locator) <= 1) {
                $locator = Locator::find($idlocator);
                $locator->nama_locator = $request->nama_locator;
                $locator->id_type_locator = $request->type_locator;
                $locator->level = $request->locator_level;
                $locator->parent = $request->locator_parent;
                $locator->status = $request->status_locator;
                $locator->save();

                $new_request = new Request([
                    'action' => "edit_locator", 'namalocator' => $locator->nama_locator
                ]);
                app(LogController::class)->log($new_request);
            } else {
                return json_encode("gagal");
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
    public function destroy($kodegudang, $idlocator)
    {
        try {
            $locator = DB::table('tbl_locator as l')
                ->join('tbl_inventory_detail as id', 'l.id_locator', '=', 'id.id_locator')
                ->where('l.id_locator', $idlocator)
                ->get();

            if (count($locator) == 0) {
                $locator = DB::table('tbl_locator as l')
                    ->where('l.id_locator', $idlocator)
                    ->get();
                $new_request = new Request([
                    'action' => "hapus_locator", 'namalocator' => $locator[0]->nama_locator
                ]);
                app(LogController::class)->log($new_request);

                $locator = DB::table('tbl_locator as l')
                    ->where('l.id_locator', $idlocator)
                    ->delete();

                return json_encode("success");
            } else {
                return json_encode("gagal");
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
