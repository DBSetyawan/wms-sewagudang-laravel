<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectHasGudang extends Model
{
    protected $table = "tbl_project_has_gudang";
    protected $primaryKey = "id_project_has_gudang";

    public function gudang()
    {
        return $this->belongsToMany(Gudang::class, 'id_gudang', 'id_gudang');
    }
}
