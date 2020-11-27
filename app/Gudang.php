<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = "tbl_gudang";
    protected $primaryKey = "id_gudang";
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tbl_akses_gudang', 'id_role', 'id_role');
    }
}
