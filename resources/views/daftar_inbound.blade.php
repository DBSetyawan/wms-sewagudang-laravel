@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar inbound
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
        href="{{route('inventory.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject])}}">Inventory</a>
</li>
<li class="breadcrumb-item active">Daftar inbound
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    @if (in_array("Inbound Add", array_column(Auth::user()->role->hakAksesModul->toArray(),
    'nama_modul')))

    @endif
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nomor Inbound</th>
                    <th>tgl_inbound</th>
                    <th>Asal</th>
                    <th>nama_status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_inbound as $inbound)
                <tr>
                    <td class="noinbound-{{$loop->index}}">{{$inbound->no_inbound}}</td>
                    <td>{{date('d-m-Y', strtotime($inbound->tanggal_inbound))}}</td>
                    <td>{{$inbound->origin}}</td>
                    <td>
                        <div
                            class="chip {{($inbound->nama_status == "Ready") ? "chip-danger" : ""}} {{($inbound->nama_status == "Done") ? "chip-success" : ""}} {{($inbound->nama_status == "Transfer" || $inbound->nama_status == "Complete") ? "chip-info" : ""}} {{($inbound->nama_status == "Incomplete") ? "chip-warning" : ""}} mr-1">
                            <div class="chip-body">
                                <span class="chip-text">{{$inbound->nama_status}}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button
                            onclick="window.location='{{route('incoming.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'noinbound' => $inbound->no_inbound])}}'"
                            class="btn btn-sm btn-icon btn-success" title="Daftar incoming inbound"
                            {{ ($inbound->nama_status == "Done" || $inbound->nama_status == "In Receipt") ? "disabled" : ""}}><i
                                class="fa fa-check-square-o"></i></button>
                        &nbsp;

                        <button
                            onclick="window.location = '{{route('inbound.edit', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'noinbound'=> $inbound->no_inbound])}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit inbound"
                            {{ ($inbound->nama_status == "Done" || $inbound->nama_status == "Complete") ? "disabled" : ""}}><i
                                class="fa fa-pencil"></i></button>
                        &nbsp;

                        <button onclick="openModalPeringatanHapusInbound('{{$inbound->no_inbound}}')"
                            class="btn btn-sm btn-icon btn-danger" title="Hapus inbound"
                            {{ ($inbound->nama_status != "In Receipt") ? "disabled" : ""}}><i
                                class="fa fa-trash-o"></i></button>
                        &nbsp;

                        <button href="#" class="btn btn-sm btn-icon btn-info" data-toggle="modal"
                            data-target="#detail_inbound" onclick="getIndex({{$loop->index}})" title="Detail inbound"><i
                                class="fa fa-info"></i></button>

                        &nbsp;
                        <button href="#" class="btn btn-sm btn-icon btn-danger"
                            onclick="openModalPOD('{{$inbound->no_inbound}}')" title="Proses POD"><i
                                class="fa fa-files-o"></i></button>

                        &nbsp;
                        <button href="#" class="btn btn-sm btn-icon btn-primary"
                            onclick="generateBarcode('{{$inbound->no_inbound}}')" title="Print Barcode"><i
                                class="fa fa-barcode"></i></button>
                        &nbsp;
                        <a href="{{route('inbound.printinvoice', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang, 'noinbound' => $inbound->no_inbound])}}"
                            class="btn btn-sm btn-icon btn-warning" target="_blank" title="Print Invoice"><i
                                class="fa fa-print"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{--  --}}
