@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Check stock count
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
                                            <option value selected disabled>Pilih locator</option>
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
                                            <option value="{{$item->id_inventory_detail}} - {{$item->id_item}}">
                                                {{$item->label}} - {{$item->nama_item}}</option>
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
                    <h4 class="card-title">Detail Stock Count By Label</h4>
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
                                                <td style="display:none"></td>
                                                <td>Tanggal</td>
                                                <td>Locator</td>
                                                <td>Item</td>
                                                <td>Qty Aktual</td>
                                                <td>Qty Inventory</td>
                                                <td>Hasil</td>
                                                <td>User</td>
                                                <td>Status</td>
                                                <td>Note</td>
                                            </tr>
                                        </thead>
                                        <tbody class="content_list_item">
                                            @foreach ($list_item as $item)
                                            <tr>
                                                <td style="display:none">{{$item->id_stock_detail}}</td>
                                                <td>{{$item->tanggal}}</td>
                                                <td>{{$item->nama_locator}}</td>
                                                <td>{{$item->label}} - {{$item->nama_item}}</td>
                                                <td>{{$item->qty_input}}</td>
                                                <td>{{$item->qty_available}}</td>
                                                <td>{{$item->qty_input - $item->qty_available}}</td>
                                                <td>{{$item->nama_user}}</td>
                                                <td>
                                                    <div
                                                        class="chip {{ ($item->status == "match") ? "chip-success" : "chip-danger" }} mr-1">
                                                        <div class="chip-body">
                                                            <span class="chip-text">{{$item->status}}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td contenteditable="true" class="editable">{{$item->note}}</td>
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

    $('.tbl_list_item tbody').on( 'blur', 'td.editable', function (e) {
        let row = $(this).closest('tr');
        let idStockCount = row[0].cells[0].innerHTML;
        $.ajax({
            type: "post",
            url: "{{route('stock.setnote', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang])}}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            data: {
                'id_stock_count_detail' : idStockCount,
                'note' : $(this).closest('tr td')[0].innerHTML

            },
            dataType: "json",
            success: function (response) {
                if(response != "success")
                {
                    Swal.fire({
                        title : "Gagal!",
                        text : response,
                        type : "error"
                    });
                }
                else
                {
                    triggeredToast("Note berhasil disimpan");
                }
            }
        });
    });

    function simpanItem() {
        try {
            let qty = $('.quantity').val();
            let id_locator = $(".locator").val();
            let object_item = $(".item").val();
            object_item = object_item.split(' - ');
            if(qty.trim() == "")
            {
                Swal.fire({
                    title : "Gagal!",
                    text : "Quantity masih kosong",
                    type : "error"
                });
            }
            else if(id_locator == "" || object_item == "")
            {
                Swal.fire({
                    title : "Gagal!",
                    text : "Locator atau item belum dipilih",
                    type : "error"
                });
            }
            else
            {
                $.ajax({
                    type: "get",
                    url: "{!! route('stock.prosesstatus', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'kodestock' => $kodestock]) !!}",
                    headers : {
                        "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                    },
                    data: {
                        'qty' :qty,
                        'id_locator' : id_locator,
                        'idinventorydetail' : object_item[0],
                        'id_item' :object_item[1],
                        'stock_count_by' : "label"
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        let result = response;
                        if( result['status'] =="success")
                        {
                            let string = `
                            <tr>
                                <td style="display:none">${result['stock_count_detail'][0]['id_stock_detail']}</td>
                                <td>${result['stock_count_detail'][0]['tanggal']}</td>
                                <td>${result['stock_count_detail'][0]['nama_locator']}</td>
                                <td>${result['stock_count_detail'][0]['label']} - ${result['stock_count_detail'][0]['nama_item']}</td>
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
                                <td contenteditable="true" class="editable"></td>
                            </tr>
                            `;

                            dt.rows.add($(string)).draw();

                            $(".quantity").val("");
                            triggeredToast("Item berhasil disimpan!");
                        }
                        else{
                            console.log(response);
                        }
                    }
                });
            }
        } catch (error) {
            triggeredToast(error, "error");
        }
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