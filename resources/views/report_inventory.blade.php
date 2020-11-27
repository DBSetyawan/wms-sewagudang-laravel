@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Report Inventory
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject' => $kodeproject])}}">Gudang
        ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item active">Report Inventory
</li>
@endsection

@section('content')

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Filter</div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="validationTooltip01">Base On</label>
                                        <select name="" class="form-control base_on">
                                            <option value="Item"> Item </option>
                                            <option value="Label"> Label </option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Awal</label>
                                        <input type="text" class="form-control tanggal_awal" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="validationTooltip01">Tanggal Akhir</label>
                                        <input type="text" class="form-control tanggal_akhir" value="">
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-outline-primary" onclick="getReport()">Tampilkan
                                            Report</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card content_report">
                <div class="card-header">
                    <div class="col-6">
                        Report Inventory - Gudang : <b>{{$kodegudang}}</b>, Project : <b>{{$kodeproject}}</b>
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table tbl_report" id="tbl_report" width="100%">
                                <thead class="header_report_inventory">
                                    <tr class="th_header">
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Label</th>
                                        <th>Locator</th>
                                        <th>Available</th>
                                        <th>Allocated</th>
                                        <th>Picked</th>
                                        <th>On Hand</th>
                                        <th>UOM</th>
                                        <th>Total CBM</th>
                                        <th>Total Weight</th>
                                        {{-- <th>Batch</th> --}}
                                        <th>Aging</th>
                                        {!! $th_detail_inbound !!}
                                        <th>Tanggal Inbound</th>
                                    </tr>
                                </thead>
                                <tbody class="content_report_inventory">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="hidden_th_container" style="display:none">
</div>
@endsection

