@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Edit Locator
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('gudang.index')}}">Gudang ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('locator.index', ['kodegudang'=>$kodegudang])}}">Locator</a>
</li>
<li class="breadcrumb-item active">Edit Locator
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form edit data locator</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Nama Locator</label>
                                        <input type="text" id="nama_perusahaan" class="form-control nama_locator"
                                            value="{{$locator[0]->nama_locator}}" name="fname"
                                            placeholder="Nama Perusahaan">
                                    </div>
                                </div>
                                <div class="col-12 div_locator_level">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih status locator</label>
                                        <select class="select2 form-control status_locator">
                                            @if ($locator[0]->status == 0)
                                            <option value="1">Aktif</option>
                                            <option value="0" selected>Tidak aktif</option>
                                            @else
                                            <option value="1" selected>Aktif</option>
                                            <option value="0">Tidak aktif</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih type locator</label>
                                        <select class="select2 form-control type_locator"
                                            placeholder="Pilih type locator" onchange="isTypeLocatorRacking()">
                                            @foreach ($type_locator as $type)
                                            <option value="{{$type->id_type_locator}}"
                                                {{($type->id_type_locator == $locator[0]->id_type_locator) ? "selected" : ""}}>
                                                {{$type->type_locator}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 div_locator_parent" style="display: none">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih parent locator</label>
                                        <select class="select2 form-control locator_parent"
                                            placeholder="Pilih locator parent">
                                            @foreach ($locator_parent as $parent)
                                            <option value="{{$parent->id_locator}}"
                                                {{($parent->id_locator == $locator[0]->id_locator) ? "selected" : ""}}>
                                                {{$parent->nama_locator}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 div_locator_level" style="display: none">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih level locator</label>
                                        <select class="select2 form-control locator_level" placeholder="Pilih level">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary" onclick="simpanData()">Simpan</button>
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
    function isTypeLocatorRacking() {
        let type_locator = $(".type_locator").val();
        if (type_locator == "2") {
            $(".div_locator_parent").attr('style', 'display:block');
            $(".div_locator_level").attr('style', 'display:block');
        } else {
            $(".div_locator_parent").attr('style', 'display:none');
            $(".div_locator_level").attr('style', 'display:none');
        }

    }

    function simpanData() {
        let nama_locator = $(".nama_locator").val();
        let type_locator = $(".type_locator").val();
        let locator_parent = 0;
        let locator_level = 0;
        let status_locator = $(".status_locator").val();
        if (type_locator == 2) {
            locator_parent = $(".locator_parent").val();
            locator_level = $('.locator_level').val();
        }
        $.ajax({
            type: 'get',
            url: "{!! route('locator.update', ['kodegudang'=> $kodegudang, 'idlocator'=> $locator[0]->id_locator]) !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: 'json',
            data: {
                nama_locator: nama_locator,
                type_locator: type_locator,
                locator_parent: locator_parent,
                locator_level: locator_level,
                status_locator: status_locator
            },
            success: function (data) {
                let message = "";
                let info = "";
                let type_swal = "";

                if (data == "success") {
                    window.location = '{!! route("locator.index", ["kodegudang" => $kodegudang]) !!}'
                } else {
                    if (data == "gagal") {
                        message = "Gagal!";
                        info = "Nama locator sudah ada";
                        type_swal = "error";
                    } else {
                        message = "Gagal";
                        info = data;
                        type_swal = "error";
                    }
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

    $(document).ready(function () {
        isTypeLocatorRacking();
    })

</script>
@endsection