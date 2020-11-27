@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Edit Role
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('role.index')}}">Role ({{$role[0]->nama_role}})</a>
</li>
<li class="breadcrumb-item active">Edit Role
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
                            <input type="text" name="" id="" class="form-control nama_role" placeholder="Nama Role"
                                value="{{$role[0]->nama_role}}">
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="">Pilih modul</label>
                                <hr>
                                <div class="list_modul row">
                                    
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-outline-primary" onclick="editRole()">Simpan Role</button>
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
    let list_gudang = [];
    let list_project = [];
    let list_modul = [];


    function editRole() {
        list_modul = [];
        $("input[name='modul[]']:checked").each(function () {
            list_modul.push(parseInt($(this).val()));
        });

        if (list_modul.length != 0) {
            $.ajax({
                type: "get",
                url: "{!! route('role.update', $idrole) !!}",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    'list_modul': JSON.stringify(list_modul),
                    'nama_role': $(".nama_role").val()
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
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
        } else {
            Swal.fire({
                title: "Gagal!",
                text: "Pilih salah satu gudang, project, dan modul!",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
    }

    function refresh() {
        let list_modul_selected = JSON.parse('{!!$list_modul_selected!!}');
        let list_modul_temp = JSON.parse('{!!$list_modul!!}');
        let selected = "";

        list_modul_temp.forEach(modul => {
            selected = list_modul_selected.some(el => el.id_modul === modul['id_modul']) == true ? "checked" :
                "";
            $(".list_modul").append(`
                <div class="col-sm-3">
                    <div class="vs-checkbox-con vs-checkbox-primary">
                        <input type="checkbox" name="modul[]" class="modul[]"
                            value="${modul['id_modul']}" ${selected}>
                        <span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span>
                        <span class="">${modul['nama_modul']}</span>
                    </div>
                </div>
            `);

            if (selected == 'checked') {
                list_modul.push(modul['id_project']);
            }
        });
    }

    $(document).ready(function () {
        refresh();

    });

</script>
@endsection