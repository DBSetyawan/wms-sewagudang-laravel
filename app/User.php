<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = "id";
    protected $fillable = [
        'name', 'email', 'password', 'id_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function hakAkses()
    {
        return $this->belongsToMany(ProjectHasGudang::class, 'tbl_hak_akses', 'id_user', 'id_project_has_gudang');
    }

    public function modulAkses()
    {
        return $this->belongsToMany(Modul::class, 'tbl_akses_gudang', 'id_role', 'id_modul')->with(Role::class);
    }
}
