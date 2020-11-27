@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar user
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Daftar User
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="content_user_list">
                @foreach ($list_user as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <div
                            class="chip {{($user->nama_role == "Admin") ? "chip-primary" : ""}} {{($user->nama_role == "Operator") ? "chip-warning" : ""}} {{($user->nama_role == "Customer") ? "chip-danger" : ""}} mr-1">
                            <div class="chip-body">
                                <span class="chip-text">{{$user->nama_role}}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button title="Edit user" onclick="window.location = '{!! route('user.edit', $user->id) !!}'"
                            class="btn btn-sm btn-icon btn-warning"><i class="fa fa-pencil"></i></button>
                        &nbsp;
                        <button title="Hapus user" onclick="openModal('{{$user->name}}', {{$user->id}})"
                            class="btn btn-sm btn-icon btn-danger"><i class="fa fa-trash-o"></i></button>
                        &nbsp;
                        <button title="Resend link verifikasi password" class="btn btn-sm btn-icon btn-primary"
                            onclick="resendLinkVerifikasi({{$user->id}})"
                            {{($user->set_password_token == "") ? "disabled" : ""}}><i
                                class="fa fa-paper-plane-o"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="iduser" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteUser()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function openModal(argNamaUser, argIdUser) {
        $("#hapus_user").modal('show');
        $(".iduser").html(argIdUser);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus <b>${argNamaUser}</b> ?`);
     }

    function resendLinkVerifikasi(argIdUser) {
        $(".background-spinner").css("display", 'inline');
        $(".text-center").css("display",'inherit');
        $.ajax({
            type: "post",
            url: "{!! route('user.resendlink') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{csrf_token()}}"
            },
            data: {
                'id_user' : argIdUser
            },
            dataType: "json",
            success: function (response) {
                $(".background-spinner").css("display", 'none');
                $(".text-center").css("display",'none');
                if(response == "success")
                {
                    Swal.fire({
                        title : "Berhasil!",
                        text : "Link berhasil dikirim. Silahkan cek email anda!",
                        type: "success"
                    });
                }
                else
                {
                    Swal.fire({
                        title : "Gagal!",
                        text : response,
                        type: "error"
                    });
                }
            }
        });
    }

    function deleteUser()
    {
        let iduser = $(".iduser").html();
        let url = "{!! route('user.delete', ['id'=>'ID_USER']) !!}";
        url = url.replace('ID_USER', iduser);
        $.ajax({
            type: "get",
            url: url,
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = JSON.parse(response);
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
                text: "Tambah User",
                action: function () {
                    window.location.href="{{route('user.create')}}"
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