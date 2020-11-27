@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Project
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject'=>$kodeproject])}}">Daftar Project
        Gudang</a>
</li>
<li class="breadcrumb-item active">Tambah Project Gudang
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Project Gudang</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="validationTooltip02">Gudang</label>
                                    <div class="form-group">
                                        <select class="select2 form-control gudang">
                                            <option value selected disabled>Pilih gudang</option>
                                            @foreach ($list_gudang as $gudang)
                                            <option value="{{$gudang->id_gudang}}">{{$gudang->kode_gudang}} -
                                                {{$gudang->nama_gudang}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary mr-1 mb-1" onclick="tambahProject()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pilih user untuk hak akses </h4>

                </div>
                {{-- <label class="">Pilih user yang dapat mengakses project gudang </label> --}}
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class=" row">
                            @foreach ($list_user as $user)
                            <div class="col-sm-3">
                                <div class="vs-checkbox-con vs-checkbox-primary">
                                    <input type="checkbox" name="user[]" class="user[]" value="{{$user->id}}">
                                    <span class="vs-checkbox">
                                        <span class="vs-checkbox--check">
                                            <i class="vs-icon feather icon-check"></i>
                                        </span>
                                    </span>
                                    <span class="">{{$user->name}}</span>
                                </div>
                            </div>
                            @endforeach

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
    function tambahProject() {
        let list_user = [];
        $("input[name='user[]']:checked").each(function () {
            list_user.push(parseInt($(this).val()));
        });

        let idgudang= $('.gudang').val();
        $.ajax({
            type: "get",
            url: "{!! route('projecthasgudang.store', ['kodeproject'=>$kodeproject]) !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {
                idgudang : idgudang,
                list_user : JSON.stringify(list_user)
            },
            success: function (data) {
                let title = ""
                let text = "";
                let type_swal = "";

                if (data == "success") {
                    window.location = "{!! route('projecthasgudang.index', ['kodeproject'=>$kodeproject]) !!}";
                } else {
                    Swal.fire({
                        title: "Gagal",
                        text: "Gudang sudah ada!",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                }


            }
        })
    }

    $(document).ready(function () {
        $('.tanggal_kontrak').pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });
    });

</script>
@endsection