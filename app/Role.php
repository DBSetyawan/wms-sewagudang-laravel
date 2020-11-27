<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "tbl_role";
    protected $primaryKey = "id_role";

    public function users()
    {
        return $this->hasMany(User::class, 'id');
    }

    public function hakAksesGudang()
    {
        return $this->belongsToMany(Gudang::class, 'tbl_akses_gudang', 'id_role', 'id_gudang');
    }

    public function hakAksesProject()
    {
        return $this->belongsToMany(Project::class, 'tbl_akses_project', 'id_role', 'id_project');
    }

    public function hakAksesModul()
    {
        return $this->belongsToMany(Modul::class, 'tbl_akses_modul', 'id_role', 'id_modul');
    }
}
