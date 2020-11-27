@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar outbound
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
<li class="breadcrumb-item active">Daftar Outbound
</li>
@endsection

@section('content')
{{-- @if ($errors->any())
<h5>{{json_encode($errors->all())}}</h5>
@endif --}}
<section id="data-thumb-view " class="data-thumb-view-header">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nomor outbound</th>
                    <th>Tanggal Outbound</th>
                    <th>Referensi/No. DO</th>
                    <th>Destination / Tujuan</th>
                    <th>nama_status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_outbound as $outbound)
                <tr>
                    <td class="outbound-{{$loop->index}}">{{$outbound->no_outbound}}</td>
                    <td>{{date('d-m-Y', strtotime($outbound->tanggal_outbound))}}</td>
                    <td>{{$outbound->referensi}}</td>
                    <td>{{$outbound->destination}}</td>
                    <td>
                        <div
                            class="{{$outbound->no_outbound}}-chip chip {{($outbound->nama_status == "Ready") ? "chip-primary" : ""}} {{($outbound->nama_status == "Done") ? "chip-success" : ""}} {{($outbound->nama_status == "Transfer" || $outbound->nama_status == "Loading") ? "chip-info" : ""}} {{($outbound->nama_status == "Incomplete") ? "chip-danger" : ""}} {{($outbound->nama_status == "Complete") ? "chip-info" : ""}} {{($outbound->nama_status == "Picking") ? "chip-info" : ""}} {{($outbound->nama_status == "Cancel") ? "chip-danger" : ""}} {{($outbound->nama_status == "Picked") ? "chip-success" : ""}} mr-1">
                            <div class="chip-body">
                                <span
                                    class="{{$outbound->no_outbound}}-status chip-text">{{$outbound->nama_status}}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="button" onclick="openModalProsesPicking('{{$outbound->no_outbound}}')"
                            class="btn btn-sm btn-icon btn-success btn_proses-{{$outbound->no_outbound}}" title="Proses"
                            {{ ($outbound->nama_status == "Picked" || $outbound->nama_status == "Cancel" || $outbound->nama_status == "Complete"|| $outbound->nama_status == "Incomplete"|| $outbound->nama_status == "Done" || $outbound->nama_status == "Loading") ? "disabled" : ""}}><i
                                class="fa fa-refresh"></i></button>
                        &nbsp;
                        <button
                            onclick="window.location='{{route('outgoing.index', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound' => $outbound->no_outbound])}}'"
                            class="btn btn-sm btn-icon btn-primary btn-outgoing-{{$outbound->no_outbound}}"
                            title="Daftar outgoing outbound"
                            {{ ($outbound->nama_status == "Cancel" || $outbound->nama_status == "Picking"||  $outbound->nama_status == "Ready" || $outbound->nama_status == "Done" || $outbound->nama_status == "Complete"|| $outbound->nama_status == "Incomplete") ? "disabled" : ""}}><i
                                class="fa fa-check-square-o"></i></button>
                        &nbsp;
                        <button
                            onclick="openModalCancelOutbound({{$outbound->id_outbound}}, '{{$outbound->no_outbound}}')"
                            class="btn btn-sm btn-icon btn-danger btn-cancel-{{$outbound->no_outbound}}" title="Cancel"
                            {{ ($outbound->nama_status == "Cancel" || $outbound->nama_status == "Ready" || $outbound->nama_status == "Done" || $outbound->nama_status == "Complete"|| $outbound->nama_status == "Incomplete" || $outbound->nama_status == "Loading" || $outbound->nama_status == "Picking") ? "disabled" : ""}}><i
                                class="fa fa-times"></i></button>
                        &nbsp;
                        <button
                            onclick="window.location = '{{route('outbound.edit', ['kodegudang' => $kodegudang, 'kodeproject'=>$kodeproject, 'nooutbound'=>$outbound->no_outbound])}}'"
                            class="btn btn-sm btn-icon btn-warning btn-edit-{{$outbound->no_outbound}}"
                            title="Edit outbound"
                            {{ ($outbound->nama_status == "Cancel" || $outbound->nama_status == "Ready" || $outbound->nama_status == "Done" || $outbound->nama_status == "Loading" || $outbound->nama_status == "Picking") ? "disabled" : "" }}><i
                                class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button onclick="infoOutbound('{{$outbound->no_outbound}}')"
                            class="btn btn-sm btn-icon btn-info" data-toggle="modal" title="Detail outbound"
                            data-target="#detail_outbound"><i class="fa fa-info"></i></button>
                        &nbsp;
                        <button onclick="openModalPeringatanHapusOutbound('{{$outbound->no_outbound}}')"
                            class="btn btn-sm btn-icon btn-danger btn-delete-{{$outbound->no_outbound}}"
                            title="Hapus outbound"
                            {{ ($outbound->nama_status == "Picked" || $outbound->nama_status == "Cancel" ||  $outbound->nama_status == "Done" || $outbound->nama_status == "Loading" || $outbound->nama_status == "Picking") ? "disabled" : ""}}><i
                                class="fa fa-trash"></i></button>
                        &nbsp;
                        <button href="#" class="btn btn-sm btn-icon btn-danger"
                            onclick="openModalPOD('{{$outbound->no_outbound}}')" title="Proses POD"><i
                                class="fa fa-files-o"></i></button>
                        &nbsp;
                        <button href="#" class="btn btn-sm btn-icon btn-primary"
                            onclick="generateBarcode('{{$outbound->no_outbound}}')" title="Print Barcode"><i
                                class="fa fa-barcode"></i></button>

                        &nbsp;
                        <a href="{{route('outbound.printinvoice', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang, 'nooutbound' => $outbound->no_outbound])}}"
                            class="btn btn-sm btn-icon btn-warning" target="_blank" title="Print Invoice"><i
                                class="fa fa-print"></i></a>
                        &nbsp;
                        <button class="btn btn-sm btn-icon btn-primary" title="Pick Note"
                            onclick="openModalPickNote('{{$outbound->no_outbound}}')"
                            {{ ($outbound->nama_status != "Incomplete") ? "" : "disabled" }}><i
                                class="fa fa-map-marker"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

