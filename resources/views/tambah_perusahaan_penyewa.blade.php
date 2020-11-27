@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Perusahaan Penyewa
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('perusahaan.index')}}">Perusahaan</a>
</li>
<li class="breadcrumb-item active">Tambah Perusahaan Penyewa
</li>
@endsection

@section('content')
<section id="simple-validation">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Data Perusahaan Penyewa</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="tambah_perusahaan" novalidate>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="nama_perusahaan">Nama Perusahaan</label>
                                            <input type="text" name="text" id="nama_perusahaan"
                                                class="form-control nama_perusahaan" name="fname"
                                                placeholder="Nama Perusahaan"
                                                data-validation-required-message="Nama perusahaan harus diisi!"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">

                                            <label for="alamat_lengkap">Alamat Lengkap</label>
                                            <input type="text" id="alamat_lengkap" class="form-control alamat_lengkap"
                                                name="email-id" placeholder="Alamat lengkap"
                                                data-validation-required-message="Alamat perusahaan harus diisi!"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">

                                            <label for="alamat_lengkap">No. Telp</label>
                                            <input type="text" id="notelp" class="form-control notelp" name="email-id"
                                                placeholder="No. Telepeon"
                                                data-validation-required-message="No. Telepon harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="email">Email</label>
                                            <input type="email" id="email" class="form-control" name="email"
                                                placeholder="example@example.com"
                                                data-validation-required-message="Email perusahaan harus diisi!"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="npwp">NPWP</label>
                                        <input type="text" id="npwp" class="form-control" name="npwp"
                                            placeholder="No NPWP">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="ktp">KTP</label>
                                        <input type="text" id="ktp" class="form-control" name="ktp"
                                            placeholder="No KTP">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1"
                                        onclick="tambahDataPerusahaanPenyewa()">Submit</button>
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
    $(".tambah_perusahaan").submit(function(e){
        e.preventDefault();
    });

    function tambahDataPerusahaanPenyewa() {
            let nama_perusahaan = $("#nama_perusahaan").val();
            let alamat = $("#alamat_lengkap").val();
            let email = $("#email").val();
            let npwp = $("#npwp").val();
            let ktp = $("#ktp").val();
            let notelp = $("#notelp").val();

            if(nama_perusahaan != "" && alamat != "" && email != "")
            {
                $.ajax({
                    type: "get",
                    url: "{!! route('perusahaan.store') !!}",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    data: {
                        nama_perusahaan: nama_perusahaan,
                        alamat: alamat,
                        email: email,
                        npwp: npwp,
                        ktp : ktp,
                        notelp : notelp
                    },
                    success: function (data) {
                        if (data == "success") {
                            window.location = "{!! route('perusahaan.index') !!}"
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data,
                                type: "danger",
                                confirmButtonClass: "btn btn-primary",
                                buttonsStyling: false,
                            });
                        }
                    },
                });
            }
        }
</script>
@endsection