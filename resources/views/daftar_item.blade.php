@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')
@extends('layout.extra_sidebar')

@section('page_name')
Daftar item
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
<li class="breadcrumb-item active">Daftar Incoming Item
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">

    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>cbm (m3)</th>
                    <th>Berat Bersih (kg)</th>
                    <th>Berat Kotor (kg)</th>
                    <th>UOM</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>{{$item->kode_item}}</td>
                    <td>{{$item->nama_item}}</td>
                    <td>{{$item->cbm}}</td>
                    <td>{{$item->berat_bersih}}</td>
                    <td>{{$item->berat_kotor}}</td>
                    <td>{{$item->nama_uom}}</td>
                    <td>
                        <button class="btn btn-icon btn-small btn-warning"
                            onclick="window.location='{{route('item.edit', ['kodegudang'=>$kodegudang, 'kodeproject'=> $kodeproject, 'kodeitem'=> $item->kode_item])}}'"
                            title="Edit item"><i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-icon btn-small btn-danger"
                            onclick="openModalPeringatanHapusItem('{{$item->kode_item}}')" title="Hapus item"><i
                                class="fa fa-trash-o"></i>
                        </button>
                        <button class="btn btn-icon btn-small btn-primary"
                            onclick="generateBarcode('{{$item->kode_item}}', '{{$item->nama_item}}')"
                            title="Print barcode"><i class="fa fa-barcode"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-thumb-view " class="data-thumb-view-header">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="kodeitem" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteitem()">Hapus</button>
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
                        <button type="button" class="btn btn-outline-primary"
                            onclick="openModalSelectCountToBePrint()">Print</button>
                    </div>
                </div>
            </div>
        </div>
</section>

<section id="data-thumb-view" class="data-thumb-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="jumlah_print" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Jumlah yang diprint</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body" style="text-align: center">
                        <input type="number" name="" id="jumlah_diprint" class="form-control" value="1"
                            style="display:block">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="printBarcode()">Print</button>
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
                            action="{{ route('item.import', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) }}"
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
        </div>
    </div>
</section>

@endsection

@section('script_document_ready')
<script>
    $("#jumlah_print").on('hidden.bs.modal', function(){
        $("#barcode_container").modal('show');
    })
    function openModalSelectCountToBePrint() { 
        $("#jumlah_print").modal('show');
        $("#barcode_container").modal('hide');
     }
    function printBarcode()
    {
       let jumlah_pdiprint = $("#jumlah_diprint").val();
        if(jumlah_pdiprint > 0)
        {
            let kode_item = $("#kodeitembarcode").val();
            let nama_item = $("#namaitembarcode").val();

            JsBarcode("#barcode_diconvert_pdf", kode_item, {
                format : "CODE128",
                text: kode_item
            });

            let barcode = document.querySelector("#barcode_diconvert_pdf");
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
            let margin_left_text =  (doc.internal.pageSize.width / 2) - (doc.getStringUnitWidth(nama_item) * doc.internal.getFontSize() / 2) + 5;
            for(let i =0; i< jumlah_pdiprint; i++)
            {
                doc.text(nama_item, doc.internal.pageSize.getWidth()/2, 120, "center");
                doc.addImage(barcode.src, "PNG", x, y, 150, 100, i+1);
                if(i+1 < jumlah_pdiprint)
                {
                    doc.addPage();
                }
            }
            doc.save('barcode.pdf');
        }
        else
        {
            Swal.fire({
                title: "Gagal!",
                text: "Jumlah yang ingin diprint minimal 1",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }

    }

    function generateBarcode(argKodeItem, argNamaItem)
    {
        let string = argKodeItem + " - " + argNamaItem;
        $("#barcode_container").modal('show');
        $("#kodeitembarcode").val(argKodeItem);
        $("#namaitembarcode").val(argNamaItem);
        JsBarcode("#barcode", argKodeItem, {
            format : "CODE128",
            text: string
        });
    }
    function openModalPeringatanHapusItem(argKodeItem) { 
        $(".modal-body").html(`Apakah anda yakin ingin menghapus item dengan kode <b>${argKodeItem}</b> ?`);
        $(".kodeitem").html(argKodeItem);
        $("#hapus_item").modal('show');
    }
    function deleteitem() { 
        let url= "{!! route('item.delete', ['kodeproject'=>$kodeproject, 'kodegudang' => $kodegudang, 'kodeitem'=>'KODE_ITEM']) !!}";
        url = url.replace('KODE_ITEM', $(".kodeitem").html())
        $.ajax({
            type: "get",
            url: url,
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            success: function (response) {
            let result = JSON.parse(response)
            if(result == 'success')
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

    function openModalImportExcelInbound() { 
        $("#import_excel").modal('show');
        $(".upload_excel").val("");
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
                text: "Tambah Item",
                action: function () {
                    window.location.href="{{route('item.create', ['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject ])}}";
                },
                className: "btn btn-outline-primary"
            },
            {
                text: "Download template",
                action: function () {
                    window.location.href="{{route('item.export', ['kodegudang'=>$kodegudang, 'kodeproject' => $kodeproject ])}}";
                },
                className: "btn btn-outline-primary btn_export"
            },
            {
                text: "Import item",
                action: function () {
                    openModalImportExcelInbound();
                },
                className: "btn btn-outline-primary btn_import"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
                $(".btn_export").attr('style', 'margin-left:5px; margin-right:5px')
            }
        });

    }

    $(document).ready(function () {
        dataThumbView();
    });
</script>
@endsection