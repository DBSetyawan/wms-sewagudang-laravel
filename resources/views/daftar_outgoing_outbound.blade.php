@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar outgoing outbound
@endsection

@section('projectGudangInformation')
&nbsp;<b>Project :</b> &nbsp;{{$projectGudang[0]->nama_project}} , &nbsp;<b>Gudang :</b>
&nbsp;{{$projectGudang[0]->nama_gudang}}
@endsection

@section('extra_information')
,&nbsp;<b>No. Outbound : </b>&nbsp; {{$outbound[0]->no_outbound}}, &nbsp;<b>Referensi/No. DO : </b>
&nbsp;{{$outbound[0]->referensi}}
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
        href="{{route('outbound.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Outbound
        ({{$nooutbound}})</a>
</li>
<li class="breadcrumb-item active">Daftar Outgoing Outbound
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Tanggal Outgoing</th>
                    <th>No Outgoing</th>
                    <th>Detail Outgoing</th>
                    <th>User</th>
                    <th>Tanggal Update</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_outgoing as $outgoing_outbound)
                <tr>
                    <td>{{$outgoing_outbound->tanggal}}</td>
                    <td>{{$outgoing_outbound->no_outgoing}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button type="button" onclick="openModal('{{$outgoing_outbound->no_outgoing}}')"
                            class="btn btn-sm btn-icon btn-success btn_open_modal" title="Proses"><i
                                class="fa fa-check-square-o"></i></button>
                        &nbsp;
                        <button
                            onclick="window.location = '{{route('outgoing.edit', ['kodegudang'=>$kodegudang, 'kodeproject'=>$kodeproject, 'nooutbound'=>$nooutbound, 'nooutgoing'=>$outgoing_outbound->no_outgoing])}}'"
                            class="btn btn-sm btn-icon btn-warning"><i class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button onclick="openModalPeringatanHapusOutgoing('{{$outgoing_outbound->no_outgoing}}')"
                            class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="detail_outgoing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Detail Outbound</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table nowrap tbl_detail_outbound" width=100%>
                                <thead>
                                    <tr class="header_tbl_detail_outbound">
                                        <th style="display:none">id_item</th>
                                        <th>Kode Item</th>
                                        <th>Label</th>
                                        <th>Nama Item</th>
                                        <th>Referensi / No. DO</th>
                                        <th>Locator</th>
                                        <th>Quantity</th>
                                        <th>Quantity Load</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="content_detail_outbound">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="form-and-scrolling-components">
    <div class="modal-size-lg mr-1 mb-1 d-inline-block">
        <div class="modal fade text-left" id="proses_picking" tabindex="-1" role="dialog"
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
                        <input type="text" name="" id="" class="idoutbounddetail" style="display: none">
                        <input type="text" name="" id="" class="nooutgoingoutbound" style="display: none">
                        <input type="text" name="" id="" class="iditem" style="display: none">
                        <input type="text" name="" id="" class="qty_max" style="display: none">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="validationTooltip01">Kode Item</label>
                                <input type="text" class="form-control kodeItem" id="validationTooltip01" disabled
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="validationTooltip01">Nama Item</label>
                                <input type="text" class="form-control nama_item" id="validationTooltip01" disabled
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <label>Quantity</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="quantity" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-outline-primary btn-simpan"
                                        onclick="prosesLoading()">Simpan</button>
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
        <div class="modal fade " id="hapus_outgoing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="nooutgoing" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body modal_body_peringatan">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteOutgoing()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    let dt = "";

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

    function openModalPeringatanHapusOutgoing(argNoOutgoing) { 
        $(".nooutgoing").html(argNoOutgoing);
        $(".modal_body_peringatan").html(`Apakah anda yakin ingin menghapus outgoing outbound dengan nomor outgoing <b>${argNoOutgoing}</b> ?`);
        $("#hapus_outgoing").modal('show');
     }

     function deleteOutgoing() {
        let nooutgoing = $(".nooutgoing").html();
        let url =
            "{!! route('outgoing.delete', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound' => $nooutbound ,'nooutgoing'=>'NO_OUTGOING']) !!}";
        url = url.replace("NO_OUTGOING", nooutgoing);
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = JSON.parse(response);
                let title = "";
                let text = "";
                let type_swal = "";

                if (result == "success") {
                    window.location.reload();
                } else {
                    title = "Gagal!";
                    text = response;
                    type_swal = "error";
                    Swal.fire({
                        title: title,
                        text: text,
                        type: type_swal,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }

                
            }
        });
    }

    function openModal(argNoOutgoing) {
        $("#detail_outgoing").modal('show');
        getOutgoingDetail(argNoOutgoing);
        // setTimeout(() => {
        //         $("#DataTables_Table_0_filter input").focus();
        // }, 1000);
    }   

    function enterKeyOnQuantityField(e) {
        if(e.keyCode==13)
        {
            prosesLoading();
        }
    }

    $('#proses_picking').on('hidden.bs.modal', function () {
        $("#detail_outgoing").modal('show');
    })

    $("#detail_outgoing").on('show.bs.modal', function () { 
        
        setTimeout(() => {
                $("#DataTables_Table_0_filter input").focus();
                dt.cell(":eq(1)").focus();
        }, 500);
        
     })

    function openModalProsesLoading(argIdInventoryDetail, argIdOutboundDetail, argNamaItem, argKodeItem, argMaxQty,
        argIdOutgoingOutbound, argIdItem) {
        $(".btn-proses-loading").attr('disabled', false);
        $("#detail_outgoing").modal('hide');
        $("#proses_picking").modal('show');
        $(".idinventorydetail").val(argIdInventoryDetail);
        $(".idoutbounddetail").val(argIdOutboundDetail);
        $(".nama_item").val(argNamaItem);
        $(".kodeItem").val(argKodeItem);
        $(".nooutgoingoutbound").val(argIdOutgoingOutbound);
        $(".iditem").val(argIdItem);
        $(".quantity").trigger("touchspin.updatesettings", {
            max: argMaxQty
        });
        $(".quantity").val(argMaxQty);
        $(".qty_max").val(argMaxQty);
        $(".quantity").focus();
    }

    function prosesLoading() {
        // $(".btn-simpan").attr('disabled', true);
        let idinventorydetail = $(".idinventorydetail").val();
        let idoutbounddetail = $(".idoutbounddetail").val();
        let nooutgoingoutbound = $(".nooutgoingoutbound").val();
        let iditem = $(".iditem").val();
        let qty = $(".quantity").val();
        let url =
            "{!! route('outgoing.prosesloading', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound' => $nooutbound, 'nooutgoing' => 'NO_OUTGOING' ]) !!}";
        url = url.replace('NO_OUTGOING', nooutgoingoutbound);
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                'idinventorydetail': idinventorydetail,
                'idoutbounddetail': idoutbounddetail,
                'nooutgoingoutbound': nooutgoingoutbound,
                'iditem': iditem,
                'qty': qty
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response == "success") {
                    window.location = "{!! route('outbound.index', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject]) !!}"
                    
                } else if(response == "partial loading") {
                    
                    getOutgoingDetail(nooutgoingoutbound);
                    $("#proses_picking").modal('hide');
                    triggeredToast("Item berhasil diloading!");
                    
                }
                else
                {
                    triggeredToast(response, 'error');
                }
                $(".btn-simpan").attr('disabled', false);
            }
        });
    }

    function getOutgoingDetail(argNoOutgoing) {
        $(".btn-simpan").attr('disabled', false);
        let url =
            "{!! route('outgoing.getoutgoingdetail', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound'=>$nooutbound, 'nooutgoing'=> 'NO_OUTGOING']) !!}";
        url = url.replace('NO_OUTGOING', argNoOutgoing);
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) {
                console.log(response);
                let result = response;
                let object = "";
                let idx= 0;
                if (result.length != 0) {
                    dt.clear().draw();
                    result.forEach(item => {
                        let disable = (item['qty'] == item['qty_load']) ? " disabled" : "";
                        object = {a:item['id_inventory_detail'], b:item['id_outbound_detail'], c:item['nama_item'], d:item['kode_item'], e:(item['qty'] - item['qty_load']),f:argNoOutgoing, g:item['id_item']}
                        string = `<tr class="${idx}">
                                <td style="display:none">${item['id_item']}</td>
                                <td>${item['kode_item']}</td>
                                <td>${item['label']}</td>
                                <td>${item['nama_item']}</td>
                                <td>${item['referensi']}</td>
                                <td>${item['nama_locator']}</td>
                                <td>${item['qty']}</td>
                                <td>${item['qty_load']}</td>
                                <td><button class="btn btn-success btn-proses-loading" onclick="openModalProsesLoading(${item['id_inventory_detail']}, ${item['id_outbound_detail']}, '${item['nama_item']}', '${item['kode_item']}',${item['qty'] - item['qty_load']}, '${argNoOutgoing}', ${item['id_item']})" title="ProsesIncoming Inbound" ${disable}>Loading</button></td>
                            </tr>`
                        dt.rows.add($(string)).draw();
                        $(`.${idx}`).data('value', object);
                        idx++;
                    });
                    dt.cell(":eq(1)").focus();
                } else {
                }
            }
        });
    }

    function createDataListView(argNamaClass) {
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
            select : true,
            keys:true,
            buttons: [],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
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
                text: "Buat Outgoing Outbound",
                action: function () {
                    window.location.href="{{route('outgoing.create', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound'=> $nooutbound])}}";
                },
                className: "btn-outline-primary"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
            }
        });

    }

    $(document).ready(function () {
        dt = createDataListView("tbl_detail_outbound");
        minMaxSpin();
        var datatable = $(".data-list-view").DataTable();
        datatable.order([0,'desc']).draw();

        dataThumbView();

        $('.tbl_detail_outbound').on('key-focus.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).addClass('selected');
        });

        $('.tbl_detail_outbound').on('key-blur.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).removeClass('selected');
        });
            
        $('.tbl_detail_outbound').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                let row_index = cell.index().row;
                let value = $(`.${row_index}`).data('value');
                openModalProsesLoading(value.a, value.b, value.c, value.d, value.e, value.f, value.g);
            }
        });   
    })

</script>
@endsection