@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Modul
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('modul.index')}}">Modul</a>
</li>
<li class="breadcrumb-item active">Tambah Modul
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Data Modul</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Nama Modul</label>
                                        <input type="text" id="nama_modul" class="form-control nama_modul" name="fname"
                                            placeholder="Nama Modul">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="tambah_modul()">Tambah Modul</button>
                                    </div>
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
    function tambah_modul()
    {
        $.ajax({
            type:'get',
            url: "{!! route('modul.store') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            dataType : "json",
            data : {
                nama_modul : $(".nama_modul").val(),
            },
            success : function(data){
                
                let message = "";
                let info ="";
                let type_swal = "";

                if(data == "success")
                {
                    window.location = "{!! route('modul.index') !!}"
                }
                else
                {
                    message = "Gagal";
                    info = data;
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
        })
    }
</script>
@endsection