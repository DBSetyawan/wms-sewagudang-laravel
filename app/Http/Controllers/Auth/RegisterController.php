<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UsersController;
use App\Providers\RouteServiceProvider;
use App\Mail\VerifikasiPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_role' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                // 'password' => Hash::make($data['password']),
                'id_role' => $data['id_role']
            ]);

            $last_inserted_user = User::orderBy('id', 'desc')->first();
            $role = DB::table('tbl_role')->where('id_role', $last_inserted_user->id_role)->get();
            $list_project_has_gudang = json_decode($data['list_project_has_gudang'], true);
            foreach ($list_project_has_gudang as $gudang) {
                DB::table('tbl_hak_akses')
                    ->insert([
                        'id_user' => $last_inserted_user->id,
                        'id_project_has_gudang' => $gudang
                    ]);
            }

            $new_request = new Request(['action' => "tambah_user", 'nama_user' => $last_inserted_user->name, 'nama_role' => $role[0]->nama_role]);
            app(LogController::class)->log($new_request);
            return $user;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function showRegistrationForm()
    {
        return view('tambah_user');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('success', 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        try {
            // Mail::to($user->email)->send(new VerifikasiPassword);
            //BUAT TOKEN 
            $token = hash_hmac('sha256', str_shuffle($user->email), config('app.key'));

            $action = "set_password";
            //SIMPAN TOKEN DI DB
            DB::table('users')
                ->where('email', $user->email)
                ->update([
                    'set_password_token' => $token,
                    'expired_at_set_password_token' => Carbon::now()->addDays(3)
                ]);

            Mail::send('emailTemplate', compact(['token', 'action']), function ($message) use ($user) {
                $message->from('admin@sewagudang.id', 'admin');
                $message->to($user->email, $user->name);
                $message->subject('Verifikasi Password Sewagudang.id');
            });
            // $new_request = new Request(['user' => $user]);
            // app(UsersController::class)->sendEmailSetPassword($new_request);
            return json_encode('success');
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
