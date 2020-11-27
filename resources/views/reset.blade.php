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
       <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

       <div class="col-md-6">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
         value="{{ $user_request[0]->email }}" required autocomplete="email" disabled>

        @error('email')
        <span class="invalid-feedback" role="alert">
         <strong>{{ $message }}</strong>
        </span>
        @enderror
       </div>
      </div>

      <div class="form-group row">
       <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

       <div class="col-md-6">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
         name="password" autocomplete="new-password" autofocus>
       </div>
      </div>

      <div class="form-group row">
       <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

       <div class="col-md-6">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
         autocomplete="new-password">
       </div>
      </div>

      <div class="form-group row mb-0">
       <div class="col-md-6 offset-md-4">
        <button type="submit" class="btn btn-primary" onclick="resetPassword()">
         {{ __('Reset Password') }}
        </button>
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

    function resetPassword() {
     let password = $("#password").val();
     let password_confirmation = $("#password-confirm").val();
     if(password == password_confirmation && password.trim() != "" && password_confirmation.trim() != "" && password.length >= 8 && password_confirmation.length >= 8)
     {
      $.ajax({
            type: "post",
            url: "{!! route('user.reset', ['token' => $user_request[0]->set_password_token]) !!}",
            headers : {
                "X-CSRF-TOKEN" : "{{ csrf_token() }}"
            },
            data: {
                'password' : password,
                'password_confirmation' : password_confirmation
            },
            dataType: "json",
            success: function (response) {
                if(response=="success")
                {
                   window.location.href = "{!! route('project.index') !!}";
                }
            }
        });
     }
     else if(password.length <8)
     {
      Swal.fire({
       title : "Gagal!",
       text : "Password minimal 8 karakter",
       type : "error"
      })
     }
     else
     {
      Swal.fire({
       title : "Gagal!",
       text : "confirmation password tidak match dengan password",
       type : "error"
      })
     }
    }
</script>
@endsection