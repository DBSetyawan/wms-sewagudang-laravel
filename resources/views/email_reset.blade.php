@extends('layout.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form class="send_email">
                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                    autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-1">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" onclick="sendEmail()">
                                    {{ __('Send Verification Email') }}
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{!! route('project.index') !!}">Sudah punya akun? Klik disini.</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script_document_ready')
<script>
    $(".send_email").submit(function (e) { 
        e.preventDefault();
        
    });

    function sendEmail() { 
        $(".background-spinner").css("display", 'inline');
        $(".text-center").css("display",'inherit');
        $.ajax({
            type: "post",
            url: "{!! route('user.sendemail') !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'email' : $("#email").val()
            },
            dataType: "json",
            success: function (response) {
                $(".background-spinner").css("display", 'none');
                $(".text-center").css("display",'none');
                if(response=="success")
                {
                    Swal.fire({
                        'title' : "Berhasil",
                        'text' : "Link reset password telah dikirim. Silahkan cek email anda!",
                        'type' : "success",
                    });
                }
                else
                {
                    Swal.fire({
                        'title' : "Gagal!",
                        'text' : response,
                        'type' : "error",
                    });
                }
            }
        });
     }

     $(document).ready(function () {
        if("{!! $errors->any()!!}")
        {
            triggeredToast('{!! json_encode($errors->all()) !!}', 'error');
        }
     });
</script>
@endsection