{{--  --}}
<section id=" " class="">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_outbound" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="nooutbound" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-hapus-outbound"
                            onclick="deleteOutbound()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>

{{--  --}}
<section id="data-list-view" class="data-list-view-header">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="proses_picking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Proses Picking</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table nowrap proses_picking" style="width: 100%">
                                <thead>
                                    <tr class="">
                                        <th style="display:none">iditem</th>
                                        <th>Kode Item</th>
                                        <th>Nama Item</th>
                                        <th>Label</th>
                                        <th>Locator</th>
                                        <th>Status</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="content_detail_inbound">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-thumb-view " class="data-thumb-view-header ">
    <div class="modal-size-xl mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="detail_outbound" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="title">Detail Outbound, No. Outbound : <b
                                class="no_outbound_detail"></b></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="nooutbound">
                        <h5>Detail item</h5>
                        <hr>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table nowrap tbl_detail_outbound">
                                    <thead>
                                        <tr class="header_tbl_detail_outbound">
                                            <th>Kode Item</th>
                                            <th>Nama Item</th>
                                            <th>Label Outbound</th>
                                            <th>Quantity</th>
                                            <th>Quantity Loaded</th>
                                            <th>Satuan</th>
                                            <th>Status</th>
                                            <th>Locator</th>
                                            <th>Label Inventory </th>
                                            {!!$template_inbound->th_detail_inbound!!}
                                        </tr>
                                    </thead>
                                    <tbody class="content_detail_outbound">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <h5 style="margin-left:10px;">History Outbound</h5>
                        <hr>
                        <div class="row">

                            <div class="table-responsive">
                                <table class="table nowrap ">
                                    <thead>
                                        <tr class="">
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Nama User</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbl_history_outbound">

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
                                <button class="btn btn-outline-primary" onclick="saveNoteOutbound()">Simpan
                                    Note</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="" class="">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="cancel_outbound" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label id="idoutbound" style="display:none"></label>
                        <label id="nooutbound" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="cancelOutbound()">Hapus</button>
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
                        <label class="nooutbound_pod" style="display:none"></label>
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
        <div class="modal fade" id="error_import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
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
                            action="{{ route('outbound.importoutbound', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) }}"
                            method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 mb-2">
                                    @if ($errors->any())
                                    <h5>{{json_encode($errors->all())}}</h5>
                                    @endif
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
                        <h5 class="modal-title" id="title">Import Outbound</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form_import_excel"
                            action="{{ route('outbound.importoutbound', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) }}"
                            method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <input type="file" name="excel_outbound" class="upload_excel" accept=".xls">
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
                        <button type="button" class="btn btn-outline-primary">Print</button>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block modal-size-lg">
        <div class="modal fade" id="pickNote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Pick Note - <b class="referensi"></b></h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="hidden_noOutbound">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <th>No.DO / Referensi</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Quantity</th>
                                    <th>Locator</th>
                                    {!!$template_inbound->th_detail_inbound!!}
                                </thead>
                                <tbody class="pickNoteDetail"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary btn-print-picknote">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif
