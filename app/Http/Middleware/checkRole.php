<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() == false) {
            return Redirect::route('login');
        } else {
            if (Auth::user()->role->nama_role != "admin" && Auth::user()->role->nama_role != "Admin") {
                $list_project_and_gudang = DB::table('tbl_gudang as g')
                    ->join('tbl_project_has_gudang as pg', 'pg.id_gudang', '=', 'g.id_gudang')
                    ->join('tbl_project as p', 'p.id_project', '=', 'pg.id_project')
                    ->join('tbl_hak_akses as ha', 'ha.id_project_has_gudang', '=', 'pg.id_project_has_gudang')
                    ->where('ha.id_user', Auth::user()->id)
                    ->get();

                if (count($list_project_and_gudang) > 0) {
                    $kodeproject = $list_project_and_gudang[0]->kode_project;
                    $kodegudang = $list_project_and_gudang[0]->kode_gudang;

                    return Redirect::route('inventory.index', ['kodeproject' => $kodeproject, 'kodegudang' => $kodegudang]);
                } else {
                    return Redirect::route("utility.landingpagenohakaksesproject");
                }
            }
            return $next($request);
        }
    }
}
