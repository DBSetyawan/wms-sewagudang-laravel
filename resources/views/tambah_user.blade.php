@extends('layout.master')
@extends('layout.navbar')
@extends('layout.sidebar')

@section('page_name')
Tambah User
@endsection

@section('breadcrum_list')
<li class="breadcrumb-item"><a href="{{route('user.index')}}">User</a>
</li>
<li class="breadcrumb-item active">Tambah User
</li>
@endsection

@section('content')
<section class="input-validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah User</h4>
                </div>
                <hr>
                <div class="card-content">
                    <div class="card-body">
                        {{-- <form class="form-horizontal" novalidate>
                            <div class="form-row">
                                <div class="col-md-6 mb-2">
                                    <label for="validationTooltip01">Nama</label>
                                    <input type="text" name="text" class="form-control nama"
                                        data-validation-required-message="This field is required"
                                        id="validationTooltip01" placeholder="Nama" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-2">
                                    <label for="validationTooltip01">Email</label>
                                    <input type="email" name="email" class="form-control email"
                                        data-validation-required-message="Must be a valid email" placeholder="Email"
                                        onchange="emailExistChecker()">
                                    <span class="notifikasi">
                                        <label class="text-danger">Email sudah
                                            terdaftar</label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-2">
                                    <label for="validationTooltip01">Password</label>
                                    <input type="password" name="password" class="form-control"
                                        data-validation-required-message="This field is required"
                                        placeholder="Password">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6 mb-2">
                                    <label for="">Role</label>
                                    <select class="role form-control">

                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-simpan"
                                onclick="tambahUser()">Submit</button>
                        </form> --}}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text"
                                    class="form-control name @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                                    value="{{ old('email') }}" onchange="emailExistChecker()" required
                                    autocomplete="email">

                                <span class="notifikasi">
                                    <label class="text-danger">Email sudah
                                        terdaftar</label>
                                </span>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password"
                                class="form-control password @error('password') is-invalid @enderror" name="password"
                                required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm"
                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control password_confirmation"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                        <div class="col-md-6">
                            <select name="role" id="role" class="form-control role">
                                @foreach ($list_role as $role)
                                <option value="{{$role->id_role}}">{{$role->nama_role}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="button" class="btn btn-primary btn-register" onclick="tambahUser()">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pilih project</h4>
            </div>
            <hr>
            <div class="card-content">
                <div class="card-body">
                    <div class=" row">
                        @foreach ($list_project as $project)
                        <div class="col-sm-3">
                            <div class="vs-checkbox-con vs-checkbox-primary">
                                <input type="checkbox" name="project[]" class="project[]"
                                    value="{{$project->id_project}}"
                                    onchange="getListGudangFromProject();  removeElement({!! $project->id_project !!})">
                                <span class="vs-checkbox">
                                    <span class="vs-checkbox--check">
                                        <i class="vs-icon feather icon-check"></i>
                                    </span>
                                </span>
                                <span class="">{{$project->nama_project}}</span>
                            </div>
                        </div>
                        @endforeach

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
@endsection

@section('script_document_ready')
<script>
    let list_project_has_gudang = [];
    let list_project = [];

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
                console.log(response);
                console.log(list_project);
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
            console.log('aloha');
            $(".container_" + argIdProject).remove();
        }
     }

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

    function getRoleList()
    {
        $.ajax({
            type: "get",
            url: "{!! route('role.getallrole') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            success: function (response) {
                let result = JSON.parse(response);
                $(".role").append("<option disabled selected>Pilih role</option>")
                result.forEach(role => {
                    $(".role").append(`<option value="${role['id_role']}">${role['nama_role']}</option>`);
                });

            }
        });
    }
    function tambahUser() {
        let nama = $(".name").val();
        let email = $(".email").val();
        // let password = $(".password").val();
        // let password_confirmation = $(".password_confirmation").val();
        let role = $('.role').val();

        $("input[name='project_has_gudang[]']:checked").each(function () {
            list_project_has_gudang.push(parseInt($(this).val()));
        });

        if($(".notifikasi").is(":hidden"))
        {
            if(nama != "" && email != "" && role !="")
            {
                $(".background-spinner").css("display", 'inline');
                $(".text-center").css("display",'inherit');
                $.ajax({
                    type: "post",
                    url: "{!! route('register') !!}",
                    headers : {
                        "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                    },
                    data: {
                        'name' : nama,
                        'email' : email,
                        // 'password' : password,
                        // 'password_confirmation' : password_confirmation,
                        'id_role' : role,
                        'list_project_has_gudang' : JSON.stringify(list_project_has_gudang)
                    },
                    dataType: "json",
                    success: function (response) {
                        $(".background-spinner").css("display", 'none');
                        $(".text-center").css("display",'none');
                        // console.log(response);
                        // let result = JSON.parse(response);
                        let title = "";
                        let text = "";
                        let type_swal = "";

                        if(response == 'success')
                        {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Link verifikasi password telah dikirim. Silahkan cek email",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then((result)=>{
                                window.location.href="{{ route('user.index') }}";    
                            });
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
            else
            {
                Swal.fire({
                    title: "Gagal!",
                    text: "Silahkan mengisi semua field",
                    type: "error",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        }
        else
        {
            Swal.fire({
                title: "Gagal!",
                text: "Cek kembali email anda",
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
        
     }

     $(document).ready(function () {
        //  getRoleList();
         $(".notifikasi").hide();
     });
     /*
     function tambahUser() {
        let nama = $(".name").val();
        let email = $(".email").val();
        let password = $(".password").val();
        let role = $('.role').val();
        let hashed_password = hashPassword(password);

        $.ajax({
            type: "post",
            url: "{!! route('user.store') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'name' : nama,
                'email' : email,
                'password' : hashed_password,
                'id_role' : role
            },
            dataType: "array",
            success: function (response) {
                let title = "";
                let text = "";
                let type_swal = "";

                if(response == 'success')
                {
                    title = "Berhasil!";
                    text = "User berhasil ditambah";
                    type_swal = "success";
                }
                else
                {
                    title = "Gagal!";
                    text = response;
                    type_swal = "error";
                }

                Swal.fire({
                    title: title,
                    text: text,
                    type: type_swal,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            }
        });
     }*/
</script>
@endsection