@endsection

@section('script_document_ready')
<script>
    let dt = "";
    let dl ="";
    let dt_outbound_detail = "";

    function saveNoteOutbound() {
        let note = $(".note").val();
        let nooutbound = $(".nooutbound").val();
        let url = "{!! route('outbound.savenoteoutbound',['kodeproject'=> $kodeproject , 'kodegudang'=>$kodegudang, 'nooutbound'=> 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', nooutbound);
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

    function generateBarcode(argNoOutbound)
    {
        let kode_project = "{!! $kodeproject !!}"
        let index= 1;
        let doc = new jsPDF({
            unit : "mm",
            format:[400,400]
        });
        let margin_left_text =  (doc.internal.pageSize.width / 4);
        let margin_top_text = (doc.internal.pageSize.height/4);
        let url =
            "{!! route('outbound.getoutbounddetail', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound' => 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);

        let response = $.ajax({
            type: "get",
            url: url,
            headers:{
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function (response) {
                console.log(response);
                let result =  response;
                
                result['outbound_detail'].forEach(item => {
                    
                    let string = item['label'];
                    JsBarcode("#barcode", string, {
                        format : "CODE128",
                        text: string
                    });

                    let png = document.getElementById("barcode");
                    doc.text(index + "/" + result['outbound_detail'].length ,doc.internal.pageSize.getWidth()/2, 20, 'center' )
                    doc.addImage(png.src, 'png', margin_left_text, margin_top_text, 70,40);
                    doc.text(item['kode_item'] + " - " + item['nama_item'] ,doc.internal.pageSize.getWidth()/2, margin_top_text+60, 'center' )
                    doc.text(item['qty'].toString() + " " + item['nama_uom'], doc.internal.pageSize.getWidth()/2, margin_top_text+70, 'center' )
                    doc.text(kode_project.toString() + " - " + result['project'][0]['nama_project'], doc.internal.pageSize.getWidth()/2, margin_top_text+80, 'center' )
                    if(index < result['outbound_detail'].length)
                    {
                        doc.addPage();
                    }
                    index++;
                });

                doc.save('barcode_outbound_' + argNoOutbound + "_" + moment().format('DD_MM_YYYY')+".pdf");
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
        
        let nooutbound = $(".nooutbound_pod").html();
        let url = "{!! route('outbound.uploadpod', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang, 'nooutbound'=>'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', nooutbound);
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
                console.log(response);
                let result = JSON.parse(response);
                if(result[0] =="success")
                {
                    // refreshPODList(nooutbound);
                    result[1].forEach(file => {
                        let url = "{!! route('outbound.downloadpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'nooutbound'=> 'NO_OUTBOUND', 'idpod' => 'ID_POD']) !!}";
                        url = url.replace('NO_OUTBOUND', file['no_outbound']);
                        url = url.replace('ID_POD', file['id_pod']);
                        $(".list_pod").append(`
                            <tr>
                                <td>${file['nama_file']}</td>
                                <td>${file['waktu_upload']}</td>
                                <td>
                                    <button class="btn btn-success" onclick="window.location.href = '${url}'">Download</button>
                                    &nbsp;
                                    <button class="btn btn-danger" onclick="hapusPOD(${file['id_pod']}, '${file['no_outbound']}')">Hapus</button>
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

    function refreshPODList(argNoOutbound) { 
        let url = "{!! route('outbound.getallpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'nooutbound'=> 'NO_OUTBOUND']) !!}";
        url = url.replace("NO_OUTBOUND", argNoOutbound);
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                let result = JSON.parse(response);
                $(".list_pod").html("");
                result.forEach(file => {
                    let url = "{!! route('outbound.downloadpod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'nooutbound'=> 'NO_OUTBOUND', 'idpod' => 'ID_POD']) !!}";
                    url = url.replace('NO_OUTBOUND', file['no_outbound']);
                    url = url.replace('ID_POD', file['id_pod']);
                    $(".list_pod").append(`
                        <tr>
                            <td>${file['nama_file']}</td>
                            <td>${file['waktu_upload']}</td>
                            <td>
                                <button class="btn btn-success" onclick="window.location.href = '${url}'">Download</button>
                                &nbsp;
                                <button class="btn btn-danger" onclick="hapusPOD(${file['id_pod']}, '${file['no_outbound']}')">Hapus</button>
                            </td>
                        </tr>
                    `);

                    
                });
            }
        });
    }

    function hapusPOD(argIdPOD, argNoOutbound) { 
        let url = "{!! route('outbound.hapuspod', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'nooutbound'=> 'NO_OUTBOUND', 'idpod' => 'ID_POD']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
        url = url.replace('ID_POD',argIdPOD);
        $.ajax({
            type: "get",
            url: url,
            success: function (response) {
                let result=JSON.parse(response);
                if(result =="success")
                {
                    refreshPODList(argNoOutbound);
                }
            }
        });
     }

    function openModalPOD(argNoOutbound)
    {
        $(".nooutbound_pod").html(argNoOutbound);
        $("#upload_pod").modal('show');
        refreshPODList(argNoOutbound);
        $(".upload_pod").val("");
        $(".list_uploaded_file").html("");
    }

    function openModalCancelOutbound(argIdOutbound, argNomorOutbound) { 
        $("#cancel_outbound").modal('show');
        $(".modal-body").html("Apakah anda yakin ingin membatalkan outbound dengan nomor  <b>" + argNomorOutbound + "</b> ?");
        $("#idoutbound").html(argIdOutbound);
        $("#nooutbound").html(argNomorOutbound);
    }

    function cancelOutbound() { 
        let nooutbound = $("#nooutbound").html();
        $.ajax({
            type: "get",
            url: "{!! route('outbound.canceloutbound', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'idoutbound': $("#idoutbound").html()
            },
            dataType: "json",
            success: function (response) {
                let result = response;
                console.log(result);
                if(result['status'] == "success")
                {
                    $("#cancel_outbound").modal('hide');
                    $('.' + nooutbound + "-status").html(result['nama_status']);
                    $(".btn_proses-" + nooutbound).prop('disabled', true);
                    $(".btn-edit-" + nooutbound).prop('disabled', true);
                    $(".btn-delete-" + nooutbound).prop('disabled', true);
                    $(".btn-proses-" + nooutbound).prop('disabled', true);
                    $(".btn-outgoing-" + nooutbound).prop('disabled', true);
                    $(".btn-cancel-" + nooutbound).prop('disabled', true);

                    let list_class_name = $('.' + nooutbound + "-chip").attr('class');
                    
                    list_class_name = list_class_name.split(" ");
                    $("." + nooutbound + "-chip").removeClass(list_class_name[2]);
                    $("." + nooutbound + "-chip").addClass("chip-danger");
                }
            }
        });
     }

   

    function infoOutbound(argNoOutbound) {
        let url =
            "{!! route('outbound.getoutbounddetail', ['kodegudang' => $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound' => 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
        $(".nooutbound").val(argNoOutbound);
        dt_outbound_detail.clear().draw();
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = response;
                console.log(result);
                $(".no_outbound_detail").html(result['outbound_detail'][0]['no_outbound']);
                
                result['outbound_detail'].forEach(item => {
                    console.log(item['label_outbound']);
                    string = `<tr>
                                <td>${item['kode_item']}</td>
                                <td>${item['nama_item']}</td>
                                <td>${item['label_outbound']}</td>
                                <td>${item['qty']}</td>
                                <td>${item['qty_load']}</td>
                                <td>${item['nama_uom']}</td>
                                <td>${item['nama_status']}</td>
                                <td>${item['nama_locator']}</td>
                                <td>${item['label_inventory']}</td>
                                ${item['inbound_custom_field_td']}
                            </tr>`
                    dt_outbound_detail.rows.add($(string)).draw();
                });

                $(".tbl_history_outbound").html("");
                result['history_outbound'].forEach(history => {
                    $(".tbl_history_outbound").append(`
                        <tr>
                            <td>${history['status']}</td>
                            <td>${history['tanggal_update']}</td>
                            <td>${history['nama_user']}</td>
                        </tr>
                    `)
                });

                $(".note").val(result['outbound_detail'][0]['note']);
            }
        });
    }

    function prosesPicking(argIdInventoryDetail, argIdOutboundDetail, argMaxQtyAllocated, argNoOutbound, argAction) {
        // $(".btn-proses-picking").attr('disabled', true);
        let url =
            "{!! route('outbound.pickingproses', ['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject, 'nooutbound'=> 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                'idinventorydetail': argIdInventoryDetail,
                'idoutbounddetail': argIdOutboundDetail,
                'qty': argMaxQtyAllocated,
                'action': argAction
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let title = "";
                let text = "";
                let type_swal = "";
                let timer = 0;
                if (response['status'] == "success") {
                    title = "Berhasil!";
                    text = "Item berhasil diproses";
                    type_swal = "success";
                    timer= 1000;
                    $('.' + argNoOutbound + "-status").html(response['nama_status']['nama_status']);
                    getOutboundDetail(argNoOutbound);

                    let list_class_name = $(`.${argNoOutbound}-chip`).attr('class');
                    list_class_name = list_class_name.split(" ");
                    console.log(list_class_name);
                    if (response['nama_status']['nama_status'] == "Picked") {
                        $("#proses_picking").modal('hide');
                        $(".btn_proses-" + argNoOutbound).prop('disabled', true);
                        $(".btn-edit-" + argNoOutbound).prop('disabled', true);
                        $(".btn-delete-" + argNoOutbound).prop('disabled', true);
                        $(".btn-proses-" + argNoOutbound).prop('disabled', false);
                        $(".btn-outgoing-" + argNoOutbound).prop('disabled', false);
                        $(".btn-cancel-" + argNoOutbound).prop('disabled', false);

                        $("." + argNoOutbound + "-chip").removeClass(list_class_name[2]);
                        $("." + argNoOutbound + "-chip").addClass("chip-primary");
                    }
                    else if(response['nama_status']['nama_status'] == "Picking")
                    {
                        $("." + argNoOutbound + "-chip").removeClass('chip-primary');
                        $("." + argNoOutbound + "-chip").addClass("chip-info");
                        $(".btn-delete-" + argNoOutbound).prop('disabled', true);
                    }
                    else if(response['nama_status']['nama_status'] == "Ready")
                    {
                        $("." + argNoOutbound + "-chip").removeClass('chip-info');
                        $("." + argNoOutbound + "-chip").addClass("chip-primary");
                        $(".btn-delete-" + argNoOutbound).prop('disabled', false);
                    }
                } else {
                    title = "Gagal!";
                    text = response;
                    type_swal = "error";
                    timer = 2500;
                }

                Swal.fire({
                    title: title,
                    text: text,
                    type: type_swal,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                    timer:timer
                });
                $(".btn-proses-picking").attr('disabled', false);
            }
        });
    }

    function openModalPeringatanHapusOutbound(argNoOutbound) {
        $("#hapus_outbound").modal('show');
        $(".modal-body").html(`Apakah anda yakin ingin menghapus <b>${argNoOutbound}</b> ?`);
        $(".nooutbound").html(argNoOutbound);
    }

    function openModalProsesPicking(argNoOutbound) {
        $("#proses_picking").modal('show');
        getOutboundDetail(argNoOutbound);
        setTimeout(() => {
            $("#DataTables_Table_1_filter input").focus(); 
            
        }, 1000);
        
    }

    function getOutboundDetail(argNoOutbound) {
        let url =
            "{!! route('outbound.getoutbounddetail', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound' => 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
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
                dl.clear();
                result['outbound_detail'].forEach(item => {
                    console.log(item);
                    let disable = item['id_status'] == 14 ? "Undo" : "Picked";
                    object = {a:item['id_inventory_detail'], b:item['id_outbound_detail'], c:item['qty'], d:item['no_outbound'], e:disable}
                    string = `<tr class="${idx}">
                                <td style="display:none">${item['id_item']}</td>
                                <td>${item['kode_item']}</td>
                                <td>${item['nama_item']}</td>
                                <td>${item['label_outbound']}</td>
                                <td>${item['nama_locator']}</td>
                                <td>${item['nama_status']}</td>
                                <td>${item['qty']}</td>
                                <td><button class="btn btn-success btn-proses-picking" onclick="prosesPicking(${item['id_inventory_detail']}, ${item['id_outbound_detail']}, '${item['qty']}', '${item['no_outbound']}', '${disable}')" title="Proses">${disable}</button></td>
                            </tr>`
                    dl.rows.add($(string)).draw();
                    $(`.${idx}`).data('value', object);
                    idx++;
                });

                result['history_outbound'].forEach(history => {
                    $(".tbl_history_outbound").append(`
                        <tr>
                            <td>${history['status']}</td>
                            <td>${history['tanggal_update']}</td>    
                            <td>${history['nama_user']}</td>
                        </tr>
                    `)
                });
                dl.cell(':eq(1)').focus();
            }
        });
    }


    function deleteOutbound() {
        $(".btn-hapus-outbound").attr('disabled', true);
        let nooutbound = $(".nooutbound").html();
        let url =
            "{!! route('outbound.delete', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'nooutbound'=>'NO_OUTBOUND']) !!}";
        url = url.replace("NO_OUTBOUND", nooutbound);
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
                    $(".btn-hapus-outbound").attr('disabled', false);
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
                [5, 10, 15, 20],
                [5, 10, 15, 20]
            ],
            order: [
                [0, "desc"]
            ],
            bInfo: false,
            pageLength: 5,
            select: true,
            keys:true,
            buttons: [],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary")
            }
        });

        return object;
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
                [1, "desc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [{
                text: "Buat Outbound Baru",
                action: function () {
                    window.location.href="{{route('outbound.create', ["kodegudang"=> $kodegudang, "kodeproject"=> $kodeproject])}}";
                },
                className: "btn-outline-primary"
            },{
                text: "Transfer",
                action: function () {
                    window.location.href="{{route('outbound.transfer', ["kodegudang"=> $kodegudang, "kodeproject"=> $kodeproject])}}";
                },
                className: "btn-outline-primary button_transfer"
            },{
                text:"Import",
                action:function(){
                    openModalImportExcelInbound();
                },
                className: "btn btn-outline-primary button_export"
            }, {
                text:"Download template outbound",
                action:function(){
                    window.location.href="{{route('outbound.downloadtemplateoutbound', ['kodeproject' => $kodeproject, 'kodegudang'=>$kodegudang])}}"
                },
                className: "btn btn-outline-primary button_export"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
                $(".button_transfer").attr('style', 'margin-left:5px;');
                $(".button_export").attr('style', 'margin-left:5px;');
            }
        });

    }

    $('#picking_qty').on('hidden.bs.modal', function () {
        let nooutbound = $(".nooutbound").val();
        $("#proses_picking").modal('show');
        getOutboundDetail(nooutbound);
    })

    function openModalPickNote(argNoOutbound) {
        let url =
            "{!! route('outbound.getoutbounddetail', ['kodegudang'=> $kodegudang, 'kodeproject' => $kodeproject, 'nooutbound' => 'NO_OUTBOUND']) !!}";
        url = url.replace('NO_OUTBOUND', argNoOutbound);
        $.ajax({
            type: "get",
            url: url,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (response) { 
                let result = response;
                let object = "";
                let idx= 0;
                $(".referensi").html(result['outbound_detail'][0]['referensi']);
                $(".hidden_noOutbound").val(result['outbound_detail'][0]['no_outbound']);
                $(".pickNoteDetail").html("");
                result['outbound_detail'].forEach(item => {
                    $(".pickNoteDetail").append(`
                        <tr>
                            <td>${item['referensi']}</td>
                            <td>${item['kode_item']}</td>
                            <td>${item['nama_item']}</td>
                            <td>${item['qty']}</td>
                            <td>${item['nama_locator']}</td>
                            ${item['inbound_custom_field_td']}
                        </tr>
                    `)
                });           
                $("#pickNote").modal("show");
            }
        });
    }
    
    $(".btn-print-picknote").on('click', function () { 
        let nooutbound = $(".hidden_noOutbound").val();
        let url = "{{route('outbound.printpicknote', ['kodeproject' => $projectGudang[0]->kode_project, 'kodegudang' => $projectGudang[0]->kode_gudang, 'nooutbound' => 'NO_OUTBOUND'])}}";
        url = url.replace('NO_OUTBOUND', nooutbound);
        window.open(url);
     });

    $(document).ready(function () {
        // loadCustomTemplate();
        dt = dataThumbView("data-thumb-view");
        dl = createDataListView("proses_picking");
        
        var datatable = $(".data-list-view").DataTable();
        datatable.order([0,'desc']).draw();

        dt_outbound_detail = createDataListView('tbl_detail_outbound')

        $('.proses_picking').on('key-focus.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).addClass('selected');
        });

        $('.proses_picking').on('key-blur.dt', function(e, datatable, cell){
            $(datatable.row(cell.index().row).node()).removeClass('selected');
        });
            
        $('.proses_picking').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                let row_index = cell.index().row;
                let value = $(`.${row_index}`).data('value');
                prosesPicking(value.a, value.b, value.c, value.d, value.e, value.f);
            }

            
        });    
        if("{!! $errors->any()!!}")
        {
            triggeredToast('{!! json_encode($errors->all()) !!}', 'error');
        }
    })

</script>
@endsection