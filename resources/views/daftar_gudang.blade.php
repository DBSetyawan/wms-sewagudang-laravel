@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar Gudang
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Gudang
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view table_daftar_gudang">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Gudang</th>
                    <th>Nama Gudang</th>
                    <th>Alamat Gudang</th>
                    {{-- <th>Kecamatan</th> --}}
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($list_gudang) !=0)
                @foreach ($list_gudang as $gudang)
                <tr>
                    <td>{{$gudang->id_gudang}}</td>
                    <td>{{$gudang->kode_gudang}}</td>
                    <td>{{$gudang->nama_gudang}}</td>
                    <td>{{$gudang->alamat_gudang}}</td>
                    {{-- <td>{{$gudang->nama_kecamatan}}</td> --}}
                    <td>{{$gudang->nama_kabupaten}}</td>
                    <td>{{$gudang->nama_provinsi}}</td>
                    <td>
                        <button onclick="window.location = '{{route('gudang.edit',$gudang->kode_gudang)}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit gudang"><i
                                class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button onclick="openModalPeringatanHapusGudang('{{$gudang->kode_gudang}}')"
                            class="btn btn-sm btn-icon btn-danger" title="Hapus gudang"><i
                                class="fa fa-trash"></i></button>
                        
                        &nbsp;
                        <button onclick="window.location = '{{route('locator.index',$gudang->kode_gudang)}}'"
                            class="btn btn-sm btn-icon btn-success" title="Daftar Locator"><i
                                class="feather icon-map-pin"></i></button>

                        
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_gudang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="kodegudang" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteGudang()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    var dt = "";
    function openModalPeringatanHapusGudang(argKodeGudang) { 
        $(".kodegudang").html(argKodeGudang);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus gudang dengan kode gudang <b>${argKodeGudang}</b> ?`);
        $("#hapus_gudang").modal('show');
     }

     function deleteGudang() { 
         let url= "{!! route('gudang.destroy', ['kodegudang'=>'kodegudang']) !!}";
         url = url.replace('kodegudang', $(".kodegudang").html())
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
            order: [
                [0, "desc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [{
                text: "Tambah Gudang",
                action: function () {
                   window.location.href = "{{route('gudang.create')}}"
                },
                className: "btn-outline-primary"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
            }
        });

    }

    $(document).ready(function () {
        // var datatable = $(".data-list-view").DataTable();
        // datatable.order([0,'desc']).draw();
        dataThumbView();
    });
</script>
@endsection