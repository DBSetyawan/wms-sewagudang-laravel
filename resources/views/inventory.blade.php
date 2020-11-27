@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Inventory
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject' => $kodeproject])}}">Gudang
        ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item active">Inventory
</li>
@endsection

@section('page_name')
<h5>Kode gudang : <b>{{$kodegudang}}</b>, Kode project : <b>{{$kodeproject}}</b></h5>
@endsection


@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Pilih item</div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <select name="" class="form-control list_item select2">
                                            @foreach ($list_item as $item)
                                            <option value="{{$item->kode_item}}">{{$item->label}} - {{$item->kode_item}}
                                                - {{$item->nama_item}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-outline-primary" onclick="getListItem()">Tampilkan
                                            item</button>
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
                        List Item
                    </div>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table tbl_list_item" id="tbl_list_item" width="100%">
                                <thead class="">
                                    <tr>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Label</th>
                                        <th>Quantity</th>
                                        <th>Locator</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="content_report_inbound">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="data-thumb-view" class="data-thumb-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="barcode_container" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                    </div>
                    <div class="modal-body" style="text-align: center">
                        <input type="text" name="" id="kodeitembarcode" style="display:none">
                        <input type="text" name="" id="namaitembarcode" style="display:none">
                        <img id="barcode">
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

    function printBarcode(argValueBarcode, argNamaItem)
    {
        JsBarcode("#barcode", argValueBarcode, {
            format : "CODE128",
            text: argValueBarcode + " - " + argNamaItem
        });

        let barcode = document.getElementById("barcode");
        let doc = new jsPDF({
            orientation : 'p',
            unit : 'pt',
            format : [150, 150]
        });
        
        let doc_width = doc.internal.pageSize.getWidth();
        var doc_height = doc.internal.pageSize.getHeight();
        let x = 1;
        let y = 0;
        let y_pdf_text = 50;
        let margin_left_text =  (doc.internal.pageSize.width / 2) - (doc.getStringUnitWidth(argNamaItem) * doc.internal.getFontSize() / 2) + 5;

        doc.text(argNamaItem, doc.internal.pageSize.getWidth()/2, 120, "center");
        doc.addImage(barcode.src, "PNG", x, y, 150, 100);
        doc.save(`barcode_${argValueBarcode}_${argNamaItem}.pdf`);

    }

    function getListItem()
        {
            let kode_item = $(".list_item").val();
            console.log(kode_item);
            let url = "{!! route('item.infofrominbounddetail', ['kodeproject' => $kodeproject,'kodegudang' => $kodegudang]) !!}";
            $.ajax({
                type: "get",
                url: url,
                headers  : {
                    "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                },
                data : {
                    'filter_value' : kode_item,
                    'filter' : 'item'
                },
                success: function (response) {
                    let result = response;
                    dt.clear().draw();
                    result.forEach(item => {
                        string = `
                                <tr data-value="">
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['label']}</td>
                                    <td>${item['available']}</td>
                                    <td>${item['nama_locator']}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-icon" onclick="printBarcode('${item['kode_item']}', '${item['nama_item']}')" title="Print barcode base kode item">
                                            <i class="fa fa-barcode"></i>
                                        </button> &nbsp;

                                        <button type="button" class="btn btn-info btn-icon" onclick="printBarcode('${item['label']}', '${item['nama_item']}')" title="Print barcode base label">
                                            <i class="fa fa-barcode"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;

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
                [10, 20, 30, "All"]
            ],
            order: [
                [1, "asc"]
            ],
            bInfo: false,
            pageLength: 10,
            keys: true,
            select: {
                style: "single"
            },
            buttons: [],
            fnDrawCallBack : function(osettings){
            },
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    $(document).ready(function () {
        dt = createDataListView("tbl_list_item");
    });
</script>
@endsection