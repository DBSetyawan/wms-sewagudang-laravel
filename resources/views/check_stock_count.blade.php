@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Check stock count
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject' => $kodeproject])}}">Gudang
        ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('inventory.index', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject])}}">Inventory</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('stock.index', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject])}}">Stock Count
        ({{$kodestock}})</a>
</li>
<li class="breadcrumb-item active">Check Stock Count
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Input data item</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">Pilih Locator</label>
                                        <select class="select2 form-control locator" placeholder="Pilih locator"
                                            onchange="">
                                            <option value selected disabled >Pilih locator</option>
                                            @foreach ($list_locator as $locator)
                                                <option value={{$locator->id_locator}}> {{$locator->nama_locator}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">Pilih Item</label>
                                        <select class="select2 form-control item" placeholder="Pilih type item">
                                            <option disabled selected> Pilih Item</option>
                                            @foreach ($list_item_master as $item)
                                        <option value={{$item->id_item}}> {{$item->kode_item}} - {{$item->nama_item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Quantity</label>
                                        <input type="number" class="quantity form-control" placeholder="Quantity">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="simpanItem()">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Stock Count</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <hr>
                                <div class="table-responsive">
                                    <table class="table nowrap tbl_list_item" id="tbl_list_item">
                                        <thead>
                                            <tr class="">
                                                <td>Tanggal</td>
                                                <td>Locator</td>
                                                <td>Item</td>
                                                <td>Qty Input</td>
                                                <td>Qty Aktual</td>
                                                <td>Hasil</td>
                                                <td>User</td>
                                                <td>Status</td>
                                            </tr>
                                        </thead>
                                        <tbody class="content_list_item">
                                            @foreach ($list_item as $item)
                                            <tr>
                                                <td>{{$item->tanggal}}</td>
                                                <td>{{$item->nama_locator}}</td>
                                                <td>{{$item->kode_item}} - {{$item->nama_item}}</td>
                                                <td>{{$item->qty_input}}</td>
                                                <td>{{$item->qty_available}}</td>
                                                <td>{{$item->qty_input - $item->qty_available}}</td>
                                                <td>{{$item->nama_user}}</td>
                                                <td>
                                                    <div class="chip {{ ($item->status == "match") ? "chip-success" : "chip-danger" }} mr-1">
                                                        <div class="chip-body">
                                                            <span class="chip-text">{{$item->status}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    function createDataListView(argNamaClass)
    {
        let object = $("." + argNamaClass).DataTable({
            responsive: false,
            columnDefs: [{
                orderable: true,
                targets: 0,
                checkboxes: {
                    selectRow: false
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
            order: [
                [1, "asc"]
            ],
            bInfo: false,
            pageLength: 4,
            buttons: [{
                text: "Download",
                action: function () {
                    exportToxlsx();
                },
                className: "btn-outline-primary"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    function getItemBaseLocator()
    {
        let id_locator = $(".locator").val();
        $.ajax({
            type: "get",
            url: "{!! route('item.getitembaselocator', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}",
            headers :{
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'id_locator': id_locator,
                'filter' : ""
            },
            dataType: "json",
            success: function (response) {
                let result = response
                $(".item").html("");
                $(".item").append('<option value selected disabled></option>')
                result.forEach(item => {
                    $(".item").append(`<option value=${item['id_item']}> ${item['nama_item']}</option>`);
                });
            }
        });
    }

    function simpanItem() {
        let qty = $('.quantity').val();
        let id_locator = $(".locator").val();
        let id_item = $(".item").val();
        $.ajax({
            type: "get",
            url: "{!! route('stock.prosesstatus', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'kodestock' => $kodestock]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'qty' :qty,
                'id_locator' : id_locator,
                'id_item' : id_item
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let result = response;
                if( result['status'] =="success")
                {
                    let string = `
                    <tr>
                        <td>${result['stock_count_detail'][0]['tanggal']}</td>
                        <td>${result['stock_count_detail'][0]['nama_locator']}</td>
                        <td>${result['stock_count_detail'][0]['kode_item']} - ${result['stock_count_detail'][0]['nama_item']}</td>
                        <td>${result['stock_count_detail'][0]['qty_input']}</td>
                        <td>${result['stock_count_detail'][0]['qty_available']}</td>
                        <td>${result['stock_count_detail'][0]['qty_input'] - result['stock_count_detail'][0]['qty_available']}</td>
                        <td>Thierry Horax</td>
                        <td>
                            <div class="chip ${(result['stock_count_detail'][0]['status'] == "match") ? "chip-success" : "chip-danger"}  mr-1">
                                <div class="chip-body">
                                    <span class="chip-text">${result['stock_count_detail'][0]['status']}</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    `;

                    dt.rows.add($(string)).draw();

                    $(".quantity").val("");
                }
                else{
                    console.log(response);
                }
            }
        });
     }

    function exportToxlsx()
    {
        let current_date = "{!! date('d-m-Y') !!}";
        let namaFile = "SC"+ " - " + "{!! $kodegudang !!}"+ " - " + "{!! $kodeproject !!}" + " - " + "{!! $kodestock !!}" + " - "  + current_date
        excel = new ExcelGen({
            "src_id" : "tbl_list_item",
            "show_header": true,
        });
        excel.generate(namaFile);
    }

    $(document).ready(function(){
        // getAllLocator();
        dt = createDataListView("tbl_list_item");
    })
</script>
@endsection