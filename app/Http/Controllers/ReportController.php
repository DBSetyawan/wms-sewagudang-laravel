<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function pageReportInbound($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $nama_file_template_inbound = file_get_contents("./template_inbound/inbound" . "_" . $kodeproject . ".json");
        $json = json_decode($nama_file_template_inbound, true);
        $daftar_label_detail_inbound = $json['daftar_label_custom_detail_inbound'];

        $tanggal_min = DB::table('tbl_inbound')
            ->min('tanggal_inbound');
        $tanggal_max = DB::table('tbl_inbound')
            ->max('tanggal_inbound');

        $project = DB::table('tbl_project')->where("kode_project", $kodeproject)->get();
        $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();

        return view('report_inbound', compact(['kodegudang', 'kodeproject', 'tanggal_min', 'tanggal_max', 'daftar_label_detail_inbound', 'json', 'project', 'gudang', 'projectGudang']));
    }

    public function pageReportOutbound($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $tanggal_min = DB::table('tbl_outbound')
            ->min('tanggal_outbound');
        $tanggal_max = DB::table('tbl_outbound')
            ->max('tanggal_outbound');

        $project = DB::table('tbl_project')->where("kode_project", $kodeproject)->get();
        $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();

        return view('report_outbound', compact(['kodegudang', 'kodeproject', 'tanggal_min', 'tanggal_max', 'project', 'gudang', 'projectGudang']));
    }

    public function pageReportInventory($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $nama_file_template_inbound = file_get_contents("./template_inbound/inbound" . "_" . $kodeproject . ".json");
        $json = json_decode($nama_file_template_inbound, true);
        $th_detail_inbound = $json['th_detail_inbound'];
        $daftar_label_detail_inbound = $json['daftar_label_custom_detail_inbound'];

        $project = DB::table('tbl_project')->where("kode_project", $kodeproject)->get();
        $gudang = DB::table('tbl_gudang')->where('kode_gudang', $kodegudang)->get();

        return view('report_inventory', compact(['kodeproject', 'kodegudang', 'th_detail_inbound', 'daftar_label_detail_inbound', 'project', 'gudang', 'projectGudang']));
    }

    public function pageReportBalanceInventory($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        $request = new Request();
        $request->replace(['filter' => 'item']);
        // $report = json_decode(app(InventoryController::class)->displayReportBalanceInventory($request, $kodeproject, $kodegudang));
        return view('report_balance_inventory', compact(['kodegudang', 'kodeproject', 'projectGudang']));
    }

    public function pageReportPutaway($kodeproject, $kodegudang)
    {
        $projectGudang = app(ProjectHasGudangController::class)->getInformationProjectGudang($kodeproject, $kodegudang)->getData();
        return view('report_putaway', compact(['kodegudang', 'kodeproject', 'projectGudang']));
    }
}