<section id="data-list-view " class="data-list-view-header ">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="detail_inbound" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Detail Inbound, No. Inbound : <b
                                class="no_inbound_detail"></b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Detail Item</h5>
                        <hr>
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table tbl_detail_inbound">
                                    <thead>
                                        <tr class="header_tbl_detail_inbound">
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Actual Weight</th>
                                            <th>Actual CBM</th>
                                            <th>Quantity</th>
                                            <th>Quantity Aktual</th>
                                            <th>Label</th>
                                            {!! $template->th_detail_inbound !!}
                                        </tr>
                                    </thead>
                                    <tbody class="content_detail_inbound">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>History Inbound</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="">
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Nama User </th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbl_history_inbound">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>Note</h5>
                                <hr>
                                <div class="form-group">
                                    <textarea name="" class="note" style="width: 100%; height:200px;"></textarea>
                                </div>
                                <button class="btn btn-outline-primary" onclick="saveNoteInbound()">Simpan Note</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_inbound" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="noinbound" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="hapusInbound()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block modal-size-lg">
        <div class="modal fade" id="upload_pod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Proses POD</h5>
                        <label class="noinbound_pod" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form_upload_pod" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <input type="file" multiple name="pod[]" class="upload_pod"
                                        accept="application/pdf">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group list_uploaded_file">
                                        {{-- <label for=""><b>Daftar upload file</b></label> --}}

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" onclick="">Upload</button>
                                </div>
                            </div>
                        </form>
                        <hr>

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table nowrap " style="width: 100%">
                                    <thead>
                                        <tr class="">
                                            <th>Nama File</th>
                                            <th>Waktu Upload</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list_pod">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block modal-size-lg">
        <div class="modal fade" id="import_excel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Import Inbound</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form_import_excel"
                            action="{{ route('inbound.uploadexcelinbound', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) }}"
                            method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input type="file" name="excel_inbound" class="upload_excel" accept=".xls">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" onclick="">Upload</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block modal-size-lg">
        <div class="modal fade" id="import_excel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Import Inbound</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                        <img id="barcode_diconvert_pdf" style="display:none">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary"
                            onclick="openModalSelectCountToBePrint()">Print</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    var dt = "";
    let idx = "";

    function saveNoteInbound() {
        let note = $(".note").val();
        let noinbound = $(".noinbound-" + idx).html();
        let url = "{!! route('inbound.savenoteinbound',['kodeproject'=> $kodeproject , 'kodegudang'=>$kodegudang, 'noinbound'=> 'NO_INBOUND']) !!}";
        url = url.replace('NO_INBOUND', noinbound);
        $.ajax({
            type: "get",
            url: url,
            data: {
                'note' : note
            },
            dataType: "json",
            success: function (response) {
                
                if(response == "success")
                {
                    Swal.fire({
                        title : "Berhasil!",
                        type : "success",
                        text : "Note berhasil disimpan!"
                    });
                }
            }
        });
    }

    function generateBarcode(argNoInbound)
    {
        let kode_project = "{!! $kodeproject !!}"
        let index= 1;
        let doc = new jsPDF({
            unit : "mm",
            format:[400,400]
        });
        let margin_left_text =  (doc.internal.pageSize.width / 4);
        let margin_top_text = (doc.internal.pageSize.height/4);
        let url = `{!! route('inbound.getdetailinbound', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject, 'noinbound'=> 'NO_INBOUND']) !!}`;
        url = url.replace('NO_INBOUND', argNoInbound);

        let response = $.ajax({
            type: "get",
            url: url,
            headers:{
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function (response) {
                console.log(response);
                let result = response;
                
                result['inbound_detail'].forEach(item => {
                    
                    let string = item['label'];
                    JsBarcode("#barcode", string, {
                        format : "CODE128",
                        text: string
                    });

                    let png = document.getElementById("barcode");
                    doc.text(index + "/" + result['inbound_detail'].length ,doc.internal.pageSize.getWidth()/2, 20, 'center' )
                    doc.addImage(png.src, 'png', margin_left_text, margin_top_text, 70,40);
                    doc.text(item['kode_item'] + " - " + item['nama_item'] ,doc.internal.pageSize.getWidth()/2, margin_top_text+60, 'center' )
                    doc.text(item['qty'].toString() + " " + item['nama_uom'], doc.internal.pageSize.getWidth()/2, margin_top_text+70, 'center' )
                    doc.text(kode_project.toString() + " - " + result['project'][0]['nama_project'], doc.internal.pageSize.getWidth()/2, margin_top_text+80, 'center' )
                    if(index < result['inbound_detail'].length)
                    {
                        doc.addPage();
                    }
                    index++;
                });

            doc.save('barcode_inbound_' + argNoInbound + "_" + moment().format('DD_MM_YYYY')+".pdf");
            }
        });
        
    }

    $(".upload_pod").change(function(){
        $(".list_uploaded_file").html("");
        let list_file=$(".upload_pod")[0].files;
        let i =0;
        let list_file_length = list_file.length;
        for(i; i < list_file_length; i++)
        {
            $(".list_uploaded_file").append(`<li> ${list_file[i].name} </li>`);
        }
    });

    $(".form_upload_pod").on('submit', function(e){
        e.preventDefault();
        let form_data = new FormData(this);
        
        let noinbound = $(".noinbound_pod").html();
        let url = "{!! route('inbound.uploadpod', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang, 'noinbound'=>'NO_INBOUND']) !!}";
        url = url.replace('NO_INBOUND', noinbound);
        $.ajax({
            type: "post",
            headers: {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            url: url,
            data: form_data,
            contentType: false, // The content type used when sending data to the server.
            cache: false, // To unable request pages to be cached
            processData: false,
            success: function (response) {
                let result = JSON.parse(response);
                $(".list_uploaded_file").html("");
                if(result[0] =="success")
                {
                    result[1].forEach(file => {
                        let url = "{!! route('inbound.downloadpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> 'NO_INBOUND', 'idpod' => 'ID_POD']) !!}";
                        url = url.replace('NO_INBOUND', file['no_inbound']);
                        url = url.replace('ID_POD', file['id_pod']);
                        $(".list_pod").append(`
                            <tr>
                                <td>${file['nama_file']}</td>
                                <td>${file['waktu_upload']}</td>
                                <td>
                                    <button class="btn btn-success" onclick="window.location.href = '${url}'">Download</button>
                                    &nbsp;
                                    <button class="btn btn-danger" onclick="hapusPOD(${file['id_pod']}, '${file['no_inbound']}')">Hapus</button>
                                </td>
                            </tr>
                        `);
                    });
                    $(".upload_pod").val("");
                    $(".list_uploaded_file").html("");
                }
            }
        });
    })

   

    function openModalImportExcelInbound() { 
        $("#import_excel").modal('show');
        $(".upload_excel").val("");
     }

    function refreshPODList(argNoInbound, argPodList) { 
        let url = "{!! route('inbound.getallpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> 'NO_INBOUND']) !!}";
        url = url.replace("NO_INBOUND", argNoInbound);
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                let result = JSON.parse(response);
                $(".list_pod").html("");
                result.forEach(file => {
                    let url = "{!! route('inbound.downloadpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> 'NO_INBOUND', 'idpod' => 'ID_POD']) !!}";
                    url = url.replace('NO_INBOUND', file['no_inbound']);
                    url = url.replace('ID_POD', file['id_pod']);
                    $(".list_pod").append(`
                        <tr>
                            <td>${file['nama_file']}</td>
                            <td>${file['waktu_upload']}</td>
                            <td>
                                <button class="btn btn-success" onclick="window.location.href = '${url}'">Download</button>
                                &nbsp;
                                <button class="btn btn-danger" onclick="hapusPOD(${file['id_pod']}, '${file['no_inbound']}')">Hapus</button>
                            </td>
                        </tr>
                    `);
                    
                });
            }
        });
    }

    function hapusPOD(argIdPOD, argNoInbound) { 
        let url = "{!! route('inbound.hapuspod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> 'NO_INBOUND', 'idpod' => 'ID_POD']) !!}";
        url = url.replace('NO_INBOUND', argNoInbound);
        url = url.replace('ID_POD',argIdPOD);
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                let result=JSON.parse(response);
                if(result =="success")
                {
                    refreshPODList(argNoInbound);
                }
            }
        });
     }

    function openModalPOD(argNoInbound)
    {
        $(".noinbound_pod").html(argNoInbound);
        $("#upload_pod").modal('show');
        refreshPODList(argNoInbound);
        $(".upload_pod").val("");
        $(".list_uploaded_file").html("");
    }

    function openModalPeringatanHapusInbound(argNoInbound) { 
        $(".modal-body").html(`Apakah anda yakin ingin menghapus inbound dengan kode <b>${argNoInbound}</b> ?`);
        $(".noinbound").html(argNoInbound);
        $('#hapus_inbound').modal('show');
     }

     function hapusInbound() { 
         let url = "{!! route('inbound.delete', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang, 'noinbound'=> 'NO_INBOUND']) !!}";
         url = url.replace('NO_INBOUND', $(".noinbound").html());
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

            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
    }

    function getDetailInbound(argNoInbound, argDataTable) {
        let url = `{!! route('inbound.getdetailinbound', ['kodegudang'=> $kodegudang, 'kodeproject'=> $kodeproject, 'noinbound'=> 'NO_INBOUND']) !!}`;
        url = url.replace('NO_INBOUND', argNoInbound);
        let response = $.ajax({
            type: "get",
            url: url,
            headers:{
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = response;
                console.log(response);
                $(".no_inbound_detail").html(result['inbound_detail'][0]['no_inbound'])
                let string = "";
                dt.clear();
                result['inbound_detail'].forEach(item => {
                    string = `<tr>
                                <td>${item['kode_item']}</td>
                                <td>${item['nama_item']}</td>
                                <td>${item['berat_bersih']}</td>
                                <td>${item['cbm']}</td>
                                <td>${item['qty']}</td>
                                <td>${item['qty_aktual']}</td>
                                <td>${item['label']}</td>
                                ${item['custom_field_td']}
                            </tr>`
                    dt.rows.add($(string)).draw();
                });
                $(".tbl_history_inbound").html('');
                result['history'].forEach(element => {
                    $(".tbl_history_inbound").append(`
                        <tr>
                            <td>${element['status']}</td>
                            <td>${element['tanggal_update']}</td>
                            <td>${element['nama_user']}</td>
                        </tr>
                    `)
                });

                $(".note").val(result['inbound_detail'][0]['note']);
                hideHiddenLabel();
            }
        });
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
                text: "Tambah Inbound",
                action: function () {
                    window.location.href="{{route('inbound.create', ["kodegudang"=> $kodegudang, "kodeproject"=> $kodeproject])}}";
                },
                className: "btn btn-outline-primary button_tambah_inbound"
            }, {
                text : "Import Inbound",
                action : function() {
                    openModalImportExcelInbound();
                },
                className: "btn btn-outline-primary btn_import_inbound"
            },  {
                text : "Download template inbound",
                action : function() {
                    window.location.href = "{!! route('inbound.downloadtemplateexcelinbound', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) !!}"
                },
                className: "btn btn-outline-primary"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
                $(".btn_import_inbound").attr('style', 'margin-left:5px; margin-right:5px')
                let enabled = "{!! in_array('Inbound Add', array_column(Auth::user()->role->hakAksesModul->toArray(),'nama_modul')) !!}";
                if(enabled != 1)
                {
                    $(".button_tambah_inbound").attr('style','display:none');
                }
            }
        });

    }

    function getIndex(argIndex)
    {
        idx = argIndex;
    }

    $("#detail_inbound").on("show.bs.modal", function(){
        let noinbound = $(".noinbound-" + idx).html();
        getDetailInbound(noinbound,dt);
    });

    $(document).ready(function(){
        sessionStorage.removeItem('testing');

        var datatable = $(".data-list-view").DataTable();
        datatable.order([0,'desc']).draw();
        dt = createDataListView('tbl_detail_inbound');
        dataThumbView();

    });

    if("{!! $errors->any()!!}")
    {
        triggeredToast('{!! json_encode($errors->all()) !!}', 'error');
    }
</script>
@endsection