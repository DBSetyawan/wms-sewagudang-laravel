@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Gudang
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('gudang.index')}}">Gudang</a>
</li>
<li class="breadcrumb-item active">Tambah Gudang
</li>
@endsection

@section('content')
<!-- Tooltip validations start -->
<section id="simple-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Gudang</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <form class="tambah_gudang" novalidate>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="validationTooltip02">Nama Gudang</label>
                                            <input type="text" class="form-control nama_gudang" name="text"
                                                placeholder="Nama Gudang"
                                                data-validation-required-message="Nama gudang harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="validationTooltip02">Alamat Lengkap</label>
                                            <input type="text" class="form-control alamat_lengkap"
                                                id="validationTooltip02" placeholder="Alamat"
                                                data-validation-required-message="Alamat lengkap harus diisi!" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="controls"><label for="validationTooltip02">Provinsi</label>
                                            <div class="form-group">
                                                <select class="select2 form-control provinsi"
                                                    onchange="getkabupaten(value)"
                                                    data-validation-required-message="Provinsi harus dipilih" required>
                                                    <option value disabled selected>Pilih Provinsi</option>
                                                    @foreach ($list_provinsi as $provinsi)
                                                    <option value="{{$provinsi->id_provinsi}}">{{$provinsi->nama_provinsi}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="controls">
                                                <label for="validationTooltip02">Kabupaten</label>
                                                <div class="form-group">
                                                    <select class="select2 form-control kabupaten"
                                                        data-validation-required-message="Nama perusahaan harus diisi!"
                                                        required>
                                                        <option value="">Pilih Kabupaten</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <button class="btn btn-primary" onclick="tambahgudang()">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Tooltip validations end -->
@endsection

@section('script_document_ready')

<script>
    $(".tambah_gudang").submit(false);

function getkabupaten(argIdProvinsi, argIdKabupaten = 0) {
    let url = "{!! route('utility.getkabupaten') !!}";
    $.ajax({
        type: "get",
        url: url,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        dataType: "json",
        data: {
            id_provinsi: argIdProvinsi,
        },
        success: function (data) {
            $(".kabupaten").html("");
            $(".kabupaten").append("<option value=''>Pilih Kabupaten</option>");

            if (argIdKabupaten == 0) {
                for (let i = 0; i < data.length; i++) {
                    $(".kabupaten").append(
                        "<option value=" +
                            data[i]["id_kabupaten"] +
                            ">" +
                            data[i]["nama_kabupaten"] +
                            "</option>"
                    );
                }
            } else {
                for (let i = 0; i < data.length; i++) {
                    let selected =
                        argIdKabupaten == data[i]["id_kabupaten"]
                            ? " selected>"
                            : ">";
                    $(".kabupaten").append(
                        "<option value='" +
                            data[i]["id_kabupaten"] +  "'" + 
                            selected +
                            data[i]["nama_kabupaten"] +
                            "</option>"
                    );
                }
            }
        },
    });
}

function tambahgudang() {
    let kode_gudang = $(".kode_gudang").val();
    let nama_gudang = $(".nama_gudang").val();
    let alamat_lengkap = $(".alamat_lengkap").val();
    let kabupaten = $(".kabupaten").val();
    let provinsi = $(".provinsi").val();

    if(nama_gudang != "" && alamat_lengkap != "" && provinsi != "" && kabupaten != "" )
    {
        $.ajax({
            type: "get",
            url: "{!! route('gudang.store') !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            dataType: "json",
            data: {
                nama_gudang: nama_gudang,
                alamat_lengkap: alamat_lengkap,
                kabupaten : kabupaten
                // kecamatan: kecamatan,
            },
            success: function (data) {
                console.log(data);
                if (data == "success") {
                    window.location = "{!! route('gudang.index') !!}"

                    // Swal.fire({
                    //     title: "Berhasil!",
                    //     text: "Gudang berhasil disimpan",
                    //     type: "success",
                    //     confirmButtonClass: "btn btn-primary",
                    //     buttonsStyling: false,
                    // });

                    // generateIdGudang();
                    // getProvinsi();
                    // getkabupaten();
                    // getKecamatan();
                    // $(".nama_gudang").val("");
                    // $(".alamat_lengkap").val("");
                }
            },
        });
    }
}
</script>
@endsection