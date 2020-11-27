@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Project
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project</a>
</li>
<li class="breadcrumb-item active">Tambah Project
</li>
@endsection

@section('content')
<section id="simple-validation">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Data Project</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="tambah_project" novalidate>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="validationTooltip02">Perusahaan Penyewa</label>
                                            <select class="select2 form-control perusahaan_penyewa"
                                                data-validation-required-message="Pilih perusahaan penyewa!" required>
                                                <option value disabled selected>Pilih perusahaan penyewa</option>
                                                @foreach ($list_perusahaan as $perusahaan)
                                                <option value="{{$perusahaan->id_perusahaan}}">
                                                    {{$perusahaan->nama_perusahaan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="form-group">
                                        <label for="kode_project">Kode Project</label>
                                        <input type="text" id="kode_project" class="form-control kode_project"
                                            name="fname" placeholder="Kode Project" disabled>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="nama_project">Nama Project</label>
                                            <input type="text" id="nama_project" class="form-control nama_project"
                                                name="email-id" placeholder="Nama Project"
                                                data-validation-required-message="Nama project harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="tanggal_kontrak">Tanggal Kontrak</label>
                                            <input type='text' class="form-control tanggal_kontrak"
                                                data-validation-required-message="Tanggal kontrak harus diisi!"
                                                required />
                                        </div>
                                    </div>
                                </div>

                                <div class=" col-12">
                                    <button class="btn btn-primary mr-1 mb-1" onclick="tambahProject()">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_document_ready')
<script>
    $(".tambah_project").submit(function(e){
        e.preventDefault();
    })
    
    function tambahProject() {
        let kode_project = $(".kode_project").val();
        let nama_project = $(".nama_project").val();
        let tanggal_kontrak = $(".tanggal_kontrak").val();
        let perusahaan_penyewa = $(".perusahaan_penyewa").val();

        if(nama_project != "" && tanggal_kontrak != "" && perusahaan_penyewa != "")
        {
            $.ajax({
                type: "get",
                url: "{!! route('project.store') !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                dataType: "json",
                data: {
                    nama_project: nama_project,
                    tanggal_kontrak: tanggal_kontrak,
                    perusahaan_penyewa: perusahaan_penyewa
                },
                success: function (data) {
                    // console.log(data);
                    let title = "Gagal!"
                    let text = data;
                    let type_swal = "error";

                    if (data == "success") {
                        window.location = "{!! route('project.index') !!}";
                    } else {
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
    }

    $(document).ready(function () {
        // getAllPerusahaan();
        $('.tanggal_kontrak').pickadate({
            format: 'dd-mm-yyyy',
            editable:true,
            onStart: function ()
            {
                var date = new Date();
                this.set('select' ,[date.getFullYear(), date.getMonth(), date.getDate()])
            },
        });
    });

</script>
@endsection