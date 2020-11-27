@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah Role
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Role</a>
</li>
<li class="breadcrumb-item active">Tambah Role
</li>
@endsection

@section('content')
<section id="number-tabs">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                           <div class="col-12 mb-2">
                            <label for="">Nama Role</label>
                            <input type="text" name="" id="" class="form-control nama_role" placeholder="Nama Role">
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-5">
                                <label for="">Pilih modul</label>
                                <hr>
                                <div class=" row">
                                    @foreach ($list_modul as $modul)
                                    <div class="col-sm-2">
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="inbound-header-required" name="modul[]"
                                                value="{{$modul->id_modul}}">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">{{$modul->nama_modul}}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-outline-primary" onclick="tambahRole()">Tambah Role</button>
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
    let list_modul = [];

    

    function tambahRole() {
        // $("input[name='gudang[]']:checked").each(function () {
        //     list_gudang.push(parseInt($(this).val()));
        // });
        $("input[name='modul[]']:checked").each(function () {
            list_modul.push(parseInt($(this).val()));
        });

        if (list_modul.length != 0) {
            $.ajax({
                type: "get",
                url: "{!! route('role.store') !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    // "list_gudang": JSON.stringify(list_gudang),
                    // 'list_project': JSON.stringify(list_project),
                    'list_modul': JSON.stringify(list_modul),
                    'nama_role': $(".nama_role").val()
                },
                dataType: "json",
                success: function (response) {
                    let title = "";
                    let text = "";
                    let type_swal = "";

                    if (response == "success") {
                        window.location = "{!! route('role.index') !!}"
                    } else {
                        title = "Gagal!";
                        text = response;
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
            });
        }
    }

</script>
@endsection