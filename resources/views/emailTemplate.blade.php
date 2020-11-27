@if ($action == "set_password")
<p>Klik link di bawah ini untuk membuat password anda</p>
<a href="{{route('user.verifikasi',['token'=>$token])}}">Buat Password</a>
@else
<p>Klik link di bawah ini untuk mengubah password anda</p>
<a href="{{route('user.verifikasiTokenResetPassword',['token'=>$token])}}">Reset Password</a>
@endif