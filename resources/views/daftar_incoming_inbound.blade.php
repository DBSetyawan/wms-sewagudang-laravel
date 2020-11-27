@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar incoming inbound
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('extra_information')
,&nbsp;<b>No. Inbound : </b>&nbsp; {{$inbound[0]->no_inbound}}, &nbsp;<b>Referensi/No. DO : </b>
&nbsp;{{$inbound[0]->referensi}}
@endsection


@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject' => $kodeproject])}}">Gudang
        ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('inventory.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inventory</a>
</li>
<li class="breadcrumb-item"><a
        href="{{route('inbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inbound
        ({{$noinbound}})</a>
</li>
<li class="breadcrumb-item active">Daftar Incoming Inbound
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <div class="row">
        <div class="col-12">
            <label style="font-size: 16pt" class="label_quantity">Quantity Loaded : <b>{{$qty_aktual}}</b> / {{$qty}}
            </label>
        </div>
    </div>
    <hr>
    <!-- dataTable starts -->
    <div class="table-responsive">

        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nomor Inbound Incoming</th>
                    <th>Tanggal Inbound</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_incoming_inbound as $incoming_inbound)
                <tr>
                    <td class="no_incoming">{{$incoming_inbound->no_incoming}}</td>
                    <td>{{$incoming_inbound->tanggal}}</td>
                    <td><button class="btn btn-sm btn-icon btn-success" title="Proses"
                            onclick="openModalProsesIncoming('{{$incoming_inbound->no_incoming}}')"><i
                                class="fa fa-check-square-o"></i></button> &nbsp;
                        <button
                            onclick="window.location = '{{route('incoming.edit', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject, 'noinbound'=> $noinbound, 'noincoming' => $incoming_inbound->no_incoming])}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit incoming"><i
                                class="fa fa-pencil"></i></button> &nbsp;
                        <button onclick="openModalPeringatanHapusIncoming('{{$incoming_inbound->no_incoming}}')"
                            class="btn btn-sm btn-icon btn-danger" title="Edit incoming"><i
                                class="fa fa-trash-o"></i></button> &nbsp;
                        <button onclick="getIncomingDetail('{{$incoming_inbound->no_incoming}}')"
                            title="Detail incoming" class="btn btn-sm btn-icon btn-info"><i
                                class="fa fa-info"></i></button>
                        &nbsp;
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="proses_incoming" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel33">No.Incoming : <b class="no_incoming_detail"></b>,
                            No.
                            Inbound : <b>{{$noinbound}}</b> , Project :
                            <b>{{$kodeproject}}</b> , Gudang : <b>{{$kodegudang}}</b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 class="label_quantity"></h3>
                        <div class="table-responsive">
                            <table class="table nowrap tbl_proses_item" width="100%">
                                <thead>
                                    <tr class="th_detail_inbound">
                                        <th style="display:none">Id Item</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Actual Weight</th>
                                        <th>Actual CBM</th>
                                        <th>Quantity</th>
                                        <th>Quantity Aktual</th>
                                        <th>Label</th>
                                        {{-- <th>Batch</th> --}}
                                        {!! $template->th_detail_inbound !!}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- @if (Auth::user()->role->nama_role == "Admin")
                        <button onclick="bulkProsesIncoming()">Bulk proses incoming</button>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="incoming_detail" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Incoming Detail, No. Incoming Inbound : <b
                                class="no_incoming_detail"></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ">
                                    <table class="table tbl_history_incoming_detail">
                                        <thead>
                                            <tr class="">
                                                <th>Kode Item</th>
                                                <th>Nama Item</th>
                                                <th>Quantity</th>
                                                <th>Tanggal Update</th>
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
        </div>
</section>

