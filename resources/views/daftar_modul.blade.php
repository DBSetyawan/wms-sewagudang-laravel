@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Daftar modul
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active">Daftar Modul
</li>
@endsection

@section('content')
<section id="data-thumb-view " class="data-thumb-view-header ">
    <!-- dataTable starts -->
    <div class="table-responsive">
        <table class="table data-thumb-view">
            <thead>
                <tr>
                    <th>Id Modul</th>
                    <th>Nama Modul</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list_modul as $modul)
                <tr>
                    <td>{{$modul->id_modul}}</td>
                    <td>{{$modul->nama_modul}}</td>
                    <td><button onclick="window.location = '{{route('modul.edit',$modul->id_modul)}}'"
                            class="btn btn-sm btn-icon btn-warning" title="Edit modul"><i
                                class="fa fa-pencil"></i></button>
                        &nbsp; <button type="button" class="btn btn-sm btn-icon btn-danger"
                            onclick='openModalPeringatanHapusProject({!!$modul->id_modul!!}, "{!! $modul->nama_modul !!}")'
                            title="Hapus modul"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_modul" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="idmodul" style="display:none"></label>
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
    </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function openModalPeringatanHapusProject(argIdModul, argNamaModul) { 
        $(".idmodul").html(argIdModul);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus modul <b>${argNamaModul}</b> ?`);
        $("#hapus_modul").modal('show');
     }

     function deleteModul() { 
         let url= "{!! route('modul.delete', ['idmodul'=>'ID_MODUL']) !!}";
         url = url.replace('ID_MODUL', $(".idmodul").html())
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

    // function deleteModul(argIdModul) {
    //     let url = "{!! route('modul.delete', ['idmodul' => 'ID_MODUL']) !!}";
    //     url = url.replace("ID_MODUL", argIdModul);
    //     console.log(url);
    //     $.ajax({
    //         type: "get",
    //         url: url,
    //         headers : {
    //             "X-CSRF-TOKEN" : "{{ csrf_token() }}"
    //         },
    //         success: function (response) {
    //             console.log(response);
    //             let message = "";
    //             let info ="";
    //             let type_swal = "";

    //             if(response == "success")
    //             {
    //                 window.location.reload();

    //             }
    //             else
    //             {
    //                 message = "Gagal";
    //                 info = response;
    //                 type_swal = "error";
                    
    //                 Swal.fire({
    //                     title: message,
    //                     text: info,
    //                     type: type_swal,
    //                     confirmButtonClass: 'btn btn-primary',
    //                     buttonsStyling: false,
    //                 });
    //             }

                
    //         }
    //     });
    //  }

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
                    text: "Tambah Modul",
                    action: function () {
                       window.location.href = "{{route('modul.create')}}";
                    },
                    className: "btn-outline-primary"
                }],
                initComplete: function (settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                }
            });

        }

    $(document).ready(function () {
        // testing();
        dataThumbView();
    });
</script>
@endsection