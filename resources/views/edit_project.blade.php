@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Edit Project
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project
        ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item active">Edit Project
</li>
@endsection



@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form edit data project</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="kode_project">Kode Project</label>
                                        <input type="text" id="kode_project" value="{{$project[0]->kode_project}}"
                                            class=" form-control kode_project" name="fname" placeholder="Kode Project"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_project">Nama Project</label>
                                        <input type="text" id="nama_project" value="{{$project[0]->nama_project}}"
                                            class="form-control nama_project" name="email-id"
                                            placeholder="Nama Project">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tanggal_kontrak">Tanggal Kontrak</label>
                                        <input type='text' class="form-control tanggal_kontrak"
                                            value={{date('d-m-Y', strtotime($project[0]->tanggal_project))}} />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="validationTooltip02">Perusahaan Penyewa</label>
                                    <div class="form-group">
                                        <select class="select2 form-control perusahaan_penyewa">
                                            <option value disabled selected>Pilih perusahaan penyewa</option>
                                            @foreach ($list_perusahaan as $perusahaan)
                                            <option value="{{$perusahaan->id_perusahaan}}"
                                                {{($perusahaan->id_perusahaan == $project[0]->id_perusahaan) ? "selected" : ""}}>
                                                {{$perusahaan->nama_perusahaan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary mr-1 mb-1" onclick="editProject()">Submit</button>
                                </div>
                            </div>
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
    $('.tanggal_kontrak').pickadate({
        format: 'dd-mm-yyyy',
        editable:true
    });

    function editProject() {
        let new_kode_project = $(".kode_project").val();
        let nama_project = $(".nama_project").val();
        let tanggal_kontrak = $(".tanggal_kontrak").val();
        let perusahaan_penyewa = $(".perusahaan_penyewa").val();
        $.ajax({
            type: "get",
            url: `{!! route('project.update', ['kodeproject' => $kodeproject]) !!}`,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {
                new_kode_project: new_kode_project,
                nama_project: nama_project,
                tanggal_kontrak: tanggal_kontrak,
                perusahaan_penyewa: perusahaan_penyewa
            },
            success: function (data) {
                let title = "";
                let text = "";
                let type_swal = "";
                if (data == "success") {
                    window.location = "{!! route('project.index') !!}";
                } else {
                    title = "Gagal!";
                    text = data;
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
        })
    }

    $(document).ready(function () {
    });

</script>
@endsection