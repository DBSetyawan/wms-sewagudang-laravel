@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar perusahaan
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Daftar Perusahaan
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>Email</th>
                    <th>NPWP</th>
                    <th>KTP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_perusahaan as $perusahaan)
                <tr>
                    <td>{{$perusahaan->nama_perusahaan}}</td>
                    <td>{{$perusahaan->alamat_perusahaan}}</td>
                    <td>{{$perusahaan->telepon}}</td>
                    <td>{{$perusahaan->email_perusahaan}}</td>
                    <td>{{$perusahaan->NPWP}}</td>
                    <td>{{$perusahaan->KTP}}</td>
                    <td><button onclick="window.location = '{{route('perusahaan.edit',$perusahaan->id_perusahaan)}}'"
                            class="btn btn-sm btn-icon btn-warning"><i class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button onclick="openModal('{{$perusahaan->nama_perusahaan}}', {{$perusahaan->id_perusahaan}})"
                            class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash"></i></button></td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_perusahaan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="idperusahaan" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deletePerusahaan()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
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
                text: "Tambah Perusahaan",
                action: function () {
                    window.location.href="{{route('perusahaan.create')}}";
                },
                className: "btn-outline-primary"
            }],
            initComplete: function (settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
            }
        });

    }

    function openModal(argNamaPerusahaan, argIdPerusahaan) {
        $("#hapus_perusahaan").modal('show');
        $(".idperusahaan").html(argIdPerusahaan);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus <b>${argNamaPerusahaan}</b> ?`);
     }

    function deletePerusahaan()
    {
        let id_perusahaan = $(".idperusahaan").html();
        let url = "{!! route('perusahaan.delete', ['idperusahaan'=>'ID_PERUSAHAAN']) !!}";
        url = url.replace('ID_PERUSAHAAN', id_perusahaan);
        $.ajax({
            type: "get",
            url: url,
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = response;
                let title = "";
                let text = "";
                let type_swal ="";
                if(result == "success")
                {
                    window.location.reload();
                    // title = "Berhasil!";
                    // text = "User berhasil dihapus";
                    // type_swal = "success";
                    // refreshUserList();
                }
                else
                {
                    title = "Gagal!";
                    text = response
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

        $(document).ready(function () {
            dataThumbView();
        });
</script>
@endsection