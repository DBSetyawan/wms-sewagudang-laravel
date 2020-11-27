@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Put Away
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
<li class="breadcrumb-item active">Put Away
</li>
@endsection

@section('content')
<section class="tooltip-validations" id="tooltip-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Put Away</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12 mb-2">
                                <label for="validationTooltip01">Pilih Locator Tujuan</label>
                                <select class="select2 form-control locator_tujuan" placeholder="Pilih locator lama"
                                    onchange="$('.item').select2('open');">
                                    <option value selected disabled>Pilih locator</option>
                                    @foreach ($list_locator as $locator)
                                    <option value="{{$locator->id_locator}}">{{$locator->nama_locator}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="validationTooltip01">Filter</label><br>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-inline-block mr-2">
                                        <fieldset>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="filter_value"
                                                    id="nama_item" onchange="refreshItem()" value="nama_item" checked>
                                                <label class="custom-control-label" for="nama_item">Nama Item</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                    <li class="d-inline-block mr-2">
                                        <fieldset>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="filter_value"
                                                    id="label" onchange="refreshItem()" value="label">
                                                <label class="custom-control-label" for="label">Label</label>
                                            </div>
                                        </fieldset>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="validationTooltip02">Pilih Item</label>
                                <select class="select2 form-control item" placeholder="Pilih item"
                                    onchange="refreshTableItem()">
                                    <option value selected disabled>Pilih item</option>
                                    @foreach ($list_item as $item)
                                    <option value="{{$item->kode_item}}">{{$item->kode_item}} - {{$item->nama_item}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Item</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table nowrap tbl_list_item" id="tbl_list_item" width="100%">
                                        <thead>
                                            <tr class="">
                                                <td>Kode Item</td>
                                                <td>Nama Item</td>
                                                <td>Label</td>
                                                <td>Locator</td>
                                                <td>Quantity</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody class="content_list_item">
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
<section id="form-and-scrolling-components">
    <div class="modal-size-lg mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="set_quantity_modal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="" id="" class="idinventorydetail" style="display: none">
                        <input type="text" name="" id="" class="idinventory" style="display: none">
                        <input type="text" name="" id="" class="iditem" style="display: none">
                        <input type="text" name="" id="" class="idlocatorasal" style="display: none">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Quantity Available</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="quantity_available form-control" value="1" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label for="validationTooltip02">Locator Asal</label>
                                <input type="text" class="namaLocatorAsal form-control" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Quantity Move</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="quantity_move" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-outline-primary btn-simpan"
                                        onclick="prosesPutAway()">Move</button>
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

        $(".quantity_move").keyup(function(){
            let limit_available = $(".quantity_available").val();
            if(parseInt($(this).val()) > parseInt(limit_available) )
            {
                $(this).val(limit_available);
            }
        })

        function getAllLocator()
        {
            $.ajax({
                type:'get',
                url : '{!! route("locator.getalllocator", $kodegudang) !!}',
                headers : {
                    "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                },
                success : function(data){
                    let result = data;
                    $(".locator_lama").html("");
                    $(".locator_baru").html("");
                    $(".locator_lama").append("<option value selected disabled >Pilih locator</option>");
                    $(".locator_baru").append("<option value selected disabled >Pilih locator</option>");
                    result.forEach(locator => {
                        $(".locator_lama").append(`<option value=${locator['id_locator']}> ${locator['nama_locator']}</option>`);
                        $(".locator_baru").append(`<option value=${locator['id_locator']}> ${locator['nama_locator']}</option>`);
                    });
                }
            })
        }
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
                    [4,10, 15, -1],
                    [4,10, 15, "All"]
                ],
                order: [
                    [1, "asc"]
                ],
                bInfo: false,
                pageLength: 4,
                keys: true,
                select : true,
                buttons: [],
                initComplete: function (settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary")
                }
            });

            return object;
        }

        function minMaxSpin() {
            let touchspinValue = $(".quantity_move"),
                counterMin = 1,
                counterMax = 1;
            if (touchspinValue.length > 0) {
                touchspinValue.TouchSpin({
                    min: counterMin,
                    max: counterMax
                }).on('touchspin.on.startdownspin', function () {
                    var $this = $(this);
                    $('.bootstrap-touchspin-up').removeClass("disabled-max-min");
                    if ($this.val() == counterMin) {
                        $(this).siblings().find('.bootstrap-touchspin-down').addClass("disabled-max-min");
                    }
                }).on('touchspin.on.startupspin', function () {
                    var $this = $(this);
                    $('.bootstrap-touchspin-down').removeClass("disabled-max-min");
                    if ($this.val() == counterMax) {
                        $(this).siblings().find('.bootstrap-touchspin-up').addClass("disabled-max-min");
                    }
                });
            }
        }

        function openModalSetQuantityMove(argIdItem, argIdInventoryDetail, argIdInventory, argMaxQuantity, argIdLocator, argNamaLocator) { 
            $("#set_quantity_modal").modal('show');
            $(".iditem").val(argIdItem);
            $(".idinventorydetail").val(argIdInventoryDetail);
            $(".idinventory").val(argIdInventory);
            $(".quantity_available").val(argMaxQuantity);
            $(".idlocatorasal").val(argIdLocator);
            $(".namaLocatorAsal").val(argNamaLocator);
            $(".quantity_move").val(argMaxQuantity);
            $(".quantity_move").trigger("touchspin.updatesettings", {
                max: parseInt(argMaxQuantity)
            });
            
         }

        function refreshItem()
        {
            let filter = $("input[name=filter_value]:checked").val();
            let url = "";
            if(filter == "label")
            {
                url = "{!! route('item.getallitembaselabel', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]) !!}"
            }
            else
            {
                url = "{!! route('item.getallitem', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]) !!}";
            }

            $.ajax({
                type: "get",
                url: url,
                headers :{
                    "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                },
                data: {
                    'filter' : $("input[name=filter_value]:checked").val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    let result = response
                    $(".item").html("");
                    $(".item").append('<option value selected disabled></option>')
                    dt.clear().draw();
                    if($("input[name=filter_value]:checked").val() == "label")
                    {
                        result.forEach(item => {
                            $(".item").append(`<option value='${item['label']}'> ${item['label']}</option>`);
                        });
                    }
                    else
                    {
                        result.forEach(item => {
                            $(".item").append(`<option value='${item['kode_item']}'> ${item['nama_item']}</option>`);
                        });
                    }
                }
            });

        }

        // function getListItem()
        // {
        //     let kode_item = $(".item").val();
        //     $.ajax({
        //         type: "get",
        //         url: "{!! route('item.infofrominbounddetail', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]) !!}",
        //         headers :{
        //             "X-CSRF-TOKEN" : "{{ csrf_token() }}"
        //         },
        //         data: {
        //             'filter_value': kode_item,
        //             'filter' : $("input[name=filter_value]:checked").val()
        //         },
        //         dataType: "json",
        //         success: function (response) {
        //             console.log(response);
        //             let result = response
        //             $(".item").html("");
        //             $(".item").append('<option value selected disabled></option>')
        //             dt.clear().draw();
        //             if($("input[name=filter_value]:checked").val() == "label")
        //             {
        //                 result.forEach(item => {
        //                     $(".item").append(`<option value='${item['label']}'> ${item['label']}</option>`);
        //                 });
        //             }
        //             else
        //             {
        //                 result.forEach(item => {
        //                     $(".item").append(`<option value='${item['kode_item']}'> ${item['nama_item']}</option>`);
        //                 });
        //             }
        //         }
        //     });
            
        // }

        function refreshTableItem()
        {
            let kode_item = $(".item").val();
            let url = "{!! route('item.infofrominbounddetail', ['kodeproject' => $kodeproject,'kodegudang' => $kodegudang]) !!}";
            $.ajax({
                type: "get",
                url: url,
                headers  : {
                    "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                },
                data : {
                    'filter_value' : kode_item,
                    'filter' : $("input[name=filter_value]:checked").val()
                },
                success: function (response) {
                    console.log(response);
                    let result = response;
                    let idx = 0;
                    let currentCountRow = $(".detail_outbound tr").length;
                    let string = "";
                    let object = "";
                    dt.clear().draw();
                    result.forEach(item => {
                         if(item['available'] > 0)
                         {
                            object = {a:item['id_item'], b:item['id_inventory_detail'], c:item['id_inventory'], d:item['available'], e:item['id_locator'], f:item["nama_locator"]};
                            string = `
                                <tr class="${idx}" data-value="">
                                    <td>${item['kode_item']}</td>
                                    <td>${item['nama_item']}</td>
                                    <td>${item['label']}</td>
                                    <td>${item['nama_locator']}</td>
                                    <td class="${currentCountRow}-quantity">${item['available']}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary" onclick="openModalSetQuantityMove(${item['id_item']}, ${item['id_inventory_detail']}, ${item['id_inventory']}, ${item['available']}, ${item['id_locator']}, '${item["nama_locator"]}')">Move</button>
                                    </td>
                                    
                                </tr>
                            `;

                            dt.rows.add($(string)).draw();
                            $(`.${idx}`).data('value', object);
                            idx++;
                            
                         }
                    });
                    dt.cell(':eq(0)').focus();
                }
            });

        }
        
        function prosesPutAway() { 
            let locator_lama = $(".idlocatorasal").val();
            let locator_baru = $(".locator_tujuan").val();
            let iditem = $(".iditem").val();
            let idinventorydetail = $(".idinventorydetail").val();
            let idinventory = $(".idinventory").val();
            let quantity_move = $(".quantity_move").val();
            let quantity_available = $(".quantity_available").val();
            if(locator_lama != locator_baru)
            {
                if(parseInt(quantity_move) <= parseInt(quantity_available))
                {
                    $.ajax({
                        type: "get",
                        url: "{!! route('putaway.prosesputaway', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]) !!}",
                        headers : {
                            "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                        },
                        data: {
                            'locator_lama' : locator_lama,
                            'locator_baru' : locator_baru,
                            'quantity_move' : quantity_move,
                            'id_item' : iditem,
                            'idinventorydetail' : idinventorydetail,
                            'idinventory' : idinventory
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            let title = "";
                            let text = "";
                            if(response=="success")
                            {
                                $("#set_quantity_modal").modal('hide');
                                refreshTableItem();
                                title = "Berhasil!";
                                text = "Item berhasil dipindahkan!";
                                type = "success";
                                $(".item").select2('open');
                            }
                            else
                            {
                                title = "Gagal!";
                                text = response;
                                type = "error";
                                refreshTableItem();
                            }
                            Swal.fire({
                                title: title,
                                text: text,
                                type: type,
                                showConfirmButton: false,
                                buttonsStyling: false,
                                timer : 1000,

                            });
                        }
                    });
                }
                else
                {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Quantity yang dipindahkan melebihi kapasitas quantity available",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                        timer : 1000
                    });
                }
            }
            else if(locator_baru == null || locator_lama == null)
            {
                Swal.fire({
                    title: "Gagal!",
                    text: "Locator lama / locator baru belum dipilih!",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
            else
            {
                Swal.fire({
                    title: "Gagal!",
                    text: "Locator lama dan locator baru tidak boleh sama",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }

         }

        $(document).ready(function () {
            dt = createDataListView('tbl_list_item');
            $('.tbl_list_item').on('key-focus.dt', function(e, datatable, cell){
                $(datatable.row(cell.index().row).node()).addClass('selected');
            });

            $('.tbl_list_item').on('key-blur.dt', function(e, datatable, cell){
                $(datatable.row(cell.index().row).node()).removeClass('selected');
            });
                
            $('.tbl_list_item').on('key.dt', function(e, datatable, key, cell, originalEvent){
                if(key === 13){
                    let row_index = cell.index().row;
                    let value = $(`.${row_index}`).data('value');
                    openModalSetQuantityMove(value.a, value.b, value.c, value.d, value.e, value.f);
                    $(".item").select2('close');
                }
            });      
            // getAllLocator();
            minMaxSpin();
            $(".locator_tujuan").select2('open');
            
        });
</script>
@endsection