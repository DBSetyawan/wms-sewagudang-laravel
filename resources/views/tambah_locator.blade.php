@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Locator
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('gudang.index')}}">Gudang ({{$kodegudang}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('locator.index', ['kodegudang'=>$kodegudang])}}">Locator </a>
</li>
<li class="breadcrumb-item active">Tambah Locator
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Data Locator</h4>
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
                                            name="fname" placeholder="Nama Locator">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih type locator</label>
                                        <select class="select2 form-control type_locator"
                                            placeholder="Pilih type locator" onchange="isTypeLocatorRacking()">
                                            @foreach ($list_type_locator as $type_locator)
                                            <option value="{{$type_locator->id_type_locator}}">
                                                {{$type_locator->type_locator}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 div_locator_parent" style="display: none">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih locator parent</label>
                                        <select class="select2 form-control locator_parent"
                                            placeholder="Pilih locator parent">
                                            <option value disabled selected>Pilih locator parent</option>
                                            @foreach ($locator_parent as $locator)
                                            <option value="{{$locator->id_locator}}"> {{$locator->nama_locator}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 div_locator_level" style="display: none">
                                    <div class="form-group">
                                        <label for="nama_perusahaan">Pilih level</label>
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
                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="simpanLocator()">Tambah Locator</button>
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

    // function getAllLocator() {
    //     $.ajax({
    //         type: 'get',
    //         url: '{!! route("locator.getalllocator", $kodegudang) !!}',
    //         headers: {
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         },
    //         success: function (data) {
    //             let result = JSON.parse(data);
    //             $(".locator_parent").html("");
    //             $(".locator_parent").append("<option value selected disabled >Pilih locator</option>");
    //             result.forEach(locator => {
    //                 $(".locator_parent").append(
    //                     `<option value=${locator['id_locator']}> ${locator['nama_locator']}</option>`
    //                 );
    //             });
    //         }
    //     })
    // }

    function simpanLocator() {
        let nama_locator = $(".nama_locator").val();
        let type_locator = $(".type_locator").val();
        let locator_parent = 0;
        let locator_level = 0;

        if (type_locator == 2) {
            locator_parent = $(".locator_parent").val();
            locator_level = $('.locator_level').val();
        }

        $.ajax({
            type: 'get',
            url: "{!! route('locator.store',$kodegudang) !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {
                nama_locator: nama_locator,
                type_locator: type_locator,
                locator_parent: locator_parent,
                locator_level: locator_level
            },
            success: function (data) {
                let message = "";
                let info = "";
                let type_swal = "";

                if (data == "success") {
                    window.location = "{!! route('locator.index', ['kodegudang'=>$kodegudang]) !!}"
                } else {
                    if (data == "gagal") {
                        message = "Gagal";
                        info = "Locator sudah ada!";
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

    // function getAllTypeLocator() {
    //     $.ajax({
    //         type: 'get',
    //         url: "{!! route('typelocator.gettypelocator') !!}",
    //         headers: {
    //             "X-CSRF-TOKEN": "{{ csrf_token() }}"
    //         },
    //         success: function (data) {
    //             console.log(data);
    //             let result = JSON.parse(data);

    //             result.forEach(typelocator => {
    //                 $(".type_locator").append(
    //                     `<option value=${typelocator['id_type_locator']}>${typelocator['type_locator']}</option>`
    //                 );
    //             });
    //         }
    //     })
    // }

    $(document).ready(function () {
        // getAllTypeLocator();
        // getAllLocator();
    })

</script>
@endsection