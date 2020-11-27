<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = "tbl_modul";
    protected $primaryKey = "id_modul";
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tbl_akses_gudang', 'id_modul', 'id_modul');
    }
}
