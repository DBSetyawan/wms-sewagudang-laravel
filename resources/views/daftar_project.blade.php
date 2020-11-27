@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('extra_sidebar')

@endsection

@section('page_name')
Daftar project
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item active nav-item d-none d-lg-block">Daftar Project
</li>
@endsection

@section('content')
<section id="data-list-view " class="data-list-view-header ">
    <!-- dataTable starts -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table data-thumb-view">
                    <thead>
                        <tr>
                            <th>Kode Project</th>
                            <th>Nama Project</th>
                            <th>Tanggal Project</th>
                            <th>Perusahaan Penyewa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_project as $project)
                        <tr>
                            <td>{{$project->kode_project}}</td>
                            <td>{{$project->nama_project}}</td>
                            <td>{{$project->tanggal_project}}</td>
                            <td>{{$project->nama_perusahaan}}</td>
                            <td><button
                                    onclick="window.location = '{{route('project.edit',['kodeproject'=> $project->kode_project])}}'"
                                    class="btn btn-sm btn-icon btn-warning" title="Edit project"><i
                                        class="fa fa-pencil"></i></button>&nbsp;
                                <button onclick="openModalPeringatanHapusProject('{{$project->kode_project}}')"
                                    class="btn btn-sm btn-icon btn-danger" title="Hapus project"><i
                                        class="fa fa-trash-o"></i></button>&nbsp;
                                <button
                                    onclick="window.location = '{{route('inbound.buattemplateinbound',['kodeproject'=> $project->kode_project])}}'"
                                    class="btn btn-sm btn-icon btn-success" title="Buat template inbound">
                                    <i class="feather icon-file-text"></i></button>&nbsp;
                                <button
                                    onclick="window.location = '{{route('outbound.buattemplateoutbound',['kodeproject'=> $project->kode_project])}}'"
                                    class="btn btn-sm btn-icon btn-danger" title="Buat template outbound">
                                    <i class="feather icon-file-text"></i></button>&nbsp;
                                <button type="button"
                                    onclick=" window.location = '{{route('projecthasgudang.index',['kodeproject'=> $project->kode_project])}}'"
                                    class="btn btn-sm btn-icon btn-success btn_inv_{{$project->kode_project}}"
                                    title="Daftar gudang" {{$project->template_exist}}><i
                                        class="fa fa-home"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<section id="data-list-view " class="data-list-view-header ">
    <div class="mr-1 mb-1 d-inline-block">
        <div class="modal fade " id="hapus_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Peringatan</h5>
                        <label class="kodeproject" style="display:none"></label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="deleteProject()">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection

@section('script_document_ready')
<script>
    function openModalPeringatanHapusProject(argKodeProject) { 
        $(".kodeproject").html(argKodeProject);
        $(".modal-body").html(`Apakah anda yakin ingin menghapus project dengan kode project <b>${argKodeProject}</b> ?`);
        $("#hapus_project").modal('show');
     }

     function deleteProject() { 
         let url= "{!! route('project.delete', ['kodeproject'=>'KODE_PROJECT']) !!}";
         url = url.replace('KODE_PROJECT', $(".kodeproject").html())
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

    function checkTemplateIsExist(argKodeProject) {
        let url = "{!! route('project.checktemplateisexist', ['kodeproject' => 'KODE_PROJECT']) !!}";
        url = url.replace('KODE_PROJECT', argKodeProject);
        $.ajax({
            type: "get",
            url: url,
            headers : {
                'X-CSRF-TOKEN' : "{{ csrf_token() }}"
            },
            success: function (response) {
                console.log(response);
                if(response == "exist")
                {
                    $(".btn_inv_" + argKodeProject).prop('disabled', false);
                }
                else
                {
                    $(".btn_inv_" + argKodeProject).prop('disabled', true);
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
                [2, "desc"]
            ],
            bInfo: false,
            pageLength: 10,
            buttons: [
            {
                text: "Tambah Project",
                className : "tambah_project btn-outline-primary",
                action : function() {
                    window.location.href= "{{route('project.create')}}"
                }
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