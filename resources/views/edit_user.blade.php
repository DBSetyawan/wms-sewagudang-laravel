@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Edit User
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('user.index')}}">User</a>
</li>
<li class="breadcrumb-item active">Edit User
</li>
@endsection

@section('content')
<section class="input-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Edit User</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control name @error('name') is-invalid @enderror" name="name"
                                    value="{{ $user[0]->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"
                                    class="form-control email @error('email') is-invalid @enderror" name="email"
                                    value="{{ $user[0]->email}}" required autocomplete="email" disabled>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select name="role" id="role" class="form-control role">
                                    @foreach ($list_role as $role)
                                    <option value="{{$role->id_role}}"
                                        {{($role->id_role == $user[0]->id_role) ? "selected" : ""}}>{{$role->nama_role}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary" onclick="editUser()">
                                    {{ __('Simpan') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($user[0]->id_role != "4")
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pilih project</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="list_project row">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="">
    <div class="row ">
        <div class="col-12 ">
            <div class="row project_has_gudang">

            </div>
        </div>
    </div>
</section>
@endif
@endsection

@section('script_document_ready')
<script>
    let list_project_has_gudang = [];
    let list_project = [];
    function emailExistChecker() {
        let email = $(".email").val();
        $.ajax({
            type: "get",
            url: "{!! route('user.emailchecker') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'email' : email
            },
            dataType: "json",
            success: function (response) {
                if(response == true)
                {
                    $(".notifikasi").show();
                }
                else
                {
                    $(".notifikasi").hide();
                }
            }
        });
     }

     function getListGudangFromProject() {
        list_project = [];
        $("input[name='project[]']:checked").each(function () {
            list_project.push(parseInt($(this).val()));
        });

        $.ajax({
            type: "get",
            url: "{!! route('projecthasgudang.getgudangfromproject') !!}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            data: {
                'list_project': JSON.stringify(list_project)
            },
            dataType: "json",
            success: function (response) {
                let i = 0;
                let project_length = list_project.length;
                let j = 0;
                let response_length = response.length;
                let container_exist = "";
                let checkbox_exist = "";
                list_project.forEach(project => {
                    container_exist = document.querySelectorAll(`.container_${project}`);
                    if(container_exist.length == 0)
                    {
                        $(".project_has_gudang").append(`
                             <div class="col-4 container_${project}">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title title_${project}"></h4>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="list_project_has_gudang${project} row">


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        `);
                    }
                    response.forEach(gudang => {
                        if(project == gudang['id_project'])
                        {
                            checkbox_exist = document.querySelectorAll(`.checkbox_${gudang['id_project']}_${gudang['id_gudang']}`);
                            if(checkbox_exist.length == 0)
                            {
                                $(`.title_${project}`).html(`Pilih gudang ${gudang['nama_project']}`)
                                $(`.list_project_has_gudang${project}`).append(`
                                    <div class="col-sm-12 checkbox_${gudang['id_project']}_${gudang['id_gudang']}">
                                        <div class="vs-checkbox-con vs-checkbox-primary">
                                            <input type="checkbox" class="inbound-header-required" name="project_has_gudang[]" value="${gudang['id_project_has_gudang']}">
                                            <span class="vs-checkbox">
                                                <span class="vs-checkbox--check">
                                                    <i class="vs-icon feather icon-check"></i>
                                                </span>
                                            </span>
                                            <span class="">${gudang['nama_gudang']}</span>
                                        </div>
                                    </div>
                                `)
                            }

                        }
                    });
                });        
            }
        });
    }

    function removeElement(argIdProject) { 
        if($(".container_" + argIdProject).css('display') == "block")
        {
            $(".container_" + argIdProject).remove();
        }
     }

    function editUser() {
       list_project_has_gudang = [];
        let nama = $(".name").val();
        let email = $(".email").val();
        let role = $('.role').val();

        $("input[name='project_has_gudang[]']:checked").each(function () {
            list_project_has_gudang.push(parseInt($(this).val()));
        });
        $.ajax({
            type: "post",
            url: "{!! route('user.update', ['id' =>  $user[0]->id]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'name' : nama,
                'email' : email,
                'id_role' : role,
                'list_project_has_gudang' : JSON.stringify(list_project_has_gudang)
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                let title = "";
                let text = "";
                let type_swal = "";
                if(response == 'success')
                {
                    window.location = "{!! route('user.index') !!}";
                }
                else
                {
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

     function refresh() {
        let list_project_has_gudang_selected = JSON.parse('{!!$list_project_has_gudang_selected!!}');

        let list_project_has_gudang_temp = JSON.parse('{!!$list_project_has_gudang!!}');
        let list_project_temp = JSON.parse('{!!$list_project!!}');
        let selected = "";

        list_project_temp.forEach(project => {
            selected = list_project_has_gudang_selected.some(el => el.id_project == project['id_project']) == true ? "checked" : "";
            $(".list_project").append(`
                    <div class="col-sm-3">
                        <div class="vs-checkbox-con vs-checkbox-primary">
                            <input type="checkbox" name="project[]" class="project[]"
                                value="${project['id_project']}"  onchange="getListGudangFromProject(); removeElement(${project['id_project']})" ${selected}>
                            <span class="vs-checkbox">
                                <span class="vs-checkbox--check">
                                    <i class="vs-icon feather icon-check"></i>
                                </span>
                            </span>
                            <span class="">${project['nama_project']}</span>
                        </div>
                    </div>
                `)

            if (selected == 'checked') {
                list_project.push(project['id_project']);
            }
        });


        list_project_has_gudang_temp.forEach(gudang => {
            selected = list_project_has_gudang_selected.some(el => el.id_gudang === gudang['id_gudang'] && el.id_project === gudang['id_project']) == true ? "checked" : "";
            if (selected == "checked") {
                list_project_has_gudang.push(gudang['id_gudang']);
                container_exist = document.querySelectorAll(`.container_${gudang['id_project']}`);
                if(container_exist.length == 0)
                {
                    $(".project_has_gudang").append(`
                            <div class="col-4 container_${gudang['id_project']}">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title title_${gudang['id_project']}"></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="list_project_has_gudang${gudang['id_project']} row">


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    `);
                    $(`.title_${gudang['id_project']}`).html(`Pilih gudang ${gudang['nama_project']}`)
                }
            }
            checkbox_exist = document.querySelectorAll(`.checkbox_${gudang['id_project']}_${gudang['id_gudang']}`);
            if(checkbox_exist.length == 0)
            {
                $(".list_project_has_gudang" + gudang['id_project']).append(`
                    <div class="col-sm-12 checkbox_${gudang['id_project']}_${gudang['id_gudang']}">
                        <div class="vs-checkbox-con vs-checkbox-primary">
                            <input type="checkbox" name="project_has_gudang[]" class="gudang[]"
                                value="${gudang['id_project_has_gudang']}" ${selected}>
                            <span class="vs-checkbox">
                                <span class="vs-checkbox--check">
                                    <i class="vs-icon feather icon-check"></i>
                                </span>
                            </span>
                            <span class="">${gudang['nama_gudang']}</span>
                        </div>
                    </div>
                `);
            }
            
        });
        
    }

     $(document).ready(function () {
         $(".notifikasi").hide();
         let id_role = "{!! $user[0]->id_role !!}"
         if(id_role !="4")
         {
            refresh();
         }
         
     });
</script>
@endsection