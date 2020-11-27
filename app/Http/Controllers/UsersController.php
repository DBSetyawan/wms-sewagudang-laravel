<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Mail\VerifikasiPassword;
use App\Project;
use App\User;
use Carbon\Carbon;
// use Illuminate\Http\Client\Request as Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;


class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['loginMobile', 'verifikasiTokenSetPassword', 'setPasswordUser', 'showResetPasswordForm', 'sendEmailResetPassword', 'resetPassword', 'verifikasiTokenResetPassword']]);
    }

    public function verifikasiTokenSetPassword($token)
    {
        try {
            $user = DB::table('users')
                ->where('set_password_token', $token)
                ->get();

            // echo () ? "valid" : "invalid";
            if (count($user) > 0 && strtotime($user[0]->expired_at_set_password_token) > strtotime(date('Y-m-d H:i:s'))) {
                return view('formSetPassword', compact(['user', 'token']));
            } else {
                if (count($user) != 0) {
                    DB::table('users')
                        ->where('email', $user[0]->email)
                        ->delete();
                }
                return view('error404');
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function setPasswordUser(Request $request)
    {
        try {
            request()->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ]);

            DB::table('users')
                ->where('email', $request->email)
                ->update([
                    'password' => Hash::make($request->password),
                    'set_password_token' => "",
                    'expired_at_set_password_token' => null
                ]);

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();

        return Redirect::route('login');
    }

    public function logoutMobile(Request $request)
    {
        try {
            DB::table('oauth_access_tokens')
                ->where('user_id', $request->id_user)
                ->delete();

            return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function loginMobile(Request $request)
    {

        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            DB::table('oauth_access_tokens')
                ->where('user_id', $user->id)
                ->delete();
            return response()->json(['user' => $user, 'access_token' => $user->createToken('sewagudangid')->accessToken]);
        } else {
            return response()->json(['user' => 'Email dan password salah!', 'access_token' => ""]);
        }
    }

    public function showResetPasswordForm()
    {
        return view('email_reset');
    }

    public function resendLinkVerifikasiEmail(Request $request)
    {
        try {
            $user = DB::table('users')
                ->where('id', $request->id_user)
                ->get();

            $action = "";
            $subject = "";
            if ($user[0]->password != "" && $user[0]->set_password_token != "") {
                $action = "reset_password";
                $subject = 'Ubah Password Sewagudang.id';
            } else if ($user[0]->password == "" && $user[0]->set_password_token != "") {
                $action = "set_password";
                $subject = 'Set Password Sewagudang.id';
            }
            $token = $user[0]->set_password_token;

            if ($action != "") {
                Mail::send('emailTemplate', compact(['token', 'action']), function ($message) use ($user) {
                    $message->from('admin@sewagudang.id', 'admin');
                    $message->to($user[0]->email, $user[0]->email);
                    $message->subject('Ubah Password Sewagudang.id');
                });
            }

            return response()->json("success");
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function sendEmailResetPassword(Request $request)
    {
        try {
            $user = DB::table('users')
                ->where('email', $request->email)
                ->where("password", "<>", '')
                ->get();

            if (count($user) > 0) {
                //BUAT TOKEN 
                $token = hash_hmac('sha256', str_shuffle($request->email), config('app.key'));

                //SIMPAN TOKEN DI DB
                DB::table('users')
                    ->where('email', $request->email)
                    ->update([
                        'set_password_token' => $token,
                        'expired_at_set_password_token' => Carbon::now()->addDays(3)
                    ]);
                $action = "reset_password";

                Mail::send('emailTemplate', compact(['token', 'action']), function ($message) use ($request) {
                    $message->from('admin@sewagudang.id', 'admin');
                    $message->to($request->email, $request->email);
                    $message->subject('Ubah Password Sewagudang.id');
                });
                return json_encode("success");
            } else {
                return json_encode('Email tidak terdaftar di sistem sewagudang');
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function verifikasiTokenResetPassword($token)
    {
        try {
            $user_request = DB::table('users')
                ->where('set_password_token', $token)
                ->whereNotNull('password')
                ->where("password", "<>", '')
                ->get();

            if (count($user_request) > 0) {
                return view('reset', compact('user_request'));
            } else {
                return view('error404');
            }
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function resetPassword(Request $request, $token)
    {
        try {
            request()->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ]);

            DB::table('users')
                ->where('set_password_token', $token)
                ->update([
                    'set_password_token' => "",
                    'expired_at_set_password_token' => null,
                    'password' => Hash::make($request->password)
                ]);

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function getAllUser()
    {
        try {
            $list_user = DB::table('users as u')
                ->join('tbl_role as r', 'u.id_role', '=', 'r.id_role')
                ->get();

            return json_encode($list_user);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    public function emailExistChecker(Request $request)
    {
        try {
            $email_exist = DB::table('users')
                ->where('email', $request->email)
                ->get();

            $is_exist = false;
            if (count($email_exist) > 0) {
                $is_exist = true;
                return json_encode($is_exist);
            }

            return json_encode($is_exist);
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $list_user = DB::table('users as u')
                ->join('tbl_role as r', 'u.id_role', '=', 'r.id_role')
                ->get();

            return view('daftar_user', compact('list_user'));
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_project = Project::all();
        $list_role = json_decode(app(RolesController::class)->getAllRole());
        return view('tambah_user', compact(['list_project', 'list_role']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = $request->validate([
                'name' => 'required|min:1',
                'email' => 'required',
                'password' => 'required|min:6',
                'id_role' => 'required'
            ]);

            User::create($validator);

            return json_encode('success');
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->get();
            $list_project_selected = array();

            $list_project = DB::table('tbl_project')->get();
            $list_project_has_gudang_selected = DB::table('users as u')
                ->join('tbl_hak_akses as ha', 'u.id', '=', 'ha.id_user')
                ->join('tbl_project_has_gudang as pg', 'pg.id_project_has_gudang', '=', 'ha.id_project_has_gudang')
                ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                ->join('tbl_gudang as g', 'g.id_gudang', '=', 'pg.id_gudang')
                ->where("u.id", $user[0]->id)
                ->get();

            $i = 0;
            $count = count($list_project_has_gudang_selected);
            for ($i; $i < $count; $i++) {
                array_push($list_project_selected, $list_project_has_gudang_selected[$i]->id_project);
            }
            $list_project_has_gudang = DB::table('tbl_project as p')
                ->join('tbl_project_has_gudang as gp', 'p.id_project', '=', 'gp.id_Project')
                ->join('tbl_gudang as g', 'gp.id_gudang', '=', 'g.id_gudang')
                ->whereIn('p.id_project', $list_project_selected)
                ->get();

            $list_gudang = DB::table('tbl_gudang')->get();

            $list_role = json_decode(app(RolesController::class)->getAllRole());
            return view('edit_user', compact(['list_project', 'list_project_has_gudang_selected', 'list_project_has_gudang', 'user', 'list_role']));
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'id_role' => $request->id_role
                ]);

            DB::table('tbl_hak_akses')->where('id_user', $id)->delete();

            $list_project_has_gudang = json_decode($request->list_project_has_gudang, true);
            foreach ($list_project_has_gudang as $gudang) {
                DB::table('tbl_hak_akses')
                    ->insert([
                        'id_user' => $id,
                        'id_project_has_gudang' => $gudang
                    ]);
            }

            $new_request = new Request([
                'action' => "edit_user", 'nama_user' => $request->name
            ]);
            app(LogController::class)->log($new_request);
            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = DB::table('users')
                ->where('id', '=', $id)
                ->get();

            DB::table('users')
                ->where('id', '=', $id)
                ->delete();

            $new_request = new Request([
                'action' => "hapus_user", 'nama_user' => $user[0]->name
            ]);
            app(LogController::class)->log($new_request);

            return json_encode("success");
        } catch (\Throwable $th) {
            return json_encode($th->getMessage());
        }
    }
}
