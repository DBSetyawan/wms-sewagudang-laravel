
@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar Role
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Role
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Id Role</th>
                    <th>Nama Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_role as $role)
                <tr>
                    <td>{{$role->id_role}}</td>
                    <td>{{$role->nama_role}}</td>
                    <td>
                        <button onclick="window.location = '{{route('role.edit', ['idrole'=>$role->id_role])}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit role"><i
                                class="fa fa-pencil"></i></button>
                        <button onclick="openModal({{$role->id_role}},'{{$role->nama_role}}')"
                            class="btn btn-sm btn-icon btn-danger" title="Hapus role"><i
                                class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label id="idrole" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteModul()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function openModal(argIdRole, argNamaRole) {
        $("#hapus_role").modal('show');
        $(".modal-body").html("Apakah anda yakin ingin menghapus <b>" + argNamaRole + "</b>  dari daftar role ?");
        $("#idrole").html(argIdRole);
     }
    function deleteModul() {
        let idrole = $("#idrole").html();
        let url = "{!! route('role.delete', ['idrole' => 'ID_ROLE']) !!}";
        url = url.replace("ID_ROLE", idrole);
        $.ajax({
            type: "get",
            url: url,
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function (response) {

                let message = "";
                let info ="";
                let type_swal = "";

                if(response == "success")
                {
                    window.location.reload();

                }
                else
                {
                    message = "Gagal";
                    info = response;
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
                text: "Tambah Role",
                action: function () {
                   window.location.href="{{route('role.create')}}";
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