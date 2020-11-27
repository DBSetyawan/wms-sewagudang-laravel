@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar Locator
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('gudang.index')}}">Gudang ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item active">Daftar Locator
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">

    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nama Locator</th>
                    <th>Type</th>
                    <th>Level</th>
                    <th>Parent</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_locator as $locator)
                <tr>
                    <td>{{$locator->nama_locator}}</td>
                    <td>{{$locator->type_locator}}</td>
                    <th>{{$locator->level}}</th>
                    @if ($locator->nama_parent != null)
                    <th>{{$locator->nama_parent}}</th>
                    @else
                    <th>0</th>
                    @endif
                    @if ($locator->status == 1)
                    <th>Aktif</th>
                    @else
                    <th>Non aktif</th>
                    @endif
                    <td>
                        <button onclick="printBarcode({{$locator->id_locator}}, '{{$locator->nama_locator}}')"
                            class="btn btn-sm btn-icon btn-primary" title="Print Barcode Locator"><i
                                class="fa fa-barcode"></i></button>

                        &nbsp;
                        @if ($locator->nama_locator != "n/a")
                        <button
                            onclick="window.location = '{{route('locator.edit',['kodegudang'=> $kodegudang, 'idlocator'=>$locator->id_locator])}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit locator"><i
                                class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button
                            onclick="openModalPeringatanHapusGudang({{$locator->id_locator}}, '{{$locator->nama_locator}}')"
                            class="btn btn-sm btn-icon btn-danger" title="Hapus locator"><i
                                class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_locator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="idlocator" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteLocator()">Hapus</button>
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
                        <img id="barcode_diconvert_pdf" style="display:none">
                    </div>

                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function printBarcode(argIdLocator, argNamaLocator)
    {
        JsBarcode("#barcode_diconvert_pdf", argIdLocator, {
            format : "CODE128",
            text: argNamaLocator
        });

        let barcode = document.querySelector("#barcode_diconvert_pdf");
        let doc = new jsPDF({
            orientation : 'p',
            unit : 'pt',
            format : [150, 170]
        });
        
        let doc_width = doc.internal.pageSize.getWidth();
        var doc_height = doc.internal.pageSize.getHeight();
        let x = 1;
        let y = 0;
        let y_pdf_text = 50;
        let margin_left_text =  (doc.internal.pageSize.width / 2) - (doc.getStringUnitWidth(argNamaLocator) * doc.internal.getFontSize() / 2) + 5;
        doc.addImage(barcode.src, "PNG", x, y, 150, 150);
        doc.save('barcode_locator_' + argNamaLocator + '.pdf');

    }

    function openModalPeringatanHapusGudang(argIdLocator, argNamaLocator) { 
            $(".idlocator").html(argIdLocator);
            $(".modal-body").html(`Apakah anda yakin ingin menghapus locator <b>${argNamaLocator}</b> ?`);
            $("#hapus_locator").modal('show');
        }

        function deleteLocator() { 
            let url= "{!! route('locator.delete', ['kodegudang' => $kodegudang,'idlocator'=>'ID_LOCATOR']) !!}";
            url = url.replace('ID_LOCATOR', $(".idlocator").html())
            $.ajax({
                type: "get",
                url: url,
                headers : {
                    'X-CSRF-TOKEN' : "{{ csrf_token() }}"
                },
                success: function (response) {
                    let result = JSON.parse(response);
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
                bInfo: false,
                pageLength: 10,
                buttons: [{
                    text: "Tambah Locator",
                    action: function () {
                        $(this).removeClass("btn-secondary");
                        window.location.href = "{!! route('locator.create', $kodegudang) !!}";
                    },
                    className: "btn-outline-primary"
                }],
                initComplete: function (settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                }
            });

        }

        $(document).ready(function () {
            dataThumbView();

        
        });
</script>
@endsection