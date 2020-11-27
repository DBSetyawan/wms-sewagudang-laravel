@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Report Outbound
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
<li class="breadcrumb-item active">Report Outbound
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
                                        <select name="" class="form-control base_on" onchange="">
                                            <option value="Item"> Item </option>
                                            <option value="Label"> Label </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Awal</label>
                                        <input type="text" class="form-control tanggal_awal">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="validationTooltip01">Tanggal Akhir</label>
                                        <input type="text" class="form-control tanggal_akhir">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-outline-primary" onclick="getReport()">Tampilkan
                                            Report</button>
                                        {{-- <button class="btn btn-outline-primary export" name="export"
                                        onclick="exportToxlsx()">Download Report</button> --}}
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
                        Report Inbound - Gudang : <b>{{$kodegudang}}</b>, Project : <b>{{$kodeproject}}</b>
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table tbl_report" id="tbl_report" width="100%">
                                <thead class="header_report_outbound">

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
        // let table = $("#tbl_report").html();
        // console.log(table);
        let current_date = "{!! date('d-m-Y') !!}";
        let namaFile = "Outbound" + "{!! $kodegudang !!}"+ " - " + "{!! $kodeproject !!}" + " - "  + current_date
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

    function changeHeaderReport()
    {
        let base_on = $(".base_on").val();
        $(".content_incoming_detail").html("");
        if(base_on == "Label")
        {
            $(".header_report_outbound").html(`<tr class="">
                                        <th>Tanggal Outbound</th>
                                        <th>No. Outbound </th>
                                        <th>Referensi</th>
                                        <th>label</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Quantity</th>
                                        <th>Qty Aktual</th>
                                        <th>UOM</th>
                                        <th>Total CBM</th>
                                        <th>Total Weight</th>
                                    </tr>`)
                                    
        }
        else
        {
            $(".header_report_outbound").html(`<tr class="">
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Quantity</th>
                                        <th>Qty Aktual</th>
                                        <th>UOM</th>
                                        <th>Total CBM</th>
                                        <th>Total Weight</th>
                                    </tr>`)
        }
        
    }

    async function getReport()
    {
        dt.destroy();
        changeHeaderReport();
        dt = await createDataListView('tbl_report');
        let base_on = $(".base_on").val();
        let tanggal_awal = $(".tanggal_awal").val();
        let tanggal_akhir = $(".tanggal_akhir").val();
        $.ajax({
            type: "get",
            url: "{!! route('outbound.displayreport', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            data: {
                'tanggal_awal' : tanggal_awal,
                'tanggal_akhir' : tanggal_akhir,
                'filter' : base_on
            },
            dataType: "json",
            success: function (result) {
                console.log(result);

                if(base_on == "Label")
                {
                    result.forEach(item => {
                        string = `<tr>
                                    <td>${item['tanggal_outbound']}</td>
                                    <td>${item['no_outbound']}</td>
                                    <td>${item['referensi']}</td>
                                    <td>${item['label']}</td>
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['qty_doc']}</td>
                                    <td>${item['qty_load']}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm'] * item['qty_load']}</td>
                                    <td>${item['berat_kotor'] * item['qty_load']}</td>
                                </tr>`
                        dt.rows.add($(string)).draw();
                    });
                }
                else
                {
                    result.forEach(item => {
                        string = `<tr>
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['qty_doc']}</td>
                                    <td>${item['qty_load']}</td>
                                    <td>${item['nama_uom']}</td>
                                    <td>${item['cbm'] * item['qty_load']}</td>
                                    <td>${item['berat_kotor'] * item['qty_load']}</td>
                                </tr>`
                        dt.rows.add($(string)).draw();
                    });
                    
                    // for(let i = 0; i < 12; i++){
                    //     string = `<tr>
                    //                 <td>${result[1]['kode_item']}</td>
                    //                 <td>${result[1]['nama_item']}</td>
                    //                 <td>${result[1]['qty_doc']}</td>
                    //                 <td>${result[1]['qty_load']}</td>
                    //                 <td>${result[1]['nama_uom']}</td>
                    //                 <td>${result[1]['cbm']}</td>
                    //             </tr>`
                    //     dt.rows.add($(string)).draw();
                    // }
                    
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
                    
                },customize: function(xlsx) {
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
                title : "Report Outbound - Project : {!! $project[0]->nama_project !!}, Gudang : {!! $gudang[0]->nama_gudang !!}",
                messageTop : "Periode : " + $(".tanggal_awal").val() + " - " + $(".tanggal_akhir").val(),
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    $(document).ready(function(){
        
        changeHeaderReport();
        $(".tanggal_awal").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })
        $(".tanggal_akhir").pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        })

        $(".tanggal_awal").val(moment().add(-30, 'days').format('DD-MM-YYYY'));
        $(".tanggal_akhir").val(moment().format('DD-MM-YYYY'));
        dt = createDataListView('tbl_report');
        
    });
</script>
@endsection