@section('script_document_ready')
<script>
    let dt = "";
    let hidden_th = [];
    function exportToxlsx() {
        let current_date = "{!! date('d-m-Y') !!}";
        let namaFile = "Inventory - " + "{!! $kodegudang !!}"+ " - " + "{!! $kodeproject !!}" + " - "  + current_date
        excel = new ExcelGen({
            "src_id" : "tbl_report",
            "show_header": true,
        });
        excel.generate(namaFile);
    }

    function changeHeaderReport()
    {
        let template_json = {!! json_encode($th_detail_inbound) !!};
        let base_on = $(".base_on").val();
        $(".header_report_inventory").html("");
        if(base_on == "Label")
        {
            $(".header_report_inventory").html(`
                                    <tr class="th_header">
                                        <th>No. Inbound</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Label</th>
                                        <th>Locator</th>
                                        <th>Available</th>
                                        <th>Allocated</th>
                                        <th>Picked</th>
                                        <th>On Hand</th>
                                        <th>UOM</th>
                                        <th>Total CBM</th>
                                        <th>Total Weight</th>
                                        <th>Aging</th>
                                        ${template_json}
                                        <th>Tanggal Inbound</th>
                                    </tr>`)
        }
        else
        {
            $(".header_report_inventory").html(` <tr class="header">
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Nama Locator</th>
                                        <th>Available</th>
                                        <th>Allocated</th>
                                        <th>Picked</th>
                                        <th>On Hand</th>
                                        <th>UOM</th>
                                        <th>Total CBM</th>
                                        <th>Total Weight</th>
                                        <th>Aging</th>
                                        ${template_json}
                                        <th>Tanggal Inbound</th>
                                    </tr>`)
        }
        
        hideHiddenLabel();
        // let hidden_th = document.querySelectorAll(".hidden_label");
        // let classname = "";

        // hidden_th.forEach((element) => {
        //     classname = element.className.split(" ");

        //     $(`.${classname[1]}`).attr("style", "display:none");
        //     // $(`.${classname[1]}`).remove();
        // });
    }

    function getReport() { 
        dt.destroy();
        changeHeaderReport();
        $(".content_report_inventory").html("");
        // let tanggal_awal = $(".tanggal_awal").val();
        // let tanggal_akhir = $(".tanggal_akhir").val();
        let filter = $(".base_on").val();
        $.ajax({
            type: "get",
            url: "{!! route('inventory.displayreport', ['kodeproject' => $kodeproject, 'kodegudang'=> $kodegudang]) !!}",
            data: {
                // tanggal_awal : tanggal_awal,
                // tanggal_akhir : tanggal_akhir,
                filter : filter
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let result = response;
                let string = "";
                // dt.clear().draw();
                result.forEach(item => {
                    let tanggal_inbound = moment(item['tanggal_inbound']);
                    let tanggal_now = moment();
                    if(filter == "Label")
                    {
                        $(".content_report_inventory").append(`<tr>
                                    <td>${item['no_inbound']}</td>
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['label']}</td>
                                    <td>${item['nama_locator']}</td>
                                    <td>${item['available']}</td>
                                    <td>${item['allocated']}</td>
                                    <td>${item['picked']}</td>
                                    <td>${parseInt(item['available']) + parseInt(item['allocated']) + parseInt(item['picked'])}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm'] * (parseInt(item['available']) + parseInt(item['allocated']) + parseInt(item['picked']))}</td>
                                    <td>${item['berat_kotor'] * (parseInt(item['available']) + parseInt(item['allocated']) + parseInt(item['picked']))}</td>
                                    <td>${tanggal_now.diff(tanggal_inbound, 'days')} Hari</td>
                                    ${item['custom_field_td']}
                                    <td>${item['tanggal_inbound']}</td>
                                </tr>`);
                    }
                    else
                    {
                        $(".content_report_inventory").append(`<tr>
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['nama_locator']}</td>
                                    <td>${item['available']}</td>
                                    <td>${item['allocated']}</td>
                                    <td>${item['picked']}</td>
                                    <td>${parseInt(item['available']) + parseInt(item['allocated']) + parseInt(item['picked'])}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm']}</td>
                                    <td>${item['berat_kotor'] * (parseInt(item['available']) + parseInt(item['allocated']) + parseInt(item['picked']))}</td>
                                    <td>${tanggal_now.diff(tanggal_inbound, 'days')} Hari</td>
                                    ${item['custom_field_td']}
                                    <td>${item['tanggal_inbound']}</td>
                                </tr>`);
                    }
                    
                    // dt.rows.add($(string)).draw();
                });
                hideHiddenLabel();
                
                dt = createDataListView('tbl_report');
            }
        });
     }

    function createDataListView(argNamaClass)
    {
        let target = [];
        let index = 0;
        let custom_label_inbound_detail = {!! json_encode($daftar_label_detail_inbound) !!};
        custom_label_inbound_detail.forEach(element => {
            index--;

            target.push(index);
        });

        let object = $("." + argNamaClass).DataTable({
            responsive: true,
            columnDefs: [{
                orderable: true,
                
               
            }],
            dom: '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sSearch: ""
            },
            aLengthMenu: [
                [10, 20, 30, -1],
                [10, 20, 30, "All"]
            ],
            select: {
                style: "multi"
            },
            order: [
                [1, "asc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [{
                extend : 'excel',
                text: "Download Report",
                className: "btn btn-outline-primary",
                exportOptions : {
                    modifier : {
                        page : 'all',      // 'all',     'current'
                    },
                    
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    // Loop over the cells
                    $('row c', sheet).each(function() {
                    //select the index of the row
                        var numero=$(this).parent().index() ;
                        var residuo = numero%2;
                        let header = 1;
                        if(numero ==2 )
                        {
                            $(this).attr('s','22');
                        }
                        else if (numero>2){
                            if(residuo !=0){
                                $(this).attr('s','5');
                            }
                            
                            
                        }
                    });
                },
                title : "Report Inventory - Project : {!! $project[0]->nama_project !!}, Gudang : {!! $gudang[0]->nama_gudang !!}",
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    $(document).ready(function(){
        
        hideHiddenLabel();

        dt = createDataListView('tbl_report');
    });
</script>
@endsection