<section id="form-and-scrolling-components">
    <div class="modal-size-lg mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="proses_item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="" id="" class="id_inbound_detail" style="display: none">
                        <input type="text" name="" id="" class="id_item" style="display: none">
                        <input type="text" name="" id="" class="qty_max" style="display: none">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Kode Item</label>
                                <div class="input-group">
                                    <input type="text" class="kode_item_field form-control" value="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Nama Item</label>
                                <div class="input-group">
                                    <input type="text" class="nama_item_field form-control" value="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Label</label>
                                <div class="input-group">
                                    <input type="text" class="label_field form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Quantity</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="quantity" onkeypress="moveToSelectLocator(event)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Locator</label>
                                    <select name="" id="" class="form-control list_locator" placeholder="Pilih Locator">
                                        @foreach ($list_locator as $locator)
                                        <option value={{$locator->id_locator}}> {{$locator->nama_locator}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-outline-primary btn-simpan"
                                        onclick="updateIncoingInbound()">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_incoming" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="noincoming" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="hapusIncoming()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    let dt = "";
    let dt_poroses_item = "";
    let arr = [];
    $(".quantity").keyup(function(){
        let limit_available = $(".qty_max").val();
        if(parseInt($(this).val()) > parseInt(limit_available))
        {
            // console.log(($(this).val().trim().length > limit_available.length));
            $(this).val(limit_available);
        }
        else
        {
            return;
        }
    })

    function openModalPeringatanHapusIncoming(argNoIncoming) { 
        $(".noincoming").html(argNoIncoming);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus incoming inbound dengan nomor incoming <b>${argNoIncoming}</b> ?`);
        $("#hapus_incoming").modal('show');
     }

    function hapusIncoming() { 
        let url = "{!! route('incoming.delete', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> $noinbound, 'noincoming' => 'NO_INCOMING']) !!}";
         url = url.replace('NO_INCOMING', $(".noincoming").html());
         $.ajax({
             type: "get",
             url: url,
             headers: {
                 "X-CSRF-TOKEN" : "{{ csrf_token() }}"
             },
             success: function (response) {
                 let result = JSON.parse(response);
                 if(result == "success")
                 {
                     window.location.reload();
                 }
                 else
                 {
                    Swal.fire({
                        title: "Gagal!",
                        text: response,
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                 }
             }
         });
     }

    function openModalProsesIncoming(argNoIncoming) {
        $("#proses_incoming").modal('show');
        $(".no_incoming_detail").html(argNoIncoming);
        refreshListItem(argNoIncoming);
        

        setTimeout(() => {
            $("#proses_incoming .dataTables_filter input").focus();
        }, 1000);
    }

    $("input[type=search]").on('change', function(){
        console.log(this);
    })

    $(".tbl_proses_item").on('draw', function(){
        console.log('object');
        hideHiddenLabel();
    });

    function createDataListView(argNamaClass) {
        let object = $("." + argNamaClass).DataTable({
            destroy: true,
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
                style: "single"
            },
            order: [
                [1, "asc"]
            ],
            bInfo: false,
            pageLength: 4,
            keys : true,
            buttons: [],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    function openModalProsesItem(argNamaItem, argMaxQuantity, argIdItem, argNoInboundDetail, argLabel, argKodeItem) {
        let no_inbound = "{!! $noinbound !!}";
        let kode_gudang = "{!! $kodegudang !!}";
        let kode_project = "{!! $kodeproject !!}";
        console.log(argLabel);
        $(".id_inbound_detail").val(argNoInboundDetail);
        $(".kode_item_field").val(argKodeItem);
        $(".label_field").val(argLabel);
        $(".nama_item_field").val(argNamaItem);
        $(".id_item").val(argIdItem);
        $(".qty_max").val(argMaxQuantity)
        $("#proses_item").modal('show');
        $("#title").html("No. Inbound : <b>" + no_inbound + "</b>, Project : <b>" + kode_project +
            "</b>, Gudang : <b>" + kode_gudang + "</b>, Nama Item : <b>" + argNamaItem + "</b>");
        $("#proses_incoming").modal('hide');
        $(".quantity").trigger("touchspin.updatesettings", {
            max: argMaxQuantity
        })
        $(".quantity").val(argMaxQuantity);
        $(".quantity").focus();
    }

    function getIncomingDetail(argNoIncoming)
    {
        $("#incoming_detail").modal('show');
        let urlFormat =
            `{!! route('incoming.info',['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject, 'noinbound' => $noinbound, 'noincoming' => 'ID_INCOMING']) !!}`;
        urlFormat = urlFormat.replace('ID_INCOMING', argNoIncoming);

        $.ajax({
            type: "get",
            url: urlFormat,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = response
                $(".no_incoming_detail").html($(".no_incoming").html());
                dt.clear().draw();
                result.forEach(item => {
                    dt.row.add([
                        item['kode_item'],
                        item['nama_item'],
                        item['quantity'],
                        item['tanggal_update']
                    ]).draw();
                });
            }
        });
    }

    $("#proses_item").on('show.bs.modal', async function () {
        let object_item = await getItemQuantity('{!! $noinbound !!}', $(".id_item").val(), $(
            ".id_inbound_detail").val());
        object_item = JSON.parse(object_item);
        if (object_item[0]['qty_aktual'] >= object_item[0]['qty']) {
            $(".btn-simpan").prop('disabled', true);
        } else {
            $(".btn-simpan").prop('disabled', false);
        }
        $(".quantity").focus();
    })

    function moveToSelectLocator(e) { 
        if(e.keyCode == 13)
        {
            $(".list_locator").focus();
        }
     }

    $("#proses_item").on('hidden.bs.modal', function () {
        $("#proses_incoming").modal('show');
        
        refreshListItem();

        setTimeout(() => {
            $("#proses_incoming .dataTables_filter input").focus();
        }, 800);
    })

    $("#proses_incoming .dataTables_filter input").on('change', function () { 
        dt_poroses_item.cell(":eq(1)").focus();
     })

    function finishDocument() {
        let list_id_incoming = [];
        
        let list_incoming = {!! json_encode($list_incoming_inbound) !!};
        list_incoming.forEach(incoming => {
            list_id_incoming.push(incoming['id_incoming_inbound']);
        });

        let url =
            "{!! route('incoming.finsihdocument', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'noinbound' => $noinbound]) !!}";
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{csrf_token()}}"
            },
            data: {
                'list_id_incoming' : JSON.stringify(list_id_incoming)
            },
            dataType : "json",
            success: function (response) {
                let result = response;
                
                if (result == "success") {
                    window.location =
                        "{!! route('inbound.index', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject]) !!}";
                } else {

                }
            }
        });
    }

    function refreshListItem(argNoIncoming) {
        let urlFormat =
            `{!! route('incoming.proses',['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject, 'noinbound' => $noinbound]) !!}`;

        $.ajax({
            type: "get",
            url: urlFormat,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (data) {
                /*
                quantity: $(".quantity").val(),
                locator: locator,
                id_item: id_item,
                id_inbound_detail: id_inbound_detail
                */
                let result = data;
                let quantity = 0;
                let quantity_aktual = 0;
                let object = "";
                let idx= 0;
                dt_poroses_item.clear();
                result.forEach(item => {
                    object = {a:item['nama_item'], b:(item['quantity']-item['qty_aktual']), c:item['id_item'],d:item['id_inbound_detail'], e:item['label'], f:item['kode_item']};
                    string = `<tr class=${idx}>
                        <td style="display:none">` + item['id_item'] + `</td>
                        <td>` + item['kode_item'] + `</td>
                        <td>` + item['nama_item'] + `</td>
                        <td>` + item['berat_bersih'] + `</td>
                        <td>` + item['cbm'] + `</td>
                        <td>` + item['quantity'] + `</td>
                        <td>` + item['qty_aktual'] + `</td>
                        <td>` + item['label'] + `</td>` +
                        item['custom_field_td'] +
                        `<td><button class="btn btn-sm btn-icon btn-success" onclick="openModalProsesItem('${item['nama_item']}', ${item['quantity']}-${item['qty_aktual']}, '${item['id_item']}' ,'${item['id_inbound_detail']}', '${item['label']}', '${item['kode_item']}')" title="Proses" ${(item['quantity'] == item['qty_aktual']) ? "disabled" : ""}><i class="feather icon-check-square"></i></button></td>` +
                        `</tr>`;

                    
                    dt_poroses_item.rows.add($(string)).draw();
                    hideHiddenLabel();
                    quantity += item['quantity'];
                    quantity_aktual += item['qty_aktual'];
                    $(`.${idx}`).data('value', object);
                    idx++;

                    // arr.push({'quantity' : item['quantity'], 'id_item' : item['id_item'], 'id_inbound_detail' : item['id_inbound_detail']})
                });
                
                hideHiddenLabel();
                $(".label_quantity").html(`Quantity Loaded : ${quantity_aktual} / ${quantity}`)
                if(quantity == quantity_aktual)
                {
                    $(".tambah_incoming").prop('disabled', true);
                }
                dt_poroses_item.cell(":eq(1)").focus();
            }
        })
    }

    function getItemQuantity(argNoInbound, argIdItem, argIdInboundDetail) {
        let url =
            "{!! route('inbound.getiteminfoininbounddetail', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject, 'noinbound'=> 'NO_INBOUND' ,'iditem'=>'VAR_ID']) !!}";
        url = url.replace("VAR_ID", argIdItem);
        url = url.replace("NO_INBOUND", argNoInbound);
        let object_item = $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                "idinbounddetail": argIdInboundDetail
            },
            dataType: "json",
            async: false,
            success: function (response) {}
        }).responseText;

        return object_item;
    }

    function updateIncoingInbound() {
        let id_inbound_detail = $(".id_inbound_detail").val();
        let id_item = $(".id_item").val();
        locator = $(".list_locator").val();

        let urlFormat =
            `{!! route('incoming.storetoincomingdetail',['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject, 'noinbound'=>$noinbound, 'noincoming'=> 'NO_INCOMING']) !!}`;
        urlFormat = urlFormat.replace('NO_INCOMING', $(".no_incoming").html());
        $.ajax({
            type: "get",
            url: urlFormat,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {

                quantity: $(".quantity").val(),
                locator: locator,
                id_item: id_item,
                id_inbound_detail: id_inbound_detail
            },
            success: function (data) {
                console.log(data);
                let message = "";
                let info = "";
                let type_swal = "";

                let object_item = getItemQuantity('{!! $noinbound !!}', id_item,
                    id_inbound_detail);
                object_item = JSON.parse(object_item);

                if (data == "success") {
                    // message = "Berhasil";
                    // info = "Item berhasil diproses";
                    // type_swal = "success";
                    if(parseInt((object_item[0]['qty'] - object_item[0]['qty_aktual'])) == 0)
                    {
                        $("#proses_item").modal('hide');
                        triggeredToast("Item telah dipindahkan semua!");
                    }
                    else
                    {
                        $(".quantity").trigger("touchspin.updatesettings", {
                            max: parseInt((object_item[0]['qty'] - object_item[0]['qty_aktual']))
                        });
                        $(".quantity").val((object_item[0]['qty'] - object_item[0]['qty_aktual']));

                        Swal.fire({
                            title: "Berhasil!",
                            text: "Item barhasil disimpan", 
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                            timer:1000
                        });
                        $("#proses_item").modal('hide');
                    }
                } else {
                    message = "Gagal";
                    Info = data;
                    type_swal = "error";
                    Swal.fire({
                        title: message,
                        text: info, 
                        type: type_swal,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }

               
            }
        })
    }

    function minMaxSpin() {
        let touchspinValue = $(".quantity"),
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

    function dataThumbView()
    {
        let dataThumbView = $(".data-thumb-view").DataTable({
            responsive: false,
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
                [0, "desc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [{
                text: "Buat Incoming Inbound",
                action: function () {
                    window.location.href="{{route('incoming.create',['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject, 'noinbound' =>$noinbound])}}"
                },
                className: "btn-outline-primary tambah_incoming"
            },
            {
                text: "Finish Document",
                action: function () {
                    finishDocument();
                },
                className: "btn-outline-primary finish_document"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
                $(".finish_document").attr('style','margin-left:5px;')
            },
            
        });

    }

    // function bulkProsesIncoming() {
    //     console.log(arr);
    //     arr.forEach(async function(item){
    //         await testingBulkProses(item);
    //     })
    // }

    // async function testingBulkProses(item) {
    //     let urlFormat =
    //     `{!! route('incoming.storetoincomingdetail',['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject, 'noinbound'=>$noinbound, 'noincoming'=> 'NO_INCOMING']) !!}`;
    //     urlFormat = urlFormat.replace('NO_INCOMING', $(".no_incoming").html());
    //     await $.ajax({
    //         type: "get",
    //         url: urlFormat,
    //         headers: {
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         },
    //         dataType: "json",
    //         data: {

    //             quantity: item['quantity'],
    //             locator: 1,
    //             id_item: item['id_item'],
    //             id_inbound_detail: item['id_inbound_detail']
    //         },
    //         success: function (data) {
    //             console.log(data);
    //             // let message = "";
    //             // let info = "";
    //             // let type_swal = "";

    //             // let object_item = getItemQuantity('{!! $noinbound !!}', id_item,
    //             //     id_inbound_detail);
    //             // object_item = JSON.parse(object_item);

    //             // if (data == "success") {
    //             //     // message = "Berhasil";
    //             //     // info = "Item berhasil diproses";
    //             //     // type_swal = "success";
    //             //     if(parseInt((object_item[0]['qty'] - object_item[0]['qty_aktual'])) == 0)
    //             //     {
    //             //         $("#proses_item").modal('hide');
    //             //         triggeredToast("Item telah dipindahkan semua!");
    //             //     }
    //             //     else
    //             //     {
    //             //         $(".quantity").trigger("touchspin.updatesettings", {
    //             //             max: parseInt((object_item[0]['qty'] - object_item[0]['qty_aktual']))
    //             //         });
    //             //         $(".quantity").val((object_item[0]['qty'] - object_item[0]['qty_aktual']));

    //             //         Swal.fire({
    //             //             title: "Berhasil!",
    //             //             text: "Item barhasil disimpan", 
    //             //             type: "success",
    //             //             confirmButtonClass: 'btn btn-primary',
    //             //             buttonsStyling: false,
    //             //             timer:1000
    //             //         });
    //             //         $("#proses_item").modal('hide');
    //             //     }
    //             // } else {
    //             //     message = "Gagal";
    //             //     Info = data;
    //             //     type_swal = "error";
    //             //     Swal.fire({
    //             //         title: message,
    //             //         text: info, 
    //             //         type: type_swal,
    //             //         confirmButtonClass: 'btn btn-primary',
    //             //         buttonsStyling: false,
    //             //     });
    //             // }

            
    //         }
    //     })
    // }

    $(document).ready(function () {
        hideHiddenLabel();
        minMaxSpin();
        dataThumbView();

        dt = createDataListView("tbl_history_incoming_detail");
        
        dt_poroses_item = createDataListView("tbl_proses_item");
        dt_poroses_item.order([6,'asc']).draw();
        var datatable = $(".data-list-view").DataTable();
        datatable.order([0,'desc']).draw();
        
        let qty_aktual = "{!! $qty_aktual !!}";
        let qty = "{!! $qty !!}}";
        if(qty_aktual == qty)
        {
            $(".tambah_incoming").prop('disabled', true);
        }

        $(document).on('click', '.paginate_button', function(){
            hideHiddenLabel();
        });

        $('.tbl_proses_item').on('key-focus.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).addClass('selected');
        });

        $('.tbl_proses_item').on('key-blur.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).removeClass('selected');
        });
            
        $('.tbl_proses_item').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                let row_index = cell.index().row;
                let value = $(`.${row_index}`).data('value');
                openModalProsesItem(value.a,value.b,value.c,value.d, value.e, value.f);
            }
        });   

        

        let last_made_incoming_inbound = "{!! $last_inserted_incoming !!}";
        console.log(last_made_incoming_inbound);
        if(last_made_incoming_inbound != "")
        {
            openModalProsesIncoming(last_made_incoming_inbound);
        }

        
    });

</script>
@endsection