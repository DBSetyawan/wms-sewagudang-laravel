@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Report Balance Inventory
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
<li class="breadcrumb-item active">Report Balance Inventory
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card content_report">
                <div class="card-header">
                    Filter
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6  mb-2">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="">Filter</label>
                                        <select name="" id="" class="select2 base_on" onchange="changeBaseOnReport()">
                                            <option value="item">Item</option>
                                            <option value="label">Label</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Awal</label>
                                        <input type="text" class="form-control tanggal_awal" value="">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Akhir</label>
                                        <input type="text" class="form-control tanggal_akhir" value="">
                                    </div>
                                </div>
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
</section>
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card content_report">
                <div class="card-header">
                    <div class="col-6">
                        Report Balance Inventory - Gudang : <b>{{$kodegudang}}</b>, Project : <b>{{$kodeproject}}</b>
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="col-md-6">
                            {{-- <button class="btn btn-outline-primary export" name="export"
                                onclick="exportToxls()">Export to
                                xls</button> --}}

                        </div>
                        <div class="table-responsive ">
                            <table class="table tbl_report" id="tbl_report" width="100%">
                                <thead class="header_tbl_report">
                                    <tr class="">
                                        {{-- <th>Kode Item</th> --}}
                                        <th>Item</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody class="content_incoming_detail">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_document_ready')
<script>
    let dt = "";
    function exportToxlsx() {   
        let current_date = "{!! date('d-m-Y') !!}";
        let namaFile = "Balance Inventory - " + "{!! $kodegudang !!}"+ " - " + "{!! $kodeproject !!}" + " - "  + current_date
        excel = new ExcelGen({
            "src_id" : "tbl_report",
            "show_header": true,
        });
        excel.generate(namaFile);
    }

    // function exportToxls()
    // {
    //     let current_date = "{!! date('d-m-Y') !!}";
    //     let namaFile = "{!! $kodegudang !!}" + " - " + "{!! $kodeproject !!}" + " - "  + current_date
    //     saveAsExcel('tbl_report', namaFile+'.xls');

    // }

    async function changeBaseOnReport() { 
        let base_on = $(".base_on").val();
        dt.destroy();
        changeHeaderTableReport();
        $(".content_incoming_detail").html("");
        // getReport(base_on);
        dt = createDataListView('tbl_report');
     }

    function changeHeaderTableReport(argFilter) { 
        $(".header_tbl_report").html("");
        // dt.clear().draw();
        if($(".base_on").val() == "item")
        {
            $(".header_tbl_report").append(`
                <tr class="">
                    <th>Nama Item</th>
                    <th>In</th>
                    <th>Out</th>
                    <th>Saldo</th>
                </tr>
            `);
        }
        else
        {
            // $(dt.column(0).header()).text('aoha');
            $(".header_tbl_report").append(`
                <tr class="">
                    <th>Label</th>
                    <th>In</th>
                    <th>Out</th>
                    <th>Saldo</th>
                </tr>
            `);
        }
        dt = createDataListView('tbl_report');
    }

    function getReport()
    {
        
        let tanggal_awal = $(".tanggal_awal").val();
        let tanggal_akhir = $(".tanggal_akhir").val();
        let filter = $(".base_on").val();
        $.ajax({
            type: "get",
            url: "{!! route('inventory.displayReportBalanceInventory', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            data :{
                'filter' : filter,
                tanggal_awal : tanggal_awal,
                tanggal_akhir : tanggal_akhir,
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let result = response
                dt.clear().draw();
                if(filter == "item")
                {
                    result.forEach(item => {
                        string = `<tr>
                                    <td>${item['kode_item']} - ${item['nama_item']}</td>
                                    <td>${item['in']}</td>
                                    <td>${item['out']}</td>
                                    <td>${item['in'] - item['out']}</td>
                                </tr>`
                        dt.rows.add($(string)).draw();
                    });
                }
                else
                {
                    let saldo = 0;
                    let i = 0;
                    let count = result.length;
                    result.forEach(element => {
                        saldo = element['qty_in'] - element['qty_out']
                        if(saldo != 0)
                        {
                            dt.row.add([element['label'] ,element['qty_in'], element['qty_out'],saldo ]).draw()
                        }
                    });
                    // result.forEach(item => {
                        
                    // });
                    
                }
                
            }
        });
    }

    function createDataListView(argNamaClass)
    {
        let object = $("." + argNamaClass).DataTable({
            responsive: true,
            columnDefs: [{
                orderable: true,
                targets: 0,
                checkboxes: {
                    selectRow: true
                }
            }],
            dom: '<"top"<"actions action-btns"B><"action-filters"lf>><"clear">rt<"bottom"<"actions">p>',
            oLanguage: {
                sLengthMenu: "_MENU_",
                sSearch: ""
            },
            aLengthMenu: [
                [4, 10, 15, 20],
                [4, 10, 15, 20]
            ],
            select: {
                style: "multi"
            },
            order: [
                [1, "asc"]
            ],
            bInfo: false,
            pageLength: 4,
            buttons: [{
                extend : 'excel',
                text : 'Download Report',
                className:'btn btn-outline-primary',
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
                            if(residuo !=0  ){//'is t',
                                $(this).attr('s','5');
                            }
                            
                            
                        }
                    });
                },
                title : "Report Balance Inventory - Project : {!! $projectGudang[0]->nama_project !!}, Gudang : {!! $projectGudang[0]->nama_gudang !!}",
                messageTop : "Periode : " + $(".tanggal_awal").val() + " - " + $(".tanggal_akhir").val(),
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            },
            drawCallback : function(settings){

            }
        });

        return object;
    }

    $(document).ready(function(){

        $(".tanggal_awal").val(moment().add(-30, 'days').format('DD-MM-YYYY'));
        $(".tanggal_akhir").val(moment().format('DD-MM-YYYY'));

        $(".tanggal_awal").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })
        $(".tanggal_akhir").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })

        dt = createDataListView('tbl_report');
        // changeBaseOnReport();
        // getReport('item');
        
    });
</script>
@endsection