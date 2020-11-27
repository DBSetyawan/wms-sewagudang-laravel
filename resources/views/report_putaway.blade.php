@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Report Putaway
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
<li class="breadcrumb-item active">Report Putaway
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card content_report">
                <div class="card-header">
                    <div class="col-6">
                        Report Putaway - Gudang : <b>{{$kodegudang}}</b>, Project : <b>{{$kodeproject}}</b>
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table tbl_report" id="tbl_report" width="100%">
                                <thead>
                                    <tr class="">
                                        <th>Tanggal</th>
                                        <th>Label</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Locator Asal</th>
                                        <th>Locator Tujuan</th>
                                        <th>Nama User</th>
                                    </tr>
                                </thead>
                                <tbody class="content_putaway">

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
        try {
            let current_date = "{!! date('d-m-Y') !!}";
            let namaFile = "putaway - " + "{!! $kodeproject !!}" + " - " + "{!! $kodegudang !!}" + " - "  + current_date
            excel = new ExcelGen({
                "src_id" : "tbl_report",
                "show_header": true,
            });
            excel.generate(namaFile);
        } catch (error) {
            console.log(error);
        }
    }

    function getReport()
    {
        $.ajax({
            type: "get",
            url: "{!! route('putaway.displayreport', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            success: function (response) {
                console.log(response);
                let result = response;
                dt.clear().draw();
                result.forEach(item => {
                    string = `<tr>
                                <td>${item['tanggal']}</td>
                                <td>${item['label']}</td>
                                <td>${item['kode_item']} - ${item['nama_item']}</td>
                                <td>${item['qty']}</td>
                                <td>${item['nama_locator_asal']}</td>
                                <td>${item['nama_locator_tujuan']}</td>
                                <td>${item['nama_user']}</td>
                            </tr>`
                    dt.rows.add($(string)).draw();
                });
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
                [10, 20, 30, -1],
                [10, 20, 30, "All"],
            ],
            select: {
                style: "multi"
            },
            order: [
                [0, "desc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [{
                extend : "excel",
                text : "Download Report",
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
                        if(numero ==1 )
                        {
                            $(this).attr('s','22');
                        }
                        else if (numero>1){
                            if(residuo !=0  ){//'is t',
                                $(this).attr('s','5');
                            }
                            
                            
                        }
                    });
                },
            title : "Report Putaway -  Project : {{$projectGudang[0]->nama_project}}, Gudang : {{$projectGudang[0]->nama_gudang}}",
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    $(document).ready(function(){
        dt = createDataListView('tbl_report');
        getReport();
    });
</script>
@endsection