@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Edit project gudang
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('project.index')}}">Project ({{$kodeproject}})</a>
</li>
<li class="breadcrumb-item"><a href="{{route('projecthasgudang.index', ['kodeproject'=>$kodeproject])}}">Daftar Project
        Gudang {{$kodegudang}}</a>
</li>
<li class="breadcrumb-item active">Edit Project Gudang
</li>
@endsection

@section('content')
<section id="basic-vertical-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Project Gudang</h4>
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
                                            @foreach ($list_gudang as $gudang)
                                            @if ($gudang->kode_gudang == $kodegudang)
                                            <option value="{{$gudang->id_gudang}}" selected>{{$gudang->kode_gudang}} -
                                                {{$gudang->nama_gudang}}</option>
                                            @else
                                            <option value="{{$gudang->id_gudang}}">{{$gudang->kode_gudang}} -
                                                {{$gudang->nama_gudang}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary mr-1 mb-1"
                                        onclick="editProjectGudang()">Submit</button>
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
                        <div class="list_user row">

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
    let list_user = [];
    function editProjectGudang() {
        let idgudang= $('.gudang').val();
        let action = "";
        
        $("input[name='user[]']:checked").each(function () {
            list_user.push(parseInt($(this).val()));
        });

        $.ajax({
            type: "get",
            url: "{!! route('projecthasgudang.update', ['kodeproject'=>$kodeproject, 'kodegudang'=>$kodegudang]) !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            dataType: "json",
            data: {
                idgudang : idgudang,
                list_user : JSON.stringify(list_user)
            },
            success: function (data) {
                console.log(data);
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

    function refresh() {
        let list_hak_akses_user = JSON.parse('{!!$list_hak_akses_user!!}');
        let list_user = JSON.parse('{!!$list_user!!}');
        let selected = "";

        list_user.forEach(user => {
            selected = list_hak_akses_user.some(el => el.id_user === user['id']) == true ? "checked" :
                "";
            $(".list_user").append(`
                <div class="col-sm-3">
                    <div class="vs-checkbox-con vs-checkbox-primary">
                        <input type="checkbox" name="user[]" class="user[]" value="${user['id']}" ${selected}>
                        <span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span>
                        <span class="">${user['name']}</span>
                    </div>
                </div>
            `);

            if (selected == 'checked') {
                list_user.push(user['id']);
            }
        });
    }

    $(document).ready(function () {
        $('.tanggal_kontrak').pickadate({
            format: 'dd-mm-yyyy',
            editable:true
        });
        refresh();
    });

</script>
@endsection