@extends('layout.master')
@extends('layout.sidebar')
@extends('layout.navbar')

@section('page_name')
Daftar Project Gudang
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item active">Daftar Project Gudang
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Kode Gudang</th>
                    <th>Nama Gudang</th>
                    <th>Alamat Gudang</th>
                    <th>Kabupaten</th>
                    <th>Provinsi</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($list_gudang) !=0)
                @foreach ($list_gudang as $gudang)
                <tr>
                    <td>{{$gudang->kode_gudang}}</td>
                    <td>{{$gudang->nama_gudang}}</td>
                    <td>{{$gudang->alamat_gudang}}</td>
                    <td>{{$gudang->nama_kabupaten}}</td>
                    <td>{{$gudang->nama_provinsi}}</td>
                    <td><button
                            onclick="window.location = '{{route('projecthasgudang.edit',['kodeproject'=> $kodeproject, 'kodegudang' => $gudang->kode_gudang])}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit project gudang"><i
                                class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button onclick="openModalHapusProjectGudang('{!! $gudang->kode_gudang !!}')"
                            class="btn btn-sm btn-icon btn-danger" title="Hapus project gudang"><i
                                class="fa fa-trash-o"></i></button>
                        &nbsp;
                        <button
                            onclick="window.location = '{{route('inventory.index',['kodegudang' => $gudang->kode_gudang, 'kodeproject' => $kodeproject])}}'"
                            class="btn btn-sm btn-icon btn-success" title="Inventory"><i
                                class="feather icon-package"></i></button>
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
                        <button type="button" class="btn btn-danger" onclick="hapusProjectGudang()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function openModalHapusProjectGudang(argKodeGudang) { 
        $(".kodegudang").html(argKodeGudang);
        $(".modal-body").html(`Apakah yakin ingin menghapus gudang <b>${argKodeGudang}</b> dari project <b>${"{!! $kodeproject !!}"}</b> ?`)
        $("#hapus_gudang").modal('show');
    }

    function hapusProjectGudang() { 
        let url = "{!!route('projecthasgudang.delete',['kodeproject'=> $kodeproject, 'kodegudang' => 'KODE_GUDANG']) !!}";
        url = url.replace('KODE_GUDANG', $(".kodegudang").html())
        $.ajax({
            type: "get",
            url: url,
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
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
                text: "Tambah Project Gudang",
                action: function () {
                    window.location.href="{{route('projecthasgudang.create', ['kodeproject'=>$kodeproject])}}";
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