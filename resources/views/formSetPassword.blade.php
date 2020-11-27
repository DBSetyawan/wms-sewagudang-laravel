@extends('layout.master')

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
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control name " name="name"
                                    value="{{ $user[0]->name }}" disabled>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control email " name="email"
                                    value="{{ $user[0]->email}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control password @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password">

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
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-primary btn-register" onclick="tambahUser()">
                                    {{ __('Buat Password') }}
                                </button>
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
    function tambahUser() {
        let email = $(".email").val();
        let password = $(".password").val();
        let password_confirmation = $(".password_confirmation").val();
        if(email != "" && password != "" && password_confirmation != "")
            {
                $.ajax({
                    type: "post",
                    url: "{!! route('user.setpassword',['token'=>$token]) !!}",
                    headers : {
                        "X-CSRF-TOKEN" : "{{ csrf_token() }}"
                    },
                    data: {
                        'email' : email,
                        'password' : password,
                        'password_confirmation' : password_confirmation,
                    },
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        // let result = JSON.parse(response);
                        let title = "";
                        let text = "";
                        let type_swal = "";

                        if(response == 'success')
                        {
                            window.location.href="{{ route('login') }}";
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